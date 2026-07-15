<?php 
include 'header.php'; 
include 'sidebar.php'; 
include 'conn.php';

// Check connection early before page structure processing
if ($link === false) {
    die("<div class='alert alert-danger m-3'><strong>Database Link Error:</strong> Unable to connect. " . mysqli_connect_error() . "</div>");
}

$alertMessage = "";

if (isset($_POST['save'])) {
    // Escaping all inputs
    $sname     = mysqli_real_escape_string($link, $_POST['sname']);
    $sadd      = mysqli_real_escape_string($link, $_POST['sadd']);
    $scountry  = mysqli_real_escape_string($link, $_POST['scountry']);
    $semail    = mysqli_real_escape_string($link, $_POST['semail']);
    $snumber   = mysqli_real_escape_string($link, $_POST['snumber']);
    
    $jname     = mysqli_real_escape_string($link, $_POST['jname']);
    $jadd      = mysqli_real_escape_string($link, $_POST['jadd']);
    $jcountry  = mysqli_real_escape_string($link, $_POST['jcountry']);
    $jemail    = mysqli_real_escape_string($link, $_POST['jemail']);
    $jnumber   = mysqli_real_escape_string($link, $_POST['jnumber']);
    
    $prod      = mysqli_real_escape_string($link, $_POST['prod']);
    $cat       = mysqli_real_escape_string($link, $_POST['cat']);
    $weight    = mysqli_real_escape_string($link, $_POST['weight']);
    $items     = mysqli_real_escape_string($link, $_POST['items']);
    
    $mode      = mysqli_real_escape_string($link, $_POST['mode']);
    $status    = mysqli_real_escape_string($link, $_POST['status']);
    $deliverys = mysqli_real_escape_string($link, $_POST['deliverys']);
    
    $ship_date = mysqli_real_escape_string($link, $_POST['ship_date']);
    $ship_time = mysqli_real_escape_string($link, $_POST['ship_time']);
    $ddate     = mysqli_real_escape_string($link, $_POST['ddate']);
    $dtime     = mysqli_real_escape_string($link, $_POST['dtime']);
    
    $currentl  = mysqli_real_escape_string($link, $_POST['currentl']);
    $pickupl   = mysqli_real_escape_string($link, $_POST['pickupl']);
    
    $lon       = mysqli_real_escape_string($link, $_POST['lon']);
    $lat       = mysqli_real_escape_string($link, $_POST['lat']);
    $descrip   = mysqli_real_escape_string($link, $_POST['descrip']);

    // Validation checks
    if (empty($sname)) {
        $alertMessage = "<div class='alert alert-danger rounded-3 shadow-sm'><strong>Validation Failed:</strong> Sender's Name cannot be left blank.</div>";
    } elseif (empty($sadd)) {
        $alertMessage = "<div class='alert alert-danger rounded-3 shadow-sm'><strong>Validation Failed:</strong> Sender's Address cannot be left blank.</div>";
    } elseif (empty($scountry)) {
        $alertMessage = "<div class='alert alert-danger rounded-3 shadow-sm'><strong>Validation Failed:</strong> Sender's Origin Country cannot be left blank.</div>";
    } elseif (empty($jname)) {
        $alertMessage = "<div class='alert alert-danger rounded-3 shadow-sm'><strong>Validation Failed:</strong> Receiver's Name cannot be left blank.</div>";
    } elseif (empty($jadd)) {
        $alertMessage = "<div class='alert alert-danger rounded-3 shadow-sm'><strong>Validation Failed:</strong> Receiver's Address cannot be left blank.</div>";
    } elseif (empty($jcountry)) {
        $alertMessage = "<div class='alert alert-danger rounded-3 shadow-sm'><strong>Validation Failed:</strong> Receiver's Destination Country cannot be left blank.</div>";
    } elseif (empty($prod)) {
        $alertMessage = "<div class='alert alert-danger rounded-3 shadow-sm'><strong>Validation Failed:</strong> Product Commodity/Description field is missing.</div>";
    } else {
        $me = rand();
        $sql = "INSERT INTO track (jname, jadd, jcountry, jemail, jnumber, sname, sadd, scountry, semail, snumber, prod, mode, ship_date, ddate, ship_time, dtime, currentl, pickupl, status, deliverys, cat, weight, items, descrip, ship_id, lon, lat) 
                VALUES ('$jname', '$jadd', '$jcountry', '$jemail', '$jnumber', '$sname', '$sadd', '$scountry', '$semail', '$snumber', '$prod', '$mode', '$ship_date', '$ddate', '$ship_time', '$dtime', '$currentl', '$pickupl', '$status', '$deliverys', '$cat', '$weight', '$items', '$descrip', 'CL-$me', '$lon', '$lat')";
        
        if (mysqli_query($link, $sql)) {
            $alertMessage = "<div class='alert alert-success rounded-3 shadow-sm'><strong>Waybill Generated!</strong> Tracking record established successfully under Manifest Ticket: <span class='badge bg-success fw-bold text-white fs-6'>CL-$me</span></div>";
        } else {
            $alertMessage = "<div class='alert alert-danger rounded-3 shadow-sm'><strong>Database Insertion Failed:</strong> " . mysqli_error($link) . "</div>";
        }
    }
}
mysqli_close($link);
?>

