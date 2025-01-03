import { Icons, SelectButtonGroup } from "@components";

const devices = {
	desktop: Icons.desktop,
	tablet: Icons.tablet,
	mobile: Icons.mobile
}

const titles = {
	desktop: "Desktop",
	tablet: "Tablet",
	mobile: "Mobile"
}

const VisibilityControl = ({
	option,
	value,
	onChange,
}) => {
	const _options = option.choices.map(op => ({ value: op.key, icon: devices[op.key], title: titles[op.key] }))
	const _value = Object.entries(value).map(([key, value]) => value && key);

	const handleOnChange = (arr) => {
		onChange({
			desktop: arr.find(a => a === "desktop"),
			tablet: arr.find(a => a === "tablet"),
			mobile: arr.find(a => a === "mobile")
		})
	}
	return <SelectButtonGroup
		value={_value}
		onChange={v => handleOnChange(v)}
		size="xl"
		options={_options}
		isMultiple
	/>
};

VisibilityControl.hiddenResponsive = true;

export default VisibilityControl;
