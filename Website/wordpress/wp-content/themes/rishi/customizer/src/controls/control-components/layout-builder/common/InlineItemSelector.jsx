import { Popover, Text } from '@components'
import { Button } from '@wordpress/components'
import { useState } from '@wordpress/element'
import { useDeviceView } from '../../ControlsContainer/context'
import { useBuilderContext } from '../context'

const PopoverContent = ({ items, itemsInUsed, onSelect }) => {

	const [search, setSearch] = useState('')

	const handleSearch = (keyword) => setSearch(keyword.toLowerCase())

	const selectorItems = items.filter(({ config }) => !!config.name.toLowerCase().match(search))

	return (
		<>
			<div className="rishi-popover-search">
				<Text type="search" placeholder="Search" onChange={handleSearch} />
			</div>
			<div className="rishi-builder-items">
				{selectorItems.map((item) => {
					const { id, config } = item
					return (
						<div key={id} data-id={id} className="rishi-builder-item">
							<div className="rishi-builder-item-content"
								 data-disabled={itemsInUsed.includes(id)}
								 onClick={onSelect(item)}>
								{config.name || 'Element'}
							</div>
						</div>
					)
				})}
			</div>
		</>
	)
}

const ItemSelector = ({ onSelect, items }) => {
	const { itemsInUsed } = useBuilderContext()

	const [currentView] = useDeviceView()

	const handleClick = (item) => () => {
		typeof onSelect === 'function' && onSelect(item)
	}

	const selectorItems = items.filter(
		(_item) => _item.config.devices.includes(('tablet' === currentView ? 'mobile' : currentView)) && !_item.is_primary,
	)

	return (
		<div data-id="__inserter__">
			<Popover
				className="rishi-builder-popover"
				content={<PopoverContent items={selectorItems} itemsInUsed={itemsInUsed} onSelect={handleClick} />}
				placement="top"
				arrow={false}
			>
				<Button data-id="__inserter__" variant="secondary">+</Button>
			</Popover>
		</div>
	)
}

export default ItemSelector
