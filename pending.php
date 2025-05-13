<?php
ob_start();
session_start();
include "config.php";

// Check user session
if (!isset($_SESSION['username'])) {
    header("location:index.php");
    exit;
}

$successMessage = $_SESSION['success'] ?? null;
$errorMessage = $_SESSION['error'] ?? null;
unset($_SESSION['error']);
unset($_SESSION['success']);

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    extract($_POST);
    $job = $con->query("INSERT INTO `followup`(`job_entry_id`, `job_no`, `proposal_date`, `call_status`, `customer_id`,`employee_id`, `customer_phone`, `remarks`) VALUES ('$job_entry_id', '$job_no', '$proposal_date', 'booked', '$customer_id','$employee_id', '$customer_phone', '$remarks');");
    if($job) {
        $_SESSION['success'] = "Successfully saved!";
        header("Location: pending.php");
        exit;
       }else {
        $_SESSION['error'] = "Error while saving!". $con->error;
        header("Location: pending.php");
        exit;
       }
    //    echo '<script>alert("Remarks Added Successfully");</script>';
    //    echo '<script>window.location.href="pending.php";</script>';
   }

// $twoMonthsAgo = date('Y-m-d', strtotime('-2 months'));
// $twoMonthsAgo = date('Y-m', strtotime('-2 months'));

$today = date('Y-m-d');

// $sql = "SELECT j.job_no, j.job_date, j.id AS job_id, c.name AS customer_name, c.id AS customer_id, c.phone AS customer_phone, e.id AS employee_id, e.name AS employee_name FROM job_entry j LEFT JOIN customer c ON j.customer_id = c.id LEFT JOIN employee e ON j.emp_id = e.id WHERE j.id NOT IN (SELECT job_entry_id FROM followup) AND status='pending' ORDER BY j.id ASC;";
// $result = $con->query($sql);

$sql = "SELECT 
c.name AS customer_name,
c.phone AS customer_phone,
f.proposal_date AS proposal_date,
f.call_status AS call_status,
f.id AS followup_id,
f.job_no AS job_no,
f.job_entry_id AS job_entry_id,
f.customer_id AS customer_id,
f.employee_id AS employee_id,
e.name AS employee_name
FROM followup f
LEFT JOIN customer c ON f.customer_id = c.id
LEFT JOIN employee e ON f.employee_id = e.id
WHERE f.id = (SELECT MAX(id)
FROM followup latest WHERE latest.job_entry_id = f.job_entry_id) AND call_status = 'pending' AND DATE_FORMAT(f.proposal_date, '%Y-%m-%d') = '$today'
ORDER BY c.name ASC;
";

$result = $con->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Pending Calls | Spot Engineers</title>
    <link rel="stylesheet" href="assets/css/app.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/components.css">
    <link rel="stylesheet" href="assets/css/custom.css">
    <link rel="stylesheet" href="assets/bundles/stepper/stepper.min.css">
    <link rel="stylesheet" href="assets/bundles/datatables/datatables.min.css">
    <link rel="stylesheet" href="assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css">
    <link rel='shortcut icon' type='image/x-icon' href='assets/img/favicon.ico' />
    <link rel="stylesheet" href="assets/bundles/select2/dist/css/select2.min.css">
    <style>
        .toast {
    min-width: 300px;
}

.toast-header {
    color: white;
}

.bg-success {
    background-color: #28a745 !important;
}

.bg-danger {
    background-color: #dc3545 !important;
}

.bg-warning {
    background-color: #ffc107 !important;
}

.bg-info {
    background-color: #17a2b8 !important;
}
    </style>
</head>

