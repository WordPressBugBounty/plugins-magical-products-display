/**
 * Pre-Layout Selector Component
 *
 * Displays available pre-layouts for selection when creating a template.
 *
 * @package Magical_Shop_Builder
 * @since   2.1.0
 */

import { useState, useEffect, useCallback } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { 
	Button, 
	Spinner,
	SearchControl,
	SelectControl,
	Icon,
	Tooltip
} from '@wordpress/components';
import { check, lock, layout as layoutIcon, plus } from '@wordpress/icons';
import classnames from 'classnames';
import { useApi } from '../../hooks/useApi';
import LayoutPreviewModal from './LayoutPreviewModal';

/**
 * Layout categories for filtering.
 */
const layoutCategories = [
	{ value: 'all', label: __( 'All Categories', 'magical-products-display' ) },
	{ value: 'basic', label: __( 'Basic', 'magical-products-display' ) },
	{ value: 'modern', label: __( 'Modern', 'magical-products-display' ) },
	{ value: 'minimal', label: __( 'Minimal', 'magical-products-display' ) },
	{ value: 'creative', label: __( 'Creative', 'magical-products-display' ) },
	{ value: 'gallery', label: __( 'Gallery', 'magical-products-display' ) },
	{ value: 'detailed', label: __( 'Detailed', 'magical-products-display' ) },
	{ value: 'luxury', label: __( 'Luxury', 'magical-products-display' ) },
	{ value: 'tech', label: __( 'Tech', 'magical-products-display' ) },
	{ value: 'compact', label: __( 'Compact', 'magical-products-display' ) },
];

/**
 * PreLayout Selector Component.
 *
 * @param {Object}   props              Component props.
 * @param {string}   props.templateType Template type to filter layouts.
 * @param {Function} props.onSelect     Callback when layout is selected.
 * @param {Function} props.onCancel     Callback when selection is cancelled.
 * @param {string}   props.className    Additional CSS classes.
 * @return {JSX.Element} PreLayoutSelector component.
 */
