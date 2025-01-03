import { useEffect, useRef } from '@wordpress/element'

const LegacyWidgetArea = ({ value, option, option: { sidebarId = 'footer-one' }, onChange }) => {
	const parentEl = useRef()

	const getWidgetsToMove = (sectionId) => {
		return Object.keys(wp.customize.control._value).filter((id) => {
			return id.startsWith('widget_') && wp.customize.control(id).section() === sectionId;
		})
	}

	useEffect(() => {
		const sectionId = `widgetAreaSection-${sidebarId}`
		const widgetsToMove = getWidgetsToMove(`sidebar-widgets-${sidebarId}`)

		const Section = wp.customize.Section.extend({
			containerParent: jQuery(parentEl.current),
			collapse: function () { },
			embed: function () {
				this.containerParent = wp.customize.ensure(this.containerParent)
				this.containerParent.append(this.contentContainer)
				this.contentContainer[0].classList.add('open')
				this.contentContainer[0].querySelector('.customize-section-description-container').remove()
				this.deferred.embedded.resolve()

				setTimeout(() => {
					widgetsToMove.forEach((control) => {
						wp.customize.control(control).embedWidgetControl()
					})
				})
			},
		})

		const section = new Section(sectionId, {})
		wp.customize.section.add(section)

		widgetsToMove.forEach((control) => {
			const widgetControl = wp.customize.control(control)
			widgetControl.prevSection = `sidebar-widgets-${sidebarId}`
			widgetControl.section(sectionId)
		})

		setTimeout(() => {
			if (parentEl.current) {
				jQuery(parentEl.current.firstElementChild).sortable('option', 'containment', 'parent')
			}
		}, 1000)

		return () => {
			const widgetsToMoveBack = getWidgetsToMove(sectionId)
			widgetsToMoveBack.forEach((control) => {
				const widgetControl = wp.customize.control(control)
				if (widgetControl && widgetControl.container[0].matches('[id*="widget_text"]')) {
					let container = widgetControl.container[0]
					let widgetId = container.querySelector('.widget-id').value

					if (wp.textWidgets.widgetControls[widgetId]) {
						wp.textWidgets.widgetControls[widgetId].remove()
					}
					wp.textWidgets.widgetControls[widgetId] = null

					widgetControl.collapse()
				}

				if (widgetControl) {
					widgetControl.section(widgetControl.prevSection || `sidebar-widgets-${sidebarId}`)
				}
			})

			document.querySelectorAll(`.customize-pane-parent [id="accordion-section-${sectionId}"]`).forEach((container) => container.remove())
			wp.customize.section.remove(section.id)
		}
	}, [])

	return <div className="rishi-option-widget-area" ref={parentEl}></div>
}

export default LegacyWidgetArea
