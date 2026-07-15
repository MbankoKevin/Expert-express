<?php
// get_coordinates.php
header('Content-Type: application/json');
include 'backend/conn.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo json_encode(['error' => 'Invalid or missing ID']);
    exit;
}

$id = (int)$_GET['id'];

// Fetch the most recent live location entries
$stmt = $link->prepare("SELECT lat, lon FROM track WHERE track_id = ? LIMIT 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($data = $result->fetch_assoc()) {
    echo json_encode([
        'lat' => (float)$data['lat'],
        'lng' => (float)$data['lon'] // Google Maps API uses 'lng'
    ]);
} else {
    echo json_encode(['error' => 'No tracking data found']);
}

$stmt->close();
?>