<?php
/**
 * Product Attributes Widget
 *
 * Displays the product attributes on single product pages.
 *
 * @package Magical_Shop_Builder
 * @since   2.0.0
 */

namespace MPD\MagicalShopBuilder\Widgets\SingleProduct;

use MPD\MagicalShopBuilder\Widgets\Base\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Product_Attributes
 *
 * @since 2.0.0
 */
class Product_Attributes extends Widget_Base {

	/**
	 * Widget category.
	 *
	 * @var string
	 */
	protected $widget_category = self::CATEGORY_SINGLE_PRODUCT;

	/**
	 * Get widget name.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'mpd-product-attributes';
	}

	/**
	 * Get widget title.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'MPD Product Attributes', 'magical-products-display' );
	}

	/**
	 * Get widget icon.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-product-info';
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 2.0.0
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'product', 'attributes', 'specifications', 'info', 'woocommerce', 'single' );
	}

	/**
	 * Get style dependencies.
	 *
	 * @since 2.0.0
	 *
	 * @return array Style dependencies.
	 */
	public function get_style_depends() {
		return array( 'mpd-single-product' );
	}

	/**
	 * Register content controls.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	protected function register_content_controls() {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => __( 'Attributes', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'layout',
			array(
				'label'   => __( 'Layout', 'magical-products-display' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'table' => __( 'Table', 'magical-products-display' ),
					'list'  => __( 'List', 'magical-products-display' ),
				),
				'default' => 'table',
			)
		);

		$this->add_control(
			'heading',
			array(
				'label'   => __( 'Section Heading', 'magical-products-display' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
			)
		);

		$this->add_control(
			'show_weight',
			array(
				'label'        => __( 'Show Weight', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'magical-products-display' ),
				'label_off'    => __( 'Hide', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_dimensions',
			array(
				'label'        => __( 'Show Dimensions', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'magical-products-display' ),
				'label_off'    => __( 'Hide', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->end_controls_section();

		// Pro Features Section.
		$this->start_controls_section(
			'section_pro_features',
			array(
				'label' => $this->pro_label( __( 'Pro Features', 'magical-products-display' ) ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		if ( ! $this->is_pro() ) {
			$this->add_pro_notice( 'pro_features_notice', __( 'Custom Attributes & Advanced Display', 'magical-products-display' ) );
		}
			$this->add_control(
				'display_mode',
				array(
					'label'   => __( 'Display Mode', 'magical-products-display' ),
					'type'    => Controls_Manager::SELECT,
					'options' => array(
						'all'      => __( 'All Attributes', 'magical-products-display' ),
						'selected' => __( 'Selected Attributes', 'magical-products-display' ),
						'exclude'  => __( 'Exclude Attributes', 'magical-products-display' ),
					),
					'default' => 'all',
				)
			);

			$this->add_control(
				'selected_attributes',
				array(
					'label'       => __( 'Attributes to Show', 'magical-products-display' ),
					'type'        => Controls_Manager::TEXT,
					'description' => __( 'Enter attribute slugs separated by commas (e.g., pa_color,pa_size)', 'magical-products-display' ),
					'condition'   => array(
						'display_mode' => 'selected',
					),
				)
			);

			$this->add_control(
				'excluded_attributes',
				array(
					'label'       => __( 'Attributes to Hide', 'magical-products-display' ),
					'type'        => Controls_Manager::TEXT,
					'description' => __( 'Enter attribute slugs separated by commas (e.g., pa_color,pa_size)', 'magical-products-display' ),
					'condition'   => array(
						'display_mode' => 'exclude',
					),
				)
			);

			$this->add_control(
				'show_icons',
				array(
					'label'        => __( 'Show Icons', 'magical-products-display' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'magical-products-display' ),
					'label_off'    => __( 'No', 'magical-products-display' ),
					'return_value' => 'yes',
					'default'      => '',
					'description'  => __( 'Display icons next to attribute labels.', 'magical-products-display' ),
				)
			);

			$this->add_control(
				'default_icon',
				array(
					'label'     => __( 'Default Icon', 'magical-products-display' ),
					'type'      => Controls_Manager::ICONS,
					'default'   => array(
						'value'   => 'fas fa-tag',
						'library' => 'fa-solid',
					),
					'condition' => array(
						'show_icons' => 'yes',
					),
				)
			);

			$this->add_control(
				'collapsible',
				array(
					'label'        => __( 'Collapsible', 'magical-products-display' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'magical-products-display' ),
					'label_off'    => __( 'No', 'magical-products-display' ),
					'return_value' => 'yes',
					'default'      => '',
					'description'  => __( 'Make attributes collapsible/expandable.', 'magical-products-display' ),
				)
			);

			$this->add_control(
				'toggle_text',
				array(
					'label'       => __( 'Toggle Button Text', 'magical-products-display' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => __( 'Product Specifications', 'magical-products-display' ),
					'condition'   => array(
						'collapsible' => 'yes',
					),
				)
			);

			$this->add_control(
				'toggle_icon',
				array(
					'label'     => __( 'Toggle Icon', 'magical-products-display' ),
					'type'      => Controls_Manager::ICONS,
					'default'   => array(
						'value'   => 'fas fa-chevron-down',
						'library' => 'fa-solid',
					),
					'condition' => array(
						'collapsible' => 'yes',
					),
				)
			);

			$this->add_control(
				'collapsed_default',
				array(
					'label'        => __( 'Collapsed by Default', 'magical-products-display' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'magical-products-display' ),
					'label_off'    => __( 'No', 'magical-products-display' ),
					'return_value' => 'yes',
					'default'      => '',
					'condition'    => array(
						'collapsible' => 'yes',
					),
				)
			);

		$this->end_controls_section();
	}

	/**
	 * Register style controls.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	protected function register_style_controls() {
		// Heading Style.
		$this->start_controls_section(
			'section_style_heading',
			array(
				'label' => __( 'Section Heading', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'heading_color',
			array(
				'label'     => __( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-attributes-heading' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'heading_typography',
				'selector' => '{{WRAPPER}} .mpd-attributes-heading',
			)
		);

		$this->add_responsive_control(
			'heading_spacing',
			array(
				'label'      => __( 'Bottom Spacing', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-attributes-heading' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Table Style.
		$this->start_controls_section(
			'section_style_table',
			array(
				'label' => __( 'Table / List', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'table_border',
				'selector' => '{{WRAPPER}} .mpd-attributes-table, {{WRAPPER}} .mpd-attributes-list',
			)
		);

		$this->add_responsive_control(
			'table_border_radius',
			array(
				'label'      => __( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-attributes-table, {{WRAPPER}} .mpd-attributes-list' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Row Style.
		$this->start_controls_section(
			'section_style_row',
			array(
				'label' => __( 'Row', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'row_bg_color',
			array(
				'label'     => __( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-attributes-table tr, {{WRAPPER}} .mpd-attributes-list-item' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'row_alt_bg_color',
			array(
				'label'     => __( 'Alternate Background', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-attributes-table tr:nth-child(even), {{WRAPPER}} .mpd-attributes-list-item:nth-child(even)' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'row_border',
				'selector' => '{{WRAPPER}} .mpd-attributes-table tr, {{WRAPPER}} .mpd-attributes-list-item',
			)
		);

		$this->add_responsive_control(
			'row_padding',
			array(
				'label'      => __( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-attributes-table td, {{WRAPPER}} .mpd-attributes-table th, {{WRAPPER}} .mpd-attributes-list-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Label Style.
		$this->start_controls_section(
			'section_style_label',
			array(
				'label' => __( 'Attribute Label', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'label_color',
			array(
				'label'     => __( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-attribute-label, {{WRAPPER}} .mpd-attributes-table th' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'label_bg_color',
			array(
				'label'     => __( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-attribute-label, {{WRAPPER}} .mpd-attributes-table th' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'label_typography',
				'selector' => '{{WRAPPER}} .mpd-attribute-label, {{WRAPPER}} .mpd-attributes-table th',
			)
		);

		$this->add_responsive_control(
			'label_width',
			array(
				'label'      => __( 'Width', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 50,
						'max' => 300,
					),
					'%' => array(
						'min' => 10,
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-attributes-table th' => 'width: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'layout' => 'table',
				),
			)
		);

		$this->end_controls_section();

		// Value Style.
		$this->start_controls_section(
			'section_style_value',
			array(
				'label' => __( 'Attribute Value', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'value_color',
			array(
				'label'     => __( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-attribute-value, {{WRAPPER}} .mpd-attributes-table td' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'value_typography',
				'selector' => '{{WRAPPER}} .mpd-attribute-value, {{WRAPPER}} .mpd-attributes-table td',
			)
		);

		$this->end_controls_section();

		// Icon Style - Pro Only.
		if ( $this->is_pro() ) {
			$this->start_controls_section(
				'section_style_icon',
				array(
					'label'     => __( 'Attribute Icons', 'magical-products-display' ),
					'tab'       => Controls_Manager::TAB_STYLE,
					'condition' => array(
						'show_icons' => 'yes',
					),
				)
			);

			$this->add_control(
				'icon_color',
				array(
					'label'     => __( 'Icon Color', 'magical-products-display' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .mpd-attribute-icon' => 'color: {{VALUE}};',
						'{{WRAPPER}} .mpd-attribute-icon svg' => 'fill: {{VALUE}};',
					),
				)
			);

			$this->add_responsive_control(
				'icon_size',
				array(
					'label'      => __( 'Icon Size', 'magical-products-display' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px', 'em' ),
					'range'      => array(
						'px' => array(
							'min' => 10,
							'max' => 40,
						),
					),
					'selectors'  => array(
						'{{WRAPPER}} .mpd-attribute-icon' => 'font-size: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .mpd-attribute-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				'icon_spacing',
				array(
					'label'      => __( 'Icon Spacing', 'magical-products-display' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px' ),
					'range'      => array(
						'px' => array(
							'min' => 0,
							'max' => 20,
						),
					),
					'selectors'  => array(
						'{{WRAPPER}} .mpd-attribute-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->end_controls_section();

			// Toggle Button Style - Pro Only.
			$this->start_controls_section(
				'section_style_toggle',
				array(
					'label'     => __( 'Toggle Button', 'magical-products-display' ),
					'tab'       => Controls_Manager::TAB_STYLE,
					'condition' => array(
						'collapsible' => 'yes',
					),
				)
			);

			$this->add_control(
				'toggle_bg_color',
				array(
					'label'     => __( 'Background Color', 'magical-products-display' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .mpd-attributes-toggle' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'toggle_text_color',
				array(
					'label'     => __( 'Text Color', 'magical-products-display' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .mpd-attributes-toggle' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'toggle_icon_color',
				array(
					'label'     => __( 'Icon Color', 'magical-products-display' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .mpd-toggle-icon' => 'color: {{VALUE}};',
						'{{WRAPPER}} .mpd-toggle-icon svg' => 'fill: {{VALUE}};',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'toggle_typography',
					'selector' => '{{WRAPPER}} .mpd-attributes-toggle',
				)
			);

			$this->add_responsive_control(
				'toggle_padding',
				array(
					'label'      => __( 'Padding', 'magical-products-display' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', 'em' ),
					'selectors'  => array(
						'{{WRAPPER}} .mpd-attributes-toggle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'     => 'toggle_border',
					'selector' => '{{WRAPPER}} .mpd-attributes-toggle',
				)
			);

			$this->add_responsive_control(
				'toggle_border_radius',
				array(
					'label'      => __( 'Border Radius', 'magical-products-display' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} .mpd-attributes-toggle' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				'toggle_margin_bottom',
				array(
					'label'      => __( 'Bottom Spacing', 'magical-products-display' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px' ),
					'range'      => array(
						'px' => array(
							'min' => 0,
							'max' => 30,
						),
					),
					'selectors'  => array(
						'{{WRAPPER}} .mpd-attributes-toggle' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->end_controls_section();
		}
	}

	/**
	 * Render widget output.
	 *
	 * @since 2.0.0
	 *
	 * @param array $settings Widget settings.
	 * @return void
	 */
	protected function render_widget( $settings ) {
		$product = $this->get_current_product();

		if ( ! $product ) {
			$this->render_editor_placeholder(
				__( 'Product Attributes', 'magical-products-display' ),
				__( 'This widget displays product attributes. Please use it on a single product page or inside a product loop.', 'magical-products-display' )
			);
			return;
		}

		$attributes = $this->get_product_attributes( $product, $settings );

		if ( empty( $attributes ) ) {
			if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
				$this->render_editor_placeholder(
					__( 'Product Attributes', 'magical-products-display' ),
					__( 'No attributes found for this product. Add attributes in product settings.', 'magical-products-display' )
				);
			}
			return;
		}

