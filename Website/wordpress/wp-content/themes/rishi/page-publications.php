<?php
/*
Template Name: Publications-custom
*/
get_header(); 
//--------custom css-----------
wp_enqueue_style( 'custom-styling', get_stylesheet_directory_uri() . '/publication-page.css' );
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">


    <!-- breadcrumb -->
    <div class=" breadcrumb gray-bg _theme-bg-50 d-none d-md-block" style="margin-bottom: 0;">
        <div class="container">
            <a href=""> <i class="fa  fa-long-arrow-left"></i> &nbsp; </a> »
            <a href="<?php echo home_url();?>">Home</a> »
            <a href="/publications" class="breadcrumb-1">Publications</a>
        </div>
    </div>
    <div class=" breadcrumb theme-bg-50 d-md-none" style="margin-bottom: 0;">
        <div class="container">
            <a href="javascript:history.back()"> <i class="fa  fa-long-arrow-left"></i> &nbsp;Back </a>
        </div>
    </div>
    <!-- breadcrumb -->

     <!-- Banner Section -->
     <div class="banner container mt-3 mb-3">
        <img src="https://www.mrcmekong.org/wp-content/uploads/2024/12/Publication-Banner-image.jpg" alt="Document_banner" class="img-fluid">
        <div class="banner-text">
            Publication
        </div>
    </div>
    <!-- Banner Section -->

<!-- Tabs Section -->
    <div class="container tabs-section">
        <div class="tabs-wrapper">
            <div class="tabs-scroll">
                    <!-- Left Arrow -->
                <button class="tabs-arrow left" id="leftArrow" onclick="scrollTabs(-100)">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <ul class="nav nav-tabs" id="docTabs" role="tablist" id="navScroll">
                    <li class="nav-item">
                        <a class="nav-link active" id="tab1" data-bs-toggle="tab" href="#agriculture" role="tab" data-tab="agriculture-and-irrigation">Agriculture and Irrigation</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab2" data-bs-toggle="tab" href="#climate" role="tab" data-tab="climate-change">Climate Change</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab3" data-bs-toggle="tab" href="#environment" role="tab" data-tab="environment">Environment</a>
                    </li>
                     <li class="nav-item">
                        <a class="nav-link" id="tab3" data-bs-toggle="tab" href="#fisheries" role="tab" data-tab="fisheries">Fisheries</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab4" data-bs-toggle="tab" href="#flood-and-drought" role="tab" data-tab="flood-and-drought">Flood and Drought</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab5" data-bs-toggle="tab" href="#gender" role="tab" data-tab="gender">Gender</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab6" data-bs-toggle="tab" href="#hydropower" role="tab" data-tab="hydropower">Hydropower</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab7" data-bs-toggle="tab" href="#navigation" role="tab" data-tab="navigation">Navigation</a>
                    </li>
                    <!-- Add more tabs if needed -->
                </ul>
                    <!-- Right Arrow -->
                <button class="tabs-arrow right" id="rightArrow" onclick="scrollTabs(100)">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
             
        </div>

        <div class="tab-content my-4">
            <!-- tabs Content agriculture-->
            <div class="tab-pane fade show active" id="agriculture" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <!-- <h5 class="mb-0">116 results</h5> -->
                    <!-- Sort Dropdown -->
                   <!--  <select class="form-select w-auto">
                        <option value="recent">Sort by: Recent</option>
                        <option value="title">Sort by: Title</option>
                        <option value="Created">Sort by: Created</option>
                    </select> -->
                    <!-- Sort Dropdown End-->
                </div>

                
                <!-- PDF Cards Section End-->             
            </div>
            <!-- tabs Content agriculture End-->

            <!-- tabs Content climate-->
            <div class="tab-pane fade" id="climate" role="tabpane2">
                <!-- <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">16 results</h5>
                </div> -->

                <!-- PDF Cards Section -->
                <!-- <div class="pdf-card">
                    <img src="https://www.mrcmekong.org/wp-content/uploads/2024/12/pdf_icon.svg" alt="pdf_svg" class="me-3">
                    <div>
                        <h6>Final report: Improved Land and Water Use</h6>
                        <small>Created December 22, 2023 | Published: October 30, 1995</small>
                        <div class="key-words">
                             <span class="key">Agriculture and Irrigation | Climate Change | </span>
                             <span class="language">English</span>
                         </div>
                    </div>
                </div>    -->
                <!-- PDF Cards Section End-->             
            </div>
            <!-- tabs Content climate End-->

            <!-- tabs Content environment-->
            <div class="tab-pane fade" id="environment" role="tabpane3">
                <!-- <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">11 results</h5>
                </div> -->

                <!-- PDF Cards Section -->
                <!-- <div class="pdf-card">
                    <img src="https://www.mrcmekong.org/wp-content/uploads/2024/12/pdf_icon.svg" alt="pdf_svg" class="me-3">
                    <div>
                        <h6>Final report: Improved Land and Water Use</h6>
                        <small>Published: October 30, 1995</small>
                        <div class="key-words">
                             <span class="key">Agriculture and Irrigation | Climate Change | </span>
                             <span class="language">English</span>
                         </div>
                    </div>
                </div>    -->
                <!-- PDF Cards Section End-->             
            </div>
            <!-- tabs Content environment End-->

            <!-- tabs Content fisheries-->
            <div class="tab-pane fade" id="fisheries" role="tabpane4"></div>
            <!-- tabs Content fisheries End-->

            <!-- tabs Content flood-and-drought-->
            <div class="tab-pane fade" id="flood-and-drought" role="tabpane5"></div>
            <!-- tabs Content flood-and-drought End-->

            <!-- tabs Content gender-->
            <div class="tab-pane fade" id="gender" role="tabpane6"></div>
            <!-- tabs Content gender End-->

            <!-- tabs Content hydropower-->
            <div class="tab-pane fade" id="hydropower" role="tabpane7"></div>
            <!-- tabs Content hydropower End-->

            <!-- tabs Content navigation-->
            <div class="tab-pane fade" id="navigation" role="tabpane8"></div>
            <!-- tabs Content navigation End-->

        </div>
    </div>
