import RowSettingsPanel from '@layout-builder/composer-sidebar/RowSettingsPanel'

const Sidebar = ({ title, children }) => {
	return (
		<div>
			{title && <h3 className="rishi-section-title" dangerouslySetInnerHTML={{ __html: title }} />}
			{children}
			<RowSettingsPanel />
		</div>
	)
}

export default Sidebar
