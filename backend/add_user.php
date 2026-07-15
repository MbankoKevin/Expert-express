<?php 
include 'conn.php';

// Check connection stability
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

$alert_message = "";

// Handle POST payload submission 
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save'])){
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($email)) {
        $alert_message = "<div class='alert alert-danger'><strong>Failed!</strong> Email Cannot Be Empty.</div>";
    } elseif (empty($username)) {
        $alert_message = "<div class='alert alert-danger'><strong>Failed!</strong> Username Cannot Be Empty.</div>";
    } elseif (empty($password)) {
        $alert_message = "<div class='alert alert-danger'><strong>Failed!</strong> Password Cannot Be Empty.</div>";
    } else {
        // Secure prepared statement to prevent SQL injection bugs
        $sql = "INSERT INTO users (email, username, password) VALUES (?, ?, md5(?))";
        
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "sss", $email, $username, $password);
            
            if(mysqli_stmt_execute($stmt)){
                $alert_message = "<div class='alert alert-success'><strong>Success!</strong> New User Successfully Created.</div>";
            } else {
                $alert_message = "<div class='alert alert-danger'><strong>ERROR:</strong> Could not execute query: " . mysqli_error($link) . "</div>";
            }
            mysqli_stmt_close($stmt);
        }
    }
}
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
            <div class="col-md-3"></div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">User Registration Form</h4>
                        
                        <!-- Dynamic Status Feedback Insertion -->
                        <?php echo $alert_message; ?>

                        <div class="basic-form">
                            <form action="" method="post">
                                <div class="form-group">
                                    <label>Email address</label>
                                    <input type="email" name="email" class="form-control" placeholder="Email" required>
                                </div>
                                <div class="form-group">
                                    <label>User Name</label>
                                    <input type="text" name="username" class="form-control" placeholder="Username" required>
                                </div>
                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox"> Check me out
                                    </label>
                                </div>
                                <button type="submit" name="save" class="btn btn-primary mt-2">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3"></div>
        </div>
    </div>
</div>

<?php 
// Close connection at the very end
mysqli_close($link);
include 'footer.php'; 
?>