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
              <li class="breadcrumb-item active">Add Time Table</li>
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
          <!-- left column -->
          <div class="col-md-12">
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Add Time Table</h3><br>
                <span style="color: red"><?php echo $this->session->flashdata('msg');?></span>
              </div>
              <form action="<?php echo base_url('User/add_newtimetable')?>" method="post">
              <div class="card-body">
                       <div class="row">
                 <!-- <div class="from-group col-md-3">
                 <label for="block">Class</label>
                            <select name="class" class="form-control"  id="block" >
                              <option value="">Select CLass</option>    
                                 
                   <?php
				  
				   $classes = $this->web->getallclassbyid($buid);
                    if(!empty($classes)){
                      foreach($classes as $clas):
                        echo "<option value=".$clas->id .">".$clas->name."</option>";
                      endforeach;
                   }
				   
                   ?></select>
                               
                  </div>-->
                  
                  
                  
                  
                  
           
                  <div class="from-group col-md-2">
                    <label for="depart">Time Table Name</label>
                    <input type="text" class="form-control" name="name" placeholder="Enter a name" id="depart" required>
                  </div>
				  <div class="from-group col-md-2">
                    <label for="depart">Start Date</label>
                    <input type="date" class="form-control" name="start" placeholder="Enter a name" id="depart" required>
                  </div>
				  <div class="from-group col-md-2">
                    <label for="depart">End Date</label>
                    <input type="date" class="form-control" name="end" placeholder="Enter a name" id="depart" required>
                  </div>

                  <div class="from-group col-md-2">
                    <label for="branch">Branch</label>
                    <select name="branch_id" class="form-control" id="branch" required>
                      <option value="">Select Branch</option>
                      <?php
                        $branches = $this->web->getBusinessDepByBusinessId($buid);
                        if(!empty($branches)){
                          foreach($branches as $branch):
                            echo "<option value=".$branch->id.">".$branch->name."</option>";
                          endforeach;
                        }
                      ?>
                    </select>
                  </div>

                  <div class="from-group col-md-2">
                    <label for="batch">Batch</label>
                    <select name="batch_id" class="form-control" id="batch" required>
                      <option value="">Select Batch</option>
                    </select>
                  </div>

                  <div class="from-group col-md-2">
                    <label for="semester">Semester</label>
                    <select name="semester_id" class="form-control" id="semester" required>
                      <option value="">Select Semester</option>
                    </select>
                  </div>

                  <div class="from-group col-md-2">
                    <label for="section">Section</label>
                    <select name="section_id" class="form-control" id="section" required>
                      <option value="">Select Section</option>
                    </select>
                  </div>

                 <input type="hidden" class="form-control" name="bid" value="<?php echo $buid ; ?>">
                  

                  <div class="from-group col-md-5">
                  <button class=" btn btn-success mt-4 mx-auto">Add Now</button>
                  </div>
                </div>
              </div>
              </form>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>

        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Time Table  List</h3>
              </div>
              <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>S. No</th>
                    <th>Name</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Branch</th>
                    <th>Batch</th>
                    <th>Semester</th>
                    <th>Section</th>
                    <th>Periods</th>
                    <th>Edit</th>
                    <th>Delete</th>
                  </tr>
                  </thead>
                  <tbody>
                      <?php
                      $res = $this->web->getall_timetable($buid);
                      $count=1;
                      foreach($res as $res){
                      ?>
                      <tr>
                        <td><?php echo $count++; ?></td>
                        <td><?php echo $res->name ; ?></td>
                        <td><?php echo date("d-M-y",$res->start_date) ; ?></td>
                        <td><?php echo date("d-M-y",$res->end_date) ; ?></td>
                        <td><?php 
                            if(isset($res->dept)) {
                                $branch = $this->web->getBusinessDepByUserId($res->dept);
                                echo !empty($branch) ? $branch[0]->name : 'N/A';
                            } else {
                                echo 'N/A';
                            }
                        ?></td>
                        <td><?php 
                            if(isset($res->session)) {
                                $batch = $this->web->getSessionById($res->session);
                                echo !empty($batch) ? $batch[0]->session_name : 'N/A';
                            } else {
                                echo 'N/A';
                            }
                        ?></td>
                        <td><?php 
                            if(isset($res->semester_id)) {
                                $semester = $this->web->getSemesterById($res->semester_id);
                                echo !empty($semester) ? $semester[0]->semestar_name : 'N/A';
                            } else {
                                echo 'N/A';
                            }
                        ?></td>
                        <td><?php 
                            if(isset($res->section)) {
                                $section = $this->web->getsectionById($res->section);
                                echo !empty($section) ? $section[0]->name : 'N/A';
                            } else {
                                echo 'N/A';
                            }
                        ?></td>
                        <td>
                          <a href="<?php echo base_url('User/period_timetable/'.$res->id); ?>" class="btn btn-success btn-sm">
                            <i class="fas fa-calendar-alt"></i> Manage Periods
                          </a>
                        </td>
                        <td>
                          <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal" onclick="mclick('<?php echo $res->id; ?>')">
                            <i class="fas fa-edit"></i>
                          </button>
                        </td>
                        <td id="delete<?php echo $res->id; ?>">
                          <button class="btn btn-danger" onclick="delete_classname('<?php echo $res->id; ?>')" >
                          <i class="fa fa-times" style="color:white"></i>
                          </button>
                        </td>
                      </tr>
                      <?php 
                      }
                      ?>
                  </tfoot>
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
  $(document).ready(function() {
    // When branch changes
    $('#branch').change(function() {
      var branchId = $(this).val();
      if(branchId != '') {
        // Clear and disable dependent dropdowns
        $('#batch').empty().append('<option value="">Loading batches...</option>');
        $('#semester').empty().append('<option value="">Select Semester</option>');
        $('#section').empty().append('<option value="">Select Section</option>');
        
        // Get batches for selected branch
        $.ajax({
          type: "POST",
          url: "<?php echo base_url('User/get_batches_by_dept'); ?>",
          data: {dept_id: branchId},
          success: function(response) {
            var batches = JSON.parse(response);
            $('#batch').empty().append('<option value="">Select Batch</option>');
            $.each(batches, function(index, batch) {
              $('#batch').append('<option value="' + batch.id + '">' + batch.session_name + '</option>');
            });
          }
        });
      }
    });

    // When batch changes
    $('#batch').change(function() {
      var batchId = $(this).val();
      var branchId = $('#branch').val();
      if(batchId != '' && branchId != '') {
        // Clear and disable dependent dropdowns
        $('#semester').empty().append('<option value="">Loading semesters...</option>');
        $('#section').empty().append('<option value="">Select Section</option>');
        
        // Get semesters for selected branch
        $.ajax({
          type: "POST",
          url: "<?php echo base_url('User/get_semester_by_branch'); ?>",
          data: {branch_id: branchId},
          success: function(response) {
            var semesters = JSON.parse(response);
            $('#semester').empty().append('<option value="">Select Semester</option>');
            $.each(semesters, function(index, semester) {
              $('#semester').append('<option value="' + semester.id + '">' + semester.semestar_name + '</option>');
            });
          }
        });
      }
    });

    // When semester changes
    $('#semester').change(function() {
      var semesterId = $(this).val();
      console.log("semesterId---",semesterId)
      var batchId = $('#branch').val();
      if(semesterId != '' && batchId != '') {
        // Clear and disable section dropdown
        $('#section').empty().append('<option value="">Loading sections...</option>');
        
        // Get sections for selected batch and semester
        $.ajax({
          type: "POST",
          url: "<?php echo base_url('User/get_section_by_branch_semester'); ?>",
          data: {branch_id: batchId, semester_id: semesterId},
          success: function(response) {
            var sections = JSON.parse(response);
            $('#section').empty().append('<option value="">Select Section</option>');
            $.each(sections, function(index, section) {
              $('#section').append('<option value="' + section.id + '">' + section.name + '</option>');
            });
          }
        });
      }
    });
  });
</script>
</body>
</html>
