import { __ } from "@wordpress/i18n";
import { PanelBody, TextControl,SelectControl } from "@wordpress/components";
import { InspectorControls } from "@wordpress/block-editor";

export default ({ attributes, setAttributes }) => {
	const {
		pinLabel,
        pinSelector
	} = attributes;

	return (
        <InspectorControls key="inspector">
            <PanelBody>
                <div className="rishi-blocks-option">                
                    <TextControl
                        label={__("Pinterest Block Title", "rishi-companion")}
                        className="recent-posts-title rishi-input-field"
                        value={pinLabel}
                        onChange={(pinLabel) => setAttributes({ pinLabel })}
                    />
                </div>
                <div className="rishi-blocks-option">
                    <SelectControl
                        label	   ={__("Title Selector", "rishi-companion")}
                        initialOpen={false}
                        value	   ={pinSelector}
                        options    ={[
                            { value: "h1", label: __("H1", "rishi-companion") },
                            { value: "h2", label: __("H2", "rishi-companion") },
                            { value: "h3", label: __("H3", "rishi-companion") },
                            { value: "h4", label: __("H4", "rishi-companion") },
                            { value: "h5", label: __("H5", "rishi-companion") },
                            { value: "h6", label: __("H6", "rishi-companion") },
                            { value: "span", label: __("span", "rishi-companion") },
                            { value: "p", label: __("p", "rishi-companion") },
                            { value: "div", label: __("div", "rishi-companion") },
                        ]}
                        onChange    ={(newType) =>
                            setAttributes({ pinSelector: newType })
                        }
                    />
                </div>
            </PanelBody>
        </InspectorControls>
    )
}