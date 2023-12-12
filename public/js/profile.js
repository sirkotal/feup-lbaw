function showProducts(sections){
    sections.forEach((section) => {
        const button = section.querySelector('.showProducts');
        const list = section.querySelector('.list-products');
        const exit = section.querySelector('.list-products button')
        button.addEventListener('click', () => {
            list.classList.add('show');
            button.classList.add('hide');
        });
        exit.addEventListener('click', () => {
            list.classList.remove('show');
            button.classList.remove('hide');
        });
    });
}

function deleteUser(button){
    username = document.querySelector('div #Username').textContent
    button.addEventListener('click', () => {
        sendAjaxRequest('post', '/profile/admin/delete_user', {username: username});
        sendAjaxRequest('get','/logout', {}, () => {location.reload();});
    });
}

const products = document.querySelectorAll('td div.products');
const delete_button = document.querySelector('button.delete');
showProducts(products);
if (delete_button !== null)
    deleteUser(delete_button);

