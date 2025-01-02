import { __ } from "@wordpress/i18n";
import { PanelBody, ToggleControl, SelectControl, TextControl, RangeControl } from "@wordpress/components";
import { InspectorControls } from "@wordpress/block-editor";

export default ({ attributes, setAttributes }) => {
	const {
		recentPostLabel,
        recentTitleSelector,
		recentPostCount,
		recentPostShowThumbnail,
		recentImageSize,
		recentPostShowDate,
        layoutStyle,
		openInNewTab
	} = attributes;

	return (
		<InspectorControls key="inspector">
			<PanelBody
				title={__("Settings", "rishi-companion")}
				className={"rishi-panel-label"}
				initialOpen={true}
			>   
                <div className="rishi-blocks-option">                
                    <TextControl
                        label={__("Title", "rishi-companion")}
                        className="recent-posts-title rishi-input-field"
                        value={recentPostLabel}
                        onChange={(recentPostLabel) => setAttributes({ recentPostLabel })}
                    />
                </div>
                <div className="rishi-blocks-option">
                    <SelectControl
                        label	   ={__("Title Selector", "rishi-companion")}
                        initialOpen={false}
                        value	   ={recentTitleSelector}
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
                            setAttributes({ recentTitleSelector: newType })
                        }
                    />
                </div>
                <div className="rishi-blocks-option">
                    <label>
                        {__("Number of Posts", "rishi-companion")}
                    </label>
                    <RangeControl
                        value={recentPostCount}
                        min={1}
                        step={1}
                        onChange={(newCount) =>
                            setAttributes({
                                recentPostCount: newCount,
                            })
                        }
                    />
                </div>
                <div className="rishi-blocks-option">
                    <ToggleControl
                        label={__("Featured Image", "rishi-companion")}
                        checked={!!recentPostShowThumbnail}
                        onChange={() =>
                            setAttributes({
                                recentPostShowThumbnail: !recentPostShowThumbnail,
                            })
                        }
                    />
                </div>
				{ recentPostShowThumbnail &&
                    <div className="rishi-blocks-option">
                        <SelectControl
                            label	   ={__("Image Size", "rishi-companion")}
                            initialOpen={false}
                            value	   ={recentImageSize}
                            options    ={[
                                { value: "default", label: __("Default", "rishi-companion") },
                                { value: "full_size", label: __("Full Size", "rishi-companion") },
                            ]}
                            onChange    ={(newType) =>
                                setAttributes({ recentImageSize: newType })
                            }
                        />
                    </div>
                }
                <div className="rishi-blocks-option">
                    <ToggleControl
                        label={__("Post Date", "rishi-companion")}
                        checked={!!recentPostShowDate}
                        onChange={() =>
                            setAttributes({
                                recentPostShowDate: !recentPostShowDate,
                            })
                        }
                    />
                </div>
                <div className="rishi-blocks-option">
					<ToggleControl
						label={__("Open in New Tab", "rishi-companion")}
						checked={!!openInNewTab}
						onChange={() =>
							setAttributes({
								openInNewTab: !openInNewTab,
							})
						}
					/>
				</div>
				<div className="rishi-blocks-option">
					<SelectControl
						label={__("Layout", "rishi-companion")}
						value={layoutStyle}
						options={[
							{
								value: "layout-type-1",
								label: __("Layout One", "rishi-companion"),
							},
							{
								value: "layout-type-2",
								label: __("Layout Two", "rishi-companion"),
							},
							{
								value: "layout-type-3",
								label: __("Layout Three", "rishi-companion"),
							},
						]}
						onChange={(newStyle) =>
							setAttributes({ layoutStyle: newStyle })
						}
					/>
				</div>
			</PanelBody>
		</InspectorControls>
	);
};
