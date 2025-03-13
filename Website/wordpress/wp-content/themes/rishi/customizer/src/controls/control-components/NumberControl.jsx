
import {InputNumber} from '@components';

const InputNumberControl = ({ option, value, onChange }) => {
	return (
		<InputNumber {...{ onChange, min: option.min, max: option.max, value }} />
	);
};

export default InputNumberControl;
