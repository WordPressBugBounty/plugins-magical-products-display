<?php
/**
 * Magical addons functions
 *
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'mgproducts_display_get_allowed_html_tags' ) ) {
function mgproducts_display_get_allowed_html_tags()
{
    $allowed_html = [
        'b' => [],
        'i' => [],
        'u' => [],
        'em' => [],
        'br' => [],
        'abbr' => [
            'title' => [],
        ],
        'span' => [
            'class' => [],
        ],
        'strong' => [],
    ];

    $allowed_html['a'] = [
        'href' => [],
        'title' => [],
        'class' => [],
        'id' => [],
    ];

    return $allowed_html;
}
}

if ( ! function_exists( 'mgproducts_display_kses_tags' ) ) {
function mgproducts_display_kses_tags($string = '')
{
    return wp_kses($string, mgproducts_display_get_allowed_html_tags());
}
}

/**
 * Check elementor version
 *
 * @param string $version
 * @param string $operator
 * @return bool
 */
if ( ! function_exists( 'mgproducts_display_elementor_version_check' ) ) {
function mgproducts_display_elementor_version_check($operator = '<', $version = '2.6.0')
{
    return defined('ELEMENTOR_VERSION') && version_compare(ELEMENTOR_VERSION, $version, $operator);
}
}

/**
 *  Taxonomy List
 * @return array
 */
if ( ! function_exists( 'mgproducts_display_taxonomy_list' ) ) {
function mgproducts_display_taxonomy_list($taxonomy = 'product_cat', $getvalue = 'slug')
{
    $terms = get_terms(array(
        'taxonomy' => $taxonomy,
        'hide_empty' => true,
    ));

    if (!empty($terms) && !is_wp_error($terms)) {
        foreach ($terms as $term) {
            $options[$term->slug] = $term->name;
        }
        return $options;
    }

    return array();
}
}

/* 
* Category list
* return first one
* @deprecated 2.0.0 Unused function — kept for backward compatibility.
*/
if ( ! function_exists( 'mgproducts_display_pcatlist' ) ) {
function mgproducts_display_pcatlist($id = null, $taxonomy = 'product_cat', $limit = 1)
{
    _deprecated_function( __FUNCTION__, '2.0.0' );
    return;
}
}

/**
 * Get Post List
 * return array
 */
if ( ! function_exists( 'mgproducts_display_product_name' ) ) {
function mgproducts_display_product_name($post_type = 'product')
{
    $options = array();
    $options['0'] = __('Select', 'magical-products-display');
    // $perpage = mgproducts_display_get_option( 'loadproductlimit', 'mgproducts_display_others_tabs', '20' );
    $all_post = array('posts_per_page' => 500, 'post_type' => $post_type);
    $post_terms = get_posts($all_post);
    if (!empty($post_terms) && !is_wp_error($post_terms)) {
        foreach ($post_terms as $term) {
            $options[$term->ID] = $term->post_title;
        }
    }

    return $options;
}
}

// Customize rating html
if (!function_exists('mgproducts_display_wc_get_rating_html')) {
    function mgproducts_display_wc_get_rating_html($mgpde_class = '')
    {
        if (get_option('woocommerce_enable_review_rating') === 'no') {
            return;
        }
        global $product;
        $rating_count = $product->get_rating_count();
        $review_count = $product->get_review_count();
        $average      = $product->get_average_rating();
        //   if ( $rating_count > 0 ) {
        $rating_whole = $average / 5 * 100;
        $wrapper_class = is_single() ? 'rating-number' : 'top-rated-rating';
        ob_start();
?>
        <div class="mgpde-rating">
            <div class="mgpdeg-product-rating <?php echo esc_attr($mgpde_class); ?>">
                <div class="<?php echo esc_attr($wrapper_class); ?>">
                    <span class="wd-product-ratting">
                        <span class="wd-product-user-ratting" style="width: <?php echo esc_attr($rating_whole); ?>%;">
                            <i class="eicon-star"></i>
                            <i class="eicon-star"></i>
                            <i class="eicon-star"></i>
                            <i class="eicon-star"></i>
                            <i class="eicon-star"></i>
                        </span>
                        <i class="eicon-star-o"></i>
                        <i class="eicon-star-o"></i>
                        <i class="eicon-star-o"></i>
                        <i class="eicon-star-o"></i>
                        <i class="eicon-star-o"></i>
                    </span>
                </div>
            </div>
        </div>
        <?php
        $html = ob_get_clean();
        //   } else { $html  = ''; }
        return $html;
    }
}
// Customize rating html
if (!function_exists('mgproducts_display_wc_empty_rating_html')) {
    function mgproducts_display_wc_empty_rating_html()
    {
        if (get_option('woocommerce_enable_review_rating') === 'no') {
            return;
        }
        global $product;
        $rating_count = $product->get_rating_count();
        if ($rating_count < 1) {
        ?>
            <div class="mgp-display-no-rating"></div>
        <?php
        }
    }
}
// Customize rating html
if (!function_exists('mgproducts_display_wc_rating_number')) {
    function mgproducts_display_wc_rating_number($text = 'Reviews')
    {
        if (get_option('woocommerce_enable_review_rating') === 'no') {
            return;
        }
        global $product;
        $rating_count = $product->get_rating_count();
        if ($rating_count > 0) {
            $count_text = $rating_count . ' ' . $text;
            echo '<span class="mgp-rating-count">(' . esc_html($count_text) . ')</span>';
        } else {
            $count_text_ziro = '0 ' . $text;

            echo '<span class="mgp-rating-count">(' . esc_html($count_text_ziro) . ')</span>';
        }
    }
}

