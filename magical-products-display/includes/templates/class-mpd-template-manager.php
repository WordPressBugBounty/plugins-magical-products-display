<?php
/**
 * Template Manager - Hybrid Approach
 *
 * Detects Elementor Pro and uses appropriate template system.
 * - Elementor Free: Custom template library (mpd_template CPT)
 * - Elementor Pro: Integration with Theme Builder
 *
 * @package Magical_Shop_Builder
 * @since   2.0.0
 */

namespace MPD\MagicalShopBuilder\Templates;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Template_Manager
 *
 * Manages the hybrid template system for both Elementor Free and Pro users.
 *
 * @since 2.0.0
 */
class Template_Manager {

	/**
	 * Instance of the class.
	 *
	 * @var Template_Manager|null
	 */
	private static $instance = null;

	/**
	 * Whether Elementor Pro Theme Builder is available.
	 *
	 * @var bool|null
	 */
	private $has_elementor_pro = null;

	/**
	 * Template types configuration.
	 *
	 * @var array
	 */
	private $template_types = array();

	/**
	 * Get instance.
	 *
	 * @since 2.0.0
	 *
	 * @return Template_Manager
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 *
	 * @since 2.0.0
	 */
	public function __construct() {
		// Template types are lazy-loaded to avoid translation calls before init hook.
	}

	/**
	 * Initialize the template manager.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public function init() {
		// Register CPT early - if init already fired, register immediately.
		if ( did_action( 'init' ) ) {
			$this->register_template_cpt();
		} else {
			add_action( 'init', array( $this, 'register_template_cpt' ), 5 );
		}

		// Add Elementor CPT support - must run before Elementor initializes.
		add_filter( 'elementor/cpt_support', array( $this, 'add_elementor_cpt_support' ) );

		// Ensure CPT support is stored in Elementor's settings option.
		add_action( 'init', array( $this, 'ensure_elementor_cpt_support' ), 20 );

		// Add support for Elementor page templates on our CPT.
		add_filter( 'theme_mpd_template_templates', array( $this, 'add_elementor_page_templates' ), 10, 4 );

		// Initialize the appropriate system based on Elementor version.
		if ( did_action( 'elementor/loaded' ) ) {
			$this->init_template_system();
		} else {
			add_action( 'elementor/loaded', array( $this, 'init_template_system' ) );
		}

		// Handle Elementor preview - hook early to set up the query correctly.
		add_action( 'pre_get_posts', array( $this, 'setup_elementor_preview_query' ), 1 );
		add_action( 'template_redirect', array( $this, 'handle_elementor_preview_redirect' ), 1 );

		// Add template file for our CPT (for frontend and preview).
		add_filter( 'single_template', array( $this, 'load_template_file' ) );
		add_filter( 'template_include', array( $this, 'load_template_file_include' ), 997 );

		// Ensure Elementor document exists for our templates.
		add_action( 'elementor/documents/register', array( $this, 'ensure_document_support' ) );

		// Handle Elementor preview initialization.
		add_action( 'elementor/preview/init', array( $this, 'init_elementor_preview' ) );

		// Admin notices.
		add_action( 'admin_notices', array( $this, 'elementor_pro_notice' ) );

		// Flush template cache on save/delete.
		add_action( 'save_post_mpd_template', array( $this, 'flush_template_cache' ) );
		add_action( 'delete_post', array( $this, 'flush_template_cache' ) );
		add_action( 'trashed_post', array( $this, 'flush_template_cache' ) );
	}

	/**
	 * Ensure our CPT is added to Elementor's CPT support in the database option.
	 *
	 * Elementor stores supported CPTs in `elementor_cpt_support` option.
	 * This ensures our CPT is added to that option so Elementor recognizes it.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public function ensure_elementor_cpt_support() {
		if ( ! class_exists( '\Elementor\Plugin' ) ) {
			return;
		}

		// Get current Elementor CPT support option.
		$cpt_support = get_option( 'elementor_cpt_support', array( 'post', 'page' ) );

		// Ensure it's an array.
		if ( ! is_array( $cpt_support ) ) {
			$cpt_support = array( 'post', 'page' );
		}

		// Add our CPT if not already present.
		if ( ! in_array( 'mpd_template', $cpt_support, true ) ) {
			$cpt_support[] = 'mpd_template';
			update_option( 'elementor_cpt_support', $cpt_support );
		}
	}

	/**
	 * Set up the WordPress query for Elementor preview requests.
	 *
	 * @since 2.0.0
	 *
	 * @param WP_Query $query The WordPress query.
	 * @return void
	 */
	public function setup_elementor_preview_query( $query ) {
		// Don't run in admin (except for AJAX).
		if ( is_admin() && ! wp_doing_ajax() ) {
			return;
		}

		// Only modify the main query.
		if ( ! $query->is_main_query() ) {
			return;
		}

		// Check for Elementor preview parameter.
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$elementor_preview_id = isset( $_GET['elementor-preview'] ) ? absint( $_GET['elementor-preview'] ) : 0;

		if ( ! $elementor_preview_id ) {
			return;
		}

		$preview_post = get_post( $elementor_preview_id );

		if ( ! $preview_post || 'mpd_template' !== $preview_post->post_type ) {
			return;
		}

		// Override the query to load our specific post.
		$query->set( 'p', $elementor_preview_id );
		$query->set( 'post_type', 'mpd_template' );
		$query->set( 'post_status', 'any' );
		$query->is_singular = true;
		$query->is_single   = true;
		$query->is_archive  = false;
		$query->is_home     = false;
		$query->is_page     = false;
	}

