<?php 
// 1. Initialize session to safely access any session variables
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'conn.php';

// Check database connection integrity
if (!$link) {
    die("Connection failed: " . mysqli_connect_error());
}

// 2. Modified query to fetch ALL users from the database 
$sql = "SELECT * FROM users ORDER BY username ASC";
$result = mysqli_query($link, $sql);
?>

<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<!-- Page wrapper -->
<div class="page-wrapper">
    <!-- Bread crumb -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-primary">Dashboard</h3> 
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </div>
    </div>
    
    <!-- Container fluid -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2"></div>
            
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">List Of All Users</h4>
                        <div class="table-responsive m-t-40">
                            <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($result && mysqli_num_rows($result) > 0) {
                                        // Loop through each user record
                                        while($row = mysqli_fetch_assoc($result)) { 
                                            // Fallback to avoid errors if primary key isn't named 'user_id'
                                            $userId = isset($row['user_id']) ? $row['user_id'] : (isset($row['id']) ? $row['id'] : '');
                                            ?>
                                            <tr>
                                                <!-- Secure output strings against XSS injection -->
                                                <td><?php echo htmlspecialchars($row["username"], ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($row["email"], ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td>
                                                    <a class="btn btn-primary btn-sm" href="edit_user.php?id=<?php echo urlencode($userId); ?>">
                                                        <i class="fa fa-pencil"></i> Edit
                                                    </a>
                                                    <a class="btn btn-danger btn-sm" href="delete_user.php?id=<?php echo urlencode($userId); ?>" onclick="return confirm('Are you sure you want to delete this user?');">
                                                        <i class="fa fa-trash"></i> Delete
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php 
                                        }
                                    } else {
                                        echo "<tr><td colspan='3' class='text-center'>No users found in the system.</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-2"></div>
        </div>
    </div>
</div>

<?php 
mysqli_close($link);
include 'footer.php'; 
?>