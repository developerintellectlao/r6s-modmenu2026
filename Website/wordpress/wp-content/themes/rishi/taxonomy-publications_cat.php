<?php 
    /* template Name: Publications Listing */
    get_header();
    //--------custom css-----------
    wp_enqueue_style( 'custom-styling', get_stylesheet_directory_uri() . '/dynamic-page.css' );

    $home_url = home_url();

   // Get the current term object
   $cate_term = get_queried_object();
   $cate_slug = $cate_name = $cate_desc = "";
   
   if ($cate_term && !is_wp_error($cate_term)) {
      $cate_slug = $cate_term->slug; // This will output "climate-change"
      $cate_name = $cate_term->name; 
      $cate_desc = $cate_term->description;
   }

    //-------------
    $interactive_term = get_term_by('slug', 'interactive', 'publications_cat');
    // Create an array of term IDs to exclude
    $exclude_terms = array();
    if ($interactive_term) {
        $exclude_terms[] = $interactive_term->term_id;
    }
   // Get all terms in the 'publications_cat' taxonomy
   $terms = get_terms(array(
      'post_type' => 'publications',
      'taxonomy'   => 'publications_cat',
      'hide_empty' => false, // Hide empty categories
      'parent'     => 0,    // Start with top-level terms
      'exclude'    => $exclude_terms, // Exclude 'interactive' and 'example-category'
   ));
?>
<!--Latest compiled and Bootstrap minified CSS  -->
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Latest compiled and Bootstrap minified CSS -->