/* 
* Category list
* return category based on type
* @param int $id Product ID
* @param string $taxonomy Taxonomy name (default: product_cat)
* @param int $limit Number of categories to display
* @param string $type Display type: 'first', 'random', 'selected'
* @param array $selected_categories Array of selected category slugs (for 'selected' type)
*/
if ( ! function_exists( 'mgproducts_display_product_category' ) ) {
function mgproducts_display_product_category($id = null, $taxonomy = 'product_cat', $limit = 1, $type = 'selected', $selected_categories = [])
{
    $terms = get_the_terms($id, $taxonomy);
    
    if (is_wp_error($terms))
        return $terms;

    if (empty($terms))
        return false;

    // Filter terms based on type
    $filtered_terms = [];
    
    switch ($type) {
        case 'selected':
            // Show only categories that match selected categories
            if (!empty($selected_categories)) {
                foreach ($terms as $term) {
                    if (in_array($term->slug, $selected_categories)) {
                        $filtered_terms[] = $term;
                    }
                }
            }
            // Fallback to first category if no match found
            if (empty($filtered_terms)) {
                $filtered_terms = [$terms[0]];
            }
            break;
            
        case 'random':
            // Shuffle and get random category
            $terms_array = (array) $terms;
            shuffle($terms_array);
            $filtered_terms = $terms_array;
            break;
            
        case 'first':
        default:
            // Show first category (default behavior)
            $filtered_terms = $terms;
            break;
    }

    // Display categories
    $i = 0;
    foreach ($filtered_terms as $term) {
        $i++;
        $link = get_term_link($term, $taxonomy);
        if (is_wp_error($link)) {
            return $link;
        }
        echo '<a href="' . esc_url($link) . '">' . esc_html($term->name) . '</a>';
        if ($i == $limit) {
            break;
        } else {
            continue;
        }
    }
}
}

if ( ! function_exists( 'mgproducts_display_products_badge' ) ) {
function mgproducts_display_products_badge()
{
    global $product;

    if ($product->is_on_sale()) {
        ?>
        <div class="mgp-display-badge">
            <?php esc_html_e('Sale!', 'magical-products-display'); ?>
        </div>
    <?php
    } elseif ($product->is_featured()) {
    ?>
        <div class="mgp-display-badge">
            <?php esc_html_e('Featured!', 'magical-products-display'); ?>
        </div>


    <?php
    }
}
}

/**
 * @deprecated 2.0.0 Use mgproducts_display_get_allowed_html_tags() instead.
 */
if ( ! function_exists( 'mgproducts_allowed_html_tags' ) ) {
function mgproducts_allowed_html_tags()
{
    _deprecated_function( __FUNCTION__, '2.0.0', 'mgproducts_display_get_allowed_html_tags()' );
    return mgproducts_display_get_allowed_html_tags();
}
}

/**
 * @deprecated 2.0.0 Use mgproducts_display_kses_tags() instead.
 */
if ( ! function_exists( 'mgproducts_kses_tags' ) ) {
function mgproducts_kses_tags($string = '')
{
    _deprecated_function( __FUNCTION__, '2.0.0', 'mgproducts_display_kses_tags()' );
    return mgproducts_display_kses_tags($string);
}
}


if ( ! function_exists( 'mgproducts_mpupdate__product_views_count' ) ) {
function mgproducts_mpupdate__product_views_count()
{
    if (is_singular('product')) {
        // Skip bots and crawlers.
        if ( ! empty( $_SERVER['HTTP_USER_AGENT'] ) && preg_match( '/bot|crawl|slurp|spider|mediapartners|facebookexternalhit/i', sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ) ) ) {
            return;
        }
        $post_id = get_queried_object_id();
        $views_count = get_post_meta($post_id, '_product_views_count', true);
        $views_count = ($views_count) ? $views_count + 1 : 1;
        update_post_meta($post_id, '_product_views_count', $views_count);
    }
}
add_action('template_redirect', 'mgproducts_mpupdate__product_views_count');
}


