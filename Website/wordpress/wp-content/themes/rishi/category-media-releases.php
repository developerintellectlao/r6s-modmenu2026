<?php 
    /* template Name: Media Releases Listing */
    get_header();
   //--------custom css-----------
   wp_enqueue_style( 'custom-styling', get_stylesheet_directory_uri() . '/dynamic-page.css' );
   $home_url = home_url();

   function get_first_paragraph($content) {
      // Use regex to match the first <p> tag
      if (preg_match('/<p>(.*?)<\/p>/', $content, $matches)) {
          return $matches[0]; // Return the first <p> tag and its content
      }
      return ''; // Return empty string if no <p> tag is found
  }
   // Get the current term object

   $cate_term = get_queried_object();
   $cate_slug = $cate_name = $cate_desc = "";
   
   if ($cate_term && !is_wp_error($cate_term)) {
      $cate_slug = $cate_term->slug; // This will output "climate-change"
      $cate_name = $cate_term->name; 
      $cate_desc = $cate_term->description;
   }

?>
<!--Latest compiled and Bootstrap minified CSS  -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Latest compiled and Bootstrap minified CSS -->
<div id="main-layout">
   <!-- breadcrumb -->
   <?php custom_breadcrumbs();?>
   <div class=" breadcrumb theme-bg-50 d-md-none" style="margin-bottom: 0;">
      <div class="container">
         <a href="javascript:history.back()"> <i class="fa  fa-long-arrow-left"></i> &nbsp;Back </a>
      </div>
   </div>
   <!-- breadcrumb -->
   <section class="page-section-0-ptb">
      <div class="container">
         <div class="row">
            <div class="col-lg-9 col-md-8 ">
               <div class="section-title line-dabble mb-15">
                  <h2 class="title c-green"><?php echo $cate_name;?></h2>
               </div>
               <!-- Category desc -->
               <div class="mt-15 mb-40 mb-sm-2"></div>
               <!-- Category desc -->
               <?php // Get the current page number taxonomy-news_and_events_cat
                    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                     
                     // Capture the selected year from the query string
                     $selected_year = isset($_GET['post_year']) ? intval($_GET['post_year']) : '';
                     //echo $selected_year; 
                     $args = array(
                        'post_type' => 'post', // Custom post type
                        'tax_query' => array(
                              array(
                                 'taxonomy' => 'category', // Custom taxonomy
                                 'field'    => 'slug',
                                 'terms'    => 'media-releases', // Get the current term
                              ),
                        ),
                        'paged' => $paged, // Pagination parameter
                        'posts_per_page' => 10, // Number of posts per page
                     );
                     // If a year is selected, add it to the query
                     if ($selected_year) {
                        $args['year'] = $selected_year;
                     }
                     // Custom query for the posts in the current taxonomy
                     $query = new WP_Query($args);
                     if($query->have_posts()):
                           while($query->have_posts()):
                              //the_post();
                              $query->the_post();
                  ?>
               <div class="product listing">
                  <div class="row">
                     <div class="col-lg-4 col-md-4 col-sm-4">
                        <div class="product-image">
                           <a href="<?php the_permalink();?>"
                           title="Read more on &quot; <?php the_title();?> &quot;">
                           <div class="blog-overlay text-center ">
                              <div class="blog-image">
                                 <img class="img-fluid mx-auto img-shadow" src="<?php echo get_the_post_thumbnail_url()?>"
                                 alt="<?php the_title();?>">
                              </div>
                           </div>
                           </a>
                        </div>
                     </div>
                     <div class="col-lg-8 col-md-8 col-sm-8">
                        <div class="text-left">
                           <div class="product-title">
                              <h3><a href="<?php the_permalink();?>" class="c-theme"><?php the_title();?></a></h3>
                           </div>
                           <div class="c-yellow">
                              <?=get_the_date()?>
                           </div>
                           <div class="product-info mt-10">
                              <a href="<?php the_permalink();?>" class="text-black" title="<?php the_title();?>"> </a>
                              <?php
                                 if (has_excerpt()) {
                                    echo get_the_excerpt();
                                 } elseif (get_field('subtitle')) {
                                    echo get_field('subtitle');
                                 }
                                 else{
                                 echo get_first_paragraph(apply_filters('the_content', get_the_content()));
                              }?>
                              <br>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="divider mt-30 mb-30"></div>
               <?php
                  endwhile;
                        wp_reset_postdata();
                  endif;
               ?> 
               <!-- Pagination  -->
               <div class="col-lg-12 col-md-12 mb-80">
                  <nav aria-label="Page navigation ">
                  <?php  
                     $total_pages = $query->max_num_pages;
                     $current_page = max(1, get_query_var('paged'));
                     custom_pagination($total_pages, $current_page);
                  /*$big = 999999999;
                        $pagination_links = paginate_links(array(
                           'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
                           'total'     => $query->max_num_pages,
                           'format'    => '?paged=%#%',
                           'current'   => $paged,
                           'prev_text' => __('«'),
                           'next_text' => __('»'),
                           'end_size'  => 6,
                           'mid_size'  => 6,
                           'type'      => 'array', // This returns the links as an array instead of a string
                        ));

                        if ( is_array( $pagination_links ) ) {
                           echo '<ul class="pagination">';
                           foreach ( $pagination_links as $link ) {
                                 // Add the 'active' class to the current page link
                                 if (strpos($link, 'current') !== false) {
                                    echo '<li class="page-item active">' . str_replace('page-numbers', 'page-link', $link) . '</li>';
                                 } else {
                                    echo '<li class="page-item">' . str_replace('page-numbers', 'page-link', $link) . '</li>';
                                 }
                           }
                           echo '</ul>';
                        }*/
                     ?>
                  </nav>
               </div>
               <!-- Pagination  -->
            </div>
            <!-- Side bar -->
            <div class="col-lg-3 col-md-4">
               <div class="sidebar-widget mb-40">
                  <h5 class="mb-20">Search our Media Releases</h5>
                  <div class="widget-search">
                     <form action="<?php echo esc_url( $home_url ); ?>" method="GET" id="news-search">
                        <i class="fa fa-search"></i>
		                     <input type="search" class="form-control placeholder" name="s"  value="<?php echo get_search_query(); ?>" placeholder="Search">
                     </form>
                  </div>
               </div>
               <div class="sidebar-widget mb-40">
                  <h5 class="mb-20">Media Releases By Year</h5>
                  <div class="widget-link">
                     <?php echo display_year_dropdown('post', 'category'); ?>
                  </div>
               </div>
            
               <div class="sidebar-widget mt-30 clearfix mb-40">
                  <div class="reading-box__green">
                  <h4 class="pt-10">
                     Media Contact
                  </h4>
                  <div>
                     <h6 class="fs-14">
                        For media inquires, please contact:
                     </h6>
                     <ul>
                        <li>
                        <a href="mailto:mrcmedia@mrcmekong.org?subject=website-media-contact">
                           mrcmedia@mrcmekong.org
                        </a>
                        </li>
                     </ul>
                  </div>
                  </div>
               </div>
               <div class="sidebar-widget mt-0 pt-0 mb-50">
                  <div class="section-title line-dabble">
                     <h4 class="title c-green"> Latest Media Releases</h4>
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
                        <img class="img-fluid " src="<?=get_the_post_thumbnail_url()?>"
                        alt="" width="256" height="256">
                     </div>
                     <div class="recent-post-info">
                        <a href="<?=the_permalink()?>" title="Read more on &quot;<?=the_title()?>&quot;"><?=the_title()?></a>
                        <small><i class="fa fa-calendar-o"></i> <?=get_the_date()?></small>
                     </div>
                  </div>
                  <?php
                     endwhile;
                     wp_reset_postdata(); // Reset the global $post object
                  ?>
                  <!-- <div class="text-right">
                     <a href="#">browse all media releases »</a>
                  </div> -->
                  <?php else :
                        echo '<p>No recent posts found.</p>';
                     endif;
                  ?>
               </div>
            </div>
             <!-- Side bar -->
         </div>
      </div>
   </section>
   <?php include 'scrollBtnPage.html';?>   
</div>

<!-- Latest compiled JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Latest compiled JavaScript -->
<?php get_footer(); ?>