import ComposerLayout from '@layout-builder/composer-layout/ComposerLayout'
import ComposerSidebar from '@layout-builder/composer-sidebar/ComposerSidebar'
import { Fragment, createPortal, useCallback, useEffect, useMemo, useReducer, useRef, useState } from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import builderReducer from './reducer'

import SidebarItems from '@layout-builder/composer-sidebar/SidebarItems'
import { TitleControl } from '../..'
import { useDeviceView } from '../../ControlsContainer/context'
import BuilderContext from '../context'

const FooterBuilder = ({ value: allBuilderSections, option, onChange }) => {
	const currentFooter = useRef(null)

	const [currentView] = useDeviceView({ useMobileView: true })

	if (currentFooter.current === null) {
		currentFooter.current = allBuilderSections.sections[0].id
	}

	useEffect(() => {
		let { __forced_static_footer__, __should_refresh__, ...old } = wp.customize('footer_builder_key_placement')()

		Object.keys(old).map((key) => parseFloat(key) && (delete old[key]))

		wp.customize('footer_builder_key_placement')({
			...old,
			__forced_static_footer__: allBuilderSections.sections[0].id,
		})

		return () => {
			const { __forced_static_footer__, ...old } = wp.customize('footer_builder_key_placement')()

			wp.customize('footer_builder_key_placement')({
				__should_refresh__: true,
				[Math.random()]: 'update',
				...old,
			})
		}
	}, [])

	const [composerValueCollection, composerDispatchInternal] = useReducer(builderReducer, {
		...allBuilderSections,
		__forced_static_footer__: currentFooter.current || 'type-1',
	})

	const composerValue = useMemo(() => composerValueCollection.sections[0], [composerValueCollection])

	const [isDragging, setIsDragging] = useState(false)

	const composerDispatch = useCallback(
		(payload) => {
			return composerDispatchInternal({
				...payload,
				onBuilderValueChange: onChange,
			})
		},
		[composerDispatchInternal, onChange]
	)

	const setList = (lists) =>
		composerDispatch({
			type: 'ON_CHANGE_ELEMENT_LIST',
			onBuilderValueChange: onChange,
			payload: {
				lists,
			},
		})

	useEffect(() => {
		document.querySelector('.wp-full-overlay').classList.add('rishi-builder-open')

		// scroll into footer
		setTimeout(() => {
			const preview = document.getElementById('customize-preview')
			const iframe = preview.querySelector('iframe')
			const iframeWindow = iframe.contentWindow
			iframeWindow.scrollTo({ top: iframe.contentDocument.body.scrollHeight, behavior: 'smooth' })
		}, 300)

		return () => {
			document.querySelector('.wp-full-overlay').classList.remove('rishi-builder-open')
		}
	}, [])

	const itemsInUsed = useMemo(
		() => _.chain(composerValue.rows).map((p) => p.columns).flatten().value(),
		[composerValue, currentView]
	)

	const { elements } = _.groupBy(rishi.themeData.builder_data.footer, ({ is_primary }) => (is_primary ? 'structures' : 'elements'))

	return (
		<Fragment>
			<BuilderContext
				value={{
					builder: 'footer',
					isDragging,
					setIsDragging,
					setList,
					composerDispatch,
					composerValueCollection,
					composerValue,
					onChange: ({ id, value }) => setList({ [id]: value }),
					itemsInUsed,
					currentView,
					dynamicItems: Object.keys(composerValue.items).filter((id) => id.indexOf('~') > -1),
				}}
			>
				<ComposerSidebar>
					<TitleControl option={{ label: __('Footer Elements') }} />
					<SidebarItems items={elements} className={'!grid grid-cols-2 !gap-[16px] pt-4'} />
				</ComposerSidebar>
				{createPortal(<ComposerLayout />, document.querySelector('.rishi-layout-composer'))}
			</BuilderContext>
		</Fragment>
	)
}

FooterBuilder.config = { design: 'none' }

export default FooterBuilder
