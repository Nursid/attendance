
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
   if($this->session->userdata()['type']=='B' || $this->session->userdata()['type']=='P')
      {
        if($this->session->userdata()['type']=='P'){
          $bid = $this->session->userdata('empCompany');
          $role=$this->web->getRollbyid($this->web->session->userdata('login_id'),$bid);
      
        } else {
          $bid=$this->web->session->userdata('login_id');
        }
    ?>
    <!-- Main content -->
    <section class="content">
    <?php
      if($this->session->userdata()['type']=='B' || $role[0]->assign=="1" || $role[0]->type=="1"){?>
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
                <h3 class="card-title">Assign to Employee
                 </h3>
              </div>
              <div class="card-body">
              <div align="right">
                <button type="button" class="btn btn-primary mb-4" onclick="saveAll()">Save All</button>
              </div>
              <form id="saveAllForm" action="<?php echo base_url('User/assignAllEmp')?>" method="post">
              <table id="example2" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>S.No</th>
                    <th>Name</th>
                    <th>Emp Code</th>
                    <th>Dev.ID</th>
                    <th>Shift</th>
                    <th>Department</th>
                    <th>Section</th>
                    <th>Rule</th>
                    <th>Save</th>
                  </tr>
                  </thead>
                  <tbody>
                  
                    <?php
                    $cudate = date("Y-m-d");
                    $cdate=strtotime($cudate);
                    $start_time= strtotime(date("Y-m-d 00:00:00",$cdate));
                    $res=$this->web->getWorkingEmployeesList($bid);
                    //$bid=$this->web->session->userdata('login_id');

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

                    $count=1;
                    foreach($res as $val){
                      $userid=$val->user_id;
                      $userRequest = $this->web->getUserRequest($bid,$val->user_id);
                      ?>
                      <tr>
                        <td><?php echo $count; ?></td>
                        <td><?php $uname = $this->web->getNameByUserId($val->user_id);echo $uname[0]->name; ?></td>
                        <input type="hidden" name="group_<?= $count?>" value="<?php echo $uname[0]->business_group ?>">
                        <input type="hidden" name="user_changed_<?= $count?>" value="0">
                        <input type="hidden" name="user_id_<?= $count?>" value="<?= $val->user_id?>">
                        <input type="hidden" name="department_<?= $count?>" value="<?php echo $uname[0]->department ?>">
                        <input type="hidden" name="section_<?= $count?>" value="<?php echo $uname[0]->section ?>">
                        <input type="hidden" name="rule_<?= $count?>" value="<?php echo $userRequest['rule_id'] ?>">
                        <input type="hidden" name="user_max" value="<?= count($res) ?>">
                        <form action="<?php echo base_url('User/assign_emp')?>" method="post">
                        <td>
                          <?php $uname = $this->web->getNameByUserId($val->user_id);
                          ?>
                         <?php echo $uname[0]->emp_code;?>
                         
                        </td>
                        <td>
                         <?php echo $uname[0]->bio_id;?>
                          
                        </td>
                        <td>
                          <select name="group" class="form-control" id="group" onchange="setAssign('group_','<?= $count?>',this)">
                          <?php
                          $gp = $this->web->getBusinessGroupByUserId($uname[0]->business_group);?>
                          <option value="<?php echo $uname[0]->business_group  ?>"><?php echo $gp[0]->name;  ?></option>
                          <?php
                          $group = $this->web->getBusinessGroupByBusinessId($bid);
                          if(!empty($group)){
                            foreach($group as $group):
                              echo "<option value=".$group->id .">".$group->name."</option>";
                            endforeach;
                          }
                          ?></select>
                        </td>
                        <td>
                          <select name="department" class="form-control" id="department" onchange="setAssign('department_','<?= $count?>',this)">
                          <?php
                          $dp = $this->web->getBusinessDepByUserId($uname[0]->department);?>
                          <option value="<?php echo $uname[0]->department  ?>"><?php echo $dp[0]->name;?></option>
                          
                          <?php
                          $department = $this->web->getBusinessDepByBusinessId($bid);
                          if(!empty($department)){
                            foreach($department as $dep):
                              echo "<option value=".$dep->id .">".$dep->name."</option>";
                            endforeach;
                          }
                          ?></select>
                        </td>
                        <td>
                          <select name="section" class="form-control"  id="section" onchange="setAssign('section_','<?= $count?>',this)">
                          <?php 
                            $section = $this->web->getBusinessSecByUserId($bid,$uname[0]->section);?>
                            <option value="<?php echo $uname[0]->section  ?>"><?php echo $section[0]->name;?></option>
                            
                            <?php
                            $section = $this->web->getBusinessSecByBId($bid);
                            if(!empty($section)){
                              foreach($section as $sec):
                                echo "<option value=".$sec->type .">".$sec->name."</option>";
                              endforeach;
                          }?>
                          </select>
                        </td>
                        <td>
                          <select name="rule" class="form-control"  id="rule" onchange="setAssign('rule_','<?= $count?>',this)">
                          <?php 
                            $rules = $this->web->getRule($bid,$userRequest['rule_id']);?>
                            <option value="<?php echo $userRequest['rule_id']  ?>"><?php echo $rules['name'];?></option>
                            
                            <?php
                            $rules = $this->web->getBusinessRules($bid);
                            if(!empty($rules)){
                              foreach($rules as $ru):
                                echo "<option value=".$ru->rule_id .">".$ru->name."</option>";
                              endforeach;
                          }?>
                          </select>
                        </td>
                        <td>
                          <input type="hidden"  name="id" value="<?php echo $userid; ?>">
                          <button type="submit" class="btn btn-success btn-fill btn-block">Save</button>
                        </td>
                        </form>
                      </tr>
                    <?php
                      $count++;
                    }?>
                    
                  
                </tbody>
              </table>
              </form>
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
<script>
  $(function () {
   var table = $('#example2').DataTable({
     "responsive": true,
      "autoWidth": false,
      "paging": false
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

function setAssign(name,count,val){
    $("input[name='user_changed_"+count+"']").val(1);
    $("input[name='"+name+count+"']").val(val.value);
}

function saveAll(){
  $("#saveAllForm").submit();
}
</script>
</body>
</html>
