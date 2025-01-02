import styled from "@emotion/styled"

const SwitchWrapper = styled.div`
    @keyframes spin{to {transform: rotate(360deg)}}
    display: inline-flex;
    align-items: center;
    input[type="checkbox"]{
        all: unset;
        appearance: none;
        display: block;
        width: 36px;
        height: 20px;
        border-radius: 12px;
        background-color: #F2F4F7;
        position: relative;
        transition: all .2s ease;
        &::before{
            all: unset;
            content: "";
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background-color: #ffffff;
            position: absolute;
            top: 2px;
            left: 2px;
            box-shadow: 0px 1px 2px 0px #1018280F, 0px 1px 3px 0px #1018281A;
            transition: all .2s ease;
        }
        &:checked{
            background-color: #307AC9;
            &::before{
                left: 18px;
            }
        }
    }
    ${props => props.isLoading && (
        `
        pointer-events: none;
        opacity: .5;
        &::after{
            display: inline-block;
            vertical-align: middle;
            content: "";
            width: 1em;
            height: 1em;
            border-radius: 50%;
            border: 2px solid rgba(0, 0, 0, .2);
            border-top-color: currentColor;
            margin-left: 4px;
            animation: spin infinite .5s linear;
        }`
    )
    }
`

const Switch = ({ switch: _switch, onChange, isLoading }) => {
    return (
        <SwitchWrapper isLoading={isLoading}>
            <label><input type="checkbox" checked={_switch === "on" && true} onChange={e => onChange(e.target.checked ? "on" : "off")} /></label>
        </SwitchWrapper>
    )
}

export default Switch
