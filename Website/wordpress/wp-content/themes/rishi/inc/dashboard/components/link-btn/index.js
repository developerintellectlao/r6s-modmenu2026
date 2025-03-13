import Icon from "../../icons";

export default ({children, to, icon, ...rest}) => {
    return (
        <a href={to} {...rest} className="rishi-ad_link-btn">
            {icon && <Icon icon={icon.before} size={{ width: '20px', height: '20px'}}/>}
            {children}
            {icon && <Icon icon={icon.after} size={{ width: '20px', height: '20px'}}/>}
        </a>
    )

}