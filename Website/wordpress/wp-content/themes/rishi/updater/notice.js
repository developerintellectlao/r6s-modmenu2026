document.addEventListener('DOMContentLoaded', function() {
    let activationLink = document.querySelector('.rishi-activation-link');
    let activationAttribute = document.querySelector('.notice-rishi-theme-activation-root');

    // Check if the element exists before attaching the event listener
    if (null !== activationLink) {

        activationLink.addEventListener('click', function(event) {
            // Prevent the default action of the link
            event.preventDefault();

            let activationNonce = activationAttribute.dataset.nonce;
            let activationURL = activationAttribute.dataset.link;
            let xhr = new XMLHttpRequest();
            let url = wp.ajax.settings.url; // Replace ajaxurl with the actual URL if needed
            let data = {
                action: "rishi_activate_license_updates",
                nonceToken: activationNonce // Make sure nonceToken is defined in your scope
            };

            // Convert the data object to a URL-encoded string
            let encodedData = Object.keys(data).map(function(key) {
                return encodeURIComponent(key) + '=' + encodeURIComponent(data[key]);
            }).join('&');

            xhr.open('POST', url, true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState ===  4 && xhr.status ===  200) {
                    let response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        if (response.data.license === "valid") {
                            location.assign(activationURL); // Make sure redirectURL is defined in your scope
                        }
                    } 
                } else if (xhr.readyState ===  4) {
                    // Handle errors
                    console.error('An error occurred during the transaction');
                }
            };
            xhr.send(encodedData);
        });
    }
});