import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener("DOMContentLoaded", () => {
    const html = document.documentElement;
    const saved = localStorage.getItem("theme.dark") === "1";

    // Apply saved mode on page load
    if (saved) html.classList.add("dark");

    document.querySelectorAll("[data-toggle-theme]").forEach(btn => {
        btn.addEventListener("click", () => {
            const isDark = html.classList.toggle("dark");
            localStorage.setItem("theme.dark", isDark ? "1" : "0");
        });
    });
});
