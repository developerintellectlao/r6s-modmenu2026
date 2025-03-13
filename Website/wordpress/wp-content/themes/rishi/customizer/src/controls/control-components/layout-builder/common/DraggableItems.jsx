import cls from 'classnames'
import Sortable from '../Sortable'
import { useBuilderContext } from '../context'

export const DraggableItem = ({ children }) => children

const DraggableItems = ({
	children,
	items,
	draggableId,
	className,
	tagName = 'div',
	direction = 'horizontal',
	group = 'header_sortables',
	options = {},
	propsForItem = (item) => ({}),
	...props
}) => {
	const { setIsDragging, onChange } = useBuilderContext()

	const handleStart = (event) => {
		setIsDragging(true)
		document.body.classList.add('rishi--builder-element--dragging')
		if (event.from && group && group.pull !== 'clone') {
			event.to.classList.add('rishi-is-over')
		}
	}

	const handleEnd = () => {
		setIsDragging(false)
		document.body.classList.remove('rishi--builder-element--dragging')
		document.querySelectorAll('.rishi-layout-composer .rishi-is-over').forEach((el) => el.classList.remove('rishi-is-over'))
	}

	const handleMove = (event) => {
		if (event.from.closest('#rishi-option-header-builder-items')) {
			event.from.querySelectorAll(`[data-id="${event.dragged.dataset.id}"]`).forEach((el) => {
				el.classList.remove('rt-builder-item')
				el.classList.add('rt-item-in-builder')
			})
		}
		document.querySelectorAll('.rishi-layout-composer .rishi-is-over').forEach((el) => el.classList.remove('rishi-is-over'))

		if (event.to) {
			event.to.classList.add('rishi-is-over')
		}
	}

	const handleChange = (order) => {
		onChange({
			id: draggableId,
			value: order.filter((i) => i !== '__inserter__'),
		})
	}

	return (
		<Sortable
			options={{
				group,
				fallbackOnBody: true,
				direction: direction,
				onStart: handleStart,
				onEnd: handleEnd,
				onMove: handleMove,
				...options,
			}}
			onChange={handleChange}
			tag={tagName}
			className={cls('rishi-builder-items', className)}
			data-id={draggableId}
			{...props}
		>
			{children}
		</Sortable>
	)
}

export default DraggableItems
