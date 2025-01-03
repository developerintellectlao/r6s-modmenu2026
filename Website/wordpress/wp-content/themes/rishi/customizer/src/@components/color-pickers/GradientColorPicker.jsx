import styled from "@emotion/styled";
import { GradientPicker } from "@wordpress/components";
import ColorPickerTrigger from "./ColorPickerTrigger";

const GradientPickerStyles = styled.div`
  .components-custom-gradient-picker__gradient-bar {
    position: relative;
    .components-custom-gradient-picker__gradient-bar-background {
      height: 50px;
      border-radius: var(--border-radius);
    }
    .components-custom-gradient-picker__control-point-dropdown {
      position: absolute;
      top: 0;
      margin-top: 12.5px;
      .components-button {
        border: 2px solid #ffffff;
        width: 25px;
        height: 25px;
        border-radius: 50%;
        background: none;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
        svg {
          fill: #ffffff;
          width: 24px;
          height: 24px;
        }
      }
    }
  }
`;

const GradientColorPicker = ({ value, title, ...ControlGroup }) => {
  return (
    <ColorPickerTrigger color={value} title={title}>
      <GradientPickerStyles>
        <div className="cw__components-color-picker">
          <GradientPicker
            __nextHasNoMargin
            value={value}
            gradients={[
              {
                name: "JShine",
                gradient:
                  "linear-gradient(135deg,#12c2e9 0%,#c471ed 50%,#f64f59 100%)",
                slug: "jshine",
              },
              {
                name: "Moonlit Asteroid",
                gradient:
                  "linear-gradient(135deg,#0F2027 0%, #203A43 0%, #2c5364 100%)",
                slug: "moonlit-asteroid",
              },
              {
                name: "Rastafarie",
                gradient:
                  "linear-gradient(135deg,#1E9600 0%, #FFF200 0%, #FF0000 100%)",
                slug: "rastafari",
              },
            ]}
            clearable={false}
            {...ControlGroup}
          />
        </div>
      </GradientPickerStyles>
    </ColorPickerTrigger>
  );
};

export default GradientColorPicker