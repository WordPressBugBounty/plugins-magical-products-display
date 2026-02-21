/**
 * Widgets page component
 *
 * @package Magical_Shop_Builder
 * @since   2.0.0
 */

import { useState, useEffect } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { 
	Button, 
	Spinner,
	SearchControl,
	ToggleControl
} from '@wordpress/components';
import { useDispatch } from '@wordpress/data';
import { store as noticesStore } from '@wordpress/notices';
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
 * Widget categories.
 */
const categories = {
	'product-display': __( 'Product Display', 'magical-products-display' ),
	'single-product': __( 'Single Product', 'magical-products-display' ),
	'cart': __( 'Cart', 'magical-products-display' ),
	'checkout': __( 'Checkout', 'magical-products-display' ),
	'my-account': __( 'My Account', 'magical-products-display' ),
	'shop-archive': __( 'Shop Archive', 'magical-products-display' ),
	'global': __( 'Global', 'magical-products-display' ),
};

/**
 * Widget item component.
 *
 * @param {Object}   props          Component props.
 * @param {Object}   props.widget   Widget data.
 * @param {Function} props.onToggle Toggle handler.
 * @return {JSX.Element} WidgetItem component.
 */
const WidgetItem = ( { widget, onToggle } ) => {
	const { isPro } = getAdminData();
	const isProLocked = widget.isPro && ! isPro;

	return (
		<div className={ `mpd-widget-item${ isProLocked ? ' is-pro-locked' : '' }` }>
			<div className="mpd-widget-item-info">
				<h4 className="mpd-widget-item-name">
					{ widget.name }
					{ widget.isPro && ! isPro && (
						<span className="mpd-pro-badge">Pro</span>
					) }
				</h4>
				<p className="mpd-widget-item-description">{ widget.description }</p>
			</div>
			<div className="mpd-widget-item-toggle">
				<ToggleControl
					checked={ widget.enabled }
					onChange={ () => onToggle( widget.id ) }
					disabled={ isProLocked }
				/>
			</div>
		</div>
	);
};

/**
 * Widgets page component.
 *
 * @return {JSX.Element} Widgets component.
 */
