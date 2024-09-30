<?php
ob_start();
session_start();
if (!isset($_SESSION['username'])) {
    header("location:index.php");
    exit;
}
 
include "config.php";
$id = $_GET['id'];
$suppliers = [];
$query = "SELECT * FROM suppliers WHERE supplier_id = '$id'";
$result = mysqli_query($con, $query);
if ($result) {
    while ($row = mysqli_fetch_object($result)) {
        $suppliers[] = $row;
    }
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
                                        <h4 class="col-deep-purple m-0">Update Supplier Details</h4>
                                    </div>
                                    <div class="card-body">
                                        <form id="myForm" method="post" action="supplier-update.php?id=<?= $id; ?>" enctype="multipart/form-data">
                                            <div class="row">
                                                <div class="col-md-3 form-group">
                                                    <label class="col-blue">Supplier Name</label>
                                                    <span class="text-danger">*</span>
                                                    <input type="text" name="Supplier_Name" value="<?= $suppliers[0]->supplier_name; ?>" class="form-control form-control-sm" required>
                                                </div>
                                                <div class="col-md-3 form-group">
                                                    <label class="col-blue">Mobile NO 1</label>
                                                    <span class="text-danger">*</span>
                                                    <input type="number" name="Mobile_one" value="<?= $suppliers[0]->mobile1; ?>" class="form-control form-control-sm" required>
                                                </div>
                                                <div class="col-md-3 form-group">
                                                    <label class="col-blue">Mobile NO 2</label>
                                                    <input type="number" name="Mobile_two" value="<?= $suppliers[0]->mobile2; ?>" class="form-control form-control-sm">
                                                </div>
                                                <div class="col-md-3 form-group">
                                                    <label class="col-blue">Landline No</label>
                                                    <input type="number" name="Landline" value="<?= $suppliers[0]->landline; ?>" class="form-control form-control-sm">
                                                </div>
                                                <div class="col-md-3 form-group">
                                                    <label class="col-blue">Fax</label>
                                                    <input type="text" name="Fax" value="<?= $suppliers[0]->fax; ?>" class="form-control form-control-sm">
                                                </div>
                                                <div class="col-md-3 form-group">
                                                    <label class="col-blue">Mail ID</label>
                                                    <input type="text" name="Mail" value="<?= $suppliers[0]->mail; ?>" class="form-control form-control-sm">
                                                </div>
                                                <div class="col-md-3 form-group">
                                                    <label class="col-blue">Website</label>
                                                    <input type="text" name="Website" value="<?= $suppliers[0]->website; ?>" class="form-control form-control-sm">
                                                </div>
                                                <div class="col-md-12 form-group m-0">
                                                    <h6 class="col-deep-purple m-0">Address Details</h6>
                                                    <hr class="bg-dark-gray" />
                                                </div>
                                                <div class="col-md-3 form-group">
                                                    <label class="col-blue"> Address Line 1</label>
                                                    <span class="text-danger">*</span>
                                                    <input type="text" name="Address1" value="<?= $suppliers[0]->address1; ?>" class="form-control form-control-sm required">
                                                </div>
                                                <div class="col-md-3 form-group">
                                                    <label class="col-blue"> Address Line 2</label>
                                                    <input type="text" name="Address2" value="<?= $suppliers[0]->address2; ?>" class="form-control form-control-sm">
                                                </div>
                                                <div class="col-md-3 form-group">
                                                    <label class="col-blue">State</label>
                                                    <span class="text-danger">*</span>
                                                    <select type="text" name="State" value="" class="form-control form-control-sm select2">
                                                        <?php
                                                        foreach ($states as $state) {
                                                        ?>
                                                            <option value="<?php echo $state->State; ?>" <?= $suppliers[0]->state == $state->State ? 'selected' : ''; ?>><?php echo $state->State; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-3 form-group">
                                                    <label class="col-blue">District</label>
                                                    <span class="text-danger">*</span>
                                                    <select type="text" name="District" value="" class="form-control form-control-sm select2">
                                                        
                                                        <?php
                                                        foreach ($districts as $district) {
                                                        ?>
                                                            <option value="<?php echo $district->District; ?>" <?= $suppliers[0]->district == $district->District ? 'selected' : ''; ?>><?php echo $district->District; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-3 form-group">
                                                    <label class="col-blue"> City</label>
                                                    <span class="text-danger">*</span>
                                                    <input type="text" name="City" value="<?= $suppliers[0]->city; ?>" class="form-control form-control-sm">
                                                </div>
                                                <div class="col-md-3 form-group">
                                                    <label class="col-blue">Pincode</label>
                                                    <span class="text-danger">*</span>
                                                    <input type="number" name="Pincode" value="<?= $suppliers[0]->pincode; ?>" class="form-control form-control-sm">
                                                </div>
                                                <div class="col-md-12 form-group m-0">
                                                    <h6 class="col-deep-purple m-0">Bank Details</h6>
                                                    <hr class="bg-dark-gray" />
                                                </div>
                                                <div class="col-md-3 form-group">
                                                    <label class="col-blue">Account No</label>
                                                    <input type="number" name="Account_no" value="<?= $suppliers[0]->account_no; ?>" class="form-control form-control-sm">
                                                </div>
                                                <div class="col-md-3 form-group">
                                                    <label class="col-blue">Bank Name</label>
                                                    <input type="text" name="Bank_name" value="<?= $suppliers[0]->bank_name; ?>" class="form-control form-control-sm">
                                                </div>
                                                <div class="col-md-3 form-group">
                                                    <label class="col-blue text-capitalize">IFSC</label>
                                                    <input type="text" name="IFSC" value="<?= $suppliers[0]->ifsc; ?>" class="form-control form-control-sm">
                                                </div>
                                                <div class="col-md-3 form-group">
                                                    <label class="col-blue">Branch Name</label>
                                                    <input type="text" name="Branch_name" value="<?= $suppliers[0]->branch_name; ?>" class="form-control form-control-sm">
                                                </div>
                                                <div class="col-md-3 form-group">
                                                    <label class="col-blue">GST NO</label>
                                                    <input type="text" name="GST" value="<?= $suppliers[0]->gst; ?>" class="form-control form-control-sm" />
                                                </div>
                                                <div class="col-md-3 mt-4 form-group">
                                                    <button type="submit" class="btn btn-primary" name="submit">Update</button>
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
</body>

</html>