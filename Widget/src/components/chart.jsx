import React, { useEffect, useState } from 'react';
import Highcharts from 'highcharts';
import HighchartsReact from 'highcharts-react-official';
import HighchartsMore from 'highcharts/highcharts-more';

if (typeof HighchartsMore === 'function') {
  HighchartsMore(Highcharts);
}

const ChartComponent = ({ data, selectedRow }) => {
  
  const [seriesList, setSeriesList] = useState([]);
  const [dateList, setDateList] = useState([]);
  const [startDate, setStartDate] = useState(null);
  const [endDate, setEndDate] = useState(null);
  const [optionsList, setOptionsList] = useState(null);
  
  useEffect(() => {
    if (data) {
      const { series, uniqueMonths, start, end } = parseData(data);
      console.log("uniqueMonths", uniqueMonths)
      // Get the last timestamp from the array
      const lastTimestamp = uniqueMonths[uniqueMonths.length - 1];

      // Create a Date object from the last timestamp
      const lastDate = new Date(lastTimestamp);

      // Add one month to the date
      lastDate.setUTCMonth(lastDate.getUTCMonth() + 1); // Adjust month

      // Set the date to the 1st day of the month (to maintain unique months)
      lastDate.setUTCDate(1);

      // Get the updated timestamp
      const nextMonthTimestamp = lastDate.getTime();

      // Add it to the uniqueMonths
      uniqueMonths.push(nextMonthTimestamp);
      setSeriesList(series);
      setDateList(uniqueMonths);
      setStartDate(start);
      setEndDate(end);

      generateChartOptions(series, uniqueMonths, start, end);
    }
  }, [data]);
  
//working

// const parseData = (data) => {
//     try {
//       const rows = data.trim().split('\n');
//       const columns = rows[0].split(',');
//       const parsedRows = rows.slice(1).map(row => row.split(','));

//       // Set Start and End Dates
//       const [startDay, startMonth, startYear] = parsedRows[0][0].split('-');
//       const start = Date.UTC(startYear, startMonth - 1, startDay);
//       const [endDay, endMonth, endYear] = parsedRows[parsedRows.length - 1][0].split('-');
//       const end = Date.UTC(endYear, endMonth - 1, endDay);

//       // Extract Dates
//       const dates = parsedRows.map(row => {
//         const [day, month, year] = row[0].split('-');
//         return Date.UTC(year, month - 1, day);
//       });

//       // Extract Series Data
//       const series = columns.slice(1).map((name, colIndex) => ({
//         name,
//         data: parsedRows.map((row, rowIndex) => [dates[rowIndex], parseFloat(row[colIndex + 1]) || null])
//       }));

//       // Generate Unique Month-Year Pairs
//       const uniqueMonths = [...new Set(dates.map(date => {
//         const tempDate = new Date(date);
//         return Date.UTC(tempDate.getUTCFullYear(), tempDate.getUTCMonth(), 1);
//       }))].sort((a, b) => a - b);

//       return { series, uniqueMonths, start, end };
//     } catch (error) {
//       console.error("Error parsing data: ", error);
//       return { series: [], uniqueMonths: [], start: null, end: null };
//     }
//   };

  const parseData = (data) => {
    try {
      const rows = data.trim().split('\n');
      const columns = rows[0].split(',');
      const parsedRows = rows.slice(1).map(row => row.split(','));

      // Set Start and End Dates
      const [startDay, startMonth, startYear] = parsedRows[0][0].includes('-') 
    ? parsedRows[0][0].split('-') 
    : parsedRows[0][0].split('/');
      const start = Date.UTC(startYear, startMonth - 1, startDay);
      const [endDay, endMonth, endYear] = parsedRows[parsedRows.length - 1][0].includes('-') 
    ? parsedRows[parsedRows.length - 1][0].split('-') 
    : parsedRows[parsedRows.length - 1][0].split('/');
      const end = Date.UTC(endYear, endMonth - 1, endDay);

      // Extract Dates
      const dates = parsedRows.map(row => {
        const [day, month, year] = row[0].includes('-') 
    ? row[0].split('-') 
    : row[0].split('/');
        return Date.UTC(year, month - 1, day);
      });

      // Extract Series Data
      const series = columns.slice(1).map((name, colIndex) => ({
        name,
        data: parsedRows.map((row, rowIndex) => [dates[rowIndex], parseFloat(row[colIndex + 1]) || null])
      }));

      // Generate Unique Month-Year Pairs
      const uniqueMonths = [...new Set(dates.map(date => {
        const tempDate = new Date(date);
        return Date.UTC(tempDate.getUTCFullYear(), tempDate.getUTCMonth(), 1);
      }))].sort((a, b) => a - b);

      return { series, uniqueMonths, start, end };
    } catch (error) {
      console.error("Error parsing data: ", error);
      return { series: [], uniqueMonths: [], start: null, end: null };
    }
  };

  const generateChartOptions = (series, uniqueMonths, start, end) => {
    console.log("Generating chart options with series:", series, series?.[13]);
    setOptionsList({
      title: {
        text: `Water Level in Dry Season at ${selectedRow?.station + " " + selectedRow?.B_name}, compared with its exceedance`,
      },
      credits: { enabled: false }, // Hide watermark
      subtitle: {
        text: `Probability (P%): 1961-${series?.[series.length - 1]?.name.split('-')[0] - 1}   (${Highcharts.dateFormat('%b, ', start)} ${series?.[series.length - 1]?.name.split('-')[0]} - ${Highcharts.dateFormat('%b, ', end)}${series?.[series.length - 1]?.name.split('-')[1]})`,
      },
      xAxis: {
        type: 'datetime',
        tickPositions: uniqueMonths,
        title: {
          text: `Observation period: ${Highcharts.dateFormat('%d %b', start)} to ${Highcharts.dateFormat('%d %b', end)}`
        },
        labels: {
          formatter: function () {
            return Highcharts.dateFormat('%b', this.value);
          }
        }
      },
      yAxis: {
        title: { text: 'Water Level (m)' }
      },
      tooltip: {
        shared: true, // Enables shared tooltip
        useHTML: true, // Allows applying custom HTML and CSS
        formatter: function () {
          let tooltipText = `<div style="border-radius: 4px; min-width: 290px; background-color: #ffffff;">`;
          tooltipText += `<p style="text-align: center; font-size: 12px; margin: 0 0 10px;">${Highcharts.dateFormat('%b %d', this.x)}</p>`;
          this.points.forEach(point => {
            tooltipText += `
              <div style="display: flex; justify-content: space-between; align-items: center; margin: 5px 0;">
                <span style="display: flex; align-items: center;">
                  
                  <span style="font-size: 12px; color: #333;">${point.series.name}</span>
                </span>
                <span style="font-size: 12px; color: #333;">${point?.high ? `${point.high} m - ${point.low} m` : `${point.y} m`} </span>

              </div>`;
          });
          tooltipText += `</div>`;
          return tooltipText;
        },
        style: {
          fontSize: '14px', // Tooltip font size
          color: '#333', // Tooltip text color
          fontFamily: 'Arial, sans-serif', // Tooltip font family
        },
      },
      

      // tooltip: {
      //   formatter: function () {
      //     return `${Highcharts.dateFormat('%b %d, %Y', this.x)}<br>${this.series.name}: ${this.y}`;
      //   }
      // },
      series: [
        { name: '2025-2026', data: series?.[12]?.data, visible: true, color: "#002469" },
        { name: '2024-2025', data: series?.[11]?.data, visible: false, color: "#03ac03ff" },
        { name: '2023-2024', data: series?.[10]?.data, visible: false, color: "#0b91a7" },
        { name: '2022-2023', data: series?.[9]?.data, visible: false, color: "#f6d500" },
        { name: `Average (1961 - ${series?.[series.length - 1]?.name.split('-')[1]})`, data: series?.[5]?.data, visible: true, color: "#ba403e" },
        { name: 'P80 = 1:5 years', data: series?.[0]?.data, visible: false, color: "#aaf3c7" },
        { name: 'P90 - 1.5 years', data: series?.[1]?.data, visible: false, color: "#476593" },
        { name: 'P95 = 1:20 years', data: series?.[2]?.data, visible: false, color: "#c2c100" },
        {
          name: `Fluctuation (1961 - ${series?.[series.length - 1]?.name.split('-')[1]})`,
          data: series?.[3]?.data?.map((point, index) => {
            const max = point[1]; // Assuming series[3].data is in [x, y] format
            const low = series?.[4]?.data?.[index]?.[1] || 0; // Assuming series[4].data is in [x, y] format
            const x = point[0]; // Use the same timestamp as series[3]
            return [x, low, max];
          }),
          type: "arearange",
          color: "rgba(0, 128, 255, 0.3)",

        }
        // { name: 'Fluctuation Min', data:series?.[4]?.data, visible: true, color: "#2caffe" },
        // { name: 'Fluctuation Max', data: series?.[3]?.data, visible: true, color: "#2caffe" }
      ],
      plotOptions: {
        series: {
          showInLegend: true, // Allows toggling visibility in legend
          marker: { enabled: false } // Optional: hide data markers for cleaner lines
        }
      }
    });
  };

  return optionsList ? (
    <HighchartsReact highcharts={Highcharts} options={optionsList} />
  ) : (
    <div>Loading chart...</div>
  );
};

export default ChartComponent;