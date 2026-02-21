<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}
/**
 *  Magical portducts display plugin style and scripts
 * 
 * 
 */

class mpdAssetsManagement
{
    public static function init()
    {
        // Register styles & scripts early so get_style_depends() / get_script_depends() work on frontend.
        // Priority 5 ensures handles exist BEFORE our template renderer parses widget deps at priority 8.
        add_action('wp_enqueue_scripts', [__CLASS__, 'register_widget_styles'], 5);
        add_action('wp_enqueue_scripts', [__CLASS__, 'register_widget_scripts'], 5);
        add_action('elementor/frontend/after_enqueue_styles', [__CLASS__, 'frontend_widget_styles']);
        add_action("elementor/frontend/after_enqueue_scripts", [__CLASS__, 'frontend_widget_scripts']);
        add_action('admin_enqueue_scripts', [__CLASS__, 'admin_scripts']);
        add_action('elementor/editor/after_enqueue_styles', [__CLASS__, 'editor_widget_styles']);
        add_action('elementor/preview/enqueue_styles', [__CLASS__, 'preview_widget_styles']);
        // Enqueue recently viewed tracking script on single product pages
        add_action('wp_enqueue_scripts', [__CLASS__, 'enqueue_product_tracking']);
    }
    
    /**
     * Enqueue recently viewed product tracking script on single product pages.
     * This ensures products are tracked even when the Recently Viewed widget isn't on the page.
     *
     * @since 1.0.0
     * @return void
     */
    public static function enqueue_product_tracking()
    {
        // Only on single product pages
        if (!function_exists('is_product') || !is_product()) {
            return;
        }
        
        // Register if not already registered
        if (!wp_script_is('mpd-global-widgets', 'registered')) {
            wp_register_script(
                'mpd-global-widgets',
                MAGICAL_PRODUCTS_DISPLAY_ASSETS . 'js/mpd-global-widgets.js',
                array('jquery'),
                MAGICAL_PRODUCTS_DISPLAY_VERSION,
                true
            );
        }
        
        // Enqueue the script
        wp_enqueue_script('mpd-global-widgets');
    }
    
