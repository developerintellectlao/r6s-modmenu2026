import {InspectorControls} from '@wordpress/block-editor';
import {PanelBody, SelectControl} from '@wordpress/components';
import {__} from '@wordpress/i18n';

export default (props) => {
    const {attributes, setAttributes} = props;
    const {feedId} = attributes;

    const feeds = cff_feed_block_editor?.feeds || [];

    return (
        <InspectorControls>
            <PanelBody title={__('Feed Settings', 'custom-facebook-feed')}>
                {feedId && feeds && feeds.length > 0 && (
                    <SelectControl
                        label={__('Select a feed', 'custom-facebook-feed')}
                        value={feedId}
                        options={feeds.map((feed) => ({
                            value: feed.id,
                            label: feed.feed_name,
                        }))}
                        onChange={(feedId) => setAttributes({feedId})}
                    />
                )}
            </PanelBody>
        </InspectorControls>
    );
}