import { Card, Column } from "..";

const Loader = ({ count, columnProps, ...rest }) => {
    return [...Array(count)].fill('count').map((c, index) => <Column {...columnProps} key={c + index}><Card isLoading {...rest} /></Column>)
}


export default Loader
