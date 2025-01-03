import styled from "@emotion/styled"

const InputField = styled.div`
	&[data-type="social"], &[data-type="link"]{
		position: relative;
		&::before{
			content: "https://";
			color: #42474B;
			position: absolute;
			left: 0;
			top: 0;
			height: 100%;
			padding: 10.5px 10px;
			background-color: var(--cw__background-color);
			border-radius: var(--cw__border-radius) 0 0 var(--cw__border-radius);
			display: flex;
    		align-items: center;
			border: 1px solid var(--cw__border-color);
			border-right: 0;
			opacity: .5;
		}
		input{
			padding-left: 75px !important;
		}
	}
`

const Text = ({ onChange, type, dataType, ...props }) => {
	type = dataType == "link" ? "url" : type;
	return <InputField className="cw__input-field" data-type={dataType}>
		<input type={type || 'text'} onChange={(e) => onChange(e.target.value)} {...props} />
	</InputField>
}

export default Text
