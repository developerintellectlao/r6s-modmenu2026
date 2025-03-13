import { useRef, useState, useEffect } from '@wordpress/element'
import { flattenOptionsWithValues } from '@helpers'
import { PanelProvider } from './context'
import ControlsContainer from './ControlsContainer'

const ControlsPanel = ({ option, ...props }) => {
	const [values, setValues] = useState(null)
	const containerRef = useRef()

	const innerOptions = flattenOptionsWithValues({ ...option })

	useEffect(() => {
		let _value = {}
		Object.keys(innerOptions).forEach((id) => {
			_value[id] = wp.customize(id) && wp.customize(id)()
		})
		setValues(_value)
	}, [])

	const handleChange = (key, value) => {
		let _value = value
		setTimeout(() => {
			setValues((_values) => ({
				...values, [key]: value,
			}))
		})

		if (['header_builder_key_placement', 'footer_builder_key_placement'].includes(key)) {
			_value = { ...value, lastUpdated: Date.now() }
		}
		wp.customize(key)?.(_value)
	}

	return (
		<div ref={containerRef} className="rishi-controls-container block relative bg-white">
			<PanelProvider containerRef={containerRef} values={values}>
				{values && <ControlsContainer
					purpose="customizer"
					onChange={handleChange}
					options={option['innerControls']}
					value={values}
				/> || null}
			</PanelProvider>
		</div>
	)
}

export default ControlsPanel