<!-- Page wrapper  -->
<div class="page-wrapper" style="background-color: #f8fafc; min-height: 100vh; padding-top: 70px;">
    
    <!-- Breadcrumb -->
    <div class="row page-titles mx-0 py-3 bg-white border-bottom border-light-subtle">
        <div class="col-md-5 align-self-center">
            <h4 class="text-dark fw-bold mb-0">New Waybill Provision</h4> 
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb m-0 justify-content-md-end bg-transparent p-0 small">
                <li class="breadcrumb-item"><a href="home.php" class="text-decoration-none">Home</a></li>
                <li class="breadcrumb-item active text-muted">Add Tracking</li>
            </ol>
        </div>
    </div>
    
    <!-- Container fluid  -->
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12 max-width-1100 mx-auto">
                
                <!-- Feedback Messages -->
                <?php if(!empty($alertMessage)) { echo $alertMessage; } ?>

                <form method="post" class="mt-2">
                    
                    <!-- SECTION 1: Sender Details (The Flow Starts Here) -->
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                        <div class="card-header bg-white border-bottom border-light-subtle py-3 px-4">
                            <h5 class="mb-0 fw-bold text-dark d-flex align-items-center"><i class="fa fa-building text-info me-2"></i> 1. Sender Information (Origin)</h5>
                        </div>
                        <div class="card-body p-4 bg-white">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-secondary small">Sender Name <span class="text-danger">*</span></label>
                                    <input type="text" name="sname" class="form-control rounded-2 border-light-subtle" placeholder="e.g. Logistics Center Hub">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-secondary small">Email Address</label>
                                    <input type="email" name="semail" class="form-control rounded-2 border-light-subtle" placeholder="e.g. vendor@domain.com">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-secondary small">Contact Number</label>
                                    <input type="tel" name="snumber" class="form-control rounded-2 border-light-subtle" placeholder="e.g. +1 555-0100">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-secondary small">Origin Country <span class="text-danger">*</span></label>
                                    <input type="text" name="scountry" class="form-control rounded-2 border-light-subtle" placeholder="e.g. Mexico">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-secondary small">Full Origin Address <span class="text-danger">*</span></label>
                                    <input type="text" name="sadd" class="form-control rounded-2 border-light-subtle" placeholder="e.g. Av. Reforma 222, CDMX">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 2: Receiver Details -->
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                        <div class="card-header bg-white border-bottom border-light-subtle py-3 px-4">
                            <h5 class="mb-0 fw-bold text-dark d-flex align-items-center"><i class="fa fa-user text-primary me-2"></i> 2. Receiver Information (Destination)</h5>
                        </div>
                        <div class="card-body p-4 bg-white">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-secondary small">Receiver Name <span class="text-danger">*</span></label>
                                    <input type="text" name="jname" class="form-control rounded-2 border-light-subtle" placeholder="e.g. John Doe">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-secondary small">Email Address</label>
                                    <input type="email" name="jemail" class="form-control rounded-2 border-light-subtle" placeholder="e.g. receiver@domain.com">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-secondary small">Contact Number</label>
                                    <input type="tel" name="jnumber" class="form-control rounded-2 border-light-subtle" placeholder="e.g. +1 555-0199">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-secondary small">Destination Country <span class="text-danger">*</span></label>
                                    <input type="text" name="jcountry" class="form-control rounded-2 border-light-subtle" placeholder="e.g. United Kingdom">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-secondary small">Full Address <span class="text-danger">*</span></label>
                                    <input type="text" name="jadd" class="form-control rounded-2 border-light-subtle" placeholder="e.g. 12 Baker St, London">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 3: Package Metrics -->
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                        <div class="card-header bg-white border-bottom border-light-subtle py-3 px-4">
                            <h5 class="mb-0 fw-bold text-dark d-flex align-items-center"><i class="fa fa-cube text-warning me-2"></i> 3. Package & Cargo Details</h5>
                        </div>
                        <div class="card-body p-4 bg-white">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-secondary small">Commodity / Product Description <span class="text-danger">*</span></label>
                                    <input type="text" name="prod" class="form-control rounded-2 border-light-subtle" placeholder="e.g. Industrial Copper Rods">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-secondary small">Category Type</label>
                                    <input type="text" name="cat" class="form-control rounded-2 border-light-subtle" placeholder="e.g. Electronics, Medical, Auto Parts">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label fw-semibold text-secondary small">Total Weight</label>
                                    <input type="text" name="weight" class="form-control rounded-2 border-light-subtle" placeholder="e.g. 45.5 kg">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label fw-semibold text-secondary small">Total Units/Pieces</label>
                                    <input type="text" name="items" class="form-control rounded-2 border-light-subtle" placeholder="e.g. 03">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 4: Logistics & Dispatch Info -->
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                        <div class="card-header bg-white border-bottom border-light-subtle py-3 px-4">
                            <h5 class="mb-0 fw-bold text-dark d-flex align-items-center"><i class="fa fa-truck text-success me-2"></i> 4. Dispatch, Tracking & Milestones</h5>
                        </div>
                        <div class="card-body p-4 bg-white">
                            <div class="row g-3">
                                <!-- Row 1: Shipping and Tracking Status -->
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold text-secondary small">Transit Mode</label>
                                    <select class="form-select rounded-2 border-light-subtle" name="mode">
                                        <option value="Air" selected>✈️ Air Freight</option>
                                        <option value="Road">🚛 Road Carrier</option>
                                        <option value="Sea">🚢 Ocean Cargo</option>
                                        <option value="Rail">🚊 Rail Network</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold text-secondary small">Waybill Status</label>
                                    <select class="form-select rounded-2 border-light-subtle" name="status">
                                        <option value="In Progress" selected>🔄 Processing</option>
                                        <option value="On Transit">📦 On Transit</option>
                                        <option value="On Hold">⚠️ On Hold</option>
                                        <option value="Delivered">✅ Delivered</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-secondary small">Current Status Update Text</label>
                                    <input type="text" name="deliverys" class="form-control rounded-2 border-light-subtle" placeholder="e.g. Cleared customs, sorting in progress">
                                </div>

                                <!-- Row 2: Scheduling -->
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold text-secondary small">Departure Date</label>
                                    <input type="date" name="ship_date" class="form-control rounded-2 border-light-subtle">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold text-secondary small">Departure Time</label>
                                    <input type="time" name="ship_time" class="form-control rounded-2 border-light-subtle">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold text-secondary small">Est. Delivery Date</label>
                                    <input type="date" name="ddate" class="form-control rounded-2 border-light-subtle">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold text-secondary small">Est. Delivery Time</label>
                                    <input type="time" name="dtime" class="form-control rounded-2 border-light-subtle">
                                </div>

                                <!-- Row 3: Physical Locations -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-secondary small">Current Physical Facility Location</label>
                                    <input type="text" name="currentl" class="form-control rounded-2 border-light-subtle" placeholder="e.g. London Sorting Center Gate 3">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-secondary small">Final Designated Dropoff Point</label>
                                    <input type="text" name="pickupl" class="form-control rounded-2 border-light-subtle" placeholder="e.g. Distribution Warehouse Hub North">
                                </div>

                                <!-- Row 4: Route Map Vectors -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-secondary small">Live Map Origin City (Route From)</label>
                                    <input type="text" name="lon" class="form-control rounded-2 border-light-subtle" placeholder="e.g. Mexico City, MX">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-secondary small">Live Map Destination City (Route To)</label>
                                    <input type="text" name="lat" class="form-control rounded-2 border-light-subtle" placeholder="e.g. London, UK">
                                </div>

                                <!-- Row 5: Notes -->
                                <div class="col-12">
                                    <label class="form-label fw-semibold text-secondary small">Internal Handling Instructions & Special Remarks</label>
                                    <textarea class="form-control rounded-2 border-light-subtle" name="descrip" rows="3" placeholder="Enter fragile notes, customs requirements, or direct customer instructions..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Action Control Bar -->
                    <div class="d-flex align-items-center justify-content-end gap-3 mb-5">
                        <button type="reset" class="btn btn-light border px-4 py-2 rounded-2 fw-semibold text-secondary">Reset System Form</button>
                        <button type="submit" name="save" class="btn btn-primary px-5 py-2 rounded-2 fw-semibold shadow-sm"><i class="fa fa-save me-1.5"></i> Commit Manifest Record</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
    <!-- End Container fluid  -->

    <!-- Custom Styling Element Injections -->
    <style>
        .form-control:focus, .form-select:focus {
            border-color: #38bdf8 !important;
            box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.15) !important;
        }
        .max-width-1100 {
            max-width: 1100px;
        }
    </style>

<?php include 'footer.php'; ?>