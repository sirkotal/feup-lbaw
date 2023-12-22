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


function redirect(button) {
    button.addEventListener('click', () => {
        window.location.href = '/user';
    });
}

function deleteUser(buttons){
    buttons.forEach((button) => {
        const row = button.closest('.userInfo');
        const username = row.querySelector('td#username').textContent;
        button.addEventListener('click', () => {
            sendAjaxRequest('post', '/profile/admin/delete_user', {username: username}, () => {location.reload();})
        });
    });
}

function blockUser(buttons){
    buttons.forEach((button) => {
        const row = button.closest('.userInfo');
        const username = row.querySelector('td#username').textContent;
        button.addEventListener('click', () => {
            sendAjaxRequest('post', '/profile/admin/block_user', {username: username, action: 'Blocking'}, () => {location.reload();})
        });
    });
}

function unblockUser(buttons){
    buttons.forEach((button) => {
        const row = button.closest('.userInfo');
        const username = row.querySelector('td#username').textContent;
        button.addEventListener('click', () => {
            sendAjaxRequest('post', '/profile/admin/block_user', {username: username, action: 'Unblocking'}, () => {location.reload();});
        });
    });
}

const delete_buttons = document.querySelectorAll('.delete');
const block_buttons = document.querySelectorAll('.block');
const unblock_buttons = document.querySelectorAll('.unblock');

deleteUser(delete_buttons);
blockUser(block_buttons);
unblockUser(unblock_buttons);









