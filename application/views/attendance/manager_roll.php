
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
 <?php $this->load->view('menu/menu')?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    
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
    
    
   
    
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
         
          <div class="col-sm-12">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Activate Login</li>
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
      if($this->session->userdata()['type']=='B' || $role[0]->manager_role=="1" || $role[0]->type=="1"){?>
      
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-2">
            <div class="card card-info">
           <!--   <div class="card-header">
              <a href="<?php echo base_url('addemployee')?>" >
                  
                  Add Employee
                </a>
               
                <span style="color: red"><?php echo $this->session->flashdata('msg');?></span>
              </div>-->
              
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
                <h3 class="card-title">Employee List</h3>
              </div>
              <div align="right" class="mt-4 mr-4">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addManagerModal">Add</button>
              </div>

              <!-- Modal -->
              <div class="modal fade" id="addManagerModal" tabindex="-1" role="dialog" aria-labelledby="addManagereModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="addManagerModalLabel">Add Manager</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <form id="addEmpRoleForm" action="<?php echo base_url('User/addEmpRole')?>" method="POST">
                      <div class="modal-body">
                        <div class="form-group">
                          <label for="empMobile">Employee Mobile Number</label>
                          <input type="text" class="form-control" id="empMobile" placeholder="Mobile" name="empMobile" maxlength="10" size="10" required>
                        </div>
                        <div class="form-group">
                        <label for="empType">Type</label>
                        <select class="form-control" id="empType" name="empType">
                          <option value="1">Admin</option>
                          <option value="2">Manager</option>
                          <option value="3">HR</option>
                          <option value="4">Accounts</option>
                          
                        </select>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>

              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>S.No</th>
                      <th>EmpCode</th>
                      <th>Name</th>
                      <th>Desig.</th>
                      <th>Mobile No.</th>
                      <th>Type</th>
                      <th>Status</th>
                      <th>Action</th>
                      <th>Roll</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php
                    $count = 1;
                    foreach($userRolls as $roll){
                      $check = $this->web->checkGeneratedLogin($roll->uid);
                      if ($check['status'] == "1") {
                        $status = '<button class="btn btn-success" onclick="inactive('.$roll->uid.')">Active</button>';
                      }else{
                        $status = '<button class="btn btn-danger" onclick="active('.$roll->uid.')">Inactive</button>';
                      }
                      if ($check==""){
                        $generate = '<button class="btn btn-success" onclick="actionPersonal('.$roll->uid.' , '.$loginId.' )">Generate login</button>';
                      }else{
                          $generate = "Login generated";
                      }
                      if($roll->type==1){
                        $type = "Admin";
                      }else if($roll->type==2){
                        $type = "Manager";
                      }else if($roll->type==3){
                        $type = "HR";
                      }else if($roll->type==4){
                        $type = "Accounts";
                      }else{
                        $type = "";
                      }
                      echo "<tr>";
                      echo "<td>$count</td>";
                      echo "<td>$roll->emp_code</td>";
                      echo "<td>$roll->name</td>";
                      echo "<td>$roll->designation</td>";
                      echo "<td>$roll->mobile</td>";
                      echo "<td>$type</td>";
                      echo "<td id='pt$roll->uid'>$generate</td>";
                      echo "<td id='stat$roll->uid'>$status</td>";
                      //echo '<td><button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal" onclick="mclick('.$roll->uid.','.$loginId.')"><i class="fas fa-edit"></i></button></td>'; 
                      ?>
                      <td> <form action="<?php echo base_url('User/edit_managerroll')?>" method="post">
                          <input type="hidden" value="<?php echo $roll->uid; ?>" name="id">
                          <input type="hidden" value="<?php echo $loginId; ?>" name="bid">  
                          <input type="submit" value="Edit" class="btn btn-primary ">
                         
                          </form> </td>
                      
                      
                      <?php
                      echo "</tr>";
                      $count++;
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
      <?php
      }else{?>
        <div class="container-fluid">
          <div class="col-sm-4 mx-auto">
            <h4>Not Authorized to Access This Page</h4>
          </div>
        </div>
      <?php 
      }?>   
      
     
      
    </section> 
  <?php 
  }?>
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
        <h4 class="modal-title" id="myModalLabel">Edit Authority</h4>
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
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script>

  $("#addEmpRoleForm").submit(function(e) {
    e.preventDefault();
    var formData = new FormData(this);
    $.ajax({
      type: 'POST',
      url: $(this).attr('action'),
      data: formData,
      cache: false,
      contentType: false,
      processData: false,
      beforeSend: function() {
        $('button').prop('disabled', true);
      },
      success: function(result) {
        var result = JSON.parse(result);
        console.log(result);
        if (result.status > 0) {
          swal("Success ", result.message, "success");
          setTimeout(function() {
            $('button').prop('disabled', false);
            window.location.reload();
          }, 2500);

        } else {
          swal("Faild ", result.message, "error");
          setTimeout(function() {
            $('button').prop('disabled', false);
          }, 2500);
        }

      }
    });
  });
</script>

<script>
  $(function () {
   var table = $('#example1').DataTable({
     "responsive": true,
      "autoWidth": false,
    });
   
  });
</script>
<script>
function mclick(id,bid){
  var add_eroll = "add_roll";
  $.ajax({
      type: "POST",
      url: "User/getajaxRequest",
      data: {id,bid,add_eroll},
    success: function(response){
      $('#modform').html(response);
    }
    })
}
</script>

<script type="text/javascript">
 
  function actionPersonal(id,bid){
    $.ajax({
    type: "POST",
    url: "User/GenPersonalLogin_new",
    data: {id,bid},
  success: function(){
    $('#pt'+id).text("Login generated");
  }
});
  }

  function active(id){
    $.ajax({
      type: "POST",
      url: "User/activateUser",
      data: {id},
    success: function(id1){
      $('#stat'+id1).html('<button class="btn btn-success" onclick="inactive(' + id1 + ')">Active</button>');
    }
    })
  }

  function inactive(id){
    $.ajax({
      type: "POST",
      url: "User/inactivateUser",
      data: {id},
    success: function(id1){
      $('#stat'+id1).html('<button class="btn btn-danger" onclick="active('+ id1 + ')">Inactive</button>');
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
