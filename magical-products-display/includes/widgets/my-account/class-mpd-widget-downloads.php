<?php
/**
 * Downloads Widget
 *
 * Displays the WooCommerce customer downloads with progress and expiry.
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
 * Class Downloads
 *
 * @since 2.0.0
 */
class Downloads extends Widget_Base {

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
	protected $widget_icon = 'eicon-download-button';

	/**
	 * Get widget name.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'mpd-account-downloads';
	}

	/**
	 * Get widget title.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'MPD Downloads', 'magical-products-display' );
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 2.0.0
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'downloads', 'files', 'digital', 'my account', 'woocommerce', 'user' );
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
			'section_title',
			array(
				'label'       => esc_html__( 'Section Title', 'magical-products-display' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'My Downloads', 'magical-products-display' ),
				'placeholder' => esc_html__( 'Enter section title', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'show_title',
			array(
				'label'        => esc_html__( 'Show Section Title', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_product_name',
			array(
				'label'        => esc_html__( 'Show Product Name', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_downloads_remaining',
			array(
				'label'        => esc_html__( 'Show Downloads Remaining', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_expiry_date',
			array(
				'label'        => esc_html__( 'Show Expiry Date', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'download_button_text',
			array(
				'label'   => esc_html__( 'Download Button Text', 'magical-products-display' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Download', 'magical-products-display' ),
			)
		);

		$this->end_controls_section();

		// Progress Section (Pro).
		$this->start_controls_section(
			'section_progress',
			array(
				'label' => esc_html__( 'Progress', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'progress_pro_notice',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => $this->get_pro_notice( esc_html__( 'Download progress tracking is a Pro feature.', 'magical-products-display' ) ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			)
		);

		$this->add_control(
			'show_progress',
			array(
				'label'        => esc_html__( 'Show Download Progress', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'classes'      => $this->is_pro() ? '' : 'mpd-pro-disabled',
			)
		);

		$this->end_controls_section();

		// Empty State Section.
		$this->start_controls_section(
			'section_empty_state',
			array(
				'label' => esc_html__( 'Empty State', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'empty_message',
			array(
				'label'   => esc_html__( 'Empty Message', 'magical-products-display' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'No downloads available yet.', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'show_shop_button',
			array(
				'label'        => esc_html__( 'Show Shop Button', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'shop_button_text',
			array(
				'label'     => esc_html__( 'Shop Button Text', 'magical-products-display' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Browse Products', 'magical-products-display' ),
				'condition' => array(
					'show_shop_button' => 'yes',
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
		// Title Style Section.
		$this->start_controls_section(
			'section_title_style',
			array(
				'label'     => esc_html__( 'Title', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_title' => 'yes',
				),
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-downloads__title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .mpd-downloads__title',
			)
		);

		$this->add_responsive_control(
			'title_spacing',
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
					'{{WRAPPER}} .mpd-downloads__title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Table Style Section.
		$this->start_controls_section(
			'section_table_style',
			array(
				'label' => esc_html__( 'Table', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'table_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .mpd-downloads__table',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'table_border',
				'selector' => '{{WRAPPER}} .mpd-downloads__table',
			)
		);

		$this->add_responsive_control(
			'table_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-downloads__table' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'table_box_shadow',
				'selector' => '{{WRAPPER}} .mpd-downloads__table',
			)
		);

		$this->end_controls_section();

		// Table Header Style Section.
		$this->start_controls_section(
			'section_table_header_style',
			array(
				'label' => esc_html__( 'Table Header', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'header_color',
			array(
				'label'     => esc_html__( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-downloads__table thead th' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'header_typography',
				'selector' => '{{WRAPPER}} .mpd-downloads__table thead th',
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'header_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .mpd-downloads__table thead th',
			)
		);

		$this->add_responsive_control(
			'header_padding',
			array(
				'label'      => esc_html__( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-downloads__table thead th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Table Row Style Section.
		$this->start_controls_section(
			'section_table_row_style',
			array(
				'label' => esc_html__( 'Table Rows', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'row_color',
			array(
				'label'     => esc_html__( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-downloads__table tbody td' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'row_typography',
				'selector' => '{{WRAPPER}} .mpd-downloads__table tbody td',
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'row_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .mpd-downloads__table tbody tr',
			)
		);

		$this->add_control(
			'row_alternate_background',
			array(
				'label'     => esc_html__( 'Alternate Row Background', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-downloads__table tbody tr:nth-child(even)' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'row_padding',
			array(
				'label'      => esc_html__( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-downloads__table tbody td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'row_border',
				'selector' => '{{WRAPPER}} .mpd-downloads__table tbody td',
			)
		);

		$this->end_controls_section();

		// Download Button Style Section.
		$this->start_controls_section(
			'section_button_style',
			array(
				'label' => esc_html__( 'Download Button', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'button_tabs' );

		$this->start_controls_tab(
			'button_normal_tab',
			array(
				'label' => esc_html__( 'Normal', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'button_color',
			array(
				'label'     => esc_html__( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-downloads__button' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'button_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .mpd-downloads__button',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'button_hover_tab',
			array(
				'label' => esc_html__( 'Hover', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'button_hover_color',
			array(
				'label'     => esc_html__( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-downloads__button:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'button_hover_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .mpd-downloads__button:hover',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'button_typography',
				'selector'  => '{{WRAPPER}} .mpd-downloads__button',
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'button_border',
				'selector' => '{{WRAPPER}} .mpd-downloads__button',
			)
		);

		$this->add_responsive_control(
			'button_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-downloads__button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'button_padding',
			array(
				'label'      => esc_html__( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-downloads__button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Empty State Style Section.
		$this->start_controls_section(
			'section_empty_style',
			array(
				'label' => esc_html__( 'Empty State', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'empty_message_color',
			array(
				'label'     => esc_html__( 'Message Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-downloads__empty-message' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'empty_message_typography',
				'selector' => '{{WRAPPER}} .mpd-downloads__empty-message',
			)
		);

		$this->add_responsive_control(
			'empty_alignment',
			array(
				'label'     => esc_html__( 'Alignment', 'magical-products-display' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'magical-products-display' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'magical-products-display' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'magical-products-display' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'center',
				'selectors' => array(
					'{{WRAPPER}} .mpd-downloads__empty' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
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
					esc_html__( 'Downloads', 'magical-products-display' ),
					esc_html__( 'This widget displays downloads for logged-in users.', 'magical-products-display' )
				);
			}
			return;
		}

		// Only show on downloads endpoint (skip in editor for preview).
		if ( ! \Elementor\Plugin::$instance->editor->is_edit_mode() && ! is_wc_endpoint_url( 'downloads' ) ) {
			return;
		}

		$is_editor = \Elementor\Plugin::$instance->editor->is_edit_mode();

		// Get customer downloads or use sample data in editor.
		$downloads = array();
		if ( $is_editor ) {
			// Sample data for editor preview.
			$downloads = $this->get_sample_downloads();
		} elseif ( WC()->customer ) {
			$downloads = WC()->customer->get_downloadable_products();
		}
		?>
		<div class="mpd-downloads">
			<?php if ( 'yes' === $settings['show_title'] && ! empty( $settings['section_title'] ) ) : ?>
				<h3 class="mpd-downloads__title"><?php echo esc_html( $settings['section_title'] ); ?></h3>
			<?php endif; ?>

			<?php
			if ( empty( $downloads ) ) {
				$this->render_empty_state( $settings );
			} else {
				$this->render_downloads_table( $downloads, $settings );
			}
			?>
		</div>
		<?php
	}

	/**
	 * Get sample downloads for editor preview.
	 *
	 * @since 2.0.0
	 *
	 * @return array Sample downloads data.
	 */
	protected function get_sample_downloads() {
		return array(
			array(
				'download_name'       => 'Product Manual v2.0.pdf',
				'product_name'        => 'Digital Product Bundle',
				'product_url'         => '#',
				'download_url'        => '#',
				'downloads_remaining' => '5',
				'access_expires'      => wp_date( 'Y-m-d', strtotime( '+30 days' ) ),
			),
			array(
				'download_name'       => 'Software License Key.txt',
				'product_name'        => 'Premium Software',
				'product_url'         => '#',
				'download_url'        => '#',
				'downloads_remaining' => '',
				'access_expires'      => '',
			),
			array(
				'download_name'       => 'Course Materials.zip',
				'product_name'        => 'Online Course',
				'product_url'         => '#',
				'download_url'        => '#',
				'downloads_remaining' => '2',
				'access_expires'      => wp_date( 'Y-m-d', strtotime( '+7 days' ) ),
			),
		);
	}