// Pro only text 

if ( ! function_exists( 'mpd_display_pro_only_text' ) ) {
function mpd_display_pro_only_text()
{
    $pro_only_text = esc_html__('Pro Only', 'magical-products-display');
    $pro_only = '<strong style="color:red;font-size:80%">(' . $pro_only_text . ')</strong>';
    if (get_option('mgppro_is_active', 'no') === 'yes') {
        return false;
    } else {
        return $pro_only;
    }
}
}


// widget help pro link 
if (!function_exists('mpd_goprolink')) :
    function mpd_goprolink($texts)
    {
        ob_start();

    ?>
        <div class="elementor-nerd-box">
            <img class="elementor-nerd-box-icon" src="<?php echo esc_url(ELEMENTOR_ASSETS_URL . 'images/go-pro.svg'); ?>" />
            <div class="elementor-nerd-box-title"><?php echo esc_html($texts['title']); ?></div>
            <div class="elementor-nerd-box-message"><?php echo esc_html($texts['massage']); ?></div>
            <?php
            // Show a `Go Pro` button only if the user doesn't have Pro.
            if ($texts['link']) { ?>
                <a class="elementor-nerd-box-link elementor-button elementor-button-default elementor-button-go-pro" href="<?php echo esc_url($texts['link']); ?>" target="_blank">
                    <?php echo esc_html__('UPGRADE NOW', 'magical-products-display'); ?>
                </a>
            <?php } ?>
        </div>
<?php
        return ob_get_clean();
    }
endif;


if ( ! function_exists( 'mpd_get_price_range' ) ) {
function mpd_get_price_range()
{
    // Try to get cached min and max prices
    $cached_price_range = wp_cache_get('mpd_price_range', 'mpd_cache_group');

    // If cache is empty, run the database queries
    if ($cached_price_range === false) {
        global $wpdb;
        
        $min_price = null;
        $max_price = null;
        
        // Try to use WooCommerce's lookup table first (WC 3.6+)
        $lookup_table = $wpdb->prefix . 'wc_product_meta_lookup';
        $table_exists = $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", $lookup_table ) );
        
        if ( $table_exists ) {
            // Get min/max from the lookup table (more reliable for variable products)
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
            $prices = $wpdb->get_row(
                $wpdb->prepare(
                    "SELECT MIN( FLOOR( min_price ) ) as min_price, MAX( CEILING( max_price ) ) as max_price
                    FROM {$lookup_table}
                    WHERE min_price > %d OR max_price > %d",
                    0,
                    0
                )
            );
            
            if ( $prices ) {
                $min_price = $prices->min_price;
                $max_price = $prices->max_price;
            }
        }
        
        // Fallback to meta query if lookup table doesn't exist or returned no results
        if ( ! $min_price || ! $max_price ) {
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
            $min_price = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT MIN( FLOOR( pm.meta_value ) )
                    FROM {$wpdb->postmeta} pm
                    INNER JOIN {$wpdb->posts} p ON pm.post_id = p.ID
                    WHERE pm.meta_key = %s
                    AND pm.meta_value > ''
                    AND p.post_type = %s
                    AND p.post_status = %s",
                    '_price',
                    'product',
                    'publish'
                )
            );
            
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
            $max_price = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT MAX( CEILING( pm.meta_value ) )
                    FROM {$wpdb->postmeta} pm
                    INNER JOIN {$wpdb->posts} p ON pm.post_id = p.ID
                    WHERE pm.meta_key = %s
                    AND pm.meta_value > ''
                    AND p.post_type = %s
                    AND p.post_status = %s",
                    '_price',
                    'product',
                    'publish'
                )
            );
        }
        
        // Fallback if no price is found
        if (!$min_price) {
            $min_price = 0;
        }
        if (!$max_price) {
            $max_price = 1000; // Set a default max if no products exist
        }

        // Prepare the array for min and max prices (ensure they're floats)
        $price_range = array('min' => floor( floatval( $min_price ) ), 'max' => ceil( floatval( $max_price ) ));

        // Store the result in the cache for future use
        wp_cache_set('mpd_price_range', $price_range, 'mpd_cache_group', 3600); // Cache for 1 hour
    } else {
        // Use cached data
        $price_range = $cached_price_range;
    }

    return $price_range;
}
}


