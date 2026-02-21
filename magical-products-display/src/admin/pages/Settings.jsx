/**
 * Settings page component
 *
 * @package Magical_Shop_Builder
 * @since   2.0.0
 */

import { useState, useEffect } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { 
	Button, 
	TabPanel,
	Spinner,
	SelectControl,
	TextControl,
	ColorPicker,
	ColorIndicator,
	Dropdown,
	CheckboxControl,
	__experimentalNumberControl as NumberControl
} from '@wordpress/components';
import { useParams, useNavigate } from 'react-router-dom';
import { useDispatch } from '@wordpress/data';
import { store as noticesStore } from '@wordpress/notices';
import Card from '../components/Card';
import Toggle from '../components/Toggle';
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
 * Settings page component.
 *
 * @return {JSX.Element} Settings component.
 */
const Settings = () => {
	const { tab } = useParams();
	const navigate = useNavigate();
	const { fetchApi, postApi, isLoading } = useApi();
	const { createSuccessNotice, createErrorNotice } = useDispatch( noticesStore );
	const { isPro } = getAdminData();

	const [ settings, setSettings ] = useState( null );
	const [ isSaving, setIsSaving ] = useState( false );
	const [ hasChanges, setHasChanges ] = useState( false );

	useEffect( () => {
		loadSettings();
	}, [] );

	const loadSettings = async () => {
		const data = await fetchApi( '/settings' );
		if ( data ) {
			setSettings( data );
		}
	};

	const updateSetting = ( group, key, value ) => {
		setSettings( ( prev ) => {
			const currentGroup = prev[ group ] || {};
			const updated = {
				...prev,
				[ group ]: {
					...currentGroup,
					[ key ]: value,
				},
			};
			return updated;
		} );
		setHasChanges( true );
	};

	const resetGroup = ( group, defaults ) => {
		setSettings( ( prev ) => ( {
			...prev,
			[ group ]: { ...defaults },
		} ) );
		setHasChanges( true );
	};

	const handleSave = async () => {
		setIsSaving( true );
		
		const result = await postApi( '/settings', settings );
		
		if ( result?.success ) {
			createSuccessNotice( __( 'Settings saved successfully!', 'magical-products-display' ) );
			setHasChanges( false );
			
			// Update local state with returned settings.
			if ( result.settings ) {
				setSettings( result.settings );
			}
		} else {
			createErrorNotice( result?.message || __( 'Failed to save settings.', 'magical-products-display' ) );
		}
		
		setIsSaving( false );
	};

	const handleTabChange = ( tabName ) => {
		navigate( `/settings/${ tabName }` );
	};

	const tabs = [
		{
			name: 'general',
			title: __( 'General', 'magical-products-display' ),
			className: 'mpd-settings-tab',
		},
		{
			name: 'performance',
			title: __( 'Performance', 'magical-products-display' ),
			className: 'mpd-settings-tab',
		},
		{
			name: 'preloader',
			title: __( 'Preloader', 'magical-products-display' ),
			className: 'mpd-settings-tab',
		},
	];

	const initialTabName = tab || 'general';

	if ( isLoading || ! settings ) {
		return (
			<div className="mpd-admin-page mpd-settings-page">
				<div className="mpd-admin-page-header">
					<h1>{ __( 'Settings', 'magical-products-display' ) }</h1>
				</div>
				<div className="mpd-loading">
					<Spinner />
				</div>
			</div>
		);
	}

	return (
		<div className={ `mpd-admin-page mpd-settings-page${ hasChanges ? ' has-sticky-save-bar' : '' }` }>
			<div className="mpd-admin-page-header">
				<div>
					<h1>{ __( 'Settings', 'magical-products-display' ) }</h1>
					<p>{ __( 'Configure your shop builder settings.', 'magical-products-display' ) }</p>
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

			<TabPanel
				className="mpd-settings-tabs"
				tabs={ tabs }
				initialTabName={ initialTabName }
				onSelect={ handleTabChange }
			>
				{ ( selectedTab ) => {
					switch ( selectedTab.name ) {
						case 'general':
							return (
								<GeneralSettings 
									settings={ settings.general } 
									updateSetting={ ( key, value ) => updateSetting( 'general', key, value ) }
									isPro={ isPro }
								/>
							);
						case 'performance':
							return (
								<PerformanceSettings 
									settings={ settings.performance } 
									updateSetting={ ( key, value ) => updateSetting( 'performance', key, value ) }
									isPro={ isPro }
								/>
							);
						case 'preloader':
							return (
								<PreloaderSettings 
									settings={ settings.preloader } 
									updateSetting={ ( key, value ) => updateSetting( 'preloader', key, value ) }
									onReset={ () => resetGroup( 'preloader', PRELOADER_DEFAULTS ) }
									isPro={ isPro }
								/>
							);
						default:
							return null;
					}
				} }
			</TabPanel>

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

/**
 * General Settings component.
 */
const GeneralSettings = ( { settings, updateSetting, isPro } ) => {
	return (
		<>
			<Card title={ __( 'General Settings', 'magical-products-display' ) }>
				<div className="mpd-settings-group">
					<Toggle
						label={ __( 'Lazy Load Images', 'magical-products-display' ) }
						help={ __( 'Lazy load product images for better performance.', 'magical-products-display' ) }
						checked={ settings.lazy_load_images }
						onChange={ ( value ) => updateSetting( 'lazy_load_images', value ) }
					/>
					<Toggle
						label={ __( 'AJAX Add to Cart', 'magical-products-display' ) }
						help={ __( 'Add products to cart without page refresh.', 'magical-products-display' ) }
						checked={ settings.add_to_cart_ajax }
						onChange={ ( value ) => updateSetting( 'add_to_cart_ajax', value ) }
					/>
				</div>
			</Card>
			<Card title={ __( 'Styling', 'magical-products-display' ) }>
				<div className="mpd-settings-info-notice">
					<p>
						<strong>{ __( 'Styling Tip:', 'magical-products-display' ) }</strong>{ ' ' }
						{ __( 'All MPD widgets inherit your Elementor Global Colors & Fonts. Configure them in', 'magical-products-display' ) }{ ' ' }
						<strong>{ __( 'Elementor → Site Settings → Global Colors / Global Fonts', 'magical-products-display' ) }</strong>.
					</p>
					<p className="mpd-settings-info-sub">
						{ __( 'You can also customize individual widget styles directly in the Elementor editor using the Style tab.', 'magical-products-display' ) }
					</p>
				</div>
			</Card>
		</>
	);
};



/**
 * Performance Settings component.
 */
const PerformanceSettings = ( { settings, updateSetting, isPro } ) => {
	return (
		<Card title={ __( 'Performance Settings', 'magical-products-display' ) }>
			<div className="mpd-settings-group">
				<Toggle
					label={ __( 'Lazy Load Widgets', 'magical-products-display' ) }
					help={ __( 'Only load widget assets when needed.', 'magical-products-display' ) }
					checked={ settings.lazy_load_widgets }
					onChange={ ( value ) => updateSetting( 'lazy_load_widgets', value ) }
				/>
				<Toggle
					label={ __( 'Minify CSS', 'magical-products-display' ) }
					help={ __( 'Minify CSS output for better performance.', 'magical-products-display' ) }
					checked={ settings.minify_css }
					onChange={ ( value ) => updateSetting( 'minify_css', value ) }
					isPro={ true }
				/>
				<Toggle
					label={ __( 'Minify JS', 'magical-products-display' ) }
					help={ __( 'Minify JavaScript output for better performance.', 'magical-products-display' ) }
					checked={ settings.minify_js }
					onChange={ ( value ) => updateSetting( 'minify_js', value ) }
					isPro={ true }
				/>
				<Toggle
					label={ __( 'Defer JavaScript', 'magical-products-display' ) }
					help={ __( 'Defer non-critical JavaScript loading.', 'magical-products-display' ) }
					checked={ settings.defer_js }
					onChange={ ( value ) => updateSetting( 'defer_js', value ) }
					isPro={ true }
				/>
				<Toggle
					label={ __( 'Cache Templates', 'magical-products-display' ) }
					help={ __( 'Cache template output for faster loading.', 'magical-products-display' ) }
					checked={ settings.cache_templates }
					onChange={ ( value ) => updateSetting( 'cache_templates', value ) }
				/>
				{ settings.cache_templates && (
					<NumberControl
						label={ __( 'Cache Duration (seconds)', 'magical-products-display' ) }
						value={ settings.cache_duration }
						onChange={ ( value ) => updateSetting( 'cache_duration', parseInt( value ) || 3600 ) }
						min={ 60 }
						max={ 86400 }
					/>
				) }
			</div>
		</Card>
	);
};

/**
 * Preloader Settings component.
 */
const PRELOADER_DEFAULTS = {
	enable: true,
	style: 'spinner',
	primary_color: '#0073aa',
	secondary_color: '#f3f3f3',
	background_color: '#ffffff',
	text_color: '#666666',
	show_logo: false,
	logo_url: '',
	loading_text: '',
	pages: [ 'shop', 'product', 'cart', 'checkout', 'my-account' ],
};

const FREE_PRELOADER_STYLES = [ 'spinner', 'double-bounce', 'pulse', 'three-dots' ];

const PreloaderSettings = ( { settings, updateSetting, onReset, isPro } ) => {
	const preloaderStyles = [
		{ value: 'spinner', label: __( 'Spinner Circle', 'magical-products-display' ) },
		{ value: 'double-bounce', label: __( 'Double Bounce', 'magical-products-display' ) },
		{ value: 'pulse', label: __( 'Pulse', 'magical-products-display' ) },
		{ value: 'three-dots', label: __( 'Three Dots', 'magical-products-display' ) },
		{ value: 'wave', label: __( 'Wave Bars', 'magical-products-display' ) },
		{ value: 'cube-grid', label: __( 'Cube Grid', 'magical-products-display' ) },
		{ value: 'ring', label: __( 'Ring', 'magical-products-display' ) },
		{ value: 'folding-cube', label: __( 'Folding Cube', 'magical-products-display' ) },
		{ value: 'circle-dots', label: __( 'Circle Dots', 'magical-products-display' ) },
		{ value: 'progress', label: __( 'Progress Bar', 'magical-products-display' ) },
		{ value: 'logo-fade', label: __( 'Logo Fade (requires logo)', 'magical-products-display' ) },
		{ value: 'logo-ring', label: __( 'Logo with Ring (requires logo)', 'magical-products-display' ) },
	].map( ( style ) => {
		const isProStyle = ! FREE_PRELOADER_STYLES.includes( style.value );
		return {
			...style,
			label: isProStyle && ! isPro ? `${ style.label } (Pro)` : style.label,
			disabled: isProStyle && ! isPro,
		};
	} );

	const pageOptions = [
		{ value: 'all', label: __( 'All Pages', 'magical-products-display' ) },
		{ value: 'shop', label: __( 'Shop / Archive', 'magical-products-display' ) },
		{ value: 'product', label: __( 'Single Product', 'magical-products-display' ) },
		{ value: 'cart', label: __( 'Cart', 'magical-products-display' ) },
		{ value: 'checkout', label: __( 'Checkout', 'magical-products-display' ) },
		{ value: 'my-account', label: __( 'My Account', 'magical-products-display' ) },
		{ value: 'thank-you', label: __( 'Thank You Page', 'magical-products-display' ) },
	];

	const handlePageToggle = ( page, checked ) => {
		const currentPages = settings.pages || [];
		let newPages;
		
		if ( page === 'all' ) {
			newPages = checked ? [ 'all' ] : [];
		} else {
			// Remove 'all' if individual pages are selected
			newPages = currentPages.filter( p => p !== 'all' );
			if ( checked ) {
				newPages = [ ...newPages, page ];
			} else {
				newPages = newPages.filter( p => p !== page );
			}
		}
		
		updateSetting( 'pages', newPages );
	};

	// Helper to extract hex color from ColorPicker value
	const handleColorChange = ( colorKey, value ) => {
		// ColorPicker can return a string or object depending on version/interaction
		let hexColor = value;
		if ( typeof value === 'object' && value !== null ) {
			hexColor = value.hex || value.color || '#000000';
		}
		// Ensure it's a valid hex format
		if ( typeof hexColor === 'string' && ! hexColor.startsWith( '#' ) ) {
			hexColor = '#' + hexColor;
		}
		updateSetting( colorKey, hexColor );
	};

	const openMediaLibrary = () => {
		const frame = wp.media( {
			title: __( 'Select Preloader Logo', 'magical-products-display' ),
			button: { text: __( 'Use this image', 'magical-products-display' ) },
			multiple: false,
			library: { type: 'image' }
		} );

		frame.on( 'select', () => {
			const attachment = frame.state().get( 'selection' ).first().toJSON();
			updateSetting( 'logo_url', attachment.url );
		} );

		frame.open();
	};

	return (
		<>
			<Card title={ __( 'Preloader Settings', 'magical-products-display' ) }>
				<div className="mpd-settings-section-header">
					<p className="mpd-settings-description">
						{ __( 'Show a loading animation while pages load to prevent flash of unstyled content (FOUC).', 'magical-products-display' ) }
					</p>
					<Button
						variant="secondary"
						isDestructive
						isSmall
						className="mpd-reset-defaults-btn"
						onClick={ () => {
							// eslint-disable-next-line no-alert
							if ( window.confirm( __( 'Reset all preloader settings to their defaults?', 'magical-products-display' ) ) ) {
								onReset();
							}
						} }
					>
						{ __( 'Reset to Defaults', 'magical-products-display' ) }
					</Button>
				</div>
				<div className="mpd-settings-group">
					<Toggle
						label={ __( 'Enable Preloader', 'magical-products-display' ) }
						help={ __( 'Show a loading animation on WooCommerce pages.', 'magical-products-display' ) }
						checked={ settings.enable }
						onChange={ ( value ) => updateSetting( 'enable', value ) }
					/>
				</div>
			</Card>

			{ settings.enable && (
				<>
					<Card title={ __( 'Preloader Style', 'magical-products-display' ) }>
						<div className="mpd-settings-group">
							<SelectControl
								label={ __( 'Animation Style', 'magical-products-display' ) }
								value={ settings.style }
								options={ preloaderStyles }
								onChange={ ( value ) => {
									const isProStyle = ! FREE_PRELOADER_STYLES.includes( value );
									if ( isProStyle && ! isPro ) {
										return; // Prevent selecting pro styles for free users
									}
									updateSetting( 'style', value );
								} }
							/>
							
							<div className="mpd-preloader-preview">
								<p className="mpd-settings-label">{ __( 'Preview', 'magical-products-display' ) }</p>
								<div 
									className="mpd-preloader-preview-box"
									style={ { 
										backgroundColor: settings.background_color,
										padding: '40px',
										borderRadius: '8px',
										display: 'flex',
										flexDirection: 'column',
										justifyContent: 'center',
										alignItems: 'center',
										gap: '16px',
										minHeight: '180px',
										border: '1px solid #ddd'
									} }
								>
									{ settings.show_logo && settings.logo_url && (
										<img 
											src={ settings.logo_url } 
											alt="Logo" 
											style={ { maxWidth: '120px', maxHeight: '60px', objectFit: 'contain' } } 
										/>
									) }
									<PreloaderPreview 
										style={ settings.style } 
										primaryColor={ settings.primary_color }
										secondaryColor={ settings.secondary_color }
									/>
									{ settings.loading_text && (
										<span style={ { color: settings.text_color, fontSize: '14px' } }>
											{ settings.loading_text }
										</span>
									) }
								</div>
							</div>
						</div>
					</Card>

					<Card title={ __( 'Colors', 'magical-products-display' ) }>
						<div className="mpd-settings-group mpd-color-settings-compact">
							<div className="mpd-color-field-compact">
								<label>{ __( 'Primary Color', 'magical-products-display' ) }</label>
								<Dropdown
									className="mpd-color-dropdown"
									contentClassName="mpd-color-popover"
									renderToggle={ ( { isOpen, onToggle } ) => (
										<button 
											type="button"
											className="mpd-color-button"
											onClick={ onToggle }
											aria-expanded={ isOpen }
										>
											<ColorIndicator colorValue={ settings.primary_color } />
											<span>{ settings.primary_color }</span>
										</button>
									) }
									renderContent={ () => (
										<ColorPicker
											color={ settings.primary_color }
											onChange={ ( value ) => handleColorChange( 'primary_color', value ) }
											enableAlpha={ false }
										/>
									) }
								/>
							</div>
							<div className="mpd-color-field-compact">
								<label>{ __( 'Secondary Color', 'magical-products-display' ) }</label>
								<Dropdown
									className="mpd-color-dropdown"
									contentClassName="mpd-color-popover"
									renderToggle={ ( { isOpen, onToggle } ) => (
										<button 
											type="button"
											className="mpd-color-button"
											onClick={ onToggle }
											aria-expanded={ isOpen }
										>
											<ColorIndicator colorValue={ settings.secondary_color } />
											<span>{ settings.secondary_color }</span>
										</button>
									) }
									renderContent={ () => (
										<ColorPicker
											color={ settings.secondary_color }
											onChange={ ( value ) => handleColorChange( 'secondary_color', value ) }
											enableAlpha={ false }
										/>
									) }
								/>
							</div>
							<div className="mpd-color-field-compact">
								<label>{ __( 'Background Color', 'magical-products-display' ) }</label>
								<Dropdown
									className="mpd-color-dropdown"
									contentClassName="mpd-color-popover"
									renderToggle={ ( { isOpen, onToggle } ) => (
										<button 
											type="button"
											className="mpd-color-button"
											onClick={ onToggle }
											aria-expanded={ isOpen }
										>
											<ColorIndicator colorValue={ settings.background_color } />
											<span>{ settings.background_color }</span>
										</button>
									) }
									renderContent={ () => (
										<ColorPicker
											color={ settings.background_color }
											onChange={ ( value ) => handleColorChange( 'background_color', value ) }
											enableAlpha={ false }
										/>
									) }
								/>
							</div>
							<div className="mpd-color-field-compact">
								<label>{ __( 'Text Color', 'magical-products-display' ) }</label>
								<Dropdown
									className="mpd-color-dropdown"
									contentClassName="mpd-color-popover"
									renderToggle={ ( { isOpen, onToggle } ) => (
										<button 
											type="button"
											className="mpd-color-button"
											onClick={ onToggle }
											aria-expanded={ isOpen }
										>
											<ColorIndicator colorValue={ settings.text_color } />
											<span>{ settings.text_color }</span>
										</button>
									) }
									renderContent={ () => (
										<ColorPicker
											color={ settings.text_color }
											onChange={ ( value ) => handleColorChange( 'text_color', value ) }
											enableAlpha={ false }
										/>
									) }
								/>
							</div>
						</div>
					</Card>

					<div className={ `mpd-pro-locked-card-wrap${ ! isPro ? ' is-locked' : '' }` }>
						{ ! isPro && (
							<div className="mpd-pro-locked-overlay">
								<span className="mpd-pro-badge">Pro</span>
								<span className="mpd-pro-locked-text">{ __( 'Upgrade to Pro to unlock Logo & Text settings', 'magical-products-display' ) }</span>
							</div>
						) }
						<Card title={ __( 'Logo & Text', 'magical-products-display' ) }>
							<div className="mpd-settings-group">
								<Toggle
									label={ __( 'Show Logo', 'magical-products-display' ) }
									help={ __( 'Display your logo above the loading animation.', 'magical-products-display' ) }
									checked={ settings.show_logo }
									onChange={ ( value ) => updateSetting( 'show_logo', value ) }
									disabled={ ! isPro }
								/>
								
								{ settings.show_logo && (
									<div className="mpd-logo-upload">
										{ settings.logo_url ? (
											<div className="mpd-logo-preview">
												<img src={ settings.logo_url } alt="Logo" style={ { maxWidth: '150px', maxHeight: '80px' } } />
												<Button 
													variant="secondary" 
													isDestructive
													onClick={ () => updateSetting( 'logo_url', '' ) }
												>
													{ __( 'Remove', 'magical-products-display' ) }
												</Button>
											</div>
										) : (
											<Button variant="secondary" onClick={ openMediaLibrary }>
												{ __( 'Upload Logo', 'magical-products-display' ) }
											</Button>
										) }
									</div>
								) }
								
								<div className="mpd-loading-text-field" style={ { maxWidth: '400px' } }>
									<TextControl
										label={ __( 'Loading Text', 'magical-products-display' ) }
										help={ __( 'Optional text to display below the animation (e.g., "Loading...").', 'magical-products-display' ) }
										value={ settings.loading_text }
										onChange={ ( value ) => updateSetting( 'loading_text', value ) }
										placeholder={ __( 'Loading...', 'magical-products-display' ) }
										disabled={ ! isPro }
									/>
								</div>
							</div>
						</Card>
					</div>

					<Card title={ __( 'Pages', 'magical-products-display' ) }>
						<p className="mpd-settings-description">
							{ __( 'Select which pages should show the preloader.', 'magical-products-display' ) }
						</p>
						<div className="mpd-settings-group mpd-checkbox-group">
							{ pageOptions.map( ( option ) => (
								<CheckboxControl
									key={ option.value }
									label={ option.label }
									checked={ settings.pages?.includes( option.value ) || false }
									onChange={ ( checked ) => handlePageToggle( option.value, checked ) }
								/>
							) ) }
						</div>
					</Card>
				</>
			) }
		</>
	);
};

/**
 * Preloader Preview component.
 */
const PreloaderPreview = ( { style, primaryColor, secondaryColor } ) => {
	const spinnerStyle = {
		'--mpd-primary': primaryColor,
		'--mpd-secondary': secondaryColor,
	};

	switch ( style ) {
		case 'double-bounce':
			return (
				<div style={ { width: '50px', height: '50px', position: 'relative', ...spinnerStyle } }>
					<div style={ {
						width: '100%', height: '100%', borderRadius: '50%', backgroundColor: primaryColor,
						opacity: 0.6, position: 'absolute', animation: 'mpd-bounce 2s infinite ease-in-out'
					} }></div>
					<div style={ {
						width: '100%', height: '100%', borderRadius: '50%', backgroundColor: primaryColor,
						opacity: 0.6, position: 'absolute', animation: 'mpd-bounce 2s infinite ease-in-out', animationDelay: '-1s'
					} }></div>
				</div>
			);
		case 'pulse':
			return (
				<div style={ {
					width: '50px', height: '50px', borderRadius: '50%', backgroundColor: primaryColor,
					animation: 'mpd-pulse 1.4s infinite ease-in-out'
				} }></div>
			);
		case 'three-dots':
			return (
				<div style={ { display: 'flex', gap: '8px' } }>
					{ [ 0, 1, 2 ].map( i => (
						<div key={ i } style={ {
							width: '12px', height: '12px', borderRadius: '50%', backgroundColor: primaryColor,
							animation: 'mpd-three-dots 1.4s infinite ease-in-out', animationDelay: `${ -0.32 + i * 0.16 }s`
						} }></div>
					) ) }
				</div>
			);
		case 'wave':
			return (
				<div style={ { display: 'flex', gap: '4px', alignItems: 'center', height: '50px' } }>
					{ [ 0, 1, 2, 3, 4 ].map( i => (
						<div key={ i } style={ {
							width: '5px', height: '100%', backgroundColor: primaryColor,
							animation: 'mpd-wave 1.2s infinite ease-in-out', animationDelay: `${ -1.2 + i * 0.1 }s`
						} }></div>
					) ) }
				</div>
			);
		case 'ring':
			return (
				<div style={ {
					width: '50px', height: '50px', border: `4px solid transparent`,
					borderTopColor: primaryColor, borderBottomColor: primaryColor,
					borderRadius: '50%', animation: 'mpd-ring 1.2s linear infinite'
				} }></div>
			);
		case 'progress':
			return (
				<div style={ {
					width: '200px', height: '4px', backgroundColor: secondaryColor,
					borderRadius: '4px', overflow: 'hidden'
				} }>
					<div style={ {
						width: '100%', height: '100%', backgroundColor: primaryColor,
						animation: 'mpd-progress 2s infinite ease-in-out', transformOrigin: 'left'
					} }></div>
				</div>
			);
		case 'spinner':
		default:
			return (
				<div style={ {
					width: '50px', height: '50px',
					border: `3px solid ${ secondaryColor }`,
					borderTop: `3px solid ${ primaryColor }`,
					borderRadius: '50%',
					animation: 'mpd-spin 1s linear infinite'
				} }></div>
			);
	}
};

export default Settings;
