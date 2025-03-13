const scrollTotop = () => {
    const backToTop = document.querySelector('.to_top');
    if (null !== backToTop) {
        document.addEventListener("scroll", () => {
            window.scrollY > 300
                ? backToTop.classList.add('active')
                : backToTop.classList.remove("active");
        })
        backToTop.addEventListener('click', () => {
            setTimeout(() => {
                window.scrollTo({
                    top: 0,
                    behavior: "smooth"
                });
            }, 100);

        });
    }
}

export default scrollTotop;
