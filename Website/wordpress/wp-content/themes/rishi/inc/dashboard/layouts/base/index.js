import { BrowserRouter as Router } from "react-router-dom"

export default ({ children }) => {
    return <Router><div className={`rishi-ad`}>{children}</div></Router>
}
