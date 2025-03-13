<?php 
    
    /* template Name: working_with_mrc_  Listing */
    get_header();
    //--------custom css-----------
   wp_enqueue_style( 'custom-styling', get_stylesheet_directory_uri() . '/dynamic-page.css' );
    
   /*$cate_term = get_queried_object();
   $cate_term_id = $cate_slug = $cate_name = $cate_desc = "";
   
   if ($cate_term && !is_wp_error($cate_term)) {
        $cate_term_id = $cate_term->term_id; // This will output "climate-change"
        $cate_slug = $cate_term->slug; // This will output "climate-change"
        $cate_name = $cate_term->name; 
        $cate_desc = $cate_term->description;
   }*/
   while ( have_posts() ) :
    the_post();
    $post_id = get_the_ID();
?>

<!--Latest compiled and Bootstrap minified CSS  -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" /> -->
<!-- Latest compiled and Bootstrap minified CSS -->
<div id="main-layout">
    <!-- Slider  -->
    <div class="container-fluid maxhd p-0"  style="overflow: hidden;">
        <div class="row no-gutters">
            <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
                <!-- <div class="carousel-indicators">
                    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
                    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
                </div> -->
                <?php // foreach( $sliderImages as $image ) { ?>
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="<?php echo get_the_post_thumbnail_url();?>" class="d-block w-100" alt="...">
                    </div>
                </div>
                <?php// } ?>
            </div>
        </div>
    </div>
    <!-- Slider -->

    <!-- breadcrumb -->
    <div class=" breadcrumb gray-bg _theme-bg-50 d-none d-md-block" style="margin-bottom: 0;">
        <div class="container">
            <a href=""> <i class="fa  fa-long-arrow-left"></i> &nbsp; </a> »
            <a href="<?php echo home_url();?>">Home</a> » 
            <a href="#" class="breadcrumb-1">About</a> » 
            <a href="#" class="breadcrumb-2"> Working with MRC » </a>
        </div>
    </div>
    <div class=" breadcrumb theme-bg-50 d-md-none" style="margin-bottom: 0;">
        <div class="container">
            <a href="javascript:history.back()"> <i class="fa  fa-long-arrow-left"></i> &nbsp;Back </a>
        </div>
    </div>
    <!-- breadcrumb -->
   
    <section class="page-section-0-ptb employment_list">
        <div class="container">
            <div class="row">
                <div class="col-lg-9 col-md-8 ">
                    <div class="section-title line-dabble mb-15">
                        <h2 class="title c-green"><?php the_title();?></h2>
                    </div>
                    <div class="mt-15 mb-40 mb-sm-2">
                        <?php //echo $cate_desc;?>
                    </div>
                    <div class="container mt-5">
                        <?php the_content();?>
                    </div>
                </div>
                <!-- Side bar -->
                <div class="col-lg-3 col-md-4">
                    <div class="sidebar-widget mt-0 pt-0 mb-50 reading-box__yellow">
                        <div class="section-title mb-0">
                            <h4 class="title pt-10">General Information-</h4>
                        </div>
                        <div class="recent-post clearfix">
                            <div>
                                <ul>
                                    <li><a href="<?php echo home_url();?>/wp-content/uploads/2024/08/DDG-01-Supplier-Declaration-Form-v2.pdf" target="_blank" rel="noopener">Supplier Declaration Form</a></li>
                                    <li><a href="<?php echo home_url();?>/wp-content/uploads/2024/08/MRC-PPD.pdf" target="_blank" rel="noopener">PPD-03 Privacy Statement</a></li>
                                    <li><a href="<?php echo home_url();?>/wp-content/uploads/2024/08/Consultant-Briefing-Kit.pdf" target="_blank" rel="noopener">Consultant briefing kit</a></li>
                                    <li><a href="<?php echo home_url();?>/wp-content/uploads/2024/08/Information-exemption-of-taxation.pdf" target="_blank" rel="noopener">Info on tax exemption</a></li>
                                    <li><a href="https://www.surveymonkey.com/r/ConsultantRoster" target="_blank" rel="noopener">MRC consultant roster</a></li>
                                    <li><a href="<?php echo home_url();?>/wp-content/uploads/2024/08/General-Procurement-Notice-2022-for-individual-consultant.pdf" target="_blank" rel="noopener">General Procurement Notice 2022</a></li>
                                    <li><a href="<?php echo home_url();?>/wp-content/uploads/2024/08/Due-Diligence-Guideline-Revised-2023.pdf" target="_blank" rel="noopener">Due Diligence Guideline</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="sidebar-widget mt-0 pt-0 mb-50">
                        <h3 class=" head" style="background-color: #D6D5CD; color: #555555;">Mekong River Commission Secretariat Contacts</h3>
                        <div class="column pub-block">
                            <div style="margin:3px 3px 3px 10px;">
                                <h5>Mekong River Commission Secretariat (MRCS)</h5>
                                <p>P.O. Box 6101, 184 Fa Ngoum Road Ban Sithane Neua, Sikhottabong District,
                                    Vientiane 01000, Lao PDR. <br>
                                    Tel: +856 (0) 21 263 263 <br>
                                    Fax: +856 (0) 21 263 264 </p>

                                <h5>MRC Regional Flood and Drought Management Centre (RFDMC)</h5>
                                <p> P.O. Box 623, 576 National<br> Road # 2, Sangkat Chak Angre Krom, Khan Menachey, <br>Phnom Penh,
                                    Cambodia<br>
                                    Tel: +855 (0) 23 425 353, <br>
                                    Fax: +855 (0) 23 425 363 </p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Side bar -->
            </div>
        </div>
    </section>
    <?php include 'scrollBtnPage.html';?>    
</div>
<?php 
        endwhile; // End of the loop.
   ?>
<!-- Latest compiled JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Latest compiled JavaScript -->
<?php get_footer(); ?>