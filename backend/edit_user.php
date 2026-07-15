<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>
        <!-- End Left Sidebar  -->
        <!-- Page wrapper  -->
        <div class="page-wrapper">
            <!-- Bread crumb -->
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-primary">Dashboard</h3> </div>
                <div class="col-md-7 align-self-center">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
            <!-- End Bread crumb -->
            <!-- Container fluid  -->
            <div class="container-fluid">
                <!-- Start Page Content -->
                <div class="row">
                    <div class="col-md-3">
   
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-title">
                                <h4>update User Form</h4>
                <?php
/* Attempt MySQL server connection. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
include 'conn.php';

// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
if(isset($_POST['save'])){
    $id1 = $_POST['id1'];
       $email = $_POST['email'];
       $username = $_POST['username'];
       $password = md5($_POST['password']);


        $sql =  "UPDATE users SET email='$email',username='$username',password='$password'  WHERE user_id='$id1' ";
        if(mysqli_query($link, $sql)){
            echo "<div class='alert alert-success'>
            <strong>Success!</strong> User Successfully Updated.
            </div>";
        } else{
            echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
        }
    // }
}
// Close connection
mysqli_close($link);
?>
<?php 
include 'conn.php';
if($_GET['id']) {
    $id = $_GET['id'];

    $sql = "SELECT * FROM users WHERE user_id = {$id}";
    $result = $link->query($sql);

    $data = $result->fetch_assoc();

}

?>

                            </div>
                            <div class="card-body">
                                <div class="basic-form">
                                       <form action="" method="post">
                                              <div class="form-group">
                                            <input type="hidden" name="id1" value="<?php echo $data["user_id"];?>" class="form-control" placeholder="Email">
                                        </div>
                                        <div class="form-group">
                                            <label>Email address</label>
                                            <input type="email" name="email" value="<?php echo $data["email"];?>" autocomplete="off" class="form-control" placeholder="Email">
                                        </div>
                                        <div class="form-group">
                                            <label>User Name</label>
                                            <input type="text" value="<?php echo $data["username"];?>" autocomplete="off" name="username" class="form-control" placeholder="Username">
                                        </div>
                                        <div class="form-group">
                                            <label>Password</label>
                                            <input type="password" name="password" autocomplete="off" class="form-control" placeholder="Enter new password">
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                    <input type="checkbox"> Check me out
                                                </label>
                                        </div>
                                        <button type="submit" name="save" class="btn btn-primary">Submit</button>
                                    </form>
                                </div>
                            </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-3">
   
                    </div>
                </div>







                <!-- End PAge Content -->
<?php include 'footer.php'; ?>