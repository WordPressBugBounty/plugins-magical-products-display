/**
 * Admin entry point for Magical Shop Builder
 *
 * @package Magical_Shop_Builder
 * @since   2.0.0
 */

import { createRoot } from '@wordpress/element';
import { SlotFillProvider } from '@wordpress/components';
import domReady from '@wordpress/dom-ready';
import App from './App';
import './styles/admin.scss';

/**
 * Initialize the admin app when DOM is ready.
 */
domReady( () => {
	const container = document.getElementById( 'mpd-admin-root' );

	if ( container ) {
		const root = createRoot( container );
		root.render(
			<SlotFillProvider>
				<App />
			</SlotFillProvider>
		);
	}
} );
