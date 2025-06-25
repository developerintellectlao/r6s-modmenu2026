import Model from "@/components/Model";
import { Icon } from "@iconify/react";
import { Stack, Table, TableBody, TableCell, TableRow, Typography } from "@mui/material";

export default function ShowModel({ open, toggleModel, size, keyChk }) {
    
    return (
        <Model open={open} handleClose={toggleModel} size={size}>

            {keyChk === "howToRead" && (
                <Stack >
                    {/* Title */}
                    <Typography
                        variant="h6"
                        component="div"
                        sx={{
                            textAlign: "center",
                            fontWeight: "700",
                            color: "#1f2937",
                            mb: 2,
                            fontSize:"16px"
                        }}
                    >
                        How to read
                    </Typography>

                    {/* Content */}
                    <Typography variant="body2" component="span" fontSize="14px">
                        This informs about the forecasted change of the water level during the next five days at 22 forecasting stations on the Mekong River mainstream. The forecast is issued daily during the wet season (June to October) and includes warnings for alarm situation and flood stage. Click on each station or arrow to get side specific information.
                    </Typography>
                    <Typography variant="body2" component="span" sx={{ mt: 2 }} fontSize="14px">
                        The whole Forecasting Bulletin can be assessed by clicking on the Link below the map.
                    </Typography>
                </Stack>)}

            {keyChk === "disclaimer" && (
                <Stack >
                    {/* Title */}
                    <Typography
                        variant="h6"
                        component="div"
                        sx={{
                            textAlign: "center",
                            fontWeight: "700",
                            color: "#1f2937",
                            mb: 2,
                            fontSize:"16px"
                        }}
                    >
                        Disclaimer
                    </Typography>

                    {/* Content */}
                    <Typography variant="body2" component="span" fontSize="14px">
                        This information is supplied as a service to the governments of the MRC Member Countries so that it may be used as a tool within existing national disaster forecast and warning systems.
                    </Typography>
                </Stack>)}

            {keyChk === "legend" && (
                <Stack >
                    {/* Title */}
                    <Typography
                        variant="h6"
                        component="div"
                        sx={{
                            textAlign: "center",
                            fontWeight: "700",
                            color: "#1f2937",
                            mb: 2,
                            fontSize:"16px"
                        }}
                    >
                        Legend
                    </Typography>

                    {/* Content */}
                    <Typography variant="subtitle1" sx={{ fontWeight: "bold", mt: 2 }} fontSize="14px">
                        Definitions:
                    </Typography>
                    <Table>
                        <TableBody>
                            {[
                                { label: <Icon icon="gravity-ui:arrow-up" width="18" height="18" color="#4d5765"  />, description: "Rising water level", color: "green" },
                                { label: <Icon icon="gravity-ui:arrow-right" width="18" height="18" color="#4d5765"  />, description: "Stable water level: Stable water level is defined as a daily change of less than 10cm from Chiang Saen to Savannakhet; less than 5cm at Pakse and Stung Treng; and no more than 3cm cm from Kratie downstream.", color: "blue" },
                                { label: <Icon icon="gravity-ui:arrow-down" width="18" height="18" color="#4d5765" />, description: "Falling water level", color: "darkblue" },
                                { label: <Icon icon="gridicons:cross" width="18" height="18" color="#4d5765"/>, description: "No data available", color: "orange" },
                                { label: "Alarm", description: "Alarm stage is when the water level ranges between alarm and flood levels", color: "#d9772e" },
                                { label: "Flood", description: "Flood stage is when the flood level is exceeded; the flood level is determined by the member countries.", color: "#ff0000" },
                            ].map((row, index) => (
                                <TableRow key={index}>
                                    <TableCell sx={{ fontSize:"14px",color: row.color, fontWeight: "bold", width:"20%"  }}>{row.label}</TableCell>
                                    <TableCell fontSize="14px">{row.description}</TableCell>
                                </TableRow>
                            ))}
                        </TableBody>
                    </Table>
            </Stack>)}
        </Model>
    );
}
