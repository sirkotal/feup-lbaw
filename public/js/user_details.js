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

function deleteUser(button){
    const username = document.querySelector('div #Username').textContent
    button.addEventListener('click', () => {
        sendAjaxRequest('post', '/profile/admin/delete_user', {username: username}, () => window.location.href = '/profile/admin/user');
    });
}

function blockUser(button){
    const username = document.querySelector('div #Username').textContent;
    button.addEventListener('click', () => {
        sendAjaxRequest('post', '/profile/admin/block_user', {username: username, action: 'Blocking'}, () => {location.reload();})
    });
}

function unblockUser(button){
    const username = document.querySelector('div #Username').textContent
    button.addEventListener('click', () => {
        sendAjaxRequest('post', '/profile/admin/block_user', {username: username, action: 'Unblocking'}, () => {location.reload();});
    });
}

const delete_btn = document.querySelector('.delete');
const block_btn = document.querySelector('.block');
if (block_btn == null){
    const unblock_btn = document.querySelector('.unblock');
    unblockUser(unblock_btn);
}
else{
    blockUser(block_btn);
}
deleteUser(delete_btn);


