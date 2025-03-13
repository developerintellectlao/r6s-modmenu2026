import styled from '@emotion/styled'
import { FormFileUpload } from '@wordpress/components'
import Icons from './assets/Icons'
import { useRef } from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import _ from 'lodash'

const FileUploadStyle = styled.div`
	.components-button {
		min-height: 43px;
		display: flex;
		align-items: center;
		justify-content: center;
		width: 100%;
		font-size: 14px;
		line-height: 18.6px;
		padding: 10px 16px;
		border: none;
		background-color: var(--cw__background-color);
		color: var(--cw__secondary-color);
		gap: 8px;
		cursor: pointer;
		border-radius: var(--cw__border-radius);
		transition: var(--cw__transition);
		background-image: none;
		svg {
			font-size: 24px;
			width: 1em;
			height: 1em;
			fill: none;
		}
		&:hover {
			background-color: var(--cw__secondary-color);
			color: #ffffff;
		}
	}
	.cw__media-preview {
		text-align: center;
		border-radius: var(--cw__border-radius);
		border: 2px dashed var(--cw__secondary-color);
		position: relative;
		padding: 16px;
		img {
			max-width: 100%;
			border-radius: var(--cw__border-radius);
			margin: 0 auto;
		}
		.cw__media-cancel-button {
			border-radius: 50%;
			color: #ff3e60;
			background: none;
			border: none;
			padding: 0;
			cursor: pointer;
			position: absolute;
			right: 0;
			top: 0;
			transform: translate(50%, -50%);
			z-index: 1;
			svg {
				width: 16px;
				height: 16px;
			}
			&:hover {
				outline: 1px solid #ff3e60;
				outline-offset: 2px;
			}
		}
		.cw__media-replace-button {
			border-radius: var(--cw__border-radius);
			color: var(--cw__secondary-color);
			position: absolute;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			background: rgba(255, 255, 255, 0.8);
			border: none;
			cursor: pointer;
			visibility: hidden;
			opacity: 0;
			transition: var(--cw__transition);
			display: flex;
			justify-content: center;
			align-items: center;
			svg {
				width: 14px;
				height: 15px;
			}
		}
		&:hover {
			.cw__media-replace-button {
				visibility: visible;
				opacity: 1;
			}
		}
	}
`

const FileUpload = ({ value, buttonLabel, wpMediaUploader = null, ...props }) => {
	const frameRef = useRef(null)

	const handleChange = () => {
		const attachment = frameRef.current.state().get('selection').first().toJSON()

		const { sizes, id, width, height } = attachment

		let url = sizes.full.url
		if (width < 700) {
			url = _.maxBy(Object.values(_.omit(sizes, 'large')), 'width').url
		}

		const _value = {
			...(props.value || {}),
			attachment_id: attachment.id,
			url: url,
		}

		typeof props.onChange === 'function' && props.onChange(_value)
		frameRef.current.close()
	}

	const handleFrameClose = () => {
		typeof wpMediaUploader.onFrameClose === 'function' && wpMediaUploader.onFrameClose()
	}

	const handleClick = () => {
		frameRef.current = wp.media({
			button: {
				text: 'Select',
				close: false,
			},
			states: [
				new wp.media.controller.Library({
					title: __('Select logo', 'rishi'),
					library: wp.media.query({
						type: wpMediaUploader.mediaType || 'image',
					}),
					multiple: false,
					date: false,
					priority: 20,
					suggestedWidth: wpMediaUploader.media.width,
					suggestedHeight: wpMediaUploader.media.height,
				}),
			],
		})

		frameRef.current.on('select', handleChange)
		frameRef.current.on('close', handleFrameClose)

		frameRef.current.setState('library').open()

		typeof wpMediaUploader.onClick === 'function' && wpMediaUploader.onClick(frameRef.current)
	}

	const handleRemoveImage = () => {
		const _value = {
			url: '',
			id: '',
			attachment_id: ''
		}

		typeof props.onChange === 'function' && props.onChange(_value)
	}

	let _value;
	if (value) {
		({ _value } = value);
	}

	return (
		<FileUploadStyle>
			{_value?.url && (
				<div className="cw__media-preview">
					<img src={_value.url} />
					<button type="button" onClick={handleRemoveImage} className="cw__media-cancel-button">
						{Icons.timesCircle}
					</button>
					<button type="button" className="cw__media-replace-button" onClick={handleClick}>
						{Icons.plus}
					</button>
				</div>
			)}
			{!_value?.url && <>
				{
					(wpMediaUploader && (
						<button type="button" className="components-button" onClick={handleClick}>
							{Icons.upload}
							{buttonLabel}
						</button>
					)) || (
						<FormFileUpload {...props}>
							{Icons.upload}
							{buttonLabel || 'Upload'}
						</FormFileUpload>
					)
				}
			</>}
		</FileUploadStyle>
	)
}

export default FileUpload
