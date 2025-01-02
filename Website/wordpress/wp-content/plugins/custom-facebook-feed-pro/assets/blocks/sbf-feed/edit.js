import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { Disabled } from '@wordpress/components';
import { Fragment, useEffect } from '@wordpress/element';
import ServerSideRender from '@wordpress/server-side-render';
import BlockInspector from './inspector';
import './editor.scss';
import { FeedCTA, FacebookPreview} from '@awesomemotive/blocks';

const Edit = (props) => {
	const { attributes, className } = props;
	const blockProps = useBlockProps();
	const { feedId, preview } = attributes;

	const feeds = cff_feed_block_editor?.feeds || [];
	const feedsOptions = [{ label: __('Select Facebook feed', 'custom-facebook-feed'), value: '' }];
	feeds.forEach((feed) => {
		feedsOptions.push({ label: feed.feed_name, value: feed.id });
	});

	useEffect(() => {
		window.cff = true;
        window.cffGutenberg = true;
		setTimeout(function() { if (typeof cff_init !== 'undefined') {cff_init();}},1000);
		setTimeout(function() { if (typeof cff_init !== 'undefined') {cff_init();}},2000);
		setTimeout(function() { if (typeof cff_init !== 'undefined') {cff_init();}},3000);
		setTimeout(function() { if (typeof cff_init !== 'undefined') {cff_init();}},5000);
		setTimeout(function() { if (typeof cff_init !== 'undefined') {cff_init();}},10000);
	}, [feedId]);

	if (preview) {
		return (
			<FacebookPreview />
		);
	}

	return (
		<div className={className} {...blockProps}>

			{!feedId && (
				<FeedCTA
					id={'facebook'}
					feeds={feeds}
					feedsOptions={feedsOptions}
					createFeedUrl={cff_feed_block_editor?.feed_url}
					isPro={cff_feed_block_editor?.is_pro_active}
					{...props}
				/>
			)}

			{feedId && feeds && feeds.length > 0 && (
				<Fragment>
					<BlockInspector {...props} />
					<Disabled>
						<ServerSideRender
							block="smashballoon/facebook-feed"
							attributes={attributes}
							urlQueryArgs={{ _locale: 'site' }}
						/>
					</Disabled>
				</Fragment>
			)}

		</div>

	);
};

export default Edit;
