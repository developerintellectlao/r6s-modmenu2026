import { createRoot } from '@wordpress/element'
import CustomizerReset from './CustomizerReset';

const onDocumentLoaded = (cb) => {
    if (/comp|inter|loaded/.test(document.readyState)) {
        cb()
    } else {
        document.addEventListener('DOMContentLoaded', cb, false)
    }
}

onDocumentLoaded(() => {
    const modal_portal = document.createElement('div');
    modal_portal.setAttribute("id", "reset-portal")
    document.body.appendChild(modal_portal);
    createRoot(modal_portal).render(<CustomizerReset />);
})
