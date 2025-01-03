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
            box-shadow: none;
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

        .cardhover table td {
            padding-left: 0 ;
        }

        .job-detail-table {
            width: 100%;
        }

        .job-detail-table th {
            text-align: left;
            font-size: 24px;
            padding-bottom: 10px;
        }
        .border-dash {
            border-bottom: 1.5px dashed #000;
            border-radius: 0;
        }
    </style>
<!--Latest compiled and Bootstrap minified CSS  -->
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Latest compiled and Bootstrap minified CSS -->
<div id="main-layout">
    <!-- breadcrumb -->
    <div class=" breadcrumb gray-bg _theme-bg-50 d-none d-md-block" style="margin-bottom: 0;">
      <div class="container">
        <a href="/"> <svg width="12px" style="margin-bottom: 4px;" aria-hidden="true" class="e-font-icon-svg e-fas-long-arrow-alt-left" viewBox="0 0 448 512" xmlns="http://www.w3.org/2000/svg"><path d="M134.059 296H436c6.627 0 12-5.373 12-12v-56c0-6.627-5.373-12-12-12H134.059v-46.059c0-21.382-25.851-32.09-40.971-16.971L7.029 239.029c-9.373 9.373-9.373 24.569 0 33.941l86.059 86.059c15.119 15.119 40.971 4.411 40.971-16.971V296z"></path></svg> &nbsp; </a> <i class="fa fa-angle-double-right ms-2 me-2" aria-hidden="true" style="font-size: 10px;"></i>
            <a href="<?php echo home_url();?>">Home</a> <i class="fa fa-angle-double-right ms-2 me-2" aria-hidden="true" style="font-size: 10px;"></i> 
            <a href="javascript:void(0);" class="breadcrumb-1">About</a> <i class="fa fa-angle-double-right ms-2 me-2" aria-hidden="true" style="font-size: 10px;"></i> 
            <a href="<?php echo home_url();?>/working_with_mrc/working-with-mrc/" class="breadcrumb-2"> Working with MRC </a> <i class="fa fa-angle-double-right ms-2 me-2" aria-hidden="true" style="font-size: 10px;"></i>
            <a href="<?php echo home_url();?>/employment/" class="breadcrumb-2"> MRC Job Vacancies </a><i class="fa fa-angle-double-right ms-2 me-2" aria-hidden="true" style="font-size: 10px;"></i> <?= the_title();?>
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
            $post_id = get_the_ID();
            $categories = get_the_category($post_id);
    ?>
    <section class="page-section-0-ptb employment_details">
        <div class="container">
            <div class="row">
                <div class="col-lg-9 col-md-8 ">
                    <div class="section-title line-dabble mb-15">
                        <h2 class="title c-green"><?= the_title();?></h2>
                    </div>
                    <?php if(get_field('short_description') && !empty(get_field('short_description'))) { ?>
                    <div class="mt-15 mb-30 mb-0">
                        <?= get_field('short_description') ;?>
                    </div>
                    <?php } ?>
                    <div class="cardhover mt-15 mb-0 pb-0" style="border: none;">
                        <?= the_content();?>
                    </div>

                    <?php if(get_field('qualifications_and_requirements') && !empty(get_field('qualifications_and_requirements'))) { ?>
                    <div class="cardhover mb-0 pb-0" style="border: none;">
                        <?= get_field('qualifications_and_requirements');?>
                    </div>
                    <?php } ?>

                    <?php if(get_field('application_procedures') && !empty(get_field('application_procedures'))) { ?>
                    <div class="cardhover mb-sm-2" style="border: none;">
                        <?=  get_field('application_procedures'); ?>
                    </div> <?php } ?>

                      <!-- More Information Start-->
                    <?php if(!empty(get_field('first_pdf')) || !empty(get_field('second_pdf')) || !empty(get_field('third_pdf'))){ ?>
                    <div class="col-md-12 tender-sidebar" style="border-color: #64A70B; padding-bottom: 1px;">
                        <h4 class="title pt-10 pl-10 mb-0 fw-6">More Information</h4>
                        <ul style="padding-left: 12px; padding-top: 12px;">
                            <?php if(get_field('first_pdf') && !empty(get_field('first_pdf'))) { ?>
                            <li><a class="black-text" href="<?php echo get_field('first_pdf');?>"
                                    target="_blank"><?php echo get_field('first_pdf_name'); ?></a></li> <?php } 
                            if(get_field('second_pdf') && !empty(get_field('second_pdf'))) { ?>
                            <li><a class="black-text" href="<?php echo get_field('second_pdf');?>"
                                    target="_blank"><?php echo get_field('second_pdf_name'); ?></a></li> <?php } 
                            if(get_field('third_pdf') && !empty(get_field('third_pdf'))) { ?>
                            <li><a class="black-text" href="<?php echo get_field('third_pdf');?>"
                                    target="_blank"><?php echo get_field('third_pdf_name'); ?></a></li> <?php } ?>
                        </ul>
                    </div>
                    <?php } ?>
                    <!-- More Information End-->

                </div>
                <!-- Side bar -->
                <div class="col-lg-3 col-md-4">
                <!-- <div class="sidebar-widget mt-0 pt-0 mb-50 reading-box__yellow"> -->
                    <div class="card sidebar-widget pt-0 mb-10 pl-0 pr-0 cardboxShadow pb-0"  style="background-color: #F5F5F5;">
                        <div style="border-top: 3px solid #D66400;">
                            <h4 class="title pt-10 pl-10 mb-0" style="font-size: 21px;">General Information</h4>
                        </div>
                        <div class="recent-post clearfix">
                            <div>
                                <ul style="padding-left: 14px;">
                                    <li><a class="black-text" href="<?php echo home_url();?>/wp-content/uploads/2024/09/Supplier-Declaration-Form.pdf" target="_blank" rel="noopener">Supplier Declaration Form</a></li>
                                    <li><a class="black-text" href="<?php echo home_url();?>/wp-content/uploads/2024/09/PPD-03-Privacy-Statement.pdf" target="_blank" rel="noopener">PPD-03 Privacy Statement</a></li>
                                    <li><a class="black-text" href="<?php echo home_url();?>/wp-content/uploads/2024/09/Consultant-Briefing-Kit.pdf" target="_blank" rel="noopener">Consultant briefing kit</a></li>
                                    <li><a class="black-text" href="<?php echo home_url();?>/wp-content/uploads/2024/09/Info-exemption-of-taxation.pdf" target="_blank" rel="noopener">Info on tax exemption</a></li>
                                    <li><a class="black-text" href="https://www.surveymonkey.com/r/ConsultantRoster" target="_blank" rel="noopener">MRC consultant roster</a></li>
                                    <li><a class="black-text" href="<?php echo home_url();?>/wp-content/uploads/2024/09/General-Procurement-Notice-2023.pdf" target="_blank" rel="noopener">General Procurement Notice 2023</a></li>
                                    <li><a class="black-text" href="<?php echo home_url();?>/wp-content/uploads/2024/09/Due-Diligence-Guideline-1.pdf" target="_blank" rel="noopener">Due Diligence Guideline</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card sidebar-widget mt-0 pt-0 mb-0 cardboxShadow  pl-0 pr-0 pb-0"  style="background-color: #F5F5F5; border-radius: 0;">
                        <!-- <h3 class=" head" style="background-color: #D6D5CD; color: #555555;">Mekong River Commission Secretariat Contacts</h3> -->
                        <div style="border-top: 3px solid #D66400;">
                            <h4 class="title pt-10 pl-10" style="font-size: 21px;">Mekong River Commission Secretariat Contacts</h4>
                        </div>
                        <div class="column pl-10 pr-10">
                            <div class="border-dash pb-1">
                                <!-- <h5>Mekong River Commission Secretariat (MRCS)</h5> -->
                                <h5>Mekong River Commission Secretariat Headquarters | Vientiane</h5>
                                <p>P.O. Box 6101, 184 Fa Ngoum Road Ban Sithane Neua, Sikhottabong District,
                                    Vientiane 01000, Lao PDR. <br>
                                    Email: <a href="mailto:mrcshr@mrcmekong.org">mrcshr@mrcmekong.org </a><br>
                                    Tel: +856 (0) 21 263 263 <br>
                                    Fax: +856 (0) 21 263 264 </p>
                            </div>
                        </div>
                    </div>
                    <div class="card sidebar-widget mt-0 pt-0 mb-10 cardboxShadow pl-0 pr-0 pb-0"  style="background-color: #F5F5F5; border-radius: 0;">
                        <!-- <h3 class=" head" style="background-color: #D6D5CD; color: #555555;">Mekong River Commission Secretariat Contacts</h3> -->
                        <div class="column pl-10">
                        <div>
                                <h5 style="margin-top:12px;">MRC Regional Flood and Drought Management Centre | Phnom Penh</h5>
                                <p> P.O. Box 623, 576 National<br> Road # 2, Sangkat Chak Angre Krom, Khan Menachey, <br>Phnom Penh,
                                    Cambodia<br>
                                    Email: <a href="mailto:mrcshr@mrcmekong.org">mrcshr@mrcmekong.org </a><br>
                                    Tel: +855 (0) 23 425 353, <br>
                                    Fax: +855 (0) 23 425 363 </p>
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