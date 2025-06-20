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

  const chooseImageForTable = (value) => {
    switch(value) {
      case "0":
        return <Icon icon="gravity-ui:arrow-down" width="18" height="18" color="#146850" style={{marginLeft:"14px"}} />;
        break;
      case "1":
        return <Icon icon="gravity-ui:arrow-right" width="18" height="18" color="#146850" style={{marginLeft:"14px"}} />;
        break;
      case "2":
        return <Icon icon="gravity-ui:arrow-up" width="18" height="18" color="#146850" style={{marginLeft:"14px"}}   />;
        break;
     case "3":
        return (
          <Stack direction={"row"} display={"flex"} alignItems={"center"} >
            <svg className="animate-ping  -ml-0.5 mr-1.5 h-2 w-2  text-amber-500" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3"></circle></svg>
            <Typography sx={{fontSize:"0.75rem", color:"#d9772e"}}>Alarm</Typography>
            <Icon icon="gravity-ui:arrow-down" width="18" height="18" color="#d9772e" />
          </Stack>
        );
        break;
      case "4":
        return <Icon icon="gravity-ui:arrow-right" width="18" height="18" color="#146850" style={{marginLeft:"14px"}} />;
        break
      case "5":
        return (
          <Stack direction={"row"} display={"flex"} alignItems={"center"} >
             <svg className="animate-ping  -ml-0.5 mr-1.5 h-2 w-2  text-amber-500" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3"></circle></svg>
            <Typography sx={{fontSize:"0.75rem", color:"#d9772e"}}>Alarm</Typography>
            <Icon icon="gravity-ui:arrow-up" width="18" height="18" color="#d9772e" />
          </Stack>
        );
        break;
      case "6":
        return <Stack direction={"row"} display={"flex"} alignItems={"center"} >
          <svg className="animate-ping  -ml-0.5 mr-1.5 h-2 w-2  text-red-400" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3"></circle></svg>
            <Typography sx={{fontSize:"0.75rem", color:"#ff0000"}}>Flood</Typography>
            <Icon icon="gravity-ui:arrow-down" width="18" height="18" color="#ff0000" />
          </Stack>
        break;
      case "7":
        return <Icon icon="gravity-ui:arrow-right" width="18" height="18" color="#146850"  style={{marginLeft:"14px"}}/>;
        break;
      case "8":
        return <Stack direction={"row"} display={"flex"} alignItems={"center"} >
           <svg className="animate-ping  -ml-0.5 mr-1.5 h-2 w-2  text-red-400" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3"></circle></svg>
            <Typography sx={{fontSize:"0.75rem", color:"#ff0000"}}>Flood</Typography>
            <Icon icon="gravity-ui:arrow-up" width="18" height="18" color="#ff0000" />
          </Stack>
        break;
      case "9":
        return <Icon icon="gridicons:cross" width="18" height="18" color="#146850" style={{marginLeft:"14px"}}/>;
        break;
      case "10":
        return <Icon icon="ic:baseline-star" width="18" height="18" color="#146850" style={{marginLeft:"14px"}}/>;
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
             
              <TableCell sx={{ py: "2px", width: "13%", fontSize:".875rem"}}>{row.riverFloodForecastData.length > 0 ? chooseImageForTable(row.riverFloodForecastData[0]) : "-"}</TableCell>
              <TableCell sx={{ py: "2px", width: "12%", fontSize:".875rem" }}>{row.riverFloodForecastData.length > 0 ? chooseImageForTable(row.riverFloodForecastData[1]) : "-"}</TableCell>
              <TableCell sx={{ py: "2px", width: "12%", fontSize:".875rem" }}>{row.riverFloodForecastData.length > 0 ? chooseImageForTable(row.riverFloodForecastData[2]) : "-"}</TableCell>
              <TableCell sx={{ py: "2px", width: "13%", fontSize:".875rem" }}>{row.riverFloodForecastData.length > 0 ? chooseImageForTable(row.riverFloodForecastData[3]) : "-"}</TableCell>
              <TableCell sx={{ py: "2px", width: "18%", fontSize:".875rem" }}>{row.riverFloodForecastData.length > 0 ? chooseImageForTable(row.riverFloodForecastData[4]) : "-"}</TableCell>

            </TableRow>
          ))}
        </TableBody>
      </Table>
    </TableContainer>
  );
};

export default MonitoringTable; 