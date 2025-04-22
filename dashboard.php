<?php
ob_start();
session_start();
include "config.php";

if(!isset($_SESSION['username'])) {
  header("Location: index.php");
  exit;  
}

$yesterday = date('Y-m-d', strtotime('yesterday'));

$sql = "SELECT COUNT(*) FROM sales WHERE sale_date = '$yesterday'";
$result = $con->query($sql)->fetch_array()[0];

$credit_sql = "SELECT COUNT(*) FROM sales WHERE sale_type = 'Credit'";
$credit_result = $con->query($credit_sql)->fetch_array()[0];

$re_order_sql = "SELECT i.item_id,i.name AS item_name, i.brand AS item_brand,i.model AS item_model,i.qty AS opening_stack,
COALESCE(p.purchase_qty, 0) AS purchase,COALESCE(pr.purchase_return_qty, 0) AS purchase_return,
COALESCE(s.sale_qty, 0) AS sale,COALESCE(si.issue_qty, 0) AS issue,(i.qty + COALESCE(p.purchase_qty, 0) - COALESCE(pr.purchase_return_qty, 0) 
     - COALESCE(s.sale_qty, 0) - COALESCE(si.issue_qty, 0)) AS closing_stack, i.re_order AS re_order  
     FROM 
    items i LEFT JOIN (
    SELECT item_id, SUM(quantity) AS purchase_qty
    FROM purchase_items
    GROUP BY item_id
) p ON i.item_id = p.item_id  
LEFT JOIN (
    SELECT item_id, SUM(qty) AS purchase_return_qty
    FROM purchase_returns_items
    GROUP BY item_id
) pr ON i.item_id = pr.item_id  
LEFT JOIN (
    SELECT item_id, SUM(qty) AS sale_qty
    FROM sales_items
    GROUP BY item_id
) s ON i.item_id = s.item_id  
LEFT JOIN (
    SELECT spare_id, SUM(qty) AS issue_qty
    FROM spare_issue_item
    GROUP BY spare_id
) si ON i.item_id = si.spare_id 
WHERE (i.qty + COALESCE(p.purchase_qty, 0) - COALESCE(pr.purchase_return_qty, 0) 
     - COALESCE(s.sale_qty, 0) - COALESCE(si.issue_qty, 0)) <= i.re_order
GROUP BY 
    i.item_id, i.name, i.brand, i.model, i.qty  

ORDER BY 
    i.name;
";
$re_order_result = $con->query($re_order_sql)->num_rows;

$sold_sql = "SELECT COUNT(*) FROM sold;";
$sold_result = $con->query($sold_sql)->fetch_array()[0];

$currentFY = date('Y') . '-' . (date('Y') + 1);
if (date('m') < 4) { // April is month 4
    $currentFY = (date('Y') - 1) . '-' . date('Y');
}

$sale_chart_sql = "SELECT MONTH(sale_date) AS month_number, DATE_FORMAT(sale_date, '%M') AS month_name, SUM(total) AS total_sales FROM sales WHERE cyear = '$currentFY' GROUP BY month_number, month_name ORDER BY id;";
$sale_chart_result = $con->query($sale_chart_sql);
$months = [];
$sales = [];

while ($row = $sale_chart_result->fetch_assoc()) {
    $months[] = $row['month_name'];
    $sales[] = $row['total_sales'];
}
// die($currentFY);

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
        <div class="row">
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="card" >
                <div class="card-statistic-4" style="height: 100%;">
                  <div class="align-items-center justify-content-between">
                    <div class="row ">
                      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                        <div class="card-content modified">
                          <h5 class="font-15">Yesterday Sales</h5>
                          <a href="yesterday_sales_report.php"><h2 class="mb-3 fw-modified"><?php echo $result; ?></h2></a>
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
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <div class="card">
                <div class="card-statistic-4" style="height: 100%;">
                  <div class="align-items-center justify-content-between" >
                    <div class="row" >
                      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                        <div class="card-content modified">
                          <h5 class="font-15"> Credit Bills</h5>
                          <a href="credit_bills.php"><h2 class="mb-3 fw-modified"><?php echo $credit_result; ?></h2></a>
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
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <div class="card">
                <div class="card-statistic-4" style="height: 100%;">
                  <div class="align-items-center justify-content-between">
                    <div class="row ">
                      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                        <div class="card-content modified">
                          <h5 class="font-15"> Re-Order Stock</h5>
                          <a href="re_order_stock.php"><h2 class="mb-3 fw-modified"><?php echo $re_order_result; ?></h2></a>
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


            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <div class="card">
                <div class="card-statistic-4" style="height: 100%;">
                  <div class="align-items-center justify-content-between">
                    <div class="row ">
                      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                        <div class="card-content modified">
                          <h5 class="font-15"> Sold</h5>
                          <a href="sold.php"><h2 class="mb-3 fw-modified"><?php echo $sold_result; ?></h2></a>
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

          <div class="row clearfix">
            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8 col-8">
              <div class="card">
                <div class="card-header">
                  <h4>Month-wise Sales <span class="col-blue font-15">(<?php echo $currentFY;?>)</span></h4>
                </div>
                <div class="card-body">
                  <div class="recent-report__chart">
                    <div id="barChart"></div>
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
    var barChart = echarts.init(document.getElementById('barChart'));
    var option = {
      tooltip: {
            trigger: 'axis',
            axisPointer: {
                type: 'shadow' // Shadow type on hover
            },
            formatter: function(params) {
                // Custom tooltip content
                return `<strong>${params[0].name}</strong><br/>
                        Amount: <b>${params[0].value}</b>`;
            }
        },
        xAxis: {
            type: 'category',
            data: <?php echo json_encode($months); ?>
        },
        yAxis: {
            type: 'value'
        },
        series: [{
            data: <?php echo json_encode($sales); ?>,
            type: 'bar',
            showBackground: true,
            backgroundStyle: {
                color: 'rgba(220, 220, 220, 0.4)'  // Light gray background
            },
            itemStyle: {
                color:
                    // Custom color for each bar
                    new echarts.graphic.LinearGradient(0, 0, 0, 1, [
    { offset: 0, color: '#83bff6' },
    { offset: 0.5, color: '#91CC75' },
    { offset: 1, color: '#91CC25' }
   ]),
    barBorderRadius: [6, 6, 0, 0],  // Rounded top corners
                borderWidth: 1,
                borderColor: '#fff'
            },
            barWidth: '40%',  // Adjust width (40% of category width)
            barGap: '30%',    // Gap between bars
            barCategoryGap: '20%'  // Gap between categories
        }],
        grid: {
            left: '3%',
            right: '4%',
            bottom: '3%',
            containLabel: true
        }
    };

    barChart.setOption(option);
    
    // Responsive behavior
    window.addEventListener('resize', function() {
        barChart.resize();
    });
</script>


</body>
</html>