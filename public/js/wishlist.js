function add_to_wishlist(productId) {
    sendAjaxRequest('post', `/wishlist/add/${productId}`, {}, add_to_wishlist_handler);
}

function remove_from_wishlist(productId) {
    sendAjaxRequest('post', `/wishlist/remove/${productId}`, {}, remove_from_wishlist_handler);
}

function add_to_wishlist_handler() {
    if(!isLoggedIn){
        Swal.fire({
            icon: "error",
            title: "You have to be logged in to add a product to your wishlist!",
            showCancelButton: true,
            confirmButtonColor: "#00754D",
            cancelButtonColor: "#d33",
            confirmButtonText: "<a href='login' style='text-decoration: none; color: white;'> Login/Register </a>"
        });
     }
    const {success, product_id} = JSON.parse(this.responseText);
    if(success){
        document.getElementById(`wishlist_remove_${product_id}`).style.display = 'block';
        document.getElementById(`wishlist_add_${product_id}`).style.display = 'none';
    }
}

function remove_from_wishlist_handler() {
    location.reload();
    const {success, product_id} = JSON.parse(this.responseText);
    if(success){
        if(window.location.pathname == "/wishlist"){
            document.getElementById(`wishlist-item-${product_id}`).style.display = 'none';
        } else {
            document.getElementById(`wishlist_remove_${product_id}`).style.display = 'none';
            document.getElementById(`wishlist_add_${product_id}`).style.display = 'block';
        }
    }
}
