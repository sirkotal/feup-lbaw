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

function deleteReport(buttons){
    buttons.forEach((button) => {
        const row = button.closest('.reportInfo');
        const user_id = row.querySelector('td#user_id').textContent;
        const review_id = row.querySelector('td#review_id').textContent;
        button.addEventListener('click', () => {
            sendAjaxRequest('post', '/admin/delete_report', {user_id: user_id, review_id:review_id}, () => {location.reload();})
        });
    });
}

function deleteReview(buttons){
    buttons.forEach((button) => {
        const row = button.closest('.reportInfo');
        const review_id = row.querySelector('td#review_id').textContent;
        button.addEventListener('click', () => {
            sendAjaxRequest('post', '/delete_review/' + review_id, {}, () => {location.reload();})
        });
    });
}

function showReviews(rows) {
    rows.forEach(row => {
        const review = row.querySelector('td.review');
        const id = 'review_' + row.querySelector('td#review_id').textContent + '_' + row.querySelector('td#user_id').textContent;
        const popup = document.querySelector('div#'+id);
        const table = document.querySelector('table#admin-reviews');
        review.addEventListener('mouseover', () => {
            popup.style.display = 'block';
            popup.style.left = review.offsetLeft + (review.offsetWidth / 10) + table.offsetLeft + 'px';
            popup.style.top = row.offsetTop + (row.offsetHeight / 5)*4 + table.offsetTop + 'px';            
        })
        review.addEventListener('mouseout', () => {
            popup.style.display = 'none';           
        })
    });
}


const delete_buttons = document.querySelectorAll('#admin-reviews .delete');
const erase_buttons = document.querySelectorAll('.erase');
const viewReports = document.querySelectorAll('tr.reportInfo');
deleteReport(delete_buttons);
deleteReview(erase_buttons);
showReviews(viewReports)