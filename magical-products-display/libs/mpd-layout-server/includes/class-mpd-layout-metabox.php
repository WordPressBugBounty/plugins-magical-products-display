<?php
/**
 * Layout Metabox
 *
 * Registers and handles metaboxes for the layout post type.
 *
 * @package MPD_Layout_Server
 * @since   1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class MPD_Layout_Metabox
 *
 * Handles metabox registration and saving for layouts.
 *
 * @since 1.0.0
 */
class MPD_Layout_Metabox {

	/**
	 * Single instance.
	 *
	 * @var MPD_Layout_Metabox|null
	 */
	private static $instance = null;

	/**
	 * Nonce action.
	 *
	 * @var string
	 */
	const NONCE_ACTION = 'mpd_layout_metabox_nonce';

	/**
	 * Nonce field name.
	 *
	 * @var string
	 */
	const NONCE_NAME = 'mpd_layout_nonce';

	/**
	 * Get single instance.
	 *
	 * @since 1.0.0
	 *
	 * @return MPD_Layout_Metabox
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	private function __construct() {
		$this->init_hooks();
	}

	/**
	 * Initialize hooks.
	 *
	 * @since 1.0.0
	 */
	private function init_hooks() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post_' . MPD_Layout_Post_Type::POST_TYPE, array( $this, 'save_meta' ), 10, 2 );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	/**
	 * Register meta boxes.
	 *
	 * @since 1.0.0
	 */
	public function add_meta_boxes() {
		// Layout Settings metabox.
		add_meta_box(
			'mpd_layout_settings',
			__( 'Layout Settings', 'magical-products-display' ),
			array( $this, 'render_settings_metabox' ),
			MPD_Layout_Post_Type::POST_TYPE,
			'normal',
			'high'
		);

		// Layout Structure metabox (JSON).
		add_meta_box(
			'mpd_layout_structure',
			__( 'Layout Structure (JSON)', 'magical-products-display' ),
			array( $this, 'render_structure_metabox' ),
			MPD_Layout_Post_Type::POST_TYPE,
			'normal',
			'default'
		);

		// Widgets metabox.
		add_meta_box(
			'mpd_layout_widgets',
			__( 'Layout Widgets', 'magical-products-display' ),
			array( $this, 'render_widgets_metabox' ),
			MPD_Layout_Post_Type::POST_TYPE,
			'side',
			'default'
		);
	}

	/**
	 * Enqueue admin scripts.
	 *
	 * @since 1.0.0
	 *
	 * @param string $hook Current admin page.
	 */
	public function enqueue_scripts( $hook ) {
		global $post_type;

		if ( MPD_Layout_Post_Type::POST_TYPE !== $post_type ) {
			return;
		}

		if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
			return;
		}

		wp_enqueue_style(
			'mpd-layout-metabox',
			MPD_LAYOUT_SERVER_URL . 'assets/css/metabox.css',
			array(),
			MPD_LAYOUT_SERVER_VERSION
		);

