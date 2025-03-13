<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Rishi
 */
/**
 * Doctype Hook
 *
 * @hooked rishi_doctype
 */
do_action('rishi_doctype');
?>
<head>
    <?php
	/**
     * Head Start
     */
	do_action('rishi_head_start'); ?>
    <?php
    /**
     * Before wp_head
     *
     * @hooked rishi_head
     */
    do_action('rishi_before_wp_head');

    wp_head(); ?>
    <?php
	/**
     * Head End
     */
	do_action('rishi_head_end'); ?>
</head>

<body <?php body_class(); ?> <?php echo rishi_print_schema('body'); ?>>
<?php wp_body_open(); ?>

<?php
/**
 * Before Header
 *
 * @hooked rishi_page_start - 20
 */
do_action('rishi_before_header');

/**
 * Before Header Builder Hook
 */
do_action('rishi_headerbuilder_before');

/**
 * Header Builder Code goes here
 *
 * @hooked rishi_header_builder
 */
do_action('rishi_headerbuilder');

/**
 * After Header Builder Hook
 */
do_action('rishi_headerbuilder_after');

/**
 * After Header
*/
do_action( 'rishi_after_header' );

/**
 * Content
 *
 * @hooked rishi_content_start
 */
do_action('rishi_content');
