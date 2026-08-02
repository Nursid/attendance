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


  <style>
    .card111{
      height: 100% !important;
    }
    .card-img-top{
      border-radius: 50%;
    }
    .card-title{
      font-size: 6px;
    }
    .card-body1{
      padding: 4px 0px 0px 18px;
      margin: 0px 7px 0px 7px;
      background: #d30831eb;
      color: #fff;
    }
    .card-body .card-text{
      color:#fff!important;
      font-size: 10px;
    }
    .card-body2{
      padding: 4px 0px 0px 18px;
      margin: 0px 7px 0px 7px;
      background: #1e6d17;
      color: #fff;
    }
    .card-body3{
      padding: 4px 0px 0px 18px;
      margin: 0px 7px 0px 7px;
      background: #d30831eb;
      color: #fff;
    }
    .card-body {
      padding: 0.25rem;
    }
    .pagination{
      display: flex;
      align-items: center;
      justify-content: center;
      top: 0.25%;
      left: 50%;
      position: absolute;
    }
    .nav{
      margin-bottom:5px !important;
    }
    aside{
      display:none;
    }
    nav{
      display:none !important;
    }
    .wrapper .content-wrapper{
      margin-left:0px !important;
    }
    .fourclass{
      display:none;
    }
    .fiveclass{
      display:none;
    }
    .sixclass{
      display:none;
    }
    .main-footer{
      display:none;
    }
    .card-header{
      display:none;
    }
    .card-danger{
      margin-top:5px;
    }
    </style>

