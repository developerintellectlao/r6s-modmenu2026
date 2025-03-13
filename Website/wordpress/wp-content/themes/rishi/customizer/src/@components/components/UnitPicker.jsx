import styled from "@emotion/styled";
import Tippy from "@tippyjs/react";

const UnitPickerStyles = styled.div`
  display: inline-flex;
  position: relative;
  button {
    min-width: 40px;
    border: none;
    border-radius: var(--cw__border-radius);
    background-color: var(--cw__background-color);
    cursor: pointer;
    min-height: 36px;
    text-transform: uppercase;
    &:hover {
      color: var(--cw__secondary-color);
    }
    &:focus {
      outline: 1px dotted;
    }
  }
  ${props => props.disabled && `
	pointer-events: none;
	cursor: default;
	button{
		color: rgba(0,0,0, .25) !important;
	}`}
`;

const PickerOptions = styled.div`
	max-width: 72px;
	width: 72px;
	border-radius: var(--cw__border-radius);
	background-color: var(--cw__background-color);
	display: flex;
	flex-wrap: wrap;
	span {
		min-width: 35px;
		flex-basis: 0;
		flex-grow: 1;
		display: inline-block;
		padding: 0.5rem 0.25rem;
		text-align: center;
		font-size: 12px;
		cursor: pointer;
		border-top: 1px solid #dcdcdc;
		&:nth-of-type(2n + 1) {
			border-right: 1px solid #dcdcdc;
		}
		&:nth-of-type(-n + 2) {
			border-top: 0;
		}
		&:last-child {
			border-right: 0;
		}
		&:hover {
			background-color: #ffffff;
		}
	}
`

const UnitPicker = ({ value, onChange, units }) => {

	const handleSelectOnKeyDown = (_unit) => (e) => {
		if (e.type === "click" || (e.type === "keydown" && e.key === "Enter")) {
			onChange(_unit);
		}
	};

	const isDisabled = !units || units.length <= 1;

	return (
		<UnitPickerStyles className="cw__unit-picker-wrapper" disabled={isDisabled}>
			<Tippy
				trigger="click"
				interactive
				theme="light"
				className="cw_popover cw__unit-picker-popover"
				content={
					<PickerOptions className="cw__unit-picker-options">
						{units?.map((unit, i) => {
							return (
								<span
									key={i}
									tabIndex={0}
									onClick={handleSelectOnKeyDown(unit)}
									onKeyDown={handleSelectOnKeyDown(unit)}
								>
									{unit}
								</span>
							);
						})}
					</PickerOptions>
				}
				disabled={isDisabled}
			>
				<button
					tabIndex={0}
					type="button"
				>
					{value}
				</button>
			</Tippy>
		</UnitPickerStyles>
	);
};

export default UnitPicker;
