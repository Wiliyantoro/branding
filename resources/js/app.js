// Mobile nav toggle, navbar shadow on scroll, smooth in-page anchors.
const menuButton = document.getElementById('mobileMenuButton');
const menu = document.getElementById('mobileMenu');

function closeMenu() {
    menu?.classList.add('hidden');
    menuButton?.setAttribute('aria-expanded', 'false');
}

menuButton?.addEventListener('click', () => {
    const open = menu.classList.toggle('hidden') === false;
    menuButton.setAttribute('aria-expanded', String(open));
});

document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeMenu();
});

const navbar = document.getElementById('navbar');
window.addEventListener('scroll', () => {
    navbar?.classList.toggle('shadow-md', window.scrollY > 50);
}, { passive: true });

// Let the browser handle scrolling (html.scroll-smooth); only fix focus + close menu.
document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener('click', () => {
        closeMenu();
        const target = document.querySelector(anchor.getAttribute('href'));
        if (target && !target.hasAttribute('tabindex')) {
            target.setAttribute('tabindex', '-1');
        }
        target?.focus({ preventScroll: true });
    });
});
