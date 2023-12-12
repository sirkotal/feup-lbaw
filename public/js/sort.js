function getStarRating(rating) {
    let stars = '';
    for (let i = 1; i <= 5; i++) {
        if (i <= rating) {
            stars += '<i class="fa fa-star checked"></i>';
        } else {
            stars += '<i class="fa fa-star"></i>';
        }
    }
    return stars;
}

function getCartFromLocalStorage() {
    return JSON.parse(localStorage.getItem('cart')) || {}; 
}

document.getElementById('sort-button').addEventListener('change', function() {
    var formData = new FormData(document.getElementById('sort-form'));
    
    const wishlistButton = document.querySelector('.auth-wishlist-checker');
    const isLoggedIn = wishlistButton && wishlistButton.getAttribute('data-loggedin') === 'true';
    
    var cartData = getCartFromLocalStorage();
    formData.append('cart', JSON.stringify(cartData));

    fetch('/sort-products', {
        method: 'POST',
        body: formData,
    })
    .then(response => response.json())
    .then(data => {
        var searchedProducts = document.querySelector('.featured-products');
        searchedProducts.innerHTML = '';
        console.log(data.option);

        if(data.option == 'price'){
            data.products.sort(function(a, b) {
                if(a.discount != 0) {
                    var effectivePriceA = a.price - (a.price * (a.discount / 100));
                } else {
                    var effectivePriceA = a.price;
                }
                if(b.discount != 0) {
                    var effectivePriceB = b.price - (b.price * (b.discount / 100));
                } else {
                    var effectivePriceB = b.price;
                }
                return effectivePriceA - effectivePriceB;
            });
        }

        data.products.forEach(function(product) {
            //console.log(wishlistButton);
            //console.log(isLoggedIn);
            //console.log(product.quantityInCart);
            var productHTML = `
                <div class="product-card">
                <a href="product/${product.id}"><img title="${product.product_name}" class="product-image-mainpage" src="images/products/${product.id}.png" alt="${product.product_path}"></a>
                    <a class="anchor" href="/showProductDetails/${product.id}"><div class="product-name-mainpage">${product.product_name}</div></a>
                    <div class="product-description-mainpage">${product.brand.brand_name}</div>
                    <div class="product-score-searchpage">
                        <p>
                            ${getStarRating(product.avg_rating)}
                        </p>
                    </div>
                    ${product.discount === 0 ?
                        `<div class="product-price-mainpage"> €${product.price}/un</div>` :
                        `<div class="product-price-mainpage"> €${parseFloat(product.price - product.price * product.discount / 100).toFixed(2)}/un <span class="notification-product-price"> ${product.price}€/un </span></div> `
                    }
                    <div class="product-buttons-mainpage">
                        ${product.stock === 0 ? '<button class="product-out-mainpage" disabled>Out of Stock</button>' : `
                        <button onClick="addToCart(${product.id})" id="add_to_cart_button_${product.id}" class="add_to_cart_button product-cart-mainpage" product_id="${product.id}" style="${product.quantityInCart > 0 ? 'display: none;' : ''}">Add to Cart</button>`}
                        <div class="quantity_buttons" id="quantity_buttons_${product.id}" product_id="${product.id}" style="${product.quantityInCart > 0 ? '' : 'display: none;'}">
                            <button onClick="removeDecreaseButton(${product.id})" class="decrease_quantity">-</button>
                            <span id="quantity_${product.id}">${product.quantityInCart}</span>
                            <button onClick="addIncreaseButton(${product.id})" class="increase_quantity">+</button>
                        </div>
                        ${isLoggedIn ? `
                            <button id="wishlist_remove_${product.id}" data-loggedin="true" onClick="remove_from_wishlist(${product.id})" data-productid="${product.id}" style="${product.wishlisted ? '' : 'display: none;'}" class="product-wishlist-mainpage"><i class="fa fa-heart"></i></button>
                            <button id="wishlist_add_${product.id}" data-loggedin="true" onClick="add_to_wishlist(${product.id})" data-productid="${product.id}" style="${product.wishlisted ? 'display: none;' : ''}" class="product-removewishlist-mainpage auth-wishlist-checker"><i class="fa fa-heart-o"></i></button>` : ''
                        }
                    </div>
                </div>
            `;
            searchedProducts.insertAdjacentHTML('beforeend', productHTML);

            var scriptTag = document.createElement('script');
            scriptTag.innerHTML = `
                if(!isLoggedIn){
                    function getCartFromLocalStorage() {
                        const cart = localStorage.getItem('cart');
                        return cart ? JSON.parse(cart) : {}; 
                    }
        
                    // Function to update the UI based on the cart data
                    function updateUIFromCart(cart) {
                        Object.keys(cart).forEach(productId => {
                            const quantity = cart[productId];
                            const addButton = document.getElementById(\`add_to_cart_button_\${productId}\`);
                            const quantityButtons = document.getElementById(\`quantity_buttons_\${productId}\`);
                            const quantitySpan = document.getElementById(\`quantity_\${productId}\`);
        
                            if (addButton && quantityButtons && quantitySpan) {
                                addButton.style.display = 'none'; 
                                quantityButtons.style.display = ''; 
                                quantitySpan.textContent = quantity; 
                            }
                        });
                    }
        
                    document.addEventListener('DOMContentLoaded', () => {
                        const cart = getCartFromLocalStorage();
                        updateUIFromCart(cart);
                    });
                }
            `;
        
            searchedProducts.appendChild(scriptTag);
        });
        filterProducts();
    })
    .catch(error => console.error(error));
});