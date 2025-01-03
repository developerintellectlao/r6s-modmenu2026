import classBinder from "../classBinder";

const Button = (props) => {
    const { variant, children, className, href, colorScheme, ...rest } = props;

    let explicitClasses;
    (function testing() {
        if (!typeof className === 'object' || !className || typeof className === 'string') {
            explicitClasses = className;
        } else {
            explicitClasses = classBinder(className)
        }
    })()
    if (href) {
        return <a href={href} className={`rishi-ad_btn${variant && ' rishi-ad_btn-' + variant || ''}${explicitClasses && ' ' + explicitClasses || ''}${colorScheme && ' color-scheme-' + colorScheme || ''}`} {...rest}>{children}</a>
    } else {
        return <button className={`rishi-ad_btn${variant && ' rishi-ad_btn-' + variant || ''}${explicitClasses && ' ' + explicitClasses || ''}${colorScheme && ' color-scheme-' + colorScheme || ''}`} {...rest}>{children}</button>
    }
}

export default Button
