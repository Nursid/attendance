
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

    <!-- Content Header (Page header)
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
      </div><!-- /.container-fluid 
    </section> -->
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
      if($this->session->userdata()['type']=='B' || $role[0]->employee_list=="1" || $role[0]->type=="1"){?>
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          
        </div>
 
          <div align="right">
          <br>
                          <input type="button" onClick="export_datas()" value="Export To Excel" />
                         <!-- <input type="button"  id="btnExport" value="Export To Pdf" onclick="exportPDF()" />-->
                          <br> <br>
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
                    <th>Desig</th>
                      <th>Depart.</th>
                    <th>Shift</th>
                     <th>Mobile No.</th>
                  <!--  <th>Address</th>-->
                    <th>Status</th>
                     <th>Edit</th>
                     <th>Action</th>
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
                      ?>
                      <tr>
                       <td><?php echo $count++; ?></td>
                        
                        <td><?php $uname = $this->web->getNameByUserId($val->user_id);
                                echo $uname[0]->emp_code;?> </td>
                        <td><?php $uname = $this->web->getNameByUserId($val->user_id);
                                echo $uname[0]->name;
								$desig= $uname[0]->designation;
								if($desig!="")
								{//echo "<br>(". $desig.")"; 
									}
								?>
                                
                                
                                </td>
                        <td>
                          <?php $uname = $this->web->getNameByUserId($val->user_id);
                                echo $uname[0]->designation; ?></td>
                          
                           <td>
                          <?php $uname = $this->web->getNameByUserId($val->user_id);
                          $dp = $this->web->getBusinessDepByUserId($uname[0]->department);
                                if(!empty($dp)){
                                echo $dp[0]->name;}
                                ?></td>
                          
                          
                          <td> 
                          <?php $uname = $this->web->getNameByUserId($val->user_id);
                                
                                $group = $this->web->getBusinessGroupByUserId($uname[0]->business_group);
                                if(!empty($group)){
						         echo $group[0]->name;
                                }
                                ?>
                          
                          </td>
                          
                          
                          
                        <td><?php $uname = $this->web->getNameByUserId($val->user_id);
                                echo $uname[0]->mobile; 
							     // echo time();
							   
							   ?>
                               
                               </td>
                                
                       <!-- <td ><?php $uname = $this->web->getNameByUserId($val->user_id);
                                echo $uname[0]->address; ?></td>-->
                        
                        <td id="activate<?php echo $val->user_id; ?>">
                          <?php
                              if ($val->user_status == "0") {
                          ?>    
                            <button class="btn btn-danger" onclick="active('<?php echo $val->user_id; ?>')" >Inactive</button>
                          <?php
                              }elseif($val->user_status == "1"){
                          ?>
                            <button class="btn btn-success" onclick="inactive('<?php echo $val->user_id; ?>')" >Active</button>
                         
                          <?php
                            }
                          ?>
                          <br> <br>
                         <?php
      if($this->session->userdata()['type']=='B' || $role[0]->add_emp=="1" || $role[0]->type=="1"){?> 
                       
                          <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#myModal" onclick="mclick('<?php echo $val->user_id;  ?>')">Left Emp.
                           
                          </button>
                          <?php }?>
                          </td>
                          
                        
                     <!--   <td>  
                        <button class="btn btn-danger"data-toggle="modal" data-target="#myModal" onclick="mclick('<?php //echo $val->id; ?>')" >Left</button>
                        
                        <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal" onclick="mclick('<?php //echo $res->id; ?>')">
                            <i class="fas fa-edit"></i>
                          </button>
                        </td> -->
                        <?php
      if($this->session->userdata()['type']=='B' || $role[0]->add_emp=="1" || $role[0]->type=="1"){?>
                        <td> 
                            
                         
                          <form action="<?php echo base_url('User/editemployees')?>" method="post">
                          <input type="hidden" value="<?php echo $val->user_id; ?>" name="id"> 
                          <input type="submit" value="Edit" class="btn btn-primary ">
                         
                          </form>
                      <br>
                          <form action="<?php echo base_url('User/staffattendanceOptions')?>" method="post">
                          <input type="hidden" value="<?php echo $val->user_id; ?>" name="id"> 
                          <input type="submit" value="Att. Option" class="btn btn-primary ">
                         
                          </form>
                          
                        
                       </td>  
                       <?php } ?>
                        <td>
                           
                          <form action="<?php echo base_url('User/emp_detail')?>" method="post">
                          <input type="hidden" value="<?php echo $val->user_id; ?>" name="id"> 
                          <input type="submit" value="Detail" class="btn btn-primary ">
                         </form>
                         <br>
                         <button type="button" class="btn btn-primary btn-mini" data-toggle="modal" data-target="#infoModal" onclick="mclick2('<?php echo $val->user_id; ?>')">
                            <i class="fa fa-info-circle"></i>
                          </button>
                        <br>
                           <?php
      if($this->session->userdata()['type']=='B' || $role[0]->add_emp=="1" || $role[0]->type=="1"){?>  
                        <!--  <button type="button" class="btn btn-info btn-circle btn-x" data-toggle="modal" data-target="#editShiftModal<?php //echo $res->id;?>">Weeklyoff</button>-->
                      <?php }?>
                         
                          
                        <!--  <a href="<?php echo base_url('User/emp_detail?id=') . $val->user_id ?>"
                       
                 
                  
               
                          <button type="button"  class="btn btn-primary "  value="('<?php //echo $val->user_id; ?>')">
                            <i class="fas fa-info-circle"></i>
                          </button>  </a>-->
                     
                     
                        </td>
                      </tr>
                      
                      
                      
           <div class="modal fade" id="editShiftModal<?= $val->id;?>" tabindex="-1" role="dialog" aria-labelledby="editShiftModalLabel<?= $val->user_id;?>" aria-hidden="true">
                      <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="editShiftModalLabel<?= $val->user_id;?>">Edit WO</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <form action="<?php echo base_url('User/editwo')?>" method="POST">
                            <div class="modal-body">
                            <input type="text" name="shift_id" id="shift_id" value="<?= $val->user_id;?>" hidden>
                              
                              <div class="row text-center pt-4">
                                <div class="col-sm-2">
                                  <h6>Day</h6>
                                </div>
                                
                                <div class="col-sm-1">
                                  <h6>WO  Ist</h6>
                                </div>
                                <div class="col-sm-1">
                                  <h6>WO IInd</h6>
                                </div>
                                <div class="col-sm-1">
                                  <h6>WO IIIrd</h6>
                                </div>
                                <div class="col-sm-1">
                                  <h6>WO IVth</h6>
                                </div>
                                <div class="col-sm-1">
                                  <h6>WO Vth</h6>
                                </div>
                              </div>
                              <?php
                                //$dayStartTimes = explode(",",$res->day_start_time);
                                //$dayEndTimes = explode(",",$res->day_end_time);
                                $weekOffs = isset($res->weekly_off) ? explode(",",$res->weekly_off): [0,0,0,0,0,0,0];
                              ?>
                              <div class="row text-center pt-2">
                                <div class="col-sm-2">
                                  <h6>Monday</h6>
                                </div>
                               
                               
                                <div class="col-sm-1">
                                  <input class="form-check-input" type="checkbox" value="" id="monday_checkbox" name="monday_checkbox" <?php if($weekOffs[0]==1){ echo "checked";}?>>
                                </div>
                                <div class="col-sm-1">
                                  <input class="form-check-input" type="checkbox" value="" id="monday_checkbox" name="monday_checkbox" <?php if($weekOffs[0]==1){ echo "checked";}?>>
                                </div>
                                <div class="col-sm-1">
                                  <input class="form-check-input" type="checkbox" value="" id="monday_checkbox" name="monday_checkbox" <?php if($weekOffs[0]==1){ echo "checked";}?>>
                                </div>
                                <div class="col-sm-1">
                                  <input class="form-check-input" type="checkbox" value="" id="monday_checkbox" name="monday_checkbox" <?php if($weekOffs[0]==1){ echo "checked";}?>>
                                </div>
                                <div class="col-sm-1">
                                  <input class="form-check-input" type="checkbox" value="" id="monday_checkbox" name="monday_checkbox" <?php if($weekOffs[0]==1){ echo "checked";}?>>
                                </div>
                              </div>
                              <div class="row text-center pt-2">
                                <div class="col-sm-2">
                                  <h6>Tuesday</h6>
                                </div>
                               
                                <div class="col-sm-1">
                                  <input class="form-check-input" type="checkbox" value="" id="tuesday_checkbox" name="tuesday_checkbox" <?php if($weekOffs[1]==1){ echo "checked";}?>>
                                </div>
                                <div class="col-sm-1">
                                  <input class="form-check-input" type="checkbox" value="" id="tuesday_checkbox" name="tuesday_checkbox" <?php if($weekOffs[1]==1){ echo "checked";}?>>
                                </div>
                                <div class="col-sm-1">
                                  <input class="form-check-input" type="checkbox" value="" id="tuesday_checkbox" name="tuesday_checkbox" <?php if($weekOffs[1]==1){ echo "checked";}?>>
                                </div>
                                <div class="col-sm-1">
                                  <input class="form-check-input" type="checkbox" value="" id="tuesday_checkbox" name="tuesday_checkbox" <?php if($weekOffs[1]==1){ echo "checked";}?>>
                                </div>
                                <div class="col-sm-1">
                                  <input class="form-check-input" type="checkbox" value="" id="tuesday_checkbox" name="tuesday_checkbox" <?php if($weekOffs[1]==1){ echo "checked";}?>>
                                </div>
                              </div>
                              <div class="row text-center pt-2">
                                <div class="col-sm-2">
                                  <h6>Wednesday</h6>
                                </div>
                                
                                 <div class="col-sm-1">
                                  <input class="form-check-input" type="checkbox" value="" id="wednesday_checkbox" name="wednesday_checkbox" <?php if($weekOffs[2]==1){ echo "checked";}?>>
                                </div>
                                <div class="col-sm-1">
                                  <input class="form-check-input" type="checkbox" value="" id="wednesday_checkbox" name="wednesday_checkbox" <?php if($weekOffs[2]==1){ echo "checked";}?>>
                                </div>
                                <div class="col-sm-1">
                                  <input class="form-check-input" type="checkbox" value="" id="wednesday_checkbox" name="wednesday_checkbox" <?php if($weekOffs[2]==1){ echo "checked";}?>>
                                </div>
                                <div class="col-sm-1">
                                  <input class="form-check-input" type="checkbox" value="" id="wednesday_checkbox" name="wednesday_checkbox" <?php if($weekOffs[2]==1){ echo "checked";}?>>
                                </div>
                                <div class="col-sm-1">
                                  <input class="form-check-input" type="checkbox" value="" id="wednesday_checkbox" name="wednesday_checkbox" <?php if($weekOffs[2]==1){ echo "checked";}?>>
                                </div>
                              </div>
                              <div class="row text-center pt-2">
                                <div class="col-sm-2">
                                  <h6>Thursday</h6>
                                </div>
                                
                                <div class="col-sm-1">
                                  <input class="form-check-input" type="checkbox" value="" id="thursday_checkbox" name="thursday_checkbox" <?php if($weekOffs[3]==1){ echo "checked";}?>>
                                </div>
                                <div class="col-sm-1">
                                  <input class="form-check-input" type="checkbox" value="" id="thursday_checkbox" name="thursday_checkbox" <?php if($weekOffs[3]==1){ echo "checked";}?>>
                                </div>
                                <div class="col-sm-1">
                                  <input class="form-check-input" type="checkbox" value="" id="thursday_checkbox" name="thursday_checkbox" <?php if($weekOffs[3]==1){ echo "checked";}?>>
                                </div>
                                <div class="col-sm-1">
                                  <input class="form-check-input" type="checkbox" value="" id="thursday_checkbox" name="thursday_checkbox" <?php if($weekOffs[3]==1){ echo "checked";}?>>
                                </div>
                                <div class="col-sm-1">
                                  <input class="form-check-input" type="checkbox" value="" id="thursday_checkbox" name="thursday_checkbox" <?php if($weekOffs[3]==1){ echo "checked";}?>>
                                </div>
                                
                              </div>
                              <div class="row text-center pt-2">
                                <div class="col-sm-2">
                                  <h6>Friday</h6>
                                </div>
                               
                                <div class="col-sm-1">
                                  <input class="form-check-input" type="checkbox" value="" id="friday_checkbox" name="friday_checkbox" <?php if($weekOffs[4]==1){ echo "checked";}?>>
                                </div>
                                <div class="col-sm-1">
                                  <input class="form-check-input" type="checkbox" value="" id="friday_checkbox" name="friday_checkbox" <?php if($weekOffs[4]==1){ echo "checked";}?>>
                                </div>
                                <div class="col-sm-1">
                                  <input class="form-check-input" type="checkbox" value="" id="friday_checkbox" name="friday_checkbox" <?php if($weekOffs[4]==1){ echo "checked";}?>>
                                </div>
                                <div class="col-sm-1">
                                  <input class="form-check-input" type="checkbox" value="" id="friday_checkbox" name="friday_checkbox" <?php if($weekOffs[4]==1){ echo "checked";}?>>
                                </div>
                                <div class="col-sm-1">
                                  <input class="form-check-input" type="checkbox" value="" id="friday_checkbox" name="friday_checkbox" <?php if($weekOffs[4]==1){ echo "checked";}?>>
                                </div>
                              </div>
                              <div class="row text-center pt-2">
                                <div class="col-sm-2">
                                  <h6>Saturday</h6>
                                </div>
                                
                                <div class="col-sm-1">
                                  <input class="form-check-input" type="checkbox" value="" id="saturday_checkbox" name="saturday_checkbox" <?php if($weekOffs[5]==1){ echo "checked";}?>>
                                </div>
                                 <div class="col-sm-1">
                                  <input class="form-check-input" type="checkbox" value="" id="saturday_checkbox" name="saturday_checkbox" <?php if($weekOffs[5]==1){ echo "checked";}?>>
                                </div>
                                 <div class="col-sm-1">
                                  <input class="form-check-input" type="checkbox" value="" id="saturday_checkbox" name="saturday_checkbox" <?php if($weekOffs[5]==1){ echo "checked";}?>>
                                </div>
                                 <div class="col-sm-1">
                                  <input class="form-check-input" type="checkbox" value="" id="saturday_checkbox" name="saturday_checkbox" <?php if($weekOffs[5]==1){ echo "checked";}?>>
                                </div>
                                 <div class="col-sm-1">
                                  <input class="form-check-input" type="checkbox" value="" id="saturday_checkbox" name="saturday_checkbox" <?php if($weekOffs[5]==1){ echo "checked";}?>>
                                </div>
                              </div>
                              <div class="row text-center pt-2">
                                <div class="col-sm-2">
                                  <h6>Sunday</h6>
                                </div>
                                
                                <div class="col-sm-1">
                                  <input class="form-check-input" type="checkbox" value="" id="sunday_checkbox" name="sunday_checkbox" <?php if($weekOffs[6]==1){ echo "checked";}?>>
                                </div>
                                <div class="col-sm-1">
                                  <input class="form-check-input" type="checkbox" value="" id="sunday_checkbox" name="sunday_checkbox" <?php if($weekOffs[6]==1){ echo "checked";}?>>
                                </div>
                                <div class="col-sm-1">
                                  <input class="form-check-input" type="checkbox" value="" id="sunday_checkbox" name="sunday_checkbox" <?php if($weekOffs[6]==1){ echo "checked";}?>>
                                </div>
                                <div class="col-sm-1">
                                  <input class="form-check-input" type="checkbox" value="" id="sunday_checkbox" name="sunday_checkbox" <?php if($weekOffs[6]==1){ echo "checked";}?>>
                                </div>
                                <div class="col-sm-1">
                                  <input class="form-check-input" type="checkbox" value="" id="sunday_checkbox" name="sunday_checkbox" <?php if($weekOffs[6]==1){ echo "checked";}?>>
                                </div>
                              </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                              <button type="submit" class="btn btn-primary">Update Shift</button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>           
                      
                      
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
        <h4 class="modal-title" id="myModalLabel">Left Employee</h4>
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


<div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">Business Detail </h4>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>


<script src="<?php echo base_url('adminassets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')?>"></script>
<script src="<?php echo base_url('adminassets/plugins/datatables-responsive/js/dataTables.responsive.min.js')?>"></script>
<script src="<?php echo base_url('adminassets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')?>"></script>
<script src="<?php echo base_url('adminassets/dist/js/adminlte.min.js')?>"></script>
<!-- AdminLTE for demo purposes -->

<script src="<?php echo base_url('adminassets/dist/js/demo.js')?>"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.3.0/exceljs.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/fixedcolumns/4.2.1/js/dataTables.fixedColumns.min.js"></script>


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
<script>
function mclick(data){
  var edit_left = "edit_left";
  $.ajax({
      type: "POST",
      url: "User/getajaxRequest",
      data: {data,edit_left},
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

<script>
function mclick2(data){
  var user_detail = "use_detail";
  $.ajax({
      type: "POST",
      url: "User/getajaxRequest",
      data: {data,user_detail},
    success: function(response){
      $('#modform2').html(response);
    }
    })
}
</script>



<script>
function export_datas(){
      var wb = new ExcelJS.Workbook();
      var sh = wb.addWorksheet("Emp Report");
      <?php
	  // if (!empty($salEmpList)) {
      
	 ?>
      sh.columns = [
        {header: 'SNo.', key: 'SNo', width: 10},
        {header: 'Empcode', key: 'Empcode', width: 15},
		 {header: 'Device id', key: 'device_id', width: 10,},
        {header: 'Name', key: 'Name', width: 20,},
        {header: 'Mobile', key: 'Mobile', width: 15,},
		{header: 'Email', key: 'Emails_id', width: 30,},
        {header: 'Contact', key: 'Contact', width: 25,},
        {header: 'Address', key: 'Address', width: 20,},
         {header: 'Father', key: 'Father_name', width: 15,},
         {header: 'Gender', key: 'gender', width: 15,},
         {header: 'Dob', key: 'dob', width: 20,},
		 {header: 'Blood', key: 'blood_group', width: 15,},
          {header: 'Desig', key: 'designation', width: 25,},
          {header: 'Department', key:'dep', width: 30,},
          {header: 'Shift', key: 'shifts', width: 20,},
         
		   {header: 'Doj', key: 'doj', width: 20,},
          {header: 'Education', key: 'edu', width: 20,},
		  
		  {header: 'Experience', key: 'experience', width: 20,},
		  
          {header: 'Pay Mode', key: 'pay_mode', width: 20,},
		   {header: 'Bank name', key: 'bank_name', width: 20,},
          {header: 'A/C No', key: 'Ac_no', width: 20,},
		  {header: 'IFSC', key: 'ifsc', width: 20,},
		  
		  {header: 'UPI', key: 'upi', width: 25,},
		  
          {header: 'PAN', key: 'pan', width: 20,},
		   {header: 'Adhar', key: 'adhar', width: 25,},
          {header: 'EPF', key: 'epf', width: 20,},
		  {header: 'UAN', key: 'uan', width: 20,},
          {header: 'ESIC', key: 'esic', width: 20,}
          
        ];
		
    
      <?php
     
    
	     $count = 1;
        foreach($res as $val){
			$uname = $this->web->getNameByUserId($val->user_id);
			 $info=$this->web->getstaffinfoByUserId($val->user_id,$id); 
		 $bname = $this->web->getNameByUserId($id);
		$cmp_name=$bname[0]->name;
			 
			  $group = $this->web->getBusinessGroupByUserId($uname[0]->business_group);
			$dpt = $this->web->getBusinessDepByUserId($uname[0]->department);
            if($uname[0]->gender==0){
            $gender=Male;
            }else{
                $gender=Female;
                
            }  
            $mail=$uname[0]->email;
          ?>
        
        
        
        sh.addRow({SNo:'<?php echo $count++;?>',Empcode:'<?= $uname[0]->emp_code; ?>',device_id:'<?= $uname[0]->bio_id; ?>',Name:'<?= $uname[0]->name; ?>',Mobile:'<?=$uname[0]->mobile;?>', Emails_id:'<?= 0; ?>',Contact:'<?=$uname[0]->phone;?>',Address:'<?= $uname[0]->address;?>',Father_name:'<?= $uname[0]->father_name; ?>',gender:'<?=$gender;?>',dob:'<?=$uname[0]->dob;?>',designation:'<?=$uname[0]->designation;?>',dep:'<?=$dpt[0]->name;?>',
			 shifts:'<?= $group[0]->name; ?>',
			 blood_group:'<?=$uname[0]->blood_group;?>',
			 doj:'<?=date("d-m-Y",$uname[0]->doj);?>',
			education:'<?=$uname[0]->education;?>',
			experience:'<?=$uname[0]->experience;?>',
			pay_mode:'<?=$info[0]->pay_mode;?>',
			bank_name:'<?=$info[0]->bank_name;?>',
			 Ac_no:'<?= $info[0]->account_no; ?>',
			 ifsc:'<?=$info[0]->ifsc_code;?>',
			 upi:'<?=$info[0]->upi;?>',
			pan:'<?=$info[0]->pan;?>',
			adhar:'<?=$info[0]->adhar;?>',
			epf:'<?= $info[0]->epf; ?>',
			 uan:'<?=$info[0]->uan;?>',
			 esic:'<?=$info[0]->esic;?>' });
        
        
        
        
      <?php 
          echo "sh.getRow(".$sr++.").alignment = { wrapText: true,vertical: 'top',horizontal: 'center' };";
          //echo "sh.getRow(".$sr.").border = {top: {style:'thin'},left: {style:'thin'},bottom: {style:'thin'},right: {style:'thin'}};";
       }
        echo "sh.getRow(".$sr.").alignment = { wrapText: true,vertical: 'top',horizontal: 'center' };";
         echo "sh.insertRow(1, ['$cmp_name']);";
     
       echo "sh.insertRow(2, ['Employee List']);";
        echo "sh.mergeCells('A1:Q1');";
        echo "sh.mergeCells('A2:Q2');";
        echo "sh.getRow(1).alignment = {horizontal: 'center' };";
        echo "sh.getRow(2).alignment = {horizontal: 'center' };";
        $sr+=4;
       // echo "sh.insertRow($sr,['Total CTC:$salaryTotalCtc, Total Salary:$salaryTotalSalary, Total Advance:$salaryTotalPaid, Total Deduction:$salaryTotalDeduction, Total Net Payable:$salaryNetPayable']);";
        echo "sh.mergeCells('A$sr:Q$sr');";
       // echo "sh.getRow($sr).alignment = {horizontal: 'center' };";
     // }
	 ?>
      wb.xlsx.writeBuffer().then((data) => {
            const blob = new Blob([data], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8' });
            saveAs(blob, 'Employee List.xlsx');
      });
  }

</script>








</body>
</html>
