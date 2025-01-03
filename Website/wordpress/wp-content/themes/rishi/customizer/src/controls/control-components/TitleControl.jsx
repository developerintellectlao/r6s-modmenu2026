import styled from '@emotion/styled'
import { Fragment } from '@wordpress/element'

const TitleStyle = styled.div`
	margin: 0 -12px 0;
	h3{
		font-size: 12px !important;
		line-height: 1.33;
		font-weight: 600;
		color: #717578;
		padding: 8px 16px;
		text-transform: uppercase;
		border-top: 1px solid var(--cw__border-color);
		border-bottom: 1px solid var(--cw__border-color);
		background-color: #F6F6F6;
	}
	.rishi-option-description{
		font-size: 13px;
		line-height: 1.33;
		color: #42474B;
		margin: 8px 0 0;
		padding: 0 16px 16px;
		a{
			color: var(--cw__secondary-color);
			font-weight: 500;
			&:hover{
				text-decoration: underline;
			}
		}
	}
	&:first-of-type{
		h3{
			border-top: none;
		}
	}
`

const Title = ({
	option: { label = '', desc = '', attr = {}, variation = 'simple' }
}) => (
	<Fragment>
		<TitleStyle
			className="rishi-title"
			{...{
				'data-type': variation,
				...(attr || {})
			}}>
			{label && <h3>{label}</h3>}
			{desc && (
				<div className="rishi-option-description"
					dangerouslySetInnerHTML={{
						__html: desc
					}}
				/>
			)}
		</TitleStyle>
	</Fragment>
)

Title.config = { design: 'none' }

export default Title