const Widgets = () => {
	const { fetchApi, postApi, isLoading } = useApi();
	const { createSuccessNotice, createErrorNotice } = useDispatch( noticesStore );
	const { isPro } = getAdminData();

	const [ widgets, setWidgets ] = useState( [] );
	const [ searchQuery, setSearchQuery ] = useState( '' );
	const [ isSaving, setIsSaving ] = useState( false );
	const [ hasChanges, setHasChanges ] = useState( false );

	useEffect( () => {
		loadWidgets();
	}, [] );

	const loadWidgets = async () => {
		const data = await fetchApi( '/widgets' );
		if ( data ) {
			setWidgets( data );
		}
	};

	const handleToggle = ( widgetId ) => {
		setWidgets( ( prev ) =>
			prev.map( ( widget ) =>
				widget.id === widgetId
					? { ...widget, enabled: ! widget.enabled }
					: widget
			)
		);
		setHasChanges( true );
	};

	const handleSave = async () => {
		setIsSaving( true );
		
		const result = await postApi( '/widgets', { widgets } );
		
		if ( result?.success ) {
			createSuccessNotice( __( 'Widget settings saved!', 'magical-products-display' ) );
			setHasChanges( false );
		} else {
			createErrorNotice( result?.message || __( 'Failed to save settings.', 'magical-products-display' ) );
		}
		
		setIsSaving( false );
	};

	const enableAll = () => {
		setWidgets( ( prev ) =>
			prev.map( ( widget ) => ( {
				...widget,
				enabled: widget.isPro && ! isPro ? widget.enabled : true,
			} ) )
		);
		setHasChanges( true );
	};

	const disableAll = () => {
		setWidgets( ( prev ) =>
			prev.map( ( widget ) => ( {
				...widget,
				enabled: false,
			} ) )
		);
		setHasChanges( true );
	};

	// Filter widgets by search query
	const filteredWidgets = widgets.filter( ( widget ) =>
		widget.name.toLowerCase().includes( searchQuery.toLowerCase() ) ||
		widget.description.toLowerCase().includes( searchQuery.toLowerCase() )
	);

	// Group widgets by category
	const groupedWidgets = Object.keys( categories ).reduce( ( acc, category ) => {
		const categoryWidgets = filteredWidgets.filter( 
			( widget ) => widget.category === category 
		);
		if ( categoryWidgets.length > 0 ) {
			acc[ category ] = categoryWidgets;
		}
		return acc;
	}, {} );

	if ( isLoading ) {
		return (
			<div className="mpd-admin-page mpd-widgets-page">
				<div className="mpd-admin-page-header">
					<h1>{ __( 'Widgets', 'magical-products-display' ) }</h1>
				</div>
				<div className="mpd-loading">
					<Spinner />
				</div>
			</div>
		);
	}

	return (
<div className={ `mpd-admin-page mpd-widgets-page${ hasChanges ? ' has-sticky-save-bar' : '' }` }>
			<div className="mpd-admin-page-header">
				<div>
					<h1>{ __( 'Widgets', 'magical-products-display' ) }</h1>
					<p>{ __( 'Enable or disable widgets to optimize performance.', 'magical-products-display' ) }</p>
				</div>
				<Button 
					variant="primary" 
					onClick={ handleSave }
					isBusy={ isSaving }
					disabled={ isSaving || ! hasChanges }
				>
					{ isSaving 
						? __( 'Saving...', 'magical-products-display' )
						: __( 'Save Changes', 'magical-products-display' )
					}
				</Button>
			</div>

			<div className="mpd-widgets-toolbar">
				<SearchControl
					value={ searchQuery }
					onChange={ setSearchQuery }
					placeholder={ __( 'Search widgets...', 'magical-products-display' ) }
				/>
				<div className="mpd-widgets-bulk-actions">
					<Button variant="secondary" onClick={ enableAll }>
						{ __( 'Enable All', 'magical-products-display' ) }
					</Button>
					<Button variant="secondary" onClick={ disableAll }>
						{ __( 'Disable All', 'magical-products-display' ) }
					</Button>
				</div>
			</div>

			<div className="mpd-widgets-summary">
				<span>
					{ __( 'Total:', 'magical-products-display' ) } { widgets.length }
				</span>
				<span>
					{ __( 'Enabled:', 'magical-products-display' ) } { widgets.filter( w => w.enabled ).length }
				</span>
				<span>
					{ __( 'Disabled:', 'magical-products-display' ) } { widgets.filter( w => ! w.enabled ).length }
				</span>
			</div>

			<div className="mpd-widgets-grid">
				{ Object.entries( groupedWidgets ).map( ( [ category, categoryWidgets ] ) => (
					<Card 
						key={ category }
						title={ categories[ category ] || category }
						className="mpd-widgets-category-card"
					>
						<div className="mpd-widgets-list">
							{ categoryWidgets.map( ( widget ) => (
								<WidgetItem
									key={ widget.id }
									widget={ widget }
									onToggle={ handleToggle }
								/>
							) ) }
						</div>
					</Card>
				) ) }

				{ Object.keys( groupedWidgets ).length === 0 && (
					<Card className="mpd-widgets-empty">
						<p>{ __( 'No widgets found matching your search.', 'magical-products-display' ) }</p>
					</Card>
				) }
			</div>

			{ hasChanges && (
				<div className="mpd-settings-save-bar">
					<div className="mpd-settings-save-bar-content">
						<span className="mpd-settings-save-bar-message">
							{ __( 'You have unsaved changes.', 'magical-products-display' ) }
						</span>
						<Button 
							variant="primary" 
							onClick={ handleSave }
							isBusy={ isSaving }
							disabled={ isSaving }
						>
							{ isSaving 
								? __( 'Saving...', 'magical-products-display' )
								: __( 'Save Changes', 'magical-products-display' )
							}
						</Button>
					</div>
				</div>
			) }
		</div>
	);
};

export default Widgets;
