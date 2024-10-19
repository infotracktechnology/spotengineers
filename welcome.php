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
  
      <!-- Main Content -->
      <div class="main-content pl-4">
        <section class="section">
        <div class="row">
            <div class="col-md-5 offset-md-4 col-sm-12">
              <div class="card">
                <div class="card-statistic-4">
                        <div class="card-body pr-4">
                          <h5 class="font-15">Hi <?php echo $_SESSION['username']; ?></h5>
                          <h2 class="mb-3 font-18"> Welcome to Spot Engineers! Express yourself.</h2>
                          <img src="assets/img/logo.png" alt="" style="height: 380px;width: 380px;">
                          <div class="mt-2">
                          <a href="dashboard.php" class="btn btn-primary my-2">Go to Sales Dashboard</a>
                          <a href="dashboard-service.php" class="btn btn-primary my-2">Go to Service Dashboard</a>
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
</body>
</html>