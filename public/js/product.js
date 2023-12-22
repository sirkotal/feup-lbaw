document.getElementById('leftButton').addEventListener('click', () => {
    nextImage(-1);
});

document.getElementById('rightButton').addEventListener('click', () => {
    nextImage(1);
});

let currentImageIndex = 0;
let productImage = document.getElementById('productImage');
let altImages = document.getElementsByClassName('additional-product-image');

let images = [productImage, ...altImages];

function nextImage(direction) {
    currentImageIndex += direction;
    if (currentImageIndex < 0) {
        currentImageIndex = images.length - 1;
    } 
    else if (currentImageIndex >= images.length) {
        currentImageIndex = 0;
    }

    images.forEach(image => {
        image.classList.add('hidden');
    });

    images[currentImageIndex].classList.remove('hidden');
}
