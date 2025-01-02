import styled from "@emotion/styled"
import { __ } from "@wordpress/i18n";

const LicenseNote = styled.p`
    background: #f4f9ff;
    border: 1px dashed #216bdb;
    padding: 16px 25px 16px 20px;
    border-radius: 6px;
    max-width: max-content;
`
const LicenseCard = styled.div`
    & div + div {
        margin-top: 1.625em;
    }
`

const LicenseUI = ({ children, isLoading, ...rest }) => {
    return (
        <>
            <LicenseNote>
                {__("You can find your license key from ", "rishi-companion")}<a target="_blank" href="https://rishitheme.com/my-account/">{__("your account ", "rishi-companion")}</a>{__("on our website. The license key is required to receive updates and support.", "rishi-companion")}
            </LicenseNote>
            <LicenseCard>
                {children}
            </LicenseCard>
        </>

    )
}

export default LicenseUI