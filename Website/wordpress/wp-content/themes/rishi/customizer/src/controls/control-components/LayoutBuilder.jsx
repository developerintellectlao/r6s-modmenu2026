import * as builders from './layout-builder';

const LayoutBuilder = ({ option, ...props }) => {
	const Builder = builders[option.builderType];
	return <Builder {...{ ...props, option }} />;
};

LayoutBuilder.config = { design: 'none' };

export default LayoutBuilder;
