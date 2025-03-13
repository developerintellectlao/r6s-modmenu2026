import { SelectButtonGroup } from '@components';
import {Icons} from '@components';

const separatorIcons = {
	dot: Icons.dot,
	'normal-slash': Icons.dash,
	pipe: Icons.pipe,
	'back-slash': Icons.slash
}

const SeparatorControl = ({ value, option, onChange }) => {

	const _options = Object.entries(option.choices).map(([key, val]) => {
		return { value: key, icon: separatorIcons[key], title: val }
	})

	return (
		<SelectButtonGroup
			options={_options}
			onChange={onChange}
			value={value}
			id={option.id}
			view={option.view}
			size="sm"
		/>
	)
}

export default SeparatorControl
