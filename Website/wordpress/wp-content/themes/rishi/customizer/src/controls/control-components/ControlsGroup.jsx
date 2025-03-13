import styled from "@emotion/styled";
import ControlBase from "./ControlBase";

const ControlsGroupItem = styled.div`
	margin-top: -4px;
	> .rishi-control{
		margin-left: -16px;
		margin-right: -16px;
		padding-top: 4px;
		padding-bottom: 4px;
		padding-left: 42px;
		position: relative;
		&::before{
			content: "";
			width: 16px;
			height: 16px;
			position: absolute;
			top: 50%;
			left: 16px;
			transform: translateY(-50%);
			background-image: url("data:image/svg+xml,%3Csvg width='16' height='16' viewBox='0 0 16 16' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cg clip-path='url(%23clip0_510_119013)'%3E%3Cpath d='M0.559784 0C0.411263 0 0.268824 0.0589998 0.163803 0.16402C0.0587826 0.269041 -0.000216484 0.411479 -0.000216484 0.56V7.1824C0.000206947 8.60354 0.564998 9.96635 1.56997 10.9712C2.57495 11.976 3.93784 12.5406 5.35898 12.5408L14.1558 12.54L11.651 15.044C11.599 15.096 11.5577 15.1577 11.5296 15.2257C11.5014 15.2936 11.487 15.3665 11.487 15.44C11.487 15.5135 11.5014 15.5864 11.5296 15.6543C11.5577 15.7223 11.599 15.784 11.651 15.836C11.703 15.888 11.7647 15.9293 11.8327 15.9574C11.9006 15.9855 11.9734 16 12.047 16C12.1205 16 12.1934 15.9855 12.2613 15.9574C12.3292 15.9293 12.391 15.888 12.443 15.836L15.835 12.4432C15.887 12.3912 15.9282 12.3295 15.9564 12.2615C15.9845 12.1936 15.999 12.1207 15.999 12.0472C15.999 11.9737 15.9845 11.9008 15.9564 11.8329C15.9282 11.7649 15.887 11.7032 15.835 11.6512L12.4422 8.2584C12.3902 8.2064 12.3284 8.16514 12.2605 8.137C12.1926 8.10886 12.1197 8.09437 12.0462 8.09437C11.9726 8.09437 11.8998 8.10886 11.8319 8.137C11.7639 8.16514 11.7022 8.2064 11.6502 8.2584C11.5982 8.3104 11.5569 8.37214 11.5288 8.44009C11.5006 8.50803 11.4862 8.58086 11.4862 8.6544C11.4862 8.72794 11.5006 8.80077 11.5288 8.86871C11.5569 8.93666 11.5982 8.9984 11.6502 9.0504L14.0222 11.4208H5.35818C4.23415 11.4206 3.15622 10.974 2.36142 10.1792C1.56661 9.38436 1.12 8.30643 1.11978 7.1824V0.56C1.11978 0.411479 1.06078 0.269041 0.955763 0.16402C0.850742 0.0589998 0.708305 0 0.559784 0Z' fill='%23CED0D3'/%3E%3C/g%3E%3Cdefs%3E%3CclipPath id='clip0_510_119013'%3E%3Crect width='16' height='16' fill='white' transform='matrix(-1 0 0 1 16 0)'/%3E%3C/clipPath%3E%3C/defs%3E%3C/svg%3E%0A");
			background-repeat: no-repeat;
			background-size: 100%;
		}
	}
`

const ControlsGroup = ({ option, value, onChange, values }) => {
	const { settings } = option;

	return <ControlsGroupItem>
		{
			Object.entries(value).map(([id, _value]) => {
				const option = settings[id]
				if (!option) return null
				return <ControlBase key={id} id={id} value={_value} values={values} option={option} onChange={(val) => onChange({ ...value, [id]: val })} hasRevertButton />
			})
		}
	</ControlsGroupItem>
}

export default ControlsGroup
