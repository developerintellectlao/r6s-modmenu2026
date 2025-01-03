import styled from '@emotion/styled';
import { RangeControl } from '@wordpress/components';
import { UnitPicker } from './components';

const RangeStyles = styled.div`
	display: flex;
	> .components-base-control {
		flex: 1;
		.components-base-control__field {
			margin-bottom: 0;
			.components-range-control__root {
				align-items: center;
			}
			.components-input-control__input {
				border: none !important;
				background-color: var(--cw__background-color) !important;
				padding-left: 5px !important;
				padding-right: 5px !important;
				text-align: center;
				min-height: 36px;
			}
		}
	}
	&.cw__has-unit {
		.components-range-control__number {
			width: auto !important;
		}
		.components-input-control__container {
			max-width: 40px;
		}
		.components-input-control__input {
			border-top-right-radius: 0 !important;
			border-bottom-right-radius: 0 !important;
			-moz-appearance: textfield;
			&::-webkit-outer-spin-button,
			&::-webkit-inner-spin-button {
				-webkit-appearance: none;
			}
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
		button {
			border-top-left-radius: 0;
			border-bottom-left-radius: 0;
			color: var(--cw__inactive-color);
		}
	}
`;

const defaultUnits = [
	{ unit: 'px', min: 0, max: 1920 },
	{ unit: '%', min: 0, max: 100 },
	{ unit: 'em', min: 0, max: 30 },
	{ unit: 'rem', min: 0, max: 30 },
	{ unit: 'vh', min: 0, max: 30 },
	{ unit: 'vw', min: 0, max: 30 },
	{ unit: 'pt', min: 0, max: 100 },
];

const RangeSlider = ({ units, value, defaultUnit: dUnit, onChange, ...rest }) => {

	const valueType = typeof value === "string";

	// Create a Map for faster lookup.
	const unitsMap = new Map(units?.map((unit) => [unit.unit || unit, unit]));

	// Filter and merge the arrays.
	const mergedUnits = defaultUnits
		.filter((defaultUnit) => unitsMap.has(defaultUnit.unit))
		.map((defaultUnit) => {
			const unit = unitsMap.get(defaultUnit.unit);
			if (unit && typeof unit === 'object') {
				return { ...defaultUnit, ...unit };
			}
			return defaultUnit;
		});

	let validValue = value || 0;
	if (valueType) {
		const [, _value, _unit] = String(value).match(/([\d.-]+)?([^\d.-]+)?/);
		validValue = { value: +_value, unit: _unit };
	}

	const unitObject = mergedUnits.find(
		({ unit }) => unit === ((validValue?.unit || dUnit) || 'px')
	);

	const handleOnChange = (val) => {
		if (valueType || units) {
			onChange(val + (validValue?.unit || mergedUnits.map(({ unit }) => unit)[0]))
		} else {
			onChange(val)
		}
	}

	return (
		<RangeStyles className={validValue?.unit || units ? 'cw__has-unit' : ''}>
			<RangeControl
				value={valueType ? validValue?.value : validValue}
				onChange={(val) => handleOnChange(val)}
				min={unitObject?.min || 0}
				max={unitObject?.max || 100}
				step={validValue?.unit && (validValue?.unit !== "px" ? 0.01 : 1)}
				{...rest}
			/>
			{(validValue?.unit || units) && (
				<UnitPicker
					units={mergedUnits.map(({ unit }) => unit)}
					value={validValue?.unit || dUnit}
					onChange={(u) => onChange((validValue?.value || 0) + u)}
				/>
			)}
		</RangeStyles>
	);
};

export default RangeSlider;
