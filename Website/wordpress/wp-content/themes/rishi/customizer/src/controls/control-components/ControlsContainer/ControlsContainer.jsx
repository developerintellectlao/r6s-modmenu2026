import { SlotFillProvider } from '@wordpress/components'
import { useMemo } from '@wordpress/element'

import { ControlBase as ControlComponent, ContainerBase } from '@controls'
import { flattenOptions } from '@helpers'
import ErrorBoundary from '../../../ErrorBoundary'

export default function ControlsContainer(props) {
	let { options, value, onChange, purpose = 'default', hasRevertButton = true, parentValue, isTabOptions = false } = props

	const groupedOptions = useMemo(() => {
		const flattenedOptions = flattenOptions(options)

		const sortedOptionIds = flattenedOptions.__SORTING_KEYS_ORDER__
			? Object.values(flattenedOptions.__SORTING_KEYS_ORDER__).sort((a, b) => a - b)
			: Object.keys(flattenedOptions).filter((id) => id !== '__SORTING_KEYS_ORDER__')
		const optionData = sortedOptionIds.map((id) => ({
			...flattenedOptions[id],
			id,
		}))
		const groupedOptions = optionData.reduce((groups, descriptor) => {
			const lastGroup = groups[groups.length - 1]
			const isSameOptionType =
				lastGroup &&
				lastGroup[0].options &&
				lastGroup[0].type === descriptor.type
			return isSameOptionType ? [...groups.slice(0, -1), [...lastGroup, descriptor]] : [...groups, [descriptor]]
		}, [])
		return groupedOptions
	}, [options])

	return (
		<SlotFillProvider>
			{groupedOptions.map((optionGroup, index) => {
				const hasContainerOptions = optionGroup[0].options

				const ComponentToRender = hasContainerOptions ? ContainerBase : ControlComponent

				let renderingProps = {
					hasRevertButton,
					purpose,
					value,
					parentValue,
					optionGroup,
					onChange: onChange,
				}

				renderingProps = {
					...renderingProps,
					...((!hasContainerOptions && {
						id: optionGroup[0].id,
						value: value[optionGroup[0].id],
						option: optionGroup[0],
						values: value,
						onChange: (_value) => onChange(optionGroup[0].id, _value),
						onChangeFor: (id, _value) => onChange(id, _value),
					}) ||
						{}),
				}

				return (
					<ErrorBoundary key={optionGroup[0].id}>
						<ComponentToRender key={optionGroup[0].id} {...renderingProps} />
					</ErrorBoundary>
				)
			})}
		</SlotFillProvider>
	)
}
