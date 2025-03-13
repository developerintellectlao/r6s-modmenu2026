"use client"

import * as React from 'react';

//MUI
import Box from '@mui/material/Box';
import Tab from '@mui/material/Tab';
import TabContext from '@mui/lab/TabContext';
import TabList from '@mui/lab/TabList';
import TabPanel from '@mui/lab/TabPanel';
import { Stack } from '@mui/material';


import TodayStatus from '../todayStaus';
import WeeklyRiverForecast from '../weeklyRiverForecast/index';
import axios from 'axios';
import LtaChart from '../ltaChart';
import { FLOW_THRESHOLD, STATUS_STATION } from '@/service/apiManagement';
import { statusMapping } from '@/common/utility';
import FlashFloodGuidance from '../flashFloodGuidance';
import DroughtForecast from '../droughtForecast';
import RainfallObservation from '../rainfallObservation';
import { COUNTRY_LIST, INITIAL_DATA } from '@/common/constants';


export default function Home() {

  const [value, setValue] = React.useState('1');
  const [finalData, setFinalData] = React.useState(INITIAL_DATA);
  const [allData, setAllData] = React.useState(INITIAL_DATA);
  const [countrys , setCountry] = React.useState(COUNTRY_LIST);
  
  React.useEffect(() => {
       getStatus();
       getFlowThresholdData();
  }, []);

  const getStatus = async () => {
    try {
      const res = await axios.get(STATUS_STATION);
      // Clone the state to avoid direct modification
      let temp = [...finalData];
      // console.log("res?.data", res?.data)
      let rows = res?.data.trim().split("\n");
      // console.log("rows", rows)

      rows.slice(1).forEach((row) => {
        let values = row.split(",");
        temp = temp.map((item) => {
          if (item.station === values[0]) {
            return {
              ...item,
              Today: statusMapping(values[1].trim()) || null,
              Weekly: statusMapping(values[2].trim()) || null,
              Trend: values[3].trim() || null,
            };
          }
          return item;
        });
      });
      // console.log("temp", temp)
      setAllData(temp);
      setFinalData(temp);
    } catch (error) {
      console.error(error);
    }
  };

  const getFlowThresholdData = async () => {
    try {
      const res = await axios.get(FLOW_THRESHOLD);
  
      setAllData((prevAllData) => {
        const updatedData = prevAllData.map((item) => {
          const filteredRow = res?.data.find((row) => row?.station_name === item.station);
          if (filteredRow) {
            return { ...item, FlowThreshold: (filteredRow?.water_level_zone === "ABOVE_AVERAGE" || filteredRow?.water_level_zone === "BELOW_AVERAGE")  ? "Normal" : filteredRow?.water_level_zone};
          }
          return item;
        });
  
        // Ensure finalData is also updated
        setFinalData(updatedData);
        return updatedData; // Return the new allData value
      });
    } catch (error) {
      console.error(error);
    }
  };
  
  const handleFilter = (country) => {
    if(country === null){
      setFinalData(allData)
    } else {
      const data = allData.filter((row)=>row?.country === country);
      setFinalData(data)
    }
  }

  const handleChange = (event, newValue) => {
    setValue(newValue);
  };

  const updateData = () => {
    getStatus();
    getFlowThresholdData();
  }

  return (
    <>
      <Box sx={{P:0, m:0}} >
        <TabContext value={value} >
          <Box sx={{ borderBottom: 1, borderColor: 'divider', display: "flex", justifyContent: "center" }} >
            <TabList onChange={handleChange} variant="scrollable" scrollButtons="auto" aria-label="lab API tabs example">
              <Tab label="TODAY'S STATUS" value="1" />
              <Tab label="Weekly River Forecast" value="2" />
              <Tab label="LTA Chart" value="3" />
              <Tab label="Flash Flood Guidance" value="4" />
              <Tab label="Drought Forecast" value="5" />
              <Tab label="Rainfall Observation" value="6" />
            </TabList>
          </Box>
          <TabPanel value="1" sx={{ p: 0 }}><TodayStatus updateData = {updateData} allData = {allData} data={finalData} country = {countrys} handleFilter = {handleFilter}/></TabPanel>
           <TabPanel value="2" sx={{ p: 0 }}><WeeklyRiverForecast updateData = {updateData} allData = {allData} data={finalData} country = {countrys} handleFilter = {handleFilter}/></TabPanel>
          <TabPanel value="3" sx={{ p: 0 }}><LtaChart allData = {allData} data={finalData} country = {countrys} handleFilter = {handleFilter}/></TabPanel>
          <TabPanel value="4" sx={{ p: 0 }}><FlashFloodGuidance allData = {allData} data={finalData} country = {countrys} handleFilter = {handleFilter} /></TabPanel>
          <TabPanel value="5" sx={{ p: 0 }}><DroughtForecast allData = {allData} data={finalData} country = {countrys} handleFilter = {handleFilter} /></TabPanel> 
         <TabPanel value="6" sx={{ p: 0 }}><RainfallObservation allData = {allData} data={finalData} country = {countrys} handleFilter = {handleFilter} /></TabPanel>
        </TabContext>
      </Box>
    </>
  );
}