		$this->add_render_attribute( 'wrapper', 'class', 'mpd-product-attributes' );
		$this->add_render_attribute( 'wrapper', 'class', 'mpd-attributes-layout-' . $settings['layout'] );

		// Pro: Collapsible.
		$is_collapsible = $this->is_pro() && 'yes' === ( $settings['collapsible'] ?? '' );
		$is_collapsed   = 'yes' === ( $settings['collapsed_default'] ?? '' );
		
		if ( $is_collapsible ) {
			$this->add_render_attribute( 'wrapper', 'class', 'mpd-attributes-collapsible' );
			$this->add_render_attribute( 'wrapper', 'data-collapsible', 'true' );
			if ( $is_collapsed ) {
				$this->add_render_attribute( 'wrapper', 'class', 'mpd-attributes-collapsed' );
			} else {
				$this->add_render_attribute( 'wrapper', 'class', 'mpd-attributes-expanded' );
			}
		}
		?>
		<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
			<?php if ( ! empty( $settings['heading'] ) && ! $is_collapsible ) : ?>
				<h3 class="mpd-attributes-heading"><?php echo esc_html( $settings['heading'] ); ?></h3>
			<?php endif; ?>

			<?php
			// Render collapsible toggle button.
			if ( $is_collapsible ) {
				$toggle_text = $settings['toggle_text'] ?? __( 'Product Specifications', 'magical-products-display' );
				$toggle_icon = $settings['toggle_icon'] ?? array( 'value' => 'fas fa-chevron-down', 'library' => 'fa-solid' );
				?>
				<button type="button" class="mpd-attributes-toggle" aria-expanded="<?php echo esc_attr( $is_collapsed ? 'false' : 'true' ); ?>">
					<span class="mpd-toggle-text"><?php echo esc_html( $toggle_text ); ?></span>
					<span class="mpd-toggle-icon">
						<?php \Elementor\Icons_Manager::render_icon( $toggle_icon, array( 'aria-hidden' => 'true' ) ); ?>
					</span>
				</button>
				<div class="mpd-attributes-content" <?php echo esc_attr( $is_collapsed ? 'style="display: none;"' : '' ); ?>>
				<?php
			}

