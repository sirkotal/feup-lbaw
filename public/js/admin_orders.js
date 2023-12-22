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

function beginEditOrders(show_sections, edit_sections){
    for (let i = 0; i < show_sections.length; i++) {
        const show_section = show_sections[i];
        const edit_section = edit_sections[i];
        const edit_button = show_section.querySelector('button.edit_order');
        edit_button.addEventListener('click', () => {
            show_section.classList.add('hidden');
            edit_section.classList.remove('hidden');
        });
        const save_button = edit_section.querySelector('button.save_order');
        save_button.addEventListener('click', () => {
            show_section.classList.remove('hidden');
            edit_section.classList.add('hidden');
        });
    }
}

function editOrders(buttons){
    buttons.forEach((button) => {
        button.addEventListener('click', () => {
            const row = button.closest('.orderInfo');
            const id = row.querySelector('td#id').textContent;
            const select = row.querySelector('select[name="orders"]');
            const orderStatus = select.options[select.selectedIndex].textContent;
            sendAjaxRequest('post', '/profile/admin/edit_order', {id: id, order_status: orderStatus}, () => {location.reload();});
        });
    });
}

const editOrder_buttons = document.querySelectorAll('.save_order');
const viewOrders = document.querySelectorAll('tr#showOrderInfo');
const editOrder = document.querySelectorAll('tr#editOrderInfo');


editOrders(editOrder_buttons);
beginEditOrders(viewOrders, editOrder);