import styled from '@emotion/styled'
import { UnitPicker } from './components'
import Icons from './assets/Icons'
import { __ } from '@wordpress/i18n'
import { useEffect } from '@wordpress/element'


const SpacingInputStyles = styled.label`
	text-align: center;
	flex: 1;
	input {
		text-align: center;
		padding-left: 0.25rem !important;
		padding-right: 0.25rem !important;
		-moz-appearance: textfield;
		&::-webkit-outer-spin-button,
		&::-webkit-inner-spin-button {
			-webkit-appearance: none;
		}
	}
	.label {
		display: inline-block;
		font-size: 10px;
		margin-top: 0.25rem;
		text-transform: uppercase;
	}
`

const SpacingGroupStyles = styled.div`
	display: flex;
	width: 100%;
	align-items: flex-start;
	gap: 0.5rem;
	.cw__spacing-button-wrapper {
		background-color: var(--cw__background-color);
		border-radius: var(--cw__border-radius);
		display: flex;
		height: 45px;
		flex: 1;
		button {
			background: none;
			border: none;
			cursor: pointer;
			color: var(--cw__inactive-color);
			padding: 0.5rem;
			font-size: 13px;
			border-radius: var(--cw__border-radius);
			display: inline-flex;
			align-items: center;
			justify-content: center;
			&:hover,
			&.active {
				color: var(--cw__secondary-color);
			}
			&:focus {
				outline: 1px dotted;
			}
			&.cw__spacing-button-link-button {
				flex: 1;
				width: 30px;
			}
		}
		.cw__unit-picker-wrapper {
			position: relative;
			&::before {
				content: '';
				width: 0;
				height: 14px;
				border-left: 1px solid var(--cw__inactive-color);
				position: absolute;
				top: 50%;
				left: 0;
				transform: translateY(-50%);
			}
		}
	}
`
const properties = [
	{ name: 'top', label: __('Top', 'Rishi') },
	{ name: 'right', label: __('Right', 'Rishi') },
	{ name: 'bottom', label: __('Bottom', 'Rishi') },
	{ name: 'left', label: __('Left', 'Rishi') },
]

const isAuto = (obj, key) => {
	return obj[key] === 'auto'
}

const SapcingInput = ({ label, value, isAuto: _isAuto, ...rest }) => {
	return (
		<SpacingInputStyles className="cw__spacing-input-wrapper">
			<span className="cw__spacing-input">
				<input
					type={!_isAuto ? 'number' : 'text'}
					value={value}
					readOnly={_isAuto}
					disabled={_isAuto}
					onWheel={e => e.target.blur()}
					{...rest}
				/>
			</span>
			{label && <span className="label">{label}</span>}
		</SpacingInputStyles>
	)
}

const defaultUnits = [
	{ unit: 'px', min: 0, max: 200 },
	{ unit: 'em', min: 0, max: 10 },
	{ unit: 'rem', min: 0, max: 10 },
	{ unit: '%', min: 0, max: 100 },
]

const Spacing = ({ onChange, value, units = defaultUnits }) => {
	const { linked } = value
	let findUnit = units.find(u => u.unit === value?.unit);
	let min = findUnit?.min || 0;
	let max = findUnit?.max || 100;

	const isLinked = (_value) => ({
		...value,
		top: isAuto(value, 'top') ? 'auto' : _value,
		right: isAuto(value, 'right') ? 'auto' : _value,
		bottom: isAuto(value, 'bottom') ? 'auto' : _value,
		left: isAuto(value, 'left') ? 'auto' : _value,
	})

	const handleOnChange = (key, _value) => {
		_value = _value > max ? max : _value < min ? min : _value
		if (linked) {
			onChange(isLinked(_value))
		} else {
			onChange({ ...value, [key]: _value })
		}
	}

	const handleLinked = () => {
		const newArray = Object.entries(value)
		const _value = (newArray.find(([a, b]) => a !== ('linked' || 'unit') && b !== '' && b !== 'auto') || ['', ''])[1]
		if (!linked) {
			onChange({ ...isLinked(_value), linked: !linked })
		} else {
			onChange({ ...value, linked: !linked })
		}
	}


	const preUnits = units
	const handleOnUnitChange = (u) => {
		const findUnit = preUnits.find(_u => _u.unit === u);
		const min = findUnit.min
		const max = findUnit.max
		const _value = value?.top > max ? max : value?.top < min ? min : value?.top
		onChange({ ...isLinked(_value), unit: u })
	}

	units = units.map(u => u.unit);

	return (
		<SpacingGroupStyles className="cw__spacing-group">
			{properties.map(({ name, label }) => {
				return (
					<SapcingInput
						key={name}
						label={label}
						name={name}
						onChange={(e) => handleOnChange(name, e.target.value)}
						value={value[name]}
						isAuto={isAuto(value, name)}
						min={min}
						max={max}
					/>
				)
			})}
			<div className="cw__spacing-button-wrapper">
				<button type="button" className={`cw__spacing-button-link-button${linked ? ' active' : ''}`} onClick={() => handleLinked()}>
					{Icons.link}
				</button>
				{(units || value?.unit) && <UnitPicker units={units} value={value.unit} onChange={(u) => handleOnUnitChange(u)} />}
			</div>
		</SpacingGroupStyles>
	)
}

export default Spacing
