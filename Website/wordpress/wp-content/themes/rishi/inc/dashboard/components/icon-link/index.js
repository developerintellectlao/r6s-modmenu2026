const Icon = ({href, icon, ...rest}) => {
    return href ? <a href={href} className="rishi-ad_icon-wrap" {...rest}>{icon}</a> : <span className="rishi-ad_icon-wrap" {...rest}>{icon}</span>;
}

export default (props) => {
    const {className, title, ...rest} = props;
    return (
        <div className={`rishi-ad_icon-link${className && ' '+className || ''}${title && ' has-tooltip' || ''}`}>
            <Icon {...rest}  />
            {
                title && <span className="rishi-ad_tooltip" data-placement="bottom">{title}</span>
            }
        </div>
    )
}