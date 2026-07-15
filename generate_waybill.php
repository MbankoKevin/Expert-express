<?php
// 1. Initialize DB Connection
$connection_file = 'backend/conn.php'; 
if (file_exists($connection_file)) {
    include $connection_file;
} else {
    die("Database connection file missing.");
}

// 2. Fetch the Tracking ID
$track_id = isset($_GET['track_id']) ? trim(mysqli_real_escape_string($link, $_GET['track_id'])) : '';

if (empty($track_id)) {
    die("Error: Missing Tracking ID.");
}

// 3. Query Shipment Data
$query = "SELECT * FROM track WHERE track_id = '$track_id' OR ship_id = '$track_id' LIMIT 1";
$result = mysqli_query($link, $query);

if (!$result || mysqli_num_rows($result) === 0) {
    die("Error: Shipment records not found.");
}

$shipment = mysqli_fetch_assoc($result);

// 4. Load FPDF Library (Fallback included to guide you in case files are missing)
if (file_exists('fpdf19/fpdf.php')) {
    require('fpdf19/fpdf.php');
} elseif (file_exists('vendor/autoload.php')) {
    require('vendor/autoload.php');
} else {
    die("Error: FPDF library not found. Please place 'fpdf.php' in your root folder or run 'composer require setasign/fpdf'.");
}

// 5. Extend FPDF to design a premium corporate template
class WaybillPDF extends FPDF {
    function Header() {
        // Top Accent bar
        $this->SetFillColor(37, 99, 235); // Blue (#2563eb)
        $this->Rect(0, 0, 210, 8, 'F');
        $this->Ln(4);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(100, 116, 139);
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . ' | This is a computer-generated official document | Meridian Logistics Group', 0, 0, 'C');
    }
}

// 6. Generate the Document
$pdf = new WaybillPDF('P', 'mm', 'A4');
$pdf->AddPage();
$pdf->SetMargins(15, 15, 15);

// --- HEADER BRANDING SECTION ---
$pdf->SetFont('Arial', 'B', 18);
$pdf->SetTextColor(30, 41, 59); // Slate-800
$pdf->Cell(110, 10, 'Meridian Lo', 0, 0, 'L');

$pdf->SetFont('Arial', 'B', 12);
$pdf->SetTextColor(100, 116, 139); // Slate-500
$pdf->Cell(70, 10, 'OFFICIAL CONSIGNMENT WAYBILL', 0, 1, 'R');

// Horizontal Divider Line
$pdf->SetDrawColor(226, 232, 240);
$pdf->Line(15, 28, 195, 28);
$pdf->Ln(6);

// --- SHIPMENT IDENTIFICATION BLOCK ---
$pdf->SetFillColor(248, 250, 252); // Soft light background
$pdf->Rect(15, 32, 180, 22, 'F');

$pdf->SetY(35);
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetTextColor(100, 116, 139);
$pdf->SetX(20); $pdf->Cell(45, 5, 'WAYBILL / TRACKING NO.', 0, 0);
$pdf->SetX(80); $pdf->Cell(55, 5, 'DISPATCH DATE', 0, 0);
$pdf->SetX(140); $pdf->Cell(50, 5, 'CURRENT STATUS', 0, 1);

$pdf->SetFont('Arial', 'B', 11);
$pdf->SetTextColor(37, 99, 235); // Accent color
$pdf->SetX(20); $pdf->Cell(45, 6, htmlspecialchars($shipment['ship_id'] ?: $shipment['track_id']), 0, 0);
$pdf->SetTextColor(30, 41, 59);
$pdf->SetX(80); $pdf->Cell(55, 6, htmlspecialchars($shipment['Ship_date'] . ' @ ' . $shipment['Ship_time']), 0, 0);

// Status Color Allocation
$status = strtoupper(trim($shipment['status']));
if ($status === 'DELIVERED') {
    $pdf->SetTextColor(22, 163, 74); // Green
} elseif (strpos($status, 'HOLD') !== false || strpos($status, 'DELAY') !== false) {
    $pdf->SetTextColor(234, 88, 12); // Orange
} else {
    $pdf->SetTextColor(37, 99, 235); // Blue
}
$pdf->SetX(140); $pdf->Cell(50, 6, $status, 0, 1);

$pdf->Ln(12);

