<?php
ob_start();
session_start();
include "config.php";

if(!isset($_SESSION['username'])) {
  header("Location: index.php");
  exit;
}

$twoMonthsAgo = date('Y-m', strtotime('-2 months'));

$followups_sql = "SELECT COUNT(*) FROM job_entry
          WHERE status = 'completed'
          AND DATE_FORMAT(job_date, '%Y-%m') = '$twoMonthsAgo' AND followup_status = '';";

$followups_result = $con->query($followups_sql)->fetch_array()[0];

$today = date('Y-m-d');

$today_sql = "SELECT COUNT(*) FROM `followup` f WHERE f.id = (SELECT MAX(id)
FROM followup latest WHERE latest.job_entry_id = f.job_entry_id) AND f.call_status != 'rejected' AND call_status != 'pending' AND DATE_FORMAT(f.proposal_date, '%Y-%m-%d') = '$today';";

$today_result = $con->query($today_sql)->fetch_array()[0];

$pending_sql = "SELECT COUNT(*) FROM `followup` f WHERE f.id = (SELECT MAX(id)
FROM followup latest WHERE latest.job_entry_id = f.job_entry_id) AND f.call_status = 'pending' AND DATE_FORMAT(f.proposal_date, '%Y-%m-%d') = '$today';";

$pending_result = $con->query($pending_sql)->fetch_array()[0];

$booked_sql = "SELECT COUNT(*) FROM `job_entry` WHERE status='pending' AND source = 'followup' AND DATE_FORMAT(job_date, '%Y-%m-%d') > '$today';";

$booked_result = $con->query($booked_sql)->fetch_array()[0];

$status_sql = "SELECT COUNT(*) FROM `followup` f WHERE f.id = (SELECT MAX(id)
FROM followup latest WHERE latest.job_entry_id = f.job_entry_id) AND f.call_status = 'rejected' AND DATE_FORMAT(f.proposal_date, '%Y-%m-%d') = '$today';";

$status_result = $con->query($status_sql)->fetch_array()[0];