if ( ! function_exists( 'mprd_validate_html_tag' ) ) {
function mprd_validate_html_tag($tag, $default_tag = 'h2', $allowed_tags = array()) {
    // Use the provided whitelist or fall back to a predefined set of safe tags
    $safe_tags = !empty($allowed_tags) ? $allowed_tags : array(
        'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span', 'p'
    );

    // Make sure we're working with a string
    $tag = is_string($tag) ? strtolower(trim($tag)) : '';

    // Return the validated tag or default
    return in_array($tag, $safe_tags, true) ? $tag : $default_tag;
}
}

/**
 * Add product image to cart item name in order review for AJAX calls.
 *
 * @since 2.5.0
 *
 * @param string $name          Product name HTML.
 * @param array  $cart_item     Cart item data.
 * @param string $cart_item_key Cart item key.
 * @return string Modified product name with image.
 */
if ( ! function_exists( 'mpd_add_product_image_to_order_review' ) ) {
function mpd_add_product_image_to_order_review( $name, $cart_item, $cart_item_key ) {
    // Avoid double-wrapping if already processed.
    if ( strpos( $name, 'mpd-msc-product-image' ) !== false ) {
        return $name;
    }

    $_product = $cart_item['data'] ?? null;
    if ( ! $_product || ! is_object( $_product ) ) {
        return $name;
    }

    // Get settings from transient.
    $image_settings = get_transient( 'mpd_msc_image_settings' );
    $image_size = ( $image_settings && isset( $image_settings['image_size'] ) ) ? absint( $image_settings['image_size'] ) : 50;
    $show_qty_controls = ( $image_settings && isset( $image_settings['show_qty_controls'] ) ) ? $image_settings['show_qty_controls'] : 'no';

    // Get product image.
    $thumbnail = $_product->get_image( array( $image_size * 2, $image_size * 2 ) );

    // Fallback to placeholder if no image.
    if ( empty( $thumbnail ) || strpos( $thumbnail, 'src=""' ) !== false ) {
        $thumbnail = '<img src="' . esc_url( wc_placeholder_img_src( 'thumbnail' ) ) . '" alt="' . esc_attr( $_product->get_name() ) . '">';
    }

    // Build the product output with image.
    $output = '<span class="mpd-msc-product-image">' . $thumbnail . '</span>';
    $output .= '<span class="mpd-msc-product-info">';
    $output .= '<span class="mpd-msc-product-name">' . $name . '</span>';
    
    // Only add quantity display if NOT using quantity controls (those are added in template).
    if ( 'yes' !== $show_qty_controls || $_product->is_sold_individually() ) {
        $quantity = isset( $cart_item['quantity'] ) ? absint( $cart_item['quantity'] ) : 1;
        $output .= '<span class="mpd-msc-product-qty">× ' . $quantity . '</span>';
    }
    
    $output .= '</span>';

    return $output;
}
}

/**
 * AJAX handler to refresh order review content.
 *
 * @since 2.0.0
 */
if ( ! function_exists( 'mpd_refresh_order_review_ajax' ) ) {
function mpd_refresh_order_review_ajax() {
    // Verify nonce for security.
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'mpd-checkout-nonce' ) ) {
        echo '<!-- Security check failed -->';
        wp_die();
    }

    // Check if WooCommerce is active.
    if ( ! class_exists( 'WooCommerce' ) || ! function_exists( 'WC' ) ) {
        echo '<!-- WooCommerce not active -->';
        wp_die();
    }

    // Initialize WooCommerce session if needed.
    if ( ! WC()->session ) {
        WC()->session = new \WC_Session_Handler();
        WC()->session->init();
    }

    // Make sure cart is loaded.
    if ( ! WC()->cart ) {
        echo '<!-- Cart not available -->';
        wp_die();
    }

    // Ensure cart is calculated.
    WC()->cart->calculate_totals();

    // Check if woocommerce_order_review function exists.
    if ( ! function_exists( 'woocommerce_order_review' ) ) {
        // Include the checkout functions if not loaded.
        if ( file_exists( WC()->plugin_path() . '/includes/wc-template-functions.php' ) ) {
            include_once WC()->plugin_path() . '/includes/wc-template-functions.php';
        }
    }

    // Output the order review HTML.
    ob_start();
    
    // Add product image filter if enabled.
    $image_settings = get_transient( 'mpd_msc_image_settings' );
    if ( $image_settings && isset( $image_settings['show_images'] ) && 'yes' === $image_settings['show_images'] ) {
        add_filter( 'woocommerce_cart_item_name', 'mpd_add_product_image_to_order_review', 10, 3 );
    }
    
    if ( function_exists( 'woocommerce_order_review' ) ) {
        woocommerce_order_review();
    } else {
        // Fallback: manually include the template.
        wc_get_template( 'checkout/review-order.php', array(
            'checkout' => WC()->checkout(),
        ) );
    }
    
    // Remove the filter after use.
    remove_filter( 'woocommerce_cart_item_name', 'mpd_add_product_image_to_order_review', 10 );
    
    $html = ob_get_clean();

    // Return the HTML.
    echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- WooCommerce template HTML
    wp_die();
}
add_action( 'wp_ajax_mpd_refresh_order_review', 'mpd_refresh_order_review_ajax' );
add_action( 'wp_ajax_nopriv_mpd_refresh_order_review', 'mpd_refresh_order_review_ajax' );
}

