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
} from "@mui/material";
import Image from "next/image";
import ThailandFlag from "@/assets/images/icons/ThailandFlag.svg";
import CambodiaFlag from "@/assets/images/icons/CambodiaFlag.svg";
import LaoFlag from "@/assets/images/icons/LaoFlag.svg";
import VietFlag from "@/assets/images/icons/VietFlag.svg";
import { getColor } from "@/common/utility";
import { STATUS } from "@/common/constants";
import { Icon } from "@iconify/react";

const MonitoringTable = ({ data ,selectedRow, handelClick}) => {

  const chooseImage = (country) => {
    switch(country) {
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

  return (
    <TableContainer >
      <Table>
        <TableBody sx={{ width: "100%" }}>
          {data.map((row, index) => (
            <TableRow key={index} onClick={() => handelClick(row)}
              style={{
                background: selectedRow?.station === row.station ? "#f5f5f4" : "",
              }}
            >
              <TableCell 
                sx={{ py: "2px", display: "flex", alignItems: "center", width: "100%", whiteSpace: "nowrap",
                  overflow: "hidden",
                  textOverflow: "ellipsis" }}>
                <Image
                  src={chooseImage(row?.country)}
                  alt="flag"
                  priority={true}

                />
                <Typography sx={{ ml: "3px", fontSize:".875rem" }}>
                {row.station && row.B_name ? `${row.station} ${row.B_name}`  : row.station ? row.station : "-"}
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
                          fontWeight:"600",
                          backgroundColor: '#007bff',
                          color: 'white',
                          maxWidth: '250px',
                          borderRadius: '4px',
                          padding: '8px',
                        },
                      },
                      arrow: {
                        sx: {
                          color: '#007bff', // Matches the tooltip background color
                        },
                      },
                    }}
                    >
                  <Icon icon="eva:question-mark-circle-outline" width="16" height="16" />
                </Tooltip>
                )}
              </TableCell>
              <TableCell
                sx={{ py: "2px", width: "30%", whiteSpace: "nowrap",
                  overflow: "hidden",
                  textOverflow: "ellipsis" }}
              >
                  <Stack direction={"row"} display={"flex"} alignItems={"center"}>
                    {row.Weekly && (
                    <Grid2 
                      // sx={{ marginRight: "5px" }}
                      style={{
                        marginRight: row?.Weekly === STATUS.ABOVE_LTAS ? "12px" : "",
                      }}
                    >
                      {row?.Weekly === STATUS.BELOW_LTAS && (
                      <svg className="animate-ping  -ml-0.5 mr-1.5 h-2 w-2  text-amber-500" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3"></circle></svg>)}
                    </Grid2>)}
                    <Typography
                      style={{
                        color: getColor(row?.Weekly)
                      }}
                      sx={{fontSize:".875rem"}}
                    >
                      {row.Weekly ? row.Weekly : "-"}
                    </Typography>
                  </Stack>
              </TableCell>
              <TableCell sx={{ py: "2px", width: "30%", fontSize:".875rem" }}>{row.Trend ? row.Trend : "-"}</TableCell>
            </TableRow>
          ))}
        </TableBody>
      </Table>
    </TableContainer>
  );
};

export default MonitoringTable;