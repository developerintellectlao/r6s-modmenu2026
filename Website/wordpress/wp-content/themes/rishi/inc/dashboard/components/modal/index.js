import { createPortal } from "@wordpress/element";
import Icon from "../../icons";

export default ({ title, children, openModal, setOpenModal, className, placement, ...rest }) => {
    return <div>
        {
            openModal && createPortal(<div className={`rishi-ad_modal${className && ' ' + className || ''}${placement && ' rishi-ad_modal-' + placement || ''}`} {...rest}>
                {title && <div className="rishi-ad_modal-header"><h3 className="rishi-ad_modal-title">{title}</h3><button type="button" className={["rishi-ad_modal-close"]} onClick={() => setOpenModal(false)}><Icon icon="times" /></button></div>}
                {children}
            </div>, document.body)
        }
    </div>
}
