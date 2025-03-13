document.body.addEventListener('click', function(event) {
    if (event.target.classList.contains('flush-it')) {
        let formData = new FormData();
        formData.append('action', 'flush_local_google_fonts');
        formData.append('nonce', rishi_cdata.nonce);

        fetch(rishi_cdata.ajax_url, {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(results => {
            document.querySelector('.flush-it').value = rishi_cdata.flushit;
        });
    }
});