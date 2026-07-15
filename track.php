<?php
// Define the correct path to your connection file.
$connection_file = 'backend/conn.php'; 

if (file_exists($connection_file)) {
    include $connection_file;
} else {
    // Elegant fallback warning if DB config is not found in the directory
    die("
    <div style='padding: 20px; font-family: sans-serif; text-align: center; margin-top: 50px;'>
        <div style='color: #dc2626; font-size: 40px; margin-bottom: 10px;'>⚠️</div>
        <h3 style='color: #1e293b;'>Database Configuration Missing</h3>
        <p style='color: #64748b;'>Could not locate the database connection file: <strong>" . htmlspecialchars($connection_file) . "</strong></p>
        <p style='color: #94a3b8; font-size: 13px;'>Please verify that this file is placed in your project root folder.</p>
    </div>");
}

// Fetch the tracking code safely from either GET or POST requests
$track_num = "";
$row = null;
$searched = false;

if (isset($_REQUEST['track_id'])) {
    $searched = true;
    $track_num = trim(mysqli_real_escape_string($link, $_REQUEST['track_id']));
    if (!empty($track_num)) {
        $query = "SELECT * FROM track WHERE track_id = '$track_num' OR ship_id = '$track_num' LIMIT 1";
        $result = mysqli_query($link, $query);
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
        }
    }
}

// Map the raw DB status into a standardized stage (1-5) if found
$current_stage = 1;
$progress_pct = 0;
if ($row) {
    $current_status = strtolower(trim($row['status'] ?? 'pending')); 

    switch ($current_status) {
        case 'pending':
        case 'ordered':
        case 'processing':
            $current_stage = 1;
            $progress_pct = 0;
            break;
        case 'dispatched':
        case 'picked up':
        case 'shipped':
            $current_stage = 2;
            $progress_pct = 25;
            break;
        case 'in transit':
        case 'on the way':
            $current_stage = 3;
            $progress_pct = 50;
            break;
        case 'out for delivery':
        case 'with courier':
            $current_stage = 4;
            $progress_pct = 75;
            break;
        case 'delivered':
        case 'completed':
            $current_stage = 5;
            $progress_pct = 100;
            break;
        default:
            $current_stage = 1;
            $progress_pct = 0;
            break;
    }
}

