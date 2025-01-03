<?php 
    /* template Name: Media Releases single */
    get_header();
    //--------custom css-----------
    wp_enqueue_style( 'custom-styling', get_stylesheet_directory_uri() . '/dynamic-page.css' );
?>
<!--Latest compiled and Bootstrap minified CSS  -->
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Latest compiled and Bootstrap minified CSS -->
 <style>
    .accordion-button {
    background-color: #f7f7f7;
    color: #333;
    font-weight: bold;
}

.accordion-button:focus {
    box-shadow: none;
}

.accordion-body {
    padding: 20px;
    background-color: #ffffff;
    border: 1px solid #ddd;
}

.accordion-item {
    border: 1px solid #ddd;
    border-radius: 5px;
    margin-bottom: 10px;
}
.accordion-button::after {
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-plus' viewBox='0 0 16 16'%3E%3Cpath d='M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z'/%3E%3C/svg%3E");
  transition: all 0.5s;
}
.accordion-button:not(.collapsed)::after {
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-dash' viewBox='0 0 16 16'%3E%3Cpath d='M4 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 4 8z'/%3E%3C/svg%3E");
}
.accordion-button:not(.collapsed)::after {
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-dash' viewBox='0 0 16 16'%3E%3Cpath d='M4 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 4 8z'/%3E%3C/svg%3E");
}
.accordion-button::after {
  transition: all 0.5s;;
}
.no-gutters {
    margin-right: 0;
    margin-left: 0;
}
//---------Iframe
.js-video {
    height: 0;
    padding-top: 25px;
    padding-bottom: 54%;
    position: relative;
    overflow: hidden;
}
.js-video iframe {
    //top: 0;
    left: 82px;
    width: 60%;
    height: 52%;
    position: absolute;
    border: none;
}
 </style>
