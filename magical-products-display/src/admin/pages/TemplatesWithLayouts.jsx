/**
 * Templates page component with Pre-Layout Selection
 *
 * @package Magical_Shop_Builder
 * @since   2.1.0
 */

import { useState, useEffect, useCallback } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { 
	Button, 
	Modal, 
	TextControl, 
	SelectControl,
	Spinner,
	Notice,
	ToggleControl
} from '@wordpress/components';
import { plus, settings, arrowLeft } from '@wordpress/icons';
import { useDispatch } from '@wordpress/data';
import { store as noticesStore } from '@wordpress/notices';
import Card from '../components/Card';
import TemplateCard, { AddTemplateCard } from '../components/TemplateCard';
import ConditionBuilder from '../components/ConditionBuilder';
import { PreLayoutSelector } from '../components/prelayouts';
import { useApi } from '../hooks/useApi';

/**
 * Template types configuration.
 */
const templateTypes = [
	{ value: 'archive-product', label: __( 'Shop/Archive', 'magical-products-display' ) },
	{ value: 'single-product', label: __( 'Single Product', 'magical-products-display' ) },
	{ value: 'cart', label: __( 'Cart', 'magical-products-display' ) },
	{ value: 'checkout', label: __( 'Checkout', 'magical-products-display' ) },
	{ value: 'my-account', label: __( 'My Account', 'magical-products-display' ) },
	{ value: 'empty-cart', label: __( 'Empty Cart', 'magical-products-display' ) },
	{ value: 'thankyou', label: __( 'Thank You', 'magical-products-display' ) },
];

/**
 * Elementor layout types.
 */
const layoutTypes = [
	{ value: 'elementor_canvas', label: __( 'Elementor Canvas (No Header/Footer)', 'magical-products-display' ) },
	{ value: 'elementor_header_footer', label: __( 'Elementor Full Width (With Theme Header/Footer)', 'magical-products-display' ) },
];

/**
 * Creation flow steps.
 */
const STEPS = {
	SELECT_TYPE: 'select_type',
	SELECT_LAYOUT: 'select_layout',
	CONFIGURE: 'configure',
};

/**
 * Templates page component.
 *
 * @return {JSX.Element} Templates component.
 */
