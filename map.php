<?php
$package_id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Real-Time Package Tracker</title>
    
    <!-- 1. Include Google Fonts & Leaflet CSS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    
    <!-- 2. Leaflet JS CDN -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <style>
        :root {
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --dark: #0f172a;
            --light: #f8fafc;
            --border: #e2e8f0;
            --text-secondary: #64748b;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--light);
            color: var(--dark);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .container {
            max-width: 1000px;
            width: 90%;
            margin: 40px auto;
        }

        .tracker-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
            border: 1px solid var(--border);
            overflow: hidden;
        }

        .card-header {
            padding: 24px 32px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
        }

        .header-title-wrapper {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .icon-box {
            width: 48px;
            height: 48px;
            background-color: #eff6ff;
            color: var(--primary);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        .header-title-wrapper h1 {
            font-size: 1.25rem;
            font-weight: 700;
            margin: 0;
            color: var(--dark);
        }

        .status-badge {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            background-color: #dcfce7;
            color: #166534;
            padding: 6px 12px;
            border-radius: 100px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .status-pulse {
            width: 8px;
            height: 8px;
            background-color: #15803d;
            border-radius: 50%;
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(0.9); opacity: 1; }
            50% { transform: scale(1.3); opacity: 0.5; }
            100% { transform: scale(0.9); opacity: 1; }
        }

        #map-layer {
            width: 100%;
            height: 550px;
            background-color: #e5e3df;
        }

        .card-footer {
            padding: 20px 32px;
            background-color: #fafafa;
            border-top: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background-color: #ffffff;
            color: var(--dark);
            text-decoration: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.875rem;
            border: 1px solid var(--border);
            transition: all 0.2s ease;
        }

        .btn-back:hover {
            background-color: #f1f5f9;
            border-color: #cbd5e1;
            transform: translateY(-1px);
        }

        .info-text {
            font-size: 0.85rem;
            color: var(--text-secondary);
            margin: 0;
        }

        @media (max-width: 600px) {
            .card-header {
                padding: 16px 20px;
            }
            #map-layer {
                height: 400px;
            }
            .card-footer {
                padding: 16px 20px;
                flex-direction: column;
                gap: 12px;
                text-align: center;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="tracker-card">
            
            <!-- Card Header -->
            <div class="card-header">
                <div class="header-title-wrapper">
                    <div class="icon-box">
                        <i class="fa-solid fa-truck-ramp-box"></i>
                    </div>
                    <div>
                        <h1>Live Route Map</h1>
                        <p class="text-muted small mb-0" style="font-size: 0.8rem; color: var(--text-secondary);">ID: #<?php echo htmlspecialchars($package_id); ?></p>
                    </div>
                </div>
                
                <span class="status-badge">
                    <span class="status-pulse"></span>
                    Live Tracking Active
                </span>
            </div>

            <!-- Map Layer -->
            <div id="map-layer"></div>

            <!-- Card Footer -->
            <div class="card-footer">
                <a href="track.php" class="btn-back">
                    <i class="fa-solid fa-arrow-left"></i> Back to Search
                </a>
                <p class="info-text">Coordinates refresh dynamically every 5 seconds.</p>
            </div>
            
        </div>
    </div>

    <script>
        var map;
        var trackingMarker = null;
        var packageId = <?php echo json_encode($package_id); ?>; 

        // Initialize the Leaflet Map
        function initLeafletMap() {
            var defaultCenter = [37.6, -95.665]; 
            
            map = L.map('map-layer').setView(defaultCenter, 14);

            // Load modern, muted map tiles from CartoDB Voyager or fallback to OpenStreetMap
            L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>'
            }).addTo(map);

            if (packageId > 0) {
                updateLocation();
                // Poll the server every 5 seconds
                setInterval(updateLocation, 5000);
            } else {
                alert("No valid tracking ID provided.");
            }
        }

        function updateLocation() {
            fetch('get_coordinates.php?id=' + packageId)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        console.error("Tracking Error:", data.error);
                        return;
                    }

                    var newPos = [data.lat, data.lng];

                    if (!trackingMarker) {
                        // Custom Marker styling (Truck icon)
                        var truckIcon = L.divIcon({
                            html: '<div style="background-color: #2563eb; color: white; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 3px solid white; box-shadow: 0 4px 10px rgba(0,0,0,0.2);"><i class="fa-solid fa-truck" style="font-size: 16px;"></i></div>',
                            className: 'custom-div-icon',
                            iconSize: [40, 40],
                            iconAnchor: [20, 20],
                            popupAnchor: [0, -20]
                        });

                        trackingMarker = L.marker(newPos, { icon: truckIcon }).addTo(map)
                            .bindPopup('<b style="font-family: inherit;">Package Current Location</b>')
                            .openPopup();
                        
                        map.setView(newPos, map.getZoom());
                    } else {
                        // Smoothly move the marker to the new coordinates
                        trackingMarker.setLatLng(newPos);
                    }
                })
                .catch(error => console.error("Error fetching coordinates:", error));
        }

        // Fire map initialization on DOM content load
        document.addEventListener("DOMContentLoaded", initLeafletMap);
    </script>
</body>
</html>