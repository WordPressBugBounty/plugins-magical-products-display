/**
 * Sidebar component for admin dashboard
 *
 * @package Magical_Shop_Builder
 * @since   2.0.0
 */

import { __ } from '@wordpress/i18n';
import { NavLink } from 'react-router-dom';
import { Icon } from '@wordpress/components';
import { 
	home, 
	layout, 
	settings, 
	widget, 
	starFilled,
	key
} from '@wordpress/icons';

/**
 * Get admin data from localized script.
 *
 * @return {Object} Admin data.
 */
const getAdminData = () => {
	return window.mpdAdmin || {};
};

/**
 * Navigation item component.
 *
 * @param {Object}      props         Component props.
 * @param {string}      props.to      Navigation path.
 * @param {JSX.Element} props.icon    Icon component.
 * @param {string}      props.label   Navigation label.
 * @param {boolean}     props.end     Whether to match exact path.
 * @param {boolean}     props.isPro   Whether this is a pro feature.
 * @return {JSX.Element} Navigation item component.
 */
const NavItem = ( { to, icon, label, end = false, isPro = false } ) => {
	return (
		<NavLink
			to={ to }
			end={ end }
			className={ ( { isActive } ) =>
				`mpd-admin-sidebar__link${ isActive ? ' active' : '' }`
			}
		>
			<Icon icon={ icon } size={ 20 } />
			<span>{ label }</span>
			{ isPro && ! getAdminData().isPro && (
				<span className="mpd-admin-sidebar__pro-badge">
					{ __( 'Pro', 'magical-products-display' ) }
				</span>
			) }
		</NavLink>
	);
};

/**
 * Sidebar component.
 *
 * @return {JSX.Element} Sidebar component.
 */
const Sidebar = () => {
	const { isPro, proAdminUrl, strings } = getAdminData();

	return (
		<aside className="mpd-admin-sidebar">
			<nav className="mpd-admin-sidebar__nav">
				<NavItem
					to="/"
					icon={ home }
					label={ strings?.dashboard || __( 'Dashboard', 'magical-products-display' ) }
					end
				/>
				<NavItem
					to="/templates"
					icon={ layout }
					label={ strings?.templates || __( 'Templates', 'magical-products-display' ) }
				/>
				<NavItem
					to="/settings"
					icon={ settings }
					label={ strings?.settings || __( 'Settings', 'magical-products-display' ) }
				/>
				<NavItem
					to="/widgets"
					icon={ widget }
					label={ strings?.widgets || __( 'Widgets', 'magical-products-display' ) }
				/>
				<div className="mpd-admin-sidebar__divider"></div>
				{ isPro ? (
					<a
						href={ proAdminUrl }
						className="mpd-admin-sidebar__link"
					>
						<Icon icon={ key } size={ 20 } />
						<span>{ __( 'License', 'magical-products-display' ) }</span>
					</a>
				) : (
					<NavItem
						to="/pro"
						icon={ starFilled }
						label={ strings?.proFeatures || __( 'Upgrade to Pro', 'magical-products-display' ) }
					/>
				) }
			</nav>
			<div className="mpd-admin-sidebar__footer">
				<p className="mpd-admin-sidebar__credit">
					{ __( 'Made with ❤️ by', 'magical-products-display' ) }
					<a 
						href="https://wpthemespace.com" 
						target="_blank" 
						rel="noopener noreferrer"
					>
						WPThemeSpace
					</a>
				</p>
			</div>
		</aside>
	);
};

export default Sidebar;