/**
 * AJAX handler to update cart item quantity.
 *
 * @since 2.0.0
 */
if ( ! function_exists( 'mpd_update_cart_quantity_ajax' ) ) {
function mpd_update_cart_quantity_ajax() {
    // Verify nonce for security.
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'mpd-checkout-nonce' ) ) {
        wp_send_json_error( array( 'message' => __( 'Security check failed', 'magical-products-display' ) ) );
    }

    // Check if WooCommerce is active.
    if ( ! class_exists( 'WooCommerce' ) || ! function_exists( 'WC' ) ) {
        wp_send_json_error( array( 'message' => __( 'WooCommerce not active', 'magical-products-display' ) ) );
    }

    // Get cart item key and quantity.
    $cart_item_key = isset( $_POST['cart_item_key'] ) ? sanitize_text_field( wp_unslash( $_POST['cart_item_key'] ) ) : '';
    $quantity      = isset( $_POST['quantity'] ) ? absint( $_POST['quantity'] ) : 0;

    if ( empty( $cart_item_key ) ) {
        wp_send_json_error( array( 'message' => __( 'Invalid cart item', 'magical-products-display' ) ) );
    }

    // Initialize WooCommerce session if needed.
    if ( ! WC()->session ) {
        WC()->session = new \WC_Session_Handler();
        WC()->session->init();
    }

    // Make sure cart is loaded.
    if ( ! WC()->cart ) {
        wp_send_json_error( array( 'message' => __( 'Cart not available', 'magical-products-display' ) ) );
    }

    // Get current cart item.
    $cart_item = WC()->cart->get_cart_item( $cart_item_key );
    
    if ( ! $cart_item ) {
        wp_send_json_error( array( 'message' => __( 'Cart item not found', 'magical-products-display' ) ) );
    }

    // Update quantity or remove if 0.
    if ( $quantity <= 0 ) {
        WC()->cart->remove_cart_item( $cart_item_key );
    } else {
        WC()->cart->set_quantity( $cart_item_key, $quantity, true );
    }

    // Recalculate cart totals.
    WC()->cart->calculate_totals();

    // Build response with updated cart data.
    $response = array(
        'success'       => true,
        'cart_count'    => WC()->cart->get_cart_contents_count(),
        'cart_total'    => WC()->cart->get_cart_total(),
        'cart_subtotal' => WC()->cart->get_cart_subtotal(),
        'fragments'     => array(),
    );

    // Get updated order review HTML.
    ob_start();
    
    // Add product image filter if enabled.
    $image_settings = get_transient( 'mpd_msc_image_settings' );
    if ( $image_settings && isset( $image_settings['show_images'] ) && 'yes' === $image_settings['show_images'] ) {
        add_filter( 'woocommerce_cart_item_name', 'mpd_add_product_image_to_order_review', 10, 3 );
    }
    
    if ( function_exists( 'woocommerce_order_review' ) ) {
        woocommerce_order_review();
    } else {
        wc_get_template( 'checkout/review-order.php', array(
            'checkout' => WC()->checkout(),
        ) );
    }
    
    // Remove the filter after use.
    remove_filter( 'woocommerce_cart_item_name', 'mpd_add_product_image_to_order_review', 10 );
    
    $response['order_review'] = ob_get_clean();

    // Trigger WooCommerce cart fragments update.
    do_action( 'woocommerce_cart_updated' );

    wp_send_json_success( $response );
}
add_action( 'wp_ajax_mpd_update_cart_quantity', 'mpd_update_cart_quantity_ajax' );
add_action( 'wp_ajax_nopriv_mpd_update_cart_quantity', 'mpd_update_cart_quantity_ajax' );
}

/**
 * Add product image filter for WooCommerce checkout AJAX updates.
 *
 * Hooks into WooCommerce's update_order_review AJAX action to ensure
 * product images persist in the order summary.
 *
 * @since 2.5.0
 */
if ( ! function_exists( 'mpd_setup_checkout_image_filter' ) ) {
function mpd_setup_checkout_image_filter() {
    // Only run during WooCommerce AJAX calls on checkout.
    if ( ! wp_doing_ajax() ) {
        return;
    }

    // Check if image settings are enabled.
    $image_settings = get_transient( 'mpd_msc_image_settings' );
    if ( $image_settings && isset( $image_settings['show_images'] ) && 'yes' === $image_settings['show_images'] ) {
        add_filter( 'woocommerce_cart_item_name', 'mpd_add_product_image_to_order_review', 10, 3 );
    }
}
add_action( 'woocommerce_checkout_update_order_review', 'mpd_setup_checkout_image_filter', 5 );
}

