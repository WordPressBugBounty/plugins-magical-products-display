<?php
/**
 * Account Dashboard Widget
 *
 * Displays the WooCommerce My Account dashboard with recent orders and stats.
 *
 * @package Magical_Shop_Builder
 * @since   2.0.0
 */

namespace MPD\MagicalShopBuilder\Widgets\MyAccount;

use MPD\MagicalShopBuilder\Widgets\Base\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Account_Dashboard
 *
 * @since 2.0.0
 */
class Account_Dashboard extends Widget_Base {

	/**
	 * Widget category.
	 *
	 * @var string
	 */
	protected $widget_category = self::CATEGORY_MY_ACCOUNT;

	/**
	 * Widget icon.
	 *
	 * @var string
	 */
	protected $widget_icon = 'eicon-dashboard';

	/**
	 * Get widget name.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'mpd-account-dashboard';
	}

	/**
	 * Get widget title.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'MPD Account Dashboard', 'magical-products-display' );
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 2.0.0
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'account', 'dashboard', 'my account', 'woocommerce', 'user', 'orders', 'stats' );
	}

	/**
	 * Get style dependencies.
	 *
	 * @since 2.0.0
	 *
	 * @return array Style dependencies.
	 */
	public function get_style_depends() {
		return array( 'mpd-my-account-widgets' );
	}

