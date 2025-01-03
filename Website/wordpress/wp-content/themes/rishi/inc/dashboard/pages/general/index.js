import { applyFilters } from '@wordpress/hooks';
import { __ } from '@wordpress/i18n';
import { Card, CardBody, Column, Link, Row } from "../../components";
import SidebarInfo from "../sidebarinfo";
import styled from '@emotion/styled'
export default () => {
    const customizerOptions = [
        {
            icon: require('../../../assets/images/search engine website.png'),
            title: __('Site Identity', 'rishi'),
            link_to: "customize.php?autofocus[section]=header",
            link_text: __('Customize', 'rishi')
        },
        {
            icon: require('../../../assets/images/color settings.png'),
            title: __('Color Settings', 'rishi'),
            link_to: "customize.php?autofocus[section]=colors_panel",
            link_text: __('Customize', 'rishi')
        },
        {
            icon: require('../../../assets/images/typography.png'),
            title: __('Typography', 'rishi'),
            link_to: "customize.php?autofocus[section]=typography-section",
            link_text: __('Go to Options ', 'rishi')
        },
        {
            icon: require('../../../assets/images/layout settings.png'),
            title: __('Global Settings', 'rishi'),
            link_to: "customize.php?autofocus[panel]=main_global_settings",
            link_text: __('Go to Options', 'rishi')
        },
        {
            icon: require('../../../assets/images/header builder.png'),
            title: __('Header Builder', 'rishi'),
            link_to: "customize.php?autofocus[section]=header",
            link_text: __('Customize', 'rishi')
        },
        {
            icon: require('../../../assets/images/blog settings.png'),
            title: __('Blog Settings', 'rishi'),
            link_to: "customize.php?autofocus[section]=blog-section",
            link_text: __('Customize', 'rishi')
        },
        {
            icon: require('../../../assets/images/page settings.png'),
            title: __('Page Settings', 'rishi'),
            link_to: "customize.php?autofocus[section]=pages-section",
            link_text: __('Customize', 'rishi')
        },
        {
            icon: require('../../../assets/images/sidebar settings.png'),
            title: __('Sidebar Settings', 'rishi'),
            link_to: "customize.php?autofocus[section]=sidebar-panel",
            link_text: __('Go to Options', 'rishi')
        },
        {
            icon: require('../../../assets/images/footer builder.png'),
            title: __('Footer Builder', 'rishi'),
            link_to: "customize.php?autofocus[section]=footer",
            link_text: __('Customize', 'rishi')
        },
    ]

    const Img = styled.img`
        margin-inline: auto;
        margin-bottom: 24px !important;
    `
    const Title = styled.div`
        font-size: 1.125em;
        font-weight: 600;
        color: #2D3039;
    `
    return <>
        <div className="rishi-main-wrapper">
            <Card size={'lg'} style={{ margin: 0 }}>
                <Row>
                    {applyFilters('rishi_license_activation_placeholder', null)}
                    <Column width={{ lg: '70%', md: '100%' }}>
                        <CardBody padding="0px">
                            <Row gap={{ gap: '20px', rowGap: '20px' }}>
                                {
                                    customizerOptions.map(({ icon, title, link_to, link_text }, index) => {
                                        return (
                                            <Column width={{ lg: '33%', md: '100%' }} key={index}>
                                                <Card style={{ margin: 0 }}>
                                                    <CardBody className="text-center has-border" padding='32px' radius="32px">
                                                        <Img src={icon} />
                                                        <Title><p>{title}</p></Title>
                                                        <Link to={link_to} target="_blank" rel="nofollow" arrow={true}>{link_text}</Link>
                                                    </CardBody>
                                                </Card>
                                            </Column>
                                        )
                                    })
                                }
                            </Row>
                        </CardBody>
                    </Column>
                    <SidebarInfo />
                </Row>
            </Card>
        </div>
    </>
}
