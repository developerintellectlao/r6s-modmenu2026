import { DateTimePicker } from "@wordpress/components";
import { useState } from "@wordpress/element";
import { Popover } from "./components";
const moment = require("moment");


const MyDateTimePicker = ({ value, format, placeholder, ...rest }) => {
	const [datePopover, setDatePopover] = useState(false);

	const dateFormat = format || "YYYY-MM-DD, h:mm:ss a";
	const date = moment(value).format(dateFormat);

	const handleOnKeyDown = (e) => {
		if (e.type === "keydown" && e.key === "Enter") {
			setDatePopover(true);
		}
	};

	return (
		<div className="cw__date-picker-wrapper">
			<Popover
				content={<DateTimePicker currentDate={value} {...rest} />}
			>
				<input
					tabIndex={0}
					value={date === "Invalid date" ? "" : date}
					className="cw__date-picker__date-input"
					type="text"
					readOnly
					placeholder={placeholder || dateFormat}
					onClick={() => setDatePopover(!datePopover)}
					onKeyDown={handleOnKeyDown}
				/>
			</Popover>
		</div>
	);
};

export default MyDateTimePicker;
