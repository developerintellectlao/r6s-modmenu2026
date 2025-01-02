import {
	useEffect,
	useState
} from '@wordpress/element';
import Card from "../../ui-components/Card"
import Switch from "../../ui-components/Switch"
import TabNav from '../../ui-components/TabNav';
import { __ } from '@wordpress/i18n';
import Button from "../../ui-components/Button"

let extensionStatus = null

let extensionName = RishiCompanionDashboard.extensions;
	export const exensionListing = () => {
	const staticData = [];
	for (let i = 1; i <= 6; i++) {
		staticData.push({
			description: __("Add additional customization to WooCommerce pages and products.", "rishi-companion"),
			extension_status: "free",
			name: staticData.length + i,
		});
	}
    // Assign static data if extensionName is empty
    if (Object.values(extensionName).length === 0) {
        extensionName = staticData;
    }
    let freeExtensions = [];
    let proExtensions = [];
    Object.values(extensionName).forEach((name, index) => {
        if (name.extension_status === 'free') {
            freeExtensions.push(name);
        } else if (name.extension_status === 'pro') {
            proExtensions.push(name);
        }
    });
    return { freeExtensions, proExtensions };
}
const Extensions = () => {
	const [isLoading, setIsLoading] = useState(!extensionStatus);
	const [isButtonLoading, setIsButtonLoading] = useState([]);
	const [tabActive, setTabActive] = useState("free");
	const { freeExtensions, proExtensions } = exensionListing();
    const extensions = tabActive === 'free' ? freeExtensions : proExtensions;
	const tabs = [
        { id: "free", label: "Free Extensions" },
    ];
    if (proExtensions.length > 0) {
        tabs.push({ id: "pro", label: "Pro Extensions" });
    }
	const extensionData = async (showLoader = false) => {
		if (showLoader) {
			setIsLoading(true)
		}

		const formData = new FormData();
		formData.append('action', 'rishi_get_extensions_status');
		try {
			const response = await fetch(RishiCompanionDashboard.ajaxUrl, {
				method: 'POST',
				body: formData
			});

			if (response.status === 200) {
				const { success, data } = await response.json()
				if (success) {
					extensionStatus = data;
					extensionName = data
				}
			}
		} catch (error) {
			console.error(error);
		}

		setIsLoading(false);
	}

	useEffect(() => {
		extensionData(!extensionStatus);
	}, [])

	const handleAction = async (extension, action) => {
		const formData = new FormData();
		formData.append("extension", extension);
		formData.append("action", action);

		setIsButtonLoading(extension);

		try {
			await fetch(RishiCompanionDashboard.ajaxUrl, {
				method: 'POST',
				body: formData,
			});
			await extensionData();
		} catch (error) {
			console.error(error);
		}

		setIsButtonLoading(false);

		for (const value of formData.values()) {
			if(value === "advanced-sidebar" || value === "custom-fonts" || value === "white-label" || value === "portfolio") {
				location.reload()
			}
		}
	}

	return (
		<>
		<div className="extension--wrapper">
			<div className="container">
				<div className="rishi-main-wrapper">
					{tabs && tabs.length > 1 && <TabNav
						active={tabActive}
						onActive={setTabActive}
						tabs={tabs}
					/>}
					<div className="extension--grid--wrapper" style={{ display: "grid", gridTemplateColumns: "repeat(3, 1fr)", gap: "20px" }}>
						{extensions.map((extension, i) => {
							const status = extension.status;
							let extensionName = extension.name;
							{ if ( 'white-label' === extension.id && rishi_dashboard.dash_links.hide_white_label ) {
								return null;
							} }
							return <Card
								key={i}
								isLoading={isLoading}
							>
								<Card.CardBody>
									<Card.CardTitle>
										{extensionName}
									</Card.CardTitle>
									<div>
										{extension.description}
									</div>
								</Card.CardBody>
								<Card.CardFooter style={{ justifyContent: "space-between" }}>
									<Switch
										isLoading={isButtonLoading === extension.id}
										switch={status !== 'deactivated' ? "on" : "off"}
										onChange={swt => handleAction(extension.id, swt !== "off" ? "rishi_enable_extension" : "rishi_disable_extension")}
									/>
									{status !== 'deactivated' &&
										( extension.link ? <a href={extension.link}>{__("Configure", "rishi-companion")}</a>
										: <a data-module={extension.id} style={{cursor: 'pointer'}}>{__("Configure", "rishi-companion")}</a> )
									}
								</Card.CardFooter>
							</Card>
						})
						}
					</div>
				</div>
			</div>
		</div>

		</>
	)
}
export default Extensions
