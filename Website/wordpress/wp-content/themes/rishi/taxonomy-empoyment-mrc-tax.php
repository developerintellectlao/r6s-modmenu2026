<?php
    /* template Name: empoyment-mrc  Listing */
    get_header();
    //--------custom css-----------
   wp_enqueue_style( 'custom-styling', get_stylesheet_directory_uri() . '/dynamic-page.css' );
    
   function get_custom_date($d){
    $date = DateTime::createFromFormat('d/m/Y', $d);
 
    if ($date) {
        return $date->format('d M Y'); // Outputs: 31 Aug 2024
    } else {
        return '';
    }
   }
   /**
    * [term_id] => 105
    * [name] => MRC Job Vacancies
    * [slug] => mrc-job-vacancies
    * [term_group] => 0
    * [term_taxonomy_id] => 105
    * [taxonomy] => empoyment-mrc-tax
    * [parent] => 0
    * [count] => 0
    * [filter] => raw
    */
    //$cate_term = get_queried_object();
    // $cate_term = get_terms(array(
    //     'post_type' => 'mrc-job-vacancies',
    //     'taxonomy'   => 'empoyment-mrc-tax',//
    //     'hide_empty' => false, // Hide empty categories
    //     'parent'     => 0,    // Start with top-level terms
    //     //'exclude'    => $exclude_terms, // Exclude 'interactive' and 'example-category'
    // ));
    $cate_term = get_term_by('slug', 'mrc-job-vacancies', 'empoyment-mrc-tax');//get_queried_object();
    $cate_term_id = $cate_slug = $cate_name = $cate_desc = "";
   
   if ($cate_term && !is_wp_error($cate_term)) {
        $cate_term_id = $cate_term->term_id; // This will output "climate-change"
        $cate_slug = $cate_term->slug; // This will output "climate-change"
        $cate_name = $cate_term->name; 
        $cate_desc = $cate_term->description;
   }

    // if (isset($_GET['post_year']) && !empty($_GET['post_year'])) {
    //     $year = intval($_GET['post_year']);
    // }
    $vacancy_type = !empty($_GET['vacancy_type']) ? sanitize_text_field($_GET['vacancy_type']) : '';
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
    width: 100%;
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

.form-select{
    border: none;
}

.custom-select:focus,
.form-select:focus {
    border: none;
    border-bottom: 1px solid #ccc;
    box-shadow: none;
    outline: none;
}
        .border-dash {
            border-bottom: 1.5px dashed #000;
            border-radius: 0;
        }
