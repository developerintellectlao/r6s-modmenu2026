import styled from '@emotion/styled'
import classNames from 'classnames'
import _ from 'lodash'
import { useDeviceView, usePanelContext } from '../../ControlsContainer/context'
import DraggableItems, { DraggableItem } from '../common/DraggableItems'
import BuilderElement from '@layout-builder/composer-layout/BuilderElement'
import ItemSelector from '@layout-builder/common/InlineItemSelector'
import { useBuilderElements } from '@layout-builder/hooks'
import { useBuilderContext } from '../context'

const getRenderingItems = (position, placements) => {
	const items = placements[position]
	const renderingItems = [items]

	if (position !== 'middle') {
		const middleItems = placements?.middle?.items || []

		if (middleItems.length > 0) {
			if (position === 'start') {
				renderingItems.push(placements['start-middle'] || [])
			} else if (position === 'end') {
				renderingItems.push(placements['end-middle'] || [])
			}
		}
	}

	return renderingItems
}

const Row = ({ label, bar, direction = 'horizontal', className, positions = ['start', 'middle', 'end'] }) => {
	const { panelsHelpers } = usePanelContext()

	const { composerValue, composerDispatch, setList } = useBuilderContext()

	const [currentView] = useDeviceView()

	const currentValue = composerValue?.items.find((item) => item.id === bar.id)
	const hideHeaderRow = currentValue?.values?.header_hide_row ?? false

	const handleToggleClick = () => {
		composerDispatch({
			type: 'ON_CHANGE_ELEMENT_VALUE',
			payload: {
				id: bar.id,
				optionId: 'header_hide_row',
				optionValue: !hideHeaderRow,
			},
		})
		wp.customize?.previewer?.refresh()
	}

	const placements = _.keyBy(bar.placements, 'id')

	const headerElements = useBuilderElements()

	return (
		<li className={`rishi-builder__row ${className}${hideHeaderRow ? ' is-hidden' : ''}`}>
			<div className="__row-label">{label}</div>
			<div className="__row-actions">
				<button onClick={() => !hideHeaderRow && panelsHelpers.open(`builder_panel_${bar.id}`)} type="button"
						className="button-setting">
					<svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
						<path
							d="M495.9 166.6c3.2 8.7 .5 18.4-6.4 24.6l-43.3 39.4c1.1 8.3 1.7 16.8 1.7 25.4s-.6 17.1-1.7 25.4l43.3 39.4c6.9 6.2 9.6 15.9 6.4 24.6c-4.4 11.9-9.7 23.3-15.8 34.3l-4.7 8.1c-6.6 11-14 21.4-22.1 31.2c-5.9 7.2-15.7 9.6-24.5 6.8l-55.7-17.7c-13.4 10.3-28.2 18.9-44 25.4l-12.5 57.1c-2 9.1-9 16.3-18.2 17.8c-13.8 2.3-28 3.5-42.5 3.5s-28.7-1.2-42.5-3.5c-9.2-1.5-16.2-8.7-18.2-17.8l-12.5-57.1c-15.8-6.5-30.6-15.1-44-25.4L83.1 425.9c-8.8 2.8-18.6 .3-24.5-6.8c-8.1-9.8-15.5-20.2-22.1-31.2l-4.7-8.1c-6.1-11-11.4-22.4-15.8-34.3c-3.2-8.7-.5-18.4 6.4-24.6l43.3-39.4C64.6 273.1 64 264.6 64 256s.6-17.1 1.7-25.4L22.4 191.2c-6.9-6.2-9.6-15.9-6.4-24.6c4.4-11.9 9.7-23.3 15.8-34.3l4.7-8.1c6.6-11 14-21.4 22.1-31.2c5.9-7.2 15.7-9.6 24.5-6.8l55.7 17.7c13.4-10.3 28.2-18.9 44-25.4l12.5-57.1c2-9.1 9-16.3 18.2-17.8C227.3 1.2 241.5 0 256 0s28.7 1.2 42.5 3.5c9.2 1.5 16.2 8.7 18.2 17.8l12.5 57.1c15.8 6.5 30.6 15.1 44 25.4l55.7-17.7c8.8-2.8 18.6-.3 24.5 6.8c8.1 9.8 15.5 20.2 22.1 31.2l4.7 8.1c6.1 11 11.4 22.4 15.8 34.3zM256 336a80 80 0 1 0 0-160 80 80 0 1 0 0 160z" />
					</svg>
				</button>
				{'offcanvas' !== bar.id && (
					<button onClick={handleToggleClick} type="button">
						{hideHeaderRow ? <svg width="20" height="17" viewBox="0 0 20 17" fill="none"
										 xmlns="http://www.w3.org/2000/svg">
							<path
								d="M16.4999 16.8333L12.9999 13.375C12.5138 13.5278 12.0242 13.6423 11.5311 13.7187C11.0381 13.7951 10.5277 13.8333 9.99989 13.8333C7.90267 13.8333 6.03461 13.2535 4.39572 12.0937C2.75683 10.934 1.56933 9.43054 0.833221 7.58331C1.12489 6.8472 1.49294 6.16317 1.93739 5.53123C2.38183 4.89929 2.88878 4.33331 3.45822 3.83331L1.16655 1.49998L2.33322 0.333313L17.6666 15.6666L16.4999 16.8333ZM9.99989 11.3333C10.1527 11.3333 10.295 11.3264 10.427 11.3125C10.5589 11.2986 10.7013 11.2708 10.8541 11.2291L6.35405 6.72915C6.31239 6.88192 6.28461 7.02429 6.27072 7.15623C6.25683 7.28817 6.24989 7.43054 6.24989 7.58331C6.24989 8.62498 6.61447 9.5104 7.34364 10.2396C8.0728 10.9687 8.95822 11.3333 9.99989 11.3333ZM16.0832 11.7083L13.4374 9.08331C13.5346 8.8472 13.611 8.60762 13.6666 8.36456C13.7221 8.12151 13.7499 7.86109 13.7499 7.58331C13.7499 6.54165 13.3853 5.65623 12.6561 4.92706C11.927 4.1979 11.0416 3.83331 9.99989 3.83331C9.72211 3.83331 9.46169 3.86109 9.21864 3.91665C8.97558 3.9722 8.736 4.05554 8.49989 4.16665L6.37489 2.04165C6.94433 1.80554 7.52767 1.62845 8.12489 1.5104C8.72211 1.39234 9.34711 1.33331 9.99989 1.33331C12.0971 1.33331 13.9652 1.91317 15.6041 3.0729C17.2429 4.23262 18.4304 5.73609 19.1666 7.58331C18.8471 8.40276 18.427 9.16317 17.9061 9.86456C17.3853 10.566 16.7777 11.1805 16.0832 11.7083ZM12.2291 7.87498L9.72906 5.37498C10.1179 5.30554 10.4756 5.33679 10.802 5.46873C11.1284 5.60067 11.4096 5.79165 11.6457 6.04165C11.8818 6.29165 12.052 6.57984 12.1561 6.90623C12.2603 7.23262 12.2846 7.55554 12.2291 7.87498Z" />
						</svg> : <svg width="15px" height="15px" viewBox="0 0 24 24">
							<path
								d="M12,4C4.1,4,0,12,0,12s3.1,8,12,8c8.1,0,12-8,12-8S20.1,4,12,4z M12,17c-2.9,0-5-2.2-5-5c0-2.8,2.1-5,5-5s5,2.2,5,5C17,14.8,14.9,17,12,17z M12,9c-1.7,0-3,1.4-3,3c0,1.6,1.3,3,3,3s3-1.4,3-3C15,10.4,13.7,9,12,9z"></path>
						</svg>}
					</button>
				)}
			</div>
			<ul className="rishi-builder__row-inner flex">
				{positions.map((position) => {
					if (!placements[position]) return null
					const renderingItems = getRenderingItems(position, placements)

					return (
						<li
							key={renderingItems[0].id}
							className={`col-${renderingItems[0].id} flex justify-between ${renderingItems[0].id === 'middle' ? 'flex-1 max-w-[33.33%]' : 'flex-[2]'
							} ${renderingItems[0].id === 'end' ? 'flex-row-reverse' : ''}`}
							{...(renderingItems[0].id === 'middle' ? { 'data-count': renderingItems[0].items.length } : {})}
						>
							<>
								{renderingItems.map((item) => {
									const draggableId = `${bar.id}:${item.id}`
									return (
										<DraggableItems
											key={item.id}
											direction={direction}
											className={classNames('flex-auto', {
												[`rishi-${item.id.includes('-') ? 'secondary' : 'primary'}-column`]: position === 'middle',
												[`justify-end`]: item.id === 'end' || item.id === 'start-middle',
												[`justify-center`]: item.id === 'middle',
											})}
											draggableId={draggableId}
											items={item.items}
										>
											{item.items.map((el, index) => {
												const draggableProps = {
													index,
													panelType: 'header',
													item: el,
													onToggleVisibility: handleToggleClick,
												}
												return (
													<DraggableItem key={el} {...draggableProps}>
														<BuilderElement
															item={el}
															onRemove={() => {
																setList({
																	[draggableId]: item.items.filter((id) => id !== el),
																	'available-items': null,
																})
																panelsHelpers.close()
															}}
															isRowHidden={hideHeaderRow}
														/>
													</DraggableItem>
												)
											})}
											<ItemSelector
												items={headerElements}
												onSelect={(_item) => {
													setList({
														[draggableId]: [...new Set([...item.items, _item.id])],
													})
												}}
											/>
										</DraggableItems>
									)
								})}
							</>
						</li>
					)
				})}
			</ul>
		</li>
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
