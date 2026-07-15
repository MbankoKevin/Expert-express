<?php
// 1. Business Logic & Dependencies
include 'header.php';
include 'sidebar.php';
include 'conn.php';

// Check connection
if (!$link) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch tracking data securely
$sql = "SELECT track_id, jname, ship_id, ddate, pickupl FROM track";
$result = mysqli_query($link, $sql);
?>

<div class="page-wrapper py-4">
    
    <div class="container-fluid mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h3 class="text-dark fw-bold mb-0">Dashboard</h3>
            </div>
            <div class="col-md-6 d-flex justify-content-md-end mt-2 mt-md-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 bg-transparent p-0">
                        <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    
    <div class="container-fluid">
        
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm bg-primary text-white p-4">
                    <div class="card-body">
                        <h1 class="fw-bold mb-1">Welcome To Admin Dashboard</h1>
                        <p class="mb-0 opacity-75">Manage, track, and control system shipments seamlessly.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                        <h5 class="card-title fw-bold text-secondary mb-0">List Of All Tracking</h5>
                        <span class="badge bg-secondary-subtle text-secondary px-2.5 py-1.5 rounded-pill fs-7">
                            Total: <?php echo mysqli_num_rows($result); ?> entries
                        </span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table id="example23" class="table table-hover table-striped align-middle mb-0" style="width:100%">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Jk Name</th>
                                        <th>Tracking ID</th>
                                        <th>Date</th>
                                        <th>Pickup Location</th>
                                        <th class="text-end pe-4">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($result && mysqli_num_rows($result) > 0): ?>
                                        <?php while($row = mysqli_fetch_assoc($result)): ?>
                                            <tr>
                                                <td class="ps-4 fw-semibold text-dark"><?php echo htmlspecialchars($row["jname"], ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><span class="font-monospace text-muted"><?php echo htmlspecialchars($row["ship_id"], ENT_QUOTES, 'UTF-8'); ?></span></td>
                                                <td><?php echo htmlspecialchars($row["ddate"], ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($row["pickupl"], ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td class="text-end pe-4">
                                                    <div class="btn-group btn-group-sm" role="group" aria-label="Tracking actions">
                                                        <a class="btn btn-outline-primary" href="view_full.php?id=<?php echo urlencode($row["track_id"]); ?>" title="View details">
                                                            <i class="bi bi-eye"></i> View
                                                        </a>
                                                        <a class="btn btn-outline-success" href="edit_tracking.php?id=<?php echo urlencode($row["track_id"]); ?>" title="Edit record">
                                                            <i class="bi bi-pencil"></i> Edit
                                                        </a>
                                                        <a class="btn btn-outline-danger" href="delete_track.php?id=<?php echo urlencode($row["track_id"]); ?>" onclick="return confirm('Are you sure you want to delete this track record?');" title="Delete record">
                                                            <i class="bi bi-trash"></i> Delete
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="text-center py-5 text-muted">
                                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                                No tracking records found.
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div> </div> </div> </div> <?php 
// Close connection and render footer
mysqli_close($link);
include 'footer.php'; 
?>