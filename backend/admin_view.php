<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<!-- Page wrapper  -->
<div class="page-wrapper" style="background-color: #f8fafc; min-height: 100vh; padding-top: 70px;">
    
    <!-- Bread crumb -->
    <div class="row page-titles mx-0 py-3 bg-white border-bottom border-light-subtle">
        <div class="col-md-5 align-self-center">
            <h4 class="text-dark fw-bold mb-0">User Directories</h4> 
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb m-0 justify-content-md-end bg-transparent p-0 small">
                <li class="breadcrumb-item"><a href="home.php" class="text-decoration-none">Home</a></li>
                <li class="breadcrumb-item active text-muted">View Users</li>
            </ol>
        </div>
    </div>
    <!-- End Bread crumb -->
    
    <!-- Container fluid  -->
    <div class="container-fluid py-4">
        <!-- Start Page Content -->
        <div class="row">
            <div class="col-12 max-width-1100 mx-auto">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-body p-4 bg-white">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <div>
                                <h5 class="card-title fw-bold text-dark mb-1">Administrative Registrations</h5>
                                <p class="text-muted small mb-0">Manage system profiles, account status settings, and credential authorizations.</p>
                            </div>
                            <a href="add_user.php" class="btn btn-primary btn-sm rounded-2 px-3 fw-semibold shadow-sm">
                                <i class="fa fa-user-plus me-1"></i> Add User
                            </a>
                        </div>
                        
                        <div class="table-responsive">
                            <table id="example23" class="table table-hover align-middle custom-saas-table" style="width:100%">
                                <thead class="table-light text-secondary small text-uppercase">
                                    <tr>
                                        <th class="py-3 px-4" style="border-top-left-radius: 8px; border-bottom-left-radius: 8px;">Username</th>
                                        <th class="py-3 px-4">Email Address</th>
                                        <th class="py-3 px-4 text-end" style="border-top-right-radius: 8px; border-bottom-right-radius: 8px; width: 220px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                include 'conn.php';
                                if (!$link) {
                                    die("<tr><td colspan='3' class='text-danger text-center p-4'>Database communication anomaly: " . mysqli_connect_error() . "</td></tr>");
                                }

                                $sql = "SELECT * FROM users";
                                $result = mysqli_query($link, $sql);

                                if (mysqli_num_rows($result) > 0) {
                                    while($row = mysqli_fetch_assoc($result)) {
                                ?>
                                    <tr class="border-bottom border-light-subtle">
                                        <td class="py-3 px-4 fw-semibold text-dark">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-light text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px; font-size: 0.9rem;">
                                                    <i class="fa fa-user"></i>
                                                </div>
                                                <?php echo htmlspecialchars($row["username"]); ?>
                                            </div>
                                        </td>
                                        <td class="py-3 px-4 text-muted small">
                                            <?php echo htmlspecialchars($row["email"]); ?>
                                        </td>
                                        <td class="py-3 px-4 text-end">
                                            <div class="d-inline-flex gap-2">
                                                <a class="btn btn-sm btn-outline-secondary px-2.5 rounded-2" href="edit_user.php?id=<?php echo urlencode($row["user_id"]); ?>" title="Edit User">
                                                    <i class="fa fa-pencil text-muted"></i> Edit
                                                </a>
                                                <a class="btn btn-sm btn-outline-danger px-2.5 rounded-2" href="delete_user.php?id=<?php echo urlencode($row["user_id"]); ?>" onclick="return confirm('Are you sure you want to delete this user profile? This route can not be reversed.');" title="Delete User">
                                                    <i class="fa fa-trash-o"></i> Delete
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php 
                                    }
                                } else {
                                    echo "<tr><td colspan='3' class='text-muted text-center p-5'><i class='fa fa-folder-open-o d-block fs-2 mb-2 text-black-50'></i> No active user profiles discovered in database context.</td></tr>";
                                }
                                mysqli_close($link);
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Page Content -->
    </div>
    <!-- End Container fluid  -->

    <!-- Embedded CSS Enhancements for Table States -->
    <style>
        .custom-saas-table tbody tr {
            transition: background-color 0.15s ease-in-out;
        }
        .custom-saas-table tbody tr:hover {
            background-color: #f8fafc !important;
        }
        .custom-saas-table border-light-subtle {
            border-color: #f1f5f9 !important;
        }
    </style>

<?php include 'footer.php'; ?>