    /**
     * Register widget styles early so get_style_depends() works on frontend.
     * This must run before Elementor processes widget dependencies.
     *
     * @since 2.0.0
     * @return void
     */
    public static function register_widget_styles()
    {
        // --- Page-type specific widget styles ---
        wp_register_style('mpd-single-product',  MAGICAL_PRODUCTS_DISPLAY_ASSETS . 'css/widgets/mpd-single-product.css', array(), MAGICAL_PRODUCTS_DISPLAY_VERSION, 'all');
        wp_register_style('mpd-cart-widgets',  MAGICAL_PRODUCTS_DISPLAY_ASSETS . 'css/widgets/mpd-cart-widgets.css', array(), MAGICAL_PRODUCTS_DISPLAY_VERSION, 'all');
        wp_register_style('mpd-checkout-widgets',  MAGICAL_PRODUCTS_DISPLAY_ASSETS . 'css/widgets/mpd-checkout-widgets.css', array(), MAGICAL_PRODUCTS_DISPLAY_VERSION, 'all');
        wp_register_style('mpd-multi-step-checkout',  MAGICAL_PRODUCTS_DISPLAY_ASSETS . 'css/widgets/mpd-multi-step-checkout.css', array(), MAGICAL_PRODUCTS_DISPLAY_VERSION, 'all');
        wp_register_style('mpd-express-checkout',  MAGICAL_PRODUCTS_DISPLAY_ASSETS . 'css/widgets/mpd-express-checkout.css', array(), MAGICAL_PRODUCTS_DISPLAY_VERSION, 'all');
        wp_register_style('mpd-my-account-widgets',  MAGICAL_PRODUCTS_DISPLAY_ASSETS . 'css/widgets/mpd-my-account-widgets.css', array(), MAGICAL_PRODUCTS_DISPLAY_VERSION, 'all');
        wp_register_style('mpd-shop-archive-widgets',  MAGICAL_PRODUCTS_DISPLAY_ASSETS . 'css/widgets/mpd-shop-archive-widgets.css', array(), MAGICAL_PRODUCTS_DISPLAY_VERSION, 'all');
        wp_register_style('mpd-global-widgets',  MAGICAL_PRODUCTS_DISPLAY_ASSETS . 'css/widgets/mpd-global-widgets.css', array(), MAGICAL_PRODUCTS_DISPLAY_VERSION, 'all');
        wp_register_style('mpd-thankyou-widgets',  MAGICAL_PRODUCTS_DISPLAY_ASSETS . 'css/widgets/mpd-thankyou-widgets.css', array(), MAGICAL_PRODUCTS_DISPLAY_VERSION, 'all');

        // --- Shared / library styles (also in frontend_widget_styles, registered early as safety net) ---
        // These must be registered before the template renderer parses widget deps at priority 8.
        wp_register_style('bootstrap-custom', MAGICAL_PRODUCTS_DISPLAY_ASSETS . 'css/bootstrap-custom.css', array(), '5.1.0', 'all');
        wp_register_style('bootstrap-grid',  MAGICAL_PRODUCTS_DISPLAY_ASSETS . 'css/bootstrap-grid.min.css', array(), '5.2.0', 'all');
        if (!wp_style_is('swiper', 'registered')) {
            wp_register_style('swiper',  MAGICAL_PRODUCTS_DISPLAY_ASSETS . 'css/swiper.min.css', array(), '8.4.5', 'all');
        }
        wp_register_style('mgproducts-hover-card',  MAGICAL_PRODUCTS_DISPLAY_ASSETS . 'css/imagehover.min.css', array(), '1.0', 'all');
        wp_register_style('mgproducts-tab',  MAGICAL_PRODUCTS_DISPLAY_ASSETS . 'css/widgets/mpd-tabs.css', array(), '1.0', 'all');
        wp_register_style('mgproducts-pricing',  MAGICAL_PRODUCTS_DISPLAY_ASSETS . 'css/widgets/mpd-pricing.css', array(), MAGICAL_PRODUCTS_DISPLAY_VERSION, 'all');
        wp_register_style('mgproducts-accordion',  MAGICAL_PRODUCTS_DISPLAY_ASSETS . 'css/widgets/mpd-accordion.css', array(), MAGICAL_PRODUCTS_DISPLAY_VERSION, 'all');
        wp_register_style('mpd-ajax-search',  MAGICAL_PRODUCTS_DISPLAY_ASSETS . 'css/mpd-ajax-search.css', array(), MAGICAL_PRODUCTS_DISPLAY_VERSION, 'all');
        wp_register_style('mpd-advanced-filter',  MAGICAL_PRODUCTS_DISPLAY_ASSETS . 'css/widgets/mpd-advanced-filter.css', array(), MAGICAL_PRODUCTS_DISPLAY_VERSION, 'all');
        wp_register_style('mpd-header-wishlist-compare',  MAGICAL_PRODUCTS_DISPLAY_ASSETS . 'css/mpd-header-wishlist-compare.css', array(), MAGICAL_PRODUCTS_DISPLAY_VERSION, 'all');
        // WC action buttons style
        wp_register_style('mpd-wc-action-buttons',  MAGICAL_PRODUCTS_DISPLAY_ASSETS . 'css/mpd-wc-action-buttons.css', array(), MAGICAL_PRODUCTS_DISPLAY_VERSION, 'all');
        // Main display style (registered here, enqueued by frontend_widget_styles or template renderer)
        wp_register_style('mgproducts-style',  MAGICAL_PRODUCTS_DISPLAY_ASSETS . 'css/mpd-display-style.css', array(), MAGICAL_PRODUCTS_DISPLAY_VERSION, 'all');
    }

