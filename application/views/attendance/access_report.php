<?php

date_default_timezone_set('Asia/Kolkata');
?>
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
  <link rel="stylesheet" href="<?php echo base_url('adminassets/dist/css/mid.css')?>">
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
                <li class="breadcrumb-item active">Access Report</li>
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
          $loginId = $this->session->userdata('empCompany');
          $role=$this->web->getRollbyid($this->web->session->userdata('login_id'),$loginId);
        } else {
          $loginId = $this->web->session->userdata('login_id');
        }
        ?>
        <!-- Main content -->
        <section class="content">
        <?php
        if($this->session->userdata()['type']=='B' || $role[0]->log_report=="1" || $role[0]->type=="1"){?>
          <div class="container-fluid">
              <div class="loading">Loading&#8230;</div>
            <div class="row">
              <!-- left column -->
            </div>
            <div class="row">
              <!-- left column -->
              <div class="col-md-12">
                <div class="card card-primary">
                  <div class="card-header">
                    <h3 class="card-title">Access Report</h3>
                  </div>
                  <div class="card-body">
                    <!-- /.Form starts -->
                    <h5> Select Biometrics </h5>
                    <div class="row">
                      <div class="col-lg-12 float-left">
                        <form action="<?php echo base_url('User/access_report')?>" method="POST">
                          <div class="row">
                            <div class="col-sm-2">
                              <input type="date" name="start_date" id="start_date"  value="<?php echo $start_date; ?>" class="form-control" max="<?php echo $end_date; ?>" onchange="startChange(event);">
                            </div>
                            <div class="col-sm-2">
                              <input type="date" name="end_date" id="end_date"  value="<?php echo $end_date; ?>"class="form-control" max="<?php echo date('Y-m-d'); ?>" min="<?php echo $start_date;?>" onchange="endChange(event);">
                            </div>
                            <div class="col-sm-2 ">
                              <!--<label for="employee">Employee</label>-->
                              <select name="bio" class="form-control"  id="bio" >
                                  
                                <?php   
                                  if ($bio!=0){
                                       $devicename = $this->web->getdevicebyid($bio);
                                  ?>
                                  <option value="<?php echo $devicesname[0]->id;  ?>"><?php echo $devicename[0]->name;  ?></option>
                                  <?php
                                } else { ?>
                                  <option value="0"> All Device </option>
                                
                                  
                                   <?php 
                                }
                                   
                                   $device = $this->web->getdevice($loginId);
                               
                                  ?>
                                 <option value="0"> All Device </option>
                               <?php  foreach($device as $device){ ?>
                                  <option value="<?php echo $device->id  ?>"><?php echo $device->name;  ?></option>
                                  
                               
                                <?php }?>
                                // 			$loginId = $this->session->userdata('login_id');
                                // if($this->session->userdata('type')=="P"){
                                // 	$userCmp = $this->app->getUserCompany($loginId);
                                // 	if(isset($userCmp) && ($userCmp['left_date']=="" || $userCmp['left_date']>time())){
                                // 		$loginId = $userCmp['business_id'];
                                // 	}
                                // }
								
								
                                
                                </select>
                              </div>
                              <div class="col-sm-2">
                                <button type="submit" class="btn btn-success btn-fill btn-block" onclick="showLoader()">Show</button>
                              </div>
                            </div>
                          </form>
                        </div>
                      </div>
                      <br><br>
                      <!-- /.total starts -->
                      
                      
                    
                      
                      
                      
                      <?php
                      if($load) {
                        $stdate=strtotime($start_date);
                        $endate=strtotime($end_date);
                        ?>
                         <div align="right">
                          <input type="button"  class="btn btn-primary" onClick="exportDatas()" value="Export To Excel" />
                        <!-- <input type="button"   class="btn btn-primary" id="btnExport" value="Export To Pdf" onclick="exportPDF()" />-->
                          
                        </div>
                        
                        
                        <h6>Logs for Date:-<?php echo date("d-M-Y ",$stdate)?> to Date:- <?php echo date("d-M-Y ",$endate)?> 
                       
                        
                      <?php  $start_time = strtotime(date("d-m-Y 00:00:00",$stdate));
			         		$end_time = strtotime(date("d-m-Y 23:59:59",$endate));
                       
                      
                      ?>
                        
                        
                        </h6>
                        

                        <table id="example1" class="table table-bordered table-striped">
                          <thead>
                            <tr>
                              <th>S.No.</th>
                               <th>Emp Id</th>
                              <th>Name</th>
                              <th>Device</th>
                              <th>date</th>
                              <th>Time</th>
                              <th>Mode</th>
                               
                            </tr>
                          </thead>
                          <tbody>
                            <?php 
                            if($bio==0) { 
                       $logs=$this->web->getCmpAccess($start_time,$end_time,$loginId);
                      }else{
                          
                         $logs=$this->web->getDeviceAccess($start_time,$end_time,$loginId,$bio);
                      } 
                        $count=1;
                        foreach($logs as $logs){  
                              
                          ?>    
                           <tr>
                               
                           <td> <?php echo $count++; ?> </td>
                           <td>
                               
                            <?php $uname = $this->web->getNameByUserId($logs->user_id);
                                        echo $uname[0]->emp_code;
                                        ?>   
                           </td>
                           
                           <td> 
                           <?php //echo $logs->user_id;
                              $uname = $this->web->getNameByUserId($logs->user_id);
                                        echo $uname[0]->name;
                                        ?>
                           
                           </td>
                           <td> <?php 
                           $devicename = $this->web->getdevicebyid($logs->device);
                                        echo $devicename[0]->name;
                          // $logs->device;
                           ?>
                           </td>
                           
                           <td>
                               <?php $field= $logs->io_time ;
					 
			           echo date('d-M-Y', $field);  
			          ?>
			          </td>
			          <td>
                               <?php $field= $logs->io_time ;
					 
			           echo date('h:i:A', $field);  
			          ?>
			          </td>
			          
			          
                           <td> <?php 
                           echo $logs->mode;
                           ?>
                           </td>
                           
                           
                               
                               
                               
                           </tr> 
                          <?php } ?> 
                        </tbody>
                        <tfoot>
                        </tfoot>
                      </table>
                    <?php 
                  ?>
                    </div>
                    <?php
                  }else{
                  ?>
                  
                   <?php
                      
                        $stdate1=(strtotime($end_date))-13200;
                        $endate1=strtotime($end_date);
                        ?>
                   <!--     <h6>Logs for Date:-<?php echo date("d-M-Y ",$stdate1)?> to Date:- <?php echo date("d-M-Y ",$endate1)?> 
                       
                        
                      <?php  $start_time1 = strtotime(date("d-m-Y 00:00:00",$stdate1));
			         		$end_time1 = strtotime(date("d-m-Y 23:59:59",$endate1));
                       
                      
                      ?>
                        
                        
                        </h6>
                      
                      
                      <table id="example1" class="table table-bordered table-striped">
                          <thead>
                            <tr>
                              <th>S.No.</th>
                               <th>Emp Id</th>
                              <th>Name</th>
                              <th>Device</th>
                              <th>Time</th>
                              <th>Mode</th>
                               
                            </tr>
                          </thead>
                          <tbody>
                            <?php 
                            
                    //   $logs=$this->web->getCmpAccess($start_time1,$end_time1,$loginId);
                       
                      
                        $count=1;
                        foreach($logs as $logs){  
                              
                          ?>    
                           <tr>
                               
                           <td> <?php echo $count++; ?> </td>
                           <td>
                               
                            <?php $uname = $this->web->getNameByUserId($logs->user_id);
                                        echo $uname[0]->emp_code;
                                        ?>   
                           </td>
                           
                           <td> 
                           <?php //echo $logs->user_id;
                              $uname = $this->web->getNameByUserId($logs->user_id);
                                        echo $uname[0]->name;
                                        ?>
                           
                           </td>
                           <td> <?php 
                           $devicename = $this->web->getdevicebyid($logs->device);
                                        echo $devicename[0]->name;
                          // $logs->device;
                           ?>
                           </td>
                           
                           <td>
                               <?php $field= $logs->io_time ;
					 
			          echo date('d-M h:i:A', $field);  
			          ?>
			          </td>    
                           <td> <?php 
                           echo $logs->mode;
                           ?>
                           </td>
                           
                           
                               
                               
                               
                           </tr> 
                          <?php } ?> 
                        </tbody>
                        <tfoot>
                        </tfoot>
                      </table>
                      
               -->
                      
                      <?php }?>
                  
                  
                  
                  
                  
                  
                  
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
        <!-- /.content -->
        <?php
      }
      ?>
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
function changeAttendDate(e,id){
  $.ajax({
    type: "POST",
    url: "User/changeAttendDate",
    data: {id : id,AttendDate: e.target.value},
    success: function(){

    }
  });
}
function showLoader(){
        $(".loading").css("display","block");
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
function startChange(e){
  //alert(e.target.value);
  $('#end_date').attr('min', e.target.value);
}
function endChange(e){
  //alert(e.target.value);
  $('#start_date').attr('max', e.target.value);
}
</script>



<script>

 function exportDatas(){
      var wb = new ExcelJS.Workbook();
      var sh = wb.addWorksheet("Report");
      <?php 
    // if (!empty($users)) {
      $sr = 1;?>
      sh.columns = [
        {header: 'SNo.', key: 'SNo', width: 10},
        {header: 'Empcode', key: 'Empcode', width: 10},
        {header: 'Name', key: 'Name', width: 15},
		{header: 'Device', key: 'Device', width:25},
       {header: 'Time', key: 'Time', width: 25},
	{header: 'Mode', key: 'Mode', width: 10}
       
       
      ];
    
      <?php
       if($bio==0) { 
                       $logs=$this->web->getCmpAccess($start_time,$end_time,$loginId);
                      }else{
                          
                         $logs=$this->web->getDeviceAccess($start_time,$end_time,$loginId,$bio);
                      } 
        foreach($logs as $logs){
         $uname = $this->web->getNameByUserId($logs->user_id);
         $devicename = $this->web->getdevicebyid($logs->device);
          ?>
   
    
 sh.addRow({SNo:'<?php echo $sr;?>',Empcode:'<?= $uname[0]->emp_code; ?>',Name:'<?= $uname[0]->name; ?>',Device: '<?= $devicename[0]->name;?>', Time:'<?= date('d-m-Y h-i-A', $logs->io_time) ;?>' , Mode: '<?= $logs->mode ; ?>'});
      <?php 
          echo "sh.getRow(".$sr++.").alignment = { wrapText: true,vertical: 'top',horizontal: 'center' };";
          
        }
        echo "sh.getRow(".$sr.").alignment = { wrapText: true,vertical: 'top',horizontal: 'center' };";
        echo "sh.insertRow(1, ['$cmp_name']);";
       
        echo "sh.insertRow(2, ['Access Log Report']);";
        echo "sh.mergeCells('A1:Q1');";
        echo "sh.mergeCells('A2:Q2');";
        echo "sh.getRow(1).alignment = {horizontal: 'center' };";
        echo "sh.getRow(2).alignment = {horizontal: 'center' };";
        $sr+=4;
      
        echo "sh.mergeCells('A$sr:Q$sr');";
        echo "sh.getRow($sr).alignment = {horizontal: 'center' };";
     // }
      ?>
      wb.xlsx.writeBuffer().then((data) => {
            const blob = new Blob([data], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8' });
            saveAs(blob, 'Log Report.xlsx');
      });
  }


</script>
      
















</body>
</html>
