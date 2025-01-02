import { __ } from "@wordpress/i18n";
import { PanelBody, TextControl, ToggleControl, SelectControl } from "@wordpress/components";
import { InspectorControls } from "@wordpress/block-editor";
import React from 'react';
import Select from 'react-select';
import makeAnimated from 'react-select/animated';

const animatedComponents = makeAnimated();

export default ({ attributes, setAttributes, categories }) => {

	const {
		categoriesLabel,
		categoriesTitleSelector,
		category_selected,
		showPostCount
	} = attributes;

	return (
		<InspectorControls key="inspector">
			<PanelBody
				title={__("Categories Settings", "rishi-companion")}
				className={"rishi-categories-panel-label"}
				initialOpen={true}
			>
				<div className="rishi-blocks-option">
					<TextControl
						label={__("Title", "rishi-companion")}
						className="categories-option categories-input-field"
						value={categoriesLabel}
						onChange={(categoriesLabel) => setAttributes({ categoriesLabel })}
					/>
				</div>
				<div className="rishi-blocks-option">
                    <SelectControl
                        label	   ={__("Title Selector", "rishi-companion")}
                        initialOpen={false}
                        value	   ={categoriesTitleSelector}
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
                            setAttributes({ categoriesTitleSelector: newType })
                        }
                    />
                </div>
				<div className="rishi-blocks-option">
					<Select
						closeMenuOnSelect={false}
						components={animatedComponents}
						isMulti
						placeholder={__("Categories List", "rishi-companion")}
						initialOpen={false}
						options={categories.map(({ id, name }) => ({ label: name, value: id }))}
						onChange={(newValue) =>
							setAttributes({ category_selected: newValue })
						}
						value={category_selected}
					/>
				</div>
				<div className="rishi-blocks-option">
					<ToggleControl
						label={__("Show Post Count", "rishi-companion")}
						className="category-show-post-option"
						checked={!!showPostCount}
						onChange={() =>
							setAttributes({ showPostCount: !showPostCount })
						}
					/>
				</div>
			</PanelBody>
		</InspectorControls>
	);
};
