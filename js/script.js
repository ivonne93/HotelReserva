//menú
let navbar = document.querySelector('.header .navbar');

document.querySelector('#menu-btn').onclick = () => {
    navbar.classList.toggle('active');
};


window.onscroll = () => {//cierre
    navbar.classList.remove('active');
};



/*home section swiper-navigation con efecto Cover Flow */
var swiper = new Swiper(".home-slider", {
    loop: true,
    effect: "coverflow",
    grabCursor: true,
    coverflowEffect: {
        rotate: 50,
        stretch: 0,
        depth: 100,
        modifier: 1,
        slideShadows: false,
    },
    navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
    },
});


/*gallery section */
var swiper = new Swiper(".gallery-slider", {
    loop: true,
    effect: "coverflow",
    slidesPerView: "auto",//diapositivas se ajusta a  contenedor
    centeredSlides: true,
    grabCursor: true,
    coverflowEffect: {
        rotate: 0,/*rotacion de la diapositiva, 0 no se rotara */
        stretch: 0,
        depth: 100,
        modifier: 2,
        slideShadows: true,
    },
    pagination: {
        el: ".gallery-slider .swiper-pagination",
    },
});


// CONTACT section la parte de las preguntas
document.querySelectorAll('.contact .row .faq .box h3').forEach(faqBox => {
    faqBox.onclick = () => {
        faqBox.parentElement.classList.toggle('active');
    }
});


// REVIEWS section swiper con Responsive breakpoints
var swiper = new Swiper(".reviews-slider", {
    loop: true,
    slidesPerView: "auto",
    grabCursor: true,
    spaceBetween: 30,
    pagination: {
        el: ".swiper-pagination",
    },
    breakpoints: {
        768: {
            slidesPerView: 1,//se mostrara 1 slide
        },
        991: {
            slidesPerView: 2,//se mostrara 2 slide
        },
    },
});