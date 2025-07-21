import { useRef, useEffect } from 'react'
import mapboxgl from 'mapbox-gl'
import 'mapbox-gl/dist/mapbox-gl.css';

function MapCanvas({ flashFloodMapData, selectedHoursOnMap, setSelectedHoursOnMap }) {
    const mapRef = useRef();
    const markersRef = useRef([]);
    mapboxgl.accessToken = process.env.MAPBOX_TOKEN;

    // Initialize map once
    useEffect(() => {
        if (!mapRef.current) {
            const map = new mapboxgl.Map({
                container: 'map-container',
                style: 'https://basemaps.cartocdn.com/gl/positron-gl-style/style.json',
                center: [103.4, 15.6],
                zoom: 5,
                scrollZoom: false,
                attributionControl: false
            });

            mapRef.current = map;

            map.addControl(new mapboxgl.NavigationControl({ showCompass: false }), 'top-right');

            map.on('load', () => {
                // Add static layers
                map.addSource('mekong-river', {
                    type: 'geojson',
                    data: "/export.geojson",
                });
                map.addLayer({
                    id: 'mekong-river-layer',
                    type: 'line',
                    source: 'mekong-river',
                    layout: { 'line-join': 'round', 'line-cap': 'round' },
                    paint: { 'line-color': '#2c6995', 'line-width': 1 },
                });

                map.addSource('lmb_boundry', {
                    type: 'geojson',
                    data: "/boundry.geojson",
                });
                map.addLayer({
                    id: 'lmb_boundry-layer',
                    type: 'line',
                    source: 'lmb_boundry',
                    layout: { 'line-join': 'round', 'line-cap': 'round' },
                    paint: { 'line-color': '#8e8e8d', 'line-width': 1 },
                });

                // ✅ Always add flash flood source with empty fallback
                map.addSource('geojson-source', {
                    type: 'geojson',
                    data: { type: 'FeatureCollection', features: [] },
                });

                map.addLayer({
                    id: 'colored-layer',
                    type: 'fill',
                    source: 'geojson-source',
                    paint: {
                        'fill-color': [
                            'match',
                            ['to-string', ['get', 'Level_of_F']],
                            'Low-Risk', '#90ee90',
                            'Moderate-Risk', '#ffff01',
                            'High-Risk', '#ec0015',
                            'grey'
                        ],
                        'fill-opacity': 1,
                    }
                });

                const popup = new mapboxgl.Popup({
                    closeButton: false,
                    closeOnClick: false,
                });

                map.on('mousemove', 'colored-layer', (e) => {
                    const feature = e.features[0];
                    const { Level_of_F, PNAME, DNAME } = feature.properties;

                    let colorName = 'Unknown';
                    if (Level_of_F === "Low-Risk") colorName = 'Low';
                    else if (Level_of_F === "Moderate-Risk") colorName = 'Moderate';
                    else if (Level_of_F === "High-Risk") colorName = 'High';

                    popup.setLngLat(e.lngLat)
                        .setHTML(`
                            <div style="color: #2e2e2f;">
                                CDI: <strong>${colorName}</strong><br />
                                District: <strong>${DNAME}</strong><br />
                                Province: <strong>${PNAME}</strong>
                            </div>
                        `)
                        .addTo(map);
                });

                map.on('mouseleave', 'colored-layer', () => {
                    popup.remove();
                });
            });
        }

        // Cleanup
        return () => {
            markersRef.current.forEach(marker => marker.remove());
            markersRef.current = [];
        };
    }, []);

    // ✅ Update geojson data when flashFloodMapData changes
    useEffect(() => {
        console.log("flashFloodMapData", flashFloodMapData)
        const map = mapRef.current;
        if (!map) return;

        const updateSource = () => {
            const source = map.getSource('geojson-source');
            if (source) {
                source.setData(
                    flashFloodMapData && Array.isArray(flashFloodMapData.features)
                        ? flashFloodMapData
                        : { type: "FeatureCollection", features: [] }
                );
            } else {
                console.warn("GeoJSON source not found yet");
            }
        };

        if (map.isStyleLoaded()) {
            updateSource();
        } else {
            map.on('load', updateSource);
            return () => map.off('load', updateSource);
        }
    }, [flashFloodMapData]);

    return (
        <div id="map-container" className="map-container">
            <div className="map-controls">
                {['1', '3', '6'].map(hour => (
                    <button
                        key={hour}
                        style={{
                            color: "#000000",
                            backgroundColor: selectedHoursOnMap === hour ? "#f0f0f0" : "transparent"
                        }}
                        onClick={() => setSelectedHoursOnMap(hour)}
                    >
                        {hour}hr
                    </button>
                ))}
            </div>
        </div>
    );
}

export default MapCanvas;
