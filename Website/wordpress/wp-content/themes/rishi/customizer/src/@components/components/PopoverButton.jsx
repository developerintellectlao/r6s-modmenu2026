import styled from "@emotion/styled";
import Icons from "./Icons";
import Tooltip from "./Tooltip";

const PopoverButtonStyle = styled.button`
  padding: 4px;
  border: none;
  border-radius: var(--cw__border-radius);
  cursor: pointer;
  background: none;
  box-shadow: 0 0 0 1px var(--cw__border-color);
  &:hover,
  &.changed {
    color: var(--cw__secondary-color);
    box-shadow: 0 0 0 1px var(--cw__secondary-color);
  }
  svg{
    vertical-align: top;
  }
`;

const PopoverButton = ({ changed, title }) => {
	return (
		<Tooltip title={title}>
			<PopoverButtonStyle
				type="button"
				className={changed === 1 ? "changed" : ""}
			>
				{Icons.pen}
			</PopoverButtonStyle>
		</Tooltip>
	);
};

export default PopoverButton;
