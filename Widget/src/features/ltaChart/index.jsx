"use client"
import dynamic from 'next/dynamic';
import { Grid2, Stack, Menu, MenuItem, Button, Autocomplete, TextField } from '@mui/material';
import { useState, useEffect } from 'react';
import { Icon } from '@iconify/react';
import MapCanvas from '@/features/ltaChart/MapCanvas';
import Link from 'next/link';
import ShowModel from "@/features/ltaChart/ShowModel";
import { LTA_CHART } from '@/service/apiManagement';
import axios from 'axios';
// import WaterLevelChart from '@/components/chart';
// import HighChartComponent from '@/components/LineChart';
const WaterLevelChart = dynamic(() => import('@/components/Chart'), { ssr: false });

export default function LtaChart({ data, country, handleFilter }) {
  const [anchorEl, setAnchorEl] = useState(null);
  const [openModel, setOpenModel] = useState({ howToRead: false, legend: false, disclaimer: false });
  const [selectedRow, setSelectedRow] = useState(data[0]);
  const [graffData, setGraffData] = useState("");

  useEffect(() => {
    getLtaChartData()
  }, [selectedRow]);

  const getLtaChartData = async () => {
    try {
    
      let url = LTA_CHART.replace('{station}', selectedRow?.code)
      const res = await axios.get(url);

      const lines = res?.data.trim().split("\n");
      const headers = lines[0].split(",");
      const json = lines.slice(1).map(line => {
        const values = line.split(",");
        return headers.reduce((acc, header, index) => {
          acc[header] = isNaN(values[index]) ? values[index] : parseFloat(values[index]);
          return acc;
        }, {});
      });

    //   console.log("type", typeof json)
    //   const gData = JSON.stringify(json, null, 2);
    // console.log("type", typeof gData)
      setGraffData(res?.data)
    } catch (error) {
      console.error(error);
    }
  };

  const open = Boolean(anchorEl);

  const handleClose = () => {
    setAnchorEl(null);
  };

  const filterByCountry = (country) => {
    setFilter(country)
    handleFilter(country)
    handleClose()
  };

  const handelClick = (row) => {
    setSelectedRow(row)
  }

  return (
    <>
      <Grid2 sx={{ width: '100%' }} >


        {/* Top bar */}
        <Stack direction={"row"} spacing={.5} sx={{ background: "#f5f5f4", p: 4, display: "flex", justifyContent: "center" }}>

        </Stack>

        {/* Map Table*/}
        <Grid2 sx={{ width: '100%', display: "flex", flexDirection: { xs: "column", sm: "column" , md:"row"}}}>

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
            <Autocomplete
              options={data}
              getOptionLabel={(option) => option.station + " " +option.B_name}
              id="disable-clearable"
              disableClearable
              // defaultValue={selectedRow}
              value={selectedRow}
              onChange={(event, newValue) => { setSelectedRow(newValue)}}
              renderInput={(params) => (
                <TextField {...params} label="Monitoring Station" variant="standard" />
              )}
              sx={{ m: 3 }}
            />

            <Stack>
            {/* <HighChartComponent /> */}
              <WaterLevelChart data={graffData} selectedRow = {selectedRow} />
            </Stack>


          </Grid2>

        </Grid2>


        {/* Buttons */}
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