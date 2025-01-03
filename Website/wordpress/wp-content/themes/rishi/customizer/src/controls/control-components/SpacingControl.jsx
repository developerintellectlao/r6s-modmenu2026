import { Spacing } from '@components';

const SpacingControl = ({ value, option, onChange }) => {
	return (
		<Spacing
			value={value}
			// units={['px', '%', 'em', 'rem', 'pt']}
			units={option?.units}
			onChange={onChange}
			option={option}
		/>
	);
};

export default SpacingControl;
