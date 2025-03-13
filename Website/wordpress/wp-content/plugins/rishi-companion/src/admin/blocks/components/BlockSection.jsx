const BlockSection = ({ id, sectionLabel,titleSelector: TitleSelector, layoutStyle, children, className }) => {
    return <section id={id} className={className}>
        {sectionLabel ? (
                <TitleSelector className="widget-title">
                    <span>{sectionLabel}</span>
                </TitleSelector>
            ) : ''}
        <ul className={layoutStyle}>
            {children}
        </ul>
    </section>
}

export default BlockSection
