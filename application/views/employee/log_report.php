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
                <li class="breadcrumb-item active">Log Report</li>
              </ol>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>
      
        <!-- Main content -->
        <section class="content">
        <?php
        if($this->session->userdata()['type']=='P'){?>
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
                    <h3 class="card-title">Log Report</h3>
                  </div>
                 <br>
                     
                      <!-- /.total starts -->
                      
                      
                      
                      <?php
                     $uid= $this->session->userdata('login_id');
					$userCmp = $this->app->getUserCompany($loginId);
					$loginId = $userCmp['business_id'];
                  ?>
                  
                   <?php
                      
                        $stdate1=(strtotime(date("Y-m-d")))-604800;
                        $endate1=strtotime(date("Y-m-d"));
                        ?>
                        <h5>Logs for Date:-<?php echo date("d-M-Y ",$stdate1)?> to Date:- <?php echo date("d-M-Y ",$endate1) ?> 
                       
                         
                      <?php  $start_time1 = strtotime(date("d-m-Y 00:00:00",$stdate1));
			         		$end_time1 = strtotime(date("d-m-Y 23:59:59",$endate1));
                       echo  	$userCmp."tt";
                      
                      ?>
                        
                        
                        </h5>
                      
                      
                      <table id="example3" class="table table-bordered table-striped">
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
                            
                       $logs=$this->web->getUserAccess2($start_time1,$end_time1,$uid);
                       
                      
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
					 
			          echo date('d-m-Y', $field); echo date('h:i:A',$field) ; 
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
                      
               
                      
                      <?php ?>
                  
                  
                  
                  
                  
                  
                  
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
  <!-- jQuery  -->
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
function export_datas(){
  let data=document.getElementById('example3');
  var fp=XLSX.utils.table_to_book(data,{sheet:'Report'});
  XLSX.write(fp,{
    bookType:'xlsx',
    type:'base64'
  });
  XLSX.writeFile(fp, 'gps Attendance.xlsx');
}
</script>

<script type="text/javascript">
function Export() {
  html2canvas(document.getElementById('example3'), {
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
