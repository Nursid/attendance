
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
 <?php $this->load->view('menu/menu')?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
         
          <div class="col-sm-12">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active"Add >Counter</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <?php
   if($this->session->userdata()['type']=='A')
      { 
    ?>
      <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <div class="card card-success">
              <div class="card-header">
                <h3 class="card-title">Assign Counter</h3><?php echo '<br>'.$this->session->flashdata('msg'); ?>
              </div>
            <div class="card-body">
          <form action="<?php echo base_url('User/CounterAssign')?>" method="post">
            <div class="row">
              
              <div class="col-md-6">
                <div class="form-group">
                  <label>Business Users</label>
                  <select class="select2" id="busines" data-placeholder="Select a Business" style="width: 100%;" name="userid">
                    <option value="" disabled selected>Select</option>
                   <?php
                    if(!empty($users)){
                      foreach($users as $users):
                        echo "<option value=".$users->userid .">".$users->name."</option>";
                      endforeach;
                   }
                   ?>
                  
                    
                   
                  </select>
                </div>
               
              </div>
              <!-- /.col -->
              <div class="col-md-6">
                <div class="form-group">
                  <label>Departments</label>
        <select class="select2"  id="departs" data-placeholder="Select a Department" style="width: 100%;" name="department">


        </select>
                </div>
                <!-- /.form-group -->
                
              </div>
              <!-- /.col -->
              <div class="col-md-6">
                <div class="form-group">
                  <label>Number Of Counters</label>
                  <input type="number" name="ncounter" class="form-control" required>
                </div>
                <!-- /.form-group -->
                
              </div>
            
              <div class="col-md-6">
                <div class="form-group">
                    <button class="btn btn-success mt-1 mx-auto">Assign Now</button>
                </div>
                <!-- /.form-group -->
                
              </div>
              <!-- /.col -->
            </div>
            </form>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>
        <!-- /.row -->
        
        <div class="row">
          <div class="col-md-12">
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Assigned Counters</h3>
              </div>
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>S no.</th>
                      <th>Business</th>
                      <th>Department</th>
                      <th>Counter No.</th>
                      <th>User Name</th>
                      <th>Edit Name</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                        if(!empty($counters)){
                        $count = 1;  
                          foreach ($counters as $key => $value) {
                      ?>
                    <tr>
                      <td><?php echo $count++; ?></td>
                      <td><?php echo $bname = $this->web->getBusinessById($value->business_id)['name']; ?></td>
                      <td><?php echo $dname = $this->web->getDepartById($value->depart_id)['0']->department; ?></td>
                      <td><?php echo $value->counter_id; ?></td>
                      <td id="uname<?php echo $value->login;?>"><?php $uname = $this->web->checkGeneratedLogin($value->login);
                                echo $uname['username'] ?></td>
                      <td>
                        <button class="btn btn-warning" type="button" data-toggle="modal" data-target="#myModal" data-cname="<?php echo $uname['username'];?>" data-lid="<?php echo $value->login;?>" onclick="clearMsg()"><i class="fas fa-edit"></i></button>
                      </td>
                      <td id="stat<?php echo $value->login; ?>">
                          <?php
                              if ($uname['status'] == "1") {
                          ?>    
                            <button class="btn btn-success" onclick="inactive('<?php echo $value->login; ?>')">Active</button>
                          <?php
                              }else{
                          ?>
                            <button class="btn btn-danger" onclick="active('<?php echo $value->login; ?>')">Inactive</button>
                          <?php
                            }
                          ?></td>
                    </tr>
                  <?php }
                          } ?>
                  </tbody>
                  <tfoot>
                    
                  </tfoot>
                </table>
              </div>
              <!-- .card-body -->
            </div>
            <!-- .card -->
          </div>
          <!-- .col -->
        </div>
        <!-- .row -->
      </div><!-- /.container-fluid -->
    </section>

    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="myModalLabel">Edit Department</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
          <div id="modform">
            <div style="color:red; font-size: 1.5rem;" id="msg"></div>
      <form method="post" id="from" action="User/editcounter">

        <div class="from-group">
          <label for="name">Counter Name</label>
          <input type="text" name="cname" id="name" class="form-control" value="" required>
        </div>
        <br>
        <input type="hidden" name="lid" id="id" value="">
        <button type="button" class="btn btn-success" id="bt_form">Save Changes</button>

      </form>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <?php 
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
  $('#myModal').on('show.bs.modal', function(e) {
    var cname = $(e.relatedTarget).data('cname');
    var lid = $(e.relatedTarget).data('lid');
    $(e.currentTarget).find('input[name="cname"]').val(cname);
    $(e.currentTarget).find('input[name="lid"]').val(lid);
  });

  $('#bt_form').on('click', function(){
        var fdata = $("#from").serialize();
      $.ajax({
        type: "POST",
        url: "User/editCounter",
        data: fdata,
        datatype: "json",
      success: function(res){
        if(res != ''){
          var obj = JSON.parse(res);
          var cname = obj.name;
          var clid = obj.id;
          console.log(cname);
          console.log(clid);
      $('#msg').html('New Name Updated!');
      $('#uname'+clid).html(cname);
        }
      }
    });
  });

  $(function () {
   var table = $('#example1').DataTable({
     "responsive": true,
      "autoWidth": false,
    });
   
  });

  $(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })
  });
 
 $('#busines').on('change', function() {
  var id = this.value;
  var datatype = "businesslist";
  $.ajax({
    type: "post",
    url: "User/getajaxRequest",
    data: {id,datatype},
    success: function(data){
      $('#departs').html(data);
    }
  });
});
</script>
<script type="text/javascript">
    function clearMsg(){
      var clr = document.getElementById('msg').innerHTML = '';
    }
    function active(id){
    $.ajax({
      type: "POST",
      url: "User/activateUser",
      data: {id},
    success: function(id1){
      var table = $('#example1').DataTable();
      var cell = table.cell('#stat'+id1);
      cell.data( '<button class="btn btn-success" onclick="inactive(' + id1 + ')">Active</button>').draw();
    }
    })
  }

  function inactive(id){
    $.ajax({
      type: "POST",
      url: "User/inactivateUser",
      data: {id},
    success: function(id1){
      var table = $('#example1').DataTable();
      var cell = table.cell('#stat'+id1);
      cell.data( '<button class="btn btn-danger" onclick="active('+ id1 + ')">Inactive</button>').draw();
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
