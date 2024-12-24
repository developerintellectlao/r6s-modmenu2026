import { useRef, useEffect } from 'react'
import mapboxgl from 'mapbox-gl'
import 'mapbox-gl/dist/mapbox-gl.css';
import { getColorForMapIcon } from '@/common/utility';

function MapCanvas({ stations, selectedRow, handelClick }) {
    const mapRef = useRef()
    // const mapContainerRef = useRef()
    const markersRef = useRef([]);
    mapboxgl.accessToken = process.env.MAPBOX_TOKEN;
    
    useEffect(() => {
        
        // Initialize the map only once
        if (!mapRef.current) {
            mapRef.current = new mapboxgl.Map({
                container: 'map-container',
                style: 'https://basemaps.cartocdn.com/gl/positron-gl-style/style.json',
                center: [103.4, 15.6],
                zoom: 5,
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
            });
        }

        // Cleanup markers when component unmounts
        return () => {
            markersRef.current.forEach(marker => marker.remove());
            markersRef.current = [];
        };
    }, []);

    

    const handleButtonClick = () => {
        
    }

    return (
        <>

            <div id="map-container" className="map-container">
                <div className="map-controls">
                    <button style={{color:"#000000"}} onClick={() => handleButtonClick('1hr')}>1hr</button>
                    <button style={{color:"#000000"}}  onClick={() => handleButtonClick('3hr')}>3hr</button>
                    <button style={{color:"#000000"}} onClick={() => handleButtonClick('6hr')}>6hr</button>
                </div>
            </div>
        </>
    )
}

export default MapCanvas