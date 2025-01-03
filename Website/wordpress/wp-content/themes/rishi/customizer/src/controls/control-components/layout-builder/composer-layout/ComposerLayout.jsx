import styled from '@emotion/styled'
import { __, sprintf } from '@wordpress/i18n'
import _ from 'lodash'
import HeaderRow from '../header/Row'
import FooterRow from '../footer/Row'
import { useState } from '@wordpress/element'
import { useDeviceView } from '@customizer/controls/control-components/ControlsContainer/context'
import { useBuilderContext } from '../context'

const layoutRows = {
	'top-row': __('Top Row', 'rishi'),
	'middle-row': __('Main Row', 'rishi'),
	'bottom-row': __('Bottom Row', 'rishi'),
	offcanvas: __('Offcanvas', 'rishi'),
}

const OffcanvasStyle = styled.div`
	.rishi-builder__edge-panel {
		margin-bottom: 0;
		.rishi-builder__row,
		.rishi-builder__row-inner {
			height: 100%;
			div[data-id="__inserter__"],
			div[aria-expanded],
			.components-button {
				width: 100%;
			}
		}
	}
`

function Composer({ className }) {
	const { composerValue, itemsInUsed = [], builder } = useBuilderContext()
	const [view] = useDeviceView()
	const showEdgePanel = builder === 'header' && (view === 'mobile' || itemsInUsed.includes('trigger'))

	const [activeRows, setActiveRows] = useState([])

	const rows = _.keyBy(composerValue?.rows ?? composerValue[view === 'tablet' ? 'mobile' : view], 'id')

	const Row = builder === 'header' ? HeaderRow : FooterRow

	const renderRow = (row) => {
		const columns = rows[row]?.placements || rows[row]?.columns || []

		if (row !== 'middle-row' && columns.every((column) => (column?.items ?? column).length <= 0) && !activeRows.includes(row)) {
			return (
				<li key={row} className="rishi-builder__row rishi-empty-row-item">
					<span className="hr-line"></span>
					<button key={row} onClick={() => setActiveRows((state) => [...state, row])} className="rishi-add-row-btn">
						{sprintf('Add %s', layoutRows[row])}
					</button>
					<span className="hr-line"></span>
				</li>
			)
		}
		return <Row label={layoutRows[row]} bar={rows[row]} key={row} />
	}

	return (
		<OffcanvasStyle className={className}>
			<div className="w-full flex align-middle border-t h-[350px] box-border relative py-[40px] px-[24px]">
				{showEdgePanel && (
					<ul className="rishi-builder__edge-panel w-52 mr-5 mb-6">
						<Row label={layoutRows['offcanvas']} direction="vertical" bar={rows['offcanvas']} positions={['start']} />
					</ul>
				)}
				<ul className="flex-1">{['top-row', 'middle-row', 'bottom-row'].map(renderRow)}</ul>
			</div>
		</OffcanvasStyle>
	)
}

export default styled(Composer)`
	display: flex;
	flex-direction: column;
	justify-content: space-between;
	position: relative;
`
