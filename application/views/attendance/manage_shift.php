
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
              <li class="breadcrumb-item active">Manage Shift</li>
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
              
               <?php
                    $cudate = date("Y-m-d");
                   // $cdate=strtotime($cudate);
                   // $start_time= strtotime(date("Y-m-d 00:00:00",$cdate));
					//$end_time= strtotime(date("Y-m-d 00:00:00",$cdate))+604800;
					?>
              
              <table id="example2" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>S.No</th>
                    <th>Emp Code</th>
                    <th>Name</th>
                     <?php
                    for($d=0; $d<7;$d++){
						$new_start_time=strtotime(date("d-m-Y 00:00:00",strtotime($cudate))." +".$d." days");
						$dayweek= date("d D",$new_start_time);
                                  echo "<th>$dayweek </th>";
                                } ?>
                    
                    <th>History</th>
                    <th>Add</th>
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
                     // $userRequest = $this->web->getUserRequest($bid,$val->user_id);
                      ?>
                      <tr>
                        <td><?php echo $count; ?></td>
                         
                        <td>
                          <?php $uname=$this->web->getNameByUserId($val->user_id);
                          ?>
                         <?php echo $uname[0]->emp_code;?>
                         
                        </td>
                        <td><?php $uname = $this->web->getNameByUserId($val->user_id);echo $uname[0]->name; ?></td>
                        
                        <?php
                       
						
                    for($d=0; $d<7;$d++){
						$new_start_time=strtotime(date("d-m-Y 00:00:00",strtotime($cudate))." +".$d." days");
						$shiftlist=$this->web->getAssignedShiftList($val->user_id,$new_start_time);
						
							
						//$gpl = $this->web->getBusinessGroupByUserId($shiftlist);
                        // $gplisted=$shiftl;
						 ?>
						<td> 
                        <?php
                        if(!empty($shiftlist[0])){
						$shiftl=explode(",", $shiftlist[0]->shift);
							foreach($shiftl as $shiftl){
								$gpl = $this->web->getBusinessGroupByUserId($shiftl);
						  echo $gpl[0]->name."<br>".$gpl[0]->shift_start."<br>".$gpl[0]->shift_end; 
							} 
                            
                        }else {
						     $group = $this->web->getBusinessGroupByUserId($uname[0]->business_group);
							  echo $group[0]->name."<br>".$group[0]->shift_start."<br>".$group[0]->shift_end;  
								
							}
                        
                        
                        
						 }
                                  //echo "<td>$gplisted <br></td>";
						?>
                        </td>
                        
                        
                        
                        <td>
                          <button type="button" class="btn btn-info btn-sm mt-4 mx-auto" data-toggle="modal" data-target="#historyModal<?php echo $val->user_id;?>">History</button>
                        </td>
                        
                        <td>
                        <button type="button" class="btn btn-danger btn-sm mt-4 mx-auto" data-toggle="modal" data-target="#addModal<?php echo $val->user_id;?>">Add </button>
                        </td>
                        
                       
                      </tr>
                    <?php
                      $count++;
                    }?>
                    
                  
                </tbody>
              </table>
              
               <?php
                        foreach($res as $user){?>
                          <!-- Modal -->
                          <div class="modal fade" id="addModal<?php echo $user->user_id;?>" tabindex="-1" role="dialog" aria-labelledby="historyModal<?php echo $user->user_id;?>" aria-hidden="true">
                            <div class="modal-dialog modal-md" role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title" id="historyModalLabel<?php echo $user->user_id;?>">Add Shift</h5>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                                <div class="modal-body">
                                  
                                  
                                  <form action="<?php echo base_url('User/add_shift_detail')?>" method="post">
              <div class="card-body">
                <div class="row">
                
                
            
                
                
                  <div class="from-group col-md-5">
                    <label for="depart">From Date</label>
                  <!--  <input type="date" class="form-control" name="from_date" placeholder="Select Date" id="from_date" required>-->
                     <input type="date"  name="from_date" placeholder="Select Date" id="start_date" value="<?php echo $start_date; ?>" class="form-control" max="<?php echo $end_date; ?>" onchange="startChange(event);" required>
                  </div>

                  <div class="from-group col-md-5">
                    <label for="pfix"> To Date</label>
                  <!--  <input type="date" class="form-control" name="to_date" id="to_date" placeholder="Select Date" required>-->
                    <input type="date" name="to_date" id="end_date" placeholder="Select Date"   value="<?php echo $end_date; ?>"class="form-control"  min="<?php echo $start_date;?>" onchange="endChange(event);" required>
                  </div>
                  
                  
                <div class="col-md-5 form-group">
            <label for="department">Shift</label>
           <select name="shift[]" class="select2" multiple="multiple"  style="width: 100%;" required>
         <!-- <select name="department" class="form-control">-->
            <!--  <option value=''>All Department</option>-->
            <?php
            $group = $this->web->getBusinessGroupByBusinessId($bid);
            
              if(!empty($group)){
                            foreach($group as $group):
                              echo "<option value=".$group->id .">".$group->name."</option>";
                            endforeach;
                          }
			
            ?></select>
          </div>  
                  
                    
                
                 <div class="from-group col-md-5"> 
                   
                  
                   
                  <label for="remark"> Shift Roasting</label>
                    <select name="type" class="form-control"  id="type">
                                    <option value="0">None </option>
                                    <option value="1">Daily </option>
                                    <option value="2">Weekly </option>
                                    <option value="3">Monthly</option>
                                  
                                </select>
                                </div>
                                  
                    <input type="hidden" name="uid" value="<?php echo $user->user_id;?>">
                     <input type="hidden" name="bid" value="<?php echo $bid; ?>">
                    
                 

                  <div class="from-group col-md-5">
                  <button class=" btn btn-success mt-4 mx-auto">Add Now</button>
                  </div>
                </div>
              </div>
              </form> 
                                  
                            
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                              </div>
                            </div>
                          </div>
                          <!-- Modal -->
                  <?php } ?> 
                 
                 <?php
                        foreach($res as $user){?>
                          <!-- Modal -->
                          <div class="modal fade" id="historyModal<?php echo $user->user_id ;?>" tabindex="-1" role="dialog" aria-labelledby="historyModal<?php echo $user->user_id;?>" aria-hidden="true">
                            <div class="modal-dialog modal-md" role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title" id="historyModalLabel<?php echo $user->user_id;?>">History</h5>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                                <div class="modal-body">
                                 <table class="table table-responsive">
                                    <thead>
                                      <tr>
                                        <th>SNo.</th>
                                        <th>From Date</th>
                                        <th>To Date</th>
                                        <th>Shift</th>
                                        <th>Rotation</th>
                                        <th>Delete</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <?php
                                      $count=1;
									  $rot = $this->web->getshiftrotation($user->user_id);
                                      foreach($rot as $rot){
                                        $shift=explode(",", $rot->shift);
									     $rotation=$rot->rotation;
                                        ?>
                                        <tr>
                                          <td><?= $count++;?></td>
                                          <td><?= date('d-m-Y',$rot->from_date);?></td>
                                          <td><?= date('d-m-Y',$rot->to_date);?></td>
                                          <td><?php foreach($shift as $shift){
											   $gp = $this->web->getBusinessGroupByUserId($shift);
											  echo $gp[0]->name."<br>";
											  }
										  
										  ?></td>
                                          <td><?php 
                                         if($rotation==0) {
                                         echo "None";
                                         }elseif($rotation==1) {
                                          echo "Daily";
                                         }elseif($rotation==2){
                                          echo "Weekly";
                                            }else{ 
                                         echo "Monthly";
                               
                                           }
                           ?></td>
                                          <td id="delete<?php echo $rot->id; ?>">
                                          <?php
                                          $id=$rot->id;
						                 $uid=$rot->uid; 
						                $fromdate=date("d-m-Y" , $rot->from_date); 
                                          ?>
                         <button class="btn btn-danger" onclick="delete_work('<?php echo $id ;?>' ,' <?php echo $uid ;?>',' <?php echo $fromdate ;?>')" >
                          <i class="fa fa-times" style="color:white"></i>
                          </button>
                        </td>
                                        </tr>
                                      <?php  }?>
                                    </tbody>
                                  </table>
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                              </div>
                            </div>
                          </div>
                          <!-- Modal -->
                  <?php } ?> 
                  
                  
                  
                  
                  
                  
                  
                  
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
    //Initialize Select2 Elements
    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })

  })
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
function startChange(e){
      //alert(e.target.value);
      $('#end_date').attr('min', e.target.value);
	   //$('#end_date').attr('max', e.target.value);
    }
    function endChange(e){
      //alert(e.target.value);
      $('#start_date').attr('max', e.target.value);
    }
function saveAll(){
  $("#saveAllForm").submit();
}
</script>
<script>
  function delete_work(id,uid,fromdate){
    $.ajax({
      type: "POST",
      url: "delete_shift_rost",
      data: {id,uid,fromdate},
     success: function(){
    $('#delete'+id).text("deleted");
    }
	
    })
  }
</script>
</body>
</html>
