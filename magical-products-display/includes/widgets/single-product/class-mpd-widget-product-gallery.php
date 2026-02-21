<?php
/**
 * Product Gallery Widget
 *
 * Displays the product image gallery on single product pages.
 *
 * @package Magical_Shop_Builder
 * @since   2.0.0
 */

namespace MPD\MagicalShopBuilder\Widgets\SingleProduct;

use MPD\MagicalShopBuilder\Widgets\Base\Widget_Base;
use MPD\MagicalShopBuilder\Admin\Product_Video_Metabox;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Product_Gallery
 *
 * @since 2.0.0
 */
class Product_Gallery extends Widget_Base {

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
		return 'mpd-product-gallery';
	}

	/**
	 * Get widget title.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'MPD Product Gallery', 'magical-products-display' );
	}

	/**
	 * Get widget icon.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-product-images';
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 2.0.0
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'product', 'gallery', 'image', 'images', 'thumbnail', 'woocommerce', 'single' );
	}

	/**
	 * Get script dependencies.
	 *
	 * @since 2.0.0
	 *
	 * @return array Script handles.
	 */
	public function get_script_depends() {
		return array( 'jquery', 'mgproducts-script' );
	}

	/**
	 * Get style dependencies.
	 *
	 * @since 2.0.0
	 *
	 * @return array Style handles.
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
				'label' => __( 'Gallery Settings', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'gallery_layout',
			array(
				'label'   => __( 'Layout', 'magical-products-display' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'default'    => __( 'Default (Thumbnails Below)', 'magical-products-display' ),
					'thumbnails-left' => __( 'Thumbnails Left', 'magical-products-display' ),
					'thumbnails-right' => __( 'Thumbnails Right', 'magical-products-display' ),
					'grid'       => __( 'Grid Gallery', 'magical-products-display' ),
				),
				'default' => 'default',
				'render_type' => 'template',
			)
		);

		$this->add_control(
			'sale_flash',
			array(
				'label'        => __( 'Show Sale Badge', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'magical-products-display' ),
				'label_off'    => __( 'Hide', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_responsive_control(
			'thumbnails_columns',
			array(
				'label'     => __( 'Thumbnails Columns', 'magical-products-display' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				),
				'default'   => '4',
				'condition' => array(
					'gallery_layout!' => 'grid',
				),
				'selectors' => array(
					'{{WRAPPER}} .mpd-thumbs-horizontal' => '--mpd-thumb-columns: {{VALUE}};',
					'{{WRAPPER}} .flex-control-thumbs' => '--mpd-thumb-columns: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'grid_columns',
			array(
				'label'     => __( 'Grid Columns', 'magical-products-display' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
				),
				'default'   => '2',
				'condition' => array(
					'gallery_layout' => 'grid',
				),
				'selectors' => array(
					'{{WRAPPER}} .mpd-gallery-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
				),
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
			$this->add_pro_notice( 'pro_features_notice', __( 'Zoom, Lightbox & Video Support', 'magical-products-display' ) );
		}
			$this->add_control(
				'enable_zoom',
				array(
					'label'        => __( 'Enable Zoom', 'magical-products-display' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'magical-products-display' ),
					'label_off'    => __( 'No', 'magical-products-display' ),
					'return_value' => 'yes',
					'default'      => 'yes',
				)
			);

			$this->add_control(
				'zoom_type',
				array(
					'label'     => __( 'Zoom Type', 'magical-products-display' ),
					'type'      => Controls_Manager::SELECT,
					'options'   => array(
						'inner'  => __( 'Inner Zoom', 'magical-products-display' ),
						'lens'   => __( 'Lens Zoom', 'magical-products-display' ),
						'window' => __( 'Window Zoom', 'magical-products-display' ),
					),
					'default'   => 'inner',
					'condition' => array(
						'enable_zoom' => 'yes',
					),
				)
			);

			$this->add_control(
				'enable_lightbox',
				array(
					'label'        => __( 'Enable Lightbox', 'magical-products-display' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'magical-products-display' ),
					'label_off'    => __( 'No', 'magical-products-display' ),
					'return_value' => 'yes',
					'default'      => 'yes',
				)
			);

			$this->add_control(
				'video_support',
				array(
					'label'        => __( 'Enable Video Support', 'magical-products-display' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'magical-products-display' ),
					'label_off'    => __( 'No', 'magical-products-display' ),
					'return_value' => 'yes',
					'default'      => '',
					'description'  => __( 'Supports YouTube/Vimeo URLs in product gallery.', 'magical-products-display' ),
				)
			);

			$this->add_control(
				'autoplay_slider',
				array(
					'label'        => __( 'Autoplay Slider', 'magical-products-display' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'magical-products-display' ),
					'label_off'    => __( 'No', 'magical-products-display' ),
					'return_value' => 'yes',
					'default'      => '',
				)
			);

			$this->add_control(
				'autoplay_speed',
				array(
					'label'     => __( 'Autoplay Speed (ms)', 'magical-products-display' ),
					'type'      => Controls_Manager::NUMBER,
					'default'   => 5000,
					'min'       => 1000,
					'max'       => 10000,
					'step'      => 500,
					'condition' => array(
						'autoplay_slider' => 'yes',
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
		// Main Image Style.
		$this->start_controls_section(
			'section_style_main_image',
			array(
				'label' => __( 'Main Image', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'main_image_border',
				'selector' => '{{WRAPPER}} .mpd-gallery-slide img, {{WRAPPER}} .mpd-gallery-grid-item img',
			)
		);

		$this->add_responsive_control(
			'main_image_border_radius',
			array(
				'label'      => __( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-gallery-slide img, {{WRAPPER}} .mpd-gallery-grid-item img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'main_image_box_shadow',
				'selector' => '{{WRAPPER}} .mpd-gallery-slide img, {{WRAPPER}} .mpd-gallery-grid-item img',
			)
		);

		$this->end_controls_section();

		// Thumbnails Style.
		$this->start_controls_section(
			'section_style_thumbnails',
			array(
				'label'     => __( 'Thumbnails', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'gallery_layout!' => 'grid',
				),
			)
		);

		$this->add_responsive_control(
			'thumbnails_gap',
			array(
				'label'      => __( 'Gap', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 30,
					),
				),
				'default'    => array(
					'size' => 10,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-gallery-thumbs' => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'thumbnail_border',
				'selector' => '{{WRAPPER}} .mpd-thumb-item',
			)
		);

		$this->add_responsive_control(
			'thumbnail_border_radius',
			array(
				'label'      => __( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-thumb-item, {{WRAPPER}} .mpd-thumb-item img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'thumbnail_opacity',
			array(
				'label'     => __( 'Opacity', 'magical-products-display' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1,
						'step' => 0.1,
					),
				),
				'default'   => array(
					'size' => 0.7,
				),
				'selectors' => array(
					'{{WRAPPER}} .mpd-thumb-item img' => 'opacity: {{SIZE}};',
				),
			)
		);

		$this->add_control(
			'thumbnail_active_opacity',
			array(
				'label'     => __( 'Active Opacity', 'magical-products-display' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1,
						'step' => 0.1,
					),
				),
				'default'   => array(
					'size' => 1,
				),
				'selectors' => array(
					'{{WRAPPER}} .mpd-thumb-item.mpd-thumb-active img, {{WRAPPER}} .mpd-thumb-item:hover img' => 'opacity: {{SIZE}};',
				),
			)
		);

		$this->end_controls_section();

		// Sale Badge Style.
		$this->start_controls_section(
			'section_style_sale_badge',
			array(
				'label'     => __( 'Sale Badge', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'sale_flash' => 'yes',
				),
			)
		);

		$this->add_control(
			'sale_badge_bg_color',
			array(
				'label'     => __( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .onsale' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'sale_badge_text_color',
			array(
				'label'     => __( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .onsale' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'sale_badge_size',
			array(
				'label'      => __( 'Size', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 30,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .onsale' => 'min-width: {{SIZE}}{{UNIT}}; min-height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'sale_badge_border_radius',
			array(
				'label'      => __( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .onsale' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
		global $product, $post;

		$product = $this->get_current_product();

		if ( ! $product ) {
			$this->render_editor_placeholder(
				__( 'Product Gallery', 'magical-products-display' ),
				__( 'This widget displays the product gallery. Please use it on a single product page or inside a product loop.', 'magical-products-display' )
			);
			return;
		}

		// Setup the global post for WooCommerce templates.
		$post = get_post( $product->get_id() );
		setup_postdata( $post );

		$layout = $settings['gallery_layout'] ?? 'default';

		$this->add_render_attribute( 'wrapper', 'class', 'mpd-product-gallery' );
		$this->add_render_attribute( 'wrapper', 'class', 'mpd-gallery-layout-' . $layout );
		$this->add_render_attribute( 'wrapper', 'data-layout', $layout );

		// Add data attributes for JS.
		if ( $this->is_pro() ) {
			$this->add_render_attribute( 'wrapper', 'data-enable-zoom', $settings['enable_zoom'] ?? 'yes' );
			$this->add_render_attribute( 'wrapper', 'data-zoom-type', $settings['zoom_type'] ?? 'inner' );
			$this->add_render_attribute( 'wrapper', 'data-enable-lightbox', $settings['enable_lightbox'] ?? 'yes' );
			$this->add_render_attribute( 'wrapper', 'data-video-support', $settings['video_support'] ?? '' );
			$this->add_render_attribute( 'wrapper', 'data-autoplay', $settings['autoplay_slider'] ?? '' );
			$this->add_render_attribute( 'wrapper', 'data-autoplay-speed', $settings['autoplay_speed'] ?? '5000' );
		}
		?>
		<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
			<?php
			if ( 'yes' !== $settings['sale_flash'] ) {
				remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );
			}

			if ( 'grid' === $layout ) {
				$this->render_grid_gallery( $product, $settings );
			} else {
				// Use our custom gallery for all layouts to have consistent control.
				$this->render_default_gallery( $product, $settings );
			}

			if ( 'yes' !== $settings['sale_flash'] ) {
				add_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );
			}
			?>
		</div>
		<?php
		wp_reset_postdata();
	}

	/**
	 * Render default gallery layout (used in editor mode).
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Product $product  The product object.
	 * @param array       $settings Widget settings.
	 * @return void
	 */
	private function render_default_gallery( $product, $settings ) {
		$attachment_ids = $product->get_gallery_image_ids();
		$main_image_id  = $product->get_image_id();

		// Get video data if video support is enabled.
		$video_url      = '';
		$video_position = 'first';
		$has_video      = false;

		if ( $this->is_pro() && 'yes' === ( $settings['video_support'] ?? '' ) ) {
			$video_url      = Product_Video_Metabox::get_product_video_url( $product->get_id() );
			$video_position = Product_Video_Metabox::get_product_video_position( $product->get_id() );
			$has_video      = ! empty( $video_url );
		}

		// Check if we have any images or video.
		$has_images = $main_image_id || ! empty( $attachment_ids );

		if ( ! $has_images && ! $has_video ) {
			echo '<div class="mpd-gallery-placeholder" style="background: #f5f5f5; padding: 40px; text-align: center; border: 1px dashed #ccc;">';
			echo wc_placeholder_img( 'woocommerce_single' );
			echo '<p style="margin-top: 15px; color: #666;">' . esc_html__( 'No product images available.', 'magical-products-display' ) . '</p>';
			echo '</div>';
			return;
		}

		$columns       = absint( $settings['thumbnails_columns'] ?? 4 );
		$layout        = $settings['gallery_layout'] ?? 'default';
		$all_images    = $main_image_id ? array_merge( array( $main_image_id ), $attachment_ids ) : $attachment_ids;
		$has_thumbs    = count( $all_images ) > 1 || $has_video;
		$is_vertical   = in_array( $layout, array( 'thumbnails-left', 'thumbnails-right' ), true );

		// Build gallery items array with video.
		$gallery_items = $this->build_gallery_items( $all_images, $video_url, $video_position, $has_video );
		?>
		<div class="mpd-gallery-inner" data-columns="<?php echo esc_attr( $columns ); ?>" data-has-video="<?php echo esc_attr( $has_video ? 'yes' : 'no' ); ?>">
			<?php
			// Vertical layout wrapper.
			if ( $is_vertical && $has_thumbs ) {
				echo '<div class="mpd-gallery-flex-container">';
				
				// Thumbnails for left layout (before main image).
				if ( 'thumbnails-left' === $layout ) {
					$this->render_thumbnails_with_video( $gallery_items, $columns, true );
				}
			}

			// Get first item URL for lightbox.
			$first_item_url = '';
			if ( ! empty( $gallery_items[0] ) ) {
				if ( 'video' === $gallery_items[0]['type'] ) {
					$first_item_url = Product_Video_Metabox::get_embed_url( $gallery_items[0]['url'] );
				} else {
					$first_full = wp_get_attachment_image_src( $gallery_items[0]['id'], 'full' );
					$first_item_url = $first_full[0] ?? '';
				}
			}
			?>
			<div class="mpd-gallery-main-image">
				<?php
				// Sale badge - inside main image container.
				if ( 'yes' === $settings['sale_flash'] && $product->is_on_sale() ) {
					echo '<span class="onsale">' . esc_html__( 'Sale!', 'magical-products-display' ) . '</span>';
				}

				// Lightbox trigger icon - inside main image container.
				echo '<a href="' . esc_url( $first_item_url ) . '" class="mpd-gallery-lightbox-trigger" data-lightbox="mpd-gallery" aria-label="' . esc_attr__( 'View full-screen image gallery', 'magical-products-display' ) . '"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line><line x1="11" y1="8" x2="11" y2="14"></line><line x1="8" y1="11" x2="14" y2="11"></line></svg></a>';
				?>
				<?php
				// Render all items (images and video) in main container for slider functionality.
				foreach ( $gallery_items as $index => $item ) {
					$active_class = 0 === $index ? ' mpd-slide-active' : '';

					if ( 'video' === $item['type'] ) {
						$this->render_video_slide( $item, $index, $active_class, $settings );
					} else {
						$this->render_image_slide( $item, $index, $active_class );
					}
				}
				?>
			</div>
			<?php
			// For vertical layouts, close flex and add right thumbnails.
			if ( $is_vertical && $has_thumbs ) {
				// Thumbnails for right layout (after main image).
				if ( 'thumbnails-right' === $layout ) {
					$this->render_thumbnails_with_video( $gallery_items, $columns, true );
				}
				echo '</div>'; // Close flex container.
			}

			// Default: Horizontal thumbnails below.
			if ( 'default' === $layout && $has_thumbs ) {
				$this->render_thumbnails_with_video( $gallery_items, $columns, false );
			}
			?>
		</div>
		<?php
	}

	/**
	 * Build gallery items array including video.
	 *
	 * @since 2.0.0
	 *
	 * @param array  $images         Array of image attachment IDs.
	 * @param string $video_url      Video URL.
	 * @param string $video_position Video position ('first' or 'last').
	 * @param bool   $has_video      Whether video is available.
	 * @return array Gallery items.
	 */
	private function build_gallery_items( $images, $video_url, $video_position, $has_video ) {
		$items = array();

		// Build image items.
		$image_items = array();
		foreach ( $images as $attachment_id ) {
			$image_items[] = array(
				'type' => 'image',
				'id'   => $attachment_id,
			);
		}

		// Add video based on position.
		if ( $has_video ) {
			$video_item = array(
				'type' => 'video',
				'url'  => $video_url,
			);

			if ( 'first' === $video_position ) {
				$items = array_merge( array( $video_item ), $image_items );
			} else {
				$items = array_merge( $image_items, array( $video_item ) );
			}
		} else {
			$items = $image_items;
		}

		return $items;
	}

	/**
	 * Render image slide.
	 *
	 * @since 2.0.0
	 *
	 * @param array  $item         Gallery item data.
	 * @param int    $index        Slide index.
	 * @param string $active_class Active class string.
	 * @return void
	 */
	private function render_image_slide( $item, $index, $active_class ) {
		$full_size_image = wp_get_attachment_image_src( $item['id'], 'full' );
		$image_title     = get_post_field( 'post_title', $item['id'] );
		?>
		<div class="mpd-gallery-slide<?php echo esc_attr( $active_class ); ?>" data-index="<?php echo esc_attr( $index ); ?>" data-type="image">
			<a href="<?php echo esc_url( $full_size_image[0] ?? '' ); ?>" data-lightbox="mpd-gallery">
				<?php echo wp_get_attachment_image( $item['id'], 'woocommerce_single', false, array( 'title' => $image_title ) ); ?>
			</a>
		</div>
		<?php
	}

	/**
	 * Render video slide.
	 *
	 * @since 2.0.0
	 *
	 * @param array  $item         Gallery item data.
	 * @param int    $index        Slide index.
	 * @param string $active_class Active class string.
	 * @param array  $settings     Widget settings.
	 * @return void
	 */
	private function render_video_slide( $item, $index, $active_class, $settings ) {
		$video_url     = $item['url'];
		$embed_url     = Product_Video_Metabox::get_embed_url( $video_url );
		$thumbnail_url = Product_Video_Metabox::get_video_thumbnail( $video_url, 'hqdefault' );
		$video_type    = Product_Video_Metabox::get_video_type( $video_url );
		$enable_lightbox = 'yes' === ( $settings['enable_lightbox'] ?? 'yes' );
		
		// Build the embed URL without autoplay for inline display.
		$embed_url_no_autoplay = str_replace( '?autoplay=1', '', $embed_url );
		$embed_url_no_autoplay = str_replace( '&autoplay=1', '', $embed_url_no_autoplay );
		?>
		<div class="mpd-gallery-slide mpd-gallery-video-slide<?php echo esc_attr( $active_class ); ?>" 
			 data-index="<?php echo esc_attr( $index ); ?>" 
			 data-type="video"
			 data-video-type="<?php echo esc_attr( $video_type ); ?>"
			 data-embed-url="<?php echo esc_url( $embed_url ); ?>"
			 data-embed-url-no-autoplay="<?php echo esc_url( $embed_url_no_autoplay ); ?>">
			
			<div class="mpd-video-thumbnail-wrapper mpd-video-trigger" 
				 data-action="<?php echo esc_attr( $enable_lightbox ? 'lightbox' : 'inline' ); ?>"
				 data-video-url="<?php echo esc_url( $embed_url ); ?>"
				 role="button"
				 tabindex="0"
				 aria-label="<?php esc_attr_e( 'Play video', 'magical-products-display' ); ?>">
				<?php if ( $thumbnail_url ) : ?>
					<img src="<?php echo esc_url( $thumbnail_url ); ?>" 
						 alt="<?php esc_attr_e( 'Video thumbnail', 'magical-products-display' ); ?>" 
						 class="mpd-video-thumbnail" />
				<?php else : ?>
					<div class="mpd-video-placeholder">
						<span class="mpd-video-icon"></span>
					</div>
				<?php endif; ?>
				
				<div class="mpd-video-play-button">
					<svg xmlns="http://www.w3.org/2000/svg" width="68" height="48" viewBox="0 0 68 48">
						<path class="mpd-video-play-bg" d="M66.52,7.74c-0.78-2.93-2.49-5.41-5.42-6.19C55.79,.13,34,0,34,0S12.21,.13,6.9,1.55 C3.97,2.33,2.27,4.81,1.48,7.74C0.06,13.05,0,24,0,24s0.06,10.95,1.48,16.26c0.78,2.93,2.49,5.41,5.42,6.19 C12.21,47.87,34,48,34,48s21.79-0.13,27.1-1.55c2.93-0.78,4.64-3.26,5.42-6.19C67.94,34.95,68,24,68,24S67.94,13.05,66.52,7.74z" fill="#f00"/>
						<path d="M 45,24 27,14 27,34" fill="#fff"/>
					</svg>
				</div>
			</div>
			
			<div class="mpd-video-embed-container">
				<iframe src="" 
						frameborder="0" 
						allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
						allowfullscreen></iframe>
			</div>
		</div>
		<?php
	}

	/**
	 * Render thumbnails (horizontal or vertical).
	 *
	 * @since 2.0.0
	 *
	 * @param array $images     Array of attachment IDs.
	 * @param int   $columns    Number of columns/rows.
	 * @param bool  $vertical   Whether to render vertically.
	 * @return void
	 */
	private function render_thumbnails( $images, $columns, $vertical = false ) {
		if ( count( $images ) <= 1 ) {
			return;
		}

		$class = $vertical ? 'mpd-gallery-thumbs mpd-thumbs-vertical' : 'mpd-gallery-thumbs mpd-thumbs-horizontal';
		$style_var = $vertical ? '--mpd-thumb-rows' : '--mpd-thumb-columns';
		?>
		<div class="<?php echo esc_attr( $class ); ?>" style="<?php echo esc_attr( $style_var ); ?>: <?php echo esc_attr( $columns ); ?>;">
			<?php foreach ( $images as $index => $attachment_id ) : ?>
				<div class="mpd-thumb-item<?php echo 0 === $index ? ' mpd-thumb-active' : ''; ?>" data-index="<?php echo esc_attr( $index ); ?>">
					<?php echo wp_get_attachment_image( $attachment_id, 'woocommerce_thumbnail' ); ?>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
	}

	/**
	 * Render thumbnails with video support (horizontal or vertical).
	 *
	 * @since 2.0.0
	 *
	 * @param array $items      Array of gallery items.
	 * @param int   $columns    Number of columns/rows.
	 * @param bool  $vertical   Whether to render vertically.
	 * @return void
	 */
	private function render_thumbnails_with_video( $items, $columns, $vertical = false ) {
		if ( count( $items ) <= 1 ) {
			return;
		}

		$class = $vertical ? 'mpd-gallery-thumbs mpd-thumbs-vertical' : 'mpd-gallery-thumbs mpd-thumbs-horizontal';
		$style_var = $vertical ? '--mpd-thumb-rows' : '--mpd-thumb-columns';
		?>
		<div class="<?php echo esc_attr( $class ); ?>" style="<?php echo esc_attr( $style_var ); ?>: <?php echo esc_attr( $columns ); ?>;">
			<?php foreach ( $items as $index => $item ) : ?>
				<?php if ( 'video' === $item['type'] ) : ?>
					<?php
					$thumbnail_url = Product_Video_Metabox::get_video_thumbnail( $item['url'], 'default' );
					?>
					<div class="mpd-thumb-item mpd-thumb-video<?php echo 0 === $index ? ' mpd-thumb-active' : ''; ?>" data-index="<?php echo esc_attr( $index ); ?>" data-type="video">
						<?php if ( $thumbnail_url ) : ?>
							<img src="<?php echo esc_url( $thumbnail_url ); ?>" alt="<?php esc_attr_e( 'Video', 'magical-products-display' ); ?>" />
						<?php endif; ?>
						<div class="mpd-thumb-video-icon">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
								<path d="M8 5v14l11-7z"/>
							</svg>
						</div>
					</div>
				<?php else : ?>
					<div class="mpd-thumb-item<?php echo 0 === $index ? ' mpd-thumb-active' : ''; ?>" data-index="<?php echo esc_attr( $index ); ?>" data-type="image">
						<?php echo wp_get_attachment_image( $item['id'], 'woocommerce_thumbnail' ); ?>
					</div>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
		<?php
	}

	/**
	 * Render grid gallery layout.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Product $product  The product object.
	 * @param array       $settings Widget settings.
	 * @return void
	 */
	private function render_grid_gallery( $product, $settings ) {
		$attachment_ids = $product->get_gallery_image_ids();
		$main_image_id  = $product->get_image_id();

		// Get video data if video support is enabled.
		$video_url      = '';
		$video_position = 'first';
		$has_video      = false;

		if ( $this->is_pro() && 'yes' === ( $settings['video_support'] ?? '' ) ) {
			$video_url      = Product_Video_Metabox::get_product_video_url( $product->get_id() );
			$video_position = Product_Video_Metabox::get_product_video_position( $product->get_id() );
			$has_video      = ! empty( $video_url );
		}

		// Add main image to the beginning.
		if ( $main_image_id ) {
			array_unshift( $attachment_ids, $main_image_id );
		}

		if ( empty( $attachment_ids ) && ! $has_video ) {
			echo wc_placeholder_img();
			return;
		}

		// Build gallery items with video.
		$gallery_items = $this->build_gallery_items( $attachment_ids, $video_url, $video_position, $has_video );
		$enable_lightbox = $this->is_pro() && 'yes' === ( $settings['enable_lightbox'] ?? 'yes' );
		?>
		<div class="mpd-gallery-grid" data-has-video="<?php echo esc_attr( $has_video ? 'yes' : 'no' ); ?>">
			<?php
			foreach ( $gallery_items as $index => $item ) :
				if ( 'video' === $item['type'] ) :
					$embed_url     = Product_Video_Metabox::get_embed_url( $item['url'] );
					$thumbnail_url = Product_Video_Metabox::get_video_thumbnail( $item['url'], 'hqdefault' );
					$video_type    = Product_Video_Metabox::get_video_type( $item['url'] );
					?>
					<div class="mpd-gallery-grid-item mpd-gallery-grid-video" data-type="video" data-video-type="<?php echo esc_attr( $video_type ); ?>">
						<?php if ( $enable_lightbox ) : ?>
							<a href="<?php echo esc_url( $embed_url ); ?>" 
							   data-lightbox="product-gallery" 
							   data-type="iframe"
							   aria-label="<?php esc_attr_e( 'Play video', 'magical-products-display' ); ?>">
						<?php endif; ?>
						
						<div class="mpd-video-thumbnail-wrapper">
							<?php if ( $thumbnail_url ) : ?>
								<img src="<?php echo esc_url( $thumbnail_url ); ?>" 
									 alt="<?php esc_attr_e( 'Video thumbnail', 'magical-products-display' ); ?>" 
									 class="mpd-video-thumbnail" />
							<?php endif; ?>
							
							<div class="mpd-video-play-button">
								<svg xmlns="http://www.w3.org/2000/svg" width="68" height="48" viewBox="0 0 68 48">
									<path class="mpd-video-play-bg" d="M66.52,7.74c-0.78-2.93-2.49-5.41-5.42-6.19C55.79,.13,34,0,34,0S12.21,.13,6.9,1.55 C3.97,2.33,2.27,4.81,1.48,7.74C0.06,13.05,0,24,0,24s0.06,10.95,1.48,16.26c0.78,2.93,2.49,5.41,5.42,6.19 C12.21,47.87,34,48,34,48s21.79-0.13,27.1-1.55c2.93-0.78,4.64-3.26,5.42-6.19C67.94,34.95,68,24,68,24S67.94,13.05,66.52,7.74z" fill="#f00"/>
									<path d="M 45,24 27,14 27,34" fill="#fff"/>
								</svg>
							</div>
						</div>
						
						<?php if ( $enable_lightbox ) : ?>
							</a>
						<?php endif; ?>
					</div>
					<?php
				else :
					$full_url = wp_get_attachment_image_url( $item['id'], 'full' );
					?>
					<div class="mpd-gallery-grid-item" data-type="image">
						<?php
						if ( $enable_lightbox ) {
							printf(
								'<a href="%s" data-lightbox="product-gallery">%s</a>',
								esc_url( $full_url ),
								wp_get_attachment_image( $item['id'], 'woocommerce_single' )
							);
						} else {
							echo wp_get_attachment_image( $item['id'], 'woocommerce_single' );
						}
						?>
					</div>
					<?php
				endif;
			endforeach;
			?>
		</div>
		<?php
	}
}
