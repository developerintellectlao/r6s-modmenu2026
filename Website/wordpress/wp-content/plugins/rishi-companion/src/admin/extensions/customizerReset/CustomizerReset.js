import { useState } from '@wordpress/element';
import { Modal } from "@wordpress/components";
import { __ } from '@wordpress/i18n';
import Icon from '../../ui-components/Icon';

const CustomizerReset = () => {

    const [modalOpen, setModalOpen] = useState(false)
    const [loading, setLoading] = useState(false);

    document.addEventListener("click", e => {
        if (e.target.classList.contains('customizer-reset')) {
            setModalOpen(true)
        }
    })
    const closeModal = () => setModalOpen(false);

    const resetSettings = () => {
        let formData = new FormData();
        formData.append('action', 'rishi_customizer_reset');
        formData.append('nonce', Rishi_Reset_Data.nonce);

        setLoading(!loading)

        fetch(Rishi_Reset_Data.ajaxUrl, {
            method: 'POST',
            body: formData
        })
            .then(response => {
                if (response.ok) {
                    wp.customize.state('saved').set(true);
                    location.reload();
                }
            })
            .catch((error) => {
                console.log(error)
            });
    }

    return <>
        {modalOpen &&
            <Modal
                title={__('Reset Settings', 'rishi-companion')}
                onRequestClose={closeModal}
                shouldCloseOnClickOutside={false}
                shouldCloseOnEsc={true}
                isDismissible={false}
                className="rishi-customizer-reset"
            >
                <div className="rishi-reset-wrapper">
                    <p>{__('You are about to restore all settings back to their original state, do you wish to proceed?', 'rishi-companion')}</p>
                    <div className="reset-confirm-btns">
                        <button className='rishi-reset-btn reset-cancel' onClick={closeModal}>{__('Cancel', 'rishi-companion')}</button>
                        <button className='rishi-reset-btn reset-confirm' onClick={resetSettings}>
                            {__('Confirm', 'rishi-companion')}
                            {
                                loading && <Icon icon="loading" />
                            }
                        </button>
                    </div>
                </div>
            </Modal>}
    </>
}

export default CustomizerReset