document.addEventListener("DOMContentLoaded", () => {

    /* =============================
       INITIALISATION SWIPER
    ============================== */

    const swiper = new Swiper('.swiper-container', {
        slidesPerView: 3,
        spaceBetween: 20,
        loop: false,

        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev'
        },

        breakpoints: {
            0:   { slidesPerView: 1 },
            576: { slidesPerView: 2 },
            992: { slidesPerView: 3 }
        }
    });

    /* =============================
       SCROLL APRÈS FILTRE
    ============================== */
    const filterForm = document.querySelector("form");

    if (filterForm) {
        filterForm.addEventListener("submit", () => {
            setTimeout(() => {
                window.scrollTo({
                    top: document.querySelector(".swiper-container").offsetTop - 50,
                    behavior: "smooth"
                });
            }, 300);
        });
    }

});
