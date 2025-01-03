import {createRoot} from "@wordpress/element";
import Dashboard from "./Dashboard";
import "./scss/index.scss";

document.addEventListener('DOMContentLoaded', () => {
    const root = createRoot(document.getElementById('rishi-dashboard'));
    root.render(<Dashboard />);
})