import styled from "@emotion/styled"
import Tippy from "@tippyjs/react"
import isPropValid from '@emotion/is-prop-valid';
import 'tippy.js/animations/scale.css'
import 'tippy.js/animations/shift-away.css'
import 'tippy.js/dist/tippy.css'

const CardItem = styled('div', {
    shouldForwardProp: (prop) => isPropValid(prop) && prop !== 'loading',
})`
@keyframes skeleton-loading {
    0% {
    background-color: hsl(200, 20%, 90%);
    }
    100% {
    background-color: hsl(200, 20%, 95%);
    }
}
border: 1px solid #EAECF0;
border-radius: 12px;
display: flex;
flex-direction: column;
position: relative;
${props => props.loading && `
    overflow: hidden;
    > div *, > *:not(div){
        position: relative;
        overflow: hidden;
        display: inline-flex;
        border-radius: 8px;
        border: none;
        pointer-events: none;
        &::before{
            content: "";
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            z-index: 1;
            animation: skeleton-loading 1s linear infinite alternate;
        }
    }`
}
.tippy-box{
    box-shadow: 0px 4px 6px -2px #10182808, 0px 12px 16px -4px #10182814;
    border-radius: 8px;

    &[data-placement="bottom"]{
        box-shadow: 0px -4px 6px -2px #10182808, 0px -12px 16px -4px #10182814;
    }

    h1, h2, h3, h4, h5, h6{
        margin: 0 0 4px;
    }
    .tippy-content{
        width: 318px;
        padding: 12px;
    }
    &[data-theme="light"]{
        background-color: #ffffff;
        color: #637279;
        h1, h2, h3, h4, h5, h6{
            color: #2D3039;
        }
        .tippy-arrow{
            color: #ffffff;
        }
    }
}
`

const CardHeader = styled.div`
    position: relative;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
`

const CardPluginHeader = styled.div`
    position: relative;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 12px;
    margin-bottom: 24px;

`

const CardBody = styled.div`
    padding: 24px;
    flex: 1;
    font-size: 16px;
    line-height: 1.75;
    position: relative;
`

const CardFooter = styled.div`
    padding: 16px 24px;
    border-top: 1px solid #EAECF0;
    position: relative;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 8px;
    a{
        font-weight: 600;
        color: #307AC9;
        text-decoration: none;
        font-size: 14px;
        &:hover{
            text-decoration: underline;
        }
    }
`

const CardTitle = styled.h5`
    font-size: 20px;
    font-weight: 400;
    line-height: 1.5;
    color: #637279 !important;
    margin: 0 0 8px !important;
    position: relative;
`

const CardPluginTitle = styled.h5`
    font-size: 18px;
    font-weight: 600;
    line-height: 1.5;
    color: #2D3039;
`

const InfoButton = styled.button`
    background: none;
    padding: 0;
    border: none;
    cursor: pointer;
    &:hover{
        path{
            fill: #307AC9;
        }
    }
`

const InfoButtonWrapper = styled.div`
    position: absolute;
    top: 20px;
    right: 20px;
    z-index: 1;
`

const Card = ({ children, info, isLoading, ...rest }) => {
    return <CardItem loading={isLoading} {...rest}>
        {
            info && <InfoButtonWrapper>
                <Tippy content={info} placement="top" theme="light" interactive allowHTML trigger="click">
                    <InfoButton type="button">
                        <svg width="21" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <mask id="mask0_68_3458" style={{ maskType: "alpha" }} maskUnits="userSpaceOnUse" x="0" y="0" width="21" height="20">
                                <rect x="0.660156" width="20" height="20" fill="#D9D9D9" />
                            </mask>
                            <g mask="url(#mask0_68_3458)">
                                <path d="M10.66 14.1666C10.8961 14.1666 11.094 14.0868 11.2537 13.927C11.4135 13.7673 11.4933 13.5694 11.4933 13.3333C11.4933 13.0972 11.4135 12.8993 11.2537 12.7395C11.094 12.5798 10.8961 12.5 10.66 12.5C10.4239 12.5 10.226 12.5798 10.0662 12.7395C9.90652 12.8993 9.82666 13.0972 9.82666 13.3333C9.82666 13.5694 9.90652 13.7673 10.0662 13.927C10.226 14.0868 10.4239 14.1666 10.66 14.1666ZM9.82666 10.8333H11.4933V5.83329H9.82666V10.8333ZM10.66 18.3333C9.50722 18.3333 8.42388 18.1145 7.40999 17.677C6.3961 17.2395 5.51416 16.6458 4.76416 15.8958C4.01416 15.1458 3.42041 14.2638 2.98291 13.25C2.54541 12.2361 2.32666 11.1527 2.32666 9.99996C2.32666 8.84718 2.54541 7.76385 2.98291 6.74996C3.42041 5.73607 4.01416 4.85413 4.76416 4.10413C5.51416 3.35413 6.3961 2.76038 7.40999 2.32288C8.42388 1.88538 9.50722 1.66663 10.66 1.66663C11.8128 1.66663 12.8961 1.88538 13.91 2.32288C14.9239 2.76038 15.8058 3.35413 16.5558 4.10413C17.3058 4.85413 17.8996 5.73607 18.3371 6.74996C18.7746 7.76385 18.9933 8.84718 18.9933 9.99996C18.9933 11.1527 18.7746 12.2361 18.3371 13.25C17.8996 14.2638 17.3058 15.1458 16.5558 15.8958C15.8058 16.6458 14.9239 17.2395 13.91 17.677C12.8961 18.1145 11.8128 18.3333 10.66 18.3333Z" fill="#98A2B3" />
                            </g>
                        </svg>
                    </InfoButton>
                </Tippy>
            </InfoButtonWrapper>
        }
        {children}
    </CardItem>
}

Card.CardHeader = CardHeader;
Card.CardBody = CardBody;
Card.CardFooter = CardFooter;
Card.CardTitle = CardTitle;
Card.cardPluginTitle = CardPluginTitle;
Card.CardPluginHeader = CardPluginHeader

export default Card
