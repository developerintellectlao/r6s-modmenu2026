import styled from "@emotion/styled";
import { InputNumber, Select, SingleColorPicker, Spacing } from "./";
import Icons from "./assets/Icons";
import { InnerControlWrapper, Popover, PopoverButton } from "./components";
import { __ } from '@wordpress/i18n'

const BorderStyle = styled.div`
  display: inline-flex;
  align-items: center;
  gap: 8px;
`;

const PopoverContent = ({
	value,
	onChange,
	units,
}) => {
	const { width: borderWidth, style: borderStyle, borderRadius } = value;
	return (
		<>
			<InnerControlWrapper label={__('Border Width', 'Rishi')} direction="horizontal">
				<InputNumber
					value={borderWidth}
					onChange={(_width) => onChange({ ...value, width: _width })}
					min="0"
					max="10"
				/>
			</InnerControlWrapper>
			<InnerControlWrapper label={__('Border Style', 'Rishi')}>
				<Select
					options={[
						{ value: "none", label: __('None', 'Rishi'), icon: Icons.none },
						{ value: "solid", label: __('Solid', 'Rishi'), icon: Icons.minus },
						{ value: "dashed", label: __('Dash', 'Rishi'), icon: Icons.dashed },
						{ value: "double", label: __('Double', 'Rishi'), icon: Icons.menu },
						{ value: "dotted", label: __('Dot', 'Rishi'), icon: Icons.ellipsis },
					]}
					value={borderStyle}
					onChange={(_style) => onChange({ ...value, style: _style })}
				/>
			</InnerControlWrapper>
			{borderRadius && <InnerControlWrapper label={__('Border Radius', 'Rishi')}>
				<Spacing
					value={borderRadius}
					units={units}
					onChange={(_radius) => onChange({ ...value, borderRadius: _radius })}
				/>
			</InnerControlWrapper>}
		</>
	);
};

const Border = ({
	colorPalette,
	changed,
	value,
	onChange,
	...ControlGroup
}) => {
	return (
		<BorderStyle>
			{value?.color?.color && <SingleColorPicker
				colorPalette={colorPalette}
				title={__('Initial', 'Rishi')}
				value={value?.color?.color}
				onChange={(_color) => onChange({ ...value, color: { ...value.color, color: _color } })}
			/>}
			{value?.color?.hover && <SingleColorPicker
				colorPalette={colorPalette}
				title={__('Hover', 'Rishi')}
				value={value?.color?.hover}
				onChange={(_color) => onChange({ ...value, color: { ...value.color, hover: _color } })}
			/>}
			<Popover
				content={
					<PopoverContent value={value} onChange={onChange} {...ControlGroup} />
				}
			>
				<PopoverButton changed={changed} />
			</Popover>
		</BorderStyle>
	);
};

export default Border
