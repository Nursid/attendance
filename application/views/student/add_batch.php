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
              <li class="breadcrumb-item active">Add Batch</li>
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
          <div class="col-md-4">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title"><span id="form_title">Add Batch</span></h3><br>
                <span style="color: red"><?php echo $this->session->flashdata('msg');?></span>
              </div>
              <form id="batch_form" action="<?php echo base_url('User/add_newsession')?>" method="post">
              <div class="card-body">
                <div class="row">
                  <div class="col-12 mb-3">
                    <?php
                        $data = $this->web->getBusinessDepByBusinessId($buid);
                    ?>

                    <select class="form-control select2" id="dept_select" style="width: 100%;" name="dept[]" multiple="multiple" data-placeholder="Select Branches">
                      
                        <?php
                        foreach($data as $key => $val){
                          
                           echo "<option value=" . $val->id . ">" .$val->name."</option>";                          
                      } 
                     ?>

                    </select>
                       
                  </div>
                  <div class="col-12 mb-3">
                    <input type="text" class="form-control" id="session_name" name="session" placeholder="Enter a name" required>
                    <input type="hidden" id="batch_id" name="batch_id" value="">
                  </div>
                  <input type="hidden" class="form-control" name="bid" value="<?php echo $buid ; ?>">
                  <div class="col-12">
                    <button type="submit" id="submit_btn" class="btn btn-danger btn-block">Add Now</button>
                  </div>
                  <div class="col-12 mt-2" id="cancel_div" style="display: none;">
                    <button type="button" id="cancel_btn" class="btn btn-secondary btn-block">Cancel</button>
                  </div>
                </div>
              </div>
              </form>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>

          <!-- right column -->
          <div class="col-md-8">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Batch List</h3>
              </div>
              <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>S.No</th>
                    <th>Branch Name</th>
                    <th>Batch Name</th>
                    <th>Actions</th>
                  </tr>
                  </thead>
                  <tbody>
                      <?php
                      $res=$this->web->getall_S_Session($buid);
                      $count=1;
                      foreach($res as $res){
                      ?>
                      <tr>
                        <td><?php echo $count++?></td>
                       
                        <td><?php 
                            $dep_ids = explode(',', $res->dep_id);
                            $branch_names = array();
                            
                            foreach($dep_ids as $dep_id) {
                                $dname = $this->web->getBusinessDepByUserId($dep_id);
                                if(!empty($dname)) {
                                    $branch_names[] = $dname[0]->name;
                                }
                            }
                            
                            echo implode(', ', $branch_names);
                        ?></td>
                         <td><?php echo $res->session_name?></td>
                         <td id="delete<?php echo $res->id; ?>">
                          <button class="btn btn-primary btn-sm edit-btn" data-id="<?php echo $res->id; ?>">
                            <i class="fa fa-edit" style="color:white"></i>
                          </button>
                          <button class="btn btn-danger btn-sm" onclick="delete_classname('<?php echo $res->id; ?>')" >
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
$(function () {
    //Initialize Select2 Elements
    $('.select2').select2({
      placeholder: "Select Branches",
      allowClear: true
    })

    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })
  });
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
      url: "User/delete_S_session",
      data: {id},
     success: function(){
    $('#delete'+id).text("deleted");
    }
	
    })
  }

  $(document).ready(function() {
    // Edit button click handler
    $('.edit-btn').on('click', function() {
      var id = $(this).data('id');
      
      // Change form title and button
      $('#form_title').text('Edit Batch');
      $('#submit_btn').text('Update');
      $('#submit_btn').removeClass('btn-danger').addClass('btn-primary');
      $('#cancel_div').show();
      
      // Change form action for update
      $('#batch_form').attr('action', '<?php echo base_url('User/update_batch')?>');
      
      // Get batch data by ID
      $.ajax({
        type: "POST",
        url: "<?php echo base_url('User/get_batch_by_id')?>",
        data: {id: id},
        dataType: 'json',
        success: function(data) {
          // Set batch ID for update
          $('#batch_id').val(data.id);
          
          // Set session name
          $('#session_name').val(data.session_name);
          
          // Set selected departments
          var depIds = data.dep_id.split(',');
          $('#dept_select').val(depIds).trigger('change');
        }
      });
    });
    
    // Cancel button click handler
    $('#cancel_btn').on('click', function() {
      // Reset form
      $('#batch_form').attr('action', '<?php echo base_url('User/add_newsession')?>');
      $('#batch_form')[0].reset();
      $('#batch_id').val('');
      $('#form_title').text('Add Batch');
      $('#submit_btn').text('Add Now');
      $('#submit_btn').removeClass('btn-primary').addClass('btn-danger');
      $('#cancel_div').hide();
      
      // Clear select2
      $('#dept_select').val(null).trigger('change');
    });
  });
</script>
</body>
</html>
