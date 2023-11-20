function showProducts(sections){
    sections.forEach((section) => {
        const button = section.querySelector('.showProducts');
        const list = section.querySelector('.list-products');
        const exit = section.querySelector('.list-products button')
        button.addEventListener('click', () => {
            list.classList.add('show');
            button.classList.add('hide');
        });
        exit.addEventListener('click', () => {
            list.classList.remove('show');
            button.classList.remove('hide');
        });
    });
}

const products = document.querySelectorAll('td div.products');
showProducts(products);