			if ( 'table' === $settings['layout'] ) {
				$this->render_table_layout( $attributes, $settings );
			} else {
				$this->render_list_layout( $attributes, $settings );
			}

			// Close collapsible content.
			if ( $is_collapsible ) {
				echo '</div>'; // .mpd-attributes-content
			}
			?>
		</div>
		<?php
	}

	/**
	 * Get product attributes.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Product $product  The product object.
	 * @param array       $settings Widget settings.
	 * @return array Attributes array.
	 */
	private function get_product_attributes( $product, $settings ) {
		$attributes = array();

		// Add weight.
		if ( 'yes' === $settings['show_weight'] && $product->has_weight() ) {
			$attributes['weight'] = array(
				'label' => __( 'Weight', 'magical-products-display' ),
				'value' => wc_format_weight( $product->get_weight() ),
			);
		}

		// Add dimensions.
		if ( 'yes' === $settings['show_dimensions'] && $product->has_dimensions() ) {
			$attributes['dimensions'] = array(
				'label' => __( 'Dimensions', 'magical-products-display' ),
				'value' => wc_format_dimensions( $product->get_dimensions( false ) ),
			);
		}

		// Get product attributes.
		$product_attributes = $product->get_attributes();

		foreach ( $product_attributes as $attribute ) {
			if ( ! $attribute->get_visible() ) {
				continue;
			}

			$attr_name = $attribute->get_name();

			// Pro: Filter attributes.
			if ( $this->is_pro() ) {
				$display_mode = $settings['display_mode'] ?? 'all';

				if ( 'selected' === $display_mode ) {
					$selected = array_map( 'trim', explode( ',', $settings['selected_attributes'] ?? '' ) );
					if ( ! in_array( $attr_name, $selected, true ) ) {
						continue;
					}
				} elseif ( 'exclude' === $display_mode ) {
					$excluded = array_map( 'trim', explode( ',', $settings['excluded_attributes'] ?? '' ) );
					if ( in_array( $attr_name, $excluded, true ) ) {
						continue;
					}
				}
			}

			$values = array();

			if ( $attribute->is_taxonomy() ) {
				$attribute_taxonomy = $attribute->get_taxonomy_object();
				$attribute_values   = wc_get_product_terms( $product->get_id(), $attribute->get_name(), array( 'fields' => 'names' ) );
				$values             = apply_filters( 'woocommerce_attribute', wpautop( wptexturize( implode( ', ', $attribute_values ) ) ), $attribute, $attribute_values );
				$label              = $attribute_taxonomy->attribute_label;
			} else {
				$values = $attribute->get_options();
				$values = wpautop( wptexturize( implode( ', ', $values ) ) );
				$label  = $attribute->get_name();
			}

			$attributes[ sanitize_title( $attr_name ) ] = array(
				'label' => $label,
				'value' => $values,
			);
		}

		return $attributes;
	}

	/**
	 * Render table layout.
	 *
	 * @since 2.0.0
	 *
	 * @param array $attributes Attributes array.
	 * @param array $settings   Widget settings.
	 * @return void
	 */
	private function render_table_layout( $attributes, $settings ) {
		$show_icons   = $this->is_pro() && 'yes' === ( $settings['show_icons'] ?? '' );
		$default_icon = $settings['default_icon'] ?? array( 'value' => 'fas fa-tag', 'library' => 'fa-solid' );
		?>
		<table class="mpd-attributes-table">
			<tbody>
				<?php foreach ( $attributes as $key => $attribute ) : ?>
					<tr class="mpd-attribute-row mpd-attribute-<?php echo esc_attr( $key ); ?>">
						<th class="mpd-attribute-label">
							<?php if ( $show_icons ) : ?>
								<span class="mpd-attribute-icon">
									<?php $this->render_attribute_icon( $key, $default_icon ); ?>
								</span>
							<?php endif; ?>
							<?php echo esc_html( $attribute['label'] ); ?>
						</th>
						<td class="mpd-attribute-value">
							<?php echo wp_kses_post( $attribute['value'] ); ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<?php
	}

	/**
	 * Render list layout.
	 *
	 * @since 2.0.0
	 *
	 * @param array $attributes Attributes array.
	 * @param array $settings   Widget settings.
	 * @return void
	 */
	private function render_list_layout( $attributes, $settings ) {
		$show_icons   = $this->is_pro() && 'yes' === ( $settings['show_icons'] ?? '' );
		$default_icon = $settings['default_icon'] ?? array( 'value' => 'fas fa-tag', 'library' => 'fa-solid' );
		?>
		<ul class="mpd-attributes-list">
			<?php foreach ( $attributes as $key => $attribute ) : ?>
				<li class="mpd-attributes-list-item mpd-attribute-<?php echo esc_attr( $key ); ?>">
					<span class="mpd-attribute-label">
						<?php if ( $show_icons ) : ?>
							<span class="mpd-attribute-icon">
								<?php $this->render_attribute_icon( $key, $default_icon ); ?>
							</span>
						<?php endif; ?>
						<?php echo esc_html( $attribute['label'] ); ?>:
					</span>
					<span class="mpd-attribute-value">
						<?php echo wp_kses_post( $attribute['value'] ); ?>
					</span>
				</li>
			<?php endforeach; ?>
		</ul>
		<?php
	}

	/**
	 * Render attribute icon.
	 *
	 * @since 2.0.0
	 *
	 * @param string $attribute_key The attribute key/slug.
	 * @param array  $default_icon  The default icon.
	 * @return void
	 */
	private function render_attribute_icon( $attribute_key, $default_icon ) {
		// Map common attribute keys to appropriate icons.
		$icon_map = array(
			'weight'     => array( 'value' => 'fas fa-weight-hanging', 'library' => 'fa-solid' ),
			'dimensions' => array( 'value' => 'fas fa-ruler-combined', 'library' => 'fa-solid' ),
			'pa_color'   => array( 'value' => 'fas fa-palette', 'library' => 'fa-solid' ),
			'color'      => array( 'value' => 'fas fa-palette', 'library' => 'fa-solid' ),
			'pa_size'    => array( 'value' => 'fas fa-expand-arrows-alt', 'library' => 'fa-solid' ),
			'size'       => array( 'value' => 'fas fa-expand-arrows-alt', 'library' => 'fa-solid' ),
			'pa_brand'   => array( 'value' => 'fas fa-building', 'library' => 'fa-solid' ),
			'brand'      => array( 'value' => 'fas fa-building', 'library' => 'fa-solid' ),
			'pa_material'=> array( 'value' => 'fas fa-layer-group', 'library' => 'fa-solid' ),
			'material'   => array( 'value' => 'fas fa-layer-group', 'library' => 'fa-solid' ),
		);

		$icon = isset( $icon_map[ $attribute_key ] ) ? $icon_map[ $attribute_key ] : $default_icon;
		
		\Elementor\Icons_Manager::render_icon( $icon, array( 'aria-hidden' => 'true' ) );
	}
}
