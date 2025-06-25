import React from "react";
import {
  Table,
  TableBody,
  TableCell,
  TableContainer,
  TableHead,
  TableRow,
  Paper,
  Typography,
  Grid2,
  Stack,
  Tooltip,
  Box,
} from "@mui/material";
import Image from "next/image";
import ThailandFlag from "@/assets/images/icons/ThailandFlag.svg";
import CambodiaFlag from "@/assets/images/icons/CambodiaFlag.svg";
import LaoFlag from "@/assets/images/icons/LaoFlag.svg";
import VietFlag from "@/assets/images/icons/VietFlag.svg";
import { getColor } from "@/common/utility";
import { STATUS } from "@/common/constants";
import { Icon } from "@iconify/react";
import Link from "next/link";

const MonitoringTable = ({ data, selectedRow, handelClick }) => {

  const chooseImage = (country) => {
    switch (country) {
      case "Cambodia":
        return CambodiaFlag;
        break;
      case "Lao":
        return LaoFlag;
        break;
      case "Thailand":
        return ThailandFlag;
        break;
      case "Viet Nam":
        return VietFlag;
        break;

    }
  }

  const chooseImageForTable = (value) => {
    switch (value) {
      case "0":
        return <Stack ><Icon icon="gravity-ui:arrow-down" width="18" height="18" color="#146850" style={{ marginLeft: "14px" }} /></Stack>;
        break;
      case "1":
        return <Icon icon="gravity-ui:arrow-right" width="18" height="18" color="#146850" style={{ marginLeft: "14px" }} />;
        break;
      case "2":
        return <Icon icon="gravity-ui:arrow-up" width="18" height="18" color="#146850" style={{ marginLeft: "14px" }} />;
        break;
      case "3":
        return (
          <Stack direction={"row"} display={"flex"} alignItems={"center"} >
            <svg className="animate-ping  -ml-0.5 mr-1.5 h-2 w-2  text-amber-500" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3"></circle></svg>
            <Typography sx={{ fontSize: "0.75rem", color: "#d9772e" }}>Alarm</Typography>
            <Icon icon="gravity-ui:arrow-down" width="18" height="18" color="#d9772e" />
          </Stack>
        );
        break;
      case "4":
        return <Stack direction={"row"} display={"flex"} alignItems={"center"} >
          <svg className="animate-ping  -ml-0.5 mr-1.5 h-2 w-2  text-amber-500" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3"></circle></svg>
          <Typography sx={{ fontSize: "0.75rem", color: "#d9772e" }}>Alarm</Typography>
        </Stack>
        break
      case "5":
        return (
          <Stack direction={"row"} display={"flex"} alignItems={"center"} >
            <svg className="animate-ping  -ml-0.5 mr-1.5 h-2 w-2  text-amber-500" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3"></circle></svg>
            <Typography sx={{ fontSize: "0.75rem", color: "#d9772e" }}>Alarm</Typography>
            <Icon icon="gravity-ui:arrow-up" width="18" height="18" color="#d9772e" />
          </Stack>
        );
        break;
      case "6":
        return <Stack direction={"row"} display={"flex"} alignItems={"center"} >
          <svg className="animate-ping  -ml-0.5 mr-1.5 h-2 w-2  text-red-400" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3"></circle></svg>
          <Typography sx={{ fontSize: "0.75rem", color: "#ff0000" }}>Flood</Typography>
          <Icon icon="gravity-ui:arrow-down" width="18" height="18" color="#ff0000" />
        </Stack>
        break;
      case "7":
        return <Stack direction={"row"} display={"flex"} alignItems={"center"} >
          <svg className="animate-ping  -ml-0.5 mr-1.5 h-2 w-2  text-red-400" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3"></circle></svg>
          <Typography sx={{ fontSize: "0.75rem", color: "#ff0000" }}>Flood</Typography>
        </Stack>
        break;
      case "8":
        return <Stack direction={"row"} display={"flex"} alignItems={"center"} >
          <svg className="animate-ping  -ml-0.5 mr-1.5 h-2 w-2  text-red-400" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3"></circle></svg>
          <Typography sx={{ fontSize: "0.75rem", color: "#ff0000" }}>Flood</Typography>
          <Icon icon="gravity-ui:arrow-up" width="18" height="18" color="#ff0000" />
        </Stack>
        break;
      case "9":
        return <Icon icon="gridicons:cross" width="18" height="18" color="#146850" style={{ marginLeft: "14px" }} />;
        break;
      case "10":
        return <Icon icon="ic:baseline-star" width="18" height="18" color="#146850" style={{ marginLeft: "14px" }} />;
        break;
    }
  }

  return (
    <TableContainer >
      <Table>
        <TableBody sx={{ width: "100%" }}>
          {data.map((row, index) => (
            <TableRow
              key={index}
              onClick={() => handelClick(row)}
              style={{
                background: selectedRow?.station === row.station ? "#f5f5f4" : "",
                borderBottom: "1px solid #e0e0e0",
              }}
            >
              <TableCell
                sx={{
                  py: "2px",
                  display: "flex",
                  alignItems: "center",
                  width: "100%",
                  whiteSpace: "nowrap",
                  overflow: "hidden",
                  textOverflow: "ellipsis",
                  borderBottom: "none",
                }}
              >
                <Image
                  src={chooseImage(row?.country)}
                  alt="flag"
                  priority={true}
                />
                <Typography sx={{ ml: "3px", fontSize: ".875rem" }}>
                  {row.station && row.B_name ? `${row.station} ${row.B_name}` : row.station || "-"}
                </Typography>
                {row?.tooltip && (
                  <Tooltip
                    placement="top"
                    title="Tan Chau and Chau Doc water levels - subject to daily tidal variations. Data displayed reflects 7am observations."
                    arrow
                    slotProps={{
                      tooltip: {
                        sx: {
                          fontSize: '12px',
                          fontWeight: "600",
                          backgroundColor: '#007bff',
                          color: 'white',
                          maxWidth: '250px',
                          borderRadius: '4px',
                          padding: '8px',
                        },
                      },
                      arrow: {
                        sx: { color: '#007bff' },
                      },
                    }}
                  >
                    <Icon icon="eva:question-mark-circle-outline" width="16" height="16" />
                  </Tooltip>
                )}
              </TableCell>

              <TableCell sx={{ width: "74%", borderBottom: "none", padding: 0 }}>
                <Grid2
                  container
                  direction="row"
                  alignItems="start"
                  justifyContent="center" 
                >
                  {[0, 1, 2, 3, 4].map((index) => (
                    <Box
                      key={index}
                      sx={{
                        py: "2px",
                        p: "0px",
                        minWidth: "10%",
                        width: "20%",
                        fontSize: ".875rem",
                        textAlign: "start",
                        display: "flex", // Make sure Box is a flex container
                        alignItems: "center", // Vertically center image if needed
                      }}
                    >
                      {row.riverFloodForecastData.length > index
                        ?
                        <Link href={`https://ffw.mrcmekong.org/stations.php?StCode=${row.code}&StName=${row.station}`} target="_blank">
                          {chooseImageForTable(row.riverFloodForecastData[index])}
                        </Link>
                        : "-"}
                    </Box>
                  ))}
                </Grid2>
              </TableCell>
            </TableRow>

          ))}
        </TableBody>
      </Table>
    </TableContainer>
  );
};

export default MonitoringTable; 