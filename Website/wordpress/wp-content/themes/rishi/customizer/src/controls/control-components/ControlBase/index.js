import { createContext, useContext, useRef } from '@wordpress/element'

import { __, sprintf } from '@wordpress/i18n'
import * as controls from '../../control-components'

import { useDeviceView } from '../ControlsContainer/context'
import { OptionParser } from './OptionParser'
import { withCompactDesign, withCompactHeader } from './Variations'

const controlContext = createContext({})
export const useControlContext = () => useContext(controlContext)

const getControlComponent = (controlName) => {
	if (controls[controlName]) {
		const ControlComponent = controls[controlName]
		return ControlComponent
	}

	const FallbackComponent = () => <div dangerouslySetInnerHTML={{ __html: sprintf(__('"%s" component not found (404).'), controlName) }} />

	FallbackComponent.fallback = true

	return FallbackComponent
}

const ControlBase = (props) => {

	const { option, value, values, onChange, onChangeFor, hasRevertButton, id } = props

	const childComponentRef = useRef(null)

	const [device] = useDeviceView()

	const Option = new OptionParser(option, device)

	const controlSetting = Option.getOption()

	const ControlComponent = getControlComponent(controlSetting.control)

	/**
	 * Fallback Component
	 */
	if (ControlComponent.fallback) {
		return <ControlComponent />
	}

	function udpatePreviewer() {
		if (controlSetting.triggerRefreshOnChange) {
			wp.customize && wp.customize.previewer && wp.customize.previewer.refresh()
		}

		if (controlSetting.switchDeviceOnChange && wp.customize && wp.customize.previewedDevice() !== controlSetting.switchDeviceOnChange) {
			wp.customize.previewedDevice.set(controlSetting.switchDeviceOnChange)
		}

		if (
			controlSetting.sync &&
			(Object.keys(controlSetting.sync).length > 0 || Array.isArray(controlSetting.sync)) &&
			wp.customize &&
			wp.customize.previewer
		) {
			wp.customize.previewer.send('ct:sync:refresh_partial', {
				id: controlSetting.sync.id || controlSetting.id,
				option: controlSetting,
			})
		}
	}

	function handleChange(raw = false) {
		return (_value) => {
			if (!Option.isResponsive()) {
				return onChange(_value)
			}

			Option.setDevice(device)
			let responsiveValue = {
				...value,
				[device]: _value,
			}

			udpatePreviewer()

			onChange(responsiveValue)
		}
	}

	let config = {
		design: 'block',
		label: true,
		wrapperAttr: {},
		showRevertButton:
			hasRevertButton &&
			!controlSetting.disableRevertButton &&
			!['ImagePickerControl', 'LayersControl', 'ImageUploaderControl', 'Panel', 'ControlsGroup', 'SwitchControl'].includes(controlSetting.control),
		showResponsiveControls: (controlSetting.responsive || controlSetting.option?.value?.desktop) ?? false,
		computeOptionValue: (value) => value,
		...(ControlComponent.config || {}),
	}

	config.design = props.option.design || config.design

	const responsiveValue = Option.getValueForDevice(value)

	const controlProps = {
		key: id,
		value: responsiveValue,
		id: id,
		values: values,
		onChangeFor: onChangeFor,
		device: device,
		onChange: handleChange(),
		option: controlSetting,
	}

	switch (config.design) {
		case !config.design:
		case 'none':
			return <ControlComponent {...controlProps} />
		case 'compact':
			return withCompactDesign(ControlComponent)({ ...controlProps, config })
		default:
			const WrappedControl = withCompactHeader(ControlComponent)
			if (ControlComponent.MetaWrapper) {
				return (
					<ControlComponent.MetaWrapper
						id={id}
						option={controlSetting}
						value={responsiveValue}
						onChangeFor={onChangeFor}
						values={values}
					>{(props) => withCompactHeader(ControlComponent)({ ...controlProps, ...props, config })}</ControlComponent.MetaWrapper>
				)
			}

			return WrappedControl({ ...controlProps, config })
	}
}

export default ControlBase
