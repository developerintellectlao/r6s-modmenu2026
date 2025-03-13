const infiniteScroll = async () => {
    if (typeof rishi_ajax === 'undefined') return;
    let pageNum = parseInt(rishi_ajax.startPage) + 1;
    const max = parseInt(rishi_ajax.maxPages);
    let nextLink = rishi_ajax.nextLink;


    if (rishi_ajax.autoLoad === "infinite_scroll") {
        let loadingPosts = false;
        let lastPost = false;

        const blog = document.querySelector('.blog');
        const search = document.querySelector('.search');
        const archive = document.querySelector('.archive');
        const infinitePagination = document.querySelector('.infinite-pagination');

        if (blog || search || archive) {
            window.addEventListener('scroll', async function () {
                if (!loadingPosts && !lastPost) {
                    const lastPostVisible = document.querySelector('.post:last-of-type');
                    if (lastPostVisible && pageNum <= max) {
                        loadingPosts = true;
                        infinitePagination.classList.add('is-loading')

                        try {

                            const response = await fetch(nextLink);
                            if (!response.ok) throw new Error('HTTP error, status = ' + response.status);
                            const text = await response.text();

                            let tempDiv = document.createElement('div');
                            tempDiv.innerHTML = text;
                            let latestPosts = tempDiv.querySelectorAll('.post');
                            let loadHtml = Array.from(latestPosts).map(function (post) {
                                return post.outerHTML;
                            }).join('');

                            pageNum++;
                            nextLink = nextLink.replace(/(\/?)page(\/|d=)[0-9]+/, '$1page$2' + pageNum);

                            const siteMain = document.querySelector('.rishi-container-wrap');
                            if (document.querySelector('.masonry_grid')) {
                                const $moreBlocks = document.createRange().createContextualFragment(loadHtml).querySelectorAll('article.rishi-post');
                                $moreBlocks.forEach(block => siteMain.appendChild(block));

                                imagesLoaded(siteMain, () => new Masonry(siteMain, { itemSelector: 'article.rishi-post' }));
                            } else {
                                siteMain.lastElementChild.insertAdjacentHTML('afterend', loadHtml);
                            }

                            const thisDiv = siteMain.querySelector('.entry-content div');
                            if (thisDiv?.classList.contains('tiled-gallery')) {
                                const script = document.createElement('script');
                                script.src = rishi_pro_ajax.plugin_url + '/jetpack/modules/tiled-gallery/tiled-gallery/tiled-gallery.js';
                                document.body.appendChild(script);
                            }
                        } catch (error) {
                            console.error('Fetch error: ', error);
                        } finally {
                            loadingPosts = false;
                            infinitePagination.classList.remove('is-loading')
                        }
                    } else {
                        lastPost = true;
                    }
                }
            });
        }

    }
}

export default infiniteScroll;
