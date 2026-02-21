<?php
/**
 * Template Conditions Handler
 *
 * Handles template condition matching for the custom template system.
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
 * Class Template_Conditions
 *
 * Manages template conditions and matching logic.
 *
 * @since 2.0.0
 */
class Template_Conditions {

	/**
	 * Instance of the class.
	 *
	 * @var Template_Conditions|null
	 */
	private static $instance = null;

	/**
	 * Available condition types.
	 *
	 * @var array
	 */
	private $condition_types = array();

	/**
	 * Get instance.
	 *
	 * @since 2.0.0
	 *
	 * @return Template_Conditions
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
		// Condition types are lazy-loaded to avoid translation calls before init hook.
	}

	/**
	 * Initialize.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public function init() {
		// Register AJAX handlers for condition options.
		add_action( 'wp_ajax_mpd_get_condition_options', array( $this, 'ajax_get_condition_options' ) );
	}

	/**
	 * Setup available condition types.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	private function setup_condition_types() {
		$this->condition_types = array(
			'all' => array(
				'label'       => __( 'All', 'magical-products-display' ),
				'description' => __( 'Apply to all pages of this type.', 'magical-products-display' ),
				'callback'    => array( $this, 'check_all' ),
				'options'     => false,
			),
			'product_type' => array(
				'label'       => __( 'Product Type', 'magical-products-display' ),
				'description' => __( 'Simple, variable, grouped, or external products.', 'magical-products-display' ),
				'callback'    => array( $this, 'check_product_type' ),
				'options'     => 'get_product_type_options',
			),
			'product_category' => array(
				'label'       => __( 'Product Category', 'magical-products-display' ),
				'description' => __( 'Products in specific categories.', 'magical-products-display' ),
				'callback'    => array( $this, 'check_product_category' ),
				'options'     => 'get_product_category_options',
			),
			'product_tag' => array(
				'label'       => __( 'Product Tag', 'magical-products-display' ),
				'description' => __( 'Products with specific tags.', 'magical-products-display' ),
				'callback'    => array( $this, 'check_product_tag' ),
				'options'     => 'get_product_tag_options',
			),
			'specific_product' => array(
				'label'       => __( 'Specific Products', 'magical-products-display' ),
				'description' => __( 'Specific product IDs.', 'magical-products-display' ),
				'callback'    => array( $this, 'check_specific_product' ),
				'options'     => 'get_product_options',
			),
			'product_in_stock' => array(
				'label'       => __( 'Stock Status', 'magical-products-display' ),
				'description' => __( 'Products in stock or out of stock.', 'magical-products-display' ),
				'callback'    => array( $this, 'check_product_stock' ),
				'options'     => 'get_stock_options',
			),
			'product_on_sale' => array(
				'label'       => __( 'On Sale', 'magical-products-display' ),
				'description' => __( 'Products currently on sale.', 'magical-products-display' ),
				'callback'    => array( $this, 'check_product_on_sale' ),
				'options'     => 'get_boolean_options',
			),
			'user_role' => array(
				'label'       => __( 'User Role', 'magical-products-display' ),
				'description' => __( 'Users with specific roles.', 'magical-products-display' ),
				'callback'    => array( $this, 'check_user_role' ),
				'options'     => 'get_user_role_options',
			),
			'user_logged_in' => array(
				'label'       => __( 'Login Status', 'magical-products-display' ),
				'description' => __( 'Logged in or guest users.', 'magical-products-display' ),
				'callback'    => array( $this, 'check_user_logged_in' ),
				'options'     => 'get_login_status_options',
			),
		);

		/**
		 * Filter available condition types.
		 *
		 * @since 2.0.0
		 *
		 * @param array $condition_types Condition types configuration.
		 */
		$this->condition_types = apply_filters( 'mpd_template_condition_types', $this->condition_types );
	}

	/**
	 * Get available condition types.
	 *
	 * Lazy-loads condition types to ensure translations are available.
	 *
	 * @since 2.0.0
	 *
	 * @return array
	 */
	public function get_condition_types() {
		// Lazy-load condition types to avoid translation loading before init hook.
		if ( empty( $this->condition_types ) ) {
			$this->setup_condition_types();
		}
		return $this->condition_types;
	}

	/**
	 * Get matching template from a list of templates.
	 *
	 * @since 2.0.0
	 *
	 * @param array $templates Array of template posts.
	 * @return \WP_Post|false Matching template or false.
	 */
	public function get_matching_template( $templates ) {
		if ( empty( $templates ) ) {
			return false;
		}

		$matched_templates = array();

		foreach ( $templates as $template ) {
			$conditions = get_post_meta( $template->ID, '_mpd_template_conditions', true );

			if ( empty( $conditions ) || ! is_array( $conditions ) ) {
				// No conditions = match all (lowest priority).
				$matched_templates[] = array(
					'template' => $template,
					'priority' => 0,
					'specificity' => 0,
				);
				continue;
			}

			// Check if template matches current context.
			$match_result = $this->check_conditions( $conditions );

			if ( $match_result['matched'] ) {
				$matched_templates[] = array(
					'template'    => $template,
					'priority'    => $this->get_template_priority( $template->ID ),
					'specificity' => $match_result['specificity'],
				);
			}
		}

		if ( empty( $matched_templates ) ) {
			return false;
		}

		// Sort by priority (higher first), then by specificity (higher first).
		usort( $matched_templates, function( $a, $b ) {
			if ( $a['priority'] !== $b['priority'] ) {
				return $b['priority'] - $a['priority'];
			}
			return $b['specificity'] - $a['specificity'];
		} );

		return $matched_templates[0]['template'];
	}

	/**
	 * Check if conditions match current context.
	 *
	 * @since 2.0.0
	 *
	 * @param array $conditions Array of conditions.
	 * @return array Match result with 'matched' and 'specificity' keys.
	 */
	public function check_conditions( $conditions ) {
		$include_matched = false;
		$exclude_matched = false;
		$specificity     = 0;

		foreach ( $conditions as $condition ) {
			$type           = isset( $condition['type'] ) ? $condition['type'] : 'include';
			$condition_name = isset( $condition['condition'] ) ? $condition['condition'] : '';
			$value          = isset( $condition['value'] ) ? $condition['value'] : '';

			if ( empty( $condition_name ) ) {
				continue;
			}

			$result = $this->check_single_condition( $condition_name, $value );

			if ( 'include' === $type ) {
				if ( $result ) {
					$include_matched = true;
					$specificity    += $this->get_condition_specificity( $condition_name, $value );
				}
			} elseif ( 'exclude' === $type ) {
				if ( $result ) {
					$exclude_matched = true;
				}
			}
		}

		// If any exclude condition matches, template doesn't match.
		if ( $exclude_matched ) {
			return array(
				'matched'     => false,
				'specificity' => 0,
			);
		}

		// If no include conditions defined, consider it a match.
		$has_include = false;
		foreach ( $conditions as $condition ) {
			if ( 'include' === ( $condition['type'] ?? 'include' ) ) {
				$has_include = true;
				break;
			}
		}

		return array(
			'matched'     => $has_include ? $include_matched : true,
			'specificity' => $specificity,
		);
	}

	/**
	 * Check a single condition.
	 *
	 * @since 2.0.0
	 *
	 * @param string $condition_name Condition name.
	 * @param mixed  $value          Condition value.
	 * @return bool
	 */
	private function check_single_condition( $condition_name, $value ) {
		$condition_types = $this->get_condition_types();

		if ( ! isset( $condition_types[ $condition_name ] ) ) {
			return false;
		}

		$callback = $condition_types[ $condition_name ]['callback'];

		if ( is_callable( $callback ) ) {
			return call_user_func( $callback, $value );
		}

		return false;
	}

	/**
	 * Get condition specificity score.
	 *
	 * Higher specificity = more specific match.
	 *
	 * @since 2.0.0
	 *
	 * @param string $condition_name Condition name.
	 * @param mixed  $value          Condition value.
	 * @return int
	 */
	private function get_condition_specificity( $condition_name, $value ) {
		$scores = array(
			'all'              => 1,
			'product_type'     => 5,
			'product_category' => 10,
			'product_tag'      => 10,
			'specific_product' => 100,
			'product_in_stock' => 3,
			'product_on_sale'  => 3,
			'user_role'        => 5,
			'user_logged_in'   => 2,
		);

		$base_score = isset( $scores[ $condition_name ] ) ? $scores[ $condition_name ] : 1;

		// Specific values are more specific.
		if ( is_array( $value ) ) {
			$base_score += count( $value );
		}

		return $base_score;
	}

	/**
	 * Get template priority.
	 *
	 * @since 2.0.0
	 *
	 * @param int $template_id Template ID.
	 * @return int
	 */
	private function get_template_priority( $template_id ) {
		$priority = get_post_meta( $template_id, '_mpd_template_priority', true );
		return $priority ? absint( $priority ) : 10;
	}

	// =========================================================================
	// Condition Check Callbacks
	// =========================================================================

	/**
	 * Check 'all' condition (always matches).
	 *
	 * @since 2.0.0
	 *
	 * @param mixed $value Condition value (unused).
	 * @return bool
	 */
	public function check_all( $value ) {
		return true;
	}

	/**
	 * Check product type condition.
	 *
	 * @since 2.0.0
	 *
	 * @param mixed $value Product type(s).
	 * @return bool
	 */
	public function check_product_type( $value ) {
		if ( ! class_exists( 'WooCommerce' ) || ! is_singular( 'product' ) ) {
			return false;
		}

		global $product;
		if ( ! $product ) {
			$product = wc_get_product( get_the_ID() );
		}

		if ( ! $product ) {
			return false;
		}

		$types = is_array( $value ) ? $value : array( $value );
		return in_array( $product->get_type(), $types, true );
	}

	/**
	 * Check product category condition.
	 *
	 * @since 2.0.0
	 *
	 * @param mixed $value Category ID(s) or slug(s).
	 * @return bool
	 */
	public function check_product_category( $value ) {
		if ( ! is_singular( 'product' ) && ! is_product_category() ) {
			return false;
		}

		if ( is_product_category() ) {
			$queried = get_queried_object();
			$term_id = $queried ? $queried->term_id : 0;
			$values  = is_array( $value ) ? $value : array( $value );

			foreach ( $values as $v ) {
				if ( absint( $v ) === $term_id || $v === $queried->slug ) {
					return true;
				}
			}
			return false;
		}

		// Single product.
		$values = is_array( $value ) ? $value : array( $value );
		foreach ( $values as $v ) {
			if ( has_term( $v, 'product_cat', get_the_ID() ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Check product tag condition.
	 *
	 * @since 2.0.0
	 *
	 * @param mixed $value Tag ID(s) or slug(s).
	 * @return bool
	 */
	public function check_product_tag( $value ) {
		if ( ! is_singular( 'product' ) && ! is_product_tag() ) {
			return false;
		}

		if ( is_product_tag() ) {
			$queried = get_queried_object();
			$term_id = $queried ? $queried->term_id : 0;
			$values  = is_array( $value ) ? $value : array( $value );

			foreach ( $values as $v ) {
				if ( absint( $v ) === $term_id || $v === $queried->slug ) {
					return true;
				}
			}
			return false;
		}

		// Single product.
		$values = is_array( $value ) ? $value : array( $value );
		foreach ( $values as $v ) {
			if ( has_term( $v, 'product_tag', get_the_ID() ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Check specific product condition.
	 *
	 * @since 2.0.0
	 *
	 * @param mixed $value Product ID(s).
	 * @return bool
	 */
	public function check_specific_product( $value ) {
		if ( ! is_singular( 'product' ) ) {
			return false;
		}

		$product_ids = is_array( $value ) ? array_map( 'absint', $value ) : array( absint( $value ) );
		return in_array( get_the_ID(), $product_ids, true );
	}

	/**
	 * Check product stock status condition.
	 *
	 * @since 2.0.0
	 *
	 * @param mixed $value Stock status ('instock' or 'outofstock').
	 * @return bool
	 */
	public function check_product_stock( $value ) {
		if ( ! class_exists( 'WooCommerce' ) || ! is_singular( 'product' ) ) {
			return false;
		}

		$the_product = wc_get_product( get_the_ID() );

		if ( ! $the_product ) {
			return false;
		}

		$status = $the_product->is_in_stock() ? 'instock' : 'outofstock';
		return $status === $value;
	}

	/**
	 * Check product on sale condition.
	 *
	 * @since 2.0.0
	 *
	 * @param mixed $value Boolean ('yes' or 'no').
	 * @return bool
	 */
	public function check_product_on_sale( $value ) {
		if ( ! class_exists( 'WooCommerce' ) || ! is_singular( 'product' ) ) {
			return false;
		}

		$the_product = wc_get_product( get_the_ID() );

		if ( ! $the_product ) {
			return false;
		}

		$on_sale = $the_product->is_on_sale();
		return ( 'yes' === $value ) === $on_sale;
	}

	/**
	 * Check user role condition.
	 *
	 * @since 2.0.0
	 *
	 * @param mixed $value User role(s).
	 * @return bool
	 */
	public function check_user_role( $value ) {
		if ( ! is_user_logged_in() ) {
			return false;
		}

		$user  = wp_get_current_user();
		$roles = is_array( $value ) ? $value : array( $value );

		foreach ( $roles as $role ) {
			if ( in_array( $role, $user->roles, true ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Check user logged in status.
	 *
	 * @since 2.0.0
	 *
	 * @param mixed $value Login status ('logged_in' or 'logged_out').
	 * @return bool
	 */
	public function check_user_logged_in( $value ) {
		$logged_in = is_user_logged_in();
		return ( 'logged_in' === $value ) === $logged_in;
	}

	// =========================================================================
	// Options Getters
	// =========================================================================

	/**
	 * Get product type options.
	 *
	 * @since 2.0.0
	 *
	 * @return array
	 */
	public function get_product_type_options() {
		return array(
			'simple'   => __( 'Simple Product', 'magical-products-display' ),
			'variable' => __( 'Variable Product', 'magical-products-display' ),
			'grouped'  => __( 'Grouped Product', 'magical-products-display' ),
			'external' => __( 'External Product', 'magical-products-display' ),
		);
	}

	/**
	 * Get product category options.
	 *
	 * @since 2.0.0
	 *
	 * @return array
	 */
	public function get_product_category_options() {
		$categories = get_terms( array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => false,
		) );

		$options = array();
		if ( ! is_wp_error( $categories ) ) {
			foreach ( $categories as $category ) {
				$options[ $category->term_id ] = $category->name;
			}
		}

		return $options;
	}

	/**
	 * Get product tag options.
	 *
	 * @since 2.0.0
	 *
	 * @return array
	 */
	public function get_product_tag_options() {
		$tags = get_terms( array(
			'taxonomy'   => 'product_tag',
			'hide_empty' => false,
		) );

		$options = array();
		if ( ! is_wp_error( $tags ) ) {
			foreach ( $tags as $tag ) {
				$options[ $tag->term_id ] = $tag->name;
			}
		}

		return $options;
	}

	/**
	 * Get product options (for specific product selection).
	 *
	 * @since 2.0.0
	 *
	 * @return array
	 */
	public function get_product_options() {
		if ( ! class_exists( 'WooCommerce' ) ) {
			return array();
		}

		$products = wc_get_products( array(
			'limit'  => 100,
			'status' => 'publish',
		) );

		$options = array();
		foreach ( $products as $product ) {
			$options[ $product->get_id() ] = $product->get_name();
		}

		return $options;
	}

	/**
	 * Get stock status options.
	 *
	 * @since 2.0.0
	 *
	 * @return array
	 */
	public function get_stock_options() {
		return array(
			'instock'    => __( 'In Stock', 'magical-products-display' ),
			'outofstock' => __( 'Out of Stock', 'magical-products-display' ),
		);
	}

	/**
	 * Get boolean options.
	 *
	 * @since 2.0.0
	 *
	 * @return array
	 */
	public function get_boolean_options() {
		return array(
			'yes' => __( 'Yes', 'magical-products-display' ),
			'no'  => __( 'No', 'magical-products-display' ),
		);
	}

	/**
	 * Get user role options.
	 *
	 * @since 2.0.0
	 *
	 * @return array
	 */
	public function get_user_role_options() {
		global $wp_roles;

		$options = array();
		foreach ( $wp_roles->roles as $role_id => $role ) {
			$options[ $role_id ] = $role['name'];
		}

		return $options;
	}

	/**
	 * Get login status options.
	 *
	 * @since 2.0.0
	 *
	 * @return array
	 */
	public function get_login_status_options() {
		return array(
			'logged_in'  => __( 'Logged In', 'magical-products-display' ),
			'logged_out' => __( 'Logged Out', 'magical-products-display' ),
		);
	}

	/**
	 * AJAX handler to get condition options.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public function ajax_get_condition_options() {
		check_ajax_referer( 'mpd_admin_nonce', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( __( 'Permission denied.', 'magical-products-display' ) );
		}

		$condition = isset( $_POST['condition'] ) ? sanitize_key( $_POST['condition'] ) : '';

		$condition_types = $this->get_condition_types();

		if ( empty( $condition ) || ! isset( $condition_types[ $condition ] ) ) {
			wp_send_json_error( __( 'Invalid condition type.', 'magical-products-display' ) );
		}

		$options_callback = $condition_types[ $condition ]['options'];

		if ( ! $options_callback ) {
			wp_send_json_success( array() );
		}

		if ( is_callable( array( $this, $options_callback ) ) ) {
			$options = call_user_func( array( $this, $options_callback ) );
			wp_send_json_success( $options );
		}

		wp_send_json_success( array() );
	}
}
