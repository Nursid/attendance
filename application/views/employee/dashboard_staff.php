<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title> MCS  User Login</title>

  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="<?php echo base_url('adminassets/plugins/fontawesome-free/css/all.min.css')?>">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="<?php echo base_url('adminassets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css')?>">
  <link rel="stylesheet" href="<?php echo base_url('adminassets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')?>">
  <link rel="stylesheet" href="<?php echo base_url('adminassets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')?>">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url('adminassets/dist/css/adminlte.min.css')?>">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>


<style>

.chart_box {
	width:45%;
	height:auto;
	padding:10px;
	margin:10px;
	background:#FFF;
	
	}

</style>







<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
<div class="wrapper">
  <?php $this->load->view('employee/staff_menu')?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    
    
   
  <?php
  if($this->session->userdata()['type']=='P'){
    $getUserCompanies  = $this->web->getUserCompanies($this->session->userdata('login_id'));
    ?>
  
  
  <?php
  }
  
  ?>
    
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
<?php
    
				$id=$this->session->userdata('id');
					
   
        
       if($this->session->userdata()['type']=='P'){
					$busi=$this->web->getBusinessbyUser($this->web->session->userdata('login_id'));
		       $id=$busi[0]->business_id;
           $id = $this->session->userdata('empCompany');
		 			} else {
				$id=$this->web->session->userdata('login_id');
					} 
        
    
        
		 ?>
    <!-- /.content -->
    
    
    
     <!-- Main content -->
    
                  <?php
				  // chart Calculation ,
				  
				  // daily Report	
				  //
				 // $id=$this->web->session->userdata('login_id');  
                 $cudate = date("Y-m-d");
       	   // $cudate= '2022-07-20';
				$cdate=strtotime($cudate);
				
				$start_time= strtotime(date("Y-m-d 00:00:00",$cdate));
                $end_time= strtotime(date("Y-m-d 23:59:59",$cdate));
                       
				$act="SELECT count(id) as actemp FROM user_request WHERE business_id='$id'and user_status=1 and left_date ='' ";
                      $aemp= $this->db->query($act)->result();
                    $active= $aemp[0]->actemp;
							 
                $pren_daily="SELECT count(DISTINCT user_id) as present FROM attendance WHERE status=1 and verified=1 and io_time BETWEEN $start_time and $end_time and bussiness_id='$id'";
               $pnt_daily= $this->db->query($pren_daily)->result();
			 
               $present_daily= $pnt_daily[0]->present;
				$absent_daily= $active-$present_daily;
				
				
		


// box calculation

$inactss="SELECT count(id) as request FROM leaves WHERE bid='$id'and status=2 ";
                    $lvs= $this->db->query($inactss)->result();
                   $pending_leave= $lvs[0]->request;
					 
		
$pending="SELECT count(id) as request FROM attendance WHERE bussiness_id='$id' and verified=0 ";
                    $att= $this->db->query($pending)->result();
                   $pending_attendance= $att[0]->request;
					 					 

$inact="SELECT count(id) as inactemp FROM user_request WHERE business_id='$id'and user_status=0 and left_date ='' ";
                      $inaemp= $this->db->query($inact)->result();
                 $inactive=$inaemp[0]->inactemp;
					
					

    ?>
       
  

 <section class="content">
      <div class="container-fluid">
        <!-- Info boxes -->
        <div class="row">


 <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-green">
                <div class="inner">
                <!--  <h3><?php echo  $active ;?></h3>-->
                  <p>Attendance Detail</p>
                </div>
                <div class="icon">
                 <i class="fa fa-users"></i>

                </div>
                <a href="<?php echo base_url('User/staff_attendance')?>" class="small-box-footer">Attendance Report <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->
          


<div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-danger">
                <div class="inner">
                <!-- <h3><?php echo  $inactive ;?></h3>-->
                  <p>Out Duty</p>
                </div>
               <div class="icon">
                 <i class="fa fa-users"></i>

                </div>
                <a href="<?php echo base_url('User/request_working')?>" class="small-box-footer">Request Working <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->
            
            
            
            
            
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-lime">
                <div class="inner">
               <!--  <h3><?php echo  $pending_leave ;?></h3>-->
                  <p>Leave Mgt</p>
                </div>
                <div class="icon">
                 <i class="fa fa-users"></i>

                </div>
                <a href="<?php echo base_url('User/staff_leave')?>" class="small-box-footer"> Request Leave <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->
            
            
            
            
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-yellow">
                <div class="inner">
                <!--  <h3><?php echo  $pending_attendance ;?></h3>-->
                  <p>Pending Attendance</p>
                </div>
                <div class="icon">
                 <i class="fa fa-users"></i>

                </div>
                <a href="<?php echo base_url('User/request_attendance')?>" class="small-box-footer">Request Attendance<i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->
            

  </div>
        <!-- /.row -->

      

      
      </div><!--/. container-fluid -->
    </section>




 

<?php  
		 ?>
    

</div>
  <!-- /.content-wrapper -->
  
  
  
  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->

  <!-- Main Footer -->
  <?php $this->load->view('menu/footer')?>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
