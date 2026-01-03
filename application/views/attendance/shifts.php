
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
              <li class="breadcrumb-item active">Shifts</li>
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
      $bid = $this->session->userdata('empCompany');
      $role=$this->web->getRollbyid($this->web->session->userdata('login_id'),$bid);
  
    } else {
      $bid=$this->web->session->userdata('login_id');
    }
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
                <h3 class="card-title">Shifts List</h3>
                <span style="color: red"><?php echo $this->session->flashdata('msg');?></span>
              </div>
              <div class="row p-2">
                <div class="col-sm-4">
                  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addShiftModal">
                    Add Shift
                  </button>
                </div>
              </div>
              <div class="card-body">
               <?php 
			 // $bid=$this->web->session->userdata('login_id');
			  
			  ?>
              <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>S.No</th>
                    <th>Shift Name</th>
                    <th>Shift Start</th>
                    <th>Shift End</th>
                    <th>Weekly Off</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                      <?php
                      $res=$this->web->getBusinessGroupByBusinessId($bid);
                      $count=1;
                      foreach($res as $res){
						 //$data = array(); 
						$data = explode(",",$res->month_weekly_off);
					//$data2 = array();
							//	foreach($data as $val) {
                               // $data2 = explode(",",$val);
                                // //$data[$key+1] = $new;
                                //   } 
						//	foreach ($data2 as $key => $dat){
                         //  $data2[$key] = $new;
						  // } 
						//  $tempArray=$new;
						  
						  
                      ?>
                      <tr>
                        <td><?php echo $count++; ?></td>
                        <td><?php echo $res->name; ?></td>
                       <td><?php echo $res->shift_start; ?></td>
                       <td><?php echo $res->shift_end; ?></td>
                      <?php
					              $weekly_off = explode(",",$res->weekly_off);
                        $weekOffs = "";
                        if($weekly_off[0]==1){
                          $weekOffs = "Mon";
                        }
                        if($weekly_off[1]==1){
                          if(!empty($weekOffs)){
                            $weekOffs = $weekOffs.", Tue";
                          }else{
                            $weekOffs = "Tue";
                          }
                        }
                        if($weekly_off[2]==1){
                          if(!empty($weekOffs)){
                            $weekOffs = $weekOffs.", Wed";
                          }else{
                            $weekOffs = "Wed";
                          }
                        }
                        if($weekly_off[3]==1){
                          if(!empty($weekOffs)){
                            $weekOffs = $weekOffs.", Thur";
                          }else{
                            $weekOffs = "Thur";
                          }
                        }
                        if($weekly_off[4]==1){
                          if(!empty($weekOffs)){
                            $weekOffs = $weekOffs.", Fri";
                          }else{
                            $weekOffs = "Fri";
                          }
                        }
                        if($weekly_off[5]==1){
                          if(!empty($weekOffs)){
                            $weekOffs = $weekOffs.", Sat";
                          }else{
                            $weekOffs = "Sat";
                          }
                        }
                        if($weekly_off[6]==1){
                          if(!empty($weekOffs)){
                            $weekOffs = $weekOffs.", Sun";
                          }else{
                            $weekOffs = "Sun";
                          }
                        }
                        ?>
                        
                         <td><?php echo $weekOffs; ?></td>
                        <td>
                          
                          <form action="<?php echo base_url('User/deleteShift')?>" method="POST">
                            <button type="button" class="btn btn-info btn-circle btn-x" data-toggle="modal" data-target="#editShiftModal<?= $res->id;?>">
                              <i class="fa fa-edit" style="color:white"></i>
                            </button>
                            <input type="text" value="<?= $res->id?>" name="shift_id" hidden/>
                            <button type="submit" class="btn btn-danger btn-circle btn-x">
                              <i class="fa fa-trash" style="color:white"></i>
                            </button>
                          </form>
                        </td>
                      </tr>
                      <div class="modal fade" id="editShiftModal<?= $res->id;?>" tabindex="-1" role="dialog" aria-labelledby="editShiftModalLabel<?= $res->id;?>" aria-hidden="true">
                      <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="editShiftModalLabel<?= $res->id;?>">Edit Shift</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <form action="<?php echo base_url('User/editShift')?>" method="POST">
                            <div class="modal-body">
                            <input type="text" name="shift_id" id="shift_id" value="<?= $res->id;?>" hidden>
                              <div class="row">
                                <div class="col">
                                  <label for="shift_name">Shift Name</label>
                                  <input type="text" name="shift_name" id="shift_name" class="form-control" placeholder="Enter Shift Name" value="<?= $res->name;?>" required>
                                </div>
                              </div>
                              <div class="row pt-2">
                                <div class="col-sm-6">
                                  <label for="shift_start">Shift Start Time</label>
                                  <input type="time" name="shift_start" id="shift_name" class="form-control" value="<?= date("H:i",strtotime($res->shift_start));?>"/>
                                </div>
                                <div class="col-sm-6">
                                  <label for="shift_end">Shift End Time</label>
                                  <input type="time" name="shift_end" id="shift_end" class="form-control" value="<?= date("H:i",strtotime($res->shift_end));?>">
                                </div>
                              </div>
                              <div class="row text-center pt-4">
                                <div class="col-sm-2">
                                  <h6>Day</h6>
                                </div>
                                <div class="col-sm-2">
                                  <h6>Shift Start Time</h6>
                                </div>
                                <div class="col-sm-2">
                                  <h6>Shift End Time</h6>
                                </div>
                                <div class="col-sm-1">
                                  <h6>WO 1st </h6>
                                </div>
                                 <div class="col-sm-1">
                                  <h6>WO2nd </h6>
                                </div>
                                 <div class="col-sm-1">
                                  <h6>WO 3rd </h6>
                                </div>
                                 <div class="col-sm-1">
                                  <h6>WO 4th </h6>
                                </div> <div class="col-sm-1">
                                  <h6>WO 5th </h6>
                                </div>
                              </div>
                              <?php
                                $dayStartTimes = explode(",",$res->day_start_time);
                                $dayEndTimes = explode(",",$res->day_end_time);
                                $weekOffs = explode(",",$res->weekly_off);
								$dataw = explode(",",$res->month_weekly_off);
								
								
                              ?>
                              <div class="row text-center pt-4">
                                <div class="col-sm-2">
                                  <h6>Mon
                                  </h6>
                                </div>
                                <div class="col-sm-2">
                                  <input type="time" class="form-control" name="monday_start" id="monday_start" value="<?= date("H:i",strtotime($dayStartTimes[0]));?>"/>
                                </div>
                                <div class="col-sm-2">
                                  <input type="time" class="form-control" name="monday_end" id="monday_end" value="<?= date("H:i",strtotime($dayEndTimes[0]));?>"/>
                                </div>
                                <div class="col-sm-1">
                                  <input class="form-check-input" type="checkbox" value="" id="monday_checkbox" name="monday_checkbox1" <?php if($dataw[0]==1){ echo "checked";}?>>
                                </div>
                                <div class="col-sm-1">
                                  <input class="form-check-input" type="checkbox" value="" id="monday_checkbox" name="monday_checkbox2" <?php if($dataw[7]==1){ echo "checked";}?>>
                                </div>
                                <div class="col-sm-1">
                                  <input class="form-check-input" type="checkbox" value="" id="monday_checkbox" name="monday_checkbox3" <?php if($dataw[14]==1){ echo "checked";}?>>
                                </div>
                                <div class="col-sm-1">
                                  <input class="form-check-input" type="checkbox" value="" id="monday_checkbox" name="monday_checkbox4" <?php if($dataw[21]==1){ echo "checked";}?>>
                                </div>
                                <div class="col-sm-1">
                                  <input class="form-check-input" type="checkbox" value="" id="monday_checkbox" name="monday_checkbox5" <?php if($dataw[28]==1){ echo "checked";}?>>
                                </div>
                              </div>
                              <div class="row text-center pt-2">
                                <div class="col-sm-2">
                                  <h6>Tuesday</h6>
                                </div>
                                <div class="col-sm-2">
                                  <input type="time" class="form-control" name="tuesday_start" id="tuesday_start" value="<?= date("H:i",strtotime($dayStartTimes[1]));?>"/>
                                </div>
                                <div class="col-sm-2">
                                  <input type="time" class="form-control" name="tuesday_end" id="tuesday_end" value="<?= date("H:i",strtotime($dayEndTimes[1]));?>"/>
                                </div>
                                <div class="col-sm-1">
                                  <input class="form-check-input" type="checkbox" value="" id="monday_checkbox" name="tuesday_checkbox1" <?php if($dataw[1]==1){ echo "checked";}?>>
                                </div>
                                <div class="col-sm-1">
                                  <input class="form-check-input" type="checkbox" value="" id="monday_checkbox" name="tuesday_checkbox2" <?php if($dataw[8]==1){ echo "checked";}?>>
                                </div>
                                <div class="col-sm-1">
                                  <input class="form-check-input" type="checkbox" value="" id="monday_checkbox" name="tuesday_checkbox3" <?php if($dataw[15]==1){ echo "checked";}?>>
                                </div>
                                <div class="col-sm-1">
                                  <input class="form-check-input" type="checkbox" value="" id="monday_checkbox" name="tuesday_checkbox4" <?php if($dataw[22]==1){ echo "checked";}?>>
                                </div>
                                <div class="col-sm-1">
                                  <input class="form-check-input" type="checkbox" value="" id="monday_checkbox" name="tuesday_checkbox5" <?php if($dataw[29]==1){ echo "checked";}?>>
                                </div>
                                
                              </div>
                              <div class="row text-center pt-2">
                                <div class="col-sm-2">
                                  <h6>Wednesday</h6>
                                </div>
                                <div class="col-sm-2">
                                  <input type="time" class="form-control" name="wednesday_start" id="wednesday_start" value="<?= date("H:i",strtotime($dayStartTimes[2]));?>"/>
                                </div>
                                <div class="col-sm-2">
                                  <input type="time" class="form-control" name="wednesday_end" id="wednesday_end" value="<?= date("H:i",strtotime($dayEndTimes[2]));?>"/>
                                </div>
                                <div class="col-sm-1">
                                  <input class="form-check-input" type="checkbox" value="" id="monday_checkbox" name="wed_checkbox1" <?php if($dataw[2]==1){ echo "checked";}?>>
                                </div>
                                <div class="col-sm-1">
                                  <input class="form-check-input" type="checkbox" value="" id="monday_checkbox" name="wed_checkbox2" <?php if($dataw[9]==1){ echo "checked";}?>>
                                </div>
                                <div class="col-sm-1">
                                  <input class="form-check-input" type="checkbox" value="" id="monday_checkbox" name="wed_checkbox3" <?php if($dataw[16]==1){ echo "checked";}?>>
                                </div>
                                <div class="col-sm-1">
                                  <input class="form-check-input" type="checkbox" value="" id="monday_checkbox" name="wed_checkbox4" <?php if($dataw[23]==1){ echo "checked";}?>>
                                </div>
                                <div class="col-sm-1">
                                  <input class="form-check-input" type="checkbox" value="" id="monday_checkbox" name="wed_checkbox5" <?php if($dataw[30]==1){ echo "checked";}?>>
                                </div>
                              </div>
                              <div class="row text-center pt-2">
                                <div class="col-sm-2">
                                  <h6>Thursday</h6>
                                </div>
                                <div class="col-sm-2">
                                  <input type="time" class="form-control" name="thursday_start" id="thursday_start" value="<?= date("H:i",strtotime($dayStartTimes[3]));?>"/>
                                </div>
                                <div class="col-sm-2">
                                  <input type="time" class="form-control" name="thursday_end" id="thursday_end" value="<?= date("H:i",strtotime($dayEndTimes[3]));?>"/>
                                </div>
                               
                                  <div class="col-sm-1">
                                  <input class="form-check-input" type="checkbox" value="" id="monday_checkbox" name="tur_checkbox1" <?php if($dataw[3]==1){ echo "checked";}?>>
                                </div>
                                <div class="col-sm-1">
                                  <input class="form-check-input" type="checkbox" value="" id="monday_checkbox" name="tur_checkbox2" <?php if($dataw[10]==1){ echo "checked";}?>>
                                </div>
                                <div class="col-sm-1">
                                  <input class="form-check-input" type="checkbox" value="" id="monday_checkbox" name="tur_checkbox3" <?php if($dataw[17]==1){ echo "checked";}?>>
                                </div>
                                <div class="col-sm-1">
                                  <input class="form-check-input" type="checkbox" value="" id="monday_checkbox" name="tur_checkbox4" <?php if($dataw[24]==1){ echo "checked";}?>>
                                </div>
                                <div class="col-sm-1">
                                  <input class="form-check-input" type="checkbox" value="" id="monday_checkbox" name="tur_checkbox5" <?php if($dataw[31]==1){ echo "checked";}?>>
                               
                                </div>
                              </div>
                              <div class="row text-center pt-2">
                                <div class="col-sm-2">
                                  <h6>Friday</h6>
                                </div>
                                <div class="col-sm-2">
                                  <input type="time" class="form-control" name="friday_start" id="friday_start" value="<?= date("H:i",strtotime($dayStartTimes[4]));?>"/>
                                </div>
                                <div class="col-sm-2">
                                  <input type="time" class="form-control" name="friday_end" id="friday_end" value="<?= date("H:i",strtotime($dayEndTimes[4]));?>"/>
                                </div>
                                <div class="col-sm-1">
                                  <input class="form-check-input" type="checkbox" value="" id="monday_checkbox" name="fri_checkbox1" <?php if($dataw[4]==1){ echo "checked";}?>>
                                </div>
                                <div class="col-sm-1">
                                  <input class="form-check-input" type="checkbox" value="" id="monday_checkbox" name="fri_checkbox2" <?php if($dataw[11]==1){ echo "checked";}?>>
                                </div>
                                <div class="col-sm-1">
                                  <input class="form-check-input" type="checkbox" value="" id="monday_checkbox" name="fri_checkbox3" <?php if($dataw[18]==1){ echo "checked";}?>>
                                </div>
                                <div class="col-sm-1">
                                  <input class="form-check-input" type="checkbox" value="" id="monday_checkbox" name="fri_checkbox4" <?php if($dataw[25]==1){ echo "checked";}?>>
                                </div>
                                <div class="col-sm-1">
                                  <input class="form-check-input" type="checkbox" value="" id="monday_checkbox" name="fri_checkbox5" <?php if($dataw[32]==1){ echo "checked";}?>>
                                </div>
                              </div>
                              <div class="row text-center pt-2">
                                <div class="col-sm-2">
                                  <h6>Saturday</h6>
                                </div>
                                <div class="col-sm-2">
                                  <input type="time" class="form-control" name="saturday_start" id="saturday_start" value="<?= date("H:i",strtotime($dayStartTimes[5]));?>"/>
                                </div>
                                <div class="col-sm-2">
                                  <input type="time" class="form-control" name="saturday_end" id="saturday_end" value="<?= date("H:i",strtotime($dayEndTimes[5]));?>"/>
                                </div>
                                <div class="col-sm-1">
                                  <input class="form-check-input" type="checkbox" value="" id="monday_checkbox" name="sat_checkbox1" <?php if($dataw[5]==1){ echo "checked";}?>>
                                </div>
                                <div class="col-sm-1">
                                  <input class="form-check-input" type="checkbox" value="" id="monday_checkbox" name="sat_checkbox2" <?php if($dataw[12]==1){ echo "checked";}?>>
                                </div>
                                <div class="col-sm-1">
                                  <input class="form-check-input" type="checkbox" value="" id="monday_checkbox" name="sat_checkbox3" <?php if($dataw[19]==1){ echo "checked";}?>>
                                </div>
                                <div class="col-sm-1">
                                  <input class="form-check-input" type="checkbox" value="" id="monday_checkbox" name="sat_checkbox4" <?php if($dataw[26]==1){ echo "checked";}?>>
                                </div>
                                <div class="col-sm-1">
                                  <input class="form-check-input" type="checkbox" value="" id="monday_checkbox" name="sat_checkbox5" <?php if($dataw[33]==1){ echo "checked";}?>>
                                </div>
                              </div>
                              <div class="row text-center pt-2">
                                <div class="col-sm-2">
                                  <h6>Sunday</h6>
                                </div>
                                <div class="col-sm-2">
                                  <input type="time" class="form-control" name="sunday_start" id="sunday_start" value="<?= date("H:i",strtotime($dayStartTimes[6]));?>"/>
                                </div>
                                <div class="col-sm-2">
                                  <input type="time" class="form-control" name="sunday_end" id="sunday_end" value="<?= date("H:i",strtotime($dayEndTimes[6]));?>"/>
                                </div>
                                <div class="col-sm-1">
                                  <input class="form-check-input" type="checkbox" value="" id="monday_checkbox" name="sun_checkbox1" <?php if($dataw[6]==1){ echo "checked";}?>>
                                </div>
                                <div class="col-sm-1">
                                  <input class="form-check-input" type="checkbox" value="" id="monday_checkbox" name="sun_checkbox2" <?php if($dataw[13]==1){ echo "checked";}?>>
                                </div>
                                <div class="col-sm-1">
                                  <input class="form-check-input" type="checkbox" value="" id="monday_checkbox" name="sun_checkbox3" <?php if($dataw[20]==1){ echo "checked";}?>>
                                </div>
                                <div class="col-sm-1">
                                  <input class="form-check-input" type="checkbox" value="" id="monday_checkbox" name="sun_checkbox4" <?php if($dataw[27]==1){ echo "checked";}?>>
                                </div>
                                <div class="col-sm-1">
                                  <input class="form-check-input" type="checkbox" value="" id="monday_checkbox" name="sun_checkbox5" <?php if($dataw[34]==1){ echo "checked";}?>>
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

<!-- Modal -->
<div class="modal fade" id="addShiftModal" tabindex="-1" role="dialog" aria-labelledby="addShiftModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addShiftModalLabel">Add Shift</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="<?php echo base_url('User/addShift')?>" method="POST">
        <div class="modal-body">
          <div class="row">
            <div class="col">
              <label for="shift_name">Shift Name</label>
              <input type="text" name="shift_name" id="shift_name" class="form-control" placeholder="Enter Shift Name" required>
            </div>
          </div>
          <div class="row pt-2">
            <div class="col-sm-6">
              <label for="shift_start">Shift Start Time</label>
              <input type="time" name="shift_start" id="shift_name" class="form-control" value="09:00">
            </div>
            <div class="col-sm-6">
              <label for="shift_end">Shift End Time</label>
              <input type="time" name="shift_end" id="shift_end" class="form-control" value="18:00">
            </div>
          </div>
          <div class="row text-center pt-4">
            <div class="col-sm-2">
              <h6>Day</h6>
            </div>
            <div class="col-sm-4">
              <h6>Shift Start Time</h6>
            </div>
            <div class="col-sm-4">
              <h6>Shift End Time</h6>
            </div>
            <div class="col-sm-2">
              <h6>WeeklyOff</h6>
            </div>
          </div>
          <div class="row text-center pt-2">
            <div class="col-sm-2">
              <h6>Monday</h6>
            </div>
            <div class="col-sm-4">
              <input type="time" class="form-control" name="monday_start" id="monday_start" value="09:00"/>
            </div>
            <div class="col-sm-4">
              <input type="time" class="form-control" name="monday_end" id="monday_end" value="18:00"/>
            </div>
            <div class="col-sm-2">
              <input class="form-check-input" type="checkbox" value="" id="monday_checkbox" name="monday_checkbox">
            </div>
          </div>
          <div class="row text-center pt-2">
            <div class="col-sm-2">
              <h6>Tuesday</h6>
            </div>
            <div class="col-sm-4">
              <input type="time" class="form-control" name="tuesday_start" id="tuesday_start" value="09:00"/>
            </div>
            <div class="col-sm-4">
              <input type="time" class="form-control" name="tuesday_end" id="tuesday_end" value="18:00"/>
            </div>
            <div class="col-sm-2">
              <input class="form-check-input" type="checkbox" value="" id="tuesday_checkbox" name="tuesday_checkbox">
            </div>
          </div>
          <div class="row text-center pt-2">
            <div class="col-sm-2">
              <h6>Wednesday</h6>
            </div>
            <div class="col-sm-4">
              <input type="time" class="form-control" name="wednesday_start" id="wednesday_start" value="09:00"/>
            </div>
            <div class="col-sm-4">
              <input type="time" class="form-control" name="wednesday_end" id="wednesday_end" value="18:00"/>
            </div>
            <div class="col-sm-2">
              <input class="form-check-input" type="checkbox" value="" id="wednesday_checkbox" name="wednesday_checkbox">
            </div>
          </div>
          <div class="row text-center pt-2">
            <div class="col-sm-2">
              <h6>Thursday</h6>
            </div>
            <div class="col-sm-4">
              <input type="time" class="form-control" name="thursday_start" id="thursday_start" value="09:00"/>
            </div>
            <div class="col-sm-4">
              <input type="time" class="form-control" name="thursday_end" id="thursday_end" value="18:00"/>
            </div>
            <div class="col-sm-2">
              <input class="form-check-input" type="checkbox" value="" id="thursday_checkbox" name="thursday_checkbox">
            </div>
          </div>
          <div class="row text-center pt-2">
            <div class="col-sm-2">
              <h6>Friday</h6>
            </div>
            <div class="col-sm-4">
              <input type="time" class="form-control" name="friday_start" id="friday_start" value="09:00"/>
            </div>
            <div class="col-sm-4">
              <input type="time" class="form-control" name="friday_end" id="friday_end" value="18:00"/>
            </div>
            <div class="col-sm-2">
              <input class="form-check-input" type="checkbox" value="" id="friday_checkbox" name="friday_checkbox">
            </div>
          </div>
          <div class="row text-center pt-2">
            <div class="col-sm-2">
              <h6>Saturday</h6>
            </div>
            <div class="col-sm-4">
              <input type="time" class="form-control" name="saturday_start" id="saturday_start" value="09:00"/>
            </div>
            <div class="col-sm-4">
              <input type="time" class="form-control" name="saturday_end" id="saturday_end" value="18:00"/>
            </div>
            <div class="col-sm-2">
              <input class="form-check-input" type="checkbox" value="" id="saturday_checkbox" name="saturday_checkbox">
            </div>
          </div>
          <div class="row text-center pt-2">
            <div class="col-sm-2">
              <h6>Sunday</h6>
            </div>
            <div class="col-sm-4">
              <input type="time" class="form-control" name="sunday_start" id="sunday_start" value="09:00"/>
            </div>
            <div class="col-sm-4">
              <input type="time" class="form-control" name="sunday_end" id="sunday_end" value="18:00"/>
            </div>
            <div class="col-sm-2">
              <input class="form-check-input" type="checkbox" value="" id="sunday_checkbox" name="sunday_checkbox">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Add Shift</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">Edit Department</h4>
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
  var add_d_data = "add_depart";
  $.ajax({
      type: "POST",
      url: "User/getajaxRequest",
      data: {data,add_d_data},
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
