/**
 * Condition Builder component for template conditions
 *
 * @package Magical_Shop_Builder
 * @since   2.0.0
 */

import { useState, useEffect } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { 
	Button, 
	SelectControl,
	Spinner,
	FormTokenField
} from '@wordpress/components';
import { plus, trash } from '@wordpress/icons';
import { useApi } from '../hooks/useApi';

/**
 * Single Condition Row component.
 *
 * @param {Object}   props                   Component props.
 * @param {Object}   props.condition         Condition data.
 * @param {number}   props.index             Condition index.
 * @param {Array}    props.conditionTypes    Available condition types.
 * @param {Function} props.onChange          Change handler.
 * @param {Function} props.onRemove          Remove handler.
 * @return {JSX.Element} ConditionRow component.
 */
const ConditionRow = ( { condition, index, conditionTypes, onChange, onRemove } ) => {
	const { fetchApi } = useApi();
	const [ options, setOptions ] = useState( [] );
	const [ isLoadingOptions, setIsLoadingOptions ] = useState( false );

	useEffect( () => {
		if ( condition.condition ) {
			loadOptions( condition.condition );
		}
	}, [ condition.condition ] );

	const loadOptions = async ( conditionType ) => {
		const type = conditionTypes.find( t => t.value === conditionType );
		if ( ! type?.hasOptions ) {
			setOptions( [] );
			return;
		}

		setIsLoadingOptions( true );
		const data = await fetchApi( `/templates/conditions/options?condition=${ conditionType }` );
		if ( data ) {
			setOptions( data );
		}
		setIsLoadingOptions( false );
	};

	const handleTypeChange = ( newType ) => {
		onChange( index, { ...condition, type: newType } );
	};

	const handleConditionChange = ( newCondition ) => {
		onChange( index, { ...condition, condition: newCondition, value: '' } );
	};

	const handleValueChange = ( newValue ) => {
		onChange( index, { ...condition, value: newValue } );
	};

	return (
		<div className="mpd-condition-row">
			<SelectControl
				value={ condition.type }
				options={ [
					{ value: 'include', label: __( 'Include', 'magical-products-display' ) },
					{ value: 'exclude', label: __( 'Exclude', 'magical-products-display' ) },
				] }
				onChange={ handleTypeChange }
				__nextHasNoMarginBottom
			/>
			<SelectControl
				value={ condition.condition }
				options={ [
					{ value: '', label: __( 'Select condition...', 'magical-products-display' ) },
					...conditionTypes.map( type => ( {
						value: type.value,
						label: type.label,
					} ) ),
				] }
				onChange={ handleConditionChange }
				__nextHasNoMarginBottom
			/>
			{ condition.condition && (
				<div className="mpd-condition-value">
					{ isLoadingOptions ? (
						<Spinner />
					) : options.length > 0 ? (
						<FormTokenField
							value={ Array.isArray( condition.value ) ? condition.value : ( condition.value ? [ condition.value ] : [] ) }
							suggestions={ options.map( o => o.label ) }
							onChange={ ( tokens ) => {
								// Convert labels back to values
								const values = tokens.map( token => {
									const option = options.find( o => o.label === token );
									return option ? option.value : token;
								} );
								handleValueChange( values );
							} }
							placeholder={ __( 'Select values...', 'magical-products-display' ) }
							__nextHasNoMarginBottom
						/>
					) : (
						<span className="mpd-condition-all">
							{ __( 'Applies to all', 'magical-products-display' ) }
						</span>
					) }
				</div>
			) }
			<Button
				icon={ trash }
				isDestructive
				onClick={ () => onRemove( index ) }
				label={ __( 'Remove condition', 'magical-products-display' ) }
			/>
		</div>
	);
};

/**
 * Condition Builder component.
 *
 * @param {Object}   props             Component props.
 * @param {Array}    props.conditions  Current conditions.
 * @param {Function} props.onChange    Change handler.
 * @return {JSX.Element} ConditionBuilder component.
 */
const ConditionBuilder = ( { conditions = [], onChange } ) => {
	const { fetchApi, isLoading } = useApi();
	const [ conditionTypes, setConditionTypes ] = useState( [] );

	useEffect( () => {
		loadConditionTypes();
	}, [] );

	const loadConditionTypes = async () => {
		const data = await fetchApi( '/templates/conditions/types' );
		if ( data ) {
			setConditionTypes( data );
		}
	};

	const addCondition = () => {
		onChange( [
			...conditions,
			{
				type: 'include',
				condition: '',
				value: '',
			},
		] );
	};

	const updateCondition = ( index, newCondition ) => {
		const updated = [ ...conditions ];
		updated[ index ] = newCondition;
		onChange( updated );
	};

	const removeCondition = ( index ) => {
		onChange( conditions.filter( ( _, i ) => i !== index ) );
	};

	if ( isLoading && conditionTypes.length === 0 ) {
		return (
			<div className="mpd-condition-builder-loading">
				<Spinner />
			</div>
		);
	}

	return (
		<div className="mpd-condition-builder">
			<div className="mpd-condition-builder-header">
				<h4>{ __( 'Display Conditions', 'magical-products-display' ) }</h4>
				<p className="description">
					{ __( 'Set when this template should be displayed. Leave empty to display for all pages of this type.', 'magical-products-display' ) }
				</p>
			</div>
			
			<div className="mpd-condition-list">
				{ conditions.length === 0 ? (
					<p className="mpd-condition-empty">
						{ __( 'No conditions set. This template will display for all pages of this type.', 'magical-products-display' ) }
					</p>
				) : (
					conditions.map( ( condition, index ) => (
						<ConditionRow
							key={ index }
							condition={ condition }
							index={ index }
							conditionTypes={ conditionTypes }
							onChange={ updateCondition }
							onRemove={ removeCondition }
						/>
					) )
				) }
			</div>

			<Button
				variant="secondary"
				icon={ plus }
				onClick={ addCondition }
				className="mpd-condition-add-btn"
			>
				{ __( 'Add Condition', 'magical-products-display' ) }
			</Button>
		</div>
	);
};

export default ConditionBuilder;