<!-- jQuery -->
<script src="<?php echo base_url('adminassets/plugins/jquery/jquery.min.js')?>"></script>
<!-- Bootstrap -->
<script src="<?php echo base_url('adminassets/plugins/bootstrap/js/bootstrap.bundle.min.js')?>"></script>
<!-- overlayScrollbars -->
<script src="<?php echo base_url('adminassets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')?>"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url('adminassets/dist/js/adminlte.js')?>"></script>

<!-- OPTIONAL SCRIPTS -->
<script src="<?php echo base_url('adminassets/dist/js/demo.js')?>"></script>
<script src="<?php echo base_url('adminassets/plugins/datatables/jquery.dataTables.min.js')?>"></script>
<script src="<?php echo base_url('adminassets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')?>"></script>
<script src="<?php echo base_url('adminassets/plugins/datatables-responsive/js/dataTables.responsive.min.js')?>"></script>
<script src="<?php echo base_url('adminassets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')?>"></script>
<!-- PAGE PLUGINS -->
<!-- jQuery Mapael -->
<script src="<?php echo base_url('adminassets/plugins/jquery-mousewheel/jquery.mousewheel.js')?>"></script>
<script src="<?php echo base_url('adminassets/plugins/raphael/raphael.min.js')?>"></script>
<script src="<?php echo base_url('adminassets/plugins/jquery-mapael/jquery.mapael.min.js')?>"></script>
<script src="<?php echo base_url('adminassets/plugins/jquery-mapael/maps/usa_states.min.js')?>"></script>
<!-- ChartJS -->
<script src="<?php echo base_url('adminassets/plugins/chart.js/Chart.min.js')?>"></script>

<!-- PAGE SCRIPTS -->
<script src="<?php echo base_url('adminassets/dist/js/pages/dashboard2.js')?>"></script>
<script
src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js">
</script>

<script>
var ctx = document.getElementById("myChart").getContext('2d');
var myChart = new Chart(ctx, {
  type: 'pie',
  data: {
    labels: ["Present","Absent"],
    datasets: [{
      backgroundColor: [
        "#2ecc71",
        "#F7464A"
      ],
	  
      data: [ <?php echo  $present_daily ; ?>, <?php echo $absent_daily; ?>]
    }]
  },
  
  options: {
    title: {
      display: true,
      text: "Today Report"
    }
  }
  
});
</script>

<script>
       
       var present = <?php echo json_encode($data2); ?>;
      var days = <?php echo json_encode($data); ?>;
	  var absent = <?php echo json_encode($data3); ?>;
	  var colour = [];
var ctx2 = document.getElementById('myChartBar').getContext('2d');
var myChart = new Chart(ctx2, {
    type: 'bar',
    data: {
      labels: days,
	 //  labels: data,
       datasets: [{
            label: 'Present',
            data: present,
            backgroundColor: 
                 "#2ecc71",
       
            hoverBackgroundColor:"#FFFF00",
            borderColor: 
                'rgba(255, 99, 132, 1)',
          borderWidth: 1 },
		{ 
		    label: 'Absent',

            data: absent,
            backgroundColor:"#3498db",
		    hoverBackgroundColor: "#F7464A",
            hoverBorderColor: 'rgba(200, 200, 200, 1)',   
          borderColor: 
                'rgba(255, 99, 132, 1)',
          borderWidth: 1
			
        }]
    },
    options: {
       title: {
      display: true,
      text: "Weekly Report"
    }
        
    }
});
	
</script>


<script>

      var month_present = <?php echo json_encode($data5); ?>;
      var month = <?php echo json_encode($data4); ?>;
	  var month_absent = <?php echo json_encode($data6); ?>;
var ctx4 = document.getElementById('myMonthBar').getContext('2d');
var myChart = new Chart(ctx4, {
    type: 'bar',
    data: {
        labels: month,
        datasets: [{
            label: 'Present',
            data: month_present,
            backgroundColor: "#2ecc71",
            hoverBackgroundColor:"#FFFF00",
            borderWidth: 0 },
		
			{ label: 'Absent',
            data: month_absent,
            backgroundColor: "#3498db",
			hoverBackgroundColor: "#F7464A",
         borderWidth: 0
			
        }]
    },
    options: {
    title: {
      display: true,
      text: "Monthly Report"
    }
        
    
    }
});
</script>


<script>

       var month_name = <?php echo json_encode($data7); ?>;
      var logs = <?php echo json_encode($data8); ?>;
	  var logs_gps = <?php echo json_encode($data9); ?>;
	  var logs_qr = <?php echo json_encode($data10); ?>;

var ctx3 = document.getElementById('myNewBar').getContext('2d');
var myChart = new Chart(ctx3, {
    
  type: "line",
  data: {
    labels: month_name,
    datasets: [{
      data: logs,
      borderColor: "red",
	   borderWidth: 1,
	   label: "All Punch",
      fill:true
    },
	{
     data: logs_gps,
      borderColor: "green",
	  borderWidth: 1,
	   label: "GPS Punch",
      fill: true
	  
	  
	  },
	{
     data: logs_qr,
      borderColor: "blue",
	  borderWidth: 1,
	   label: "QR Punch",
      fill: true
    
    }]
  },
  options: {
    title: {
      display: true,
      text: 'Month Punch Logs: <?php  echo $result_log_all; ?>'
    }
  }
});
	
	
</script>


</body>
</html>
