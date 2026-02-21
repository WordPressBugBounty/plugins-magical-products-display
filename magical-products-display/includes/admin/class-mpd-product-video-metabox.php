<?php
/**
 * Product Video Metabox
 *
 * Adds a video URL field to WooCommerce products for gallery video support.
 *
 * @package Magical_Shop_Builder
 * @since   2.0.0
 */

namespace MPD\MagicalShopBuilder\Admin;

use MPD\MagicalShopBuilder\Core\Pro;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Product_Video_Metabox
 *
 * Handles the product video URL metabox in WooCommerce.
 *
 * @since 2.0.0
 */
class Product_Video_Metabox {

	/**
	 * Meta key for the video URL.
	 *
	 * @var string
	 */
	const META_KEY = '_mpd_product_video_url';

	/**
	 * Meta key for video position.
	 *
	 * @var string
	 */
	const POSITION_META_KEY = '_mpd_product_video_position';

	/**
	 * Instance of the class.
	 *
	 * @var Product_Video_Metabox|null
	 */
	private static $instance = null;

	/**
	 * Get instance.
	 *
	 * @since 2.0.0
	 *
	 * @return Product_Video_Metabox
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Initialize the metabox.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public function init() {
		// Only initialize if Pro is active.
		if ( ! Pro::is_active() ) {
			return;
		}

		// Add video field to product data tabs.
		add_filter( 'woocommerce_product_data_tabs', array( $this, 'add_product_video_tab' ) );
		add_action( 'woocommerce_product_data_panels', array( $this, 'add_product_video_panel' ) );
		add_action( 'woocommerce_process_product_meta', array( $this, 'save_product_video_meta' ) );

		// Add quick edit field for video URL.
		add_action( 'woocommerce_product_options_general_product_data', array( $this, 'add_general_video_field' ) );
	}

	/**
	 * Add product video tab.
	 *
	 * @since 2.0.0
	 *
	 * @param array $tabs Existing tabs.
	 * @return array Modified tabs.
	 */
	public function add_product_video_tab( $tabs ) {
		$tabs['mpd_video'] = array(
			'label'    => __( 'Product Video', 'magical-products-display' ),
			'target'   => 'mpd_product_video_data',
			'class'    => array( 'show_if_simple', 'show_if_variable', 'show_if_grouped', 'show_if_external' ),
			'priority' => 65,
		);

		return $tabs;
	}

