
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
              <li class="breadcrumb-item active">Device Access</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
<?php
   if($this->session->userdata()['type']=='B' || $this->session->userdata()['type']=='P' ){ 

    $buid=$this->web->session->userdata('login_id'); 
	  
    ?>
    <!-- Main content -->
    <section class="content">
        <?php
      if($this->session->userdata()['type']=='B' || $role[0]->att_setting=="1" || $role[0]->type=="1"){?>
      
      <div class="container-fluid">
        

        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Device Access</h3>
                <span style="color: red"><?php echo $this->session->flashdata('msg');?></span>
              </div>
              <div class="card-body">
              <table id="example2" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    
                     <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                      <?php
					   
			    
                      $dev=$this->web->getdevice($buid);
                      $count=1;
                     // foreach($dev as $plan){
                      ?>
                      <tr>
                       
                       
                       <td>
                <button type="button" class="btn btn-primary btn-sm mt-4 mx-auto" data-toggle="modal" data-target="#Modal-1">Transfer User Name </button>
                       
             <button type="button" class="btn btn-primary btn-sm mt-4 mx-auto" data-toggle="modal" data-target="#Modal-2">Transfer User Name & Card </button>
                        
                        
            <button type="button" class="btn btn-primary btn-sm mt-4 mx-auto" data-toggle="modal" data-target="#Modal-3">Get User Detail </button>
                        
           <button type="button" class="btn btn-primary btn-sm mt-4 mx-auto" data-toggle="modal" data-target="#Modal-4">Get Punch Log </button>
                       
           <button type="button" class="btn btn-primary btn-sm mt-4 mx-auto" data-toggle="modal" data-target="#Modal-5">Sync Punch Log  </button> 
                       
           <button type="button" class="btn btn-primary btn-sm mt-4 mx-auto" data-toggle="modal" data-target="#Modal-6">Set Timing </button>
           
           <button type="button" class="btn btn-primary btn-sm mt-4 mx-auto" data-toggle="modal" data-target="#Modal-7">Device Status </button>
                         
           <button type="button" class="btn btn-primary btn-sm mt-4 mx-auto" data-toggle="modal" data-target="#Modal-8">Delete User </button>
                         
           <button type="button" class="btn btn-primary btn-sm mt-4 mx-auto" data-toggle="modal" data-target="#Modal-9">Clear All User </button>
                         
            <button type="button" class="btn btn-primary btn-sm mt-4 mx-auto" data-toggle="modal" data-target="#Modal-10">Clear Admin </button>
                          
            <button type="button" class="btn btn-primary btn-sm mt-4 mx-auto" data-toggle="modal" data-target="#Modal-11">Clear All Log </button>
            
           <button type="button" class="btn btn-primary btn-sm mt-4 mx-auto" data-toggle="modal" data-target="#Modal-12">Enable User </button>
           
            <button type="button" class="btn btn-primary btn-sm mt-4 mx-auto" data-toggle="modal" data-target="#Modal-13">Disable User </button>
                        
                      </td>
                        
                      </tr>
                      <?php 
                    //  }
                      ?>
                  </tfoot>
                </table>
                
                
                  
                          <!-- Modal -->
                          <div class="modal fade" id="Modal-1" tabindex="-1" role="dialog" aria-labelledby="historyModal" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title" id="historyModalLabel">Transfer Users Name </h5>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                                
                                <div class="modal-body">
                                    
                               <span style="color: red"><?php echo $this->session->flashdata('msg');?></span>
                               
                               
                                <div class="col-sm-2">
                              <select name="class_id" class="form-control"  id="emp" required>
                                <option value="">Select Class</option>
                              <?php 
                            $res2 = $this->web->getallclassbyid($buid);
                                    foreach ($res2 as $res2) :
                             // $uname = $this->web->getNameByUserId($res->user_id); ?>
                                <option value="<?php echo $res2->id  ?>" <?php if($res2->id==$id){ echo "selected";}?>><?php echo $res2->name; ?></option>
                              <?php endforeach; ?>
                              </select>
                            </div>
                         
                            <div class="col-sm-2">
                              <button type="submit" class="btn btn-success btn-fill btn-block">Show</button>
                            </div>
                              <?php  
                              // if($res2->$id!=0) {
                               
                               ?>
                               
                                <form action="<?php echo base_url('Api_v17/addbiometricusername')?>" method="post">
                                 <table id="example2" class="table table-bordered table-responsive">
                                    <thead>
                                     <tr>
                                     <th colspan="5"> 
                                      <div class="row">
                  <div class="from-group col-md-3">
                      <br>
                    <label for="name">Select Device</label>
                                      
                             </div>     
                                    
                                    <div class="from-group col-md-5">
                                        <br>   
                                       <select name="device" class="form-control"  style="width: 100%;" required>
            <?php
             $res=$this->web->getdevice($buid);
            
              if(!empty($res)){
                            foreach($res as $res):
                              echo "<option value=".$res->deviceid .">".$res->name."</option>";
                            endforeach;
                          }
			
            ?></select> 
            </div>
         
         <div class="from-group col-md-3">
          <button class=" btn btn-info mt-4 mx-auto">Add</button> 
          </div>
          </div>
          
          </th>
                                         </tr>
                                         
                                    
                                      <tr>
                                      <!-- <th width="10%">Tick</th>-->
                                        <th>Select</th>
                                         <th width="10%">Student Id</th>
                                        <th width="10%">Bio Id</th>
                                      <!-- <th>width="10%">Rfid</th>-->
                                        <th >Name</th>
                                        <th>Mobile</th>
                                     
                                      
                                      </tr>
                                    </thead>
                                    <tbody>
                                    
                                      <?php
                                      $count=1;
                                      $stu=$this->web->getHostelStudentList($buid);
                                      foreach($stu as $value){
                                       
                                        ?>
                                       
                                         <tr>
                                          <td><input type="checkbox" name="stid[]" value="<?php echo $value->user_id; ?>"></td> 
                                         
                                          <td>
                                          <?php $uname = $this->web->getNameByUserId($value->user_id);
                                          echo $uname[0]->emp_code;
                                          ?>
                                          </td>
                                          <td><?= $uname[0]->bio_id;?></td>
                                        <!--  <td><?= $uname[0]->bio_id;?></td>-->
                                          <td><?= $uname[0]->name;?></td>
                                          <td><?= $uname[0]->mobile;?></td>
                                         
                                        </tr>
                                      <?php  }?>
                                    </tbody>
                                  </table>
                                   </form>
                                   <?php //} 
                                   ?>
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                              </div>
                            </div>
                          </div>
                          <!-- Modal -->
                          
                          
                          
                          
                          
                          
                            <!-- Modal -->
                          <div class="modal fade" id="Modal-2" tabindex="-1" role="dialog" aria-labelledby="historyModal" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title" id="historyModalLabel">Transfer Users Name & Card  </h5>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                                <div class="modal-body">
                                <span style="color: red"><?php echo $this->session->flashdata('msg');?></span>
                                <form action="<?php echo base_url('Api_v17/addbiometricusercard')?>" method="post">
                                 <table id="example2" class="table table-bordered table-responsive">
                                    <thead>
                                     <tr>
                                     <th colspan="6"> 
                                      <div class="row">
                  <div class="from-group col-md-3">
                      <br>
                    <label for="name">Select Device</label>
                                      
                             </div>     
                                    
                                    <div class="from-group col-md-5">  
                                    <br>
                                       <select name="device" class="form-control"  style="width: 100%;" required>
            <?php
             $res=$this->web->getdevice($buid);
            
              if(!empty($res)){
                            foreach($res as $res):
                              echo "<option value=".$res->deviceid .">".$res->name."</option>";
                            endforeach;
                          }
			
            ?></select> 
            </div>
         
         <div class="from-group col-md-3">
          <button class=" btn btn-info mt-4 mx-auto">Add</button> 
          </div>
          </div>
          
          </th>
                                         </tr>
                                         
                                    
                                      <tr>
                                      <!-- <th width="10%">Tick</th>-->
                                        <th>Select</th>
                                         <th width="10%">Student Id</th>
                                        <th width="10%">Bio Id</th>
                                       <th width="10%">Rfid</th>
                                        <th >Name</th>
                                        <th>Mobile</th>
                                     
                                      
                                      </tr>
                                    </thead>
                                    <tbody>
                                    
                                      <?php
                                      $count=1;
                                      $stu=$this->web->getHostelStudentList($buid);
                                      foreach($stu as $value){
                                       
                                        ?>
                                       
                                         <tr>
                                          <td><input type="checkbox" name="stid[]" value="<?php echo $value->user_id; ?>"></td> 
                                         
                                          <td>
                                          <?php $uname = $this->web->getNameByUserId($value->user_id);
                                          echo $uname[0]->emp_code;
                                          ?>
                                          </td>
                                          <td><?= $uname[0]->bio_id;?></td>
                                        <td><?= $uname[0]->rfid;?></td>
                                          <td><?= $uname[0]->name;?></td>
                                          <td><?= $uname[0]->mobile;?></td>
                                         
                                        </tr>
                                      <?php  }?>
                                    </tbody>
                                  </table>
                                   </form>
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                              </div>
                            </div>
                          </div>
                          <!-- Modal -->
                          
                          
                          
                          
                          
                           <!-- Modal -->
                          <div class="modal fade" id="Modal-3" tabindex="-1" role="dialog" aria-labelledby="historyModal" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title" id="historyModalLabel">Fetch Users Detail </h5>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                                <div class="modal-body">
                                <span style="color: red"><?php echo $this->session->flashdata('msg');?></span>
                                <form action="<?php echo base_url('Api_v17/getusercarddetail')?>" method="post">
                                 
                                      <div class="row">
                                          <div class="from-group col-md-1">
                      <br>
                    <label for="name"></label>
                                      
                             </div>     
                                    
                  <div class="from-group col-md-2">
                      <br>
                    <label for="name">Select Device</label>
                                      
                             </div>     
                                    
                                    <div class="from-group col-md-3">  
                                    <br>
                                       <select name="device" class="form-control"  style="width: 100%;" required>
            <?php
             $res=$this->web->getdevice($buid);
            
              if(!empty($res)){
                            foreach($res as $res):
                              echo "<option value=".$res->deviceid .">".$res->name."</option>";
                            endforeach;
                          }
			
            ?></select> 
            </div>
         
         <div class="from-group col-md-2">
          <button class=" btn btn-info mt-4 mx-auto">Check</button> 
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
                 
                 
                 
                 
                 
                           <!-- Modal -->
                          <div class="modal fade" id="Modal-4" tabindex="-1" role="dialog" aria-labelledby="historyModal" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title" id="historyModalLabel">Get Users Punch Log</h5>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                                <div class="modal-body">
                                <span style="color: red"><?php echo $this->session->flashdata('msg');?></span>
                                <form action="<?php echo base_url('Api_v17/getuserpunchdetail')?>" method="post">
                                 
                                      <div class="row">
                                          <div class="col-sm-3">
                                              <br>
                              <input type="date" name="start_date" id="start_date"  value="<?php echo date("Y-m-d"); ?>" class="form-control" max="<?php echo $end_date; ?>" onchange="startChange(event);">
                            </div>
                            <div class="col-sm-3">
                                <br>
                              <input type="date" name="end_date" id="end_date"  value="<?php echo date("Y-m-d"); ?>"class="form-control" max="<?php echo date('Y-m-d'); ?>" min="<?php echo $start_date;?>" onchange="endChange(event);">
                            </div>
                                    
                         <div class="from-group col-md-3">  
                                    <br>
                                       <select name="device" class="form-control"  style="width: 100%;" required>
                                           <option value=''>Select Device</option>
            <?php
             $res=$this->web->getdevice($buid);
            
              if(!empty($res)){
                            foreach($res as $res):
                              echo "<option value=".$res->deviceid .">".$res->name."</option>";
                            endforeach;
                          }
			
            ?></select> 
            </div>
         
         <div class="from-group col-md-2">
          <button class=" btn btn-info mt-4 mx-auto">Check</button> 
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
                 
                 
                 
                 
                
            <!-- Modal -->
                          <div class="modal fade" id="Modal-5" tabindex="-1" role="dialog" aria-labelledby="historyModal" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title" id="historyModalLabel">Transfer Users Punch Logs</h5>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                                <div class="modal-body">
                                <span style="color: red"><?php echo $this->session->flashdata('msg');?></span>
                                <form action="<?php echo base_url('Api_v17/transferStuBioAttendance')?>" method="post">
                                 
                                      <div class="row">
                                          <div class="col-sm-3">
                                              <br>
                              <input type="date" name="start_date" id="start_date"  value="<?php echo date("Y-m-d"); ?>" class="form-control" max="<?php echo $end_date; ?>" onchange="startChange(event);">
                            </div>
                            <div class="col-sm-3">
                                <br>
                              <input type="date" name="end_date" id="end_date"  value="<?php echo date("Y-m-d"); ?>"class="form-control" max="<?php echo date('Y-m-d'); ?>" min="<?php echo $start_date;?>" onchange="endChange(event);">
                            </div>
                                    
                         <div class="from-group col-md-3">  
                                    <br>
                                       <select name="device" class="form-control"  style="width: 100%;" required>
                                           <option value=''>Select Device</option>
            <?php
             $res=$this->web->getdevice($buid);
            
              if(!empty($res)){
                            foreach($res as $res):
                              echo "<option value=".$res->id .">".$res->name."</option>";
                            endforeach;
                          }
			
            ?></select> 
            </div>
         <input type="hidden" name="buid" value="<?php  echo $buid; ?>">
         <div class="from-group col-md-2">
          <button class=" btn btn-info mt-4 mx-auto">Check</button> 
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
                
                
                
                
                
                
                
                
                
                <!-- Modal -->
                          <div class="modal fade" id="Modal-6" tabindex="-1" role="dialog" aria-labelledby="historyModal" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title" id="historyModalLabel">Set Device Timing </h5>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                                <div class="modal-body">
                                <span style="color: red"><?php echo $this->session->flashdata('msg');?></span>
                                <form action="<?php echo base_url('Api_v17/setTiming')?>" method="post">
                                 
                                      <div class="row">
                                          <div class="from-group col-md-1">
                      <br>
                    <label for="name"></label>
                                      
                             </div>     
                                    
                  <div class="from-group col-md-2">
                      <br>
                    <label for="name">Select Device</label>
                                      
                             </div>     
                                    
                                    <div class="from-group col-md-3">  
                                    <br>
                                       <select name="device" class="form-control"  style="width: 100%;" required>
            <?php
             $res=$this->web->getdevice($buid);
            
              if(!empty($res)){
                            foreach($res as $res):
                              echo "<option value=".$res->deviceid .">".$res->name."</option>";
                            endforeach;
                          }
			
            ?></select> 
            </div>
         
         <div class="from-group col-md-2">
          <button class=" btn btn-info mt-4 mx-auto">Set Time</button> 
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
                          
                          
                          
                          
                          
                           
                <!-- Modal -->
                          <div class="modal fade" id="Modal-7" tabindex="-1" role="dialog" aria-labelledby="historyModal" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title" id="historyModalLabel">Check Device Status </h5>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                                <div class="modal-body">
                                <span style="color: red"><?php echo $this->session->flashdata('msg');?></span>
                                <form action="<?php echo base_url('Api_v17/getdevicestatus')?>" method="post">
                                 
                                      <div class="row">
                                          <div class="from-group col-md-1">
                      <br>
                    <label for="name"></label>
                                      
                             </div>     
                                    
                  <div class="from-group col-md-2">
                      <br>
                    <label for="name">Select Device</label>
                                      
                             </div>     
                                    
                                    <div class="from-group col-md-3">  
                                    <br>
                                       <select name="device" class="form-control"  style="width: 100%;" required>
            <?php
             $res=$this->web->getdevice($buid);
            
              if(!empty($res)){
                            foreach($res as $res):
                              echo "<option value=".$res->deviceid .">".$res->name."</option>";
                            endforeach;
                          }
			
            ?></select> 
            </div>
         
         <div class="from-group col-md-2">
          <button class=" btn btn-info mt-4 mx-auto">Check</button> 
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
                
                
                
                
                
                
                
             
                          <!-- Modal -->
                          <div class="modal fade" id="Modal-8" tabindex="-1" role="dialog" aria-labelledby="historyModal" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title" id="historyModalLabel">Delete Users From device </h5>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                                <div class="modal-body">
                                <span style="color: red"><?php echo $this->session->flashdata('msg');?></span>
                                <form action="<?php echo base_url('Api_v17/deletebiouser')?>" method="post">
                                 <table id="example2" class="table table-bordered table-responsive">
                                    <thead>
                                     <tr>
                                     <th colspan="5"> 
                                      <div class="row">
                                          
                  <div class="from-group col-md-3">
                      <br>
                    <label for="name">Select Device</label>
                                      
                             </div>     
                                    
                                    <div class="from-group col-md-5">  
                                    <br>
                                       <select name="device" class="form-control"  style="width: 100%;" required>
            <?php
             $res=$this->web->getdevice($buid);
            
              if(!empty($res)){
                            foreach($res as $res):
                              echo "<option value=".$res->deviceid .">".$res->name."</option>";
                            endforeach;
                          }
			
            ?></select> 
            </div>
         
         <div class="from-group col-md-3">
          <button class=" btn btn-info mt-4 mx-auto">Add</button> 
          </div>
          </div>
          
          </th>
                                         </tr>
                                         
                                    
                                      <tr>
                                      <!-- <th width="10%">Tick</th>-->
                                        <th>Select</th>
                                         <th width="10%">Student Id</th>
                                        <th width="10%">Bio Id</th>
                                      <!-- <th>width="10%">Rfid</th>-->
                                        <th >Name</th>
                                        <th>Mobile</th>
                                     
                                      
                                      </tr>
                                    </thead>
                                    <tbody>
                                    
                                      <?php
                                      $count=1;
                                      $stu=$this->web->getHostelStudentList($buid);
                                      foreach($stu as $value){
                                       
                                        ?>
                                       
                                         <tr>
                                          <td><input type="checkbox" name="stid[]" value="<?php echo $value->user_id; ?>"></td> 
                                         
                                          <td>
                                          <?php $uname = $this->web->getNameByUserId($value->user_id);
                                          echo $uname[0]->emp_code;
                                          ?>
                                          </td>
                                          <td><?= $uname[0]->bio_id;?></td>
                                        <!--  <td><?= $uname[0]->bio_id;?></td>-->
                                          <td><?= $uname[0]->name;?></td>
                                          <td><?= $uname[0]->mobile;?></td>
                                         
                                        </tr>
                                      <?php  }?>
                                    </tbody>
                                  </table>
                                   </form>
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                              </div>
                            </div>
                          </div>
                          <!-- Modal -->   
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
           <!-- Modal -->
                          <div class="modal fade" id="Modal-9" tabindex="-1" role="dialog" aria-labelledby="historyModal" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title" id="historyModalLabel">Clear All User Data </h5>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                                <div class="modal-body">
                                <span style="color: red"><?php echo $this->session->flashdata('msg');?></span>
                                <form action="<?php echo base_url('Api_v17/clearalluser')?>" method="post">
                                 
                                      <div class="row">
                                          <div class="from-group col-md-1">
                      <br>
                    <label for="name"></label>
                                      
                             </div>     
                                    
                  <div class="from-group col-md-2">
                      <br>
                    <label for="name">Select Device</label>
                                      
                             </div>     
                                    
                                    <div class="from-group col-md-3">  
                                    <br>
                                       <select name="device" class="form-control"  style="width: 100%;" required>
            <?php
             $res=$this->web->getdevice($buid);
            
              if(!empty($res)){
                            foreach($res as $res):
                              echo "<option value=".$res->deviceid .">".$res->name."</option>";
                            endforeach;
                          }
			
            ?></select> 
            </div>
         
         <div class="from-group col-md-2">
          <button class=" btn btn-info mt-4 mx-auto">Clear All User</button> 
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
                
                
                
                
                
                 <!-- Modal -->
                          <div class="modal fade" id="Modal-10" tabindex="-1" role="dialog" aria-labelledby="historyModal" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title" id="historyModalLabel">Clear Admin </h5>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                                <div class="modal-body">
                                <span style="color: red"><?php echo $this->session->flashdata('msg');?></span>
                                <form action="<?php echo base_url('Api_v17/clearBioAdmin')?>" method="post">
                                 
                                      <div class="row">
                                          <div class="from-group col-md-1">
                      <br>
                    <label for="name"></label>
                                      
                             </div>     
                                    
                  <div class="from-group col-md-2">
                      <br>
                    <label for="name">Select Device</label>
                                      
                             </div>     
                                    
                                    <div class="from-group col-md-3">  
                                    <br>
                                       <select name="device" class="form-control"  style="width: 100%;" required>
            <?php
             $res=$this->web->getdevice($buid);
            
              if(!empty($res)){
                            foreach($res as $res):
                              echo "<option value=".$res->deviceid .">".$res->name."</option>";
                            endforeach;
                          }
			
            ?></select> 
            </div>
         
         <div class="from-group col-md-2">
          <button class=" btn btn-info mt-4 mx-auto">Clear Admin</button> 
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
                
                
                
                
                
                
                
                 <!-- Modal -->
                          <div class="modal fade" id="Modal-11" tabindex="-1" role="dialog" aria-labelledby="historyModal" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title" id="historyModalLabel">Clear All Log </h5>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                                <div class="modal-body">
                                <span style="color: red"><?php echo $this->session->flashdata('msg');?></span>
                                <form action="<?php echo base_url('Api_v17/clearAllLog')?>" method="post">
                                 
                                      <div class="row">
                                          <div class="from-group col-md-1">
                      <br>
                    <label for="name"></label>
                                      
                             </div>     
                                    
                  <div class="from-group col-md-2">
                      <br>
                    <label for="name">Select Device</label>
                                      
                             </div>     
                                    
                                    <div class="from-group col-md-3">  
                                    <br>
                                       <select name="device" class="form-control"  style="width: 100%;" required>
            <?php
             $res=$this->web->getdevice($buid);
            
              if(!empty($res)){
                            foreach($res as $res):
                              echo "<option value=".$res->deviceid .">".$res->name."</option>";
                            endforeach;
                          }
			
            ?></select> 
            </div>
         
         <div class="from-group col-md-2">
          <button class=" btn btn-info mt-4 mx-auto">Clear Logs</button> 
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
                
                
                
                
                
                
                
                
                
                
                
              
              
              
              
              
                          <!-- Modal -->
                          <div class="modal fade" id="Modal-12" tabindex="-1" role="dialog" aria-labelledby="historyModal" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title" id="historyModalLabel">Enable Users On Device </h5>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                                <div class="modal-body">
                                <span style="color: red"><?php echo $this->session->flashdata('msg');?></span>
                                <form action="<?php echo base_url('Api_v17/enablebiometricuser')?>" method="post">
                                 <table id="example2" class="table table-bordered table-responsive">
                                    <thead>
                                     <tr>
                                     <th colspan="5"> 
                                      <div class="row">
                  <div class="from-group col-md-3">
                      <br>
                    <label for="name">Select Device</label>
                                      
                             </div>     
                                    
                                    <div class="from-group col-md-5"> 
                                    <br>
                                       <select name="device" class="form-control"  style="width: 100%;" required>
            <?php
             $res=$this->web->getdevice($buid);
            
              if(!empty($res)){
                            foreach($res as $res):
                              echo "<option value=".$res->deviceid .">".$res->name."</option>";
                            endforeach;
                          }
			
            ?></select> 
            </div>
         
         <div class="from-group col-md-3">
          <button class=" btn btn-info mt-4 mx-auto">Enable</button> 
          </div>
          </div>
          
          </th>
                                         </tr>
                                         
                                    
                                      <tr>
                                      <!-- <th width="10%">Tick</th>-->
                                        <th>Select</th>
                                         <th width="10%">Student Id</th>
                                        <th width="10%">Bio Id</th>
                                      <!-- <th>width="10%">Rfid</th>-->
                                        <th >Name</th>
                                        <th>Mobile</th>
                                     
                                      
                                      </tr>
                                    </thead>
                                    <tbody>
                                    
                                      <?php
                                      $count=1;
                                      $stu=$this->web->getHostelStudentList($buid);
                                      foreach($stu as $value){
                                       
                                        ?>
                                       
                                         <tr>
                                          <td><input type="checkbox" name="stid[]" value="<?php echo $value->user_id; ?>"></td> 
                                         
                                          <td>
                                          <?php $uname = $this->web->getNameByUserId($value->user_id);
                                          echo $uname[0]->emp_code;
                                          ?>
                                          </td>
                                          <td><?= $uname[0]->bio_id;?></td>
                                        <!--  <td><?= $uname[0]->bio_id;?></td>-->
                                          <td><?= $uname[0]->name;?></td>
                                          <td><?= $uname[0]->mobile;?></td>
                                         
                                        </tr>
                                      <?php  }?>
                                    </tbody>
                                  </table>
                                   </form>
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                              </div>
                            </div>
                          </div>
                          <!-- Modal -->  
                          
                          
                          
                          <!-- Modal -->
                          <div class="modal fade" id="Modal-13" tabindex="-1" role="dialog" aria-labelledby="historyModal" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title" id="historyModalLabel">Disable User On Device </h5>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                                <div class="modal-body">
                                <span style="color: red"><?php echo $this->session->flashdata('msg');?></span>
                                <form action="<?php echo base_url('Api_v17/disablebiometricuser')?>" method="post">
                                 <table id="example2" class="table table-bordered table-responsive">
                                    <thead>
                                     <tr>
                                     <th colspan="5"> 
                                      <div class="row">
                  <div class="from-group col-md-3">
                      <br>
                    <label for="name">Select Device</label>
                                      
                             </div>     
                                    
                                    <div class="from-group col-md-5">   
                                    <br>
                                       <select name="device" class="form-control"  style="width: 100%;" required>
            <?php
             $res=$this->web->getdevice($buid);
            
              if(!empty($res)){
                            foreach($res as $res):
                              echo "<option value=".$res->deviceid .">".$res->name."</option>";
                            endforeach;
                          }
			
            ?></select> 
            </div>
         
         <div class="from-group col-md-3">
          <button class=" btn btn-info mt-4 mx-auto">Disable Now</button> 
          </div>
          </div>
          
          </th>
                                         </tr>
                                         
                                    
                                      <tr>
                                      <!-- <th width="10%">Tick</th>-->
                                        <th>Select</th>
                                         <th width="10%">Student Id</th>
                                        <th width="10%">Bio Id</th>
                                      <!-- <th>width="10%">Rfid</th>-->
                                        <th >Name</th>
                                        <th>Mobile</th>
                                     
                                      
                                      </tr>
                                    </thead>
                                    <tbody>
                                    
                                      <?php
                                      $count=1;
                                      $stu=$this->web->getHostelStudentList($buid);
                                      foreach($stu as $value){
                                       
                                        ?>
                                       
                                         <tr>
                                          <td><input type="checkbox" name="stid[]" value="<?php echo $value->user_id; ?>"></td> 
                                         
                                          <td>
                                          <?php $uname = $this->web->getNameByUserId($value->user_id);
                                          echo $uname[0]->emp_code;
                                          ?>
                                          </td>
                                          <td><?= $uname[0]->bio_id;?></td>
                                        <!--  <td><?= $uname[0]->bio_id;?></td>-->
                                          <td><?= $uname[0]->name;?></td>
                                          <td><?= $uname[0]->mobile;?></td>
                                         
                                        </tr>
                                      <?php  }?>
                                    </tbody>
                                  </table>
                                   </form>
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                              </div>
                            </div>
                          </div>
                          <!-- Modal -->  
                
                
                
                
                
                
                
                
                
                
                
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->  <?php
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
        <h4 class="modal-title" id="myModalLabel">Edit Device</h4>
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
	
  var add_bio_data = "add_device";
  $.ajax({
      type: "POST",
      url: "User/getajaxRequest",
      data: {data,add_bio_data},
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
