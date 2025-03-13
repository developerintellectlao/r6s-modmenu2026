import { ColorPalettes } from '@components'
import { useEffect } from '@wordpress/element'
import _ from 'lodash'

const ColorPalettesControl = ({ option, value, onChange }) => {
	const palettes = value.palettes.map(({ id: _id, ...colors }, index) => ({
		colors: Object.entries(colors).map(([name, color]) => ({
			name,
			color: color.color,
		})),
		name: _id,
	}))

	const palettesById = _.keyBy(palettes, 'name')

	const handleChange = (_value) => {
		const { name, colors } = _value
		let _colors = {}
		colors.forEach(({ name, color }) => {
			_colors = { ..._colors, [name]: { color: color } }
		})
		onChange({
			...value,
			current_palette: name,
			palettes: value.palettes.map((obj) => {
				if (obj.id === name) {
					return { ...obj, ..._colors }
				}
				return obj
			}),
			..._colors,
		})
	}

	// updating the color variables
	useEffect(() => {
		palettesById[value.current_palette].colors.forEach(({ color }, i) => {
			document.querySelector(':root').style.setProperty(`--paletteColor${i + 1}`, color)
		})
	}, [value])

	return <ColorPalettes colorPalettes={palettes} value={palettesById[value.current_palette]} onChange={handleChange} />
}

export default ColorPalettesControl
