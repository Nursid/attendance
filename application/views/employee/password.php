
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
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url('adminassets/dist/css/adminlte.min.css')?>">
  <link rel="stylesheet" href="<?php echo base_url('adminassets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css')?>">
  <script src="<?php echo base_url('adminassets/plugins/sweetalert2/sweetalert2.min.js')?>"></script>
<!-- Toastr -->
  <script src="<?php echo base_url('adminassets/plugins/toastr/toastr.min.js')?>"></script>
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
 <?php $this->load->view('employee/staff_menu')?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
         
          <div class="col-sm-12">
            
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Settings</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Change Password</h3><br>
                <span style="color: red"><?php echo $this->session->flashdata('msg');?></span>
              </div>
              <div class="card-body">
              <form action="<?php echo base_url('User/update_password')?>" method="post">
                <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="newpass">New Password:</label>
                    <input type="text" id="newpass" name="npass" class="form-control" placeholder="Enter new password" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="cnewpass">Confirm Password:</label>
                    <input type="text" id="cnewpass" name="cnpass" class="form-control" placeholder="Enter new password" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="oldpass">Old Password:</label>
                    <input type="password" id="oldpass" name="opass" class="form-control"placeholder="Enter old password" required>
                  </div>
                </div>
                <div class="col-md-6">
                    <button type="submit" class="btn-lg btn-success float-right mt-4">Update</button>
                </div>
                </div>
              </form>
            </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>
        <!-- .row -->
        <?php if($this->session->userdata('type') == 'C'){?>
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title">Change Display Name</h3><br>
                
              </div>
              <div class="card-body">
              <form action="<?php echo base_url('User/updateDispName')?>" method="post">
                <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="displayname">Enter new name:</label>
                    <input type="text" id="displayname" name="name" class="form-control" placeholder="Enter new name" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="pass">Password:</label>
                    <input type="password" id="pass" name="pass" class="form-control"placeholder="Enter password" required>
                  </div>
                </div>
                <div class="col-md-12">
                    <button type="submit" class="btn-lg btn-success float-right">Update</button>
                </div>
                </div>
              </form>
            </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>
        <!-- .row -->
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title">Change Username</h3><br>
                <span id="w_msg" style="color: red; font-size: 1.5rem;"></span>
              </div>
              <div class="card-body">
              <form id="uform" action="<?php echo base_url('User/updateUserName')?>" method="post">
                <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="username">Enter new username:</label>
                    <input type="text" id="username" name="uname" class="form-control" placeholder="Enter new name" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="pass2">Password:</label>
                    <input type="password" id="pass2" name="password" class="form-control"placeholder="Enter password" required>
                  </div>
                </div>
                <div class="col-md-12">
                    <button type="button" id="bt_form" class="btn-lg btn-success float-right">Update</button>
                </div>
                </div>
              </form>
            </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>
        <!-- .row -->
      <?php } ?>
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
<!-- Select2 -->
<script src="<?php echo base_url('adminassets/plugins/select2/js/select2.full.min.js')?>"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url('adminassets/dist/js/adminlte.min.js')?>"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo base_url('adminassets/dist/js/demo.js')?>"></script>
<script src="<?php echo base_url('adminassets/plugins/datatables/jquery.dataTables.min.js')?>"></script>
<link rel="stylesheet" href="<?php echo base_url('adminassets/plugins/select2/css/select2.min.css')?>">
<script src="<?php echo base_url('adminassets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')?>"></script>
<script src="<?php echo base_url('adminassets/plugins/datatables-responsive/js/dataTables.responsive.min.js')?>"></script>
<script src="<?php echo base_url('adminassets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')?>"></script>
<script>
  $(function () {
    $('#example1').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });
</script>
<script>
$('.toastsDefaultSuccess').click(function() {
      $(document).Toasts('create', {
        class: 'bg-success', 
        title: 'Toast Title',
        subtitle: 'Subtitle',
        body: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
      })
    });
$('#bt_form').on('click', function(){
        var fdata = $("#uform").serialize();
      $.ajax({
        type: "POST",
        url: "User/updateUserName",
        data: fdata,
        datatype: "json",
      success: function(data){
        if(data == 0){
          console.log(data);
          $('#w_msg').html('Username already taken, try another name!');
          $('form :input').val('');
        }else if(data == 1){
          $('#w_msg').html('Username updated successfully!');
          $('form :input').val('');
        }
      }
    });
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
</body>
</html>
