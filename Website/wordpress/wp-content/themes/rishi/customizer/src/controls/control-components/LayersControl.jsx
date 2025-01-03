import { InnerControlWrapper, Popover, PopoverButton, Select, Sortable, SortableItem } from '@components'
import ControlBase from './ControlBase'
import Tabs from './TabsContainer';

const PopoverContent = ({ settings, value, onChange, values }) => {
	const { options } = settings
	return (
		<>
			{Object.entries(options).map((_props) => {
				const [key, option] = _props;
				const revertBtn = typeof option?.hasRevertButton === 'boolean' ? option?.hasRevertButton : true;
				if (key === "tab") {
					let tabsValue = {};
					const optionGroup = Object.entries(option).map(([id, obj]) => ({ id: id, ...obj }));
					Object.entries(option).forEach(([, obj]) => Object.entries(obj.options).forEach(([id, { value: _val }]) => tabsValue = { ...tabsValue, [id]: _val }))
					return <Tabs
						optionGroup={optionGroup}
						value={value?.tabsValue || values}
						onChange={(a, b) => onChange({ ...value, tabsValue: { ...tabsValue, [a]: b } })}
					/>
				} else {
					return <ControlBase
						key={key}
						id={key}
						value={value[key]}
						option={option}
						onChange={(val) => onChange({ ...value, [key]: val })}
						hasRevertButton={revertBtn}
					/>
				}
			})}
		</>
	)
}

const SortableList = ({ value, onChange, option, values }) => {
	const { id: layerId, settings } = option

	const handleOnChange = (id, val) => {
		onChange(
			value.map((obj) => {
				if (obj.id === id) {
					return { ...obj, ...val }
				}
				return obj
			})
		)
	}

	const handleSelect = (ids) => {
		ids.forEach((id) => {
			const { options } = settings[id]
			const hasId = value.find((a) => a.id === id);
			let fieldsValue = {}
			Object.entries(options).map(([key, value]) => {
				fieldsValue = {
					...fieldsValue,
					[key]: value.value,
				}
			})
			const objectItem = { id, enabled: true, ...fieldsValue }
			onChange(hasId ? value : [...value, objectItem])
		})
	}
	const contactOptions = Object.entries(settings).map(([_val, obj]) => ({ value: _val, label: obj.label }))
	const contactValue = value.map((a) => a.id)

	return (
		<>
			{(layerId === 'contact_items' || layerId === 'header_socials' || layerId === 'footer_socials') && (
				<Select
					options={contactOptions}
					value={contactValue}
					onChange={(_ids) => handleSelect(_ids)}
					onCancelClick={(_id) => onChange(value.filter((a) => a.id !== _id))}
					isMultiple
					isSearchable
					style={{ marginBottom: '16px' }}
				/>
			)}
			<Sortable items={value} setItems={onChange}>
				{value.map((item) => {
					const { id } = item
					const setting = settings[id]
					const { label, ...rest } = setting
					const enabled = item.enabled

					return (
						<SortableItem key={id} id={id} enabled={enabled}>
							<InnerControlWrapper
								label={label}
								direction="horizontal"
								enabled={enabled}
								onVisibilityClick={() => handleOnChange(id, { enabled: !enabled })}
								hasOptions={setting?.options}
								{...rest}
							>
								{setting?.options && <Popover
									content={
										<PopoverContent
											settings={setting}
											value={item}
											onChange={(val) => handleOnChange(id, val)}
											values={values}
										/>
									}
								>
									<PopoverButton />
								</Popover>}
							</InnerControlWrapper>
						</SortableItem>
					)
				})}
			</Sortable>
		</>
	)
}

export default SortableList
