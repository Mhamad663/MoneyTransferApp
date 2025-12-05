// Toggle mobile menu
document.addEventListener("DOMContentLoaded", function() {
    var toggle = document.getElementById("mobileMenuToggle");
    var menu = document.getElementById("mobileMenu");

    if (toggle && menu) {
        toggle.addEventListener("click", function() {
            var isOpen = menu.style.display === "flex";
            menu.style.display = isOpen ? "none" : "flex";
        });
    }

    // Smooth scroll for internal anchor links
    document.querySelectorAll('a[href^="#"]').forEach(function(link) {
        link.addEventListener("click", function(e) {
            var targetId = this.getAttribute("href").slice(1);
            var target = document.getElementById(targetId);
            if (target) {
                e.preventDefault();
                window.scrollTo({
                    top: target.offsetTop - 80,
                    behavior: "smooth"
                });
                if (menu && menu.style.display === "flex") {
                    menu.style.display = "none";
                }
            }
        });
    });
});