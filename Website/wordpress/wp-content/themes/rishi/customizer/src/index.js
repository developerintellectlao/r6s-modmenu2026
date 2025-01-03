import domReady from '@wordpress/dom-ready'
import { createRoot } from '@wordpress/element'

import './index.scss'
import 'tippy.js/dist/tippy.css'
import 'tippy.js/animations/scale.css'
import 'tippy.js/animations/shift-away.css'
import Builder from './builder'
import { ControlsContainer as ControlsPanel } from './controls'

import './modules'

domReady(() => {

	window['rishi'] = window['rishi'] ?? {}
	window.rishi.headerBuilder = new Builder('header')
	window.rishi.footerBuilder = new Builder('footer')

	setTimeout(() => {
		Object.values(wp.customize.control._value)
			.filter(({ params: { type } }) => type === 'rishi-customizer-section')
			.forEach((control) => {
				wp.customize.section(control.section(), (section) => {
					let root = null
					section.expanded.bind((value) => {
						if (!value) return root?.unmount()
						root = createRoot(control.container[0])
						if (value) {
							// ControlsContainer
							return root.render(
								<ControlsPanel
									id={control.id}
									onChange={(value) => {
										control.setting.set(value)
									}}
									value={control.setting.get()}
									option={control.params.option}
									customizeControl={control}
								/>
							)
						}
					})
				})
			})
	})
})
