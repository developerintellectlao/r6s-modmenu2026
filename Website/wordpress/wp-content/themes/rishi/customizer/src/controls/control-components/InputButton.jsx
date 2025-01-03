import styled from "@emotion/styled"

const ButtonWrapper = styled.div`
	input.rishi-button{
		padding: 10px 16px;
		font-weight: 600;
		border-radius: 4px;
		background-color: var(--cw__secondary-color);
		color: #ffffff;
		&:hover, &:focus{
			background-color: #0f52b7;
		}
		&.full{
			width: 100%;
		}
	}
`


const InputButton = ({ option: { input_attrs, type, size } }) => {
	const { class: cls, ...attrs } = input_attrs;
	return (
		<ButtonWrapper>
			<input type={type || 'button'} className={`rishi-button ${cls || ''} ${size || ''}`} {...attrs} />
		</ButtonWrapper>
	)
}

export default InputButton
