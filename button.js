const menuButton = document.getElementById('menu-button');
const navList = document.querySelector('nav ul');

menuButton.addEventListener('click', () => {
    if (navList.style.display === 'block') {
        navList.style.display = 'none';
    } else {
        navList.style.display = 'block';
    }
});

// vede latime ecran si da hide la butoane din nav
function toggleMenuOnScreenSize() {
    if (window.innerWidth <= 769) {
        menuButton.style.display = 'block';
        navList.style.display = 'none';
    } else {
        menuButton.style.display = 'none';
        navList.style.display = 'block';
    }
}

// check initial
toggleMenuOnScreenSize();

window.addEventListener('resize', toggleMenuOnScreenSize);