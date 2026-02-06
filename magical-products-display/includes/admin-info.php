<?php
/*
* Magical products display info
*
*
*/

/**
 * Rev notice text
 *
 */
function mpd_display_pro_want()
{
    $dismiss_url = add_query_arg(array(
        'dismissed' => 1,
        '_wpnonce' => wp_create_nonce('mpd_dismiss_addoninfo_nonce'),
    ));
?>
    <div class="mgadin-hero">
        <div class="mge-info-content">
            <div class="mge-info-hello">
                <?php
                $current_user = wp_get_current_user();
                $mppro_link = 'https://wpthemespace.com/product/magical-products-display-pro/#pricing-mpd';
                $mppro_link1 = 'https://wpthemespace.com/product/magical-products-display-pro/?add-to-cart=9177';

                esc_html_e('Hello, ', 'magical-products-display');
                echo esc_html($current_user->display_name);
                ?>

                <?php esc_html_e('👋🏻', 'magical-products-display'); ?>
            </div>
            <div class="mge-info-desc">
                <div class="mge-offer"><?php echo esc_html('Looking to Boost Sales? 📈', 'magical-products-display'); ?></div>
                <div><?php echo esc_html('Upgrade to the Pro Version of Magical Products Display for powerful filters, stunning layouts, offer countdowns, product hotshots, price comparisons, and the latest product ticker—plus many more features designed to boost your sales. Upgrade today!', 'magical-products-display'); ?></div>
                <div class="mge-rev"><strong><?php echo esc_html('One satisfied user raved, "The Pro version made my product displays absolutely magical!" 🌟🌟🌟🌟🌟', 'magical-products-display'); ?></strong></div>
            </div>
            <div class="mge-info-actions">
                <a href="<?php echo esc_url($mppro_link1); ?>" target="_blank" class="button button-primary upgrade-btn" style="background:#b40000">
                    <?php esc_html_e('Quick Upgrade', 'magical-products-display'); ?>
                </a>
                <a href="<?php echo esc_url($mppro_link); ?>" target="_blank" class="button button-primary upgrade-btn">
                    <?php esc_html_e('View Pricing', 'magical-products-display'); ?>
                </a>
                <a href="<?php echo esc_url($dismiss_url); ?>" class="button button-info mgpd-revdismiss"><?php esc_html_e('Hide Notice', 'magical-products-display') ?></a>

            </div>

        </div>

    </div>
<?php
}


//Admin notice 
function mpd_display_pinfo_optins_texts()
{
    $hide_date = get_option('mpd_addon_info1_text');
    $mpd_install_date = get_option('mpd_install_date');


    $mpd_install_date = get_option('mpd_install_date');
    if (!empty($mpd_install_date)) {
        $mpd_install_date = round((time() - strtotime($mpd_install_date)) / 24 / 60 / 60);
        if ($mpd_install_date < 5) {
            return;
        }
    }
    if (!empty($hide_date)) {
        $clickhide = round((time() - strtotime($hide_date)) / 24 / 60 / 60);
        if ($clickhide < 25) {
            return;
        }
    }

    wp_enqueue_style('admin-info-style');
?>
    <div class="mgaddin-notice notice notice-success mgadin-theme-dashboard mgadin-theme-dashboard-notice mge is-dismissible meis-dismissible">
        <?php mpd_display_pro_want(); ?>
    </div>
<?php


}
add_action('admin_notices', 'mpd_display_pinfo_optins_texts');


function mpd_display_proinfo_texts_init()
{
    // Unsplash and sanitize the 'dismissed' and '_wpnonce' parameters
    $dismissed = isset($_GET['dismissed']) ? sanitize_text_field(wp_unslash($_GET['dismissed'])) : '';
    $nonce = isset($_GET['_wpnonce']) ? sanitize_text_field(wp_unslash($_GET['_wpnonce'])) : '';

    if ($dismissed === '1') {
        // Verify the nonce and ensure user has permission
        if (wp_verify_nonce($nonce, 'mpd_dismiss_addoninfo_nonce') && current_user_can('manage_options')) {
            // Update the option if the nonce is valid
            update_option('mpd_addon_info1_text', gmdate('Y-m-d H:i:s'));
        }
    }
}
add_action('init', 'mpd_display_proinfo_texts_init');

