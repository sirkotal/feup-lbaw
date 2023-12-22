function setActive(nav_links){
    nav_links.forEach(link => {
        if (link.href == window.location.href)
            link.classList.add('active');
    });
}


const nav_links = document.querySelectorAll('nav > ul > li > a');
setActive(nav_links);

function logout(){
    isLoggedIn = false;
    console.log(isLoggedIn);
}