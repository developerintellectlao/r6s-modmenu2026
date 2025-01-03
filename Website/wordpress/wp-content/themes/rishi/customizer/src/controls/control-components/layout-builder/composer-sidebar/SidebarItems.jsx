import { Panel, PanelMetaWrapper } from '@controls'
import { extractValuesFromOptions } from '@helpers'
import DraggableItems, { DraggableItem } from '@layout-builder/common/DraggableItems'
import { useBuilderContext } from '@layout-builder/context'
import { Slot } from '@wordpress/components'
import { Fragment } from '@wordpress/element'
import cls from 'classnames'
import _ from 'lodash'

const Element = ({ item, itemData, index, itemOptions, displayList = true }) => {
	const { composerValue, composerDispatch, panelsActions, currentView, isDragging, itemsInUsed, builder, dynamicItems } = useBuilderContext()

	const allDynamicItems = dynamicItems

	const itemId = item.split('~')[0]

	const allClonesAndOriginal = [itemId, ...allDynamicItems.filter((id) => id.split('~')[0] === itemId)]

	let inlinedItemsFromAllViewsBuilder = []
	const itemInBuilder = itemsInUsed.indexOf(item) > -1
	let row = '__none__'

	switch (builder) {
		case 'header':
			inlinedItemsFromAllViewsBuilder = [
				...composerValue.desktop.reduce(
					(currentItems, { id, placements }) => [...currentItems, ...(placements || []).reduce((c, { id, items }) => [...c, ...items], [])],
					[]
				),

				...composerValue.mobile.reduce(
					(currentItems, { id, placements }) => [...currentItems, ...(placements || []).reduce((c, { id, items }) => [...c, ...items], [])],
					[]
				),
			]
			row = itemInBuilder && _.chain(composerValue[currentView]).flatMap('placements').flatMap('items').find((i) => i === item).get('id').value()
			break

		case 'footer':
			inlinedItemsFromAllViewsBuilder = composerValue.rows.reduce(
				(currentItems, { columns }) => [...currentItems, ...(columns || []).reduce((c, items) => [...c, ...items], [])],
				[]
			)
			row =
				itemInBuilder &&
				_.chain(composerValue.rows).flatMap(({ columns }) => columns || []).flatMap((items) => items).find((i) => i === item).get('id').value()
			break
	}

	const itemName = allClonesAndOriginal.length > 1 ? `${itemData?.config?.name} ${allClonesAndOriginal.indexOf(item) + 1}` : itemData.config.name
	const option = {
		label: itemName,
		innerControls: itemOptions,
	}

	const id = `builder_panel_${item}`

	return (
		<PanelMetaWrapper id={id} option={option} {...panelsActions}>
			{({ open }) => (
				<Fragment>
					{inlinedItemsFromAllViewsBuilder.indexOf(item) > -1 && (
						<Panel
							id={id}
							getValues={() => {
								let itemValue = 'header' === builder ? composerValue.items.find(({ id }) => id === item) : composerValue.items[item]

								if (itemValue && Object.keys(itemValue.values) > 5) {
									return { builderSettings: composerValue.settings || {}, row, ...itemValue.values }
								}

								return {
									...extractValuesFromOptions(itemOptions, itemValue ? itemValue.values : {}),
									row,
									builderSettings: composerValue.settings || {},
								}
							}}
							isBuilderItemPanel
							option={option}
							onChangeFor={(optionId, optionValue) => {
								const currentValue = 'header' === builder ? composerValue.items.find(({ id }) => id === item) : composerValue.items[item]

								composerDispatch({
									type: 'ON_CHANGE_ELEMENT_VALUE',
									payload: {
										id: item,
										optionId,
										optionValue,
										values:
											!currentValue || (currentValue && Object.keys(currentValue.values).length === 0)
												? extractValuesFromOptions(itemOptions, {})
												: {},
									},
								})
							}}
							view="simple"
						/>
					)}

					{itemData?.config?.devices?.indexOf(currentView) > -1 && displayList && (
						<div
							data-id={item}
							className={`rishi-builder-sidebar-item rishi-builder-item ${cls({
								'rishi-item-in-builder': itemInBuilder,
							})}`}
							onClick={() => !isDragging && itemInBuilder && open()}
						>
							<div className="rishi-builder-item-content">
								{itemName}
								<Slot name={`PlacementsBuilderSidebarItem_${index}`} fillProps={{ item, itemInBuilder, itemData }} />
							</div>
							<div className="rishi-builder-item-background"></div>
						</div>
					)}
				</Fragment>
			)}
		</PanelMetaWrapper>
	)
}

const SidebarItems = ({ items, className }) => {
	const itemsById = _.keyBy(items, 'id')

	return (
		<DraggableItems
			options={{ sort: false, filter: '.rishi-item-in-builder' }}
			group={{
				name: 'header_sortables',
				put: false,
				pull: 'clone',
			}}
			draggableId={'available-items'}
			items={items}
			direction="vertical"
			className={className}
		>
			{items
				.filter(({ is_primary }) => !is_primary)
				.map(({ id: _id }, index) => {
					const itemData = itemsById[_id.split('~')[0]]
					return (
						<DraggableItem item={_id} index={index} key={_id} panelType="footer">
							<Element
								itemOptions={items.find(({ id }) => id === _id.split('~')[0]).options}
								index={index}
								item={_id}
								itemData={itemData}
							/>
						</DraggableItem>
					)
				})}
		</DraggableItems>
	)
}

export default SidebarItems
