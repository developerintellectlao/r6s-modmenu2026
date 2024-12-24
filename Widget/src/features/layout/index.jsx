"use client"

import MainLayout from "@/features/layout/MainLayout";

const Layout = ({ children }) => {

    return (
        <>
            <MainLayout>
                {children}
            </MainLayout>
        </>
    )

}

export default Layout;