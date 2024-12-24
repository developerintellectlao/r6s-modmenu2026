import React from "react";
import { Box, Typography, CircularProgress, Paper, Tooltip } from "@mui/material";

const RainInfoCard = ({ title, mmSize, value, circleBgcolor, circleFilledColor,length }) => {
  return (
    <Paper className="shadow"
      sx={{
        width: "50%",
        md: { width: "50%" },
        height: "188px",
        mt: .5,
        borderRadius: 0,
        boxShadow: "var(--tw-ring-offset-shadow, 0 0 #0000), var(--tw-ring-shadow, 0 0 #0000), var(--tw-shadow)",
        backgroundColor: "white",
        textAlign: "center",
        pt:0.5,
        pl:2, 
        pb:0.5,
        pr:2
      }}
    >
      <Typography variant="h6" sx={{ mb: 0.5, fontSize:"20px"}}>
        {title}
      </Typography>
      <Typography variant="body2" color="text.secondary">
        {mmSize}
      </Typography>

      {/* Circle progress */}
      <Tooltip
        arrow
        title={`${value} of ${length} stations (${parseInt(Math.round(value *100/length))}%)`}
        placement="top"
        slotProps={{
          tooltip: {
            sx: {
              fontSize: '12px',
              fontWeight:"600",
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
        <Box
          sx={{
            position: "relative",
            display: "inline-block",
            width: 100,
            height: 100,
            mt: 2,
          }}
        >
          <CircularProgress
            variant="determinate"
            value={100} // Full circle
            sx={{
              color: circleBgcolor,
              position: "absolute",
              top: 0,
              left: 0,

            }}
            size={100}
            thickness={6}
          />
          <CircularProgress
            variant="determinate"
            value={value} // Percentage to show
            sx={{
              color: circleFilledColor, // Red color
              position: "absolute",
              top: 0,
              left: 0,
              transform: "rotate(-90deg)",
            }}
            size={100}
            thickness={6}
          />
          <Box
            sx={{
              position: "absolute",
              top: "50%",
              left: "50%",
              transform: "translate(-50%, -50%)",
            }}
          >
            <Typography variant="h4" sx={{ fontSize: "1.875rem" }}>
              {value}
            </Typography>
          </Box>
        </Box>
      </Tooltip>
    </Paper>
  );
};

export default RainInfoCard;
