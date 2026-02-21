/**
 * Card component for admin dashboard
 *
 * @package Magical_Shop_Builder
 * @since   2.0.0
 */

import classnames from 'classnames';

/**
 * Card component.
 *
 * @param {Object}      props           Component props.
 * @param {string}      props.title     Card title.
 * @param {JSX.Element} props.icon      Card icon.
 * @param {string}      props.className Additional CSS class.
 * @param {JSX.Element} props.actions   Card action buttons.
 * @param {JSX.Element} props.children  Card content.
 * @return {JSX.Element} Card component.
 */
const Card = ( { title, icon, className, actions, children } ) => {
	const classes = classnames( 'mpd-admin-card', className );

	return (
		<div className={ classes }>
			{ ( title || actions ) && (
				<div className="mpd-admin-card-header">
					{ title && (
						<div className="mpd-admin-card-title">
							{ icon && (
								<span className="mpd-admin-card-icon">{ icon }</span>
							) }
							<h3>{ title }</h3>
						</div>
					) }
					{ actions && (
						<div className="mpd-admin-card-actions">{ actions }</div>
					) }
				</div>
			) }
			<div className="mpd-admin-card-body">{ children }</div>
		</div>
	);
};

export default Card;
