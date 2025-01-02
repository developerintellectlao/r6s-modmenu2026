import { registerBlockType } from '@wordpress/blocks';

/**
 * Internal dependencies
*/
import Edit from './edit';
import metadata from './block.json';

const FacebookFeed = () => {
	registerBlockType( metadata.name, {
		edit: Edit,
		save: () => null,
	} );
}

export default FacebookFeed;
