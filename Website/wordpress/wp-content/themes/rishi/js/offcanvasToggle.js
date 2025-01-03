import submenuToggle from "./submenuToggle";
import setStyle from "./setStyle";

function offcanvasToggle() {

	const site = document.querySelector('.site-content');
	const offcanvas = document.querySelector('.rishi-offcanvas-drawer');
	const offcanvasBodies = document.querySelectorAll('.rishi-drawer-inner'); 
	const offcanvasToggles = [...new Set([
		...document.querySelectorAll('[href="#rishi-offcanvas"]'),
		...document.querySelectorAll('[aria-controls="rishi-offcanvas"]')
	])]

	if(!offcanvas) return;
	const firstFocus = offcanvas.querySelector('[aria-controls="rishi-offcanvas"]');

	let returnFocus;

	let isOffcanvasOpen;
	let isOffcanvasOpening;

	function resetOffcanvas() {
		offcanvas.setAttribute('aria-hidden', 'true');
		isOffcanvasOpen = false;
		isOffcanvasOpening = false;
	}

	resetOffcanvas();

	document.body.addEventListener('focus', e => {
		if (isOffcanvasOpen && !e.target.closest('.rishi-drawer-wrapper')) {
			firstFocus.focus()
		}
	}, { capture: true }
	)

	document.addEventListener('keydown', e => {
		if (isOffcanvasOpen && e.key === 'Escape') {
			closeOffcanvas();
		}
	}
	)

	document.addEventListener('click', e => {
		if (isOffcanvasOpen && e.target === offcanvas) {
			closeOffcanvas();
		}
	})

	offcanvasToggles.forEach(toggle => {
		toggle.setAttribute('aria-controls', 'rishi-offcanvas');
		function handleToggle(e) {
			e.preventDefault();
			if (!isOffcanvasOpening) {
				isOffcanvasOpen ? closeOffcanvas() : openOffcanvas();
			}
		};

		toggle.addEventListener('click', handleToggle);
		if (toggle.hasAttribute('href')) {
			toggle.addEventListener('keydown', e => {
				if (e.key === 'Enter' || e.key === ' ') {
					handleToggle(e);
				}
			})
		}
	})

	function openOffcanvas() {
		isOffcanvasOpen = true;
		returnFocus = document.activeElement;
		window.requestAnimationFrame(() => {
			offcanvasToggles.forEach(toggle => {
				toggle.setAttribute('aria-expanded', 'true');
			});
			setStyle(document.body, { overflow: 'hidden' })
			setStyle(site, { 'pointer-events': 'none' })
			offcanvas.setAttribute('aria-hidden', 'false')
		})
	}

	function closeOffcanvas() {
		isOffcanvasOpen = false;
		window.requestAnimationFrame(() => {
			offcanvasToggles.forEach(toggle => {
				toggle.setAttribute('aria-expanded', 'false');
			});
			setStyle(document.body, { overflow: false })
			setStyle(site, { 'pointer-events': false })
			offcanvas.setAttribute('aria-hidden', 'true')
			returnFocus.focus();
		})
	}

	offcanvasBodies.forEach(offcanvasBody => {
		offcanvasBody.addEventListener('click', e => {
			if(offcanvas.getAttribute('aria-hidden') === 'true') return;
	
			const toggleBtn = e.target.closest('.submenu-toggle')
			if( toggleBtn) {
				e.preventDefault()
				e.stopPropagation()
				submenuToggle(toggleBtn.closest('.menu-item-has-children'))
			}
		})
	})
}

export default offcanvasToggle;