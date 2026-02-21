<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}




class mgProducts_AJAX_Search extends \Elementor\Widget_Base
{
    use mpdProHelpLink;



    /**
     * Get widget name.
     *
     * @return string Widget name.
     * @since 1.0.0
     * @access public
     */
    public function get_name()
    {
        return 'mpd_ajax_search';
    }

    /**
     * Get widget title.
     *
     * @return string Widget title.
     * @since 1.0.0
     * @access public
     */
    public function get_title()
    {
        return __('MPD AJAX Search', 'magical-products-display');
    }

    /**
     * Get widget icon.
     *
     * @return string Widget icon.
     * @since 1.0.0
     * @access public
     */
    public function get_icon()
    {
        return 'eicon-search';
    }

    /**
     * Get widget categories.
     *
     * @return array Widget categories.
     * @since 1.0.0
     * @access public
     */
    public function get_categories()
    {
        return ['mpd-productwoo'];
    }

    public function get_keywords()
    {
        return ['mpd', 'woo', 'product', 'search', 'ajax', 'ecommerce'];
    }

    /**
     * Get style dependencies.
     *
     * @return array Widget style dependencies.
     */
    public function get_style_depends()
    {
        return ['mpd-ajax-search'];
    }

    /**
     * Get script dependencies.
     *
     * @return array Widget script dependencies.
     */
    public function get_script_depends()
    {
        return ['mpd-ajax-search'];
    }

