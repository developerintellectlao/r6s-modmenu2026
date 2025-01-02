import { addFilter } from "@wordpress/hooks"
import { Component } from "@wordpress/element";
import { __ } from "@wordpress/i18n";
const RishiCompanionTabs = [
    { id: 'extensions', label: __("Extensions", 'rishi-companion'), },
    { id: 'usefulplugin', label: __("Useful Plugins", 'rishi-companion'), },
    { id: 'license', label: __("License", 'rishi-companion'), }
];
class CompanionTabs extends Component {
    constructor(props) {
        super();
        addFilter('rishi_companion_tabs', 'RishiTabs', function (tabs) {
            RishiCompanionTabs && RishiCompanionTabs.map((tab, index) => {
                tabs[tab.id] = tab.label;
            });

            return tabs;
        });
    }
}
new CompanionTabs();
