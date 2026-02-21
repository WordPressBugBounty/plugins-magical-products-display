<?php
/**
 * Action Buttons Trait
 *
 * Provides action buttons (Compare, Wishlist, Quick View) functionality for product widgets.
 *
 * @package Magical_Products_Display
 * @since   2.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Trait MPD_Action_Buttons
 *
 * Use this trait in product widgets to add Compare, Wishlist, and Quick View buttons.
 *
 * @since 2.0.0
 */
trait MPD_Action_Buttons {

    /**
     * Register Action Buttons content controls.
     *
     * @since 2.0.0
     * @param string $prefix Control prefix for unique IDs (e.g., 'mgpdeg', 'mgpdel')
     */
    protected function register_action_buttons_content_controls( $prefix = 'mgpdeg' ) {
        
        $is_pro = function_exists( 'mpd_is_pro_active' ) && mpd_is_pro_active();

        // Compare & Wishlist Section (Pro)
        $this->start_controls_section(
            $prefix . '_compare_wishlist_section',
            [
                'label' => $is_pro
                    ? __('Compare & Wishlist', 'magical-products-display')
                    : __('Compare & Wishlist', 'magical-products-display') . ' <span style="color:#ff5722;font-weight:600;">(Pro)</span>',
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        if ( ! $is_pro ) {
            $this->add_control(
                $prefix . '_pro_cw_notice',
                [
                    'type'            => \Elementor\Controls_Manager::RAW_HTML,
                    'raw'             => sprintf(
                        '<div style="padding: 10px; background: #fff3cd; border-left: 4px solid #ff5722; margin: 10px 0;">
                            <strong style="color: #ff5722;">%s</strong><br>
                            <span style="color: #666;">%s</span>
                            <a href="%s" target="_blank" style="display: inline-block; margin-top: 5px; color: #ff5722;">%s →</a>
                        </div>',
                        esc_html__( 'Pro Feature', 'magical-products-display' ),
                        esc_html__( 'Compare & Wishlist Buttons requires the Pro version.', 'magical-products-display' ),
                        esc_url( 'https://wpthemespace.com/product/magical-shop-builder/#pricing' ),
                        esc_html__( 'Upgrade to Pro', 'magical-products-display' )
                    ),
                    'content_classes' => 'mpd-pro-notice',
                ]
            );
        }

        $this->add_control(
            $prefix . '_show_compare_btn',
            [
                'label' => __('Show Compare Button', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'magical-products-display'),
                'label_off' => __('No', 'magical-products-display'),
                'return_value' => 'yes',
                'default' => '',
            ]
        );

        $this->add_control(
            $prefix . '_show_wishlist_btn',
            [
                'label' => __('Show Wishlist Button', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'magical-products-display'),
                'label_off' => __('No', 'magical-products-display'),
                'return_value' => 'yes',
                'default' => '',
            ]
        );

        $this->end_controls_section();

        // Action Buttons Section (Quick View & Settings)
        $this->start_controls_section(
            $prefix . '_action_buttons_section',
            [
                'label' => __('Action Buttons', 'magical-products-display'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            $prefix . '_show_quickview_btn',
            [
                'label' => __('Show Quick View Button', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'magical-products-display'),
                'label_off' => __('No', 'magical-products-display'),
                'return_value' => 'yes',
                'default' => '',
            ]
        );

        $this->add_control(
            $prefix . '_action_btn_position',
            [
                'label' => __('Button Position', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'on_image',
                'options' => [
                    'on_image' => __('On Image (Center)', 'magical-products-display'),
                    'top_right' => __('Top Right', 'magical-products-display'),
                    'top_left' => __('Top Left', 'magical-products-display'),
                    'below_image' => __('Below Image', 'magical-products-display'),
                ],
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        ['name' => $prefix . '_show_compare_btn', 'value' => 'yes'],
                        ['name' => $prefix . '_show_wishlist_btn', 'value' => 'yes'],
                        ['name' => $prefix . '_show_quickview_btn', 'value' => 'yes'],
                    ],
                ],
            ]
        );

        $this->add_control(
            $prefix . '_action_btn_style',
            [
                'label' => __('Button Style', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'icon_only',
                'options' => [
                    'icon_only' => __('Icon Only', 'magical-products-display'),
                    'icon_text' => __('Icon & Text', 'magical-products-display'),
                ],
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        ['name' => $prefix . '_show_compare_btn', 'value' => 'yes'],
                        ['name' => $prefix . '_show_wishlist_btn', 'value' => 'yes'],
                        ['name' => $prefix . '_show_quickview_btn', 'value' => 'yes'],
                    ],
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Register Action Buttons style controls.
     *
     * @since 2.0.0
     * @param string $prefix Control prefix for unique IDs (e.g., 'mgpdeg', 'mgpdel')
     */
    protected function register_action_buttons_style_controls( $prefix = 'mgpdeg' ) {
        
        $this->start_controls_section(
            $prefix . '_action_buttons_style',
            [
                'label' => __('Action Buttons', 'magical-products-display'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        ['name' => $prefix . '_show_compare_btn', 'value' => 'yes'],
                        ['name' => $prefix . '_show_wishlist_btn', 'value' => 'yes'],
                        ['name' => $prefix . '_show_quickview_btn', 'value' => 'yes'],
                    ],
                ],
            ]
        );

        // General Action Buttons Settings
        $this->add_control(
            $prefix . '_action_btn_general_heading',
            [
                'label' => __('General', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::HEADING,
            ]
        );

        $this->add_responsive_control(
            $prefix . '_action_btn_icon_size',
            [
                'label' => __('Icon Size', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 40,
                    ],
                ],
                'default' => [
                    'size' => 14,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mpd-action-btn i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => $prefix . '_action_btn_typography',
                'label' => __('Typography', 'magical-products-display'),
                'selector' => '{{WRAPPER}} .mpd-action-btn span',
                'condition' => [
                    $prefix . '_action_btn_style' => 'icon_text',
                ],
            ]
        );

        $this->add_responsive_control(
            $prefix . '_action_btn_padding',
            [
                'label' => __('Padding', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .mpd-action-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            $prefix . '_action_btn_gap',
            [
                'label' => __('Buttons Gap', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 30,
                    ],
                ],
                'default' => [
                    'size' => 8,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mpd-action-buttons' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            $prefix . '_action_btn_border_radius',
            [
                'label' => __('Border Radius', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mpd-action-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Wishlist Button Style
        $this->add_control(
            $prefix . '_wishlist_btn_heading',
            [
                'label' => __('Wishlist Button', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    $prefix . '_show_wishlist_btn' => 'yes',
                ],
            ]
        );

        $this->start_controls_tabs(
            $prefix . '_wishlist_btn_tabs',
            [
                'condition' => [
                    $prefix . '_show_wishlist_btn' => 'yes',
                ],
            ]
        );

        $this->start_controls_tab(
            $prefix . '_wishlist_btn_normal',
            [
                'label' => __('Normal', 'magical-products-display'),
            ]
        );

        $this->add_control(
            $prefix . '_wishlist_btn_color',
            [
                'label' => __('Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mpd-wishlist-btn' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            $prefix . '_wishlist_btn_bg',
            [
                'label' => __('Background', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mpd-wishlist-btn' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            $prefix . '_wishlist_btn_border_color',
            [
                'label' => __('Border Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mpd-wishlist-btn' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            $prefix . '_wishlist_btn_hover',
            [
                'label' => __('Hover', 'magical-products-display'),
            ]
        );

        $this->add_control(
            $prefix . '_wishlist_btn_hover_color',
            [
                'label' => __('Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mpd-wishlist-btn:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            $prefix . '_wishlist_btn_hover_bg',
            [
                'label' => __('Background', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mpd-wishlist-btn:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            $prefix . '_wishlist_btn_hover_border_color',
            [
                'label' => __('Border Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mpd-wishlist-btn:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            $prefix . '_wishlist_btn_active',
            [
                'label' => __('Active', 'magical-products-display'),
            ]
        );

        $this->add_control(
            $prefix . '_wishlist_btn_active_color',
            [
                'label' => __('Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mpd-wishlist-btn.in-wishlist' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            $prefix . '_wishlist_btn_active_bg',
            [
                'label' => __('Background', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mpd-wishlist-btn.in-wishlist' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            $prefix . '_wishlist_btn_active_border_color',
            [
                'label' => __('Border Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mpd-wishlist-btn.in-wishlist' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        // Compare Button Style
        $this->add_control(
            $prefix . '_compare_btn_heading',
            [
                'label' => __('Compare Button', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    $prefix . '_show_compare_btn' => 'yes',
                ],
            ]
        );

        $this->start_controls_tabs(
            $prefix . '_compare_btn_tabs',
            [
                'condition' => [
                    $prefix . '_show_compare_btn' => 'yes',
                ],
            ]
        );

        $this->start_controls_tab(
            $prefix . '_compare_btn_normal',
            [
                'label' => __('Normal', 'magical-products-display'),
            ]
        );

        $this->add_control(
            $prefix . '_compare_btn_color',
            [
                'label' => __('Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mpd-compare-btn' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            $prefix . '_compare_btn_bg',
            [
                'label' => __('Background', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mpd-compare-btn' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            $prefix . '_compare_btn_border_color',
            [
                'label' => __('Border Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mpd-compare-btn' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            $prefix . '_compare_btn_hover',
            [
                'label' => __('Hover', 'magical-products-display'),
            ]
        );

        $this->add_control(
            $prefix . '_compare_btn_hover_color',
            [
                'label' => __('Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mpd-compare-btn:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            $prefix . '_compare_btn_hover_bg',
            [
                'label' => __('Background', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mpd-compare-btn:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            $prefix . '_compare_btn_hover_border_color',
            [
                'label' => __('Border Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mpd-compare-btn:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            $prefix . '_compare_btn_active',
            [
                'label' => __('Active', 'magical-products-display'),
            ]
        );

        $this->add_control(
            $prefix . '_compare_btn_active_color',
            [
                'label' => __('Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mpd-compare-btn.in-compare' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            $prefix . '_compare_btn_active_bg',
            [
                'label' => __('Background', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mpd-compare-btn.in-compare' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            $prefix . '_compare_btn_active_border_color',
            [
                'label' => __('Border Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mpd-compare-btn.in-compare' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        // Quick View Button Style
        $this->add_control(
            $prefix . '_quickview_btn_heading',
            [
                'label' => __('Quick View Button', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    $prefix . '_show_quickview_btn' => 'yes',
                ],
            ]
        );

        $this->start_controls_tabs(
            $prefix . '_quickview_btn_tabs',
            [
                'condition' => [
                    $prefix . '_show_quickview_btn' => 'yes',
                ],
            ]
        );

        $this->start_controls_tab(
            $prefix . '_quickview_btn_normal',
            [
                'label' => __('Normal', 'magical-products-display'),
            ]
        );

        $this->add_control(
            $prefix . '_quickview_btn_color',
            [
                'label' => __('Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mpd-quick-view-btn' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            $prefix . '_quickview_btn_bg',
            [
                'label' => __('Background', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mpd-quick-view-btn' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            $prefix . '_quickview_btn_border_color',
            [
                'label' => __('Border Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mpd-quick-view-btn' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            $prefix . '_quickview_btn_hover',
            [
                'label' => __('Hover', 'magical-products-display'),
            ]
        );

        $this->add_control(
            $prefix . '_quickview_btn_hover_color',
            [
                'label' => __('Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mpd-quick-view-btn:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            $prefix . '_quickview_btn_hover_bg',
            [
                'label' => __('Background', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mpd-quick-view-btn:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            $prefix . '_quickview_btn_hover_border_color',
            [
                'label' => __('Border Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mpd-quick-view-btn:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    /**
     * Render action buttons HTML.
     *
     * @since 2.0.0
     * @param array  $settings Widget settings
     * @param string $position Position context ('on_image', 'below_image')
     * @param string $prefix   Control prefix for unique IDs
     */
    protected function render_action_buttons_html( $settings, $position, $prefix = 'mgpdeg' ) {
        $btn_position = isset( $settings[ $prefix . '_action_btn_position' ] ) ? $settings[ $prefix . '_action_btn_position' ] : 'on_image';
        
        // Only render if position matches
        if ( $position === 'below_image' && $btn_position !== 'below_image' ) {
            return;
        }
        if ( $position === 'on_image' && ! in_array( $btn_position, [ 'on_image', 'top_right', 'top_left' ] ) ) {
            return;
        }

        $is_pro = function_exists( 'mpd_is_pro_active' ) && mpd_is_pro_active();

        $show_compare   = $is_pro && isset( $settings[ $prefix . '_show_compare_btn' ] ) && $settings[ $prefix . '_show_compare_btn' ] === 'yes';
        $show_wishlist  = $is_pro && isset( $settings[ $prefix . '_show_wishlist_btn' ] ) && $settings[ $prefix . '_show_wishlist_btn' ] === 'yes';
        $show_quickview = isset( $settings[ $prefix . '_show_quickview_btn' ] ) && $settings[ $prefix . '_show_quickview_btn' ] === 'yes';

        if ( ! $show_compare && ! $show_wishlist && ! $show_quickview ) {
            return;
        }

        global $product;
        if ( ! $product ) {
            $product = wc_get_product( get_the_ID() );
        }
        if ( ! $product ) {
            return;
        }

        $product_id = $product->get_id();
        $btn_style  = isset( $settings[ $prefix . '_action_btn_style' ] ) ? $settings[ $prefix . '_action_btn_style' ] : 'icon_only';
        $show_text  = $btn_style === 'icon_text';

        // Position classes
        $position_class = 'mpd-action-buttons';
        if ( $btn_position === 'on_image' ) {
            $position_class .= ' mpd-action-buttons--on-image';
        } elseif ( $btn_position === 'top_right' ) {
            $position_class .= ' mpd-action-buttons--top-right';
        } elseif ( $btn_position === 'top_left' ) {
            $position_class .= ' mpd-action-buttons--top-left';
        } elseif ( $btn_position === 'below_image' ) {
            $position_class .= ' mpd-action-buttons--below-image';
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