/**
 * Global storage for MPD checkout widget settings.
 *
 * @since 2.0.0
 */
global $mpd_checkout_field_settings;
$mpd_checkout_field_settings = array(
    'billing'  => null,
    'shipping' => null,
);

/**
 * Store billing form settings when widget renders.
 *
 * @since 2.0.0
 *
 * @param array $settings Widget settings.
 */
if ( ! function_exists( 'mpd_store_billing_form_settings' ) ) {
function mpd_store_billing_form_settings( $settings ) {
    global $mpd_checkout_field_settings;
    $mpd_checkout_field_settings['billing'] = $settings;
    
    // Also save to transient for AJAX requests (checkout validation).
    $checkout_page_id = wc_get_page_id( 'checkout' );
    if ( $checkout_page_id ) {
        set_transient( 'mpd_billing_settings_' . $checkout_page_id, $settings, HOUR_IN_SECONDS );
    }
}
}

/**
 * Store shipping form settings when widget renders.
 *
 * @since 2.0.0
 *
 * @param array $settings Widget settings.
 */
if ( ! function_exists( 'mpd_store_shipping_form_settings' ) ) {
function mpd_store_shipping_form_settings( $settings ) {
    global $mpd_checkout_field_settings;
    $mpd_checkout_field_settings['shipping'] = $settings;
    
    // Also save to transient for AJAX requests (checkout validation).
    $checkout_page_id = wc_get_page_id( 'checkout' );
    if ( $checkout_page_id ) {
        set_transient( 'mpd_shipping_settings_' . $checkout_page_id, $settings, HOUR_IN_SECONDS );
    }
}
}

/**
 * Get billing form settings (from global or transient).
 *
 * @since 2.0.0
 *
 * @return array|null Settings or null.
 */
if ( ! function_exists( 'mpd_get_billing_form_settings' ) ) {
function mpd_get_billing_form_settings() {
    global $mpd_checkout_field_settings;
    
    // Always prefer the global (current request) settings.
    if ( ! empty( $mpd_checkout_field_settings['billing'] ) ) {
        return $mpd_checkout_field_settings['billing'];
    }
    
    // During AJAX requests (checkout validation), try to get from transient.
    if ( wp_doing_ajax() ) {
        $checkout_page_id = wc_get_page_id( 'checkout' );
        if ( $checkout_page_id ) {
            $settings = get_transient( 'mpd_billing_settings_' . $checkout_page_id );
            if ( $settings ) {
                return $settings;
            }
        }
    }
    
    return null;
}
}

/**
 * Get shipping form settings (from global or transient).
 *
 * @since 2.0.0
 *
 * @return array|null Settings or null.
 */
if ( ! function_exists( 'mpd_get_shipping_form_settings' ) ) {
function mpd_get_shipping_form_settings() {
    global $mpd_checkout_field_settings;
    
    // Always prefer the global (current request) settings.
    if ( ! empty( $mpd_checkout_field_settings['shipping'] ) ) {
        return $mpd_checkout_field_settings['shipping'];
    }
    
    // During AJAX requests (checkout validation), try to get from transient.
    if ( wp_doing_ajax() ) {
        $checkout_page_id = wc_get_page_id( 'checkout' );
        if ( $checkout_page_id ) {
            $settings = get_transient( 'mpd_shipping_settings_' . $checkout_page_id );
            if ( $settings ) {
                return $settings;
            }
        }
    }
    
    return null;
}
}

/**
 * Customize WooCommerce checkout fields based on MPD widget settings.
 *
 * @since 2.0.0
 *
 * @param array $fields Checkout fields.
 * @return array Modified checkout fields.
 */
if ( ! function_exists( 'mpd_customize_checkout_fields' ) ) {
function mpd_customize_checkout_fields( $fields ) {
    // Get billing settings.
    $billing_settings = mpd_get_billing_form_settings();
    
    // Apply billing settings if available.
    if ( ! empty( $billing_settings ) ) {
        $fields = mpd_apply_billing_field_settings( $fields, $billing_settings );
    }
    
    // Get shipping settings.
    $shipping_settings = mpd_get_shipping_form_settings();
    
    // Apply shipping settings if available.
    if ( ! empty( $shipping_settings ) ) {
        $fields = mpd_apply_shipping_field_settings( $fields, $shipping_settings );
    }
    
    return $fields;
}
add_filter( 'woocommerce_checkout_fields', 'mpd_customize_checkout_fields', 999 );
}

