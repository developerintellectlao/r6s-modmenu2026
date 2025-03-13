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
                            fontSize:"16px",
                            mb: 2,
                        }}
                    >
                        How to read
                    </Typography>

                    {/* Content */}
                    <Typography variant="body2" component="span" fontSize="14px">
                        Daily rainfall data are provided by MRC Member Countries. This gives an overview of the daily accumulated rainfall distribution within the Lower Mekong Basin (LMB), presenting the data from 144 hydrometeorological stations classified in different rainfall level.
                    </Typography>

                    <Typography variant="body2" component="span" sx={{ mt: 3 }} fontSize="14px">
                        The data are plotted to visualize daily accumulated rainfall from 7h00 AM with classification ranging from no rain to very heavy rain symbolizing through unique colours.
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

                    <Typography variant="subtitle1" sx={{ fontWeight: "bold", mt: 2 }}>
                        Rainfall levels:
                    </Typography>
                    <Table>
                        <TableBody>
                            {[
                                { label: "Very Heavy rain", description: "90+ mm of rainfall within 24 hours.", color: "#e74c3c" },
                                {
                                    label: "Heavy rain", description: `36-90 mm of rainfall within 24 hours.`, color: "#e57e22" },
                                { label: "Moderate rain", description: "11-35 mm of rainfall within 24 hours.", color: "#f1c71d" },
                                { label: "Light rain", description: "1-10 mm of rainfall within 24 hours.", color: "#53a7e0" },
                                { label: "No rain", description: "0 mm of rainfall within 24 hours.", color: "#b4bfc4" },
                                { label: "No data", description: "No data recorded within the last 24 hours.", color: "#b4bfc4" },

                            ].map((row, index) => (
                                <TableRow key={index}>
                                    <TableCell sx={{ color: row.color,fontSize:"14px", fontWeight: "bold", width: "20%" }}>{row.label}</TableCell>
                                    <TableCell fontSize="14px">{row.description}</TableCell>
                                </TableRow>
                            ))}
                        </TableBody>
                    </Table>

                    
                </Stack>)}
        </Model>
    );
}
