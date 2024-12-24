import { useRef, useEffect } from 'react';
import mapboxgl from 'mapbox-gl';
import 'mapbox-gl/dist/mapbox-gl.css';
import { getColorForMapIcon } from '@/common/utility';

function MapCanvas({ stations, selectedRow, handelClick }) {
    const mapRef = useRef(null);
    const markersRef = useRef([]);
    mapboxgl.accessToken = process.env.MAPBOX_TOKEN;

    // Initialize the map
    useEffect(() => {
        if (!mapRef.current) {
            mapRef.current = new mapboxgl.Map({
                container: 'map-container',
                style: 'https://basemaps.cartocdn.com/gl/positron-gl-style/style.json',
                center: [103.4, 15.6],
                zoom: 4.9,
                scrollZoom: false,
                attributionControl: false,
            });

            mapRef.current.addControl(new mapboxgl.NavigationControl({showCompass: false }), 'top-right');

            mapRef.current.on('style.load', () => {
                // Add river layer
                mapRef.current.addSource('mekong-river', {
                    type: 'geojson',
                    data: '/export.geojson',
                });

                mapRef.current.addLayer({
                    id: 'mekong-river-layer',
                    type: 'line',
                    source: 'mekong-river',
                    layout: {
                        'line-join': 'round',
                        'line-cap': 'round',
                    },
                    paint: {
                        'line-color': '#2c6995',
                        'line-width': 1,
                    },
                });

                // Add boundary layer
                mapRef.current.addSource('lmb_boundry', {
                    type: 'geojson',
                    data: '/boundry.geojson',
                });

                mapRef.current.addLayer({
                    id: 'lmb_boundry-layer',
                    type: 'line',
                    source: 'lmb_boundry',
                    layout: {
                        'line-join': 'round',
                        'line-cap': 'round',
                    },
                    paint: {
                        'line-color': '#8e8e8d',
                        'line-width': 1,
                    },
                });
            });
        }

        return () => {
            if (mapRef.current) {
                mapRef.current.remove();
                mapRef.current = null;
            }
        };
    }, []);

    // Update markers based on stations and selectedRow
    useEffect(() => {
        // Remove existing markers
        markersRef.current.forEach(marker => marker.remove());
        markersRef.current = [];

        stations.forEach(row => {
            // Create a custom marker element
            const markerElement = document.createElement('div');
            markerElement.className = 'custom-marker-container';

            // Label
            const label = document.createElement('span');
            label.className = 'marker-label';
            label.style.color = '#0a0a0a';
            markerElement.appendChild(label);

            // SVG for the marker
            const svgColor = getColorForMapIcon(row.Today);
            const svg = `
                <svg class="_animate-ping" height="${selectedRow?.station === row.station ? 26 : 16}" viewBox="0 0 36 36" stroke="blue" stroke-width="1" style="cursor: pointer; fill: ${svgColor}; stroke: blue;">
                    <path id="Shape" d="M18,0C25.732,0 32,5.641 32,12.6C32,23.963 18,36 18,36C18,36 4,24.064 4,12.6C4,5.641 10.268,0 18,0Z" stroke-linecap="round" stroke-linejoin="round"></path>
                    <circle class="animate-bounce" id="Oval" cx="18" cy="20" r="5" style="cursor: pointer; fill: rgb(30, 58, 138); stroke: black;"></circle>
                </svg>`;

            const svgWrapper = document.createElement('div');
            svgWrapper.className = 'custom-svg-wrapper';
            svgWrapper.innerHTML = svg;
            markerElement.appendChild(svgWrapper);

            markerElement.onclick = () => handelClick(row);

            // Add marker to the map
            const marker = new mapboxgl.Marker(markerElement)
                .setLngLat([parseFloat(row.longitude), parseFloat(row.latitude)])
                .addTo(mapRef.current);

            markersRef.current.push(marker);
        });
    }, [stations, selectedRow, handelClick]);

    // Add station labels as a GeoJSON layer
    useEffect(() => {
        if (mapRef.current) {
            mapRef.current.on('style.load', () => {
            const stationsGeoJson = {
                type: 'FeatureCollection',
                features: stations.map(station => ({
                    type: 'Feature',
                    properties: { name: station.station },
                    geometry: {
                        type: 'Point',
                        coordinates: [parseFloat(station.longitude), parseFloat(station.latitude)],
                    },
                })),
            };

            if (mapRef.current.getSource('stations')) {
                mapRef.current.getSource('stations').setData(stationsGeoJson);
            } else {
                mapRef.current.addSource('stations', {
                    type: 'geojson',
                    data: stationsGeoJson,
                });

                mapRef.current.addLayer({
                    id: 'station-labels',
                    type: 'symbol',
                    source: 'stations',
                    layout: {
                        'text-field': ['get', 'name'],
                        'text-size': 12,
                        'text-font': ['Open Sans Regular', 'Arial Unicode MS Regular'],
                        'text-anchor': 'top',
                        'text-offset': [0, 0.6],
                        'text-allow-overlap': false,
                    },
                    paint: {
                        'text-color': 'rgb(0, 0, 0)',
                        // 'font-size':"12px"
                        // 'text-halo-color': '#ffffff',
                        // 'text-halo-width': 1,
                    },
                });
            }
        })
        }
    }, [stations]);

    return <div id="map-container"  />;
}

