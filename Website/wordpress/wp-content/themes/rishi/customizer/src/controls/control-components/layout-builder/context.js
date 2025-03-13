import { createContext, useContext } from '@wordpress/element';

export const builderContext = createContext({});
export const useBuilderContext = () => useContext(builderContext);

export default function BuilderContext({ value, children }) {
	return (
		<builderContext.Provider value={value}>{children}</builderContext.Provider>
	);
}
