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
              <li class="breadcrumb-item active">Add Section</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>


<?php 

$buid=$this->web->session->userdata('login_id');
?>
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column - Add Section Form -->
          <div class="col-md-4">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Add Section</h3><br>
                <span style="color: red"><?php echo $this->session->flashdata('msg');?></span>
              </div>
              <form action="<?php echo base_url('User/add_newsection')?>" method="post" id="section_form">
              <div class="card-body">
                <div class="row">
                  <div class="col-12 mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                      <!-- <label class="mb-0">Branches and Semesters</label> -->
                      <div class="d-flex">
                        <div class="form-check mr-3">
                          <input class="form-check-input" type="checkbox" id="select-all-branches">
                          <label class="form-check-label" for="select-all-branches">
                            <strong>All Branches</strong>
                          </label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" id="select-all-semesters">
                          <label class="form-check-label" for="select-all-semesters">
                            <strong>All Semesters</strong>
                          </label>
                        </div>
                      </div>
                    </div>
                   
                    <div id="branches_semesters_container" class="border p-3 rounded">
                    <div class="col-12 mb-3">
                    <input type="text" class="form-control" name="name" placeholder="Enter section title" id="depart" required>
                  </div>
                      <?php
                        // Get all branchess
                        $branches = $this->web->getBusinessDepByBusinessId($buid);
                        // Get all semesters
                        $allSemesters = $this->web->getallSemesters($buid);
                        
                        if(!empty($branches)) {
                          // First create an array to store semesters by branch
                          $semestersByBranch = array();
                          
                          // Organize semesters by branch
                          foreach($branches as $branch) {
                            $semestersByBranch[$branch->id] = array(
                              'name' => $branch->name,
                              'semesters' => array()
                            );
                          }
                          
                          // Categorize semesters by branch
                          foreach($allSemesters as $semester) {
                            $semesterDeps = explode(',', $semester->dep_id);
                            foreach($semesterDeps as $depId) {
                              if(isset($semestersByBranch[$depId])) {
                                $semestersByBranch[$depId]['semesters'][] = $semester;
                              }
                            }
                          }
                          
                          // Display branches and their semesters
                          foreach($semestersByBranch as $branchId => $branchData) {
                            if(empty($branchData['semesters'])) {
                              continue; // Skip branches with no semesters
                            }
                            
                            echo '<div class="branch-section mb-4">';
                            echo '<div class="branch-header d-flex align-items-center bg-primary text-white p-2 rounded">';
                            echo '<div class="form-check mb-0 mr-2">';
                            echo '<input class="form-check-input branch-checkbox" type="checkbox" value="' . $branchId . '" id="branch_' . $branchId . '" data-branch-name="' . $branchData['name'] . '">';
                            echo '</div>';
                            echo '<h5 class="mb-0 branch-title">' . $branchData['name'] . '</h5>';
                            echo '</div>';
                            
                            echo '<div class="semester-list pl-4" id="semesters_' . $branchId . '" style="display:none;">';
                            foreach($branchData['semesters'] as $semester) {
                              echo '<div class="form-check">';
                              echo '<input class="form-check-input semester-checkbox" data-branch="' . $branchId . '" type="checkbox" value="' . $semester->id . '" id="semester_' . $branchId . '_' . $semester->id . '" data-semester-name="' . $semester->semestar_name . '" disabled>';
                              echo '<label class="form-check-label" for="semester_' . $branchId . '_' . $semester->id . '">' . $semester->semestar_name . ' (' . $semester->year . ' Year)</label>';
                              echo '</div>';
                            }
                            echo '</div>'; // End semester-list
                            echo '</div>'; // End branch-section
                          }
                        } else {
                          echo '<div class="alert alert-info">No branches found</div>';
                        }
                      ?>
                    </div>
                  </div>
                  
               

                  <input type="hidden" class="form-control" name="bid" value="<?php echo $buid ; ?>">
                  
                  <!-- Hidden input to store structured branch/semester data -->
                  <input type="hidden" name="structured_data" id="structured_data" value="">
                  
                  <div class="col-12">
                    <button type="submit" class="btn btn-danger btn-block">Add Now</button>
                  </div>
                </div>
              </div>
              </form>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>

          <!-- right column - Section List -->
          <div class="col-md-8">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Section List</h3>
              </div>
              <div class="card-body">
    <table id="example1" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>S.No</th>
                <th>Title</th>
                <th>Branch > Semester</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $res = $this->web->getall_S_sectionbyid($buid);
         
            $count = 1;
            foreach($res as $res) {
            ?>
            <tr>
                <td><?php echo $count++?></td>
                <td><?php echo $res->name ?></td>
                <td>
                    <?php 
                    // Get branch-semester combinations for this section
                    $branchSemesters = $this->web->getSectionBranchSemesters($res->id);
                    
                    $branchSemesterList = array();
                    foreach($branchSemesters as $bs) {
                        $branchName = $this->web->getBusinessDepByUserId($bs->branch_id);
                       
                        $semesterName = $this->web->getSemesterNameById($bs->semester_id);
                        
                        if($branchName && $semesterName) {
                            $branchSemesterList[] = $branchName[0]->name . ' > ' . $semesterName;
                        }
                    }
                    
                    echo implode('<br>', $branchSemesterList);
                    ?>
                </td>
                <td><span class="badge badge-success">Active</span></td>
                <td id="delete<?php echo $res->id; ?>">
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal" onclick="mclick('<?php echo $res->id; ?>')">
                        <i class="fas fa-edit" style="color:white"></i>
                    </button>
                    <button class="btn btn-danger btn-sm" onclick="delete_classname('<?php echo $res->id; ?>')">
                        <i class="fa fa-times" style="color:white"></i>
                    </button>
                </td>
            </tr>
            <?php 
            }
            ?>
        </tbody>
    </table>
