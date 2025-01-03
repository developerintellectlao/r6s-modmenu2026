import { isObject, reduce } from 'lodash';

const defaultDeviceDescriptor = {
	desktop: true,
	tablet: true,
	mobile: true,
}

export function isControlEnabledForDevice(device, deviceDescriptor) {
	return {
		...defaultDeviceDescriptor,
		...(typeof deviceDescriptor === 'boolean' ? {} : deviceDescriptor || {}),
	}[device]
}

export function extractValuesFromOptions(options, values, getValue = null, hasInnerOptions = true) {
	let primaryOptions = extractTopLevelOptions(options, hasInnerOptions)

	return Object.keys(primaryOptions).reduce((result, optionId) => {
		
		if (getValue) {
			return { ...result, ...getValue(optionId, primaryOptions[optionId]) };
		}
		
		let value = '';
		const optionValue = values[optionId] ?? primaryOptions[optionId]?.value;

		if (['string', 'number'].includes(typeof optionValue)) {
			value = optionValue ?? '';
		} else if (Array.isArray(optionValue)) {
			value = optionValue || [];
		} else if (typeof optionValue === 'object') {
			value = { ...primaryOptions[optionId].value, ...optionValue };
		}

		return { ...result, [optionId]: value };
	}, { ...values });
}

export function isControlResponsive(option, args = {}) {
	let { ignoreHidden = false } = args

	let OptionComponent = rishi.customize.controls[option.control]

	if (OptionComponent.hiddenResponsive) {
		if (!ignoreHidden) {
			return true
		}
	}

	return !!option.responsive
}

export function formatResponsiveValueIfNeeded(value, isResponsive = true) {
	if (value && value.desktop) {
		return !isResponsive ? value.desktop : value
	}
	if (!isResponsive) return value

	return {
		desktop: value,
		tablet: value,
		mobile: value,
	}
}

export function flattenOptions(options) {
	return reduce(
		options,
		(result, value, key) => {
			return isObject(value)
				? {
					...result,
					...(value.control ? { [key]: value } : key === '__SORTING_KEYS_ORDER__' ? { [key]: value } : flattenOptions(value)),
				}
				: result
		},
		{}
	)
}

export const orderChoicesIfNeeded = (choices) =>
	Array.isArray(choices)
		? choices
		: Object.keys(choices).reduce(
			(current, choice) => [
				...current,
				{ key: choice, value: choices[choice], },
			],
			[]
		)

export const extractTopLevelOptions = (options, includeInnerOptions = true) => {
	const { __SORTING_KEYS_ORDER__, ...remainingOptions } = options

	return Object.keys(remainingOptions).reduce((currentOptions, optionId) => {
		const option = options[optionId]

		if (!option.control) {
			return {
				...currentOptions,
				...extractTopLevelOptions(option, includeInnerOptions),
			}
		}

		if (option?.options) {
			return {
				...currentOptions,
				...extractTopLevelOptions(option.options, includeInnerOptions),
			}
		}

		if (option && option['innerControls'] && includeInnerOptions) {
			return {
				...currentOptions,
				[optionId]: option,
				...extractTopLevelOptions(option['innerControls'], includeInnerOptions),
			}
		}

		return {
			...currentOptions,
			[optionId]: option,
		}
	}, {})
}

export const flattenOptionsWithValues = (object) => {
	const innerOptions = object['innerControls'] || object.options || {}

	let values = {}
	const flattenObject = (value) => {
		Object.keys(value).forEach((key) => {
			const _innerOptions = value[key]['innerControls'] || value[key].options
			if (_innerOptions) {
				flattenObject(_innerOptions)
			} else {
				if (value[key].value) {
					values = {
						...values,
						[key]: value[key],
					}
				}
			}
		})
	}

	flattenObject(innerOptions)

	return values
}

export const __return_null = () => null
export const __return_empty_object = () => ({})
