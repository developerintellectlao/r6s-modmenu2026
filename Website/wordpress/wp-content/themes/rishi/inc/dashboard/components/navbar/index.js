import { Link } from "react-router-dom";
import { useCurrentTab } from "../Hooks";
import Icons from "../../icons";
import { __ } from "@wordpress/i18n";

const path = "?page=rishi-dashboard";

export default (props) => {
    const { pages } = props;

    const current_tab = useCurrentTab()

    return <nav className="rishi-ad_navbar">
        <ul className="rishi-ad_navbar-nav">
            {
                Object.entries(pages).map(([_tab, component]) => {
                    switch(component) {
                        case 'Home':
                            component = <><Icons icon="home"/>{__('Home','rishi')}</>
                            break;
                        case 'Starter Sites':
                            component = <><Icons icon="starter_sites"/>{__('Starter Sites','rishi')}</>
                            break;
                        case 'Extensions':
                            component = <><Icons icon="ExtentionFree"/>{__('Extensions','rishi')}</>
                            break;
                        case 'Useful Plugins':
                            component = <><Icons icon="plugins"/>{__('Useful Plugins','rishi')}</>
                            break;
                        case 'License':
                            component = <><Icons icon="license"/>{__('License','rishi')}</>
                            break;
                        default:
                            component = component;
                            break;
                    }
                    return <li key={_tab} className={_tab === current_tab && 'current' || ''}>
                        <Link to={`${path}&page_tab=${_tab}`} className="rishi-ad_navbar-link">{component}</Link>
                    </li>
                })
            }
        </ul>
    </nav>
}
