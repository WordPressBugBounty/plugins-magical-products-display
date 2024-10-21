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
    $hide_date = get_option('mpd_addon_infotext');
    $mpd_install_date = get_option('mpd_install_date');


    $mpd_install_date = get_option('mpd_install_date');
    if (!empty($mpd_install_date)) {
        $mpd_install_date = round((time() - strtotime($mpd_install_date)) / 24 / 60 / 60);
        if ($mpd_install_date < 3) {
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
            update_option('mpd_addon_infotext', current_time('mysql'));
        }
    }
}
add_action('init', 'mpd_display_proinfo_texts_init');
