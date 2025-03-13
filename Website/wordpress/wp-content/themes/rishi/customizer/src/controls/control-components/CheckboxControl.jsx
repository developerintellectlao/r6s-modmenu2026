import styled from "@emotion/styled";
import { orderChoicesIfNeeded } from "@helpers";

const CheckboxesWrapper = styled.div`
	display: flex;
	align-items: center;
	flex-wrap: wrap;
	row-gap: 10px;
	&[data-columns="1"]{
		label{
			width: 100%;
		}
	}
	&[data-columns="2"]{
		label{
			width: 50%;
			padding-right: 16px;
		}
	}
	input[type=checkbox]{
		appearance: none;
		border: none;
		margin: -0.25em 8px 0 0;
		&::before{
			content: "";
			display: block;
			width: 16px;
			height: 16px;
			border-radius: 4px;
			border: 1px solid var(--cw__border-color);
			background-color: #ffffff;
			margin: 0;
		}
		&:checked{
			&::before{
				border-color: var(--cw__secondary-color);
				background-color: var(--cw__background-color);
				background-image: url("data:image/svg+xml,%3Csvg width='12' height='13' viewBox='0 0 12 13' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M10 3.5L4.5 9L2 6.5' stroke='%23216BDB' stroke-width='1.6666' strokeLinecap='round' strokeLinejoin='round'/%3E%3C/svg%3E%0A");
				background-repeat: no-repeat;
				background-position: center;
				background-size: 12px 12px;
			}
		}
	}
`

const Checkboxes = ({
	option,
	value,
	onChange,
	option: { view = "checkboxes" },
}) => {
	const options = orderChoicesIfNeeded(option.choices);

	const { inline = false } = option;

	return <CheckboxesWrapper className="cw__checkbox"
		{...(inline ? { ["data-inline"]: "" } : {})}
		{...(option.attr || {})}
	>
		{
			options.map(({ key, value: _value }) => {
				return <label key={key}>
					<input
						data-id={key}
						type="checkbox"
						checked={typeof value[key] === "boolean" ? value[key] : value[key] === "true"}
						onChange={() => onChange(
							{
								...value,
								[key]: value[key]
									? Object.values(value).filter((v) => v).length === 1 &&
										!option.allow_empty
										? true
										: false
									: true,
							}
						)}
					/>
					{_value}
				</label>
			})
		}
	</CheckboxesWrapper>
};

export default Checkboxes;
