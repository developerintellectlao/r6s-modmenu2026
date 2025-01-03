import { Icons, RangeSlider, SelectButtonGroup, SingleColorPicker } from '@components'
import { ImagePickerControl as ImagePicker, SpacingControl } from '@controls'
import { SwitchControl, TitleControl } from '@customizer/controls/control-components'
import { TabPanel } from '@wordpress/components'
import { useDispatch, useSelect } from '@wordpress/data'
import { PluginSidebar } from '@wordpress/edit-post'
import { __ } from '@wordpress/i18n'
import { registerPlugin } from '@wordpress/plugins'
import { useCallback } from 'react'
import 'tippy.js/animations/scale.css'
import 'tippy.js/animations/shift-away.css'
import 'tippy.js/dist/tippy.css'
import './postMeta.scss'
import { applyFilters } from '@wordpress/hooks';
import { centered, defaultsidebar, leftsidebar, nosidebar, rightsidebar } from './images'

const toggleControls = [
	{ label: __('Disable Featured Image', 'rishi'), metaKey: 'disable_featured_image', type: 'all' },
	{ label: __('Disable Post Tags', 'rishi'), metaKey: 'disable_post_tags', type: 'post' },
	{ label: __('Disable Author Box', 'rishi'), metaKey: 'disable_author_box', type: 'post' },
	{ label: __('Disable Posts Navigation', 'rishi'), metaKey: 'disable_posts_navigation', type: 'post' },
	{ label: __('Disable Comments', 'rishi'), metaKey: 'disable_comments', type: 'all' },
	{ label: __('Disable Related Posts', 'rishi'), metaKey: 'disable_related_posts', type: 'post' },
	{ label: __('Disable Header', 'rishi'), metaKey: 'disable_header', type: 'all' },
	{ label: __('Disable Footer', 'rishi'), metaKey: 'disable_footer', type: 'all' },
]

// Function to decode the JSON-encoded data
const decodePostMeta = (value) => {
	try {
		return JSON.parse(value)
	} catch (e) {
		return {}
	}
}

// Function to encode the object as a JSON-encoded string
const encodePostMeta = (value) => JSON.stringify(value)

