<?php
/**
 * Framework gallery field.
 *
 * @link       https://shapedplugin.com
 * @since      3.0.0
 *
 * @package    WP_Carousel_Pro
 * @subpackage WP_Carousel_Pro/admin/views
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

if ( ! class_exists( 'SP_WPCP_Framework_Field_gallery' ) ) {
	/**
	 *
	 * Field: gallery
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class SP_WPCP_Framework_Field_gallery extends SP_WPCP_Framework_Fields {
		/**
		 * Field class constructor.
		 *
		 * @param array  $field The field type.
		 * @param string $value The values of the field.
		 * @param string $unique The unique ID for the field.
		 * @param string $where To where show the output CSS.
		 * @param string $parent The parent args.
		 */
		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		/**
		 * Getting image metadata.
		 *
		 * @param  int $image_id id.
		 * @return array
		 */
		private function getting_image_metadata( $image_id ) {
			$image_metadata_array                          = array();
			$image_linking_meta                            = wp_get_attachment_metadata( $image_id );
			$image_linking_urls                            = isset( $image_linking_meta['image_meta'] ) ? $image_linking_meta['image_meta'] : '';
			$image_linking_url                             = isset( $image_linking_urls['wpcplinking'] ) ? $image_linking_urls['wpcplinking'] : '';
			$image_metadata_array['status']                = 'active';
			$image_metadata_array['id']                    = $image_id;
			$image_metadata_array['url']                   = esc_url( wp_get_attachment_url( $image_id ) );
			$image_metadata_array['height']                = $image_linking_meta['height'] ?? '';
			$image_metadata_array['width']                 = $image_linking_meta['width'] ?? '';
			$image_metadata_array['alt']                   = trim( esc_html( get_post_meta( $image_id, '_wp_attachment_image_alt', true ) ) ) ?? '';
			$image_metadata_array['caption']               = trim( esc_html( get_post_field( 'post_excerpt', $image_id ) ) ) ?? '';
			$image_metadata_array['title']                 = trim( esc_html( get_post_field( 'post_title', $image_id ) ) ) ?? '';
			$image_metadata_array['description']           = trim( get_post_field( 'post_content', $image_id ) ) ?? '';
			$image_metadata_array['filename']              = trim( esc_html( get_post_field( 'post_name', $image_id ) ) ) ?? '';
			$image_metadata_array['wpcplink']              = $image_linking_url;
			$image_metadata_array['link_target']           = get_post_meta( $image_id, 'wpcplinktarget', true );
			$image_metadata_array['crop_position']         = trim( esc_html( get_post_meta( $image_id, 'crop_position', true ) ) ) ?? 'center_center';
			$image_metadata_array['editLink']              = get_edit_post_link( $image_id, 'display' );
			$image_metadata_array['type']                  = 'image';
			$image_metadata_array['mime']                  = $image_linking_meta['sizes']['thumbnail']['mime-type'] ?? '';
			$image_metadata_array['filesizeHumanReadable'] = round( filesize( get_attached_file( $image_id ) ) / 1024 ) . ' KB';
			if ( array_key_exists( 'sizes', $image_linking_meta ) ) {
				unset( $image_linking_meta['sizes'] );
			}
			if ( array_key_exists( 'image_meta', $image_linking_meta ) ) {
				unset( $image_linking_meta['image_meta'] );
			}
			return array_merge( $image_linking_meta, $image_metadata_array );
		}
		/**
		 * Render
		 *
		 * @return void
		 */
		public function render() {

			$args = wp_parse_args(
				$this->field,
				array(
					'add_title'   => esc_html__( 'Add Gallery', 'wp-carousel-pro' ),
					'edit_title'  => esc_html__( 'Edit Gallery', 'wp-carousel-pro' ),
					'clear_title' => esc_html__( 'Clear', 'wp-carousel-pro' ),
				)
			);

			$hidden = ( empty( $this->value ) ) ? ' hidden' : '';

			echo wp_kses_post( $this->field_before() );
			// echo '<a href="#" class="button button-primary sp_wpcp-button"><i class="fa fa-plus-circle"></i>' . esc_html( $args['add_title'] ) . '</a>';
			echo '<a href="#" class="button button-primary sp_wpcp-button"><img src="' . WPCAROUSEL_URL . 'Admin/img/add-image.svg" alt="">' . esc_html( $args['add_title'] ) . '</a>';
			echo '<ul class="sp-gallery-images">';
			if ( ! empty( $this->value ) ) {
				$values = explode( ',', $this->value );
				foreach ( $values as $id ) {
					$attachment = wp_get_attachment_image_src( $id, 'thumbnail' );
					if ( ! $attachment ) {
						continue;
					}
					$image_meta = $this->getting_image_metadata( $id );
					$json       = wp_json_encode( $image_meta );
					if ( isset( $attachment[0] ) ) {
						echo '<li class="wpcp_image-slide wpcp_image-image "><img src="' . esc_url( $attachment[0] ) . '" /><a class="edit-attachment-modify edit-icon wcp-icon" data-id="' . esc_attr( $id ) . '" href="" target="_blank" data-wpcp_image-model=\'' . $json . '\' ><span class="wpcp-icon-edit"></span></a> <a class="wpcp_image-thumbnail-delete remove-icon wcp-icon" data-id="' . esc_attr( $id ) . '" href=""><span class="wpcp-icon-delete"></span></a></li>';
					}
				}
			}
			echo '</ul>';

			// echo '<ul> <li>';
			// echo '<a href="#" class="button sp_wpcp-edit-gallery' . esc_attr( $hidden ) . '"><i class="fa fa-pencil-square-o"></i>' . esc_html( $args['edit_title'] ) . '</a>';
			// echo '</li></ul>';

			echo '<ul> <li>';
			echo '<a href="#" class="button sp_wpcp-warning-primary sp_wpcp-clear-gallery' . esc_attr( $hidden ) . '"><i class="fa fa-trash"></i>' . esc_html( $args['clear_title'] ) . '</a>';
			echo '</li></ul>';
			echo '<input type="hidden" name="' . esc_attr( $this->field_name() ) . '" value="' . esc_attr( $this->value ) . '"' . $this->field_attributes() . '/>'; // phpcs:ignore

			echo wp_kses_post( $this->field_after() );
		}
	}
}