/**
 * Clear checkout field settings transients when checkout page is saved.
 *
 * @since 2.0.0
 *
 * @param int|object $post_id_or_document Post ID or Elementor Document.
 */
if ( ! function_exists( 'mpd_clear_checkout_field_transients' ) ) {
function mpd_clear_checkout_field_transients( $post_id_or_document ) {
    // Handle Elementor Document object.
    if ( is_object( $post_id_or_document ) && method_exists( $post_id_or_document, 'get_post' ) ) {
        $post = $post_id_or_document->get_post();
        $post_id = $post ? $post->ID : 0;
    } else {
        $post_id = (int) $post_id_or_document;
    }
    
    $checkout_page_id = wc_get_page_id( 'checkout' );
    
    if ( $post_id === $checkout_page_id ) {
        delete_transient( 'mpd_billing_settings_' . $checkout_page_id );
        delete_transient( 'mpd_shipping_settings_' . $checkout_page_id );
    }
}
add_action( 'save_post', 'mpd_clear_checkout_field_transients' );
add_action( 'elementor/document/after_save', 'mpd_clear_checkout_field_transients' );
}

/**
 * Apply billing field settings from MPD widget.
 *
 * @since 2.0.0
 *
 * @param array $fields   Checkout fields.
 * @param array $settings Widget settings.
 * @return array Modified checkout fields.
 */
if ( ! function_exists( 'mpd_apply_billing_field_settings' ) ) {
function mpd_apply_billing_field_settings( $fields, $settings ) {
    return mpd_apply_address_field_settings( $fields, $settings, 'billing' );
}
}

/**
 * Apply shipping field settings from MPD widget.
 *
 * @since 2.0.0
 *
 * @param array $fields   Checkout fields.
 * @param array $settings Widget settings.
 * @return array Modified checkout fields.
 */
if ( ! function_exists( 'mpd_apply_shipping_field_settings' ) ) {
function mpd_apply_shipping_field_settings( $fields, $settings ) {
    return mpd_apply_address_field_settings( $fields, $settings, 'shipping' );
}
}

/**
 * Shared helper for applying address field settings.
 *
 * @since 2.0.0
 *
 * @param array  $fields   Checkout fields.
 * @param array  $settings Widget settings.
 * @param string $type     'billing' or 'shipping'.
 * @return array Modified checkout fields.
 */
if ( ! function_exists( 'mpd_apply_address_field_settings' ) ) {
function mpd_apply_address_field_settings( $fields, $settings, $type ) {
    $prefix = $type . '_';

    // Handle name field mode (Full Name vs First/Last).
    $name_mode = isset( $settings['name_field_mode'] ) ? $settings['name_field_mode'] : 'separate';

    if ( 'full_name' === $name_mode ) {
        if ( isset( $fields[ $type ][ $prefix . 'last_name' ] ) ) {
            unset( $fields[ $type ][ $prefix . 'last_name' ] );
        }
        if ( isset( $fields[ $type ][ $prefix . 'first_name' ] ) ) {
            $fields[ $type ][ $prefix . 'first_name' ]['label'] = ! empty( $settings['full_name_label'] )
                ? $settings['full_name_label']
                : __( 'Full Name', 'magical-products-display' );
            $fields[ $type ][ $prefix . 'first_name' ]['class'] = array( 'form-row-wide' );
        }
    }

    // Company field.
    $show_company = ! empty( $settings['show_company_field'] ) && 'yes' === $settings['show_company_field'];
    if ( $show_company ) {
        if ( ! isset( $fields[ $type ][ $prefix . 'company' ] ) ) {
            $fields[ $type ][ $prefix . 'company' ] = array(
                'label'        => __( 'Company name', 'magical-products-display' ),
                'class'        => array( 'form-row-wide' ),
                'autocomplete' => 'organization',
                'priority'     => 30,
                'required'     => false,
            );
        }
        if ( 'billing' === $type ) {
            $company_required = ! empty( $settings['company_required'] ) && 'yes' === $settings['company_required'];
            $fields[ $type ][ $prefix . 'company' ]['required'] = $company_required;
        }
    } elseif ( isset( $fields[ $type ][ $prefix . 'company' ] ) ) {
        unset( $fields[ $type ][ $prefix . 'company' ] );
    }

    // Toggle-able address fields.
    $toggle_fields = array( 'country', 'address_1', 'address_2', 'city', 'state', 'postcode' );
    foreach ( $toggle_fields as $field ) {
        $setting_key = 'show_' . $field . '_field';
        $show = ! empty( $settings[ $setting_key ] ) && 'yes' === $settings[ $setting_key ];
        if ( ! $show && isset( $fields[ $type ][ $prefix . $field ] ) ) {
            unset( $fields[ $type ][ $prefix . $field ] );
        }
    }

    // Billing-only fields: phone and email.
    if ( 'billing' === $type ) {
        $show_phone = ! empty( $settings['show_phone_field'] ) && 'yes' === $settings['show_phone_field'];
        if ( ! $show_phone && isset( $fields['billing']['billing_phone'] ) ) {
            unset( $fields['billing']['billing_phone'] );
        } elseif ( isset( $fields['billing']['billing_phone'] ) ) {
            $phone_required = ! empty( $settings['phone_required'] ) && 'yes' === $settings['phone_required'];
            $fields['billing']['billing_phone']['required'] = $phone_required;
        }

        $show_email = ! empty( $settings['show_email_field'] ) && 'yes' === $settings['show_email_field'];
        if ( ! $show_email && isset( $fields['billing']['billing_email'] ) ) {
            unset( $fields['billing']['billing_email'] );
        }
    }

    return $fields;
}
}

