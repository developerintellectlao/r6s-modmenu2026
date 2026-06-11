"use client"

import { Grid2, Typography, Stack, Tooltip, Menu, MenuItem, Button } from '@mui/material';
import { useState, useEffect } from 'react';
import Table from "@/features/todayStaus/Todaytable";
import { Icon } from '@iconify/react';
import MapCanvas from '@/features/todayStaus/MapCanvas';
import Link from 'next/link';
import ShowModel from "@/features/todayStaus/ShowModel";
import { STATUS } from '@/common/constants';

export default function TodayStatus({ allData, data, country, handleFilter, updateData, floodSeason }) {
  const [anchorEl, setAnchorEl] = useState(null);
  const [filter, setFilter] = useState(null);
  const [openModel, setOpenModel] = useState({ howToRead: false, legend: false, disclaimer: false });
  const [statusCount, setStatusCount] = useState({
    Normal: 0,
    BelowMinimum: 0,
    BelowLTAs: 0,
    AboveLTAs: 0,
    AboveMax: 0
  });
  const [selectedRow, setSelectedRow] = useState();
  const [alarmStationCount, setAlarmStationCount] = useState(0)
  const [floodStationCount, setFloodStationCount] = useState(0)
  // const [todayStausMapArea, setTodayStausMapArea] = useState();
  useEffect(() => {
    updateData()
  }, [])

  useEffect(() => {
    let obj = {
      Normal: 0,
      BelowMinimum: 0,
      BelowLTAs: 0,
      AboveLTAs: 0,
      AboveMax: 0
    }
    allData.forEach((row, index) => {
      if (row?.Today === STATUS.NORMAL) {
        obj.Normal = obj.Normal + 1
      } else if (row?.Today === STATUS.BELOW_MINIMUM) {
        obj.BelowMinimum = obj.BelowMinimum + 1
      } else if (row?.Today === STATUS.BELOW_LTAS) {
        obj.BelowLTAs = obj.BelowLTAs + 1
      } else if (row?.Today === STATUS.ABOVE_LTAS) {
        obj.AboveLTAs = obj.AboveLTAs + 1
      } else if (row?.Today === STATUS.ABOVE_MAX) {
        obj.AboveMax = obj.AboveMax + 1
      }
    })
    setStatusCount(obj);
  }, [allData]);

  useEffect(() => {
      if(!data.length < 0) return
      let countAlarm = 0
      let countFlood = 0
  
      data.forEach((row)=>{
       if(row?.Today === '3' || row?.Today === '4'|| row?.Today === '5'){
          countAlarm++
       } 
        if(row?.Today === '6' || row?.Today === '7'|| row?.Today === '8'){
          countFlood++
       }
      })
      setAlarmStationCount(countAlarm)
      setFloodStationCount(countFlood)
  
    }, [data])

  // useEffect(() => {
  //   getTodayStatusMapArea();
  // }, []);

  // const getTodayStatusMapArea = async () => {
  //   try {
  //     const res = await axios.get(TODAY_STATUS);
  //     setTodayStausMapArea(res?.data)
  //   } catch (error) {
  //     console.error(error);
  //   }
  // };
  const open = Boolean(anchorEl);

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
        {floodSeason ? 
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
          {formatDate(new Date())}&nbsp;

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
      :
        <Stack
          direction="row"
          sx={{
            background: "#f5f5f4",
            p: { xs: 2, sm: 3 },
            display: "flex",
            justifyContent: "center",
          }}
        >
          <Typography
            sx={{
              textAlign: "center",
              fontWeight: "700",
              fontSize: { xs: "12px", sm: "14px" },
              color: "#4b5563",
              lineHeight: "1.5",
            }}
            variant="body1"
          >
            {formatDate(new Date())}.&nbsp;

            <span style={{ color: "#047857" }}>
              Normal: {statusCount?.Normal} {statusCount?.Normal <= 1 ? "station" : "stations"}.
            </span>&nbsp;

            <span style={{ color: "#b45309" }}>
              Below Minimum: {statusCount?.BelowMinimum}{" "}
              {statusCount?.BelowMinimum <= 1 ? "station" : "stations"}.
            </span>&nbsp;

            <span style={{ color: "#f59e0b" }}>
              Below LTAs: {statusCount?.BelowLTAs}{" "}
              {statusCount?.BelowLTAs <= 1 ? "station" : "stations"}.
            </span>&nbsp;

            <span style={{ color: "#3b82f6" }}>
              Above LTAs: {statusCount?.AboveLTAs}{" "}
              {statusCount?.AboveLTAs <= 1 ? "station" : "stations"}.
            </span>&nbsp;

            <span style={{ color: "#1e40af" }}>
              Above Max: {statusCount?.AboveMax}{" "}
              {statusCount?.AboveMax <= 1 ? "station" : "stations"}.
            </span>
          </Typography>

        </Stack>}

        {/* Map Table*/}
        <Grid2 sx={{ width: '100%', display: "flex", flexDirection: { xs: "column", sm: "column", md: "row" } }}>

          {/* Map */}
          <Grid2
            sx={{
              width: { xs: "100%", sm: "100%", md: "50%" },
              height: "633px",
              pb: { xs: "8px", sm: "8px" }
            }}
          >
            <MapCanvas
              height="723px"
              stations={data}
              selectedRow={selectedRow}
              handelClick={handelClick}
              floodSeason = {floodSeason}
            // riverArea = {todayStausMapArea}
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
              width='100%'
              sx={{ p: 1, background: "#f9fafb" }}
            >
              <Stack direction={"row"}>
                <Stack
                  onClick={handleClick}
                  width={floodSeason ? "70%"  :"70%"}//width={floodSeason ? "39%"  :"41%"}
                  direction={"row"}
                  display={"flex"}
                  alignItems={"center"}
                  sx={{
                    cursor: 'pointer'
                  }}
                >
                  <Typography
                    variant="subtitle1"
                    fontWeight="bold"
                    fontSize={"14px"}
                    sx={{ fontWeight: "600", color: "#000000" }}
                  >
                    {"Monitoring Station"}
                  </Typography>

                  <Icon icon="icon-park-outline:down" width="16" height="16" color="#000000" />
                </Stack>
                <Stack width="30%">
                  <Typography
                    variant="subtitle1"
                    fontSize={"14px"}
                    sx={{ fontWeight: "600", color: "#000000" }}
                  >
                    {"Water Level"}
                  </Typography>
                </Stack>
                {/* <Stack width="24%" direction={"row"} display={"flex"} alignItems={"center"}>
                  <Typography
                    variant="subtitle1"
                    fontWeight="bold"
                    fontSize={"14px"}
                    sx={{ pr: "2px", fontWeight: "600", color: "#000000" }}
                  >
                    {"Flow Threshold"}
                  </Typography>
                  <Tooltip
                    title="Based on the cooperation on the maintenance of an acceptable flow regime on the Mekong mainstream as defined in the MRC PMFM"
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
                        sx: {
                          color: '#007bff', // Matches the tooltip background color
                        },
                      },
                    }}
                  >
                    <Icon icon="eva:question-mark-circle-outline" width="16" height="16" color="#000000" />
                  </Tooltip>
                </Stack> */}
              </Stack>
              <Stack direction={"row"} display={"flex"} alignItems={"center"} >
                <Typography
                  variant="body1"
                  // fontWeight="bold"
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
                {filter && <Icon color="#000000" onClick={() => filterByCountry(null)} icon="basil:cross-outline" width="24" height="24" />}
              </Stack>
            </Grid2>
            <Table
              data={data}
              handelClick={handelClick}
              selectedRow={selectedRow}
              floodSeason = {floodSeason}
            />

          </Grid2>

        </Grid2>

        <Stack direction={"row"} display={"flex"} justifyContent={"space-between"} sx={{
          flexDirection: { xs: "column", sm: "column", md: "row" },
          background:"#ffffff"
        }}>
          <Grid2>
            <Link href={floodSeason ? "https://ffw.mrcmekong.org/bulletin_wet.php" : "https://ffw.mrcmekong.org/reportflood.php"} target="_blank">
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
                {floodSeason ? "Flood & Flash Flood Page" : "Weekly flood & drought situation report"}
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
          floodSeason = {floodSeason}
        />
      )}

      {openModel?.disclaimer && (
        <ShowModel
          open={openModel?.disclaimer}
          toggleModel={() => setOpenModel({ howToRead: false, legend: false, disclaimer: false })}
          size={"xs"}
          keyChk={"disclaimer"}
          floodSeason = {floodSeason}
        />
      )}

      {openModel?.legend && (
        <ShowModel
          open={openModel?.legend}
          toggleModel={() => setOpenModel({ howToRead: false, legend: false, disclaimer: false })}
          size={"md"}
          keyChk={"legend"}
          floodSeason = {floodSeason}
        />
      )}

    </>
  );
}