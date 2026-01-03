
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
              <li class="breadcrumb-item active">Employee List</li>
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
//	$id=$this->web->session->userdata('login_id');
	
	
    ?>
    <!-- Main content -->
    <section class="content">
      <?php
      if($this->session->userdata()['type']=='B' || $role[0]->employee_login=="1" || $role[0]->type=="1"){?>
      
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-2">
            <div class="card card-info">
              
                <span style="color: red"><?php echo $this->session->flashdata('msg');?></span>
             
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
                <h3 class="card-title">Employee List
                </h3>
              </div>
              <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>S.No</th>
                    <th>EmpCode</th>
                    <th>Name</th>
                     <th>Mobile No.</th>
                     <th>Type</th>
                      <th>Login</th>
                    <th>Status</th>
                     <th>Edit</th>
                  </tr>
                  </thead>
                  <tbody>
               <?php
			           
				   // $left=strtotime(date("d-m-Y",time()));
				  $cudate = date("Y-m-d");
				 // $cudate= '2022-04-15';
				$cdate=strtotime($cudate);
				
				$start_time=time();
                     
         
			
			 $res=$this->web->getWorkingEmployeesList($id);
					  $count=1;
					  if($this->session->userdata()['type']=='P'){
              if($role[0]->type!=1){
                $departments = explode(",",$role[0]->department);
                $sections = explode(",",$role[0]->section);
                $team = explode(",",$role[0]->team);
                
                if(!empty($departments[0]) || !empty($sections[0]) || !empty($team[0])){
                  foreach ($res as $key => $dataVal) {
                    $uname = $this->web->getNameByUserId($dataVal->user_id);
                    $roleDp = array_search($uname[0]->department,$departments);
                    $roleSection = array_search($uname[0]->section,$sections);
                    $roleTeam = array_search($dataVal->user_id,$team);
                   
                    if(!is_bool($roleTeam) || !is_bool($roleSection) || !is_bool($roleDp)){
            
                    }else{
                      unset($res[$key]);
                    }
                  }
                }
              }
            }
			
			 foreach($res as $val){
			 $userid=$val->user_id;
		        $roll=$this->web->getRollbyid($userid,$id);
			$uid=$roll[0]->uid;
			$check = $this->web->checkGeneratedLogin($val->user_id);
			 if ($check==""){
                        $generate = '<button class="btn btn-success" onclick="actionPersonal('.$userid.' , '.$id.' )">Generate login</button>';
						 $status ="";
                      }else{
                          $generate = "Login generated";
                          $reset= '<button class="btn btn-danger" onclick="resetp('.$userid.' , '.$id.')">Reset Password</button>';
                     
                      if ($check['status'] == "1") {
                        $status = '<button class="btn btn-success" onclick="inactive('.$userid.')">Active</button>';
                      }else{
                        $status = '<button class="btn btn-danger" onclick="active('.$userid.')">Inactive</button>';
                      } }
                     
                      if($roll[0]->type==0){
                        $type = "Employee";
						 }elseif($roll[0]->type==1){
                        $type = "Admin";
                      }elseif($roll[0]->type==2){
                        $type = "Manager";
                      }elseif($roll[0]->type==3){
                        $type = "HR";
                      }elseif($roll[0]->type==4){
                        $type = "Accounts";
                      }else{
                        $type = "";
                      }
			
			
                     
						         
                      ?>
                      <tr>
                       <td><?php echo $count++; ?></td>
                        
                        <td><?php $uname = $this->web->getNameByUserId($val->user_id);
                                echo $uname[0]->emp_code; ?></td>
                        <td><?php $uname = $this->web->getNameByUserId($val->user_id);
                                echo $uname[0]->name;
								// echo $id;
								//echo $loginId;
								//echo $val->user_id;
								?></td>
                       
                           
                        <td><?php 
                               echo $uname[0]->mobile; 
							//   echo $roll[0]->type;
							   
							   ?>
                        </td>
                        
                        
                       
                        <?php
                         echo "<td>$type</td>";
                      echo "<td id='pt$val->user_id'>$generate <br> $reset </td>";
                      echo "<td id='stat$val->user_id'>$status</td>";     
                         ?>     
                     <td id="delete<?php echo $val->user_id; ?>">
                       <!-- <button type="button" class="btn btn-info btn-circle btn-x" data-toggle="modal" data-target="#myModal" onclick="mclick('<?php echo$val->user_id; ?>')">-->
        <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal" onclick="mclick('<?php echo $val->user_id;  ?>' )"><i class="fas fa-edit"></i></button>
                            
                          
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
function resetp(id,bid){
    $.ajax({
      type: "POST",
      url: "User/resetpassword",
      data: {id,bid},
    success: function(){
      $('#pt'+id).text("Password Reset");
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

<script>
function mclick(data){
  var edit_emproll = "edit_emproll";
  $.ajax({
      type: "POST",
      url: "User/getajaxRequest",
      data: {data,edit_emproll},
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
</body>
</html>
