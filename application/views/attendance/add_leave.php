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
  <link rel="stylesheet" href="<?php echo base_url('adminassets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')?>">
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
                <li class="breadcrumb-item active">Add Leave</li>
              </ol>
            </div>
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
        if($this->session->userdata()['type']=='B' || $role[0]->leave_manage=="1"){?>
          <div class="container-fluid">
            <div class="row">
              <!-- left column -->
              <div class="col-md-12">
                <div class="card card-danger">
                  <div class="card-header">
                    <h3 class="card-title">Monthly Leave Report</h3>
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
                          <input type="button"  class="btn btn-primary" onClick="exportDatas()" value="Export To Excel" />
                         <input type="button"   class="btn btn-primary" id="btnExport" value="Export To Pdf" onclick="exportPDF()" />
                          
                        </div>
                        <br>
                 
                 
                 
                  <div class="card-body">
                    <div class="row">
                      <div class="col-lg-12 float-left">
                        <table id="example1" class="table table-bordered table-responsive">
                          <thead>
                            <tr>
                              <th>SNo.</th>
                              <th>Empcode</th>
                              <th>Name</th>
                              <th>Carry Bal</th>
                              <th>Opening Leave</th>
                             <th>Entitlment Leave</th>
                              <th>Used Leave</th>
                              <th>Closing Bal Leave</th>
                              <th>Add Leave</th>
                              <th>History</th>
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
                              $totalLeaves = $user['cl']+$user['pl']+$user['el']+$user['sl']+$user['hl']+$user['rh']+$user['comp_off'];                   
							  
							 $yearName  = date('Y', strtotime($month));
		$monthName = date('m', strtotime($month));
		 $d = (cal_days_in_month(CAL_GREGORIAN,date('m',strtotime($month)),date('Y',strtotime($month))))-1;
		$start_time = strtotime(date("d-m-Y 00:00:00",strtotime($yearName."-".$monthName."-01")));
		$end_time = strtotime(date("d-m-Y 23:59:59",strtotime($yearName."-".$monthName."-01"))." +".$d." days"); 
							  
		  //$totalsLeaves = $totalLeaves+$user['other'];
                              $usedLeaves = 0;
							  $usedLeavesot = 0;
							  $usedLeavesold = 0;
                              foreach($user['leaves'] as $leave){
                                if($leave->from_date>=$start_time && $leave->from_date<=$end_time){
                                  if($leave->type!="" && $leave->type!="unpaid" && $leave->status==1 ){
									  $half_day=$leave->half_day;
                                    $usedLeaves=$usedLeaves+$half_day;
                                  }
								}
								 if($leave->to_date<=$start_time ){
								  if($leave->type="other" && $leave->type!="unpaid" && $leave->status==1 ){
									  $half_dayo=$leave->half_day;
                                    $usedLeavesot=$usedLeavesot+$half_dayo;
								  }
								 
								 if($leave->type!="" && $leave->type!="unpaid" && $leave->status==1 ){
									  $half_dayold=$leave->half_day;
                                    $usedLeavesold=$usedLeavesold+$half_dayold;
								  } 
								  
                                }
                              }
							  $opening_bal=$user['fixed_limit']*3;
							  $closing_bal=$opening_bal-$usedLeaves;
							  
                              ?>
                              <tr>
                                <td><?= $count++;?></td>
                                <td><?= $user['emp_code'];?></td>
                                <td><?= $user['name'];?></td>
                                <td><?= $user['other']-$usedLeavesot;?></td>
                                <td><?= $opening_bal;?></td>
                                <td><?= $user['fixed_limit'];?></td>
                                <td><?= $usedLeaves;?></td>
                                <td><?= $closing_bal;?></td>
                                  <td>
                                   <button type="button" class="btn btn-danger btn-sm mt-4 mx-auto" data-toggle="modal" data-target="#addModal<?php echo $user['user_id'];?>">Add Leave</button>
                                </td>
                                <td>
                                  <button type="button" class="btn btn-info btn-sm mt-4 mx-auto" data-toggle="modal" data-target="#historyModal<?php echo $user['user_id'];?>">History</button>
                                  
                                  </td>
                                

                               
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
                     <input type="date"  name="from_date" placeholder="Select Date" id="start_date" value="<?php echo $start_date; ?>" class="form-control" max="<?php echo $end_date; ?>" onchange="startChange(event);" required>
                  </div>

                  <div class="from-group col-md-5">
                    <label for="pfix">Leave To Date</label>
                  <!--  <input type="date" class="form-control" name="to_date" id="to_date" placeholder="Select Date" required>-->
                    <input type="date" name="to_date" id="end_date" placeholder="Select Date"   value="<?php echo $end_date; ?>"class="form-control"  min="<?php echo $start_date;?>" onchange="endChange(event);" required>
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
                                         $balanceCl= $balanceCl-$leave->half_day;
                                        }else if($leave->type=="pl"){
                                         $balancePl=$balancePl-$leave->half_day;
                                        }else if($leave->type=="el"){
                                           $balanceEl=$balanceEl-$leave->half_day;
                                        }else if($leave->type=="sl"){
                                          $balanceSl=$balanceSl-$leave->half_day;
                                        }else if($leave->type=="other"){
                                          $balanceOther=$balanceOther-$leave->half_day;
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
                                    <option value="other">Carry Bal: <?php echo $balanceOther; ?></option>
                                    <option value="comp_off">Comp Off</option
                                  <!--  <option value="lop">LOP</option>
                                    <option value="rh">RH</option>
                                    <option value="comp_off">Comp Off</option>-->
                                    
                                </select>
                                </div>
                                <div class="from-group col-md-3"> 
                                 <label for="days">No of days</label>
                                 <?php         $fixed_limit = $user['fixed_limit']; ?>
                           <input type="number" name="days"  min="0" step="0.5" id="days" class="form-control">  
                           
                           </div> 
                           
                            <div class="from-group col-md-5"> 
                                 <label for="days"> <br>Carry fwd Limit</label> 
                                 
                                 <?php        echo ": 0"; ?>
                                 <br>
                                 <label for="days"> Monthly Limit</label> 
                                
                                 <?php        echo ": " .$user['fixed_limit']; ?>
                          
                           
                           </div>  
                           
                        
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
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>


      <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
      <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.22/pdfmake.min.js"></script>
      <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
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
      <script src="<?php echo base_url('adminassets/plugins/datatables-buttons/js/dataTables.buttons.min.js')?>"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
      <script src="<?php echo base_url('adminassets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js')?>"></script>
      <script src="<?php echo base_url('adminassets/plugins/datatables-buttons/js/buttons.html5.min.js')?>"></script>
      <script src="<?php echo base_url('adminassets/plugins/datatables-buttons/js/buttons.print.min.js')?>"></script>
      <script src="<?php echo base_url('adminassets/plugins/datatables-buttons/js/buttons.colVis.min.js')?>"></script>

      <script src="<?php echo base_url('adminassets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')?>"></script>
      <script src="<?php echo base_url('adminassets/dist/js/adminlte.min.js')?>"></script>
      <!-- AdminLTE for demo purposes -->
      <script src="<?php echo base_url('adminassets/dist/js/demo.js')?>"></script>

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
            url: "<?php base_url('User/add_leave'); ?>",
            data: {'date_from': date_from},
            success: function (data) {
                window.location.href = "<?php base_url('User/add_leave'); ?>?getDate="+date_from;
            }
    });
  }
  
      </script>



      <script>

      function export_datas(){
        let data=document.getElementById('example1');
        var fp=XLSX.utils.table_to_book(data,{sheet:'Report'});
        XLSX.write(fp,{
          bookType:'xlsx',
          type:'base64'
        });
        XLSX.writeFile(fp, 'Monthly Leave Report.xlsx');
      }
      </script>
      <script type="text/javascript">
      function exportPDF() {
        html2canvas(document.getElementById('example1'), {
          onrendered: function (canvas) {
            var data = canvas.toDataURL();
            var docDefinition = {
              content: [{
                image: data,
                width: 500
              }]
            };
            pdfMake.createPdf(docDefinition).download("Employee Attendance.pdf");
          }
        });
      }
      </script>
    </body>
    </html>
