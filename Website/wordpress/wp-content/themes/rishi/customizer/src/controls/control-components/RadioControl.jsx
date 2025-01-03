import { SelectButtonGroup } from '@components';
import {Icons} from '@components';

const radioIcons = {
	both: Icons.both,
	top: Icons.top,
	bottom: Icons.bottom,
	none: Icons.noneAlignment,
	left: Icons.leftAlignment,
	center: Icons.centerAlignment,
	right: Icons.rightAlignment,
	"flex-start": Icons.leftAlignment,
	"flex-end": Icons.rightAlignment
}

const verticalAligIcons = {
	"flex-start": Icons.top,
	"flex-end": Icons.bottom,
	center: Icons.center
}

const RadioControl = ({ value, option, onChange }) => {

	const type = option.attr ? option.attr['data-type'] : "";
	const isType = type === "content-spacing" || type === "alignment" || type === "vertical-alignment" || type === "horizontal-alignment";
	const _options = Object.entries(option.choices).map(([key, val]) => {
		if (isType) {
			return { value: key, icon: type === "vertical-alignment" ? verticalAligIcons[key] : radioIcons[key], title: val }
		} else {
			return { value: key, label: val || "" }
		}
	})

	return (
		<SelectButtonGroup
			options={_options}
			onChange={onChange}
			value={value}
			id={option.id}
			view={option.view}
			size={option.size}
			{...option.attr}
		/>
	)
}

export default RadioControl
