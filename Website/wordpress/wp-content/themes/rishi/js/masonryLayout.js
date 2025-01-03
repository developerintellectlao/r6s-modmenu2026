const masonryLayout = () => {
    let elem = document.querySelector('.rishi-container-wrap');
    let msnry = new Masonry( elem, {
    // options
    itemSelector: '.rishi-post'
    });
}

masonryLayout();