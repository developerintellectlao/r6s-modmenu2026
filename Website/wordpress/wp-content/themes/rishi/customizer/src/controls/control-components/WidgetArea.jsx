import BlockWidgetArea from './widget-area/BlockWidgetArea'
import LegacyWidgetArea from './widget-area/LegacyWidgetArea'

const WidgetArea = ({ ...props }) => {
	let hasBlockWidgets = rishi.themeData.use_new_widgets || false
	if (hasBlockWidgets) {
		return <BlockWidgetArea {...props} />
	}

	return <LegacyWidgetArea {...props} />
}

WidgetArea.config = { design: 'none' }

export default WidgetArea
