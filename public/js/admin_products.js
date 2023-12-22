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

function addProduct(section){
    const category = section.querySelector('td.category');
    const id = section.querySelector('td#id').textContent;
    const id_pop = 'product_' + id;
    const popup = document.querySelector('div#'+id_pop);
    const table = document.querySelector('table#admin-products');
    const button = section.querySelector('button.add_product')
    category.addEventListener('click', () => {
        if (popup.style.display != 'block'){
            popup.style.display = 'block';
            popup.style.left = category.offsetLeft + (category.offsetWidth / 10) + table.offsetLeft + 'px';
            popup.style.top = section.offsetTop + (section.offsetHeight / 5)*4 + table.offsetTop + 'px';   
        }
        else
            popup.style.display = 'none';          
    })
    button.addEventListener('click', () => {
        const product_name = section.querySelector('td input[name="product_name"]').value;
        const description = section.querySelector('td input[name="description"]').value;
        const extra_information = section.querySelector('td input[name="extra_information"]').value;
        const brand_name = section.querySelector('td select[name="brand_name"]').value;
        const categories = JSON.stringify(Array.from(document.querySelectorAll('div#'+ id_pop + ' p'), x => x.textContent));
        const price = section.querySelector('td input[name="price"]').value;
        const stock = section.querySelector('td input[name="stock"]').value;
        sendAjaxRequest('post', '/admin/add_new_product', {product_name: product_name, description: description, extra_information: extra_information, brand_name: brand_name, categories: categories, price: price, stock: stock, id: id}, () => {location.reload();});
    });
}

function editProduct(buttons){
    buttons.forEach((button) => {
        button.addEventListener('click', () => {
            const row = button.closest('.productInfo');
            const product_name = row.querySelector('td input[name="product_name"]').value;
            const description = row.querySelector('td input[name="description"]').value;
            const extra_information = row.querySelector('td input[name="extra_information"]').value;
            const brand_name = row.querySelector('td select[name="brand_name"]').value;
            const id = row.querySelector('td#id').textContent;
            const id_pop = 'product_' + id;
            const categories = JSON.stringify(Array.from(document.querySelectorAll('div#'+ id_pop + ' p.categories_names'), x => x.textContent));
            
            const price = row.querySelector('td input[name="price"]').value;
            const stock = row.querySelector('td input[name="stock"]').value;
            sendAjaxRequest('post', '/admin/edit_product/' + id, {product_name: product_name, description: description, extra_information: extra_information, brand_name: brand_name, categories: categories, price: price, stock: stock, id: id}, () => {location.reload();});
        });
    });
}

function deleteProduct(buttons){
    buttons.forEach((button) => {
        const row = button.closest('.productInfo');
        const id = row.querySelector('td#id').textContent;
        button.addEventListener('click', () => {
            sendAjaxRequest('post', '/admin/delete_product/' + id, {id: id}, () => {location.reload();})
        });
    });
}

function beginEditProducts(show_sections, edit_sections){
    for (let i = 0; i < show_sections.length; i++) {
        const show_section = show_sections[i];
        const edit_section = edit_sections[i];
        const edit_button = show_section.querySelector('button.edit_product');
        const id =  edit_section.querySelector('td#id').textContent;
        const category = edit_section.querySelector('td.category');
        const popup = document.querySelector('div#product_'+id);
        const edit_photos = document.querySelector('div#photo_'+id);
        const photo = show_section.querySelector(':first-child');
        const photo_2 = edit_section.querySelector(':first-child');
        const table = document.querySelector('table#admin-products');
        const buttons = popup.querySelectorAll('button');
        const select = popup.querySelector('select[name="category"]')
        const alert_icon = popup.querySelector('#alert i');
        const alert = popup.querySelector('#alert p');
        edit_button.addEventListener('click', () => {
            if (alert != null){
                alert.classList.add('hidden');
                alert_icon.classList.add('hidden');
            }
            show_section.classList.add('hidden');
            edit_section.classList.remove('hidden');
            popup.style.display = 'block';
            popup.style.left = category.offsetLeft + (category.offsetWidth / 10) + table.offsetLeft + 'px';
            popup.style.top = edit_section.offsetTop + (edit_section.offsetHeight / 5)*4 + table.offsetTop + 'px';  
            buttons.forEach(button => {
                button.classList.remove('hidden');
            });
            select.classList.remove('hidden');
        });
        const save_button = edit_section.querySelector('button.save_edit');
        save_button.addEventListener('click', () => {
            show_section.classList.remove('hidden');
            edit_section.classList.add('hidden');
            popup.style.display = 'none'; 
            buttons.forEach(button => {
                button.classList.add('hidden');
            });
            select.classList.add('hidden');
        });
        category.addEventListener('click', () => {
            if (popup.style.display != 'block'){
                popup.style.display = 'block';
                popup.style.left = category.offsetLeft + (category.offsetWidth / 10) + table.offsetLeft + 'px';
                popup.style.top = edit_section.offsetTop + (edit_section.offsetHeight / 5)*4 + table.offsetTop + 'px'; 
            }
            else
                popup.style.display = 'none';                 
        })
        photo.addEventListener('click', () => {
            table.style.pointerEvents = 'none';
            table.style.filter = 'grayscale(1)';
            edit_photos.style.display = 'flex';
            edit_photos.style.justifyContent = 'center';
            edit_photos.style.alignItems = 'center';
            edit_photos.style.left = '25%'
            edit_photos.style.top = table.offsetTop + (table.offsetTop/2) + 'px'
        });
        photo_2.addEventListener('click', () => {
            table.style.pointerEvents = 'none';
            table.style.filter = 'grayscale(1)';
            edit_photos.style.display = 'flex';
            edit_photos.style.justifyContent = 'center';
            edit_photos.style.alignItems = 'center';
            edit_photos.style.left = '25%'
            edit_photos.style.top = table.offsetTop + (table.offsetTop/2) + 'px'
        });
    }
}

