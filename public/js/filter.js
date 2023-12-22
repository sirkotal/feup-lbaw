document.addEventListener('DOMContentLoaded', function() {
    const brandCheckboxes = document.querySelectorAll('.brand-checkbox input[type="checkbox"]');
    const categoryCheckboxes = document.querySelectorAll('.category-checkbox input[type="checkbox"]');
    const sortForm = document.getElementById('sort-form');
    const filterForm = document.getElementById('filter-form');

    const discountCheckboxes = document.querySelectorAll('.discount-option input[type="checkbox"]');
    const viewMoreCategoriesButton = document.getElementById('view-more-categories');
    const viewMoreBrandsButton = document.getElementById('view-more-brands');
    const hiddenCategories = document.querySelectorAll('.category-checkbox:nth-child(n+6)');
    const hiddenBrands = document.querySelectorAll('.brand-checkbox:nth-child(n+6)');

    if (viewMoreCategoriesButton){
        viewMoreCategoriesButton.addEventListener('click', function() {
            hiddenCategories.forEach(category => {
                category.style.display = 'block';
            });
    
            viewMoreCategoriesButton.style.display = 'none';
        });
    }

    if (viewMoreBrandsButton){
        viewMoreBrandsButton.addEventListener('click', function() {
            hiddenBrands.forEach(brand => {
                brand.style.display = 'block';
            });
    
            viewMoreBrandsButton.style.display = 'none';
        });
    }

    if (hiddenBrands.length <= 5){
        if (viewMoreBrandsButton) viewMoreBrandsButton.style.display = 'none';
    }

    if (hiddenCategories.length <= 5){
        if (viewMoreCategoriesButton) viewMoreCategoriesButton.style.display = 'none';
    }

    function updateHiddenInput(inputName, checkboxes) {
        const selectedValues = Array.from(checkboxes)
            .filter(checkbox => checkbox.checked)
            .map(checkbox => checkbox.value);
        localStorage.setItem(`selected_${inputName}`, JSON.stringify(selectedValues));
        filterForm.querySelector(`input[name="selected_${inputName}"]`).value = selectedValues.join(',');
        sortForm.querySelector(`input[name="selected_${inputName}"]`).value = selectedValues.join(',');
    }

    function handleCheckboxClick(checkboxes, inputName) {
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('click', function() {
                updateHiddenInput(inputName, checkboxes);
            });
        });
    }

    function restoreCheckboxState(checkboxes, inputName) {
        const storedValues = localStorage.getItem(`selected_${inputName}`);
        if (storedValues) {
            const parsedValues = JSON.parse(storedValues);
            checkboxes.forEach(checkbox => {
                checkbox.checked = parsedValues.includes(checkbox.value);
            });
            updateHiddenInput(inputName, checkboxes);
        }
    }

    function resetFilters() {
        console.log('a')
        brandCheckboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
    
        categoryCheckboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
    
        discountCheckboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
    
        sortForm.querySelector('input[name="selected_brands"]').value = '';
        sortForm.querySelector('input[name="selected_categories"]').value = '';
        sortForm.querySelector('input[name="min-price"]').value = '';
        sortForm.querySelector('input[name="max-price"]').value = '';
        sortForm.querySelector('input[name="selected_discount"]').value = '';
    
        filterForm.querySelector('input[name="selected_brands"]').value = '';
        filterForm.querySelector('input[name="selected_categories"]').value = '';
        filterForm.querySelector('input[name="min-price"]').value = '';
        filterForm.querySelector('input[name="max-price"]').value = '';
        filterForm.querySelector('input[name="selected_discount"]').value = '';
    
        document.getElementById('min-price').value = '';
        document.getElementById('max-price').value = '';
    
        localStorage.removeItem('selected_brands');
        localStorage.removeItem('selected_categories');
        localStorage.removeItem('selected_discount');
        localStorage.removeItem('min-price');
        localStorage.removeItem('max-price');
    }

    const resetFiltersButton = document.getElementById('reset-filters-button');
    resetFiltersButton.addEventListener('click', resetFilters);

    const resetCheckboxesButton = document.getElementById('reset-checkboxes-button');
    resetCheckboxesButton.addEventListener('click', resetFilters);

    const navLinks = document.querySelectorAll('nav ul li a');
    navLinks.forEach(link => {
        link.addEventListener('click', resetFilters);
    });

    function restorePrices() {
        let minPrice = localStorage.getItem('min-price');
        let maxPrice = localStorage.getItem('max-price');
        filterForm.querySelector(`input[name="min-price"`).value = minPrice;
        filterForm.querySelector(`input[name="max-price"`).value = maxPrice;
        sortForm.querySelector(`input[name="min-price"]`).value = minPrice;
        sortForm.querySelector(`input[name="max-price"]`).value = maxPrice;

        if (minPrice) {
            document.getElementById('min-price').value = minPrice;
        }

        if (maxPrice) {
            document.getElementById('max-price').value = maxPrice;
        }
    }


    if (brandCheckboxes) handleCheckboxClick(brandCheckboxes, 'brands');
    if (categoryCheckboxes) handleCheckboxClick(categoryCheckboxes, 'categories');
    if (discountCheckboxes) handleCheckboxClick(discountCheckboxes, 'discount')

    if (brandCheckboxes) restoreCheckboxState(brandCheckboxes, 'brands');
    if (categoryCheckboxes) restoreCheckboxState(categoryCheckboxes, 'categories');
    if (discountCheckboxes) restoreCheckboxState(discountCheckboxes, 'discount');

    restorePrices();
});

function setPrices(){
    const minPrice = document.getElementById('min-price').value;
    const maxPrice = document.getElementById('max-price').value;

    localStorage.setItem('min-price', minPrice);
    localStorage.setItem('max-price', maxPrice);

}