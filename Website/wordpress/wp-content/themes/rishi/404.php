<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Rishi
 */
get_header();

/**
 * 404 Page Content hook
 * 
 * @hooked rishi_404_topsection - 10
 * @hooked rishi_404_search - 20
 * @hooked rishi_404_latestposts - 30
*/
do_action( 'rishi_404_page_content' );

get_footer();