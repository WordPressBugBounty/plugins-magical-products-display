/**
 * Main App component for Magical Shop Builder Admin
 *
 * @package Magical_Shop_Builder
 * @since   2.0.0
 */

import { useState, useEffect } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { HashRouter, Routes, Route, Navigate } from 'react-router-dom';
import { SnackbarList } from '@wordpress/components';
import { useDispatch, useSelect } from '@wordpress/data';
import { store as noticesStore } from '@wordpress/notices';

// Components
import Header from './components/Header';
import Sidebar from './components/Sidebar';

// Pages
import Dashboard from './pages/Dashboard';
import TemplatesWithLayouts from './pages/TemplatesWithLayouts';
import Settings from './pages/Settings';
import Widgets from './pages/Widgets';
import ProFeatures from './pages/ProFeatures';

/**
 * Get admin data from localized script.
 *
 * @return {Object} Admin data.
 */
const getAdminData = () => {
	return window.mpdAdmin || {};
};

/**
 * Main App component.
 *
 * @return {JSX.Element} App component.
 */
const App = () => {
	const [ isLoading, setIsLoading ] = useState( true );
	const { removeNotice } = useDispatch( noticesStore );
	const { isPro } = getAdminData();

	const notices = useSelect( ( select ) =>
		select( noticesStore ).getNotices()
	);

	// Simulate initial load
	useEffect( () => {
		const timer = setTimeout( () => {
			setIsLoading( false );
		}, 500 );

		return () => clearTimeout( timer );
	}, [] );

	if ( isLoading ) {
		return (
			<div className="mpd-admin-loading">
				<div className="mpd-admin-loading-spinner"></div>
				<p>{ __( 'Loading Magical Shop Builder...', 'magical-products-display' ) }</p>
			</div>
		);
	}

	return (
		<HashRouter>
			<div className="mpd-admin-app">
				<Header />
				<div className="mpd-admin-layout">
					<Sidebar />
					<main className="mpd-admin-content">
						<Routes>
							<Route path="/" element={ <Dashboard /> } />
							<Route path="/templates" element={ <TemplatesWithLayouts /> } />
							<Route path="/settings" element={ <Settings /> } />
							<Route path="/settings/:tab" element={ <Settings /> } />
							<Route path="/widgets" element={ <Widgets /> } />
							{ ! isPro && (
								<Route path="/pro" element={ <ProFeatures /> } />
							) }
							<Route path="*" element={ <Navigate to="/" replace /> } />
						</Routes>
					</main>
				</div>
				<div className="mpd-admin-notices">
					<SnackbarList
						notices={ notices }
						onRemove={ removeNotice }
					/>
				</div>
			</div>
		</HashRouter>
	);
};

export default App;