	/**
	 * Register content controls.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	protected function register_content_controls() {
		// Content Section.
		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Content', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'show_welcome_message',
			array(
				'label'        => esc_html__( 'Show Welcome Message', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'welcome_message',
			array(
				'label'       => esc_html__( 'Welcome Message', 'magical-products-display' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => esc_html__( 'Hello {customer_name}! From your account dashboard you can manage your orders and account details.', 'magical-products-display' ),
				'description' => esc_html__( 'Use {customer_name} to display customer name.', 'magical-products-display' ),
				'condition'   => array(
					'show_welcome_message' => 'yes',
				),
			)
		);

		$this->add_control(
			'show_quick_links',
			array(
				'label'        => esc_html__( 'Show Quick Links', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->end_controls_section();

		// Recent Orders Section.
		$this->start_controls_section(
			'section_recent_orders',
			array(
				'label' => esc_html__( 'Recent Orders', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'show_recent_orders',
			array(
				'label'        => esc_html__( 'Show Recent Orders', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'orders_count',
			array(
				'label'     => esc_html__( 'Number of Orders', 'magical-products-display' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 5,
				'min'       => 1,
				'max'       => 20,
				'condition' => array(
					'show_recent_orders' => 'yes',
				),
			)
		);

		$this->add_control(
			'recent_orders_title',
			array(
				'label'     => esc_html__( 'Section Title', 'magical-products-display' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Recent Orders', 'magical-products-display' ),
				'condition' => array(
					'show_recent_orders' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		// Account Stats Section (Pro).
		$this->start_controls_section(
			'section_stats',
			array(
				'label' => esc_html__( 'Account Stats', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'stats_pro_notice',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => $this->get_pro_notice( esc_html__( 'Account stats (total orders, total spent, etc.) is a Pro feature.', 'magical-products-display' ) ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			)
		);

		$this->add_control(
			'show_stats',
			array(
				'label'        => esc_html__( 'Show Account Stats', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'classes'      => $this->is_pro() ? '' : 'mpd-pro-disabled',
			)
		);

		$this->add_control(
			'show_total_orders',
			array(
				'label'        => esc_html__( 'Show Total Orders', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'classes'      => $this->is_pro() ? '' : 'mpd-pro-disabled',
				'condition'    => array(
					'show_stats' => 'yes',
				),
			)
		);

		$this->add_control(
			'show_total_spent',
			array(
				'label'        => esc_html__( 'Show Total Spent', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'classes'      => $this->is_pro() ? '' : 'mpd-pro-disabled',
				'condition'    => array(
					'show_stats' => 'yes',
				),
			)
		);

		$this->add_control(
			'show_pending_orders',
			array(
				'label'        => esc_html__( 'Show Pending Orders', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'classes'      => $this->is_pro() ? '' : 'mpd-pro-disabled',
				'condition'    => array(
					'show_stats' => 'yes',
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
		// Welcome Message Style Section.
		$this->start_controls_section(
			'section_welcome_style',
			array(
				'label'     => esc_html__( 'Welcome Message', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_welcome_message' => 'yes',
				),
			)
		);

		$this->add_control(
			'welcome_color',
			array(
				'label'     => esc_html__( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-account-dashboard__welcome' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'welcome_typography',
				'selector' => '{{WRAPPER}} .mpd-account-dashboard__welcome',
			)
		);

		$this->add_responsive_control(
			'welcome_spacing',
			array(
				'label'      => esc_html__( 'Spacing', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-account-dashboard__welcome' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Quick Links Style Section.
		$this->start_controls_section(
			'section_quick_links_style',
			array(
				'label'     => esc_html__( 'Quick Links', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_quick_links' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'links_columns',
			array(
				'label'          => esc_html__( 'Columns', 'magical-products-display' ),
				'type'           => Controls_Manager::SELECT,
				'options'        => array(
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
				),
				'default'        => '3',
				'tablet_default' => '2',
				'mobile_default' => '1',
				'selectors'      => array(
					'{{WRAPPER}} .mpd-account-dashboard__quick-links' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
				),
			)
		);

		$this->add_responsive_control(
			'links_gap',
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
				'selectors'  => array(
					'{{WRAPPER}} .mpd-account-dashboard__quick-links' => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'link_color',
			array(
				'label'     => esc_html__( 'Link Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-account-dashboard__quick-link' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'link_hover_color',
			array(
				'label'     => esc_html__( 'Link Hover Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-account-dashboard__quick-link:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'link_typography',
				'selector' => '{{WRAPPER}} .mpd-account-dashboard__quick-link',
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'link_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .mpd-account-dashboard__quick-link',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'link_border',
				'selector' => '{{WRAPPER}} .mpd-account-dashboard__quick-link',
			)
		);

		$this->add_responsive_control(
			'link_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-account-dashboard__quick-link' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'link_padding',
			array(
				'label'      => esc_html__( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-account-dashboard__quick-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'link_box_shadow',
				'selector' => '{{WRAPPER}} .mpd-account-dashboard__quick-link',
			)
		);

		$this->end_controls_section();

		// Recent Orders Style Section.
		$this->start_controls_section(
			'section_orders_style',
			array(
				'label'     => esc_html__( 'Recent Orders', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_recent_orders' => 'yes',
				),
			)
		);

		$this->add_control(
			'orders_title_color',
			array(
				'label'     => esc_html__( 'Title Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-account-dashboard__orders-title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'orders_title_typography',
				'selector' => '{{WRAPPER}} .mpd-account-dashboard__orders-title',
			)
		);

		$this->add_responsive_control(
			'orders_title_spacing',
			array(
				'label'      => esc_html__( 'Title Spacing', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-account-dashboard__orders-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'orders_table_border',
				'selector' => '{{WRAPPER}} .mpd-account-dashboard__orders-table',
			)
		);

		$this->end_controls_section();

		// Stats Style Section.
		$this->start_controls_section(
			'section_stats_style',
			array(
				'label'     => esc_html__( 'Account Stats', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_stats' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'stats_columns',
			array(
				'label'          => esc_html__( 'Columns', 'magical-products-display' ),
				'type'           => Controls_Manager::SELECT,
				'options'        => array(
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
				),
				'default'        => '3',
				'tablet_default' => '2',
				'mobile_default' => '1',
				'selectors'      => array(
					'{{WRAPPER}} .mpd-account-dashboard__stats' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
				),
			)
		);

		$this->add_responsive_control(
			'stats_gap',
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
				'selectors'  => array(
					'{{WRAPPER}} .mpd-account-dashboard__stats' => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'stat_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .mpd-account-dashboard__stat',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'stat_border',
				'selector' => '{{WRAPPER}} .mpd-account-dashboard__stat',
			)
		);

		$this->add_responsive_control(
			'stat_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-account-dashboard__stat' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'stat_padding',
			array(
				'label'      => esc_html__( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-account-dashboard__stat' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'stat_value_color',
			array(
				'label'     => esc_html__( 'Value Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-account-dashboard__stat-value' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'stat_value_typography',
				'selector' => '{{WRAPPER}} .mpd-account-dashboard__stat-value',
			)
		);

		$this->add_control(
			'stat_label_color',
			array(
				'label'     => esc_html__( 'Label Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-account-dashboard__stat-label' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'stat_label_typography',
				'selector' => '{{WRAPPER}} .mpd-account-dashboard__stat-label',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Get customer stats.
	 *
	 * @since 2.0.0
	 *
	 * @return array Customer stats.
	 */
	protected function get_customer_stats() {
		$customer_id = get_current_user_id();
		$stats       = array();

		// Get total orders.
		$total_orders = wc_get_customer_order_count( $customer_id );
		$stats['total_orders'] = array(
			'value' => $total_orders,
			'label' => esc_html__( 'Total Orders', 'magical-products-display' ),
			'icon'  => 'fas fa-shopping-bag',
		);

		// Get total spent.
		$total_spent = wc_get_customer_total_spent( $customer_id );
		$stats['total_spent'] = array(
			'value' => wc_price( $total_spent ),
			'label' => esc_html__( 'Total Spent', 'magical-products-display' ),
			'icon'  => 'fas fa-wallet',
		);

		// Get pending orders.
		$pending_orders = wc_get_orders(
			array(
				'customer_id' => $customer_id,
				'status'      => array( 'pending', 'on-hold', 'processing' ),
				'return'      => 'ids',
			)
		);
		$stats['pending_orders'] = array(
			'value' => count( $pending_orders ),
			'label' => esc_html__( 'Pending Orders', 'magical-products-display' ),
			'icon'  => 'fas fa-clock',
		);

		return $stats;
	}

