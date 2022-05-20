require("./bootstrap");

// Navbar Animation on Scroll
let navbar = document.querySelector(".navbar");
let offset = 0;
window.addEventListener("scroll", function () {
    let st = window.pageYOffset;
    if (st > offset) {
        navbar.classList.add("fixed-nav");
        document.querySelector(".scroll-top-btn").classList.add("bottom-20");
        document
            .querySelector(".scroll-top-btn")
            .classList.remove("-bottom-10");
    } else {
        navbar.classList.remove("fixed-nav");
        document.querySelector(".scroll-top-btn").classList.remove("bottom-20");
        document.querySelector(".scroll-top-btn").classList.add("-bottom-10");
    }
});

// Toggle Menu
let hMenu = document.querySelector(".toggle-menu");
hMenu.addEventListener("click", function () {
    if (!hMenu.classList.contains("show")) {
        this.classList.add("show");
        document.querySelector(".nav-menu").classList.add("show");
        document.querySelector(".navbar").classList.add("show");
    } else {
        this.classList.remove("show");
        document.querySelector(".nav-menu").classList.remove("show");
        document.querySelector(".navbar").classList.remove("show");
    }
});

// Toggle dark mode
const toggleSwitch = document.querySelector("#toggle-darkMode");
toggleSwitch.addEventListener("click", () => {
    document.body.classList.toggle("dark");
});

// Fasilitas Carousel
if (window.innerWidth < 768) {
    // Carousel
    document.addEventListener("DOMContentLoaded", function () {
        var splide = new Splide(".splide", {
            type: "loop",
            perPage: 2,
            perMove: 2,
            arrows: false,
            classes: {
                pagination: "splide__pagination fasilitas-pagination",
                page: "splide__pagination__page fasilitas-page",
            },
        });
        splide.mount();
    });
} else {
    // Carousel
    document.addEventListener("DOMContentLoaded", function () {
        var splide = new Splide(".splide", {
            type: "loop",
            perPage: 3,
            perMove: 3,
            arrows: false,
            classes: {
                pagination: "splide__pagination fasilitas-pagination",
                page: "splide__pagination__page fasilitas-page",
            },
        });
        splide.mount();
    });
}