$currentFY = date('Y') . '-' . (date('Y') + 1);
if (date('m') < 4) { // April is month 4
  $currentFY = (date('Y') - 1) . '-' . date('Y');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Spot Engineers - Admin Dashboard</title>
  <link rel="stylesheet" href="assets/css/app.min.css">
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/components.css">
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
                        <h5 class="font-15 heading">Pending Calls</h5>
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
                        <h5 class="font-15 heading">Calls Status</h5>
                        <a href="calls_status.php"><h2 class="mb-3 fw-modified sub_heading"><?php echo $status_result ?></h2></a>
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

          <div class="card">
          <div class="card-body">
            <div class="row">
            <div class="form-group col-sm-5">
                  <label for="fromDate">From</label>
                  <input type="date" class="form-control form-control " id="fromDate" max="<?php echo date('Y-m-d'); ?>">
                  </div>
                  <div class="form-group col-sm-5">
                  <label for="toDate">To</label>
                  <input type="date" class="form-control form-control" id="toDate" max="<?php echo date('Y-m-d'); ?>">
                  </div>
                  <div class="form-group col-sm-2 mt-4">
                  <button id="filterBtn" class="btn btn-primary w-100 mt-1">Filter</button>
                  </div>
            </div>
          </div>
        </div>
        

          <div class="row clearfix">
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 col-12">
              <div class="card">
                <div class="card-header justify-content-between align-items-center">
                  <h4>Month Vs Job Entry <span class="col-blue font-15"></span></h4>
                  <!-- <div class="card-header-right" data-toggle='modal' data-target='#dateModal'>
                  <i class="fas fa-calendar-alt font-20"></i>
                  </div> -->
                </div>
                <div class="card-body">
                  <div class="recent-report__chart">
                  <canvas id="jobChart" style="width:100%; height:300px;"></canvas>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 col-12">
              <div class="card">
                <div class="card-header">
                  <h4>Employee Vs jobs <span class="col-blue font-15"></span></h4>
                </div>
                <div class="card-body">
                  <canvas id="salesChart" style="width:100%; height:300px;"></canvas>
                </div>
              </div>
            </div>

            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 col-12">
              <div class="card">
                <div class="card-header">
                  <h4>Month Vs Job Leads <span class="col-blue font-15"></span></h4>
                </div>
                <div class="card-body">
                  <div id="pieChart" style="width:100%; height:400px;"></div>
                </div>
              </div>
            </div>

            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 col-12">
              <div class="card">
                <div class="card-header">
                  <h4>Month Vs Job revenue <span class="col-blue font-15"></span></h4>
                </div>
                <div class="card-body">
                  <div id="empChart" style="width:100%; height:400px;"></div>
                </div>
              </div>
            </div>
          </div>



        </section>
        <div class="modal fade" id="dateModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Date Filter</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
            <div class="card">
          <div class="card-body">
                  <div class="form-group">
                  <label for="fromDate">From</label>
                  <input type="date" class="form-control form-control" id="fromDate" max="<?php echo date('Y-m-d'); ?>">
                  </div>
                  <div class="form-group">
                  <label for="toDate">To</label>
                  <input type="date" class="form-control form-control" id="toDate" max="<?php echo date('Y-m-d'); ?>">
                  </div>

          </div>
          <div class="card-footer mt-0 pt-0">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
              <button id="filterBtn" class="btn btn-primary">Filter</button>
          </div>
        </div>
            </div>
        </div>
    </div>
</div>
      </div>
      <?php include_once 'footer.php';?>
    </div>
  </div>
  <script src="assets/js/app.min.js"></script>
  <script src="assets/bundles/apexcharts/apexcharts.min.js"></script>
  <script src="assets/js/scripts.js"></script>
  <script src="assets/js/custom.js"></script>
  <script src="assets/js/app.js"></script>

  <script type="text/javascript" src="https://fastly.jsdelivr.net/npm/echarts@5.5.0/dist/echarts.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/echarts/dist/echarts.min.js"></script>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let salesChart;
let jobChart;

function fetchAndRenderChart(fromDate, toDate) {
    $.getJSON('sales_chart.php', { from: fromDate, to: toDate }, function (data) {
      if(data){
        $('#dateModal').modal('hide');
      }
      const job_emp_data = data.job_emp_data;

        const labels = job_emp_data.map(item => item.emp_id);
        const jobs = job_emp_data.map(item => parseInt(item.job_count));


        if (salesChart) {
            salesChart.data.labels = labels;
            salesChart.data.datasets[0].data = jobs;
            salesChart.update();
        } else {
            const ctx = document.getElementById('salesChart').getContext('2d');
            const gradient = ctx.createLinearGradient(0, 0, 0, 300); // x0, y0, x1, y1 (vertical)
            gradient.addColorStop(0, 'rgba(0, 123, 255, 0.7)');   // Start color (more opaque)
            gradient.addColorStop(1, 'rgba(0, 123, 255, 0.2)');   // End color (less opaque)
            salesChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Jobs by Employee',
                        data: jobs,
                        backgroundColor: gradient, // Apply the gradient
                        borderColor: 'rgba(0, 123, 255, 1)',
                        borderWidth: 1,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 0
                    }]
                },
                options: {
  responsive: true,
  scales: {
    x: {
      title: { display: true, text: 'Employee' }
    },
    y: {
      title: { display: true, text: 'Job Count' },
      beginAtZero: true
    }
  }
}

            });
        }
        const job_data = data.job_data;
        const job_counts_month = job_data.map(item => item.months);
        const job_counts = job_data.map(item => item.counts);
        const ctx1 = document.getElementById('jobChart').getContext('2d');

    if (jobChart) {
        jobChart.data.labels = job_counts_month;
        jobChart.data.datasets[0].data = job_counts;
        jobChart.update();
    } else {
        jobChart = new Chart(ctx1, {
        type: 'line',
        data: {
            labels: job_counts_month,
            datasets: [{
                label: 'Job Entries per Month',
                data: job_counts,
                fill: false,
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.3,
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Month'
                    }
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Job Count'
                    }
                }
            }
        }
    });
    }

    const lead_data = data.lead_data;

        if (lead_data && lead_data.length > 0) {
            const leads = lead_data.map(item => item.lead);
            const counts = lead_data.map(item => parseInt(item.job_count));

            const leadChartDom = document.getElementById('pieChart');
            const leadChart = echarts.init(leadChartDom);
            let leadChartOption;

            leadChartOption = {
                title: {
                    text: 'Job Entries by Lead Source',
                    left: 'center'
                },
                tooltip: {
                    trigger: 'item'
                },
                legend: {
                    orient: 'vertical',
                    left: 'left',
                    data: leads
                },
                series: [
                    {
                        name: 'Lead Source',
                        type: 'pie',
                        radius: '50%',
                        data: leads.map((lead, index) => ({ value: counts[index], name: lead })),
                        emphasis: {
                            itemStyle: {
                                shadowBlur: 10,
                                shadowOffsetX: 0,
                                shadowColor: 'rgba(0, 0, 0, 0.5)'
                            }
                        }
                    }
                ]
            };

            leadChartOption && leadChart.setOption(leadChartOption);
        } else {
            const leadChartDom = document.getElementById('pieChart');
            const leadChart = echarts.init(leadChartDom);
            leadChart.clear();
            leadChart.setOption({
                title: {
                    text: 'No Lead Data Available',
                    left: 'center'
                }
            });
        }


  const revenue_data = data.revenue_data;


  if (revenue_data && revenue_data.length > 0) {
    const revenueMonths = revenue_data.map(item => item.month);
    const revenueData = revenue_data.map(item => item.revenue);

    const empChartDom = document.getElementById('empChart');
    const empChart = echarts.init(empChartDom);
    const empChartOption = {
      title: {
        text: 'Revenue from Jobs',
        left: 'center',
        top: 10
      },
      tooltip: {
        trigger: 'axis',
        axisPointer: {
          type: 'line'
        }
      },
      grid: {
        top: 60,
        left: 80,
        right: 40,
        bottom: 60,
        containLabel: true
      },
      xAxis: {
        type: 'category',
        data: revenueMonths,
        name: 'Months',
        nameLocation: 'middle',
        nameGap: 40,
        axisLabel: {
          rotate: 0
        },
        nameTextStyle: {
          fontSize: 14,
          fontWeight: 'bold'
        }
      },
      yAxis: {
        type: 'value',
        name: 'Revenue',
        nameLocation: 'middle',
        nameRotate: 90,
        nameGap: 60,
        nameTextStyle: {
          fontSize: 14,
          fontWeight: 'bold'
        },
        splitLine: {
          lineStyle: {
            type: 'dashed'
          }
        }
      },
      series: [
        {
          name: 'Job Count',
          type: 'line',
          data: revenueData,
          itemStyle: {
            color: '#007bff'
          },
          lineStyle: {
            width: 2
          },
          emphasis: {
            focus: 'series'
          }
        }
      ]
    };

    empChart.setOption(empChartOption);
    window.addEventListener('resize', () => empChart.resize());
  } else {
    const empChartDom = document.getElementById('empChart');
    const empChart = echarts.init(empChartDom);
    empChart.clear();
    empChart.setOption({
      title: {
        text: 'No Revenue Data Available',
        left: 'center'
      }
    });
  }



    });
}

// Initial load with default date range
fetchAndRenderChart('<?php echo date('Y-m-01'); ?>', '<?php echo date('Y-m-d'); ?>');

// On filter button click
$('#filterBtn').click(function () {
    const from = $('#fromDate').val();
    const to = $('#toDate').val();
    const selectedfromDate = new Date(from);
    const selectedtoDate = new Date(to);
    const maxDate = new Date('<?php echo date('Y-m-d'); ?>');
    if(selectedfromDate > maxDate || selectedtoDate > maxDate) {
      alert('Date should not be greater');
      return;
    }
    if (from && to) {
        fetchAndRenderChart(from, to);
    } else {
        alert("Please select both dates.");
    }
});
</script>
</body>
</html>