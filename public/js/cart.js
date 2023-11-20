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
            sendAjaxRequest('post', '/product/'+ data.product_id + '/add_to_shoppingcart?product_id=' + data.product_id,{}, addToShoppingCartHandler
            );
        });
    }
}

function addToCart() {
    let addToCartButton = document.getElementsByClassName("add_to_cart_button");

    for (let i = 0; i < addToCartButton.length; i++) {
        addToCartButton[i].addEventListener("click", function() {
            let data = {
                'product_id': addToCartButton[i].getAttribute("product_id"),
            };
            sendAjaxRequest('post', '/product/'+ data.product_id + '/add_to_shoppingcart?product_id=' + data.product_id,{}, addHandler
            );
        });
    }
}

function addToShoppingCartIncreaseButton() {
    let increaseQuantityButton = document.getElementById("increase_quantity");

    if (increaseQuantityButton){
        increaseQuantityButton.addEventListener("click", function() {
            let data = {
                'product_id': increaseQuantityButton.getAttribute("product_id"),
            };
            sendAjaxRequest('post', '/product/'+ data.product_id + '/add_to_shoppingcart?product_id=' + data.product_id, {}, addToShoppingCartHandler);
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
        sendAjaxRequest('post', '/product/'+ data.product_id + '/remove_from_shoppingcart?product_id=' + data.product_id, {}, removeFromShoppingCartHandler);
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


function addIncreaseButton() {

    let increasebutton = document.getElementsByClassName("increase_quantity");

    for (let i = 0; i < increasebutton.length; i++) {
        increasebutton[i].addEventListener("click", function() {
        let data = {
            'product_id': increasebutton[i].getAttribute("product_id"),
        };
        sendAjaxRequest('post', '/product/'+ data.product_id + '/add_to_shoppingcart?product_id=' + data.product_id, data, addHandler);
        });
    }
}

function removeDecreaseButton() {

    let decreasebutton = document.getElementsByClassName("decrease_quantity");

    for (let i = 0; i < decreasebutton.length; i++) {
        decreasebutton[i].addEventListener("click", function() {
        let data = {
            'product_id': decreasebutton[i].getAttribute("product_id"),
        };
        if(document.getElementById('add_to_cart_button_'+ data.product_id)) {
            sendAjaxRequest('post', '/product/'+ data.product_id + '/remove_from_shoppingcart?product_id=' + data.product_id, {}, removeHandler);
        } else {
            sendAjaxRequest('post', '/product/'+ data.product_id + '/remove_from_cart_page?product_id=' + data.product_id, data, removeHandler);
        }
        });
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
addToCart()
addToShoppingCart();
addToShoppingCartIncreaseButton();
removeFromShoppingCartDecreaseButton();
addIncreaseButton();
removeDecreaseButton();
