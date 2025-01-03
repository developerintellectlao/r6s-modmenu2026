import { Textarea } from '@components';

const TextareaControl = ({
	option,
	value,
	onChange,
}) => {
	return (
		<Textarea
			value={value}
			onChange={onChange}
			id={option?.id}
			placeholder={option?.placeholder}
		/>
	);
};

export default TextareaControl;
