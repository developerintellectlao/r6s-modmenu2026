import { __return_null, isControlEnabledForDevice, isControlResponsive } from '@helpers'
import { ResponsiveControls } from '@controls'
import { Fragment, useState } from '@wordpress/element'
import classnames from 'classnames'
import deepEqual from 'deep-equal'
import { Icons } from '@components'
import styled from '@emotion/styled'
import { __ } from '@wordpress/i18n'
import classNames from 'classnames'
import { usePanelContext } from '../ControlsContainer/context'

const ControlHelpStyle = styled.button`
	margin: 0 8px;
	display: inline-flex;
	&:hover {
		color: var(--cw__secondary-color);
	}
`

export function withCompactHeader(Component) {
	return ({ wrapperAttr: { className, ...additionalWrapperAttr } = {}, ...props } = {}) => {
		const [showDescription, setShowDescription] = useState(props?.config?.showDescription ?? false)

		const { option, onChange, id, values, value, device, hasRevertButton, childComponentRef, onChangeFor } = props

		const { values: customizerValues } = usePanelContext()
		if (option.conditions) {
			const match = Object.entries(option.conditions).every(([key, _value]) => {
				return _value.split('|').includes(values[key]) || _value.split('|').includes(customizerValues[key])
			})
			if (!match) return null
		}

		const config = props.config
		const actualDesignType = config.design

		const { className: optionClassName, ...optionAdditionalWrapperAttr } = option.wrapperAttr || {}

		let { computeOptionValue, design } = config

		const disabledResetButton = deepEqual(
			computeOptionValue(config.showResponsiveControls ? option.value[device] : option.value),
			config.getValueForRevert
				? config.getValueForRevert({
					value,
					option,
					values,
					device,
				})
				: value ?? option.value
		)

		const LabelToolbar = Component.labelToolbar ?? __return_null
		const ControlEnd = Component.controlEnd ?? __return_null
		const description = (option?.help && <div dangerouslySetInnerHTML={{ __html: option.help }} className="cw__control-description" />) || null

		return (
			<Fragment>
				<div
					className={classnames('rishi-control', className, optionClassName, {})}
					data-design={actualDesignType}
					data-divider={option.divider || 'none'}
					{...{
						...((isControlResponsive(option) && !isControlEnabledForDevice(device, option.responsive)) || option.state === 'disabled'
							? { 'data-state': 'disabled' }
							: {}),
					}}
					{...{
						...optionAdditionalWrapperAttr,
						...additionalWrapperAttr,
					}}
				>
					<ControlHeader
						label={(option.label && option.label) || null}
						showResponsiveControls={config.showResponsiveControls}
						showRevertButton={config.showRevertButton}
						design={actualDesignType}
						labelToolbar={<LabelToolbar {...{ option, value: option.value, id, onChange }} />}
						performRevert={config.performRevert}
						disabledResetButton={disabledResetButton}
						childComponentRef={childComponentRef}
						onChangeFor={onChangeFor}
						option={option}
						onChange={onChange}
						device={device}
						onTooltipClick={() => setShowDescription(!showDescription)}
					/>

					{design === 'block' && showDescription && description}

					{((isControlResponsive(option) && isControlEnabledForDevice(device, option.responsive)) || !isControlResponsive(option)) && (
						<Fragment>
							{withCompactDesign(Component)(props)}
							<ControlEnd />
							{design === 'inline' && showDescription && description}
						</Fragment>
					)}
				</div>
			</Fragment>
		)
	}
}

function ControlHeader({
	label,
	showRevertButton,
	showResponsiveControls,
	labelToolbar,
	option: { help },
	option,
	performRevert,
	disabledResetButton,
	childComponentRef,
	onChangeFor,
	design,
	device,
	onChange,
	onTooltipClick,
}) {

	const handleRevertClick = () => {
		if (childComponentRef && childComponentRef.current) {
			childComponentRef.current.handleOptionRevert()
		}
		performRevert && performRevert({ onChangeFor })

		onChange(showResponsiveControls ? option.value[device] : option.value)
	}

	const actionButtons = [
		(showRevertButton && !disabledResetButton && (
			<button type="button" key="cw__reset-button" disabled={disabledResetButton} className="cw__reset-button" onClick={handleRevertClick} />
		)) ||
		null,
		(showResponsiveControls && (
			<ResponsiveControls
				key="cw__responsive-controls"
				showControls={'block' === design && showResponsiveControls}
				device={device}
				responsiveDescriptor={option.responsive}
			/>
		)) ||
		null,
	]

	return (
		<div className="rishi-control_header">
			{(label || help) && (
				<label>
					{label}
					{help && (
						<ControlHelpStyle type="button" onClick={onTooltipClick}>
							<i>{Icons.help}</i>
						</ControlHelpStyle>
					)}
				</label>
			)}
			{actionButtons.some(Boolean) && <div className="cw__action-buttons">{actionButtons}</div>}
		</div>
	)
}

export function withCompactDesign(Component) {
	return ({ config, ...props }) => {
		const {
			option,
			option: { id, label },
			device,
		} = props

		const link = option.link ? <a href={option.link} {...(option.linkAttr || {})} /> : null

		const actualDesignType = config.design

		const DeviceControls = () => {
			if (isControlResponsive(option, { ignoreHidden: true }) && actualDesignType === 'inline') {
				return (
					<ResponsiveControls
						device={device}
						responsiveDescriptor={option.responsive}
					/>
				)
			}
			return null
		}

		return (
			<div
				{...(option.sectionAttr || {})}
				className={classNames(
					'rishi-control_wrapper',
					{
						'rishi-responsive-container': isControlResponsive(option, { ignoreHidden: true }) && actualDesignType === 'inline',
					},
					Component.sectionClassName ? Component.sectionClassName() : ''
				)}
			>
				{label && 'compact' === actualDesignType && <label>{label || id}</label>}

				<DeviceControls />
				<Component {...props} />

				{link}
			</div>
		)
	}
}
