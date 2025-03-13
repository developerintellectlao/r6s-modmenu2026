<?php
/**
 * Media View Class.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WPCarousel Media View
 *
 * @since 2.5.0
 */
class WP_Carousel_Free_Media_View {

	/**
	 * Holds the class object.
	 *
	 * @since 2.5
	 *
	 * @var object
	 */
	public static $instance;

	/**
	 * Path to the file.
	 *
	 * @since 2.5
	 *
	 * @var string
	 */
	public $file = __FILE__;

	/**
	 * Holds the base class object.
	 *
	 * @since 2.5
	 *
	 * @var object
	 */
	public $base;

	/**
	 * Primary class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		// Modals.
		add_action( 'print_media_templates', array( $this, 'print_media_templates' ) );
	}

	/**
	 * Adds media view (modal) strings.
	 *
	 * @since 2.5
	 *
	 * @param    array $strings Media View Strings.
	 * @return   array Media View Strings
	 */
	public function media_view_strings( $strings ) {
		return $strings;
	}

	/**
	 * Outputs backbone.js wp.media compatible templates, which are loaded into the modal
	 * view
	 *
	 * @since 2.5
	 */
	public function print_media_templates() {
		// Get the Gallery Post and Config.
		global $post;

		if ( isset( $post ) ) {
			$post_id = absint( $post->ID );
		} else {
			$post_id = 0;
		}

		// Bail if we're not editing an sp_wp_carousel Gallery.
		if ( get_post_type( $post_id ) !== 'sp_wp_carousel' ) {
			return;
		}

		// Meta Editor.
		// Use: wp.media.template( 'wpcp_image-meta-editor' ).
		?>
		<script type="text/html" id="tmpl-wpcp_image-meta-editor">
			<div class="edit-media-header">
				<button class="left dashicons"><span class="screen-reader-text"><?php esc_html_e( 'Edit previous media item', 'wp-carousel-free' ); ?></span></button>
				<button class="right dashicons"><span class="screen-reader-text"><?php esc_html_e( 'Edit next media item', 'wp-carousel-free' ); ?></span></button>
			</div>
			<div class="media-frame-title">
				<h1><?php esc_html_e( 'Edit Item', 'wp-carousel-free' ); ?></h1>
			</div>
			<div class="media-frame-content">
				<div class="attachment-details save-ready">
					<!-- Left -->
					<div class="attachment-media-view portrait">
						<# if ( data.type  !== 'html' ) { #>
						<div class="thumbnail thumbnail-image">
							<img class="details-image" src="{{ data.url }}" draggable="false" />
								<# if ( data.type  === 'video' ) { #>
								<!-- Choose Video Placeholder Image + Remove Video Placeholder Image -->
								<a href="#" class="wpcp_image-thumbnail button button-primary" data-field="wpcp_image-src" title="Choose Video Placeholder Image"><?php esc_html_e( 'Choose Video Placeholder Image', 'wp-carousel-free' ); ?></a>
								<a href="#" class="wpcp_image-thumbnail-delete button button-secondary" data-field="wpcp_image-src" title="Remove Video Placeholder Image"><?php esc_html_e( 'Remove Video Placeholder Image', 'wp-carousel-free' ); ?></a>
								<# } #>
						</div>
						<# } #>
						<# if ( data.type  === 'html' ) { #>
							<div class="wpcp_image-code-preview">
								{{ data.code }}
							</div>
						<# } #>
					</div>

					<!-- Right -->
					<div class="attachment-info">
						<!-- Settings -->
						<div class="settings">
							<!-- Attachment ID -->
							<input type="hidden" name="id" value="{{ data.id }}" />
							<input type="hidden" name="type" value="{{ data.type }}" />
							<div class="wpcp_image-meta">
								<ul class="wpcp_tabs-nav">
									<li class="wpcp-tab tab-active">
										<a href="#tab-1" rel="nofollow">General</a>
									</li>
									<li class="wpcp-tab">
										<a href="#tab-2" rel="nofollow">SEO</a>
									</li>
									<li class="wpcp-tab">
										<a href="#tab-3" rel="nofollow">Crop</a>
									</li>
									<li class="wpcp-tab">
										<a href="#tab-4" rel="nofollow">Additional Info</a>
									</li>
								</ul>
								<div class="wpcp_tabs-stage">
									<div id="tab-1" class="" style="display: block;">
									<div class="wpcp-general-content wpcp-media-content">
										<# if ( data.type  !== 'html' ) { #>
											<!-- Caption -->
											<div class="wpcp_image-meta">
												<div class="setting">
													<span class="name"><?php esc_html_e( 'Caption', 'wp-carousel-free' ); ?></span>
													<?php
													wp_editor(
														'',
														'caption',
														array(
															'media_buttons' => false,
															'wpautop'   => false,
															'tinymce'   => false,
															'textarea_name' => 'caption',
															'quicktags' => array(
																'buttons' => 'strong,em,link,ul,ol,li,close',
															),
														)
													);

													?>

												</div>
											</div>
											<!-- Description -->
											<div class="wpcp_image-meta">
												<div class="setting">
													<span class="name"><?php esc_html_e( 'Description', 'wp-carousel-free' ); ?></span>
													<?php
													wp_editor(
														'',
														'description',
														array(
															'media_buttons' => false,
															'wpautop'       => false,
															'tinymce'       => false,
															'textarea_name' => 'description',
															'quicktags' => array(
																'buttons' => 'strong,em,link,ul,ol,li,close',
															),
														)
													);
													?>
													<span class="description">
														<?php esc_html_e( 'Description for the slide image. Field accepts any valid HTML.', 'wp-carousel-free' ); ?>
													</span>

												</div>
											</div>
										<# } #>
										<# if ( data.type  === 'image' ) { #>
										<div class="wpcp_image-meta wpcp-media-link">
											<label class="setting">
												<span class="name">Link URL <a href="https://wpcarousel.io/pricing/?ref=1" target="_blank"><b>(Pro)</b></a></span>
												<input type="text" disabled name="wpcplink" value="{{ data.wpcplink }}" />
												<span class="buttons">
													<button class="button button-small media-file"><?php esc_html_e( 'Media File', 'wp-carousel-free' ); ?></button>
													<button class="button button-small attachment-page"><?php esc_html_e( 'Attachment Page', 'wp-carousel-free' ); ?></button>
												</span>
											</label>
											<!-- Link in New Window -->
											<label class="setting wpcp-media-checkbox">
												<input type="checkbox" disabled name="link_target" value="1" <# if ( data.link_target == '1' ) { #> checked <# } #> /><span class="name"><?php esc_html_e( 'Open a New Tab', 'wp-carousel-free' ); ?></span>
												</span>
											</label>
											</div>
										<# } #>
										</div>
									</div>
									<div id="tab-2" style="display: none;">
										<!-- Title -->
										<div class="wpcp_image-meta">
											<label class="setting">
												<span class="name"><?php esc_html_e( 'Title', 'wp-carousel-free' ); ?></span>
												<input type="text" name="title" value="{{ data.title }}" />
												<span class="description">
													<?php esc_html_e( 'Enter the title for your slide.', 'wp-carousel-free' ); ?>
												</span>
											</label>
										</div>
										<div class="wpcp_image-meta">
											<label class="setting">
												<span class="name"><?php esc_html_e( 'Alt Text', 'wp-carousel-free' ); ?></span>
												<input type="text" name="alt" value="{{ data.alt }}" />
												<span class="description">
													<?php esc_html_e( 'Describes the image for search engines and screen readers. Important for SEO and accessibility.', 'wp-carousel-free' ); ?>
												</span>
											</label>
										</div>
									</div>
									<div id="tab-3" style="display: none;">
										<div class="wpcp_image-meta">
											<label class="setting">
												<span class="name"><?php esc_html_e( 'Crop Position', 'wp-carousel-free' ); ?></span>
												<select name="crop_position" class="crop_position">
													<option value="left-top" <# if ( data.crop_position == 'left-top' ) { #>selected="selected"<# } #>>Top Left</option>
													<option value="center-top" <# if ( data.crop_position == 'center-top' ) { #>selected="selected"<# } #>>Top Center</option>
													<option value="right-top" <# if ( data.crop_position == 'right-top' ) { #>selected="selected"<# } #>>Top Right</option>
													<option value="left-center" <# if ( data.crop_position == 'left-center' ) { #>selected="selected"<# } #> >Center Left</option>
													<option value="center-center" <# if ( !data.crop_position || data.crop_position == 'center-center' ) { #>selected="selected"<# } #> >Center Center</option>
													<option value="right-center" <# if ( data.crop_position == 'right-center' ) { #>selected="selected"<# } #> >Center Right</option>
													<option value="left-bottom" <# if ( data.crop_position == 'left-bottom' ) { #>selected="selected"<# } #> >Bottom Left</option>
													<option value="center-bottom" <# if ( data.crop_position == 'center-bottom' ) { #>selected="selected"<# } #> >Bottom Center</option>
													<option value="right-bottom" <# if ( data.crop_position == 'right-bottom' ) { #>selected="selected"<# } #> >Bottom Right</option>
												</select>
												<!-- <span class="description">
													<?php esc_html_e( 'Describes the image for search engines and screen readers. Important for SEO and accessibility.', 'wp-carousel-free' ); ?>
												</span> -->
											</label>
										</div>
									</div>
									<div id="tab-4" style="display: none;">
									<!--  -->
										<div class="details">
											<div class="filename"><span>File Name: </span> {{ data.filename }}</div>
											<div class="file-type"><span>File Type: </span>{{ data.mime }}</div>
											<# var width = data.width  #>
											<# var height = data.height #>
											<div class="file-size"><span>File size: </span>{{ data.filesizeHumanReadable }}</div>
											<div class="dimensions"><span>Dimensions:</span> {{  height }} X {{ width }}</div>
											<span class="setting" data-setting="url">
												<div class="wpcp-link-row">
												<label for="attachment-details-copy-link" class="name">File URL:
												</label>
												<div class="wpcp_attachment-copy-link">
													<input type="text" class="attachment-details-copy-link" id="attachment-details-copy-link" value="{{ data.url }}" readonly>
												</div>
</div>
												<div class="copy-to-clipboard-container">
													<button type="button" class="button button-small copy-attachment-url" data-clipboard-target="#attachment-details-copy-link">Copy URL to clipboard</button>
													<span class="success hidden" aria-hidden="true">Copied!</span>
												</div>
											</span>
											<a class="edit-attachment" href="{{ data.editLink }}" target="_blank">Edit Image</a>
										</div>
									</div>
								</div>
							</div>

							<# if ( data.type  === 'video' ) { #>
							<div class="wpcp_image-meta">
								<!-- Link -->
								<label class="setting">
									<span class="name"><?php esc_html_e( 'URL', 'wp-carousel-free' ); ?></span>
									<input type="text" name="link" value="{{ data.url }}" />
								</label>
							</div>
							<# } #>
							<# if ( data.type  === 'html' ) { #>
							<div class="wpcp_image-meta code">
								<!-- Link -->
									<label class="code">
										<span class="name"><?php esc_html_e( 'Code', 'wp-carousel-free' ); ?></span>
										<textarea class="wpcp_image-html-slide-code" name="code">{{ data.code }}</textarea>
									</label>
							</div>
							<# } #>
							<!-- Addons can populate the UI here -->
							<div class="addons"></div>
						</div>
						<!-- /.settings -->

						<!-- Actions -->
						<div class="actions">
							<a href="#" class="wpcp_image-meta-submit button media-button button-large button-primary media-button-insert" title="<?php esc_attr_e( 'Save Metadata', 'wp-carousel-free' ); ?>">
								<?php esc_html_e( 'Save Metadata', 'wp-carousel-free' ); ?>
							</a>

							<!-- Save Spinner -->
							<span class="settings-save-status">
								<span class="spinner"></span>
								<span class="saved"><?php esc_html_e( 'Saved.', 'wp-carousel-free' ); ?></span>
							</span>
						</div>
						<!-- /.actions -->
					</div>
				</div>
			</div>
		</script>
		<?php
		// Bulk Image Editor.
		// Use: wp.media.template( 'wpcp_image-meta-bulk-editor' ).
		?>
		<script type="text/html" id="tmpl-wpcp_image-meta-bulk-editor">

			<div class="media-frame-title">
				<h1><?php esc_html_e( 'Bulk Edit', 'wp-carousel-free' ); ?></h1>
			</div>

			<div class="media-frame-content">
				<div class="attachment-details save-ready">
					<!-- Left -->
					<div class="attachment-media-view portrait">
						<ul class="attachments wpcp_image-bulk-edit">
						</ul>
					</div>
					<!-- Right -->
					<div class="attachment-info">
						<!-- Settings -->
						<div class="settings">
							<!-- Attachment ID -->
							<!-- Title -->
							<div class="wpcp_image-meta">
								<label class="setting">
									<span class="name"><?php esc_html_e( 'Alt Text', 'wp-carousel-free' ); ?></span>
									<input type="text" name="alt" value="{{ data.alt }}" />
									<span class="description">
										<?php esc_html_e( 'Describes the image for search engines and screen readers. Important for SEO and accessibility.', 'wp-carousel-free' ); ?>
									</span>
								</label>
							</div>
							<!-- Caption -->
							<div class="wpcp_image-meta">
								<div class="setting">
									<span class="name"><?php esc_html_e( 'Caption', 'wp-carousel-free' ); ?></span>
									<?php
									wp_editor(
										'',
										'caption',
										array(
											'media_buttons' => false,
											'wpautop'   => false,
											'tinymce'   => false,
											'textarea_name' => 'caption',
											'quicktags' => array(
												'buttons' => 'strong,em,link,ul,ol,li,close',
											),
										)
									);
									?>
								</div>
							</div>

							<# if ( data.type  === 'image' ) { #>
							<div class="wpcp_image-meta">
								<label class="setting">
									<span class="name"><?php esc_html_e( 'URL', 'wp-carousel-free' ); ?></span>
									<input type="text" name="link" value="{{ data.link }}" />
									<# if ( typeof( data.id ) === 'number' ) { #>
										<span class="buttons">
											<button class="button button-small media-file"><?php esc_html_e( 'Media File', 'wp-carousel-free' ); ?></button>
											<button class="button button-small attachment-page"><?php esc_html_e( 'Attachment Page', 'wp-carousel-free' ); ?></button>
										</span>
									<# } #>
									<span class="description">
										<strong><?php esc_html_e( 'URL', 'wp-carousel-free' ); ?></strong>
										<?php esc_html_e( 'Enter a hyperlink to link this slide to another page.', 'wp-carousel-free' ); ?>
									</span>
								</label>
								<!-- Link in New Window -->
								<label class="setting">
									<span class="name"><?php esc_html_e( 'Open URL in New Window?', 'wp-carousel-free' ); ?></span>
									<input type="checkbox" name="link_new_window" value="1"<# if ( data.link_new_window == '1' ) { #> checked <# } #> />
									<span class="check-label"><?php esc_html_e( 'Opens your image links in a new browser window / tab.', 'wp-carousel-free' ); ?></span>
								</label>

								</div>
							<# } #>

							<# if ( data.type  === 'video' ) { #>
							<div class="wpcp_image-meta">
								<!-- Link -->
								<label class="setting">
									<span class="name"><?php esc_html_e( 'URL', 'wp-carousel-free' ); ?></span>
									<input type="text" name="link" value="{{ data.url }}" />
								</label>
							</div>
							<# } #>

							<!-- Addons can populate the UI here -->
							<div class="addons"></div>
						</div>
						<!-- /.settings -->

						<!-- Actions -->
						<div class="actions">
							<a href="#" class="wpcp_image-meta-submit button media-button button-large button-primary media-button-insert" title="<?php esc_attr_e( 'Save Metadata', 'wp-carousel-free' ); ?>">
								<?php esc_html_e( 'Save Metadata', 'wp-carousel-free' ); ?>
							</a>
							<!-- Save Spinner -->
							<span class="settings-save-status">
								<span class="spinner"></span>
								<span class="saved"><?php esc_html_e( 'Saved.', 'wp-carousel-free' ); ?></span>
							</span>
						</div>
						<!-- /.actions -->
					</div>
				</div>
			</div>
		</script>
		<?php
		// Bulk Image Editor Image.
		// Use: wp.media.template( 'wpcp_image-meta-bulk-editor-image' ).
		?>
		<script type="text/html" id="tmpl-wpcp_image-meta-bulk-editor-slides">
			<div class="attachment-preview">
				<div class="thumbnail">
					<div class="centered">
					<# if ( data.type  !== 'html' ) { #>

						<img src={{ data.src }} />

					<# } #>
						<# if ( data.type  === 'html' ) { #>

							<div class="wpcp_image-code-preview">

								<!-- <img src="<?php // echo esc_url( plugins_url( 'assets/images/html.png', $this->base->file ) ); ?>" /> -->

							</div>

						<# } #>

					</div>
				</div>
			</div>
		</script>
		<?php
		do_action( 'wpcp_image_print_templates' );
	}

	/**
	 * Returns the singleton instance of the class.
	 *
	 * @since 2.5
	 *
	 * @return object The WPCarousel_Media_View_Lite object.
 */
	// public static function get_instance() {

	// if ( ! isset( self::$instance ) && ! ( self::$instance instanceof WPCarousel_Media_View_Lite ) ) {
	// self::$instance = new WPCarousel_Media_View_Lite();
	// }

	// return self::$instance;
	// }
}

// Load the media class.
new WP_Carousel_Free_Media_View();
