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
} from "@mui/material";
import Image from "next/image";
import ThailandFlag from "@/assets/images/icons/ThailandFlag.svg";
import CambodiaFlag from "@/assets/images/icons/CambodiaFlag.svg";
import LaoFlag from "@/assets/images/icons/LaoFlag.svg";
import VietFlag from "@/assets/images/icons/VietFlag.svg";
import { getColor, toCapitalizedFirstLetter } from "@/common/utility";
import { STATUS } from "@/common/constants";

const MonitoringTable = ({ data, handelClick, selectedRow }) => {

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

  return (
    <TableContainer component={Paper}>
      <Table>
        <TableBody sx={{ width: "100%" }}>
          {data.map((row, index) => (
            <TableRow key={index} onClick={() => handelClick(row)}
              style={{
                background: selectedRow?.station === row.station ? "#f5f5f4" : "",
              }}
            >
              <TableCell sx={{ py: "4px", display: "flex", alignItems: "center", width: "100%" }}>
                <Image
                  src={chooseImage(row?.country)}
                  alt="flag"
                  priority={true}
                />
                <Typography sx={{ ml: "3px" }}>
                  {row.station ? row.station : "-"}
                </Typography>
              </TableCell>
              <TableCell
                sx={{ py: "2px", width: "30%" }}
              >
                <Stack direction={"row"} display={"flex"} alignItems={"center"} >
                  {row.Today && (
                    <Grid2
                      // sx={{ marginRight:  }}
                      style={{
                        marginRight: row.Today === STATUS.ABOVE_LTAS ? "12px" : "",
                      }}
                    >
                      {row.Today === STATUS.BELOW_LTAS && (
                      <svg className="animate-ping  -ml-0.5 mr-1.5 h-2 w-2  text-amber-500" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3"></circle></svg>)}
                    </Grid2>)}
                  <Typography
                    style={{
                      color: getColor(row.Today)
                    }}
                  >
                    {row.Today ? row.Today : "-"}
                  </Typography>
                </Stack>


              </TableCell>
              <TableCell sx={{ py: "4px", width: "30%" }}>{row.FlowThreshold ? toCapitalizedFirstLetter(row.FlowThreshold) : "-"}</TableCell>
            </TableRow>
          ))}
        </TableBody>
      </Table>
    </TableContainer>
  );
};

export default MonitoringTable;
