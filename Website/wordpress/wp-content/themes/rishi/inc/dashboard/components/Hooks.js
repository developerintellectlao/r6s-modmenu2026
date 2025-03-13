import { useLocation } from "react-router-dom";
import { __ } from '@wordpress/i18n'

const useQuery = () => {
    let location = useLocation();
    return new URLSearchParams(location.search);
}
const  useCurrentTab = () => {
    let tab = useQuery().get('page_tab')
    if (!tab) {
        tab = 'general'
    }
    return tab
}

export { useQuery, useCurrentTab }