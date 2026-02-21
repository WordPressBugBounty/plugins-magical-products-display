<?php
/**
 * MPD Action Buttons Widget
 *
 * Standalone Elementor widget for displaying Compare, Wishlist, and Quick View buttons.
 * Works on single product pages and within Elementor templates.
 *
 * @package Magical_Products_Display
 * @since   2.0.0
 */

namespace MPD\MagicalShopBuilder\Widgets\SingleProduct;

use MPD\MagicalShopBuilder\Widgets\Base\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * MPD Action Buttons Widget.
 *
 * @since 2.0.0
 */
class Action_Buttons extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @since 2.0.0
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'mpd-action-buttons';
	}

	/**
	 * Get widget title.
	 *
	 * @since 2.0.0
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'MPD Action Buttons', 'magical-products-display' );
	}

	/**
	 * Get widget icon.
	 *
	 * @since 2.0.0
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-button';
	}

	/**
	 * Get widget categories.
	 *
	 * @since 2.0.0
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'mpd-single-product' );
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 2.0.0
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'action', 'buttons', 'compare', 'wishlist', 'quick view', 'woocommerce', 'product' );
	}

	/**
	 * Get script dependencies.
	 *
	 * @since 2.0.0
	 * @return array Script dependencies.
	 */
	public function get_script_depends() {
		return array( 'mpd-global-widgets' );
	}

	/**
	 * Get style dependencies.
	 *
	 * @since 2.0.0
	 * @return array Style dependencies.
	 */
	public function get_style_depends() {
		return array( 'mpd-single-product', 'mpd-global-widgets' );
	}

	/**
	 * Register widget controls.
	 *
	 * @since 2.0.0
	 */
	protected function register_controls() {
		$this->register_content_controls();
		$this->register_style_controls();
	}

	/**
	 * Register content controls.
	 *
	 * @since 2.0.0
	 */
	protected function register_content_controls() {
		$this->start_controls_section(
			'section_buttons',
			array(
				'label' => $this->pro_label( esc_html__( 'Buttons', 'magical-products-display' ) ),
			)
		);

		if ( ! $this->is_pro() ) {
			$this->add_pro_notice( 'pro_features_notice', __( 'Compare & Wishlist Buttons', 'magical-products-display' ) );
		}

		$this->add_control(
			'show_compare',
			array(
				'label'        => esc_html__( 'Show Compare', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'compare_text',
			array(
				'label'     => esc_html__( 'Compare Text', 'magical-products-display' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Compare', 'magical-products-display' ),
				'condition' => array(
					'show_compare' => 'yes',
				),
			)
		);

		$this->add_control(
			'show_wishlist',
			array(
				'label'        => esc_html__( 'Show Wishlist', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'wishlist_text',
			array(
				'label'     => esc_html__( 'Wishlist Text', 'magical-products-display' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Add to Wishlist', 'magical-products-display' ),
				'condition' => array(
					'show_wishlist' => 'yes',
				),
			)
		);

		$this->add_control(
			'show_quick_view',
			array(
				'label'        => esc_html__( 'Show Quick View', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => '',
				'description'  => esc_html__( 'Note: Quick View is hidden on single product pages in frontend.', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'quick_view_text',
			array(
				'label'     => esc_html__( 'Quick View Text', 'magical-products-display' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Quick View', 'magical-products-display' ),
				'condition' => array(
					'show_quick_view' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		// Layout Section.
		$this->start_controls_section(
			'section_layout',
			array(
				'label' => esc_html__( 'Layout', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'button_style',
			array(
				'label'   => esc_html__( 'Button Style', 'magical-products-display' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'icon_text',
				'options' => array(
					'icon_only' => esc_html__( 'Icon Only', 'magical-products-display' ),
					'text_only' => esc_html__( 'Text Only', 'magical-products-display' ),
					'icon_text' => esc_html__( 'Icon & Text', 'magical-products-display' ),
				),
			)
		);

		$this->add_control(
			'button_layout',
			array(
				'label'   => esc_html__( 'Layout', 'magical-products-display' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'horizontal',
				'options' => array(
					'horizontal' => esc_html__( 'Horizontal', 'magical-products-display' ),
					'vertical'   => esc_html__( 'Vertical', 'magical-products-display' ),
				),
			)
		);

		$this->add_responsive_control(
			'button_alignment',
			array(
				'label'     => esc_html__( 'Alignment', 'magical-products-display' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'flex-start' => array(
						'title' => esc_html__( 'Left', 'magical-products-display' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'     => array(
						'title' => esc_html__( 'Center', 'magical-products-display' ),
						'icon'  => 'eicon-text-align-center',
					),
					'flex-end'   => array(
						'title' => esc_html__( 'Right', 'magical-products-display' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'flex-start',
				'selectors' => array(
					'{{WRAPPER}} .mpd-action-buttons-widget' => 'justify-content: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'button_gap',
			array(
				'label'      => esc_html__( 'Gap', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 10,
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-action-buttons-widget' => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register style controls.
	 *
	 * @since 2.0.0
	 */
	protected function register_style_controls() {
		$this->start_controls_section(
			'section_button_style',
			array(
				'label' => esc_html__( 'Buttons', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_typography',
				'selector' => '{{WRAPPER}} .mpd-action-btn',
			)
		);

		$this->add_responsive_control(
			'button_padding',
			array(
				'label'      => esc_html__( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-action-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'icon_size',
			array(
				'label'      => esc_html__( 'Icon Size', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 50,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 16,
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-action-btn i' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'button_tabs' );

		// Normal Tab.
		$this->start_controls_tab(
			'button_normal_tab',
			array(
				'label' => esc_html__( 'Normal', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'button_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-action-btn' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-action-btn' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'button_border',
				'selector' => '{{WRAPPER}} .mpd-action-btn',
			)
		);

		$this->end_controls_tab();

		// Hover Tab.
		$this->start_controls_tab(
			'button_hover_tab',
			array(
				'label' => esc_html__( 'Hover', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'button_text_color_hover',
			array(
				'label'     => esc_html__( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-action-btn:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_bg_color_hover',
			array(
				'label'     => esc_html__( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-action-btn:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_border_color_hover',
			array(
				'label'     => esc_html__( 'Border Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-action-btn:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		// Active Tab.
		$this->start_controls_tab(
			'button_active_tab',
			array(
				'label' => esc_html__( 'Active', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'button_text_color_active',
			array(
				'label'     => esc_html__( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-action-btn.mpd-active' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_bg_color_active',
			array(
				'label'     => esc_html__( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-action-btn.mpd-active' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'button_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'separator'  => 'before',
				'selectors'  => array(
					'{{WRAPPER}} .mpd-action-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .mpd-action-btn',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget output.
	 *
	 * @since 2.0.0
	 */
	protected function render_widget( $settings ) {
		// Pro-only widget: show upgrade message for free users.
		if ( ! $this->is_pro() ) {
			$this->render_pro_message( __( 'Action Buttons (Compare & Wishlist)', 'magical-products-display' ) );
			return;
		}

		global $product;

		$is_editor     = \Elementor\Plugin::$instance->editor->is_edit_mode();
		$is_preview    = \Elementor\Plugin::$instance->preview->is_preview_mode();

		// Try to get product from context.
		if ( ! $product ) {
			$product = wc_get_product( get_the_ID() );
		}

		// In editor mode without product, use demo data.
		$product_id   = 0;
		$product_name = '';

		if ( $product ) {
			$product_id   = $product->get_id();
			$product_name = $product->get_name();
		} elseif ( $is_editor ) {
			// Use demo data for editor preview.
			$product_id   = 999;
			$product_name = __( 'Demo Product', 'magical-products-display' );
		} else {
			// No product on frontend - don't show anything.
			return;
		}

		$show_compare    = 'yes' === $settings['show_compare'];
		$show_wishlist   = 'yes' === $settings['show_wishlist'];
		$show_quick_view = 'yes' === $settings['show_quick_view'];

		if ( ! $show_compare && ! $show_wishlist && ! $show_quick_view ) {
			if ( $is_editor ) {
				echo '<div class="mpd-editor-notice">';
				echo esc_html__( 'Enable at least one button (Compare, Wishlist, or Quick View) to display this widget.', 'magical-products-display' );
				echo '</div>';
			}
			return;
		}

		$button_style  = $settings['button_style'];
		$button_layout = $settings['button_layout'];

		$wrapper_classes = array(
			'mpd-action-buttons-widget',
			'mpd-action-btn-style--' . $button_style,
			'mpd-action-btn-layout--' . $button_layout,
		);

		// Add editor class to disable button clicks in editor.
		if ( $is_editor ) {
			$wrapper_classes[] = 'mpd-editor-mode';
		}
		?>
		<div class="<?php echo esc_attr( implode( ' ', $wrapper_classes ) ); ?>">
			<?php if ( $show_compare ) : ?>
				<button type="button" 
					class="mpd-action-btn mpd-compare-btn" 
					data-product-id="<?php echo esc_attr( $product_id ); ?>"
					data-product-name="<?php echo esc_attr( $product_name ); ?>"
					title="<?php echo esc_attr( $settings['compare_text'] ); ?>">
					<?php if ( 'text_only' !== $button_style ) : ?>
						<i class="eicon-exchange" aria-hidden="true"></i>
					<?php endif; ?>
					<?php if ( 'icon_only' !== $button_style ) : ?>
						<span><?php echo esc_html( $settings['compare_text'] ); ?></span>
					<?php endif; ?>
				</button>
			<?php endif; ?>

			<?php if ( $show_wishlist ) : ?>
				<button type="button" 
					class="mpd-action-btn mpd-wishlist-btn" 
					data-product-id="<?php echo esc_attr( $product_id ); ?>"
					data-product-name="<?php echo esc_attr( $product_name ); ?>"
					title="<?php echo esc_attr( $settings['wishlist_text'] ); ?>">
					<?php if ( 'text_only' !== $button_style ) : ?>
						<i class="eicon-heart-o" aria-hidden="true"></i>
					<?php endif; ?>
					<?php if ( 'icon_only' !== $button_style ) : ?>
						<span><?php echo esc_html( $settings['wishlist_text'] ); ?></span>
					<?php endif; ?>
				</button>
			<?php endif; ?>

			<?php if ( $show_quick_view ) : ?>
				<button type="button" 
					class="mpd-action-btn mpd-quick-view-btn" 
					data-product-id="<?php echo esc_attr( $product_id ); ?>"
					title="<?php echo esc_attr( $settings['quick_view_text'] ); ?>">
					<?php if ( 'text_only' !== $button_style ) : ?>
						<i class="eicon-zoom-in-bold" aria-hidden="true"></i>
					<?php endif; ?>
					<?php if ( 'icon_only' !== $button_style ) : ?>
						<span><?php echo esc_html( $settings['quick_view_text'] ); ?></span>
					<?php endif; ?>
				</button>
			<?php endif; ?>
		</div>
		<?php
	}
}