export default MapCanvas;


// import { useRef, useEffect } from 'react'
// import mapboxgl from 'mapbox-gl'
// import 'mapbox-gl/dist/mapbox-gl.css';



// function MapCanvas({ stations, selectedRow, handelClick, riverArea }) {
//     const mapRef = useRef()
//     // const mapContainerRef = useRef()
//     const markersRef = useRef([]);
//     mapboxgl.accessToken = process.env.MAPBOX_TOKEN;
   
//     useEffect(() => {
//         // Initialize the map only once
//         if (!mapRef.current){// && riverArea?.features?.length > 0) {
//             mapRef.current = new mapboxgl.Map({
//                 container: 'map-container',
//                 style: 'https://basemaps.cartocdn.com/gl/positron-gl-style/style.json',
//                 center: [103.4, 15.6],
//                 zoom: 5,
//                 attributionControl: false
//             });

//             // Add navigation controls
//             const navControl = new mapboxgl.NavigationControl();
//             mapRef.current.addControl(navControl, 'top-right');

//             // Add river and border layers
//             mapRef.current.on('load', () => {
//                 mapRef.current.addSource('mekong-river', {
//                     type: 'geojson',
//                     data: "/export.geojson", 
//                 });

//                 mapRef.current.addLayer({
//                     id: 'mekong-river-layer',
//                     type: 'line',
//                     source: 'mekong-river',
//                     layout: {
//                         'line-join': 'round',
//                         'line-cap': 'round',
//                     },
//                     paint: {
//                         'line-color': '#2c6995',
//                         'line-width': 1,
//                     },
//                 });

//                 mapRef.current.addSource('lmb_boundry', {
//                     type: 'geojson',
//                     data: "/boundry.geojson",
//                 });

//                 mapRef.current.addLayer({
//                     id: 'lmb_boundry-layer',
//                     type: 'line',
//                     source: 'lmb_boundry',
//                     layout: {
//                         'line-join': 'round',
//                         'line-cap': 'round',
//                     },
//                     paint: {
//                         'line-color': '#8e8e8d',
//                         'line-width': 1,
//                     },
//                 });
//             });
//         }

//         // Cleanup markers when component unmounts
//         return () => {
//             markersRef.current.forEach(marker => marker.remove());
//             markersRef.current = [];
//         };
//     }, []);

//     // useEffect(() => {
//     //     // Clear old markers
//     //     markersRef.current.forEach(marker => marker.remove());
//     //     markersRef.current = [];
    
//     //     // Create GeoJSON for station labels
//     //     const stationsGeoJson = {
//     //         type: 'FeatureCollection',
//     //         features: stations.map(station => ({
//     //             type: 'Feature',
//     //             properties: { name: station.station },
//     //             geometry: {
//     //                 type: 'Point',
//     //                 coordinates: [parseFloat(station.longitude), parseFloat(station.latitude)],
//     //             },
//     //         })),
//     //     };
    
//     //     // Add or update GeoJSON source for station labels
//     //     if (mapRef.current.getSource('stations')) {
//     //         mapRef.current.getSource('stations').setData(stationsGeoJson);
//     //     } else {
//     //         mapRef.current.addSource('stations', {
//     //             type: 'geojson',
//     //             data: stationsGeoJson,
//     //         });
    
//     //         mapRef.current.addLayer({
//     //             id: 'station-labels',
//     //             type: 'symbol',
//     //             source: 'stations',
//     //             layout: {
//     //                 'text-field': ['get', 'name'], // Station name
//     //                 'text-size': 12,
//     //                 'text-font': ['Open Sans Regular', 'Arial Unicode MS Regular'],
//     //                 'text-anchor': 'top',
//     //                 'text-offset': [0, 0.6], // Offset the text to avoid overlap with markers
//     //                 'text-allow-overlap': false, // Prevent label overlap
//     //             },
//     //             paint: {
//     //                 'text-color': '#0a0a0a', // Label color
//     //                 'text-halo-color': '#ffffff', // Add a white halo for better visibility
//     //                 'text-halo-width': 1,
//     //             },
//     //         });
//     //     }
    
//     //     // Add custom markers for each station
//     //     stations.forEach(row => {
//     //         // Create a container div for the marker
//     //         const markerElement = document.createElement('div');
//     //         markerElement.className = 'custom-marker-container';
    
