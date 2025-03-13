import { Text } from '@components'
const TextControl = ({ value, option, onChange }) => {
	return (
		<Text
			label={option.label ?? ''}
			id={option.id}
			value={value}
			onChange={onChange}
			dataType={option?.type}
		/>
	)
}
export default TextControl
