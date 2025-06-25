import { useRef, useEffect } from 'react'
import mapboxgl from 'mapbox-gl'
import 'mapbox-gl/dist/mapbox-gl.css';

function MapCanvas({ droughtGeoJson }) {
    const mapRef = useRef()
    // const mapContainerRef = useRef()
    const markersRef = useRef([]);
    // mapboxgl.accessToken = 'pk.eyJ1IjoiaGVtYW50MTIzMTExMSIsImEiOiJjbTQ5dnJ5M2YwMHl2MmpyNHEzZ29wem5nIn0.ilRi5khhJROtYfAcL6GuWQ'
    mapboxgl.accessToken = process.env.MAPBOX_TOKEN;
    
    useEffect(() => {
        // Initialize the map only once
        if (!mapRef.current) {
            mapRef.current = new mapboxgl.Map({
                container: 'map-container',
                style: 'https://basemaps.cartocdn.com/gl/positron-gl-style/style.json',
                center: [103.4, 15.6],
                zoom: 4.9,
                scrollZoom: false,
                attributionControl: false
            });

            // Add navigation controls
            mapRef.current.addControl(new mapboxgl.NavigationControl({showCompass: false }), 'top-right');

            // Add river and border layers
            mapRef.current.on('load', () => {
                mapRef.current.addSource('mekong-river', {
                    type: 'geojson',
                    data: "/export.geojson",
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

                mapRef.current.addSource('lmb_boundry', {
                    type: 'geojson',
                    data: "/boundry.geojson",
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

                 // Add fill layer for yellow areas
            mapRef.current.addSource('geojson-source', {
                type: 'geojson',
                data: droughtGeoJson, 
            });

             // Add fill layer with conditional colors based on gridcode
             mapRef.current.addLayer({
                id: 'colored-layer',
                type: 'fill',
                source: 'geojson-source',
                paint: {
                    // Conditional styling for gridcode
                    'fill-color': [
                        'match',
                        ['get', 'gridcode'], // Check the 'gridcode' property
                        1, '#FCE254',        // Yellow for gridcode 1
                        2, '#FCCBCA',          // pink for gridcode 2
                        3, '#FD2D2D',          // red for gridcode 3
                        4,'#8F5032',       // brown for gridcode 4
                        '#cccccc'            // Default color if no match
                    ],
                    'fill-opacity': 1,
                },
            });

            // Add a popup
            const popup = new mapboxgl.Popup({
                closeButton: false,
                closeOnClick: false,
            });

            mapRef.current.on('mousemove', 'colored-layer', (e) => {
                // Get the hovered feature
                const feature = e.features[0];
                const gridcode = feature.properties.gridcode;
                const provName = feature.properties.PROVNAME;

                // Determine the color name based on gridcode
                let colorName = 'Unknown';
                if (gridcode === 1) colorName = 'Moderate Drought';
                else if (gridcode === 2) colorName = 'Severe Drought';
                else if(gridcode === 3)colorName = 'Extreme Drought';
                else if(gridcode === 4)colorName = 'Exceptional Drought';

                // Set the popup content
                popup.setLngLat(e.lngLat)
                    .setHTML(`<div style="color: #2e2e2f;">
                        CDI: <strong>${colorName}</strong><br />
                        Province: <strong>${provName}</strong></div>
                    `)
                    .addTo(mapRef.current);
            });

            mapRef.current.on('mouseleave', 'colored-layer', () => {
                popup.remove();
            });
       




            });
        }

        // Cleanup markers when component unmounts
        return () => {
            markersRef.current.forEach(marker => marker.remove());
            markersRef.current = [];
        };
    }, []);

    return (
        <>
            <div id="map-container" />
        </>
    )
}

export default MapCanvas