	/**
	 * Check if we're on a specific My Account endpoint.
	 *
	 * Dashboard should only show when no specific endpoint is active.
	 *
	 * @since 2.0.0
	 *
	 * @return bool True if on a specific endpoint.
	 */
	protected function is_on_specific_endpoint() {
		$endpoints = array(
			'orders',
			'view-order',
			'downloads',
			'edit-address',
			'edit-account',
			'payment-methods',
			'add-payment-method',
			'lost-password',
			'customer-logout',
		);

		foreach ( $endpoints as $endpoint ) {
			if ( is_wc_endpoint_url( $endpoint ) ) {
				return true;
			}
		}

		return false;
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
		// Check if user is logged in.
		if ( ! is_user_logged_in() ) {
			// In editor mode, show placeholder.
			if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
				$this->render_editor_placeholder(
					esc_html__( 'Account Dashboard', 'magical-products-display' ),
					esc_html__( 'This widget displays My Account dashboard for logged-in users.', 'magical-products-display' )
				);
			}
			return;
		}

		// Only show on dashboard (no specific endpoint) - skip in editor for preview.
		if ( ! \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			// Check if we're on the my-account page but on a specific endpoint.
			if ( is_account_page() && $this->is_on_specific_endpoint() ) {
				return;
			}
		}

		$current_user = wp_get_current_user();
		?>
		<div class="mpd-account-dashboard">
			<?php
			// Welcome Message.
			if ( 'yes' === $settings['show_welcome_message'] ) {
				$welcome_message = str_replace(
					'{customer_name}',
					'<strong>' . esc_html( $current_user->display_name ) . '</strong>',
					$settings['welcome_message']
				);
				?>
				<div class="mpd-account-dashboard__welcome">
					<?php echo wp_kses_post( $welcome_message ); ?>
				</div>
				<?php
			}

			// Account Stats (Pro).
			if ( $this->is_pro() && 'yes' === $settings['show_stats'] ) {
				$stats = $this->get_customer_stats();
				?>
				<div class="mpd-account-dashboard__stats">
					<?php
					if ( 'yes' === $settings['show_total_orders'] ) {
						$this->render_stat( $stats['total_orders'] );
					}
					if ( 'yes' === $settings['show_total_spent'] ) {
						$this->render_stat( $stats['total_spent'] );
					}
					if ( 'yes' === $settings['show_pending_orders'] ) {
						$this->render_stat( $stats['pending_orders'] );
					}
					?>
				</div>
				<?php
			}

