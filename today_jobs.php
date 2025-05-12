<?php
ob_start();
session_start();
include "config.php";

// Check user session
if (!isset($_SESSION['username'])) {
    header("location:index.php");
    exit;
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    extract($_POST);
    $job = $con->query("INSERT INTO `followup`(`job_entry_id`, `job_no`, `proposal_date`, `call_status`, `customer_id`,`employee_id`, `customer_phone`, `remarks`) VALUES ('$job_entry_id', '$job_no', '$proposal_date', 'pending', '$customer_id','$employee_id', '$customer_phone', 'pending');");
    if($job) {
        echo '<script>alert("Remarks Added Successfully");</script>';
        echo '<script>window.location.href="today_jobs.php";</script>';
       }else {
        echo '<script>alert("Something went wrong");</script>';
        echo '<script>window.location.href="today_jobs.php";</script>';
       }
   }

$today = date('Y-m-d');

$followups_sql = "SELECT 
f.proposal_date AS proposal_date,
f.job_no AS job_no,
c.name AS customer_name,
c.phone AS customer_phone,
e.name AS employee_name,
f.call_status AS call_status,
f.id AS followup_id,
f.job_no AS job_no,
f.job_entry_id AS job_entry_id,
f.customer_id AS customer_id,
f.employee_id AS employee_id
FROM followup f
LEFT JOIN customer c ON f.customer_id = c.id
LEFT JOIN employee e ON f.employee_id = e.id
WHERE f.id = (SELECT MAX(id)
FROM followup latest WHERE latest.job_entry_id = f.job_entry_id) AND call_status != 'rejected' AND DATE_FORMAT(f.proposal_date, '%Y-%m-%d') = '$today'
ORDER BY c.name ASC;";

$followups_result = $con->query($followups_sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Today Assigned Calls | Spot Engineers</title>
    <link rel="stylesheet" href="assets/css/app.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/components.css">
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
            <div class="main-content">
                <section class="section">
                    <div class="section-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h4 class="col-deep-purple m-0">Today Assigned Calls</h4>
                                    </div>
                                    <div class="card-body">
                                      
                                        <div class="table-responsive">
                                            <table class="table table-striped table-sm" id="tableExport" style="width:100%;">
                                                <thead class="tableHeader">
                                                    <tr>
                                                        <th>S.No</th>
                                                        <th>Proposal Date</th>
                                                        <th>Customer Name</th>
                                                        <th>Customer Phone</th>
                                                        <th>Employee Name</th>
                                                        <th>Job Entry</th>
                                                        <th>Move to Pending</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="tableBody">
                                                    <?php
                                                    $i = 1;
                                                    while ($row = $followups_result->fetch_assoc()) {
                                                        echo "<tr>
                                                            <td>" . $i . "</td>
                                                            <td>" . $row['proposal_date'] . "</td>
                                                            <td>" . $row['customer_name'] . "</td>
                                                            <td>" . $row['customer_phone'] . "</td>
                                                            <td>" . $row['employee_name'] . "</td>
                                                            <td><a href='labour-entry.php?customer_id=" . $row['customer_id'] . "' target='_blank' class='btn btn-primary'><i class='fas fa-external-link-alt'></i></a></td>
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
                                                                    <i class='fas fa-recycle' style='font-size:16px; vertical-align:middle'></i>
                                                            </button>
                                                        </td>
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
            <form action="today_jobs.php" method="POST">

            <div class="modal-body">
                <div class="form-group">
                <label>Job #: <span id="modalJobNumber" class="font-weight-bold"></span></label><br>
                </div>

                <div class="form-group date_div">
                    <label for="proposalDate">Proposal Date <span class="text-danger">*</span></label><input type="date" name="proposal_date" class="form-control" id="proposalDate" min="<?php echo date('Y-m-d'); ?>" value="<?php echo date('Y-m-d'); ?>" required>
                    <input type="hidden" id="modalCustomerId" name="customer_id" value="">
                    <input type="hidden" id="modalEmployeeId" name="employee_id" value="">
                    <input type="hidden" id="modalphone" name="customer_phone" value="">
                    <input type="hidden" id="modaljob_no" name="job_no" value="">
                    <input type="hidden" id="modaljob_entry_id" name="job_entry_id" value="">
                    <input type="hidden" id="modalfollowup_id" name="followup_id" value="">
                    <input type="hidden" id="modalproposal_status" name="call_status" value="">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="saveFeedback">Save Feedback</button>
            </div>
            </form>
        </div>
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
<script>
        $('.pending-btn').click(function() {
        var phone = $(this).data('phone');
        var job = $(this).data('job_no');
        var customer_id = $(this).data('customerid');
        var employee_id = $(this).data('employeeid');
        var job_id = $(this).data('job_entry_id');
        var followup_id = $(this).data('followup_id');
        var proposal_status = $(this).data('proposal_status');
        $('#modalphone').val(phone); 
        $('#modalCustomerId').val(customer_id); 
        $('#modalEmployeeId').val(employee_id);
        $('#modalJobNumber').text(job);
        $('#modaljob_no').val(job);
        $('#modaljob_entry_id').val(job_id);
        $('#modelfollowup_id').val(followup_id);
        $('#modalproposal_status').val(proposal_status);
    });
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
</script>

</html>