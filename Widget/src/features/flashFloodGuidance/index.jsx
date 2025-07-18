"use client"

import { Grid2, Typography, Stack, Tooltip, Menu, MenuItem, Button, Autocomplete, TextField } from '@mui/material';
import { useState, useEffect } from 'react';
import { Icon } from '@iconify/react';
import MapCanvas from '@/features/flashFloodGuidance/MapCanvas';
import Link from 'next/link';
import ShowModel from "@/features/flashFloodGuidance/ShowModel";
import axios from 'axios';
import { FLASH_FLOOD_JSON, FLASH_FLOOD_TEXT, GET_DROUGHT_FORECAST_DATA, FLASH_FLOOD_GUIDANCE_6HR, FLASH_FLOOD_GUIDANCE_3HR, FLASH_FLOOD_GUIDANCE_1HR } from '@/service/apiManagement';
import Table from "@/features/flashFloodGuidance/Todaytable";
import { getCountryCode } from '@/common/utility';

const hours = ["Low", "Moderate", "High"];

export default function FlashFloodGuidance({ data, country, floodSeason }) {
  const [anchorEl, setAnchorEl] = useState(null);
  const [openModel, setOpenModel] = useState({ howToRead: false, legend: false, disclaimer: false });
  const [selectedRow, setSelectedRow] = useState();
  const [filter, setFilter] = useState()
  const [hoursFilterKey, setHoursFilterKey] = useState()
  const [hoursFilter, setHoursFilter] = useState({ key: "", hours: "" })// which hours click ,  "low", "1"
  const [flashFloodText, setFlashFloodText] = useState()
  const [flashFloodJson, setFlashFloodJson] = useState([])
  const [allFlashFloodJson, setAllFlashFloodJson] = useState([])
  const [showOptions, setshowOptions] = useState({ country: false, hours: false })
  const [selectedHoursOnMap, setSelectedHoursOnMap] = useState("1");
  const [mapGeoJsonData, setMapGeoJsonData] = useState([])
  const [mapGeoJsonAllData, setMapGeoJsonAllData] = useState([])

  useEffect(() => {
    getFlashFloodtext()
    getFlashFloodJson()
  }, [])

  useEffect(() => {
    getMapData()
  }, [selectedHoursOnMap])

  const getMapData = async () => {
    try {
      // setMapGeoJsonData([]);
      // setMapGeoJsonAllData([]);

      let res;
      if (selectedHoursOnMap === "1") {
        res = await axios.get(FLASH_FLOOD_GUIDANCE_1HR);
      } else if (selectedHoursOnMap === "3") {
        res = await axios.get(FLASH_FLOOD_GUIDANCE_3HR);
      } else if (selectedHoursOnMap === "6") {
        res = await axios.get(FLASH_FLOOD_GUIDANCE_6HR);
      }
      setMapGeoJsonAllData(res.data);

      if(filter){
        let result = res.data.features.filter((item) => {
        return item.properties.Country === changeCountryAccordingToJson(filter)
      })

      let obj = {
        type: res.data.type,
        features: result
      }

      setMapGeoJsonData(obj);
      } else {
        setMapGeoJsonData(res.data);
      }
        
      
    } catch (error) {
      console.error(error);
    }
  }

  const getFlashFloodtext = async () => {
    const res = await axios.get(FLASH_FLOOD_TEXT);
    setFlashFloodText(res.data);
  }

  const getFlashFloodJson = async () => {
    const res = await axios.get(FLASH_FLOOD_JSON);
    setFlashFloodJson(res.data);
    setAllFlashFloodJson(res.data)
  }

  const open = Boolean(anchorEl);

  //click on arrow
  const handleClick = (event, key, value) => {
    if (key === "country") {
      setshowOptions({ country: true, hours: false })
    } else {
      setHoursFilter({ ...hoursFilter, hours: value })
      setshowOptions({ country: false, hours: true })
    }
    setAnchorEl(event.currentTarget);
  };

  //close open menu
  const handleClose = () => {
    setAnchorEl(null);
  };

  const handelClick = (row) => {
    setSelectedRow(row)
  }

  // it call when cross click on filter
  const handleClickMenu = (country, hours) => {
    if (country || hours) {
      let filteredCode;
      let hoursKey = `hr${hoursFilter?.hours}Risk`;
      let statusKey = hours ? hours : hoursFilter.key;
      let countryKey = country ? getCountryCode(country) : getCountryCode(filter);

      if (country && hours) {
        filteredCode = allFlashFloodJson.filter((item) => {
          return item[hoursKey] === statusKey && item.country === countryKey
        })
        setFlashFloodJson(filteredCode);
        setHoursFilter({ ...hoursFilter, key: hours })
        setFilter(country)
        handleClose()
      } else if (country) {
        filteredCode = allFlashFloodJson.filter((item) => {
          return item.country === countryKey
        })
        setFilter(country)
        setFlashFloodJson(filteredCode);
        handleClose()
        setHoursFilter({ key: "", hours: "" })
      } else if (hours) {
        filteredCode = allFlashFloodJson.filter((item) => {
          return item[hoursKey] === statusKey
        })
        setHoursFilter({ ...hoursFilter, key: hours })
        setFlashFloodJson(filteredCode);
        handleClose()
        setFilter(null)
      }

    } else {
      setFlashFloodJson(allFlashFloodJson);
      setHoursFilter({ key: "", hours: "" })
      handleClose()
      setFilter(null)
    }
  }

  //filter for map
  const handleFilterForMap = (countryName) => {

    if (countryName === null) {
      setMapGeoJsonData(mapGeoJsonAllData);
    } else {
      // setMapGeoJsonData([]);

      let result = mapGeoJsonAllData.features.filter((item) => {
        return item.properties.Country === changeCountryAccordingToJson(countryName)
      })

      let obj = {
        type: mapGeoJsonAllData.type,
        features: result
      }
      setMapGeoJsonData(obj);
    }
  }

  const changeCountryAccordingToJson = (country) => {
    switch (country) {
      case 'Lao':
        return "Laos";
      case 'Cambodia':
        return "Cambodia";
      case 'Viet Nam':
        return "Vietnam";
      default:
        return null;
    }
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
            flexDirection: { xs: "column", sm: "column", md: "row" }
          }}
        >

          {/* Map */}
          <Grid2
            sx={{
              width: { xs: "100%", sm: "100%", md: "50%" },
              minHeight: "633px",
              pb: { xs: "8px", sm: "8px" }
            }}
          >
            {/* {mapGeoJsonData?.features?.length > 0 && ( */}
              <MapCanvas
                flashFloodMapData={mapGeoJsonData}
                selectedHoursOnMap={selectedHoursOnMap}
                setSelectedHoursOnMap={setSelectedHoursOnMap}
              />
              {/* )} */}
          </Grid2>

          {/* Table */}
          <Grid2
            sx={{
              width: { xs: "100%", sm: "100%", md: "50%" },
              minHeight: "633px"
            }}
          >
            <Grid2
              display={"flex"}
              flexDirection={"column"}
              width='100%'
              sx={{ pb: 3, pr: 3, pl: 3, background: "#f9fafb" }}
            >
              <Stack direction={"row"}>
                <Stack width="35%" direction={"row"} display={"flex"} alignItems={"center"}>
                  <Typography
                    variant="subtitle1"
                    fontWeight="bold"
                    fontSize={"14px"}
                    sx={{ fontWeight: "600", color: "#000000" }}
                  >
                    {"Country / District"}
                  </Typography>

                  <Icon onClick={(e) => handleClick(e, "country")} icon="icon-park-outline:down" width="16" height="16" color="#000000" />
                </Stack>
                <Stack width="25%" direction={"row"} display={"flex"} alignItems={"center"}>
                  <Typography
                    variant="subtitle1"
                    fontSize={"14px"}
                    sx={{ fontWeight: "600", color: "#000000" }}
                  >
                    {"1hr"}
                  </Typography>
                  <Icon onClick={(e) => handleClick(e, "hours", 1)} icon="icon-park-outline:down" width="16" height="16" color="#000000" />

                </Stack>
                <Stack width="25%" direction={"row"} display={"flex"} alignItems={"center"}>
                  <Typography
                    variant="subtitle1"
                    fontWeight="bold"
                    fontSize={"14px"}
                    sx={{ pr: "2px", fontWeight: "600", color: "#000000" }}
                  >
                    {"3hr"}
                  </Typography>
                  <Icon onClick={(e) => handleClick(e, "hours", 3)} icon="icon-park-outline:down" width="16" height="16" color="#000000" />

                </Stack>
                <Stack width="25%" direction={"row"} display={"flex"} alignItems={"center"}>
                  <Typography
                    variant="subtitle1"
                    fontWeight="bold"
                    fontSize={"14px"}
                    sx={{ pr: "2px", fontWeight: "600", color: "#000000" }}
                  >
                    {"6hr"}
                  </Typography>
                  <Icon onClick={(e) => handleClick(e, "hours", 6)} icon="icon-park-outline:down" width="16" height="16" color="#000000" />

                </Stack>
              </Stack>

              <Stack direction={"row"}>
                <Stack width="35%" direction={"row"} display={"flex"} alignItems={"center"}>
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
                  {filter && <Icon onClick={() => { handleClickMenu(null, hoursFilter.key); handleFilterForMap(null) }} icon="basil:cross-outline" width="24" height="24" color="#000000" />}
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
                  {(hoursFilter?.hours === 1 && hoursFilter?.key) && <Icon onClick={() => handleClickMenu(filter, null)} icon="basil:cross-outline" width="24" height="24" color="#000000" />}

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
                  {(hoursFilter?.hours === 3 && hoursFilter?.key) && <Icon onClick={() => handleClickMenu(filter, null)} icon="basil:cross-outline" width="24" height="24" color="#000000" />}

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
                  {(hoursFilter?.hours === 6 && hoursFilter?.key) && <Icon onClick={() => handleClickMenu(filter, null)} icon="basil:cross-outline" width="24" height="24" color="#000000" />}

                </Stack>
              </Stack>

            </Grid2>
            {flashFloodJson?.length === 0  ? (
              <Stack>
                <Typography
                  variant="body1"
                  // fontWeight="bold"
                  fontSize={"14px"}
                  sx={{ color: "#1e5fbb", fontWeight: "500", pl: 3, mt: 10 }}
                >
                 {!floodSeason ? "The flash flood risk is indicated only in case of abnormal weather condition." : null}
                </Typography>
              </Stack>
            ) :
              <Stack sx={{ maxHeight: "539px" }}>
                <Table
                  data={flashFloodJson}
                  handelClick={handelClick}
                  selectedRow={selectedRow}

                />
              </Stack>
            }
          </Grid2>
        </Grid2>


        {/* Buttons */}
        <Stack direction={"row"} display={"flex"} justifyContent={"space-between"} sx={{
          flexDirection: { xs: "column", sm: "column", md: "row" },
          background: "#ffffff"
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
            <MenuItem key={index} onClick={() => { handleClickMenu(row, hoursFilter.key); handleFilterForMap(row) }}>{row}</MenuItem>
          )) :
          hours.map((row, index) => (
            <MenuItem key={index} onClick={() => handleClickMenu(filter, row)} >{row}</MenuItem>
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