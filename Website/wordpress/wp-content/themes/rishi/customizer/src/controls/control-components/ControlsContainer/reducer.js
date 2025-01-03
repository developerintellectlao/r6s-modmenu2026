import {
    useReducer
} from '@wordpress/element';

export const reducer = (state, action) => {
    const { type, payload } = action;

    switch (type) {
        case 'SET_ACTIVE_PANEL':
            const { panelId } = payload;

            if (state.isTransitioning || state.isOpen === panelId) {
                return state;
            }

            return {
                ...state,
                isOpen: panelId,
                isTransitioning: panelId,
                previousPanel: state.isOpen || false,
            };

        case 'SET_PANEL_TITLE':
            return { ...state, titlePrefix: payload.titlePrefix };

        case 'SET_PANEL_META':
            return { ...state, ...payload };

        case 'SET_SECOND_LEVEL_PANEL':
            return { ...state, currentLevel: 2 };

        case 'PANEL_IS_CLOSE':
            return { ...state, isTransitioning: state.isOpen, isOpen: false };

        case 'PANEL_TRANSITION_END':
            return {
                ...state,
                isTransitioning: false,
                previousPanel: state.isOpen !== state.previousPanel ? false : state.previousPanel,
            };

        default:
            return state;
    }
};

export function useCustomizePanelReducer(initialState) {
    return useReducer(reducer, initialState);
}
