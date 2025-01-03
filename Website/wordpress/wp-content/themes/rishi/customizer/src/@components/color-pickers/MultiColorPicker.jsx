import styled from "@emotion/styled";
import SingleColorPicker from "./SingleColorPicker";

const MultiColorPickerStyle = styled.div`
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
`;

const MultiColorPicker = ({ colors, value, onChange, ...ControlGroup }) => {
	return (
		<MultiColorPickerStyle>
			{colors?.map(({ title, name, colorPalette }, i) => {
				return (
					<SingleColorPicker
						key={i}
						value={value[name]}
						title={title}
						colorPalette={colorPalette}
						onChange={(_color) => onChange({ ...value, [name]: _color })}
						{...ControlGroup}
					/>
				);
			})}
		</MultiColorPickerStyle>
	);
};

export default MultiColorPicker