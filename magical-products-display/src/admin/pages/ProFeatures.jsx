/**
 * Upgrade to Pro page component
 *
 * Shows a comprehensive list of pro features with an upgrade CTA.
 * Only displayed when the pro plugin is not active.
 *
 * @package Magical_Shop_Builder
 * @since   2.0.0
 */

import { __ } from '@wordpress/i18n';
import { 
	Button, 
	Icon
} from '@wordpress/components';
import { check, external } from '@wordpress/icons';
import Card from '../components/Card';

/**
 * Get admin data from localized script.
 *
 * @return {Object} Admin data.
 */
const getAdminData = () => {
	return window.mpdAdmin || {};
};

/**
 * Pro features organized by category.
 */
const proFeatureCategories = [
	{
		category: __( 'Shop & Archive', 'magical-products-display' ),
		features: [
			{
				title: __( 'Advanced Product Filters', 'magical-products-display' ),
				description: __( 'Ajax-based price, color, size, attribute, and category filters with instant results.', 'magical-products-display' ),
			},
			{
				title: __( 'Infinite Scroll & Load More', 'magical-products-display' ),
				description: __( 'Load products dynamically as users scroll or click "Load More" button.', 'magical-products-display' ),
			},
			{
				title: __( 'Masonry Grid Layout', 'magical-products-display' ),
				description: __( 'Display products in a beautiful Pinterest-style masonry grid layout.', 'magical-products-display' ),
			},
			{
				title: __( 'Advanced Template Conditions', 'magical-products-display' ),
				description: __( 'Apply templates based on product type, category, tag, price, and more.', 'magical-products-display' ),
			},
		],
	},
	{
		category: __( 'Single Product', 'magical-products-display' ),
		features: [
			{
				title: __( 'Product Image Zoom & Gallery', 'magical-products-display' ),
				description: __( 'Advanced image gallery with zoom, lightbox, and thumbnail slider.', 'magical-products-display' ),
			},
			{
				title: __( 'Sticky Add to Cart', 'magical-products-display' ),
				description: __( 'Show a sticky add to cart bar when scrolling on product pages.', 'magical-products-display' ),
			},
			{
				title: __( 'Product Video', 'magical-products-display' ),
				description: __( 'Add YouTube, Vimeo, or self-hosted videos to product galleries.', 'magical-products-display' ),
			},
			{
				title: __( 'Size Chart', 'magical-products-display' ),
				description: __( 'Display product size charts in a popup or tab for better conversions.', 'magical-products-display' ),
			},
			{
				title: __( 'Product 360° View', 'magical-products-display' ),
				description: __( 'Let customers rotate products 360° for a better product experience.', 'magical-products-display' ),
			},
		],
	},
	{
		category: __( 'Cart & Checkout', 'magical-products-display' ),
		features: [
			{
				title: __( 'Side Cart / Slide-Out Cart', 'magical-products-display' ),
				description: __( 'Show a sliding cart panel for quick cart access without page reload.', 'magical-products-display' ),
			},
			{
				title: __( 'Multi-Step Checkout', 'magical-products-display' ),
				description: __( 'Split checkout into easy-to-follow steps for better conversion rates.', 'magical-products-display' ),
			},
			{
				title: __( 'Advanced Checkout Fields', 'magical-products-display' ),
				description: __( 'Add, edit, or remove checkout fields without any code.', 'magical-products-display' ),
			},
			{
				title: __( 'Express Checkout', 'magical-products-display' ),
				description: __( 'One-click checkout for returning customers to boost sales.', 'magical-products-display' ),
			},
			{
				title: __( 'Order Bump', 'magical-products-display' ),
				description: __( 'Add order bump offers on the checkout page to increase average order value.', 'magical-products-display' ),
			},
		],
	},
	{
		category: __( 'Customer Experience', 'magical-products-display' ),
		features: [
			{
				title: __( 'Quick View', 'magical-products-display' ),
				description: __( 'Let customers view product details in a popup without leaving the page.', 'magical-products-display' ),
			},
			{
				title: __( 'Wishlist', 'magical-products-display' ),
				description: __( 'Allow customers to save products to their wishlist for later.', 'magical-products-display' ),
			},
			{
				title: __( 'Product Compare', 'magical-products-display' ),
				description: __( 'Enable customers to compare multiple products side by side.', 'magical-products-display' ),
			},
			{
				title: __( 'Recently Viewed Products', 'magical-products-display' ),
				description: __( 'Show recently viewed products to help customers find items they browsed.', 'magical-products-display' ),
			},
			{
				title: __( 'Back in Stock Notification', 'magical-products-display' ),
				description: __( 'Let customers subscribe to notifications when out-of-stock products return.', 'magical-products-display' ),
			},
		],
	},
	{
		category: __( 'Marketing & Sales', 'magical-products-display' ),
		features: [
			{
				title: __( 'Sales Countdown Timer', 'magical-products-display' ),
				description: __( 'Add urgency with countdown timers for sale products and special offers.', 'magical-products-display' ),
			},
			{
				title: __( 'Free Shipping Progress Bar', 'magical-products-display' ),
				description: __( 'Show customers how much more they need to spend for free shipping.', 'magical-products-display' ),
			},
			{
				title: __( 'Trust Badges', 'magical-products-display' ),
				description: __( 'Display security and trust badges to increase customer confidence.', 'magical-products-display' ),
			},
			{
				title: __( 'Product Badges & Labels', 'magical-products-display' ),
				description: __( 'Add custom badges like "New", "Hot", "Bestseller" to product images.', 'magical-products-display' ),
			},
		],
	},
	{
		category: __( 'Performance & Development', 'magical-products-display' ),
		features: [
			{
				title: __( 'CSS & JS Minification', 'magical-products-display' ),
				description: __( 'Minify and optimize plugin CSS/JS output for faster page speeds.', 'magical-products-display' ),
			},
			{
				title: __( 'Deferred JavaScript Loading', 'magical-products-display' ),
				description: __( 'Defer non-critical JavaScript to improve Core Web Vitals scores.', 'magical-products-display' ),
			},
			{
				title: __( 'Template Caching', 'magical-products-display' ),
				description: __( 'Cache rendered templates for lightning-fast page loads.', 'magical-products-display' ),
			},
			{
				title: __( 'Priority Support', 'magical-products-display' ),
				description: __( 'Get faster response times and dedicated help from our support team.', 'magical-products-display' ),
			},
			{
				title: __( 'Regular Updates', 'magical-products-display' ),
				description: __( 'Receive new features, improvements, and compatibility updates regularly.', 'magical-products-display' ),
			},
		],
	},
];

