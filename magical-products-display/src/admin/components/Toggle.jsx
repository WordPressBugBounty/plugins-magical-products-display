/**
 * Toggle component for admin dashboard
 *
 * @package Magical_Shop_Builder
 * @since   2.0.0
 */

import { ToggleControl } from '@wordpress/components';
import classnames from 'classnames';

/**
 * Toggle component.
 *
 * @param {Object}   props           Component props.
 * @param {string}   props.label     Toggle label.
 * @param {string}   props.help      Help text.
 * @param {boolean}  props.checked   Whether toggle is checked.
 * @param {Function} props.onChange  Change handler.
 * @param {boolean}  props.disabled  Whether toggle is disabled.
 * @param {boolean}  props.isPro     Whether this is a pro feature.
 * @param {string}   props.className Additional CSS class.
 * @return {JSX.Element} Toggle component.
 */
const Toggle = ( { 
	label, 
	help, 
	checked, 
	onChange, 
	disabled = false, 
	isPro = false,
	className 
} ) => {
	const adminData = window.mpdAdmin || {};
	const isProLocked = isPro && ! adminData.isPro;
	
	const classes = classnames( 
		'mpd-toggle', 
		className,
		{
			'is-pro-locked': isProLocked,
			'is-disabled': disabled,
		}
	);

	// Create label with Pro badge
	const labelWithBadge = (
		<span className="mpd-toggle-label">
			{ label }
			{ isProLocked && (
				<span className="mpd-pro-badge">Pro</span>
			) }
		</span>
	);

	return (
		<div className={ classes }>
			<ToggleControl
				checked={ checked }
				onChange={ onChange }
				disabled={ disabled || isProLocked }
			/>
			<div className="mpd-toggle-content">
				<div className="mpd-toggle-label-wrap">{ labelWithBadge }</div>
				{ help && <p className="mpd-toggle-help">{ help }</p> }
			</div>
		</div>
	);
};

export default Toggle;
