"use client"

import { Grid2, Typography, Stack, Tooltip, Menu, MenuItem, Button, } from '@mui/material';
import { useEffect, useState } from 'react';
import Table from "@/features/riverFloodForecast/RiverFloodtable";
import { Icon } from '@iconify/react';
import MapCanvas from './MapCanvas';
import Link from 'next/link';
import ShowModel from './ShowModel';
import { RIVER_FLOOD_FORECAST } from '@/service/apiManagement';
import axios from 'axios';

export default function RiverFloodForecast({ allData, data, country, handleFilter }) {
  const [anchorEl, setAnchorEl] = useState(null);
  const [filter, setFilter] = useState(null);
  const [openModel, setOpenModel] = useState({ howToRead: false, legend: false, disclaimer: false });
  const [selectedRow, setSelectedRow] = useState();
  const [tableData, setTableData] = useState([]);
  const [alarmStationCount, setAlarmStationCount] = useState(0)
  const [floodStationCount, setFloodStationCount] = useState(0)

  useEffect(() => {
    getRiverFloodForecastData()
  }, [data])

  useEffect(() => {
    if(!tableData.length < 0) return
    let countAlarm = 0
    let countFlood = 0
    console.log("tableData", tableData)

    tableData.forEach((row) => {
      if(row?.riverFloodForecastData.includes('6') || row?.riverFloodForecastData.includes('7') || row?.riverFloodForecastData.includes('8')) {
        countFlood++
      }  else if(row?.riverFloodForecastData.includes('3') || row?.riverFloodForecastData.includes('4') || row?.riverFloodForecastData.includes('5')) {
        countAlarm++  
      }

    })
    setAlarmStationCount(countAlarm)
    setFloodStationCount(countFlood)

  }, [tableData])

  const open = Boolean(anchorEl);

  const getRiverFloodForecastData = async () => {
      try {
        const res = await axios.get(RIVER_FLOOD_FORECAST);
        let temp = [...data]
        let rows = res?.data.trim().split("\n");
        let row = rows.slice(2)
        temp = temp.map((item, index) => ({
          ...item,
          riverFloodForecastData: row[index].trim().split(',').slice(1)
        }));
        setTableData(temp);
      } catch (error) {
        console.error(error);
      }
    };

  const handleClick = (event) => {
    setAnchorEl(event.currentTarget);
  };

  const handleClose = () => {
    setAnchorEl(null);
  };

  const filterByCountry = (country) => {
    setFilter(country)
    handleFilter(country)
    handleClose()
  }

  // function formatDate(date) {
  //   const suffixes = { 1: "st", 2: "nd", 3: "rd", default: "th" };

  //   const day = date.getDate();
  //   const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
  //   const month = monthNames[date.getMonth()];
  //   const year = date.getFullYear();

  //   const suffix =
  //     (day % 10 === 1 && day !== 11) ? suffixes[1] :
  //       (day % 10 === 2 && day !== 12) ? suffixes[2] :
  //         (day % 10 === 3 && day !== 13) ? suffixes[3] : suffixes.default;

  //   return `${day}${suffix} ${month} ${year}`;
  // }

  function getWeekRange() {
    const suffixes = { 1: "st", 2: "nd", 3: "rd", default: "th" };

    const getDayWithSuffix = (day) => {
      return (day % 10 === 1 && day !== 11) ? suffixes[1] :
        (day % 10 === 2 && day !== 12) ? suffixes[2] :
          (day % 10 === 3 && day !== 13) ? suffixes[3] : suffixes.default;
    };

    const formatDateByWeek = (date) => {
      const day = date.getDate();
      const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
      const month = monthNames[date.getMonth()];
      const year = date.getFullYear();
      const suffix = getDayWithSuffix(day);
      return `${day}${suffix} ${month} ${year}`;
    };

    const today = new Date();
    const dayOfWeek = today.getDay(); // 0 is Sunday, 6 is Saturday
    const startOfWeek = new Date(today);
    startOfWeek.setDate(today.getDate() - dayOfWeek + 1); // Start of the week (Monday)

    const endOfWeek = new Date(startOfWeek);
    endOfWeek.setDate(startOfWeek.getDate() + 6); // End of the week (Sunday)

    return `${formatDateByWeek(startOfWeek)} - ${formatDateByWeek(endOfWeek)}`;
  }

  const handelClick = (row) => {
    setSelectedRow(row)
  }

  const formatDate = (date) => {
      const day = addSuffix(date.getDate());
      const month = date.toLocaleString('default', { month: 'short' });
      const year = date.getFullYear();
      let res = `${day} ${month} ${year}`;
      return res
    };

    const addSuffix = (day) => {
      if (day >= 11 && day <= 13) return day + 'th';
      const lastDigit = day % 10;
      switch (lastDigit) {
        case 1: return day + 'st';
        case 2: return day + 'nd';
        case 3: return day + 'rd';
        default: return day + 'th';
      }
    };

  function getDateRangeString() {
    let today = new Date();
    today.setDate(today.getDate() + 1);
    const endDate = new Date();
    endDate.setDate(today.getDate() + 4); // 6 days to include today + next 5 days

    return `${formatDate(today)} - ${formatDate(endDate)}`;
  }

  function addDays(date, days) {
  const result = new Date(date);
  result.setDate(result.getDate() + days);
  return result;
}

  return (
    <>
      <Grid2 sx={{ width: '100%' }} >

        {/* Top bar */}
        <Stack
          direction="row"
          spacing={1}
          sx={{
            background: "#f5f5f4",
            p: { xs: 2, sm: 3 },
            display: "flex",
            justifyContent: "center",
          }}
        >
          {/* First Row as Paragraph */}
          <Typography
            sx={{
              // textAlign: "center",
              fontWeight: 700,
              fontSize: { xs: "12px", sm: "14px" },
              color: "#4b5563",
            }}
            variant="body1"
          >
            {`${getDateRangeString()} `} 

           {alarmStationCount > 0 && (
            <span style={{ color: "#f59e0b" }}>
              {`: Alarm level at ${alarmStationCount} station${alarmStationCount > 1 ? 's' : ''} `}
            </span>)}
             {floodStationCount > 0 && (
            <span style={{ color: "#ff0000" }}>
              {`: Flood level at ${floodStationCount} station${floodStationCount > 1 ? 's' : ''}`}
            </span>)}
          </Typography>
        </Stack>

        <Grid2 sx={{ width: '100%', display: "flex", flexDirection: { xs: "column", sm: "column", md: "row" } }}>

          {/* Map */}
          <Grid2
            sx={{
              width: { xs: "100%", sm: "100%", md: "50%" },
              minHeight: "633px",
              pb: { xs: "8px", sm: "8px" }
            }}
          >
            <MapCanvas
              height="700px"
              stations={tableData}
              selectedRow={selectedRow}
              handelClick={handelClick}
            />
          </Grid2>

          {/* Table */}
          <Grid2
            sx={{
              width: { xs: "100%", sm: "100%", md: "50%" },
              minHeight: "633px",
            }}
          >
            <Grid2
              display={"flex"}
              flexDirection={"column"}
              // width='100%'
              sx={{ p: 1, background: "#f9fafb" }}
            >
              <Stack direction={"row"} display={"flex"} justifyContent={"space-between"} alignItems={"center"}>

                  <Stack 
                    onClick={handleClick}  
                    direction={"row"} 
                    display={"flex"} 
                    alignItems={"center"}
                      width={floodStationCount ? { lg: "26%", md: "32%", sm: "40%" } : undefined}

                  >
                    <Typography
                      variant="subtitle1"
                      fontWeight="bold"
                      fontSize={"14px"}
                      sx={{ fontWeight: "600", color: "#000000", cursor: "pointer" }}
                    >
                      {"Forecasting Station"}
                    </Typography>

                    <Icon icon="icon-park-outline:down" width="16" height="16" color='#1c1c1d' />
                  </Stack>

                <Stack 
                  direction={"row"} 
                  display={"flex"} 
                  padding={"0px"}
                  // justifyContent={"space-around"} 
                  alignItems={"center"} 
                  justifyContent="center" 
                  sx={{ width: "74%" }}
                >
                  <Stack 
                    sx={{ width: "20%", pl:{lg:"8px",md:"18px"}}}  
                    display={"flex"} 
                    // alignItems={"start "}
>
                    <Typography
                      variant="subtitle1"
                      fontSize={"14px"}
                      sx={{ fontWeight: "600", color: "#000000" }}
                    >
                      {addSuffix(addDays(new Date(), 1).getDate())}
                    </Typography>
                  </Stack>
                  <Stack sx={{ width: "20%",pl:{lg:"8px",md:"18px"}}} display={"flex"} >
                    <Typography
                      variant="subtitle1"
                      fontSize={"14px"}
                      sx={{ fontWeight: "600", color: "#000000" }}
                    >
                      {addSuffix(addDays(new Date(), 2).getDate())}
                    </Typography>
                  </Stack>
                  <Stack sx={{ width: "20%", pl:{lg:"8px",md:"18px"}}} display={"flex"} >
                    <Typography
                      variant="subtitle1"
                      fontSize={"14px"}
                      sx={{ fontWeight: "600", color: "#000000" }}
                    >
                      {addSuffix(addDays(new Date(), 3).getDate())}
                    </Typography>
                  </Stack>
                  <Stack sx={{ width: "20%", pl:{lg:"8px",md:"18px"}}} direction={"row"} display={"flex"} >
                    <Typography
                      variant="subtitle1"
                      fontWeight="bold"
                      fontSize={"14px"}
                      sx={{ pr: "2px", fontWeight: "600", color: "#000000" }}
                    >
                      {addSuffix(addDays(new Date(), 4).getDate())}
                    </Typography>

                  </Stack>
                  <Stack sx={{ width: "20%", pl:{lg:"8px",md:"18px"}}} direction={"row"} display={"flex"} >
                    <Typography
                      variant="subtitle1"
                      fontWeight="bold"
                      fontSize={"14px"}
                      sx={{ pr: "2px", fontWeight: "600", color: "#000000" }}
                    >
                     {addSuffix(addDays(new Date(), 5).getDate())}
                    </Typography>

                  </Stack>
                </Stack>
                
              </Stack>

              <Stack direction={"row"} display={"flex"} alignItems={"end"} >
                <Typography
                  variant="body1"
                  // fontWeight="bold"
                  fontSize={"14px"}
                  sx={{ color: "#6b7280" }}
                  style={{
                    opacity: filter ? 1 : 0,
                  }}
                >
                  {"Filter :"}
                </Typography>
                <Typography
                  variant="body1"
                  // fontWeight="bold"
                  fontSize={"14px"}
                  sx={{ color: "black" }}
                >
                  {`${filter ? filter : " "}`}
                </Typography>
                {filter && <Icon onClick={() => filterByCountry(null)} icon="basil:cross-outline" width="24" height="24" color="#1c1c1d" />}
              </Stack>
            </Grid2>
            <Table
              data={tableData}
              handelClick={handelClick}
              selectedRow={selectedRow}
            />
          </Grid2>
        </Grid2>


        {/* Links */}

        <Stack direction={"row"} display={"flex"} justifyContent={"space-between"} sx={{
          flexDirection: { xs: "column", sm: "column", md: "row" },
          background: "#ffffff"
        }}>
          <Grid2>
            <Link href={"https://ffw.mrcmekong.org/bulletin_wet.php"} target="_blank">
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
                {"Flood & Flash Flood Page"}
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

      <Menu
        id="basic-menu"
        anchorEl={anchorEl}
        open={open}
        onClose={handleClose}
        MenuListProps={{
          'aria-labelledby': 'basic-button',
        }}
      >
        {country.map((row, index) => (
          <MenuItem sx={{}} key={index} onClick={() => filterByCountry(row)}>{row}</MenuItem>
        ))}

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