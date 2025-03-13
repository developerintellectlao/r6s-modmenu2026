<?php 
    /* template Name: Publications Listing */
    get_header();
    //--------custom css-----------
    wp_enqueue_style( 'custom-styling', get_stylesheet_directory_uri() . '/dynamic-page.css' );
?>
<!--Latest compiled and Bootstrap minified CSS  -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Latest compiled and Bootstrap minified CSS -->
 
<div id="main-layout"> 
    <div class="container-fluid maxhd p-0" style="overflow: hidden;">
      <div class="row no-gutters">
         <div class="owl-carousel blog-image owl-loaded owl-drag" data-nav-dots="false" data-autoheight="false" data-items="1" data-md-items="1" data-sm-items="1" data-xs-items="1" data-xx-items="1" data-space="0">
            <div class="owl-stage-outer">
               <div class="owl-stage"></div>
            </div>
            <!-- <div class="owl-nav disabled">
               <div class="owl-prev"><i class="fa fa-angle-left fa-2x"></i></div>
               <div class="owl-next"><i class="fa fa-angle-right fa-2x"></i></div>
            </div> -->
            <div class="owl-dots disabled"></div>
         </div>
      </div>
   </div>

   <!-- breadcrumb -->
   <?php echo custom_breadcrumbs(); ?> 
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
           // get_the_post_thumbnail_url();
            //get_field('upload_pdf_file');
           // get_template_part( 'single-publications-template', 'single' );
    ?>
   <div class="container pt-40 pb-40">
        <div class="row">
            <div class="container profile-container">
                <div class="row align-items-start">
                    <!-- Profile Image -->
                    <div class="col-md-3 text-center">
                        <div class="profile-image">
                            <img src="<?php echo get_the_post_thumbnail_url()?>" alt="<?php the_title();?>" class="img-fluid">
                        </div>
                    </div>
                    
                    <!-- Profile Details -->
                    <div class="col-md-9 mt-2 ps-md-3">
                        <h3 class="fw-bold"><?php the_title();?></h3>
                        <div class="profile-title"><?php echo get_field('sub_title')?></div>
                        <div class="profile-date mb-3"><?php echo get_field('duration')?></div>
                        <div class="profile-bio">
                        <?php the_content();?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
   </div>
   <?php 
        endwhile; // End of the loop.
   ?>
   <div class=" breadcrumb gray-bg _theme-bg-50 " style="margin-bottom: 0;">
        <div class="container">
            <!-- <a href="javascript:history.back()"> <i class="fa  fa-long-arrow-left"></i> &nbsp;Back </a> -->
        </div>
        <?php include 'scrollBtnPage.html';?>   
   </div>
   <!-- Footer -->
        <!-- Latest compiled JavaScript -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Latest compiled JavaScript -->
        <?php get_footer(); ?>               
   <!-- Footer -->                  
</div>