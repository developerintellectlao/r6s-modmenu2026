import classnames from 'classnames'
import { Tooltip } from '@components'
import styled from '@emotion/styled'

const LayoutOptions = styled.div`
	display: flex;
	flex-wrap: wrap;
	margin: 0 -8px;
	row-gap: 16px;
	> div,
	&[data-usage='menu-type'] > div {
		width: 100%;
		padding: 0 8px;
		&.active {
			.rishi-layout-item {
				border-color: var(--cw__secondary-color);
				background-color: var(--cw__background-color);
				color: var(--cw__secondary-color);
			}
		}
	}
	&[data-columns='2'] > div,
	> div {
		width: 50%;
	}
	&[data-columns='3'] > div {
		width: 33.33%;
	}
	&[data-columns='4'] > div {
		width: 25%;
	}
	&.rishi-columns-layout {
		.rishi-layout-item {
			height: 74px;
		}
	}
`

const LayoutOptionItem = styled.label`
	width: 100%;
	padding: 8px;
	border-radius: var(--cw__border-radius);
	border: 1px solid var(--cw__border-color);
	background-color: #ffffff;
	min-height: 44px;
	display: flex;
	justify-content: center;
	align-items: center;
	svg {
		width: 100%;
	}
	&:hover {
		background-color: var(--cw__background-color);
	}
`

const LayoutButton = ({ title, className, children, ...rest }) => {
	return (
		<Tooltip className={className} title={title}>
			<LayoutOptionItem className="rishi-layout-item" {...rest}>
				{children}
			</LayoutOptionItem>
		</Tooltip>
	)
}

const ImagePicker = ({ option: { choices, tabletChoices, mobileChoices, size }, option, device, value, onChange }) => {
	const { className, ...attr } = { ...(option.attr || {}) }

	let deviceChoices = option.choices

	if (device === 'tablet' && tabletChoices) {
		deviceChoices = tabletChoices
	}

	if (device === 'mobile' && mobileChoices) {
		deviceChoices = mobileChoices
	}

	const handleSelect = (val) => (e) => {
		if (e.type === 'click' || (e.type === 'keydown' && e.key === 'Enter')) {
			onChange(val)
		}
	}

	return (
		<LayoutOptions {...attr} className={`${className || ''}`} {...(option.title && null ? { 'data-title': '' } : {})}>
			{(Array.isArray(deviceChoices)
				? deviceChoices
				: Object.keys(deviceChoices).map((choice) => ({
						key: choice,
						...deviceChoices[choice],
				  }))
			).map((choice) => (
				<LayoutButton
					tabIndex={0}
					title={choice?.title}
					className={classnames({
						active: choice.key === value,
					})}
					onClick={handleSelect(choice.key)}
					onKeyDown={handleSelect(choice.key)}
					key={choice.key}
				>
					{choice.src.indexOf('<svg') === -1 ? (
						<img src={choice.src} style={{ width: size, height: size }} />
					) : (
						<span
							dangerouslySetInnerHTML={{
								__html: choice.src,
							}}
						/>
					)}
				</LayoutButton>
			))}
		</LayoutOptions>
	)
}

export default ImagePicker
