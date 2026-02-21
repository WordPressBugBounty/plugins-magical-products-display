<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * MPD AJAX Search Handler
 * Handles AJAX search requests and returns formatted results
 */
class MPD_AJAX_Search_Handler {

    /**
     * Constructor
     */
    public function __construct() {
        add_action('wp_ajax_mpd_ajax_search', array($this, 'handle_search_request'));
        add_action('wp_ajax_nopriv_mpd_ajax_search', array($this, 'handle_search_request'));
        
        // Rate limiting cleanup
        add_action('mpd_cleanup_rate_limits', array($this, 'cleanup_rate_limits'));
        if (!wp_next_scheduled('mpd_cleanup_rate_limits')) {
            wp_schedule_event(time(), 'hourly', 'mpd_cleanup_rate_limits');
        }
    }

    /**
     * Handle AJAX search request
     */
    public function handle_search_request() {
        // Check if WooCommerce is active
        if (!class_exists('WooCommerce')) {
            wp_send_json_error(array('message' => __('WooCommerce is not active', 'magical-products-display')));
            return;
        }

        // Verify nonce
        $widget_id = isset($_POST['widget_id']) ? sanitize_text_field($_POST['widget_id']) : '';
        $nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : '';
        
        // Verify nonce for security
        $nonce_valid = wp_verify_nonce($nonce, 'mpd_ajax_search_' . $widget_id);
        if (!$nonce_valid) {
            wp_send_json_error(array('message' => __('Security check failed', 'magical-products-display')));
            return;
        }

        // Rate limiting
        if (!$this->check_rate_limit()) {
            wp_send_json_error(array('message' => __('Too many requests. Please wait a moment.', 'magical-products-display')));
            return;
        }

        // Sanitize and validate input
        $query = isset($_POST['query']) ? sanitize_text_field( wp_unslash( $_POST['query'] ) ) : '';
        $limit = max(1, min(50, isset($_POST['limit']) ? intval($_POST['limit']) : 10));
        $filters = $this->sanitize_filters(isset($_POST['filters']) ? wp_unslash( $_POST['filters'] ) : array());

        // Validate query length
        if (strlen($query) < 3) {
            wp_send_json_error(array('message' => __('Query too short', 'magical-products-display')));
            return;
        }

        // Check cache first
        $cache_key = $this->get_cache_key($query, $filters, $limit);
        $cached_result = get_transient($cache_key);
        
        if ($cached_result !== false) {
            wp_send_json_success($cached_result);
            return;
        }

        try {
            // Perform search
            $results = $this->perform_search($query, $filters, $limit);
            
            // Cache results for 5 minutes
            set_transient($cache_key, $results, 5 * MINUTE_IN_SECONDS);
            
            wp_send_json_success($results);
            
        } catch (Exception $e) {
            mpd_log( 'Ajax Search Error: ' . $e->getMessage(), 'error' );
            wp_send_json_error(array('message' => __('Search failed. Please try again.', 'magical-products-display')));
        }
    }

