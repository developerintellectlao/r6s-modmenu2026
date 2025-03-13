import styled from "@emotion/styled";
import Tippy from "@tippyjs/react";
import { useEffect, useRef, useState } from "@wordpress/element";
import { __ } from '@wordpress/i18n';

const Icons = {
	close: (
		<svg
			width="9"
			height="10"
			viewBox="0 0 9 10"
			fill="none"
			xmlns="http://www.w3.org/2000/svg"
		>
			<path
				d="M8.12428 1.46449L1.05321 8.53556M1.05321 1.46449L8.12428 8.53556"
				stroke="currentColor"
				strokeWidth="1.5"
				strokeLinecap="round"
				strokeLinejoin="round"
			/>
		</svg>
	),
};

const SelectStyles = styled.div`
	position: relative;
	font-size: 14px;
	min-width: 136px;
	[data-tippy-root]{
		width: 100%;
	}
	.tippy-box{
		background: none;
	}
	.tippy-content{
		padding: 6px;
		background-color: #ffffff;
		border-radius: var(--cw__border-radius);
		box-shadow:
		  0px 4px 6px -2px #10182808,
		  0px 12px 16px -4px #10182814;
		border: 1px solid var(--cw__border-color);
		padding-top: 0.5rem;
		min-width: 100%;
	}
  .cw__custom-select__input-wrapper{
	  &::after {
		content: "";
		width: 1rem;
		height: 1rem;
		background-color: var(--cw__inactive-color);
		position: absolute;
		right: 0.5rem;
		top: 50%;
		transform: translateY(-50%);
		transition: var(--cw__transition);
		mask: url("data:image/svg+xml,%3Csvg width='15' height='8' viewBox='0 0 15 8' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.5177 1L7.5177 7L13.5177 1' stroke='%2393999F' stroke-width='2' strokeLinecap='round' strokeLinejoin='round'/%3E%3C/svg%3E%0A");
		mask-size: 100%;
		mask-position: center;
		mask-repeat: no-repeat;
	  }
  }
  &.is-multiple {
	.cw__custom-select__input-wrapper{
		&::after {
		  content: none;
		}
	}
  }
  .open {
	.cw__custom-select__input-wrapper{
		&::after {
		  transform: translateY(-50%) rotate(180deg);
		}
	}
  }
  .cw__select-input {
    padding-right: 2rem;
    cursor: default;
  }
  .cw__select-dropdown {
    input[type="search"] {
      margin: 0 0 8px;
    }
    .cw__404-text {
      display: block;
      text-align: center;
      color: #ff0e0e;
      font-weight: 600;
      padding: 6px;
    }
  }
  .cw__select-options {
    padding: 0;
    margin: 0;
    list-style: none;
    max-height: 202px;
    overflow-y: auto;
    li {
      padding: 10.5px 8px;
      cursor: default;
      border-radius: var(--cw__border-radius);
      color: #2b3034;
      cursor: pointer;
      font-size: 14px;
      display: flex;
      align-items: center;
      gap: 8px;
	  margin-bottom: 0.25rem;
      &:last-child {
		margin-bottom: 0;
      }
      &:hover {
        color: var(--cw__secondary-color);
      }
      &.selected {
        font-weight: 600;
        color: var(--cw__secondary-color);
        background-color: var(--cw__background-color);
        background-image: url("data:image/svg+xml,%3Csvg width='21' height='20' viewBox='0 0 21 20' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M16.7021 5L7.53544 14.1667L3.36877 10' stroke='%23216BDB' stroke-width='1.66667' strokeLinecap='round' strokeLinejoin='round'/%3E%3C/svg%3E%0A");
        background-size: 20px 20px;
        background-repeat: no-repeat;
        background-position: center right 10px;
        padding-right: 40px;
      }
      .icon {
        display: inline-flex;
        font-size: 20px;
        svg {
          width: 1em;
          height: 1em;
        }
      }
      .icon + .text {
        margin-left: 8px;
      }
    }
  }
  &.solid {
    .cw__custom-select__input-wrapper {
      border-color: transparent;
      background-color: var(--cw__background-color);
    }
  }
  .cw__custom-select__input-wrapper {
    min-width: 100px;
    color: #2b3034;
    border: 1px solid var(--cw__border-color);
    border-radius: var(--cw__border-radius);
    min-height: 44px;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    padding: 10px;
    gap: 8px;
    cursor: pointer;
    input.cw__custom-select__input {
      min-height: unset;
      padding: 0;
      width: 1px;
      min-width: unset;
      border: none;
    }
    &:focus {
      border-color: var(--cw__secondary-color);
    }
    .cw__custom-select__input-value {
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .placeholder {
      color: var(--cw__inactive-color);
    }
  }
  &:not(.is-multiple) {
    .cw__custom-select__input-wrapper {
      padding-right: 32px;
    }
  }
`;

