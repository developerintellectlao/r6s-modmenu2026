<?php
/**
 * Register Schema Controls.
 */
namespace Rishi\Schema;

class Microdata {

	protected static $instance = null;

    /**
	 * Instance of this class.
	 *
	 * @var object
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

    /**
	 * Constructor.
	 */
	public function __construct() {
	}

	/**
	 * Get any necessary microdata.
	 *
	 * @param string $placements Targeting elements in different part of the body.
	 * @return string Microdata Element.
	 */
	public function get_microdata( $placements ){
		//default to false 
		$schema_html = false;

		if ( 'head' === $placements ) {
			$schema_html = 'itemtype="https://schema.org/WebSite" itemscope';
		}

		if ( 'body' === $placements ) {
	
			if ( is_home() || is_archive() || is_attachment() || is_tax() ) {
				$schema_html = 'itemtype="https://schema.org/Blog" itemscope';
			}
	
			if ( is_search() ) {
				$schema_html = 'itemtype="https://schema.org/SearchResultsPage" itemscope';
			}

			$schema_html = 'itemtype="https://schema.org/WebPage" itemscope';
		}

		if ( 'blog' === $placements ) {
			$schema_html = 'itemtype="https://schema.org/Blog" itemscope';
		}

		if ( 'logo' === $placements ) {
			$schema_html = 'itemtype="https://schema.org/Organization" itemscope';
		}

		if ( 'article' === $placements ) {
			$schema_html = 'itemtype="https://schema.org/CreativeWork" itemscope';
		}

		if ( 'header' === $placements ) {
			$schema_html = 'itemtype="https://schema.org/WPHeader" itemscope';
		}

		if ( 'navigation' === $placements ) {
			$schema_html = 'itemtype="https://schema.org/SiteNavigationElement" itemscope';
		}

		if ( 'post-author' === $placements ) {
			$schema_html = 'itemprop="author" itemtype="https://schema.org/Person" itemscope';
		}
	
		if ( 'comment-body' === $placements ) {
			$schema_html = 'itemtype="https://schema.org/Comment" itemscope';
		}
	
		if ( 'comment-author' === $placements ) {
			$schema_html = 'itemprop="author" itemtype="https://schema.org/Person" itemscope';
		}

		if ( 'person' === $placements ) {
			$schema_html = 'itemscope itemtype="https://schema.org/Person"';
		}
	
		if ( 'sidebar' === $placements ) {
			$schema_html = 'itemtype="https://schema.org/WPSideBar" itemscope';
		}
	
		if ( 'footer' === $placements ) {
			$schema_html = 'itemtype="https://schema.org/WPFooter" itemscope';
		}

		if ( 'creative_work' === $placements ) {
			$schema_html = 'itemtype="https://schema.org/CreativeWork" itemscope';
		}

		if ( 'breadcrumb_list' === $placements ) {
			$schema_html = 'itemtype="https://schema.org/BreadcrumbList" itemscope';
		}

		if ( 'breadcrumb_item' === $placements ) {
			$schema_html = 'itemprop="itemListElement" itemtype="https://schema.org/ListItem" itemscope';
		}

		if ( 'comment-body' === $placements ) {
			$schema_html = 'itemprop="itemListElement" itemtype="https://schema.org/UserComments" itemscope';
		}

		if ( $schema_html ) {
			return apply_filters( "rishi_{$placements}_microdata", $schema_html );
		}

	}
	/**
	 * Print microdata.
	 * @param string $placements Targeting elements in different part of the body.
	 * @return string Microdata Element.
	 */
	public function print_schema( $placements ) {
		return $this->get_microdata( $placements ); 
	}
}