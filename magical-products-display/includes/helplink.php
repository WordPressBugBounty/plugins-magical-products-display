<?php
/*
* Doc Help link 
*
*
*/

trait mpdProHelpLink
{
    public function link_pro_added()
    {
        if (get_option('mgppro_is_active') == 'yes') {
            return;
        }

        $this->start_controls_section(
            'mgpd_gopro',
            [
                'label' => esc_html__('Upgrade Pro | Start Only $24!!', 'magical-products-display'),
            ]
        );
        $this->add_control(
            'mgpd__pro',
            [
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => mpd_goprolink([
                    'title' => esc_html__('Get All Pro Features', 'magical-products-display'),
                    'massage' => esc_html__('Unlock all pro featurs and widgets. Upgrade pro to fully recharge your WoooCommerce shop.', 'magical-products-display'),
                    'link' => 'https://wpthemespace.com/product/magical-products-display-pro/?add-to-cart=9177',
                ]),
            ]
        );
        $this->end_controls_section();
    }

     public function pro_help_link($title, $url, $desc = '') {
        $html = '<div class="mpd-pro-help-link" style="margin: 16px 0; padding: 12px; background: #f8fafc; border: 1px solid #e1e5e9; border-radius: 8px;">';
        $html .= '<strong style="font-size: 15px; color: #2563eb;">' . esc_html($title) . '</strong>';
        if ($desc) {
            $html .= '<div style="margin: 8px 0 0; color: #64748b; font-size: 13px;">' . esc_html($desc) . '</div>';
        }
        $html .= '<div style="margin-top: 10px;"><a href="' . esc_url($url) . '" target="_blank" style="color: #2563eb; text-decoration: underline; font-weight: 500;">' . esc_html__('Learn More', 'magical-products-display') . '</a></div>';
        $html .= '</div>';
        return $html;
    }
}