			// Quick Links.
			if ( 'yes' === $settings['show_quick_links'] ) {
				$menu_items = wc_get_account_menu_items();
				unset( $menu_items['dashboard'] );
				unset( $menu_items['customer-logout'] );
				?>
				<div class="mpd-account-dashboard__quick-links">
					<?php foreach ( $menu_items as $endpoint => $label ) : ?>
						<a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>" class="mpd-account-dashboard__quick-link">
							<span class="mpd-account-dashboard__quick-link-icon">
								<?php echo $this->get_endpoint_icon( $endpoint ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</span>
							<span class="mpd-account-dashboard__quick-link-text"><?php echo esc_html( $label ); ?></span>
						</a>
					<?php endforeach; ?>
				</div>
				<?php
			}

			// Recent Orders.
			if ( 'yes' === $settings['show_recent_orders'] ) {
				$this->render_recent_orders( $settings );
			}
			?>
		</div>
		<?php
	}

	/**
	 * Render stat item.
	 *
	 * @since 2.0.0
	 *
	 * @param array $stat Stat data.
	 * @return void
	 */
	protected function render_stat( $stat ) {
		?>
		<div class="mpd-account-dashboard__stat">
			<span class="mpd-account-dashboard__stat-icon">
				<i class="<?php echo esc_attr( $stat['icon'] ); ?>"></i>
			</span>
			<span class="mpd-account-dashboard__stat-value"><?php echo wp_kses_post( $stat['value'] ); ?></span>
			<span class="mpd-account-dashboard__stat-label"><?php echo esc_html( $stat['label'] ); ?></span>
		</div>
		<?php
	}

	/**
	 * Get endpoint icon.
	 *
	 * @since 2.0.0
	 *
	 * @param string $endpoint Endpoint.
	 * @return string Icon HTML.
	 */
	protected function get_endpoint_icon( $endpoint ) {
		$icons = array(
			'orders'       => 'fas fa-shopping-bag',
			'downloads'    => 'fas fa-download',
			'edit-address' => 'fas fa-map-marker-alt',
			'edit-account' => 'fas fa-user-cog',
		);

		$icon_class = isset( $icons[ $endpoint ] ) ? $icons[ $endpoint ] : 'fas fa-link';
		return '<i class="' . esc_attr( $icon_class ) . '"></i>';
	}

	/**
	 * Render recent orders.
	 *
	 * @since 2.0.0
	 *
	 * @param array $settings Widget settings.
	 * @return void
	 */
	protected function render_recent_orders( $settings ) {
		$customer_orders = wc_get_orders(
			array(
				'customer_id' => get_current_user_id(),
				'limit'       => absint( $settings['orders_count'] ),
				'orderby'     => 'date',
				'order'       => 'DESC',
			)
		);

		if ( ! empty( $settings['recent_orders_title'] ) ) {
			?>
			<h3 class="mpd-account-dashboard__orders-title"><?php echo esc_html( $settings['recent_orders_title'] ); ?></h3>
			<?php
		}

		if ( empty( $customer_orders ) ) {
			?>
			<p class="mpd-account-dashboard__no-orders"><?php esc_html_e( 'No orders yet.', 'magical-products-display' ); ?></p>
			<?php
			return;
		}
		?>
		<table class="mpd-account-dashboard__orders-table woocommerce-orders-table shop_table">
			<thead>
				<tr>
					<th><?php esc_html_e( 'Order', 'magical-products-display' ); ?></th>
					<th><?php esc_html_e( 'Date', 'magical-products-display' ); ?></th>
					<th><?php esc_html_e( 'Status', 'magical-products-display' ); ?></th>
					<th><?php esc_html_e( 'Total', 'magical-products-display' ); ?></th>
					<th><?php esc_html_e( 'Actions', 'magical-products-display' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ( $customer_orders as $order ) : ?>
					<tr>
						<td>
							<a href="<?php echo esc_url( $order->get_view_order_url() ); ?>">
								#<?php echo esc_html( $order->get_order_number() ); ?>
							</a>
						</td>
						<td><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></td>
						<td>
							<span class="mpd-order-status mpd-order-status--<?php echo esc_attr( $order->get_status() ); ?>">
								<?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?>
							</span>
						</td>
						<td><?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></td>
						<td>
							<a href="<?php echo esc_url( $order->get_view_order_url() ); ?>" class="button">
								<?php esc_html_e( 'View', 'magical-products-display' ); ?>
							</a>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<?php
	}
}
