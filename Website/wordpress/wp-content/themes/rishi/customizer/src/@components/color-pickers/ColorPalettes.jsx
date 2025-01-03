import { Popover } from "../components";
import styled from "@emotion/styled";
import Icons from "../assets/Icons";
import ColorSwatches from "./ColorSwatches";

const ColorPaletteOptions = styled.div`
  .cw__palette-label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    margin: 0 0 8px;
  }
  .cw__color-palette-option {
    &:not(:last-child) {
      margin-bottom: 13px;
    }
    .cw__color-palette-swatches-inner {
      gap: 2px;
    }
  }
`;

const ColorPalettes = ({ value, onChange, colorPalettes }) => {

	const handleSelectPalette = (_value) => (e) => {
		onChange(_value);
	};

	return (
		<ColorSwatches
			colors={value.colors}
			className="has-dropdown"
			onChange={(_colors) => onChange({ ...value, colors: _colors })}
		>
			<div className="cw__dropdown-button-wrapper">
				<Popover
					className="cw__color-palettes-popover"
					content={
						<ColorPaletteOptions className="cw__color-palette-options">
							{colorPalettes &&
								colorPalettes.map((palette, i) => {
									return (
										<div
											key={i}
											className="cw__color-palette-option"
										>
											<label className="cw__palette-label">
												{palette.name}
											</label>
											<ColorSwatches
												className={
													palette.name === value.name ? "selected" : ""
												}
												colors={palette.colors}
												onClick={handleSelectPalette(palette)}
											/>
										</div>
									);
								})}
						</ColorPaletteOptions>
					}
				>
					<button type="button" className="dropdown-button">{Icons.chevronDown}</button>
				</Popover>
			</div>
		</ColorSwatches>
	);
};

export default ColorPalettes
