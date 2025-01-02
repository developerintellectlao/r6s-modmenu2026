import { __ } from "@wordpress/i18n";
const attributes = {
	block_id: {
		type: "string",
	},
	categoriesList: {
		type: "array",
		default: []
	},
	categoriesLabel: {
		type: "string",
		default: __("Categories", "rishi-companion"),
	},
	categoriesTitleSelector: {
		type: "string",
		default: "h2",
	},
	category_selected: {
		type: "array",
		default: []
	},
	category: {
		type: "array",
		default: []
	},
	showPostCount: {
		type: "boolean",
		default: true,
	},
	colors: {
		type: "object",
	}
};
export default attributes;
