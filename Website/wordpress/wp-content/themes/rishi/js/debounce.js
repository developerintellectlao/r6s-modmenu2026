const debounce = (func, delay) => {
    // Declare a variable called 'timeout' to store the timer ID
    let timeout;
    // Return an anonymous function that takes in any number of arguments
    return function (...args) {
        const later = () => {
            func(...args);
        };
        // Clear the previous timer to prevent the execution of 'func'
        clearTimeout(timeout);
        // Set a new timer that will execute 'func' after the specified delay
        timeout = setTimeout(later, delay);
    };
};

export default debounce;
