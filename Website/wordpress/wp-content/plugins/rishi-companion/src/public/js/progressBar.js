document.addEventListener("DOMContentLoaded", function () {
    let winHeight = window.innerHeight,
        docHeight = document.body.clientHeight,
        progressBar = document.querySelector('#rishi-progress-bar progress'),
        max

    /* Set the max scrollable area */
    max = docHeight - winHeight;

    function setProgrssBar(value, max) {
        if (progressBar) {
            progressBar.setAttribute('value', value);
            progressBar.setAttribute('max', max);
        }
    }

    setProgrssBar(window.scrollY, max);

    window.addEventListener('scroll', function () {
        setProgrssBar(window.scrollY, max)
    });

});