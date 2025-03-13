class Builder {
	constructor(panel, args = {}) {
		this.args = {
			container: '.rishi-layout-composer',
			parentEl: '.wp-full-overlay',
			activeClass: 'rishi-builder-open',
			...args,
		}
		this.panel = panel
		// this.settingKey = settingKey;
		this.container = document.querySelector(this.args.container)
		this.parentEl = document.querySelector(this.args.parentEl)

		if (!this.container) {
			const root = document.createElement('div')
			root.classList.add(this.args.container.replace(/^\W/, ''))
			this.container = root
			this.parentEl.appendChild(root)
		}

		this.toggle = this.toggle.bind(this)
		this.getPanelInstance = this.getPanelInstance.bind(this)
		this.setupPanel = this.setupPanel.bind(this)
		this.setupPanel()
	}

	getPanelInstance() {
		return wp.customize.panel(this.panel) ? wp.customize.panel : wp.customize.section
	}

	toggle(open) {
		this.parentEl.classList.toggle(this.args.activeClass, open)
		if (!open) setTimeout(() => (this.container.innerHTML = ''))
		if (open) this.container.dataset.builder = this.panel
		else this.container.dataset.builder = ''
	}

	setupPanel() {
		this.getPanelInstance()(this.panel, (section) => section.expanded.bind(this.toggle))
	}
}

export default Builder
