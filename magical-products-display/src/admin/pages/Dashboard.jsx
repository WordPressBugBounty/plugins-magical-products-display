/**
 * Dashboard page component
 *
 * @package Magical_Shop_Builder
 * @since   2.0.0
 */

import { useState, useEffect } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { Button, Icon, Spinner } from '@wordpress/components';
import { 
	layout, 
	box, 
	chartBar, 
	external,
	plus
} from '@wordpress/icons';
import { Link } from 'react-router-dom';
import Card from '../components/Card';
import { useApi } from '../hooks/useApi';

/**
 * Get admin data from localized script.
 *
 * @return {Object} Admin data.
 */
const getAdminData = () => {
	return window.mpdAdmin || {};
};

/**
 * Stat Card component.
 *
 * @param {Object}      props       Component props.
 * @param {string}      props.title Stat title.
 * @param {string}      props.value Stat value.
 * @param {JSX.Element} props.icon  Stat icon.
 * @param {string}      props.href  Optional link.
 * @return {JSX.Element} StatCard component.
 */
const StatCard = ( { title, value, icon, href } ) => {
	const content = (
		<div className="mpd-stat-card">
			<div className="mpd-stat-card-icon">
				<Icon icon={ icon } size={ 24 } />
			</div>
			<div className="mpd-stat-card-content">
				<span className="mpd-stat-card-value">{ value }</span>
				<span className="mpd-stat-card-title">{ title }</span>
			</div>
		</div>
	);

	if ( href ) {
		return (
			<Link to={ href } className="mpd-stat-card-link">
				{ content }
			</Link>
		);
	}

	return content;
};

/**
 * Dashboard page component.
 *
 * @return {JSX.Element} Dashboard component.
 */
const Dashboard = () => {
	const { isPro, proUrl, docsUrl } = getAdminData();
	const { fetchApi, isLoading } = useApi();
	const [ stats, setStats ] = useState( null );

	useEffect( () => {
		loadStats();
	}, [] );

	const loadStats = async () => {
		const data = await fetchApi( '/stats' );
		if ( data ) {
			setStats( data );
		}
	};

	return (
		<div className="mpd-admin-page mpd-dashboard-page">
			<div className="mpd-admin-page-header">
				<h1>{ __( 'Dashboard', 'magical-products-display' ) }</h1>
				<p>{ __( 'Welcome to Magical Shop Builder! Build stunning WooCommerce stores with Elementor.', 'magical-products-display' ) }</p>
			</div>

			{ isLoading ? (
				<div className="mpd-loading">
					<Spinner />
				</div>
			) : stats ? (
				<div className="mpd-dashboard-stats">
					<StatCard
						title={ __( 'Templates', 'magical-products-display' ) }
						value={ stats.templates?.total || 0 }
						icon={ layout }
						href="/templates"
					/>
					<StatCard
						title={ __( 'Products', 'magical-products-display' ) }
						value={ stats.products?.total || 0 }
						icon={ box }
					/>
					<StatCard
						title={ __( 'Orders Today', 'magical-products-display' ) }
						value={ stats.woocommerce?.orders_today || 0 }
						icon={ chartBar }
					/>
				</div>
			) : null }

			<div className="mpd-dashboard-grid">
				<Card 
					title={ __( 'Quick Start', 'magical-products-display' ) }
					className="mpd-dashboard-quickstart"
				>
					<div className="mpd-quickstart-actions">
						<Link to="/templates">
							<Button variant="primary" icon={ plus }>
								{ __( 'Create Template', 'magical-products-display' ) }
							</Button>
						</Link>
						<Link to="/widgets">
							<Button variant="secondary">
								{ __( 'Manage Widgets', 'magical-products-display' ) }
							</Button>
						</Link>
						<Link to="/settings">
							<Button variant="secondary">
								{ __( 'Configure Settings', 'magical-products-display' ) }
							</Button>
						</Link>
					</div>
				</Card>

				<Card 
					title={ __( 'Template Types', 'magical-products-display' ) }
					className="mpd-dashboard-templates"
				>
					<ul className="mpd-template-types-list">
						<li>
							<strong>{ __( 'Single Product', 'magical-products-display' ) }</strong>
							<span>{ __( 'Customize individual product pages', 'magical-products-display' ) }</span>
						</li>
						<li>
							<strong>{ __( 'Shop/Archive', 'magical-products-display' ) }</strong>
							<span>{ __( 'Design shop and category pages', 'magical-products-display' ) }</span>
						</li>
						<li>
							<strong>{ __( 'Cart', 'magical-products-display' ) }</strong>
							<span>{ __( 'Build custom cart pages', 'magical-products-display' ) }</span>
						</li>
						<li>
							<strong>{ __( 'Checkout', 'magical-products-display' ) }</strong>
							<span>{ __( 'Create optimized checkout experiences', 'magical-products-display' ) }</span>
						</li>
						<li>
							<strong>{ __( 'My Account', 'magical-products-display' ) }</strong>
							<span>{ __( 'Customize customer account pages', 'magical-products-display' ) }</span>
						</li>
					</ul>
					<Link to="/templates">
						<Button variant="link">
							{ __( 'View All Templates', 'magical-products-display' ) }
							<Icon icon={ external } size={ 16 } />
						</Button>
					</Link>
				</Card>

				{ ! isPro && (
					<Card 
						title={ __( 'Upgrade to Pro', 'magical-products-display' ) }
						className="mpd-dashboard-pro"
					>
						<p>{ __( 'Unlock advanced features including:', 'magical-products-display' ) }</p>
						<ul className="mpd-pro-features-list">
							<li>{ __( 'Advanced template conditions', 'magical-products-display' ) }</li>
							<li>{ __( 'Sticky add to cart', 'magical-products-display' ) }</li>
							<li>{ __( 'Wishlist & Compare', 'magical-products-display' ) }</li>
							<li>{ __( 'Quick View popup', 'magical-products-display' ) }</li>
							<li>{ __( 'Priority support', 'magical-products-display' ) }</li>
						</ul>
						<Button
							variant="primary"
							href={ proUrl }
							target="_blank"
							className="mpd-upgrade-btn"
						>
							{ __( 'Upgrade Now', 'magical-products-display' ) }
							<Icon icon={ external } size={ 16 } />
						</Button>
					</Card>
				) }

				<Card 
					title={ __( 'Resources', 'magical-products-display' ) }
					className="mpd-dashboard-resources"
				>
					<ul className="mpd-resources-list">
						<li>
							<a href={ docsUrl } target="_blank" rel="noopener noreferrer">
								{ __( 'Documentation', 'magical-products-display' ) }
								<Icon icon={ external } size={ 16 } />
							</a>
						</li>
						<li>
							<a href="https://wordpress.org/support/plugin/magical-products-display/" target="_blank" rel="noopener noreferrer">
								{ __( 'Get Support', 'magical-products-display' ) }
								<Icon icon={ external } size={ 16 } />
							</a>
						</li>
						<li>
							<a href="https://wordpress.org/support/plugin/magical-products-display/reviews/#new-post" target="_blank" rel="noopener noreferrer">
								{ __( 'Leave a Review', 'magical-products-display' ) }
								<Icon icon={ external } size={ 16 } />
							</a>
						</li>
					</ul>
				</Card>
			</div>
		</div>
	);
};

export default Dashboard;
