export default function builderReducer(state, action) {
	if (!action.onBuilderValueChange) {
		throw new Error('When you dispatch pass onBuilderValueChange fn.')
	}

	const { sections, __forced_static_footer__ } = state
	const currentSection = sections.find((section) => {
		return section.id.indexOf(__forced_static_footer__ || sections[0].id) > -1
	})

	switch (action.type) {
		case 'ON_CHANGE_ELEMENT_VALUE': {
			const { id, optionId, optionValue, values = {}, silent } = action.payload
			const itemId = `${id}:${optionId}`
			const { items, rows } = currentSection
			const item = items[id] || { values: {} }
			const newValues = {
				...item.values,
				...values,
				...(!['top-row', 'middle-row', 'bottom-row'].includes('id') && optionId === 'items_per_row'
					? { items_per_row: rows.find((row) => row.id === id).columns.length }
					: {}),
				...(!['top-row', 'middle-row', 'bottom-row'].includes('id') || optionId !== 'items_per_row'
					? {}
					: { items_per_row: parseInt(optionValue, 10) }),
				[optionId]: optionValue,
			}

			const newItem = { id, values: newValues }
			const newItems = Array.isArray(items) ? {} : items
			newItems[id] = newItem
			let newSection = {
				...currentSection,
				items: newItems,
				rows: rows.map((row) => {
					if (optionId === 'items_per_row' && row.id === id) {
						const numberOfColumns = parseInt(optionValue, 10)
						row.columns = Array.from({ length: numberOfColumns }, (el, index) => row.columns[index] ?? [])
					}
					return row
				}),
			}

			const newSections = sections.map((section) => {
				return section.id === currentSection.id ? newSection : section
			})
			const newState = { ...state, sections: newSections }

			if (!silent) {
				action.onBuilderValueChange(newState)
			}
			return newState
		}
		case 'ON_CHANGE_ELEMENT_LIST': {
			const { lists } = action.payload

			const newRows = currentSection.rows.map((row) => {
				const { id, columns } = row
				const newColumns = columns.map((column, index) => {
					const key = `${id}:${index}`
					return lists[key] ? lists[key] : column
				})
				return { id, columns: newColumns }
			})
			const newSection = { ...currentSection, rows: newRows }
			const newSections = sections.map((section) => {
				return section.id === currentSection.id ? newSection : section
			})
			const newState = { ...state, sections: newSections }
			action.onBuilderValueChange(newState)
			return newState
		}
		default:
			return state
	}
}
