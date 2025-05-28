<?php 
date_default_timezone_set('Asia/Kolkata');
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>MID</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
 <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url('adminassets/plugins/fontawesome-free/css/all.min.css')?>">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  
  <!-- Select2 -->
  <link rel="stylesheet" href="<?php echo base_url('adminassets/plugins/select2/css/select2.min.css')?>">
  <link rel="stylesheet" href="<?php echo base_url('adminassets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')?>">
  <link rel="stylesheet" href="<?php echo base_url('adminassets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')?>">
  <link rel="stylesheet" href="<?php echo base_url('adminassets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')?>">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url('adminassets/dist/css/adminlte.min.css')?>">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
<?php $this->load->view('student/student_menu')?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-12">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Teachers Monthly Report</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
     <?php
      if($this->session->userdata()['type']=='B' || $this->session->userdata()['type']=='P')
      {
        if ($this->session->userdata()['type'] == 'P') {
          $bid = $this->session->userdata('empCompany');
          $role=$this->web->getRollbyid($this->web->session->userdata('login_id'),$bid);
        } else {
          $bid = $this->web->session->userdata('login_id');
        }
      ?>
      <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <div class="card card-danger">
              <div class="card-header">
                <h3 class="card-title">Teachers Monthly Report</h3>
              </div>
              <div class="card-body">
              <div class="row">
                <div class="col-lg-12 float-left">
                  <form action="<?php echo base_url('User/teachers_monthly_report')?>" method="POST" id="teachersMonthlyReport">
                    <div class="row">      
                      <div class="col-sm-2">
                        <input type="date" name="start_date" id="start_date" value="<?php echo $start_date; ?>" class="form-control" max="<?php echo $end_date; ?>" onchange="startChange(event);">
                      </div>
                      <div class="col-sm-2">
                        <input type="date" name="end_date" id="end_date" value="<?php echo $end_date; ?>" class="form-control" max="<?php echo date('Y-m-d'); ?>" min="<?php echo $start_date;?>" onchange="endChange(event);">
                      </div>
                      <div class="col-sm-1">
                        <button type="submit" id="actionSubmit" class="btn btn-success btn-fill btn-block" onclick="showLoader()">Show</button>
                      </div>
                    </div>
                  </form>                           
                </div>
              </div>
              
              <br>   
              
              <?php if($load && !empty($report)) { 
                $stdate = strtotime($start_date);
                $endate = strtotime($end_date);
              ?>
                       
                <h5>Teachers Attendance for Date: <?php echo date("d-M-Y", $stdate)?> to Date: <?php echo date("d-M-Y", $endate)?></h5>
                        
                <table id="attendanceReport" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Teacher Name</th>
                      <?php foreach ($period_days as $day): ?>
                        <th class="text-center">
                          <?php echo $day['sequential_day']; ?><br>
                          <small>
                            <?php echo $day['day_name']; ?><br>
                            (<?php echo $day['calendar_day']; ?>)
                          </small>
                        </th>
                      <?php endforeach; ?>
                      <th>Present</th>
                      <th>Absent</th>
                      <th>Holiday</th>
                      <th>Total Days</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($report as $index => $teacher): ?>
                      <?php 
                      $presentCount = 0;
                      $absentCount = 0;
                      $totalDays = 0;
                      $holidayCount = 0;
                      ?>
                      <tr>
                        <td><?php echo $index + 1; ?></td>
                        <td><?php echo $teacher['name']; ?></td>
                        <?php foreach ($teacher['data'] as $attendance): ?>
                          <td class="text-center">
                            <?php 
                            if ($attendance['data']['status'] == 'P') {
                              echo '<span class="badge bg-success">P</span>';
                              $presentCount++;
                              $totalDays++;
                            } elseif (strpos($attendance['data']['status'], 'Holiday') !== false) {
                              echo '<span class="badge bg-info">H</span>';
                              $holidayCount++;
                            } else {
                              echo '<span class="badge bg-danger">A</span>';
                              $absentCount++;
                              $totalDays++;
                            }
                            ?>
                          </td>
                        <?php endforeach; ?>
                        
                        <td class="text-center"><?php echo $presentCount; ?></td>
                        <td class="text-center"><?php echo $absentCount; ?></td>
                        <td class="text-center"><?php echo $holidayCount; ?></td>
                        <td class="text-center"><?php echo $totalDays; ?></td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>

                <div class="mb-3">
                  <button id="exportToCsv" class="btn btn-primary">Export to CSV</button>
                </div>
              <?php } elseif($load && empty($report)) { ?>
                <div class="alert alert-info">
                  <h5>No teachers found for the selected date range.</h5>
                </div>
              <?php } ?>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
  <?php } ?>
  
  <?php $this->load->view('menu/footer')?>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>

