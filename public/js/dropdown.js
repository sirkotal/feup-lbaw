function faqDropdown() {
    const questions = document.querySelectorAll('.faq-question');
  
    questions.forEach(section => {
        const button = section.querySelector('.dropdown-button');
        const icon = button.querySelector('i');
  
        button.addEventListener('click', () => {
            section.classList.toggle('open');
            button.classList.toggle('rotate');
        });
    });
}

faqDropdown();