import styled from '@emotion/styled'
import { Transition, animated } from '@react-spring/web'
import { createPortal, useEffect } from '@wordpress/element'
import bezierEasing from 'bezier-easing'
import ControlsContainer from './ControlsContainer/ControlsContainer'
import { usePanelContext } from './ControlsContainer/context'
import Switch from './SwitchControl'

const ArrowButton = styled.button`
	&:hover {
		color: var(--cw__secondary-color);
	}
`

export const PanelMetaWrapper = ({ id, option, value, children }) => {
	const { panelsState, panelsHelpers, panelsDispatch, containerRef } = usePanelContext()

	const selfPanelId = id

	useEffect(() => {
		if (panelsState.previousPanel) {
			return
		}

		if (!panelsHelpers.isTransitioningFor(id)) {
			return
		}

		if (panelsHelpers.isOpenFor(id)) {
			if (!panelsHelpers.getWrapperParent().querySelector('.customize-virtual-section')) {
				const wrapper = document.createElement('div')
				wrapper.classList.add('customize-virtual-section')
				panelsHelpers.getWrapperParent().appendChild(wrapper)
			}

			if (panelsHelpers.getParentOptionsWrapper()) {
				panelsHelpers.getParentOptionsWrapper().classList.add('rishi-panel-open')
			}

			const h3 =
				containerRef.current.closest('ul') && containerRef.current.closest('ul').querySelector('.customize-section-description-container h3')

			panelsDispatch({
				type: 'SET_PANEL_TITLE',
				payload: {
					titlePrefix: h3 ? `${h3.querySelector('span').innerText} ▸ ${h3.innerText.split('\n')[h3.innerText.split('\n').length - 1]}` : '',
				},
			})
		} else {
			if (panelsHelpers.getParentOptionsWrapper()) {
				panelsHelpers.getParentOptionsWrapper().classList.remove('rishi-panel-open')
			}
		}
	}, [panelsState.previousPanel, id, panelsHelpers.isOpenFor(id)])

	useEffect(() => {
		return () => {
			;[...document.querySelectorAll('.rishi-panel-open:not(.open)')].map((el) => el.classList.remove('rishi-panel-open'))
		}
	}, [])

	const handleOpen = () => panelsHelpers.open(id)

	if (typeof children === 'function') {
		return children({
			open: handleOpen,
			wrapperAttr: {
				className: `rishi-panel ${!option.switch || value === 'yes' ? 'rishi-click-allowed' : ''}`,
				onClick: ({ target }) => {
					if ((option.switch && value !== 'yes') || target.closest('.customize-virtual-section')) {
						return
					}
					panelsHelpers.open(selfPanelId)
				},
			},
		})
	}
}

export const PanelContainer = ({ option, id, onChangeFor, getValues }) => {
	const maybeLabel = option.label || id.replace(/./, (s) => s.toUpperCase()).replace(/\_|\-/g, ' ');

	const {
		panelsState: { titlePrefix, previousPanel },
		panelsHelpers,
		containerRef,
	} = usePanelContext();

	const parentElement = containerRef.current && panelsHelpers.getWrapperParent().querySelector('.customize-virtual-section');

	if (!parentElement) return null;

	return createPortal(
		<Transition
			items={panelsHelpers.isOpenFor(id)}
			from={{ transform: 'translateX(100%)' }}
			enter={{ transform: 'translateX(0%)' }}
			leave={{ transform: `translateX(${previousPanel === id ? '-' : ''}100%)` }}
			config={() => ({ duration: 180, easing: bezierEasing(0.645, 0.045, 0.355, 1) })}
			onRest={(isOpen) => {
				panelsHelpers.stopTransitioning();
				if (!isOpen && !previousPanel) {
					Array.from(panelsHelpers.getWrapperParent().querySelectorAll('.customize-virtual-section')).forEach((el) => el.remove());
				}
			}}
		>
			{(props, isOpen) =>
				isOpen && (
					<animated.div className="rishi-customizer-panel-new rishi-controls-container">
						<div>
							<div className="customize-panel-actions">
								<button
									onClick={(e) => {
										e.stopPropagation();
										panelsHelpers.close();
									}}
									type="button"
									className="customize-section-back"
								/>
								<h3>
									<span>{titlePrefix}</span>
									{maybeLabel}
								</h3>
							</div>
							<div className="customizer-panel-content">
								<ControlsContainer
									purpose="customizer"
									onChange={onChangeFor}
									options={option['innerControls']}
									value={getValues()}
								/>
							</div>
						</div>
					</animated.div>
				)
			}
		</Transition>,
		parentElement
	);
};

const Panel = (props) => {
	const { id, getValues, values, onChangeFor, option, value, view = 'normal', onChange } = props

	const {
		panelsHelpers,
	} = usePanelContext()

	const selfPanelId = id

	if (view === 'simple') {
		return panelsHelpers.isTransitioningFor(id) || panelsHelpers.isOpenFor(id) ? (
			<PanelContainer id={id} getValues={() => (getValues ? getValues() : values)} onChangeFor={onChangeFor} option={option} />
		) : null
	}

	return (
		<>
			<div className="flex gap-2">
				{option.switch && <Switch value={value} onChange={onChange} onClick={(e) => e.stopPropagation()} />}

				{
					(!Object.keys(option).includes("switch") || value === "yes") &&
					<ArrowButton className="rishi-forward-button" type="button"
						onClick={
							({ target }) => !target.closest('.customize-virtual-section') && panelsHelpers.open(selfPanelId)
						}
					>
						<svg width="11" height="19" viewBox="0 0 11 19" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path
								d="M2.0129 0.709991L10.2629 8.95999L10.7774 9.49999L10.2622 10.04L2.01215 18.29L0.932154 17.21L8.64515 9.49999L0.935155 1.78999L2.0129 0.709991Z"
								fill="currentColor"
							/>
						</svg>
					</ArrowButton>
				}
			</div>

			{(panelsHelpers.isTransitioningFor(id) || panelsHelpers.isOpenFor(id)) && (
				<PanelContainer id={id} getValues={() => (getValues ? getValues() : values)} onChangeFor={onChangeFor} option={option} />
			)}
		</>
	)
}

Panel.config = {
	design: 'inline',
}

Panel.MetaWrapper = PanelMetaWrapper

export default Panel
