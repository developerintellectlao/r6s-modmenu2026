import styled from "@emotion/styled";

const StyleProvider = styled.div`
    .select-input{
        position: relative;
        width: 100%;
        &::after{
            position: absolute;
            pointer-events: none;
            font: 400 10px/10px dashicons;
            content: "\\f341";
            width: 10px;
            height: 10px;
            top: calc(50% - 5px);
            right: 5px;
            transform: rotate(-90deg);
        }
        &[aria-expanded=true]{
            &::after{
                transform: rotate(90deg);
            }
        }
    }
    [data-design=inline]{
        .select-input, .option-input{
            max-width: 120px;
        }
    }
    input{
        border: 1px solid #ddd !important;
        &[type=text], &[type=number]{
            --fontSize: 15px;
            height: 40px;
            width: var(--width, 100%) !important;
            margin: var(--margin, 0) !important;
            padding: var(--padding, 3px 8px) !important;
            min-height: initial;
            font-size: var(--fontSize) !important;
            color: var(--primaryColor);
            line-height: normal;
            background-color: var(--background, #fff);
            border: none;
            border-radius: 6px;
            box-shadow: 0 0 0 0 transparent;
            transition: box-shadow 0.1s linear, background 0.1s linear, border-radius 0.1s linear, border-color 0.1s linear;
        }
    }
`

export default StyleProvider