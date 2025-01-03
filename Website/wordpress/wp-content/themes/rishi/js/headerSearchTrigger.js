import setStyle from "./setStyle";

const headerSearchTrigger = () => {
	let headerSearchbtns = document.querySelectorAll('.header-search-btn'),
		searchField = document.querySelector('.search-form-section .search-field'),
		fadeInInterval,
		fadeOutInterval;

	//fadeInFunction
	function fadeIn(element) {
		searchField.focus();
		clearInterval(fadeInInterval);
		clearInterval(fadeOutInterval);

		element.fadeIn = function (timing) {
			let newValue = 0;

			element.style.display = 'block';
			element.style.opacity = 0;

			fadeInInterval = setInterval(function () {

				if (newValue < 1) {
					newValue += 0.1;
				} else if (newValue === 1) {
					clearInterval(fadeInInterval);
				}

				element.style.opacity = newValue;

			}, timing);

		}

		element.fadeIn(2);
		setStyle(document.body, { overflow: 'hidden' })

	}

	//functionfadeOut
	function fadeOut(element) {
		clearInterval(fadeInInterval);
		clearInterval(fadeOutInterval);

		element.fadeOut = function (timing) {
			let newValue = 1;
			element.style.opacity = 1;

			fadeOutInterval = setInterval(function () {

				if (newValue > 0) {
					newValue -= 0.1;
				} else if (newValue < 0) {
					element.style.opacity = 0;
					element.style.display = 'none';
					clearInterval(fadeOutInterval);
				}

				element.style.opacity = newValue;

			}, timing);

		}

		element.fadeOut(2);
		setStyle(document.body, { overflow: false })
	}
	let currentOpenModal = null;

	if (headerSearchbtns !== null) {
		headerSearchbtns.forEach(function (headerSearchbtn) {
			let modalKey = headerSearchbtn.dataset.modalKey;
			let element = document.querySelector(`.search-toggle-form[data-modal-key="${modalKey}"]`);
			let headerCloseBtn = document.querySelector(`.search-toggle-form[data-modal-key="${modalKey}"] .btn-form-close`);
			let SearchFormFld = document.querySelector(`.search-toggle-form[data-modal-key="${modalKey}"] .search-field`);
			let SearchFormScn = document.querySelector(`.search-toggle-form[data-modal-key="${modalKey}"] .search-submit`);
			headerSearchbtn.addEventListener('click', function (event) {
				event.preventDefault();
				this.classList.add('active');
				fadeIn(element);
				searchField.focus();
				currentOpenModal = element;
			});

			headerCloseBtn.addEventListener('click', function (event) {
				// event.preventDefault();
				fadeOut(element);
				searchField.blur();
				headerSearchbtn.classList.remove('active');
				currentOpenModal = null;
			});

			if (element !== null) {
				element.addEventListener('click', function (event) {
					fadeOut(element);
				})
			}

			document.addEventListener('keyup', function (e) {
				if (e.key == "Escape" && currentOpenModal) {
					fadeOut(currentOpenModal);
					currentOpenModal = null;
					e.stopImmediatePropagation();
				}
			});

			if (SearchFormFld !== null) {
				SearchFormFld.addEventListener('click', function (e) {
					e.stopPropagation();
				})
			}

			if (SearchFormScn !== null) {
				SearchFormScn.addEventListener('click', function (e) {
					e.stopPropagation();
				})
			}
		});
	}
};

export default headerSearchTrigger;