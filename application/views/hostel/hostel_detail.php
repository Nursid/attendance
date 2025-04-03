
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
 <?php $this->load->view('hostel/hostel_menu')?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
         
          <div class="col-sm-12">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Hostel Detail</li>
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
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Add Block</h3><br>
                <span style="color: red"><?php echo $this->session->flashdata('msg');?></span>
              </div>
              <form action="<?php echo base_url('User/addblock')?>" method="post">
              <div class="card-body">
                <div class="row">
                  <div class="from-group col-md-5">
                    <label for="depart">Name</label>
                    <input type="text" class="form-control" name="block" placeholder="Enter a name" id="depart" required>
                  </div>
                  <div class="from-group col-md-5">
                   
                    <input type="hidden" class="form-control" name="bid" value="<?php echo $bid=$this->web->session->userdata('login_id'); ?> ">
                  </div>

                  
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
                <h3 class="card-title">Block List</h3>
              </div>
              <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>S. No</th>
                    <th>Block Name</th>
                    
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                      <?php
					  $bid=$this->web->session->userdata('login_id');
                      $res=$this->web->getallBlock($bid);
                      $count=1;
                      foreach($res as $res){
                      ?>
                      <tr>
                        <td><?php echo $count++; ?></td>
                        <td><?php echo $res->name; ?></td>
                        
                        <td>
                          <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal" onclick="mclick('<?php echo $res->id; ?>')">
                            <i class="fas fa-edit"></i>
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
    
    
     <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Add Room Type</h3><br>
                <span style="color: red"><?php echo $this->session->flashdata('msg');?></span>
              </div>
              <form action="<?php echo base_url('User/addroomtype')?>" method="post">
              <div class="card-body">
                <div class="row">
                  <div class="from-group col-md-5">
                    <label for="depart">Name</label>
                    <input type="text" class="form-control" name="roomtype" placeholder="Enter a name" id="roomtype" required>
                  </div>
<div class="from-group col-md-5">
                   
                    <input type="hidden" class="form-control" name="bid" value="<?php echo $bid=$this->web->session->userdata('login_id'); ?> ">
                  </div>
                  
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
                <h3 class="card-title">Room Type List</h3>
              </div>
              <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>S. No</th>
                    <th>Name</th>
                    
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                      <?php
					  $bid=$this->web->session->userdata('login_id');
                      $res2=$this->web->getallrooms($bid);
                      $count=1;
                      foreach($res2 as $res2){
                      ?>
                      <tr>
                        <td><?php echo $count++; ?></td>
                        <td><?php echo $res2->name; ?></td>
                        
                        <td>
                          <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal2" onclick="mclick2('<?php echo $res2->id; ?>')">
                            <i class="fas fa-edit"></i>
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
        <h4 class="modal-title" id="myModalLabel">Edit Block</h4>
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



<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">Edit Room Type</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <div id="modform2">
          
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
      "paging": false,
      order: [[1, 'asc']],
    });
   
  });
</script>
<script>
function active(id){
    $.ajax({
      type: "POST",
      url: "User/activateEmployee",
      data: {id},
    success: function(id1){
      $('#activate'+id1).html('<button class="btn btn-success" onclick="inactive(' + id1 + ')">Active</button>');
    }
    })
  }

  function inactive(id){
    $.ajax({
      type: "POST",
      url: "User/inactivateEmployee",
      data: {id},
    success: function(id1){
      $('#activate'+id1).html('<button class="btn btn-danger" onclick="active('+ id1 + ')">Inactive</button>');
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
</body>
</html>