// --- ADDRESS PANELS (Side-by-Side) ---
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetTextColor(30, 41, 59);
$pdf->SetX(15); $pdf->Cell(90, 6, '1. SHIPPER / CONSIGNOR', 0, 0);
$pdf->SetX(110); $pdf->Cell(90, 6, '2. CONSIGNEE / RECIPIENT', 0, 1);

$pdf->SetDrawColor(203, 213, 225);
$pdf->Line(15, 67, 100, 67);
$pdf->Line(110, 67, 195, 67);
$pdf->Ln(3);

$pdf->SetFont('Arial', 'B', 9);
$pdf->SetTextColor(15, 23, 42);
$pdf->SetX(15); $pdf->Cell(90, 5, htmlspecialchars($shipment['jname']), 0, 0);
$pdf->SetX(110); $pdf->Cell(90, 5, htmlspecialchars($shipment['sname']), 0, 1);

$pdf->SetFont('Arial', '', 8.5);
$pdf->SetTextColor(71, 85, 105);
$pdf->SetX(15); 
$pdf->MultiCell(85, 4.2, htmlspecialchars($shipment['jadd'] . "\n" . $shipment['jcountry']), 0, 'L');

// Align right address pane Y height safely
$addressY = $pdf->GetY();
$pdf->SetY(75); 
$pdf->SetX(110); 
$pdf->MultiCell(85, 4.2, htmlspecialchars($shipment['sadd'] . "\n" . $shipment['scountry']), 0, 'L');

$maxAddressY = max($addressY, $pdf->GetY());
$pdf->SetY($maxAddressY + 8);

// --- NEW SECTION: PAYMENT & ROUTE DETAILS (2-Column Grid) ---
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetTextColor(30, 41, 59);
$pdf->Cell(180, 6, '3. LOGISTICS & BILLING SUMMARY', 0, 1);
$pdf->Line(15, $pdf->GetY(), 195, $pdf->GetY());
$pdf->Ln(4);

// Left Column Data
$pdf->SetFont('Arial', 'B', 8.5); $pdf->SetTextColor(100, 116, 139);
$pdf->Cell(45, 5, 'ORIGIN DEPARTURE:', 0, 0);
$pdf->SetFont('Arial', '', 8.5); $pdf->SetTextColor(30, 41, 59);
$pdf->Cell(45, 5, htmlspecialchars($shipment['lon']), 0, 0);

// Right Column Data
$pdf->SetFont('Arial', 'B', 8.5); $pdf->SetTextColor(100, 116, 139);
$pdf->Cell(45, 5, 'PAYMENT TERMS:', 0, 0);
$pdf->SetFont('Arial', '', 8.5); $pdf->SetTextColor(30, 41, 59);
$pdf->Cell(45, 5, htmlspecialchars($shipment['payment_mode'] ?? 'Prepaid / Sender Account'), 0, 1);

// Row 2
$pdf->SetFont('Arial', 'B', 8.5); $pdf->SetTextColor(100, 116, 139);
$pdf->Cell(45, 5, 'DESTINATION HUB:', 0, 0);
$pdf->SetFont('Arial', '', 8.5); $pdf->SetTextColor(30, 41, 59);
$pdf->Cell(45, 5, htmlspecialchars($shipment['lat']), 0, 0);

$pdf->SetFont('Arial', 'B', 8.5); $pdf->SetTextColor(100, 116, 139);
$pdf->Cell(45, 5, 'DECLARED VALUE:', 0, 0);
$pdf->SetFont('Arial', '', 8.5); $pdf->SetTextColor(30, 41, 59);
$pdf->Cell(45, 5, htmlspecialchars($shipment['declared_value'] ?? 'N/D (Not Declared)'), 0, 1);

// Row 3
$pdf->SetFont('Arial', 'B', 8.5); $pdf->SetTextColor(100, 116, 139);
$pdf->Cell(45, 5, 'EST. ARRIVAL DATE:', 0, 0);
$pdf->SetFont('Arial', '', 8.5); $pdf->SetTextColor(30, 41, 59);
$pdf->Cell(45, 5, htmlspecialchars($shipment['ddate'] . ' @ ' . $shipment['dtime']), 0, 0);