const SelectedBadgeStyle = styled.span`
  display: inline-flex;
  gap: 4px;
  align-items: center;
  color: #2b3034;
  padding: 6px 12px;
  background-color: #e5f0ff;
  border-radius: var(--cw__border-radius);
  .cw__cancel {
    border: none;
    background: none;
    padding: 0;
    cursor: pointer;
  }
`;

const SelectOptions = ({ value, options, isSearchable, onSelect, onSearch }) => {
	return <div className="cw__select-dropdown">
		{isSearchable && (
			<input
				type="search"
				placeholder={__( 'Search...', 'Rishi' )}
				onChange={onSearch}
			/>
		)}
		{options.length <= 0 && (
			<span className="cw__404-text">There are no options!</span>
		)}
		<ul className="cw__select-options">
			{options?.map(({ value: _value, label, icon }, i) => {
				const selected = value === _value;
				return (
					<li
						key={i}
						tabIndex={0}
						className={selected ? "selected" : ""}
						onClick={onSelect(_value)}
						onKeyDown={onSelect(_value)}
					>
						{icon && <i className="icon">{icon}</i>}
						<span className="text">{label}</span>
					</li>
				);
			})}
		</ul>
	</div>
}

const SelectedBadge = ({ text, onCancel }) => {
	return (
		<SelectedBadgeStyle className="cw__selected-badge">
			{text}
			<button
				type="button"
				aria-label="cancel"
				className="cw__cancel"
				onClick={onCancel}
			>
				{Icons.close}
			</button>
		</SelectedBadgeStyle>
	);
};

const removeItems = (a, b) => {
	return a?.filter((item) => {
		return b?.indexOf(item.value) < 0;
	});
};

const Select = ({
	onChange,
	onCancelClick,
	options,
	value,
	isMultiple,
	isSearchable,
	placeholder,
	variant,
	style,
}) => {
	const [selectOptions, setSelectOptions] = useState(options);
	const selectInput = useRef(null);
	const chosen = options?.find((a) => a.value === value);

	useEffect(() => {
		if (isMultiple) {
			setSelectOptions(removeItems(options, value));
		}
	}, [value]);

	const handleSelectOnKeyDown = (_value) => (e) => {
		if (e.type === "click" || (e.type === "keydown" && e.key === "Enter")) {
			onChange(
				isMultiple
					? !value.includes(_value)
						? [...value, _value]
						: value.filter((v) => v != _value)
					: _value,
			);
			selectInput.current.focus();
		}
	};

	const handleOnSearch = (e) => {
		const keywords = e.target.value.toLowerCase();
		setSelectOptions(
			isMultiple
				? removeItems(options, value).filter((f) =>
					f.label.toLowerCase().match(keywords),
				)
				: options.filter((f) => f.value.toLowerCase().split("-").join(" ").match(keywords)),
		);
	};

	return (
		<SelectStyles className={`${isMultiple ? " is-multiple" : ""} ${variant || ""}`}>
			<Tippy
				content={
					<SelectOptions
						value={value}
						isSearchable={isSearchable}
						options={(isMultiple || isSearchable) ? selectOptions : options}
						onSelect={handleSelectOnKeyDown}
						onSearch={handleOnSearch}
					/>
				}
				trigger="click"
				arrow={false}
				interactive
			>
				<div
					className={`cw__custom-select`}
				>
					<div
						tabIndex={0}
						className="cw__custom-select__input-wrapper"
						ref={selectInput}
						style={style}
					>
						{isMultiple &&
							value?.map((val, i) => {
								const _selectedValue = options?.find(
									(a) => a.value == val,
								)?.label;
								return (
									<SelectedBadge
										key={i}
										text={_selectedValue}
										onCancel={() => {
											onCancelClick ? onCancelClick(val) : onChange(value?.filter((a) => a !== val))
										}}
									/>
								);
							})
						}
						{!isMultiple && (
							<span className="cw__custom-select__input-value">
								{chosen?.icon}
								{chosen?.label}
							</span>
						)}
						{placeholder && value?.length <= 0 && (
							<span className="placeholder">{placeholder || "Select"}</span>
						)}
					</div>
				</div>
			</Tippy>
		</SelectStyles>
	);
};

export default Select
