import offcanvasToggle from "./offcanvasToggle";
import headerSearchTrigger from "./headerSearchTrigger";
import scrollToTop from "./scrollToTop";
import infiniteScroll from "./infiniteScroll";
import submenuPosition from "./submenuPosition";
import accessibility from "./accessibility";
import debounce from "./debounce";
import scrollTrigger from "./animation";

/**
 * Is the DOM ready?
 *
 * This implementation is coming from https://gomakethings.com/a-native-javascript-equivalent-of-jquerys-ready-method/
 */
function isDOMReady(fn) {
	if (typeof fn !== 'function') return;

	if (document.readyState === 'complete') {
		return fn();
	}

	document.addEventListener('DOMContentLoaded', fn, false);
}

const animationClasses = ['.slide-up-fade-in', '.slide-down-fade-in', '.slide-left-fade-in', '.slide-right-fade-in', '.clipIn'];

isDOMReady(function () {
	accessibility();
	offcanvasToggle();
	headerSearchTrigger();
	scrollToTop();
	submenuPosition();
	infiniteScroll();
	scrollTrigger(animationClasses, {
		rootMargin: '0px'
	});
	const debouncedResize = debounce(() => {
		submenuPosition();
	}, 250);

	window.addEventListener('resize', debouncedResize);
})