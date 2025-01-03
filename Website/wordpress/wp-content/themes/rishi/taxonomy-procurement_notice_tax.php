<?php 
    /* template Name: Publications Listing */
    get_header();
    //--------custom css-----------
    wp_enqueue_style( 'custom-styling', get_stylesheet_directory_uri() . '/dynamic-page.css' );

    $year = $noticeType = ''; 
    if (isset($_GET['post_year']) && !empty($_GET['post_year'])) {
        $year = intval($_GET['post_year']);
    }
    if (isset($_GET['status']) && !empty($_GET['status'])) {
        $noticeType = $_GET['status'];
    }
    $cate_term = get_terms(array(
        'post_type' => 'procurement_notice',
        'taxonomy'   => 'procurement_notice_tax',
        'hide_empty' => false, // Hide empty categories
        'parent'     => 0,    // Start with top-level terms
        //'exclude'    => $exclude_terms, // Exclude 'interactive' and 'example-category'
     ));
     $cate_slug = $cate_name = $cate_desc = "";
    if ($cate_term && !is_wp_error($cate_term)) {
        $cate_slug = $cate_term[0]->slug; // This will output "climate-change"
        $cate_name = $cate_term[0]->name; 
        $cate_desc = $cate_term[0]->description;
    }
?>
<!--Latest compiled and Bootstrap minified CSS  -->
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
.download-section {
    text-align: right;
    margin-bottom: 20px;
}

.download-section a {
    font-size: 16px;
    color: #004c97;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 9.4px 15px;
    border: 1px solid #004c97;
    border-radius: 4px;
    font-weight: bold;
    transition: background-color 0.3s, color 0.3s;
}

.download-section a:hover {
    background-color: #004c97;
    color: white;
}

.download-section.ver-2 a {
    border: none;
    position: relative;
    justify-content: space-between;
    font-weight: 400;
    color: #333;
    font-family: Roboto, Sans-Serif;
}

.download-section.ver-2 a:hover {
    background-color: transparent;
    color: #333;
}

.download-section.ver-2 a:after {
    position: absolute;
    bottom: -4px;
    width: calc(100% - 16px);
    content: "";
    height: 2px;
    background-color: #e4e4e4;
}


/* Filter Section */
.filter-section {
    padding-left: 8px;
}

.filter-section h4 {
    font-size: 18px;
    margin-bottom: 15px;
    color: #333;
    display: flex;
    align-items: center;
    gap: 10px;
}

.filter-section .filter-btn {
    display: block;
    width: 100%;
    padding: 12px;
    font-size: 16px;
    font-weight: bold;
    color: #333;
    background-color: #e9ecef;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    text-align: left;
    transition: background-color 0.3s;
    margin-top: 10px;
}

.filter-section .filter-btn:hover {
    background-color: #004c97;
    color: white;
}

.filter-section select {
    width: 100%;
    padding: 10px;
    border-radius: 0px;
    border: none;
    border-bottom: 1px solid #ccc;
    margin-bottom: 15px;
    font-size: 15px;
}

/* Table Dropdown */
.dropdown-container {
    position: relative;
    display: inline-block;
}

.dropdown-toggle {
    background: transparent !important;
    border: none !important;
    color: #000 !important;
    font-weight: bold;
    padding: 0;
    font-size: 16px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
}

.dropdown-toggle:hover {
    border: none;
    background-color: transparent;
    color: #FFF;
}

.dropdown-menu {
    display: none;
    position: absolute;
    left: -36px;
    background-color: white;
    min-width: 150px;
    box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
    z-index: 1;
    padding: 8px;
    border-radius: 4px;
}

.dropdown-menu a {
    color: #333;
    padding: 8px 12px;
    text-decoration: none;
    display: block;
    font-size: 14px;
}

.dropdown-menu a:hover {
    background-color: #f1f1f1;
}

.procurement-table.table-1 thead {
    position: relative;
}

.procurement-table.table-1 thead:after {
    position: absolute;
    bottom: 10px;
    width: 100%;
    content: "";
    height: 2px;
    background-color: #e4e4e4;
}

.procurement-table.table-1 thead tr th {
    background: #004C97;
    background: #FFF;
    color: #000;
    padding: 10px 5px 20px;
}

.widget-link .custom-select {
    background-size: 16px 12px;
}

.custom-select:focus,
.form-select:focus {
    border: none;
    border-bottom: 1px solid #ccc;
    box-shadow: none;
    outline: none;
}
</style>

<!-- Latest compiled and Bootstrap minified CSS -->

