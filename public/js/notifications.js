function notifications() { 
    var notificationspanel = document.getElementById("notifications");
    var dot = document.getElementById("dot");

    if (notificationspanel.style.display == "flex") {
        notificationspanel.style.display = "none";
    } else {
        if(dot) {dot.style.display = "none";}
        notificationspanel.style.display = "flex";
    }
}

function notification(notificationId) {
    sendAjaxRequest('post', `/notifications_read/${notificationId}`,{}, notificationHandler);
}

function notificationHandler(){
    let response = JSON.parse(this.responseText);
    let aux = document.getElementById(response.notification_id);
    if(response.success == true){
        aux.style.backgroundColor = "#cacaca";
    }
}

function deleteNotification(notificationId){
    sendAjaxRequest('post', `/notification/delete/${notificationId}`,{}, deleteNotificationHandler);
}

function deleteNotificationHandler(){
    let {success, notification_id} = JSON.parse(this.responseText);
    if(success) { document.getElementById(notification_id).style.display = 'none';}
    if(document.getElementById('notifications').innerText === "") { location.reload();}
}

document.addEventListener('DOMContentLoaded', function() {
    const userId = document.querySelector('a#notifications_icon').getAttribute('data-userid');
    const dotElement = document.getElementById("dot");

    if (dotElement) {
        const pusher = new Pusher('2a57bfc1a4ab5818050a', {
            cluster: 'eu',
            encrypted: true
        });

        const channel = pusher.subscribe(`user.${userId}`);

        channel.bind('notification', function() {
            dotElement.style.display = 'inline-block';
            addNotificationHandler();
        });
    } else {
        console.error("Element with ID 'dot' not found.");
    }
});

function addNotificationHandler(){
    fetch('/last_notification')
    .then(response => response.json())
    .then(data => {
        var newContent = '';
        let notifications_content= document.getElementById('notifications');
        data.notifications.forEach(function(response) {
            console.log('here');
            if(response.success){
                if(response.type == 'liked_review'){
                    newContent = `
                    <div onmouseover="notification(${response.notification_id})" id="${response.notification_id}" class="notification" style="${ response.is_read ? 'background-color: #cacaca' : '' }">
                    <div class="notification-content">
                    <a href="product/${response.product_id}"><img ${response.product_path ? `src="storage/products/${response.product_id}_1.png"` : `src="images/products/default.png"`} alt="" class="notification-image"></a>
                            <div class="notification-message">
                                Someone liked your review on <span class="product-name">${response.product_name}</span>!
                            </div>
                            </div>
                            <button onclick="deleteNotification(${response.notification_id})" class="notification-delete"><i class="fa fa-trash"></i></button>
                    </div>`;
                }
                else if(response.type == 'change_in_price'){
                    newContent = `
                    <div onmouseover="notification(${response.notification_id})" id="${response.notification_id}" class="notification" style="${ response.is_read ? 'background-color: #cacaca' : '' }">
                    <div class="notification-content">
                        <a href="product/${response.product_id}"><img ${response.product_path ? `src="storage/products/${response.product_id}_1.png"` : `src="images/products/default.png"`} alt="" class="notification-image"></a>
                        <div>${parseFloat(response.price - response.price * response.discount / 100).toFixed(2)}</span>€/un <span class="notification-product-price">${response.price}€/un </span></div>
                            <div class="notification-message">
                                <span class="product-name">${response.product_name}</span> from your wishlist is now on sale!
                            </div>
                            </div>
                            <button onclick="deleteNotification(${response.notification_id})" class="notification-delete"><i class="fa fa-trash"></i></button>
                    </div>`;
                }
                else if(response.type == 'item_availability'){
                    newContent = `
                    <div onmouseover="notification(${response.notification_id})" id="${response.notification_id}" class="notification" style="${ response.is_read ? 'background-color: #cacaca' : '' }">
                    <div class="notification-content">
                    <a href="product/${response.product_id}"><img ${response.product_path ? `src="storage/products/${response.product_id}_1.png"` : `src="images/products/default.png"`} alt="" class="notification-image"></a>
                        <div class="notification-message">
                            ${ response.text == 'LAST ITEM AVAILABLE' ?
                            `There's only one <span class="product-name">${response.product_name}</span> left in stock!` :
                            `<span class="product-name">{{ $notif->product->product_name }}</span> from your wishlist is now available!`
                            }
                        </div>
                        </div>
                            <button onclick="deleteNotification(${response.notification_id})" class="notification-delete"><i class="fa fa-trash"></i></button>
                    </div>`;
                }
            }
            notifications_content.innerHTML = newContent + notifications_content.innerHTML;
        })    
    })
    .catch(error => {
        console.error('Error fetching notifications:', error);
    });
    
}