</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
 <?php $this->load->view('hostel/hostel_menu')?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
         
          <div class="col-sm-12">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Student Timing Report</li>
            </ol>
          </div>
        </div>
      </div>
    </section> -->
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
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <div class="card card-danger">
              <div class="card-header">
                <h3 class="card-title">Student Timing Report</h3>
              </div>
              <div class="card-body">
              
            <ul class="nav nav-pills">
              <li class="active btn btn-primary"><a href="<?php echo base_url()?>hostel_student_timing_report" style="color:#fff;" href="#home">All-<?php echo ($total_count_in+$total_count_out);?></a></li>
              <li class="btn btn-success" style="margin-left:5px;"><a href="<?php echo base_url()?>hostel_student_timing_report" style="color:#fff;" href="#menu2">In-<?php echo $total_count_in;?></a></li>
              <li class="btn btn-danger" style="margin-left:5px;"><a href="<?php echo base_url()?>hostel_student_timing_report" style="color:#fff;" href="#menu3">Out-<?php echo $total_count_out;?></a></li>
              <button clas="btn btn-success" style="margin-left:5px;" id="stopButton"><i class="fa fa-stop"></i></button>
              <button class="" style="margin-left:5px;" id="resumeButton"><i class="fa fa-play"></i></button>
              
              <?php 
                 echo $links;
              ?>
              <button id="fullscreenButton" style="margin-left:5px;float: right;
              display: flex;
              position: absolute;
              right: 6.5%;
              padding: .375rem .75rem;"><i class="fa fa-expand"></i></button>
              <button id="exitFullscreenButton" style="margin-left:5px;float: right;
              display: flex;
              position: absolute;
              right: 3.5%;
              padding: .375rem .75rem;"><i class="fa fa-compress"></i></button>
              
			  <a href = "hostel_student_timing_report">
              <button  style="
                  display: flex;
                  position: absolute;
                  float: right;
                  right: 0.3%;
                  padding: .375rem .75rem;
              ">
			  <i class="fa fa-eye"></i>
			  </button>
			  </a>
			  
			  
            </ul>
				  
            <div class="row eightclass" id="eight">
            <?php if($results){
              foreach($results as $key=>$stdDetails){ ?>
                <?php //$mod = $key+1;?>
				<?php //echo'<pre>';print_r($stdDetails);die;?>
                <div class="card card111" style="width:6.9%;box-shadow: 5px 5px #ddd;border: 1px solid #ddd;margin-left:15px;">
                <div class="row">
                  <div class="col-md-12 col-lg-12">
                  <?php if($stdDetails->mode=='in'){?>
                    <img class="card-img-top" src="<?php echo base_url()?>/assets/images/user-avatars-thumbnail.png" alt="Card image">
                  <?php }?>
                  <?php if($stdDetails->mode=='out'){?>
                    <img class="card-img-top" src="<?php echo base_url()?>/assets/images/user-avatars-thumbnail.png" alt="Card image">
                  <?php }?>
                  </div>
                  <div class="col-md-12 col-lg-12 card-body text-center
                  <?php if($stdDetails->mode=='out'){?>
                  card-body1
                  <?php }?>
                  <?php if($stdDetails->mode=='in'){?>
                  card-body2
                  <?php }?>
                  <?php if($stdDetails->mode=='all'){?>
                  card-body3
                  <?php }?>
                  ">
                  <h4 class="card-title" data-toggle="tooltip" data-placement="top" title="
                  <?php if($stdDetails->mode=='in'){?>
                  In Time:
                  <?php echo date('Y-m-d H:i:s', $stdDetails->io_time);?>
                  <?php }?>
                  <?php if($stdDetails->mode=='out'){?>
                  Out Time:
                  <?php echo date('Y-m-d H:i:s', $stdDetails->io_time);?>  
                  <?php }?>
                  "><?php echo substr($stdDetails->name,0,10);?></h4>
                  </div>
                </div>
              </div>
              <?php }
            }?>
              
            
					
                    <!-- /.card-body -->
            </div>
            
                  
                  <!-- /.card -->
                </div>
              </div>
              <!-- /.row -->
            </div><!-- /.container-fluid -->



  



  <?php 
        }
   ?>
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
	<script>
	$(document).ready(function() {
        var interval;
        var lastPage = 5; // Change this according to your actual last page

        function getCurrentPage() {
            var path = window.location.pathname;
            var segments = path.split('/');
            var lastSegment = segments.pop() || segments.pop(); // Handle potential trailing slash
            return isNaN(lastSegment) ? 1 : parseInt(lastSegment);
        }

        function goToNextPage() {
            var currentPage = getCurrentPage();
            var nextPage = currentPage + 1;
            var basePath = window.location.origin + '/hostel_student_timing_report_sm/';

            if (currentPage >= lastPage) {
                nextPage = 1; // Loop back to the first page
            }
            window.location.href = basePath + nextPage;
        }

        function startAutoPagination() {
            interval = setInterval(goToNextPage, 5000); // 15000 milliseconds = 15 seconds
        }

        function stopAutoPagination() {
            clearInterval(interval);
        }

        $('#pauseBtn').on('click', function() {
            stopAutoPagination();
            $('#pauseBtn').hide();
            $('#playBtn').show();
        });

        $('#playBtn').on('click', function() {
            startAutoPagination();
            $('#playBtn').hide();
            $('#pauseBtn').show();
        });

        // Start automatic pagination on page load
        startAutoPagination();
    });

	</script>
    <script>
    $(document).ready(function() {
      $('#fullscreenButton').click(function() {
        localStorage.setItem('fullscreen', 1);
        var docElm = document.documentElement;
        if (docElm.requestFullscreen) {
          docElm.requestFullscreen();
        } else if (docElm.mozRequestFullScreen) { 
          docElm.mozRequestFullScreen();
        } else if (docElm.webkitRequestFullscreen) { 
          docElm.webkitRequestFullscreen();
        } else if (docElm.msRequestFullscreen) { 
          docElm.msRequestFullscreen();
        }
      });

      // Exit fullscreen button click event
      $('#exitFullscreenButton').click(function() {
        localStorage.setItem('fullscreen', 0);
        if (document.exitFullscreen) {
          document.exitFullscreen();
        } else if (document.mozCancelFullScreen) { 
          document.mozCancelFullScreen();
        } else if (document.webkitExitFullscreen) { 
          document.webkitExitFullscreen();
        } else if (document.msExitFullscreen) { 
          document.msExitFullscreen();
        }
      });
    });
  </script>

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
      $('[data-toggle="tooltip"]').tooltip()
    })
  
    $(document).ready(function () {
        var table = $('#example1').DataTable( {
            scrollY:        "500px",
            scrollX:        true,
            scrollCollapse: true,
            paging:         false,
            fixedColumns:   {
                leftColumns: 2
            }
        } );
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
    }
    function endChange(e){
      //alert(e.target.value);
      $('#start_date').attr('max', e.target.value);
    }
    function showLoader(){
      $(".loading").css("display","block");
    }

   
    </script>


<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">Add Out Timing</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <div id="modform">
          


                <form action="<?php echo base_url('User/add_user_out_timing')?>" method="POST">
                <div class="card-body">
                      <div class="row">
                        <div class="from-group col-md-12">
                          <label for="depart">Select User</label>
                          <select class="form-control" name="user_id">
                            <option>--- Select User ---</option>
                            <?php foreach($studentDetails as $student){?>
                              <option value="<?php echo($student->id);?>"><?php echo($student->name);?></option>
                            <?php }?>
                          </select>
                        </div>
                        <div class="from-group col-md-12">
                          <label for="depart">Out Time</label>
                          <input type="datetime-local" name="out_time" id="out_time" class="form-control">
                        </div>
                        <div class="from-group col-md-12">
                        <button class=" btn btn-success mt-4 mx-auto">Add Now</button>
                        </div>
                      </div>
                    </div>
              </form>







        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
















<div class="modal fade" id="myModalIn" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">Add In Timing</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <div id="modform">
          


        <form action="<?php echo base_url('User/add_user_in_timing')?>" method="POST">
                    <div class="card-body">
                      <div class="row">
                        
                        <div class="from-group col-md-12">
                          <label for="depart">Select User</label>
                          <select class="form-control" name="timing_id">
                          <option>--- Select User ---</option>
                           
                            <?php 
                            
                            foreach($studentOutData as $student){?>
                              <option value="<?php echo($student['timing_id']);?>"><?php echo($student['name']);?></option>
                            <?php }?>
                          </select>
                        </div>
                        <div class="from-group col-md-12">
                          <label for="depart">In Time</label>
                          <input type="datetime-local" name="in_time" id="in_time" class="form-control">
                        </div>
                        
                        
                        <div class="from-group col-md-12">
                        <button class=" btn btn-success mt-4 mx-auto">Add Now</button>
                        </div>
                      </div>
                    </div>
              </form>







        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>