	/**
	 * Handle Elementor preview redirect to ensure correct template loading.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public function handle_elementor_preview_redirect() {
		// Don't run in admin.
		if ( is_admin() ) {
			return;
		}

		global $wp_query, $post;

		// Check for Elementor preview parameter.
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$elementor_preview_id = isset( $_GET['elementor-preview'] ) ? absint( $_GET['elementor-preview'] ) : 0;

		if ( ! $elementor_preview_id ) {
			return;
		}

		$preview_post = get_post( $elementor_preview_id );

		if ( ! $preview_post || 'mpd_template' !== $preview_post->post_type ) {
			return;
		}

		// Set up globals for this post.
		$post = $preview_post;
		setup_postdata( $post );

		// Ensure query flags are correct.
		$wp_query->queried_object    = $post;
		$wp_query->queried_object_id = $post->ID;
		$wp_query->is_singular       = true;
		$wp_query->is_single         = true;
		$wp_query->is_archive        = false;
		$wp_query->is_home           = false;
		$wp_query->is_page           = false;
		$wp_query->post_count        = 1;
		$wp_query->posts             = array( $post );
	}

	/**
	 * Load custom template file for mpd_template post type.
	 *
	 * @since 2.0.0
	 *
	 * @param string $template The template file path.
	 * @return string
	 */
	public function load_template_file( $template ) {
		global $post;

		if ( ! $post || 'mpd_template' !== $post->post_type ) {
			return $template;
		}

		// Check for template in theme first.
		$theme_template = locate_template( array( 'single-mpd_template.php' ) );

		if ( $theme_template ) {
			return $theme_template;
		}

		// Use our plugin template.
		$plugin_template = MAGICAL_PRODUCTS_DISPLAY_DIR . 'includes/templates/views/single-mpd_template.php';

		if ( file_exists( $plugin_template ) ) {
			return $plugin_template;
		}

		return $template;
	}

