<?php
/**
 * AJAX Handler for Products Tab Widget
 * Handles lazy loading of tab content
 * 
 * @since 1.1.35
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class MPD_Products_Tab_Ajax
{
    /**
     * Initialize the AJAX handler
     */
    public static function init()
    {
        add_action('wp_ajax_mpd_load_tab_products', [__CLASS__, 'load_tab_products']);
        add_action('wp_ajax_nopriv_mpd_load_tab_products', [__CLASS__, 'load_tab_products']);
    }

    /**
     * AJAX callback to load products for a tab
     */
    public static function load_tab_products()
    {
        // Verify nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'mpd_tab_ajax_nonce')) {
            wp_send_json_error(['message' => __('Security check failed', 'magical-products-display')]);
            return;
        }

        // Get and sanitize parameters
        $category_slug = isset($_POST['category_slug']) ? sanitize_text_field(wp_unslash($_POST['category_slug'])) : '';
        $settings = isset($_POST['settings']) ? self::sanitize_settings($_POST['settings']) : [];

        if (empty($category_slug)) {
            wp_send_json_error(['message' => __('Category not specified', 'magical-products-display')]);
            return;
        }

        // Start output buffering
        ob_start();

        // Render products
        self::render_products($category_slug, $settings);

        $html = ob_get_clean();

        wp_send_json_success(['html' => $html]);
    }

    /**
     * Sanitize settings array
     */
    private static function sanitize_settings($settings)
    {
        if (!is_array($settings)) {
            return [];
        }

        $sanitized = [];
        foreach ($settings as $key => $value) {
            $key = sanitize_key($key);
            if (is_array($value)) {
                $sanitized[$key] = self::sanitize_settings($value);
            } else {
                $sanitized[$key] = sanitize_text_field($value);
            }
        }
        return $sanitized;
    }

    /**
     * Render products HTML
     */
    private static function render_products($pcat_slug, $settings)
    {
        // Default settings
        $defaults = [
            'bsktab_products_count' => 6,
            'mgpdeg_rownumber' => '4',
            'mgpdeg_rownumber_tab' => '6',
            'mgpdeg_rownumber_mob' => '12',
            'mgpdeg_product_style' => '1',
            'mgpdeg_product_img_show' => 'yes',
            'mgpdeg_badge_show' => 'yes',
            'mgpdeg_img_effects' => 'mgpr-hvr-shine',
            'mgpdeg_img_size' => 'medium_large',
            'mgpdeg_show_title' => 'yes',
            'mgpdeg_crop_title' => 5,
            'mgpdeg_title_tag' => 'h2',
            'mgpdeg_desc_show' => '',
            'mgpdeg_crop_desc' => 10,
            'mgpdeg_price_show' => 'yes',
            'mgpdeg_cart_btn' => 'yes',
            'mgpdeg_btn_type' => 'cart',
            'mgpdeg_card_text' => 'View Details',
            'mgpdeg_category_show' => 'yes',
            'mgpdeg_category_type' => 'selected',
            'mgpdeg_grid_categories' => [],
            'mgpdeg_ratting_show' => 'yes',
            'mgpdeg_badge_discount' => 'hide',
            'mgpdeg_badge_after_text' => '',
            'mgpdeg_badge_before_sign' => '',
            'mgpdeg_img_flip_show' => '',
            'mgpdeg_adicons_show' => '',
            'mgpdeg_adicons_position' => 'right',
            'mgpdeg_wishlist_show' => '',
            'mgpdeg_wishlist_text' => '',
            'mgpdeg_share_show' => '',
            'mgpdeg_share_text' => '',
            'mgpdeg_qrcode_show' => '',
            'mgpdeg_qrcode_text' => '',
            'mgpdeg_video_show' => '',
            'mgpdeg_video_text' => '',
            'mgpdeg_stock_show' => '',
            'mgpdeg_total_stock_show' => '',
            'mgpdeg_stock_text' => '',
            'mgpdeg_total_sold_show' => '',
            'mgpdeg_sold_text' => '',
            'mgpdeg_stock_slide_show' => '',
        ];

        $settings = wp_parse_args($settings, $defaults);

        // Extract settings
        $bsktab_products_count = intval($settings['bsktab_products_count']);
        $mgpdeg_rownumber = $settings['mgpdeg_rownumber'];
        $mgpdeg_product_style = $settings['mgpdeg_product_style'];
        $mgpdeg_product_img_show = $settings['mgpdeg_product_img_show'];
        $mgpdeg_badge_show = $settings['mgpdeg_badge_show'];
        $mgpdeg_cart_btn = $settings['mgpdeg_cart_btn'];
        $mgpdeg_btn_type = $settings['mgpdeg_btn_type'];
        $mgpdeg_card_text = $settings['mgpdeg_card_text'];
        $after_text = $settings['mgpdeg_badge_after_text'];
        $before_sign = $settings['mgpdeg_badge_before_sign'];

        if ($settings['mgpdeg_img_flip_show'] == 'yes' && (get_option('mgppro_is_active', 'no') == 'yes')) {
            $img_effects = 'no-effects';
        } else {
            $img_effects = $settings['mgpdeg_img_effects'];
        }

        // Pro icons
        if (function_exists('yith_wishlist_install')) {
            $mgpdeg_wishlist_show = $settings['mgpdeg_wishlist_show'];
            $mgpdeg_wishlist_text = $settings['mgpdeg_wishlist_text'];
        } else {
            $mgpdeg_wishlist_show = '';
            $mgpdeg_wishlist_text = '';
        }

        $mgpdeg_share_show = $settings['mgpdeg_share_show'];
        $mgpdeg_share_text = $settings['mgpdeg_share_text'];
        $mgpdeg_qrcode_show = $settings['mgpdeg_qrcode_show'];
        $mgpdeg_qrcode_text = $settings['mgpdeg_qrcode_text'];
        $mgpdeg_video_show = $settings['mgpdeg_video_show'];
        $mgpdeg_video_text = $settings['mgpdeg_video_text'];

        ?>
        <div class="row">
            <?php
            $args = array(
                'post_type'             => 'product',
                'post_status'           => 'publish',
                'ignore_sticky_posts'   => 1,
                'posts_per_page'        => $bsktab_products_count,
                'tax_query' => array(
                    array(
                        'taxonomy' => 'product_cat',
                        'field'    => 'slug',
                        'terms'    => $pcat_slug,
                    ),
                ),
            );

            $mgpteb_item_products = new WP_Query($args);
            if ($mgpteb_item_products->have_posts()) :
                while ($mgpteb_item_products->have_posts()) : $mgpteb_item_products->the_post();
                    global $product;
            ?>
                    <div class="col-lg-<?php echo esc_attr($mgpdeg_rownumber); ?> col-md-<?php echo esc_attr($settings['mgpdeg_rownumber_tab']); ?> col-sm-<?php echo esc_attr($settings['mgpdeg_rownumber_mob']); ?>">
                        <div class="mgpde-shadow mgpde-card mgpdeg-card mb-4 mgpde-has-hover">
                            <?php if ($mgpdeg_product_img_show == 'yes') : ?>
                                <div class="mgpde-card-img mgpdeg-card-img <?php echo esc_attr($img_effects); ?>">
                                    <?php
                                    if (class_exists('WooCommerce') && $mgpdeg_badge_show == 'yes') {
                                        mgproducts_display_products_badge(get_the_ID());
                                    }
                                    if (get_option('mgppro_is_active', 'no') == 'yes') {
                                        if ($settings['mgpdeg_badge_discount'] == 'percentage') {
                                            do_action('mgppro_percent_sale_badge', $after_text);
                                        }
                                        if ($settings['mgpdeg_badge_discount'] == 'number') {
                                            do_action('mgppro_number_sale_badge', $before_sign, $after_text);
                                        }
                                    }
                                    ?>
                                    <figure>
                                        <a href="<?php the_permalink(); ?>">
                                            <?php
                                            if ($settings['mgpdeg_img_flip_show'] == 'yes' && (get_option('mgppro_is_active', 'no') == 'yes')) {
                                                do_action('mgppro_flip_product_image', get_the_ID(), $settings['mgpdeg_img_size']);
                                            } else {
                                                the_post_thumbnail($settings['mgpdeg_img_size']);
                                            }
                                            ?>
                                        </a>
                                        <?php if ($settings['mgpdeg_adicons_show'] && get_option('mgppro_is_active', 'no') == 'yes') : ?>
                                            <div class="mgp-exicons exicons-<?php echo esc_attr($settings['mgpdeg_adicons_position']); ?>">
                                                <?php do_action('mgproducts_pro_advance_icons', $mgpdeg_wishlist_show, $mgpdeg_wishlist_text, $mgpdeg_share_show, $mgpdeg_share_text, $mgpdeg_video_show, $mgpdeg_video_text, $mgpdeg_qrcode_show, $mgpdeg_qrcode_text); ?>
                                            </div>
                                        <?php endif; ?>
                                    </figure>
                                    <?php if ($mgpdeg_cart_btn == 'yes' && $mgpdeg_product_style == '2') : ?>
                                        <div class="woocommerce mgpdeg-cart-btn">
                                            <?php if ($mgpdeg_btn_type == 'cart') : ?>
                                                <?php woocommerce_template_loop_add_to_cart(); ?>
                                            <?php else : ?>
                                                <a class="button " href="<?php the_permalink(); ?>"><?php echo esc_html($mgpdeg_card_text); ?></a>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            <?php self::render_product_content($settings); ?>
                        </div>
                    </div>
                <?php
                endwhile;
                wp_reset_postdata();
            else :
                ?>
                <div class="col-12">
                    <p class="text-center"><?php esc_html_e('No products found in this category.', 'magical-products-display'); ?></p>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }

    /**
     * Render product content
     */
    private static function render_product_content($settings)
    {
        global $product;
        $rating_count = $product->get_rating_count();
        $mgpdeg_product_style = $settings['mgpdeg_product_style'];
        $mgpdeg_show_title = $settings['mgpdeg_show_title'];
        $mgpdeg_crop_title = intval($settings['mgpdeg_crop_title']);
        $mgpdeg_title_tag  = $settings['mgpdeg_title_tag'];
        $mgpdeg_desc_show  = $settings['mgpdeg_desc_show'];
        $mgpdeg_crop_desc  = intval($settings['mgpdeg_crop_desc']);
        $mgpdeg_price_show = $settings['mgpdeg_price_show'];
        $mgpdeg_cart_btn   = $settings['mgpdeg_cart_btn'];
        $mgpdeg_category_show = $settings['mgpdeg_category_show'];
        $mgpdeg_ratting_show  = $settings['mgpdeg_ratting_show'];
        $mgpdeg_btn_type      = $settings['mgpdeg_btn_type'];
        $mgpdeg_card_text     = $settings['mgpdeg_card_text'];
        ?>
        <div class="mgpde-card-text mgpdeg-card-text mgp-text-style<?php echo esc_attr($mgpdeg_product_style); ?>">
            <?php if ($mgpdeg_category_show == 'yes' && $mgpdeg_product_style != '2') : ?>
                <div class="mgpde-meta mgpde-category">
                    <?php
                    $category_type = $settings['mgpdeg_category_type'] ?? 'selected';
                    $selected_categories = [];
                    if ($category_type === 'selected' && !empty($settings['mgpdeg_grid_categories'])) {
                        $selected_categories = is_array($settings['mgpdeg_grid_categories'])
                            ? $settings['mgpdeg_grid_categories']
                            : explode(',', str_replace(' ', '', $settings['mgpdeg_grid_categories']));
                    }
                    mgproducts_display_product_category(get_the_ID(), 'product_cat', 1, $category_type, $selected_categories);
                    ?>
                </div>
            <?php endif; ?>
            <?php if ($mgpdeg_ratting_show && $mgpdeg_product_style == '2') : ?>
                <div class="mg-rating-out">
                    <?php echo wp_kses_post(mgproducts_display_wc_get_rating_html()); ?>
                    <?php mgproducts_display_wc_rating_number(); ?>
                </div>
            <?php endif; ?>
            <?php if ($mgpdeg_show_title == 'yes') : ?>
                <a class="mgpde-ptitle-link" href="<?php the_permalink(); ?>">
                    <?php
                    $allowed_tags = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span', 'p'];
                    $title_tag = in_array($mgpdeg_title_tag, $allowed_tags) ? $mgpdeg_title_tag : 'h2';
                    printf(
                        '<%1$s class="mgpde-ptitle">%2$s</%1$s>',
                        esc_html($title_tag),
                        esc_html(wp_trim_words(get_the_title(), $mgpdeg_crop_title))
                    );
                    ?>
                </a>
            <?php endif; ?>
            <?php if ($mgpdeg_category_show == 'yes' && $mgpdeg_product_style == '2') : ?>
                <div class="mgpde-meta mgpde-category">
                    <?php
                    $category_type = $settings['mgpdeg_category_type'] ?? 'selected';
                    $selected_categories = [];
                    if ($category_type === 'selected' && !empty($settings['mgpdeg_grid_categories'])) {
                        $selected_categories = is_array($settings['mgpdeg_grid_categories'])
                            ? $settings['mgpdeg_grid_categories']
                            : explode(',', str_replace(' ', '', $settings['mgpdeg_grid_categories']));
                    }
                    mgproducts_display_product_category(get_the_ID(), 'product_cat', 1, $category_type, $selected_categories);
                    ?>
                </div>
            <?php endif; ?>
            <?php if ($mgpdeg_ratting_show && $mgpdeg_product_style != '2') : ?>
                <div class="mg-rating-out">
                    <?php echo wp_kses_post(mgproducts_display_wc_get_rating_html()); ?>
                    <?php mgproducts_display_wc_rating_number(); ?>
                </div>
            <?php endif; ?>
            <?php if ($mgpdeg_desc_show) : ?>
                <p><?php echo esc_html(wp_trim_words(get_the_content(), $mgpdeg_crop_desc, '...')); ?></p>
            <?php endif; ?>
            <?php if ($mgpdeg_price_show == 'yes' && $mgpdeg_product_style != '3') : ?>
                <div class="mgpdeg-product-price mb-2">
                    <?php woocommerce_template_loop_price(); ?>
                </div>
            <?php endif; ?>
            <?php if ($mgpdeg_cart_btn == 'yes' && $mgpdeg_product_style == '1') : ?>
                <div class="woocommerce mgpdeg-cart-btn">
                    <?php if ($mgpdeg_btn_type == 'cart') : ?>
                        <?php woocommerce_template_loop_add_to_cart(); ?>
                    <?php else : ?>
                        <a class="button " href="<?php the_permalink(); ?>"><?php echo esc_html($mgpdeg_card_text); ?></a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <?php if (($mgpdeg_price_show == 'yes' || $mgpdeg_cart_btn == 'yes') && $mgpdeg_product_style == '3') : ?>
                <div class="mgpdeg-price-btn mb-2 mt-2">
                    <?php
                    if ($mgpdeg_price_show == 'yes') {
                        woocommerce_template_loop_price();
                    }
                    ?>
                    <?php if ($mgpdeg_cart_btn == 'yes') : ?>
                        <div class="woocommerce mgpdeg-cart-link">
                            <?php if ($mgpdeg_btn_type == 'cart') : ?>
                                <?php woocommerce_template_loop_add_to_cart(); ?>
                            <?php else : ?>
                                <a class="button " href="<?php the_permalink(); ?>"><?php echo esc_html($mgpdeg_card_text); ?></a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <?php
            if ($settings['mgpdeg_stock_show'] && get_option('mgppro_is_active', 'no') == 'yes') {
                do_action(
                    'mgppro_products_stock',
                    $settings['mgpdeg_total_stock_show'],
                    $settings['mgpdeg_stock_text'],
                    $settings['mgpdeg_total_sold_show'],
                    $settings['mgpdeg_sold_text'],
                    $settings['mgpdeg_stock_slide_show']
                );
            }
            ?>
        </div>
        <?php
    }
}

MPD_Products_Tab_Ajax::init();
