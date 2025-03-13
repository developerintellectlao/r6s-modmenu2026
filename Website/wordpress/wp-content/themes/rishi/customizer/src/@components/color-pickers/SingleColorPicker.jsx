import { Icons } from '@components'
import { Tooltip } from '@components/components'
import styled from '@emotion/styled'
import { ColorPicker } from '@wordpress/components'
import ColorPickerTrigger from './ColorPickerTrigger'

const ColorPickerHeader = styled.header`
	padding: 5px;
	border: 1px solid var(--cw__border-color);
	border-radius: var(--cw__border-radius);
	margin: 0 -4px 13px;
	.cw__color-picker-header-swatches {
		display: flex;
		align-items: center;
		flex-wrap: wrap;
		gap: 8px;
	}
	.cw__color-picker-swatch-inner {
		position: relative;
		display: flex;
		width: 25px;
		height: 25px;
		border: 1px solid #e0e3e7;
		border-radius: 50%;
		background-color: #000000;
		font-size: 0;
	}
	.checked-icon {
		position: absolute;
		width: 12px;
		height: auto;
		top: 50%;
		left: 50%;
		transform: translate(-50%, -50%);
		color: #cbcbcb;
		margin: -0.5px;
	}
`

const SingleColorPicker = ({ colorPalette, value, triggerValue, title, placement, onChange, ...props }) => {
	return (
		<ColorPickerTrigger color={triggerValue || value} title={title} placement={placement}>
			{colorPalette && (
				<ColorPickerHeader>
					<div className="cw__color-picker-header-swatches">
						{Array.apply(null, Array(8)).map((val, i) => {
							return (
								<Tooltip key={i} title={`color${i + 1}`}>
									<button
										type="button"
										className="cw__color-picker-swatch-inner"
										style={{ backgroundColor: `var(--paletteColor${i + 1})` }}
										onClick={() => onChange(`var(--paletteColor${i + 1})`)}
									>
										<span>{`color${i + 1}`}</span>
										{triggerValue === `var(--paletteColor${i + 1})` && <span className="checked-icon">{Icons.check}</span>}
									</button>
								</Tooltip>
							)
						})}
					</div>
				</ColorPickerHeader>
			)}
			<ColorPicker color={value} enableAlpha defaultValue="#000" onChange={onChange} {...props} />
		</ColorPickerTrigger>
	)
}

export default SingleColorPicker
