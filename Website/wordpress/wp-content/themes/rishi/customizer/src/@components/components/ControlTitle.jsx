import styled from "@emotion/styled"

const ControlTitle = styled.div`
    padding: 8px 16px;
    font-size: 12px;
    color: #717578;
    background-color: #F6F6F6;
	margin: 0 -12px 12px;
	border-top: 1px solid var(--cw__border-color);
	border-bottom: 1px solid var(--cw__border-color);
`

export default ({title}) => {
    return <ControlTitle className="cw__control-title">{title}</ControlTitle>
}
