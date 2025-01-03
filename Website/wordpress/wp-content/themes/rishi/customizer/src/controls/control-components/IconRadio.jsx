import { Icons, SelectButtonGroup } from '@components'

const IconRadio = ({ value, onChange, option }) => {
    const options = Object.entries(option.choices).map(([key, rest]) => ({ value: key, icon: Icons[rest.icon], title: rest.title }))
    return (
        <SelectButtonGroup
            value={value}
            onChange={onChange}
            options={options}
            style={option?.style}
        />
    )
}

export default IconRadio