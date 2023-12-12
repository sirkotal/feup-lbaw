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

  function addToShoppingCart() {
    let addToCartButton = document.getElementById("add_to_cart_button");

    if (addToCartButton){
        addToCartButton.addEventListener("click", function() {
            let data = {
                'product_id': addToCartButton.getAttribute("product_id"),
            };
            if(!isLoggedIn){
                addToLocalStorage(data.product_id);
            } else {
                sendAjaxRequest('post', '/product/'+ data.product_id + '/add_to_shoppingcart?product_id=' + data.product_id,{}, addToShoppingCartHandler
            );
            }
        });
    }
}

function addToCart(productId) {
    if(!isLoggedIn){
        addToLocalStorage(productId);
    } else {
        sendAjaxRequest('post', `/product/${productId}/add_to_shoppingcart?product_id=${productId}`, {}, addHandler);
    }
    Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 1000
        }).fire({
    icon: "success",
    title: "Product added to cart successfully!"
    });
}

function addToShoppingCartIncreaseButton() {
    let increaseQuantityButton = document.getElementById("increase_quantity");
    if (increaseQuantityButton){
        increaseQuantityButton.addEventListener("click", function() {
            let data = {
                'product_id': increaseQuantityButton.getAttribute("product_id"),
            };
            if(!isLoggedIn) {
                addToLocalStorage(data.product_id);
            } else {
                sendAjaxRequest('post', '/product/'+ data.product_id + '/add_to_shoppingcart?product_id=' + data.product_id, {}, addToShoppingCartHandler);
            }
        });
    }
}

function removeFromShoppingCartDecreaseButton() {
    let decreaseQuantityButton = document.getElementById("decrease_quantity");

    if (decreaseQuantityButton){
        decreaseQuantityButton.addEventListener("click", function() {
        let data = {
            'product_id': decreaseQuantityButton.getAttribute("product_id"),
        };
        if(!isLoggedIn) {
            removeFromLocalStorage(data.product_id);
        } else {
            sendAjaxRequest('post', '/product/'+ data.product_id + '/remove_from_shoppingcart?product_id=' + data.product_id, {}, removeFromShoppingCartHandler);
        }
    });
    }
}

function addToShoppingCartHandler() {
    let response = JSON.parse(this.responseText);
    console.log(response);
    if (response.success) {
        document.getElementById("add_to_cart_button").style.display = "none";
        document.getElementById("quantity_buttons").style.display = "flex";
        document.getElementById("current_quantity").innerText = response.quantity;
    }
}

function removeFromShoppingCartHandler() {
    let response = JSON.parse(this.responseText);
    console.log(response);
    if (response.success) {
        if (response.quantity == 0){
            document.getElementById("add_to_cart_button").style.display = "block";
            document.getElementById("quantity_buttons").style.display = "none";
        }
        document.getElementById("current_quantity").innerText = response.quantity;
    }
}


function addIncreaseButton(productId) {
    if(!isLoggedIn){
        addToLocalStorage(productId);
    } else {
        sendAjaxRequest('post', `/product/${productId}/add_to_shoppingcart?product_id=${productId}`, {}, addHandler);
    }
}

function removeDecreaseButton(productId) {
        if(!isLoggedIn){
            removeFromLocalStorage(productId);
        } else {
            if(document.getElementById('add_to_cart_button_'+ productId)) {
                sendAjaxRequest('post', `/product/${productId}/remove_from_shoppingcart?product_id=${productId}`, {}, removeHandler);
            } else {
                sendAjaxRequest('post', `/product/${productId}/remove_from_cart_page?product_id=${productId}`, {}, removeHandler);
            }
        }
}

function addHandler() {
    let response = JSON.parse(this.responseText);
    let button = document.getElementById('add_to_cart_button_'+ response.product_id);
    if (response.success) {
        if(response.quantity == 2 && !button) {
            document.getElementById("decrease_button_" + response.product_id).style.display = "block";
            document.getElementById("unable_" + response.product_id).style.display = "none";
        }
        if(!button) {
            document.getElementById('total_' + response.product_id).innerText = response.price;
            document.getElementById('subtotal').innerText = response.total;
            document.getElementById('total').innerText = response.total;
        } else {
            button.style.display = "none";
            document.getElementById("quantity_buttons_" + response.product_id).style.display = "block";
        }
        document.getElementById('quantity_' + response.product_id).innerText = response.quantity;
    }
}

