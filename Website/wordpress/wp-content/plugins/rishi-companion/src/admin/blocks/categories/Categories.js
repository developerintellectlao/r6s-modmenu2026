import { __experimentalUseColorProps as useColorProps } from '@wordpress/block-editor'; //WordPress dependencies

import { BlockSection, CategoryCard } from "../components";

const Categories = ({ attributes, categories }) => {
    const {
        categoriesLabel,
        categoriesTitleSelector,
        layoutStyle,
        category_selected,
    } = attributes;

    var cat = [];
    if (category_selected != null) {
        category_selected.forEach(element => {
            cat.push(element.value)
        });
        cat.join();
    }

    const colorProps = useColorProps(attributes);

    const filteredCategories = categories.filter(_category => {
        return category_selected?.length <= 0 || category_selected.find(selected => selected.value === _category.id)
    })

    return <BlockSection className="rishi_sidebar_widget_categories"
        titleSelector={categoriesTitleSelector}
        sectionLabel={categoriesLabel}
        layoutStyle='layout-type-1'
        >
        {filteredCategories.map((item, index) => {
            return <CategoryCard key={index} item={{
                ...attributes,
                ...item,
                colorProps,
                layoutStyle: 'layout-type-1'
            }} />
        })}
    </BlockSection>
}

export default Categories;
