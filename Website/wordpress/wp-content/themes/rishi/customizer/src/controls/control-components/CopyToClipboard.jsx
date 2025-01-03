import { Tooltip } from "@components";
import styled from "@emotion/styled"
import { CopyToClipboard as Copy } from 'react-copy-to-clipboard';
import { useState, useEffect } from '@wordpress/element'

const CopyText = styled.div`
    padding: 10px;
    border: 1px solid var(--cw__border-color);
    border-radius: var(--cw__border-radius);
    background-color: var(--cw__background-color);
    color: #000000;
    font-style: italic;
    font-weight: 600;
    width: 100%;
`

const CopyToClipboard = ({ option }) => {
    const [isCopied, setIsCopied] = useState(false)

    useEffect(() => {
        setTimeout(() => {
            setIsCopied(false)
        }, 5000)
    }, [isCopied])

    return (
        <Copy text={option.value} onCopy={() => setIsCopied(true)}>
            <Tooltip title="Copied." visible={isCopied}>
                <CopyText>{option.value}</CopyText>
            </Tooltip>
        </Copy>
    )
}

export default CopyToClipboard