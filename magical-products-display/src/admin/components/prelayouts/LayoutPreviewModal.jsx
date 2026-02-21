/**
 * Layout Preview Modal Component
 *
 * Full-screen modal for previewing a layout before selection.
 *
 * @package Magical_Shop_Builder
 * @since   2.1.0
 */

import { useState, useEffect } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { 
	Button, 
	Modal,
	Spinner,
	Icon
} from '@wordpress/components';
import { check, lock } from '@wordpress/icons';
import classnames from 'classnames';
import { useApi } from '../../hooks/useApi';

/**
 * Layout Preview Modal Component.
 *
 * @param {Object}   props          Component props.
 * @param {Object}   props.layout   Layout data to preview.
 * @param {Function} props.onClose  Callback when modal is closed.
 * @param {Function} props.onSelect Callback when layout is selected.
 * @param {boolean}  props.isPro    Whether the layout requires Pro.
 * @return {JSX.Element} LayoutPreviewModal component.
 */
const LayoutPreviewModal = ( { layout, onClose, onSelect, isPro } ) => {
	const { fetchApi, isLoading } = useApi();
	
	const [ previewData, setPreviewData ] = useState( null );
	const [ activeTab, setActiveTab ] = useState( 'preview' );

	useEffect( () => {
		if ( layout?.id && layout.id !== 'custom' ) {
			loadPreviewData();
		}
	}, [ layout ] );

	const loadPreviewData = async () => {
		const data = await fetchApi( `/prelayouts/${ layout.id }/preview` );
		if ( data?.preview ) {
			setPreviewData( data.preview );
		}
	};

	if ( ! layout ) {
		return null;
	}

	const tabs = [
		{ id: 'preview', label: __( 'Preview', 'magical-products-display' ) },
		{ id: 'widgets', label: __( 'Widgets Included', 'magical-products-display' ) },
		{ id: 'features', label: __( 'Features', 'magical-products-display' ) },
	];

	return (
		<Modal
			title={ layout.name }
			onRequestClose={ onClose }
			className="mpd-layout-preview-modal"
			isFullScreen
		>
			<div className="mpd-layout-preview-container">
				<div className="mpd-layout-preview-header">
					<div className="mpd-layout-preview-tabs">
						{ tabs.map( ( tab ) => (
							<Button
								key={ tab.id }
								variant={ activeTab === tab.id ? 'primary' : 'secondary' }
								onClick={ () => setActiveTab( tab.id ) }
								className="mpd-layout-preview-tab"
							>
								{ tab.label }
							</Button>
						) ) }
					</div>
					
					{ isPro && (
						<div className="mpd-layout-preview-pro-notice">
							<Icon icon={ lock } />
							<span>{ __( 'This layout requires Magical Shop Builder Pro', 'magical-products-display' ) }</span>
							<Button
								variant="primary"
								href={ window.mpdAdmin?.proUrl || '#' }
								target="_blank"
								className="mpd-layout-preview-upgrade-btn"
							>
								{ __( 'Upgrade to Pro', 'magical-products-display' ) }
							</Button>
						</div>
					) }
				</div>

				<div className="mpd-layout-preview-content">
					{ isLoading ? (
						<div className="mpd-layout-preview-loading">
							<Spinner />
							<p>{ __( 'Loading preview...', 'magical-products-display' ) }</p>
						</div>
					) : (
						<>
							{ activeTab === 'preview' && (
								<div className="mpd-layout-preview-screenshot">
									<div className="mpd-layout-preview-frame">
										{ previewData?.screenshot ? (
											<img
												src={ previewData.screenshot }
												alt={ layout.name }
											/>
										) : (
											<div className="mpd-layout-preview-placeholder">
												<img
													src={ layout.thumbnail }
													alt={ layout.name }
												/>
												<p>{ __( 'Full preview coming soon', 'magical-products-display' ) }</p>
											</div>
										) }
									</div>
									
									<div className="mpd-layout-preview-info">
										<h3>{ layout.name }</h3>
										<p>{ layout.description }</p>
										
										{ layout.category && (
											<span className="mpd-layout-preview-category">
												{ layout.category }
											</span>
										) }
									</div>
								</div>
							) }

							{ activeTab === 'widgets' && (
								<div className="mpd-layout-preview-widgets">
									<h3>{ __( 'Widgets Included in This Layout', 'magical-products-display' ) }</h3>
									<p className="mpd-layout-preview-widgets-intro">
										{ __( 'The following widgets will be automatically added to your template. Each widget is fully customizable.', 'magical-products-display' ) }
									</p>
									
									<div className="mpd-layout-preview-widgets-grid">
										{ ( previewData?.widgets || layout.widgets || [] ).map( ( widget, index ) => (
											<div key={ index } className="mpd-layout-widget-item">
												<Icon icon={ check } />
												<span>{ typeof widget === 'object' ? widget.label : widget }</span>
											</div>
										) ) }
									</div>

									<div className="mpd-layout-preview-widgets-note">
										<strong>{ __( 'Note:', 'magical-products-display' ) }</strong>
										<span>
											{ __( 'No demo data will be imported. All widgets will use your actual product data.', 'magical-products-display' ) }
										</span>
									</div>
								</div>
							) }

							{ activeTab === 'features' && (
								<div className="mpd-layout-preview-features">
									<h3>{ __( 'Layout Features', 'magical-products-display' ) }</h3>
									
									<div className="mpd-layout-features-list">
										<div className="mpd-layout-feature-item">
											<Icon icon={ check } />
											<div>
												<strong>{ __( 'No Demo Data', 'magical-products-display' ) }</strong>
												<p>{ __( 'Layout imports only structure, using your real products.', 'magical-products-display' ) }</p>
											</div>
										</div>
										
										<div className="mpd-layout-feature-item">
											<Icon icon={ check } />
											<div>
												<strong>{ __( 'Fully Customizable', 'magical-products-display' ) }</strong>
												<p>{ __( 'Every widget can be customized in Elementor editor.', 'magical-products-display' ) }</p>
											</div>
										</div>
										
										<div className="mpd-layout-feature-item">
											<Icon icon={ check } />
											<div>
												<strong>{ __( 'Responsive Design', 'magical-products-display' ) }</strong>
												<p>{ __( 'Optimized for desktop, tablet, and mobile devices.', 'magical-products-display' ) }</p>
											</div>
										</div>
										
										<div className="mpd-layout-feature-item">
											<Icon icon={ check } />
											<div>
												<strong>{ __( 'Fast Import', 'magical-products-display' ) }</strong>
												<p>{ __( 'Instantly apply the layout without heavy data import.', 'magical-products-display' ) }</p>
											</div>
										</div>

										{ previewData?.features?.map( ( feature, index ) => (
											<div key={ index } className="mpd-layout-feature-item">
												<Icon icon={ check } />
												<div>
													<strong>{ feature }</strong>
												</div>
											</div>
										) ) }
									</div>
								</div>
							) }
						</>
					) }
				</div>

				<div className="mpd-layout-preview-footer">
					<Button variant="secondary" onClick={ onClose }>
						{ __( 'Close Preview', 'magical-products-display' ) }
					</Button>
					
					{ ! isPro ? (
						<Button variant="primary" onClick={ onSelect }>
							<Icon icon={ check } />
							{ __( 'Use This Layout', 'magical-products-display' ) }
						</Button>
					) : (
						<Button
							variant="primary"
							href={ window.mpdAdmin?.proUrl || '#' }
							target="_blank"
						>
							<Icon icon={ lock } />
							{ __( 'Unlock with Pro', 'magical-products-display' ) }
						</Button>
					) }
				</div>
			</div>
		</Modal>
	);
};

export default LayoutPreviewModal;