const MetaControlGroup = () => {
	const editPost = useDispatch('core/editor').editPost
	const postType = useSelect((select) => select('core/editor').getCurrentPostType())
	const meta = useSelect((select) => select('core/editor').getEditedPostAttribute('meta'))
	const pageTitle = meta?.page_title_panel
	const styleSource = meta?.content_style_source
	const transparent_header = meta?.has_transparent_header
	const verticalSpacing = meta?.vertical_spacing_source

	const updateMeta = useCallback((key, value) => {
		editPost({ meta: { [key]: value } })
	}, [editPost]);

	const generateToggleControl = (label, metaKey) => (
		<div className="rishi-control" data-design="inline" data-divider="top" key={metaKey}>
			<div className="rishi-control_header">
				<label>{label}</label>
			</div>
			<SwitchControl title={label} value={meta?.[metaKey] ?? 'no'} onChange={(isChecked) => updateMeta(metaKey, isChecked)} />
		</div>
	)

	const generateSelectButtonGroup = (metaKey, defaultValue, options) => (
		<SelectButtonGroup key={metaKey} value={meta?.[metaKey] || defaultValue} onChange={(value) => updateMeta(metaKey, value)} options={options} />
	)

	const generateElements = (postType) => {
		const title = postType === 'post' ? __('Post Elements', 'rishi') : postType === 'page' ? __('Page Elements', 'rishi') : null
		const controls = postType === 'post' ? toggleControls : toggleControls.filter(({ type }) => type === 'all')
		return (
			<>
				<TitleControl option={{ label: title }} />
				{controls.map(({ label, metaKey }) => generateToggleControl(label, metaKey))}
			</>
		)
	}

	// Use decodePostMeta to retrieve the content_area_padding data
	const contentAreaPadding = decodePostMeta(
		postType === 'post' ? meta?.single_post_boxed_content_spacing : meta?.single_page_boxed_content_spacing
	)

	const contentAreaBorderRadius = decodePostMeta(
		postType === 'post' ? meta?.single_post_content_boxed_radius : meta?.single_page_content_boxed_radius
	)

	// Update the content_area_padding using encodePostMeta
	const updateContentAreaPadding = (metaKey, newValues) => {
		editPost({ meta: { [metaKey]: encodePostMeta(newValues) } })
	}

	// Update the content_area_border_radius using encodePostMeta
	const updateContentAreaBorderRadius = (metaKey, newValues) => {
		editPost({ meta: { [metaKey]: encodePostMeta(newValues) } })
	}

	return (
		<>
			{postType === 'post' && generateToggleControl(__('Disable Breadcrumbs', 'rishi'), 'breadcrumbs_single_post')}
			{postType === 'page' && (
				<>
					<TitleControl option={{ label: __('Page Title', 'rishi') }} />
					<div className="rishi-control">
						{generateSelectButtonGroup('page_title_panel', 'inherit', [
							{ label: __('Inherit', 'rishi'), value: 'inherit' },
							{ label: __('Custom', 'rishi'), value: 'custom' },
							{ label: __('Disabled', 'rishi'), value: 'disabled' },
						])}
					</div>
					{pageTitle === 'custom' && (
						<>
							{generateToggleControl(__('Disable Breadcrumbs', 'rishi'), 'breadcrumbs_single_page')}
							<div className="rishi-control" data-divider="top">
								<div className="rishi-control_header">
									<label>{__('Horizontal Alignment', 'rishi')}</label>
								</div>
								<div className="rishi-control_wrapper">
									{generateSelectButtonGroup('single_page_alignment', 'left', [
										{ title: __('Left', 'rishi'), value: 'left', icon: Icons.leftAlignment },
										{ title: __('Center', 'rishi'), value: 'center', icon: Icons.centerAlignment },
										{ title: __('Right', 'rishi'), value: 'right', icon: Icons.rightAlignment },
									])}
								</div>
							</div>

							<div className="rishi-control" data-divider="top">
								<div className="rishi-control_header">
									<label>{__('Bottom Spacing', 'rishi')}</label>
								</div>
								<div className="rishi-control_wrapper">
									<RangeSlider
										units={[{ unit: 'px', min: 0, max: 300 }]}
										value={meta?.single_page_margin || '50px'}
										defaultUnit={'px'}
										onChange={(value) => updateMeta('single_page_margin', value)}
									/>
								</div>
							</div>
						</>
					)}
				</>
			)}
			<TabPanel
				className="post-structure-tab-panel rishi-tabs"
				activeClass="general-tab"
				tabs={[
					{ name: 'general-tab', title: __( 'General', 'rishi' ), className: 'general-tab' },
					{ name: 'design-tab', title: __( 'Design', 'rishi' ), className: 'design-tab' },
				]}
			>
				{(tab) => {
					return tab.name === 'general-tab' ? (
						<>
							<div className="rishi-control">
								<ImagePicker
									option={{
										choices: [
											{ key: 'default-sidebar', src: defaultsidebar, title: __('Inherit from Customizer', 'rishi') },
											{ key: 'right-sidebar', src: rightsidebar, title: __('Right Sidebar', 'rishi') },
											{ key: 'left-sidebar', src: leftsidebar, title: __('Left Sidebar', 'rishi') },
											{ key: 'no-sidebar', src: nosidebar, title: __('Fullwidth', 'rishi') },
											{ key: 'centered', src: centered, title: __('Fullwidth Centered', 'rishi') },
										],
									}}
									value={meta?.page_structure_type || 'default-sidebar'}
									onChange={(value) => updateMeta('page_structure_type', value)}
								/>
							</div>
							<div className="rishi-control" data-divider="top">
								<div className="rishi-control_header">
									<label>{__('Content Area Style Source', 'rishi')}</label>
								</div>
								<div className="rishi-control_wrapper">
									{generateSelectButtonGroup('content_style_source', 'inherit', [
										{ label: __('Inherit', 'rishi'), value: 'inherit' },
										{ label: __('Custom', 'rishi'), value: 'custom' },
									])}
								</div>
							</div>
							{styleSource === 'custom' && (
								<>
									<div className="rishi-control" data-divider="top">
										<div className="rishi-control_header">
											<label>{__('Content Area Style', 'rishi')}</label>
										</div>
										<div className="rishi-control_wrapper">
											{generateSelectButtonGroup('content_style', '', [
												{ label: __('Boxed', 'rishi'), value: 'boxed' },
												{ label: __('Content Boxed', 'rishi'), value: 'content_boxed' },
												{ label: __('Unboxed', 'rishi'), value: 'full_width_contained' },
											])}
										</div>
									</div>
								</>
							)}
							{postType === 'post' && generateToggleControl(__('Disable Stretch Layout', 'rishi'), 'blog_post_streched_ed')}
							{postType === 'page' && generateToggleControl(__('Disable Stretch Layout', 'rishi'), 'blog_page_streched_ed')}
							{postType === 'page' && generateToggleControl(__('Enable Transparent Header', 'rishi'), 'has_transparent_header')}
							{transparent_header === 'yes' && generateToggleControl(__('Disable on Mobile', 'rishi'), 'disable_transparent_header')}
							<div className="rishi-control" data-divider="top">
								<div className="rishi-control_header">
									<label>{__('Content Area Vertical Spacing','rishi')}</label>
								</div>
								<div className="rishi-control_wrapper">
									{generateSelectButtonGroup('vertical_spacing_source', 'inherit', [
										{ label: __('Inherit', 'rishi'), value: 'inherit' },
										{ label: __('Custom', 'rishi'), value: 'custom' },
									])}
								</div>
							</div>

							{verticalSpacing === 'custom' && (
								<div className="rishi-control" data-divider="top">
									{generateSelectButtonGroup('content_area_spacing', 'both', [
										{ value: 'both', icon: Icons.both, title: __('Top & Bottom', 'rishi') },
										{ value: 'top', icon: Icons.top, title: __('Top Only', 'rishi') },
										{ value: 'bottom', icon: Icons.bottom, title: __('Bottom Only', 'rishi') },
										{ value: 'none', icon: Icons.noneAlignment, title: __('None', 'rishi') },
									])}
								</div>
							)}
						</>
					) : tab.name === 'design-tab' ? (
						<>
							{!(styleSource === 'custom' && meta?.content_style === 'full_width_contained') && (
								<>
									<div className="rishi-control" data-design="inline" data-divider="top">
										<div className="rishi-control_header">
											<label>{__('Content Area Background Color', 'rishi')}</label>
										</div>
										<div className="color-picker">
											<SingleColorPicker
												colorPalette={false}
												value={
													(postType === 'post'
														? meta?.single_post_content_background
														: meta?.single_page_content_background) || '#f4f4f4'
												}
												defaultValue="#f4f4f4"
												onChange={(value) =>
													updateMeta(
														postType === 'post' ? 'single_post_content_background' : 'single_page_content_background',
														value
													)
												}
											/>
										</div>
									</div>
									<div className="rishi-control" data-design="block" data-divider="top">
										<div className="rishi-control_header">
											<label>{__('Content Area Padding', 'rishi')}</label>
										</div>
										<div className="rishi-control_wrapper">
											<SpacingControl
												value={{
													bottom: contentAreaPadding.bottom,
													left: contentAreaPadding.left,
													linked: contentAreaPadding.linked || false,
													right: contentAreaPadding.right,
													top: contentAreaPadding.top,
													unit: contentAreaPadding.unit || 'px',
												}}
												onChange={(newValues) => {
													updateContentAreaPadding(
														postType === 'post' ? 'single_post_boxed_content_spacing' : 'single_page_boxed_content_spacing',
														newValues
													)
												}}
												label=''
											/>
										</div>


									</div>
									<div className="rishi-control" data-design="block" data-divider="top">
										<div className="rishi-control_header">
											<label>{__('Content Area Border Radius', 'rishi')}</label>
										</div>
										<div className="rishi-control_wrapper">
											<SpacingControl
												value={{
													bottom: contentAreaBorderRadius.bottom,
													left: contentAreaBorderRadius.left,
													linked: contentAreaBorderRadius.linked || false,
													right: contentAreaBorderRadius.right,
													top: contentAreaBorderRadius.top,
													unit: contentAreaBorderRadius.unit || 'px',
												}}
												onChange={(newValues) => {
													updateContentAreaBorderRadius(
														postType === 'post' ? 'single_post_content_boxed_radius' : 'single_page_content_boxed_radius',
														newValues
													)
												}}
												label=''
											/>
										</div>
									</div>
								</>
							)}
						</>
					) : null
				}}
			</TabPanel>
			{generateElements(postType)}
			{applyFilters(
				"rishi_advanced_sidebars",
				updateMeta,
				null,
			)}
		</>
	)
}
if (window.pagenow === 'post' || window.pagenow === 'page') {
	registerPlugin('rishi', {
		render: () => (
			<PluginSidebar
				name="rishi"
				icon={
					<svg className="svg-cb-icon" width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
						<rect width="48" height="48" fill="#2355D3" />
						<path
							d="M28.3032 23.5649L30.0625 22.799L34.0585 25.936C35.9599 24.8592 37.2416 22.8273 37.2416 20.5014V20.4986C37.2416 17.0536 34.4308 14.2529 30.9606 14.2529H22.8577H20.4164H17.2759C16.7842 14.2529 16.3891 14.6486 16.3891 15.1347C16.3891 15.6236 16.787 16.0164 17.2759 16.0164H19.1317C19.6234 16.0164 20.0185 16.4121 20.0185 16.8981C20.0185 17.3871 19.6206 17.7799 19.1317 17.7799H15.0476C14.556 17.7799 14.1609 18.1755 14.1609 18.6616C14.1609 19.1505 14.5588 19.5434 15.0476 19.5434H19.5296C20.0213 19.5434 20.4164 19.939 20.4164 20.4251C20.4164 20.914 20.0185 21.3068 19.5296 21.3068H17.2759C16.7842 21.3068 16.3891 21.7025 16.3891 22.1886C16.3891 22.6775 16.787 23.0703 17.2759 23.0703H17.6595C18.1512 23.0703 18.5463 23.466 18.5463 23.9521V23.9549C18.5463 24.4438 18.1484 24.8366 17.6595 24.8366H12.6319C12.1402 24.8366 11.7451 25.2323 11.7451 25.7184C11.7451 26.2073 12.143 26.6001 12.6319 26.6001H18.9413C19.433 26.6001 19.8281 26.9958 19.8281 27.4818C19.8281 27.9679 19.4302 28.3636 18.9413 28.3636H17.2759C16.7842 28.3636 16.3891 28.7592 16.3891 29.2453C16.3891 29.7342 16.787 30.1271 17.2759 30.1271H17.7334C18.2251 30.1271 18.6202 30.5227 18.6202 31.0088V31.0116C18.6202 31.5005 18.2223 31.8934 17.7334 31.8934H15.6672C15.1755 31.8934 14.7805 32.289 14.7805 32.7751C14.7805 33.264 15.1784 33.6568 15.6672 33.6568H20.4192H22.8577H37.1734L28.3288 26.7471L26.7571 25.5149L25.7766 26.7471L24.3896 28.4964L22.8549 27.2897L22.1643 26.7471L21.1013 25.9162L22.8549 23.709L24.8074 21.2447L28.235 16.9179L31.529 19.501L29.3491 22.2451L28.3032 23.5649Z"
							fill="white"
						/>
						<path
							d="M11.5573 16.0022H13.2853C13.7713 16.0022 14.1664 15.6093 14.1664 15.1261C14.1664 14.6428 13.7713 14.25 13.2853 14.25H11.5573C11.0713 14.25 10.6763 14.6428 10.6763 15.1261C10.6763 15.6122 11.0713 16.0022 11.5573 16.0022Z"
							fill="white"
						/>
						<path
							d="M11.5573 23.0703H13.2853C13.7713 23.0703 14.1664 22.6775 14.1664 22.1942C14.1664 21.7109 13.7713 21.3181 13.2853 21.3181H11.5573C11.0713 21.3181 10.6763 21.7109 10.6763 22.1942C10.6763 22.6775 11.0713 23.0703 11.5573 23.0703Z"
							fill="white"
						/>
						<path
							d="M13.1062 28.386H11.3811C10.8951 28.386 10.5 28.7788 10.5 29.2621V29.2762C10.5 29.7595 10.8951 30.1523 11.3811 30.1523H13.1091C13.5951 30.1523 13.9901 29.7595 13.9901 29.2762V29.2621C13.9873 28.7788 13.5922 28.386 13.1062 28.386Z"
							fill="white"
						/>
						<path
							d="M37.1703 33.6879H33.3108L26.5721 28.7395L24.4036 31.5599L21.1011 29.2171V25.9162L22.164 26.7471L24.3894 28.4992L25.7763 26.7471L26.7569 25.5149L28.3286 26.7471L37.1703 33.6879Z"
							fill="white"
						/>
					</svg>
				}
				title={('post' === window.pagenow) ? __('Rishi Post Settings', 'rishi') : __('Rishi Page Settings', 'rishi')}
			>
				<div className="rishi-sidebar-content">
					<MetaControlGroup />
				</div>
			</PluginSidebar>
		),
	})
}
