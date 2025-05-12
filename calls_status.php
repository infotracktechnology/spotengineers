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
    $job = $con->query("INSERT INTO `followup`(`job_entry_id`, `job_no`, `proposal_date`, `call_status`, `customer_id`,`employee_id`, `customer_phone`, `remarks`) VALUES ('$job_entry_id', '$job_no', '$proposal_date', '$call_status', '$customer_id','$employee_id', '$customer_phone', '$remarks');");
    if($job) {
        echo '<script>alert("Remarks Added Successfully");</script>';
        echo '<script>window.location.href="calls_status.php";</script>';
       }else {
        echo '<script>alert("Something went wrong");</script>';
        echo '<script>window.location.href="calls_status.php";</script>';
       }
    //    echo '<script>alert("Remarks Added Successfully");</script>';
    //    echo '<script>window.location.href="pending.php";</script>';
   }

// $yesterday = date('Y-m-d', strtotime('yesterday'));

$today = date('Y-m-d');

// $sql = "SELECT c.name AS customer_name, c.phone AS customer_phone, f.proposal_date AS proposal_date, f.call_status AS call_status, f.id AS followup_id, f.job_no AS job_no, f.job_entry_id AS job_entry_id, f.customer_id AS customer_id, f.employee_id AS employee_id, e.name AS employee_name FROM followup f LEFT JOIN customer c ON f.customer_id = c.id LEFT JOIN employee e ON f.employee_id = e.id WHERE f.call_status = 'rejected' AND DATE_FORMAT(f.proposal_date, '%Y-%m-%d') = '$today' ORDER BY c.name ASC;";
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
FROM followup latest WHERE latest.job_entry_id = f.job_entry_id) AND call_status = 'rejected' AND DATE_FORMAT(f.proposal_date, '%Y-%m-%d') = '$today'
ORDER BY c.name ASC;
";
$result = $con->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Call Status | Spot Engineers</title>
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
                                        <h4 class="col-deep-purple m-0">Call Status Report</h4>
                                    </div>
                                    <div class="card-body">
                                      
                                        <div class="table-responsive">
                                            <table class="table table-striped table-sm" id="tableExport" style="width:100%;">
                                                <thead>
                                                    <tr>
                                                        <th>S.No</th>
                                                        <th>Name</th>
                                                        <th>Customer Phone</th>
                                                        <th>Proposal Date</th>
                                                        <th>Call Status</th>
                                                        <th>Employee Name</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $i = 1;
                                                    while ($row = $result->fetch_assoc()) {
                                                        echo "<tr>
                                                            <td>" . $i . "</td>
                                                            <td>" . $row['customer_name'] . "</td>
                                                            <td>" . $row['customer_phone'] . "</td>
                                                            <td>" . $row['proposal_date'] . "</td>
                                                            <td>" . $row['call_status'] . "</td>
                                                            <td>" . $row['employee_name'] . "</td>
                                                            <td>
                                                            <button type='button' class='btn btn-primary btn update-btn' 
                                                                    data-toggle='modal' 
                                                                    data-customerid='" . $row['customer_id'] . "'
                                                                    data-employeeid='" . $row['employee_id'] . "'
                                                                    data-target='#callModal'
                                                                    data-phone='" . $row['customer_phone'] . "'
                                                                    data-job_no='" . $row['job_no'] . "'
                                                                    data-job_entry_id='" . $row['job_entry_id'] . "'
                                                                    data-followup_id='" . $row['followup_id'] . "'>
                                                                    <i class='fas fa-edit'></i>
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
            <form action="calls_status.php" method="POST">

            <div class="modal-body">
                <div class="form-group">
                <label>Job #: <span id="modalJobNumber" class="font-weight-bold"></span></label><br>
                <button id="modalPhoneNumber" class="btn btn-primary" type="button" style="cursor: pointer;">Call</button>
                </div>

                <div class="form-group">
                    <label for="proposalStatus">Call Status <span class="text-danger">*</span></label>
                    <select class="form-control" id="proposalStatus" name="call_status" required>
                        <option value="">Select Call Status</option>
                        <option value="booked">Booked</option>
                        <option value="accepted">Accepted</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>

                <div class="form-group date_div" style="display: none;">
                    <label for="proposalDate">Proposal Date</label><input type="date" min="<?php echo date('Y-m-d'); ?>" name="proposal_date" class="form-control" id="proposalDate" value="<?php echo date('Y-m-d'); ?>" required>
                    <input type="hidden" id="modalCustomerId" name="customer_id" value="">
                    <input type="hidden" id="modalEmployeeId" name="employee_id" value="">
                    <input type="hidden" id="modalphone" name="customer_phone" value="">
                    <input type="hidden" id="modaljob_no" name="job_no" value="">
                    <input type="hidden" id="modaljob_entry_id" name="job_entry_id" value="">
                    <input type="hidden" id="modalfollowup_id" name="followup_id" value="">
                </div>

                <div class="form-group">
                    <label for="feedbackText">Remarks</label>
                    <textarea class="form-control" id="feedbackText" name="remarks" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <!-- <button type="button" class="btn btn-primary" id="saveFeedback">Save Feedback</button> -->
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
    $(document).ready(function() {
        $('.update-btn').click(function() {
        var phone = $(this).data('phone');
        var job = $(this).data('job_no');
        var customer_id = $(this).data('customerid');
        var employee_id = $(this).data('employeeid');
        var job_id = $(this).data('job_entry_id');
        var followup_id = $(this).data('followup_id');
        $('#modalphone').val(phone); 
        $('#modalCustomerId').val(customer_id); 
        $('#modalEmployeeId').val(employee_id);
        $('#modalJobNumber').text(job);
        $('#modaljob_no').val(job);
        $('#modaljob_entry_id').val(job_id);
        $('#modelfollowup_id').val(followup_id);
        $('#modalPhoneNumber').attr('onclick', "window.location.href='tel:+91" + phone + "';");
    });

    $('#proposalStatus').on('change', function() {
    const selectedValue = $(this).val();
    const dateDiv = $('.date_div');
    const proposalDate = $('#proposalDate');
    const feedbackText = $('#feedbackText');

    const isRejected = selectedValue === 'rejected';
    const isAcceptedOrBooked = selectedValue === 'accepted' || selectedValue === 'booked';

    dateDiv.toggle(isRejected);
    proposalDate.prop('required', isRejected).css('pointer-events', isRejected ? 'auto' : 'none');
    feedbackText.prop('required', selectedValue !== ''); // Feedback required for any status other than the default/empty

    // if (!isRejected) {
    //   proposalDate.val(''); // Clear date if not rejected
    // }
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
 
      
    });
</script>

</html>