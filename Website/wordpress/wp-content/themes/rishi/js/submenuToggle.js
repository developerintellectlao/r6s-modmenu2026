function submenuToggle(parentLi) {

    handleParent(parentLi);
    function handleParent(parentLi) {
        if(!parentLi) return;

        const toggleBtn = parentLi.querySelector('.submenu-toggle');
        const submenu = parentLi.querySelector(('ul.sub-menu'))

        if(parentLi.classList.contains('submenu-active')) {
            toggleBtn.setAttribute('aria-expanded', 'false');

            closeSubmenu(submenu, () => {
                parentLi.classList.toggle('submenu-active');
            })
        } else {
            toggleBtn.setAttribute('aria-expanded', 'true');
            [...parentLi.parentNode.children].map((el) => {
                el.classList.contains('submenu-active') && handleParent(el)
            })

            parentLi.classList.toggle('submenu-active')
            openSubmenu(submenu)
        }
    }

    function openSubmenu(submenu) {

        handleTransitionEnd(submenu)
    
        requestAnimationFrame(() => {
            const submenuHeight = submenu.getBoundingClientRect().height
            submenu.style.height = '0px'
    
            requestAnimationFrame(() => {
                submenu.style.height = `${submenuHeight}px`
            })
        })
    
    }
    
    function closeSubmenu(submenu, cb) {
    
        handleTransitionEnd(submenu, cb)
    
        requestAnimationFrame(() => {
            const submenuHeight = submenu.getBoundingClientRect().height
            submenu.style.height = `${submenuHeight}px`
    
            requestAnimationFrame(() => {
                submenu.style.height = '0px'
            })
        })
    }
    
    function handleTransitionEnd(submenu, cb) {
        submenu.addEventListener('transitionend', function onTransitionEnd() {
            submenu.removeAttribute('style')
            submenu.removeEventListener('transitionend', onTransitionEnd)
            if (cb) cb()
        })
    }
}

export default submenuToggle;