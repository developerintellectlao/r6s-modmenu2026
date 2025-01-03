import classNames from 'classnames'
import { usePanelContext } from '../../ControlsContainer/context'
import { useBuilderContext } from '../context'

const BuilderElement = ({ item, className, onRemove, isRowHidden }) => {
	const { panelsHelpers } = usePanelContext()

	const { composerValue, builder, composerDispatch } = useBuilderContext()

	let itemValue = 'header' === builder ? composerValue.items.find(({ id }) => id === item) : composerValue.items[item]

	const itemData = rishi.themeData.builder_data[builder].find(({ id }) => {
		return id === item.split('~')[0]
	})
	if (!itemData) return null

	const handleToggleVisibility = (itemData) => (e) => {
		e.stopPropagation()
		let itemValue = 'header' === builder ? composerValue.items.find(({ id }) => id === item) : composerValue.items[item]

		!isRowHidden && composerDispatch({
			type: 'ON_CHANGE_ELEMENT_VALUE',
			payload: {
				id: item,
				optionId: itemData.config.visibilityKey,
				optionValue: !itemValue?.values[itemData?.config?.visibilityKey],
			},
		})
	}

	return (
		<div
			data-id={item}
			className={classNames('rishi-builder-item in-builder', className, {
				'is-hidden': itemValue?.values[itemData?.config?.visibilityKey],
			})}
			onClick={() => {
				!isRowHidden && panelsHelpers.open(`builder_panel_${item}`)
			}}
		>
			<div className="rishi-builder-item-content">
				{itemData?.config?.visibilityKey && (
					<button type="button" onClick={handleToggleVisibility(itemData)} className="rishi-visibility">
						{itemValue?.values[itemData?.config?.visibilityKey] ?
							<svg width="20" height="17" viewBox="0 0 20 17" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M16.4999 16.8333L12.9999 13.375C12.5138 13.5278 12.0242 13.6423 11.5311 13.7187C11.0381 13.7951 10.5277 13.8333 9.99989 13.8333C7.90267 13.8333 6.03461 13.2535 4.39572 12.0937C2.75683 10.934 1.56933 9.43054 0.833221 7.58331C1.12489 6.8472 1.49294 6.16317 1.93739 5.53123C2.38183 4.89929 2.88878 4.33331 3.45822 3.83331L1.16655 1.49998L2.33322 0.333313L17.6666 15.6666L16.4999 16.8333ZM9.99989 11.3333C10.1527 11.3333 10.295 11.3264 10.427 11.3125C10.5589 11.2986 10.7013 11.2708 10.8541 11.2291L6.35405 6.72915C6.31239 6.88192 6.28461 7.02429 6.27072 7.15623C6.25683 7.28817 6.24989 7.43054 6.24989 7.58331C6.24989 8.62498 6.61447 9.5104 7.34364 10.2396C8.0728 10.9687 8.95822 11.3333 9.99989 11.3333ZM16.0832 11.7083L13.4374 9.08331C13.5346 8.8472 13.611 8.60762 13.6666 8.36456C13.7221 8.12151 13.7499 7.86109 13.7499 7.58331C13.7499 6.54165 13.3853 5.65623 12.6561 4.92706C11.927 4.1979 11.0416 3.83331 9.99989 3.83331C9.72211 3.83331 9.46169 3.86109 9.21864 3.91665C8.97558 3.9722 8.736 4.05554 8.49989 4.16665L6.37489 2.04165C6.94433 1.80554 7.52767 1.62845 8.12489 1.5104C8.72211 1.39234 9.34711 1.33331 9.99989 1.33331C12.0971 1.33331 13.9652 1.91317 15.6041 3.0729C17.2429 4.23262 18.4304 5.73609 19.1666 7.58331C18.8471 8.40276 18.427 9.16317 17.9061 9.86456C17.3853 10.566 16.7777 11.1805 16.0832 11.7083ZM12.2291 7.87498L9.72906 5.37498C10.1179 5.30554 10.4756 5.33679 10.802 5.46873C11.1284 5.60067 11.4096 5.79165 11.6457 6.04165C11.8818 6.29165 12.052 6.57984 12.1561 6.90623C12.2603 7.23262 12.2846 7.55554 12.2291 7.87498Z" fill="currentColor" />
							</svg> :
							<svg width="20" height="14" viewBox="0 0 20 14" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M9.96424 10.7479C11.0059 10.7479 11.8913 10.3833 12.6205 9.65411C13.3497 8.92495 13.7142 8.03953 13.7142 6.99786C13.7142 5.9562 13.3497 5.07078 12.6205 4.34161C11.8913 3.61245 11.0059 3.24786 9.96424 3.24786C8.92258 3.24786 8.03716 3.61245 7.30799 4.34161C6.57883 5.07078 6.21424 5.9562 6.21424 6.99786C6.21424 8.03953 6.57883 8.92495 7.30799 9.65411C8.03716 10.3833 8.92258 10.7479 9.96424 10.7479ZM9.96424 9.24786C9.33924 9.24786 8.80799 9.02911 8.37049 8.59161C7.93299 8.15411 7.71424 7.62286 7.71424 6.99786C7.71424 6.37286 7.93299 5.84161 8.37049 5.40411C8.80799 4.96661 9.33924 4.74786 9.96424 4.74786C10.5892 4.74786 11.1205 4.96661 11.558 5.40411C11.9955 5.84161 12.2142 6.37286 12.2142 6.99786C12.2142 7.62286 11.9955 8.15411 11.558 8.59161C11.1205 9.02911 10.5892 9.24786 9.96424 9.24786ZM9.96424 13.2479C7.93647 13.2479 6.08924 12.6819 4.42258 11.5499C2.75591 10.418 1.54758 8.90064 0.797577 6.99786C1.54758 5.09509 2.75591 3.57772 4.42258 2.44578C6.08924 1.31384 7.93647 0.747864 9.96424 0.747864C11.992 0.747864 13.8392 1.31384 15.5059 2.44578C17.1726 3.57772 18.3809 5.09509 19.1309 6.99786C18.3809 8.90064 17.1726 10.418 15.5059 11.5499C13.8392 12.6819 11.992 13.2479 9.96424 13.2479Z" fill="currentColor" />
							</svg>
						}
					</button>
				)}
				{itemData?.config?.name}
				<button
					className="rishi-btn-remove hover:!text-red-400"
					onClick={(e) => {
						e.stopPropagation()
						!isRowHidden && onRemove()
					}}
				>
					<svg width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path
							d="M14.9999 5.99786L4.99988 15.9979M4.99988 5.99786L14.9999 15.9979"
							stroke="currentColor"
							strokeWidth="1.66667"
							strokeLinecap="round"
							strokeLinejoin="round"
						/>
					</svg>
				</button>
			</div>
			<div className="rishi-builder-item-background"></div>
			<div className="absolute -top-4">
				<button onClick={() => panelsHelpers.open(`builder_panel_${itemData.id}`)} />
			</div>
		</div>
	)
}

export default BuilderElement