// Guest Checkout Account Creator plugin suggestion notice
function mpd_display_plugin_suggestion_notice()
{
    // Check if we're in admin area
    if (!is_admin()) {
        return;
    }
    
    // Check if user has capability and notice hasn't been dismissed
    if (!current_user_can('install_plugins')) {
        return;
    }
    
    // Only show if WooCommerce is active since this is a WooCommerce-related plugin
    if (!class_exists('WooCommerce')) {
        return;
    }
    
    $hide_date = get_option('mpd_plugin_suggestion_dismissed1');
    if (!empty($hide_date)) {
        return;
    }
    
    // Include plugin.php to use is_plugin_active function
    if (!function_exists('is_plugin_active')) {
        include_once(ABSPATH . 'wp-admin/includes/plugin.php');
    }
    
    // Check if plugin is already installed/active
    $plugin_slug = 'guest-checkout-account-creator/guest-checkout-account-creator.php';
    if (function_exists('is_plugin_active') && is_plugin_active($plugin_slug)) {
        return;
    }
    
    $dismiss_url = add_query_arg(array(
        'mpd_dismiss_suggestion' => '1',
        '_wpnonce' => wp_create_nonce('mpd_dismiss_suggestion_nonce'),
    ));
    
    $plugin_name = 'Guest Checkout Account Creator';
    $plugin_slug_base = 'guest-checkout-account-creator';
    
    // Check if plugin is installed but not active
    $plugin_file = WP_PLUGIN_DIR . '/' . $plugin_slug;
    $is_installed = file_exists($plugin_file);
    
    if ($is_installed) {
        $action_url = wp_nonce_url('plugins.php?action=activate&plugin=' . urlencode($plugin_slug), 'activate-plugin_' . $plugin_slug);
        $action_text = __('Activate Plugin', 'magical-products-display');
        $action_class = 'mpd-activate-plugin';
    } else {
        $action_url = wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin=' . $plugin_slug_base), 'install-plugin_' . $plugin_slug_base);
        $action_text = __('Install Plugin', 'magical-products-display');
        $action_class = 'mpd-install-plugin';
    }
    
    // Make sure CSS is loaded
    wp_enqueue_style('admin-info-style', MAGICAL_PRODUCTS_DISPLAY_ASSETS . 'css/admin-info.css', array(), MAGICAL_PRODUCTS_DISPLAY_VERSION, 'all');
    ?>
    <div class="notice notice-info mpd-plugin-suggestion" data-nonce="<?php echo esc_attr(wp_create_nonce('mpd_dismiss_suggestion')); ?>" data-plugin-slug="guest-checkout-account-creator" data-plugin-file="guest-checkout-account-creator/guest-checkout-account-creator.php">
        <div class="mpd-notice-content">
            <div class="mpd-notice-icon">
                <span class="dashicons dashicons-admin-plugins"></span>
            </div>
            <div class="mpd-notice-text">
                <h3><?php echo esc_html__('Boost Your WooCommerce Sales!', 'magical-products-display'); ?></h3>
                <p><?php echo esc_html__('Enhance your customer experience and increase sales with the Guest Checkout Account Creator plugin. This powerful tool automatically creates customer accounts during guest checkout and sends welcome emails, making it easier for customers to track orders and return for future purchases. Reduce support requests and improve customer satisfaction effortlessly!', 'magical-products-display'); ?></p>
                
                <div class="mpd-notice-actions">
                    <button type="button" class="button button-primary <?php echo esc_attr($action_class); ?>" data-action-url="<?php echo esc_url($action_url); ?>">
                        <?php echo esc_html($action_text); ?>
                    </button>
                  
                    <button type="button" class="button button-secondary mpd-dismiss-suggestion">
                        <?php echo esc_html__('Dismiss', 'magical-products-display'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <?php
}

function mpd_display_plugin_suggestion_init()
{
    // Handle AJAX dismiss request
    if (isset($_POST['action']) && isset($_POST['nonce'])) {
        // Verify nonce first before checking action value
        $nonce = sanitize_text_field(wp_unslash($_POST['nonce']));
        $action = sanitize_text_field(wp_unslash($_POST['action']));
        
        if ($action === 'mpd_dismiss_suggestion') {
            if (!current_user_can('manage_options')) {
                wp_die(esc_html__('You do not have sufficient permissions.', 'magical-products-display'));
            }
            
            if (!wp_verify_nonce($nonce, 'mpd_dismiss_suggestion')) {
                wp_die(esc_html__('Security check failed.', 'magical-products-display'));
            }
            
            update_option('mpd_plugin_suggestion_dismissed1', gmdate('Y-m-d H:i:s'));
            wp_die('success');
        }
    }
    
    // Handle GET dismiss request (fallback)
    $dismissed = isset($_GET['mpd_dismiss_suggestion']) ? sanitize_text_field(wp_unslash($_GET['mpd_dismiss_suggestion'])) : '';
    $nonce = isset($_GET['_wpnonce']) ? sanitize_text_field(wp_unslash($_GET['_wpnonce'])) : '';
    
    if ($dismissed === '1' && current_user_can('manage_options')) {
        if (wp_verify_nonce($nonce, 'mpd_dismiss_suggestion_nonce')) {
            update_option('mpd_plugin_suggestion_dismissed1', gmdate('Y-m-d H:i:s'));
        }
    }
}

add_action('admin_notices', 'mpd_display_plugin_suggestion_notice');
add_action('init', 'mpd_display_plugin_suggestion_init');
add_action('wp_ajax_mpd_dismiss_suggestion', 'mpd_display_plugin_suggestion_init');

// AJAX handler for plugin installation
function mpd_ajax_install_plugin() {
    // Check permissions
    if (!current_user_can('install_plugins')) {
        wp_send_json_error(__('You do not have permission to install plugins.', 'magical-products-display'));
    }
    
    // Verify nonce
    $nonce = isset($_POST['nonce']) ? sanitize_text_field(wp_unslash($_POST['nonce'])) : '';
    if (!wp_verify_nonce($nonce, 'updates')) {
        wp_send_json_error(__('Security check failed.', 'magical-products-display'));
    }
    
    $plugin_slug = isset($_POST['plugin_slug']) ? sanitize_text_field(wp_unslash($_POST['plugin_slug'])) : '';
    
    if (empty($plugin_slug)) {
        wp_send_json_error(__('Plugin slug is required.', 'magical-products-display'));
    }
    
    // Include necessary WordPress files
    if (!function_exists('plugins_api')) {
        require_once(ABSPATH . 'wp-admin/includes/plugin-install.php');
    }
    if (!class_exists('WP_Upgrader')) {
        require_once(ABSPATH . 'wp-admin/includes/class-wp-upgrader.php');
    }
    if (!class_exists('WP_Ajax_Upgrader_Skin')) {
        require_once(ABSPATH . 'wp-admin/includes/class-wp-ajax-upgrader-skin.php');
    }
    if (!function_exists('request_filesystem_credentials')) {
        require_once(ABSPATH . 'wp-admin/includes/file.php');
    }
    
    // Get plugin info
    $api = plugins_api('plugin_information', array(
        'slug' => $plugin_slug,
        'fields' => array(
            'short_description' => false,
            'sections' => false,
            'requires' => false,
            'rating' => false,
            'ratings' => false,
            'downloaded' => false,
            'last_updated' => false,
            'added' => false,
            'tags' => false,
            'compatibility' => false,
            'homepage' => false,
            'donate_link' => false,
        ),
    ));
    
    if (is_wp_error($api)) {
        wp_send_json_error($api->get_error_message());
    }
    
    // Install plugin
    $upgrader = new Plugin_Upgrader(new WP_Ajax_Upgrader_Skin());
    $result = $upgrader->install($api->download_link);
    
    if (is_wp_error($result)) {
        wp_send_json_error($result->get_error_message());
    } elseif (is_null($result)) {
        wp_send_json_error(__('Plugin installation failed.', 'magical-products-display'));
    }
    
    wp_send_json_success(__('Plugin installed successfully.', 'magical-products-display'));
}

// AJAX handler for plugin activation
function mpd_ajax_activate_plugin() {
    // Check permissions
    if (!current_user_can('activate_plugins')) {
        wp_send_json_error(__('You do not have permission to activate plugins.', 'magical-products-display'));
    }
    
    // Verify nonce
    $nonce = isset($_POST['nonce']) ? sanitize_text_field(wp_unslash($_POST['nonce'])) : '';
    if (!wp_verify_nonce($nonce, 'updates')) {
        wp_send_json_error(__('Security check failed.', 'magical-products-display'));
    }
    
    $plugin_file = isset($_POST['plugin_file']) ? sanitize_text_field(wp_unslash($_POST['plugin_file'])) : '';
    
    if (empty($plugin_file)) {
        wp_send_json_error(__('Plugin file is required.', 'magical-products-display'));
    }
    
    // Include necessary WordPress files
    if (!function_exists('activate_plugin')) {
        require_once(ABSPATH . 'wp-admin/includes/plugin.php');
    }
    
    // Activate plugin
    $result = activate_plugin($plugin_file);
    
    if (is_wp_error($result)) {
        wp_send_json_error($result->get_error_message());
    }
    
    wp_send_json_success(__('Plugin activated successfully.', 'magical-products-display'));
}

add_action('wp_ajax_mpd_install_plugin', 'mpd_ajax_install_plugin');
add_action('wp_ajax_mpd_activate_plugin', 'mpd_ajax_activate_plugin');



