import Model from "@/components/Model";
import {
  Stack,
  Table,
  TableBody,
  TableCell,
  TableRow,
  Typography,
} from "@mui/material";

export default function ShowModel({ open, toggleModel, size, keyChk, floodSeason }) {
  const renderHowToRead = () => (
    <Stack>
      <Typography
        variant="h6"
        sx={{ textAlign: "center", fontWeight: 700, color: "#1f2937", mb: 2, fontSize: "16px" }}
      >
        How to read
      </Typography>
      <Typography variant="body2" fontSize="14px">
        This gives an overview of the water level condition at 23 Mekong mainstream river monitoring stations. 12 of those stations have been selected for monitoring the water flow condition per the MRC’s procedure.
      </Typography>
      <Typography variant="body2" fontSize="14px">
        Click on the condition of each station to get more site-specific information.
      </Typography>
      <Typography variant="body2" sx={{ mt: 3 }} fontSize="14px">
        For further information on the weekly situation, please click on the link below the map.
      </Typography>
    </Stack>
  );

  const renderHowToReadInWet = () => (
    <Stack>
      <Typography
        variant="h6"
        sx={{ textAlign: "center", fontWeight: 700, color: "#1f2937", mb: 2, fontSize: "16px" }}
      >
        How to read
      </Typography>
      <Typography variant="body2" fontSize="14px">
        This gives an overview of the water level condition at 22 Mekong mainstream river monitoring stations. 9 of those stations have been selected for monitoring the water flow condition per the MRC’s procedure.
      </Typography>
      <Typography variant="body2" fontSize="14px">
        Click on the condition of each station to get more site-specific information.
      </Typography>
      <Typography variant="body2" sx={{ mt: 3 }} fontSize="14px">
        For further information on the weekly situation, please click on the link below the map.
      </Typography>
    </Stack>
  );

  const renderDisclaimer = () => (
    <Stack>
      <Typography
        variant="h6"
        sx={{ textAlign: "center", fontWeight: 700, color: "#1f2937", mb: 2, fontSize: "16px" }}
      >
        Disclaimer
      </Typography>
      <Typography variant="body2" fontSize="14px">
        This information is supplied as a service to the governments of the MRC Member Countries so that it may be used as a tool within existing national disaster forecast and warning systems.
      </Typography>
    </Stack>
  );

  const renderDisclaimerInWet = () => (
    <Stack>
      <Typography
        variant="h6"
        sx={{ textAlign: "center", fontWeight: 700, color: "#1f2937", mb: 2, fontSize: "16px" }}
      >
        Disclaimer
      </Typography>
      <Typography variant="body2" fontSize="14px">
        This information is supplied as a service to the governments of the MRC Member Countries so that it may be used as a tool within existing national disaster forecast and warning systems.
      </Typography>

      {/* <Typography
        variant="h6"
        sx={{ textAlign: "center", fontWeight: 700, color: "#1f2937", mb: 2, fontSize: "16px" }}
      >
        Legend
      </Typography>
      <Typography variant="body2" fontSize="14px">
        During the June-October flood season, the MRC’s Regional Flood and Drought Management Center issues daily monitoring and forecasting products, and weekly situation reports.
      </Typography>

      <Typography variant="subtitle1" sx={{ fontWeight: "bold", mt: 2 }} fontSize="14px">
        Water Level Definitions:
      </Typography>
      <Table>
        <TableBody>
          {[
            { label: "Normal", description: "Normal water levels, which neither reach alarm nor flood levels.", color: "#99ccff" },
            { label: "Alarm", description: "Water level ranges between alarm and flood levels.", color: "#fbbf24" },
            { label: "Flood", description: "Water level exceeds flood level.", color: "#ff0000" },
          ].map((row, index) => (
            <TableRow key={index}>
              <TableCell sx={{ fontSize: "14px", color: row.color, fontWeight: "bold", width: "20%" }}>{row.label}</TableCell>
              <TableCell fontSize="14px">{row.description}</TableCell>
            </TableRow>
          ))}
        </TableBody>
      </Table>

      <Typography variant="subtitle1" sx={{ fontWeight: "bold", mt: 3 }} fontSize="14px">
        Flow Threshold Definition (PMFM Article 6C):
      </Typography>
      <Table>
        <TableBody>
          {[
            { label: "Normal", description: "If the daily observed water level lies below ARI 1:2, it means “normal hydrological conditions”. There is no need for action.", color: "green" },
            { label: "Stable", description: "If the daily observed water level lies between ARI 1:2 and ARI 1:10, it is greater than what naturally occur, but it still means that hydrological conditions remain “stable”. There is a need for caution.", color: "yellow" },
            { label: "Unstable", description: "If the daily observed water level lies between ARI 1:10 and ARI 1:20, it means that hydrological conditions are “unstable”. Investigation should be undertaken to identify the possible cause(s) and possible mitigation measures. There is a need to be on alert.", color: "#fbbf24" },
            { label: "Severe", description: "If the daily observed water level lies above ARI 1:20, it means that hydrological conditions are “severe”. Implementation of mitigating measures should be considered. ", color: "#ff0000" },
          ].map((row, index) => (
            <TableRow key={index}>
              <TableCell sx={{ fontSize: "14px", color: row.color, fontWeight: "bold", width: "20%" }}>{row.label}</TableCell>
              <TableCell fontSize="14px">{row.description}</TableCell>
            </TableRow>
          ))}
        </TableBody>
      </Table> */}
    </Stack>
  );

  const renderLegendFlood = () => (
    <Stack>
      <Typography
        variant="h6"
        sx={{ textAlign: "center", fontWeight: 700, color: "#1f2937", mb: 2, fontSize: "16px" }}
      >
        Legend
      </Typography>
      <Typography variant="body2" fontSize="14px">
        During the June–October flood season, the MRC’s Regional Flood and Drought Management Center issues daily flood forecasts and warnings.
      </Typography>

      <Typography variant="subtitle1" sx={{ fontWeight: "bold", mt: 2 }} fontSize="14px">
        Water Level Definitions:
      </Typography>
      <Table>
        <TableBody>
          {[
            { label: "Normal", description: "Normal water level.", color: "green" },
            { label: "Alarm", description: "Alarm stage is when the water level ranges between alarm and flood levels", color: "#fbbf24" },
            { label: "Flood", description: "Flood stage is when the flood level is exceeded; the flood level is determined by the member countries.", color: "#ff0000" },
          ].map((row, index) => (
            <TableRow key={index}>
              <TableCell sx={{ fontSize: "14px", color: row.color, fontWeight: "bold", width: "20%" }}>{row.label}</TableCell>
              <TableCell fontSize="14px">{row.description}</TableCell>
            </TableRow>
          ))}
        </TableBody>
      </Table>

      <Typography variant="subtitle1" sx={{ fontWeight: "bold", mt: 3 }} fontSize="14px">
        Flow Threshold Definitions:
      </Typography>
      {renderFlowDefinitions()}
    </Stack>
  );

  const renderLegendDry = () => (
    <Stack>
      <Typography
        variant="h6"
        sx={{ textAlign: "center", fontWeight: 700, color: "#1f2937", mb: 2, fontSize: "16px" }}
      >
        Legend
      </Typography>
      <Typography variant="body2" fontSize="14px">
        During the November–May dry season, the MRC’s Regional Flood and Drought Management Center issues daily and weekly situation reports.
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
              <TableCell sx={{ fontSize: "14px", color: row.color, fontWeight: "bold", width: "20%" }}>{row.label}</TableCell>
              <TableCell fontSize="14px">{row.description}</TableCell>
            </TableRow>
          ))}
        </TableBody>
      </Table>

      <Typography variant="subtitle1" sx={{ fontWeight: "bold", mt: 3 }} fontSize="14px">
        Flow Threshold Definitions:
      </Typography>
      {renderFlowDefinitions()}
    </Stack>
  );

  const renderFlowDefinitions = () => (
    <Table>
      <TableBody>
        {[
          {
            label: "Normal",
            description: "Daily observed flow is higher than the flow occurring on average once every five years — no action needed.",
          },
          {
            label: "Stable",
            description: "Flow between once-in-5-years and once-in-10-years — caution advised.",
          },
          {
            label: "Unstable",
            description: "Flow between once-in-10-years and once-in-20-years — alert status, investigate causes.",
          },
          {
            label: "Severe",
            description: "Flow below once-in-20-years — implement mitigation measures.",
          },
        ].map((row, index) => (
          <TableRow key={index}>
            <TableCell sx={{ fontSize: "14px", fontWeight: "600", width: "20%", color: "#4B5563" }}>{row.label}</TableCell>
            <TableCell fontSize="14px">{row.description}</TableCell>
          </TableRow>
        ))}
      </TableBody>
    </Table>
  );

  // Main return
  return (
    <Model sx={{ overflow: "auto" }} open={open} handleClose={toggleModel} size={size}>
      {keyChk === "howToRead"  && (floodSeason ? renderHowToReadInWet() :renderHowToRead())}
      {keyChk === "disclaimer" && (floodSeason ?  renderDisclaimerInWet() : renderDisclaimer())}
      {keyChk === "legend" && (floodSeason ? renderLegendFlood() : renderLegendDry())}
    </Model>
  );
}