const PreLayoutSelector = ( { templateType, onSelect, onCancel, className } ) => {
	const { fetchApi, isLoading } = useApi();
	
	const [ layouts, setLayouts ] = useState( [] );
	const [ filteredLayouts, setFilteredLayouts ] = useState( [] );
	const [ selectedLayout, setSelectedLayout ] = useState( null );
	const [ searchQuery, setSearchQuery ] = useState( '' );
	const [ categoryFilter, setCategoryFilter ] = useState( 'all' );
	const [ previewLayout, setPreviewLayout ] = useState( null );
	const [ isPreviewOpen, setIsPreviewOpen ] = useState( false );

	// Load layouts on mount.
	useEffect( () => {
		loadLayouts();
	}, [ templateType ] );

	// Filter layouts when search or category changes.
	useEffect( () => {
		filterLayouts();
	}, [ layouts, searchQuery, categoryFilter ] );

	const loadLayouts = async () => {
		const data = await fetchApi( `/prelayouts/by-type/${ templateType }` );
		if ( data?.layouts ) {
			setLayouts( data.layouts );
			setFilteredLayouts( data.layouts );
		}
	};

	const filterLayouts = useCallback( () => {
		let filtered = [ ...layouts ];

		// Filter by search query.
		if ( searchQuery ) {
			const query = searchQuery.toLowerCase();
			filtered = filtered.filter( 
				( layout ) =>
					layout.name.toLowerCase().includes( query ) ||
					layout.description.toLowerCase().includes( query )
			);
		}

		// Filter by category.
		if ( categoryFilter !== 'all' ) {
			filtered = filtered.filter( ( layout ) => layout.category === categoryFilter );
		}

		setFilteredLayouts( filtered );
	}, [ layouts, searchQuery, categoryFilter ] );

	const handleLayoutSelect = ( layout ) => {
		setSelectedLayout( layout.id );
	};

	const handleLayoutDoubleClick = ( layout ) => {
		if ( ! layout.is_pro || window.mpdAdmin?.isPro ) {
			onSelect( layout );
		}
	};

	const handlePreview = ( layout, event ) => {
		event.stopPropagation();
		setPreviewLayout( layout );
		setIsPreviewOpen( true );
	};

	const handleConfirmSelection = () => {
		const layout = layouts.find( ( l ) => l.id === selectedLayout );
		if ( layout ) {
			onSelect( layout );
		}
	};

	const isProLayout = ( layout ) => {
		return layout.is_pro && ! window.mpdAdmin?.isPro;
	};

	const classes = classnames( 'mpd-prelayout-selector', className );

	return (
		<div className={ classes }>
			<div className="mpd-prelayout-selector-header">
				<h2>{ __( 'Choose a Layout', 'magical-products-display' ) }</h2>
				<p>
					{ __( 'Select a pre-designed layout or start with a custom blank canvas.', 'magical-products-display' ) }
				</p>
			</div>

			<div className="mpd-prelayout-selector-filters">
				<SearchControl
					value={ searchQuery }
					onChange={ setSearchQuery }
					placeholder={ __( 'Search layouts...', 'magical-products-display' ) }
					className="mpd-prelayout-search"
				/>
				<SelectControl
					value={ categoryFilter }
					options={ layoutCategories }
					onChange={ setCategoryFilter }
					className="mpd-prelayout-category-filter"
					__nextHasNoMarginBottom
				/>
			</div>

			{ isLoading ? (
				<div className="mpd-prelayout-loading">
					<Spinner />
					<p>{ __( 'Loading layouts...', 'magical-products-display' ) }</p>
				</div>
			) : (
				<div className="mpd-prelayout-grid">
					{ filteredLayouts.map( ( layout ) => (
						<div
							key={ layout.id }
							className={ classnames( 'mpd-prelayout-card', {
								'is-selected': selectedLayout === layout.id,
								'is-custom': layout.is_custom,
								'is-pro': isProLayout( layout ),
							} ) }
							onClick={ () => handleLayoutSelect( layout ) }
							onDoubleClick={ () => handleLayoutDoubleClick( layout ) }
							role="button"
							tabIndex={ 0 }
							onKeyDown={ ( e ) => {
								if ( e.key === 'Enter' ) {
									handleLayoutSelect( layout );
								}
							} }
						>
							<div className="mpd-prelayout-card-thumbnail">
								{ layout.is_custom ? (
									<div className="mpd-prelayout-custom-icon">
										<Icon icon={ plus } size={ 48 } />
									</div>
								) : (
									<img
										src={ layout.thumbnail }
										alt={ layout.name }
										loading="lazy"
										onError={ ( e ) => {
											// Add cache buster to placeholder fallback.
											const timestamp = Date.now();
											e.target.src = window.mpdAdmin?.pluginUrl + '/assets/images/prelayouts/placeholder.svg?ver=' + timestamp;
										} }
									/>
								) }
								
								{ isProLayout( layout ) && (
									<div className="mpd-prelayout-pro-badge">
										<Icon icon={ lock } />
										<span>{ __( 'Pro', 'magical-products-display' ) }</span>
									</div>
								) }

								{ selectedLayout === layout.id && (
									<div className="mpd-prelayout-selected-badge">
										<Icon icon={ check } />
									</div>
								) }

								{ ! layout.is_custom && (
									<div className="mpd-prelayout-card-actions">
										<Tooltip text={ __( 'Preview', 'magical-products-display' ) }>
											<Button
												icon={ layoutIcon }
												onClick={ ( e ) => handlePreview( layout, e ) }
												className="mpd-prelayout-preview-btn"
											/>
										</Tooltip>
									</div>
								) }
							</div>

							<div className="mpd-prelayout-card-content">
								<h4 className="mpd-prelayout-card-title">
									{ layout.name }
								</h4>
								<p className="mpd-prelayout-card-description">
									{ layout.description }
								</p>
							</div>
						</div>
					) ) }
				</div>
			) }

			{ filteredLayouts.length === 0 && ! isLoading && (
				<div className="mpd-prelayout-no-results">
					<p>{ __( 'No layouts found matching your criteria.', 'magical-products-display' ) }</p>
					<Button
						variant="secondary"
						onClick={ () => {
							setSearchQuery( '' );
							setCategoryFilter( 'all' );
						} }
					>
						{ __( 'Clear Filters', 'magical-products-display' ) }
					</Button>
				</div>
			) }

			<div className="mpd-prelayout-selector-footer">
				<Button variant="secondary" onClick={ onCancel }>
					{ __( 'Cancel', 'magical-products-display' ) }
				</Button>
				<Button
					variant="primary"
					onClick={ handleConfirmSelection }
					disabled={ ! selectedLayout || ( selectedLayout && isProLayout( layouts.find( l => l.id === selectedLayout ) ) ) }
				>
					{ __( 'Use Selected Layout', 'magical-products-display' ) }
				</Button>
			</div>

			{ isPreviewOpen && previewLayout && (
				<LayoutPreviewModal
					layout={ previewLayout }
					onClose={ () => setIsPreviewOpen( false ) }
					onSelect={ () => {
						setIsPreviewOpen( false );
						onSelect( previewLayout );
					} }
					isPro={ isProLayout( previewLayout ) }
				/>
			) }
		</div>
	);
};

export default PreLayoutSelector;
