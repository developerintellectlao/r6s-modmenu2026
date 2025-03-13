import styled from "@emotion/styled";
import Icons from "./assets/Icons";

const NumberStyles = styled.div`
    display: inline-flex;
    background-color: var(--cw__background-color);
    border-radius: var(--cw__border-radius);
    input[type=number]{
        padding: 4px !important;
        border: none !important;
        background: none !important;
        text-align: center;
        width: 40px !important;
        -moz-appearance: textfield;
        &::-webkit-outer-spin-button, &::-webkit-inner-spin-button{
            -webkit-appearance: none;
        }
    }
    button{
        border: none;
        background: none;
        padding: 10px;
        cursor: pointer;
        display: flex;
        align-items: center;
        &:hover{
            color: var(--cw__secondary-color);
        }
        &:disabled{
            cursor: not-allowed;
            pointer-event: none;
            color: var(--cw__inactive-color);
            opacity: .5;
        }
    }
`

const InputNumber = ({ value, min, max, onChange, step, ...rest }) => {
    const checkType = typeof value === "number";
    const updateStep = step || 1;

    const handleDecrease = () => {
        handleOnChange(+value - updateStep)
    }

    const handleIncrease = () => {
        handleOnChange(+value + updateStep)
    }

    const handleOnChange = (val) => {
        if (max && val > max) {
            onChange(max)
        } else if (min && val < min) {
            onChange(min)
        } else {
            onChange(val)
        }
    }

    return <NumberStyles className="cw__input-number-wrapper">
        <button disabled={min >= value} type="button" onClick={handleDecrease}>
            {Icons.minus}
        </button>
        <input
            type="number"
            value={value}
            onChange={(e) => onChange(e.target.value)}
            onBlur={(e) => handleOnChange(e.target.value)}
            min={min}
            max={max}
            onWheel={e => e.target.blur()}
            {...rest}
        />
        <button disabled={max <= value} type="button" onClick={handleIncrease}>
            {Icons.plus}
        </button>
    </NumberStyles>
}

export default InputNumber
