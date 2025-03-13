import Model from "@/components/Model";
import { Stack, Table, TableBody, TableCell, TableRow, Typography } from "@mui/material";

export default function ShowModel({ open, toggleModel, size, keyChk }) {
    // console.log("keyChk", keyChk)
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
                    This evaluates the threat of flash flooding within the Lower Mekong Basin (LMB) for the next one to six hours. Districts under flash flood risk are marked in colour on the map. By moving the mouse over the locations, risk level, district and province are indicated. Forecasting time can be changed by clicking on the 1h, 3h and 6h button.
                    </Typography>
                    <Typography variant="body2" component="span" sx={{ mt: 0 }} fontSize="14px">
                    For further information on the Flash Flood Guidance System click on the Link below the map.
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
                            fontSize:"16px",
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

                    <Typography variant="subtitle1  " component="span" sx={{mb: 2}} fontSize="14px">
                        Flash Flood Guidance - Unit: mm/time
                    </Typography>

                    {/* Content */}
                    <Typography variant="body2" component="span" fontSize="14px">
                        Amount of rainfall in mm for a given duration (1,3 and 6 hours) over a given catchment (each sub-basin) that is just enough to cause bank-full condition at the outlet of the draining stream.
                    </Typography>

                    <Typography variant="subtitle1" sx={{ fontWeight: "bold", mt: 2 }} fontSize="14px">
                        Flash Flood Guidance risk levels:
                    </Typography>
                    <Table>
                        <TableBody>
                            {[
                                { label: "Low Risk", description1: "W40.01 < FFG 1h < 60.00 mm ", description2: "40.01 < FFG 3h < 70.00 mm", description3: "40.01 < FFG 6h < 100.00 mm", color: "green" },
                                { label: "Moderate Risk", description: "25.01 < FFG 1h < 40.00 mm", description2: "25.01 < FFG 3h < 40.00 mm", description3: "30.01 < FFG 6h < 60.00 mm", color: "blue" },
                                { label: "High Risk", description: "10.01 < FFG 1h < 25.00 mm", description2: "10.01 < FFG 3h < 25.00 mm", description3: "15.01 < FFG 6h < 30.00 mm", color: "darkblue" },
                                { label: "Extreme Risk", description: "0.01 < FFG 1h < 10.00 mm", description2: "0.01 < FFG 3h < 10.00 mm", description3: "0.01 < FFG 6h < 15.00 mm", color: "orange" },
                            ].map((row, index) => (
                                <TableRow key={index}>
                                    <TableCell sx={{fontSize:"14px", color: row.color, fontWeight: "bold", width: "20%" }}>{row.label}</TableCell>
                                    <TableCell >
                                        <Typography fontSize="14px">
                                        {row.description1}
                                    </Typography>
                                    <Typography fontSize="14px">
                                        {row.description2}
                                    </Typography>
                                    <Typography fontSize="14px" >
                                        {row.description3}
                                    </Typography>
                                    </TableCell>
                                </TableRow>
                            ))}
                        </TableBody>
                    </Table>


                </Stack>)}
        </Model>
    );
}
