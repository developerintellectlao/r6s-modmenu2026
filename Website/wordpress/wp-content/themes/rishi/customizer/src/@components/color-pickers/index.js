import SingleColorPicker from './SingleColorPicker';
import GradientPicker from './GradientColorPicker';
import ColorPalettes from './ColorPalettes';
import MultiColorPicker from './MultiColorPicker';

export default function ColorPicker({ type, ...props }) {
	switch (type) {
		case 'gradient':
			return <GradientPicker {...props} />;
		case 'palette':
			return <ColorPalettes {...props} />;
		case 'multi':
			return <MultiColorPicker {...props} />;
		default:
			return <SingleColorPicker {...props} />;
	}
}

export { default as SingleColorPicker } from './SingleColorPicker';
export { default as GradientPicker } from './GradientColorPicker';
export { default as ColorPalettes } from './ColorPalettes';
export { default as MultiColorPicker } from './MultiColorPicker';
