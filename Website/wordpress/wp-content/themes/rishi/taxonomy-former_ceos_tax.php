<?php 
    /* template Name: Publications Listing */
    get_header();
    //--------custom css-----------
    wp_enqueue_style( 'custom-styling', get_stylesheet_directory_uri() . '/dynamic-page.css' );
    wp_enqueue_style( 'custom-styling', get_stylesheet_directory_uri() . '/former_page.css' );


   // Get the current term object
   $cate_term = get_queried_object();
   $cate_slug = $cate_name = $cate_desc = "";
   
   if ($cate_term && !is_wp_error($cate_term)) {
      $cate_slug = $cate_term->slug; // This will output "climate-change"
      $cate_name = $cate_term->name; 
      $cate_desc = $cate_term->description;
   }

    //-------------
    // $interactive_term = get_term_by('slug', 'interactive', 'former_ceos_tax');
    // // Create an array of term IDs to exclude
    // $exclude_terms = array();
    // if ($interactive_term) {
    //     $exclude_terms[] = $interactive_term->term_id;
    // }
//    // Get all terms in the 'publications_cat' taxonomy
//    $terms = get_terms(array(
//       'post_type' => 'former_ceos',
//       'taxonomy'   => 'former_ceos_tax',
//       'hide_empty' => false, // Hide empty categories
//       'parent'     => 0,    // Start with top-level terms
//       'exclude'    => $exclude_terms, // Exclude 'interactive' and 'example-category'
//    ));
?>
<!--Latest compiled and Bootstrap minified CSS  -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Latest compiled and Bootstrap minified CSS -->
<style>
.profile-card {
    text-align: center;
    padding: 0px;
}

.profile-card .profile-img {
    overflow: hidden;
}

.profile-card img {
    width: 100%;
    height: auto;
    /*max-width: 200px;*/
    border-radius: 4px;
    transition: 0.3s;
}

.profile-card a {
    text-decoration: none;
    color: #000;
}
.profile-card a:hover{
    color: #000;
}

.profile-card a[href="javascript:void(0);"]:hover {
    cursor: default;
}

.profile-card a:focus{
    color: #000;
}

/* .profile-card a:hover .profile-img img {
    transform: scale(1.1);
} */

.profile-name {
    font-weight: bold;
    font-size: 1rem;
    margin-top: 10px;
}

.profile-title {
    font-size: 0.9rem;
    margin-top: 5px;
}

.profile-date {
    font-size: 0.9rem;
    color: #555;
}
</style>


<div id="main-layout">
    <!-- breadcrumb -->
    <div class=" breadcrumb gray-bg _theme-bg-50 d-none d-md-block" style="margin-bottom: 0;">
        <div class="container">
            <a href="/"> <i class="fa  fa-long-arrow-left"></i> &nbsp; </a> »
            <a href="/">Home</a> »
            <span class="breadcrumb-1">About</span> »
            <span class="breadcrumb-2">MRC Secretariat</span> » Former CEOs
        </div>
    </div>
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
                    <div class="section-title line-dabble mb-2">
                        <h2 class="title c-green">FORMER CHIEF EXECUTIVE OFFICERS OF THE MRC SECRETARIAT
                            <!-- <span class="d-block mt-1">MEKONG SECRETARIAT </span> -->
                        </h2>
                    </div>
                </div>
            </div>
        </div>
        <!--  -->
        <div class="container mt-4">
            <div class="row gy-4">
                <?php  
                    $args = array(
                            'post_type' => 'former_ceos', // Custom post type
                            'tax_query' => array(
                                array(
                                    'taxonomy' => 'former_ceos_tax', // Custom taxonomy
                                    'field'    => 'slug',
                                    'terms'    => 'former-ceos', // Get the current term
                                ),
                            ),
                        );
                       
                    $query = new WP_Query($args);
                    if($query->have_posts()):
                        while($query->have_posts()):
                            $query->the_post();
                ?>
                <div class="col-md-6 col-lg-3">
                    <div class="profile-card">
                        <a
                            href="<?php echo !empty(trim(get_the_content())) ? esc_url(get_permalink()) : 'javascript:void(0);'; ?>">
                            <div class="profile-img"><img src="<?php echo get_the_post_thumbnail_url()?>"
                                    alt="<?php the_title();?>"></div>
                            <div class="profile-name"><?php the_title();?></div>
                            <div class="profile-title"><?php echo get_field('sub_title')?></div>
                            <div class="profile-date"><?php echo get_field('duration')?></div>
                        </a>
                    </div>
                </div>
                <?php 
                    endwhile;
                        wp_reset_postdata();
                    endif;
                ?>
            </div>
        </div>
    </section>
    <section class="pt-20 pb-0 mb-0">
        <div class="container mt-4">
            <div class="row gy-4 mb-5">
                <div class="col-12">
                    <div class="section-title line-dabble mb-2">
                        <h2 class="title c-green">FORMER EXECUTIVE AGENTS OF THE MEKONG SECRETARIAT
                            <!-- <span class="d-block mt-1">MEKONG RIVER COMMISSION SECRETARIAT </span> -->
                        </h2>
                    </div>
                </div>
                <?php  
                    $args = array(
                            'post_type' => 'former_ceos', // Custom post type
                            'tax_query' => array(
                                array(
                                    'taxonomy' => 'former_ceos_tax', // Custom taxonomy
                                    'field'    => 'slug',
                                    'terms'    => 'executive-agents', // Get the current term
                                ),
                            ),
                        );
                       
                    $query = new WP_Query($args);
                    if($query->have_posts()):
                        while($query->have_posts()):
                            $query->the_post();
                ?>
                <div class="col-md-6 col-lg-3">
                    <div class="profile-card">
                        <a
                            href="<?php echo !empty(trim(get_the_content())) ? esc_url(get_permalink()) : 'javascript:void(0);'; ?>">
                            <div class="profile-img"><img src="<?php echo get_the_post_thumbnail_url()?>"
                                    alt="<?php the_title();?>"></div>
                            <div class="profile-name"><?php the_title();?></div>
                            <div class="profile-title"><?php echo get_field('sub_title')?></div>
                            <div class="profile-date"><?php echo get_field('duration')?></div>
                        </a>
                    </div>
                </div>
                <?php 
                    endwhile;
                        wp_reset_postdata();
                    endif;
                ?>
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