const Templates = () => {
	const { fetchApi, postApi, deleteApi, isLoading } = useApi();
	const { createSuccessNotice, createErrorNotice } = useDispatch( noticesStore );
	
	const [ templates, setTemplates ] = useState( [] );
	const [ isCreateModalOpen, setIsCreateModalOpen ] = useState( false );
	const [ isDeleteModalOpen, setIsDeleteModalOpen ] = useState( false );
	const [ isConditionsModalOpen, setIsConditionsModalOpen ] = useState( false );
	const [ selectedTemplate, setSelectedTemplate ] = useState( null );
	const [ templateConditions, setTemplateConditions ] = useState( [] );
	
	// Creation flow state.
	const [ creationStep, setCreationStep ] = useState( STEPS.SELECT_TYPE );
	const [ newTemplate, setNewTemplate ] = useState( {
		title: '',
		type: 'single-product',
		layout: 'elementor_canvas',
		prelayout: null,
	} );
	const [ selectedPreLayout, setSelectedPreLayout ] = useState( null );
	
	const [ isCreating, setIsCreating ] = useState( false );
	const [ isDeleting, setIsDeleting ] = useState( false );
	const [ isSavingConditions, setIsSavingConditions ] = useState( false );
	const [ isImportingLayout, setIsImportingLayout ] = useState( false );

	// Settings state for template type enable/disable toggles.
	const [ templateSettings, setTemplateSettings ] = useState( null );

	/**
	 * Map template type values to their settings group and key.
	 */
	const templateToggleMap = {
		'single-product': { group: 'single_product', key: 'enable_custom_template' },
		'archive-product': { group: 'general', key: 'enable_custom_archive' },
		'cart': { group: 'cart_checkout', key: 'enable_custom_cart' },
		'checkout': { group: 'cart_checkout', key: 'enable_custom_checkout' },
		'my-account': { group: 'my_account', key: 'enable_custom_my_account' },
	};

	useEffect( () => {
		loadTemplates();
		loadSettings();
	}, [] );

	const loadSettings = async () => {
		const data = await fetchApi( '/settings' );
		if ( data ) {
			setTemplateSettings( data );
		}
	};

	/**
	 * Toggle a template type's enable/disable setting and auto-save.
	 */
	const handleToggleTemplateType = useCallback( async ( type, value ) => {
		const mapping = templateToggleMap[ type ];
		if ( ! mapping || ! templateSettings ) return;

		// Optimistic update.
		const updatedSettings = {
			...templateSettings,
			[ mapping.group ]: {
				...( templateSettings[ mapping.group ] || {} ),
				[ mapping.key ]: value,
			},
		};
		setTemplateSettings( updatedSettings );

		// Auto-save.
		const result = await postApi( '/settings', updatedSettings );
		if ( result?.success ) {
			createSuccessNotice(
				value
					? __( 'Custom template enabled.', 'magical-products-display' )
					: __( 'Custom template disabled.', 'magical-products-display' ),
				{ type: 'snackbar', isDismissible: true }
			);
			if ( result.settings ) {
				setTemplateSettings( result.settings );
			}
		} else {
			// Revert on failure.
			setTemplateSettings( templateSettings );
			createErrorNotice( result?.message || __( 'Failed to update setting.', 'magical-products-display' ) );
		}
	}, [ templateSettings, postApi, createSuccessNotice, createErrorNotice ] );

	/**
	 * Get the current enabled state for a template type.
	 */
	const isTemplateTypeEnabled = ( type ) => {
		const mapping = templateToggleMap[ type ];
		if ( ! mapping || ! templateSettings ) return true; // Default to true if no mapping.
		return !! templateSettings[ mapping.group ]?.[ mapping.key ];
	};

	const loadTemplates = async () => {
		const data = await fetchApi( '/templates' );
		if ( data ) {
			setTemplates( data );
		}
	};

	/**
	 * Handle template creation with pre-layout support.
	 */
	const handleCreate = async () => {
		if ( ! newTemplate.title.trim() ) {
			createErrorNotice( __( 'Please enter a template title.', 'magical-products-display' ) );
			return;
		}

		setIsCreating( true );
		
		// First, create the template.
		const result = await postApi( '/templates', {
			title: newTemplate.title,
			type: newTemplate.type,
			layout: newTemplate.layout,
		} );
		
		if ( ! result?.success ) {
			createErrorNotice( result?.message || __( 'Failed to create template.', 'magical-products-display' ) );
			setIsCreating( false );
			return;
		}

		const templateId = result.template?.id;

		// If a pre-layout was selected (not custom), import it.
		if ( selectedPreLayout && ! selectedPreLayout.is_custom && templateId ) {
			setIsImportingLayout( true );
			
			const importResult = await postApi( '/prelayouts/import', {
				layout_id: selectedPreLayout.id,
				template_id: templateId,
			} );
			
			if ( ! importResult?.success ) {
				createErrorNotice( 
					importResult?.message || __( 'Template created but layout import failed.', 'magical-products-display' )
				);
			} else {
				createSuccessNotice( 
					__( 'Template created with selected layout!', 'magical-products-display' )
				);
			}
			
			setIsImportingLayout( false );
		} else {
			createSuccessNotice( __( 'Template created successfully!', 'magical-products-display' ) );
		}
		
		// Reset state and close modal.
		setIsCreateModalOpen( false );
		resetCreationState();
		loadTemplates();
		
		// Open Elementor editor for new template.
		if ( result.template?.editUrl ) {
			window.open( result.template.editUrl, '_blank' );
		}
		
		setIsCreating( false );
	};

	/**
	 * Reset creation state.
	 */
	const resetCreationState = () => {
		setCreationStep( STEPS.SELECT_TYPE );
		setNewTemplate( { 
			title: '', 
			type: 'single-product', 
			layout: 'elementor_canvas',
			prelayout: null,
		} );
		setSelectedPreLayout( null );
	};

	/**
	 * Handle pre-layout selection.
	 */
	const handlePreLayoutSelect = ( layout ) => {
		setSelectedPreLayout( layout );
		setNewTemplate( { ...newTemplate, prelayout: layout.id } );
		setCreationStep( STEPS.CONFIGURE );
	};

	/**
	 * Handle back navigation in creation flow.
	 */
	const handleBackStep = () => {
		if ( creationStep === STEPS.CONFIGURE ) {
			setCreationStep( STEPS.SELECT_LAYOUT );
		} else if ( creationStep === STEPS.SELECT_LAYOUT ) {
			setCreationStep( STEPS.SELECT_TYPE );
		}
	};

	/**
	 * Handle next step in creation flow.
	 */
	const handleNextStep = () => {
		if ( creationStep === STEPS.SELECT_TYPE ) {
			setCreationStep( STEPS.SELECT_LAYOUT );
		}
	};

	const handleDelete = async () => {
		if ( ! selectedTemplate ) return;

		setIsDeleting( true );
		
		const result = await deleteApi( `/templates/${ selectedTemplate.id }` );
		
		if ( result?.success ) {
			createSuccessNotice( __( 'Template deleted successfully!', 'magical-products-display' ) );
			setIsDeleteModalOpen( false );
			setSelectedTemplate( null );
			loadTemplates();
		} else {
			createErrorNotice( result?.message || __( 'Failed to delete template.', 'magical-products-display' ) );
		}
		
		setIsDeleting( false );
	};

	const openDeleteModal = ( template ) => {
		setSelectedTemplate( template );
		setIsDeleteModalOpen( true );
	};

	const openConditionsModal = ( template ) => {
		setSelectedTemplate( template );
		setTemplateConditions( template.conditions || [] );
		setIsConditionsModalOpen( true );
	};

	const handleSaveConditions = async () => {
		if ( ! selectedTemplate ) return;

		setIsSavingConditions( true );
		
		const result = await postApi( `/templates/${ selectedTemplate.id }/conditions`, {
			conditions: templateConditions,
		} );
		
		if ( result?.success ) {
			createSuccessNotice( __( 'Conditions saved successfully!', 'magical-products-display' ) );
			setIsConditionsModalOpen( false );
			loadTemplates();
		} else {
			createErrorNotice( result?.message || __( 'Failed to save conditions.', 'magical-products-display' ) );
		}
		
		setIsSavingConditions( false );
	};

	const openCreateModal = ( type = 'single-product' ) => {
		setNewTemplate( { ...newTemplate, type } );
		setCreationStep( STEPS.SELECT_LAYOUT );
		setIsCreateModalOpen( true );
	};

	// Group templates by type.
	const groupedTemplates = templateTypes.reduce( ( acc, type ) => {
		acc[ type.value ] = templates.filter( t => t.type === type.value );
		return acc;
	}, {} );

	/**
	 * Render creation step content.
	 */
	const renderCreationStepContent = () => {
		switch ( creationStep ) {
			case STEPS.SELECT_TYPE:
				return (
					<div className="mpd-template-type-selection">
						<h3>{ __( 'Select Template Type', 'magical-products-display' ) }</h3>
						<div className="mpd-template-type-grid">
							{ templateTypes.map( ( type ) => (
								<div
									key={ type.value }
									className={ `mpd-template-type-option ${ newTemplate.type === type.value ? 'is-selected' : '' }` }
									onClick={ () => setNewTemplate( { ...newTemplate, type: type.value } ) }
									role="button"
									tabIndex={ 0 }
									onKeyDown={ ( e ) => {
										if ( e.key === 'Enter' ) {
											setNewTemplate( { ...newTemplate, type: type.value } );
										}
									} }
								>
									<span className="mpd-template-type-label">{ type.label }</span>
								</div>
							) ) }
						</div>
						<div className="mpd-modal-actions">
							<Button 
								variant="secondary" 
								onClick={ () => {
									setIsCreateModalOpen( false );
									resetCreationState();
								} }
							>
								{ __( 'Cancel', 'magical-products-display' ) }
							</Button>
							<Button 
								variant="primary" 
								onClick={ handleNextStep }
							>
								{ __( 'Next: Choose Layout', 'magical-products-display' ) }
							</Button>
						</div>
					</div>
				);

			case STEPS.SELECT_LAYOUT:
				return (
					<div className="mpd-template-layout-selection">
						<PreLayoutSelector
							templateType={ newTemplate.type }
							onSelect={ handlePreLayoutSelect }
							onCancel={ () => {
								setIsCreateModalOpen( false );
								resetCreationState();
							} }
						/>
					</div>
				);

			case STEPS.CONFIGURE:
				return (
					<div className="mpd-template-configuration">
						<Button
							variant="link"
							icon={ arrowLeft }
							onClick={ handleBackStep }
							className="mpd-back-button"
						>
							{ __( 'Back to Layout Selection', 'magical-products-display' ) }
						</Button>

						{ selectedPreLayout && (
							<div className="mpd-selected-layout-preview">
								<img 
									src={ selectedPreLayout.thumbnail } 
									alt={ selectedPreLayout.name }
									className="mpd-selected-layout-thumbnail"
								/>
								<div className="mpd-selected-layout-info">
									<h4>{ selectedPreLayout.name }</h4>
									<p>{ selectedPreLayout.description }</p>
								</div>
							</div>
						) }

						<TextControl
							label={ __( 'Template Name', 'magical-products-display' ) }
							value={ newTemplate.title }
							onChange={ ( value ) => setNewTemplate( { ...newTemplate, title: value } ) }
							placeholder={ __( 'Enter template name...', 'magical-products-display' ) }
						/>
						
						<SelectControl
							label={ __( 'Template Type', 'magical-products-display' ) }
							value={ newTemplate.type }
							options={ templateTypes }
							onChange={ ( value ) => setNewTemplate( { ...newTemplate, type: value } ) }
							disabled
						/>
						
						<SelectControl
							label={ __( 'Page Layout', 'magical-products-display' ) }
							value={ newTemplate.layout }
							options={ layoutTypes }
							onChange={ ( value ) => setNewTemplate( { ...newTemplate, layout: value } ) }
							help={ newTemplate.layout === 'elementor_header_footer' 
								? __( 'Uses your theme\'s header and footer.', 'magical-products-display' )
								: __( 'Full blank canvas without header/footer.', 'magical-products-display' )
							}
						/>

						{ selectedPreLayout && ! selectedPreLayout.is_custom && (
							<Notice status="info" isDismissible={ false }>
								{ __( 'The selected layout will be imported without demo data. All widgets will use your actual product data.', 'magical-products-display' ) }
							</Notice>
						) }

						<div className="mpd-modal-actions">
							<Button 
								variant="secondary" 
								onClick={ () => {
									setIsCreateModalOpen( false );
									resetCreationState();
								} }
							>
								{ __( 'Cancel', 'magical-products-display' ) }
							</Button>
							<Button 
								variant="primary" 
								onClick={ handleCreate }
								isBusy={ isCreating || isImportingLayout }
								disabled={ isCreating || isImportingLayout }
							>
								{ isImportingLayout 
									? __( 'Importing Layout...', 'magical-products-display' )
									: isCreating 
										? __( 'Creating...', 'magical-products-display' )
										: __( 'Create & Edit', 'magical-products-display' )
								}
							</Button>
						</div>
					</div>
				);
		}
	};

	/**
	 * Get modal title based on current step.
	 */
	const getModalTitle = () => {
		switch ( creationStep ) {
			case STEPS.SELECT_TYPE:
				return __( 'Create New Template - Step 1', 'magical-products-display' );
			case STEPS.SELECT_LAYOUT:
				return __( 'Choose a Layout - Step 2', 'magical-products-display' );
			case STEPS.CONFIGURE:
				return __( 'Configure Template - Step 3', 'magical-products-display' );
			default:
				return __( 'Create New Template', 'magical-products-display' );
		}
	};

	/**
	 * Get modal class based on current step.
	 */
	const getModalClass = () => {
		const classes = [ 'mpd-create-template-modal' ];
		
		if ( creationStep === STEPS.SELECT_LAYOUT ) {
			classes.push( 'mpd-layout-selection-modal' );
		}
		
		return classes.join( ' ' );
	};

	return (
		<div className="mpd-admin-page mpd-templates-page">
			<div className="mpd-admin-page-header">
				<div>
					<h1>{ __( 'Templates', 'magical-products-display' ) }</h1>
					<p>{ __( 'Create and manage your shop templates. Choose from pre-designed layouts or build custom templates with Elementor.', 'magical-products-display' ) }</p>
				</div>
				<Button 
					variant="primary" 
					icon={ plus }
					onClick={ () => {
						resetCreationState();
						setIsCreateModalOpen( true );
					} }
				>
					{ __( 'Add New Template', 'magical-products-display' ) }
				</Button>
			</div>

			{ isLoading ? (
				<div className="mpd-loading">
					<Spinner />
				</div>
			) : (
				<div className="mpd-templates-grid">
					{ templateTypes.map( ( type ) => {
						const hasToggle = !! templateToggleMap[ type.value ];
						const isEnabled = isTemplateTypeEnabled( type.value );

						return (
							<Card 
								key={ type.value }
								title={ type.label }
								className={ `mpd-template-type-card${ hasToggle && ! isEnabled ? ' mpd-template-type-disabled' : '' }` }
								actions={ hasToggle && templateSettings ? (
									<ToggleControl
										label={ isEnabled ? __( 'Enabled', 'magical-products-display' ) : __( 'Disabled', 'magical-products-display' ) }
										checked={ isEnabled }
										onChange={ ( value ) => handleToggleTemplateType( type.value, value ) }
										className="mpd-template-type-toggle"
									/>
								) : undefined }
							>
								<div className="mpd-template-type-templates">
									{ groupedTemplates[ type.value ]?.map( ( template ) => (
										<TemplateCard
											key={ template.id }
											template={ template }
											onDelete={ openDeleteModal }
											onConditions={ openConditionsModal }
										/>
									) ) }
									<AddTemplateCard
										type={ type.value }
										onAdd={ openCreateModal }
									/>
								</div>
							</Card>
						);
					} ) }
				</div>
			) }

			{ /* Create Modal with Layout Selection */ }
			{ isCreateModalOpen && (
				<Modal
					title={ getModalTitle() }
					onRequestClose={ () => {
						setIsCreateModalOpen( false );
						resetCreationState();
					} }
					className={ getModalClass() }
					isFullScreen={ creationStep === STEPS.SELECT_LAYOUT }
				>
					{ renderCreationStepContent() }
				</Modal>
			) }

			{ /* Delete Confirmation Modal */ }
			{ isDeleteModalOpen && selectedTemplate && (
				<Modal
					title={ __( 'Delete Template', 'magical-products-display' ) }
					onRequestClose={ () => setIsDeleteModalOpen( false ) }
					className="mpd-delete-template-modal"
				>
					<p>
						{ __( 'Are you sure you want to delete', 'magical-products-display' ) }
						{ ' ' }
						<strong>{ selectedTemplate.title }</strong>?
						{ ' ' }
						{ __( 'This action cannot be undone.', 'magical-products-display' ) }
					</p>
					<div className="mpd-modal-actions">
						<Button 
							variant="secondary" 
							onClick={ () => setIsDeleteModalOpen( false ) }
						>
							{ __( 'Cancel', 'magical-products-display' ) }
						</Button>
						<Button 
							variant="primary" 
							isDestructive
							onClick={ handleDelete }
							isBusy={ isDeleting }
							disabled={ isDeleting }
						>
							{ isDeleting 
								? __( 'Deleting...', 'magical-products-display' )
								: __( 'Delete', 'magical-products-display' )
							}
						</Button>
					</div>
				</Modal>
			) }

			{ /* Conditions Modal */ }
			{ isConditionsModalOpen && selectedTemplate && (
				<Modal
					title={ __( 'Template Conditions', 'magical-products-display' ) + ' - ' + selectedTemplate.title }
					onRequestClose={ () => setIsConditionsModalOpen( false ) }
					className="mpd-conditions-modal"
				>
					<ConditionBuilder
						conditions={ templateConditions }
						onChange={ setTemplateConditions }
					/>
					<div className="mpd-modal-actions">
						<Button 
							variant="secondary" 
							onClick={ () => setIsConditionsModalOpen( false ) }
						>
							{ __( 'Cancel', 'magical-products-display' ) }
						</Button>
						<Button 
							variant="primary" 
							onClick={ handleSaveConditions }
							isBusy={ isSavingConditions }
							disabled={ isSavingConditions }
						>
							{ isSavingConditions 
								? __( 'Saving...', 'magical-products-display' )
								: __( 'Save Conditions', 'magical-products-display' )
							}
						</Button>
					</div>
				</Modal>
			) }
		</div>
	);
};

export default Templates;