    /**
     * Register widget controls.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function register_controls()
    {
        $this->register_content_controls();
        $this->register_style_controls();
        $this->register_advanced_controls();
    }

    /**
     * Register content controls.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function register_content_controls()
    {
        // Search Settings Section
        $this->start_controls_section(
            'section_search_settings',
            [
                'label' => __('Search Settings', 'magical-products-display'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'placeholder_text',
            [
                'label' => __('Placeholder Text', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Search products...', 'magical-products-display'),
                'placeholder' => __('Enter placeholder text', 'magical-products-display'),
            ]
        );

        $this->add_control(
            'min_characters',
            [
                'label' => __('Minimum Characters', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 3,
                'min' => 1,
                'max' => 10,
                'description' => __('Minimum characters to trigger search', 'magical-products-display'),
            ]
        );

        $this->add_control(
            'search_delay',
            [
                'label' => __('Search Delay (ms)', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 300,
                'min' => 100,
                'max' => 2000,
                'step' => 100,
                'description' => __('Delay before search starts (prevents excessive requests)', 'magical-products-display'),
            ]
        );

        $this->add_control(
            'results_limit',
            [
                'label' => __('Results Limit', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 10,
                'min' => 1,
                'max' => 50,
                'description' => __('Maximum number of search results', 'magical-products-display'),
            ]
        );

        $this->add_control(
            'show_search_icon',
            [
                'label' => __('Show Search Icon', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'magical-products-display'),
                'label_off' => __('Hide', 'magical-products-display'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_clear_button',
            [
                'label' => __('Show Clear Button', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'magical-products-display'),
                'label_off' => __('Hide', 'magical-products-display'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        // Pro Filter Settings Section
        $this->start_controls_section(
            'section_pro_filters',
            [
                'label' => __('Pro Filter Settings', 'magical-products-display'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        if (get_option('mgppro_is_active', 'no') !== 'yes') {
            $this->add_control(
                'pro_filters_notice',
                [
                    'type' => \Elementor\Controls_Manager::RAW_HTML,
                    'raw' => $this->pro_help_link(
                        esc_html__('Unlock Advanced Filter Features with Pro!', 'magical-products-display'),
                        'https://wpthemespace.com/product/magical-products-display-pro/',
                        esc_html__('Advanced filters, custom layouts, and more search options are available in the Pro version.', 'magical-products-display')
                    ),
                ]
            );
        } else {
            $this->add_control(
                'enable_filters',
                [
                    'label' => __('Enable Filters', 'magical-products-display'),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => __('Yes', 'magical-products-display'),
                    'label_off' => __('No', 'magical-products-display'),
                    'return_value' => 'yes',
                    'default' => 'no',
                ]
            );

            $this->add_control(
                'filter_layout_style',
                [
                    'label' => __('Filter Layout Style', 'magical-products-display'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'youtube_style',
                    'options' => [
                        'youtube_style' => __('Toggle-Style Filters', 'magical-products-display'),
                        'integrated' => __('Integrated Filters', 'magical-products-display'),
                    ],
                    'condition' => [
                        'enable_filters' => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'available_filters',
                [
                    'label' => __('Available Filters', 'magical-products-display'),
                    'type' => \Elementor\Controls_Manager::SELECT2,
                    'multiple' => true,
                    'options' => [
                        'categories' => __('Categories', 'magical-products-display'),
                        'tags' => __('Tags', 'magical-products-display'),
                        'price_range' => __('Price Range', 'magical-products-display'),
                        'featured' => __('Featured Products', 'magical-products-display'),
                        'stock_status' => __('Stock Status', 'magical-products-display'),
                    ],
                    'default' => ['categories'],
                    'condition' => [
                        'enable_filters' => 'yes',
                    ],
                ]
            );
        }

        $this->end_controls_section();
    }

    /**
     * Register style controls.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function register_style_controls()
    {
        // Search Bar Style Section
        $this->start_controls_section(
            'section_search_bar_style',
            [
                'label' => __('Search Bar Style', 'magical-products-display'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'search_bar_width',
            [
                'label' => __('Width', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vw'],
                'range' => [
                    'px' => [
                        'min' => 200,
                        'max' => 1000,
                    ],
                    '%' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mpd-ajax-search__container' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'search_bar_height',
            [
                'label' => __('Height', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 30,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mpd-ajax-search__input' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'search_bar_bg_color',
            [
                'label' => __('Background Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .mpd-ajax-search__input' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'search_bar_text_color',
            [
                'label' => __('Text Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .mpd-ajax-search__input' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .mpd-ajax-search__input::placeholder' => 'color: {{VALUE}}; opacity: 0.7;',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'search_bar_border',
                'label' => __('Border', 'magical-products-display'),
                'selector' => '{{WRAPPER}} .mpd-ajax-search__input',
            ]
        );

        $this->add_control(
            'search_bar_border_radius',
            [
                'label' => __('Border Radius', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 25,
                    'right' => 25,
                    'bottom' => 25,
                    'left' => 25,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mpd-ajax-search__input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'search_bar_typography',
                'label' => __('Typography', 'magical-products-display'),
                'selector' => '{{WRAPPER}} .mpd-ajax-search__input',
            ]
        );

        $this->add_control(
            'search_bar_padding',
            [
                'label' => __('Padding', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mpd-ajax-search__input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Icon Style Section
        $this->start_controls_section(
            'section_icon_style',
            [
                'label' => __('Icon Style', 'magical-products-display'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_search_icon' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'icon_size',
            [
                'label' => __('Icon Size', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'size' => 18,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mpd-ajax-search__icon' => 'font-size: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label' => __('Icon Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .mpd-ajax-search__icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_position',
            [
                'label' => __('Icon Position', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'left',
                'options' => [
                    'left' => __('Left', 'magical-products-display'),
                    'right' => __('Right', 'magical-products-display'),
                ],
            ]
        );

        $this->end_controls_section();

        // Loading Style Section
        $this->start_controls_section(
            'section_loading_style',
            [
                'label' => __('Loading Style', 'magical-products-display'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'spinner_type',
            [
                'label' => __('Spinner Type', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'dots',
                'options' => [
                    'dots' => __('Dots', 'magical-products-display'),
                    'circle' => __('Circle', 'magical-products-display'),
                    'bars' => __('Bars', 'magical-products-display'),
                ],
            ]
        );

        $this->add_control(
            'spinner_color',
            [
                'label' => __('Spinner Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .mpd-ajax-search__spinner' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .mpd-ajax-search__spinner::before' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .mpd-ajax-search__spinner::after' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'spinner_size',
            [
                'label' => __('Spinner Size', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 16,
                        'max' => 40,
                    ],
                ],
                'default' => [
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mpd-ajax-search__spinner' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Results Style Section
        $this->start_controls_section(
            'section_results_style',
            [
                'label' => __('Results Style', 'magical-products-display'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'results_bg_color',
            [
                'label' => __('Background Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .mpd-ajax-search__results' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'results_border',
                'label' => __('Border', 'magical-products-display'),
                'selector' => '{{WRAPPER}} .mpd-ajax-search__results',
            ]
        );

        $this->add_control(
            'results_border_radius',
            [
                'label' => __('Border Radius', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 8,
                    'right' => 8,
                    'bottom' => 8,
                    'left' => 8,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mpd-ajax-search__results' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'results_max_height',
            [
                'label' => __('Max Height', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh'],
                'range' => [
                    'px' => [
                        'min' => 200,
                        'max' => 600,
                    ],
                    'vh' => [
                        'min' => 20,
                        'max' => 80,
                    ],
                ],
                'default' => [
                    'size' => 400,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mpd-ajax-search__results' => 'max-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'results_shadow',
                'label' => __('Box Shadow', 'magical-products-display'),
                'selector' => '{{WRAPPER}} .mpd-ajax-search__results',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Register advanced controls.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function register_advanced_controls()
    {
        $this->start_controls_section(
            'section_advanced',
            [
                'label' => __('Advanced', 'magical-products-display'),
                'tab' => \Elementor\Controls_Manager::TAB_ADVANCED,
            ]
        );

        $this->add_control(
            'custom_css',
            [
                'label' => __('Custom CSS', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::CODE,
                'language' => 'css',
                'rows' => 20,
                'description' => __('Add your custom CSS code here.', 'magical-products-display'),
            ]
        );

        $this->end_controls_section();

        $this->link_pro_added();
    }

    /**
     * Render widget output.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $widget_id = $this->get_id();

        // Generate unique nonce for this widget instance
        $nonce = wp_create_nonce('mpd_ajax_search_' . $widget_id);

        $container_classes = [
            'mpd-ajax-search',
            'mpd-ajax-search--' . $settings['spinner_type'],
        ];

        if ($settings['show_search_icon'] === 'yes') {
            $container_classes[] = 'mpd-ajax-search--has-icon';
            $container_classes[] = 'mpd-ajax-search--icon-' . $settings['icon_position'];
        }

        if ($settings['show_clear_button'] === 'yes') {
            $container_classes[] = 'mpd-ajax-search--has-clear';
        }

        ?>
        <div class="<?php echo esc_attr(implode(' ', $container_classes)); ?>" 
             data-widget-id="<?php echo esc_attr($widget_id); ?>"
             data-min-chars="<?php echo esc_attr($settings['min_characters']); ?>"
             data-delay="<?php echo esc_attr($settings['search_delay']); ?>"
             data-limit="<?php echo esc_attr($settings['results_limit']); ?>"
             data-nonce="<?php echo esc_attr($nonce); ?>">
            
            <div class="mpd-ajax-search__container">
                <div class="mpd-ajax-search__input-wrapper">
                    <?php if ($settings['show_search_icon'] === 'yes' && $settings['icon_position'] === 'left') : ?>
                        <button type="button" class="mpd-ajax-search__button">
                            <svg class="mpd-ajax-search__icon" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
                            </svg>
                        </button>
                    <?php endif; ?>
                    
                    <input type="text" 
                           class="mpd-ajax-search__input" 
                           placeholder="<?php echo esc_attr($settings['placeholder_text']); ?>"
                           autocomplete="off">
                    
                    <?php if ($settings['show_search_icon'] === 'yes' && $settings['icon_position'] === 'right') : ?>
                        <button type="button" class="mpd-ajax-search__button mpd-ajax-search__button--right">
                            <svg class="mpd-ajax-search__icon" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
                            </svg>
                        </button>
                    <?php endif; ?>
                    
                    <?php if ($settings['show_clear_button'] === 'yes') : ?>
                        <button type="button" class="mpd-ajax-search__clear" style="display: none;">
                            <svg class="mpd-ajax-search__clear-icon" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                            </svg>
                        </button>
                    <?php endif; ?>
                    
                    <div class="mpd-ajax-search__spinner" style="display: none;">
                        <?php if ($settings['spinner_type'] === 'dots') : ?>
                            <span></span><span></span><span></span>
                        <?php elseif ($settings['spinner_type'] === 'circle') : ?>
                            <div class="mpd-ajax-search__spinner-circle"></div>
                        <?php else : ?>
                            <div class="mpd-ajax-search__spinner-bars">
                                <div></div><div></div><div></div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- YouTube Style Filter Toggle - positioned to the right of search bar -->
                <?php if (get_option('mgppro_is_active', 'no') === 'yes' && !empty($settings['enable_filters']) && $settings['enable_filters'] === 'yes' && $settings['filter_layout_style'] === 'youtube_style') : ?>
                    <button type="button" class="mpd-ajax-search__filter-toggle">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M10,18H14V16H10V18M3,6V8H21V6H3M6,13H18V11H6V13Z"/>
                        </svg>
                        <?php esc_html_e('Filters', 'magical-products-display'); ?>
                    </button>
                <?php endif; ?>
                
                <div class="mpd-ajax-search__results" style="display: none;">
                    <!-- Results will be populated via AJAX -->
                </div>
            </div>
            
            <?php
            // Pro filters section
            if (get_option('mgppro_is_active', 'no') === 'yes' && !empty($settings['enable_filters']) && $settings['enable_filters'] === 'yes') {
                $this->render_pro_filters($settings);
            }
            ?>
        </div>
        <?php

        // Add custom CSS if provided
        if (!empty($settings['custom_css'])) {
            echo '<style>' . wp_kses_post($settings['custom_css']) . '</style>';
        }
    }

    /**
     * Render Pro filters section.
     *
     * @param array $settings Widget settings
     * @since 1.0.0
     * @access protected
     */
    protected function render_pro_filters($settings)
    {
        if (empty($settings['available_filters'])) {
            return;
        }

        $filter_style = $settings['filter_layout_style'];
        ?>
        <div class="mpd-ajax-search__filters mpd-ajax-search__filters--<?php echo esc_attr($filter_style); ?>">
            <?php if ($filter_style === 'youtube_style') : ?>
                <!-- YouTube style: Filter toggle is already rendered above, this is just the chips container -->
                <div class="mpd-ajax-search__filter-chips" style="display: none;">
            <?php endif; ?>

            <?php foreach ($settings['available_filters'] as $filter) : ?>
                <?php $this->render_filter_control($filter, $filter_style); ?>
            <?php endforeach; ?>

            <?php if ($filter_style === 'youtube_style') : ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }

