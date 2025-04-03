<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title>MidApp User Login</title>

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
  <?php $this->load->view('hostel/hostel_menu')?>
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
					
    if($id =='1'){
    ?>
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Info boxes -->
        <div class="row">
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-info elevation-1"><i class="fa fa-users"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Total Users</span>
                <span class="info-box-number">
                  <?php //echo $count['countData']; ?>
                  
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-star"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Counter</span>
                <span class="info-box-number"><?php echo $counter['counterData'];?></span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->

          <!-- fix for small devices only -->
          <div class="clearfix hidden-md-up"></div>

          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-success elevation-1"><i class="fas fa-th"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Booking Appoinment</span>
                <span class="info-box-number"><?php echo $bookappoinment['bookAppoinment'];?></span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">New Users</span>
                <span class="info-box-number">
                    <?php  
					
                $start= strtotime(date("Y-m-d 00:00:00"));
                $endtime= strtotime(date("Y-m-d 23:59:59"));
                $oo="SELECT count(id) as regisDate FROM login WHERE date >= '$start' AND date <= '$endtime'";
                $pp= $this->db->query($oo)->result();
                echo $pp[0]->regisDate;?>
                    </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->

        <div class="row">
       <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-success elevation-1"><i class="fas fa-th"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Business users </span>
                <?php
				
			
			
				?>
                
                <span class="info-box-number"><?php echo $buisness ;?></span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          
            <div class="clearfix hidden-md-up"></div>
          
          
           <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-success elevation-1"><i class="fas fa-th"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Premium Users</span>
                <span class="info-box-number"><?php echo $pbuisness ;?></span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          
           <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-success elevation-1"><i class="fas fa-th"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">total logs</span>
                <span class="info-box-number"><?php echo $total_log;?></span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          
           <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-success elevation-1"><i class="fas fa-th"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Today logs</span>
                <span class="info-box-number"><?php echo $today_log;?></span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          
          
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-success elevation-1"><i class="fas fa-th"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Active Business</span>
                <span class="info-box-number"><?php echo $active_business;?></span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          
          
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-success elevation-1"><i class="fas fa-th"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Active Users</span>
                <span class="info-box-number"><?php echo $active_user;?>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          
          </div>
          
          
      
      
      
      
      
      
      
      
      
      

      
      </div><!--/. container-fluid -->
    </section>
    <?php } else {
        
        
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
				
				
		


// box calculation

					
					

    ?>
       
  

 <section class="content">
      <div class="container-fluid">
        <!-- Info boxes -->
        <div class="row">


 <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-green">
                <div class="inner">
                  <h3><?php echo  $active ;?></h3>
                  <p>Active Employee</p>
                </div>
                <div class="icon">
                 <i class="fa fa-users"></i>

                </div>
                <a href="" class="small-box-footer">Employee Detail <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->
          


<div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-danger">
                <div class="inner">
                 <h3><?php echo  $inactive ;?></h3>
                  <p>InActive Employee</p>
                </div>
                <div class="icon">
                 <i class="fa fa-users"></i>

                </div>
                <a href="" class="small-box-footer">Inactive Detail <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->
            
            
            
            
            
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-lime">
                <div class="inner">
                 <h3><?php echo  $pending_leave ;?></h3>
                  <p>Pending Leave</p>
                </div>
                <div class="icon">
                 <i class="fa fa-users"></i>

                </div>
                <a href="" class="small-box-footer">Aprove Leave <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->
            
            
            
            
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-yellow">
                <div class="inner">
                  <h3><?php echo  $pending_attendance ;?></h3>
                  <p>Pending Attendance</p>
                </div>
                <div class="icon">
                 <i class="fa fa-users"></i>

                </div>
                <a href="" class="small-box-footer">Aprove Attendance<i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->
            

  </div>
        <!-- /.row -->

      

      
      </div><!--/. container-fluid -->
    </section>


<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Chart Report</h1>
          </div><!-- /.col -->
         
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header
    removed section
    
    -->

 

<?php } 
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




</body>
</html>
