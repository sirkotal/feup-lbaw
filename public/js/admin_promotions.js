function encodeForAjax(data) {
    if (data == null) return null;
    return Object.keys(data).map(function(k){
      return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
    }).join('&');
  }
  
function sendAjaxRequest(method, url, data, handler) {
    let request = new XMLHttpRequest();

    request.open(method, url, true);
    request.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    request.addEventListener('load', handler);
    request.send(encodeForAjax(data));
}

function addPromotion(section){
    const product = section.querySelector('td.products');
    const id = section.querySelector('td#id').textContent;
    const id_pop = 'promotion_' + id;
    const popup = document.querySelector('div#'+id_pop);
    const table = document.querySelector('table#admin-promotions');
    const button = section.querySelector('button.add_promotion')
    product.addEventListener('click', () => {
        if (popup.style.display != 'block'){
            popup.style.display = 'block';
            popup.style.left = product.offsetLeft + (product.offsetWidth / 10) + table.offsetLeft + 'px';
            popup.style.top = section.offsetTop + (section.offsetHeight / 5)*4 + table.offsetTop + 'px';   
        }
        else
            popup.style.display = 'none';          
    })
    button.addEventListener('click', () => {
        const name = section.querySelector('td input[name="name"]').value;
        const start_date = section.querySelector('td input[name="start_date"]').value;
        const end_date = section.querySelector('td input[name="end_date"]').value;
        const percentage = section.querySelector('td input[name="percentage"]').value;
        const products = JSON.stringify(Array.from(document.querySelectorAll('div#'+ id_pop + ' p'), x => x.textContent));
        console.log('here')
        sendAjaxRequest('post', '/admin/add_new_promotion', {name: name, start_date: start_date, end_date: end_date, percentage: percentage, products: products, id: id}, () => {location.reload();});
    });
}

function editPromotion(buttons){
    buttons.forEach((button) => {
        button.addEventListener('click', () => {
            const row = button.closest('.promotionInfo');
            const name = row.querySelector('td input[name="name"]').value;
            const start_date = row.querySelector('td input[name="start_date"]').value;
            const end_date = row.querySelector('td input[name="end_date"]').value;
            const percentage = row.querySelector('td input[name="percentage"]').value;
            const id = row.querySelector('td#id').textContent;
            const id_pop = 'promotion_' + id;
            const products = JSON.stringify(Array.from(document.querySelectorAll('div#'+ id_pop + ' p'), x => x.textContent));
            console.log(id_pop)
            sendAjaxRequest('post', '/profile/admin/edit_promotion', {name: name, start_date: start_date, end_date: end_date, percentage: percentage, products: products, id: id}, () => {location.reload();});
        });
    });
}

function deletePromotion(buttons){
    buttons.forEach((button) => {
        const row = button.closest('.promotionInfo');
        const id = row.querySelector('td#id').textContent;
        button.addEventListener('click', () => {
            sendAjaxRequest('post', '/admin/delete_promotion/' + id, {id: id}, () => {location.reload();});
        });
    });
}

function beginEditPromotions(show_sections, edit_sections){
    for (let i = 0; i < show_sections.length; i++) {
        const show_section = show_sections[i];
        const edit_section = edit_sections[i];
        const edit_button = show_section.querySelector('button.edit_promotion');
        const id = 'promotion_' + edit_section.querySelector('td#id').textContent;
        const products = edit_section.querySelector('td.products');
        const popup = document.querySelector('div#'+id);
        const table = document.querySelector('table#admin-promotions');
        const buttons = popup.querySelectorAll('button');
        const select = popup.querySelector('select[name="product"]')
        edit_button.addEventListener('click', () => {
            show_section.classList.add('hidden');
            edit_section.classList.remove('hidden');
            popup.style.display = 'block';
            popup.style.left = products.offsetLeft + (products.offsetWidth / 10) + table.offsetLeft + 'px';
            popup.style.top = edit_section.offsetTop + (edit_section.offsetHeight / 5)*4 + table.offsetTop + 'px';  
            buttons.forEach(button => {
                button.classList.remove('hidden');
            });
            select.classList.remove('hidden');
        });
        const save_button = edit_section.querySelector('button.save_promotion');
        save_button.addEventListener('click', () => {
            show_section.classList.remove('hidden');
            edit_section.classList.add('hidden');
            popup.style.display = 'none'; 
            buttons.forEach(button => {
                button.classList.add('hidden');
            });
            select.classList.add('hidden');
        });
        products.addEventListener('click', () => {
            if (popup.style.display == 'none'){
                popup.style.display = 'block';
                popup.style.left = products.offsetLeft + (products.offsetWidth / 10) + table.offsetLeft + 'px';
                popup.style.top = edit_section.offsetTop + (edit_section.offsetHeight / 5)*4 + table.offsetTop + 'px'; 
            }
            else
                popup.style.display = 'none';                 
        })
    }
}

function showProducts(rows) {
    rows.forEach(row => {
        const products = row.querySelector('td.products');
        const id = 'promotion_' + row.querySelector('td#id').textContent;
        const popup = document.querySelector('div#'+id);
        const table = document.querySelector('table#admin-promotions');
        products.addEventListener('mouseover', () => {
            popup.style.display = 'block';
            popup.style.left = products.offsetLeft + (products.offsetWidth / 10) + table.offsetLeft + 'px';
            popup.style.top = row.offsetTop + (row.offsetHeight / 5)*4 + table.offsetTop + 'px';            
        })
        products.addEventListener('mouseout', () => {
            popup.style.display = 'none';           
        })
    });
}

function editProducts(popups) {
    popups.forEach(popup => {
        const remove_sections = popup.querySelectorAll('.toast div.product_bundle');
        const add_sections = popup.querySelectorAll('.toast div.add_product');
        const categories_list = popup.querySelector('div.products_list');
        const select = popup.querySelector('select[name="product"]');
        remove_sections.forEach(section => {
            const button = section.querySelector('button');
            button.addEventListener('click', () => {
                const option = document.createElement('option');
                option.textContent = section.querySelector('p').textContent
                select.appendChild(option);
                section.remove();
            })
        });
        add_sections.forEach(section => {
            const button = section.querySelector('button');
            button.addEventListener('click', () => {
                const option = select.options[select.selectedIndex];
                const category = option.textContent;
                const div = document.createElement('div');
                div.classList.add("product_bundle", "d-flex", "justify-content-between");
                const paragraph = document.createElement('p');
                paragraph.textContent = category;
                const image = document.createElement('i');
                image.classList.add('bi', 'bi-trash3-fill');
                const button = document.createElement('button');
                button.classList.add('align-middle');
                button.appendChild(image);
                button.addEventListener('click', () => {
                    select.appendChild(option);
                    div.remove();
                })
                div.appendChild(paragraph);
                div.appendChild(button); 
                categories_list.appendChild(div); 
                option.remove();
            });            
        });
    });
}

const editPromotion_buttons = document.querySelectorAll('.save_promotion');
const deletePromotion_buttons = document.querySelectorAll('.delete_promotion');
const viewPromotions = document.querySelectorAll('tr.showPromotionInfo');
const editPromotions = document.querySelectorAll('tr.editPromotionInfo');
const addSection = document.querySelector('tr#addPromotionInfo');
const popups = document.querySelectorAll('div.toast');

addPromotion(addSection);
editPromotion(editPromotion_buttons);
deletePromotion(deletePromotion_buttons);
beginEditPromotions(viewPromotions, editPromotions);
showProducts(viewPromotions);
editProducts(popups);