    /**
     * Render individual filter control.
     *
     * @param string $filter Filter type
     * @param string $style Filter style
     * @since 1.0.0
     * @access protected
     */
    protected function render_filter_control($filter, $style)
    {
        switch ($filter) {
            case 'categories':
                $this->render_categories_filter($style);
                break;
            case 'tags':
                $this->render_tags_filter($style);
                break;
            case 'price_range':
                $this->render_price_range_filter($style);
                break;
            case 'featured':
                $this->render_featured_filter($style);
                break;
            case 'stock_status':
                $this->render_stock_status_filter($style);
                break;
        }
    }

    /**
     * Render categories filter.
     */
    protected function render_categories_filter($style)
    {
        $categories = get_terms([
            'taxonomy' => 'product_cat',
            'hide_empty' => true,
        ]);

        if (empty($categories) || is_wp_error($categories)) {
            return;
        }

        ?>
        <div class="mpd-ajax-search__filter mpd-ajax-search__filter--categories">
            <select class="mpd-ajax-search__filter-select" data-filter="categories">
                <option value=""><?php esc_html_e('All Categories', 'magical-products-display'); ?></option>
                <?php foreach ($categories as $category) : ?>
                    <option value="<?php echo esc_attr($category->term_id); ?>">
                        <?php echo esc_html($category->name); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php
    }

