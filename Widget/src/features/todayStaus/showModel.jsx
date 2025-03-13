import Model from "@/components/Model";
import { Stack, Table, TableBody, TableCell, TableRow, Typography } from "@mui/material";

export default function ShowModel({ open, toggleModel, size, keyChk }) {
    // console.log("keyChk", keyChk)
    return (
        <Model sx={{overflow: "auto"}} open={open} handleClose={toggleModel} size={size}>

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
                        This gives an overview of the water level condition at 23 Mekong mainstream river monitoring stations. 12 of those stations have been selected for monitoring the water flow condition per the MRC’s procedure.
                    </Typography>
                    <Typography variant="body2" component="span" sx={{ mt: 0 }} fontSize="14px">
                        Click on the condition of each station to get more site-specific information.
                    </Typography>
                    <Typography variant="body2" component="span" sx={{ mt: 3 }} fontSize="14px">
                        For further information on the weekly situation, please click on the link below the map.
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
                    <Typography variant="body2" component="span" fontSize="14px">
                        During the November-May dry season season, the MRC’s Regional Flood and Drought Management Center issues daily and weekly situation reports.
                    </Typography>

                    <Typography variant="subtitle1" sx={{ fontWeight: "bold", mt: 2 }} fontSize="14px">
                        Water Level Definitions:
                    </Typography>
                    <Table>
                        <TableBody>
                            {[
                                { label: "Normal", description: "Water level lies between Long Term Average Level (LTA)", color: "green" },
                                { label: "Above LTA", description: "High water level", color: "blue" },
                                { label: "Above Max", description: "Extreme High water level", color: "darkblue" },
                                { label: "Below LTA", description: "Low water level", color: "orange" },
                                { label: "Below Min", description: "Critical water level", color: "red" },
                            ].map((row, index) => (
                                <TableRow key={index}>
                                    <TableCell sx={{ fontSize:"14px",color: row.color, fontWeight: "bold", width:"20%"  }}>{row.label}</TableCell>
                                    <TableCell fontSize="14px">{row.description}</TableCell>
                                </TableRow>
                            ))}
                        </TableBody>
                    </Table>

                    <Typography variant="subtitle1" sx={{ fontWeight: "bold", mt: 3 }} fontSize="14px">
                        Water Level Definitions:
                    </Typography>

                    <Table>
                        <TableBody>
                            {[
                                { label: "Normal", description: "When the daily observed flow is higher than the flow occurring on average once every five years, the hydrological condition is ‘normal’; there is no need for action."},
                                { label: "Stable", description: "When the daily observed flow lies between the flow occurring on average once every five years and ten years, the hydrological condition is ‘stable’. There is a need for caution."},
                                { label: "Unstable", description: "When the daily observed flow lies between the flow occurring on average once every ten years and twenty years, the hydrological condition is considered ‘unstable’. There is a need to be on alert. An investigation should be undertaken to identify the possible cause(s) and possible mitigation measures." },
                                { label: "Severe", description: "When the daily observed flow is lower than the flow occurring on average once every twenty years, the hydrological condition is considered ‘severe’. The implementation of mitigating measures should be considered." }
                            ].map((row, index) => (
                                <TableRow key={index}>
                                    <TableCell sx={{fontSize:"14px",fontWeight: "600", width:"20%", color:"#4B5563" }}>{row.label}</TableCell>
                                    <TableCell fontSize="14px"> {row.description}</TableCell>
                                </TableRow>
                            ))}
                        </TableBody>
                    </Table>
            </Stack>)}
        </Model>
    );
}
