const getHeaderID = () => {
	const iframe = wp.customize.previewer.container.find('iframe')[0];
	const header = iframe.contentDocument.querySelector('header#header');
	return header ? header.dataset.id : null;
}

const updatePlacementItems = (sectionId, placement, lists) => {
	const placementId = placement.id;
	const items = placement.items;
	return lists[`${sectionId}:${placementId}`]
		? { id: placementId, items: lists[`${sectionId}:${placementId}`], }
		: { id: placementId, items: items, };
};

const updateSectionPlacements = (section, lists) => {
	const sectionId = section.id;
	const placements = section.placements;
	return {
		id: sectionId,
		placements: placements.map((placement) => updatePlacementItems(sectionId, placement, lists)),
	};
};

const findPlacement = (section, placementId) => {
	return section.placements.find((placement) => placement.id === placementId);
};

const updateSection = (section, lists, sectionId) => {
	let updatedSection = updateSectionPlacements(section, lists);

	const middlePlacement = findPlacement(updatedSection, 'middle');
	const startMiddlePlacement = findPlacement(updatedSection, 'start-middle');
	const endMiddlePlacement = findPlacement(updatedSection, 'end-middle');

	if (
		middlePlacement &&
		middlePlacement.items.length === 0 &&
		startMiddlePlacement &&
		(startMiddlePlacement.items.length > 0 || endMiddlePlacement.items.length > 0)
	) {
		const startPlacement = findPlacement(updatedSection, 'start');
		const endPlacement = findPlacement(updatedSection, 'end');

		const updatedItems = {
			[`${sectionId}:start`]: [...startPlacement.items, ...startMiddlePlacement.items],
			[`${sectionId}:end`]: [...endMiddlePlacement.items, ...endPlacement.items],
			[`${sectionId}:start-middle`]: [],
			[`${sectionId}:end-middle`]: [],
		};

		updatedSection.placements = updatedSection.placements.map((placement) => {
			const placementId = placement.id;
			const items = placement.items;
			const updatedItemsForPlacement = updatedItems[`${sectionId}:${placementId}`];

			return {
				id: placementId,
				items: updatedItemsForPlacement ? updatedItemsForPlacement : items,
			};
		});
	}
	return updatedSection;
};


export default function builderReducer(state, action) {
	state.__should_refresh__
	const newState = Object.entries(state).reduce((acc, [key, value]) => {
		if (!['__should_refresh__'].includes(key)) {
			acc[key] = value
		}
		return acc
	}, {})

	if (!action.onBuilderValueChange) {
		throw new Error('When you dispatch pass onBuilderValueChange fn.')
	}
	let selectedSection = newState.sections.find(
		(section) => section.id.indexOf(newState.__static_header_required__ || getHeaderID() || newState.sections[0].id) > -1
	),
		shouldRefresh = false,
		shouldRefreshItem = false,
		uniqueIds = []

	switch (action.type) {
		case 'ON_CHANGE_ELEMENT_VALUE':
			const { id, optionId, optionValue, values = {}, silent } = action.payload

			const itemKey = `${id}:${optionId}`
			const itemIndex = selectedSection.items.findIndex((item) => item.id === id)

			if (itemIndex === -1) {
				selectedSection.items.push({ id, values: {} })
			}

			selectedSection.items = selectedSection.items.map((item) => {
				if (item.id === id) {
					item.values = { ...item.values, ...values, [optionId]: optionValue }
				}

				return item
			})

			if (id === 'logo' && optionId === 'custom_logo' && selectedSection.id === 'type-1' && wp.customize) {
				wp.customize('custom_logo')(optionValue ? optionValue.desktop || optionValue : '')
			}

			if (!silent && wp.customize && wp.customize.previewer) {
				wp.customize.previewer.send('rishi:header:receive-value-update', {
					itemId: id,
					optionId,
					optionValue,
					futureItems: selectedSection.items,
					values: {
						...selectedSection.items.find((item) => item.id === id)?.values,
						...values,
						[optionId]: optionValue,
					},
				})
			}
			break
		case 'ON_CHANGE_ELEMENT_LIST':
			shouldRefresh = true
			const { currentView, lists } = action.payload
			selectedSection = {
				...selectedSection,
				...(currentView && {
					[currentView]: selectedSection[currentView].map((section) => {
						const sectionId = section.id;
						if (
							Object.keys(lists)
								.map((list) => list.split(':')[0])
								.indexOf(sectionId) > -1
						) {
							return updateSection(section, lists, sectionId);
						}
						return {
							id: sectionId,
							placements: section.placements,
						};
					}),
				}),
			};
			break
	}

	const updatedState = {
		...newState,
		__should_refresh__: shouldRefresh ? true : false,
		__should_refresh_item__: shouldRefreshItem,
		sections: [
			...newState.sections.map((section) => {
				return section.id === selectedSection.id ? selectedSection : section
			}),
		].filter((section) => {
			const sectionId = section.id
			return -1 === uniqueIds.indexOf(sectionId)
		}),
	}

	// Reload Preview
	action.onBuilderValueChange({ ...updatedState })
	return updatedState
}
