<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Class PP_Config.
 */
class mgpProWidgets
{
    function __construct()
    {

        add_filter('elementor/editor/localize_settings', [$this, 'get_promotion_widgets']);
        add_action('elementor/editor/after_enqueue_scripts', [$this, 'editor_scripts']);
    }

    function editor_scripts()
    {
        wp_enqueue_script("mpdadmin-el-editor", MAGICAL_PRODUCTS_DISPLAY_ASSETS . 'js/el-editor.js', array('jquery'), MAGICAL_PRODUCTS_DISPLAY_VERSION, true);
        
        // Pass template type to JavaScript for smart category collapse.
        $template_type = $this->get_current_template_type();
        wp_localize_script('mpdadmin-el-editor', 'mpdEditorData', array(
            'templateType' => $template_type,
        ));
    }

    /**
     * Detect the current template type being edited.
     *
     * @since 2.0.0
     * @return string Template type identifier.
     */
    private function get_current_template_type() {
        // Get the post ID being edited.
        $post_id = 0;
        
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        if ( isset( $_GET['post'] ) ) {
            $post_id = absint( $_GET['post'] );
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        } elseif ( isset( $_GET['elementor-preview'] ) ) {
            $post_id = absint( $_GET['elementor-preview'] );
        }

        if ( ! $post_id ) {
            return '';
        }

        // Check if this is one of our templates.
        $post_type = get_post_type( $post_id );
        
        if ( 'mpd_template' === $post_type ) {
            $template_type = get_post_meta( $post_id, '_mpd_template_type', true );
            return $template_type ? $template_type : '';
        }

        // Check WooCommerce page types.
        if ( function_exists( 'wc_get_page_id' ) ) {
            if ( $post_id === wc_get_page_id( 'shop' ) ) {
                return 'archive-product';
            }
            if ( $post_id === wc_get_page_id( 'cart' ) ) {
                return 'cart';
            }
            if ( $post_id === wc_get_page_id( 'checkout' ) ) {
                return 'checkout';
            }
            if ( $post_id === wc_get_page_id( 'myaccount' ) ) {
                return 'my-account';
            }
        }

        // Check for product post type.
        if ( 'product' === $post_type ) {
            return 'single-product';
        }

        return '';
    }

    public function get_promotion_widgets($config)
    {

        $promotion_widgets = [];

        if (isset($config['promotionWidgets'])) {
            $promotion_widgets = $config['promotionWidgets'];
        }

        $pro_widgets = $this::get_pro_widgets();

        $combine_array = array_merge($promotion_widgets, $pro_widgets);

        $config['promotionWidgets'] = $combine_array;

        return $config;
    }



    /**
     * Get Widget List.
     *
     * @since 1.2.9.4
     *
     * @return array The Widget List.
     */
    public static function get_pro_widgets()
    {
        $pro_widgets = [
            [
                'name'       => 'mgppro_compare',
                'title'      => __('Compare Table', 'magical-products-display'),
                'categories' => '["mpd-productwoo"]',
                'keywords'   => ['compare', 'table', 'price', 'pricing', 'product'],
                'icon'       => 'eicon-price-table',
            ],
            [
                'name'       => 'mgppro_pdetails',
                'title'      => __('Product Pro Details', 'magical-products-display'),
                'categories' => '["mpd-productwoo"]',
                'keywords'   => ['product', 'details', 'countdown', 'display'],
                'icon'       => 'eicon-product-info',
            ],
            [
                'name'       => 'mgppro_countdown',
                'title'      => __('Advance Countdown', 'magical-products-display'),
                'categories' => '["mpd-productwoo"]',
                'keywords'   => ['countdown', 'offer', 'product', 'banner'],
                'icon'       => 'eicon-banner',
            ],
            [
                'name'       => 'mgppro_hotspot',
                'title'      => __('Product Hotspots', 'magical-products-display'),
                'categories' => '["mpd-productwoo"]',
                'keywords'   => ['hotspot', 'image', 'product', 'Hotspots', 'marker'],
                'icon'       => 'eicon-image-hotspot',
            ],
            [
                'name'       => 'mgppro_ticker',
                'title'      => __('Product Ticker', 'magical-products-display'),
                'categories' => '["mpd-productwoo"]',
                'keywords'   => ['ticker', 'latest', 'product', 'woo', 'animation'],
                'icon'       => 'eicon-posts-ticker',
            ],


        ];



        return $pro_widgets;
    }
}
$mpd_admin_notices = new mgpProWidgets();
