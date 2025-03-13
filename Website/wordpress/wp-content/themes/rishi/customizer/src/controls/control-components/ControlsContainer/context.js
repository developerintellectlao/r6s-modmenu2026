import {
	createContext,
	useCallback,
	useContext,
	useEffect,
	useMemo,
	useState
} from '@wordpress/element';

import { addAction } from '@wordpress/hooks';
import { useCustomizePanelReducer } from './reducer';

export const useDeviceView = (args = {}) => {
	const { useMobileView = false } = args;

	const [currentDevice, setCurrentDevice] = useState('desktop');

	const handleDeviceChange = (device) => setCurrentDevice(device);

	useEffect(() => {
		setCurrentDevice(wp?.customize?.previewedDevice() || 'desktop');
		setTimeout(
			() => wp?.customize?.previewedDevice.bind(handleDeviceChange),
			1000
		);
		return () => wp?.customize?.previewedDevice.unbind(handleDeviceChange);
	}, []);

	return [
		useMobileView ? (currentDevice === 'tablet' ? 'mobile' : currentDevice) : currentDevice,
		(device) => {
			setCurrentDevice(device);
			wp.customize && wp.customize.previewedDevice.set(device);
		},
	];
};


let deepLinkLocation = null;

export const getDeepLinkPanel = () => deepLinkLocation ? deepLinkLocation.split(':')[1] : false;
export const removeDeepLink = () => (deepLinkLocation = null);

export const PanelContext = createContext({
	titlePrefix: '',
	isOpen: false,
	isTransitioning: false,
	previousPanel: false,
});

export const usePanelContext = () => useContext(PanelContext);

export const PanelProvider = ({ id, children, containerRef, parentContainerRef, useRefsAsWrappers, ...props }) => {
	const [panelsState, panelsDispatch] = useCustomizePanelReducer({
		isOpen: false,
		isTransitioning: false,
	});

	const handleDeepLinkStart = useCallback((location) => {
		const [_, panelId] = location.split(':');

		panelsDispatch({
			type: panelId ? 'SET_ACTIVE_PANEL' : 'PANEL_IS_CLOSE',
			payload: panelId ? { panelId } : {},
		});
	}, []);

	useEffect(() => {
		addAction('rishi-deeplinkstart', 'rishi', handleDeepLinkStart);

		const deepLinkPanel = getDeepLinkPanel();
		if (deepLinkPanel) {
			setTimeout(() => {
				panelsDispatch({
					type: 'SET_ACTIVE_PANEL',
					payload: { panelId: deepLinkPanel },
				});
				removeDeepLink();
			}, 200);
		}
	}, [handleDeepLinkStart]);

	const panelsHelpers = useMemo(() => ({
		isOpenFor: (panelId) => panelsState.isOpen && panelId === panelsState.isOpen,
		isTransitioningFor: (panelId) => (panelsState.previousPanel && panelId === panelsState.previousPanel) || (panelsState.isTransitioning && panelId === panelsState.isTransitioning),
		open: (panelId) => panelsDispatch({ type: 'SET_ACTIVE_PANEL', payload: { panelId } }),
		close: () => panelsDispatch({ type: 'PANEL_IS_CLOSE' }),
		stopTransitioning: () => panelsDispatch({ type: 'PANEL_TRANSITION_END' }),
		getWrapperParent: () => useRefsAsWrappers ? parentContainerRef.current : containerRef.current.closest('[id="customize-theme-controls"]'),
		getParentOptionsWrapper: () => useRefsAsWrappers ? containerRef.current : containerRef.current.closest('.accordion-section-content'),
	}), [panelsState, panelsDispatch, useRefsAsWrappers, containerRef, parentContainerRef]);

	return (
		<PanelContext.Provider value={{ id, containerRef, panelsState, panelsDispatch, panelsHelpers, ...props }}>
			{children}
		</PanelContext.Provider>
	);
};
