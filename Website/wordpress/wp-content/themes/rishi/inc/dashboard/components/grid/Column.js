export default ({ children, width, ...rest }) => {
    return <div className="rishi-ad_col" style={{ '--width': width?.lg&&width?.lg, '--width-md': width?.md&&width?.md, '--width-sm': width?.sm&&width?.sm }} {...rest}>{children}</div>
}