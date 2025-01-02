import styled from "@emotion/styled"

const Nav = styled.div`
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
    margin: 0 0 24px;
    button.rishi-nav-tab{
        padding: 12px 16px;
        background: #eff5fb;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-weight: 500;
        transition: all .2s ease;
        &[data-active="true"]{
            background-color: #307ac9;
            color: #ffffff;
        }
    }
`

const TabNav = ({ active, onActive, tabs }) => {
    return (
        <Nav>
            {tabs.map(({ id, label }) => {
                return <button
                    type="button"
                    className="rishi-nav-tab"
                    data-active={active === id}
                    onClick={() => onActive(id)}
                    key={id}
                >
                    {label}
                </button>
            })}
        </Nav>
    )
}

export default TabNav