// Helper function to output active/completed CSS classes for the steps
function getStepClass($step_number, $current_stage) {
    if ($step_number < $current_stage) {
        return 'completed';
    } elseif ($step_number == $current_stage) {
        return 'active';
    }
    return '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Your Shipment | Expert Express</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Leaflet CSS for Maps -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <style>
        body {
            background-color: #f8fafc;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .tracking-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.06);
            background: #ffffff;
            overflow: hidden;
        }

        #map-layer {
            width: 100%;
            height: 520px;
            min-height: 480px;
            background-color: #e5e3df;
            position: relative;
        }

        /* Badge Status Formatting */
        .badge-transit { background-color: #2563eb; color: #fff; }
        .badge-delivered { background-color: #16a34a; color: #fff; }
        .badge-hold { background-color: #ea580c; color: #fff; }
        .badge-pending { background-color: #64748b; color: #fff; }

        .info-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            font-weight: 700;
            color: #64748b;
            margin-bottom: 2px;
            letter-spacing: 0.5px;
        }
        
        .info-value {
            font-size: 0.95rem;
            font-weight: 600;
            color: #1e293b;
        }

        .custom-div-icon {
            background: transparent;
            border: none;
        }

        /* --- Dynamic 5-Stage Step Progress Tracker CSS --- */
        .tracking-stepper {
            display: flex;
            justify-content: space-between;
            position: relative;
            margin: 25px 0;
            padding: 0 10px;
        }

        /* Background connecting timeline line */
        .tracking-stepper::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 0;
            width: 100%;
            height: 4px;
            background-color: #e2e8f0;
            z-index: 1;
        }

        /* Active connecting color fill line */
        .progress-line-fill {
            position: absolute;
            top: 20px;
            left: 0;
            height: 4px;
            background: linear-gradient(90deg, #3b82f6, #10b981);
            z-index: 1;
            transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Stepper circle wrappers */
        .step-item {
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            align-items: center;
            flex: 1;
        }

        /* The node dots */
        .step-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #fff;
            border: 3px solid #cbd5e1;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
            transition: all 0.4s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }

        .step-circle svg {
            width: 16px;
            height: 16px;
            fill: none;
            stroke: currentColor;
            stroke-width: 2.5;
        }

        /* Node label texts */
        .step-title {
            margin-top: 8px;
            font-size: 0.8rem;
            font-weight: 600;
            color: #64748b;
            text-align: center;
        }

        /* Active Stage Node states */
        .step-item.active .step-circle {
            border-color: #3b82f6;
            color: #3b82f6;
            background-color: #eff6ff;
            transform: scale(1.1);
        }

        .step-item.active .step-title {
            color: #1e3a8a;
        }

        /* Completed Stage Node states */
        .step-item.completed .step-circle {
            background-color: #10b981;
            border-color: #10b981;
            color: #ffffff;
        }

        .step-item.completed .step-title {
            color: #065f46;
        }
    </style>
</head>
<body>

<div class="container py-5">

    <!-- STATE 1: Default Landing & Invalid Search Forms -->
    <?php if (!$searched || ($searched && !$row)): ?>
        <div class="row justify-content-center my-auto">
            <div class="col-md-6 col-lg-5">
                
                <?php if ($searched && !$row && !empty($track_num)): ?>
                    <!-- Display warning layout dynamically if search query yielded no results -->
                    <div class="alert alert-danger border-0 shadow-sm p-3 rounded-4 mb-4 d-flex align-items-center" role="alert">
                        <i class="fa-solid fa-circle-exclamation fa-2x me-3 text-danger"></i>
                        <div>
                            <strong class="d-block text-danger">Shipment Not Found</strong>
                            <span class="small text-muted">We couldn't locate details for "<?php echo htmlspecialchars($track_num); ?>".</span>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="card p-4 p-md-5 shadow border-0 rounded-4 tracking-card">
                    <div class="text-center mb-4">
                        <div class="bg-primary-subtle text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                            <i class="fa-solid fa-boxes-packing fa-2x text-primary"></i>
                        </div>
                        <h3 class="fw-bold text-dark">Track Your Shipment</h3>
                        <p class="text-muted small">Enter your consignment code or shipment tracking ID below to check live transit paths and road updates.</p>
                    </div>

                    <form action="track.php" method="POST">
                        <div class="form-group mb-4">
                            <label class="form-label small text-uppercase fw-bold text-muted">Shipment Tracking Number</label>
                            <div class="input-group input-group-lg border rounded-3 overflow-hidden">
                                <span class="input-group-text bg-white border-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                                <input type="text" name="track_id" class="form-control border-0 fs-6 ps-1" placeholder="e.g. EXP-7854-YTR" value="<?php echo htmlspecialchars($track_num); ?>" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg w-100 rounded-3 fw-bold py-3 fs-6 shadow-sm mb-3"><i class="fa-solid fa-location-crosshairs me-2"></i>Track Live Shipment</button>
                    </form>

                    <!-- Clean back to home reference link -->
                    <div class="text-center">
                        <a href="index.php" class="text-secondary small text-decoration-none fw-semibold"><i class="fa-solid fa-house me-1"></i> Return to Home Page</a>
                    </div>
                </div>
            </div>
        </div>

    <!-- STATE 2: Shipment is Found (Side-by-Side Map Dashboard) -->
    <?php else: 
        $status_raw = strtolower(trim($row['status']));
        $badge_class = 'badge-pending';
        if (strpos($status_raw, 'transit') !== false) {
            $badge_class = 'badge-transit';
        } elseif (strpos($status_raw, 'deliver') !== false) {
            $badge_class = 'badge-delivered';
        } elseif (strpos($status_raw, 'hold') !== false) {
            $badge_class = 'badge-hold';
        }
    ?>
        
        <!-- Live Action Layout Header -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
            <div>
                <div class="d-flex gap-2 mb-2">
                    <a href="track.php" class="btn btn-sm btn-outline-secondary rounded-pill px-3 small"><i class="fa-solid fa-arrow-left me-1"></i> Search Again</a>
                    <a href="index.php" class="btn btn-sm btn-outline-primary rounded-pill px-3 small"><i class="fa-solid fa-house me-1"></i> Home</a>
                </div>
                <h1 class="h2 mb-0 fw-bold">Tracking ID: <?php echo htmlspecialchars($row['ship_id'] ?: $row['track_id']); ?></h1>
            </div>
            <div>
                <span class="badge <?php echo $badge_class; ?> px-4 py-2.5 rounded-pill fs-6 shadow-sm">
                    <i class="fa-solid fa-circle-notch fa-spin me-2"></i><?php echo htmlspecialchars($row['status']); ?>
                </span>
            </div>
        </div>

        <!-- Dynamic 5-Stage Progress Tracker Banner -->
        <div class="card tracking-card mb-4 p-4">
            <h5 class="fw-bold mb-1 text-dark"><i class="fa-solid fa-list-check text-primary me-2"></i>Transit Progress Milestone</h5>
            <p class="text-muted small mb-4">Visual real-time status log representing physical checkpoints scanned.</p>
            
            <div class="tracking-stepper">
                <div class="progress-line-fill" style="width: <?php echo $progress_pct; ?>%;"></div>

                <!-- Stage 1: Order Placed -->
                <div class="step-item <?php echo getStepClass(1, $current_stage); ?>">
                    <div class="step-circle">
                        <?php if ($current_stage > 1): ?>
                            <svg viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5" stroke="#fff"/></svg>
                        <?php else: ?>
                            <svg viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4zM3 6h18M16 10a4 4 0 0 1-8 0" stroke="currentColor"/></svg>
                        <?php endif; ?>
                    </div>
                    <div class="step-title">Order Placed</div>
                </div>

                <!-- Stage 2: Dispatched -->
                <div class="step-item <?php echo getStepClass(2, $current_stage); ?>">
                    <div class="step-circle">
                        <?php if ($current_stage > 2): ?>
                            <svg viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5" stroke="#fff"/></svg>
                        <?php else: ?>
                            <svg viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16zM3.27 6.96L12 12.01l8.73-5.05M12 22.08V12" stroke="currentColor"/></svg>
                        <?php endif; ?>
                    </div>
                    <div class="step-title">Dispatched</div>
                </div>

                <!-- Stage 3: In Transit -->
                <div class="step-item <?php echo getStepClass(3, $current_stage); ?>">
                    <div class="step-circle">
                        <?php if ($current_stage > 3): ?>
                            <svg viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5" stroke="#fff"/></svg>
                        <?php else: ?>
                            <svg viewBox="0 0 24 24"><path d="M10 17H3a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11a2 2 0 0 1 2 2v2m4 1h3a1 1 0 0 1 1 1v4h-6v-5zM14 17a3 3 0 1 1-6 0M20 17a3 3 0 1 1-6 0" stroke="currentColor"/></svg>
                        <?php endif; ?>
                    </div>
                    <div class="step-title">In Transit</div>
                </div>

                <!-- Stage 4: Out for Delivery -->
                <div class="step-item <?php echo getStepClass(4, $current_stage); ?>">
                    <div class="step-circle">
                        <?php if ($current_stage > 4): ?>
                            <svg viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5" stroke="#fff"/></svg>
                        <?php else: ?>
                            <svg viewBox="0 0 24 24"><path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2M19 18h2a1 1 0 0 0 1-1v-5.5a1.5 1.5 0 0 0-.5-1.1L18 7h-4M7 18a2 2 0 1 1-4 0M17 18a2 2 0 1 1-4 0" stroke="currentColor"/></svg>
                        <?php endif; ?>
                    </div>
                    <div class="step-title">Out for Delivery</div>
                </div>

                <!-- Stage 5: Delivered -->
                <div class="step-item <?php echo getStepClass(5, $current_stage); ?>">
                    <div class="step-circle">
                        <svg viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14M22 4L12 14.01l-3-3" stroke="currentColor"/></svg>
                    </div>
                    <div class="step-title">Delivered</div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Left Grid Pane: Leaflet Driving Route Map -->
            <div class="col-lg-7 col-md-12">
                <div class="card tracking-card h-100">
                    <div class="card-header bg-white border-0 py-3 px-4 d-flex align-items-center justify-content-between">
                        <h5 class="m-0 fw-bold"><i class="fa-solid fa-route text-primary me-2"></i>Live Road Mapping</h5>
                        <button onclick="restartMarkerAnimation()" class="btn btn-xs btn-primary py-1 px-3 rounded-pill text-xs fw-semibold"><i class="fa-solid fa-arrows-rotate me-1"></i> Re-play Transit</button>
                    </div>
                    <div class="card-body p-0">
                        <div id="map-layer"></div>
                    </div>
                </div>
            </div>

            <!-- Right Grid Pane: Full Shipment Metadata Panel -->
            <div class="col-lg-5 col-md-12">
                <div class="card tracking-card h-100 p-4 d-flex flex-column justify-content-between">
                    
                    <div>
                        <!-- Header with PDF Waybill Export Button aligned side-by-side -->
                        <div class="d-flex justify-content-between align-items-center pb-2 border-bottom mb-3">
                            <h5 class="fw-bold m-0 text-secondary"><i class="fa-solid fa-truck-ramp-box me-2"></i>Logistics Metadata</h5>
                            <a href="generate_waybill.php?track_id=<?php echo urlencode($row['ship_id'] ?: $row['track_id']); ?>" 
                               class="btn btn-sm btn-outline-danger fw-bold rounded-pill px-3 shadow-sm" 
                               target="_blank">
                                <i class="fa-solid fa-file-pdf me-1"></i> Waybill PDF
                            </a>
                        </div>
                        
                        <!-- Timeline Routing Visualizer -->
                        <div class="my-4 ps-3 border-start border-2 border-primary position-relative">
                            <div class="mb-4 position-relative">
                                <span class="position-absolute start-0 translate-middle-x bg-primary rounded-circle border border-white" style="width:12px; height:12px; margin-left:-17px; margin-top: 6px;"></span>
                                <div class="info-label">Origin Departure</div>
                                <div class="info-value text-primary"><?php echo htmlspecialchars($row['lon']); ?></div>
                                <div class="text-muted small mt-1"><i class="fa-regular fa-clock me-1"></i><?php echo htmlspecialchars($row['Ship_date'] . ' ' . $row['Ship_time']); ?></div>
                            </div>
                            
                            <div class="position-relative">
                                <span class="position-absolute start-0 translate-middle-x bg-success rounded-circle border border-white" style="width:12px; height:12px; margin-left:-17px; margin-top: 6px;"></span>
                                <div class="info-label">Scheduled Destination</div>
                                <div class="info-value text-success"><?php echo htmlspecialchars($row['lat']); ?></div>
                                <div class="text-muted small mt-1"><i class="fa-regular fa-calendar-check me-1"></i>Est. Arrival: <?php echo htmlspecialchars($row['ddate'] . ' ' . $row['dtime']); ?></div>
                            </div>
                        </div>

                        <!-- Grid Details -->
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="info-label">Consignment Item</div>
                                <div class="info-value"><?php echo htmlspecialchars($row['prod']); ?></div>
                            </div>
                            <div class="col-6">
                                <div class="info-label">Category Type</div>
                                <div class="info-value"><?php echo htmlspecialchars($row['cat'] ?: 'General Freight'); ?></div>
                            </div>
                            <div class="col-6">
                                <div class="info-label">Gross Weight</div>
                                <div class="info-value"><?php echo htmlspecialchars($row['weight'] ?: 'N/A'); ?></div>
                            </div>
                            <div class="col-6">
                                <div class="info-label">Freight Mode</div>
                                <div class="info-value"><i class="fa-solid fa-plane-departure text-muted me-1"></i><?php echo htmlspecialchars($row['mode'] ?: 'Express Road'); ?></div>
                            </div>
                            <div class="col-6">
                                <div class="info-label">Pieces Packaged</div>
                                <div class="info-value"><?php echo htmlspecialchars($row['items'] ?: '1 unit'); ?></div>
                            </div>
                            <div class="col-6">
                                <div class="info-label">Current Location</div>
                                <div class="info-value text-warning"><i class="fa-solid fa-map-pin me-1"></i><?php echo htmlspecialchars($row['currentl'] ?: 'In Transit'); ?></div>
                            </div>
                        </div>
                    </div>

                    <!-- Client and Consignee Parties -->
                    <div class="mt-4 pt-3 border-top">
                        <div class="row">
                            <div class="col-6">
                                <div class="info-label">Shipper / Consignor</div>
                                <div class="info-value text-dark mb-1"><?php echo htmlspecialchars($row['jname']); ?></div>
                                <div class="text-muted small lh-sm"><?php echo htmlspecialchars($row['jadd'] . ', ' . $row['jcountry']); ?></div>
                            </div>
                            <div class="col-6">
                                <div class="info-label">Recipient / Consignee</div>
                                <div class="info-value text-dark mb-1"><?php echo htmlspecialchars($row['sname']); ?></div>
                                <div class="text-muted small lh-sm"><?php echo htmlspecialchars($row['sadd'] . ', ' . $row['scountry']); ?></div>
                            </div>
                        </div>
                    </div>

                    <!-- Internal System Log Comments -->
                    <?php if (!empty($row['descrip'])): ?>
                        <div class="mt-4 p-3 bg-light rounded-3">
                            <div class="info-label mb-1"><i class="fa-regular fa-comment-dots me-1"></i>Latest Log Comments</div>
                            <p class="mb-0 text-dark small" style="line-height: 1.4;"><?php echo nl2br(htmlspecialchars($row['descrip'])); ?></p>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Leaflet Javascript Engine -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    var map;
    var animatedMarker = null;
    var animationInterval = null;
    var routeCoordinates = []; // Stores the ordered coordinate array of the path
    var routeFromStr = <?php echo isset($row) ? json_encode($row['lon']) : 'null'; ?>; 
    var routeToStr = <?php echo isset($row) ? json_encode($row['lat']) : 'null'; ?>;

    function initCombinedMap() {
        if (!routeFromStr || !routeToStr) return;

        // Initialize map centered globally first
        map = L.map('map-layer').setView([20, 0], 2);

        // Render clean layout tile layer
        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        drawCityRoute(routeFromStr, routeToStr);
    }

    // Geocode locations to coordinates
    function geocodeCity(cityName) {
        return fetch('https://nominatim.openstreetmap.org/search?format=json&q=' + encodeURIComponent(cityName))
            .then(res => res.json())
            .then(data => {
                if (data && data.length > 0) {
                    return {
                        lat: parseFloat(data[0].lat),
                        lon: parseFloat(data[0].lon),
                        displayName: data[0].display_name
                    };
                }
                throw new Error("Could not find coordinates for: " + cityName);
            });
    }

    function drawCityRoute(fromCity, toCity) {
        Promise.all([geocodeCity(fromCity), geocodeCity(toCity)])
            .then(results => {
                var origin = results[0];
                var destination = results[1];

                var fromCoords = [origin.lat, origin.lon];
                var toCoords = [destination.lat, destination.lon];

                // Create clean stylized static icons
                var startIcon = L.divIcon({
                    html: '<div style="background-color: #2563eb; color: white; width: 34px; height: 34px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 3px solid white; box-shadow: 0 4px 10px rgba(0,0,0,0.2);"><i class="fa-solid fa-map-pin" style="font-size: 14px;"></i></div>',
                    className: 'custom-div-icon',
                    iconSize: [34, 34],
                    iconAnchor: [17, 17],
                    popupAnchor: [0, -17]
                });

                var endIcon = L.divIcon({
                    html: '<div style="background-color: #16a34a; color: white; width: 34px; height: 34px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 3px solid white; box-shadow: 0 4px 10px rgba(0,0,0,0.2);"><i class="fa-solid fa-flag-checkered" style="font-size: 14px;"></i></div>',
                    className: 'custom-div-icon',
                    iconSize: [34, 34],
                    iconAnchor: [17, 17],
                    popupAnchor: [0, -17]
                });

                // Place standard endpoints
                L.marker(fromCoords, { icon: startIcon }).addTo(map)
                    .bindPopup('<b>Shipment Origin</b><br>' + origin.displayName);

                L.marker(toCoords, { icon: endIcon }).addTo(map)
                    .bindPopup('<b>Target Destination</b><br>' + destination.displayName);

                // Fetch detailed routes using OSRM driving profile
                var osrmUrl = 'https://router.project-osrm.org/route/v1/driving/' + 
                              origin.lon + ',' + origin.lat + ';' + 
                              destination.lon + ',' + destination.lat + 
                              '?overview=full&geometries=geojson';

                fetch(osrmUrl)
                    .then(response => response.json())
                    .then(routeData => {
                        if (routeData.code === 'Ok' && routeData.routes && routeData.routes.length > 0) {
                            var routeGeoJSON = routeData.routes[0].geometry;
                            
                            // Highlight the realistic road sequence on the map
                            L.geoJSON(routeGeoJSON, {
                                style: {
                                    color: '#4f46e5',
                                    weight: 4,
                                    opacity: 0.45, // Set path opacity lower to let animated marker pop
                                    lineCap: 'round',
                                    lineJoin: 'round'
                                }
                            }).addTo(map);

                            // Extract point array for the animation (Note: GeoJSON is [lon, lat], Leaflet is [lat, lon])
                            routeCoordinates = routeGeoJSON.coordinates.map(function(coord) {
                                return [coord[1], coord[0]];
                            });

                            // Begin dynamic transit animation
                            startMarkerAnimation();
                        } else {
                            // Interpolate coordinates for straight line animation fallback
                            generateStraightLineRoute(fromCoords, toCoords);
                            startMarkerAnimation();
                        }
                    })
                    .catch(err => {
                        console.warn("Routing engine returned error. Using fallback interpolation.", err);
                        generateStraightLineRoute(fromCoords, toCoords);
                        startMarkerAnimation();
                    });

                // Auto bounding calculation
                var bounds = L.latLngBounds([fromCoords, toCoords]);
                map.fitBounds(bounds, { padding: [60, 60] });
            })
            .catch(err => console.error("Map route plotting error:", err));
    }

    // Creates steps between two points when no road data is available
    function generateStraightLineRoute(start, end) {
        routeCoordinates = [];
        var steps = 100;
        for (var i = 0; i <= steps; i++) {
            var ratio = i / steps;
            var lat = start[0] + (end[0] - start[0]) * ratio;
            var lon = start[1] + (end[1] - start[1]) * ratio;
            routeCoordinates.push([lat, lon]);
        }
        drawDashedLineFallback(start, end);
    }

    function drawDashedLineFallback(fromCoords, toCoords) {
        L.polyline([fromCoords, toCoords], {
            color: '#4f46e5',
            weight: 3.5,
            opacity: 0.4,
            dashArray: '10, 8'
        }).addTo(map);
    }

    // Dynamic marker animation controller
    function startMarkerAnimation() {
        if (routeCoordinates.length === 0) return;

        // Clean up any existing instances
        if (animatedMarker) map.removeLayer(animatedMarker);
        if (animationInterval) clearInterval(animationInterval);

        // Custom animated shipping vehicle icon (a moving pulse truck)
        var truckIcon = L.divIcon({
            html: `
                <div style="position: relative; width: 42px; height: 42px; display: flex; align-items: center; justify-content: center;">
                    <!-- Pulse waves -->
                    <div style="position: absolute; width: 100%; height: 100%; background-color: #ec4899; border-radius: 50%; opacity: 0.4; transform: scale(1); animation: markerPulse 1.8s infinite ease-out;"></div>
                    <!-- Core Icon -->
                    <div style="position: relative; background-color: #db2777; color: white; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 2.5px solid white; box-shadow: 0 4px 12px rgba(219,39,119,0.35);">
                        <i class="fa-solid fa-truck" style="font-size: 13px;"></i>
                    </div>
                </div>
                <style>
                    @keyframes markerPulse {
                        0% { transform: scale(0.6); opacity: 0.9; }
                        100% { transform: scale(1.6); opacity: 0; }
                    }
                </style>
            `,
            className: 'custom-div-icon',
            iconSize: [42, 42],
            iconAnchor: [21, 21]
        });

        var currentIndex = 0;
        animatedMarker = L.marker(routeCoordinates[0], { icon: truckIcon }).addTo(map);

        // Slide the marker smoothly across coordinates
        animationInterval = setInterval(function() {
            if (currentIndex >= routeCoordinates.length - 1) {
                // Loop the transit animation upon completion
                currentIndex = 0;
            }
            currentIndex++;
            animatedMarker.setLatLng(routeCoordinates[currentIndex]);
        }, 120); // Interval speed in ms (lower is faster)
    }

    function restartMarkerAnimation() {
        startMarkerAnimation();
    }

    document.addEventListener("DOMContentLoaded", function() {
        setTimeout(initCombinedMap, 250); 
    });
</script>
</body>
</html>