	/**
	 * Render downloads table.
	 *
	 * @since 2.0.0
	 *
	 * @param array $downloads Downloads.
	 * @param array $settings  Widget settings.
	 * @return void
	 */
	protected function render_downloads_table( $downloads, $settings ) {
		?>
		<table class="mpd-downloads__table woocommerce-table shop_table">
			<thead>
				<tr>
					<?php if ( 'yes' === $settings['show_product_name'] ) : ?>
						<th><?php esc_html_e( 'Product', 'magical-products-display' ); ?></th>
					<?php endif; ?>
					<th><?php esc_html_e( 'File', 'magical-products-display' ); ?></th>
					<?php if ( 'yes' === $settings['show_downloads_remaining'] ) : ?>
						<th><?php esc_html_e( 'Remaining', 'magical-products-display' ); ?></th>
					<?php endif; ?>
					<?php if ( 'yes' === $settings['show_expiry_date'] ) : ?>
						<th><?php esc_html_e( 'Expires', 'magical-products-display' ); ?></th>
					<?php endif; ?>
					<th><?php esc_html_e( 'Action', 'magical-products-display' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ( $downloads as $download ) : ?>
					<tr>
						<?php if ( 'yes' === $settings['show_product_name'] ) : ?>
							<td>
								<?php if ( $download['product_url'] ) : ?>
									<a href="<?php echo esc_url( $download['product_url'] ); ?>">
										<?php echo esc_html( $download['product_name'] ); ?>
									</a>
								<?php else : ?>
									<?php echo esc_html( $download['product_name'] ); ?>
								<?php endif; ?>
							</td>
						<?php endif; ?>
						<td class="mpd-downloads__file-name">
							<?php echo esc_html( $download['download_name'] ); ?>
						</td>
						<?php if ( 'yes' === $settings['show_downloads_remaining'] ) : ?>
							<td class="mpd-downloads__remaining">
								<?php
								if ( is_numeric( $download['downloads_remaining'] ) ) {
									echo esc_html( $download['downloads_remaining'] );
								} else {
									echo '<span class="mpd-downloads__unlimited">' . esc_html__( 'Unlimited', 'magical-products-display' ) . '</span>';
								}
								?>
							</td>
						<?php endif; ?>
						<?php if ( 'yes' === $settings['show_expiry_date'] ) : ?>
							<td class="mpd-downloads__expiry">
								<?php
								if ( ! empty( $download['access_expires'] ) ) {
									$expiry_date = strtotime( $download['access_expires'] );
									$is_expired = $expiry_date < time();
									$class = $is_expired ? 'mpd-downloads__expired' : '';
									echo '<span class="' . esc_attr( $class ) . '">' . esc_html( date_i18n( get_option( 'date_format' ), $expiry_date ) ) . '</span>';
								} else {
									echo '<span class="mpd-downloads__never-expires">' . esc_html__( 'Never', 'magical-products-display' ) . '</span>';
								}
								?>
							</td>
						<?php endif; ?>
						<td class="mpd-downloads__action">
							<a href="<?php echo esc_url( $download['download_url'] ); ?>" class="button mpd-downloads__button">
								<?php echo esc_html( $settings['download_button_text'] ); ?>
							</a>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<?php
	}

	/**
	 * Render empty state.
	 *
	 * @since 2.0.0
	 *
	 * @param array $settings Widget settings.
	 * @return void
	 */
	protected function render_empty_state( $settings ) {
		?>
		<div class="mpd-downloads__empty">
			<div class="mpd-downloads__empty-icon">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
					<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
					<polyline points="7 10 12 15 17 10"/>
					<line x1="12" y1="15" x2="12" y2="3"/>
				</svg>
			</div>
			<p class="mpd-downloads__empty-message"><?php echo esc_html( $settings['empty_message'] ); ?></p>
			<?php if ( 'yes' === $settings['show_shop_button'] ) : ?>
				<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="button mpd-downloads__shop-button">
					<?php echo esc_html( $settings['shop_button_text'] ); ?>
				</a>
			<?php endif; ?>
		</div>
		<?php
	}
}
