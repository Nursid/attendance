<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>MidApp</title>
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

  <?php
  if($this->session->userdata()['type']=='P'){
    $getUserCompanies  = $this->web->getUserCompanies($this->session->userdata('login_id'));
    ?>
  
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mt-4">
        <div class="col-sm-2 ml-auto">
          <div class="form-group">
          <select class="form-control" id="selectBusiness" name="selectBusiness" onchange="switchCompany(this)">
            <?php 
              foreach($getUserCompanies as $empCompany){
                $businessSelected = "";
                if($this->session->userdata('empCompany')==$empCompany->bid){
                  $businessSelected = "selected";
                }
                echo "<option value='$empCompany->bid' $businessSelected>$empCompany->name</option>";
              }
            ?>
          </select>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php
  }
  
  ?>

    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
         
          <div class="col-sm-12">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Teachers List</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
<?php
   if($this->session->userdata()['type']=='B' || $this->session->userdata()['type']=='P' ){ 
	  
	  if($this->session->userdata()['type']=='P'){
      // $busi=$this->web->getBusinessbyUser($this->web->session->userdata('login_id'));
      // $id=$busi[0]->business_id;
      $id = $this->session->userdata('empCompany');
      $role=$this->web->getRollbyid($this->web->session->userdata('login_id'),$id);
  
    } else {
      $id=$this->web->session->userdata('login_id');
    }
    ?>
    <!-- Main content -->
    <section class="content">
      <?php
      if($this->session->userdata()['type']=='B' || $role[0]->employee_list=="1"){?>
      <div class="container-fluid">
        <!-- Teacher Search Form -->
        <div class="row mb-4">
          <div class="col-md-12">
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Search Teacher's Classes</h3>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="teacher_select">Select Teacher:</label>
                      <select class="form-control select2" id="teacher_select" style="width: 100%;">
                        <option value="">-- Select Teacher --</option>
                        <?php if(!empty($teachers)): ?>
                          <?php foreach($teachers as $teacher): ?>
                            <option value="<?php echo $teacher->mobile; ?>" data-name="<?php echo $teacher->name; ?>">
                              <?php echo $teacher->name; ?> (<?php echo $teacher->mobile; ?>)
                            </option>
                          <?php endforeach; ?>
                        <?php endif; ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <label>&nbsp;</label>
                    <button class="btn btn-primary btn-block" id="search_teacher">Search Classes</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Teacher's Classes Result -->
        <div class="row" id="teacher_classes_section" style="display:none;">
          <div class="col-md-12">
            <div class="card card-success">
              <div class="card-header">
                <h3 class="card-title" id="classes_title">Teacher's Assigned Classes</h3>
              </div>
              <div class="card-body">
                <div id="classes_loading" style="display:none;">
                  <div class="text-center">
                    <div class="spinner-border text-primary" role="status">
                      <span class="sr-only">Loading...</span>
                    </div>
                    <p>Loading classes data...</p>
                  </div>
                </div>
                <div id="no_classes_found" style="display:none;">
                  <div class="alert alert-warning">
                    No classes assigned to this teacher or teacher not found.
                  </div>
                </div>
                <div id="classes_data_container">
                  <!-- Day tabs will be generated dynamically -->
                  <ul class="nav nav-tabs" id="dayTabs" role="tablist"></ul>
                  
                  <!-- Tab content will be generated dynamically -->
                  <div class="tab-content" id="dayTabContent"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="row">
          <!-- left column -->
          <div class="col-md-2">
            <div class="card card-info">
            <!--  <div class="card-header">
                <span style="color: red"><?php echo $this->session->flashdata('msg');?></span>
              </div>-->
              
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>
        <!-- /.row -->
        
      </div><!-- /.container-fluid -->
      <?php
      }else{?>
        <div class="container-fluid">
          <div class="col-sm-4 mx-auto">
            <h4>Not Authorized to Access This Page</h4>
          </div>
        </div>
      <?php 
      }?>   
    </section> <?php 
                        }
                      
                      ?>
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
  $(function () {
   var table = $('#example1').DataTable({
     "responsive": true,
      "autoWidth": false,
      "paging": false,
      order: [[1, 'asc']],
    });
   
  });