$pdf->SetFont('Arial', 'B', 8.5); $pdf->SetTextColor(100, 116, 139);
$pdf->Cell(45, 5, 'HANDLING CODES:', 0, 0);
$pdf->SetFont('Arial', 'B', 8.5); $pdf->SetTextColor(219, 39, 119); // Pink alert text for handling
$pdf->Cell(45, 5, htmlspecialchars($shipment['handling_instructions'] ?? 'STANDARD HANDLE'), 0, 1);

$pdf->Ln(6);

// --- ITEM DETAILS SECTION ---
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetTextColor(30, 41, 59);
$pdf->Cell(180, 6, '4. CONSIGNMENT ITEM METRICS', 0, 1);
$pdf->Line(15, $pdf->GetY(), 195, $pdf->GetY());
$pdf->Ln(4);

// Clean Table Headers
$pdf->SetFont('Arial', 'B', 8.5);
$pdf->SetFillColor(241, 245, 249);
$pdf->SetTextColor(71, 85, 105);

$pdf->Cell(60, 8, ' DESCRIPTION', 1, 0, 'L', true);
$pdf->Cell(40, 8, ' CATEGORY', 1, 0, 'C', true);
$pdf->Cell(25, 8, ' WEIGHT', 1, 0, 'C', true);
$pdf->Cell(30, 8, ' FREIGHT MODE', 1, 0, 'C', true);
$pdf->Cell(25, 8, ' QUANTITY', 1, 1, 'C', true);

// Table Data
$pdf->SetFont('Arial', '', 8.5);
$pdf->SetTextColor(15, 23, 42);
$pdf->Cell(60, 8, ' ' . htmlspecialchars($shipment['prod']), 1, 0, 'L');
$pdf->Cell(40, 8, htmlspecialchars($shipment['cat'] ?: 'General Cargo'), 1, 0, 'C');
$pdf->Cell(25, 8, htmlspecialchars($shipment['weight'] ?: 'N/A'), 1, 0, 'C');
$pdf->Cell(30, 8, htmlspecialchars($shipment['mode'] ?: 'Express Road'), 1, 0, 'C');
$pdf->Cell(25, 8, htmlspecialchars($shipment['items'] ?: '1 unit'), 1, 1, 'C');

$pdf->Ln(8);

// --- NEW SECTION: SYSTEM DIARY / REMARKS / TERMS ---
if (!empty($shipment['descrip'])) {
    $pdf->SetFont('Arial', 'B', 9.5);
    $pdf->SetTextColor(30, 41, 59);
    $pdf->Cell(180, 5, 'Special Shipment Instructions & Remarks:', 0, 1);
    $pdf->SetFont('Arial', 'I', 8);
    $pdf->SetTextColor(71, 85, 105);
    $pdf->MultiCell(180, 4.5, htmlspecialchars($shipment['descrip']), 1, 'L');
    $pdf->Ln(4);
}

// --- LEGAL LIABILITY STATEMENT ---
$pdf->SetFont('Arial', 'I', 7);
$pdf->SetTextColor(148, 163, 184);
$terms_text = "Standard Carriage Terms: The shipper declares that the values and items contained on this waybill are correct. Expert Express is only responsible for direct losses up to standard liability ceilings unless additional cargo insurance was purchased and verified. The receiver's signature below constitutes clean delivery without exceptions.";
$pdf->MultiCell(180, 3.5, $terms_text, 0, 'L');

// --- SIGNATURE FOOTER ---
$pdf->SetY(-40); // Lock securely near the bottom edge
$pdf->SetFont('Arial', 'B', 7.5);
$pdf->SetTextColor(100, 116, 139);

$pdf->Cell(85, 4, 'PREPARED & DISPATCHED BY (AGENT)', 0, 0, 'L');
$pdf->Cell(10, 4, '', 0, 0);
$pdf->Cell(85, 4, 'RECEIVED IN GOOD CONDITION BY (CONSIGNEE)', 0, 1, 'L');

$pdf->Cell(85, 12, '', 'B', 0); // Signature Border line
$pdf->Cell(10, 12, '', 0, 0);
$pdf->Cell(85, 12, '', 'B', 1);

// 7. Output compiled PDF binary directly to user's browser tab
$filename = 'Waybill_' . ($shipment['ship_id'] ?: $shipment['track_id']) . '.pdf';
$pdf->Output('I', $filename);
?>