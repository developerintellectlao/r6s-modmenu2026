import { __ } from '@wordpress/i18n';
import { Fragment, useCallback } from '@wordpress/element';
import { FauxBlocksModal } from '@awesomemotive/blocks';
import './editor.scss';
import { useDispatch } from '@wordpress/data';
import {store as blockEditorStore} from '@wordpress/block-editor';

const Edit = (props) => {
	const { attributes, block, isSelected, clientId } = props;
	const { preview } = attributes;
	const pluginInstalled = cff_feed_block_editor?.plugins_info?.[block.id]?.installed || false;
	block.pluginInstalled = pluginInstalled;

	const handleClick = (event) => {
		event.preventDefault();
		let loadingText = pluginInstalled ? __('Activating...', 'custom-facebook-feed') :
			__('Installing...', 'custom-facebook-feed');
		event.target.innerHTML = loadingText;

		let plugin = event.target.getAttribute( 'href' );
		let action = 'cff_install_addon';
		let loadedText = __('Installed and Activated', 'custom-facebook-feed');

		if( pluginInstalled ) {
			plugin = cff_feed_block_editor?.plugins_info?.[block.id]?.plugin_file;
			action = 'cff_activate_addon';
			loadedText = __('Activated', 'custom-facebook-feed');
		}

		let data = new FormData();
		data.append( 'action', action );
		data.append( 'nonce', cff_feed_block_editor.nonce );
		data.append( 'plugin', plugin );
		data.append( 'type', 'plugin' );

		fetch(ajaxurl, {
			method: "POST",
			credentials: 'same-origin',
			body: data
		})
		.then(response => response.json())
		.then(data => {
			if( data.success == true ) {
				event.target.innerHTML = loadedText;
				console.log( data );
			} else {
				console.log( data );
				if(data.data[0].code == 'folder_exists') {
					event.target.innerHTML = loadedText;
				}
			}
			return;
		});
	}

	if ( preview ) {
		return(
			<Fragment>
				{block?.preview}
			</Fragment>
		);
	}

	const { removeBlock } =	useDispatch( blockEditorStore );
    const remove = useCallback( () => removeBlock( clientId ), [ clientId ] );
	
	return (
		<Fragment>
			{ isSelected && 
				<FauxBlocksModal 
					block={block}
					removeBlock={remove} 
					handleInstallActivate={handleClick}
				/>
			}
		</Fragment>
	);
};

export default Edit;
