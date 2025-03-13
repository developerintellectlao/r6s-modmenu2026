<?php

namespace Templately\Core\Importer\Runners;

use Elementor\Plugin;
use Exception;
use Templately\Core\Importer\Utils\Utils;

class ElementorContent extends BaseRunner {
	public function get_name(): string {
		return 'content';
	}

	public function get_label(): string {
		return __( 'Elementor', 'templately' );
	}

	public function should_run( $data, $imported_data = [] ): bool {
		return $this->manifest['platform'] === 'elementor' && ! empty( $this->manifest['content'] );
	}

	public function should_log(): bool {
		return true;
	}

	public function get_action(): string {
		return 'eventLog';
	}

	public function log_message(): string {
		return __( 'Importing Elementor Templates (Pages, Posts etc)', 'templately' );
	}

	/**
	 * @throws Exception
	 */
	public function import( $data, $imported_data ): array {
		$results  = [];
		$contents = $this->manifest['content'];
		$path     = $this->dir_path . 'content' . DIRECTORY_SEPARATOR;

		// $total     = array_reduce( $contents, function ( $carry, $item ) {
		// 	return $carry + count( $item );
		// }, 0 );
		// $processed = 0;

		/**
		 * Check if there is any active kit?
		 * If not, create one.
		 */

		$kits_manager = Plugin::$instance->kits_manager;

		$active_kit = $kits_manager->get_active_id();
		$kit        = $kits_manager->get_kit( $active_kit );
		$old_logo   = $kit->get_settings('site_logo');

		if(isset($this->manifest['has_settings']) && $this->manifest['has_settings']){
			// backing up the active kit id before updating the new one
			if(!get_option("__templately_el_active_kit")){
				add_option("__templately_el_active_kit", $active_kit, '', 'no');
			}

			$file     = $this->dir_path . "settings.json";
			$settings = Utils::read_json_file( $file );

			if(!empty($data['color'])){
				if (!empty($settings['system_colors'])) {
					foreach ($settings['system_colors'] as $key => $color) {
						$settings['system_colors'][$key]['color'] = $data['color'][$color['_id']] ?? $color['color'];
					}
				}
				if (!empty($settings['custom_colors'])) {
					foreach ($settings['custom_colors'] as $key => $color) {
						$settings['custom_colors'][$key]['color'] = $data['color'][$color['_id']] ?? $color['color'];
					}
				}
			}

			if (!empty($data['logo']['id'])) {
				$settings['site_logo'] = $data['logo'];
			} elseif (!empty($data['logo'])) {
				// If there's no old logo id, try to upload a new logo
				if (empty($old_logo['id'])) {
					$site_logo = Utils::upload_logo($data['logo']);

					// If the upload was successful, use the new logo, otherwise use the old one
					$settings['site_logo'] = !empty($site_logo['id']) ? $site_logo : $old_logo;
				} else {
					// If there's an old logo id, use the old logo
					$settings['site_logo'] = $old_logo;
				}
			}


			$kit_id = $kits_manager->create_new_kit( $this->manifest['name'], $settings, true );

			$kit    = $kits_manager->get_kit( $kit_id );

			// $kit->update_settings( ['site_logo' => $settings['site_logo']] );

			// Create an array with the post ID and the new title
			$post_data = array(
				'ID'         => $kit_id,
				'post_title' => $this->manifest['name'] . " Kit",
			);
			// Update the post
			wp_update_post( $post_data );

		}

		$active_kit = $kits_manager->get_active_id();
		$kit        = $kits_manager->get_kit( $active_kit );

		if ( ! $kit->get_id() ) {
			$kit = $kits_manager->create_default();
			update_option( $kits_manager::OPTION_ACTIVE, $kit );
		}

		$processed = 0;
		$total     = array_reduce($contents, function($carry, $item) {
			return $carry + count($item);
		}, 0);

		foreach ( $contents as $post_type => $post ) {
			if ( ! post_type_exists( $post_type ) ) {
				continue;
			}

			foreach ( $post as $id => $content_settings ) {
				$import = $this->import_post_type_content( $id, $post_type, $path, $imported_data, $content_settings );

				if ( ! $import ) {
					$results[ $post_type ]['failed'][ $id ] = $import;
				} else {
					Utils::import_page_settings( $import, $content_settings );
					$results[ $post_type ]['succeed'][ $id ] = $import;
				}

				// Broadcast Log
				$processed += 1;
				$progress   = floor( ( 100 * $processed ) / $total );
				$this->log( $progress, null, 'eventLog' );
			}
		}

		return [ 'content' => $results ];
	}

	/**
	 * @throws Exception
	 */
	private function import_post_type_content( $id, $post_type, $path, $imported_data, $content_settings ) {
		try {
			$template = $this->factory->create( $content_settings['doc_type'], [
				'post_title'  => $content_settings['title'],
				'post_status' => 'publish',
				'post_type'   => $post_type,
			] );

			$file      = $path . $post_type . DIRECTORY_SEPARATOR . "{$id}.json";
			$post_data = Utils::read_json_file( $file );

			if ( ! empty( $content_settings['data'] ) ) {
				/**
				 * TODO:
				 *
				 * We can check if there is any data for settings.
				 * if yes: ignore content from insert.
				 *
				 * Process the content while finalizing.
				 */
				// $this->json->prepare( $post_data['content'], $id, $content_settings['data'], $imported_data );

				$post_data['content'] = [];
			}

			unset($content_settings['conditions']);
			$post_data['import_settings'] = $content_settings;

			$template->import( $post_data );

			return $template->get_main_id();
		} catch ( Exception $e ) {
			return false;
		}
	}
}