const Textarea = ({ onChange, ...props }) => {
	return <textarea onChange={(e) => onChange(e.target.value)} {...props} />
}

export default Textarea