</div>
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
  <?php $this->load->view('menu/footer')?>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->





<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">Edit Section</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <div id="modform">
          
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>





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
    });
   
  });
</script>
<script>
function mclick(data){
  var add_section_data = "add_section";
  $.ajax({
      type: "POST",
      url: "User/getajaxRequest",
      data: {data,add_section_data},
    success: function(response){
      $('#modform').html(response);
    }
    })
}
</script>
<script>$(document).ready(function () { 
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
</script>
<script>
  function delete_classname(id){
    $.ajax({
      type: "POST",
      url: "User/delete_S_Section",
      data: {id},
     success: function(){
    $('#delete'+id).text("deleted");
    }
	
    })
  }
</script>

<script>
$(function () {
    //Initialize Select2 Elements
    $('.select2').select2();

    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    });
    
    // Branch checkbox functionality
    $('.branch-checkbox').on('change', function() {
      var branchId = $(this).val();
      var isChecked = $(this).is(':checked');
      
      // Show/hide semester list for this branch
      $('#semesters_' + branchId).toggle(isChecked);
      
      // Enable/disable semester checkboxes for this branch
      $('.semester-checkbox[data-branch="' + branchId + '"]').prop('disabled', !isChecked);
      
      // If branch is unchecked, uncheck all its semesters
      if (!isChecked) {
        $('.semester-checkbox[data-branch="' + branchId + '"]').prop('checked', false);
      }
      
      // If branch is checked and "select all semesters" is checked, check all its semesters
      if (isChecked && $('#select-all-semesters').is(':checked')) {
        $('.semester-checkbox[data-branch="' + branchId + '"]').prop('checked', true);
      }
      
      // Update "Select All Branches" checkbox
      updateSelectAllBranches();
    });
    
    // "Select All Branches" checkbox functionality
    $('#select-all-branches').on('change', function() {
      var isChecked = $(this).is(':checked');
      $('.branch-checkbox').prop('checked', isChecked);
      
      // Show/hide all semester lists
      if (isChecked) {
        $('.semester-list').show();
      } else {
        $('.semester-list').hide();
      }
      
      // Enable/disable all semester checkboxes
      $('.semester-checkbox').prop('disabled', !isChecked);
      
      // If unchecking all branches, uncheck all semesters
      if (!isChecked) {
        $('.semester-checkbox').prop('checked', false);
      }
      
      // If checking all branches and "select all semesters" is checked, check all semesters
      if (isChecked && $('#select-all-semesters').is(':checked')) {
        $('.semester-checkbox').prop('checked', true);
      }
    });
    
    // "Select All Semesters" checkbox functionality
    $('#select-all-semesters').on('change', function() {
      var isChecked = $(this).is(':checked');
      
      // Only check semesters for selected branches
      $('.branch-checkbox:checked').each(function() {
        var branchId = $(this).val();
        $('.semester-checkbox[data-branch="' + branchId + '"]').prop('checked', isChecked);
      });
      
      // Update "All Semesters" checkbox status
      updateSelectAllSemesters();
    });
    
    // When any individual semester checkbox changes
    $('.semester-checkbox').on('change', function() {
      updateSelectAllSemesters();
    });
    
    // Helper function to update "Select All Branches" checkbox
    function updateSelectAllBranches() {
      var allBranches = $('.branch-checkbox').length;
      var selectedBranches = $('.branch-checkbox:checked').length;
      $('#select-all-branches').prop('checked', allBranches === selectedBranches && allBranches > 0);
    }
    
    // Helper function to update "Select All Semesters" checkbox
    function updateSelectAllSemesters() {
      var enabledSemesters = $('.semester-checkbox:not(:disabled)').length;
      var selectedSemesters = $('.semester-checkbox:checked').length;
      $('#select-all-semesters').prop('checked', enabledSemesters === selectedSemesters && enabledSemesters > 0);
    }

    // Handle form submission
    $('#section_form').on('submit', function(e) {
      // Create a structured data object
      var structuredData = {};
      var deptArray = [];
      var sessionArray = [];
      
      // Loop through all checked branches
      $('.branch-checkbox:checked').each(function() {
        var branchId = $(this).val();
        deptArray.push(branchId);
        
        // Find all checked semesters for this branch
        var branchSemesters = [];
        $('.semester-checkbox[data-branch="' + branchId + '"]:checked').each(function() {
          var semesterId = $(this).val();
          sessionArray.push(semesterId);
          branchSemesters.push(semesterId);
        });
        
        // Add branch and its semesters to the structured data
        structuredData[branchId] = branchSemesters;
      });
      
      // Store structured data in hidden input
      $('#structured_data').val(JSON.stringify(structuredData));
      
      // Add traditional form fields for backward compatibility
      if (deptArray.length > 0) {
        deptArray.forEach(function(dept) {
          $('<input>').attr({
            type: 'hidden',
            name: 'dept[]',
            value: dept
          }).appendTo('#section_form');
        });
      }
      
      if (sessionArray.length > 0) {
        sessionArray.forEach(function(session) {
          $('<input>').attr({
            type: 'hidden',
            name: 'session[]',
            value: session
          }).appendTo('#section_form');
        });
      }
    });
  });
</script>











</body>
</html>
