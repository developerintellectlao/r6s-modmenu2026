import * as controls from '../../control-components'
import { __ } from '@wordpress/i18n'
export class OptionParser {
	constructor(option, device = 'desktop') {
		this.device = device
		this.option = option
		this.devices = {
			desktop: true,
			tablet: true,
			mobile: true,
		}
	}

	getId() {
		return this.option.id
	}

	getOption() {
		return this.option
	}

	getControl() {
		return this.option.control
	}

	getValue() {
		return this.option.value
	}

	getResponsive() {
		return this.option.responsive || false
	}

	getLabel() {
		return this.option.label
	}

	getInnerControls() {
		return this.option.innerControls || []
	}

	getSettings() {
		return this.option.settings || {}
	}
	getDescription() {
		return this.option.desc
	}

	getControlComponent() {
		return this.option.controlComponent
	}

	getValueForDevice(value) {
		if (!this.isResponsive()) return value

		const _value = this.getResponsiveValue(value || this.option.value)
		if (!_value) return _value

		return 'tablet' === this.device && !this.isDeviceEnabledForTablet() ? _value.mobile : _value[this.device]
	}

	getResponsiveValue(value, changedValue) {
		if (value && value.desktop) {
			return !this.isResponsive() ? value.desktop : value
		}
		if (!this.isResponsive()) return changedValue

		return {
			...value,
			[this.device]: changedValue,
		}
	}

	hasInnerControls() {
		return !!this.getInnerControls().length
	}

	isConditionMatches(values) {
		const conditions = this.option.conditions
		if (!conditions) return true

		return Object.keys(conditions).every((key) => {
			return values[key] === conditions[key]
		})
	}

	isResponsive() {
		return !!this.getResponsive()
	}

	isDeviceEnabled(device) {
		return { ...this.devices, ...(typeof this.option.responsive === 'boolean' ? {} : this.option.responsive || {}) }[device]
	}

	isDeviceEnabledForTablet() {
		return this.isDeviceEnabled('tablet') !== 'skip'
	}

	setDevice(device) {
		this.device = device
		return this
	}

	getControlComponent(values) {
		const controlName = this.getControl()
		if (controls[controlName]) {
			const ControlComponent = controls[controlName]

			if (!this.option.conditions) return ControlComponent

			const conditions = this.option.conditions
			const match = Object.keys(conditions).every((key) => {
				return values[key] === conditions[key]
			})
			if (match) {
				return ControlComponent
			}
		}

		const FallbackComponent = () => <div dangerouslySetInnerHTML={{ __html: sprintf(__('"%s" component not found (404).'), controlName) }} />

		FallbackComponent.fallback = true

		return FallbackComponent
	}
}
