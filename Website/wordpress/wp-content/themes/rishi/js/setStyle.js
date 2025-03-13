const setStyle = (element, style) => {
    for (const [key, value] of Object.entries(style)) {
        if (value === false) {
            element.style.removeProperty(key);
        } else {
            element.style.setProperty(key, value);
        }
    }
};

export default setStyle;