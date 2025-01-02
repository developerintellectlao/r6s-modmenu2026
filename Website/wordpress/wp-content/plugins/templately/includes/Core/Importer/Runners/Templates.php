<?php

namespace Templately\Core\Importer\Runners;

use Exception;
use Templately\Builder\PageTemplates;
use Templately\Builder\Types\BaseTemplate;
use Templately\Core\Importer\Utils\Utils;
use Templately\Core\Importer\WPImport;
use Templately\Utils\Helper;

class Templates extends BaseRunner {
	protected $imported_types = [];

	public function get_name(): string {
		return 'templates';
	}

	public function get_label(): string {
		return __( 'Templates', 'templately' );
	}

	public function log_message(): string {
		return __( 'Importing Templates (i.e: Header, Footer, etc)', 'templately' );
	}

	public function should_run( $data, $imported_data = [] ): bool {
		return ! empty( $this->manifest['templates'] );
		// return isset( $data['templates'] ) && $data['templates'] && ! empty( $this->manifest['templates'] );
	}

	/**
	 * @throws Exception
	 */
	public function import( $data, $imported_data ): array {
		$results   = [];
		$templates = $this->manifest['templates'];
		$path      = $this->dir_path . 'templates' . DIRECTORY_SEPARATOR;

		$_extra_pages = [];

		$total     = count( $templates );
		$processed = 0;
		foreach ( $templates as $id => $template_settings ) {
			$template_content = Utils::read_json_file( $path . $id . '.json' );

			$import = $this->import_template( $id, $template_settings, $template_content );
			if ( $import ) {
				$results['succeed'][ $id ]   = $import;
				$results['template_types'][] = $template_settings['type'];

				if ( $template_settings['type'] === 'archive' || $template_settings['type'] === 'product_archive' || $template_settings['type'] === 'course_archive' ) {
					$page_id = $this->create_archive_page( $template_settings, $this->manifest['platform'] );
					if ( $page_id ) {
						$_extra_pages['archive_settings'] = [
							'old_id'     => $id,
							'page_id'    => $page_id,
							'archive_id' => $import
						];
					}
				}

			} else {
				$results['failed'][ $id ] = $import;
			}

			// Broadcast Log
			$processed += 1;
			$progress  = floor( ( 100 * $processed ) / $total );
			$this->log( $progress );
		}

		return array_merge( [ 'templates' => $results ], $_extra_pages );
	}

	private function import_template( $id, $template_settings, $template_content ) {
		$type = $template_settings['type'];

		/**
		 * @var BaseTemplate $template
		 */

		$post_data = [
			'post_title'  => $template_settings['title'] ?? ucfirst( $type ) . ' - (by Templately)',
			'post_status' => 'publish',
			'post_type'   => 'templately_library',
		];

		$meta = [];

		if ( $this->manifest['platform'] == 'gutenberg' ) {
			$meta['_wp_page_template'] = PageTemplates::TEMPLATE_HEADER_FOOTER;
		}

		$template = $this->factory->create( $type, $post_data, $meta );

		if ( is_wp_error( $template ) ) {
			return false;
		}
		if (!$template->is_elementor_template()) {
			$attachments = $this->json->parse_images($template_content['content']);

			if (!empty($attachments)) {
				$manifest_content = &$this->manifest['templates'][$id];
				if(!isset($manifest_content['__attachments'])){
					$manifest_content['__attachments'] = [];
				}
				$manifest_content['__attachments'] = $attachments;
			}
		}

		$template_content['id']              = $id;
		unset($template_settings['conditions']);
		$template_content['import_settings'] = $template_settings;
		$template->import( $template_content );

		if($template->has_logo($template_content)){
			$this->manifest['templates'][$id]['has_logo'] = true;
		}

		return $template->get_main_id();
	}


	/**
	 * @param $template_settings
	 * @param $platform
	 *
	 * @return false|int
	 */
	private function create_archive_page( $template_settings, $platform ) {
		try {
			$type = $template_settings['type'];

			$archive_page = wp_insert_post( [
				'post_title'    => $template_settings['title'] ?? ucfirst( $type ) . ' - (by Templately)',
				'post_status'   => 'publish',
				'post_type'     => 'page',
				'post_content'  => '',
				'page_template' => $platform === 'elementor' ? 'elementor_header_footer' : PageTemplates::TEMPLATE_HEADER_FOOTER,
			] );

			if ( is_wp_error( $archive_page ) ) {
				return false;
			}

			if($type === 'archive'){
				Utils::update_option( 'page_for_posts', $archive_page );
			}

			if($type === 'product_archive'){
				Utils::update_option( 'woocommerce_shop_page_id', $archive_page );
			}
			return $archive_page;
		} catch ( \Exception $e ) {
			return false;
		}
	}
}