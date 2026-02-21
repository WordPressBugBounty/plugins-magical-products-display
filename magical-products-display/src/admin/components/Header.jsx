/**
 * Header component for admin dashboard
 *
 * @package Magical_Shop_Builder
 * @since   2.0.0
 */

import { __ } from '@wordpress/i18n';
import { Button, Icon } from '@wordpress/components';
import { external, help } from '@wordpress/icons';

/**
 * Get admin data from localized script.
 *
 * @return {Object} Admin data.
 */
const getAdminData = () => {
	return window.mpdAdmin || {};
};

/**
 * Header component.
 *
 * @return {JSX.Element} Header component.
 */
const Header = () => {
	const { version, isPro, docsUrl, supportUrl, proUrl } = getAdminData();

	return (
		<header className="mpd-admin-header">
			<div className="mpd-admin-header__left">
				<a href="#/" className="mpd-admin-header__logo">
					<svg
						xmlns="http://www.w3.org/2000/svg"
						viewBox="0 0 24 24"
						width="28"
						height="28"
						fill="none"
						stroke="currentColor"
						strokeWidth="2"
						strokeLinecap="round"
						strokeLinejoin="round"
					>
						<path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z" />
						<line x1="3" y1="6" x2="21" y2="6" />
						<path d="M16 10a4 4 0 0 1-8 0" />
					</svg>
					{ __( 'Magical Shop Builder', 'magical-products-display' ) }
				</a>
				<span className="mpd-admin-header__version">
					v{ version }
				</span>
				{ isPro && (
					<span className="mpd-admin-header__pro-badge">
						{ __( 'Pro', 'magical-products-display' ) }
					</span>
				) }
			</div>
			<div className="mpd-admin-header__right">
				<a
					href={ docsUrl }
					target="_blank"
					rel="noopener noreferrer"
					className="mpd-admin-header__link"
				>
					<Icon icon={ help } size={ 16 } />
					{ __( 'Documentation', 'magical-products-display' ) }
				</a>
				<a
					href={ supportUrl }
					target="_blank"
					rel="noopener noreferrer"
					className="mpd-admin-header__link"
				>
					{ __( 'Support', 'magical-products-display' ) }
					<Icon icon={ external } size={ 16 } />
				</a>
				{ ! isPro && (
					<Button
						href={ proUrl }
						target="_blank"
						rel="noopener noreferrer"
						variant="primary"
						className="mpd-admin-header__upgrade"
					>
						{ __( 'Upgrade to Pro', 'magical-products-display' ) }
					</Button>
				) }
			</div>
		</header>
	);
};

export default Header;
