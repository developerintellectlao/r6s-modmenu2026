import { Border } from '@components';
const root = document.querySelector(':root');

const BorderControl = ({ value, onChange, option }) => {
	const { color: { color: borderColor, hover } } = value;
	const _color = borderColor ? (borderColor.match(/^var\(.+\)$/)?.[0]
		? getComputedStyle(root).getPropertyValue(borderColor.replace(/var\(/, '').replace(/\)/, ''))
		: borderColor) : '';
	const hoverColor = hover ? (hover.match(/^var\(.+\)$/)?.[0]
		? getComputedStyle(root).getPropertyValue(hover.replace(/var\(/, '').replace(/\)/, ''))
		: hover) : '';
	const _value = { ...value, color: { color: _color, hover: hoverColor } }
	return (
		<Border value={_value} onChange={(val) => onChange(val)} option={option} />
	);
};

export default BorderControl;
