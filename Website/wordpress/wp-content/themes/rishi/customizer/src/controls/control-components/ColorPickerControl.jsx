import {ColorPicker} from '@components';
import styled from '@emotion/styled';

const MultiColorPickerStyle = styled.div`
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
`;

function ColorControl({ option, value, onChange, values }) {
	const root = document.querySelector(':root');
	return <MultiColorPickerStyle>
		{option?.pickers?.map(({ id, title }) => {
			const color = value[id].color.match(/^var\(.+\)$/)?.[0]
				? getComputedStyle(root).getPropertyValue(value[id].color.replace(/var\(/, '').replace(/\)/, ''))
				: value[id].color;

			return (
				<ColorPicker
					key={id}
					onChange={(color) => {
						onChange({ ...value, [id]: { color } });
					}}
					value={color}
					triggerValue={value[id].color}
					title={title}
					colorPalette={option?.colorPalette}
				/>
			);
		})}
	</MultiColorPickerStyle>
}
export default ColorControl;