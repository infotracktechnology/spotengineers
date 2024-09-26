<?php 
ob_start();
session_start();
include "config.php";
if(!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}
$states = [];
$query = "SELECT * FROM district_list GROUP BY State ORDER BY State ASC";
$result = mysqli_query($con, $query);

if ($result) {
    while ($row = mysqli_fetch_object($result)) {
        $states[] = $row;
    }
}

$districts = [];
$query = "SELECT * FROM district_list ORDER BY District ASC";
$result = mysqli_query($con, $query);

if ($result) {
    while ($row = mysqli_fetch_object($result)) {
        $districts[] = $row;
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Supplier Master</title>
    <!-- General CSS Files -->
    <link rel="stylesheet" href="assets/css/app.min.css">
    <!-- Template CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/components.css">
    <!-- Custom style CSS -->
    <link rel="stylesheet" href="assets/css/custom.css">
    <link rel="stylesheet" href="assets/bundles/stepper/stepper.min.css">
    <link rel="stylesheet" href="assets/bundles/datatables/datatables.min.css">
    <link rel="stylesheet" href="assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css">
    <link rel='shortcut icon' type='image/x-icon' href='assets/img/favicon.ico' />
    <link rel="stylesheet" href="assets/bundles/select2/dist/css/select2.min.css">

    <!-- Jquery Library -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div class="loader"></div>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            <?php require('sidebar.php'); ?>
            <!-- Main Content -->
            <div class="main-content">
                <section class="section">
                    <div class="section-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h4 class="col-deep-purple m-0">Supplier Details</h4>
                                    </div>
                                    <div class="card-body">
                                        <form id="myForm" method="post" action="supplier-store.php" enctype="multipart/form-data">
                                            <div class="row">
                                                <div class="col-md-3 form-group">
                                                    <label class="col-blue">Supplier Name</label>
                                                    <span class="text-danger">*</span>
                                                    <input type="text" name="Supplier_Name" class="form-control form-control-sm" required>
                                                    <div class="invalid-feedback">Please Enter the Name.</div>
                                                </div>
                                                <div class="col-md-3 form-group">
                                                    <label class="col-blue">Mobile NO 1</label>
                                                    <span class="text-danger">*</span>
                                                    <input type="number" name="Mobile_one" value="" class="form-control form-control-sm" required maxlength="999999999">
                                                    <div class="invalid-feedback">Please Enter 10 digits Mobile Number.</div>
                                                </div>
                                                <div class="col-md-3 form-group">
                                                    <label class="col-blue">Mobile NO 2</label>
                                                    <input type="number" name="Mobile_two" value="" class="form-control form-control-sm">
                                                    <div class="invalid-feedback">Please Enter 10 digits Mobile Number.</div>
                                                </div>
                                                <div class="col-md-3 form-group">
                                                    <label class="col-blue">Landline No</label>
                                                    <input type="number" name="Landline" value="" class="form-control form-control-sm">
                                                </div>
                                                <div class="col-md-3 form-group">
                                                    <label class="col-blue">Fax</label>
                                                    <input type="text" name="Fax" value="" class="form-control form-control-sm">
                                                </div>
                                                <div class="col-md-3 form-group">
                                                    <label class="col-blue">Mail ID</label>
                                                    <input type="text" name="Mail" value="" class="form-control form-control-sm">
                                                    <div class="invalid-feedback">Please Enter Correct Email Address.</div>
                                                </div>
                                                <div class="col-md-3 form-group">
                                                    <label class="col-blue">Website</label>
                                                    <input type="text" name="Website" value="" class="form-control form-control-sm">
                                                    <div class="invalid-feedback">Please Enter Website Address.</div>
                                                </div>
                                                <div class="col-md-12 form-group m-0">
                                                    <h6 class="col-deep-purple m-0">Address Details</h6>
                                                    <hr class="bg-dark-gray" />
                                                </div>
                                                <div class="col-md-3 form-group">
                                                    <label class="col-blue"> Address Line 1</label>
                                                    <span class="text-danger">*</span>
                                                    <input type="text" name="Address1" value="" class="form-control form-control-sm" required>
                                                    <div class="invalid-feedback">Please Enter the Address.</div>
                                                </div>
                                                <div class="col-md-3 form-group">
                                                    <label class="col-blue"> Address Line 2</label>
                                                    <input type="text" name="Address2" value="" class="form-control form-control-sm">
                                                </div>
                                                <div class="col-md-3 form-group">
                                                    <label class="col-blue">State</label>
                                                    <span class="text-danger">*</span>
                                                    <select type="text" name="State" value="" class="form-control form-control-sm select2" required>
                                                        <option value="">Select</option>
                                                        <?php
                                                        foreach ($states as $state) {
                                                        ?>
                                                            <option value="<?php echo $state->State; ?>"><?php echo $state->State; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-3 form-group">
                                                    <label class="col-blue">District</label>
                                                    <span class="text-danger">*</span>
                                                    <select type="text" name="District" value="" class="form-control form-control-sm select2" required>
                                                        <option value="">Select</option>
                                                        <?php
                                                        foreach ($districts as $district) {
                                                        ?>
                                                            <option value="<?php echo $district->District; ?>"><?php echo $district->District; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-3 form-group">
                                                    <label class="col-blue"> City</label>
                                                    <span class="text-danger">*</span>
                                                    <input type="text" name="City" value="" class="form-control form-control-sm" required>
                                                    <div class="invalid-feedback">Please Enter the City.</div>
                                                </div>
                                                <div class="col-md-3 form-group">
                                                    <label class="col-blue">Pincode</label>
                                                    <span class="text-danger">*</span>
                                                    <input type="number" name="Pincode" value="" class="form-control form-control-sm" required>
                                                    <div class="invalid-feedback">Please Enter Valid Pincode.</div>
                                                </div>
                                                <div class="col-md-12 form-group m-0">
                                                    <h6 class="col-deep-purple m-0">Bank Details</h6>
                                                    <hr class="bg-dark-gray" />
                                                </div>
                                                <div class="col-md-3 form-group">
                                                    <label class="col-blue">Account No</label>
                                                    <input type="number" name="Account_no" value="" class="form-control form-control-sm">
                                                </div>
                                                <div class="col-md-3 form-group">
                                                    <label class="col-blue">Bank Name</label>
                                                    <input type="text" name="Bank_name" value="" class="form-control form-control-sm">
                                                </div>
                                                <div class="col-md-3 form-group">
                                                    <label class="col-blue">IFSC</label>
                                                    <input type="text" name="IFSC" value="" class="form-control form-control-sm">
                                                </div>
                                                <div class="col-md-3 form-group">
                                                    <label class="col-blue">Branch Name</label>
                                                    <input type="text" name="Branch_name" value="" class="form-control form-control-sm">
                                                </div>
                                                <div class="col-md-3 form-group">
                                                    <label class="col-blue">GST NO</label>
                                                    <input type="text" name="GST" value="" class="form-control form-control-sm" >
                                                </div>
                                                <div class="col-md-3 mt-4 form-group">
                                                    <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
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
    <script src="assets/bundles/datatables/datatables.min.js"></script>
    <script src="assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>
    <script src="assets/bundles/select2/dist/js/select2.full.min.js"></script>
    <script src="assets/js/app.js"></script>
</body>

</html>