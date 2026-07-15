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
                                <?php 
include 'conn.php';
if($_GET['id']) {
    $id = $_GET['id'];

    $sql = "SELECT * FROM track WHERE track_id = {$id}";
    $result = $link->query($sql);

    $data = $result->fetch_assoc();

}

?>
                <!-- Start Page Content -->
                <h3>Complete Overview Of Shipment Number <?php echo $data["ship_id"];?></h3>

                <div class="row">
                    <div class="col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title" align="center">jk Name</h4>
                                <div class="card-content">
                                    <div class="sweetalert m-t-15" align="center">
                                        <p><?php echo $data["jname"];?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /# card -->
                    </div>
                    <!-- /# column -->


                    <div class="col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title" align="center">jk Address</h4>
                                <div class="card-content">
                                    <div class="sweetalert m-t-15"  align="center">
                                       <p><?php echo $data["jadd"];?></p> 
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /# card -->
                    </div>
                    <!-- /# column -->


                    <div class="col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title" align="center">Jk country</h4>
                                <div class="card-content">
                                    <div class="sweetalert m-t-15" align="center">
                                        <p><?php echo $data["jcountry"];?></p> 
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /# card -->
                    </div>
                    <!-- /# column -->


                    <div class="col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title" align="center">Seller Name</h4>
                                <div class="card-content">
                                    <div class="sweetalert m-t-15" align="center">
                                        <p><?php echo $data["sname"];?></p> 
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /# card -->
                    </div>
                    <!-- /# column -->

                </div>





                                <div class="row">
                    <div class="col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title" align="center">Product Name</h4>
                                <div class="card-content">
                                    <div class="sweetalert m-t-15" align="center">
                                        <p><?php echo $data["prod"];?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /# card -->
                    </div>
                    <!-- /# column -->


                    <div class="col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title" align="center">Tranport Mode</h4>
                                <div class="card-content">
                                    <div class="sweetalert m-t-15"  align="center">
                                       <p><?php echo $data["mode"];?></p> 
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /# card -->
                    </div>
                    <!-- /# column -->


                    <div class="col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title" align="center">Shipped Date</h4>
                                <div class="card-content">
                                    <div class="sweetalert m-t-15" align="center">
                                        <p><?php echo $data["Ship_date"];?></p> 
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /# card -->
                    </div>
                    <!-- /# column -->


                    <div class="col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title" align="center">Delivery Date</h4>
                                <div class="card-content">
                                    <div class="sweetalert m-t-15" align="center">
                                        <p><?php echo $data["ddate"];?></p> 
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /# card -->
                    </div>
                    <!-- /# column -->

                </div>



                <div class="row">
                    <div class="col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title" align="center">Current Location</h4>
                                <div class="card-content">
                                    <div class="sweetalert m-t-15" align="center">
                                        <p><?php echo $data["currentl"];?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /# card -->
                    </div>
                    <!-- /# column -->


                    <div class="col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title" align="center">Pickup Location</h4>
                                <div class="card-content">
                                    <div class="sweetalert m-t-15"  align="center">
                                       <p><?php echo $data["pickupl"];?></p> 
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /# card -->
                    </div>
                    <!-- /# column -->


                    <div class="col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title" align="center">Shipment Status</h4>
                                <div class="card-content">
                                    <div class="sweetalert m-t-15" align="center">
                                        <p><?php echo $data["status"];?></p> 
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /# card -->
                    </div>
                    <!-- /# column -->


                    <div class="col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title" align="center">Delivery Status</h4>
                                <div class="card-content">
                                    <div class="sweetalert m-t-15" align="center">
                                        <p><?php echo $data["ddate"];?></p> 
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /# card -->
                    </div>
                    <!-- /# column -->

                </div>




                <div class="row">
                    <div class="col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title" align="center">Shipment Category</h4>
                                <div class="card-content">
                                    <div class="sweetalert m-t-15" align="center">
                                        <p><?php echo $data["cat"];?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /# card -->
                    </div>
                    <!-- /# column -->


                    <div class="col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title" align="center">Pickup Location</h4>
                                <div class="card-content">
                                    <div class="sweetalert m-t-15"  align="center">
                                       <p><?php echo $data["pickupl"];?></p> 
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /# card -->
                    </div>
                    <!-- /# column -->


                    <div class="col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title" align="center">Number Of Items</h4>
                                <div class="card-content">
                                    <div class="sweetalert m-t-15" align="center">
                                        <p><?php echo $data["items"];?></p> 
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /# card -->
                    </div>
                    <!-- /# column -->


                    <div class="col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title" align="center">Tracking Number</h4>
                                <div class="card-content">
                                    <div class="sweetalert m-t-15" align="center">
                                        <p><?php echo $data["ship_id"];?></p> 
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /# card -->
                    </div>
                    <!-- /# column -->

                </div>

                                <div class="row">
                    <div class="col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title" align="center">Longitude</h4>
                                <div class="card-content">
                                    <div class="sweetalert m-t-15" align="center">
                                        <p><?php echo $data["lon"];?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /# card -->
                    </div>
                    <!-- /# column -->


                    <div class="col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title" align="center">Latitude</h4>
                                <div class="card-content">
                                    <div class="sweetalert m-t-15"  align="center">
                                       <p><?php echo $data["lat"];?></p> 
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /# card -->
                    </div>
                    <!-- /# column -->


                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title" align="center">Shipment Description</h4>
                                <div class="card-content">
                                    <div class="sweetalert m-t-15" align="center">
                                        <p><?php echo $data["descrip"];?></p> 
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /# card -->
                    </div>
                    <!-- /# column -->

                </div>










                <!-- End PAge Content -->
<?php include 'footer.php'; ?>