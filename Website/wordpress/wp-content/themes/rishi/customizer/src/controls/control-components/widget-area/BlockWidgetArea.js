import { useContext, useEffect, useRef } from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import $ from 'jquery'
import { usePanelContext } from '../ControlsContainer/context'

const BlockWidgetArea = ({ option: { sidebarId = 'footer-one' } }) => {
	const parentEl = useRef()
	const { panelsHelpers, panelsDispatch } = usePanelContext()

	useEffect(() => {
		const config = { attributes: true, childList: true, subtree: true }
		const sidebarForCleanup = sidebarId === 'footer-one' ? 'footer-two' : 'footer-one'
		const controlForSidebarId = wp.customize.control._value[`sidebars_widgets[${sidebarId}]`]

		const callback = (mutationsList, observer) => {
			if (!controlForSidebarId.container[0].closest('.rishi-customizer-panel-new')) {
				const currentTab = document.querySelector('.rishi-customizer-panel-new .rt-current-tab') || document.querySelector('.rishi-customizer-panel-new .customizer-panel-content')
				currentTab && currentTab.prepend(controlForSidebarId.container[0])
			}
		}

		const observer = new MutationObserver(callback)

		wp.customize.control._value[`sidebars_widgets[${sidebarForCleanup}]`].subscribers.forEach((c) => c(true))

		requestAnimationFrame(() => controlForSidebarId.subscribers.forEach((c) => c(true)))

		controlForSidebarId.oldContainer = controlForSidebarId.container
		controlForSidebarId.container = $(parentEl.current)

		setTimeout(() => {
			panelsDispatch({ type: 'SET_PANEL_META', payload: { secondLevelTitleLabel: __('Block Settings', 'rishi-pro') } })
		}, 10)

		controlForSidebarId.oldContainer.remove()
		wp.customize.section(controlForSidebarId.section()).container = $(parentEl.current)

		observer.observe(parentEl.current.parentNode, config)

		return () => {
			controlForSidebarId.container = controlForSidebarId.oldContainer
			observer.disconnect()
			panelsDispatch({ type: 'SET_PANEL_META', payload: { secondLevelTitleLabel: null } })
		}
	}, [])

	return <div className="rishi-option-widget-area customize-control-sidebar_block_editor" ref={parentEl}></div>
}

export default BlockWidgetArea
