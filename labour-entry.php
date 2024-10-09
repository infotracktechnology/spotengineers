<?php
ob_start();
session_start();
if (!isset($_SESSION['username'])) {
    header("location:index.php");
    exit;
}
include "config.php";
// $id = $_GET['id'];
// $purchases = $con->query("SELECT a.*, b.supplier_name, b.city FROM purchase a INNER JOIN suppliers b ON a.supplier = b.supplier_id WHERE a.purchase_id = $id GROUP BY a.purchase_id")->fetch_object();
// $purchase_items = $con->query("SELECT a.*, b.name, b.hsn, b.brand, (a.quantity) AS max_qty, b.item_id FROM purchase_items a INNER JOIN items b ON a.item_id = b.item_id WHERE a.purchase_id = $id GROUP BY a.item_id")->fetch_all(MYSQLI_ASSOC);

// $purchasedQuantities = [];
// foreach ($purchase_items as $row) {
//     $purchasedQuantities[$row['item_id']] = $row['max_qty'];
// }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport" />
    <title>Labour Entry</title>
    <!-- General CSS Files -->
    <link rel="stylesheet" href="assets/css/app.min.css" />
    <link rel="stylesheet" href="assets/bundles/summernote/summernote-bs4.css" />
    <link rel="stylesheet" href="assets/bundles/jquery-selectric/selectric.css" />
    <link rel="stylesheet" href="assets/bundles/bootstrap-tagsinput/dist/bootstrap-tagsinput.css" />
    <!-- Template CSS -->
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="stylesheet" href="assets/css/components.css" />
    <!-- Custom style CSS -->
    <link rel="stylesheet" href="assets/css/custom.css" />
    <link rel="stylesheet" href="assets/bundles/datatables/datatables.min.css" />
    <link rel="stylesheet" href="assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css" />
    <link rel="stylesheet" href="assets/bundles/select2/dist/css/select2.min.css" />
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.ico" />
    
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.8.2/angular.min.js"></script> -->
</head>

<body>
    <div class="loader"></div>
    <div id="app" ng-app="myApp" ng-controller="issueController">
        <div class="main-wrapper main-wrapper-1">
            <?php require('sidebar.php'); ?>
            <!-- Main Content -->
            <div class="main-content">
                <section class="section">
                    <div class="section-body">
                        <div class="row">
                            <div class="col-md-12">
                            <form method="post" name="myForm" action="labour-entry.php" enctype="multipart/form-data">
    <div class="card card-primary">
        <div class="card-header">
            <h4 class="col-deep-purple m-0">Labour Entry</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-2 form-group">
                    <label class="col-blue">job no</label>
                    <input type="number" name="job_no" class="form-control form-control-sm" value="1" required />
                </div>
                <div class="col-md-2 form-group">
                    <label class="col-blue">Service Date</label>
                    <input type="date" name="service_date" value="" class="form-control form-control-sm" required />
                </div>
                <div class="col-md-2 form-group">
                    <label class="col-blue">Customer</label>
                    <input type="text" name="Customer" id="Customer" class="form-control form-control-sm" value="" required />
                </div>
                
                <div class="col-md-2 form-group">
                    <label class="col-blue">C.City</label>
                    <input type="text" value="" name="c.city" class="form-control form-control-sm" required />
                </div>
                <div class="col-md-3 form-group">
                    <label class="col-blue">GST No</label>
                    <input type="text" value="" name="gst_no" class="form-control form-control-sm" readonly />
                </div>
                <div class="col-md-3 form-group">
                    <label class="col-blue">Technician</label>
                    <input type="text" value="" name="technician" class="form-control form-control-sm" required />
                </div>


                                                <div class="col-md-12 form-group m-0">
                                                    <h6 class="col-deep-purple m-0"></h6>
                                                    <hr class="bg-dark-gray" />
                                                
                  
                    <div class="row">
                    <div class="col-md-1 form-group">
                    <label class="col-blue">S.No</label>
                    <input type="text" class="form-control form-control-sm" id="S.No"  min="1" />
                </div>


                <div class="col-md-3 form-group">
                    <label class="col-blue">Brand/Spares</label>
                    <select class="form-control form-control-sm select2" id="parts">
                        <option value="">Select Parts</option>
                        <?php foreach ($purchase_items as $row) { ?>
                            <option value="<?php echo $row['item_id']; ?>"><?php echo $row['item_id'].'-'.$row['name'].'/'.$row['brand']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-3 form-group">
                    <label class="col-blue">Work</label>
                    <input type="text" class="form-control form-control-sm" id="Work"  min="1" />
                </div>
                <div class="col-md-2 form-group">
                    <label class="col-blue">Qty</label>
                    <input type="number" class="form-control form-control-sm" id="Qty" />
                </div>
                <div class="col-md-2 form-group">
                    <label class="col-blue">Rate</label>
                    <input type="number" min="0" step="any" id="taxPercentage" class="form-control form-control-sm"  />
                </div>
               
                <div class="col-md-2 form-group">
                    <label class="col-blue">Total</label>
                    <input type="text"  id="total" class="form-control form-control-sm" readonly />
                </div>
                <div class="col-md-1 form-group">
                    <button type="button" class="btn btn-warning mt-3 btn-lg px-3 py-2" id="addItemButton">
                        <i class="fa fa-plus small-icon"></i>
                    </button>
                </div>
            </div>

                                                <div class="col-md-12 table-responsive form-group">
                                                    <table class="table table-sm table-striped text-right" id="itemsTable">
                                                        <thead>
                                                            
                                                            <tr>
                                                                <th>S.No</th>
                                                                <th>Appliance</th>
                                                                <th>work</th>
                                                                <th>Qty</th>
                                                                <th>Rate</th>
                                                                <th>Total</th>
                                                                <th>Action</th>
                                                            </tr>
                                                           
                                                        </thead>
                                                        <tbody>

                                                        </tbody>

                                                    </table>
                                                  
                                                    <hr>
                                                    <div class="row">
                    <div class="col-md-3 form-group">
                        <label class="col-blue"> Total Price: </label>
                        <input type="hidden" name="total_price" value="" id="total_price" />
                        <span id="mrpTotal">0.00</span>
                    </div>
                   
                    <div class="col-md-3 form-group">
                        <label class="col-blue">Grand Total: </label>
                        <input type="hidden" name="grand_total" value="" id="grand_total" />
                        <span id="overallTotal">0.00</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 form-group">
                                                    <button type="submit" ng-disabled="myForm.$submitted" class="btn btn-success">Submit</button>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
    <script src="assets/js/app.min.js"></script>
    <script src="assets/js/scripts.js"></script>
    <script src="assets/js/custom.js"></script>
    <script src="assets/bundles/select2/dist/js/select2.full.min.js"></script>
    <script src="assets/bundles/datatables/datatables.min.js"></script>
    <script src="assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>
   
    <script>
                       
</script>



 

</body>

</html>