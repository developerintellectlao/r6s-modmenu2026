import { addFilter } from "@wordpress/hooks"
import { Component } from "@wordpress/element";

import Extensions from "./components/Extensions.js";
import UsefulPlugins from "./components/UsefulPlugins.js";
import License from "./components/License.js";
class CompanionContent extends Component {
    constructor(props) {
        super();
        addFilter('rishi_dashboard_components', 'CompanionContent', function (path) {

                return (
                <>
                    { "extensions" === path ? <Extensions /> : '' }
                    { "usefulplugin" === path ? <UsefulPlugins /> : '' }
                    { "license" === path ? <License /> : '' }
                </> );

        });
    }

}
new CompanionContent();