</style>
<!-- Latest compiled and Bootstrap minified CSS -->
<div id="main-layout">
    <!-- Slider  -->
    <div class="container-fluid p-0" style="overflow: hidden;">
        <div class="row no-gutters">
            <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
                <!-- <div class="carousel-indicators">
                    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
                    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
                </div> -->
                <?php /* foreach( $sliderImages as $image ) { */?>
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="<?php echo home_url();?>/wp-content/uploads/2024/08/MRC_employment.jpeg"
                            class="d-block w-100" alt="...">
                    </div>
                </div>
                <?php/* }*/ ?>
            </div>
        </div>
    </div>
    <!-- Slider -->

    <!-- breadcrumb -->
    <div class=" breadcrumb gray-bg _theme-bg-50 d-none d-md-block" style="margin-bottom: 0;">
        <div class="container">
        <a href="/"> <svg width="12px" style="margin-bottom: 4px;" aria-hidden="true" class="e-font-icon-svg e-fas-long-arrow-alt-left" viewBox="0 0 448 512" xmlns="http://www.w3.org/2000/svg"><path d="M134.059 296H436c6.627 0 12-5.373 12-12v-56c0-6.627-5.373-12-12-12H134.059v-46.059c0-21.382-25.851-32.09-40.971-16.971L7.029 239.029c-9.373 9.373-9.373 24.569 0 33.941l86.059 86.059c15.119 15.119 40.971 4.411 40.971-16.971V296z"></path></svg> &nbsp; </a> <i class="fa fa-angle-double-right ms-2 me-2" aria-hidden="true" style="font-size: 10px;"></i>
            <a href="<?php echo home_url();?>">Home</a> <i class="fa fa-angle-double-right ms-2 me-2" aria-hidden="true" style="font-size: 10px;"></i>
            <a href="#" class="breadcrumb-1">About</a> <i class="fa fa-angle-double-right ms-2 me-2" aria-hidden="true" style="font-size: 10px;"></i>
            <a href="<?php echo home_url();?>/working_with_mrc/working-with-mrc/" class="breadcrumb-2"> Working with MRC </a> <i class="fa fa-angle-double-right ms-2 me-2" aria-hidden="true" style="font-size: 10px;"></i> Employment
        </div>
    </div>
    <div class=" breadcrumb theme-bg-50 d-md-none" style="margin-bottom: 0;">
        <div class="container">
            <a href="javascript:history.back()"> <i class="fa  fa-long-arrow-left"></i> &nbsp;Back </a>
        </div>
    </div>
    <!-- breadcrumb -->
    <section class="page-section-0-ptb">
        <div class="container">
            <div class="row">
                <div class="col-lg-9 col-md-9 ">
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="section-title line-dabble">
                                <h2 class="title c-green"><?php echo $cate_name;?></h2>
                            </div>
                            <div class="mt-0 mb-16 mb-sm-2">
                                <?php echo $cate_desc;?>
                            </div>
                        </div>
                        <?php
                            // Fetch child terms
                            $child_terms = get_terms(array(
                                'taxonomy'   => 'empoyment-mrc-tax',
                                'hide_empty' => false,
                                'parent'     => $cate_term_id,
                            ));
                        ?>
                        <div style="overflow: auto;">
                            <table class="table table-striped table-1 table-sm procurement-table"
                                style="width: calc(100% - 2px); border-radius: 4px; margin-left: 2px;">
                                <thead>
                                    <tr>
                                        <th style="font-size: 16px; font-weight:600; min-width: 140px;">Position Title
                                        </th>
                                        <th
                                            style="font-size: 16px; font-weight:600; min-width: 120px; border-left: 1px solid #FFF;">
                                            Division</th>
                                        <th
                                            style="font-size: 16px; font-weight:600; min-width: 80px; text-align:center; border-left: 1px solid #FFF;">
                                            <div class="dropdown-container">
                                                <button class="dropdown-toggle" onclick="toggleDropdown(event)">
                                                    Vacancies Type
                                                </button>
                                                <div class="dropdown-menu">
                                                    <form action="" method="get">
                                                        <select id="notice-type" class="form-select" name="vacancy_type"
                                                            onchange="this.form.submit();">
                                                            <option value=""
                                                                <?php if($vacancy_type === '') echo 'selected';?>>All
                                                            </option>
                                                            <?php if (!empty($child_terms)) {
                                                                    foreach ($child_terms as $chtr) {?>
                                                                    <option value="<?php echo $chtr->term_id;?>"
                                                                        <?php if((int)$vacancy_type === $chtr->term_id) echo 'selected';?>>
                                                                        <?php echo $chtr->name;?>
                                                                    </option>
                                                            <?php } }?>
                                                        </select>
                                                    </form>
                                                </div>
                                            </div>
                                        </th>
                                        <th
                                            style="font-size: 16px; font-weight:600; min-width: 120px; text-align:center; border-left: 1px solid #FFF;">
                                            Open Date</th>
                                        <th
                                            style="font-size: 16px; font-weight:600; min-width: 100px; text-align:center; border-left: 1px solid #FFF;">
                                            Close Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

                                    if (!empty($child_terms)) {
                                        foreach ($child_terms as $cht) {
                                            if ($vacancy_type && $cht->term_id !== (int)$vacancy_type) {
                                                continue; // Skip non-matching terms
                                            }
                                            
                                            // Query posts for each child term
                                            $args = array(
                                                'post_type'      => 'empoyment-mrc', // Custom post type
                                                'tax_query'      => array(
                                                    array(
                                                        'taxonomy' => 'empoyment-mrc-tax', // Custom taxonomy
                                                        'field'    => 'term_id',
                                                        'terms'    => $cht->term_id,
                                                    ),
                                                ),
                                                'paged'          => $paged, // Pagination parameter
                                                'posts_per_page' => 10,    // Number of posts per page
                                            );
                                            
                                            $query = new WP_Query($args);

                                            if ($query->have_posts()) {
                                                while ($query->have_posts()) {
                                                    $query->the_post(); ?>
                                                    <tr>
                                                        <!-- Add data for each column -->
                                                        <td style="border: 1px solid #dee2e6; padding:10px;">
                                                        <a class="tender-title" href="<?php the_permalink();?>" title="<?php the_title();?>"> <?php the_title(); ?>  </a> 
                                                        </td>
                                                        <td style="border: 1px solid #dee2e6; padding:10px;">
                                                            <?php echo get_field('sub_heading'); ?>
                                                        </td>
                                                        <td style="border: 1px solid #dee2e6; padding:10px;">
                                                            <?php echo $cht->name; ?>
                                                        </td>
                                                        <td style="border: 1px solid #dee2e6; text-align: center; padding:10px; min-width: 110px;">
                                                            <?php echo get_the_date(); ?>
                                                        </td>
                                                        <td style="border: 1px solid #dee2e6; text-align: center; padding:10px; min-width: 110px;">
                                                            <?php echo !empty(get_field('closing_date')) ? esc_html(get_field('closing_date')) : "-"; ?>
                                                        </td>
                                                    </tr>
                                                <?php }
                                            } elseif(!empty($vacancy_type)) { ?>
                                                <tr>
                                                    <td colspan="6" style="text-align: center; padding:10px;">
                                                        No vacancies available for <?php echo esc_html($cht->name); ?> at the moment.
                                                    </td>
                                                </tr>
                                            <?php }
                                            wp_reset_postdata();
                                        }
                                    } else { ?>
                                        <tr>
                                            <td colspan="6" style="text-align: center; padding:10px;">
                                                No vacancies available at the moment.
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- Side bar -->
                <div class="col-lg-3 col-md-4">
                    <!-- <div class="sidebar-widget mt-0 pt-0 mb-50 reading-box__yellow"> -->
                    <div class="card sidebar-widget pt-0 mb-10 cardboxShadow" style="background-color: #F5F5F5;">
                        <div style="border-top: 3px solid #D66400;">
                            <h4 class="title pt-10 pl-10" style="font-size: 21px;">General Information</h4>
                        </div>
                        <div class="recent-post clearfix">
                            <div>
                                <ul style="padding-left: 18px;">
                                    <li><a class="black-text"
                                            href="<?php echo home_url();?>/wp-content/uploads/2024/09/Supplier-Declaration-Form.pdf"
                                            target="_blank" rel="noopener">Supplier Declaration Form</a></li>
                                    <li><a class="black-text"
                                            href="<?php echo home_url();?>/wp-content/uploads/2024/09/PPD-03-Privacy-Statement.pdf"
                                            target="_blank" rel="noopener">PPD-03 Privacy Statement</a></li>
                                    <li><a class="black-text"
                                            href="<?php echo home_url();?>/wp-content/uploads/2024/09/Consultant-Briefing-Kit.pdf"
                                            target="_blank" rel="noopener">Consultant briefing kit</a></li>
                                    <li><a class="black-text"
                                            href="<?php echo home_url();?>/wp-content/uploads/2024/09/Info-exemption-of-taxation.pdf"
                                            target="_blank" rel="noopener">Info on tax exemption</a></li>
                                    <li><a class="black-text" href="https://www.surveymonkey.com/r/ConsultantRoster"
                                            target="_blank" rel="noopener">MRC consultant roster</a></li>
                                    <li><a class="black-text"
                                            href="<?php echo home_url();?>/wp-content/uploads/2024/09/General-Procurement-Notice-2023.pdf"
                                            target="_blank" rel="noopener">General Procurement Notice 2023</a></li>
                                    <li><a class="black-text"
                                            href="<?php echo home_url();?>/wp-content/uploads/2024/09/Due-Diligence-Guideline-1.pdf"
                                            target="_blank" rel="noopener">Due Diligence Guideline</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card sidebar-widget mt-0 pt-0 mb-0 cardboxShadow" style="background-color: #F5F5F5; border-radius: 0;">
                        <!-- <h3 class=" head" style="background-color: #D6D5CD; color: #555555;">Mekong River Commission Secretariat Contacts</h3> -->
                        <div style="border-top: 3px solid #D66400;">
                            <h4 class="title pt-10 pl-10" style="font-size: 21px;">Mekong River Commission Secretariat
                                Contacts</h4>
                        </div>
                        <div class="column pl-10 pr-10">
                            <div class="border-dash">
                                <!-- <h5>Mekong River Commission Secretariat (MRCS)</h5> -->
                                <h5>Mekong River Commission Secretariat Headquarters | Vientiane</h5>
                                <p style="margin-bottom:12px;">P.O. Box 6101, 184 Fa Ngoum Road Ban Sithane Neua,
                                    Sikhottabong District,
                                    Vientiane 01000, Lao PDR. <br>
                                    Email: <a href="mailto:mrcshr@mrcmekong.org">mrcshr@mrcmekong.org </a><br>
                                    Tel: +856 (0) 21 263 263 <br>
                                    Fax: +856 (0) 21 263 264 </p>
                            </div>
                        </div>
                    </div>
                    <div class="card sidebar-widget mt-0 pt-0 mb-10 cardboxShadow" style="background-color: #F5F5F5; border-radius: 0;">
                        <!-- <h3 class=" head" style="background-color: #D6D5CD; color: #555555;">Mekong River Commission Secretariat Contacts</h3> -->
                        <div class="column pl-10">
                            <div>
                                <h5 style="margin-top:12px;">MRC Regional Flood and Drought Management Centre | Phnom
                                    Penh</h5>
                                <p> P.O. Box 623, 576 National<br> Road # 2, Sangkat Chak Angre Krom, Khan Menachey,
                                    <br>Phnom Penh,
                                    Cambodia<br>
                                    Email: <a href="mailto:mrcshr@mrcmekong.org">mrcshr@mrcmekong.org </a><br>
                                    Tel: +855 (0) 23 425 353, <br>
                                    Fax: +855 (0) 23 425 363
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- MRC Job Vacancies -->
                    <!-- <div class="card sidebar-widget mt-0 pt-0 mb-10 cardboxShadow">
                        <h3 class=" head" style="background-color: #D6D5CD; color: #555555;">Mekong River Commission Secretariat Contacts</h3>
                        <div class="section-title mb-2">
                            <h4 class="title pt-10 pl-10">MRC Job Vacancies</h4>
                        </div>
                        <div class="column">
                            <div style="margin: 8px;">
                                <h5>Mekong River Commission Secretariat (MRCS)</h5>
                                <h5>Consultant</h5>
                                <ul class="pl-4">
                                    <li><a class="black-text" href="<?php echo home_url();?>/wp-content/uploads/2024/09/Supplier-Declaration-Form.pdf" target="_blank" rel="noopener">Supplier Declaration Form</a></li>
                                    <li><a class="black-text" href="<?php echo home_url();?>/wp-content/uploads/2024/09/PPD-03-Privacy-Statement.pdf" target="_blank" rel="noopener">Supplier Declaration Form</a></li>
                                </ul>
                                <h5>Support Staff</h5>
                                <ul class="pl-4">
                                    <li><a class="black-text" href="<?php echo home_url();?>/wp-content/uploads/2024/09/Supplier-Declaration-Form.pdf" target="_blank" rel="noopener">Auditor</a></li>
                                </ul>
                            </div>
                        </div>
                    </div> -->
                </div>
                <!-- Side bar -->
            </div>
        </div>
    </section>
    <?php include 'scrollBtnPage.html';?>
</div>
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
<!-- Latest compiled JavaScript -->
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> -->

<!-- Latest compiled JavaScript -->
<?php get_footer(); ?>