/**
 * Ensure shipping address is properly set when not shipping to a different address.
 *
 * When using MPD checkout widgets and the customer doesn't check "Ship to different address",
 * we need to ensure the billing address is copied to shipping address.
 *
 * @since 2.0.0
 *
 * @param WC_Order $order Order object.
 * @param array    $data  Posted data.
 */
if ( ! function_exists( 'mpd_copy_billing_to_shipping_on_order' ) ) {
function mpd_copy_billing_to_shipping_on_order( $order, $data ) {
    // Check if shipping to different address was NOT selected.
    $ship_to_different = isset( $data['ship_to_different_address'] ) && ! empty( $data['ship_to_different_address'] );
    
    if ( ! $ship_to_different ) {
        // Copy billing address to shipping address.
        $shipping_fields = array(
            'first_name',
            'last_name',
            'company',
            'address_1',
            'address_2',
            'city',
            'state',
            'postcode',
            'country',
        );
        
        foreach ( $shipping_fields as $field ) {
            // Try to get from order methods.
            $getter = "get_billing_{$field}";
            $billing_value = '';
            if ( method_exists( $order, $getter ) ) {
                $billing_value = $order->$getter();
            }
            
            // Set shipping value if billing has value and shipping doesn't.
            $shipping_getter = "get_shipping_{$field}";
            $shipping_setter = "set_shipping_{$field}";
            
            if ( method_exists( $order, $shipping_getter ) && method_exists( $order, $shipping_setter ) ) {
                $shipping_value = $order->$shipping_getter();
                
                if ( empty( $shipping_value ) && ! empty( $billing_value ) ) {
                    $order->$shipping_setter( $billing_value );
                }
            }
        }
    }
}
add_action( 'woocommerce_checkout_create_order', 'mpd_copy_billing_to_shipping_on_order', 20, 2 );
}

/**
 * Handle "Full Name" mode - split into first/last name for display if needed.
 *
 * When the checkout uses a single "Full Name" field instead of separate first/last name,
 * we need to handle the data properly so it displays correctly in order details.
 *
 * @since 2.0.0
 *
 * @param array $data Posted checkout data.
 * @return array Modified checkout data.
 */
if ( ! function_exists( 'mpd_handle_full_name_checkout_data' ) ) {
function mpd_handle_full_name_checkout_data( $data ) {
    // Check billing - if we have billing_first_name but no billing_last_name, try to split.
    if ( ! empty( $data['billing_first_name'] ) && empty( $data['billing_last_name'] ) ) {
        $full_name = trim( $data['billing_first_name'] );
        $name_parts = explode( ' ', $full_name, 2 );
        
        if ( count( $name_parts ) === 2 ) {
            $data['billing_first_name'] = $name_parts[0];
            $data['billing_last_name'] = $name_parts[1];
        } else {
            // Single name - use as first name, leave last name empty.
            $data['billing_first_name'] = $full_name;
        }
    }
    
    // Check shipping - if we have shipping_first_name but no shipping_last_name, try to split.
    if ( ! empty( $data['shipping_first_name'] ) && empty( $data['shipping_last_name'] ) ) {
        $full_name = trim( $data['shipping_first_name'] );
        $name_parts = explode( ' ', $full_name, 2 );
        
        if ( count( $name_parts ) === 2 ) {
            $data['shipping_first_name'] = $name_parts[0];
            $data['shipping_last_name'] = $name_parts[1];
        } else {
            // Single name - use as first name, leave last name empty.
            $data['shipping_first_name'] = $full_name;
        }
    }
    
    return $data;
}
add_filter( 'woocommerce_checkout_posted_data', 'mpd_handle_full_name_checkout_data', 10, 1 );
}