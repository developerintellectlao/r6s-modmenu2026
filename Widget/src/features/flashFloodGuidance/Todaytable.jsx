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
import { getColorForFFD } from "@/common/utility";

const MonitoringTable = ({ data }) => {

  const chooseImage = (country) => {
    switch (country) {
      case "KH":
        return CambodiaFlag;
      case "LA":
        return LaoFlag;
      case "VN":
        return VietFlag;
      case "TH":
        return ThailandFlag;
    }
  }
 
  return (
    <TableContainer component={Paper}>
      <Table>
        <TableBody sx={{ width: "100%" }}>
          {data.map((row, index) => (
            <TableRow key={index}>
              <TableCell sx={{ py: "4px", display: "flex", alignItems: "center", width: "100%" }}>
                <Image
                  src={chooseImage(row?.country)}
                  alt="flag"
                  priority={true}

                />
                <Typography sx={{ ml: "3px", fontSize:".875rem"}}>
                  {row.district ? row.district : "-"}
                </Typography>
              </TableCell>
              <TableCell sx={{ py: "2px", width: "23%" }}>
                <Stack direction={"row"} display={"flex"} alignItems={"center"} >
                  <Typography
                    style={{
                      color: getColorForFFD(row.hr1Risk)
                    }}
                    sx={{fontSize:".875rem"}}
                  >
                    {row.hr1Risk ? row.hr1Risk : "-"}
                  </Typography>
                </Stack>


              </TableCell>
              <TableCell sx={{ py: "4px", width: "22%", fontSize:".875rem", color: getColorForFFD(row.hr3Risk) }}>{row.hr3Risk ? row.hr3Risk : "-"}</TableCell>
              <TableCell sx={{ py: "4px", width: "25%", fontSize:".875rem", color: getColorForFFD(row.hr6Risk)}}>{row.hr6Risk ? row.hr6Risk : "-"}</TableCell>

            </TableRow>
          ))}
        </TableBody>
      </Table>
    </TableContainer>
  );
};

export default MonitoringTable;