    /**
     * Register all widget script handles early so get_script_depends() works.
     * Localization data is added by frontend_widget_scripts() when Elementor's hook fires.
     *
     * @since 2.0.0
     * @return void
     */
    public static function register_widget_scripts()
    {
        wp_register_script('bootstrap-bundle', MAGICAL_PRODUCTS_DISPLAY_ASSETS . 'js/bootstrap.bundle.min.js', array('jquery'), '5.1.0', true);
        // Register custom swiper as fallback if Elementor's swiper is not available.
        if (!wp_script_is('swiper', 'registered')) {
            wp_register_script('swiper', MAGICAL_PRODUCTS_DISPLAY_ASSETS . 'js/swiper.min.js', array('jquery'), '8.4.7', true);
        }
        wp_register_script('mg-swiper', MAGICAL_PRODUCTS_DISPLAY_ASSETS . 'js/swiper.min.js', array('jquery'), '8.4.7', true);
        wp_register_script('mgproducts-script', MAGICAL_PRODUCTS_DISPLAY_ASSETS . 'js/main-scripts.js', array('jquery'), MAGICAL_PRODUCTS_DISPLAY_VERSION, true);
        wp_register_script('mgproducts-slider', MAGICAL_PRODUCTS_DISPLAY_ASSETS . 'js/widgets-active/products-slider-active.js', array('jquery'), MAGICAL_PRODUCTS_DISPLAY_VERSION, true);
        wp_register_script('mgproducts-carousel', MAGICAL_PRODUCTS_DISPLAY_ASSETS . 'js/widgets-active/products-carousel-active.js', array('jquery', 'swiper'), MAGICAL_PRODUCTS_DISPLAY_VERSION, true);
        wp_register_script('mgproducts-tcarousel', MAGICAL_PRODUCTS_DISPLAY_ASSETS . 'js/widgets-active/testimonail-carousel-active.js', array('jquery'), MAGICAL_PRODUCTS_DISPLAY_VERSION, true);
        wp_register_script('mpd-ajax-search', MAGICAL_PRODUCTS_DISPLAY_ASSETS . 'js/mpd-ajax-search.js', array('jquery'), MAGICAL_PRODUCTS_DISPLAY_VERSION, true);
        wp_register_script('mpd-products-tab-ajax', MAGICAL_PRODUCTS_DISPLAY_ASSETS . 'js/mpd-products-tab-ajax.js', array('jquery'), MAGICAL_PRODUCTS_DISPLAY_VERSION, true);
        wp_register_script('mpd-global-widgets', MAGICAL_PRODUCTS_DISPLAY_ASSETS . 'js/mpd-global-widgets.js', array('jquery'), MAGICAL_PRODUCTS_DISPLAY_VERSION, true);
        wp_register_script('mpd-shop-archive', MAGICAL_PRODUCTS_DISPLAY_ASSETS . 'js/mpd-shop-archive.js', array('jquery'), MAGICAL_PRODUCTS_DISPLAY_VERSION, true);
        wp_register_script('mpd-advanced-filter', MAGICAL_PRODUCTS_DISPLAY_ASSETS . 'js/mpd-advanced-filter.js', array('jquery', 'jquery-ui-slider', 'wc-price-slider', 'mpd-shop-archive'), MAGICAL_PRODUCTS_DISPLAY_VERSION, true);
        wp_register_script('mpd-add-to-cart', MAGICAL_PRODUCTS_DISPLAY_ASSETS . 'js/widgets/mpd-add-to-cart.js', array('jquery'), MAGICAL_PRODUCTS_DISPLAY_VERSION, true);
        wp_register_script('mpd-cart-table', MAGICAL_PRODUCTS_DISPLAY_ASSETS . 'js/widgets/mpd-cart-table.js', array('jquery'), MAGICAL_PRODUCTS_DISPLAY_VERSION, true);
        wp_register_script('mpd-mini-cart', MAGICAL_PRODUCTS_DISPLAY_ASSETS . 'js/widgets/mpd-mini-cart.js', array('jquery'), MAGICAL_PRODUCTS_DISPLAY_VERSION, true);
        wp_register_script('mpd-cross-sells', MAGICAL_PRODUCTS_DISPLAY_ASSETS . 'js/widgets/mpd-cross-sells.js', array('jquery', 'mg-swiper'), MAGICAL_PRODUCTS_DISPLAY_VERSION, true);
        wp_register_script('mpd-coupon-form', MAGICAL_PRODUCTS_DISPLAY_ASSETS . 'js/widgets/mpd-coupon-form.js', array('jquery'), MAGICAL_PRODUCTS_DISPLAY_VERSION, true);
        wp_register_script('mpd-checkout-widgets', MAGICAL_PRODUCTS_DISPLAY_ASSETS . 'js/widgets/mpd-checkout-widgets.js', array('jquery'), MAGICAL_PRODUCTS_DISPLAY_VERSION, true);
        wp_register_script('mpd-multi-step-checkout', MAGICAL_PRODUCTS_DISPLAY_ASSETS . 'js/widgets/mpd-multi-step-checkout.js', array('jquery'), MAGICAL_PRODUCTS_DISPLAY_VERSION, true);
        wp_register_script('mpd-my-account-widgets', MAGICAL_PRODUCTS_DISPLAY_ASSETS . 'js/widgets/mpd-my-account-widgets.js', array('jquery'), MAGICAL_PRODUCTS_DISPLAY_VERSION, true);
    }
    
