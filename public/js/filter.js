document.addEventListener('DOMContentLoaded', function () {
    window.filterProducts = function() {
        const selectedBrands = Array.from(document.querySelectorAll('input[name="brands"]:checked')).map(brand => brand.value);
        const minPrice = parseFloat(document.getElementById('min_price').value) || 0;
        const maxPrice = parseFloat(document.getElementById('max_price').value) || Infinity;

        const products = document.querySelectorAll('.product-card');
        products.forEach(product => {
            const productBrand = product.querySelector('.product-description-mainpage').textContent.trim();
            const productPrice = parseFloat(product.querySelector('.product-price-mainpage').textContent.trim().replace('€', ''));

            const brandFilter = selectedBrands.length === 0 || selectedBrands.includes(productBrand);
            const priceFilter = productPrice >= minPrice && productPrice <= maxPrice;

            if (brandFilter && priceFilter) {
                product.classList.remove('hidden');
            } 
            else {
                product.classList.add('hidden');
            }
        });
    }

    const filterForm = document.getElementById('filter-form'); 
    filterForm.addEventListener('change', function () {
        filterProducts();
    });
});
