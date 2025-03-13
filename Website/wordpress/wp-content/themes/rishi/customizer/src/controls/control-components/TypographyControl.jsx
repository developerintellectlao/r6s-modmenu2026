import { Popover, PopoverButton } from '@components';
import ControlBase from './ControlBase';
import { useEffect, useState } from '@wordpress/element';
import { InView } from 'react-intersection-observer';

const defaultFamily = `-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'`;

const toAppendGoogleFont = (font, fonts) => {
	const id = font.toLowerCase().split(" ").join("-");
	const link = document.createElement('link');
	const variants = fonts[font.replace(/\s/g, '_')]?.variants || [];
	let _w = variants.map(el => {
		let [_weight] = String(el).match(/([\d]+)?/);
		if (el == 'regular') {
			_weight = "400"
		}
		if (_weight !== '') {
			return _weight;
		}
	}).filter(el => el !== undefined)
	_w = [...new Set(_w)].join(';')
	link.rel = "stylesheet";
	font = font.split(" ").join("+");
	const src = `https://fonts.googleapis.com/css2?family=${font}${_w.length > 0 && ":wght@" || ""}${_w}`;
	link.href = src;
	return { id, link, src }
}

const fontWeightLabels = {
	100: 'Thin 100',
	200: 'Extra-Light 200',
	300: 'Light 300',
	400: 'Regular 400',
	500: 'Medium 500',
	600: 'Semi-Bold 600',
	700: 'Bold 700',
	800: 'Extra-Bold 800',
	900: 'Ultra-Bold 900'
}
const fontStyleLabels = {
	'default': 'Default',
	'italic': 'Italic',
	'normal': 'Normal',
}

const PopoverContent = ({ option: { settings: { options } }, value, onChange }) => {

	const [variant, setVariant] = useState({
		weight: Object.entries(fontWeightLabels).map(([key, val]) => ({ key: key, value: val })),
		style: Object.entries(fontStyleLabels).map(([key, val]) => ({ key: key, value: val }))
	})

	let fonts = rishi.themeData.fonts;
	const variants = fonts[value.family.replace(/\s/g, '_')]?.variants || [];
	const fontSource = fonts[value.family]?.fontSource || [];
	const customfontWeight = fonts[value.family]?.fontWeight || [];
	let fontSources = Object.values(fonts)
	.filter(font => font.hasOwnProperty('fontSource'))
	.map(font => {
		const selectedFontWeight = font.fontWeight.split("-").pop();
		return {
		name: font.name,
		fontWeight: selectedFontWeight,
		fontSource: font.fontSource
		}
	});

	useEffect(() => {
		let _fontWeights = [];
		let _fontStyles = [];
		if (variants.length > 0) {
			// enqueue google font after choosing font
			const { link } = toAppendGoogleFont(value.family, fonts);
			document.head.appendChild(link)

			_fontWeights = variants
				.map(el => {
					let [_weight] = String(el).match(/([\d]+)?/);
					if (el == 'regular') {
						_weight = "400"
					}
					if (_weight !== '') {
						return { key: _weight, value: fontWeightLabels[_weight] };
					}
					return null;
				})
				.filter(Boolean)
				.reduce((accumulator, item) => {
					if (!accumulator.some(existingItem => existingItem.value === item.value)) {
						accumulator.push(item);
					}
					return accumulator;
				}, []);
		} else if ( fontSource != '' ){
			const selectedFontWeight = customfontWeight.split("-").pop();
			_fontWeights = [{ key: selectedFontWeight, value: fontWeightLabels[selectedFontWeight] }];
		} else {
			_fontWeights = Object.entries(fontWeightLabels).map(fw => ({ key: fw[0], value: fw[1] }))
		}
		if (variants.includes('regular') || variants.includes('italic')) {
			_fontStyles = variants
				.filter(variant => variant === 'regular' || variant === 'italic')
				.map(variant => {
					if (variant === 'regular') {
						return { key: 'normal', value: 'Normal' };
					} else {
						return { key: variant, value: variant.charAt(0).toUpperCase() + variant.slice(1) };
					}
				});
		} else {
			_fontStyles = Object.entries(fontStyleLabels).map(fs => ({ key: fs[0], value: fs[1] }))
		}
		setVariant({ weight: _fontWeights, style: _fontStyles })
		onChange(
			{
			...value,
			weight: fontSources.find(fs => fs.name === value.family) ? fontSources.find(fs => fs.name === value.family).fontWeight : _fontWeights.find(wt => wt.key === value.weight) ? value.weight : "400",
			style: _fontStyles.find(st => st.key == value.style) ? value.style : "normal"
		 })
	}, [value?.family])

	const handleInView = (font, status) => {
		const _variants = fonts[font.replace(/\s/g, '_')]?.variants || [];
		const { id, link } = toAppendGoogleFont(font, fonts);
		link.id = id;
		const element = document.querySelector(`#${id}`);
		if (status && _variants.length > 0) {
			if (!element) {
				document.head.appendChild(link)
			}
		} else if (element) {
			document.head.removeChild(element)
		}
	}

	return <>
		{
			Object.entries(options).map(([key, option]) => {
				const revertBtn = option?.hasRevertButton === false ? option?.hasRevertButton : true;
				let _option = option;
				if (key === "weight" || key === "style") {
					const fontstyle = "weight" === key ? "fontWeight" : "fontStyle";
					_option = {
						...option, choices: variant[key].map(v => ({
							...v,
							value: <span
								style={{
									[fontstyle]: v.key,
									fontFamily: value?.family.match("Default") ? defaultFamily : value?.family
								}}
							>{v.value}</span>
						}))
					}
				}
				if (key === "text-transform") {
					_option = {
						...option, choices: option?.choices.map(t => ({
							...t,
							value: <span
								style={{
									textTransform: t.key,
									fontFamily: value?.family.match("Default") ? defaultFamily : value?.family
								}}
							>{t.value}</span>
						}))
					}
				}
				if ("family" === key) {
					const fonts = Object.values(option.choices).map(f => {
						return {
							key: f.key,
							value: <InView
								onChange={(inView) => handleInView(f.key, inView)}
								style={{ fontFamily: f.key.match("Default") ? defaultFamily : f.key }}
							>{f.value}</InView>
						}
					})
					_option = { ...option, choices: fonts }
				}
				return <ControlBase
					key={key}
					id={key}
					value={value[key]}
					option={_option}
					onChange={(val) => onChange({ ...value, [key]: val })}
					hasRevertButton={revertBtn}
				/>
			})
		}
	</>
}

const TypographyControl = (props) => {
	return <Popover content={<PopoverContent {...props} />}>
		<PopoverButton />
	</Popover>
}

TypographyControl.config = { design: 'inline' }

export default TypographyControl
