import { __ } from '@wordpress/i18n';
import classnames from 'classnames';
import { Card, CardBody, CardHeader, Column, LinkBtn } from "../../components";

export default () => {
    const sidebarDetails = [
        {
            title: __('Documentation', 'rishi'),
            description: __('Need help with using the WordPress as quickly as possible? Visit our well-organized Knowledge Base!', 'rishi'),
            link_to: rishi_dashboard.dash_links.docs,
            link_text: __('Documentation', 'rishi'),
            mb: true,
            border_bottom: true,
            icon: {
                before: 'file',
                after: 'chevron_right'
            },
            padding: '0px 0px 30px 0px',
            display: rishi_dashboard.dash_links.hide_doc_link
        },
        {
            title: __('Support', 'rishi'),
            description: __("If the Knowledge Base didn't answer your queries, submit us a support ticket here. Our response time usually is less than a business day, except on the weekends.", 'rishi'),
            link_to: rishi_dashboard.dash_links.support,
            link_text: __('Submit a Ticket', 'rishi'),
            mb: false,
            border_bottom: false,
            icon: {
                before: 'ticket',
                after: 'chevron_right'
            },
            padding: '0px 0px 30px 0px',
            display: rishi_dashboard.dash_links.hide_support_link
        },
        {
            title: __('Video Tutorials', 'rishi'),
            description: __('Check our step by step video tutorials.', 'rishi'),
            link_to: rishi_dashboard.dash_links.tutorial,
            link_text: __('Watch Videos', 'rishi'),
            mb: false,
            border_bottom: false,
            icon: {
                before: 'play_circle',
                after: 'chevron_right'
            },
            padding: '0px 0px 30px 0px',
            display: rishi_dashboard.dash_links.hide_video_link
        },

    ]
    return <>
        <Column width={{ lg: '30%', md: '100%' }}>
            {
                sidebarDetails.map(({ title, description, link_text, link_to, mb, icon, padding, border_bottom, display }, index) => {
                    return ( !display &&
                        <Card style={{ margin: 0 }} key={index}>
                            <CardBody className={classnames({'m-bottom-sm':mb}, {'has-border-bottom':border_bottom})} padding={padding} radius='0px'>
                                <CardHeader title={title} spacing={{ pb: '0px', mb: '8px' }} fontVariant="sidebar"/>
                                <p>{description}</p>
                                <LinkBtn to={link_to} target="_blank" rel="nofollow" icon={icon} >{link_text}</LinkBtn>
                            </CardBody>
                        </Card>
                    )
                })
            }
        </Column>
    </>
}
