import { RangeSlider } from '@components'

const SliderControl = ({ option, value, onChange }) => {
	const units = option?.units || []

	return <RangeSlider units={units} value={value} onChange={onChange} />
}

export default SliderControl