function removeHandler() {
    let response = JSON.parse(this.responseText);
    let button = document.getElementById('add_to_cart_button_'+ response.product_id);
    if (response.success) {
        if (response.quantity == 0 && button){
            button.style.display = "block";
            document.getElementById("quantity_buttons_" + response.product_id).style.display = "none";
        }
        if(response.quantity == 1 && !button){ 
            document.getElementById('decrease_button_' + response.product_id).style.display = "none";
            document.getElementById('unable_' + response.product_id).style.display = "block";
        }
        if(!button) {
            document.getElementById('total_' + response.product_id).innerText = response.price;
            document.getElementById('subtotal').innerText = response.total;
            document.getElementById('total').innerText = response.total;
        }
        document.getElementById('quantity_' + response.product_id).innerText = response.quantity;
    }
}

function addToLocalStorage(productId) {
    let cart = localStorage.getItem('cart');
    let button = document.getElementById("add_to_cart_button");
    if (!cart) {
        cart = {};
    } else {
        cart = JSON.parse(cart);
    }
    
    if (cart[productId]) {
        cart[productId] += 1;
        if(button){
            document.getElementById("current_quantity").innerText = cart[productId];
        } else {
            if(window.location.pathname == '/shopping-cart'){
                const price = document.getElementById(`item_price_${productId}`).innerText;
                const subtotal = document.getElementById('subtotal').innerText;
                document.getElementById(`total_${productId}`).innerText = parseFloat(cart[productId] * Number(price)).toFixed(2); 
                document.getElementById('subtotal').innerText = parseFloat(Number(subtotal) + Number(price)).toFixed(2);
                document.getElementById('total').innerText = parseFloat(Number(subtotal) + Number(price)).toFixed(2);
                document.getElementById(`decrease_button_${productId}`).style.display = "block";
                document.getElementById(`unable_${productId}`).style.display = "none"; 
            }
            document.getElementById('quantity_' + productId).innerText = cart[productId];
        }
    } else {
        cart[productId] = 1;
        if(button) {
            document.getElementById("add_to_cart_button").style.display = "none";
            document.getElementById("quantity_buttons").style.display = "flex";
            document.getElementById("current_quantity").innerText = cart[productId];
        } else {
            document.getElementById('add_to_cart_button_'+ productId).style.display = "none";
            document.getElementById("quantity_buttons_" + productId).style.display = "block";
            document.getElementById('quantity_' + productId).innerText = cart[productId];
        }
        
    }
    localStorage.setItem('cart', JSON.stringify(cart));
}

function removeFromLocalStorage(productId) {
    let cart = localStorage.getItem('cart');
    let button = document.getElementById("add_to_cart_button");
    cart = JSON.parse(cart);
    
    if (cart[productId]) {
        cart[productId] -= 1;

        if (cart[productId] === 0) {
            delete cart[productId];
            if(button) {
                document.getElementById("add_to_cart_button").style.display = "block";
                document.getElementById("quantity_buttons").style.display = "none";
            } else {
                document.getElementById('add_to_cart_button_'+ productId).style.display = "block";
                document.getElementById("quantity_buttons_" + productId).style.display = "none";
            }
        } else {
            if(button){
                document.getElementById("current_quantity").innerText = cart[productId];
            } else {
                if(window.location.pathname == '/shopping-cart'){
                    if(cart[productId] === 1) {
                        document.getElementById(`decrease_button_${productId}`).style.display = "none";
                        document.getElementById(`unable_${productId}`).style.display = "block";
                    }
                    const price = document.getElementById(`item_price_${productId}`).innerText;
                    const subtotal = document.getElementById('subtotal').innerText;
                    document.getElementById(`total_${productId}`).innerText = parseFloat(cart[productId] * Number(price)).toFixed(2); 
                    document.getElementById('subtotal').innerText = parseFloat(Number(subtotal) - Number(price)).toFixed(2);
                    document.getElementById('total').innerText = parseFloat(Number(subtotal) - Number(price)).toFixed(2);
                }
                document.getElementById('quantity_' + productId).innerText = cart[productId];
            }
        }
        
        localStorage.setItem('cart', JSON.stringify(cart));
    }
}

addToShoppingCart();
addToShoppingCartIncreaseButton();
removeFromShoppingCartDecreaseButton();

if(isLoggedIn) {
    const cart = JSON.parse(localStorage.getItem('cart'));
    if(cart) {
        Object.keys(cart).forEach(productId => {
            sendAjaxRequest('post', '/save-cart-items/'+ productId + '/' + cart[productId],{}, addHandler);
        });
        localStorage.clear();
    }
}