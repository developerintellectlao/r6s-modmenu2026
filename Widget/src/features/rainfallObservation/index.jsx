"use client"

import { Grid2, Typography, Stack, Tooltip, Menu, MenuItem, Button } from '@mui/material';
import { useState, useEffect } from 'react';
import MapCanvas from '@/features/rainfallObservation/MapCanvas';
import ShowModel from "@/features/rainfallObservation/showModel";
import RainInfoCard from './RainInfoCard';
import { GET_RAINFALL_MAP_DATA, GET_RAINFALL_SUMMERY } from '@/service/apiManagement';
import axios from 'axios';

export default function RainfallObservation({ allData, data, country, handleFilter }) {

  const [rainfallSummery, setRainFallSummery] = useState("");
  const [rainfallMapData, setRainfallMapData] = useState();
  const [rainfallData, setRainfallData] = useState([]);
  const [openModel, setOpenModel] = useState({ howToRead: false, legend: false, disclaimer: false });

  useEffect(() => {
    getRainfallSummary();
    getRainfallMapData();
  }, []);

  const getRainfallSummary = async () => {
    try {
      const res = await axios.get(GET_RAINFALL_SUMMERY);
      setRainFallSummery(res?.data)
    } catch (error) {
      console.error(error);
    }
  };

  const getRainfallMapData = async () => {
    try {
      const res = await axios.get(GET_RAINFALL_MAP_DATA);
      setRainfallData(res?.data)
      let obj = {
        zeroMM: 0,
        oneToTenMM: 0,
        elevenToThirtyFiveMM: 0,
        thirtySixToNinetyMM: 0,
        overNinetyMM: 0,
        length:res?.data.features.length,
        noData: 0
      }
      res?.data.features.forEach((row) => {
        let mm = parseFloat(row.properties.mm);
        if (mm === 0) {
          obj.zeroMM++;
        } else if (mm > 0 && mm <= 10) {
          obj.oneToTenMM++;
        } else if (mm > 10 && mm <= 35) {
          obj.elevenToThirtyFiveMM++;
        } else if (mm > 35 && mm <= 90) {
          obj.thirtySixToNinetyMM++;
        } else if (mm > 90) {
          obj.overNinetyMM++;
        } else if(mm < 0){
          obj.noData++
        }
    });
      setRainfallMapData(obj)
    } catch (error) {
      console.error(error);
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

  return (
    <>
      <Grid2 sx={{ width: '100%' }} >


        {/* Top bar */}
        <Stack direction={"row"} spacing={.5} sx={{ background: "#f5f5f4", p: 3, display: "flex", justifyContent: "center" }}>
          <Typography
            sx={{
              textAlign: "start",
              fontWeight: "700",
              fontSize: "14px",
              color: "rgb(0, 0, 0)"
            }}
            variant='body1'>
            {rainfallSummery}
          </Typography>
        </Stack>

        {/* Map - Table*/}
        <Grid2 
          sx={{ 
            width: '100%', 
            display: "flex",
            flexDirection: { xs: "column", sm: "column" , md:"row"},
          }}
        >

          {/* Map */}
          <Grid2
            sx={{
              width: { xs: "100%", sm: "100%" , md:"50%"},
              minHeight: "633px",
              // border: "1px solid red"
            }}
          >
            <MapCanvas
              height="700px"
              data={rainfallData}
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
              sx={{ p: 2, background: "#f9fafb" }}
            >
              <Stack display={"flex"} alignItems={"center"}>
                <Typography
                  variant="subtitle1"
                  fontWeight="bold"
                  fontSize={"14px"}
                  sx={{ fontWeight: "600" , color:"#000000"}}
                >
                  {`Observed Rainfall from ${rainfallMapData?.length} stations on ${formatDate(new Date())}`}
                </Typography>
              </Stack>

            </Grid2>
            <Stack direction={"row"}>
              <RainInfoCard
                title={"Very Heavy rain"}
                mmSize={"90+ mm"}
                value={rainfallMapData?.overNinetyMM}
                circleBgcolor={"#f8ccc8"}
                circleFilledColor={"#E74C3C"}
                length = {rainfallMapData?.length}
              />
              <RainInfoCard
                title={"Heavy rain"}
                mmSize={"36-90 mm"}
                value={rainfallMapData?.thirtySixToNinetyMM}
                circleBgcolor={"#FAE5D2"}
                circleFilledColor={"#E57E22"}
                length = {rainfallMapData?.length}
              />
            </Stack>
            <Stack direction={"row"}>
              <RainInfoCard
                title={"Moderate rain"}
                mmSize={"11-35 mm"}
                value={rainfallMapData?.elevenToThirtyFiveMM}
                circleBgcolor={"#FCF2C8"}
                circleFilledColor={"#F0C40F"}
                length = {rainfallMapData?.length}
              />
              <RainInfoCard
                title={"Light rain"}
                mmSize={"1-10 mm"}
                value={rainfallMapData?.oneToTenMM}
                circleBgcolor={"#DAEDF8"}
                circleFilledColor={"#3598DB"}
                length = {rainfallMapData?.length}
              />
            </Stack>
            <Stack direction={"row"}>
              <RainInfoCard
                title={"No rain"}
                mmSize={"0 mm"}
                value={rainfallMapData?.zeroMM}
                circleBgcolor={"#DDE3E5"}
                circleFilledColor={"#B3BEC3"}
                length = {rainfallMapData?.length}
              />
              <RainInfoCard
                title={"No data"}
                mmSize={"No data received"}
                value={rainfallMapData?.noData}
                circleBgcolor={"#DDE3E5"}
                circleFilledColor={"#B3BEC3"}
                length = {rainfallMapData?.length}
              />
            </Stack>
          </Grid2>

        </Grid2>

        <Stack direction={"row"} display={"flex"} justifyContent={"flex-end"}>
          <Grid2
            direction={"row"}
            sx={{
              display: "flex",
              justifyContent: "flex-end"
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
      </Grid2>
    </>
  );
}