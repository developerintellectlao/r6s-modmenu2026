// animation intersection observer
function scrollTrigger(selector, options = {}) {
    const elements = [];
    selector.forEach(selector => {
        const els = document.querySelectorAll(selector);
        elements.push(...Array.from(els));
    });

    elements.forEach(el => {
        addObserver(el, options);
    });
}

function addObserver(el, options) {
    if (!('IntersectionObserver' in window)) {
        entry.target.classList.add('animate');
        return;
    }
    let observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate');
                observer.unobserve(entry.target);
            }
        })
    }, options)
    observer.observe(el)
}

export default scrollTrigger