	/**
	 * Load custom template file via template_include filter.
	 *
	 * This handles cases where single_template doesn't work (e.g., preview mode).
	 *
	 * @since 2.0.0
	 *
	 * @param string $template The template file path.
	 * @return string
	 */
	public function load_template_file_include( $template ) {
		global $post;

		// Check for Elementor preview parameter.
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$elementor_preview_id = isset( $_GET['elementor-preview'] ) ? absint( $_GET['elementor-preview'] ) : 0;

		// Check for WordPress native preview parameters.
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$wp_preview_id = isset( $_GET['preview_id'] ) ? absint( $_GET['preview_id'] ) : 0;
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$is_wp_preview = isset( $_GET['preview'] ) && 'true' === $_GET['preview'];

		// If this is an Elementor preview request for our CPT.
		if ( $elementor_preview_id ) {
			$preview_post = get_post( $elementor_preview_id );
			
			if ( $preview_post && 'mpd_template' === $preview_post->post_type ) {
				// Use our plugin template.
				$plugin_template = MAGICAL_PRODUCTS_DISPLAY_DIR . 'includes/templates/views/single-mpd_template.php';

				if ( file_exists( $plugin_template ) ) {
					return $plugin_template;
				}
			}
		}

		// If this is a WordPress native preview request for our CPT.
		if ( $wp_preview_id && $is_wp_preview ) {
			$preview_post = get_post( $wp_preview_id );
			
			if ( $preview_post && 'mpd_template' === $preview_post->post_type ) {
				// Use our plugin template.
				$plugin_template = MAGICAL_PRODUCTS_DISPLAY_DIR . 'includes/templates/views/single-mpd_template.php';

				if ( file_exists( $plugin_template ) ) {
					return $plugin_template;
				}
			}
		}

		// Check if we're viewing our CPT normally.
		if ( $post && 'mpd_template' === $post->post_type ) {
			// Use our plugin template.
			$plugin_template = MAGICAL_PRODUCTS_DISPLAY_DIR . 'includes/templates/views/single-mpd_template.php';

			if ( file_exists( $plugin_template ) ) {
				return $plugin_template;
			}
		}

		return $template;
	}

	/**
	 * Add Elementor CPT support for our template post type.
	 *
	 * @since 2.0.0
	 *
	 * @param array $cpt_support Array of supported post types.
	 * @return array
	 */
	public function add_elementor_cpt_support( $cpt_support ) {
		$cpt_support[] = 'mpd_template';
		return $cpt_support;
	}

	/**
	 * Add Elementor page templates support for our CPT.
	 *
	 * This allows our CPT to use Elementor's canvas and header-footer templates.
	 *
	 * @since 2.0.0
	 *
	 * @param array    $page_templates Array of page templates.
	 * @param WP_Theme $theme          The theme object.
	 * @param WP_Post  $post           The post object.
	 * @param string   $post_type      The post type.
	 * @return array
	 */
	public function add_elementor_page_templates( $page_templates, $theme, $post, $post_type ) {
		// Add Elementor's page templates to our CPT.
		$page_templates['elementor_canvas']        = __( 'Elementor Canvas', 'magical-products-display' );
		$page_templates['elementor_header_footer'] = __( 'Elementor Full Width', 'magical-products-display' );
		
		return $page_templates;
	}

	/**
	 * Ensure Elementor document support for our templates.
	 *
	 * @since 2.0.0
	 *
	 * @param \Elementor\Core\Documents_Manager $documents_manager Documents manager.
	 * @return void
	 */
	public function ensure_document_support( $documents_manager ) {
		// Register our post type to use the 'wp-page' document type.
		// This is how Elementor knows to treat our CPT like a page.
	}

