<?php
/**
 * AJAX Handler for Products Archive Widget
 * Handles infinite scroll loading
 * 
 * @package Magical_Products_Display
 * @since 2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Class MPD_Products_Archive_Ajax
 */
class MPD_Products_Archive_Ajax {

    /**
     * Initialize the AJAX handler
     */
    public static function init() {
        add_action( 'wp_ajax_mpd_load_more_products', array( __CLASS__, 'load_more_products' ) );
        add_action( 'wp_ajax_nopriv_mpd_load_more_products', array( __CLASS__, 'load_more_products' ) );
        
        // AJAX filter action
        add_action( 'wp_ajax_mpd_filter_products', array( __CLASS__, 'filter_products' ) );
        add_action( 'wp_ajax_nopriv_mpd_filter_products', array( __CLASS__, 'filter_products' ) );
    }
    
    /**
     * AJAX callback to filter products (for price filter, etc.)
     */
    public static function filter_products() {
        // Verify nonce
        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'mpd_shop_archive_nonce' ) ) {
            wp_send_json_error( array( 'message' => __( 'Security check failed', 'magical-products-display' ) ) );
            return;
        }

        // Get parameters
        $widget_id      = isset( $_POST['widget_id'] ) ? sanitize_text_field( wp_unslash( $_POST['widget_id'] ) ) : '';
        $post_id        = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0;
        $query_string   = isset( $_POST['query_string'] ) ? sanitize_text_field( wp_unslash( $_POST['query_string'] ) ) : '';
        $posts_per_page = isset( $_POST['posts_per_page'] ) ? absint( $_POST['posts_per_page'] ) : 12;

        if ( empty( $widget_id ) || empty( $post_id ) ) {
            wp_send_json_error( array( 'message' => __( 'Invalid widget parameters', 'magical-products-display' ) ) );
            return;
        }

        // Parse query string to simulate $_GET for WooCommerce filters.
        // Only allow known WC filter keys to prevent superglobal pollution.
        if ( ! empty( $query_string ) ) {
            $allowed_keys = array(
                'orderby', 'order', 'min_price', 'max_price', 'product_cat',
                'product_tag', 's', 'paged', 'rating_filter',
            );
            parse_str( $query_string, $parsed_query );
            foreach ( $parsed_query as $key => $value ) {
                $skey = sanitize_key( $key );
                if ( in_array( $skey, $allowed_keys, true ) || 0 === strpos( $skey, 'filter_' ) || 0 === strpos( $skey, 'query_type_' ) ) {
                    $_GET[ $skey ] = sanitize_text_field( wp_unslash( $value ) );
                }
            }
        }

        // Get widget settings from Elementor
        $settings = self::get_widget_settings( $post_id, $widget_id );

        if ( empty( $settings ) ) {
            wp_send_json_error( array( 'message' => __( 'Widget settings not found', 'magical-products-display' ) ) );
            return;
        }

        // Override posts per page if provided
        if ( $posts_per_page > 0 ) {
            $settings['posts_per_page'] = $posts_per_page;
        }

        // Build query args (page 1 for fresh filter results)
        $args = self::build_query_args( $settings, 1 );
        
        // Run query
        $query = new WP_Query( $args );

        if ( ! $query->have_posts() ) {
            wp_send_json_success( array(
                'html'        => '',
                'found_posts' => 0,
                'max_pages'   => 0,
                'message'     => __( 'No products found matching your criteria.', 'magical-products-display' ),
            ) );
            return;
        }

        // Generate HTML output
        ob_start();
        while ( $query->have_posts() ) {
            $query->the_post();
            
            // Setup WooCommerce product data for AJAX context
            $product_id = get_the_ID();
            $product = wc_get_product( $product_id );

            if ( ! $product || ! $product instanceof WC_Product ) {
                continue;
            }
            
            // Set up global product for WooCommerce template functions
            $GLOBALS['product'] = $product;
            
            self::render_product_item( $product, $settings );
        }
        $html = ob_get_clean();
        wp_reset_postdata();

        wp_send_json_success( array(
            'html'        => $html,
            'found_posts' => $query->found_posts,
            'max_pages'   => $query->max_num_pages,
        ) );
    }

    /**
     * AJAX callback to load more products
     */
    public static function load_more_products() {
        // Verify nonce
        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'mpd_shop_archive_nonce' ) ) {
            wp_send_json_error( array( 'message' => __( 'Security check failed', 'magical-products-display' ) ) );
            return;
        }

        // Get parameters
        $page           = isset( $_POST['page'] ) ? absint( $_POST['page'] ) : 2;
        $widget_id      = isset( $_POST['widget_id'] ) ? sanitize_text_field( wp_unslash( $_POST['widget_id'] ) ) : '';
        $post_id        = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0;
        $query_string   = isset( $_POST['query_string'] ) ? sanitize_text_field( wp_unslash( $_POST['query_string'] ) ) : '';
        $posts_per_page = isset( $_POST['posts_per_page'] ) ? absint( $_POST['posts_per_page'] ) : 0;

        if ( empty( $widget_id ) || empty( $post_id ) ) {
            wp_send_json_error( array( 'message' => __( 'Invalid widget parameters', 'magical-products-display' ) ) );
            return;
        }

        // Parse query string to simulate $_GET for WooCommerce filters.
        // Only allow known WC filter keys to prevent superglobal pollution.
        if ( ! empty( $query_string ) ) {
            $allowed_keys = array(
                'orderby', 'order', 'min_price', 'max_price', 'product_cat',
                'product_tag', 's', 'paged', 'rating_filter',
            );
            parse_str( $query_string, $parsed_query );
            foreach ( $parsed_query as $key => $value ) {
                $skey = sanitize_key( $key );
                if ( in_array( $skey, $allowed_keys, true ) || 0 === strpos( $skey, 'filter_' ) || 0 === strpos( $skey, 'query_type_' ) ) {
                    $_GET[ $skey ] = sanitize_text_field( wp_unslash( $value ) );
                }
            }
        }

        // Get widget settings from Elementor
        $settings = self::get_widget_settings( $post_id, $widget_id );

        if ( empty( $settings ) ) {
            wp_send_json_error( array( 'message' => __( 'Widget settings not found', 'magical-products-display' ) ) );
            return;
        }

        // Override posts_per_page if provided (from WooCommerce main query)
        if ( $posts_per_page > 0 ) {
            $settings['posts_per_page'] = $posts_per_page;
        }

        // Build query args
        $args = self::build_query_args( $settings, $page );

        // Run query
        $query = new WP_Query( $args );

        if ( ! $query->have_posts() ) {
            wp_send_json_success( array(
                'html'      => '',
                'max_pages' => $query->max_num_pages,
            ) );
            return;
        }

        // Start output buffering
        ob_start();

        while ( $query->have_posts() ) {
            $query->the_post();
            
            // Setup WooCommerce product data for AJAX context
            $product_id = get_the_ID();
            $product = wc_get_product( $product_id );

            if ( ! $product || ! $product instanceof WC_Product ) {
                continue;
            }
            
            // Set up global product for WooCommerce template functions
            $GLOBALS['product'] = $product;

            self::render_product_item( $product, $settings );
        }

        $html = ob_get_clean();
        wp_reset_postdata();

        wp_send_json_success( array(
            'html'      => $html,
            'max_pages' => $query->max_num_pages,
        ) );
    }

    /**
     * Get widget settings from Elementor
     *
     * @param int    $post_id   Post ID
     * @param string $widget_id Widget ID
     * @return array Widget settings
     */
    private static function get_widget_settings( $post_id, $widget_id ) {
        if ( ! class_exists( '\Elementor\Plugin' ) ) {
            return array();
        }

        $document = \Elementor\Plugin::$instance->documents->get( $post_id );

        if ( ! $document ) {
            return array();
        }

        $elements = $document->get_elements_data();

        return self::find_widget_settings( $elements, $widget_id );
    }

    /**
     * Find widget settings recursively
     *
     * @param array  $elements  Elements data
     * @param string $widget_id Widget ID
     * @return array Widget settings
     */
    private static function find_widget_settings( $elements, $widget_id ) {
        foreach ( $elements as $element ) {
            if ( isset( $element['id'] ) && $element['id'] === $widget_id ) {
                return isset( $element['settings'] ) ? $element['settings'] : array();
            }

            if ( ! empty( $element['elements'] ) ) {
                $found = self::find_widget_settings( $element['elements'], $widget_id );
                if ( ! empty( $found ) ) {
                    return $found;
                }
            }
        }

        return array();
    }

    /**
     * Build query args from settings
     *
     * @param array $settings Widget settings
     * @param int   $page     Page number
     * @return array Query args
     */
    private static function build_query_args( $settings, $page ) {
        // Nonce is verified in the calling methods: filter_products() and load_more_products().
        // phpcs:disable WordPress.Security.NonceVerification.Recommended
        $posts_per_page = isset( $settings['posts_per_page'] ) ? absint( $settings['posts_per_page'] ) : 12;
        $orderby        = isset( $settings['orderby'] ) ? sanitize_text_field( $settings['orderby'] ) : 'menu_order';
        $order          = isset( $settings['order'] ) ? sanitize_text_field( $settings['order'] ) : 'ASC';
        
        // Check for orderby from query string (WooCommerce ordering)
        if ( isset( $_GET['orderby'] ) ) {
            $orderby = sanitize_text_field( wp_unslash( $_GET['orderby'] ) );
        }

        $args = array(
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'posts_per_page' => $posts_per_page,
            'paged'          => $page,
            'orderby'        => $orderby,
            'order'          => $order,
        );

        // Handle special orderby cases
        if ( 'price' === $orderby ) {
            $args['meta_key'] = '_price';
            $args['orderby']  = 'meta_value_num';
            $args['order']    = 'ASC';
        } elseif ( 'price-desc' === $orderby ) {
            $args['meta_key'] = '_price';
            $args['orderby']  = 'meta_value_num';
            $args['order']    = 'DESC';
        } elseif ( 'popularity' === $orderby ) {
            $args['meta_key'] = 'total_sales';
            $args['orderby']  = 'meta_value_num';
            $args['order']    = 'DESC';
        } elseif ( 'rating' === $orderby ) {
            $args['meta_key'] = '_wc_average_rating';
            $args['orderby']  = 'meta_value_num';
            $args['order']    = 'DESC';
        } elseif ( 'date' === $orderby ) {
            $args['orderby'] = 'date';
            $args['order']   = 'DESC';
        }

        // Initialize tax_query
        $args['tax_query'] = array(
            'relation' => 'AND',
            array(
                'taxonomy' => 'product_visibility',
                'field'    => 'name',
                'terms'    => array( 'exclude-from-catalog', 'exclude-from-search' ),
                'operator' => 'NOT IN',
            ),
        );
        
        // Apply WooCommerce attribute filters
        if ( function_exists( 'wc_get_attribute_taxonomies' ) ) {
            $attribute_taxonomies = wc_get_attribute_taxonomies();
            foreach ( $attribute_taxonomies as $taxonomy ) {
                $filter_name = 'filter_' . $taxonomy->attribute_name;
                if ( isset( $_GET[ $filter_name ] ) ) {
                    $filter_value = wc_clean( wp_unslash( $_GET[ $filter_name ] ) );
                    $terms = is_array( $filter_value ) ? $filter_value : explode( ',', $filter_value );
                    
                    if ( ! empty( $terms ) ) {
                        $args['tax_query'][] = array(
                            'taxonomy' => 'pa_' . $taxonomy->attribute_name,
                            'field'    => 'slug',
                            'terms'    => $terms,
                            'operator' => 'IN',
                        );
                    }
                }
            }
        }
        
        // Apply category filter
        if ( isset( $_GET['product_cat'] ) ) {
            $args['tax_query'][] = array(
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => explode( ',', wc_clean( wp_unslash( $_GET['product_cat'] ) ) ),
                'operator' => 'IN',
            );
        }

        // Apply tag filter
        if ( isset( $_GET['product_tag'] ) ) {
            $args['tax_query'][] = array(
                'taxonomy' => 'product_tag',
                'field'    => 'slug',
                'terms'    => explode( ',', wc_clean( wp_unslash( $_GET['product_tag'] ) ) ),
                'operator' => 'IN',
            );
        }

        // Apply product_brand filter (custom brand taxonomy)
        if ( isset( $_GET['product_brand'] ) ) {
            $args['tax_query'][] = array(
                'taxonomy' => 'product_brand',
                'field'    => 'slug',
                'terms'    => explode( ',', wc_clean( wp_unslash( $_GET['product_brand'] ) ) ),
                'operator' => 'IN',
            );
        }

        // Apply attribute taxonomies from URL (pa_brand, pa_color, etc.)
        $attribute_taxonomies = wc_get_attribute_taxonomy_names();
        foreach ( $attribute_taxonomies as $taxonomy ) {
            if ( isset( $_GET[ $taxonomy ] ) ) {
                $args['tax_query'][] = array(
                    'taxonomy' => $taxonomy,
                    'field'    => 'slug',
                    'terms'    => explode( ',', wc_clean( wp_unslash( $_GET[ $taxonomy ] ) ) ),
                    'operator' => 'IN',
                );
            }
        }

        // Apply any other custom product taxonomies from URL
        // This handles dynamically configured brand taxonomies
        $product_taxonomies = get_object_taxonomies( 'product', 'names' );
        $excluded_taxonomies = array( 'product_cat', 'product_tag', 'product_type', 'product_visibility', 'product_shipping_class', 'product_brand' );
        foreach ( $product_taxonomies as $taxonomy ) {
            // Skip already handled taxonomies and pa_* attributes
            if ( in_array( $taxonomy, $excluded_taxonomies, true ) || strpos( $taxonomy, 'pa_' ) === 0 ) {
                continue;
            }
            if ( isset( $_GET[ $taxonomy ] ) ) {
                $args['tax_query'][] = array(
                    'taxonomy' => $taxonomy,
                    'field'    => 'slug',
                    'terms'    => explode( ',', wc_clean( wp_unslash( $_GET[ $taxonomy ] ) ) ),
                    'operator' => 'IN',
                );
            }
        }
        
        // Initialize meta_query if not set
        if ( ! isset( $args['meta_query'] ) ) {
            $args['meta_query'] = array();
        }
        
        // Apply price filter from URL using WooCommerce native meta query
        if ( isset( $_GET['min_price'] ) || isset( $_GET['max_price'] ) ) {
            $min_price = isset( $_GET['min_price'] ) ? floatval( wc_clean( wp_unslash( $_GET['min_price'] ) ) ) : 0;
            $max_price = isset( $_GET['max_price'] ) ? floatval( wc_clean( wp_unslash( $_GET['max_price'] ) ) ) : PHP_INT_MAX;
            
            // Use WooCommerce's native price meta query
            $args['meta_query']['price_filter'] = array(
                'key'     => '_price',
                'value'   => array( $min_price, $max_price ),
                'compare' => 'BETWEEN',
                'type'    => 'DECIMAL(10,2)',
            );
        }
        
        // Apply rating filter
        if ( isset( $_GET['rating_filter'] ) ) {
            $rating = absint( wc_clean( wp_unslash( $_GET['rating_filter'] ) ) );
            $args['meta_query']['rating_filter'] = array(
                'key'     => '_wc_average_rating',
                'value'   => $rating,
                'compare' => '>=',
                'type'    => 'DECIMAL(2,1)',
            );
        }

        // Apply stock status filter
        if ( isset( $_GET['stock_status'] ) ) {
            $stock_status = wc_clean( wp_unslash( $_GET['stock_status'] ) );
            if ( 'instock' === $stock_status ) {
                $args['meta_query']['stock_filter'] = array(
                    'key'     => '_stock_status',
                    'value'   => 'instock',
                    'compare' => '=',
                );
            } elseif ( 'outofstock' === $stock_status ) {
                $args['meta_query']['stock_filter'] = array(
                    'key'     => '_stock_status',
                    'value'   => 'outofstock',
                    'compare' => '=',
                );
            } elseif ( 'onbackorder' === $stock_status ) {
                $args['meta_query']['stock_filter'] = array(
                    'key'     => '_stock_status',
                    'value'   => 'onbackorder',
                    'compare' => '=',
                );
            }
        }

        // Apply on sale filter
        if ( isset( $_GET['on_sale'] ) && 'yes' === wc_clean( wp_unslash( $_GET['on_sale'] ) ) ) {
            $args['post__in'] = array_merge( 
                isset( $args['post__in'] ) ? $args['post__in'] : array(), 
                wc_get_product_ids_on_sale() 
            );
            // Ensure we have at least one product to avoid empty query
            if ( empty( $args['post__in'] ) ) {
                $args['post__in'] = array( 0 );
            }
        }

        // Apply featured filter
        if ( isset( $_GET['featured'] ) && 'yes' === wc_clean( wp_unslash( $_GET['featured'] ) ) ) {
            $args['tax_query'][] = array(
                'taxonomy' => 'product_visibility',
                'field'    => 'name',
                'terms'    => 'featured',
                'operator' => 'IN',
            );
        }
        // phpcs:enable WordPress.Security.NonceVerification.Recommended

        return $args;
    }

    /**
     * Render product item HTML
     *
     * @param WC_Product $product  Product object
     * @param array      $settings Widget settings
     */
    private static function render_product_item( $product, $settings ) {
        $show_image       = isset( $settings['show_image'] ) ? $settings['show_image'] : 'yes';
        $show_title       = isset( $settings['show_title'] ) ? $settings['show_title'] : 'yes';
        $show_price       = isset( $settings['show_price'] ) ? $settings['show_price'] : 'yes';
        $show_rating      = isset( $settings['show_rating'] ) ? $settings['show_rating'] : 'yes';
        $show_add_to_cart = isset( $settings['show_add_to_cart'] ) ? $settings['show_add_to_cart'] : 'yes';
        $show_sale_badge  = isset( $settings['show_sale_badge'] ) ? $settings['show_sale_badge'] : 'yes';
        $show_category    = isset( $settings['show_category'] ) ? $settings['show_category'] : '';
        
        // Action buttons
        $show_compare   = 'yes' === ( $settings['show_compare_btn'] ?? '' );
        $show_wishlist  = 'yes' === ( $settings['show_wishlist_btn'] ?? '' );
        $show_quickview = 'yes' === ( $settings['show_quickview_btn'] ?? '' );
        $has_action_buttons = $show_compare || $show_wishlist || $show_quickview;
        $btn_position   = $settings['action_btn_position'] ?? 'image_center';
        $btn_style      = $settings['action_btn_style'] ?? 'icon_only';
        $show_on_hover  = 'yes' === ( $settings['action_btn_show_on_hover'] ?? 'yes' );
        $show_text      = 'icon_text' === $btn_style;
        ?>
        <div class="mpd-products-archive__item" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>">
            <?php if ( 'yes' === $show_sale_badge && $product->is_on_sale() ) : ?>
                <span class="mpd-products-archive__badge"><?php echo wp_kses_post( self::get_sale_badge_text( $product, $settings ) ); ?></span>
            <?php endif; ?>

            <?php if ( 'yes' === $show_image ) : ?>
                <div class="mpd-products-archive__image">
                    <a href="<?php echo esc_url( $product->get_permalink() ); ?>">
                        <?php echo wp_kses_post( $product->get_image( 'woocommerce_thumbnail' ) ); ?>
                    </a>
                    <?php 
                    // Render action buttons on image positions
                    if ( $has_action_buttons && in_array( $btn_position, array( 'image_center', 'top_right', 'top_left' ), true ) ) {
                        self::render_action_buttons( $product, $settings );
                    }
                    ?>
                </div>
            <?php endif; ?>

            <?php 
            // Render action buttons below image
            if ( $has_action_buttons && 'below_image' === $btn_position ) {
                self::render_action_buttons( $product, $settings );
            }
            ?>

            <div class="mpd-products-archive__content">
                <?php if ( 'yes' === $show_category ) : ?>
                    <div class="mpd-products-archive__category">
                        <?php echo wp_kses_post( self::get_product_categories( $product, $settings ) ); ?>
                    </div>
                <?php endif; ?>

                <?php if ( 'yes' === $show_title ) : ?>
                    <h3 class="mpd-products-archive__title">
                        <a href="<?php echo esc_url( $product->get_permalink() ); ?>">
                            <?php echo esc_html( $product->get_name() ); ?>
                        </a>
                    </h3>
                <?php endif; ?>

                <?php if ( 'yes' === $show_rating && wc_review_ratings_enabled() ) : ?>
                    <div class="mpd-products-archive__rating">
                        <?php echo wp_kses_post( self::get_star_rating_html( $product ) ); ?>
                    </div>
                <?php endif; ?>

                <?php if ( 'yes' === $show_price ) : ?>
                    <div class="mpd-products-archive__price">
                        <?php echo wp_kses_post( $product->get_price_html() ); ?>
                    </div>
                <?php endif; ?>

                <?php if ( 'yes' === $show_add_to_cart ) : ?>
                    <div class="mpd-products-archive__add-to-cart">
                        <?php woocommerce_template_loop_add_to_cart(); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }

    /**
     * Get sale badge text with optional discount.
     *
     * @param WC_Product $product  Product object
     * @param array      $settings Widget settings
     * @return string
     */
    private static function get_sale_badge_text( $product, $settings ) {
        $discount_type = $settings['badge_discount_type'] ?? 'hide';
        $after_text    = $settings['badge_after_text'] ?? '';

        if ( 'hide' === $discount_type ) {
            return esc_html__( 'Sale!', 'magical-products-display' );
        }

        // Calculate discount
        $regular_price = (float) $product->get_regular_price();
        $sale_price    = (float) $product->get_sale_price();

        // Handle variable products
        if ( $product->is_type( 'variable' ) ) {
            $regular_price = (float) $product->get_variation_regular_price( 'max' );
            $sale_price    = (float) $product->get_variation_sale_price( 'min' );
        }

        if ( $regular_price <= 0 || $sale_price <= 0 ) {
            return esc_html__( 'Sale!', 'magical-products-display' );
        }

        $discount = $regular_price - $sale_price;

        if ( 'percentage' === $discount_type ) {
            $percentage = round( ( $discount / $regular_price ) * 100 );
            return $percentage . '%' . esc_html( $after_text );
        } elseif ( 'number' === $discount_type ) {
            $currency_symbol = get_woocommerce_currency_symbol();
            $formatted_discount = $currency_symbol . number_format( $discount, 0, '', wc_get_price_thousand_separator() );
            return $formatted_discount . esc_html( $after_text );
        }

        return esc_html__( 'Sale!', 'magical-products-display' );
    }

    /**
     * Get star rating HTML with icons.
     *
     * @param WC_Product $product Product object
     * @return string
     */
    private static function get_star_rating_html( $product ) {
        $average = $product->get_average_rating();
        $rating_percentage = ( $average / 5 ) * 100;

        ob_start();
        ?>
        <div class="mpd-star-rating">
            <span class="mpd-star-rating__empty">
                <i class="eicon-star-o"></i>
                <i class="eicon-star-o"></i>
                <i class="eicon-star-o"></i>
                <i class="eicon-star-o"></i>
                <i class="eicon-star-o"></i>
            </span>
            <span class="mpd-star-rating__filled" style="width: <?php echo esc_attr( $rating_percentage ); ?>%;">
                <i class="eicon-star"></i>
                <i class="eicon-star"></i>
                <i class="eicon-star"></i>
                <i class="eicon-star"></i>
                <i class="eicon-star"></i>
            </span>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Get product categories HTML.
     *
     * @param WC_Product $product  Product object
     * @param array      $settings Widget settings
     * @return string
     */
    private static function get_product_categories( $product, $settings ) {
        $display_type = $settings['category_display_type'] ?? 'first';
        $terms = get_the_terms( $product->get_id(), 'product_cat' );

        if ( empty( $terms ) || is_wp_error( $terms ) ) {
            return '';
        }

        $categories = array();

        if ( 'first' === $display_type ) {
            $term = reset( $terms );
            $categories[] = '<a href="' . esc_url( get_term_link( $term ) ) . '">' . esc_html( $term->name ) . '</a>';
        } elseif ( 'random' === $display_type ) {
            $random_key = array_rand( $terms );
            $term = $terms[ $random_key ];
            $categories[] = '<a href="' . esc_url( get_term_link( $term ) ) . '">' . esc_html( $term->name ) . '</a>';
        } else { // all
            foreach ( $terms as $term ) {
                $categories[] = '<a href="' . esc_url( get_term_link( $term ) ) . '">' . esc_html( $term->name ) . '</a>';
            }
        }

        return implode( ', ', $categories );
    }

    /**
     * Render action buttons (Compare, Wishlist, Quick View)
     *
     * @param WC_Product $product  Product object
     * @param array      $settings Widget settings
     */
    private static function render_action_buttons( $product, $settings ) {
        $show_compare   = 'yes' === ( $settings['show_compare_btn'] ?? '' );
        $show_wishlist  = 'yes' === ( $settings['show_wishlist_btn'] ?? '' );
        $show_quickview = 'yes' === ( $settings['show_quickview_btn'] ?? '' );

        if ( ! $show_compare && ! $show_wishlist && ! $show_quickview ) {
            return;
        }

        $product_id   = $product->get_id();
        $btn_style    = $settings['action_btn_style'] ?? 'icon_only';
        $btn_position = $settings['action_btn_position'] ?? 'image_center';
        $show_on_hover = 'yes' === ( $settings['action_btn_show_on_hover'] ?? 'yes' );
        $show_text    = 'icon_text' === $btn_style;

        // Position classes
        $position_class = 'mpd-action-buttons';
        if ( 'image_center' === $btn_position ) {
            $position_class .= ' mpd-action-buttons--image-center';
        } elseif ( 'top_right' === $btn_position ) {
            $position_class .= ' mpd-action-buttons--top-right';
        } elseif ( 'top_left' === $btn_position ) {
            $position_class .= ' mpd-action-buttons--top-left';
        } elseif ( 'below_image' === $btn_position ) {
            $position_class .= ' mpd-action-buttons--below-image';
        }
        
        // Add hover visibility class
        if ( 'below_image' !== $btn_position && $show_on_hover ) {
            $position_class .= ' mpd-action-buttons--hover-only';
        }
        ?>
        <div class="<?php echo esc_attr( $position_class ); ?>">
            <?php if ( $show_wishlist ) : ?>
                <button type="button" class="mpd-wishlist-btn mpd-action-btn" data-product-id="<?php echo esc_attr( $product_id ); ?>" title="<?php esc_attr_e( 'Add to Wishlist', 'magical-products-display' ); ?>">
                    <i class="eicon-heart-o"></i>
                    <?php if ( $show_text ) : ?>
                        <span><?php esc_html_e( 'Wishlist', 'magical-products-display' ); ?></span>
                    <?php endif; ?>
                </button>
            <?php endif; ?>

            <?php if ( $show_compare ) : ?>
                <button type="button" class="mpd-compare-btn mpd-action-btn" data-product-id="<?php echo esc_attr( $product_id ); ?>" title="<?php esc_attr_e( 'Compare', 'magical-products-display' ); ?>">
                    <i class="eicon-exchange"></i>
                    <?php if ( $show_text ) : ?>
                        <span><?php esc_html_e( 'Compare', 'magical-products-display' ); ?></span>
                    <?php endif; ?>
                </button>
            <?php endif; ?>

            <?php if ( $show_quickview ) : ?>
                <button type="button" class="mpd-quick-view-btn mpd-action-btn" data-product-id="<?php echo esc_attr( $product_id ); ?>" title="<?php esc_attr_e( 'Quick View', 'magical-products-display' ); ?>">
                    <i class="eicon-zoom-in-bold"></i>
                    <?php if ( $show_text ) : ?>
                        <span><?php esc_html_e( 'Quick View', 'magical-products-display' ); ?></span>
                    <?php endif; ?>
                </button>
            <?php endif; ?>
        </div>
        <?php
    }
}

// Initialize
MPD_Products_Archive_Ajax::init();
