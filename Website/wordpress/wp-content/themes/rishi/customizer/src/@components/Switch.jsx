import styled from "@emotion/styled";

const SwitchStyles = styled.div`
    width: 40px;
    height: 22px;
    border-radius: 45px;
    background-color: #d1d1d1;
    position: relative;
    box-shadow: var(--cw__box-shadow);
    transition: var(--cw__transition);
    cursor: pointer;
    span{
        content: "";
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background-color: #ffffff;
        position: absolute;
        top: 2px;
        left: 2px;
        transition: var(--cw__transition);
        box-shadow: 2px 0px 4px rgba(0,0,0, .1)
    }
    &.checked{
        background-color: var(--cw__secondary-color);
        span{
            left: 20px;
            box-shadow: -2px 0px 4px rgba(0,0,0, .1)
        }
    }
`

const Switch = ({ onChange, value, onClick }) => {

	const handleSwitchOnKeyDown = (e) => {
		if (e.type === "keydown" && e.key === "Enter") {
			onChange(!value)
		}
	}

	return <SwitchStyles
		tabIndex={0}
		className={`cw__switch${value ? ' checked' : ''}`}
		onClick={(e) => {
			onClick && onClick(e)
			onChange(!value)
		}}
		onKeyDown={handleSwitchOnKeyDown}
	>
		<span></span>
	</SwitchStyles>
}

export default Switch
