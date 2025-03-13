function accessibility() {
    const rishi_body = document.querySelector('body');
    const focusableElements = 'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])';
    const modal = document.querySelectorAll(".search-toggle-form, .rishi-drawer-wrapper"); // select the modal by it's id

    document.addEventListener('keydown', (e) => {
        if (e.key == 'Tab') {
            rishi_body.classList.add('keyboard-nav-on');
        }
    })

    document.addEventListener('mousemove', () => {
        rishi_body.classList.remove('keyboard-nav-on');

    })

    modal.forEach(element => {
        let firstFocusableElement = element.querySelectorAll(focusableElements)[0]; // get first element to be focused inside modal
        let focusableContent = element.querySelectorAll(focusableElements);
        let lastFocusableElement = focusableContent[focusableContent.length - 1]; // get last element to be focused inside modal
        document.addEventListener('keydown', function (e) {
            let isTabPressed = e.key === 'Tab' || e.keyCode === 9;
    
            if (!isTabPressed) {
                return;
            }
    
            if (e.shiftKey) {
                // if shift key pressed for shift + tab combination
                if (document.activeElement === firstFocusableElement) {
                    lastFocusableElement.focus(); // add focus for the last focusable element
                    e.preventDefault();
                }
            } else {
                // if tab key is pressed
                if (document.activeElement === lastFocusableElement) {
                    // if focused has reached to last focusable element then focus first focusable element after pressing tab
                    firstFocusableElement.focus(); // add focus for the first focusable element
                    e.preventDefault();
                }
            }
        });
    
        firstFocusableElement.focus();
    
    });
    
}

export default accessibility