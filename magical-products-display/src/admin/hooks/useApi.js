/**
 * API hook for admin dashboard
 *
 * @package Magical_Shop_Builder
 * @since   2.0.0
 */

import { useState, useCallback } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';

/**
 * Custom hook for API calls.
 *
 * @return {Object} API methods and state.
 */
export const useApi = () => {
	const [ isLoading, setIsLoading ] = useState( false );
	const [ error, setError ] = useState( null );


	/**
	 * Make a fetch request.
	 *
	 * @param {string} endpoint API endpoint.
	 * @param {Object} options  Request options.
	 * @return {Promise<any>} Response data.
	 */
	const makeRequest = useCallback( async ( endpoint, options = {} ) => {
		setIsLoading( true );
		setError( null );

		try {
			const response = await apiFetch( {
				path: `mpd/v1${ endpoint }`,
				...options,
			} );
			
			return response;
		} catch ( err ) {
			setError( err?.message || 'An error occurred' );
			console.error( 'API Error:', err );
			return null;
		} finally {
			setIsLoading( false );
		}
	}, [] );

	/**
	 * GET request.
	 *
	 * @param {string} endpoint API endpoint.
	 * @param {Object} params   Query parameters.
	 * @return {Promise<any>} Response data.
	 */
	const fetchApi = useCallback( 
		( endpoint, params = {} ) => {
			const queryString = new URLSearchParams( params ).toString();
			const url = queryString ? `${ endpoint }?${ queryString }` : endpoint;
			
			return makeRequest( url, { method: 'GET' } );
		}, 
		[ makeRequest ] 
	);

	/**
	 * POST request.
	 *
	 * @param {string} endpoint API endpoint.
	 * @param {Object} data     Request body.
	 * @return {Promise<any>} Response data.
	 */
	const postApi = useCallback( 
		( endpoint, data = {} ) => {
			return makeRequest( endpoint, {
				method: 'POST',
				data,
			} );
		}, 
		[ makeRequest ] 
	);

	/**
	 * PUT request.
	 *
	 * @param {string} endpoint API endpoint.
	 * @param {Object} data     Request body.
	 * @return {Promise<any>} Response data.
	 */
	const putApi = useCallback( 
		( endpoint, data = {} ) => {
			return makeRequest( endpoint, {
				method: 'PUT',
				data,
			} );
		}, 
		[ makeRequest ] 
	);

	/**
	 * DELETE request.
	 *
	 * @param {string} endpoint API endpoint.
	 * @return {Promise<any>} Response data.
	 */
	const deleteApi = useCallback( 
		( endpoint ) => {
			return makeRequest( endpoint, { method: 'DELETE' } );
		}, 
		[ makeRequest ] 
	);

	return {
		isLoading,
		error,
		fetchApi,
		postApi,
		putApi,
		deleteApi,
	};
};

export default useApi;
