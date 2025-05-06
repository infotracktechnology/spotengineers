<?php
ob_start();
session_start();
include "config.php";

// Check user session
if (!isset($_SESSION['username'])) {
    header("location:index.php");
    exit;
}

// $twoMonthsAgo = date('Y-m-d', strtotime('-2 months'));
// $twoMonthsAgo = date('Y-m', strtotime('-2 months'));

$today = date('Y-m-d');

$sql = "SELECT j.job_no, j.job_date, j.id AS job_id, c.name AS customer_name, c.id AS customer_id, c.phone AS customer_phone, e.id AS employee_id, e.name AS employee_name FROM job_entry j LEFT JOIN customer c ON j.customer_id = c.id LEFT JOIN employee e ON j.emp_id = e.id WHERE j.id NOT IN (SELECT job_entry_id FROM followup) AND status='pending' ORDER BY j.id ASC;";
$result = $con->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Pending Jobs | Spot Engineers</title>
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
                                        <h4 class="col-deep-purple m-0">Pending Jobs</h4>
                                    </div>
                                    <div class="card-body">
                                      
                                        <div class="table-responsive">
                                            <table class="table table-striped table-sm" id="tableExport" style="width:100%;">
                                                <thead class="tableHeader">
                                                    <tr>
                                                        <th>S.No</th>
                                                        <th>Job No</th>
                                                        <th>Job Date</th>
                                                        <th>Customer Name</th>
                                                        <th>Customer Phone</th>
                                                        <th>Employee Name</th>
                                                        <th>Call</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="tableBody">
                                                    <?php
                                                    $i = 1;
                                                    while ($row = $result->fetch_assoc()) {
                                                        echo "<tr>
                                                            <td>" . $i . "</td>
                                                            <td>" . $row['job_no'] . "</td>
                                                            <td>" . $row['job_date'] . "</td>
                                                            <td>" . $row['customer_name'] . "</td>
                                                            <td>" . $row['customer_phone'] . "</td>
                                                            <td>" . $row['employee_name'] . "</td>
                                                            <td>
                                                            <button type='button' class='btn btn-primary btn-sm call-btn' 
                                                                    data-toggle='modal' 
                                                                    data-customerid='" . $row['customer_id'] . "'
                                                                    data-employeeid='" . $row['employee_id'] . "'
                                                                    data-target='#callModal'
                                                                    data-phone='" . $row['customer_phone'] . "'
                                                                    data-job='" . $row['job_no'] . "'
                                                                    data-job_id='" . $row['job_id'] . "'>
                                                                <i class='fas fa-phone text-white'></i>
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
<!-- Feedback Modal -->
<div class="modal fade" id="callModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Call Remarks</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <!-- <form action="save_followup.php" method="POST"> -->

            <div class="modal-body">
                <div class="form-group">
                <label>Job #: <span id="modalJobNumber" class="font-weight-bold"></span></label><br>
                <button id="modalPhoneNumber" class="btn btn-primary" type="button" style="cursor: pointer;">Call</button>
                </div>

                <div class="form-group">
                    <label for="proposalStatus">Call Status</label>
                    <select class="form-control" id="proposalStatus" name="call_status" required>
                        <option value="booked">Booked</option>
                        <option value="pending">Pending</option>
                        <option value="accepted">Accepted</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="proposalDate">Proposal Date</label><input type="date" name="proposal_date" class="form-control" id="proposalDate" value="<?php echo date('Y-m-d'); ?>" required>
                    <input type="hidden" id="modalCustomerId" name="customer_id" value="">
                    <input type="hidden" id="modalEmployeeId" name="employee_id" value="">
                    <input type="hidden" id="modalphone" name="customer_phone" value="">
                    <input type="hidden" id="modaljob" name="job_no" value="">
                    <input type="hidden" id="modaljob_id" name="job_entry_id" value="">
                </div>

                <div class="form-group">
                    <label for="feedbackText">Remarks</label>
                    <textarea class="form-control" id="feedbackText" name="remarks" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-secondary" id="test">Test</button></button> -->
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveFeedback">Save Feedback</button>
                <!-- <button type="submit" class="btn btn-primary" id="saveFeedback">Save Feedback</button> -->
            </div>
            <!-- </form> -->
        </div>
    </div>
</div>
<!-- Toast Notification Container (place this in your layout file) -->
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
    $('.call-btn').click(function() {
        var phone = $(this).data('phone');
        var job = $(this).data('job');
        var customer_id = $(this).data('customerid');
        var employee_id = $(this).data('employeeid');
        var job_id = $(this).data('job_id');
        $('#modalphone').val(phone); 
        $('#modalCustomerId').val(customer_id); 
        $('#modalEmployeeId').val(employee_id);
        $('#modalJobNumber').text(job);
        $('#modaljob').val(job);
        $('#modaljob_id').val(job_id);
        $('#modalPhoneNumber').attr('onclick', "window.location.href='tel:+91" + phone + "';");
    });

    $('#saveFeedback').click(function() {
        $.ajax({
            url: 'save_followup.php',
            method: 'POST',
            data: {
                customer_phone: $('#modalphone').val(),
                call_status: $('#proposalStatus').val(),
                job_no: $('#modaljob').val(),
                job_entry_id: $('#modaljob_id').val(),
                customer_id: $('#modalCustomerId').val(),
                employee_id: $('#modalEmployeeId').val(),
                remarks: $('#feedbackText').val(),
                proposal_date: $('#proposalDate').val()
            },
            success: function(response) {
            // Hide modal
            $('#callModal').modal('hide');
            
            // Show toast notification
            showToast(
                'Success', 
                'Feedback saved successfully!', 
                'success'
            );
            
            // Reset form fields if needed
            $('#feedbackText').val('');
            $('#proposalStatus').val('pending');
        },
        error: function(xhr) {
            showToast(
                'Error', 
                'Failed to save feedback: ' + (xhr.responseJSON?.message || xhr.statusText), 
                'danger'
            );
        }
        });
    });

    function showToast(title, message, type = 'success') {
    const toast = $('#toastNotification');
    
    // Set toast content and style
    toast.find('.toast-header strong').text(title);
    toast.find('.toast-body').text(message);
    
    // Remove previous color classes
    toast.find('.toast-header').removeClass('bg-success bg-danger bg-warning bg-info');
    toast.find('.toast-header').addClass('bg-' + type);
    
    // Show the toast
    toast.toast('show');
    
    // Auto-hide after delay
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