    public static function frontend_widget_styles()
    {
        // Register swiper style (not registered in register_widget_styles since Elementor may provide it).
        if (!wp_style_is('swiper', 'registered')) {
            wp_register_style('swiper',  MAGICAL_PRODUCTS_DISPLAY_ASSETS . 'css/swiper.min.css', array(), '8.4.5', 'all');
        }

        // Enqueue main display style (all shared styles registered earlier in register_widget_styles).
        wp_enqueue_style('mgproducts-style');
    }
    public static function frontend_widget_scripts()
    {
        // All script handles are already registered in register_widget_scripts().
        // This method only adds localization data and enqueues the main script.

        // Localize Global Widgets script (Wishlist, Comparison, Header Cart, Recently Viewed).
        wp_localize_script('mpd-global-widgets', 'mpdGlobalWidgets', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('mpd_global_widgets_nonce'),
            'cartUrl' => class_exists('WooCommerce') ? wc_get_cart_url() : '',
            'checkoutUrl' => class_exists('WooCommerce') ? wc_get_checkout_url() : '',
            'i18n' => array(
                'addedToWishlist' => esc_html__('Added to wishlist', 'magical-products-display'),
                'removedFromWishlist' => esc_html__('Removed from wishlist', 'magical-products-display'),
                'addedToComparison' => esc_html__('Added to comparison', 'magical-products-display'),
                'removedFromComparison' => esc_html__('Removed from comparison', 'magical-products-display'),
                'comparisonFull' => esc_html__('Comparison list is full (max 4 items)', 'magical-products-display'),
                'alreadyInWishlist' => esc_html__('Already in wishlist', 'magical-products-display'),
                'alreadyInComparison' => esc_html__('Already in comparison', 'magical-products-display'),
            )
        ));
        
        // Enqueue main script.
        wp_enqueue_script('mgproducts-script');
        