</script>
<script>$(document).ready(function () { 
// Initialize Select2 for teacher dropdown
$('#teacher_select').select2({
  placeholder: "-- Select Teacher --",
  allowClear: true
});

$('.nav-link').click(function(e) {
$('.nav-link').removeClass('active');        
$(this).addClass("active");

});

// Search for teacher's assigned classes
$('#search_teacher').click(function() {
  var mobile = $('#teacher_select').val();
  var teacherName = $('#teacher_select option:selected').data('name');
  
  if(mobile === '') {
    alert('Please select a teacher');
    return;
  }
  
  // Show loading
  $('#classes_loading').show();
  $('#no_classes_found').hide();
  
  // Clear previous content
  $('#dayTabs').empty();
  $('#dayTabContent').empty();
  
  // Create day tabs dynamically before making the API call
  var days = [
    {id: 'sunday', label: 'Sunday', dayNum: '0'},
    {id: 'monday', label: 'Monday', dayNum: '1'},
    {id: 'tuesday', label: 'Tuesday', dayNum: '2'},
    {id: 'wednesday', label: 'Wednesday', dayNum: '3'},
    {id: 'thursday', label: 'Thursday', dayNum: '4'},
    {id: 'friday', label: 'Friday', dayNum: '5'},
    {id: 'saturday', label: 'Saturday', dayNum: '6'}
  ];
  
  // Create tabs
  $.each(days, function(i, day) {
    // Create tab
    $('#dayTabs').append(
      '<li class="nav-item">' +
        '<a class="nav-link" id="' + day.id + '-tab" data-toggle="tab" href="#' + day.id + '" role="tab">' + day.label + '</a>' +
      '</li>'
    );
    
    // Create tab content
    $('#dayTabContent').append(
      '<div class="tab-pane fade" id="' + day.id + '" role="tabpanel">' +
        '<div class="table-responsive mt-3">' +
          '<table class="table table-bordered table-striped">' +
            '<thead>' +
              '<tr>' +
                '<th>Time</th>' +
                '<th>Subject</th>' +
                '<th>Section</th>' +
                '<th>Department</th>' +
                '<th>Semester</th>' +
                '<th>Room</th>' +
              '</tr>' +
            '</thead>' +
            '<tbody id="' + day.id + '-classes"></tbody>' +
          '</table>' +
        '</div>' +
      '</div>'
    );
  });
  
  $('#teacher_classes_section').show();
  
  // Update the title with teacher's name
  $('#classes_title').text("Classes for " + teacherName);
  
  // Ajax call to get teacher's assigned classes
  $.ajax({
    url: '<?php echo base_url("User/get_assigned_class_by_teacher") ?>',
    type: 'POST',
    data: {teacher_mobile: mobile},
    dataType: 'json',
    success: function(response) {
      $('#classes_loading').hide();
      
      if(response.status === 'success' && response.data && response.data.length > 0) {
        var classCountByDay = {};
        
        // Initialize counts for each day
        $.each(days, function(i, day) {
          classCountByDay[day.dayNum] = 0;
        });
        
        // Process each class
        $.each(response.data, function(index, cls) {
          var dayNumber = cls.days;
          var dayId = getDayIdFromNumber(dayNumber, days);
          
          if (dayId) {
            // Create row for this class
            var html = '<tr>' +
              '<td>' + cls.start_time + ' - ' + cls.end_time + '</td>' +
              '<td>' + cls.subject_name + '</td>' +
              '<td>' + cls.section_name + '</td>' +
              '<td>' + cls.dept_name + '</td>' +
              '<td>' + cls.semester_name + '</td>' +
              '<td>' + cls.class_room + '</td>' +
            '</tr>';
            
            // Add to the appropriate day tab
            $('#' + dayId + '-classes').append(html);
            classCountByDay[dayNumber]++;
          }
        });
        
        // Show "No classes" message for empty days
        $.each(days, function(i, day) {
          if (classCountByDay[day.dayNum] === 0) {
            $('#' + day.id + '-classes').html('<tr><td colspan="7" class="text-center text-info">No classes assigned for ' + day.label + '</td></tr>');
          }
        });
        
        // Find first day with classes
        var firstDayWithClasses = null;
        for (var i = 0; i < days.length; i++) {
          if (classCountByDay[days[i].dayNum] > 0) {
            firstDayWithClasses = days[i].id;
            break;
          }
        }
        
        if (firstDayWithClasses) {
          // Show the tab for the first day with classes
          $('#' + firstDayWithClasses + '-tab').tab('show');
        } else {
          // No classes found for any day
          $('#no_classes_found').show();
        }
      } else {
        // Show no classes found message
        $('#no_classes_found').show();
      }
    },
    error: function(xhr, status, error) {
      $('#classes_loading').hide();
      $('#no_classes_found').show();
    }
  });
});

// Helper function to get day ID from day number
function getDayIdFromNumber(dayNumber, days) {
  for (var i = 0; i < days.length; i++) {
    if (days[i].dayNum === dayNumber) {
      return days[i].id;
    }
  }
  return null;
}

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
</script>
</body>
</html>
