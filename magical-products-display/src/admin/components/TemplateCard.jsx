/**
 * Template Card component for admin dashboard
 *
 * @package Magical_Shop_Builder
 * @since   2.0.0
 */

import { __ } from '@wordpress/i18n';
import { Button, Icon, Dropdown, MenuGroup, MenuItem } from '@wordpress/components';
import { moreVertical, pencil, trash, plus, external, cog } from '@wordpress/icons';
import classnames from 'classnames';

/**
 * Template type labels.
 */
const templateTypeLabels = {
	'single-product': __( 'Single Product', 'magical-products-display' ),
	'archive-product': __( 'Shop/Archive', 'magical-products-display' ),
	'cart': __( 'Cart', 'magical-products-display' ),
	'checkout': __( 'Checkout', 'magical-products-display' ),
	'my-account': __( 'My Account', 'magical-products-display' ),
	'empty-cart': __( 'Empty Cart', 'magical-products-display' ),
	'thankyou': __( 'Thank You', 'magical-products-display' ),
};

/**
 * Template Card component.
 *
 * @param {Object}   props              Component props.
 * @param {Object}   props.template     Template data.
 * @param {Function} props.onDelete     Delete handler.
 * @param {Function} props.onConditions Conditions handler.
 * @param {string}   props.className    Additional CSS class.
 * @return {JSX.Element} TemplateCard component.
 */
const TemplateCard = ( { template, onDelete, onConditions, className } ) => {
	const classes = classnames( 'mpd-template-card', className );
	const typeLabel = templateTypeLabels[ template.type ] || template.type;

	return (
		<div className={ classes }>
			<div className="mpd-template-card-header">
				<h4 className="mpd-template-card-title">{ template.title }</h4>
				<Dropdown
					popoverProps={ { placement: 'bottom-end' } }
					renderToggle={ ( { isOpen, onToggle } ) => (
						<Button
							icon={ moreVertical }
							onClick={ onToggle }
							aria-expanded={ isOpen }
							label={ __( 'More options', 'magical-products-display' ) }
							className="mpd-template-card-menu-btn"
						/>
					) }
					renderContent={ ( { onClose } ) => (
						<MenuGroup>
							<MenuItem 
								icon={ pencil } 
								onClick={ () => {
									window.open( template.editUrl, '_blank' );
									onClose();
								} }
							>
								{ __( 'Edit with Elementor', 'magical-products-display' ) }
							</MenuItem>
							<MenuItem 
								icon={ cog } 
								onClick={ () => {
									onConditions?.( template );
									onClose();
								} }
							>
								{ __( 'Display Conditions', 'magical-products-display' ) }
							</MenuItem>
							<MenuItem 
								icon={ trash } 
								onClick={ () => {
									onDelete?.( template );
									onClose();
								} }
								isDestructive
							>
								{ __( 'Delete', 'magical-products-display' ) }
							</MenuItem>
						</MenuGroup>
					) }
				/>
			</div>
			<div className="mpd-template-card-body">
				<span className="mpd-template-card-type">{ typeLabel }</span>
				{ template.conditions?.length > 0 && (
					<span className="mpd-template-card-conditions">
						{ template.conditions.length } { __( 'conditions', 'magical-products-display' ) }
					</span>
				) }
			</div>
			<div className="mpd-template-card-footer">
				<Button
					variant="secondary"
					href={ template.editUrl }
					target="_blank"
				>
					{ __( 'Edit', 'magical-products-display' ) }
					<Icon icon={ external } size={ 16 } />
				</Button>
			</div>
		</div>
	);
};

/**
 * Add Template Card component.
 *
 * @param {Object}   props           Component props.
 * @param {string}   props.type      Template type.
 * @param {Function} props.onAdd     Add handler.
 * @param {string}   props.className Additional CSS class.
 * @return {JSX.Element} AddTemplateCard component.
 */
export const AddTemplateCard = ( { type, onAdd, className } ) => {
	const classes = classnames( 'mpd-template-card mpd-template-card-add', className );
	const typeLabel = templateTypeLabels[ type ] || type;

	return (
		<div className={ classes }>
			<Button
				variant="secondary"
				icon={ plus }
				onClick={ () => onAdd?.( type ) }
				className="mpd-template-card-add-btn"
			>
				{ __( 'Add New', 'magical-products-display' ) }
			</Button>
			<span className="mpd-template-card-type">{ typeLabel }</span>
		</div>
	);
};

export default TemplateCard;
