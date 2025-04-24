<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title>School ERP Dashboard</title>
  
  <!-- jQuery -->
  <script src="<?php echo base_url('adminassets/plugins/jquery/jquery.min.js')?>"></script>

  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="<?php echo base_url('adminassets/plugins/fontawesome-free/css/all.min.css')?>">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="<?php echo base_url('adminassets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css')?>">
  <link rel="stylesheet" href="<?php echo base_url('adminassets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')?>">
  <link rel="stylesheet" href="<?php echo base_url('adminassets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')?>">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url('adminassets/dist/css/adminlte.min.css')?>">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  
  <style>
    .card-dashboard {
      transition: all 0.3s ease;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 4px 25px 0 rgba(0, 0, 0, 0.1);
    }
    
    .card-dashboard:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 25px 0 rgba(0, 0, 0, 0.2);
    }
    
    .info-box {
      border-radius: 10px;
      min-height: 100px;
    }
    
    .info-box-icon {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 30px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    
    .info-box-content {
      padding-left: 20px;
    }
    
    .info-box-number {
      font-size: 24px;
      font-weight: 700;
    }
    
    .custom-select-sm {
      border-radius: 20px;
      border: 1px solid #e0e0e0;
      box-shadow: 0 2px 5px rgba(0,0,0,0.08);
    }
    
    .attendance-percentage {
      font-size: 36px;
      font-weight: 700;
      color: #4e73df;
    }
    
    .chart-container {
      position: relative;
      height: 300px;
      width: 100%;
    }
    
    .filter-card {
      background: linear-gradient(to right, #f5f7fa, #e5ebf2);
      border: none;
    }
    
    .attendance-card {
      background: linear-gradient(to right, #4e73df, #224abe);
      color: white;
    }
    
    .card-title {
      font-weight: 600;
    }
  </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
<div class="wrapper">
  <?php $this->load->view('student/student_menu')?>
  
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Dashboard</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">


         <!-- Info boxes -->
         <div class="row">
          <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box card-dashboard">
              <span class="info-box-icon bg-info"><i class="fas fa-building"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Total Branches</span>
                <span class="info-box-number" id="total-branches"><?php echo $total_branches; ?></span>
              </div>
            </div>
          </div>
          <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box card-dashboard">
              <span class="info-box-icon bg-success"><i class="fas fa-user-graduate"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Total Students</span>
                <span class="info-box-number" id="total-students"><?php echo $total_students; ?></span>
              </div>
            </div>
          </div>
          <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box card-dashboard">
              <span class="info-box-icon bg-warning"><i class="fas fa-chalkboard-teacher"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Total Staff</span>
                <span class="info-box-number" id="total-staff"><?php echo $total_staff; ?></span>
              </div>
            </div>
          </div>
          <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box card-dashboard">
              <span class="info-box-icon bg-danger"><i class="fas fa-book"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Total Courses</span>
                <span class="info-box-number" id="total-subjects"><?php echo $total_subjects; ?></span>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Filter Row -->
        <div class="row mb-4">
          <div class="col-md-12">
            <div class="card filter-card">
              <div class="card-body">
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label><i class="fas fa-building mr-1"></i> Branch</label>
                      <select class="form-control custom-select-sm" id="branch_select">
                        <option value="" selected disabled>Select Branch</option>
                        <?php foreach($branches as $branch): ?>
                        <option value="<?php echo $branch->id; ?>"><?php echo $branch->name; ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label><i class="fas fa-layer-group mr-1"></i> Semester</label>
                      <select class="form-control custom-select-sm" id="semester_select">
                        <option value="" selected disabled>Select Semester</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="form-group">
                      <label><i class="fas fa-graduation-cap mr-1"></i> Section</label>
                      <select class="form-control custom-select-sm" id="section_select">
                        <option value="" selected disabled>Select Section</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="form-group">
                      <label>&nbsp;</label>
                      <button id="filter_button" class="btn btn-primary btn-block">
                        <i class="fas fa-filter mr-1"></i> Filter
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Main Charts Row -->
        <div class="row mt-4">
          <div class="col-md-6">
            <div class="card card-dashboard">
              <div class="card-header">
                <h3 class="card-title">Students by Branch</h3>
              </div>
              <div class="card-body">
                <div class="chart-container">
                  <canvas id="branchStudentsChart"></canvas>
                </div>
              </div>
            </div>
          </div>
          
          <div class="col-md-6">
            <div class="card card-dashboard">
              <div class="card-header">
                <h3 class="card-title">Subject-wise Attendance</h3>
              </div>
              <div class="card-body">
                <div id="subjectAttendanceContainer" class="chart-container">
                  <div class="text-center py-5">
                    <p>Select a branch to view subject-wise attendance</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    
    <!-- Attendance Table Section -->
    <section class="content">
      <div class="container-fluid">
        <div class="row mt-4">
          <div class="col-md-12">
            <div id="attendanceTableContainer">
        
              <!-- <div class="text-center">
                <p>Use the filter and click the Filter button to view today's attendance</p>
              </div> -->
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
  
  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  
  <!-- Main Footer -->
  <?php $this->load->view('menu/footer')?>
</div>

<!-- REQUIRED SCRIPTS -->
<!-- jQuery -->
<script src="<?php echo base_url('adminassets/plugins/jquery/jquery.min.js')?>"></script>
<!-- Bootstrap -->
<script src="<?php echo base_url('adminassets/plugins/bootstrap/js/bootstrap.bundle.min.js')?>"></script>
<!-- overlayScrollbars -->
<script src="<?php echo base_url('adminassets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')?>"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url('adminassets/dist/js/adminlte.js')?>"></script>
<!-- DataTables -->
<script src="<?php echo base_url('adminassets/plugins/datatables/jquery.dataTables.min.js')?>"></script>
<script src="<?php echo base_url('adminassets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')?>"></script>
<script src="<?php echo base_url('adminassets/plugins/datatables-responsive/js/dataTables.responsive.min.js')?>"></script>
<!-- ChartJS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
<!-- Excel Export Libraries -->
<script src="https://unpkg.com/exceljs/dist/exceljs.min.js"></script>
<script src="https://unpkg.com/file-saver/dist/FileSaver.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
  // Initialize Dashboard Charts
  initializeCharts();
  
  // Setup filter change events
  setupFilterEvents();
});

function initializeCharts() {
  // Students by Branch Chart
  const branchStudentsCtx = document.getElementById('branchStudentsChart').getContext('2d');
  const branchStudentsChart = new Chart(branchStudentsCtx, {
    type: 'bar',
    data: {
      labels: [],
      datasets: [{
        label: 'Number of Students',
        data: [],
        backgroundColor: [
          'rgba(78, 115, 223, 0.8)',
          'rgba(28, 200, 138, 0.8)',
          'rgba(246, 194, 62, 0.8)',
          'rgba(54, 185, 204, 0.8)',
          'rgba(90, 92, 105, 0.8)'
        ],
        borderWidth: 0
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: false
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          grid: {
            drawBorder: false
          },
          ticks: {
            precision: 0
          }
        },
        x: {
          grid: {
            display: false
          }
        }
      }
    }
  });

  // Store chart references in window object for later access
  window.dashboardCharts = {
    branchStudents: branchStudentsChart
  };
}

function updateCharts(data) {
  const charts = window.dashboardCharts;
  
  // Update branch students chart
  if (data.branches_data) {
    const branchLabels = data.branches_data.map(branch => branch.name);
    const branchData = data.branches_data.map(branch => branch.student_count);
    
    if (charts && charts.branchStudents) {
      charts.branchStudents.data.labels = branchLabels;
      charts.branchStudents.data.datasets[0].data = branchData;
      charts.branchStudents.update();
    }
  }
  
  // Update subject attendance chart
  if (data.subject_attendance && data.subject_attendance.length > 0) {
    let subjectHtml = `<canvas id="subjectAttendanceChart" height="300"></canvas>`;
    $('#subjectAttendanceContainer').html(subjectHtml);
    
    const chartElement = document.getElementById('subjectAttendanceChart');
    if (chartElement) {
      // Extract data for the chart
      const subjectLabels = data.subject_attendance.map(subject => subject.name);
      const presentData = data.subject_attendance.map(subject => subject.present || 0);
      const absentData = data.subject_attendance.map(subject => subject.absent || 0);
      
      new Chart(chartElement.getContext('2d'), {
        type: 'bar',
        data: {
          labels: subjectLabels,
          datasets: [
            {
              label: 'Present',
              data: presentData,
              backgroundColor: 'rgba(54, 162, 235, 0.7)',
              borderColor: 'rgba(54, 162, 235, 1)',
              borderWidth: 1
            },
            {
              label: 'Absent',
              data: absentData,
              backgroundColor: 'rgba(255, 99, 132, 0.7)',
              borderColor: 'rgba(255, 99, 132, 1)',
              borderWidth: 1
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: 'top',
            },
            tooltip: {
              callbacks: {
                label: function(context) {
                  return context.dataset.label + ': ' + context.raw + ' students';
                }
              }
            }
          },
          scales: {
            x: {
              grid: {
                display: false
              }
            },
            y: {
              beginAtZero: true,
              grid: {
                drawBorder: false
              },
              ticks: {
                precision: 0
              }
            }
          }
        }
      });
    }
  } else {
    $('#subjectAttendanceContainer').html('<div class="text-center py-5"><p>No subject attendance data available</p></div>');
  }
  
  // Update dashboard cards with summary data
  if (data.summary) {
    $('#total-students').text(data.summary.total_students || 0);
    $('#total-staff').text(data.summary.total_staff || 0);
    $('#total-branches').text(data.summary.total_branches || 0);
    $('#total-subjects').text(data.summary.total_subjects || 0);
  }
}

// Add Excel export function
function exportTableToExcel(tableID, filename = 'attendance_report.xlsx') {
  // Create a new workbook
  var workbook = new ExcelJS.Workbook();
  var worksheet = workbook.addWorksheet('Attendance');
  
  // Get the table
  var table = document.getElementById(tableID);
  if (!table) return;
  
  // Add report title
  worksheet.mergeCells('A1:J1');
  var titleCell = worksheet.getCell('A1');
  titleCell.value = 'Daily Attendance Report - ' + new Date().toLocaleDateString();
  titleCell.font = { size: 16, bold: true };
  titleCell.alignment = { horizontal: 'center' };
  
  // Get headers
  var headers = [];
  table.querySelectorAll('thead th').forEach(th => {
    // Clean up header text (remove line breaks)
    let headerText = th.innerText.replace(/\n/g, ' - ');
    headers.push(headerText);
  });
  
  // Add headers to worksheet
  worksheet.addRow(headers);
  
  // Format header row
  worksheet.getRow(2).eachCell(cell => {
    cell.font = { bold: true };
    cell.fill = {
      type: 'pattern',
      pattern: 'solid',
      fgColor: { argb: 'FFE0E0E0' }
    };
    cell.border = {
      top: { style: 'thin' },
      left: { style: 'thin' },
      bottom: { style: 'thin' },
      right: { style: 'thin' }
    };
  });
  
  // Add data rows
  table.querySelectorAll('tbody tr').forEach(tr => {
    let rowData = [];
    tr.querySelectorAll('td').forEach(td => {
      // Clean up cell text (remove line breaks)
      let cellText = td.innerText.replace(/\n/g, ', ');
      rowData.push(cellText);
    });
    worksheet.addRow(rowData);
  });
  
  // Auto-size columns
  worksheet.columns.forEach(column => {
    let maxLength = 0;
    column.eachCell({ includeEmpty: true }, cell => {
      const length = cell.value ? cell.value.toString().length : 10;
      if (length > maxLength) {
        maxLength = length;
      }
    });
    column.width = maxLength < 10 ? 10 : maxLength + 2;
  });
  
  // Write to file
  workbook.xlsx.writeBuffer().then(data => {
    const blob = new Blob([data], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
    saveAs(blob, filename);
  });
}

function setupFilterEvents() {
  // Branch dropdown change event
  $('#branch_select').on('change', function() {
    const branchId = $(this).val();
    
    // Clear other dropdowns
    $('#section_select').html('<option value="" selected disabled>Select Section</option>');
    $('#semester_select').html('<option value="" selected disabled>Select Semester</option>');
    
    if(branchId) {
      // Fetch semesters for this branch
      $.ajax({
        type: "post",
        url: "<?php echo base_url('User/get_semester_by_branch'); ?>",
        data: {branch_id: branchId},
        success: function(data) {
          const semesters = JSON.parse(data);
          let options = '<option value="" selected disabled>Select Semester</option>';
          
          semesters.forEach(function(semester) {
            options += `<option value="${semester.id}">${semester.semestar_name}</option>`;
          });
          
          $('#semester_select').html(options);
        }
      });
    }
  });
  
  // Semester dropdown change event
  $('#semester_select').on('change', function() {
    const branchId = $('#branch_select').val();
    const semesterId = $(this).val();
    
    if(branchId && semesterId) {
      // Fetch sections for this branch and semester
      $.ajax({
        type: "post",
        url: "<?php echo base_url('User/get_section_by_branch_semester'); ?>",
        data: {branch_id: branchId, semester_id: semesterId},
        success: function(data) {
          const sections = JSON.parse(data);
          let options = '<option value="" selected disabled>Select Section</option>';
          
          sections.forEach(function(section) {
            options += `<option value="${section.id}">${section.name}</option>`;
          });
          
          $('#section_select').html(options);
        }
      });
    }
  });
  
  // Apply Filter button click event
  $('#filter_button').on('click', function() {
    const branchId = $('#branch_select').val();
    const semesterId = $('#semester_select').val();
    const sectionId = $('#section_select').val();
    
    if(!branchId) {
      alert('Please select a Branch');
      return;
    }
    
    // Show loading state
    $(this).html('<i class="fas fa-spinner fa-spin mr-1"></i> Loading...');
    $(this).prop('disabled', true);
    
    // Prepare request data
    const requestData = {
      action: 'get_dashboard_data',
      branch_id: branchId
    };
    
    // Add optional filters if selected
    if(semesterId) requestData.semester_id = semesterId;
    if(sectionId) requestData.section_id = sectionId;
    
    // Fetch dashboard data based on filters
    $.ajax({
      url: '<?php echo base_url('User/student_dashboard_api'); ?>',
      type: 'POST',
      dataType: 'json',
      data: requestData,
      contentType: 'application/x-www-form-urlencoded',
      success: function(response) {
        // Reset button state
        $('#filter_button').html('<i class="fas fa-filter mr-1"></i> Filter');
        $('#filter_button').prop('disabled', false);
        
        if(response.status === 'success') {
          updateCharts(response.data);
        } else {
          console.error('Error: ' + (response.message || 'Unknown error'));
          alert('Failed to fetch data: ' + (response.message || 'Unknown error'));
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        // Reset button state
        $('#filter_button').html('<i class="fas fa-filter mr-1"></i> Filter');
        $('#filter_button').prop('disabled', false);
        console.error('AJAX Error: ' + textStatus + ': ' + errorThrown);
        alert('Failed to connect to the server. Please check your connection and try again.');
      }
    });
  });
}
</script>

</body>
</html>
