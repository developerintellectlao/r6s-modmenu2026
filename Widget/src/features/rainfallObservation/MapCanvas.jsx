import { useRef, useEffect } from 'react'
import mapboxgl from 'mapbox-gl'
import 'mapbox-gl/dist/mapbox-gl.css';

function MapCanvas({ data }) {
    const mapRef = useRef()
    // const mapContainerRef = useRef()
    const markersRef = useRef([]);
    mapboxgl.accessToken = process.env.MAPBOX_TOKEN;

    useEffect(() => {
        // Initialize the map only once
        if (!mapRef.current && data?.features?.length > 0) {
            mapRef.current = new mapboxgl.Map({
                container: 'map-container',
                style: 'https://basemaps.cartocdn.com/gl/positron-gl-style/style.json',
                center: [103.4, 15.6],
                zoom: 4.8,
                scrollZoom: false,
                attributionControl: false
            });

            // Add navigation controls
            mapRef.current.addControl(new mapboxgl.NavigationControl({showCompass: false }), 'top-right');

            // Add river and border layers
            mapRef.current.on('load', async () => {
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

                // Load an image and add it to the map
                function loadImageAndAddToMap(map, id, url) {
                    const image = new Image();
                    image.onload = () => {
                        map.addImage(id, image);
                    };
                    image.onerror = (err) => {
                        console.error(`Error loading image: ${url}`, err);
                    };
                    image.src = url;
                }

                // Add images to the map
                loadImageAndAddToMap(mapRef.current, 'no-rainfall', '/rainfallIcon/no-rainfall.svg');
                loadImageAndAddToMap(mapRef.current, 'rainfall', '/rainfallIcon/rainfall.svg');
                loadImageAndAddToMap(mapRef.current, 'rainfall-yellow', '/rainfallIcon/rainfall-yellow.svg');
                loadImageAndAddToMap(mapRef.current, 'rain-orange', '/rainfallIcon/rain-orange.svg');
                loadImageAndAddToMap(mapRef.current, 'rain-red', '/rainfallIcon/rain-red.svg');
                loadImageAndAddToMap(mapRef.current, 'nodata', '/rainfallIcon/nodata.svg');

                mapRef.current.addSource('points', {
                    type: 'geojson',
                    data: data,
                    
                });


                mapRef.current.addLayer({
                    id: 'points-layer',
                    type: 'symbol',
                    source: 'points',
                    layout: {
                        'icon-image': [
                            'case',
                            ['==', ['get', 'mm'], 0], 'no-rainfall',       // mm = 0
                            ['all', ['>', ['get', 'mm'], 0], ['<=', ['get', 'mm'], 10]], 'rainfall', //  0 < mm <= 10
                            ['all', ['>', ['get', 'mm'], 10], ['<=', ['get', 'mm'], 35]], 'rainfall-yellow', // 10 < mm <= 35
                            ['all', ['>', ['get', 'mm'], 35], ['<=', ['get', 'mm'], 90]], 'rain-orange', // 35 < mm <= 90
                            ['>', ['get', 'mm'], 90], 'rain-red',          // mm > 90
                            ['<', ['get', 'mm'], 0], 'nodata',           //  mm < 0
                            'nodata',
                        ],
                        'icon-size': 0.5,
                        'icon-allow-overlap': true, // Allow icons to overlap
                        'icon-ignore-placement': true,
                    },
                    minzoom: 0
                });

                

                // mapRef.current.addLayer({
                //     id: 'points-layer',
                //     type: 'circle',
                //     source: 'points',
                //     paint: {
                //         'circle-radius': 6,
                //         'circle-color': [
                //             'case',
                //             ['==', ['get', 'mm'], 0], '#B3BEC3', // Orange for mm = 0
                //             ['all', ['>', ['get', 'mm'], 0], ['<=', ['get', 'mm'], 10]], '#3598DB', // Blue for 0 < mm <= 10
                //             ['all', ['>', ['get', 'mm'], 10], ['<=', ['get', 'mm'], 35]], '#F0C40F', // Green for 10 < mm <= 35
                //             ['all', ['>', ['get', 'mm'], 35], ['<=', ['get', 'mm'], 90]], '#E57E22', // Purple for 35 < mm <= 90
                //             ['>', ['get', 'mm'], 90], '#E74C3C', // Red for mm > 90
                //             ['<', ['get', 'mm'], 0], '#B3BEC3', // Bright red for mm < 0
                //             '#808080', // Default gray for no data
                //         ],
                //         'circle-stroke-width': 1,
                //         'circle-stroke-color': '#ffffff',
                //     },
                // });


                // Add popup on click
                mapRef.current.on('click', 'points-layer', (e) => {
                    const coordinates = e.features[0].geometry.coordinates.slice();
                    const properties = e.features[0].properties;

                    new mapboxgl.Popup()
                        .setLngLat(coordinates)
                        .setHTML(`<strong>${properties.StaName}</strong><br>mm: ${properties.mm}`)
                        .addTo(mapRef.current);
                });

                // Change cursor to pointer on hover
                mapRef.current.on('mouseenter', 'points-layer', () => {
                    mapRef.current.getCanvas().style.cursor = 'pointer';
                });
                mapRef.current.on('mouseleave', 'points-layer', () => {
                    mapRef.current.getCanvas().style.cursor = '';
                });



            });
        }

        // Cleanup markers when component unmounts
        return () => {
            // markersRef.current.forEach(marker => marker.remove());
            markersRef.current = [];
        };
    }, [data]);


    return (
        <>
            <div id='map-container' />
        </>
    )
}

export default MapCanvas