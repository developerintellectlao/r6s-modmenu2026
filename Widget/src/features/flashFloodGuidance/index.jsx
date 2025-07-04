"use client"

import { Grid2, Typography, Stack, Tooltip, Menu, MenuItem, Button, Autocomplete, TextField } from '@mui/material';
import { useState, useEffect } from 'react';
import { Icon } from '@iconify/react';
import MapCanvas from '@/features/flashFloodGuidance/MapCanvas';
import Link from 'next/link';
import ShowModel from "@/features/flashFloodGuidance/ShowModel";
import axios from 'axios';
import { FLASH_FLOOD_JSON, FLASH_FLOOD_TEXT } from '@/service/apiManagement';

const hours = ["Low", "moderate", "High"];

export default function FlashFloodGuidance({ data, country, handleFilter }) {
  const [anchorEl, setAnchorEl] = useState(null);
  const [openModel, setOpenModel] = useState({ howToRead: false, legend: false, disclaimer: false });
  const [selectedRow, setSelectedRow] = useState({ country: "Lao", station: "Vientiane", Today: "", FlowThreshold: "", Weekly: "", Trend: "", latitude: "17.975706", longitude: "102.633102" });
  const [filter, setFilter] = useState()
  const [hoursFilter, setHoursFilter] = useState({key:"", hours:""})
  const [flashFloodText, setFlashFloodText] = useState()
  const [flashFloodJson, setFlashFloodJson] = useState([])
  const [showOptions, setshowOptions] = useState({country:false, hours:false})
  
  useEffect(()=>{
    getFlashFloodtext() 
    getFlashFloodJson()
  },[])

  const getFlashFloodtext = async () => {
    const res = await axios.get(FLASH_FLOOD_TEXT);
    setFlashFloodText(res.data);
  }

  const getFlashFloodJson = async () => {
    const res = await axios.get(FLASH_FLOOD_JSON);
    setFlashFloodJson(res.data);
  }

  const open = Boolean(anchorEl);

  const handleClick = (event, key,value) => {
    setAnchorEl(event.currentTarget);
    if(key === "country"){
      setshowOptions({country:true, hours:false})
    } else {
      setHoursFilter({...hoursFilter,hours:value})
      setshowOptions({country:false, hours:true})
    }
  };

  const handleClose = () => {
    setAnchorEl(null);
  };

  const filterByCountry = (country) => {
    setFilter(country)
    handleFilter(country)
    handleClose()
  };

  const filterByHours = (key) => {
    if(key == null){
      setHoursFilter({key:"", hours:""})
    } else {
      setHoursFilter({...hoursFilter,key:key})
      // handleFilter(country)
      handleClose()
    }
  };

  function formatDate(date) {
    const suffixes = { 1: "st", 2: "nd", 3: "rd", default: "th" };

    const day = date.getDate();
    const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    const month = monthNames[date.getMonth()];
    const year = date.getFullYear();

    const suffix =
      (day % 10 === 1 && day !== 11) ? suffixes[1] :
        (day % 10 === 2 && day !== 12) ? suffixes[2] :
          (day % 10 === 3 && day !== 13) ? suffixes[3] : suffixes.default;

    return `${day}${suffix} ${month} ${year}`;
  };

  const handelClick = (row) => {
    setSelectedRow(row)
  }


  return (
    <>
      <Grid2 sx={{ width: '100%' }} >


        {/* Top bar */}
        <Stack direction={"row"} spacing={.5} sx={{ background: "#f5f5f4", p: 3, display: "flex", justifyContent: "center" }}>
          <Stack sx={{ display: "flex", justifyContent: "center" }}>
            <Typography
              sx={{
                textAlign: "center",
                fontWeight: "700",
                fontSize: "14px",
                color: "#000000"
              }}
              variant='body1'>
              {flashFloodText}
            </Typography>
          </Stack>
        </Stack>

        {/* Map Table*/}
        <Grid2 
          sx={{ 
            width: '100%', 
            display: "flex", 
            flexDirection: { xs: "column", sm: "column" , md:"row"}
          }}
        >

          {/* Map */}
          <Grid2
            sx={{
              width: { xs: "100%", sm: "100%" , md:"50%"},
              minHeight: "633px",
              pb:{ xs: "8px", sm: "8px" }
            }}
          >
            <MapCanvas
              height="700px"
              stations={data}
              selectedRow={selectedRow}
              handelClick={handelClick}
            />
          </Grid2>

          {/* Table */}
          <Grid2
            sx={{
              width: { xs: "100%", sm: "100%" , md:"50%"},
              minHeight: "633px",
              
            }}
          >
            <Grid2
              display={"flex"}
              flexDirection={"column"}
              width='100%'
              sx={{ pb: 3,pr: 3,pl:3, background: "#f9fafb" }}
            >
             <Stack direction={"row"}>
                <Stack width="25%" direction={"row"} display={"flex"} alignItems={"center"}>
                  <Typography
                    variant="subtitle1"
                    fontWeight="bold"
                    fontSize={"14px"}
                    sx={{ fontWeight: "600" , color:"#000000"}}
                  >
                    {"Country / District"}
                  </Typography>

                  <Icon onClick={(e)=>handleClick(e, "country")} icon="icon-park-outline:down" width="16" height="16" />
                </Stack>
                <Stack width="25%"  direction={"row"} display={"flex"} alignItems={"center"}>
                  <Typography
                    variant="subtitle1"
                    fontSize={"14px"}
                    sx={{ fontWeight: "600", color:"#000000" }}
                  >
                    {"1hr"}
                  </Typography>
                  <Icon onClick={(e)=>handleClick(e,"hours", 1)} icon="icon-park-outline:down" width="16" height="16" />

                </Stack>
                <Stack width="25%" direction={"row"} display={"flex"} alignItems={"center"}>
                  <Typography
                    variant="subtitle1"
                    fontWeight="bold"
                    fontSize={"14px"}
                    sx={{ pr: "2px", fontWeight: "600", color:"#000000" }}
                  >
                    {"3hr"}
                  </Typography>
                  <Icon onClick={(e)=>handleClick(e,"hours",3)} icon="icon-park-outline:down" width="16" height="16" />

                </Stack>
                <Stack width="25%" direction={"row"} display={"flex"} alignItems={"center"}>
                  <Typography
                    variant="subtitle1"
                    fontWeight="bold"
                    fontSize={"14px"}
                    sx={{ pr: "2px", fontWeight: "600", color:"#000000" }}
                  >
                    {"6hr"}
                  </Typography>
                  <Icon onClick={(e)=>handleClick(e,"hours",6)} icon="icon-park-outline:down" width="16" height="16" />

                </Stack>
              </Stack>
              <Stack direction={"row"}>
                <Stack width="25%" direction={"row"} display={"flex"} alignItems={"center"}>
                <Typography
                  variant="body1"
                  fontSize={"14px"}
                  sx={{ color: "#6b7280" }}
                  style={{
                    opacity: filter ? 1 : 0,
                  }}
                >
                  {"Filter: "}
                </Typography>
                <Typography
                  variant="body1"
                  // fontWeight="bold"
                  fontSize={"14px"}
                  sx={{ color: "black" }}
                >
                  {`${filter ? filter : " "}`} 
                </Typography>
                 {filter && <Icon onClick={() => filterByCountry(null)} icon="basil:cross-outline" width="24" height="24" />} 
                </Stack>
                <Stack width="25%" direction={"row"} display={"flex"} alignItems={"center"}>
                <Typography
                  variant="body1"
                  // fontWeight="bold"
                  fontSize={"14px"}
                  sx={{ color: "#6b7280" }}
                  style={{
                    opacity: (hoursFilter?.hours === 1 && hoursFilter?.key) ? 1 : 0,
                  }}
                >
                  {"Filter: "}
                </Typography>
                <Typography
                  variant="body1"
                  // fontWeight="bold"
                  fontSize={"14px"}
                  sx={{ color: "black" }}
                >
                  {`${(hoursFilter?.hours === 1 && hoursFilter?.key) ? hoursFilter?.key : " "}`} 
                </Typography>
                 {(hoursFilter?.hours === 1 && hoursFilter?.key) && <Icon onClick={() => filterByHours(null)} icon="basil:cross-outline" width="24" height="24" />} 

                </Stack>
                <Stack width="25%" direction={"row"} display={"flex"} alignItems={"center"}>
                <Typography
                  variant="body1"
                  // fontWeight="bold"
                  fontSize={"14px"}
                  sx={{ color: "#6b7280" }}
                  style={{
                    opacity: (hoursFilter?.hours === 3 && hoursFilter?.key) ? 1 : 0,
                  }}
                >
                  {"Filter: "}
                </Typography>
                <Typography
                  variant="body1"
                  // fontWeight="bold"
                  fontSize={"14px"}
                  sx={{ color: "black" }}
                >
                  {`${(hoursFilter?.hours === 3 && hoursFilter?.key) ? hoursFilter?.key : " "}`} 
                </Typography>
                 {(hoursFilter?.hours === 3 && hoursFilter?.key) && <Icon onClick={() => filterByHours(null)} icon="basil:cross-outline" width="24" height="24" />} 

                </Stack>
                <Stack width="25%" direction={"row"} display={"flex"} alignItems={"center"}>
                <Typography
                  variant="body1"
                  // fontWeight="bold"
                  fontSize={"14px"}
                  sx={{ color: "#6b7280" }}
                  style={{
                    opacity: (hoursFilter?.hours === 6 && hoursFilter?.key) ? 1 : 0,
                  }}
                >
                  {"Filter: "}
                </Typography>
                <Typography
                  variant="body1"
                  // fontWeight="bold"
                  fontSize={"14px"}
                  sx={{ color: "black" }}
                >
                  {`${(hoursFilter?.hours === 6 && hoursFilter?.key) ? hoursFilter?.key : " "}`} 
                </Typography>
                 {(hoursFilter?.hours === 6 && hoursFilter?.key) && <Icon onClick={() => filterByHours(null)} icon="basil:cross-outline" width="24" height="24" />} 

                </Stack>
              </Stack>
            </Grid2>
            {flashFloodJson?.length === 0 && (
                <Stack>
                  <Typography
                    variant="body1"
                    // fontWeight="bold"
                    fontSize={"14px"}
                    sx={{ color: "#1e5fbb", fontWeight:"500", pl:3, mt:10 }}
                  >
                    {"The flash flood risk is indicated only in case of abnormal weather condition."}
                  </Typography>
                </Stack>
              )}
          </Grid2>
        </Grid2>


        {/* Buttons */}
        <Stack direction={"row"} display={"flex"} justifyContent={"space-between"} sx={{
          flexDirection: { xs: "column", sm: "column", md: "row" },
          background:"#ffffff"
        }}>
          <Grid2>
            <Link href={"http://ffp.mrcmekong.org:8000/bulletin"} target="_blank">
              <Button
                variant='outlined'
                startIcon={<Icon icon="tabler:external-link" width="14" height="14" color="#228be6" />}
                sx={{
                  margin: "17px 0px 0px 8px",
                  padding: "0 13px 0 12px",
                  fontSize: "12px",
                  color: "#228be6",
                  fontWeight: "600",
                  textTransform: "none",
                  fontFamily: "var(--font-primary)"
                }}
              >
                {"Flash Flood Guidance System"}
              </Button>
            </Link>
          </Grid2>
          <Grid2
            direction={"row"}
            sx={{
              display: "flex",
              justifyContent: { md: "flex-end" }
            }}
          >
            <Button
              variant='outlined'
              sx={{
                margin: "17px 0 8px 17px",
                padding: "0 10px 0 10px",
                fontSize: "12px",
                color: "#228be6",
                fontWeight: "600",
                textTransform: "none",
                fontFamily: "var(--font-primary)"
              }}
              onClick={(e) => { e.preventDefault(), e.stopPropagation(), setOpenModel({ ...openModel, howToRead: true }) }}
            >
              {"How to read"}
            </Button>
            <Button
              variant='outlined'
              sx={{
                margin: "17px 0 8px 8px",
                padding: "0 10px 0 10px",
                fontSize: "12px",
                color: "#228be6",
                fontWeight: "600",
                textTransform: "none",
                fontFamily: "var(--font-primary)"
              }}
              onClick={(e) => { e.preventDefault(), e.stopPropagation(), setOpenModel({ ...openModel, legend: true }) }}
            >
              {"Legend"}
            </Button>
            <Button
              variant='outlined'
              sx={{
                margin: "17px 4px 8px 8px",
                padding: "0 10px 0 10px",
                fontSize: "12px",
                color: "#228be6",
                fontWeight: "600",
                textTransform: "none",
                fontFamily: "var(--font-primary)"
              }}
              onClick={(e) => { e.preventDefault(), e.stopPropagation(), setOpenModel({ ...openModel, disclaimer: true }) }}
            >
              {"Disclaimer"}
            </Button>
          </Grid2>
        </Stack>


      </Grid2>

      {/* show menu list */}
      <Menu
        id="basic-menu"
        anchorEl={anchorEl}
        open={open}
        onClose={handleClose}
        MenuListProps={{
          'aria-labelledby': 'basic-button',
        }}
      >
        {showOptions?.country ? 
        country.map((row, index) => (
          <MenuItem key={index} onClick={() => filterByCountry(row)}>{row}</MenuItem>
        )) : 
        hours.map((row, index) => (
          <MenuItem key={index} onClick={() => filterByHours(row)} >{row}</MenuItem>
        ))
      }
      </Menu>


      {/* show popup model */}
      {openModel?.howToRead && (
        <ShowModel
          open={openModel?.howToRead}
          toggleModel={() => setOpenModel({ howToRead: false, legend: false, disclaimer: false })}
          size={"md"}
          keyChk={"howToRead"}
        />
      )}

      {openModel?.disclaimer && (
        <ShowModel
          open={openModel?.disclaimer}
          toggleModel={() => setOpenModel({ howToRead: false, legend: false, disclaimer: false })}
          size={"xs"}
          keyChk={"disclaimer"}
        />
      )}

      {openModel?.legend && (
        <ShowModel
          open={openModel?.legend}
          toggleModel={() => setOpenModel({ howToRead: false, legend: false, disclaimer: false })}
          size={"md"}
          keyChk={"legend"}
        />
      )}

    </>
  );
}