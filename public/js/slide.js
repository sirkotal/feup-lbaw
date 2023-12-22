const images = ['/images/cappucino.jpeg', '/images/cappucino_alt.jpg'];
const container = '.cappuccino';

function changeBackground(images, containerSelector) {
  const container = document.querySelector(containerSelector);

  let nextIndex = 1;

  let preloadImages = [];

  if (container) {
    container.style.transition = 'background-image 3s ease-in-out';
    preloadImages = images.map((src) => {
      const img = new Image();
      img.src = src;
      return img;
    });
  }

  setInterval(() => {
    const nextImage = preloadImages[nextIndex];
    if (nextImage && container) {
      setTimeout(() => {
        container.style.backgroundImage = `url(${nextImage.src})`;
        nextIndex++;
        if (nextIndex === images.length) {
          nextIndex = 0;
        }
      }, 1000);
    }
  }, 5000);
}

changeBackground(images, container);