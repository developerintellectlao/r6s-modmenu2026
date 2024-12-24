import { Box, Container } from "@mui/material";

const MainLayout = ({ children }) => {

    const containerCustomStyles = () => {
        let styleobj = {

            position: "relative",
            minHeight: "calc(100vh - 110px)",
            display: "flex",
            flexDirection: "column",
            paddingTop: "6px !important",
        };
        return styleobj;
    };

    return (
        <Box sx={{ display: "flex", width: "100%",p:0, m:0  }}>
            <Box
                component="main"
                sx={{
                    p:0, m:0 ,
                    // width: "calc(100% - 260px)",
                    flexGrow: 1,
                    // p: { xs: 1, sm: 1 },
                    
                }}
            >
                <Container
                    maxWidth="100%"
                    sx={{p:"0px !important", m:0}}
                    // maxWidth={"50%"}
                    // sx={containerCustomStyles}
                >
                    {children}
                </Container>
            </Box>
        </Box>
    );
};

export default MainLayout;
