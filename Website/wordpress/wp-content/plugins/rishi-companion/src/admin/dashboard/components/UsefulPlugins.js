import {
    useEffect,
    useState
} from '@wordpress/element';
import { __ } from "@wordpress/i18n";
import Button from '../../ui-components/Button';
import Card from '../../ui-components/Card';

let pluginStatus = null

const pluginName = RishiCompanionDashboard.pluginName;
const buttonActions = {
    activated: { label: __("Deactivate", "rishi-companion"), action: "rishi_get_plugin_deactivate" },
    deactivated: { label: __( 'Activate', 'rishi-companion' ), action: "rishi_get_plugin_activate" },
    uninstalled: { label: __( 'Install', 'rishi-companion' ), action: "rishi_get_plugin_download" },
}

export const pluginsListing = () => {
    return Object.keys(pluginName).reduce((acc, name, index) => {
        let plugin = pluginName[name];
        plugin['name'] = name;
        acc.push(plugin);
        return acc;
    }, []);
}

const UsefulPlugins = () => {
    const [isLoading, setIsLoading] = useState(!pluginStatus);
    const [isButtonLoading, setIsButtonLoading] = useState([]);
    const [pluginsStatus, setPluginStatus] = useState(
        pluginStatus || []
    )

    const plugins = pluginsListing()

    const pluginData = async (showLoader = false) => {
        if (showLoader) {
            setIsLoading(true)
        }

        const formData = new FormData();
        formData.append('action', 'rishi_get_plugins_status');


        try {
            const response = await fetch(RishiCompanionDashboard.ajaxUrl, {
                method: 'POST',
                body: formData
            });

            if (response.status === 200) {
                const { success, data } = await response.json()
                if (success) {
                    setPluginStatus(data);
                    pluginStatus = data;
                }
            }
        } catch (error) {
            console.error(error);
        }

        setIsLoading(false);
    }

    useEffect(() => {
        pluginData(!pluginStatus);
    }, [])

    const handleAction = async (plugin, action) => {
        const formData = new FormData();
        formData.append("plugin", plugin);
        formData.append("action", action);

        setIsButtonLoading(plugin);

        try {
            const res = await fetch(RishiCompanionDashboard.ajaxUrl, {
                method: 'POST',
                body: formData
            });
            await pluginData();
            if ( res.status == 200 && action === 'rishi_get_plugin_download') {
                setPluginStatus(prevStatus => prevStatus.map(item =>
                    item.name === plugin ? { ...item, status: 'deactivated' } : item
                ));
            }
        } catch (error) {
            console.error(error);
        }
        setIsButtonLoading(false);
    }

    return (
        <div className="plugin--wrapper">
            <div className="container">
                {plugins.length > 0 && (
                    <div className="rishi-main-wrapper">
                        <div className="plugin--grid--wrapper" style={{ display: "grid", gridTemplateColumns: "repeat(3, 1fr)", gap: "20px" }}>
                            {plugins.map(plugin => {
                                const status = (pluginsStatus.find(({ name }) => name === plugin.name) || {}).status;

                                return <Card key={plugin.name} isLoading={isLoading}>
                                    <Card.CardBody>
                                        <Card.CardPluginHeader>
                                            <img src={plugin.icon}></img>
                                            <Card.cardPluginTitle>{plugin.title}</Card.cardPluginTitle>
                                        </Card.CardPluginHeader>
                                        <div>
                                            {plugin.description}
                                        </div>
                                    </Card.CardBody>
                                    <Card.CardFooter>
                                        <Button
                                            type="button"
                                            onClick={() => handleAction(plugin.name, buttonActions[status]?.action)}
                                            isLoading={isButtonLoading === plugin.name}
                                        >
                                            {__(isLoading ? "Loading..." : buttonActions[status]?.label, "rishi-companion")}
                                        </Button>
                                    </Card.CardFooter>
                                </Card>
                            })}
                        </div>
                    </div>
                )}
            </div>
        </div>
    )
}

export default UsefulPlugins;
