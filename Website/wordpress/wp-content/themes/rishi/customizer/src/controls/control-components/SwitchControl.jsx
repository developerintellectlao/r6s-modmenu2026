import { Switch as SwitchControl } from "@components";

const Switch = ({ value, onChange }) => {
	return <SwitchControl value={value === "yes"} onChange={val => onChange(val ? "yes" : "no")} />
};

Switch.config = {
	design: "inline",
};

export default Switch;
