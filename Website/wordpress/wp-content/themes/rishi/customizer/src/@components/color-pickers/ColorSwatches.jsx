import styled from "@emotion/styled";
import { Tooltip } from "../components";
import SingleColorPicker from "./SingleColorPicker";

const ColorPaletteSwatches = styled.div`
  padding: 10px;
  border: 1px solid var(--cw__border-color);
  border-radius: var(--cw__border-radius);
  display: flex;
  align-items: center;
  padding-right: 24px;
  position: relative;
  cursor: pointer;
  .cw__color-palette-swatches-inner {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 4px;
    .cw__control-item {
      margin: 0 !important;
    }
  }
  .cw__color-palette-swatch,
  .cw__color-picker-trigger .cw__color-picker-color-block {
    width: 25px;
    height: 25px;
    border: 1px solid var(--cw__border-color);
    border-radius: 50%;
  }
  .cw__dropdown-button-wrapper {
    position: absolute;
    top: 50%;
    right: 10px;
    transform: translateY(-50%);
  }
  .dropdown-button {
    padding: 0;
    background: none;
    border: none;
    width: 12px;
    height: 12px;
    cursor: pointer;
    color: #a3b1bf;
  }
  &.selected {
    &::after {
      content: "";
      width: 14px;
      height: 14px;
      background-image: url("data:image/svg+xml,%3Csvg width='14' height='15' viewBox='0 0 14 15' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Ccircle cx='7' cy='7.5' r='6.74' fill='%23216BDB' stroke='%23216BDB' stroke-width='0.52'/%3E%3Cg clipPath='url(%23clip0_336_1961)'%3E%3Cpath d='M5.40589 11.2598L2.44189 8.29584L3.18289 7.55484L5.40589 9.77784L10.1769 5.00684L10.9179 5.74784L5.40589 11.2598Z' fill='white'/%3E%3C/g%3E%3Cdefs%3E%3CclipPath id='clip0_336_1961'%3E%3Crect width='9.36' height='6.76' fill='white' transform='translate(2 4.5)'/%3E%3C/clipPath%3E%3C/defs%3E%3C/svg%3E%0A");
      background-size: 14px 14px;
      background-repeat: no-repeat;
      position: absolute;
      top: 50%;
      right: 10px;
      transform: translateY(-50%);
    }
  }
  &.has-dropdown {
    cursor: default;
  }
`;

const ColorSwatches = ({ children, className, colors, onChange, ...rest }) => {
	const handleOnChange = (arr) => {
		arr.sort((a, b) => {
			if (a.name > b.name) {
				return 1;
			}
			if (a.name < b.name) {
				return -1;
			}
			return 0;
		});
		onChange(arr);
	};

	return (
		<ColorPaletteSwatches
			tabIndex={0}
			className={`cw__color-palette-swatches ${className}`}
			{...rest}
		>
			<div className="cw__color-palette-swatches-inner">
				{colors?.map((color, i) => {
					if (onChange) {
						return <SingleColorPicker
							key={i}
							value={color?.color}
							title={color?.name}
							onChange={(_color) =>
								handleOnChange([
									...colors.filter((a) => a.name !== color.name),
									{
										name: color.name,
										color: _color,
									},
								])
							}
							placement="bottom"
						/>
					} else {
						return <Tooltip key={i} title={color?.name}>
							<span
								className="cw__color-palette-swatch"
								style={{ backgroundColor: color?.color }}
							></span>
						</Tooltip>
					}
				})}
			</div>
			{children}
		</ColorPaletteSwatches>
	);
};

export default ColorSwatches;
