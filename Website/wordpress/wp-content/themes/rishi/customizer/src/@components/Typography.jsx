import { RangeSlider, Select, SelectButtonGroup } from '@components'
import Icons from './assets/Icons'
import { InnerControlWrapper, Popover, PopoverButton } from './components'
import { __ } from '@wordpress/i18n'

const defaultFamily = `-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'`;

const TypographyContent = ({ value, onChange, fontFamilies, fontWeights, fontStyles }) => {
	const {
		family,
		size,
		'line-height': lineHeight,
		'letter-spacing': letterSpacing,
		'word-spacing': wordSpacing,
		weight,
		style,
		'text-transform': transform,
		'text-decoration': decoration,
	} = value

	const handleOnChange = (key, _value) => {
		onChange({ ...value, [key]: _value })
	}

	return (
		<>
			<InnerControlWrapper label={__( 'Family', 'Rishi' )} direction="horizontal">
				<Select
					value={family}
					onChange={(val) => handleOnChange('family', val)}
					options={fontFamilies || []}
					placeholder="Select Font Family"
					variant="solid"
					isSearchable
					style={{ minWidth: '178px' }}
				/>
			</InnerControlWrapper>
			<InnerControlWrapper label={__( 'Size', 'Rishi' )}>
				<RangeSlider
					value={size}
					onChange={(val) => handleOnChange('size', val)}
					units={[
						{ unit: 'px', min: 0, max: 100 },
						{ unit: 'em', min: 0, max: 10 },
						{ unit: 'rem', min: 0, max: 10 },
						{ unit: 'vw', min: 0, max: 100 },
					]}
				/>
			</InnerControlWrapper>
			<InnerControlWrapper label={__( 'Line Height', 'Rishi' )}>
				<RangeSlider
					value={lineHeight}
					defaultUnit="em"
					onChange={(val) => handleOnChange('line-height', val)}
					units={[
						{ unit: 'em', min: 0, max: 10 },
						{ unit: 'rem', min: 0, max: 10 },
						{ unit: 'px', min: 0, max: 100 },
					]}
					step={0.01}
				/>
			</InnerControlWrapper>
			<InnerControlWrapper label={__( 'Letter Spacing', 'Rishi' )}>
				<RangeSlider
					value={letterSpacing}
					onChange={(val) => handleOnChange('letter-spacing', val)}
					units={[
						{ unit: 'em', min: 0, max: 10 },
						{ unit: 'rem', min: 0, max: 10 },
						{ unit: 'px', min: 0, max: 100 },
					]}
					step={0.01}
				/>
			</InnerControlWrapper>
			{wordSpacing && (
				<InnerControlWrapper label={__( 'Word Spacing', 'Rishi' )}>
					<RangeSlider
						value={wordSpacing}
						onChange={(val) => handleOnChange('word-spacing', val)}
						units={[
							{ unit: 'px', min: 0, max: 100 },
							{ unit: 'em', min: 0, max: 10 },
						]}
						step={0.01}
					/>
				</InnerControlWrapper>
			)}
			<InnerControlWrapper label={__( 'Font Weight', 'Rishi' )} direction="horizontal">
				<Select
					value={weight}
					options={fontWeights.map(w => ({
						...w, label: <Span
							style={{
								fontFamily: family.toLowerCase().includes("default") ? defaultFamily : family,
								fontWeight: w.value
							}}
						>
							{w.label}
						</Span>
					})) || []}
					onChange={(val) => handleOnChange('weight', val)}
					variant="solid"
					style={{ minWidth: '136px' }}
				/>
			</InnerControlWrapper>
			<InnerControlWrapper label={__( 'Style', 'Rishi' )} direction="horizontal">
				<Select
					options={fontStyles.map(s => ({
						...s, label: <Span
							style={{
								fontFamily: family.toLowerCase().includes("default") ? defaultFamily : family,
								fontStyle: s.value
							}}
						>
							{s.label}
						</Span>
					})) || []}
					value={style || 'default'}
					onChange={(val) => handleOnChange('style', val)}
					variant="solid"
					style={{ minWidth: '136px' }}
				/>
			</InnerControlWrapper>
			<InnerControlWrapper label={__( 'Transform', 'Rishi' )} direction="horizontal">
				<Select
					options={[
						{
							value: 'default', label: <Span
								style={{
									fontFamily: family.toLowerCase().includes("default") ? defaultFamily : family,
									textTransform: "default"
								}}
							>
								{__( 'Default', 'Rishi' )}
							</Span>
						},
						{
							value: 'uppercase', label: <Span
								style={{
									fontFamily: family.toLowerCase().includes("default") ? defaultFamily : family,
									textTransform: "uppercase"
								}}
							>
								{__( 'Uppercase', 'Rishi' )}
							</Span>
						},
						{
							value: 'lowercase', label: <Span
								style={{
									fontFamily: family.toLowerCase().includes("default") ? defaultFamily : family,
									textTransform: "lowercase"
								}}
							>
								{__( 'Lowercase', 'Rishi' )}
							</Span>
						},
						{
							value: 'capitalize', label: <Span
								style={{
									fontFamily: family.toLowerCase().includes("default") ? defaultFamily : family,
									textTransform: "capitalize"
								}}
							>
								{__( 'Capitalize', 'Rishi' )}
							</Span>
						},
						{
							value: 'normal', label: <Span
								style={{
									fontFamily: family.toLowerCase().includes("default") ? defaultFamily : family,
									textTransform: "normal"
								}}
							>
								{__( 'Normal', 'Rishi' )}
							</Span>
						},
					]}
					value={transform || 'default'}
					onChange={(val) => handleOnChange('transform', val)}
					variant="solid"
					style={{ minWidth: '136px' }}
				/>
			</InnerControlWrapper>
			<InnerControlWrapper label={__( 'Decoration', 'Rishi' )} direction="horizontal">
				<SelectButtonGroup
					value={decoration}
					options={[
						{ value: 'none', icon: Icons.decoration_normal, title: 'None' },
						{ value: 'underline', icon: Icons.decoration_underline, title: 'Underline' },
						{ value: 'line-through', icon: Icons.decoration_strikeout, title: 'Line Through' },
					]}
					onChange={(val) => handleOnChange('decoration', val)}
					style={{ width: '178px' }}
				/>
			</InnerControlWrapper>
		</>
	)
}

const Typography = ({ changed, ...props }) => {
	return (
		<Popover content={<TypographyContent {...props} />}>
			<PopoverButton changed={changed} />
		</Popover>
	)
}

const Span = ({ children, ...rest }) => <span {...rest}>{children}</span>

export default Typography
