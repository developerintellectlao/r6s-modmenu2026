"use client"

import { Grid2, Typography, Stack, Menu, MenuItem, Button } from '@mui/material';
import { useState, useEffect } from 'react';
import { Icon } from '@iconify/react';
import MapCanvas from '@/features/droughtForecast/MapCanvas';
import Link from 'next/link';
import ShowModel from "@/features/droughtForecast/ShowModel";
import { GET_DROUGHT_FORECAST_DATA, GET_DROUGHT_FORECAST_DATA_TEXT } from '@/service/apiManagement';
import axios from 'axios';

export default function DroughtForecast() {
  const [droughtText, setDroughtText] = useState()
  const [droughtGeoJson, setDroughtGeoJson] = useState([])
  const [openModel, setOpenModel] = useState({ howToRead: false, legend: false, disclaimer: false });

  useEffect(() => {
    getDroughData();
    getDroughTitleText();
  }, []);

  const getDroughTitleText = async () => {
    try {
      const res = await axios.get(GET_DROUGHT_FORECAST_DATA_TEXT);
      setDroughtText(res?.data)
    } catch (error) {
      console.error(error);
    }
  };

  const getDroughData = async () => {
    try {
      const res = await axios.get(GET_DROUGHT_FORECAST_DATA);
      setDroughtGeoJson(res?.data)
    } catch (error) {
      console.error(error);
    }
  };

  return (
    <>
      <Grid2 sx={{ width: '100%' }} >

        {/* Top bar */}
        <Stack direction={"row"} spacing={.5} sx={{ background: "#f5f5f4", p: 4, display: "flex", justifyContent: "center" }}>
          <Stack sx={{ display: "flex", justifyContent: "center" }}>
            <Typography
              sx={{
                textAlign: "center",
                fontWeight: "700",
                fontSize: "14px",
                color: "#000000"
              }}
              variant='body1'>
                {droughtText}
            </Typography>
          </Stack>
        </Stack>

        {/* Map Table*/}
        <Grid2 sx={{ width: '100%', display: "flex", }}>
          {droughtGeoJson?.features?.length > 0 && (
          <MapCanvas
            height="700px"
            droughtGeoJson = {droughtGeoJson}
          />
          )} 

        </Grid2>

        {/* Buttons */}
        <Stack direction={"row"} display={"flex"} justifyContent={"space-between"} sx={{
          flexDirection: { xs: "column", sm: "column", md: "row" },
          background:"#ffffff"
        }}>
          <Grid2>
            <Link href={"http://droughtforecast.mrcmekong.org/maps"} target="_blank">
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
                {"Drought Forecast website"}
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