<body>
    <div class="loader"></div>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            <?php require('sidebar.php'); ?>
            <div class="main-content">
                <section class="section">
                    <div class="section-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h4 class="col-deep-purple m-0">Pending Calls</h4>
                                    </div>
                                    <div class="card-body">
                                      
                                        <div class="table-responsive">
                                            <table class="table table-striped table-sm" id="tableExport" style="width:100%;">
                                                <thead class="tableHeader">
                                                    <tr>
                                                        <th>S.No</th>
                                                        <th>Job #</th>
                                                        <th>Proposal Date</th>
                                                        <th>Customer Name</th>
                                                        <th>Customer Phone</th>
                                                        <th>Employee Name</th>
                                                        <th>Move To Booked</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="tableBody">
                                                    <?php
                                                    $i = 1;
                                                    while ($row = $result->fetch_assoc()) {
                                                        echo "<tr>
                                                            <td>" . $i . "</td>
                                                            <td>" . $row['job_no'] . "</td>
                                                            <td>" . $row['proposal_date'] . "</td>
                                                            <td>" . $row['customer_name'] . "</td>
                                                            <td>" . $row['customer_phone'] . "</td>
                                                            <td>" . $row['employee_name'] . "</td>
                                                            <td>
                                                            <button type='button' class='btn btn-danger btn pending-btn' 
                                                                    data-toggle='modal' 
                                                                    data-customerid='" . $row['customer_id'] . "'
                                                                    data-employeeid='" . $row['employee_id'] . "'
                                                                    data-target='#callModal'
                                                                    data-phone='" . $row['customer_phone'] . "'
                                                                    data-job_no='" . $row['job_no'] . "'
                                                                    data-job_entry_id='" . $row['job_entry_id'] . "'
                                                                    data-followup_id='" . $row['followup_id'] . "'>
                                                                    <i class='fas fa-exchange-alt' style='font-size:16px; vertical-align:middle'></i>
                                                            </
                                                        </tr>";
                                                        $i++;
                                                    }
                                                    ?>
                                                </tbody>

                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
<div class="modal fade" id="callModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Call Remarks</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="pending.php" method="POST" id="callForm">

            <div class="modal-body">
                <div class="form-group">
                <label>Job #: <span id="modalJobNumber" class="font-weight-bold"></span></label><br>
                </div>

                <div class="form-group date_div">
                <label for="proposalDate">Proposal Date <span class="text-danger">*</span></label>
                <input type="date" min="<?php echo date('Y-m-d'); ?>" name="proposal_date" class="form-control" id="proposalDate" value="<?php echo date('Y-m-d'); ?>" required>
                    <input type="hidden" id="modalCustomerId" name="customer_id" value="">
                    <input type="hidden" id="modalEmployeeId" name="employee_id" value="">
                    <input type="hidden" id="modalphone" name="customer_phone" value="">
                    <input type="hidden" id="modaljob" name="job_no" value="">
                    <input type="hidden" id="modaljob_id" name="job_entry_id" value="">
                </div>

                <div class="form-group remarks_div">
                    <label for="feedbackText">Remarks <span class="text-danger call-remarks">*</span></label>
                    <textarea class="form-control" id="feedbackText" name="remarks" rows="3" placeholder="Enter Remarks" required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="saveFeedback">Send To Assignment</button>
            </div>
            </form>
        </div>
    </div>
</div>
<div aria-live="polite" aria-atomic="true" style="position: fixed; top: 20px; right: 20px; z-index: 9999">
    <div id="toastNotification" class="toast" data-delay="3000" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="mr-auto">Notification</strong>
            <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="toast-body"></div>
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
    <script>
$(document).ready(function() {
    $('.pending-btn').click(function() {
        var phone = $(this).data('phone');
        var job = $(this).data('job_no');
        var customer_id = $(this).data('customerid');
        var employee_id = $(this).data('employeeid');
        var job_id = $(this).data('job_entry_id');
        $('#modalphone').val(phone); 
        $('#modalCustomerId').val(customer_id); 
        $('#modalEmployeeId').val(employee_id);
        $('#modalJobNumber').text(job);
        $('#modaljob').val(job);
        $('#modaljob_id').val(job_id);
    });

    $('#callForm').on('submit', function(e) {
            const submitDate = $('#proposalDate');
            const selectedDate = new Date(submitDate.val());
            const minDate = new Date('<?php echo date('Y-m-d'); ?>');
            if(selectedDate < minDate) {
                showToast('Error', 'Proposal Date should not be lesser', 'danger');
                e.preventDefault();
                return;
        }
        
    });

        <?php if ($successMessage): ?>
        showToast('Success', '<?php echo $successMessage; ?>', 'success');
        <?php endif; ?>

    function showToast(title, message, type = 'success') {
    const toast = $('#toastNotification');
    
    toast.find('.toast-header strong').text(title);
    toast.find('.toast-body').text(message);
    
    toast.find('.toast-header').removeClass('bg-success bg-danger bg-warning bg-info');
    toast.find('.toast-header').addClass('bg-' + type);
    
    toast.toast('show');
    
    setTimeout(() => {
        toast.toast('hide');
    }, 3000);
}

    $('#tableExport').DataTable({
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                exportOptions: {
                    columns: ':not(:last-child)'
                }
            },
            {
                extend: 'pdf',
                exportOptions: {
                    columns: ':not(:last-child)'
                }
            }
        ]
    });
});
</script>
</body>
</html>