//     //         // Create an SVG element for the marker
//     //         const svgColor = getColorForMapIcon(row.Today);
//     //         const svg = `
//     //             <svg class="_animate-ping"
//     //                  height="${selectedRow?.station === row.station ? 26 : 16}"
//     //                  viewBox="0 0 36 36"
//     //                  stroke="blue"
//     //                  stroke-width="1"
//     //                  style="cursor: pointer; fill: ${svgColor}; stroke: blue;">
//     //                 <path id="Shape"
//     //                       d="M18,0C25.732,0 32,5.641 32,12.6C32,23.963 18,36 18,36C18,36 4,24.064 4,12.6C4,5.641 10.268,0 18,0Z"
//     //                       stroke-linecap="round"
//     //                       stroke-linejoin="round"></path>
//     //                 <circle class="animate-bounce"
//     //                         id="Oval"
//     //                         cx="18" cy="20" r="5"
//     //                         style="cursor: pointer; fill: rgb(30, 58, 138); stroke: black;"></circle>
//     //             </svg>`;
//     //         const svgWrapper = document.createElement('div');
//     //         svgWrapper.className = 'custom-svg-wrapper';
//     //         svgWrapper.innerHTML = svg;
//     //         markerElement.appendChild(svgWrapper);
    
//     //         // Add click handler for the marker
//     //         markerElement.onclick = () => {
//     //             handelClick(row);
//     //         };
    
//     //         // Add the marker to the map
//     //         const marker = new mapboxgl.Marker(markerElement)
//     //             .setLngLat([parseFloat(row.longitude), parseFloat(row.latitude)])
//     //             .addTo(mapRef.current);
    
//     //         markersRef.current.push(marker);
//     //     });
//     // }, [stations, selectedRow]);
    

//     useEffect(() => {
//         // Update markers whenever stations or selectedRow changes
//         markersRef.current.forEach(marker => marker.remove());
//         markersRef.current = [];

//         stations.forEach(row => {
//             // Create a container div for the marker
//             const markerElement = document.createElement('div');
//             markerElement.className = 'custom-marker-container';

//             // Create a label for the place name
//             const label = document.createElement('span');
//             label.className = 'marker-label';
//             label.style.color ="#0a0a0a"
//             // label.innerText = row.station + row.B_name; // Set the place name as the label text
//             // label.style.transform = `translate(-50%, -50%) translate(${Math.random() * 20}px, ${Math.random() * 35}px)`;
//             markerElement.appendChild(label);

//             // Create an SVG element for the marker
//             const svgColor = getColorForMapIcon(row.Today);
//             const svg = `
//                 <svg class="_animate-ping"
//                      height="${selectedRow?.station === row.station ? 26 : 16}"
//                      viewBox="0 0 36 36"
//                      stroke="blue"
//                      stroke-width="1"
//                      style="cursor: pointer; fill: ${svgColor}; stroke: blue;">
//                     <path id="Shape"
//                           d="M18,0C25.732,0 32,5.641 32,12.6C32,23.963 18,36 18,36C18,36 4,24.064 4,12.6C4,5.641 10.268,0 18,0Z"
//                           stroke-linecap="round"
//                           stroke-linejoin="round"></path>
//                     <circle class="animate-bounce"
//                             id="Oval"
//                             cx="18" cy="20" r="5"
//                             style="cursor: pointer; fill: rgb(30, 58, 138); stroke: black;"></circle>
//                 </svg>`;
//             const svgWrapper = document.createElement('div');
//             svgWrapper.className = 'custom-svg-wrapper';
//             svgWrapper.innerHTML = svg;
//             markerElement.appendChild(svgWrapper);

//             markerElement.onclick = () => {
//                 handelClick(row)
//             };

//             // Add the marker to the map
//             const marker = new mapboxgl.Marker(markerElement)
//                 .setLngLat([parseFloat(row.longitude), parseFloat(row.latitude)])
//                 .addTo(mapRef.current);

//             markersRef.current.push(marker);
//         });
//     }, [stations, selectedRow]);

//     useEffect(() => {
//         if (mapRef.current) {
//             const stationsGeoJson = {
//                 type: 'FeatureCollection',
//                 features: stations.map(station => ({
//                     type: 'Feature',
//                     properties: { name: station.station },
//                     geometry: {
//                         type: 'Point',
//                         coordinates: [parseFloat(station.longitude), parseFloat(station.latitude)],
//                     },
//                 })),
//             };
    
//             if (mapRef.current.getSource('stations')) {
//                 mapRef.current.getSource('stations').setData(stationsGeoJson);
//             } else {
//                 mapRef.current.addSource('stations', {
//                     type: 'geojson',
//                     data: stationsGeoJson,
//                 });
    
//                 mapRef.current.addLayer({
//                     id: 'station-labels',
//                     type: 'symbol',
//                     source: 'stations',
//                     layout: {
//                         'text-field': ['get', 'name'], // Station name
//                         'text-size': 12,
//                         'text-font': ['Open Sans Regular', 'Arial Unicode MS Regular'],
//                         'text-anchor': 'top',
//                         'text-offset': [0, 0.6], // Offset the text to avoid overlap with markers
//                         'text-allow-overlap': false, // Prevent label overlap
//                     },
//                     paint: {
//                         'text-color': '#0a0a0a', // Label color
//                         'text-halo-color': '#ffffff', // Add a white halo for better visibility
//                         'text-halo-width': 1,
//                     },
//                 });
//             }
//         }
//     }, [stations]);
    

//     return (
//         <>
//             <div id='map-container' />
//         </>
//     )
// }

// export default MapCanvas