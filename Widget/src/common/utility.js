import { STATUS } from "./constants";

export const getColor = (status) => {
    switch (status) {
        case STATUS.BELOW_MINIMUM:
            return "#b4532f";
        case STATUS.BELOW_LTAS:
            return "#f59e0b";
        case STATUS.ABOVE_MAX:
            return "#1e40af";
        case STATUS.ABOVE_LTAS:
            return "#3b82f6";
        case STATUS.NORMAL:
            return "#047857";
        default:
            return "black";
    }
};


export const getColorForMapIcon = (status) => {
    switch (status) {
        case STATUS.BELOW_MINIMUM:
            return "#b4532f";
        case STATUS.BELOW_LTAS:
            return "#fcd34d";
        case STATUS.ABOVE_MAX:
            return "#1e40af";
        case STATUS.ABOVE_LTAS:
            return "#bfdbfe";
        case STATUS.NORMAL:
            return "#047857";
        default:
            return "#b6e6b2";
    }
};

export function toCapitalizedFirstLetter(str) {
    str = str.toLowerCase(); // Convert the entire string to lowercase
    return str.charAt(0).toUpperCase() + str.slice(1); // Capitalize the first letter
}


export const statusMapping = (status) => {

    switch (status) {
        case "NO_DATA":
            return "NO DATA";
        case "Below":
            return "Below LTAs";
        case "Above":
            return "Above LTAs";
        case "Min":
            return "Below Min";
        case "Max":
            return "Above Max";
        case "Normal":
            return "Normal";
    }
}