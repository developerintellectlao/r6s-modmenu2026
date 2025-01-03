export const useBuilderElements = ( type = 'header' ) => {
	return rishi.themeData.builder_data[ type ];
};

export const useBuilderOptions = ( type = 'header' ) => {
	return rishi.themeData.builder_data[ `${ type }_data` ].header_options;
}
