
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
              <li class="breadcrumb-item active">Edit Employee</li>
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
                <h3 class="card-title">Edit Manager Roll</h3><br>
               <!-- <span style="color: red"><?php echo $this->session->flashdata('msg');?></span>-->
              </div>
            
          
              
              <?php   
			
	 $id = $this->input->post('id');
	 $bid = $this->input->post('bid');
      $value = $this->web->getDepartById($id);
	 	 	  
			  ?>
              
              
              <form action="<?php echo base_url('User/editroll')?>" method="post">
              <div class="card-body">
             <div class="row">
                            <div class="col-1">
                              <p class="text-sm-left">1)</p>
                            </div>
                            <div class="col-3">
                              <p class="text-sm-left"> View Employee  </p>
                            </div>
                     <?php $role=$this->web->checkEmpRoleCmp($id,$bid);
							   $rl=$role[0]->add_emp; 
							    ?>
                            <div class="col-2"> 
                              <input id="employee_list" name="employee_list" type="checkbox" value="1" data-toggle="toggle" data-onstyle="success" data-size="sm" <?php if($role[0]->employee_list=="1"){echo "checked";} ?>> 
                            
                            </div>
                            
                            
                            <div class="col-1">
                              <p class="text-sm-left">2)</p>
                            </div>
                            <div class="col-3">
                              <p class="text-sm-left">Add/Edit Employee</p>
                            </div>
                         
                            <div class="col-2" >
                              <input id="add_emp" name="add_emp" type="checkbox" data-toggle="toggle"  value="1" data-onstyle="success" data-size="sm" 
                              <?php if($role[0]->add_emp=="1"){echo "checked";} ?>>
                            </div>
                        </div>
                        
                       <div class="row">
                       
                       
                        <div class="col-1">
                              <p class="text-sm-left">3)</p>
                            </div>
                            <div class="col-3">
                              <p class="text-sm-left"> Assign </p>
                            </div>

                            <div class="col-2">
                              <input id="" name="assign" type="checkbox" value="1" data-toggle="toggle" data-onstyle="success" data-size="sm" <?php if($role[0]->assign=="1"){echo "checked";} ?>> 
                            
                            </div>
                            
                        <div class="col-1">
                              <p class="text-sm-left">4)</p>
                            </div>
                            <div class="col-3">
                              <p class="text-sm-left"> Employee Login </p>
                            </div>

                            <div class="col-2">
                              <input id="" name="employee_login" type="checkbox" value="1" data-toggle="toggle" data-onstyle="success" data-size="sm" <?php if($role[0]->employee_login=="1"){echo "checked";} ?>> 
                            
                            </div>
                            
                        </div>
                        
                        
                        <div class="row">
                       
                       
                        <div class="col-1">
                              <p class="text-sm-left">5)</p>
                            </div>
                            <div class="col-3">
                              <p class="text-sm-left"> Manager Role </p>
                            </div>

                            <div class="col-2">
                              <input id="" name="manager_role" type="checkbox" value="1" data-toggle="toggle" data-onstyle="success" data-size="sm" <?php if($role[0]->manager_role=="1"){echo "checked";} ?>> 
                            
                            </div>
                            
                        <div class="col-1">
                              <p class="text-sm-left">6)</p>
                            </div>
                            <div class="col-3">
                              <p class="text-sm-left"> Activity Log </p>
                            </div>

                            <div class="col-2">
                              <input id="" name="activity" type="checkbox" value="1" data-toggle="toggle" data-onstyle="success" data-size="sm" <?php if($role[0]->activity=="1"){echo "checked";} ?>> 
                            
                            </div>
                            
                        </div>
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        <div class="row">
                        
                        <div class="col-1">
                              <p class="text-sm-left">7)</p>
                            </div>
                            <div class="col-3">
                              <p class="text-sm-left">Aprove Attendance </p>
                            </div>
                         
                            
                            <div class="col-2">
                              <input id="" name="pending_att" type="checkbox" value="1" data-toggle="toggle" data-onstyle="success" data-size="sm" <?php if($role[0]->pending_att=="1"){echo "checked";} ?>> 
                            
                            </div>
                       
                        
                       
                            <div class="col-1">
                              <p class="text-sm-left">8)</p>
                            </div>
                            <div class="col-3">
                              <p class="text-sm-left">Manual Attendance </p>
                            </div>
                         
                            <div class="col-2">
                              <input id="" name="manual_att" type="checkbox" value="1" data-toggle="toggle" data-onstyle="success" data-size="sm" <?php if($role[0]->manual_att=="1"){echo "checked";} ?>> 
                            
                            </div>
                           </div>  
                        
                     
                           <div class="row">
                           
                           
                           <div class="col-1">
                              <p class="text-sm-left">9)</p>
                            </div>
                            <div class="col-3">
                              <p class="text-sm-left">Daily Report</p>
                            </div>
                         
                            <div class="col-2">
                              <input id="" name="daily_report" type="checkbox" value="1" data-toggle="toggle" data-onstyle="success" data-size="sm" <?php if($role[0]->daily_report=="1"){echo "checked";} ?>> 
                            
                            </div>
                            
                            <div class="col-1">
                              <p class="text-sm-left">10)</p>
                            </div>
                            <div class="col-3">
                              <p class="text-sm-left">Monthly Report</p>
                            </div>
                         
                            <div class="col-2">
                              <input id="" name="other_report" type="checkbox" value="1" data-toggle="toggle" data-onstyle="success" data-size="sm" <?php if($role[0]->other_report=="1"){echo "checked";} ?>> 
                            
                            </div>
                            </div>
                            
                            
                            <div class="row">
                           
                           
                           <div class="col-1">
                              <p class="text-sm-left">11)</p>
                            </div>
                            <div class="col-3">
                              <p class="text-sm-left">GPS Report</p>
                            </div>
                         
                            <div class="col-2">
                              <input id="" name="gps_report" type="checkbox" value="1" data-toggle="toggle" data-onstyle="success" data-size="sm" <?php if($role[0]->gps_report=="1"){echo "checked";} ?>> 
                            
                            </div>
                            
                            <div class="col-1">
                              <p class="text-sm-left">12)</p>
                            </div>
                            <div class="col-3">
                              <p class="text-sm-left">Log Report</p>
                            </div>
                         
                            <div class="col-2">
                              <input id="" name="log_report" type="checkbox" value="1" data-toggle="toggle" data-onstyle="success" data-size="sm" <?php if($role[0]->log_report=="1"){echo "checked";} ?>> 
                            
                            </div>
                            </div>
                            
                            
                            <div class="row">
                            <div class="col-1">
                              <p class="text-sm-left">13)</p>
                            </div>
                            <div class="col-3">
                              <p class="text-sm-left"> Manage Leave</p>
                            </div>
                            <div class="col-2" >
                              <input id="" name="leave_manage" type="checkbox" data-toggle="toggle"  value="1" data-onstyle="success" data-size="sm" 
                              <?php if($role[0]->leave_manage=="1"){echo "checked";} ?>>
                            </div>
                            
                            <div class="col-1">
                              <p class="text-sm-left">14)</p>
                            </div>
                            <div class="col-3">
                              <p class="text-sm-left"> Add/ Aprove Leave</p>
                            </div>
                         <div class="col-2">
                              <input id="" name="add_leave" type="checkbox" value="1" data-toggle="toggle" data-onstyle="success" data-size="sm" <?php if($role[0]->add_leave=="1"){echo "checked";} ?>> 
                            
                            </div>
                            </div>
                            
                            
                            <div class="row">
                            
                            <div class="col-1">
                              <p class="text-sm-left">15)</p>
                            </div>
                            <div class="col-3">
                              <p class="text-sm-left">Attendance Setting</p>
                            </div>
                            <div class="col-2" >
                              <input id="" name="att_setting" type="checkbox" data-toggle="toggle"  value="1" data-onstyle="success" data-size="sm" 
                              <?php if($role[0]->att_setting=="1"){echo "checked";} ?>>
                            </div>
                       
                        
                      
                            <div class="col-1">
                              <p class="text-sm-left">16)</p>
                            </div>
                            <div class="col-3">
                              <p class="text-sm-left">Attendance Option  </p>
                            </div>
                         
                            <div class="col-2">
                              <input id="" name="att_option" type="checkbox" value="1" data-toggle="toggle" data-onstyle="success" data-size="sm" <?php if($role[0]->att_option=="1"){echo "checked";} ?>> 
                            
                            </div>
                
                             </div>
                             
                              <div class="row" >
                           <div class="col-1">
                              <p class="text-sm-left">17)</p>
                            </div>
                            <div class="col-3" >
                              <p class="text-sm-left"> View Salary </p>
                            </div>
                         
                            <div class="col-2" >
                              <input id="" name="salary" type="checkbox" data-toggle="toggle"  value="1" data-onstyle="success" data-size="sm" 
                              <?php if($role[0]->salary=="1"){echo "checked";} ?>>
                            </div>
                            
                            <div class="col-1">
                             <p class="text-sm-left">18)</p>
                            </div>
                            <div class="col-3" >
                              <p class="text-sm-left"> Edit Salary </p>
                            </div>
                         
                            <div class="col-2" >
                              <input id="" name="add_salary" type="checkbox" data-toggle="toggle"  value="1" data-onstyle="success" data-size="sm" 
                              <?php if($role[0]->add_salary=="1"){echo "checked";} ?>>
                            </div> 
                            
                            
                        </div>
                        
                        
                        
                         <div class="row" >
                           <div class="col-1">
                              <p class="text-sm-left">19)</p>
                            </div>
                            <div class="col-3" >
                              <p class="text-sm-left"> View Earning/Deduction </p>
                            </div>
                         
                            <div class="col-2" >
                              <input id="" name="earn" type="checkbox" data-toggle="toggle"  value="1" data-onstyle="success" data-size="sm" 
                              <?php if($role[0]->earn=="1"){echo "checked";} ?>>
                            </div>
                            
                            <div class="col-1">
                             <p class="text-sm-left">20)</p>
                            </div>
                            <div class="col-3" >
                              <p class="text-sm-left"> Edit Earning/Deduction </p>
                            </div>
                         
                            <div class="col-2" >
                              <input id="" name="add_earn" type="checkbox" data-toggle="toggle"  value="1" data-onstyle="success" data-size="sm" 
                              <?php if($role[0]->add_earn=="1"){echo "checked";} ?>>
                            </div> 
                            
                            
                        </div>
                   
                        
          <div class="row">
          <div class="col-sm-6">
            <div class="form-group">
              <label for="empType">Type</label>
              <select class="form-control" id="empType" name="empType">
                <option value="1" <?php if($role[0]->type=='1'){ echo 'selected';}?>>Admin</option>
                <option value="2" <?php if($role[0]->type=='2'){ echo 'selected';}?>>Manager</option>
                <option value="3" <?php if($role[0]->type=='3'){ echo 'selected';}?>>HR</option>
                <option value="4" <?php if($role[0]->type=='4'){ echo 'selected';}?>>Accounts</option>
              </select>
            </div>
          </div>
          <div class="col-sm-6 form-group">
            <label for="department">Department</label>
           <select name="department[]" class="select2" multiple="multiple"  style="width: 100%;" required>
         <!-- <select name="department" class="form-control">-->
              <option value=''>All Department</option>
            <?php
            $department = $this->web->getBusinessDepByBusinessId($bid);
            if(!empty($department)){
				
              foreach($department as $dep):
                $depSelected2 = '';
				$seldep=explode(",", $role[0]->department);
				  foreach ($seldep as $seldep){ 
				 if($dep->id==$seldep){
				  
                  $depSelected2 = 'selected';
                }
				      
				  }
                echo "<option value=".$dep->id ." $depSelected2>".$dep->name."</option>";
              endforeach;
            }
            ?></select>
          </div>
          
          
          
          
          </div>
          <div class="row">
          
          <div class="col-sm-6 form-group">
            <label for="section">Section</label>
          <select name="section[]" class="select2" multiple="multiple"  style="width: 100%;"required>
              <option value=''>All Section</option>
            <?php
            $section = $this->web->getBusinessSecByBId($bid);
            if(!empty($section)){
              foreach($section as $sec):
                $secSelected2 = '';
				$selsec=explode(",", $role[0]->section);
				 foreach ($selsec as $selsec){
                if($sec->type==$selsec){
                  $secSelected2 = 'selected';
                }
				     
				 }
                echo "<option value=".$sec->type ." $secSelected2>".$sec->name."</option>";
              endforeach;
            }
            ?></select>
          </div>
          
          
          
         <div class="col-sm-6 form-group">
            <label for="emp">Employee</label>
          <select name="emp[]" class="select2" multiple="multiple"  style="width: 100%;"required>
              <option value=''>All Employee</option>
            <?php
            $res=$this->web->getActiveEmployeesList($bid);
            if(!empty($res)){
              foreach($res as $res):
                $empSelected2 = '';
				$uname = $this->web->getNameByUserId($res->user_id);
               $selemp=explode(",", $role[0]->team);
			  // $roleTeam = array_search($res->user_id,$selemp);
			   // if(!is_bool($roleTeam)){
				//	$empSelected = 'selected';
			//	}
				foreach ($selemp as $selemp){
               if($res->user_id == $selemp){
                 $empSelected2 = 'selected';
               }
				    
				}
			   
                echo "<option value=".$uname[0]->id ." $empSelected2>".$uname[0]->name."</option>";
              endforeach;
            }
			
            ?></select>
           
                      </div>
          </div>  
          
                      
         <div class="row">
          <div class="col-7">
          <input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
          <input type="hidden" name="bid" id="id" value="<?php echo $bid; ?>">
          </div>
         <div class="col-5"  align="right">
        
         <button  class=" btn btn-success mt-4 mx-auto" >Update Now</button>
            <a href="<?php echo base_url('manager_roll')?>" <button class=" btn btn-success mt-4 mx-auto" >Cancel</button> </a>
            
          </div>
           </div>
           </div>
       </form>


            </div>
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
  $(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })

  })
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