<div id="main-layout"> 
    <div class="container-fluid maxhd p-0"  style="overflow: hidden;">
        <div class="row no-gutters">
            <?php if(get_field('show_slider')){
            ?>
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
            <?php  } ?>
        </div>
   </div>

   <!-- breadcrumb -->
   <?php // echo custom_breadcrumbs(); ?> 
   <div class=" breadcrumb gray-bg _theme-bg-50 d-none d-md-block" style="margin-bottom: 0;">
      <div class="container">
         <a href="/"> <svg width="12px" style="margin-bottom: 4px;" aria-hidden="true" class="e-font-icon-svg e-fas-long-arrow-alt-left" viewBox="0 0 448 512" xmlns="http://www.w3.org/2000/svg"><path d="M134.059 296H436c6.627 0 12-5.373 12-12v-56c0-6.627-5.373-12-12-12H134.059v-46.059c0-21.382-25.851-32.09-40.971-16.971L7.029 239.029c-9.373 9.373-9.373 24.569 0 33.941l86.059 86.059c15.119 15.119 40.971 4.411 40.971-16.971V296z"></path></svg> &nbsp; </a> <i class="fa fa-angle-double-right ms-2 me-2" aria-hidden="true" style="font-size: 10px;"></i>
         <a href="<?php echo home_url();?>">Home</a> <i class="fa fa-angle-double-right ms-2 me-2" aria-hidden="true" style="font-size: 10px;"></i> News and Events
         <i class="fa fa-angle-double-right ms-2 me-2" aria-hidden="true" style="font-size: 10px;"></i> Multimedia <i class="fa fa-angle-double-right ms-2 me-2" aria-hidden="true" style="font-size: 10px;"></i>
         <a href="<?php echo home_url();?>/photo-galleries/" class="breadcrumb-2"> Photo Galleries </a> <i class="fa fa-angle-double-right ms-2 me-2" aria-hidden="true" style="font-size: 10px;"></i>
         <?php the_title();?> <?php //the_title();?>
       </div>
   </div>
   <div class=" breadcrumb theme-bg-50 d-md-none" style="margin-bottom: 0;">
      <div class="container">
         <a href="javascript:history.back()"> <i class="fa  fa-long-arrow-left"></i> &nbsp;Back </a>
      </div>
   </div>
   <!-- breadcrumb -->
   <?php
        //echo $term_id = get_queried_object_id();
        // Define the group fields
        $accordion_groups = [];
        for ($i=1; $i <=10 ; $i++) { 
            array_push($accordion_groups,'accordion_group_'.$i);
        }

        while ( have_posts() ) :
            the_post();

            $post_id = get_the_ID();
            
            $terms = get_the_terms($post_id,'news_and_events_cat');
            $termsId = "";
            if(!empty($terms)){
                $termsId = $terms[0]->term_id;
            }
    ?>
   <section class="page-sidebar page-section-0-ptb">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 page-content">

                    <div class="section-title line-dabble mb-3">
                        <h3 class="theme-color title c-green"><?php the_title();?></h3>
                    </div>

                    <!-- Featured Image -->
                    <?php if(get_field('show_slider') ===false){ ?>
                    <div class="d-none">
                        <img class="img-fluid" src="<?php echo get_the_post_thumbnail_url();?>" alt="<?php the_title();?>">
                    </div>
                    <?php } ?>
                    <!-- Featured Image -->
                       
                    <!-- Social button -->
                    <div class="mb-10 d-none">
                        <span class="text-muted">Share:</span>
                        <!-- Sharingbutton Facebook -->
                        <a class="resp-sharing-button__link" href="https://facebook.com/sharer/sharer.php?u=<?=get_current_pageURL()?>" target="_blank" rel="noopener" aria-label="">
                            <div class="resp-sharing-button resp-sharing-button--facebook resp-sharing-button--small">
                                <div aria-hidden="true" class="resp-sharing-button__icon resp-sharing-button__icon--solidcircle">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <path d="M12 0C5.38 0 0 5.38 0 12s5.38 12 12 12 12-5.38 12-12S18.62 0 12 0zm3.6 11.5h-2.1v7h-3v-7h-2v-2h2V8.34c0-1.1.35-2.82 2.65-2.82h2.35v2.3h-1.4c-.25 0-.6.13-.6.66V9.5h2.34l-.24 2z"></path>
                                    </svg>
                                </div>
                            </div>
                        </a>
                        <!-- Sharingbutton Twitter -->
                        <a class="resp-sharing-button__link" href="https://twitter.com/intent/tweet/?text=<?= the_title() ?>&amp;url=<?=get_current_pageURL()?>" target="_blank" rel="noopener" aria-label="">
                            <div class="resp-sharing-button resp-sharing-button--twitter resp-sharing-button--small">
                                <div aria-hidden="true" class="resp-sharing-button__icon resp-sharing-button__icon--solidcircle">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z"/></svg>
                                </div>
                            </div>
                        </a>
                        <!-- Sharingbutton E-Mail -->
                        <a class="resp-sharing-button__link" href="mailto:mrcmedia@mrcmekong.org/?subject=<?= the_title() ?>&amp;body=<?=get_current_pageURL()?>" target="_self" rel="noopener" aria-label="">
                            <div class="resp-sharing-button resp-sharing-button--email resp-sharing-button--small">
                                <div aria-hidden="true" class="resp-sharing-button__icon resp-sharing-button__icon--solidcircle">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <path d="M12 0C5.38 0 0 5.38 0 12s5.38 12 12 12 12-5.38 12-12S18.62 0 12 0zm8 16c0 1.1-.9 2-2 2H6c-1.1 0-2-.9-2-2V8c0-1.1.9-2 2-2h12c1.1 0 2 .9 2 2v8z"></path>
                                    <path d="M17.9 8.18c-.2-.2-.5-.24-.72-.07L12 12.38 6.82 8.1c-.22-.16-.53-.13-.7.08s-.15.53.06.7l3.62 2.97-3.57 2.23c-.23.14-.3.45-.15.7.1.14.25.22.42.22.1 0 .18-.02.27-.08l3.85-2.4 1.06.87c.1.04.2.1.32.1s.23-.06.32-.1l1.06-.9 3.86 2.4c.08.06.17.1.26.1.17 0 .33-.1.42-.25.15-.24.08-.55-.15-.7l-3.57-2.22 3.62-2.96c.2-.2.24-.5.07-.72z"></path>
                                    </svg>
                                </div>
                            </div>
                        </a>
                        <!-- Sharingbutton LinkedIn -->
                        <a class="resp-sharing-button__link" href="https://www.linkedin.com/shareArticle?mini=true&amp;url=<?=get_current_pageURL()?>&amp;title=<?= the_title() ?>&amp;summary=<?= the_title() ?>&amp;source=<?=get_current_pageURL()?>" target="_blank" rel="noopener" aria-label="">
                            <div class="resp-sharing-button resp-sharing-button--linkedin resp-sharing-button--small">
                                <div aria-hidden="true" class="resp-sharing-button__icon resp-sharing-button__icon--solidcircle">
                                    <svg version="1.1" x="0px" y="0px" width="24px" height="24px" viewBox="0 0 24 24" enable-background="new 0 0 24 24" xml:space="preserve">
                                    <path d="M12,0C5.383,0,0,5.383,0,12s5.383,12,12,12s12-5.383,12-12S18.617,0,12,0z M9.5,16.5h-2v-7h2V16.5z M8.5,7.5 c-0.553,0-1-0.448-1-1c0-0.552,0.447-1,1-1s1,0.448,1,1C9.5,7.052,9.053,7.5,8.5,7.5z M18.5,16.5h-3V13c0-0.277-0.225-0.5-0.5-0.5 c-0.276,0-0.5,0.223-0.5,0.5v3.5h-3c0,0,0.031-6.478,0-7h3v0.835c0,0,0.457-0.753,1.707-0.753c1.55,0,2.293,1.12,2.293,3.296V16.5z"></path>
                                    </svg>
                                </div>
                            </div>
                        </a>
                        <!-- Sharingbutton WhatsApp -->
                        <a class="resp-sharing-button__link" href="https://api.whatsapp.com//send?text=<?=get_current_pageURL()?>" target="_blank" rel="noopener" aria-label="">
                            <div class="resp-sharing-button resp-sharing-button--whatsapp resp-sharing-button--small">
                                <div aria-hidden="true" class="resp-sharing-button__icon resp-sharing-button__icon--solidcircle">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="24" width="24" viewBox="0 0 24 24">
                                    <path d="m12 0c-6.6 0-12 5.4-12 12s5.4 12 12 12 12-5.4 12-12-5.4-12-12-12zm0 3.8c2.2 0 4.2 0.9 5.7 2.4 1.6 1.5 2.4 3.6 2.5 5.7 0 4.5-3.6 8.1-8.1 8.1-1.4 0-2.7-0.4-3.9-1l-4.4 1.1 1.2-4.2c-0.8-1.2-1.1-2.6-1.1-4 0-4.5 3.6-8.1 8.1-8.1zm0.1 1.5c-3.7 0-6.7 3-6.7 6.7 0 1.3 0.3 2.5 1 3.6l0.1 0.3-0.7 2.4 2.5-0.7 0.3 0.099c1 0.7 2.2 1 3.4 1 3.7 0 6.8-3 6.9-6.6 0-1.8-0.7-3.5-2-4.8s-3-2-4.8-2zm-3 2.9h0.4c0.2 0 0.4-0.099 0.5 0.3s0.5 1.5 0.6 1.7 0.1 0.2 0 0.3-0.1 0.2-0.2 0.3l-0.3 0.3c-0.1 0.1-0.2 0.2-0.1 0.4 0.2 0.2 0.6 0.9 1.2 1.4 0.7 0.7 1.4 0.9 1.6 1 0.2 0 0.3 0.001 0.4-0.099s0.5-0.6 0.6-0.8c0.2-0.2 0.3-0.2 0.5-0.1l1.4 0.7c0.2 0.1 0.3 0.2 0.5 0.3 0 0.1 0.1 0.5-0.099 1s-1 0.9-1.4 1c-0.3 0-0.8 0.001-1.3-0.099-0.3-0.1-0.7-0.2-1.2-0.4-2.1-0.9-3.4-3-3.5-3.1s-0.8-1.1-0.8-2.1c0-1 0.5-1.5 0.7-1.7s0.4-0.3 0.5-0.3z"></path>
                                    </svg>
                                </div>
                            </div>
                        </a>
                    </div>
                    <!-- Social button -->
                    <main role="main" class="main--section">
                        <div class="element org__mrcmekong__block__contentblock" id="e1472">
                            <section class="p-0 container">
                                <div class="row ">
                                    <div class="col-sm-12 ">
                                        <?php the_content();?>
                                    </div>
                                </div>
                            </section>
                        </div>
                        <div class="element org__mrcmekong__block__accordionblock" id="e1474">
                            <section class="p-0 container">
                                <div class="row ">
                                    <div class="col-sm-12 ">
                                        <div class="accordion gray plus-icon round mb-30" id="accordionExample">
                                            <?php  $k=1; 
                                                foreach ($accordion_groups as $group) { 
                                                    //echo $group .'_'.$k;
                                                    //$title = get_field($group . '_accordion_title', 'term_' . $term_id);
                                                    //$content = get_field($group . '_accordion_content', 'term_' . $term_id);
                                                    $content = $title="";
                                                    $accordion_header_id = 'headingOne_'.$k;
                                                    $accordion_collapse = 'collapse_'.$k;
                                                    $ac_tg = $group.'_accordion_title_'.$k;
                                                    $title = get_field($group.'_accordion_title_'.$k);
                                                    $content = get_field($group.'_accordion_content_'.$k);
                                                    $k++;
                                                    if ($title && $content) {
                                            ?>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="<?php echo $accordion_header_id;?>">
                                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#<?php echo $accordion_collapse;?>" aria-expanded="true" aria-controls="<?php echo $accordion_collapse;?>">
                                                    <?php echo $title;?>
                                                </button>
                                                </h2>
                                                <div id="<?php echo $accordion_collapse;?>" class="accordion-collapse collapse" aria-labelledby="<?php echo $accordion_header_id;?>" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <?php echo $content;?>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php } } ?>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                        <!-- Video block -->
                         <?php if(get_field('youtube_video_title') !="" && get_field('youtube_link') !="") { ?>
                        <div class="element org__mrcmekong__block__videoblock" id="e1346">
                            <section class="p-0 container">
                                <div class="row ">
                                    <div class="col-sm-12">
                                        <h3><?php echo get_field('youtube_video_title');?></h3>
                                    </div>
                                    <div class="col-sm-12 ">
                                        <div class="js-video [youtube, widescreen]">
                                            <iframe src="<?php echo get_field('youtube_link');?>" allowfullscreen="" width="100%"  data-ruffle-polyfilled=""></iframe>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 ">
                                    </div>
                                </div>
                            </section>
                        </div>
                        <?php } ?>
                        <!-- Video block -->
                    </main>
                </div>
                <!-- Side Bar -->
                <div class="col-lg-3 col-md-4 d-none">
                    <div class="sidebar-widget mt-0 pt-0 mb-50">
                        <div class="section-title line-dabble">
                            <h4 class="title c-green">Latest Media Releases</h4>
                        </div>
                        <?php
                            $args1 = array(
                                'post_type' => 'post', 
                                'orderby' => 'date',
                                'order' => 'desc',
                                'tax_query' => array(
                                    array(
                                        'taxonomy' => 'category',
                                        'field'    => 'term_id',
                                        'terms'    => 24
                                    ),
                                ),
                                'posts_per_page' => 3
                            );


                            $latest_posts = new WP_Query($args1);

                            if ($latest_posts->have_posts()) :
                                while ($latest_posts->have_posts()) : $latest_posts->the_post();
                        ?>
                        <div class="recent-post clearfix">
                            <div class="recent-post-image">
                                <img class="img-fluid " src="<?=get_the_post_thumbnail_url()?>" alt="" width="256" height="256">
                            </div>
                            <div class="recent-post-info">
                                <a href="<?=the_permalink()?>" title="Read more on &quot;<?=the_title()?>&quot;"><?=the_title()?></a>
                                <small><i class="fa fa-calendar-o"></i> <?=get_the_date()?>
                                </small>
                            </div>
                        </div>
                        <?php
                            endwhile;
                            wp_reset_postdata(); // Reset the global $post object
                        ?>
                        <div class="text-right">
                            <a href="/category/media-releases/">browse all media releases »</a>
                        </div>
                            <?php else :
                                    echo '<p>No recent posts found.</p>';
                                endif;
                            ?>
                    </div>
                    <div class="sidebar-widget mt-30 clearfix mb-40">
                        <div class="reading-box__green">
                            <h4 class="pt-10">Media Contact</h4>
                            <div>
                                <h6 class="fs-14">For media inquires, please contact:</h6>
                                <ul>
                                    <li>
                                    <a href="mailto:mrcmedia@mrcmekong.org?subject=website-media-contact">mrcmedia@mrcmekong.org</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <?php if(in_array($termsId,['74'])) { ?>
                        <div class="sidebar-widget mt-0 pt-0 mb-50">
                            <div class="section-title line-dabble">
                                <h4 class="title c-green">Latest Events</h4>
                            </div>
                            <div class="recent-post clearfix">
                                <div class="recent-post-image">
                                    <img class="img-fluid "
                                        src="/web/20230323195531im_/https://www.mrcmekong.org/assets/Photos/Plenary-4__FillWzI1NiwyNTZd.JPG"
                                        alt=""
                                        width="256"
                                        height="256">
                                            
                                </div>
                                <div class="recent-post-info">
                                    <a href="/web/20230323195531/https://www.mrcmekong.org/news-and-events/news/cooperation-opportunities-with-application-of-mrc-studies-guidelines-and-tools-for-hydropower-planning/"
                                    title="Read more on &quot;Cooperation opportunities with application of MRC studies, guidelines and tools for hydropower planning&quot;">Cooperation opportunities with application of MRC studies, guidelines and tools for hydropower planning</a>
                                    <small>
                                        <i class="fa fa-calendar-o">26 October 2016 </i>
                                    </small>
                                </div>
                            </div>
                            <div class="text-right">
                                <a href="#">browse all events »</a>
                            </div>
                        </div>
                    <?php }else {?>
                    <h6>Connect with us</h6>
                    <div class="social-small-magazine">
                        <ul>
                            <li class="facebook">
                                <a href="https://www.facebook.com/mrcmekong" target="_blank"><i class="fab fa-facebook-f"></i>
                                </a>
                            </li>
                            <li class="youtube">
                                <a href="http://www.youtube.com/mrcmekongorg" target="_blank"><i class="fab fa-youtube"></i>
                                </a>
                            </li>
                            <li class="twitter">
                                <a href="http://twitter.com/MRCMekong" target="_blank"><svg fill="white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z"></path></svg>
                                </a>
                            </li>
                            <li class="linkedin">
                                <a href="https://www.linkedin.com/company/mrcsecretariat" target="_blank"><i class="fab fa-linkedin"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>
    <?php 
       endwhile; // End of the loop.
   ?>
   </br>
   <div class=" breadcrumb gray-bg _theme-bg-50 " style="margin-bottom: 0;">
        <div class="container">
            <!-- <a href="javascript:history.back()"> <i class="fa  fa-long-arrow-left"></i> &nbsp;Back </a> -->
        </div>
   </div>
   <?php include 'scrollBtnPage.html';?>   
   <!-- Footer -->
        <!-- Latest compiled JavaScript -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Latest compiled JavaScript -->
        <?php get_footer(); ?>               
   <!-- Footer -->                  
</div>