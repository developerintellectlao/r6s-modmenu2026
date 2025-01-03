import { sprintf,__ } from '@wordpress/i18n'
import { Button } from "../../components";
import StarterImg from "../../../assets/images/starter-sites-banner.png";
import Icon from "../../icons";
import { useState } from '@wordpress/element'

function rishi_activatePlugin() {
    let data = new FormData();
    data.append('action', 'rishi_get_install_starter');
    data.append('security', rishi_dashboard.ajax_nonce);
    data.append('status', rishi_dashboard.status);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', rishi_dashboard.ajaxUrl, true);
    xhr.onload = function () {
        if (this.status >= 200 && this.status < 400) {
            var response = JSON.parse(this.response);
            if (response.success) {
                location.replace(rishi_dashboard.starterURL);
            }
        } else {
            console.error('Server responded with a status of ' + this.status);
        }
    };
    xhr.onerror = function() {
        console.error('Request failed');
    };
    xhr.send(data);
}
export default () => {
	const [working, setWorking] = useState(null);
    const handleClick = () => {
        setWorking(true);
		rishi_activatePlugin();
    }

    return <>
        <div className="rishi-main-wrapper">
            <div className="rishi-left-card">
                <h4 className="starter-title">{__( 'Rishi Theme Templates To Get Started', 'rishi' )}</h4>
                <img src={StarterImg}/>
                <div className="rishi-section-content">
                    <p
                        className="rishi-para-content"
                        dangerouslySetInnerHTML={{
                            __html: sprintf(
                                __(
                                    "Rishi theme includes variety of starter templates suited for different niches of websites. New designs are added frequently to the collection. %sVisit Here%s to see all the templates.",
                                    'rishi'
                                ),
                                '<a href="https://rishitheme.com/starter-sites/" target="_blank">',
                                "</a>"
                            ),
                        }}
                    />
                    {rishi_dashboard.starterTemplates && (
                        <a
                            className="rishi-ad_btn rishi-ad_btn-primary"
                            href={rishi_dashboard.starterURL}
                        >
                            {rishi_dashboard.starterLabel}
                        </a>
                    )}
                    {!rishi_dashboard.starterTemplates && (
                        <>
                            <Button onClick={() => handleClick()} variant="starter">
                                {rishi_dashboard.starterLabel}
                                <Icon icon="arrowRight" />
                                {working && (
                                <Icon icon="loading" />
                            )}
                            </Button>

                        </>
                    )}
                </div>
            </div>
        </div>
    </>
}
