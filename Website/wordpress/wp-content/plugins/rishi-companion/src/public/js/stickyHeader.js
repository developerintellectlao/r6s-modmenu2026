document.addEventListener("DOMContentLoaded", function() {
    const stickyElements = document.querySelectorAll('.sticky-header');

    if (stickyElements.length === 0) return;

    // Use a map to track the intersection state of each element
    const intersectionStates = new Map();

    const observer = new IntersectionObserver((entries) => {
        // Use 'find' instead of 'forEach' to avoid unnecessary iterations
        const intersectingEntry = entries.find(entry => entry.isIntersecting);

        if (intersectingEntry) {
            intersectingEntry.target.classList.remove('is-sticky');
            // Increase the intersection count for this element
            intersectionStates.set(intersectingEntry.target, (intersectionStates.get(intersectingEntry.target) || 0) + 1);

            if (intersectionStates.get(intersectingEntry.target) > 1) {
                intersectingEntry.target.classList.add('sticky-done');
            }
        } else {
            entries.forEach(entry => {
                entry.target.classList.add('is-sticky');
                entry.target.classList.remove('sticky-done');
            });
        }
    }, { threshold: 0 });

    stickyElements.forEach(ele => {
        observer.observe(ele);
    })
})
