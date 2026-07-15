<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<div class="page-wrapper">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-primary">Dashboard</h3> 
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Edit Tracking</li>
            </ol>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card card-outline-primary shadow-sm border-0 rounded-4">
                    <div class="card-header bg-primary py-3">
                        <h4 class="m-b-0 text-white"><i class="fa fa-edit me-2"></i>Edit Shipment Information</h4>
                    </div>
                    <div class="card-body p-4 p-md-5">
                        
                        <?php
                        include 'conn.php';

                        if($link === false){
                            die("ERROR: Could not connect. " . mysqli_connect_error());
                        }

                        if(isset($_POST['save'])){
                            $id1 = mysqli_real_escape_string($link,$_POST['id1']);
                            $jname = mysqli_real_escape_string($link,$_POST['jname']);
                            $jadd = mysqli_real_escape_string($link,$_POST['jadd']);
                            $jcountry = mysqli_real_escape_string($link,$_POST['jcountry']);
                            $sname = mysqli_real_escape_string($link,$_POST['sname']);
                            $sadd = mysqli_real_escape_string($link,$_POST['sadd']);
                            $scountry = mysqli_real_escape_string($link,$_POST['scountry']);
                            $prod = mysqli_real_escape_string($link,$_POST['prod']);
                            $mode = mysqli_real_escape_string($link,$_POST['mode']);
                            $ship_date = mysqli_real_escape_string($link,$_POST['ship_date']);
                            $ddate = mysqli_real_escape_string($link,$_POST['ddate']);
                            $ship_time = mysqli_real_escape_string($link,$_POST['ship_time']);
                            $dtime = mysqli_real_escape_string($link,$_POST['dtime']);
                            $currentl = mysqli_real_escape_string($link,$_POST['currentl']);
                            $pickupl = mysqli_real_escape_string($link,$_POST['pickupl']);
                            $status = mysqli_real_escape_string($link,$_POST['status']);
                            $deliverys = mysqli_real_escape_string($link,$_POST['deliverys']);
                            $cat = mysqli_real_escape_string($link,$_POST['cat']);
                            $weight = mysqli_real_escape_string($link,$_POST['weight']);
                            $items = mysqli_real_escape_string($link,$_POST['items']);
                            $descrip = mysqli_real_escape_string($link,$_POST['descrip']);
                            
                            // Storing location text inside lon and lat fields
                            $lon = mysqli_real_escape_string($link,$_POST['lon']); // Will hold Origin (From)
                            $lat = mysqli_real_escape_string($link,$_POST['lat']); // Will hold Destination (To)

                            $sql = "UPDATE track SET 
                                    jname='$jname', jadd='$jadd', jcountry='$jcountry', 
                                    sname='$sname', sadd='$sadd', scountry='$scountry', 
                                    prod='$prod', mode='$mode', ship_date='$ship_date', 
                                    ddate='$ddate', ship_time='$ship_time', dtime='$dtime', 
                                    currentl='$currentl', pickupl='$pickupl', status='$status', 
                                    deliverys='$deliverys', cat='$cat', weight='$weight', 
                                    items='$items', descrip='$descrip', lon='$lon', lat='$lat' 
                                    WHERE track_id='$id1'";

                            if(mysqli_query($link, $sql)){
                                echo "
                                <div class='alert alert-success alert-dismissible fade show border-0 shadow-sm p-3 rounded-3 mb-4' role='alert'>
                                    <i class='fa fa-check-circle me-2'></i><strong>Success!</strong> Shipment route has been updated.
                                </div>";
                            } else{
                                echo "
                                <div class='alert alert-danger border-0 shadow-sm p-3 rounded-3 mb-4'>
                                    <i class='fa fa-exclamation-triangle me-2'></i><strong>Error!</strong> Update query failed: " . mysqli_error($link) . "
                                </div>";
                            }
                        }
                        mysqli_close($link);
                        ?>

                        <?php 
                        include 'conn.php';
                        if(isset($_GET['id'])) {
                            $id = mysqli_real_escape_string($link, $_GET['id']);
                            $sql = "SELECT * FROM track WHERE track_id = '{$id}'";
                            $result = $link->query($sql);
                            if($result && $result->num_rows > 0) {
                                $data = $result->fetch_assoc();
                            } else {
                                echo "<div class='alert alert-warning'>No shipment record found.</div>";
                                exit;
                            }
                        }
                        ?>

                        <form method="post" class="form-horizontal">
                            <input type="hidden" name="id1" value="<?php echo htmlspecialchars($data["track_id"]); ?>">

                            <!-- SECTION 1: Parties -->
                            <h5 class="card-subtitle text-primary border-bottom pb-2 mb-4 fw-bold"><i class="fa fa-users me-2"></i>Parties Involved</h5>
                            <div class="row">
                                <div class="col-md-6 border-right">
                                    <h6 class="text-secondary fw-semibold mb-3">Sender Details</h6>
                                    <div class="form-group mb-3">
                                        <label class="form-label small text-uppercase fw-bold text-muted">Sender Name</label>
                                        <input type="text" name="jname" value="<?php echo htmlspecialchars($data["jname"]); ?>" class="form-control rounded-3" required>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="form-label small text-uppercase fw-bold text-muted">Sender Address</label>
                                        <input type="text" name="jadd" value="<?php echo htmlspecialchars($data["jadd"]); ?>" class="form-control rounded-3" required>
                                    </div>
                                    <div class="form-group mb-4">
                                        <label class="form-label small text-uppercase fw-bold text-muted">Sender Country</label>
                                        <input type="text" name="jcountry" value="<?php echo htmlspecialchars($data["jcountry"]); ?>" class="form-control rounded-3" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-secondary fw-semibold mb-3">Receiver Details</h6>
                                    <div class="form-group mb-3">
                                        <label class="form-label small text-uppercase fw-bold text-muted">Receiver Name</label>
                                        <input type="text" name="sname" value="<?php echo htmlspecialchars($data["sname"]); ?>" class="form-control rounded-3" required>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="form-label small text-uppercase fw-bold text-muted">Receiver Address</label>
                                        <input type="text" name="sadd" value="<?php echo htmlspecialchars($data["sadd"]); ?>" class="form-control rounded-3" required>
                                    </div>
                                    <div class="form-group mb-4">
                                        <label class="form-label small text-uppercase fw-bold text-muted">Receiver Country</label>
                                        <input type="text" name="scountry" value="<?php echo htmlspecialchars($data["scountry"]); ?>" class="form-control rounded-3" required>
                                    </div>
                                </div>
                            </div>

                            <!-- SECTION 2: Shipment Logistics Info -->
                            <h5 class="card-subtitle text-primary border-bottom pb-2 mb-4 mt-2 fw-bold"><i class="fa fa-box me-2"></i>Shipment & Freight Details</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label small text-uppercase fw-bold text-muted">Product Item Name</label>
                                        <input type="text" name="prod" value="<?php echo htmlspecialchars($data["prod"]); ?>" class="form-control rounded-3" required>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="form-label small text-uppercase fw-bold text-muted">Category Type</label>
                                        <input type="text" name="cat" value="<?php echo htmlspecialchars($data["cat"]); ?>" class="form-control rounded-3">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="form-label small text-uppercase fw-bold text-muted">Total Package Weight</label>
                                        <input type="text" name="weight" value="<?php echo htmlspecialchars($data["weight"]); ?>" class="form-control rounded-3">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="form-label small text-uppercase fw-bold text-muted">Total Pieces / Items</label>
                                        <input type="text" name="items" value="<?php echo htmlspecialchars($data["items"]); ?>" class="form-control rounded-3">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label small text-uppercase fw-bold text-muted">Transportation Mode</label>
                                        <select class="form-control rounded-3" name="mode">
                                            <option value="Air" <?php echo ($data['mode'] == 'Air') ? 'selected' : ''; ?>>Air Freight</option>
                                            <option value="Road" <?php echo ($data['mode'] == 'Road') ? 'selected' : ''; ?>>Road Logistics</option>
                                            <option value="Sea" <?php echo ($data['mode'] == 'Sea') ? 'selected' : ''; ?>>Ocean Freight</option>
                                            <option value="Rail" <?php echo ($data['mode'] == 'Rail') ? 'selected' : ''; ?>>Rail Network</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="form-label small text-uppercase fw-bold text-muted">Shipped Date</label>
                                        <input type="text" name="ship_date" value="<?php echo htmlspecialchars($data["Ship_date"]); ?>" class="form-control rounded-3">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="form-label small text-uppercase fw-bold text-muted">Shipped Time</label>
                                        <input type="text" name="ship_time" value="<?php echo htmlspecialchars($data["Ship_time"]); ?>" class="form-control rounded-3">
                                    </div>
                                </div>
                            </div>

                            <!-- SECTION 3: Live Mapping Cities instead of Coordinates -->
                            <h5 class="card-subtitle text-primary border-bottom pb-2 mb-4 mt-4 fw-bold"><i class="fa fa-map-marked-alt me-2"></i>Live Status & Route Locations</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label small text-uppercase fw-bold text-muted">Scheduled Pickup Hub</label>
                                        <input type="text" name="pickupl" value="<?php echo htmlspecialchars($data["pickupl"]); ?>" class="form-control rounded-3">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="form-label small text-uppercase fw-bold text-muted">Current Terminal / Hub</label>
                                        <input type="text" name="currentl" value="<?php echo htmlspecialchars($data["currentl"]); ?>" class="form-control rounded-3">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="form-label small text-uppercase fw-bold text-muted">General Progress Status</label>
                                        <select class="form-control rounded-3" name="status">
                                            <option value="In Progress" <?php echo ($data['status'] == 'In Progress' || $data['status'] == 'Proccessing') ? 'selected' : ''; ?>>Processing</option>
                                            <option value="Delivered" <?php echo ($data['status'] == 'Delivered') ? 'selected' : ''; ?>>Delivered</option>
                                            <option value="On Hold" <?php echo ($data['status'] == 'On Hold') ? 'selected' : ''; ?>>On Hold</option>
                                            <option value="On Transit" <?php echo ($data['status'] == 'On Transit') ? 'selected' : ''; ?>>In Transit</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="form-label small text-uppercase fw-bold text-muted">Delivery Status Headline</label>
                                        <input type="text" name="deliverys" value="<?php echo htmlspecialchars($data["deliverys"]); ?>" class="form-control rounded-3">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label small text-uppercase fw-bold text-muted">Target Delivery Date</label>
                                        <input type="text" name="ddate" value="<?php echo htmlspecialchars($data["ddate"]); ?>" class="form-control rounded-3">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="form-label small text-uppercase fw-bold text-muted">Target Delivery Time</label>
                                        <input type="text" name="dtime" value="<?php echo htmlspecialchars($data["dtime"]); ?>" class="form-control rounded-3">
                                    </div>
                                    
                                    <!-- City Route Input Areas -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="form-label small text-uppercase fw-bold text-danger"><i class="fa fa-map-marker-alt me-1"></i>Live Map Origin (Route From)</label>
                                                <input type="text" name="lon" value="<?php echo htmlspecialchars($data["lon"]); ?>" class="form-control border-danger-subtle rounded-3" placeholder="e.g. New York, USA" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="form-label small text-uppercase fw-bold text-success"><i class="fa fa-flag-checkered me-1"></i>Live Map Destination (Route To)</label>
                                                <input type="text" name="lat" value="<?php echo htmlspecialchars($data["lat"]); ?>" class="form-control border-success-subtle rounded-3" placeholder="e.g. Dallas, Texas, USA" required>
                                            </div>
                                        </div>
                                    </div>
                                    <small class="text-muted d-block mt-1"><i class="fa fa-info-circle me-1"></i>Type actual cities or states. OpenStreetMap will find them and draw a visual route map.</small>
                                </div>
                            </div>

                            <!-- SECTION 4: Description -->
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <div class="form-group mb-4">
                                        <label class="form-label small text-uppercase fw-bold text-muted">Detailed Log Comments</label>
                                        <textarea class="form-control rounded-4 p-3" name="descrip" rows="5" placeholder="Detailed shipment comments..." style="height: 120px;"><?php echo htmlspecialchars($data["descrip"]); ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <hr class="border-light-subtle my-4">

                            <!-- Actions -->
                            <div class="d-flex justify-content-end gap-2">
                                <a href="manage_tracking.php" class="btn btn-outline-secondary px-4 py-2"><i class="fa fa-times me-2"></i>Cancel</a>
                                <button type="submit" name="save" class="btn btn-primary px-5 py-2 fw-semibold"><i class="fa fa-save me-2"></i>Save Tracking Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</div>