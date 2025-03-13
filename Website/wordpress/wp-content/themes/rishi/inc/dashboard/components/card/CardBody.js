import classnames from "classnames";

export default ({ children, className, padding, radius, ...rest }) => {
    return <div className={classnames('rishi-ad_card-body', {
        [`${className}`]: className,
    })} style={{ '--padding': padding, '--radius': radius }} {...rest}>
        {children}
    </div>
}