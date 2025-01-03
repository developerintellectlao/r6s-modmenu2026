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
            <!--<main role="main" class="main&#45;&#45;section">-->
            <div class="col-lg-12 col-md-12">
                <div class="row">
                    <div class="col-lg-12 col-md-12 _mb-50 sm-mb-30">
                        <div class="mb-0 pb-0">
                            <h1 class="h2 title "><?php the_title();?></h1>
                        </div>
                    </div>
                </div>
                <dl class="row bordered">
                <dt class="col-12 col-md-3 text-muted"> DOI</dt>
                <dd class="col-12 col-md-9 ">
                    <a href="<?php echo get_field('doi_url')?>" data-toggle="tooltip" title="" data-original-title="Please use this identifier to cite or link to this item"><?php echo get_field('doi')?></a>
                </dd>
                <dt class="col-12 col-md-3 text-muted">Title</dt>
                <dd class="col-12 col-md-9 "><?php the_title();?></dd>
                <dt class="col-12 col-md-3 text-muted">Author</dt>
                <dd class="col-12 col-md-9 ">MRC</dd>
                <dt class="col-12 col-md-3 text-muted ">Abstract</dt>
                <dd class="col-12 col-md-9 "><?php the_content();?></dd>
                <dt class="col-12 col-md-3 text-muted">Publication Date</dt>
                <dd class="col-12 col-md-9 ">
                    <time datetime="<?php echo get_the_date();?>" data-toggle="tooltip" title="" data-original-title="Publication date"><?php echo get_the_date();?>
                    </time>
                </dd>
                <dt class="col-12 col-md-3 text-muted">Publisher</dt>
                <dd class="col-12 col-md-9 "><?php echo get_field('publisher');?></dd>
                <dt class="col-12 col-md-3 text-muted">Language</dt>
                <dd class="col-12 col-md-9 "><?php echo get_field('language')?></dd>

                <dt class="col-12 col-md-3 text-muted">Categories</dt>
                <dd class="col-12 col-md-9 ">
                    <span><?php echo get_field('categories');?></span>
                </dd>
                <dt class="col-12 col-md-3 text-muted">Keywords</dt>
                <dd class="col-12 col-md-9 ">
                    <span><?php echo get_field('keywords'); ?></span>
                </dd>
                <dt class="col-12 col-md-3 text-muted">Access</dt>
                <dd class="col-12 col-md-9 ">
                    <span><?php echo get_field('access'); ?></span>
                </dd>
                <!-- <dt class="col-12 col-md-3 text-muted">Copyright</dt> -->
                <!-- <dd class="col-12 col-md-9 "><span><//?php echo get_field('copyright'); ?></span></dd> -->
                <!-- <dt class="col-12 col-md-3 text-muted">License</dt> -->
                <!-- <dd class="col-12 col-md-9 ">
                    <a rel="license" href="<//?php echo get_field('license')?>"><i class="fa fa-external-link"></i> Creative Commons Attribution–NonCommercial 4.0</a>
                </dd> -->
                <div class="col-12 p-0">
                    <table class="table table-sm">
                        <thead>
                            <tr class="">
                            <th style="border-bottom:0">File</th>
                            <th style="border-bottom:0">Size</th>
                            <th style="border-bottom:0"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                            <td>
                                <a class="filename" href="<?php echo get_field('upload_pdf_file');?>" download=""><?php echo get_fileName($post_id,'upload_pdf_file')?></a>
                                <!-- <br><small class="text-muted nowrap">SHA-1:1688100aebfb66edd13ce710972194d8003c0a99
                                <i class="fa fa-question-circle text-muted" data-toggle="tooltip" tooltip="" data-placement="top" title="" data-original-title="This is the file fingerprint (SHA-1 checksum), which can be used to verify the file integrity."></i></small> -->
                            </td>
                            <td class="nowrap"><?php echo get_pdf_file_size_from_url(get_field('upload_pdf_file'));
                            //echo formatBytes(filesize(get_field('upload_pdf_file')))?></td>
                            <td class="nowrap"><span class="pull-right">
                                <a class="btn btn-primary" href="<?php echo get_field('upload_pdf_file');?>" download=""><i class="fa fa-download"></i> Download</a></span>
                            </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                </dl>
                <div class="row">
                <div class="col-12 mb-20">
                    <div>
                        <div class="">
                            <div class="row">
                                <div class="col-12">
                                    <div class="accordion gray plus-icon round mb-30">
                                        <div class="acd-group acd-active">
                                            <!-- <a href="<?php //the_permalink(); ?>" class="acd-heading " style="background-color: lightgrey;color: #1a1a1a">Preview</a> -->
                                            <span>Preview</span>
                                            <div class="acd-des" style="">
                                                <iframe class="preview-iframe" id="preview-iframe" width="100%" height="1080" src="<?php echo get_field('upload_pdf_file');?>" data-ruffle-polyfilled=""></iframe>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </div>
            <!-- </main>-->
             <!-- Sidebar -->
            <div class="col-lg-3 col-md-12 d-none">
                <div class="sticky-top">
                <h4 class="mt-10 pl-2">Share</h4>
                <div class="mb-10">
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
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path d="M12 0C5.38 0 0 5.38 0 12s5.38 12 12 12 12-5.38 12-12S18.62 0 12 0zm5.26 9.38v.34c0 3.48-2.64 7.5-7.48 7.5-1.48 0-2.87-.44-4.03-1.2 1.37.17 2.77-.2 3.9-1.08-1.16-.02-2.13-.78-2.46-1.83.38.1.8.07 1.17-.03-1.2-.24-2.1-1.3-2.1-2.58v-.05c.35.2.75.32 1.18.33-.7-.47-1.17-1.28-1.17-2.2 0-.47.13-.92.36-1.3C7.94 8.85 9.88 9.9 12.06 10c-.04-.2-.06-.4-.06-.6 0-1.46 1.18-2.63 2.63-2.63.76 0 1.44.3 1.92.82.6-.12 1.95-.27 1.95-.27-.35.53-.72 1.66-1.24 2.04z"></path>
                            </svg>
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
                <div class="gray-bg p-10">
                    <h4>
                        Cite as <i class="fa fa-question-circle text-muted" data-toggle="tooltip" tooltip="" data-placement="top" title="" data-original-title="For bibliographic purposes, this publication may be cited as"></i>
                    </h4>
                    <div>
                        <p id="pub-citation">MRC. (2022). Proceedings of the 1st ASEAN-MRC Water Security Dialogue: Solutions for a Changing Region. Vientiane: MRC
                            Secretariat. https://doi.org/10.52107/mrc.ajutqy
                        </p>
                    </div>
                    <a href="javascript:void(0)" id="citation-tooltip"><i class="fa fa-clipboard text-muted" data-toggle="tooltip" tooltip="" data-placement="top" title="" data-original-title="For bibliographic purposes, this publication may be cited as"></i>
                    Copy citation to clipboard </a>
                    <script>
                        function copyTextFromElement() {
                            let element = document.getElementById("pub-citation"); //select the element
                            let elementText = element.textContent; //get the text content from the element
                            navigator.clipboard.writeText(elementText);
                        
                            const tooltip = document.getElementById("citation-tooltip");
                            tooltip.innerHTML = "Copied citation to clipboard!";
                        }
                        
                        document.querySelector("#citation-tooltip").addEventListener("click", copyTextFromElement);
                    </script>
                </div>
                <div>
                </div>
                </div>
            </div>
            <!-- Sidebar -->
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