	/**
	 * Add product video panel.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public function add_product_video_panel() {
		global $post;

		$video_url = get_post_meta( $post->ID, self::META_KEY, true );
		$video_position = get_post_meta( $post->ID, self::POSITION_META_KEY, true );

		if ( empty( $video_position ) ) {
			$video_position = 'first';
		}
		?>
		<div id="mpd_product_video_data" class="panel woocommerce_options_panel">
			<div class="options_group">
				<p class="form-field">
					<label for="mpd_product_video_url"><?php esc_html_e( 'Video URL', 'magical-products-display' ); ?></label>
					<input type="url" 
						   class="short" 
						   style="width: 50%;" 
						   name="mpd_product_video_url" 
						   id="mpd_product_video_url" 
						   value="<?php echo esc_attr( $video_url ); ?>" 
						   placeholder="https://www.youtube.com/watch?v=..." />
					<span class="description"><?php esc_html_e( 'Enter a YouTube or Vimeo video URL.', 'magical-products-display' ); ?></span>
				</p>

				<p class="form-field">
					<label for="mpd_product_video_position"><?php esc_html_e( 'Video Position', 'magical-products-display' ); ?></label>
					<select name="mpd_product_video_position" id="mpd_product_video_position" class="select short">
						<option value="first" <?php selected( $video_position, 'first' ); ?>><?php esc_html_e( 'First (Before Images)', 'magical-products-display' ); ?></option>
						<option value="last" <?php selected( $video_position, 'last' ); ?>><?php esc_html_e( 'Last (After Images)', 'magical-products-display' ); ?></option>
					</select>
				</p>

				<div class="mpd-video-preview" style="padding: 12px;">
					<?php if ( ! empty( $video_url ) ) : ?>
						<?php $this->render_video_preview( $video_url ); ?>
					<?php else : ?>
						<p class="mpd-no-video-message" style="color: #999; font-style: italic;">
							<?php esc_html_e( 'No video added yet. Enter a YouTube or Vimeo URL above.', 'magical-products-display' ); ?>
						</p>
					<?php endif; ?>
				</div>
			</div>

			<div class="options_group">
				<p class="form-field">
					<span class="description">
						<strong><?php esc_html_e( 'Supported Formats:', 'magical-products-display' ); ?></strong><br>
						• YouTube: https://www.youtube.com/watch?v=VIDEO_ID<br>
						• YouTube Short: https://youtu.be/VIDEO_ID<br>
						• Vimeo: https://vimeo.com/VIDEO_ID
					</span>
				</p>
			</div>
		</div>

		<style>
			#woocommerce-product-data ul.wc-tabs li.mpd_video_options a::before {
				content: '\f236';
				font-family: 'dashicons';
			}
		</style>

		<script>
		jQuery(document).ready(function($) {
			$('#mpd_product_video_url').on('change blur', function() {
				var url = $(this).val();
				var $preview = $('.mpd-video-preview');
				
				if (!url) {
					$preview.html('<p class="mpd-no-video-message" style="color: #999; font-style: italic;"><?php echo esc_js( __( 'No video added yet. Enter a YouTube or Vimeo URL above.', 'magical-products-display' ) ); ?></p>');
					return;
				}

				// Show loading.
				$preview.html('<p style="color: #999;"><?php echo esc_js( __( 'Loading preview...', 'magical-products-display' ) ); ?></p>');

				// Parse the URL and show preview.
				var videoId = '';
				var videoType = '';

				// YouTube patterns.
				var youtubeMatch = url.match(/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/);
				if (youtubeMatch) {
					videoId = youtubeMatch[1];
					videoType = 'youtube';
				}

				// Vimeo patterns.
				var vimeoMatch = url.match(/(?:vimeo\.com\/)(\d+)/);
				if (vimeoMatch) {
					videoId = vimeoMatch[1];
					videoType = 'vimeo';
				}

				if (videoType === 'youtube') {
					$preview.html('<img src="https://img.youtube.com/vi/' + videoId + '/mqdefault.jpg" style="max-width: 320px; border-radius: 4px;" /><p style="color: #46b450; margin-top: 8px;"><span class="dashicons dashicons-yes"></span> <?php echo esc_js( __( 'Valid YouTube video detected', 'magical-products-display' ) ); ?></p>');
				} else if (videoType === 'vimeo') {
					$preview.html('<p style="color: #46b450;"><span class="dashicons dashicons-yes"></span> <?php echo esc_js( __( 'Valid Vimeo video detected (ID: ', 'magical-products-display' ) ); ?>' + videoId + ')</p>');
				} else {
					$preview.html('<p style="color: #dc3232;"><span class="dashicons dashicons-no"></span> <?php echo esc_js( __( 'Invalid video URL. Please use a YouTube or Vimeo URL.', 'magical-products-display' ) ); ?></p>');
				}
			});
		});
		</script>
		<?php
	}

	/**
	 * Add video field to general product data.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public function add_general_video_field() {
		// This is an alternative location - the tab is preferred.
		// Keeping this for backwards compatibility or quick access.
	}

	/**
	 * Save product video meta.
	 *
	 * @since 2.0.0
	 *
	 * @param int $post_id The product ID.
	 * @return void
	 */
	public function save_product_video_meta( $post_id ) {
		// Verify nonce is handled by WooCommerce (woocommerce_process_product_meta hook).
		// phpcs:disable WordPress.Security.NonceVerification.Missing

		// Save video URL.
		if ( isset( $_POST['mpd_product_video_url'] ) ) {
			$video_url = esc_url_raw( wp_unslash( $_POST['mpd_product_video_url'] ) );
			
			if ( ! empty( $video_url ) && $this->is_valid_video_url( $video_url ) ) {
				update_post_meta( $post_id, self::META_KEY, $video_url );
			} else {
				delete_post_meta( $post_id, self::META_KEY );
			}
		}

		// Save video position.
		if ( isset( $_POST['mpd_product_video_position'] ) ) {
			$position = sanitize_text_field( wp_unslash( $_POST['mpd_product_video_position'] ) );
			
			if ( in_array( $position, array( 'first', 'last' ), true ) ) {
				update_post_meta( $post_id, self::POSITION_META_KEY, $position );
			}
		}
		// phpcs:enable WordPress.Security.NonceVerification.Missing
	}

	/**
	 * Check if URL is a valid video URL.
	 *
	 * @since 2.0.0
	 *
	 * @param string $url The URL to check.
	 * @return bool Whether the URL is valid.
	 */
	public function is_valid_video_url( $url ) {
		return $this->get_video_type( $url ) !== false;
	}