<div id="main-layout">
    <!-- breadcrumb -->
    <div class=" breadcrumb gray-bg _theme-bg-50 d-none d-md-block" style="margin-bottom: 0;">
        <div class="container">
        <a href="/"> <svg width="12px" style="margin-bottom: 4px;" aria-hidden="true" class="e-font-icon-svg e-fas-long-arrow-alt-left" viewBox="0 0 448 512" xmlns="http://www.w3.org/2000/svg"><path d="M134.059 296H436c6.627 0 12-5.373 12-12v-56c0-6.627-5.373-12-12-12H134.059v-46.059c0-21.382-25.851-32.09-40.971-16.971L7.029 239.029c-9.373 9.373-9.373 24.569 0 33.941l86.059 86.059c15.119 15.119 40.971 4.411 40.971-16.971V296z"></path></svg> &nbsp; </a> <i class="fa fa-angle-double-right ms-2 me-2" aria-hidden="true" style="font-size: 10px;"></i>
            <a href="<?php echo home_url();?>">Home</a> <i class="fa fa-angle-double-right ms-2 me-2" aria-hidden="true" style="font-size: 10px;"></i>
            <a href="#" class="breadcrumb-1">About</a> <i class="fa fa-angle-double-right ms-2 me-2" aria-hidden="true" style="font-size: 10px;"></i>
            <a href="<?php echo home_url();?>/working_with_mrc/working-with-mrc/" class="breadcrumb-2"> Working with MRC</a> <i class="fa fa-angle-double-right ms-2 me-2" aria-hidden="true" style="font-size: 10px;"></i> Procurement Notices
        </div>
    </div>
    <div class=" breadcrumb theme-bg-50 d-md-none" style="margin-bottom: 0;">
        <div class="container">
            <a href="javascript:history.back()"> <i class="fa  fa-long-arrow-left"></i> &nbsp;Back </a>
        </div>
    </div>
    <!-- breadcrumb -->
    <!-- <section class="pt-20 pb-0 mb-0">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 g-0">
                    <div class="section-title line-dabble">
                        <h2 class="title c-green">Procurement Notices<?php 
                            //echo $cate_name;
                        ?></h2>
                    </div>
                     <div class="mt-15 mb-16 mb-sm-2">
                        <?php //echo $cate_desc;?>
                    </div>
                </div>
            </div>
        </div>
    </section> -->
    <section class="page-section-0-ptb pt-20">
        <div class="container">
            <div class="row">
                <div class="col-lg-9 col-md-9 ">
                    <div class="row ">
                        <div class="col-lg-12 col-md-12">
                            <div class="section-title line-dabble">
                            <?php  /*<h2 class="title c-green">Procurement Notices</h2>*/?>
                            <h2 class="title c-green"><?php echo $cate_name;?></h2></h2>
                            </div>
                            <div class="mt-15 mb-16 mb-sm-2">
                                <?php //echo $cate_desc;?>
                            </div>
                        </div>
                        <div style="overflow: auto;">
                            <table class="table table-striped table-1 table-sm procurement-table"
                                style="width: 100%; border-radius: 4px;">
                                <thead>
                                    <tr>
                                        <th style="font-size: 16px; font-weight:600; min-width: 140px;">Tender number
                                        </th>
                                        <th
                                            style="font-size: 16px; font-weight:600; min-width: 120px; border-left: 1px solid #FFF;">
                                            Description</th>
                                        <th
                                            style="font-size: 16px; font-weight:600; min-width: 80px; border-left: 1px solid #FFF;">
                                            Procurement Methods</th>
                                        <th
                                            style="font-size: 16px; font-weight:600; min-width: 120px; text-align:center; border-left: 1px solid #FFF;">
                                            Published Date</th>
                                        <th
                                            style="font-size: 16px; font-weight:600; min-width: 100px; text-align:center; border-left: 1px solid #FFF;">
                                            Close Date</th>
                                        <th
                                            style="font-size: 16px; font-weight:600; min-width: 80px; text-align:center; border-left: 1px solid #FFF;">
                                            <div class="dropdown-container">
                                                <button class="dropdown-toggle" onclick="toggleDropdown(event)">
                                                    Notice Type
                                                </button>
                                                <div class="dropdown-menu">
                                                    <form action="" method="get">
                                                        <select id="notice-type" class="form-select" name="status"
                                                            onchange="this.form.submit();">
                                                            <option value="All"
                                                                <?php if($noticeType === 'All') echo 'selected';?>>All
                                                            </option>
                                                            <option value="Open"
                                                                <?php if($noticeType === 'Open') echo 'selected';?>>Open
                                                            </option>
                                                            <option value="Awarded"
                                                                <?php if($noticeType === 'Awarded') echo 'selected';?>>
                                                                Awarded</option>
                                                        </select>
                                                    </form>
                                                </div>
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                                <?php 
                                $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                                $args = array(
                                    'post_type' => 'procurement_notice', // Custom post type
                                    'tax_query' => array(
                                        array(
                                           'taxonomy' => 'procurement_notice_tax', // Custom taxonomy
                                            'field'    => 'slug',
                                            'terms'    => 'procurement-notice'//get_queried_object()->slug, // Get the current term
                                        ),
                                    ),
                                    'paged' => $paged, // Pagination parameter
                                    'posts_per_page' => 10, // Number of posts per page
                                );
                                if ($year) {
                                    $args['year'] = $year;
                                }
                                // Add a meta_query for the custom field 'notice_type' if $noticeType is set
                                if (!empty($noticeType) && $noticeType !== 'All') {
                                    $args['meta_query'] = array(
                                        array(
                                            'key'     => 'notice_type', // Custom field key
                                            'value'   => $noticeType,   // Value from the URL parameter
                                            'compare' => '=',           // Comparison operator
                                        ),
                                    );
                                }
                                $query = new WP_Query($args);
                                if($query->have_posts()):  
                            ?>
                                <tbody>
                                    <?php
                                        while($query->have_posts()):
                                            $query->the_post();
                                    ?>
                                    <tr>
                                        <td style="border: 1px solid #dee2e6; padding:10px;">
                                            <?php echo get_field('tender_number');?></td>
                                        <td style="border: 1px solid #dee2e6; padding:10px;">
                                            <?php 
                                            if( get_field('notice_type') === 'Awarded'){ ?>
                                            <a class="tender-title" href="<?php echo get_field('pdf_one');?>"
                                                title="<?php the_title();?>"
                                                target="_blank"><?php echo the_excerpt();?></a>
                                            <?php }else { ?>
                                            <a class="tender-title" href="<?php the_permalink();?>"
                                                title="<?php the_title();?>"><?php echo the_excerpt();?></a><?php } ?>
                                        </td>
                                        <td style="border: 1px solid #dee2e6; padding:10px;">
                                            <?php echo get_field('procurement_method');?></td>
                                        <td style="border: 1px solid #dee2e6; text-align: center; padding:10px; min-width: 110px;">
                                            <?php echo get_the_date();?></td>
                                        <td style="border: 1px solid #dee2e6; text-align: center; padding:10px; min-width: 110px;">
                                            <?php echo !empty(get_field('close_date')) ? get_field('close_date') : "-"   ;?>
                                        </td>
                                        <td style="border: 1px solid #dee2e6; text-align: center; padding:10px;">
                                            <?php echo get_field('notice_type');?></td>
                                    </tr>
                                    <?php 
                                    endwhile;
                                    wp_reset_postdata();
                                ?>
                                    <?php else: ?>
                                    <tr>
                                        <td colspan="6">
                                            There are currently no active tenders. You can subscribe to our News Feed
                                            for
                                            Employment and Tenders.
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- Pagination  -->
                        <div class="col-lg-12 col-md-12 mb-20 mt-30">
                            <nav aria-label="Page navigation ">
                                <?php 
                                //$big = 999999999;
                                $total_pages = $query->max_num_pages;
                                
                                $current_page = max(1, get_query_var('paged'));
                                custom_pagination($total_pages, $current_page);
                            ?>
                            </nav>
                        </div>
                        <!-- Pagination  -->
                    </div>
                </div>
                <!-- Sidebar -->
                <div class="col-lg-3 col-md-3">

                    <!-- Download to Excel Section -->
                    <div class="download-section ver-2">
                        <a href="<?php echo site_url('/download-procurement-notices'); ?>">
                            Download to Excel <i class="fa fa-file-excel-o"></i>
                        </a>
                    </div>
                    <!-- Filter Section -->
                    <div class="filter-section">
                        <h4><i class="fa fa-filter"></i> Filter</h4>
                        <label for="notice-type" class="form-label">Notice Type</label>
                        <form action="" method="get">
                            <select id="notice-type_" class="form-select" name="status">
                                <option value="All" <?php if($noticeType === 'All') echo 'selected';?>>All</option>
                                <option value="Open" <?php if($noticeType === 'Open') echo 'selected';?>>Open</option>
                                <option value="Awarded" <?php if($noticeType === 'Awarded') echo 'selected';?>>Awarded
                                </option>
                            </select>

                            <label for="year" class="form-label">Select Year</label>
                            <div class="widget-link">
                                <?php echo custom_year_dropdown('procurement_notice', 'procurement_notice_tax'); ?>
                            </div>
                            <!-- Apply Filter Button -->
                            <button class="filter-btn mb-3" type="submit">Apply Filters</button>
                        </form>
                    </div>

                    <!-- <div class="sidebar-widget mt-0 pt-0 mb-50 reading-box__yellow"> -->
                    <div class="card sidebar-widget mt-0 pt-0 mb-10 tender-sidebar">
                        <div class="section-title mb-2">
                            <h4 class="title pt-10 pl-10">General Information</h4>
                        </div>
                        <div class="recent-post clearfix">
                            <div>
                            <ul>
                                <li><a class="black-text"
                                        href="<?php echo home_url();?>/wp-content/uploads/2024/09/Consultant-Briefing-Kit.pdf"
                                        target="_blank" rel="noopener">Consultant briefing kit</a></li>
                                <li><a class="black-text"
                                        href="<?php echo home_url();?>/wp-content/uploads/2024/09/Due-Diligence-Guideline-1.pdf"
                                        target="_blank" rel="noopener">Due Diligence Guideline</a></li>
                                <li><a class="black-text"
                                        href="<?php echo home_url();?>/wp-content/uploads/2024/09/General-Procurement-Notice-2023.pdf"
                                        target="_blank" rel="noopener">General Procurement Notice 2023</a></li>
                                <li><a class="black-text"
                                        href="<?php echo home_url();?>/wp-content/uploads/2024/09/Info-exemption-of-taxation.pdf"
                                        target="_blank" rel="noopener">Info on tax exemption</a></li>
                                <li><a class="black-text"
                                        href="<?php echo home_url();?>/wp-content/uploads/2024/09/MRC-Procurement-Manual-Updated-Approval-by-53JC-26042022-Clean.pdf"
                                        target="_blank" rel="noopener">Procurement Manual</a></li>
                                <li><a class="black-text"
                                        href="<?php echo home_url();?>/wp-content/uploads/2024/09/PPD-03-Privacy-Statement.pdf"
                                        target="_blank" rel="noopener">PPD-03 Privacy Statement</a></li>
                                <li><a class="black-text"
                                        href="<?php echo home_url();?>/wp-content/uploads/2024/09/Supplier-Declaration-Form.pdf"
                                        target="_blank" rel="noopener">Supplier Declaration Form</a></li>
                                <li><a class="black-text" href="https://www.surveymonkey.com/r/ConsultantRoster"
                                        target="_blank" rel="noopener">MRC consultant roster</a></li>
                                <li><a class="black-text" href="/related-annexes/" target="_blank"
                                        rel="noopener">Related Annexes</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="card sidebar-widget mt-0 pt-0 mb-20 tender-sidebar">
                        <div class="section-title">
                            <h4 class="title pt-10 pl-10 mb-0">Procurement Contact</h4>
                        </div>
                        <div class="column">
                            <div style="margin: 8px;">
                                <!-- <h5>Mekong River Commission Secretariat (MRCS)</h5> -->
                                <ul>
                                    <li><i class="fas fa-map-pin"></i> P.O. Box 6101, 184 Fa Ngoum Road</li>
                                    <li><i class="fas fa-map-marker-alt"></i>Ban Sithane Neua, Sikhottabong District,
                                        Vientiane 01000, Lao PDR.</li>
                                    <li><i class="fas fa-phone-alt"></i>Tel: +856 (0) 21 263 263</li>
                                    <li><i class="fas fa-fax"></i>Fax: +856 (0) 21 263 264</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Sidebar -->
            </div>
        </div>
    </section>
    <!-- Scroll top -->
    <?php include 'scrollBtnPage.html';?>
    <!-- Scroll top -->
</div>
<!-- Latest compiled JavaScript -->
<script>
function toggleDropdown(event) {
    // Prevent other click events from closing it
    event.stopPropagation();

    // Toggle the dropdown menu visibility
    const dropdownMenu = event.currentTarget.nextElementSibling;
    dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';

    // Close dropdown if clicked outside
    document.addEventListener('click', function(event) {
        if (!event.target.closest('.dropdown-container')) {
            dropdownMenu.style.display = 'none';
        }
    });
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Latest compiled JavaScript -->
<?php get_footer(); ?>