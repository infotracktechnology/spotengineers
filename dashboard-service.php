<?php
ob_start();
session_start();
include "config.php";

if(!isset($_SESSION['username'])) {
  header("Location: index.php");
  exit;  
}

// $twoMonthsAgo = date('Y-m-d', strtotime('-2 months'));

$twoMonthsAgo = date('Y-m', strtotime('-2 months'));

$followups_sql = "SELECT COUNT(*) FROM job_entry 
          WHERE status = 'completed' 
          AND DATE_FORMAT(job_date, '%Y-%m') = '$twoMonthsAgo' AND followup_status = '';";

$followups_result = $con->query($followups_sql)->fetch_array()[0];

$today = date('Y-m-d');

$today_sql = "SELECT COUNT(*) FROM job_entry WHERE source = 'followup' AND job_date= '$today';";

$today_result = $con->query($today_sql)->fetch_array()[0];

$pending_sql ="SELECT COUNT(*) FROM `job_entry` WHERE id NOT IN (SELECT job_entry_id FROM followup) AND status='pending';";

$pending_result = $con->query($pending_sql)->fetch_array()[0];

$booked_sql = "SELECT COUNT(*) FROM `job_entry` WHERE status='pending' AND source = 'followup' AND DATE_FORMAT(job_date, '%Y-%m-%d') > '$today';";

$booked_result = $con->query($booked_sql)->fetch_array()[0];
?>
<!DOCTYPE html> 
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Spot Engineers - Admin Dashboard</title>
  <!-- General CSS Files -->
  <link rel="stylesheet" href="assets/css/app.min.css">
  <!-- Template CSS -->
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/components.css">
  <!-- Custom style CSS -->
  <link rel="stylesheet" href="assets/css/custom.css">
  <link rel='shortcut icon' type='image/x-icon' href='assets/img/favicon.ico' />
  <style>
    .modified{
      display: flex; flex-direction: column; align-items: center; text-align: center; justify-content: space-around; height: 100%;
    }

    .fw-modified{
      font-style: italic;
      font-size: 30px;
      font-weight: 600;
    }

    .heading {
  min-height: 40px; /* Adjust based on font size and expected lines */
  margin-bottom: 8px;
  display: block;
}

.sub_heading {
  min-height: 48px; /* Or enough to fit 2 lines max */
  display: block;
}

.card-statistic-4 {
  display: flex;
  flex-direction: column;
  justify-content: center;
  height: 100%;
}
  </style>
</head>
<body class="sidebar-mini">
  <div class="loader"></div>
  <div id="app">
    <div class="main-wrapper main-wrapper-1">
   <?php include('sidebar.php');?>
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
        <div class="row d-flex">
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12 d-flex">
            <div class="card w-100">
                <div class="card-statistic-4" style="height: 100%;">
                  <div class="align-items-center justify-content-between">
                    <div class="row ">
                      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                        <div class="card-content modified">
                          <h5 class="font-15 heading">Followup Calls</h5>
                          <a href="followups.php"><h2 class="mb-3 fw-modified sub_heading"><?php echo $followups_result ?></h2></a>
                        </div>
                      </div>
                      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                        <div class="banner-img">
                          <img src="assets/img/banner/5.avif" alt="" height="100" width="100">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12 d-flex">
              <div class="card w-100">
                <div class="card-statistic-4" style="height: 100%;">
                  <div class="align-items-center justify-content-between" >
                    <div class="row" >
                      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                        <div class="card-content modified">
                          <h5 class="font-15 heading">Today Assignment Calls</h5>
                          <a href="today_jobs.php"><h2 class="mb-3 fw-modified sub_heading"><?php echo $today_result ?></h2></a>
                        </div>
                      </div>
                      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0" >
                        <div class="banner-img">
                          <img src="assets/img/banner/6.avif" alt="" height="100" width="100">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12 d-flex">
              <div class="card w-100">
                <div class="card-statistic-4" style="height: 100%;">
                  <div class="align-items-center justify-content-between">
                    <div class="row ">
                      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                        <div class="card-content modified">
                          <h5 class="font-15 heading">Pending Job</h5>
                          <a href="pending.php"><h2 class="mb-3 fw-modified sub_heading"><?php echo $pending_result ?></h2></a>
                        </div>
                      </div>
                      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                        <div class="banner-img">
                          <img src="assets/img/banner/8.jpg" alt="" height="100" width="100">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>


            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12 d-flex">
              <div class="card w-100">
                <div class="card-statistic-4" style="height: 100%;">
                  <div class="align-items-center justify-content-between">
                    <div class="row ">
                      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                        <div class="card-content modified">
                          <h5 class="font-15 heading">Calls Booked</h5>
                          <a href="calls_booked.php"><h2 class="mb-3 fw-modified sub_heading"><?php echo $booked_result ?></h2></a>
                        </div>
                      </div>
                      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                        <div class="banner-img">
                          <img src="assets/img/banner/9.webp" alt="" height="100" width="100">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
          </div>

         
        </section>
      </div>
      <?php include_once 'footer.php';?>
    </div>
  </div>
  <!-- General JS Scripts -->
  <script src="assets/js/app.min.js"></script>
  <!-- JS Libraies -->
  <script src="assets/bundles/apexcharts/apexcharts.min.js"></script>
  <!-- Page Specific JS File -->
  <!-- Template JS File -->
  <script src="assets/js/scripts.js"></script>
  <!-- Custom JS File -->
  <script src="assets/js/custom.js"></script>
  <script type="text/javascript" src="https://fastly.jsdelivr.net/npm/echarts@5.5.0/dist/echarts.min.js"></script>
  <script>
    var chartDom = document.getElementById('chart1');
    var myChart = echarts.init(chartDom);
    var option;

    option = {
      tooltip: {
        trigger: 'item'
      },
      legend: {
        orient: 'vertical',
        left: 'left'
      },
      series: [
        {
          type: 'pie',
          radius: '50%',
          data: [
            { value: 10, name: 'Pending Calls',itemStyle:{color:'#dc3545'} },
            { value: 4, name: 'Today Assignment',itemStyle:{color:'#ffc107'} },
            { value: 2, name: 'Tomorrow Followup Calls',itemStyle:{color:'#28a745'} },
          ],
        }
      ]
    };

    option && myChart.setOption(option);

   
</script>
</body>
</html>