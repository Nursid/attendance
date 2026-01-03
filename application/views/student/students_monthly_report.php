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
              <li class="breadcrumb-item active">Monthly Report</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
     <?php
      if($this->session->userdata()['type']=='B' || $this->session->userdata()['type']=='P')
      {
        if ($this->session->userdata()['type'] == 'P') {
          //$busi = $this->web->getBusinessbyUser($this->web->session->userdata('login_id'));
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
                <h3 class="card-title">Monthly Report</h3>
              </div>
              <div class="card-body">
              
              <h5> Select Date Range
			     </h5> 
			   
              <div class="row">
    <div class="col-lg-12 float-left">
    
      <!-- <form action="<?php echo base_url('User/students_monthly_report_new')?>" method="POST" id="hostelmonthlyReport"> -->
      <form id="hostelmonthlyReport" onsubmit="return false;">

                          <div class="row">
                              
                              
                            <div class="from-group col-sm-2">
                 
                    <?php
                        $data = $this->web->getBusinessDepByBusinessId($bid);
                    ?>

                    <div class="form-group">
                        <select class="form-control select2" id="departs" name="dept">
                            <option value="" disabled selected>Select Branch</option>
                            <?php foreach($data as $key => $val){
                                $selected = ($dept == $val->id) ? 'selected' : '';
                                echo "<option value='" . $val->id . "' " . $selected . ">" .$val->name."</option>";                          
                            } ?>
                        </select>
                    </div>
                       
                  </div>  
                              
                     
              
              <div class="col-md-2">
                <div class="form-group">
                    <select class="form-control" id="sdeparts" name="session">
                        <option value="" disabled selected>Select Batch</option>
                        <?php 
                        if(isset($dept) && $dept > 0) {
                            $batches = $this->web->getSessionByDeptId($dept, $bid);
                            if(!empty($batches)) {
                                foreach($batches as $batch) {
                                    $selected = ($session == $batch->id) ? 'selected' : '';
                                    echo "<option value='" . $batch->id . "' " . $selected . ">" . $batch->session_name . "</option>";
                                }
                            }
                        }
                        ?>
                    </select>
                </div>
                <!-- /.form-group -->
              </div>

              <div class="col-md-2">
                <div class="form-group">
                    <select class="form-control" id="semester" name="semester">
                        <option value="" disabled selected>Select Semester</option>
                        <?php 
                        if(isset($dept) && $dept > 0) {
                            $semesters = $this->web->getallSemesters($bid);
                            if(!empty($semesters)) {
                                foreach($semesters as $sem) {
                                    $selected = ($semester == $sem->id) ? 'selected' : '';
                                    echo "<option value='" . $sem->id . "' " . $selected . ">" . $sem->semestar_name . "</option>";
                                }
                            }
                        }
                        ?>
                    </select>
                </div>
                <!-- /.form-group -->
              </div>
              
              <div class="col-md-2">
                <div class="form-group">
                <select class="form-control" id="section" name="section">
                    <option value="" disabled selected>Select Section</option>
                    <?php 
                    if(isset($session) && $session > 0) {
                        $sections = $this->web->getSectionsByBranchAndSemester($dept, $semester);
                        if(!empty($sections)) {
                            foreach($sections as $sec) {
                                $selected = ($section == $sec->id) ? 'selected' : '';
                                echo "<option value='" . $sec->id . "' " . $selected . ">" . $sec->name . "</option>";
                            }
                        }
                    }
                    ?>
                </select>
                </div>
                <!-- /.form-group -->
              </div>
              
              <div class="col-md-2">
                <div class="form-group">
                    <select class="form-control" id="subject" name="subject">
                        <option value="0">All Courses</option>
                        <?php 
                        if(!empty($all_subjects)) {
                            foreach($all_subjects as $subj) {
                                $selected = ($subject == $subj->id) ? 'selected' : '';
                                echo "<option value='" . $subj->id . "' " . $selected . ">" . $subj->name . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <!-- /.form-group -->
              </div>
                          
                              
                              
                              
                              
                              
                              
                              
                            <div class="col-sm-2">
                              <input type="date" name="start_date" id="start_date"  value="<?php echo $start_date; ?>" class="form-control" max="<?php echo $end_date; ?>" onchange="startChange(event);">
                            </div>
                            <div class="col-sm-2">
                              <input type="date" name="end_date" id="end_date"  value="<?php echo $end_date; ?>"class="form-control" max="<?php echo date('Y-m-d'); ?>" min="<?php echo $start_date;?>" onchange="endChange(event);">
                            </div>
                            
                             
                             
                            <div class="col-sm-1">
                            
                              <button type="button" id="actionSubmit" class="btn btn-success btn-fill btn-block">Show</button>
                            </div>
                          
                           </div>
                     </form>                           
    </div>
  </div>
              
           <br>   
              
             <?php
                      // if($load) {
                        $stdate=strtotime($start_date);
                        $endate=strtotime($end_date);
                        
                        $dep = $this->web->getBusinessDepByUserId($dept);
                    if(!empty($dep)){
                      $department=$dep[0]->name;  
                    }
                     $sess = $this->web->getSessionById($session);
                    if(!empty($sess)){
                      $ses=$sess[0]->session_name;  
                    }
                     $sec = $this->web->getsectionById($section);
                    if(!empty($sec)){
                      $sect=$sec[0]->name;  
                    }
                   
                        ?>
                       
                        <h5>Attendance for Date:-<?php echo date("d-M-Y ",$stdate)?> to Date:- <?php echo date("d-M-Y ",$endate)?></h5>
                        <h5>Branch: <?php echo $branch_name ?? 'N/A'; ?> | Batch: <?php echo $batch_name ?? 'N/A'; ?> | Semester: <?php echo $semester_name ?? 'N/A'; ?> | Section: <?php echo $section_name ?? 'N/A'; ?>
                        <?php if(!empty($subject_name)): ?> | Subject: <?php echo $subject_name; ?><?php endif; ?>
                        </h5>
                        
                    
<!--                   
                        <table id="attendanceReport" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Student ID</th>
                                <th>Student Name</th>
                                <?php foreach ($period_days as $dayIndex => $day): ?>
                                    <th class="text-center">
                                        <?php echo $day['sequential_day']; ?><br>
                                        <small>
                                            <?php echo $day['day_name']; ?><br>
                                            (<?php echo $day['calendar_day']; ?>)<br>
                                            <?php if(!empty($day['teacher_name'])): ?>
                                                <span class="text-primary"><?php echo $day['teacher_name']; ?></span>
                                            <?php endif; ?>
                                        </small>
                                    </th>
                                <?php endforeach; ?>
                                <th>Present</th>
                                <th>Absent</th>
                                <th>Holiday</th>
                                <th>Total Lecture</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($report as $index => $student): ?>
                                <?php 
                                $presentCount = 0;
                                $absentCount = 0;
                                $lectureCount = 0;
                                $holidayCount = 0;
                                ?>
                                <tr>
                                    <td><?php echo $index + 1; ?></td>
                                    <td><?php echo $student['user_id']; ?></td>
                                    <td><?php echo $student['name']; ?></td>
                                    
                                    <?php foreach ($student['data'] as $attendance): ?>
                                        <td class="text-center">
                                            <?php 
                                            if ($attendance['data']['status'] == 'P') {
                                              echo '<span class="badge bg-success">P</span>';
                                              $presentCount++;
                                              $lectureCount++;
                                          } elseif (strpos($attendance['data']['status'], 'Holiday') !== false) {
                                              // Check if the status contains 'Holiday'
                                              echo '<span class="badge bg-info">' . $attendance['data']['status'] . '</span>';
                                              // Optionally, you might want to count holidays separately
                                              $holidayCount++;
                                          } else {
                                              echo '<span class="badge bg-danger">A</span>';
                                              $absentCount++;
                                              $lectureCount++;
                                          }

                                            ?>
                                        </td>
                                    <?php endforeach; ?>
                                    
                                    <td class="text-center"><?php echo $presentCount; ?></td>
                                    <td class="text-center"><?php echo $absentCount; ?></td>
                                    <td class="text-center"><?php echo $holidayCount; ?></td>
                                    <td class="text-center"><?php echo $lectureCount; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table> -->

                    <div id="attendanceReport"></div>


                    <div class="mb-3">
                      <button id="exportToCsv" class="btn btn-primary">Export to CSV</button>
                  </div>
                      </div>
                    <?php }
                    ?>
                    <!-- /.card-body -->
                  </div>
                  <!-- /.card -->
                </div>
              </div>
              <!-- /.row -->
            </div><!-- /.container-fluid -->

  <?php 
        // }
   ?>
   </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
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
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.22/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>




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
        // var table = $('#attendanceReport').DataTable( {
        //     scrollY:        "500px",
        //     scrollX:        true,
        //     scrollCollapse: true,
        //     paging:         false,
        //     fixedColumns:   {
        //         leftColumns: 2
        //     }
        // } );
      $('.nav-link').click(function(e) {
        $('.nav-link').removeClass('active');
        $(this).addClass("active");

      });
      // var table = $('#example1').DataTable({
      //   searching:false,
      // });
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
      //alert(e.target.value);
      $('#end_date').attr('min', e.target.value);
    }
    function endChange(e){
      //alert(e.target.value);
      $('#start_date').attr('max', e.target.value);
    }
    function showLoader(){
      $(".loading").css("display","block");
    }

   
    </script>
    <script>
$(document).ready(function () {
  // Use event delegation to handle change events
  $(document).on('change', '#departs', function() {
    var branchId = this.value;
    $('#sdeparts').html('<option value="" disabled selected>Select Batch</option>');
    $('#semester').html('<option value="" disabled selected>Select Semester</option>');
    $('#section').html('<option value="" disabled selected>Select Section</option>');
    $('#subject').html('<option value="0">All Subjects</option>');

    $.ajax({
      type: "post",
      url: "<?php echo base_url('User/get_batch_by_branch'); ?>",
      data: {branch_id: branchId},
      success: function(data){
        var batches = JSON.parse(data);
        var options = '<option value="" disabled selected>Select Batch</option>';
        batches.forEach(function(batch) {
          options += '<option value="' + batch.id + '">' + batch.session_name + '</option>';
        });
        $('#sdeparts').html(options);
      },
      error: function() {
        console.log("Error loading batches");
      }
    });

    $.ajax({
      type: "post",
      url: "<?php echo base_url('User/get_semester_by_branch'); ?>",
      data: {branch_id: branchId},
      success: function(data){
        var semesters = JSON.parse(data);
        var options = '<option value="" disabled selected>Select Semester</option>';
        semesters.forEach(function(semester) {
          options += '<option value="' + semester.id + '">' + semester.semestar_name + '</option>';
        });
        $('#semester').html(options);
      }
    });
    
    // Load subjects for the selected branch/department
    $.ajax({
      type: "post",
      url: "<?php echo base_url('User/getallsubjectbyid'); ?>",
      data: {bid: branchId},
      success: function(data){
        var subjects = JSON.parse(data);
        var options = '<option value="0">All Subjects</option>';
        subjects.forEach(function(subject) {
          options += '<option value="' + subject.id + '">' + subject.name + '</option>';
        });
        $('#subject').html(options);
      },
      error: function() {
        console.log("Error loading subjects");
      }
    });
  });

  $(document).on('change', '#semester', function() {
    var branchId = $('#departs').val();
    var semesterId = this.value;
    $('#section').html('<option value="" disabled selected>Select Section</option>');

    $.ajax({
        type: "post",
        url: "<?php echo base_url('User/get_section_by_branch_semester'); ?>",
        data: {branch_id: branchId, semester_id: semesterId},
        success: function(data){
            var sections = JSON.parse(data);
            var options = '<option value="" disabled selected>Select Section</option>';
            sections.forEach(function(section) {
                var selected = (section.id == <?= $section ?>) ? 'selected' : '';
                options += '<option value="' + section.id + '" ' + selected + '>' + section.name + '</option>';
            });
            $('#section').html(options);
        },
        error: function() {
            console.log("Error loading sections");
        }
    });
});
  
  // Initialize values based on current params if they exist
  <?php if(isset($dept) && $dept > 0): ?>
  $('#departs').trigger('change');
  <?php endif; ?>
  
  <?php if(isset($session) && $session > 0): ?>
  setTimeout(function() {
    $('#sdeparts').val('<?= $session ?>').trigger('change');
  }, 500);
  <?php endif; ?>
  
  <?php 
  if(isset($section) && $section > 0):
   ?>
  setTimeout(function() {
    $('#section').val('<?= $section ?>');
  }, 1000);
  <?php endif; ?>
  
  <?php if(isset($semester) && $semester > 0): ?>
  setTimeout(function() {
    $('#semester').val('<?= $semester ?>');
  }, 1000);
  <?php endif; ?>
  
  <?php if(isset($subject) && $subject > 0): ?>
  setTimeout(function() {
    $('#subject').val('<?= $subject ?>');
  }, 1500);
  <?php endif; ?>
});
</script>

<script>
$(document).ready(function() {
    $('#exportToCsv').click(function() {
        // Get the header information from your HTML
        var dateRange = $('h5:contains("Attendance for Date")').text().trim();
        var details = $('h5:contains("Branch:")').text().trim();
        
        // Get the table data
        var table = document.getElementById('attendanceReport');
        var rows = table.querySelectorAll('tr');
        
        // Prepare CSV content
        var csv = [];
        
        // Add header information as merged rows
        // First row (date range) - merge all columns
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
        
        // Add table headers with merged cells
        var headerRow = [];
        var headers = table.querySelectorAll('thead th');
        headers.forEach(function(header) {
            // Remove the <br> and <small> content from day headers
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
                // For attendance cells, get the status (P/A/N/A)
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
        link.setAttribute('download', 'attendance_report.csv');
        link.style.visibility = 'hidden';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    });
  })

$(document).ready(function () {

  $("#actionSubmit").click(function () {
    let payload = {
        dept: $("#departs").val(),
        session: $("#sdeparts").val(),
        semester: $("#semester").val(),
        section: $("#section").val(),
        subject: $("#subject").val(),
        start_date: $("#start_date").val(),
        end_date: $("#end_date").val()
    };

    $.ajax({
        url: "http://localhost:5000/api/students_monthly_report",
        method: "POST",
        contentType: "application/json",
        data: JSON.stringify(payload),
        success: function (response) {
            renderMonthlyReport(response);
        }
    });
});


function renderMonthlyReport(data) {
  console.log(data)
    let report = data.report;
    let periodDays = data.period_days;

    let tableHtml = `
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Student ID</th>
                    <th>Name</th>
    `;

    periodDays.forEach(day => {
        tableHtml += `
            <th class="text-center">
                ${day.sequential_day}<br>
                <small>
                    ${day.day_name}<br>
                    (${day.calendar_day})<br>
                    ${day.teacher_name ?? ""}
                </small>
            </th>
        `;
    });

    tableHtml += `
        <th>Present</th>
        <th>Absent</th>
        <th>Holiday</th>
        <th>Total Lecture</th>
        </tr>
        </thead>
        <tbody>
    `;

    report.forEach((student, index) => {
        let present = 0, absent = 0, holiday = 0, lecture = 0;

        tableHtml += `
            <tr>
                <td>${index + 1}</td>
                <td>${student.user_id}</td>
                <td>${student.name}</td>
        `;

        student.data.forEach(day => {
            let status = day.data.status;

            if (status === "P") {
                present++; lecture++;
            } else if (status.includes("Holiday")) {
                holiday++;
            } else {
                absent++; lecture++;
            }

            tableHtml += `<td class="text-center">${status}</td>`;
        });

        tableHtml += `
            <td>${present}</td>
            <td>${absent}</td>
            <td>${holiday}</td>
            <td>${lecture}</td>
            </tr>
        `;
    });

    tableHtml += "</tbody></table>";
    console.log(tableHtml)

    document.getElementById("attendanceReport").innerHTML = tableHtml;
}

});
</script>

