import styled from "@emotion/styled"

const ButtonItem = styled.button`
    @keyframes spin{to {transform: rotate(360deg)}}
    font-size: 14px;
    font-weight: 600;
    color: #637279;
    padding: 10px 24px;
    border: 1px solid #D0D5DD;
    transition: all .2s ease;
    cursor: pointer;
    background: none;
    border-radius: 8px;
    text-align: center;
    &:hover{
        background-color: #307AC9;
        color: #ffffff;
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

const Button = ({ children, ...rest }) => {
    return (
        <ButtonItem {...rest}>
            {children}
        </ButtonItem>
    )
}

export default Button