<!-- Tabs Section End-->
<!-- Pagination Section -->
        <?php
            // echo paginate_links([
            //     'total' => $query->max_num_pages,
            // ]);
        ?>
<!-- Pagination Section End-->

<div class=" breadcrumb gray-bg _theme-bg-50 " style="margin-bottom: 0;">
        <div class="container">
            <!-- <a href="javascript:history.back()"> <i class="fa  fa-long-arrow-left"></i> &nbsp;Back </a> -->
        </div>
        <?php include 'scrollBtnPage.html';?>   
   </div>

<!-- Latest compiled JavaScript -->
    <script>
        // Check Tabs Overflow and Toggle Arrow Visibility
        function checkTabsOverflow() {
            const tabsScroll = document.querySelector('.nav.nav-tabs');
            const leftArrow = document.querySelector('.tabs-arrow.left');
            const rightArrow = document.querySelector('.tabs-arrow.right');

            if (tabsScroll.scrollWidth > tabsScroll.clientWidth) {
                leftArrow.style.display = 'flex';
                rightArrow.style.display = 'flex';
            } else {
                leftArrow.style.display = 'none';
                rightArrow.style.display = 'none';
            }
        }

        function scrollTabs(amount) {
            const tabsScroll = document.querySelector('.nav.nav-tabs');
            if (tabsScroll) {
                tabsScroll.scrollBy({ left: amount, behavior: 'smooth' });
            } else {
                console.error("Tabs container not found. Ensure the '.tabs-scroll' class is applied to the correct element.");
            }
        }
        // Update Arrows Based on Scroll Position
        // function updateArrows() {
        //     const tabsScroll = document.querySelector('.nav.nav-tabs');
        //     const leftArrow = document.querySelector('.tabs-arrow.left');
        //     const rightArrow = document.querySelector('.tabs-arrow.right');

        //     const scrollLeft = tabsScroll.scrollLeft;
        //     const maxScrollLeft = tabsScroll.scrollWidth - tabsScroll.clientWidth;

        
        //     leftArrow.classList.toggle('disabled', scrollLeft === 0);

            
        //     rightArrow.classList.toggle('disabled', scrollLeft >= maxScrollLeft);
        // }

        // Initialize and Monitor Events
        window.addEventListener('load', () => {
            checkTabsOverflow();
            // updateArrows();
        });

        window.addEventListener('resize', () => {
            checkTabsOverflow();
            // updateArrows();
        });
        // document.getElementById('navScroll').addEventListener('scroll', updateArrows);
        //----------------####------------------//
        jQuery(document).ready(function ($) {
            function loadPublications(page = 1, category = '', tabId) {
                $.ajax({
                    url: '/wp-json/v1/publications', // Use your custom REST API route
                    type: 'GET',
                    data: {
                        page: page,
                        category: category,
                    },
                    beforeSend: function () {
                        $(tabId).html('<p>Loading...</p>'); // Optional loading message
                    },
                    success: function (response) {
                        if (response.posts.length > 0) {
                            let html = `<div class="d-flex justify-content-between align-items-center mb-3">
                             <h5 class="mb-0">${response.total_post} Results</h5>
                            </div>`;
                            response.posts.forEach(post => {
                                let filename = post.title.replace(" ", "-");
                                html += `
                                    <div class="pdf-card">
                                        <img src="https://www.mrcmekong.org/wp-content/uploads/2024/12/pdf_icon.svg" alt="pdf_svg" class="me-3">
                                        <div class="content">
                                            <h6><a href="/?download_document=1&document_id=${post.post_id_}&name=${filename}" target="_blank">
                    ${post.title}
                </a></h6><small> <span class="d-none">Created ${post.create_date} |</span> Published: ${post.publish_date}</small>
                                            <div class="key-words">
                                                <span class="key">${post.allterms}</span>
                                                <span class="language">| ${post.language}</span>
                                            </div>
                                        </div>
                                    </div> 
                                `;
                            });

                            html += generatePagination(response.current_page, response.total_pages);
                            $(tabId).html(html);
                            scrolldiv(tabId);
                        } else {
                            $(tabId).html('<p>No publications found.</p>');
                        }
                    },
                    error: function () {
                        $(tabId).html('<p>Something went wrong.</p>');
                    },
                });
            }
                
            function scrolldiv(elementId){
                // $('html, body').animate({ scrollTop: $(elementId).offset().top }, 1000);
                $('#scrollTopBtn').click();
            } 

            function generatePagination(current, total) {
                const showPages = 7; // Number of pages to show around the current page
                let html = '<ul class="pagination mt-3">';

                // Calculate the start and end page numbers
                let start = Math.max(1, current - Math.floor(showPages / 2));
                let end = Math.min(total, current + Math.floor(showPages / 2));

                // Adjust the start and end pages if they are too close to the edges
                if (end - start + 1 < showPages) {
                    if (current < showPages) {
                        end = Math.min(total, showPages);
                    } else {
                        start = Math.max(1, total - showPages + 1);
                    }
                }

                // Previous page link
                if (current > 1) {
                    html += `<li class="page-item"><a class="page-link" href="#" data-page="${current - 1}">«</a></li>`;
                }

                // Page numbers
                for (let i = start; i <= end; i++) {
                    if (i === current) {
                        html += `<li class="page-item active"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
                    } else {
                        html += `<li class="page-item"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
                    }
                }

                // Next page link
                if (current < total) {
                    html += `<li class="page-item"><a class="page-link" href="#" data-page="${current + 1}">»</a></li>`;
                }

                html += '</ul>';
                return html;
            }


            // Initial load
            loadPublications(1,'agriculture-and-irrigation','#agriculture');

            // Pagination
            $(document).on('click', '.pagination a', function (e) {
                e.preventDefault();
                const page = $(this).data('page');
                let category = '';
                let tabId = '#agriculture';
                $('.nav-link.active').each(function() {
                    category = $(this).data('tab');
                    tabId = $(this).attr('href'); // Get the href attribute of the active tab
                });
                loadPublications(page, category, tabId);
            });

            // // Category filter
            $('.nav-link').on('click', function () {
                const category = $(this).data('tab');
                const tabId =$(this).attr('href');
                loadPublications(1, category, tabId); // Reset to page 1
            });
        });


    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Latest compiled JavaScript -->
<?php get_footer(); ?>
