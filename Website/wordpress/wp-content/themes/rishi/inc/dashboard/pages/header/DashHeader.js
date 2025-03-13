import React from 'react'
import logo from "../../../assets/images/rishi-logo-main-nav.png";
import Icon from "../../icons";
import { IconLink, IconLinkGroup, Row, Column } from "../../components";
import { __ } from '@wordpress/i18n'
import rishi_main_logo from '../../../assets/images/rishi-logo-main.png'
function DashHeader() {
	return (
		<div className='rishi-ad_header'>
			<div className='rishi-ad_container'>
				<div className='rishi-ad_head'>
					<div className='rishi-ad_header-brand'>
						{
							rishi_dashboard.dash_header_data.has_theme_name
								? <p className='custom-name'>{rishi_dashboard.dash_header_data.has_theme_name}</p>
								: <img id="dash-logo" src={logo} alt={__("Rishi", 'rishi')} />
						}
					</div>
					<div className='rishi-ad_header-right'>
						<div className='rishi-version-control'>
							{rishi_dashboard.ThemeVersion}
						</div>
						<IconLinkGroup>
							<IconLink title={__("Website", 'rishi')} target="_blank" href={rishi_dashboard.dash_links.agency} rel="nofollow" icon={<Icon icon="globe" />} />
							{
								!rishi_dashboard.dash_links.hide_support_link &&
								<IconLink title={__("Support", 'rishi')} href={rishi_dashboard.dash_links.support} target="_blank" icon={<Icon icon="headphone" />} />}
							{
								!rishi_dashboard.dash_links.hide_doc_link &&
								<IconLink title={__("Docs", 'rishi')} href={rishi_dashboard.dash_links.docs} target="_blank" icon={<Icon icon="docs" />} />
							}
						</IconLinkGroup>
					</div>
				</div>
			</div>
			{!rishi_dashboard.dash_links.hide_dash_header_info &&
				<div className="rishi-ad_banner">
					<div className="rishi-ad_banner-wrapper">
						<div className='rishi-ad_banner-text'>
							<p>{__("Rishi Theme is core web vitals optimized WordPress theme. It's lightning fast, lightweight and highly customizable.", 'rishi')}</p>
						</div>
						<div className='rishi-ad_banner-logo'>
							<img src={rishi_main_logo} alt="" />
						</div>
					</div>
				</div>
			}
		</div>
	)
}

export default DashHeader