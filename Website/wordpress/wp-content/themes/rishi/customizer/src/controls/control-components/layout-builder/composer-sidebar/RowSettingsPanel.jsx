import Panel, { PanelMetaWrapper } from '../../Panel'
import { __return_null, extractValuesFromOptions } from '@helpers'
import { Fragment } from '@wordpress/element'
import { useBuilderContext } from '../context'
import { useBuilderElements } from '../hooks'

const SettingsPanel = ({ panelId, option, item, ...props }) => {
	const { composerValue, composerDispatch, builder } = useBuilderContext()

	const getItemValue = (item) => {
		return Object.values(composerValue.items).find((_item) => _item.id === item.id)
	}

	return (
		<Panel
			id={panelId}
			getValues={() => {
				const itemValue = getItemValue(item)

				if (itemValue && Object.keys(itemValue.values).length > 5) {
					return {
						...itemValue.values,
						builderSettings: composerValue.settings,
					}
				} else {
					return {
						...extractValuesFromOptions(item.options, itemValue ? itemValue.values : {}),
						builderSettings: composerValue.settings || {},
					}
				}
			}}
			option={option}
			onChangeFor={(optionId, optionValue) => {
				const itemValue = getItemValue(item)

				composerDispatch({
					type: 'ON_CHANGE_ELEMENT_VALUE',
					payload: {
						id: item.id,
						optionId,
						optionValue,
						values: !itemValue || (itemValue && 0 === Object.keys(itemValue.values).length) ? extractValuesFromOptions(item.options, {}) : {},
					},
				})
			}}
			view="simple"
		/>
	)
}

SettingsPanel.type = 'panel'

const RowSettingsPanel = () => {
	const { builder, panelsActions } = useBuilderContext()
	const builderElements = useBuilderElements(builder)

	const primaryItems = builderElements.filter((_item) => _item.is_primary)

	return (
		<Fragment>
			{primaryItems.map((primaryItem) => {
				const option = {
					label: primaryItem.config.name,
					innerControls: primaryItem.options,
				}

				const id = `builder_panel_${primaryItem.id}`

				return (
					<PanelMetaWrapper id={id} key={primaryItem.id} option={option} {...panelsActions}>
						{(props) => <SettingsPanel {...props} panelId={id} item={primaryItem} option={option} />}
					</PanelMetaWrapper>
				)
			})}
		</Fragment>
	)
}

export default RowSettingsPanel
