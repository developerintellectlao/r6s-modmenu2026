import TabsContainer from './TabsContainer'

const containers = {
    TabsContainer,
}

export default function ContainerBase(props) {
    const Component = containers[props.optionGroup?.[0]?.control || -1] || null

    return Component ? <Component {...props} /> : null
}

export { TabsContainer }
