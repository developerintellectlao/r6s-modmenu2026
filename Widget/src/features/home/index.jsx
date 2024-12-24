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

let initialData = [
  { country: "Thailand", station: "Chiang Saen", B_name:"",code:"CSA", Today: "",FlowThreshold:"",  Weekly: "", Trend: "" ,latitude: "20.27412033", longitude:"100.0885468"},
  { country: "Lao", station: "Luang Prabang",B_name:"", code:"LUA",Today: "",FlowThreshold:"",  Weekly: "", Trend: "",latitude: "19.89279938", longitude:"102.1341782" },
  { country: "Thailand", station: "Chiang Khan",B_name:"", code:"CKH",Today: "",FlowThreshold:"",  Weekly: "", Trend: "",latitude: "17.90026093", longitude:"101.6698914" },
  { country: "Lao", station: "Vientiane",B_name:"", code:"VIE",Today: "",FlowThreshold:"",  Weekly: "", Trend: "" ,latitude: "17.93098068", longitude:"102.6155624"},
  { country: "Thailand", station: "Nong Khai",B_name:"", code:"NON",Today: "",FlowThreshold:"",  Weekly: "", Trend: "" ,latitude: "17.8777504", longitude:"102.7166672"},
  { country: "Lao", station: "Paksane",B_name:"", code:"PAK",Today: "",FlowThreshold:"",  Weekly: "", Trend: "",latitude: "18.37340403", longitude:"103.6632097" },
  { country: "Thailand", station: "Nakhon Phanom",B_name:"", code:"NAK",Today: "",FlowThreshold:"",  Weekly: "", Trend: "" ,latitude: "17.623797", longitude:"104.517419"},
  { country: "Lao", station: "Thakhek",B_name:"", code:"THA",Today: "",FlowThreshold:"",  Weekly: "", Trend: "",latitude: "17.39453405", longitude:"104.8014" },
  { country: "Thailand", station: "Mukdahan",B_name:"", code:"MUK",Today: "",FlowThreshold:"",  Weekly: "", Trend: "",latitude: "16.58279991", longitude:"104.7331772" },
  { country: "Lao", station: "Savannakhet",B_name:"", code:"SAV",Today: "",FlowThreshold:"",  Weekly: "", Trend: "" ,latitude: "16.558", longitude:"104.744"},
  { country: "Thailand", station: "Khong Chiam",B_name:"", code:"KHO",Today: "",FlowThreshold:"",  Weekly: "", Trend: "" ,latitude: "15.32209015", longitude:"105.4934769"},
  { country: "Lao", station: "Pakse",B_name:"", code:"PKS",Today: "",FlowThreshold:"",  Weekly: "", Trend: "" ,latitude: "15.09976006", longitude:"105.8131866"},
  { country: "Cambodia", station: "Stung Treng",B_name:"", code:"STR",Today: "",FlowThreshold:"",  Weekly: "", Trend: "",latitude: "13.53250027", longitude:"105.9501877" },
  { country: "Cambodia", station: "Kratie",B_name:"", code:"KRA",Today: "",FlowThreshold:"",  Weekly: "", Trend: "" ,latitude: "12.48141003", longitude:"106.0176163"},
  { country: "Cambodia", station: "Kompong Cham",B_name:"", code:"KOM",Today: "",FlowThreshold:"",  Weekly: "", Trend: "",latitude: "11.994647", longitude:"105.468727" },
  { country: "Cambodia", station: "Phnom Penh (Bassac)",B_name:"", code:"PPB",Today: "",FlowThreshold:"",  Weekly: "", Trend: "",latitude: "11.568271", longitude:"104.9224426" },
  { country: "Cambodia", station: "Phnom Penh Port ",B_name:"", code:"PPP",Today: "",FlowThreshold:"",  Weekly: "", Trend: "",latitude: "11.57641", longitude:"104.92651" },
  { country: "Cambodia", station: "Koh Khel",B_name:"(Bassac)", code:"KOH",Today: "",FlowThreshold:"",  Weekly: "", Trend: "",latitude: "11.24203528", longitude:"105.0361553" },
  { country: "Cambodia", station: "Neak Luong",B_name:"", code:"NEA",Today: "",FlowThreshold:"",  Weekly: "", Trend: "",latitude: "11.26303677", longitude:"105.2801239" },
  { country: "Cambodia", station: "Prek Kdam",B_name:"(Tonel Sap)", code:"PRE",Today: "",FlowThreshold:"",  Weekly: "", Trend: "",latitude: "11.81116962", longitude:"104.806778" },
  { country: "Viet Nam", station: "Tan Chau",B_name:"", code:"TCH",Today: "",FlowThreshold:"",  Weekly: "", Trend: "" ,latitude: "10.80062008", longitude:"105.2480164", tooltip:true},
  { country: "Viet Nam", station: "Chau Doc",B_name:"", code:"CDO",Today: "",FlowThreshold:"",  Weekly: "", Trend: "",latitude: "10.7052803", longitude:"105.1335068", tooltip:true }
]

let countryList = ["China", "Cambodia", "Lao", "Thailand", "Viet Nam"]

export default function Home() {

  const [value, setValue] = React.useState('1');
  const [finalData, setFinalData] = React.useState(initialData);
  const [allData, setAllData] = React.useState(initialData);
  const [countrys , setCountry] = React.useState(countryList);
  
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
