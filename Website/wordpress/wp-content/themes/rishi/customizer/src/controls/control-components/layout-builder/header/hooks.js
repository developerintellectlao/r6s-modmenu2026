import { useDeviceView } from '../../ControlsContainer/context'
import { useBuilderContext } from '../context'

export function useHeaderElements() {
	const { composerValue, itemsInUsed } = useBuilderContext()
	const [currentView] = useDeviceView()

	const secondaryItems = rishi.themeData.builder_data.secondary_items.header.filter((h) => h.config.enabled)

	const allDynamicItems = composerValue.items.filter(({ id }) => id.includes('~'))

	const filteredItems = [...secondaryItems, ...allDynamicItems].filter((id) => {
		if (id !== 'mobile-menu' || currentView !== 'desktop') {
			return true
		}
		return itemsInUsed.includes('trigger')
	})

	const sortedItems = filteredItems.sort((a, b) => {
		const aItemData = rishi.themeData.builder_data.header.find(({ id }) => id === a.id.split('~')[0])
		const bItemData = rishi.themeData.builder_data.header.find(({ id }) => id === b.id.split('~')[0])
		return aItemData.config.name.localeCompare(bItemData.config.name)
	})

	return sortedItems
}

export function useBuilderElements(builder = 'header') {
	const { composerValue, itemsInUsed, dynamicItems } = useBuilderContext()
	const [currentView] = useDeviceView()

	const secondaryItems = rishi.themeData.builder_data.secondary_items[builder].filter((h) => h.config.enabled)

	const allDynamicItems = dynamicItems

	const filteredItems = [...secondaryItems, ...allDynamicItems].filter((id) => {
		if (id !== 'mobile-menu' || currentView !== 'desktop') {
			return true
		}
		return itemsInUsed.includes('trigger')
	})

	const sortedItems = filteredItems.sort((a, b) => {
		const aItemData = rishi.themeData.builder_data[builder].find(({ id }) => id === a.id.split('~')[0])
		const bItemData = rishi.themeData.builder_data[builder].find(({ id }) => id === b.id.split('~')[0])
		return aItemData.config.name.localeCompare(bItemData.config.name)
	})

	return sortedItems
}
