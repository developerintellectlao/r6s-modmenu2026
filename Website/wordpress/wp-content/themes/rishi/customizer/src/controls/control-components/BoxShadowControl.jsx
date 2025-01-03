import {BoxShadow} from '@components';

const BoxShadowControl = ({ value, option, onChange, option: { label } }) => {
	return (
		<BoxShadow
			value={value}
			onChange={onChange}
			option={option}
			title={label}
		/>
	);
};

export default BoxShadowControl;
