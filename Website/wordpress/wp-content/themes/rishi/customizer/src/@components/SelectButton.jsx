import styled from "@emotion/styled";
import { Tooltip } from "./components";

const SelectButtonStyles = styled.label`
  display: inline-flex;
  align-items: center;
  justify-content: center;
  position: relative;
  margin: 0;
  padding: 10px 6px;
  border-radius: var(--cw__border-radius);
  background-color: var(--cw__background-color);
  color: var(--cw__inactive-color);
  border-radius: var(--cw__border-radius);
  background-color: var(--cw__background-color);
  color: var(--cw__inactive-color);
  cursor: pointer;
  text-align: center;
  font-size: 14px;
  font-weight: 600;
  transition: var(--cw__transition);
  .cw__select-button {
    position: absolute;
    inset-block-start: 0;
    inset-inline-start: 0;
    width: 100%;
    height: 100%;
    z-index: -1;
  }
  .cw__icon {
    display: flex;
    svg {
      vertical-align: -0.12em;
    }
  }
  .cw__icon + span {
    margin-left: 0.25rem;
  }
  .cw__select-button-input {
    width: 0;
    height: 0;
    opacity: 0;
    pointer-events: none;
  }
  &.cw__select-button-wrapper-checked {
    background-color: var(--cw__secondary-color);
    color: #ffffff;
  }
`;

const SelectButtonGroupStyles = styled.div`
  padding: 6px;
  border-radius: var(--cw__border-radius);
  background-color: var(--cw__background-color);
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
  &.sm {
    padding: 4px;
	.cw__select-button{
		padding-top: 8px;
		padding-bottom: 8px;
	}
  }
  > * {
    flex: 1;
    gap: 6px;
  }
  .cw__select-button {
    width: 100%;
    &.cw__select-button-checked {
      background-color: #ffffff;
      color: var(--cw__secondary-color);
      box-shadow: var(--cw__box-shadow);
    }
	&:hover{
		color: var(--cw__secondary-color);
		background-color: #ffffff;
	}
  }
  &.cw__separate {
    padding: 0;
    background: none;
    border-radius: 0;
    gap: 15px;
    .cw__select-button {
      border: 1px solid var(--cw__border-color);
      background: none;
      &.cw__select-button-checked {
        border-color: var(--cw__secondary-color);
        box-shadow: none;
      }
    }
  }
  &[data-view=radio]{
	flex-wrap: wrap;
	background: none;
	padding: 0;
	gap: 0;
	row-gap: 14px;
	label{
		flex: unset;
		width: 50%;
		padding-right: 16px;
		font-size: 14px;
		color: #42474B;
		input[type="radio"]{
			width: 20px;
			height: 20px;
			margin-right: 8px;
			border-color: #E0E3E7;
			&:checked, &:focus{
				border-color: var(--cw__secondary-color);
			}
			&:checked{
				background-color: var(--cw__background-color);
			}
			&::before{
				width: 7.5px;
				height: 7.5px;
				background-color: var(--cw__secondary-color);
				margin: 5px;
			}
		}
	}
  }
  &[data-view=radio][data-columns="1"]{
	> label{
		width: 100%;
	}
  }
  &[data-view=radio][data-columns="2"]{
	> label{
		width: 50%;
	}
  }
  &[data-view=radio][data-columns="3"]{
	> label{
		width: 33.33%;
	}
  }
  &[data-view=radio][data-columns="4"]{
	> label{
		width: 25%;
	}
  }
`;

const sizes = {
	sm: "13px",
	md: "16px",
	lg: "18px",
	xl: "20px",
};

const SelectButton = ({
	value,
	label,
	checked,
	icon,
	onChange,
	title,
	size,
	...rest
}) => {
	const { style, ..._rest } = { ...rest };
	const handleSelectOnKeyDown = (e) => {
		if (e.type === "keydown" && e.key === "Enter") {
			onChange(value);
		}
	};
	return (
		<Tooltip title={title}>
			<SelectButtonStyles
				tabIndex={0}
				className={`cw__select-button${(checked && " cw__select-button-checked") || ""
					}`}
				onKeyDown={handleSelectOnKeyDown}
				style={{ fontSize: sizes[size], ...style }}
			>
				<span className="cw__select-button">
					<input
						tabIndex={-1}
						className="cw__select-button-input"
						type="checkbox"
						value={value}
						checked={checked}
						onChange={onChange}
						{..._rest}
					/>
				</span>

				{icon && <span className="cw__icon">{icon}</span>}
				{label && <span>{label}</span>}
			</SelectButtonStyles>
		</Tooltip>
	);
};

const SelectButtonGroup = ({
	options,
	className,
	onChange,
	value,
	separate,
	isMultiple,
	size,
	view,
	...ControlGroup
}) => {
	const handleOnChange = (_value) => () => {
		if (isMultiple) {
			onChange(
				!value.includes(_value)
					? [...value, _value]
					: value.filter((a) => a != _value),
			);
		} else {
			onChange(_value);
		}
	};
	return (
		<SelectButtonGroupStyles
			className={`cw__select-button-group ${className || ""} ${separate ? "cw__separate" : ""
				} ${size || ""}`}
			data-view={view}
			{...ControlGroup}
		>
			{options.map(({ value: _value, label, ...rest }, i) => {
				const _checked = isMultiple ? value.includes(_value) : value === _value;
				if (view === "radio") {
					return <label key={i}>
						<input type="radio" checked={_checked} onChange={handleOnChange(_value)} />
						{label}
					</label>
				}
				return <SelectButton
					key={i}
					size={size}
					value={_value}
					label={label}
					checked={_checked}
					onChange={handleOnChange(_value)}
					{...rest}
				/>
			})}
		</SelectButtonGroupStyles>
	);
};

export default SelectButtonGroup
