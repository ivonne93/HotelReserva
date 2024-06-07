//menu-btn
let navbar = document.querySelector('.header .flex .navbar');
let menuBtn = document.querySelector('.header .flex #menu-btn');

menuBtn.onclick = () => {
    menuBtn.classList.toggle('fa-times');
    navbar.classList.toggle('active');
}

window.onscroll = () => {//se cierren esa opciones del menu
    menuBtn.classList.remove('fa-times');
    navbar.classList.remove('active');
}


