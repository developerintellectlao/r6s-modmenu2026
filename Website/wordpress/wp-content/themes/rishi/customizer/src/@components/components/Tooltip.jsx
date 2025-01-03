import styled from '@emotion/styled'
import Tippy from '@tippyjs/react'

const TooltipStyle = styled.div`
	display: flex;
	cursor: pointer;
	&:hover {
		color: var(--cw__secondary-color);
	}
	.wc__tooltip {
		display: block !important;
	}
`
export default ({ children, className, title, onClick, ...rest }) => {
	return (
		<TooltipStyle className={className} onClick={() => typeof onClick === 'function' && onClick()}>
			<Tippy className="wc__tooltip" content={title} disabled={!title} arrow {...rest}>
				{children}
			</Tippy>
		</TooltipStyle>
	)
}