	/**
	 * Initialize Elementor preview for our templates.
	 *
	 * This ensures the preview iframe loads correctly.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public function init_elementor_preview() {
		// Check if we're previewing our template.
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$post_id = isset( $_GET['elementor-preview'] ) ? absint( $_GET['elementor-preview'] ) : 0;

		if ( ! $post_id ) {
			return;
		}

		$post = get_post( $post_id );

		if ( ! $post || 'mpd_template' !== $post->post_type ) {
			return;
		}

		// Ensure the post has Elementor metadata.
		$edit_mode = get_post_meta( $post_id, '_elementor_edit_mode', true );

		if ( empty( $edit_mode ) ) {
			update_post_meta( $post_id, '_elementor_edit_mode', 'builder' );
		}

		$elementor_data = get_post_meta( $post_id, '_elementor_data', true );

		if ( empty( $elementor_data ) ) {
			update_post_meta( $post_id, '_elementor_data', '[]' );
		}

		$page_template = get_post_meta( $post_id, '_wp_page_template', true );

		if ( empty( $page_template ) ) {
			update_post_meta( $post_id, '_wp_page_template', 'elementor_canvas' );
		}
	}

	/**
	 * Setup template types configuration.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	private function setup_template_types() {
		$this->template_types = array(
			'single-product'  => array(
				'label'       => __( 'Single Product', 'magical-products-display' ),
				'description' => __( 'Template for individual product pages.', 'magical-products-display' ),
				'icon'        => 'eicon-single-product',
				'wc_hook'     => 'woocommerce_before_main_content',
			),
			'archive-product' => array(
				'label'       => __( 'Shop/Archive', 'magical-products-display' ),
				'description' => __( 'Template for shop and product archive pages.', 'magical-products-display' ),
				'icon'        => 'eicon-products',
				'wc_hook'     => 'woocommerce_before_main_content',
			),
			'cart'            => array(
				'label'       => __( 'Cart', 'magical-products-display' ),
				'description' => __( 'Template for the cart page.', 'magical-products-display' ),
				'icon'        => 'eicon-cart',
				'wc_hook'     => 'woocommerce_before_cart',
			),
			'checkout'        => array(
				'label'       => __( 'Checkout', 'magical-products-display' ),
				'description' => __( 'Template for the checkout page.', 'magical-products-display' ),
				'icon'        => 'eicon-checkout',
				'wc_hook'     => 'woocommerce_before_checkout_form',
			),
			'my-account'      => array(
				'label'       => __( 'My Account', 'magical-products-display' ),
				'description' => __( 'Template for my account pages.', 'magical-products-display' ),
				'icon'        => 'eicon-user-circle-o',
				'wc_hook'     => 'woocommerce_before_account_navigation',
			),
			'empty-cart'      => array(
				'label'       => __( 'Empty Cart', 'magical-products-display' ),
				'description' => __( 'Template for empty cart state.', 'magical-products-display' ),
				'icon'        => 'eicon-cart-light',
				'wc_hook'     => 'woocommerce_cart_is_empty',
			),
			'thankyou'        => array(
				'label'       => __( 'Thank You', 'magical-products-display' ),
				'description' => __( 'Template for order received page.', 'magical-products-display' ),
				'icon'        => 'eicon-check-circle',
				'wc_hook'     => 'woocommerce_thankyou',
			),
		);

		/**
		 * Filter template types.
		 *
		 * @since 2.0.0
		 *
		 * @param array $template_types Template types configuration.
		 */
		$this->template_types = apply_filters( 'mpd_template_types', $this->template_types );
	}

	/**
	 * Get template types.
	 *
	 * Lazy-loads template types to ensure translations are available.
	 *
	 * @since 2.0.0
	 *
	 * @return array
	 */
	public function get_template_types() {
		// Lazy-load template types to avoid translation loading before init hook.
		if ( empty( $this->template_types ) ) {
			$this->setup_template_types();
		}
		return $this->template_types;
	}

	/**
	 * Check if Elementor Pro Theme Builder is available.
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	public function has_elementor_pro_theme_builder() {
		if ( null === $this->has_elementor_pro ) {
			$this->has_elementor_pro = class_exists( '\ElementorPro\Modules\ThemeBuilder\Module' );
		}
		return $this->has_elementor_pro;
	}

	/**
	 * Initialize the appropriate template system.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public function init_template_system() {
		if ( $this->has_elementor_pro_theme_builder() ) {
			// For Elementor Pro users - integrate with Theme Builder.
			$this->init_elementor_pro_integration();
		} else {
			// For Elementor Free users - use our custom template system.
			$this->init_custom_template_system();
		}
	}

	/**
	 * Initialize Elementor Pro Theme Builder integration.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	private function init_elementor_pro_integration() {
		// Register our conditions in Elementor Pro Theme Builder.
		add_action( 'elementor/theme/register_conditions', array( $this, 'register_pro_conditions' ) );

		/**
		 * Fires when Elementor Pro integration is initialized.
		 *
		 * @since 2.0.0
		 */
		do_action( 'mpd_elementor_pro_integration_init' );
	}

	/**
	 * Initialize custom template system for Elementor Free.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	private function init_custom_template_system() {
		// Register our custom document type.
		add_action( 'elementor/documents/register', array( $this, 'register_document_type' ) );

		// Initialize template renderer.
		Template_Renderer::instance()->init();

		// Initialize template conditions.
		Template_Conditions::instance()->init();

		/**
		 * Fires when custom template system is initialized.
		 *
		 * @since 2.0.0
		 */
		do_action( 'mpd_custom_template_system_init' );
	}

	/**
	 * Register the template custom post type.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public function register_template_cpt() {
		$labels = array(
			'name'                  => _x( 'Shop Templates', 'Post type general name', 'magical-products-display' ),
			'singular_name'         => _x( 'Shop Template', 'Post type singular name', 'magical-products-display' ),
			'menu_name'             => _x( 'Shop Templates', 'Admin Menu text', 'magical-products-display' ),
			'add_new'               => __( 'Add New', 'magical-products-display' ),
			'add_new_item'          => __( 'Add New Template', 'magical-products-display' ),
			'new_item'              => __( 'New Template', 'magical-products-display' ),
			'edit_item'             => __( 'Edit Template', 'magical-products-display' ),
			'view_item'             => __( 'View Template', 'magical-products-display' ),
			'all_items'             => __( 'All Templates', 'magical-products-display' ),
			'search_items'          => __( 'Search Templates', 'magical-products-display' ),
			'not_found'             => __( 'No templates found.', 'magical-products-display' ),
			'not_found_in_trash'    => __( 'No templates found in Trash.', 'magical-products-display' ),
		);

		$args = array(
			'labels'              => $labels,
			'public'              => true, // Must be true for Elementor to work.
			'publicly_queryable'  => true,
			'exclude_from_search' => true,
			'show_ui'             => true,
			'show_in_menu'        => false, // Managed via React admin.
			'show_in_admin_bar'   => false,
			'show_in_rest'        => true,
			'rest_base'           => 'mpd-templates',
			'capability_type'     => 'page',
			'map_meta_cap'        => true,
			'has_archive'         => false,
			'hierarchical'        => false,
			'supports'            => array( 'title', 'editor', 'author', 'thumbnail', 'custom-fields', 'page-attributes' ),
			'rewrite'             => array(
				'slug'       => 'mpd-template',
				'with_front' => false,
			),
			'query_var'           => true,
		);

		register_post_type( 'mpd_template', $args );

		// Register template type taxonomy.
		register_taxonomy(
			'mpd_template_type',
			'mpd_template',
			array(
				'hierarchical'      => false,
				'public'            => false,
				'show_ui'           => false,
				'show_admin_column' => false,
				'show_in_rest'      => true,
				'rewrite'           => false,
			)
		);

		// Flush rewrite rules on first activation.
		if ( get_option( 'mpd_flush_rewrite_rules', false ) ) {
			flush_rewrite_rules();
			delete_option( 'mpd_flush_rewrite_rules' );
		}
	}

	/**
	 * Register document type for Elementor.
	 *
	 * @since 2.0.0
	 *
	 * @param \Elementor\Core\Documents_Manager $documents_manager Documents manager.
	 * @return void
	 */
	public function register_document_type( $documents_manager ) {
		// Register our custom document type for mpd_template CPT.
		$documents_manager->register_document_type( 'mpd-template', Template_Document::class );
	}

	/**
	 * Register conditions for Elementor Pro Theme Builder.
	 *
	 * @since 2.0.0
	 *
	 * @param \ElementorPro\Modules\ThemeBuilder\Classes\Conditions_Manager $conditions_manager Conditions manager.
	 * @return void
	 */
	public function register_pro_conditions( $conditions_manager ) {
		// Only register if Theme Builder is available.
		if ( ! $this->has_elementor_pro_theme_builder() ) {
			return;
		}

		// Get WooCommerce condition group.
		$woocommerce_condition = $conditions_manager->get_condition( 'woocommerce' );

		if ( ! $woocommerce_condition ) {
			return;
		}

		// We could register additional sub-conditions here if needed.
		// For now, Elementor Pro's built-in WooCommerce conditions are sufficient.
		
		/**
		 * Fires after Pro conditions are registered.
		 *
		 * @since 2.0.0
		 *
		 * @param \ElementorPro\Modules\ThemeBuilder\Classes\Conditions_Manager $conditions_manager Conditions manager.
		 */
		do_action( 'mpd_after_register_pro_conditions', $conditions_manager );
	}

	/**
	 * Display admin notice for Elementor Pro users.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public function elementor_pro_notice() {
		// Only show on our admin pages.
		$screen = get_current_screen();
		if ( ! $screen || false === strpos( $screen->id, 'magical-shop-builder' ) ) {
			return;
		}

		// Only show for Elementor Pro users.
		if ( ! $this->has_elementor_pro_theme_builder() ) {
			return;
		}

		// Check if dismissed.
		if ( get_user_meta( get_current_user_id(), 'mpd_elementor_pro_notice_dismissed', true ) ) {
			return;
		}

		?>
		<div class="notice notice-info is-dismissible mpd-elementor-pro-notice">
			<p>
				<strong><?php esc_html_e( 'Elementor Pro Detected!', 'magical-products-display' ); ?></strong>
				<?php esc_html_e( 'You can create shop templates using Elementor > Theme Builder. Our widgets are available in the Elementor editor for building your templates.', 'magical-products-display' ); ?>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=elementor-app#/site-editor/templates' ) ); ?>" class="button button-small" style="margin-left: 10px;">
					<?php esc_html_e( 'Open Theme Builder', 'magical-products-display' ); ?>
				</a>
			</p>
		</div>
		<?php
	}

	/**
	 * Get templates by type.
	 *
	 * @since 2.0.0
	 *
	 * @param string $type Template type.
	 * @return array Array of template posts.
	 */
	public function get_templates_by_type( $type ) {
		$cache_key = 'mpd_templates_' . sanitize_key( $type );
		$cached    = get_transient( $cache_key );

		if ( false !== $cached ) {
			return $cached;
		}

		$args = array(
			'post_type'      => 'mpd_template',
			'posts_per_page' => -1,
			'post_status'    => 'publish',
			'meta_query'     => array(
				array(
					'key'   => '_mpd_template_type',
					'value' => sanitize_key( $type ),
				),
			),
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
		);

		$templates = get_posts( $args );
		set_transient( $cache_key, $templates, 5 * MINUTE_IN_SECONDS );

		return $templates;
	}

	/**
	 * Flush template cache for all types.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public function flush_template_cache() {
		$types = array(
			'single-product', 'archive-product', 'cart', 'empty-cart',
			'checkout', 'my-account', 'thankyou',
		);
		foreach ( $types as $type ) {
			delete_transient( 'mpd_templates_' . $type );
		}
	}

	/**
	 * Get template for current page.
	 *
	 * @since 2.0.0
	 *
	 * @param string $type Template type.
	 * @return int|false Template ID or false.
	 */
	public function get_template_for_current_page( $type ) {
		$templates = $this->get_templates_by_type( $type );

		if ( empty( $templates ) ) {
			return false;
		}

		// Get the matching template based on conditions.
		$matching_template = Template_Conditions::instance()->get_matching_template( $templates );

		return $matching_template ? $matching_template->ID : false;
	}

	/**
	 * Check if custom templates are enabled.
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	public function is_enabled() {
		$settings = get_option( 'mpd_general_settings', array() );
		return isset( $settings['enable_templates'] ) && 'yes' === $settings['enable_templates'];
	}

	/**
	 * Check if using custom template system (not Elementor Pro).
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	public function is_using_custom_system() {
		return ! $this->has_elementor_pro_theme_builder();
	}
}