	/**
	 * Get video type from URL.
	 *
	 * @since 2.0.0
	 *
	 * @param string $url The video URL.
	 * @return string|false Video type ('youtube' or 'vimeo') or false if invalid.
	 */
	public static function get_video_type( $url ) {
		if ( empty( $url ) ) {
			return false;
		}

		// YouTube patterns.
		if ( preg_match( '/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $url ) ) {
			return 'youtube';
		}

		// Vimeo patterns.
		if ( preg_match( '/(?:vimeo\.com\/)(\d+)/', $url ) ) {
			return 'vimeo';
		}

		return false;
	}

	/**
	 * Get video ID from URL.
	 *
	 * @since 2.0.0
	 *
	 * @param string $url The video URL.
	 * @return string|false Video ID or false if not found.
	 */
	public static function get_video_id( $url ) {
		if ( empty( $url ) ) {
			return false;
		}

		// YouTube patterns.
		if ( preg_match( '/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $url, $matches ) ) {
			return $matches[1];
		}

		// Vimeo patterns.
		if ( preg_match( '/(?:vimeo\.com\/)(\d+)/', $url, $matches ) ) {
			return $matches[1];
		}

		return false;
	}

	/**
	 * Get video thumbnail URL.
	 *
	 * @since 2.0.0
	 *
	 * @param string $url  The video URL.
	 * @param string $size Thumbnail size (default, medium, high, maxres for YouTube).
	 * @return string|false Thumbnail URL or false if not found.
	 */
	public static function get_video_thumbnail( $url, $size = 'mqdefault' ) {
		$video_type = self::get_video_type( $url );
		$video_id   = self::get_video_id( $url );

		if ( ! $video_type || ! $video_id ) {
			return false;
		}

		if ( 'youtube' === $video_type ) {
			// YouTube thumbnail sizes: default, mqdefault, hqdefault, sddefault, maxresdefault.
			return 'https://img.youtube.com/vi/' . $video_id . '/' . $size . '.jpg';
		}

		if ( 'vimeo' === $video_type ) {
			// Vimeo requires API call for thumbnail, use a placeholder or fetch via oembed.
			// For now, return a placeholder - real implementation would use Vimeo API.
			return MAGICAL_PRODUCTS_DISPLAY_ASSETS . 'images/video-placeholder.png';
		}

		return false;
	}

	/**
	 * Get video embed URL.
	 *
	 * @since 2.0.0
	 *
	 * @param string $url The video URL.
	 * @return string|false Embed URL or false if not found.
	 */
	public static function get_embed_url( $url ) {
		$video_type = self::get_video_type( $url );
		$video_id   = self::get_video_id( $url );

		if ( ! $video_type || ! $video_id ) {
			return false;
		}

		if ( 'youtube' === $video_type ) {
			return 'https://www.youtube.com/embed/' . $video_id . '?autoplay=1&rel=0';
		}

		if ( 'vimeo' === $video_type ) {
			return 'https://player.vimeo.com/video/' . $video_id . '?autoplay=1';
		}

		return false;
	}

	/**
	 * Get product video URL.
	 *
	 * @since 2.0.0
	 *
	 * @param int $product_id The product ID.
	 * @return string|false Video URL or false if not set.
	 */
	public static function get_product_video_url( $product_id ) {
		$url = get_post_meta( $product_id, self::META_KEY, true );
		return ! empty( $url ) ? $url : false;
	}

	/**
	 * Get product video position.
	 *
	 * @since 2.0.0
	 *
	 * @param int $product_id The product ID.
	 * @return string Video position ('first' or 'last').
	 */
	public static function get_product_video_position( $product_id ) {
		$position = get_post_meta( $product_id, self::POSITION_META_KEY, true );
		return ! empty( $position ) ? $position : 'first';
	}

	/**
	 * Render video preview in admin.
	 *
	 * @since 2.0.0
	 *
	 * @param string $url The video URL.
	 * @return void
	 */
	private function render_video_preview( $url ) {
		$video_type = self::get_video_type( $url );
		$video_id   = self::get_video_id( $url );

		if ( 'youtube' === $video_type && $video_id ) {
			?>
			<img src="https://img.youtube.com/vi/<?php echo esc_attr( $video_id ); ?>/mqdefault.jpg" 
				 style="max-width: 320px; border-radius: 4px;" 
				 alt="<?php esc_attr_e( 'Video preview', 'magical-products-display' ); ?>" />
			<p style="color: #46b450; margin-top: 8px;">
				<span class="dashicons dashicons-yes"></span> 
				<?php esc_html_e( 'Valid YouTube video detected', 'magical-products-display' ); ?>
			</p>
			<?php
		} elseif ( 'vimeo' === $video_type && $video_id ) {
			?>
			<p style="color: #46b450;">
				<span class="dashicons dashicons-yes"></span> 
				<?php 
				/* translators: %s: Vimeo video ID */
				printf( esc_html__( 'Valid Vimeo video detected (ID: %s)', 'magical-products-display' ), esc_html( $video_id ) ); 
				?>
			</p>
			<?php
		} else {
			?>
			<p style="color: #dc3232;">
				<span class="dashicons dashicons-no"></span> 
				<?php esc_html_e( 'Invalid video URL. Please use a YouTube or Vimeo URL.', 'magical-products-display' ); ?>
			</p>
			<?php
		}
	}
}
