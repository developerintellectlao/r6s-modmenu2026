import React from 'react'
import Navbar from './components/navbar'
import PageComponent from './pages'
import DashHeader from './pages/header/DashHeader'
import {Base, MainContent, Container} from "./layouts"
import { Row, Column } from './components';
import { applyFilters } from '@wordpress/hooks';

const pages = applyFilters( 'rishi_companion_tabs', {
	home: "Home",
	starter_sites: "Starter Sites",
});

{
	rishi_dashboard.dash_links.hide_starter_sites && pages.starter_sites && delete pages.starter_sites
	rishi_dashboard.dash_links.hide_plugins_tab && pages.usefulplugin && delete pages.usefulplugin
}

function Dashboard() {
	return (
    <>
        <Base>
        <DashHeader/>
            <Container>
                <MainContent>
					<Row gap={{ gap: '24px' }} flex={{ flexGrow: "1" }}>
						<Column width={{ lg: '18.75%' }}>
							<Navbar pages={pages} />
						</Column>
						<Column width={{ lg: '81.25%', md: '100%' }}>
							<PageComponent />
						</Column>
					</Row>
				</MainContent>
            </Container>
        </Base>
    </>
)
}

export default Dashboard
