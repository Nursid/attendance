<?php

date_default_timezone_set('Asia/Kolkata');
?>
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

       <!--     <div class="col-sm-12">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Open Leave</li>
              </ol>
            </div>-->
          </div>
        </div><!-- /.container-fluid -->
      </section>
      <?php
      if($this->session->userdata()['type']=='B' || $this->session->userdata()['type']=='P')
      {
        if ($this->session->userdata()['type'] == 'P') {
          //$busi = $this->web->getBusinessbyUser($this->web->session->userdata('login_id'));
          $bid = $this->session->userdata('empCompany');
          $role=$this->web->getRollbyid($this->web->session->userdata('login_id'),$bid);
        } else {
          $bid = $this->web->session->userdata('login_id');
        }
		  ?>
        <!-- Main content -->
        <section class="content">
        <?php
        if($this->session->userdata()['type']=='B' || $role[0]->leave_manage=="1" || $role[0]->type=="1"){?>
          <div class="container-fluid">
            <div class="row">
              <!-- left column -->
              <div class="col-md-12">
                <div class="card card-danger">
                  <div class="card-header">
                    <h3 class="card-title">Leave Balance Report</h3>
                  </div>
                     
                 <div align="right">
                    <button type="button" class="btn btn-primary m-4" data-toggle="modal" data-target="#leaveModal">Open Multiple Leave</button>
                  </div>
                  <div class="modal fade" id="leaveModal" tabindex="-1" role="dialog" aria-labelledby="leaveModal" aria-hidden="true">
                    <div class="modal-dialog modal-md" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="leaveModalLabel">Open Leave</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <form action="<?php echo base_url('User/update_open_leave_all')?>" method="POST">
                          <div class="modal-body">
                            <div class="row">
                              <div class="col">
                                <label for="open_date">Opening Date</label>
                                <input type="date" name="open_date" id="open_date"  class="form-control">
                              </div>
                              <div class="col">
                                <label for="close_date">Closing Date</label>
                                <input type="date" name="close_date" id="close_date" class="form-control">
                              </div>
                            </div>
                            <br>
                             <div class="row text-center">
                                            <div class="col">
                                              <label for="cl">CL</label>
                                              <input type="number" name="cl" id="cl" value="<?= isset($user) ? $user['cl']:0;?>" min="0" class="form-control">
                                            </div>
                                            <div class="col">
                                              <label for="el">EL</label>
                                              <input type="number" name="el" id="el" value="<?= isset($user) ? $user['el']:0;?>" min="0" class="form-control">
                                            </div>
                                            <div class="col">
                                              <label for="sl">SL</label>
                                              <input type="number" name="sl" id="sl" value="<?= isset($user) ? $user['sl']:0;?>" min="0" class="form-control">
                                            </div>
                                            <div class="col">
                                              <label for="pl">PL</label>
                                              <input type="number" name="pl" id="pl" value="<?= isset($user) ? $user['pl']:0;?>" min="0" class="form-control">
                                            </div>
                                            </div>
                                             <div class="row text-center">
                                            
                                            
                                            <div class="col">
                                              <label for="other">Carry Balance</label>
                                              <input type="number" name="other" id="other" value="<?= isset($user) ? $user['other']:0;?>" min="0" class="form-control">
                                            </div>
                                          </div>
                                          <br> <br>
                                           <div class="row text-center">
                                           <div class="col">
                                              <label for="comp">Limit Type</label>
                                              <select name="limit_type" class="form-control" id="limit_type">
                                   <option value="1"> <?php if(isset($user) && $user['limit_type']==0){echo "Monthly";} else {echo "Quaterly";} ?></option>
                                    <option value="0">Monthly</option>
                                    <option value="1">Quaterly</option>
                                </select>
                                            </div>
                                            <div class="col">
                                              <label for="limit">Fixed Limit</label>
                                              <input type="number" name="fixed_limit" id="fixed_limit" min="0" step="0.25" value="<?= isset($user) ? $user['fixed_limit']:0;?>"  class="form-control">
                                            </div>
                                            
                                            <div class="col">
                                              <label for="other">Carry Farward</label>
                                              <br> 
                                          
                                 <input class="form-check-input" type="checkbox" value="" id="checkbox" name="carry" <?php if(isset($user) && $user['carry']==1){ echo "checked";}?> >
                                            </div>
                                           
                                           
                                           </div>
                           
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success">Save</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>     
                     
                     
                     
               <?php
  
    $month = isset($_GET['getDate']) ? $_GET['getDate'] : date("Y-m");
  ?> 
                
                 <div class="card-body">
                <div class="row">
                  
                  <div class="col-sm-2">
                    <input type="month" title="Start Date" placeholder="Date From" value="<?php echo $month; ?>" onchange="setDate()" id="setDate">
                      <!-- <button type="button" class="btn btn-primary m-4" data-toggle="modal" data-target="#allCtcModal">All CTC</button> -->
                  </div>

                </div>
              <div align="right">
                          <input type="button"  class="btn btn-primary" onClick="exportData()" value="Export To Excel" />
                        <!-- <input type="button"   class="btn btn-primary" id="btnExport" value="Export To Pdf" onclick="exportPDF()" />-->
                          
                        </div>
                       
                  </div>
                  
                  
                  
                  <!-- Modal -->
                 
                  
                  
                  <div class="row">
                      <div class="col-md-12">
            <div class="card card-primary">
                  <div class="card-body">
                    
                     
                        <table id="example1" class="table table-bordered table-striped table-responsive">
                          <thead>
                            <tr>
                              <th>SNo.</th>
                              <th>Empcode</th>
                              <th>Name</th>
                              <th>Caryy Bal</th>
                              <th>Opening Date</th>
                              <th>Closing Date</th>
                              <th>Opening Leave</th>
                              <th>Total Used </th>
                              <th>yearly Balance</th>
                              <th>Monthly Opening</th>
                             <!-- <th>Entitlement</th>-->
                              <th>Monthly Used</th>
                              <th>Monthly Balance</th>
                              <th>Add Leave</th>
                              <th>Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                            $count=1;
                            foreach($users as $user){
                              $open_date = date("Y-m");
                              $close_date = date("Y-m");
                              if($user['open_date']!=""){
                                $open_date=date("Y-m",strtotime($user['open_date']));
                              }
                              if($user['close_date']!=""){
                                $close_date=date("Y-m",strtotime($user['close_date']));
                              }
                              $totalLeavesY = $user['cl']+$user['pl']+$user['el']+$user['sl'];
                               $totalsLeaves = $totalLeavesY+$user['carry_bal'];
                               $usedLeaves = 0;
							  
                              foreach($user['leaves'] as $leave){
                                //if($leave->from_date>=$start_time && $leave->from_date<=$end_time){
                                  if($leave->type!="" && $leave->type!="unpaid" && $leave->type!="comp_off" && $leave->status==1 ){
									  $half_day=!empty($leave->half_day) ? $leave->half_day:0;
                                    $usedLeaves=$usedLeaves+$half_day;
                                  }
							//	}
								 
                              }
							 // $opening_bal=$user['fixed_limit']*3;
							  $closing_bal=$user['opening_leave']-$user['usedleavem']+$user['other'];
                              ?>
                              <tr>
                                <td><?= $count++;?></td>
                                <td><?= $user['emp_code'];?></td>
                                <td><?= $user['name'];?></td>
                                <td><?= $user['carry_bal'];?></td>
                                <td><?= $user['open_date'];?></td>
                                <td><?= $user['close_date'];?></td>
                                <td><?= $totalLeavesY;?></td>
                                <td><?= $user['usedleavetotalY'];?></td>
                                <td><?= $totalsLeaves-$user['usedleavetotalY'];?></td>
                              <td><?=  $user['opening_leave']+$user['other'];?></td>
                                <!--<td><?= $user['fixed_limit'];
								?></td>-->
                                <td><?= $user['usedleavem'];?></td>
                                <td><?= $closing_bal;?></td>
                                
                                
                                
                                  <td>
                                   <button type="button" class="btn btn-danger btn-sm mt-4 mx-auto" data-toggle="modal" data-target="#addModal<?php echo $user['user_id'];?>">Add Leave</button>
                                </td>
                                <td>
                                  <button type="button" class="btn btn-info btn-sm mt-4 mx-auto" data-toggle="modal" data-target="#historyModal<?php echo $user['user_id'];?>">History</button>
                                  <button type="button" class="btn btn-success btn-sm mt-4 mx-auto" data-toggle="modal" data-target="#leaveModal<?php echo $user['user_id'];?>">Open Leave</button>
                                  </td>
                                

                                <!-- Modal -->
                                <div class="modal fade" id="leaveModal<?php echo $user['user_id'];?>" tabindex="-1" role="dialog" aria-labelledby="leaveModal<?php echo $user['user_id'];?>" aria-hidden="true">
                                  <div class="modal-dialog modal-md" role="document">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <h5 class="modal-title" id="leaveModalLabel<?php echo $user['user_id'];?>">Open Leave</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                        </button>
                                      </div>
                                      <form action="<?php echo base_url('User/update_open_leave')?>" method="POST">
                                        <div class="modal-body">
                                          <div class="row">
                                            <div class="col">
                                              <label for="open_date">Opening Date</label>
                                              <input type="month" name="open_date" id="open_date"  value="<?php echo $open_date; ?>" class="form-control">
                                            </div>
                                            <div class="col">
                                              <label for="close_date">Closing Date</label>
                                              <input type="month" name="close_date" id="close_date"  value="<?php echo $close_date; ?>"class="form-control">
                                            </div>
                                            <input type="text" value="<?php echo $user['user_id']; ?>" name="user_id" hidden>
                                          </div>
                                          <br>
                                          <div class="row text-center">
                                            <div class="col">
                                              <label for="cl">CL</label>
                                              <input type="number" name="cl" id="cl" value="<?= $user['cl'];?>" min="0" class="form-control">
                                            </div>
                                            <div class="col">
                                              <label for="el">EL</label>
                                              <input type="number" name="el" id="el" value="<?= $user['el'];?>" min="0" class="form-control">
                                            </div>
                                            <div class="col">
                                              <label for="sl">SL</label>
                                              <input type="number" name="sl" id="sl" value="<?= $user['sl'];?>" min="0" class="form-control">
                                            </div>
                                            <div class="col">
                                              <label for="pl">PL</label>
                                              <input type="number" name="pl" id="pl" value="<?= $user['pl'];?>" min="0" class="form-control">
                                            </div>
                                            </div>
                                             <div class="row text-center">
                                          
                                            <div class="col">
                                              <label for="hl">HL</label>
                                              <input type="number" name="hl" id="hl" value="<?= $user['hl'];?>" min="0" class="form-control">
                                            </div>
                                            <div class="col">
                                              <label for="rh">RH</label>
                                              <input type="number" name="rh" id="rh" value="<?= $user['rh'];?>" min="0" class="form-control">
                                            </div>
                                            <div class="col">
                                              <label for="comp">Comp_Off</label>
                                              <input type="number" name="comp_off" id="comp" value="<?= $user['comp_off'];?>" min="0" class="form-control">
                                            </div>
                                            <div class="col">
                                              <label for="other">Carry Balance</label>
                                              <input type="number" name="other" id="other" value="<?= $user['other'];?>" min="0"  class="form-control">
                                            </div>
                                          </div>
                                          <br> <br>
                                           <div class="row text-center">
                                           <div class="col">
                                              <label for="comp">Limit Type</label>
                                              <select name="limit_type" class="form-control" id="limit_type">
                                   <option value=""<?= $user['limit_type'];?>""> <?php if($user['limit_type']==0){echo "Monthly";} else {echo "Quaterly";} ?></option>-->
                                    <option value="0">Monthly</option>
                                    <option value="1">Quaterly</option>
                                </select>
                                            </div>
                                            <div class="col">
                                              <label for="limit">Fixed Limit</label>
                                              <input type="number" name="fixed_limit"  min="0" step="0.25" id="fixed_limit" value="<?= $user['fixed_limit'];?>"  class="form-control">
                                            </div>
                                            
                                            <div class="col">
                                              <label for="other">Carry Farward</label>
                                              <br> 
                                          
                                 <input class="form-check-input" type="checkbox" value="" id="checkbox" name="carry" <?php if($user['carry']==1){ echo "checked";}?> >
                                            </div>
                                           
                                           
                                           </div>
                                          
                                          
                                          
                                          
                                        </div>
                                        <div class="modal-footer">
                                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                          <button type="submit" class="btn btn-success">Save</button>
                                        </div>
                                      </form>
                                    </div>
                                  </div>
                                </div>
                                <!-- Modal -->
                              </tr>

                      <?php }?>

                          </tbody>
                        </table>
                        <?php
                        foreach($users as $user){?>
                          <!-- Modal -->
                          <div class="modal fade" id="historyModal<?php echo $user['user_id'];?>" tabindex="-1" role="dialog" aria-labelledby="historyModal<?php echo $user['user_id'];?>" aria-hidden="true">
                            <div class="modal-dialog modal-md" role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title" id="historyModalLabel<?php echo $user['user_id'];?>">History</h5>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                                <div class="modal-body">
                                  <h6>Balance Leave:</h6>
                                  <?php 
                                    $balanceCl = $user['cl'];
                                    $balancePl = $user['pl'];
                                    $balanceEl = $user['el'];
                                    $balanceSl = $user['sl'];
                                    $balanceOther = $user['other'];

                                    foreach($user['leaves'] as $leave){
                                      if($leave->date_time>=$user['open_date'] && $leave->date_time<=$user['close_date']){
                                        if($leave->type=="cl"){
                                          $balanceCl= (!empty($balanceCl)?$balanceCl:0) - (!empty($leave->half_day) ? $leave->half_day : 0);
                                        }else if($leave->type=="pl"){
                                          $balancePl= (!empty($balancePl)?$balancePl:0) - (!empty($leave->half_day) ? $leave->half_day : 0);
                                        }else if($leave->type=="el"){
                                          $balanceEl= (!empty($balanceEl)?$balanceEl:0) - (!empty($leave->half_day) ? $leave->half_day : 0);
                                        }else if($leave->type=="sl"){
                                         $balanceSl= (!empty($balanceSl)?$balanceSl:0) - (!empty($leave->half_day) ? $leave->half_day : 0);
                                        }else if($leave->type=="other"){
                                         $balanceOther= (!empty($balanceOther)?$balanceOther:0) - (!empty($leave->half_day) ? $leave->half_day : 0);
                                        }
                                      }
                                    }
                                  ?>
                                  <div class="row">
                                    <div class="col-sm-2">CL: <?= $balanceCl?></div>
                                    <div class="col-sm-2">PL: <?= $balancePl?></div>
                                    <div class="col-sm-2">EL: <?= $balanceEl?></div>
                                    <div class="col-sm-2">SL: <?= $balanceSl?></div>
                                    <div class="col-sm-3">Carry Bal: <?= $balanceOther?></div>
                                  </div>
                                  <table class="table table-responsive">
                                    <thead>
                                      <tr>
                                        <th>SNo.</th>
                                        <th>From Date</th>
                                        <th>To Date</th>
                                        <th>No.of Days</th>
                                        <th>Type</th>
                                        <th>Reason</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <?php
                                      $leavecount=1;
                                      foreach($user['leaves'] as $leave){
                                        $from_date_leave=date_create(date("Y-m-d",$leave->from_date));
                  											$to_date_leave=date_create(date("Y-m-d",$leave->to_date));
                  											$leave_diff=date_diff($from_date_leave,$to_date_leave);
                  											$leave_days = $leave_diff->format("%a");
                  											$leave_days++;
                                        ?>
                                        <tr>
                                          <td><?= $leavecount++;?></td>
                                          <td><?= date('d-m-Y',$leave->from_date);?></td>
                                          <td><?= date('d-m-Y',$leave->to_date);?></td>
                                          <td><?= $leave->half_day;?></td>
                                          <td><?= $leave->type;?></td>
                                          <td><?= $leave->reason;?></td>
                                        </tr>
                                      <?php }?>
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
                  
                  
                  
                 <?php
                        foreach($users as $user){?>
                          <!-- Modal -->
                          <div class="modal fade" id="addModal<?php echo $user['user_id'];?>" tabindex="-1" role="dialog" aria-labelledby="historyModal<?php echo $user['user_id'];?>" aria-hidden="true">
                            <div class="modal-dialog modal-md" role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title" id="historyModalLabel<?php echo $user['user_id'];?>">Add Leave</h5>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                                <div class="modal-body">
                                  
                                  
                                  <form action="<?php echo base_url('User/add_leave')?>" method="post">
              <div class="card-body">
                <div class="row">
                  <div class="from-group col-md-5">
                    <label for="depart">Leave From Date</label>
                  <!--  <input type="date" class="form-control" name="from_date" placeholder="Select Date" id="from_date" required>-->
                     <input type="date"  name="from_date" placeholder="Select Date" id="start_date" class="form-control" onchange="startChange(event);" required>
                  </div>

                  <div class="from-group col-md-5">
                    <label for="pfix">Leave To Date</label>
                  <!--  <input type="date" class="form-control" name="to_date" id="to_date" placeholder="Select Date" required>-->
                    <input type="date" name="to_date" id="end_date" placeholder="Select Date" class="form-control" onchange="endChange(event);" required>
                  </div>
                  
                
                 <div class="from-group col-md-3"> 
                   
                   <?php         
                                    $balanceCl = $user['cl'];
                                    $balancePl = $user['pl'];
                                    $balanceEl = $user['el'];
                                    $balanceSl = $user['sl'];
                                    $balanceOther = $user['other'];

                                    foreach($user['leaves'] as $leave){
                                      if($leave->date_time>=$user['open_date'] && $leave->date_time<=$user['close_date']){
                                        if($leave->type=="cl"){
                                         $balanceCl= $balanceCl= (!empty($balanceCl)?$balanceCl:0) - (!empty($leave->half_day) ? $leave->half_day : 0);
                                        }else if($leave->type=="pl"){
                                         $balancePl=$balancePl= (!empty($balancePl)?$balancePl:0) - (!empty($leave->half_day) ? $leave->half_day : 0);
                                        }else if($leave->type=="el"){
                                           $balanceEl=$balanceEl= (!empty($balanceEl)?$balanceEl:0) - (!empty($leave->half_day) ? $leave->half_day : 0);
                                        }else if($leave->type=="sl"){
                                          $balanceSl=$balanceSl= (!empty($balanceSl)?$balanceSl:0) - (!empty($leave->half_day) ? $leave->half_day : 0);
                                        }else if($leave->type=="other"){
                                          $balanceOther=$balanceOther= (!empty($balanceOther)?$balanceOther:0) - (!empty($leave->half_day) ? $leave->half_day : 0);
                                        }
                                      }
                                    }
                                  ?> 
   
                  <label for="remark">Leave Type</label>
                    <select name="type" class="form-control"  id="type">
                                    <option value="cl">CL: <?php echo $balanceCl; ?> </option>
                                    <option value="pl">PL: <?php echo $balancePl; ?></option>
                                    <option value="el">EL: <?php echo $balanceEl; ?></option>
                                    <option value="sl">SL: <?php echo $balanceSl; ?></option>
                                   <!-- <option value="lop">LOP</option>
                                    <option value="rh">RH</option>--->
                                    
                                    <option value="other">Carry Bal: <?php echo $balanceOther; ?></option>
                                    <option value="comp_off">Comp Off</option>
                                    <option value="ul">Unpaid Leave</option>
                                </select>
                                </div>
                                <div class="from-group col-md-3"> 
                                 <label for="days">No of days</label>
                                 <?php         $fixed_limit = $user['fixed_limit']; ?>
                           <input type="number" name="days"  min="0" step="0.5" id="days" class="form-control">  
                           
                           </div> 
                           
                            <div class="from-group col-md-5"> 
                                 <label for="days"> <br>Max Leave Limit</label> 
                                 
                                 <?php    $limitf= $user['opening_leave']+$user['other']-$user['usedleavem'];
                                 echo ": ".$limitf;  ?> 
                                 <br>
                               <!--  <label for="days"> Monthly Limit</label> -->
                                
                                 <?php      //  echo ": " .$user['fixed_limit']; ?>
                          
                           
                           </div>  
                           
                           
                      
                             <!--    <div class="row">
                                    <div class="from-group col-md-3">
                                    <label for="limit">CL(<?= $balanceCl?>)
                                    </label>
                                              <input type="number" name="cl"  min="0" step="0.25" id="" max="<?= $balanceCl;?>"  class="form-control">
                                           </div>  
                                             <div class="from-group col-md-3"> 
                                              <label for="limit">PL(<?= $balancePl?>)
                                    </label>
                                              <input type="number" name="cl"  min="0" step="0.25" id="" max="<?= $balanceCl;?>"  class="form-control">
                                           </div>
                                           <div class="from-group col-md-3">
                                              <label for="limit">SL(<?= $balanceSl?>)
                                    </label>
                                              <input type="number" name="cl"  min="0" step="0.25" id="" max="<?= $balanceCl;?>"  class="form-control">
                                    </div>
                                    </div> -->
                                         
                        
                  <div class="from-group col-md-5">
                    <label for="remark">Reason</label>
                    <input type="textarea" class="form-control" name="reason" placeholder="Enter reason" id="reason" required>
                    <input type="hidden" name="uid" value="<?php echo $user['user_id'];?>">
                     <input type="hidden" name="bid" value="<?php echo $bid; ?>">
                    
                  </div>


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
                  
                  
                  
                  
                  
                  
                  </div> </div>
                    </div>
                    <!-- /.card -->
                 
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
<script src="https://cdn.datatables.net/buttons/2.3.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.print.min.js"></script>

<!-- AdminLTE for demo purposes -->
<script src="<?php echo base_url('adminassets/dist/js/demo.js')?>"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.3.0/exceljs.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>


      <script>
  $(function () {
   var table = $('#example1').DataTable({
     "responsive": false,
      "autoWidth": true,
    });
   
  });
</script>

<script>
      $(document).ready(function () {
        $('.nav-link').click(function(e) {
          $('.nav-link').removeClass('active');
          $(this).addClass("active");

        });
        // var table = $('#example1').DataTable({
        //   searching:false,
        // });
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

     function startChange(e){
      //alert(e.target.value);
      $('#end_date').attr('min', e.target.value);
	   //$('#end_date').attr('max', e.target.value);
    }
    function endChange(e){
      //alert(e.target.value);
      $('#start_date').attr('max', e.target.value);
    }
	function setDate() {

    var date_from = $("#setDate").val();
        $.ajax({
            type: "get",
            url: "<?php base_url('User/open_leave'); ?>",
            data: {'date_from': date_from},
            success: function (data) {
                window.location.href = "<?php base_url('User/open_leave'); ?>?getDate="+date_from;
            }
    });
  }
      </script>



      
    </body>
    </html>
<script>

 function exportData(){
      var wb = new ExcelJS.Workbook();
      var sh = wb.addWorksheet("Report");
      <?php 
     if (!empty($users)) {
      $sr = 1;?>
      sh.columns = [
        {header: 'SNo.', key: 'SNo', width: 10},
        {header: 'Empcode', key: 'Empcode', width: 10},
        {header: 'Name', key: 'Name', width: 15,},
		{header: 'Carry', key: 'Carry', width:10,},
        {header: 'OpeningDate', key: 'OpeningDate', width: 15,},
		{header: 'ClosingDate', key: 'ClosingDate', width: 15,},
        {header: 'OpeningLeave ', key: 'OpeningLeave', width:15,},
        {header: 'UsedTotal', key: 'UsedTotal', width:15,},
        {header: 'BalanceYearly', key: 'BalanceYealy', width:15,},
        {header: 'CurrentOpening', key: 'CurrentOpening', width: 15,},
       // {header: 'Entitlment ', key: 'Entitlment', width:10,},
        {header: 'Used', key: 'Used', width:10,},
        {header: 'CurrentClosing', key: 'CurrentClosing', width:15,}
       
      ];
     // sh.addRow(["SNo.","Empcode","Name","CTC","P","W/H","L","ED","NWD","Salary","PF","ESI","Advance","Addition","Deduction","NetPayable"]);
      <?php
       // $salaryTotalPaid = 0;
       // $salaryNetPayable = 0;
        //$salaryTotalCtc = 0;
       // $salaryTotalSalary = 0;
       // $salaryTotalDeduction = 0;
        //usort($salEmpList, function($a, $b) {
         // if(empty($a->emp_code)){
           //   return -1;
         // }elseif ($a->emp_code > $b->emp_code) {
             // return 1;
         // } elseif ($a->emp_code < $b->emp_code) {
             // return -1;
          //}
         // return 0;
       // });
        foreach($users as $user){
         // $salaryTotalPaid+=$empData->getTotalPaid;
         // $salaryNetPayable+=$empData->netPayable;
         //// $salaryTotalCtc+=$empData->ctc;
         // $salaryTotalSalary+=$empData->total;
         // $salaryTotalDeduction+=$empData->deductionAmount;
          
          $newusedleave=isset($user['usedleave'])?$user['usedleave']:0;
          $totalLeavesY = $user['cl']+$user['pl']+$user['el']+$user['sl']+$user['hl']+$user['rh']+$user['comp_off'];
           $totalsLeaves = $totalLeavesY+$user['other'];
        $closing_bal=$user['opening_leave']-$user['usedleavem']+$user['other'];
          ?>
       
       
        sh.addRow({SNo:'<?php echo $sr;?>',Empcode:'<?= $user['emp_code']; ?>',Name:'<?= $user['name'];?>',Carry:'<?= $user['other'];?>',OpeningDate:'<?= $user['open_date'];?>', ClosingDate: '<?= $user['close_date']; ?>', OpeningLeave: '<?= $totalLeavesY;?>', UsedTotal: '<?= $user['usedleavetotalY']; ?>',BalanceYealy: '<?= $totalsLeaves-$user['usedleavetotalY']; ?>', CurrentOpening: '<?= $user['opening_leave']+$user['other']; ?>',Used: '<?= $user['usedleavem']; ?>',CurrentClosing: '<?= $closing_bal; ?>'});
    
      <?php 
          echo "sh.getRow(".$sr++.").alignment = { wrapText: true,vertical: 'top',horizontal: 'center' };";
          //echo "sh.getRow(".$sr.").border = {top: {style:'thin'},left: {style:'thin'},bottom: {style:'thin'},right: {style:'thin'}};";
        }
        echo "sh.getRow(".$sr.").alignment = { wrapText: true,vertical: 'top',horizontal: 'center' };";
        //echo "sh.insertRow(1, ['$cmp_name']);";
        echo "sh.insertRow(1,'');";
       // $new_start_date = date('F Y',$salEmpList[0]->startDate);
       // $new_end_date = date('F Y',$salEmpList[0]->endDate);
        echo "sh.insertRow(2, ['Leave Report for the month of  $month']);";
        echo "sh.mergeCells('A1:Q1');";
        echo "sh.mergeCells('A2:Q2');";
        echo "sh.getRow(1).alignment = {horizontal: 'center' };";
        echo "sh.getRow(2).alignment = {horizontal: 'center' };";
        $sr+=4;
       // echo "sh.insertRow($sr,['Total CTC:$salaryTotalCtc, Total Salary:$salaryTotalSalary, Total Advance:$salaryTotalPaid, Total Deduction:$salaryTotalDeduction, Total Net Payable:$salaryNetPayable']);";
        echo "sh.mergeCells('A$sr:Q$sr');";
        echo "sh.getRow($sr).alignment = {horizontal: 'center' };";
      }
      ?>
      wb.xlsx.writeBuffer().then((data) => {
            const blob = new Blob([data], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8' });
            saveAs(blob, 'Leave Report.xlsx');
      });
  }

  
  
  
  
      

</script>
      