import { __ } from '@wordpress/i18n'
import cls from 'classnames'
import { useDeviceView, usePanelContext } from '../../ControlsContainer/context'
import { useBuilderContext } from '../context'
// import DraggableItems from '../DraggableItems'
import DraggableItems, { DraggableItem } from '@layout-builder/common/DraggableItems'
import styled from '@emotion/styled'
import ItemSelector from '@layout-builder/common/InlineItemSelector'
import { useBuilderElements } from '@layout-builder/header/hooks'
import BuilderElement from '@layout-builder/composer-layout/BuilderElement'

const rows = {
	'top-row': __('Top Row', 'rishi'),
	'middle-row': __('Main Row', 'rishi'),
	'bottom-row': __('Bottom Row', 'rishi'),
}

const Row = ({ bar, className }) => {
	const { panelsHelpers } = usePanelContext()

	const { composerValue, composerDispatch, setList } = useBuilderContext()

	const [currentView] = useDeviceView()

	const rowItems = composerValue.items[bar.id]

	const rowValues = rowItems ? rowItems.values : {}

	let layout = 'initial'

	let hideFooterRow = rowValues?.hide_footer_row ?? false

	const numberOfColumns = composerValue.items?.[bar.id]?.values?.items_per_row ?? bar.columns.length

	if (numberOfColumns > 1) {
		layout = rowValues[`${numberOfColumns}_columns_layout`] || {
			desktop: `repeat(${numberOfColumns}, 1fr)`,
			tablet: 'initial',
			mobile: 'initial',
		}
	}

	layout = layout.desktop || layout

	const handleRowVisibilityClick = (row) => () => {
		composerDispatch({
			type: 'ON_CHANGE_ELEMENT_VALUE',
			payload: {
				id: row,
				optionId: 'hide_footer_row',
				optionValue: !hideFooterRow,
			},
		})
		wp.customize && wp.customize.previewer && wp.customize.previewer.refresh()
	}

	const handleRowSettingClick = (row) => () => !hideFooterRow && panelsHelpers.open(`builder_panel_${row}`)

	const footerElements = useBuilderElements('footer')

	const selectorItems = footerElements.filter((_item) => _item.config.devices.includes('tablet' === currentView ? 'mobile' : currentView) && !_item.is_primary)

	const columns = Array.from({ length: numberOfColumns }, (el, index) => bar.columns[index] ?? [])

	return (
		layout && (
			<li className={cls('rishi-builder__row', className, { 'is-hidden': hideFooterRow })}>
				<div className="__row-label">{rows[bar.id]}</div>
				<div className="__row-actions">
					<button onClick={handleRowSettingClick(bar.id)} type="button" className="button-setting">
						<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path
								d="M6.16659 14.6667L5.89993 12.5333C5.75548 12.4778 5.61937 12.4111 5.49159 12.3333C5.36382 12.2556 5.23882 12.1722 5.11659 12.0833L3.13326 12.9167L1.29993 9.75001L3.01659 8.45001C3.00548 8.37223 2.99993 8.29723 2.99993 8.22501V7.77501C2.99993 7.70279 3.00548 7.62779 3.01659 7.55001L1.29993 6.25001L3.13326 3.08334L5.11659 3.91668C5.23882 3.82779 5.36659 3.74445 5.49993 3.66668C5.63326 3.5889 5.76659 3.52223 5.89993 3.46668L6.16659 1.33334H9.83326L10.0999 3.46668C10.2444 3.52223 10.3805 3.5889 10.5083 3.66668C10.636 3.74445 10.761 3.82779 10.8833 3.91668L12.8666 3.08334L14.6999 6.25001L12.9833 7.55001C12.9944 7.62779 12.9999 7.70279 12.9999 7.77501V8.22501C12.9999 8.29723 12.9888 8.37223 12.9666 8.45001L14.6833 9.75001L12.8499 12.9167L10.8833 12.0833C10.761 12.1722 10.6333 12.2556 10.4999 12.3333C10.3666 12.4111 10.2333 12.4778 10.0999 12.5333L9.83326 14.6667H6.16659ZM8.03326 10.3333C8.6777 10.3333 9.2277 10.1056 9.68326 9.65001C10.1388 9.19445 10.3666 8.64445 10.3666 8.00001C10.3666 7.35557 10.1388 6.80557 9.68326 6.35001C9.2277 5.89445 8.6777 5.66668 8.03326 5.66668C7.37771 5.66668 6.82493 5.89445 6.37493 6.35001C5.92493 6.80557 5.69993 7.35557 5.69993 8.00001C5.69993 8.64445 5.92493 9.19445 6.37493 9.65001C6.82493 10.1056 7.37771 10.3333 8.03326 10.3333Z"
								fill="currentColor"
							/>
						</svg>
					</button>
					<button onClick={handleRowVisibilityClick(bar.id)} type="button">
						{hideFooterRow ? <svg width="20" height="17" viewBox="0 0 20 17" fill="none"
											  xmlns="http://www.w3.org/2000/svg">
							<path
								d="M16.4999 16.8333L12.9999 13.375C12.5138 13.5278 12.0242 13.6423 11.5311 13.7187C11.0381 13.7951 10.5277 13.8333 9.99989 13.8333C7.90267 13.8333 6.03461 13.2535 4.39572 12.0937C2.75683 10.934 1.56933 9.43054 0.833221 7.58331C1.12489 6.8472 1.49294 6.16317 1.93739 5.53123C2.38183 4.89929 2.88878 4.33331 3.45822 3.83331L1.16655 1.49998L2.33322 0.333313L17.6666 15.6666L16.4999 16.8333ZM9.99989 11.3333C10.1527 11.3333 10.295 11.3264 10.427 11.3125C10.5589 11.2986 10.7013 11.2708 10.8541 11.2291L6.35405 6.72915C6.31239 6.88192 6.28461 7.02429 6.27072 7.15623C6.25683 7.28817 6.24989 7.43054 6.24989 7.58331C6.24989 8.62498 6.61447 9.5104 7.34364 10.2396C8.0728 10.9687 8.95822 11.3333 9.99989 11.3333ZM16.0832 11.7083L13.4374 9.08331C13.5346 8.8472 13.611 8.60762 13.6666 8.36456C13.7221 8.12151 13.7499 7.86109 13.7499 7.58331C13.7499 6.54165 13.3853 5.65623 12.6561 4.92706C11.927 4.1979 11.0416 3.83331 9.99989 3.83331C9.72211 3.83331 9.46169 3.86109 9.21864 3.91665C8.97558 3.9722 8.736 4.05554 8.49989 4.16665L6.37489 2.04165C6.94433 1.80554 7.52767 1.62845 8.12489 1.5104C8.72211 1.39234 9.34711 1.33331 9.99989 1.33331C12.0971 1.33331 13.9652 1.91317 15.6041 3.0729C17.2429 4.23262 18.4304 5.73609 19.1666 7.58331C18.8471 8.40276 18.427 9.16317 17.9061 9.86456C17.3853 10.566 16.7777 11.1805 16.0832 11.7083ZM12.2291 7.87498L9.72906 5.37498C10.1179 5.30554 10.4756 5.33679 10.802 5.46873C11.1284 5.60067 11.4096 5.79165 11.6457 6.04165C11.8818 6.29165 12.052 6.57984 12.1561 6.90623C12.2603 7.23262 12.2846 7.55554 12.2291 7.87498Z" />
						</svg> : <svg width="15px" height="15px" viewBox="0 0 24 24">
							<path
								d="M12,4C4.1,4,0,12,0,12s3.1,8,12,8c8.1,0,12-8,12-8S20.1,4,12,4z M12,17c-2.9,0-5-2.2-5-5c0-2.8,2.1-5,5-5s5,2.2,5,5C17,14.8,14.9,17,12,17z M12,9c-1.7,0-3,1.4-3,3c0,1.6,1.3,3,3,3s3-1.4,3-3C15,10.4,13.7,9,12,9z"></path>
						</svg>}
					</button>
				</div>
				<ul className="rishi-builder__row-inner grid border-2 w-full bg-white"
					style={{ gridTemplateColumns: layout }}>
					{bar.columns.map(function(items, n) {
						const draggableId = `${bar.id}:${n}`
						return (
							<li className="!flex-1" key={n}>
								<DraggableItems items={items} draggableId={draggableId}>
									{items.map((item, index) => {
										// const draggableId = `${bar.id}:${item.id}`
										const draggableProps = {
											index,
											panelType: 'footer',
											item,
											onToggleVisibility: () => {
											},
										}
										return (
											<DraggableItem key={item} {...draggableProps}>
												<BuilderElement
													item={item}
													onRemove={() => {
														setList({
															[draggableId]: items.filter((id) => id !== item),
															'available-items': null,
														})
														panelsHelpers.close()
													}}
													isRowHidden={hideFooterRow}
												/>
											</DraggableItem>
										)
									})}
									<ItemSelector
										items={selectorItems}
										onSelect={(_item) => {
											setList({
												[draggableId]: [...new Set([...items, _item.id])],
											})
										}}
									/>
								</DraggableItems>
							</li>
						)
					})}
				</ul>
			</li>
		)
	)
}

export default styled(Row)`
	display: flex;
	flex-wrap: wrap;
	align-items: center;
	justify-content: space-between;
	position: relative;
	z-index: 1;
	margin: 0 0 25px 0;
`
