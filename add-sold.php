<?php
ob_start();
session_start();
if (!isset($_SESSION['username'])) {
    header("location:index.php");
    exit;
}
include "config.php";

$query = "SELECT id, appliance_name FROM customer_appliances WHERE customer_id = 78";
$result = $con->query($query);

if (!$result) {
    die("Error executing query: " . $con->error);
}

$appliance_list = array();

while ($row = $result->fetch_assoc()) {
    $appliance_list[] = array(
        'id' => $row['id'],
        'appliance_name' => $row['appliance_name']
    );
}

$query = "SELECT id, name, phone, type FROM customer WHERE id != 78 order by name";
$result = $con->query($query);

if (!$result) {
    die("Error executing query: " . $con->error);
}

$customer_list = array();

while ($row = $result->fetch_assoc()) {
    $customer_list[] = array(
        'id' => $row['id'],
        'name' => $row['name'],
        'phone' => $row['phone'],
        'type' => $row['type']
    );
}

$customer_json = json_encode($customer_list, JSON_UNESCAPED_UNICODE);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sold_date = $_POST['sold_date'];
    $appliance_id = $_POST['appliance_id'];
    $expense = $_POST['expense'];
    $seller_id = $_POST['seller_id'];
    $sell_amnt = $_POST['sell_amnt'];

    $sql = "INSERT INTO sold (sold_date, appliance_id, expense, seller_id, sell_amnt) 
            VALUES ('$sold_date', '$appliance_id', '$expense', '$seller_id', '$sell_amnt')";

    if (mysqli_query($con, $sql)) {
        $appliance = $con->query("UPDATE customer_appliances SET customer_id = $seller_id WHERE id = '$appliance_id'");
        $success_message = "Successfully updated";
        header("location:sold.php");
        exit;
    } else {
        $error_message = "Error updating data: " . mysqli_error($con);
        header("location:sold.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport" />
    <title>Sold </title>
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
    <script src="//unpkg.com/alpinejs" defer></script>
</head>

<body>
    <div class="loader"></div>
    <div id="app" x-data="app">
        <div class="main-wrapper main-wrapper-1">
            <?php require('sidebar.php'); ?>
            <!-- Main Content -->
            <div class="main-content">
                <section class="section">
                    <div class="section-body">
                        <div class="row">
                            <div class="col-md-12">
                                <form method="post" name="myForm" action="add-sold.php" enctype="multipart/form-data">
                                    <div class="card card-primary">
                                        <div class="card-header">
                                            <h4 class="col-deep-purple m-0">Sold Details</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">

                                                <div class="col-md-3 form-group">
                                                    <label class="col-blue">Sold Date</label>
                                                    <input type="date" name="sold_date" class="form-control form-control-sm" value="<?php echo date('Y-m-d'); ?>" required />
                                                </div>
                                                <div class="col-md-3 form-group">
                                                    <label class="col-blue">Appliance Name</label>
                                                    <select name="appliance_id" class="form-control form-control-sm" required>
                                                        <option value="">Select Appliance</option>
                                                        <?php
                                                        foreach ($appliance_list as $appliance) {
                                                            echo "<option value='" . $appliance['id'] . "'>" . $appliance['appliance_name'] . "</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-3 form-group">
                                                    <label class="col-blue">Expense</label>
                                                    <input type="number" name="expense" class="form-control form-control-sm" required />
                                                </div>

                                                <div class="col-md-3 form-group">
                                                    <label class="col-blue">Customer Phone</label>
                                                    <input type="text" id="phone" @keyup="getCustomer($el.value)" class="form-control form-control-sm" required />
                                                </div>

                                                <div class="col-md-3 form-group">
                                                    <label class="col-blue">Customer Name <a href="customer-create.php"></a></label>
                                                    <input type="text" class="form-control form-control-sm" x-model="customer_name" readonly />
                                                    <input type="hidden" name="seller_id" x-model="customer_id">
                                                </div>
                                                
                                                <div class="col-md-3 form-group">
                                                    <label class="col-blue">Customer Type</label>
                                                    <input type="text" name="customer_type" x-model="customer_type" class="form-control form-control-sm" readonly />
                                                </div>


                                                <div class="col-md-3 form-group">
                                                    <label class="col-blue">Sell Amount</label>
                                                    <input type="number" name="sell_amnt" class="form-control form-control-sm" required />
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
    <script src="assets/bundles/sweetalert/sweetalert.min.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('app', () => ({
                customer_name: '',
                customer_id: '',
                customer_type: '',
                customers: JSON.parse(`<?php echo $customer_json; ?>`),
                getCustomer(value) {
                    let customer = this.customers.find(c => c.phone == value);
                    if (customer) {
                         this.customer_name = customer.name;
                        this.customer_id = customer.id;
                        this.customer_type = customer.type;
                    } else {
                          this.customer_name = '';
                         this.customer_id = '';
                        this.customer_type = '';
                    }
                }
            }))
        })
    </script>
</body>

</html>