		wp_enqueue_script(
			'mpd-layout-metabox',
			MPD_LAYOUT_SERVER_URL . 'assets/js/metabox.js',
			array( 'jquery' ),
			MPD_LAYOUT_SERVER_VERSION,
			true
		);
	}

	/**
	 * Render settings metabox.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Post $post Current post object.
	 */
	public function render_settings_metabox( $post ) {
		// Get existing values.
		$layout_id   = get_post_meta( $post->ID, '_mpd_layout_id', true );
		$layout_type = get_post_meta( $post->ID, '_mpd_layout_type', true );
		$category    = get_post_meta( $post->ID, '_mpd_category', true );
		$description = get_post_meta( $post->ID, '_mpd_description', true );
		$is_pro      = get_post_meta( $post->ID, '_mpd_is_pro', true );

		// Nonce field.
		wp_nonce_field( self::NONCE_ACTION, self::NONCE_NAME );

		// Layout types.
		$layout_types = array(
			'single-product'  => __( 'Single Product', 'magical-products-display' ),
			'archive-product' => __( 'Archive/Shop', 'magical-products-display' ),
			'cart'            => __( 'Cart', 'magical-products-display' ),
			'checkout'        => __( 'Checkout', 'magical-products-display' ),
			'my-account'      => __( 'My Account', 'magical-products-display' ),
			'empty-cart'      => __( 'Empty Cart', 'magical-products-display' ),
			'thankyou'        => __( 'Thank You', 'magical-products-display' ),
		);

		// Categories.
		$categories = array(
			'basic'       => __( 'Basic', 'magical-products-display' ),
			'modern'      => __( 'Modern', 'magical-products-display' ),
			'minimal'     => __( 'Minimal', 'magical-products-display' ),
			'gallery'     => __( 'Gallery', 'magical-products-display' ),
			'creative'    => __( 'Creative', 'magical-products-display' ),
			'luxury'      => __( 'Luxury', 'magical-products-display' ),
			'tech'        => __( 'Tech', 'magical-products-display' ),
			'compact'     => __( 'Compact', 'magical-products-display' ),
			'organized'   => __( 'Organized', 'magical-products-display' ),
			'list'        => __( 'List', 'magical-products-display' ),
			'filtered'    => __( 'Filtered', 'magical-products-display' ),
			'wizard'      => __( 'Wizard', 'magical-products-display' ),
			'express'     => __( 'Express', 'magical-products-display' ),
			'promotional' => __( 'Promotional', 'magical-products-display' ),
		);
		?>
		<div class="mpd-metabox-wrap">
			<table class="form-table">
				<tr>
					<th scope="row">
						<label for="mpd_layout_id"><?php esc_html_e( 'Layout ID', 'magical-products-display' ); ?></label>
					</th>
					<td>
						<input type="text" id="mpd_layout_id" name="mpd_layout_id" value="<?php echo esc_attr( $layout_id ); ?>" class="regular-text" required />
						<p class="description"><?php esc_html_e( 'Unique identifier for this layout (e.g., single-product-modern). Use lowercase letters, numbers, and hyphens only.', 'magical-products-display' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="mpd_layout_type"><?php esc_html_e( 'Layout Type', 'magical-products-display' ); ?></label>
					</th>
					<td>
						<select id="mpd_layout_type" name="mpd_layout_type" required>
							<option value=""><?php esc_html_e( '— Select Type —', 'magical-products-display' ); ?></option>
							<?php foreach ( $layout_types as $value => $label ) : ?>
								<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $layout_type, $value ); ?>><?php echo esc_html( $label ); ?></option>
							<?php endforeach; ?>
						</select>
						<p class="description"><?php esc_html_e( 'The page type this layout is designed for.', 'magical-products-display' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="mpd_category"><?php esc_html_e( 'Category', 'magical-products-display' ); ?></label>
					</th>
					<td>
						<select id="mpd_category" name="mpd_category">
							<option value=""><?php esc_html_e( '— Select Category —', 'magical-products-display' ); ?></option>
							<?php foreach ( $categories as $value => $label ) : ?>
								<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $category, $value ); ?>><?php echo esc_html( $label ); ?></option>
							<?php endforeach; ?>
						</select>
						<p class="description"><?php esc_html_e( 'Style category for grouping layouts.', 'magical-products-display' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="mpd_description"><?php esc_html_e( 'Description', 'magical-products-display' ); ?></label>
					</th>
					<td>
						<textarea id="mpd_description" name="mpd_description" rows="3" class="large-text"><?php echo esc_textarea( $description ); ?></textarea>
						<p class="description"><?php esc_html_e( 'Brief description of the layout.', 'magical-products-display' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="mpd_is_pro"><?php esc_html_e( 'Pro Layout', 'magical-products-display' ); ?></label>
					</th>
					<td>
						<label>
							<input type="checkbox" id="mpd_is_pro" name="mpd_is_pro" value="1" <?php checked( $is_pro, '1' ); ?> />
							<?php esc_html_e( 'This is a Pro-only layout', 'magical-products-display' ); ?>
						</label>
						<p class="description"><?php esc_html_e( 'Check if this layout requires the Pro version.', 'magical-products-display' ); ?></p>
					</td>
				</tr>
			</table>
		</div>
		<?php
	}

	/**
	 * Render structure metabox.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Post $post Current post object.
	 */
	public function render_structure_metabox( $post ) {
		$structure = get_post_meta( $post->ID, '_mpd_layout_structure', true );
		?>
		<div class="mpd-metabox-wrap">
			<p class="description"><?php esc_html_e( 'Paste the Elementor-compatible JSON structure for this layout.', 'magical-products-display' ); ?></p>
			<textarea id="mpd_layout_structure" name="mpd_layout_structure" rows="20" class="large-text code"><?php echo esc_textarea( $structure ); ?></textarea>
			<p>
				<button type="button" class="button" id="mpd_validate_json"><?php esc_html_e( 'Validate JSON', 'magical-products-display' ); ?></button>
				<button type="button" class="button" id="mpd_format_json"><?php esc_html_e( 'Format JSON', 'magical-products-display' ); ?></button>
				<span id="mpd_json_status"></span>
			</p>
		</div>
		<?php
	}

	/**
	 * Render widgets metabox.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Post $post Current post object.
	 */
	public function render_widgets_metabox( $post ) {
		$widgets = get_post_meta( $post->ID, '_mpd_layout_widgets', true );
		$widgets = is_array( $widgets ) ? $widgets : array();

		// Available widgets.
		$available_widgets = array(
			'mpd-product-gallery'           => __( 'Product Gallery', 'magical-products-display' ),
			'mpd-product-title'             => __( 'Product Title', 'magical-products-display' ),
			'mpd-product-price'             => __( 'Product Price', 'magical-products-display' ),
			'mpd-product-rating'            => __( 'Product Rating', 'magical-products-display' ),
			'mpd-product-short-description' => __( 'Short Description', 'magical-products-display' ),
			'mpd-product-description'       => __( 'Full Description', 'magical-products-display' ),
			'mpd-product-add-to-cart'       => __( 'Add to Cart', 'magical-products-display' ),
			'mpd-product-meta'              => __( 'Product Meta', 'magical-products-display' ),
			'mpd-product-stock'             => __( 'Stock Status', 'magical-products-display' ),
			'mpd-product-tabs'              => __( 'Product Tabs', 'magical-products-display' ),
			'mpd-product-attributes'        => __( 'Product Attributes', 'magical-products-display' ),
			'mpd-product-reviews'           => __( 'Customer Reviews', 'magical-products-display' ),
			'mpd-product-navigation'        => __( 'Product Navigation', 'magical-products-display' ),
			'mpd-related-products'          => __( 'Related Products', 'magical-products-display' ),
			'mpd-upsells'                   => __( 'Upsell Products', 'magical-products-display' ),
			'mpd-cross-sells'               => __( 'Cross-sell Products', 'magical-products-display' ),
			'mpd-accordion-widget'          => __( 'Accordion', 'magical-products-display' ),
			'mpd-products-grid'             => __( 'Products Grid', 'magical-products-display' ),
			'mpd-products-list'             => __( 'Products List', 'magical-products-display' ),
			'mpd-cart-table'                => __( 'Cart Table', 'magical-products-display' ),
			'mpd-cart-totals'               => __( 'Cart Totals', 'magical-products-display' ),
			'mpd-checkout-billing'          => __( 'Billing Details', 'magical-products-display' ),
			'mpd-checkout-shipping'         => __( 'Shipping Details', 'magical-products-display' ),
			'mpd-checkout-order-review'     => __( 'Order Review', 'magical-products-display' ),
			'mpd-checkout-payment'          => __( 'Payment Methods', 'magical-products-display' ),
		);
		?>
		<div class="mpd-widgets-wrap">
			<p class="description"><?php esc_html_e( 'Select widgets used in this layout:', 'magical-products-display' ); ?></p>
			<div class="mpd-widgets-list" style="max-height: 300px; overflow-y: auto;">
				<?php foreach ( $available_widgets as $widget_id => $widget_name ) : ?>
					<label style="display: block; margin-bottom: 5px;">
						<input type="checkbox" name="mpd_layout_widgets[]" value="<?php echo esc_attr( $widget_id ); ?>" <?php checked( in_array( $widget_id, $widgets, true ) ); ?> />
						<?php echo esc_html( $widget_name ); ?>
					</label>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Save meta data.
	 *
	 * @since 1.0.0
	 *
	 * @param int     $post_id Post ID.
	 * @param WP_Post $post    Post object.
	 */
	public function save_meta( $post_id, $post ) {
		// Verify nonce.
		if ( ! isset( $_POST[ self::NONCE_NAME ] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST[ self::NONCE_NAME ] ) ), self::NONCE_ACTION ) ) {
			return;
		}

		// Check autosave.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Check permissions.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Sanitize and save layout ID.
		if ( isset( $_POST['mpd_layout_id'] ) ) {
			$layout_id = sanitize_title( wp_unslash( $_POST['mpd_layout_id'] ) );
			update_post_meta( $post_id, '_mpd_layout_id', $layout_id );
		}

		// Sanitize and save layout type.
		if ( isset( $_POST['mpd_layout_type'] ) ) {
			$layout_type = sanitize_text_field( wp_unslash( $_POST['mpd_layout_type'] ) );
			$valid_types = array( 'single-product', 'archive-product', 'cart', 'checkout', 'my-account', 'empty-cart', 'thankyou' );
			if ( in_array( $layout_type, $valid_types, true ) ) {
				update_post_meta( $post_id, '_mpd_layout_type', $layout_type );
			}
		}

		// Sanitize and save category.
		if ( isset( $_POST['mpd_category'] ) ) {
			$category = sanitize_text_field( wp_unslash( $_POST['mpd_category'] ) );
			update_post_meta( $post_id, '_mpd_category', $category );
		}

		// Sanitize and save description.
		if ( isset( $_POST['mpd_description'] ) ) {
			$description = sanitize_textarea_field( wp_unslash( $_POST['mpd_description'] ) );
			update_post_meta( $post_id, '_mpd_description', $description );
		}

		// Sanitize and save is_pro.
		$is_pro = isset( $_POST['mpd_is_pro'] ) ? '1' : '0';
		update_post_meta( $post_id, '_mpd_is_pro', $is_pro );

		// Sanitize and save structure JSON.
		if ( isset( $_POST['mpd_layout_structure'] ) ) {
			// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- JSON structure needs to be preserved.
			$structure = wp_unslash( $_POST['mpd_layout_structure'] );

			// Validate JSON.
			$decoded = json_decode( $structure );
			if ( json_last_error() === JSON_ERROR_NONE ) {
				// Re-encode to ensure proper formatting.
				$structure = wp_json_encode( $decoded );
				update_post_meta( $post_id, '_mpd_layout_structure', $structure );
			}
		}

		// Sanitize and save widgets.
		if ( isset( $_POST['mpd_layout_widgets'] ) && is_array( $_POST['mpd_layout_widgets'] ) ) {
			$widgets = array_map( 'sanitize_text_field', wp_unslash( $_POST['mpd_layout_widgets'] ) );
			update_post_meta( $post_id, '_mpd_layout_widgets', $widgets );
		} else {
			delete_post_meta( $post_id, '_mpd_layout_widgets' );
		}
	}
}
