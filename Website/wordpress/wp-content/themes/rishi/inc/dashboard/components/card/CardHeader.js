import classnames from 'classnames';
import Icon from "../../icons";

export default ({ children, title, icon, fontSize, fontVariant, className, spacing, ...rest }) => {
    return <div className={classnames('rishi-ad_card-header', {
        [`${className}`]: className,
    })} style={{ '--padding-bottom': spacing?.pb, '--margin-bottom': spacing?.mb }} {...rest}>
        {(title || icon) && <h3 className={classnames('rishi-ad_card-title', {
            [`rishi-ad_card-title-${fontVariant}`]: fontVariant
        })}>{icon && <Icon icon={icon} style={{ fontSize: fontSize }} />}{title}</h3>}
        {children}
    </div>
}