    /**
     * Perform product search
     *
     * @param string $query Search query
     * @param array $filters Search filters
     * @param int $limit Results limit
     * @return array Search results
     */
    private function perform_search($query, $filters, $limit) {
        // Start with a basic query
        $args = array(
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => $limit + 1,
            's' => $query,
            'orderby' => 'title',
            'order' => 'ASC',
            'fields' => 'ids',
            'meta_query' => array(),
            'tax_query' => array()
        );

        // Apply WooCommerce product visibility filter
        if (function_exists('wc_get_product_visibility_term_ids')) {
            $product_visibility_term_ids = wc_get_product_visibility_term_ids();
            if (!empty($product_visibility_term_ids['exclude-from-search'])) {
                $args['tax_query'][] = array(
                    'taxonomy' => 'product_visibility',
                    'field'    => 'term_taxonomy_id',
                    'terms'    => $product_visibility_term_ids['exclude-from-search'],
                    'operator' => 'NOT IN',
                );
            }
        }

        // Apply additional filters
        $this->apply_filters_to_query($args, $filters);

        // Set meta_query relation if we have multiple meta queries
        if (isset($args['meta_query']) && count($args['meta_query']) > 1) {
            $args['meta_query']['relation'] = 'AND';
        } elseif (isset($args['meta_query']) && count($args['meta_query']) === 0) {
            // Remove empty meta_query to avoid issues
            unset($args['meta_query']);
        }

        // Set tax_query relation if we have multiple tax queries
        if (isset($args['tax_query']) && count($args['tax_query']) > 1) {
            $args['tax_query']['relation'] = 'AND';
        } elseif (isset($args['tax_query']) && count($args['tax_query']) === 0) {
            // Remove empty tax_query to avoid issues
            unset($args['tax_query']);
        }

        // Perform search query
        $search_query = new WP_Query($args);
        $product_ids = $search_query->posts;
        $total_found = $search_query->found_posts;

        // Check if we have more results than requested
        $has_more = count($product_ids) > $limit;
        if ($has_more) {
            array_pop($product_ids); // Remove the extra product
        }

        // Get actual products count for display
        $products_count = count($product_ids);

        // Format products for frontend
        $products = array();
        foreach ($product_ids as $product_id) {
            $product_data = $this->format_product_data($product_id);
            if ($product_data) {
                $products[] = $product_data;
            }
        }

        return array(
            'products' => $products,
            'total' => $total_found,
            'query' => $query,
            'has_more' => $has_more
        );
    }

    /**
     * Apply filters to WP_Query
     *
     * @param array &$args WP_Query arguments
     * @param array $filters Search filters
     */
    private function apply_filters_to_query(&$args, $filters) {
        // Category filter
        if (!empty($filters['category'])) {
            $category_id = intval($filters['category']);
            
            $args['tax_query'][] = array(
                'taxonomy' => 'product_cat',
                'field' => 'term_id',
                'terms' => $category_id,
                'operator' => 'IN'
            );
        }

        // Tags filter
        if (!empty($filters['tags']) && is_array($filters['tags'])) {
            $tag_ids = array_map('intval', $filters['tags']);
            
            if (!empty($tag_ids)) {
                $args['tax_query'][] = array(
                    'taxonomy' => 'product_tag',
                    'field' => 'term_id',
                    'terms' => $tag_ids,
                    'operator' => 'IN'
                );
            }
        }

        // Price range filter
        if (!empty($filters['price_min']) || !empty($filters['price_max'])) {
            // Initialize price meta query
            $price_meta_query = array();
            
            if (!empty($filters['price_min']) && !empty($filters['price_max'])) {
                $min_price = floatval($filters['price_min']);
                $max_price = floatval($filters['price_max']);
                
                // Use _price field which is WooCommerce's display price
                $price_meta_query = array(
                    'key' => '_price',
                    'value' => array($min_price, $max_price),
                    'type' => 'DECIMAL(10,2)',
                    'compare' => 'BETWEEN'
                );
            } elseif (!empty($filters['price_min'])) {
                $min_price = floatval($filters['price_min']);
                
                $price_meta_query = array(
                    'key' => '_price',
                    'value' => $min_price,
                    'type' => 'DECIMAL(10,2)',
                    'compare' => '>='
                );
            } elseif (!empty($filters['price_max'])) {
                $max_price = floatval($filters['price_max']);
                
                $price_meta_query = array(
                    'key' => '_price',
                    'value' => $max_price,
                    'type' => 'DECIMAL(10,2)',
                    'compare' => '<='
                );
            }
            
            // Only add if we have a valid price query
            if (!empty($price_meta_query)) {
                $args['meta_query'][] = $price_meta_query;
            }
        }

        // Featured products filter (Pro)
        if (!empty($filters['featured']) && get_option('mgppro_is_active', 'no') === 'yes') {
            $args['tax_query'][] = array(
                'taxonomy' => 'product_visibility',
                'field' => 'name',
                'terms' => 'featured'
            );
        }

        // Stock status filter (Pro)
        if (!empty($filters['stock_status']) && get_option('mgppro_is_active', 'no') === 'yes') {
            $args['meta_query'][] = array(
                'key' => '_stock_status',
                'value' => sanitize_text_field($filters['stock_status']),
                'compare' => '='
            );
        }

        // Set relation for multiple queries
        if (count($args['meta_query']) > 1) {
            $args['meta_query']['relation'] = 'AND';
        }
        
        if (count($args['tax_query']) > 1) {
            $args['tax_query']['relation'] = 'AND';
        }
    }

