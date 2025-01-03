import ComposerLayout from '@layout-builder/composer-layout/ComposerLayout'
import ComposerSidebar from '@layout-builder/composer-sidebar/ComposerSidebar'
import { Fragment, createPortal, useCallback, useEffect, useMemo, useReducer, useRef, useState } from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import _ from 'lodash'
import { useDeviceView } from '../../ControlsContainer/context'
import BuilderContext from '../context'
import builderReducer from './reducer'
import SidebarItems from '@layout-builder/composer-sidebar/SidebarItems'
import { TitleControl } from '../..'

const HeaderBuilder = ({ value: allBuilderSections, option, onChange: onBuilderValueChange, id, onChangeFor, onChange }) => {
	const currentHeader = useRef(null)

	if (currentHeader.current === null) {
		currentHeader.current = allBuilderSections.sections[0].id
	}

	useEffect(() => {
		// scroll into header
		const preview = document.getElementById('customize-preview')
		const iframe = preview.querySelector('iframe')
		const iframeWindow = iframe.contentWindow
		iframeWindow.scrollTo({ top: 0, behavior: 'smooth' })

	}, [])

	const [isDragging, setIsDragging] = useState(false)

	const [composerValueCollection, composerDispatchInternal] = useReducer(builderReducer, {
		...allBuilderSections,
		...(currentHeader.current ? { __static_header_required__: currentHeader.current } : {}),
	})

	const composerValue = useMemo(
		() =>
			composerValueCollection.sections.find(({ id }) => id === composerValueCollection.__static_header_required__) ||
			composerValueCollection.sections[0],
		[composerValueCollection]
	)

	const [currentView] = useDeviceView({ useMobileView: true })

	const itemsInUsed = useMemo(() => {
		return _.chain(composerValue[currentView]).map((p) => p.placements).flatten().map((a) => a.items).flatten().value()
	}, [composerValue, currentView])

	const composerDispatch = useCallback(
		(payload) => composerDispatchInternal({ ...payload, onBuilderValueChange, }),
		[composerDispatchInternal, onBuilderValueChange]
	)

	const setList = useCallback(
		(lists) => {
			return composerDispatch({
				type: 'ON_CHANGE_ELEMENT_LIST',
				onBuilderValueChange,
				payload: {
					currentView,
					lists,
				},
			})
		},
		[composerDispatch, currentView, onBuilderValueChange]
	)

	const itemsInUsedInAllViews = useMemo(() => {
		return [
			..._.flatMap(composerValue.desktop, ({ placements }) => _.flatMap(placements, ({ items }) => items)),
			..._.flatMap(composerValue.mobile, ({ placements }) => _.flatMap(placements, ({ items }) => items)),
		]
	}, [composerValue])

	const { elements } = _.groupBy(rishi.themeData.builder_data.header, ({ is_primary }) => (is_primary ? 'structures' : 'elements'))

	return (
		<Fragment>
			<BuilderContext
				value={{
					builder: 'header',
					option,
					currentView,
					isDragging,
					setIsDragging,
					setList,
					composerDispatch,
					composerValue,
					itemsInUsedInAllViews, // Items used in all views/devices.
					itemsInUsed, // Items in used for current view/device.
					onChange: ({ id, value }) => setList({ [id]: value }),
					composerValueCollection,
					dynamicItems: composerValue.items.filter(({ id }) => id.indexOf('~') > -1).map(({ id }) => id),
				}}
			>
				<ComposerSidebar allBuilderSections={allBuilderSections}>
					<TitleControl option={{ label: __('Header Elements','rishi') }} />
					<SidebarItems items={elements} className={'!grid grid-cols-2 !gap-[16px] pt-4'} />
				</ComposerSidebar>
				{createPortal(<ComposerLayout />, document.querySelector('.rishi-layout-composer'))}
			</BuilderContext>
		</Fragment>
	)
}

export default HeaderBuilder
