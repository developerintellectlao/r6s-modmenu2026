<?php 
    /* template Name: empoyment-mrc  Listing */
    get_header();
    //--------custom css-----------
   wp_enqueue_style( 'custom-styling', get_stylesheet_directory_uri() . '/dynamic-page.css' );
    
   $category = get_category_by_slug('mrc-job-vacancies');
   $cate_term_id = $category_name = $category_desc ="";
   if ($category && !is_wp_error($category)) {
       $cate_term_id = $category->term_id; // This will output "climate-change"
       $category_name = $category->name;
       $category_desc = $category->description;
   }
?>
<style>
.card {
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    padding: 20px;
    margin-bottom: 20px;
}

.card h1 {
    font-size: 24px;
    margin-bottom: 10px;
}

.card h2 {
    font-size: 18px;
    margin-bottom: 15px;
}

.card p {
    margin: 5px 0;
    line-height: 1.6;
}

.card ul {
    margin: 10px 0;
    padding-left: 20px;
}

.card ul li {
    margin-bottom: 8px;
    line-height: 1.5;
}

.card a {
    color: #007bff;
    text-decoration: none;
}

.card a:hover {
    text-decoration: underline;
}

/* .section-title {
            font-size: 18px;
            margin-bottom: 10px;
            font-weight: bold;
        } */

.job-detail-table {
    width: 100%;
}

.job-detail-table th {
    text-align: left;
    font-size: 24px;
    padding-bottom: 10px;
}
.card.card-ver2{
    border: none;
    box-shadow: none;
}
table td {
    padding: 10px 0 0 0;
}
</style>
<!--Latest compiled and Bootstrap minified CSS  -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Latest compiled and Bootstrap minified CSS -->
<div id="main-layout">
    <!-- breadcrumb -->
    <div class=" breadcrumb gray-bg _theme-bg-50 d-none d-md-block" style="margin-bottom: 0;">
        <div class="container">
            <a href=""> <i class="fa  fa-long-arrow-left"></i> &nbsp; </a> »
            <a href="<?php echo home_url();?>">Home</a> »
            <a href="#" class="breadcrumb-1">About</a> »
            <a href="<?php echo home_url();?>/working_with_mrc/working-with-mrc/" class="breadcrumb-2"> Working with MRC
                » </a>
            <a href="<?php echo home_url();?>/procurement_notice_tax/procurement-notice/" class="breadcrumb-2">
                Procurement Notices</a> <?php //the_title();?>
        </div>
    </div>
    <div class=" breadcrumb theme-bg-50 d-md-none" style="margin-bottom: 0;">
        <div class="container">
            <a href="javascript:history.back()"> <i class="fa  fa-long-arrow-left"></i> &nbsp;Back </a>
        </div>
    </div>
    <!-- breadcrumb -->
    <?php 
         while ( have_posts() ) :
            the_post();
            //$post_id = get_the_ID();
            //$categories = get_the_category($post_id);
    ?>
    <section class="page-section-0-ptb employment_details">
        <div class="container">
            <div class="row">
                <div class="col-lg-9 col-md-8 ">
                    <div class="section-title line-dabble mb-15">
                        <h2 class="title c-green"><?php the_title();?></h2>
                    </div>
                    <div class="mt-15 mb-40 mb-sm-2 card-ver2">
                        <?php echo the_content();?>
                    </div>

                    <!-- More Information Start-->
                    <?php if(!empty(get_field('pdf_one')) || !empty(get_field('pdf_two')) || !empty(get_field('pdf_three'))){ ?>
                    <div class="col-md-12 tender-sidebar" style="border-color: #64A70B; padding-bottom: 1px;">
                        <h4 class="title pt-10 pl-10 mb-0 fw-6">More Information</h4>
                        <ul style="padding-left: 12px; padding-top: 12px;">
                            <?php if(get_field('pdf_one') && !empty(get_field('pdf_one'))) { ?>
                            <li><a class="black-text" href="<?php echo get_field('pdf_one');?>"
                                    target="_blank"><?php echo get_field('first_pdf_name'); ?></a></li> <?php } 
                            if(get_field('pdf_two') && !empty(get_field('pdf_two'))) { ?>
                            <li><a class="black-text" href="<?php echo get_field('pdf_two');?>"
                                    target="_blank"><?php echo get_field('second_pdf_name'); ?></a></li> <?php } 
                            if(get_field('pdf_three') && !empty(get_field('pdf_three'))) { ?>
                            <li><a class="black-text" href="<?php echo get_field('pdf_three');?>"
                                    target="_blank"><?php echo get_field('third_pdf_name'); ?></a></li> <?php } ?>
                        </ul>
                    </div>
                    <?php } ?>
                    <!-- More Information End-->
                </div>
                <!-- Sidebar -->
                <div class="col-lg-3 col-md-4" style="padding-top: 44px">
                    <div class="sidebar-widget mt-0 pt-0 pb-20 tender-sidebar">
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

                    <!-- <div class="sidebar-widget mt-0 pt-0 mb-50 reading-box__yellow"> -->
                    <div class="sidebar-widget mt-0 pt-0 mb-10 tender-sidebar pb-2">
                        <div class="section-title">
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
                </div>
                <!-- Sidebar -->
            </div>
        </div>
    </section>
    <?php 
       endwhile; // End of the loop.
   ?>
    <!-- Scroll top -->
    <?php include 'scrollBtnPage.html';?>
    <!-- Scroll top -->
</div>

<!-- Latest compiled JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Latest compiled JavaScript -->
<?php get_footer(); ?>