

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

let total = 0;
if(!isLoggedIn){
    const cart = JSON.parse(localStorage.getItem('cart'));
    if(cart && (Object.keys(cart).length !== 0)) {
        document.querySelector('.cart-checkout').style.display = "flex";
        Object.keys(cart).forEach(productId => {
            sendAjaxRequest('post', `/product_info/${productId}`, {}, productInfoHandler);
        })
    } else{
        document.querySelector('.no-items').style.display = 'flex';
    }
} 

function productInfoHandler() {
    const cart = JSON.parse(localStorage.getItem('cart'));
    const {product_id, price, success, product_name, discount, product_path} = JSON.parse(this.responseText);
    if(discount == 0) {
        total += cart[product_id] * price;
    } else {
        total += cart[product_id] * parseFloat(price - price*discount/100,2);
    }
    if(success) {
        document.querySelector('.cart-items').innerHTML += `
        <div id="product_content_${product_id}" class="item-ticket">
            <img class="cart-img" ${product_path ? `src="storage/products/${product_id}_1.png"` : `src="images/products/default.png"`} >
            <div  class="item-details">
                <div class="item-name">${product_name}</div>
                ${discount === 0 ?
                    `<div class="item-quantity"><span id="item_price_${product_id}">${price}</span>€/un</div>` :
                    `<div class="item-quantity"><span id="item_price_${product_id}">${parseFloat(price - price * discount / 100).toFixed(2)}</span>€/un <span class="notification-product-price">${price}€/un </span></div>`
                }
            </div>
            <div id="quantity_buttons" class="buttons">
                <button onClick="removeDecreaseButton(this.dataset.productid)" data-productid="${product_id}" id="decrease_button_${product_id}" class="decrease_quantity cart-button" >-</button>
                <div id="unable_${product_id}" class="unable" >-</div>
                <div id="quantity_${product_id}" class="current_quantity item-price"> ${cart[product_id]}</div>
                <button onClick="addIncreaseButton(this.dataset.productid)" class="increase_quantity cart-button" data-productid="${product_id}">+</button>
            </div>
            ${discount === 0 ?
                `<div class="item-price-total"><span id="total_${product_id}">${parseFloat((cart[product_id] * price).toFixed(2))}</span>€</div>` :
                `<div class="item-price-total"><span id="total_${product_id}">${parseFloat((cart[product_id] * parseFloat(price - price*discount/100,2)).toFixed(2))}</span>€</div>`
            }
            <button onClick="removeProductFromCartPage(this.dataset.productid)" data-productid="${product_id}" class="delete_product" type="submit"><i class="fa fa-trash"></i></button>
        </div>
        `
        if(cart[product_id] > 1) {
            document.getElementById('unable_' + product_id).style.display = "none";
            document.getElementById('decrease_button_' + product_id).style.display = "block";
        } else {
            document.getElementById('unable_' + product_id).style.display = "block";
            document.getElementById('decrease_button_' + product_id).style.display = "none";
        }
        document.getElementById('subtotal').innerText = parseFloat(total.toFixed(2));
        document.getElementById('total').innerText = parseFloat(total.toFixed(2));

    }
}

function removeProductFromCartPage(productId) {
    Swal.fire({
        title: "Do you want to remove this item from your cart?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#00754D",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
      }).then((result) => {
        if (result.isConfirmed) {
            if(isLoggedIn){
                sendAjaxRequest('post', `/product/${productId}/delete_from_cart`, {}, thishandler);
            } else {
                location.reload();
                document.getElementById(`product_content_${productId}`).style.display = 'none';
                const price = document.getElementById(`item_price_${productId}`).innerText;
                let cart = JSON.parse(localStorage.getItem('cart'));
                const quantity = cart[productId];
                delete cart[productId];
                localStorage.setItem('cart', JSON.stringify(cart));
                if(Object.keys(cart).length === 0) {
                    document.querySelector('.cart-checkout').style.display = "none";
                    document.querySelector('.no-items').innerHTML = `There are no items in your shopping cart.`;
                } else {
                    const subtotal = document.getElementById('subtotal').innerText;
                    document.getElementById('total').innerText = parseFloat(Number(subtotal) - Number(price)*quantity).toFixed(2);
                    document.getElementById('subtotal').innerText = parseFloat(Number(subtotal) - Number(price)*quantity).toFixed(2);
                }
            }
        }
      });
}

function thishandler(){
    location.reload();
}

function redirectToLogin(){
    Swal.fire({
        icon: "error",
        title: "You have to be logged in to checkout!",
        showCancelButton: true,
        confirmButtonColor: "#00754D",
        cancelButtonColor: "#d33",
        confirmButtonText: "<a href='login' style='text-decoration: none; color: white;'> Login/Register </a>"
    });
}
