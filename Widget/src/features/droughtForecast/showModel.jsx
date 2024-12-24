import Model from "@/components/model";
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
                    This gives an overview of the drought condition being forecasted on a weekly basis for the whole Lower Mekong Basin (LMB). It displays the likeliness of drought with different classification from no drought to exceptional drought in 5 km2 grid scale. By moving the mouse over the locations, risk level and province are indicated.
                    </Typography>
                    <Typography variant="body2" component="span" sx={{ mt: 2 }} fontSize="14px">
                    For further drought forecasting information, please click on the Link below the map.
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
                            fontSize:"16px",
                        }}
                    >
                        Legend
                    </Typography>

                    {/* Content */}
                    <Typography variant="body2" component="span" fontSize="14px">
                    Combined Drought Index (CDI): The CDI is the main index representing a global state of the meteorological, hydrological and agricultural indices.
                    </Typography>
                    
                    <Typography variant="body2" component="span" sx={{mt:2}} fontSize="14px">
                    CDI Classification: CDI is a dimensionless index varying between -3 to 3 and ranging in the following stretch of conditions.
                    </Typography>

                    <Typography variant="subtitle1" sx={{ fontWeight: "bold", mt: 2, fontSize:"14px" }}>
                    Combined Drought Index (CDI) Classification:
                    </Typography>
                    <Table>
                        <TableBody>
                            {[
                                { label: `No drought(no color)(CDI ≥ -1)`, description: "The area has only one moderate meteorological or agricultural drought.", color: "#4b5577" },
                                { label: `Moderate Drought(1.5 ≤ CDI < -1)`, description: "The area has moderate or severe meteorological and/or agricultural droughts.", color: "#fde047" },
                                { label: `Severe Drought(-2 ≤ CDI < -1.5)`, description: "The area has extreme or severe meteorological and agricultural droughts.", color: "#fdcbdb" },
                                { label: `Extreme Drought(-2.5 ≤ CDI < -2)`, description: "The area has both extreme or severe droughts of meteorological and agricultural droughts.", color: "#fe0029" },
                                { label: `Exceptional Drought(CDI < -2.5)`, description: "The area has both extreme drought of meteorological and agricultural droughts.", color: "#742600" }
                            ].map((row, index) => (
                                <TableRow key={index}>
                                    <TableCell sx={{fontSize:"14px", color: row.color, fontWeight: "bold", width:"20%"  }}>{row.label}</TableCell>
                                    <TableCell fontSize="14px">{row.description}</TableCell>
                                </TableRow>
                            ))}
                        </TableBody>
                    </Table>

                    
            </Stack>)}
        </Model>
    );
}
