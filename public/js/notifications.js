
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
}