<div id="main-layout">
    <!-- breadcrumb -->
    <?php echo custom_breadcrumbs(); ?>
    <!-- <div class=" breadcrumb gray-bg _theme-bg-50 d-none d-md-block" style="margin-bottom: 0;">
      <div class="container">
         <a href=""> <i class="fa  fa-long-arrow-left"></i> &nbsp; </a> »
         <a href="">Home</a> » 
         <a href="" class="breadcrumb-1">News and Events</a> » 
         <a href="" class="breadcrumb-2">Consultations</a> » Regional Stakeholder Forums
      </div>
   </div> -->
    <div class=" breadcrumb theme-bg-50 d-md-none" style="margin-bottom: 0;">
        <div class="container">
            <a href="javascript:history.back()"> <i class="fa  fa-long-arrow-left"></i> &nbsp;Back </a>
        </div>
    </div>
    <!-- breadcrumb -->
    <section class="pt-20 pb-0 mb-0">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="section-title line-dabble">
                        <h2 class="title c-green"><?php 
                            if($cate_name === "Publications"){
                                echo $cate_name;
                            }else{
                                echo "Publications related to ".$cate_name;
                            }
                        ?></h2>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="page-section-0-ptb pt-0">
        <div class="container">
            <div class="row">
                <div class="col-lg-9 col-md-9 ">
                    <div class="row ">
                        <div class="col-12 mb-20"></div>
                        <?php
                        $year = ''; 
                        // Get the current page number taxonomy-publications_cat
                        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                       
                        
                       // Capture the selected year from the query string
                       if (isset($_GET['post_year']) && !empty($_GET['post_year'])) {
                           $year = intval($_GET['post_year']);
                       }
                        $args = array(
                           'post_type' => 'publications', // Custom post type
                           'tax_query' => array(
                               array(
                                   'taxonomy' => 'publications_cat', // Custom taxonomy
                                   'field'    => 'slug',
                                   'terms'    => get_queried_object()->slug, // Get the current term
                               ),
                           ),
                           'paged' => $paged, // Pagination parameter
                           'posts_per_page' => 10, // Number of posts per page
                        );
                        // If a year is selected, add it to the query
                        if ($year) {
                           $args['year'] = $year;
                        }
                        // Custom query for the posts in the current taxonomy
                        $query = new WP_Query($args);
                        if($query->have_posts()):
                            while($query->have_posts()):
                                 //the_post();
                                 $query->the_post();
                                 if(get_field('upload_pdf_file') != ""){
                    ?>
                        <div class="col-12 mb-20">
                            <div class="card flex-fill">
                                <div class="card-body pb-0  ">
                                    <div class="row">
                                        <div class="col-12 col-sm-12 col-lg-3 col-md-4 ">
                                            <div class="text-center">
                                                <a class="publication-thumb" href="<?php the_permalink();?>" target="_blank"
                                                    title="<?php the_title();?>">
                                                    <img class="img-fluid mx-auto img-shadow mb-20"
                                                        src="<?php echo get_the_post_thumbnail_url()?>"
                                                        alt="<?php the_title();?>">
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-lg-9 col-md-8 col-sm-8">
                                            <h5 class="card-title ">
                                                <a href="<?php the_permalink();?>" class="c-darkblue fs-16"
                                                    title="Preview <?php the_title();?>"><?php the_title();?></a>

                                            </h5>

                                            <?php the_content(); ?>


                                            <div class=" pb-10 mt-0 align-self-end">
                                                <p class="text-muted">
                                                    <?php $filelink = '<a href="' . get_field('upload_pdf_file') . '" class="card-link" title="Download ' . get_the_title() . '"><span class="ti-download"></span> Download</a> |';
;
                                                        if($cate_name === "Governance"){
                                                            $filelink = "";
                                                        }
                                                    ?>
                                                    <?php
                                                        echo $filelink;
                                                    ?>

                                                    <!-- <a href="<?php// echo "#";?>"
                                                        class="card-link" title="Download <?php// the_title();?>"
                                                        ><span class="ti-download"></span> Download</a> -->

                                                    <?php if(get_field('doi_url')) { ?>
                                                    <span>DOI: <a href="<?php echo get_field('doi_url')?>"
                                                            target="_blank"><?php echo get_field('doi')?></a></span> |
                                                    <?php } ?>
                                                    <span>Published on : <?php echo get_the_date();?></span> |
                                                    <span>Language : <?php echo get_field('language')?> </span>
                                                </p>
                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php }
                        endwhile;
                            wp_reset_postdata();
                       endif;
                    ?>

                        <!-- Pagination  -->
                        <div class="col-lg-12 col-md-12 mb-80">
                            <nav aria-label="Page navigation ">
                                <?php 
                                //$big = 999999999;
                                $total_pages = $query->max_num_pages;
                                
                                $current_page = max(1, get_query_var('paged'));
                                custom_pagination($total_pages, $current_page);
                                /* $pagination_links =  paginate_links(array(
                                    //'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
                                    'total'        => $total_pages,
                                    //'format'    => '?paged=%#%',
                                    'current'      => $paged,
                                    'prev_text'    => __('«'),
                                    'next_text'    => __('»'),
                                    'end_size'     => 1, // Adjusted to show 1 link at each end
                                    'mid_size'     => 3, // Adjusted to show 3 links on either side of the current page
                                    'type'      => 'array', // This returns the links as an array instead of a string
                                ));

                                if ( is_array( $pagination_links ) ) {
                                    echo '<ul class="pagination">';
                                    foreach ( $pagination_links as $link ) {
                                    // Add the 'active' class to the current page link
                                    // Remove ellipses by skipping them
                                        // if (strpos($link, 'dots') !== false) {
                                        //     continue;
                                        // }
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
                        <?php
                       // wp_reset_postdata();
                       // else: 
                        //   echo '<p>No posts found</p>';
                        //endif;
                    ?>
                    </div>
                </div>
                <!-- Sidebar -->
                <div class="col-lg-3 col-md-3">
                    <div class="sidebar-widgets-wrap">
                        <div class="sidebar-widget mb-40">
                            <h5 class="mb-20">Search our Publications</h5>
                            <div class="widget-search">
                                <form action="<?php echo esc_url( $home_url ); ?>" method="GET" id="publication-search">
                                    <i class="fa fa-search"></i>
                                    <input type="search" placeholder="Search" class="form-control placeholder" name="s"
                                        value="<?php echo get_search_query(); ?>">
                                </form>
                            </div>
                        </div>
                        <div class="sidebar-widget mb-20">
                            <div class=" mt-30 mb-0">
                                <h5 class="title">Publications by Year</h5>
                            </div>
                            <div class="widget-link">
                                <?php echo display_year_dropdown('publications', 'publications_cat'); ?>
                            </div>
                        </div>
                        <?php foreach ($terms as $term) {
                        // Get and display subcategories
                           $child_terms = get_terms(array(
                              'taxonomy'   => 'publications_cat',
                              'hide_empty' => false,
                              'parent'     => $term->term_id,
                        ));
                  ?>
                        <div class="sidebar-widget mb-20">
                            <div class="section-title line-dabble c-yellow mt-30 mb-0">
                                <?php if($term->name == "Publications"){ ?>
                                <h5 class="title">Browse by Type</h5>
                                <?php } else {?>
                                <h5 class="title">Publications by <?= $term->name; ?></h5>
                                <?php } ?>
                            </div>
                            <div class="widget-link">
                                <ul class="fa-ul">
                                    <?php 
                              if (!empty($child_terms) && !is_wp_error($child_terms)) {
                                 foreach ($child_terms as $child_term) {
                                    $link=get_term_link($child_term);
                                        if ($term->name == "Language" || $term->name == "Interactive") {
                                           $link="#";
                                        } 
                                        ?>
                                    <li><a href="<?php echo $link;?>"
                                            title="Go to the <?php echo $child_term->name;?> page"><span
                                                class="fa-li"><i class="fa fa-angle-double-right"></i></span>
                                            <?php echo $child_term->name;?> </a></li>
                                    <?php }
                                 }?>
                                </ul>
                            </div>
                        </div>
                        <?php } ?>

                        <!--Language -->
                        <!-- <div class="sidebar-widget mb-20">
                     <div class="section-title line-dabble yellow mt-30 mb-0">
                        <h5 class="title">Publications by Language</h5>
                     </div>
                     <div class="widget-link">
                        <ul class="fa-ul">
                           <li><a href="publications/language/english" title="English publications"><span class="fa-li"><i class="fa fa-angle-double-right"></i></span>English</a></li>
                           <li><a href="publications/language/khmer" title="Khmer publications"><span class="fa-li"><i class="fa fa-angle-double-right"></i></span>Khmer/ ខែ្មរ&ZeroWidthSpace;</a></li>
                           <li><a href="publications/language/lao" title="Lao publications"><span class="fa-li"><i class="fa fa-angle-double-right"></i></span>Lao / ລາວ</a></li>
                           <li><a href="publications/language/thai" title="Thai publications"><span class="fa-li"><i class="fa fa-angle-double-right"></i></span>Thai / ภาษาไทย</a></li>
                           <li><a href="publications/language/vietnamese" title="Vietnamese publications"><span class="fa-li"><i class="fa fa-angle-double-right"></i></span>Vietnamese / Tiếng Việt</a>
                           </li>
                        </ul>
                     </div>
                  </div> -->
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Latest compiled JavaScript -->
<?php get_footer(); ?>