<?php
ob_start();
session_start();
include "config.php";
if(!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

$cyear = $_SESSION['cyear'];
$biil_no = $con->query("SELECT max(bill_no)bill_no FROM bills WHERE cyear = '$cyear'")->fetch_array();
$bill_no = $biil_no['bill_no'] ? $biil_no['bill_no']+1 : 1;

$bill_by = isset($_GET['bill_by']) ? $_POST['bill_by'] : '';
$value = isset($_GET['value']) ? $_POST['value'] : '';
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport" />
    <title>Bills | Spot Engineers</title>
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
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.ico" />
    <script src="//unpkg.com/alpinejs" defer></script>
</head>

<body>
    <div class="loader"></div>
    <div id="app" ng-app="myApp" ng-controller="issueController">
        <div class="main-wrapper main-wrapper-1">
            <?php require('sidebar.php'); ?>
            <!-- Main Content -->
            <div class="main-content" x-data="app">
                <section class="section">
                    <div class="section-body">
                        <div class="row">
                            <div class="col-md-12">
                            <form method="post" name="myForm" action="bills_create.php" enctype="multipart/form-data">
                                    <div class="card card-primary">
                                        <div class="card-header">
                                            <h4 class="col-deep-purple m-0">Generate Bill</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-2 form-group">
                                                    <label class="col-blue">Bill No</label>
                                                    <input type="number" name="bill_no" class="form-control form-control-sm" value="<?php echo $bill_no; ?>" readonly />
                                                </div>

                                                <div class="col-md-4 form-group">
                                                <label class="col-blue">Bill By </label>
                                                <select name="search" class="form-control form-control-sm" required>
                                                    <option value="">Select Search</option>
                                                    <option value="Sale" <?= ($bill_by == 'Sale') ? 'selected' : ''; ?>>Sale No</option>
                                                    <!-- <option value="Job">Job No</option> -->
                                                </select>
                                                </div>

                                                <div class="col-md-2 form-group">
                                                    <label class="col-blue">Value To Search</label>
                                                    <input type="number" name="value" placeholder="Value eg: 1" class="form-control form-control-sm" value="<?php echo $value; ?>"  required/>
                                                </div>

                                            <div class="col-md-12">
                                                <button type="submit" name="submit" class="btn btn-primary">Generate Bill</button>
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
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    <script>

</script>

</body>

</html>