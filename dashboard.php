<?php
ob_start();
session_start();
include "config.php";

if(!isset($_SESSION['username'])) {
  header("Location: index.php");
  exit;  
}
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
</head>
<body>
  <div class="loader"></div>
  <div id="app">
    <div class="main-wrapper main-wrapper-1">
   <?php include('sidebar.php');?>
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
        <div class="row">
            <div class="col-sm-12 col-lg-4">
              <div class="card card-danger">
                <div class="card-header">
                <a href="#">Total Pending Calls</a>
                </div>
                <div class="card-body">
                <h5 class="font-15">10</h5>
                </div>
              </div>
            </div>
            <div class="col-sm-12 col-lg-4">
              <div class="card card-warning">
                <div class="card-header">
                <a href="#">Today Assignment</a>
                </div>
                <div class="card-body">
                <h5 class="font-15">4</h5>
                </div>
              </div>
            </div>
            <div class="col-sm-12 col-lg-4">
              <div class="card card-success">
                <div class="card-header">
                  <a href="#">Tomorrow Followup Calls</a>
                </div>
                <div class="card-body">
                <h5 class="font-15">2</h5>
                </div>
              </div>
            </div>


            <div class="col-sm-12 col-lg-8">
              <div class="card card-primary">
                <div class="card-header">
                <a href="#">Total Calls</a>
                </div>
                <div class="card-body">
                  <div id="chart1" style="height: 400px;"></div>
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