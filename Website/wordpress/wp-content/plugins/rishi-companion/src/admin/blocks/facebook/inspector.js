import { __ } from "@wordpress/i18n";
import { PanelBody, TextControl,ToggleControl,SelectControl,RangeControl } from "@wordpress/components";
import { InspectorControls } from "@wordpress/block-editor";
export default ({ attributes, setAttributes}) => {

    const{
        facebookTitleLabel,
        facebookTitleSelector,
        facebookUrl,
        facebookTabs,
        facebookWidth,
        facebookHeight,
        facebookCoverPhoto,
	    facebookSmallHeader
    } = attributes;

    return(
        <InspectorControls key="inspector">
            <PanelBody
            title={__("Settings", "rishi-companion")}
            className={"rishi-panel-label"}
            initialOpen={true}
            >
                <div className="rishi-blocks-option">
                    <TextControl
                        label={__("Title", "rishi-companion")}
                        className="facebook-posts-title rishi-input-field"
                        value={facebookTitleLabel}
                        onChange={(facebookTitleLabel) => setAttributes({ facebookTitleLabel })}
                    />
                </div>
                <div className="rishi-blocks-option">
                    <SelectControl
                        label	   ={__("Title Selector", "rishi-companion")}
                        initialOpen={false}
                        value	   ={facebookTitleSelector}
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
                            setAttributes({ facebookTitleSelector: newType })
                        }
                    />
                </div>
                <TextControl
                    label={__("Enter valid Facebook Page URL", "rishi-companion")}
                    className="facebook-posts-link rishi-input-field"
                    value={ facebookUrl }
                    onChange={(facebookUrl) => setAttributes({ facebookUrl })}
                />
                <SelectControl
                    label	   ={__("Tabs", "rishi-companion")}
                    initialOpen={false}
                    value	   ={facebookTabs}
                    options    ={[
                        { value: "timeline", label: __("Timeline", "rishi-companion") },
                        { value: "messages", label: __("Messages", "rishi-companion") },
                    ]}
                    onChange  ={(newType) =>
                        setAttributes({ facebookTabs: newType })
                    }
                />
                <div className= "rishi-block-option">
                    <label>
                        {__("Width", "rishi-companion") }
                    </label>
                    <RangeControl
                        value={facebookWidth}
                        min={180}
                        step={10}
                        max={500}
                        allowReset = {true}
                        resetFallbackValue = {300}
                        onChange={(newCount) =>
                            setAttributes({
                                facebookWidth: newCount,
                            })
                        }
                    />
                </div>
                <div className= "rishi-blocks-option">
                    <label>
                        {__("Height", "rishi-companion") }
                    </label>
                    <RangeControl
                        value={facebookHeight}
                        min={70}
                        step={10}
                        max={1000}
                        allowReset = {true}
                        resetFallbackValue = {500}
                        onChange={(newCount) =>
                            setAttributes({
                                facebookHeight: newCount,
                            })
                        }
                    />
                </div>
                <div className= "rishi-blocks-option">
                    <ToggleControl
                        label={__("Cover Photo", "rishi-companion")}
                        className="facebook-posts-cover-photo"
                        checked={!!facebookCoverPhoto}
                        onChange={() =>
                            setAttributes({ facebookCoverPhoto: !facebookCoverPhoto })
                        }
                    />
                </div>
                <div className= "rishi-blocks-option">
                    <ToggleControl
                        label={__("Small Header", "rishi-companion")}
                        className="facebook-posts-small-header"
                        checked={!!facebookSmallHeader}
                        onChange={() =>
                            setAttributes({ facebookSmallHeader: !facebookSmallHeader })
                        }
                    />
                </div>
            </PanelBody>
        </InspectorControls>
    );
};