const classBinder = (className) => {
    let explicitStyles = '';
    if (!className) {
        return;
    }
    if (className.length < 1) {
        return '';
    }
    className.map(cName => {
        explicitStyles = explicitStyles.concat(' ' + cName);
    });
    return explicitStyles;
}

export default classBinder;