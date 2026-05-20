document.addEventListener("DOMContentLoaded", function () {
    const goTopBtn = document.getElementById("goTopBtn");
    if (goTopBtn) {
        window.addEventListener("scroll", function () {
            goTopBtn.style.display = window.scrollY > 300 ? "block" : "none";
        });
        goTopBtn.addEventListener("click", function (event) {
            event.preventDefault();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }
});