        // Localize Products Tab AJAX script.
        wp_localize_script('mpd-products-tab-ajax', 'mpdTabAjax', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('mpd_tab_ajax_nonce')
        ));
        
        // Localize AJAX Search script.
        wp_localize_script('mpd-ajax-search', 'mpdAjaxSearch', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'shopUrl' => class_exists('WooCommerce') ? get_permalink(wc_get_page_id('shop')) : home_url('/shop/'),
            'currencySymbol' => class_exists('WooCommerce') ? get_woocommerce_currency_symbol() : '$'
        ));
        
        // Localize Shop Archive script.
        wp_localize_script('mpd-shop-archive', 'mpdShopArchive', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('mpd_shop_archive_nonce'),
            'i18n' => array(
                'loading' => esc_html__('Loading more products...', 'magical-products-display'),
                'noMore' => esc_html__('No more products to load', 'magical-products-display'),
                'error' => esc_html__('Error loading products', 'magical-products-display'),
                'filtering' => esc_html__('Filtering products...', 'magical-products-display'),
                'noProducts' => esc_html__('No products found matching your criteria.', 'magical-products-display'),
            )
        ));
        
        // Localize Checkout Widgets script.
        wp_localize_script('mpd-checkout-widgets', 'mpd_checkout_params', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('mpd_checkout_nonce'),
        ));
        
        // Localize My Account Widgets script.
        wp_localize_script('mpd-my-account-widgets', 'mpdMyAccountWidgets', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce'   => wp_create_nonce('mpd_my_account_nonce'),
            'i18n'    => array(
                'saveError'       => esc_html__('Error saving address. Please try again.', 'magical-products-display'),
                'uploadError'     => esc_html__('Error uploading avatar. Please try again.', 'magical-products-display'),
                'invalidFileType' => esc_html__('Please select an image file.', 'magical-products-display'),
                'fileTooLarge'    => esc_html__('File is too large. Maximum size is 2MB.', 'magical-products-display'),
            )
        ));
    }
    /**
     * Init admin js
     *
     * Include js files 
     *
     * @since 1.0.13
     *
     * @access public
     */
    public static function admin_scripts()
    {
        wp_register_style('admin-info-style', MAGICAL_PRODUCTS_DISPLAY_ASSETS . 'css/admin-info.css', array(), MAGICAL_PRODUCTS_DISPLAY_VERSION, 'all');
        wp_enqueue_script('mgpd-admin-js', MAGICAL_PRODUCTS_DISPLAY_ASSETS . 'js/admin.js',   array('jquery'), MAGICAL_PRODUCTS_DISPLAY_VERSION, true);
        
        // Localize script for AJAX
        wp_localize_script('mgpd-admin-js', 'mpd_admin_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('mpd_admin_nonce'),
            'updates_nonce' => wp_create_nonce('updates')
        ));
    }

    public static function editor_widget_styles()
    {
        wp_enqueue_style('mpd-editor-style', MAGICAL_PRODUCTS_DISPLAY_ASSETS . 'css/mpd-editor-style.css', array(), MAGICAL_PRODUCTS_DISPLAY_VERSION, 'all');
        // Enqueue all page-type widget styles for editor/preview.
        self::enqueue_all_widget_styles();
    }

    /**
     * Enqueue styles for Elementor preview mode.
     *
     * @since 2.0.0
     *
     * @return void
     */
    public static function preview_widget_styles()
    {
        self::enqueue_all_widget_styles();
    }

    /**
     * Enqueue all widget page-type styles.
     * Shared by both editor and preview modes.
     *
     * @since 2.0.0
     *
     * @return void
     */
    private static function enqueue_all_widget_styles()
    {
        $styles = array(
            'mpd-single-product',
            'mpd-cart-widgets',
            'mpd-checkout-widgets',
            'mpd-multi-step-checkout',
            'mpd-express-checkout',
            'mpd-my-account-widgets',
            'mpd-shop-archive-widgets',
            'mpd-global-widgets',
            'mpd-thankyou-widgets',
            'mpd-ajax-search',
            'mpd-wc-action-buttons',
            'mpd-header-wishlist-compare',
        );

        foreach ( $styles as $handle ) {
            wp_enqueue_style( $handle );
        }
    }
}
mpdAssetsManagement::init();
