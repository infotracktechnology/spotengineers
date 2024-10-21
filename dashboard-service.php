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
            <div class="col-sm-12 col-lg-6">
              <div class="card">
                <div class="card-header">
                <a href="#">Total Pending Calls</a>
                </div>
                <div class="card-body">
                  <div id="pending_calls" style="height: 350px;"></div>
                </div>
              </div>
            </div>
            <div class="col-sm-12 col-lg-6">
              <div class="card">
                <div class="card-header">
                <a href="#">Today Assignment</a>
                </div>
                <div class="card-body">
                  <div id="today_assignment" style="height: 350px;"></div>
                </div>
              </div>
            </div>
            <div class="col-sm-12 col-lg-6">
              <div class="card">
                <div class="card-header">
                  <a href="#">Tomorrow Followup Calls</a>
                </div>
                <div class="card-body">
                  <div id="tomorrow_follow_up" style="height: 350px;"></div>
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
    var chartDom = document.getElementById('pending_calls');
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
            { value: 0, name: 'Pending Calls' },
          ],
        }
      ]
    };

    option && myChart.setOption(option);

    var chartDom = document.getElementById('today_assignment');
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
            { value: 0, name: 'Today Assignment' },
          ],
          itemStyle: {
            color:'#43b31a'
          },
        }
      ]
    };

    option && myChart.setOption(option);

    var chartDom = document.getElementById('tomorrow_follow_up');
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
            { value: 0, name: 'Tomorrow Follow Up Calls' },
          ],
          itemStyle: {
            color: '#f3b31a'
          },
        }
      ]
    };

    option && myChart.setOption(option);
</script>
</body>
</html>