    /**
     * Render tags filter.
     */
    protected function render_tags_filter($style)
    {
        $tags = get_terms([
            'taxonomy' => 'product_tag',
            'hide_empty' => true,
            'number' => 20,
        ]);

        if (empty($tags) || is_wp_error($tags)) {
            return;
        }

        ?>
        <div class="mpd-ajax-search__filter mpd-ajax-search__filter--tags">
            <select class="mpd-ajax-search__filter-select" data-filter="tags" multiple>
                <?php foreach ($tags as $tag) : ?>
                    <option value="<?php echo esc_attr($tag->term_id); ?>">
                        <?php echo esc_html($tag->name); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php
    }

    /**
     * Render price range filter.
     */
    protected function render_price_range_filter($style)
    {
        global $wpdb;
        
        // Get min and max prices
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
        $prices = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT MIN(meta_value+0) as min_price, MAX(meta_value+0) as max_price 
                FROM {$wpdb->postmeta} 
                WHERE meta_key = %s 
                AND meta_value != ''",
                '_price'
            )
        );

        if (!$prices) {
            return;
        }

        ?>
        <div class="mpd-ajax-search__filter mpd-ajax-search__filter--price-range">
            <div class="mpd-ajax-search__price-range">
                <input type="range" 
                       class="mpd-ajax-search__price-min" 
                       data-filter="price_min"
                       min="<?php echo esc_attr($prices->min_price); ?>" 
                       max="<?php echo esc_attr($prices->max_price); ?>" 
                       value="<?php echo esc_attr($prices->min_price); ?>">
                <input type="range" 
                       class="mpd-ajax-search__price-max" 
                       data-filter="price_max"
                       min="<?php echo esc_attr($prices->min_price); ?>" 
                       max="<?php echo esc_attr($prices->max_price); ?>" 
                       value="<?php echo esc_attr($prices->max_price); ?>">
                <div class="mpd-ajax-search__price-display">
                    <span class="mpd-ajax-search__price-min-display"><?php echo wp_kses_post(wc_price($prices->min_price)); ?></span>
                    <span> - </span>
                    <span class="mpd-ajax-search__price-max-display"><?php echo wp_kses_post(wc_price($prices->max_price)); ?></span>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Render featured filter.
     */
    protected function render_featured_filter($style)
    {
        ?>
        <div class="mpd-ajax-search__filter mpd-ajax-search__filter--featured">
            <label class="mpd-ajax-search__checkbox-label">
                <input type="checkbox" class="mpd-ajax-search__filter-checkbox" data-filter="featured">
                <span class="mpd-ajax-search__checkmark"></span>
                <?php esc_html_e('Featured Products Only', 'magical-products-display'); ?>
            </label>
        </div>
        <?php
    }

    /**
     * Render stock status filter.
     */
    protected function render_stock_status_filter($style)
    {
        ?>
        <div class="mpd-ajax-search__filter mpd-ajax-search__filter--stock">
            <select class="mpd-ajax-search__filter-select" data-filter="stock_status">
                <option value=""><?php esc_html_e('All Stock Status', 'magical-products-display'); ?></option>
                <option value="instock"><?php esc_html_e('In Stock', 'magical-products-display'); ?></option>
                <option value="outofstock"><?php esc_html_e('Out of Stock', 'magical-products-display'); ?></option>
                <option value="onbackorder"><?php esc_html_e('On Backorder', 'magical-products-display'); ?></option>
            </select>
        </div>
        <?php
    }
}
