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
                <?php
/* Attempt MySQL server connection. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
include 'conn.php';

// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
if(isset($_POST['save'])){
       $tracking = $_POST['tracking'];
       $from = $_POST['from'];
       $to = $_POST['to'];


        $sql =  "UPDATE track SET lon='$from',lat='$to'   WHERE ship_id='$tracking' ";
        if(mysqli_query($link, $sql)){
            echo "<div class='alert alert-success'>
            <strong>Success!</strong> Map Successfully Updated.
            </div>";
        } else{
            echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
        }
    // }
}
// Close connection
mysqli_close($link);
?>


                        </div>
                        <div class="basic-form">
                            <h3>Edit Location On Map</h3>
                            <div class="basic-form">
                           
                                <form action="" method="post" action="">
                                
                                <div class="form-group">
                                    <label><b>Tracking #</b></label>
                                    <input type="text" name="tracking" class="form-control" placeholder="" required="">
                                </div>
                                <div class="form-group">
                                    <label><b>From (Current Location)</b></label>
                                    <input type="text" name="from" class="form-control" placeholder="" required="">
                                </div>
                                <div class="form-group">
                                    <label><b>To (Destination)</b></label>
                                    <input type="text" name="to" class="form-control" placeholder="" required="">
                                </div>
                                <button type="submit" name="save" class="btn btn-primary">Mail Tracking</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-md-3">
         
        </div>
    </div>
       <?php include 'footer.php'; ?>