<!-- jQuery -->
<script src="<?php echo base_url('adminassets/plugins/jquery/jquery.min.js')?>"></script>
<!-- Bootstrap 4 -->
<script src="<?php echo base_url('adminassets/plugins/bootstrap/js/bootstrap.bundle.min.js')?>"></script>
<!-- bs-custom-file-input -->
<script src="<?php echo base_url('adminassets/plugins/bs-custom-file-input/bs-custom-file-input.min.js')?>"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url('adminassets/plugins/datatables/jquery.dataTables.min.js')?>"></script>
<!-- Select2 -->
<script src="<?php echo base_url('adminassets/plugins/select2/js/select2.full.min.js')?>"></script>
<script src="<?php echo base_url('adminassets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')?>"></script>
<script src="<?php echo base_url('adminassets/plugins/datatables-responsive/js/dataTables.responsive.min.js')?>"></script>
<script src="<?php echo base_url('adminassets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')?>"></script>
<script src="<?php echo base_url('adminassets/dist/js/adminlte.min.js')?>"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo base_url('adminassets/dist/js/demo.js')?>"></script>

<script>
$(document).ready(function () {
    var table = $('#attendanceReport').DataTable( {
        scrollY: "500px",
        scrollX: true,
        scrollCollapse: true,
        paging: false,
        fixedColumns: {
            leftColumns: 3
        }
    });
    
    $('.nav-link').click(function(e) {
        $('.nav-link').removeClass('active');
        $(this).addClass("active");
    });
});

$(function () {
    var url = window.location;
    // for single sidebar menu
    $('ul.nav-sidebar a').filter(function () {
        return this.href == url;
    }).addClass('active');

    // for sidebar menu and treeview
    $('ul.nav-treeview a').filter(function () {
        return this.href == url;
    }).parentsUntil(".nav-sidebar > .nav-treeview")
    .css({'display': 'block'})
    .addClass('menu-open').prev('a')
    .addClass('active');
});

function startChange(e){
    $('#end_date').attr('min', e.target.value);
}

function endChange(e){
    $('#start_date').attr('max', e.target.value);
}

function showLoader(){
    $(".loading").css("display","block");
}
</script>

<script>
$(document).ready(function() {
    $('#exportToCsv').click(function() {
        // Get the header information from your HTML
        var dateRange = $('h5:contains("Teachers Attendance for Date")').text().trim();
        var details = $('h5:contains("Company:")').text().trim();
        
        // Get the table data
        var table = document.getElementById('attendanceReport');
        if (!table) return;
        
        var rows = table.querySelectorAll('tr');
        
        // Prepare CSV content
        var csv = [];
        
        // Add header information as merged rows
        var columnCount = table.querySelectorAll('thead th').length;
        var headerRow1 = ['"' + dateRange + '"'];
        for (var i = 1; i < columnCount; i++) {
            headerRow1.push('""');
        }
        csv.push(headerRow1.join(','));
        
        // Second row (details) - merge all columns
        var headerRow2 = ['"' + details + '"'];
        for (var i = 1; i < columnCount; i++) {
            headerRow2.push('""');
        }
        csv.push(headerRow2.join(','));
        
        // Add empty row as separator
        csv.push('');
        
        // Add table headers
        var headerRow = [];
        var headers = table.querySelectorAll('thead th');
        headers.forEach(function(header) {
            var headerText = header.innerText.replace(/\n/g, ' ').trim();
            headerRow.push('"' + headerText + '"');
        });
        csv.push(headerRow.join(','));
        
        // Add data rows
        var dataRows = table.querySelectorAll('tbody tr');
        dataRows.forEach(function(row) {
            var rowData = [];
            var cols = row.querySelectorAll('td');
            cols.forEach(function(col) {
                // For attendance cells, get the status (P/A/H)
                if (col.querySelector('.badge')) {
                    var status = col.querySelector('.badge').innerText;
                    rowData.push('"' + status + '"');
                } else {
                    rowData.push('"' + col.innerText + '"');
                }
            });
            csv.push(rowData.join(','));
        });
        
        // Create CSV file and download
        var csvContent = csv.join('\n');
        var blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        var url = URL.createObjectURL(blob);
        var link = document.createElement('a');
        link.setAttribute('href', url);
        link.setAttribute('download', 'teachers_attendance_report.csv');
        link.style.visibility = 'hidden';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    });
});
</script>

</body>
</html>

