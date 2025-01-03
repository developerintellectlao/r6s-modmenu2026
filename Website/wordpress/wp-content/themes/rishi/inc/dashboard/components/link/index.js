import Icon from "../../icons";
import classnames from 'classnames'

export default (props) => {
    const { children, to, arrow, ...rest } = props;
    return <a href={to} {...rest} className={classnames("rishi-ad_link", {
        'has-arrow': arrow
    })}>
        {children}
        {arrow && <Icon icon="arrowRight" size={{ width: '14px', height: '12px'}}/>}
    </a>
}