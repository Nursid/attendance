<?php
date_default_timezone_set('Asia/Kolkata');
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>OBHS Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="<?php echo base_url('adminassets/plugins/fontawesome-free/css/all.min.css')?>">
  <link rel="stylesheet" href="<?php echo base_url('adminassets/dist/css/adminlte.min.css')?>">
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo base_url('adminassets/dist/css/mid.css')?>">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <?php $this->load->view('menu/menu')?>
  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">OBHS Feedback Dashboard</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url('page')?>">Home</a></li>
              <li class="breadcrumb-item active">OBHS Dashboard</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">

        <!-- Date filter -->
        <div class="card">
          <div class="card-body py-2">
            <form action="<?php echo base_url('obhs-dashboard')?>" method="GET" class="form-inline">
              <label class="mr-2">From</label>
              <input type="date" name="start_date" class="form-control form-control-sm mr-2" value="<?php echo isset($filters['start_date']) ? $filters['start_date'] : '';?>">
              <label class="mr-2">To</label>
              <input type="date" name="end_date" class="form-control form-control-sm mr-2" value="<?php echo isset($filters['end_date']) ? $filters['end_date'] : '';?>">
              <button type="submit" class="btn btn-sm btn-primary mr-2">Apply</button>
              <a href="<?php echo base_url('obhs-dashboard')?>" class="btn btn-sm btn-secondary">Reset</a>
            </form>
          </div>
        </div>

        <!-- Summary cards -->
        <div class="row">
          <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
              <div class="inner">
                <h3><?php echo (int)$summary['total_feedback'];?></h3>
                <p>Total Feedback</p>
              </div>
              <div class="icon"><i class="fas fa-comments"></i></div>
              <a href="<?php echo base_url('obhs-master-search')?>" class="small-box-footer">Master Search <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
              <div class="inner">
                <h3><?php echo $summary['avg_psi'] !== null ? $summary['avg_psi'] : '0';?><sup style="font-size:20px">/100</sup></h3>
                <p>Average PSI Score</p>
              </div>
              <div class="icon"><i class="fas fa-chart-line"></i></div>
              <a href="<?php echo base_url('obhs-psi-report')?>" class="small-box-footer">PSI Report <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
              <div class="inner">
                <h3><?php echo (int)$summary['total_complaints'];?></h3>
                <p>Complaints (<?php echo (int)$summary['pending_complaints'];?> Pending / <?php echo (int)$summary['resolved_complaints'];?> Resolved)</p>
              </div>
              <div class="icon"><i class="fas fa-exclamation-circle"></i></div>
              <a href="<?php echo base_url('obhs-complaints')?>" class="small-box-footer">Complaint Tracking <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
              <div class="inner">
                <h3><?php echo (int)$summary['trains_covered'];?> / <?php echo (int)$summary['active_janitors'];?></h3>
                <p>Trains Covered / Active Janitors</p>
              </div>
              <div class="icon"><i class="fas fa-train"></i></div>
              <a href="<?php echo base_url('obhs-janitor-report')?>" class="small-box-footer">Janitor Performance <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
        </div>

        <!-- Charts -->
        <div class="row">
          <div class="col-md-6">
            <div class="card">
              <div class="card-header"><h3 class="card-title">Category Wise Average Score (0-4, Not Attended = 0)</h3></div>
              <div class="card-body"><canvas id="categoryChart" height="220"></canvas></div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card">
              <div class="card-header"><h3 class="card-title">PSI Distribution</h3></div>
              <div class="card-body"><canvas id="psiChart" height="220"></canvas></div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header"><h3 class="card-title">Monthly Trend (Feedback, Avg PSI, Complaints)</h3></div>
              <div class="card-body"><canvas id="monthlyChart" height="90"></canvas></div>
            </div>
          </div>
        </div>

        <!-- Recent feedback -->
        <div class="row">
          <div class="col-md-12">
            <div class="card card-primary">
              <div class="card-header"><h3 class="card-title">Recent Feedback</h3></div>
              <div class="card-body table-responsive p-0">
                <table class="table table-hover table-sm text-nowrap">
                  <thead>
                    <tr>
                      <th>Journey Date</th><th>Train No</th><th>Coach</th><th>Passenger</th>
                      <th>PSI</th><th>Janitor</th><th></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach($recent as $row){ ?>
                    <tr>
                      <td><?php echo date('d-m-Y',strtotime($row['journey_date']));?></td>
                      <td><?php echo $row['train_no'];?></td>
                      <td><?php echo $row['coach_no'];?></td>
                      <td><?php echo $row['passenger_name'];?></td>
                      <td><span class="badge badge-<?php echo ($row['psi_score']>=80)?'success':(($row['psi_score']>=60)?'info':(($row['psi_score']>=40)?'warning':'danger'));?>"><?php echo $row['psi_score'];?></span></td>
                      <td><?php echo !empty($row['janitor_name']) ? $row['janitor_name'] : $row['staff_name'];?></td>
                      <td><a href="<?php echo base_url('obhs-feedback/'.$row['id'])?>" class="btn btn-xs btn-outline-primary">View</a></td>
                    </tr>
                    <?php } if(empty($recent)){ echo '<tr><td colspan="7" class="text-center">No feedback yet</td></tr>'; } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

      </div>
    </section>
  </div>
</div>

<script src="<?php echo base_url('adminassets/plugins/jquery/jquery.min.js')?>"></script>
<script src="<?php echo base_url('adminassets/plugins/bootstrap/js/bootstrap.bundle.min.js')?>"></script>
<script src="<?php echo base_url('adminassets/plugins/chart.js/Chart.bundle.js')?>"></script>
<script src="<?php echo base_url('adminassets/dist/js/adminlte.min.js')?>"></script>
<script>
var categoryData = <?php echo json_encode($category);?>;
var monthlyData  = <?php echo json_encode($monthly);?>;
var psiDist      = <?php echo json_encode($psi_dist);?>;

new Chart(document.getElementById('categoryChart'),{
  type:'bar',
  data:{
    labels: categoryData.map(function(c){return c.label;}),
    datasets:[{
      label:'Avg Rating',
      data: categoryData.map(function(c){return c.avg;}),
      backgroundColor:['#17a2b8','#28a745','#ffc107','#dc3545']
    }]
  },
  options:{scales:{yAxes:[{ticks:{beginAtZero:true,max:4}}]},legend:{display:false}}
});

new Chart(document.getElementById('psiChart'),{
  type:'doughnut',
  data:{
    labels:['Excellent (80+)','Good (60-79)','Average (40-59)','Poor (<40)'],
    datasets:[{
      data:[psiDist.excellent||0, psiDist.good||0, psiDist.average||0, psiDist.poor||0],
      backgroundColor:['#28a745','#17a2b8','#ffc107','#dc3545']
    }]
  }
});

new Chart(document.getElementById('monthlyChart'),{
  type:'line',
  data:{
    labels: monthlyData.map(function(m){return m.month;}),
    datasets:[
      {label:'Feedback', data: monthlyData.map(function(m){return parseInt(m.total_feedback);}), borderColor:'#17a2b8', fill:false},
      {label:'Avg PSI', data: monthlyData.map(function(m){return parseFloat(m.avg_psi)||0;}), borderColor:'#28a745', fill:false},
      {label:'Complaints', data: monthlyData.map(function(m){return parseInt(m.complaints);}), borderColor:'#dc3545', fill:false}
    ]
  },
  options:{scales:{yAxes:[{ticks:{beginAtZero:true}}]}}
});
</script>
</body>
</html>
