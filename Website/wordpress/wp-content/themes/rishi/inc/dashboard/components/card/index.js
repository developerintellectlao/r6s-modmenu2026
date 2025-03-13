import classnames from "classnames";
import CardBody from "./CardBody";
import CardHeader from "./CardHeader";

export default (props) => {
    const { children, size, className, isLoading, ...rest } = props;

    if (isLoading) {
        return <div className={`rishi-ad_card is-loading${size && ' rishi-ad_card-' + size || ''}`}>
            <CardHeader />
            <CardBody />
        </div>
    }
    return <div className={classnames('rishi-ad_card', {
        [`rishi-ad_card-${size}`]: size,
        [`${className}`]: className
    })} {...rest}>{children}</div>
}
