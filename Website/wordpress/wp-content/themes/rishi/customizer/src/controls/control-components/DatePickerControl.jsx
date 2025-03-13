import { DateTimePicker } from "@components"

const DatePickerControl = ({value, onChange, option}) => {
  return <DateTimePicker {...option} value={value} onChange={onChange} />
}

export default DatePickerControl