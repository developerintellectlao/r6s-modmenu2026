import Icons from '../assets/Icons'
import styled from '@emotion/styled'

const IconStyle = styled.span`
	margin-right: 6px;
	color: #2B3034;
	svg{
		fill: currentColor;
	}
`

const InnerControlWrapper = (
	{
		icon,
		label,
		children,
		direction,
		enabled,
		onVisibilityClick,
		prevValue,
		currValue,
		onRevertClick,
		hasOptions,
		className,
		...rest
	}
) => {
	const _prevValue = JSON.stringify(prevValue)
	const _currValue = JSON.stringify(currValue)
	return (
		<div className={`cw__control-item ${direction || ''} ${className || ''}`} {...rest}>
			{label && (
				<div className={`rishi-control_header${!hasOptions ? ' no-divider' : ''}`}>
					<label>
						{icon && <IconStyle className="cw__icon" dangerouslySetInnerHTML={{ __html: icon }} />}
						{label}
					</label>
					{(typeof enabled === 'boolean' || _prevValue !== _currValue) && (
						<div className="cw__action-buttons">
							{_prevValue !== _currValue && (
								<button type="button" className="cw__reset-button" onClick={() => onRevertClick(prevValue)} />
							)}
							<button type="button" className="cw__visibility-button" data-enabled={enabled} onClick={onVisibilityClick}>
								{enabled ? Icons.eye : Icons.visibility_off}
							</button>
						</div>
					)}
				</div>
			)}
			<div className="rishi-control_wrapper">{children}</div>
		</div>
	)
}

export default InnerControlWrapper
