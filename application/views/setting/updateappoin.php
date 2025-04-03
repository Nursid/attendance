
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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.css">
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
  <style type="text/css">
.stepwizard-step p {
    margin-top: 10px;
}
/*.has-error{
  border:red solid 2px;
}*/
.stepwizard-row {
    display: table-row;
}

.stepwizard {
    display: table;
    width: 100%;
    position: relative;
}

.stepwizard-step button[disabled] {
    opacity: 1 !important;
    filter: alpha(opacity=100) !important;
}

.stepwizard-row:before {
    top: 14px;
    bottom: 0;
    position: absolute;
    content: " ";
    width: 100%;
    height: 1px;
    background-color: #ccc;
    z-order: 0;

}

.stepwizard-step {
    display: table-cell;
    text-align: center;
    position: relative;
}

.btn-circle {
  width: 30px;
  height: 30px;
  text-align: center;
  padding: 6px 0;
  font-size: 12px;
  line-height: 1.428571429;
  border-radius: 15px;
}
  </style>
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
              <li class="breadcrumb-item"><a href="#">Settings</a></li>
              <li class="breadcrumb-item active">Appointment</li>
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
                  <h3 class="card-title">Update Information</h3><br>
                  
                  <span style="color: red; font-size: 1.5rem"><?php echo $this->session->flashdata('msg'); ?></span>
                </div>
                <div class="card-body">
                  <div class="stepwizard">
                      <div class="stepwizard-row setup-panel">
                          <div class="stepwizard-step">
                              <a href="#step-1" type="button" class="btn btn-primary btn-circle">1</a>
                              <p>Step 1</p>
                          </div>
                          <div class="stepwizard-step">
                              <a href="#step-2" type="button" class="btn btn-default btn-circle" disabled="disabled">2</a>
                              <p>Step 2</p>
                          </div>
                          <div class="stepwizard-step">
                              <a href="#step-3" type="button" class="btn btn-default btn-circle" disabled="disabled">3</a>
                              <p>Step 3</p>
                          </div>
                          <div class="stepwizard-step">
                              <a href="#step-4" type="button" class="btn btn-default btn-circle" disabled="disabled">4</a>
                              <p>Step 4</p>
                          </div>
                          <div class="stepwizard-step">
                              <a href="#step-5" type="button" class="btn btn-default btn-circle" disabled="disabled">5</a>
                              <p>Step 5</p>
                          </div>
                      </div>
                  </div>
                  <?php
                 $id=$this->session->userdata('login_id');
                  $sql="SELECT * FROM appoint_setting WHERE id = '$id' ";
                 $data=$this->db->query($sql)->row_array();
                  //print_r($data);
                  
                  ?>
                  
                  
                  
                  <form role="form" id="form" action="<?php echo base_url('User/appointUpdate') ?>" method="post">
				  
				  	<?php
											
											if(!empty($get)){
												//print_r($get);
												$get['id'];
												$login_id;
												 $business_id;
												 
												 $depart_id;
												  $subdepart_id;
												$department_name=$this->db->query("select id,department from department where id='$depart_id'")->row_array();
												$dep_id=$department_name['id'];
												$sub_depart_name=$this->db->query("select id,depart_name from department_sub where id='$subdepart_id'")->row_array();
												$data=$this->db->query("SELECT * FROM `appoint_setting` WHERE login_id='$login_id' && bussiness_id='$business_id' && department='$depart_id' && subdepart='$subdepart_id'")->row_array();
												//print_r($data);
											?>
								 <input type="hidden" value="<?php echo $get['id'];?>"  name="id" >			
                        <div class="row setup-content" id="step-1">
                          <div class="col-xs-12 w-100">
                              <div class="col-md-12">
                                  <h3> Step 1</h3>
                                    <div class="col-12 text-center">
                                      <h4 class="display-4 font-weight-bold  py-2">Choose Departments:-</h4>
                                    </div>
                                      <div class="col-12">
                                        <div class="form-group">
										
									
                                            <lable>Select Department</lable>
                                            <select class="form-control" name="department" required id="departs">
                                               
                                                     <option value="<?php echo $department_name['id']?>" ><?php echo  $department_name['department'];?></option>
                                                  
                                               
                                            </select>
                                        </div>
                                      </div>
                                      <div class="col-12">
                                        <div class="form-group">
                                            <lable>Select Subdepartment</lable>
                                            <select class="form-control" name="sdepartment"  id="sdeparts">
                                                <?php 
											if($data['subdepart']=='0'){
												?>
												 <option value="0" ></option>
												<?php
											}else{
												?>
												 <option value="<?php echo $sub_depart_name['id']?>" ><?php echo $sub_depart_name['depart_name'];?></option>
												<?php
											}
											?>
                                            </select>
                                        </div>
                                      </div>
                                  <button class="btn btn-primary nextBtn btn-lg pull-right" type="button" >Next</button>
                              </div>
                          </div>
                      </div>
                      <div class="row setup-content" id="step-2">
                          <div class="col-xs-12 w-100">
                              <div class="col-md-12">
                                  <h3> Step 1</h3>
                                    <div class="col-12 text-center">
                                      <h4 class="display-4 font-weight-bold  py-2">Weekly Operating Days:-</h4>
                                    </div>
                                  <div class="text-center py-3">
                                      <div class="custom-control custom-checkbox mb-3 pr-2 d-inline">
                                        <input type="checkbox" class="custom-control-input"  <?php if ($data['monday'] == 'open'){ echo $checked = 'checked="checked"';} ?>   id="customCheck1" name="monday">
                                        <label class="custom-control-label" for="customCheck1">Monday</label>
                                      </div>
                                      <div class="custom-control custom-checkbox mb-3 pr-2 d-inline">
                                        <input type="checkbox" class="custom-control-input" id="customCheck2"  <?php if ($data['tuesday'] == 'open'){ echo $checked = 'checked="checked"';} ?>  name="tuesday">
                                        <label class="custom-control-label" for="customCheck2">Tuesday</label>
                                      </div>
                                      <div class="custom-control custom-checkbox mb-3 pr-2 d-inline">
                                        <input type="checkbox" class="custom-control-input" id="customCheck3"  <?php if ($data['wednesday'] == 'open'){ echo $checked = 'checked="checked"';} ?> name="wednesday">
                                        <label class="custom-control-label" for="customCheck3">Wednesday</label>
                                      </div>
                                      <div class="custom-control custom-checkbox mb-3 pr-2 d-inline">
                                        <input type="checkbox" class="custom-control-input" id="customCheck4"   <?php if ($data['thursday'] == 'open'){ echo $checked = 'checked="checked"';} ?> name="thursday">
                                        <label class="custom-control-label" for="customCheck4">Thursday</label>
                                      </div>
                                      <div class="custom-control custom-checkbox mb-3 pr-2 d-inline">
                                        <input type="checkbox" class="custom-control-input" id="customCheck5"  <?php if ($data['friday'] == 'open'){ echo $checked = 'checked="checked"';} ?> name="friday">
                                        <label class="custom-control-label" for="customCheck5">Friday</label>
                                      </div>
                                      <div class="custom-control custom-checkbox mb-3 pr-2 d-inline">
                                        <input type="checkbox" class="custom-control-input" id="customCheck6"  <?php if ($data['saturday'] == 'open'){ echo $checked = 'checked="checked"';} ?> name="saturday">
                                        <label class="custom-control-label" for="customCheck6">Saturday</label>
                                      </div>
                                      <div class="custom-control custom-checkbox mb-3 pr-2 d-inline">
                                        <input type="checkbox" class="custom-control-input" id="customCheck7"  <?php if ($data['sunday'] == 'open'){ echo $checked = 'checked="checked"';} ?> name="sunday">
                                        <label class="custom-control-label" for="customCheck7">Sunday</label>
                                      </div>
                                      
                                  </div>
                                  <button class="btn btn-primary nextBtn btn-lg pull-right" type="button" >Next</button>
                              </div>
                          </div>
                      </div>
                      <div class="row setup-content" id="step-3">
                          <div class="col-xs-12 w-100">
                              <div class="col-md-12">
                                <h3> Step 2</h3>
                                  <div class="col-12 text-center">
                                    <h5 class="display-5 text-center font-weight-bold">Working Hours:-</h5>
                                  </div>
                                  <div class="col-12 text-center">
                                <div class="bootstrap-timepicker">
                                  <div class="form-group">
                                    <label>Opening Time:</label>
                
                                    <div class="input-group date" data-target-input="nearest">
                                      <?php 
											if($data['close_time']=='0'){
												?>
												 <input type="text" class="form-control datetimepicker-input"  id="timepicker1" name="opent" data-target="#timepicker1">
												<?php
											}else{
												?>
												 <input type="text" class="form-control datetimepicker-input" value="<?php echo date("H:i A",$data['open_time']); ?>"  id="timepicker1" name="opent" data-target="#timepicker1">
												<?php
											}
										?>
                                      <div class="input-group-append" data-target="#timepicker1" data-toggle="datetimepicker">
                                          <div class="input-group-text"><i class="far fa-clock"></i></div>
                                      </div>
                                      </div>
                                    <!-- /.input group -->
                                  </div>
                                  <!-- /.form group -->
                                </div>
                              </div>
                              <div class="col-12 text-center">
                                <div class="bootstrap-timepicker">
                                  <div class="form-group">
                                    <label>Closing Time:</label>
                
                                    <div class="input-group date" data-target-input="nearest">
                                      <input type="text" class="form-control datetimepicker-input" value="<?php echo date("H:i A",$data['close_time']); ?>"  id="timepicker2" name="closet" data-target="#timepicker2">
                                      <div class="input-group-append" data-target="#timepicker2" data-toggle="datetimepicker">
                                          <div class="input-group-text"><i class="far fa-clock"></i></div>
                                      </div>
                                      </div>
                                    <!-- /.input group -->
                                  </div>
                                  <!-- /.form group -->
                                </div>
                                    </div>
                                  <button class="btn btn-primary nextBtn btn-lg pull-right" type="button" >Next</button>
                              </div>
                          </div>
                      </div>
                      <div class="row setup-content" id="step-4">
                          <div class="col-xs-12 w-100">
                              <div class="col-md-12">
                                  <h3> Step 3</h3>
                                <div class="col-12 text-center">
                                    <h4 class="display-4 text-center font-weight-bold">Daily Break Hours:-</h4>
                                <div class="bootstrap-timepicker">
                                  <div class="form-group">
                                    <label>Starting Time:</label>
									
                
                                    <div class="input-group date" data-target-input="nearest">
                                      <input type="text" class="form-control datetimepicker-input" value="<?php echo date("H:i A",$data['break_start_time']); ?>"  id="timepicker3" name="breakst" data-target="#timepicker3">
                                      <div class="input-group-append" data-target="#timepicker3" data-toggle="datetimepicker">
                                          <div class="input-group-text"><i class="far fa-clock"></i></div>
                                      </div>
                                      </div>
                                    <!-- /.input group -->
                                  </div>
                                  <!-- /.form group -->
                                </div>
                                      </div>
                                      <div class="col-12 text-center">
                                <div class="bootstrap-timepicker">
                                  <div class="form-group">
                                    <label>Ending Time:</label>
                                    <div class="input-group date" data-target-input="nearest">
                                      <?php 
											if($data['break_end_time']=='0'){
												?>
												 <input type="text" class="form-control datetimepicker-input"  id="timepicker4" name="breakct" data-target="#timepicker4">
												<?php
											}else{
												?>
												 <input type="text" class="form-control datetimepicker-input" value="<?php echo date("H:i A",$data['break_end_time']); ?>"  id="timepicker4" name="breakct" data-target="#timepicker4">
												<?php
											}
										?>
                                      <div class="input-group-append" data-target="#timepicker4" data-toggle="datetimepicker">
                                          <div class="input-group-text"><i class="far fa-clock"></i></div>
                                      </div>
                                      </div>
                                    <!-- /.input group -->
                                  </div>
                                  <!-- /.form group -->
                                </div>
                                      </div>
                                     
                                      <button class="btn btn-primary nextBtn btn-lg pull-right" type="button" >Next</button>
                              </div>
                          </div>
                      </div>
                      <div class="row setup-content" id="step-5">
                          <div class="col-xs-12 w-100">
                              <div class="col-md-12">
                                  <h3> Step 4</h3>
                                <div class="col-12 text-center">
                                    <h4 class="display-4 text-center font-weight-bold">Time Slots Difference:-</h4>
                                <div class="form-group">
                                  <select class="form-control" name="timediff">
                                    <option value="" disabled selected>Select difference between two time slots</option>
                                    <option  value="15" <?php if($data['slot_diff']=='15') echo "selected";?>>15 Minutes</option>
                                    <option value="20" <?php if($data['slot_diff']=='20') echo "selected";?>>20 Minutes</option>
                                    <option value="25" <?php if($data['slot_diff']=='25') echo "selected";?>>25 Minutes</option>
                                    <option value="30" <?php if($data['slot_diff']=='30') echo "selected";?>>30 Minutes</option>
                                    <option value="45" <?php if($data['slot_diff']=='45') echo "selected";?>>45 Minutes</option>
                                  </select>
                                </div>
                                      </div>
                                  <button class="btn btn-success btn-lg pull-right" type="submit">Update!</button>
                              </div>
                          </div>
                      </div>
											<?php
											}
											else{
												  $business_id;
												 $depart_id;
												  $subdepart_id;
												$department_name=$this->db->query("select id,department from department where id='$depart_id'")->row_array();
												$dep_id=$department_name['id'];
												$sub_depart_name=$this->db->query("select id,depart_name from department_sub where id='$subdepart_id'")->row_array();
												?>
												
												
												     <div class="row setup-content" id="step-1">
                          <div class="col-xs-12 w-100">
                              <div class="col-md-12">
                                  <h3> Step 1</h3>
                                    <div class="col-12 text-center">
                                      <h4 class="display-4 font-weight-bold  py-2">Choose Departments:-</h4>
                                    </div>
                                      <div class="col-12">
                                        <div class="form-group">
										
									
                                            <lable>Select Department</lable>
                                            <select class="form-control" name="department" required id="departs">
                                               
                                                     <option value="<?php echo $department_name['id']?>" ><?php echo  $department_name['department'];?></option>
                                                  
                                               
                                            </select>
                                        </div>
                                      </div>
                                      <div class="col-12">
                                        <div class="form-group">
                                            <lable>Select Subdepartment</lable>
                                            <select class="form-control" name="sdepartment"  id="sdeparts">
                                                <option value="<?php echo $sub_depart_name['id']?>" ><?php echo $sub_depart_name['depart_name'];?></option>
                                            </select>
                                        </div>
                                      </div>
                                  <button class="btn btn-primary nextBtn btn-lg pull-right" type="button" >Next</button>
                              </div>
                          </div>
                      </div>
                      <div class="row setup-content" id="step-2">
                          <div class="col-xs-12 w-100">
                              <div class="col-md-12">
                                  <h3> Step 1</h3>
                                    <div class="col-12 text-center">
                                      <h4 class="display-4 font-weight-bold  py-2">Weekly Operating Days:-</h4>
                                    </div>
                                  <div class="text-center py-3">
                                      <div class="custom-control custom-checkbox mb-3 pr-2 d-inline">
                                        <input type="checkbox" class="custom-control-input"   id="customCheck1" name="monday">
                                        <label class="custom-control-label" for="customCheck1">Monday</label>
                                      </div>
                                      <div class="custom-control custom-checkbox mb-3 pr-2 d-inline">
                                        <input type="checkbox" class="custom-control-input" id="customCheck2"    name="tuesday">
                                        <label class="custom-control-label" for="customCheck2">Tuesday</label>
                                      </div>
                                      <div class="custom-control custom-checkbox mb-3 pr-2 d-inline">
                                        <input type="checkbox" class="custom-control-input" id="customCheck3"   name="wednesday">
                                        <label class="custom-control-label" for="customCheck3">Wednesday</label>
                                      </div>
                                      <div class="custom-control custom-checkbox mb-3 pr-2 d-inline">
                                        <input type="checkbox" class="custom-control-input" id="customCheck4"    name="thursday">
                                        <label class="custom-control-label" for="customCheck4">Thursday</label>
                                      </div>
                                      <div class="custom-control custom-checkbox mb-3 pr-2 d-inline">
                                        <input type="checkbox" class="custom-control-input" id="customCheck5"   name="friday">
                                        <label class="custom-control-label" for="customCheck5">Friday</label>
                                      </div>
                                      <div class="custom-control custom-checkbox mb-3 pr-2 d-inline">
                                        <input type="checkbox" class="custom-control-input" id="customCheck6"   name="saturday">
                                        <label class="custom-control-label" for="customCheck6">Saturday</label>
                                      </div>
                                      <div class="custom-control custom-checkbox mb-3 pr-2 d-inline">
                                        <input type="checkbox" class="custom-control-input" id="customCheck7"  name="sunday">
                                        <label class="custom-control-label" for="customCheck7">Sunday</label>
                                      </div>
                                      
                                  </div>
                                  <button class="btn btn-primary nextBtn btn-lg pull-right" type="button" >Next</button>
                              </div>
                          </div>
                      </div>
                      <div class="row setup-content" id="step-3">
                          <div class="col-xs-12 w-100">
                              <div class="col-md-12">
                                <h3> Step 2</h3>
                                  <div class="col-12 text-center">
                                    <h5 class="display-5 text-center font-weight-bold">Working Hours:-</h5>
                                  </div>
                                  <div class="col-12 text-center">
                                <div class="bootstrap-timepicker">
                                  <div class="form-group">
                                    <label>Opening Time:</label>
                
                                    <div class="input-group date" data-target-input="nearest">
                                      <input type="text" class="form-control datetimepicker-input"   id="timepicker1" name="opent" data-target="#timepicker1">
                                      <div class="input-group-append" data-target="#timepicker1" data-toggle="datetimepicker">
                                          <div class="input-group-text"><i class="far fa-clock"></i></div>
                                      </div>
                                      </div>
                                    <!-- /.input group -->
                                  </div>
                                  <!-- /.form group -->
                                </div>
                              </div>
                              <div class="col-12 text-center">
                                <div class="bootstrap-timepicker">
                                  <div class="form-group">
                                    <label>Closing Time:</label>
                
                                    <div class="input-group date" data-target-input="nearest">
                                      <input type="text" class="form-control datetimepicker-input"  id="timepicker2" name="closet" data-target="#timepicker2">
                                      <div class="input-group-append" data-target="#timepicker2" data-toggle="datetimepicker">
                                          <div class="input-group-text"><i class="far fa-clock"></i></div>
                                      </div>
                                      </div>
                                    <!-- /.input group -->
                                  </div>
                                  <!-- /.form group -->
                                </div>
                                    </div>
                                  <button class="btn btn-primary nextBtn btn-lg pull-right" type="button" >Next</button>
                              </div>
                          </div>
                      </div>
                      <div class="row setup-content" id="step-4">
                          <div class="col-xs-12 w-100">
                              <div class="col-md-12">
                                  <h3> Step 3</h3>
                                <div class="col-12 text-center">
                                    <h4 class="display-4 text-center font-weight-bold">Daily Break Hours:-</h4>
                                <div class="bootstrap-timepicker">
                                  <div class="form-group">
                                    <label>Starting Time:</label>
									
                
                                    <div class="input-group date" data-target-input="nearest">
                                      <input type="text" class="form-control datetimepicker-input"  id="timepicker3" name="breakst" data-target="#timepicker3">
                                      <div class="input-group-append" data-target="#timepicker3" data-toggle="datetimepicker">
                                          <div class="input-group-text"><i class="far fa-clock"></i></div>
                                      </div>
                                      </div>
                                    <!-- /.input group -->
                                  </div>
                                  <!-- /.form group -->
                                </div>
                                      </div>
                                      <div class="col-12 text-center">
                                <div class="bootstrap-timepicker">
                                  <div class="form-group">
                                    <label>Ending Time:</label>
                                    <div class="input-group date" data-target-input="nearest">
                                      <input type="text" class="form-control datetimepicker-input"   id="timepicker4" name="breakct" data-target="#timepicker4">
                                      <div class="input-group-append" data-target="#timepicker4" data-toggle="datetimepicker">
                                          <div class="input-group-text"><i class="far fa-clock"></i></div>
                                      </div>
                                      </div>
                                    <!-- /.input group -->
                                  </div>
                                  <!-- /.form group -->
                                </div>
                                      </div>
                                     
                                      <button class="btn btn-primary nextBtn btn-lg pull-right" type="button" >Next</button>
                              </div>
                          </div>
                      </div>
                      <div class="row setup-content" id="step-5">
                          <div class="col-xs-12 w-100">
                              <div class="col-md-12">
                                  <h3> Step 4</h3>
                                <div class="col-12 text-center">
                                    <h4 class="display-4 text-center font-weight-bold">Time Slots Difference:-</h4>
                                <div class="form-group">
                                  <select class="form-control" name="timediff">
                                    <option value="" disabled selected>Select difference between two time slots</option>
                                    <option  value="15" >15 Minutes</option>
                                    <option value="20" >20 Minutes</option>
                                    <option value="25" >25 Minutes</option>
                                    <option value="30" >30 Minutes</option>
                                    <option value="45">45 Minutes</option>
                                  </select>
                                </div>
                                      </div>
                                  <button class="btn btn-success btn-lg pull-right" type="submit">Update!</button>
                              </div>
                          </div>
                      </div>
												
											<?php
											}
											
										   ?>
										   
                  </form>

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
<script src="<?php echo base_url('adminassets/plugins/moment/moment.min.js')?>"></script>
<!-- Select2 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="<?php echo base_url('adminassets/plugins/select2/js/select2.full.min.js')?>"></script>
<script src="<?php echo base_url('adminassets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')?>"></script>
<script src="<?php echo base_url('adminassets/plugins/datatables-responsive/js/dataTables.responsive.min.js')?>"></script>
<script src="<?php echo base_url('adminassets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
<script src="<?php echo base_url('adminassets/dist/js/adminlte.min.js')?>"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo base_url('adminassets/dist/js/demo.js')?>"></script>
<script>
  $(function () {
   var table = $('#example1').DataTable({
     "responsive": true,
      "autoWidth": false,
      "order": [ 8, 'asc' ]
    });

   $('#timepicker1').datetimepicker({
      format: 'LT'
    })
   $('#timepicker2').datetimepicker({
      format: 'LT'
    })
   $('#timepicker3').datetimepicker({
      format: 'LT'
    })
   $('#timepicker4').datetimepicker({
      format: 'LT'
    })
   
  });

  $(document).ready(function () {

    var navListItems = $('div.setup-panel div a'),
            allWells = $('.setup-content'),
            allNextBtn = $('.nextBtn');

    allWells.hide();

    navListItems.click(function (e) {
        e.preventDefault();
        var $target = $($(this).attr('href')),
                $item = $(this);
        if (!$item.hasClass('disabled')) {
            navListItems.removeClass('btn-primary').addClass('btn-default');
            $item.addClass('btn-primary');
            allWells.hide();
            $target.show();
            $target.find('input:eq(0)').focus();
        }
    });

    allNextBtn.click(function(){
        var curStep = $(this).closest(".setup-content"),
            curStepBtn = curStep.attr("id"),
            nextStepWizard = $('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().next().children("a"),
            curInputs = curStep.find(':input'),
            isValid = true;

        $(".form-group").removeClass("has-error");
        for(var i=0; i<curInputs.length; i++){
            if (!curInputs[i].validity.valid){
                isValid = false;
                $(curInputs[i]).closest(".form-group").addClass("has-error");
            }
        }

        if (isValid)
            nextStepWizard.removeAttr('disabled').trigger('click');
    });

    $('div.setup-panel div a.btn-primary').trigger('click');
});

    $('#sbt').on('click', function(){
        var fdata = $("#form").serialize();
        console.log(fdata);
      $.ajax({
        type: "POST",
        url: "User/appointUpdate",
        data: fdata,
        datatype: "json",
      success: function(){
        /*if(res != ''){
          var obj = JSON.parse(res);
          var cname = obj.name;
          var clid = obj.id;
          console.log(cname);
          console.log(clid);
      $('#msg').html('New Name Updated!');
      $('#uname'+clid).html(cname);
        }*/
      }
    });
  });
  $('#Switch1').on('change', function() {
    $('#Switch1l').text('Open');
    /*var bid = <?php echo $cid ?>;
    var tSDept="getTokenBySdept";
    if (sdid != "allTokens") {
        calltype = 2;
        tid = sdid;
    }else{
        calltype = 1;
        tid = did;
    }
    //console.log(calltype,tid);
    $.ajax({
      type: "post",
      url: "User/getajaxRequest",
      data: {sdid,did,bid,tSDept},
      success: function(data){
        $('#fetch').html(data);
        var table = $('#example1').DataTable();
        table.destroy();
 
        table = $('#example1').DataTable( {
            "responsive": true,
            "autoWidth": false,
            "order": [ 8, 'asc' ]
        } );
      }
    });*/
  });
</script>
<script>
$('#departs').on('change', function() {
  var id = this.value;
  var datatypes = "sdepartByDepart";
  $.ajax({
    type: "post",
    url: "User/getajaxRequest",
    data: {id,datatypes},
    success: function(data){
      $('#sdeparts').html(data);
    }
  });
});
</script>

  <?php 
    if($this->session->userdata()['type']=='C')
      { 
    ?>

<script type="text/javascript">
  $(function () {

    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })
  });
  var calltype = 1;
  var tid = <?php echo $depids;?>;
  $('#sdepart').on('change', function() {
    var sdid = this.value;
    var did = <?php echo $depids ?>;
    var bid = <?php echo $cid ?>;
    var tSDept="getTokenBySdept";
    if (sdid != "allTokens") {
        calltype = 2;
        tid = sdid;
    }else{
        calltype = 1;
        tid = did;
    }
    //console.log(calltype,tid);
    $.ajax({
      type: "post",
      url: "User/getajaxRequest",
      data: {sdid,did,bid,tSDept},
      success: function(data){
        $('#fetch').html(data);
        var table = $('#example1').DataTable();
        table.destroy();
 
        table = $('#example1').DataTable( {
            "responsive": true,
            "autoWidth": false,
            "order": [ 8, 'asc' ]
        } );
      }
    });
});

  function callNext(bid){
    $.ajax({
      type: "post",
      url: "User/callNextToken",
      data: {calltype,tid,bid},
      datatype: "json",
    success: function(data){
      //console.log(data);
      var obj = JSON.parse(data);
      //console.log(obj);
        var id1 = obj.id;
        var id2 = obj.uid;
        var id3 = obj.cid;
        var id4 = obj.bid;
        var table = $('#example1').DataTable();
      if (obj.type == 3) {

        var cell = table.cell( '#stat'+id1 );
        cell.data( '<button class="btn btn-success" onclick="Close(\'' + id1+'\',\''+ id2 + '\',\''+ id3 + '\',\''+ id4 + '\')">Calling</button>' ).draw();
        $('#stat'+id1).attr('data-order', '1');

      }else if(obj.type == 1){

        var id5 = obj.nid;
        var id6 = obj.nuid;
        var cell = table.cell( '#stat'+id1 );
        cell.data( '<button class="btn btn-primary">Done</button>' ).draw();
        $('#stat'+id1).attr('data-order', '3');
        var cell = table.cell( '#stat'+id5 );
        cell.data( '<button class="btn btn-success" onclick="Close(\'' + id5+'\',\''+ id6 + '\',\''+ id3 + '\',\''+ id4 + '\')">Calling</button>' ).draw();
        $('#stat'+id5).attr('data-order', '1');
      }else if(obj.type == 2){

        var cell = table.cell( '#stat'+id1 );
        cell.data( '<button class="btn btn-primary">Done</button>' ).draw();
        $('#stat'+id1).attr('data-order', '3');
      }
      table.destroy();
 
        table = $('#example1').DataTable( {
            "responsive": true,
            "autoWidth": false,
            "order": [ 8, 'asc' ]
        } );
      }
    })
  }
  function active(id,uid,cid,bid){
    $.ajax({
      type: "POST",
      url: "User/activateToken",
      data: {id,uid,cid,bid},
      datatype: "json",
    success: function(data){
      var obj = JSON.parse(data);
      var id1 = obj.id;
      var id2 = obj.uid;
      var id3 = obj.cid;
      var id4 = obj.bid;
      var table = $('#example1').DataTable();
      var cell = table.cell( '#stat'+id1 );
      $('#stat'+id1).attr('data-order', '1');
      cell.data( '<button class="btn btn-success" onclick="Close(\'' + id1+'\',\''+ id2 + '\',\''+ id3 + '\',\''+ id4 + '\')">Calling</button>' ).draw();
        table.destroy();
 
        table = $('#example1').DataTable( {
            "responsive": true,
            "autoWidth": false,
            "order": [ 8, 'asc' ]
        } );
    }
    })
  }

  function Close(id,uid,cid,bid){
    $.ajax({
      type: "POST",
      url: "User/closeToken",
      data: {id,uid,cid,bid},
      datatype: "json",      
      success: function(data){
      var obj = JSON.parse(data);
      var id1 = obj.id;
      var id2 = obj.uid;
      var id3 = obj.cid;
      var id4 = obj.bid;
      var table = $('#example1').DataTable();
      var cell = table.cell( '#stat'+id1 );
      $('#stat'+id1).attr('data-order', '3');
      cell.data( '<button class="btn btn-primary">Done</button>' ).draw();
      table.destroy();
 
        table = $('#example1').DataTable( {
            "responsive": true,
            "autoWidth": false,
            "order": [ 8, 'asc' ]
        } );
    }
    })
  }
</script>
<?php } ?>
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
