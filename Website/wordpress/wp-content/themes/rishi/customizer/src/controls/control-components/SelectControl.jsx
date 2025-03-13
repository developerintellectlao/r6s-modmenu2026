import { Select } from '@components';
import { __ } from '@wordpress/i18n';

const SelectControl = ({ option, value, onChange }) => {

	const _options = option.choices.map(choice => ({ value: choice.key, label: choice.value }));

	return (
		<Select
			{...option}
			options={_options}
			placeholder={__('Select Option', 'rishi')}
			value={value}
			onChange={onChange}
		/>
	)
}

export default SelectControl