/**
 * Upgrade to Pro page component.
 *
 * @return {JSX.Element} ProFeatures component.
 */
const ProFeatures = () => {
	const { proUrl } = getAdminData();

	return (
		<div className="mpd-admin-page mpd-pro-page">
			<div className="mpd-admin-page-header">
				<h1>{ __( 'Upgrade to Pro', 'magical-products-display' ) }</h1>
				<p>{ __( 'Unlock powerful features to build better WooCommerce stores and increase your sales.', 'magical-products-display' ) }</p>
			</div>

			{ /* Hero CTA Section */ }
			<Card className="mpd-upgrade-hero-card">
				<div className="mpd-upgrade-hero">
					<div className="mpd-upgrade-hero-content">
						<h2>{ __( 'Magical Shop Builder Pro', 'magical-products-display' ) }</h2>
						<p>{ __( 'Get access to 30+ premium widgets, advanced template conditions, performance optimizations, and priority support to take your WooCommerce store to the next level.', 'magical-products-display' ) }</p>
						<ul className="mpd-upgrade-highlights">
							<li>
								<Icon icon={ check } size={ 18 } />
								{ __( '30+ Premium Widgets', 'magical-products-display' ) }
							</li>
							<li>
								<Icon icon={ check } size={ 18 } />
								{ __( 'Advanced Template Conditions', 'magical-products-display' ) }
							</li>
							<li>
								<Icon icon={ check } size={ 18 } />
								{ __( 'Priority Support', 'magical-products-display' ) }
							</li>
							<li>
								<Icon icon={ check } size={ 18 } />
								{ __( '1 Year Updates & Support', 'magical-products-display' ) }
							</li>
							<li>
								<Icon icon={ check } size={ 18 } />
								{ __( '14-Day Money-Back Guarantee', 'magical-products-display' ) }
							</li>
						</ul>
					</div>
					<div className="mpd-upgrade-hero-cta">
						<div className="mpd-upgrade-price-box">
							<span className="mpd-price-label">{ __( 'Starting at', 'magical-products-display' ) }</span>
							<div className="mpd-price-amount">
								<span className="mpd-price">$39</span>
								<span className="mpd-price-period">/ { __( 'year', 'magical-products-display' ) }</span>
							</div>
							<Button
								variant="primary"
								href={ proUrl }
								target="_blank"
								className="mpd-upgrade-btn"
							>
								{ __( 'Upgrade Now', 'magical-products-display' ) }
								<Icon icon={ external } size={ 16 } />
							</Button>
							<span className="mpd-guarantee-text">
								{ __( '14-day money-back guarantee', 'magical-products-display' ) }
							</span>
						</div>
					</div>
				</div>
			</Card>

			{ /* Features by Category */ }
			{ proFeatureCategories.map( ( group, groupIndex ) => (
				<Card 
					key={ groupIndex }
					title={ group.category }
					className="mpd-features-card"
				>
					<div className="mpd-features-grid">
						{ group.features.map( ( feature, featureIndex ) => (
							<div key={ featureIndex } className="mpd-feature-item">
								<div className="mpd-feature-icon">
									<Icon icon={ check } size={ 20 } />
								</div>
								<div className="mpd-feature-content">
									<h4>{ feature.title }</h4>
									<p>{ feature.description }</p>
								</div>
							</div>
						) ) }
					</div>
				</Card>
			) ) }

			{ /* Bottom CTA */ }
			<Card className="mpd-upgrade-bottom-card">
				<div className="mpd-upgrade-bottom">
					<h3>{ __( 'Ready to Upgrade?', 'magical-products-display' ) }</h3>
					<p>{ __( 'Join thousands of store owners who use Magical Shop Builder Pro to grow their business.', 'magical-products-display' ) }</p>
					<Button
						variant="primary"
						href={ proUrl }
						target="_blank"
						className="mpd-upgrade-btn"
					>
						{ __( 'Get Pro Now', 'magical-products-display' ) }
						<Icon icon={ external } size={ 16 } />
					</Button>
				</div>
			</Card>
		</div>
	);
};

export default ProFeatures;
