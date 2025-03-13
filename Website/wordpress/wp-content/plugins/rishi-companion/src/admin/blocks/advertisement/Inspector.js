import { InspectorControls, MediaUpload, URLInput } from "@wordpress/block-editor";
import { Button, PanelBody, SelectControl, TextControl, TextareaControl, ToggleControl } from "@wordpress/components";
import { __ } from "@wordpress/i18n";

export default ({ attributes, setAttributes }) => {

	const {
		advertisementLabel,
		advertisementTitleSelector,
		advertisementType,
		advertisementImageID,
		advertisementImageURL,
		advertisementImageAlt,
		advertisementFeaturedLink,
		openInNewTab,
		relAttributeNofollow,
		relAttributeSponsored,
		advertisementCode				
	} = attributes;

	const onSelectImage = (img) => {
		setAttributes({
			advertisementImageID: img.id,
			advertisementImageURL: img.url,
			advertisementImageAlt: img.alt,
		});
	};
	const onReplaceImage = (replace) => {
		setAttributes({
			advertisementImageID: replace.id,
			advertisementImageURL: replace.url,
			advertisementImageAlt: replace.alt,
		});
	};
	const onRemoveImage = () => {
		setAttributes({
			advertisementImageID: null,
			advertisementImageURL: null,
			advertisementImageAlt: null,
		});
	};

	return (
		<InspectorControls key="inspector">
			<PanelBody
				title={__("Advertisement Settings", "rishi-companion")}
				className={"rishi-panel-label rishi-advertisment "}
				initialOpen={true}
			>
				<div className="rishi-blocks-option">
					<TextControl
						label={__("Title", "rishi-companion")}
						className="advertisement-option popular-input-field"
						value={advertisementLabel}
						onChange={(advertisementLabel) => setAttributes({ advertisementLabel })}
					/>
				</div>
				<div className="rishi-blocks-option">
                    <SelectControl
                        label	   ={__("Title Selector", "rishi-companion")}
                        initialOpen={false}
                        value	   ={advertisementTitleSelector}
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
                            setAttributes({ advertisementTitleSelector: newType })
                        }
                    />
                </div>
				<div className="rishi-blocks-option">
					<SelectControl
						label	   ={__("Display Advertisement from:", "rishi-companion")}
						initialOpen={false}
						value	   ={advertisementType}
						options    ={[
							{ value: "ad_code", label: __("Ad Code", "rishi-companion") },
							{ value: "ad_image", label: __("Upload Image", "rishi-companion") },
						]}
						onChange    ={(newType) =>
							setAttributes({ advertisementType: newType })
						}
					/>
				</div>
				{advertisementType == 'ad_image' &&
					<>
					<div className="rishi-blocks-option">
						<img src={advertisementImageURL} alt={advertisementImageAlt}/>
						{
							!advertisementImageID ? (
								<MediaUpload
									onSelect={
										onSelectImage
									}
									type="image"
									value={advertisementImageID}
									render={({
										open,
									}) => (
										<Button
											className={
												"advertisement-upload-btn"
											}
											onClick={
												open
											}
										>
											{__(
												" Upload Image",
												"rishi-companion"
											)}
										</Button>
									)}
								></MediaUpload>
							) : (
								<div className="advertisement-upload-btn-wrapper">
									<MediaUpload
										onSelect={
											onReplaceImage
										}
										type="image"
										value={
											advertisementImageID
										}
										render={({
											open,
										}) => (
											<Button
												className={
													"advertisement-replace-btn"
												}
												onClick={
													open
												}
											>
												{__(
													" Replace Image",
													"rishi-companion"
												)}
											</Button>
										)}
									></MediaUpload>							
									<Button
										className="advertisement-remove-image"
										onClick={
											onRemoveImage
										}
									>
										{__(
											"Remove Image",
											"rishi-companion"
										)}
									</Button>
								</div>
							)                   
						}  
					</div>
					<URLInput
						label={__("Featured Link", "rishi-companion")}
						value={advertisementFeaturedLink}
						onChange={(advertisementFeaturedLink) => setAttributes({ advertisementFeaturedLink })}
					/>
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
						<ToggleControl
							label={__("Add rel attribute nofollow", "rishi-companion")}
							checked={!!relAttributeNofollow}
							onChange={() =>
								setAttributes({
									relAttributeNofollow: !relAttributeNofollow,
								})
							}
						/>
					</div>
					<div className="rishi-blocks-option">
						<ToggleControl
							label={__("Add rel attribute sponsored", "rishi-companion")}
							checked={!!relAttributeSponsored}
							onChange={() =>
								setAttributes({
									relAttributeSponsored: !relAttributeSponsored,
								})
							}
						/>
					</div>
					</>  
				}
				{advertisementType == 'ad_code' &&
					<div className="rishi-blocks-option">
						<TextareaControl
							label={__("Ad Code", "rishi-companion")}
							value={advertisementCode}
							onChange={(advertisementCode) => setAttributes({ advertisementCode })}
						/>
					</div>
				}				
			</PanelBody>
		</InspectorControls>
	);
};