    /**
     * Format product data for frontend
     *
     * @param int $product_id Product ID
     * @return array|null Formatted product data
     */
    private function format_product_data($product_id) {
        $product = wc_get_product($product_id);
        
        if (!$product || !$product->is_visible()) {
            return null;
        }

        // Get product image
        $image_id = $product->get_image_id();
        $image_url = $image_id ? wp_get_attachment_image_url($image_id, 'thumbnail') : '';

        // Get product URL
        $product_url = get_permalink($product_id);

        // Get price HTML
        $price_html = $product->get_price_html();

        // Get SKU
        $sku = $product->get_sku();

        return array(
            'id' => $product_id,
            'title' => $product->get_name(),
            'url' => esc_url($product_url),
            'image' => esc_url($image_url),
            'price_html' => $price_html,
            'sku' => $sku ? esc_html($sku) : '',
            'in_stock' => $product->is_in_stock(),
            'featured' => $product->is_featured()
        );
    }

    /**
     * Sanitize search filters
     *
     * @param array $filters Raw filters
     * @return array Sanitized filters
     */
    private function sanitize_filters($filters) {
        if (!is_array($filters)) {
            return array();
        }

        $sanitized = array();

        // Category
        if (isset($filters['category'])) {
            $sanitized['category'] = intval($filters['category']);
        }

        // Tags
        if (isset($filters['tags']) && is_array($filters['tags'])) {
            $sanitized['tags'] = array_map('intval', $filters['tags']);
        }

        // Price range
        if (isset($filters['price_min'])) {
            $sanitized['price_min'] = floatval($filters['price_min']);
        }
        if (isset($filters['price_max'])) {
            $sanitized['price_max'] = floatval($filters['price_max']);
        }

        // Featured
        if (isset($filters['featured'])) {
            $sanitized['featured'] = (bool) $filters['featured'];
        }

        // Stock status
        if (isset($filters['stock_status'])) {
            $valid_statuses = array('instock', 'outofstock', 'onbackorder');
            $status = sanitize_text_field($filters['stock_status']);
            if (in_array($status, $valid_statuses)) {
                $sanitized['stock_status'] = $status;
            }
        }

        return $sanitized;
    }

    /**
     * Generate cache key
     *
     * @param string $query Search query
     * @param array $filters Search filters
     * @param int $limit Results limit
     * @return string Cache key
     */
    private function get_cache_key($query, $filters, $limit) {
        $key_data = array(
            'query' => $query,
            'filters' => $filters,
            'limit' => $limit,
            'user_id' => get_current_user_id(), // Different cache for different users if needed
        );
        
        return 'mpd_search_' . md5(serialize($key_data));
    }

    /**
     * Check rate limiting
     *
     * @return bool True if request is allowed
     */
    private function check_rate_limit() {
        $ip = $this->get_client_ip();
        $rate_limit_key = 'mpd_rate_limit_' . md5($ip);
        
        $current_requests = get_transient($rate_limit_key);
        if ($current_requests === false) {
            $current_requests = 0;
        }

        // Allow 30 requests per minute
        $max_requests = 30;
        $time_window = 60; // seconds

        if ($current_requests >= $max_requests) {
            return false;
        }

        // Increment request count
        set_transient($rate_limit_key, $current_requests + 1, $time_window);
        
        return true;
    }

    /**
     * Get client IP address
     *
     * @return string Client IP
     */
    private function get_client_ip() {
        // Use REMOTE_ADDR as primary — proxy headers are spoofable.
        return isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '127.0.0.1';
    }

    /**
     * Cleanup old rate limit transients
     */
    public function cleanup_rate_limits() {
        global $wpdb;
        
        // Delete expired rate limit transients
        $wpdb->query(
            $wpdb->prepare(
                "DELETE FROM {$wpdb->options} 
                 WHERE option_name LIKE %s 
                 AND option_value < %d",
                '_transient_timeout_mpd_rate_limit_%',
                time()
            )
        );
    }
}

// Initialize the AJAX handler
new MPD_AJAX_Search_Handler();
