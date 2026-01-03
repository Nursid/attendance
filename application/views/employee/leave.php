
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
 <?php $this->load->view('employee/staff_menu')?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
         
          <div class="col-sm-12">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Leave</li>
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
                <h3 class="card-title">Add Leave</h3><br>
                <span style="color: red"><?php echo $this->session->flashdata('msg');?></span>
              </div>
              <?php 
			 
				$empId=$this->web->session->userdata('login_id');
				// $loginId=$this->session->userdata('empCompany');
				$userCmp = $this->app->getUserCompany($empId );
			  $loginId = $userCmp['business_id'];	
				
				 $leaves = $this->web->getactEmpLeaves($empId);
				 $leavesall = $this->web->getEmpLeaves($empId);


					        $open_leaves = $this->web->getOpenLeave($loginId,$empId); 
							if($open_leaves){
						$open_date = $open_leaves['open_date'];
						$close_date = $open_leaves['close_date'];
						$cl = $open_leaves['cl'];
						$pl = $open_leaves['pl'];
						$el = $open_leaves['el'];
						$sl = $open_leaves['sl'];
						$other = $open_leaves['other'];
						$rh = $open_leaves['rh'];
						$hl = $open_leaves['hl'];
						$comp_off = $open_leaves['comp_off'];
						$limit_type = $open_leaves['limit_type'];
						$fixed_limit = $open_leaves['fixed_limit'];
						$carry = $open_leaves['carry'];
					}
					//if($open_date!=""){
					//	$open_date = date('d-m-Y',$open_date);
				//	}
				//	if($close_date!=""){
					//	$close_date = date('d-m-Y',$close_date);
				//	}       
								$balanceCl = $cl;
                                    $balancePl = $pl;
                                  $balanceEl = $el;
                                    $balanceSl = $sl;
                                   $balanceOther = $other;
 foreach($leaves as $leave){
                                      if($leave->from_date>=$open_date && $leave->from_date<=$close_date){
                                        if($leave->type=="cl"){
                                          $balanceCl= $balanceCl-$leave->half_day;
                                        }else if($leave->type=="pl"){
                                          $balancePl= $balancePl-$leave->half_day;
                                        }else if($leave->type=="el"){
                                          $balanceEl= $balanceEl-$leave->half_day;
                                        }else if($leave->type=="sl"){
                                         $balanceSl= $balanceSl-$leave->half_day;
                                        }else if($leave->type=="other"){
                                         $balanceOther= $balanceOther-$leave->half_day;
                                        }
                                      }
                                    }
				
                
                $month = date("Y-m");
                $yearName  = date('Y', strtotime($month));
		$monthName = date('m', strtotime($month));
		 $d = (cal_days_in_month(CAL_GREGORIAN,date('m',strtotime($month)),date('Y',strtotime($month))))-1;
		$start_time = strtotime(date("d-m-Y 00:00:00",strtotime($yearName."-".$monthName."-01")));
		$end_time = strtotime(date("d-m-Y 23:59:59",strtotime($yearName."-".$monthName."-01"))." +".$d." days"); 
         
          
           
           $data['usedleavetotal'] = $this->db->select_sum('leaves.half_day')
                                              ->from('leaves')
                                              ->where('leaves.status',1)
                                              ->where("uid",$empId)
                                              ->where("bid",$loginId)
                                               ->where('leaves.type!=',"unpaid")
                                              ->where('leaves.type!=',"comp_off")
                                               // ->where("DATE_FORMAT(from_date,%Y-%m)",$monthName)
                                              ->where("from_date >=",$open_date )
                                              ->where("from_date <=",$close_date)
                                              ->get()
                                              ->row();        
                
               $usedleavetotalY=$data['usedleavetotal'] ? $data['usedleavetotal']->half_day :0;  
                $openleavemonth=date('m', $open_date);
                $monthdiff=$monthName-$openleavemonth+1;
               $opening_leave= ($fixed_limit* $monthdiff)+$other-$usedleavetotalY; 
                
           
                
			   ?>
             <form action="<?php echo base_url('User/add_staffleave')?>" method="post">
              <div class="card-body">
                <div class="row">
                  <div class="from-group col-md-2">
                    <label for="depart">Leave From Date</label>
                  <!--  <input type="date" class="form-control" name="from_date" placeholder="Select Date" id="from_date" required>-->
                     <input type="date"  name="from_date" placeholder="Select Date" id="start_date" value="<?php echo $start_date; ?>" class="form-control" max="<?php echo $end_date; ?>" onchange="startChange(event);" required>
                  </div>

                  <div class="from-group col-md-2">
                    <label for="pfix">Leave To Date</label>
                  <!--  <input type="date" class="form-control" name="to_date" id="to_date" placeholder="Select Date" required>-->
                    <input type="date" name="to_date" id="end_date" placeholder="Select Date"   value="<?php echo $end_date; ?>"class="form-control"  min="<?php echo $start_date;?>" onchange="endChange(event);" required>
                  </div>
                  
                
                 <div class="from-group col-md-2"> 
                   
                  
                   
                  <label for="remark">Leave Type</label>
                    <select name="type" class="form-control"  id="type">
                                    <option value="cl">CL: <?php echo $balanceCl; ?> </option>
                                    <option value="pl">PL: <?php echo $balancePl; ?></option>
                                    <option value="el">EL: <?php echo $balanceEl; ?></option>
                                    <option value="sl">SL: <?php echo $balanceSl; ?></option>
                                    <option value="other">Carry Balance: <?php echo $balanceOther; ?></option>
                                    
                                </select>
                                </div>
                                <div class="from-group col-md-1"> 
                                
                                 <label for="days">days</label>
                                 <?php         ?>
                           <input type="number" name="days"  min="0" step="0.5" id="days" max= "<?php echo $opening_leave ?>"  class="form-control">  
                           
                           </div>  
                           <div class="from-group col-md-3"> 
                                 <label for="days"> <br>Total Balance Leave </label> 
                                 
                                <?php   // $limitf= $user['opening_leave']+$other-$user['usedleavem'];
                                 echo ": ". $opening_leave;  ?> 
                                 
                                 <br>
                                <!-- <label for="days"> Monthly Limit</label> -->
                                
                                 <?php      //  echo ": " .$fixed_limit; ?>
                          
                           
                           </div>    
                      
                            
                                         
                        
                  <div class="from-group col-md-5">
                    <label for="remark">Reason</label>
                    <input type="textarea" class="form-control" name="reason" placeholder="Enter reason" id="reason" required>
                    <input type="hidden" name="uid" value="<?php echo $empId;?>">
                     <input type="hidden" name="bid" value="<?php echo $loginId; ?>">
                      <input type="hidden" name="status" value="2">
                    
                  </div>


                  <div class="from-group col-md-5">
                  <button class=" btn btn-success mt-4 mx-auto">Add Now</button>
                  </div>
                </div>
              </div>
              </form> 
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
                <h3 class="card-title">History</h3>
               
              </div>
              <div class="card-body">
               <?php 
                           
                                   
                                  ?>
                                  <div class="row">
                                    <div class="col-sm-2">CL: <?= $balanceCl?></div>
                                    <div class="col-sm-2">PL: <?= $balancePl?></div>
                                    <div class="col-sm-2">EL: <?= $balanceEl?></div>
                                    <div class="col-sm-2">SL: <?= $balanceSl?></div>
                                    <div class="col-sm-3">Carry Balance: <?= $balanceOther?></div>
                                  </div>
                                   <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                      <tr>
                                        <th>SNo.</th>
                                        <th>From Date</th>
                                        <th>To Date</th>
                                        <th>No.of Days</th>
                                        <th>Type</th>
                                        <th>Reason</th>
                                        <th>Status</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <?php
                                      $leavecount=1;
                                      foreach($leavesall as $leave){
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
                                          <td> <?php if($leave->status==1){
                                             echo "Verified"; 
                                              
                                          }elseif($leave->status==3)
                                          {
                                           echo "Cancelled";    
                                          } else {
                                           echo "Pending";     
                                          }
                                          
                                         ?> 
                                          
                                          </td>
                                        </tr>
                                      <?php }?>
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
<script>
  function delete_device(id){
    $.ajax({
      type: "POST",
      url: "User/delete_device",
      data: {id},
     success: function(){
    $('#delete'+id).text("deleted");
    }
	
    })
  }
</script>
</body>
</html>
