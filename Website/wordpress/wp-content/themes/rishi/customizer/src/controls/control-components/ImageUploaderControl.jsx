import { __ } from '@wordpress/i18n'
import _ from 'underscore'
import { FileUpload } from '@components'

const ImageUploaderControl = ({ option, value, onChange }) => {
	const handleChange = (_value) => {
		onChange({ _value })
	}
	const label = (value?._value?.attachment_id === '' || value === '' || value == undefined) ? option.emptyLabel : option.filledLabel
	return (
		<FileUpload
			buttonLabel={label}
			label={option.label}
			value={value}
			onChange={handleChange}
			wpMediaUploader={{
				mediaType: option.mediaType || 'image',
				media: {
					width: option?.logo?.width || 'auto',
					height: option?.logo?.height || 'auto',
					skipCrop: option.skipCrop || true,
				},
			}}
		/>
	)
}

export default ImageUploaderControl
