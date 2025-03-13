import styled from "@emotion/styled"

const TitleStyle = styled.div`
	padding: 16px;
	margin: 0 -12px;
	h3{
		font-size: 20px !important;
		font-weight: 600;
		color: #2B3034;
	}
	.rishi-panel-description{
		margin: 8px 0 0;
		font-size: 13px;
		color: #42474B;
	}
`

const PanelTitle = ({
	option: { label = '', desc = '', attr = {}, variation = 'simple' }
}) => {
	return (
		<TitleStyle className="rishi-panel-title" {...{
			'data-type': variation,
			...(attr || {})
		}}>
			<h3>{label}</h3>
			{desc && (
				<div className="rishi-panel-description"
					dangerouslySetInnerHTML={{
						__html: desc
					}}
				/>
			)}
		</TitleStyle>
	)
}

PanelTitle.renderingConfig = { design: 'none' }

export default PanelTitle