function showCategories(rows) {
    rows.forEach(row => {
        const category = row.querySelector('td.category');
        const id = 'product_' + row.querySelector('td#id').textContent;
        const popup = document.querySelector('div#'+id);
        const table = document.querySelector('table#admin-products');
        category.addEventListener('mouseover', () => {
            popup.style.display = 'block';
            popup.style.left = category.offsetLeft + (category.offsetWidth / 10) + table.offsetLeft + 'px';
            popup.style.top = row.offsetTop + (row.offsetHeight / 5)*4 + table.offsetTop + 'px';            
        })
        category.addEventListener('mouseout', () => {
            popup.style.display = 'none';           
        })
    });
}

function editCategories(popups) {
    popups.forEach(popup => {
        const remove_sections = popup.querySelectorAll('.toast div.category_bundle');
        const add_sections = popup.querySelectorAll('.toast div.add_category');
        const categories_list = popup.querySelector('div.categories_list');
        const select = popup.querySelector('select[name="category"]');
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
                div.classList.add("category_bundle", "d-flex", "justify-content-between");
                const paragraph = document.createElement('p');
                paragraph.classList.add('categories_names');
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

function editPhoto(photoSections){
    photoSections.forEach(section => {
        let photos = Array.from(section.querySelectorAll('img'));
        let number = photos.length;
        const alert = section.querySelector('p');
        const left_button = section.querySelector('i.bi-chevron-left');
        const right_button = section.querySelector('i.bi-chevron-right');
        const delete_button = section.querySelector('button.delete_image');
        const close_button = section.querySelector('button.close_edit');
        const product_id = section.getAttribute('data-id');
        const td_photo = document.querySelector('tr.showProductInfo td#id[data-id="' + product_id + '"]').closest('tr').querySelector('td:first-child');
        let photo = td_photo.querySelector('img');
        const td_photo2 = document.querySelector('tr.editProductInfo td#id[data-id="' + product_id + '"]').closest('tr').querySelector('td:first-child');
        let photo2 = td_photo2.querySelector('img');
        let counter = 0;
        let current = photos[counter];
        if (current != null){
            current.classList.remove('hidden');
        } 
        else{
            alert.classList.remove('hidden');
            delete_button.classList.add('hidden');
            right_button.classList.add('hidden');
            left_button.classList.add('hidden');
        }
        left_button.addEventListener('click', () => {
            counter-=1;
            if (counter < 0)
                counter = number-1;
            current.classList.add('hidden'); 
            current = photos[counter];
            current.classList.remove('hidden');
        })
        right_button.addEventListener('click', () => {
            counter+=1;
            if (counter >= number)
                counter = 0;
            current.classList.add('hidden'); 
            current = photos[counter];
            current.classList.remove('hidden'); 
        })
        delete_button.addEventListener('click', () => {
            sendAjaxRequest('post', '/admin/edit_product/delete_photo/' + product_id, {product_id: product_id, number: counter + 1}, 
            () => {
                counter-=1;
                number-=1; 
                if (counter < 0)
                    counter = 0;
                photos = photos.filter(photo => photo !== current);
                current.remove();
                if (number != 0){
                    current = photos[0];
                    current.classList.remove('hidden');
                    photo.remove();
                    photo2.remove();
                    photo = current.cloneNode(true);
                    photo2 = current.cloneNode(true);
                    td_photo.appendChild(photo);
                    td_photo2.appendChild(photo2);
                }
                else{
                    alert.classList.remove('hidden');
                    delete_button.classList.add('hidden');
                    right_button.classList.add('hidden');
                    left_button.classList.add('hidden');
                    photo.src = 'http://localhost:8000/storage/products/def.png'
                    photo2.src = 'http://localhost:8000/storage/products/def.png'
                }
            });
        })
        const table = document.querySelector('table#admin-products');
        close_button.addEventListener('click', () => {
            section.style.display = 'none';
            table.style.pointerEvents = 'auto';
            table.style.filter = 'grayscale(0)';
        })
    });
}

const editProduct_buttons = document.querySelectorAll('.save_edit');
const deleteProduct_buttons = document.querySelectorAll('.delete_product');
const viewproducts = document.querySelectorAll('tr.showProductInfo');
const editproducts = document.querySelectorAll('tr.editProductInfo');
const addSection = document.querySelector('tr#addProductInfo');
const popups = document.querySelectorAll('div.toast');
const photoSections = document.querySelectorAll('div.edit_photos');

addProduct(addSection);
editProduct(editProduct_buttons);
deleteProduct(deleteProduct_buttons);
beginEditProducts(viewproducts, editproducts);
showCategories(viewproducts);
editCategories(popups);
editPhoto(photoSections);