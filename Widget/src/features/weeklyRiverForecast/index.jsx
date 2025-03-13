"use client"

import { Grid2, Typography, Stack, Tooltip, Menu, MenuItem, Button, } from '@mui/material';
import { useEffect, useState } from 'react';
import Table from "@/features/weeklyRiverForecast/Weeklytable";
import { Icon } from '@iconify/react';
import MapCanvas from './MapCanvas';
import Link from 'next/link';
import ShowModel from './ShowModel';
import { STATUS } from '@/common/constants';

export default function WeeklyRiverForecast({ allData, data, country, handleFilter, updateData }) {
  const [anchorEl, setAnchorEl] = useState(null);
  const [filter, setFilter] = useState(null);
  const [openModel, setOpenModel] = useState({ howToRead: false, legend: false, disclaimer: false });
  const [statusCount, setStatusCount] = useState({
    Normal: 0,
    BelowMinimum: 0,
    BelowLTAs: 0,
    AboveLTAs: 0,
    AboveMax: 0,
    Decreasing: 0,
    Increasing: 0,
    Stable: 0
  });
  const [selectedRow, setSelectedRow] = useState();

  useEffect(() => {
    updateData()
  }, [])

  useEffect(() => {
    let obj = {
      Normal: 0,
      BelowMinimum: 0,
      BelowLTAs: 0,
      AboveLTAs: 0,
      AboveMax: 0,
      Decreasing: 0,
      Increasing: 0,
      Stable: 0
    }

    allData.forEach((row, index) => {
      if (row?.Weekly === STATUS.NORMAL) {
        obj.Normal = obj.Normal + 1
      } else if (row?.Weekly === STATUS.BELOW_MINIMUM) {
        obj.BelowMinimum = obj.BelowMinimum + 1
      } else if (row?.Weekly === STATUS.BELOW_LTAS) {
        obj.BelowLTAs = obj.BelowLTAs + 1
      } else if (row?.Weekly === STATUS.ABOVE_LTAS) {
        obj.AboveLTAs = obj.AboveLTAs + 1
      } else if (row?.Weekly === STATUS.ABOVE_MAX) {
        obj.AboveMax = obj.AboveMax + 1
      }

      if (row?.Trend === STATUS.STABLE) {
        obj.Stable = obj.Stable + 1
      } else if (row?.Trend === STATUS.INCREASING) {
        obj.Increasing = obj.Increasing + 1
      } else if (row?.Trend === STATUS.DECREASING) {
        obj.Decreasing = obj.Decreasing + 1
      }
    })
    setStatusCount(obj);
  }, [allData]);

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
  }

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
  }

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

  function getLastWeekRange() {
  const today = new Date();
  const suffixes = { 1: "st", 2: "nd", 3: "rd", default: "th" };
  // Calculate last week's start and end dates
  const lastWeekEnd = new Date(today.setDate(today.getDate() - today.getDay())); // Last Sunday's date
  const lastWeekStart = new Date(today.setDate(today.getDate() - 6)); // Previous Monday's date

  // Helper function to format date as "DD MMM YYYY"
  function formatDate(date) {
    const options = { day: "2-digit", month: "short", year: "numeric" };
    return date.toLocaleDateString("en-US", options);
  }

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

  let startDate= formatDateByWeek(lastWeekStart)
  let endDate= formatDateByWeek(lastWeekEnd)
  return `${startDate} - ${endDate}` ;
}
  return (
    <>
      <Grid2 sx={{ width: '100%' }} >

        {/* Top bar */}
        <Stack
          direction="column"
          spacing={1}
          sx={{
            background: "#f5f5f4",
            p: { xs: 1, sm: 1.5,  },
            pl:{xl:"340px" ,lg:"138px" , md:"30px"},
            width: "100%",
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
            {getLastWeekRange(new Date())}.&nbsp;

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
          <Typography
              sx={{
                // textAlign: "center",
                fontWeight: 700,
                fontSize: { xs: "12px", sm: "14px" },
                color: "#4b5563",
              }}
              variant="body1"
            >
              {getWeekRange(new Date())}.&nbsp;

              <span style={{ color: "#000000" }}>
                Stable: {statusCount?.Stable}{" "}
                {statusCount?.Stable <= 1 ? "station" : "stations"}.&nbsp;

                Decreasing: {statusCount?.Decreasing}{" "}
                {statusCount?.Decreasing <= 1 ? "station" : "stations"}.&nbsp;

                Increasing: {statusCount?.Increasing}{" "}
                {statusCount?.Increasing <= 1 ? "station" : "stations"}.
              </span>
            </Typography>

          {/* Second Row */}
          {/* <Stack
            sx={{
              display: "flex",
              justifyContent: "center",
              textAlign: "center",
              mt: { xs: 1, sm: 2 },
            }}
          > */}
            
          {/* </Stack> */}

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
              stations={data}
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
              width='100%'
              sx={{ p: 1, background: "#f9fafb" }}
            >
              <Stack direction={"row"}>
                <Stack onClick={handleClick} width="43%" direction={"row"} display={"flex"} alignItems={"center"}>
                  <Typography
                    variant="subtitle1"
                    fontWeight="bold"
                    fontSize={"14px"}
                    sx={{ fontWeight: "600", color: "#000000", cursor: "pointer" }}
                  >
                    {"Monitoring Station"}
                  </Typography>

                  <Icon icon="icon-park-outline:down" width="16" height="16" />
                </Stack>
                <Stack width="30%" display={"flex"} alignItems={"start "}>
                  <Typography
                    variant="subtitle1"
                    fontSize={"14px"}
                    sx={{ fontWeight: "600", color: "#000000" }}
                  >
                    {"Last Week"}
                  </Typography>
                </Stack>
                <Stack width="24%" direction={"row"} display={"flex"} alignItems={"center"}>
                  <Typography
                    variant="subtitle1"
                    fontWeight="bold"
                    fontSize={"14px"}
                    sx={{ pr: "2px", fontWeight: "600", color: "#000000" }}
                  >
                    {"This Week's Trend"}
                  </Typography>

                </Stack>
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
                {filter && <Icon onClick={() => filterByCountry(null)} icon="basil:cross-outline" width="24" height="24" />}
              </Stack>
            </Grid2>
            <Table
              data={data}
              handelClick={handelClick}
              selectedRow={selectedRow}
            />
          </Grid2>
        </Grid2>

        <Stack direction={"row"} display={"flex"} justifyContent={"space-between"} sx={{
          flexDirection: { xs: "column", sm: "column", md: "row" },
          background:"#ffffff"
        }}>
          <Grid2>
            <Link href={"https://ffw.mrcmekong.org/reportflood.php"} target="_blank">
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
                {"Weekly flood & drought situation report"}
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