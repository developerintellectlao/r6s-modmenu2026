import { __ } from "@wordpress/i18n";
import Edit from './edit';
import {fauxBlocks} from '@awesomemotive/blocks';

const FauxBlocksInit = () => {
    // create a faux block for each block in fauxBlocks
    for( let block of fauxBlocks ) {
        const pluginActive = cff_feed_block_editor?.plugins_info?.[block.id]?.active || false;
        const blockExists = wp.blocks.getBlockType(block.name);
        const blockRegistered = wp.blocks.getBlockType(`smashballoon/${block.id}-feed`);

        if(pluginActive && !blockRegistered) {
            break;
        }

        if (!blockExists && !pluginActive) {
            wp.blocks.registerBlockType(block.name, {
                apiVersion: 2,
                title: block.title,
                icon: block.icon,
                description: block.description,
                category: 'smashballoon',
                textdomain: 'custom-facebook-feed',
                attributes: {
                    blockId: {
                        type: 'string'
                    },
                    preview: {
                        type: 'boolean',
                        default: false
                    }
                },
                example: {
                    attributes: {
                        preview: true
                    }
                },
                keywords: [
                    __('facebook', 'custom-facebook-feed'),
                    __('feed', 'custom-facebook-feed'),
                    __('gallery', 'custom-facebook-feed'),
                    __('photos', 'custom-facebook-feed'),
                    __('images', 'custom-facebook-feed'),
                    __('social media', 'custom-facebook-feed'),
                    __('smashballoon', 'custom-facebook-feed'),
                ],
                edit: ({attributes, isSelected, clientId}) => 
                    <Edit 
                        attributes={attributes} 
                        block={block} 
                        isSelected={isSelected} 
                        clientId={clientId}
                    />,
                save: () => null,
            });
        }
    }
}

export default FauxBlocksInit;
