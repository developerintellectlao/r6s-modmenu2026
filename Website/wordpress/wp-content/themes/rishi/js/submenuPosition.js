function submenuPosition() {
    let submenus = document.querySelectorAll('.site-header .rishi-menu .sub-menu');

    submenus.forEach(function(submenu) {
        let rect = submenu.getBoundingClientRect();
        if (rect.right + rect.width > window.innerWidth) {
            submenu.classList.add('right');
        } else {
            submenu.classList.remove('right');
        }
    });
}


export default submenuPosition