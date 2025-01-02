import FacebookFeed from './sbf-feed';
import FauxBlocksInit from './faux-blocks';

FacebookFeed();
FauxBlocksInit();

// unregister the 'cff/cff-feed-block' block if it is not used
wp.domReady( function () {
    const cff_block_exist = cff_feed_block_editor.has_facebook_feed_block;
    if (!cff_block_exist ) {
        wp.blocks.unregisterBlockType( 'cff/cff-feed-block' );
    }
} );