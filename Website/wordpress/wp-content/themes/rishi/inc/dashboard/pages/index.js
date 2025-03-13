import { useCurrentTab } from "../components/Hooks";
import { applyFilters } from '@wordpress/hooks';
import General from "./general";
import StarterSettings from "./startersettings";

const PageComponent = () => {
	const currentPage = useCurrentTab();

	if ("addons" === currentPage) {
		return applyFilters('rishi_dashboard_pages', null);
	} else if ("extensions" === currentPage || "usefulplugin" === currentPage || 'license' === currentPage ) {
		return applyFilters('rishi_dashboard_components', currentPage);
	} else {
		switch (currentPage) {
			case "general":
				return <General />
			case "starter_sites":
				return <StarterSettings />
			default:
				return <General />
		}
	}
}

export default PageComponent;
