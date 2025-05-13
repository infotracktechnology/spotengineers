<?php
ob_start();
session_start();
include "config.php";
if(!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}
$from = $_GET['from'] ?? date('Y-m-01');
$to = $_GET['to'] ?? date('Y-m-d');

    $job_query = "SELECT 
             DATE_FORMAT(job_date, '%Y-%m') AS month, 
             COUNT(*) AS job_count 
          FROM job_entry 
          WHERE job_date BETWEEN '$from' AND '$to'
          GROUP BY month 
          ORDER BY month ASC";

$job_result = mysqli_query($con, $job_query);

$job_data = [];

while ($row = mysqli_fetch_assoc($job_result)) {
  $date = DateTime::createFromFormat('Y-m', $row['month']);
    $job_data[] = [
        'months' => $date->format('M Y'),
        'counts' => $row['job_count']
    ];
}


$revenue_query = "
  SELECT 
    DATE_FORMAT(job_date, '%Y-%m') AS month,
    SUM(grand_total) AS revenue
  FROM job_entry
  WHERE job_date BETWEEN '$from' AND '$to'
  GROUP BY month
  ORDER BY month ASC
";

$revenue_result = mysqli_query($con, $revenue_query);

$revenue_data = [];
while ($row = mysqli_fetch_assoc($revenue_result)) {
    $date = DateTime::createFromFormat('Y-m', $row['month']);
    $revenue_data[] = [
        'month' => $date->format('M Y'),
        'revenue' => (float)$row['revenue']
    ];
}


$lead_query = "
      SELECT 
        source AS lead,
        COUNT(*) AS job_count 
      FROM job_entry
      WHERE source IS NOT NULL AND job_date BETWEEN '$from' AND '$to'
      GROUP BY lead
      ORDER BY lead ASC
    ";
    
    $lead_result = mysqli_query($con, $lead_query);
    
    $lead_data = [];
    while ($row = mysqli_fetch_assoc($lead_result)) {
        $lead_data[] = [
            'lead' => $row['lead'],
            'job_count' => $row['job_count']
        ];
    }

    $job_emp_query = "SELECT 
             employee.name AS emp_id,
             COUNT(*) AS job_count
          FROM job_entry LEFT JOIN employee ON job_entry.emp_id = employee.id
          WHERE job_date BETWEEN '$from' AND '$to'
          GROUP BY job_entry.emp_id
          ORDER BY emp_id ASC";
    
    $job_emp_result = mysqli_query($con, $job_emp_query);
    
    $job_emp_data = [];
    
    while ($row = mysqli_fetch_assoc($job_emp_result)) {
        $job_emp_data[] = [
            'emp_id' => $row['emp_id'],
            'job_count' => $row['job_count']
        ];
    }
    
    echo json_encode(array_merge(['lead_data' => $lead_data], ['job_data' => $job_data], ['revenue_data' => $revenue_data], ['job_emp_data' => $job_emp_data]));


?>