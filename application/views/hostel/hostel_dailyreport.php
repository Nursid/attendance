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
   <?php $this->load->view('hostel/hostel_menu')?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
    
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">

            <div class="col-sm-12">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Daily Report</li>
              </ol>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>
      <?php
      if($this->session->userdata()['type']=='B' )
      {
        
      ?>
        <!-- Main content -->
        <section class="content">
        <?php
		$bid = $this->web->session->userdata('login_id');
		
		
        if($this->session->userdata()['type']=='B' ){?>
          <div class="container-fluid">
              <div class="loading">Loading&#8230;</div>
            <div class="row">
              <!-- left column -->
              <div class="col-md-12">
                <span style="color: red"><?php echo $this->session->flashdata('msg');?></span>
              </div>
            </div>
            <div class="row">
              <!-- left column -->
              <div class="col-md-12">
                <div class="card card-primary">
                  <div class="card-header">
                    <h3 class="card-title">Daily Report</h3>
                  </div>
                  <div class="card-body">
                    <h5> Select Date</h5>
                    <div class="row">
                      <div class="col-lg-12 float-left">
                        <form action="<?php echo base_url('User/hostel_daily_report')?>" method="POST">
                          <div class="row">
                            <div class="col-sm-2">

                              <input type="date" name="start_date" id="start_date"  value="<?php echo $start_date; ?>" class="form-control"  max="<?php echo date('Y-m-d'); ?>" onchange="startChange(event);">
                            </div>
                            
                            <div class="col-1">
                              <button type="submit" id="actionSubmit" class="btn btn-success btn-fill btn-block" onclick="showLoader()">Show</button>
                            </div>
                          </div>
                          <br>
                          <input type="hidden" id="action" name="action" value="active"/>
                          <div class="row">
                            <div class="col-2">
                              <button type="button" id="" onClick="setAction('active');" class="btn btn-success btn-fill btn-block">Active : <?php echo $totalActive;?></button>
                            </div>
                            <div class="col-2">
                              <button type="button" id="" onClick="setAction('present');" class="btn btn-success btn-fill btn-block">IN : <?php echo $totalPresent;?></button>
                            </div>
                            <div class="col-2">
                              <button type="button" id="" onClick="setAction('absent');" class="btn btn-danger btn-fill btn-block">Out: <?php echo $totalAbsent;?></button>
                            </div>
                            
                            
                          </div>
                        </form>
                      </div>
                    </div>
                    <br>
                    <?php
                    if($load) {
                      $stdate=strtotime($start_date);
                      ?>
                      <h5>Attendance for Date:-<?php echo date("d-M-Y ",$stdate)?>  </h5>
                     <!-- <div align="right">
                        <input type="button" onClick="exportExcel()" value="Export To Excel" />
                        <input type="button"  id="btnExport" value="Export To Pdf" onclick="exportPDF()" />
                        <br>
                      </div>-->
                      <table id="example1" class="table table-bordered table-striped">
                        <thead>
                          <?php
                          echo "<tr> <td colspan='12'> Attendance For Date:-".$start_date."</td></tr>";
                          // echo " <tr> <td colspan='12'> Total Present : ".$present." , ";
                          // echo "Total Absent: ".$absent." , ";

                          // echo "Total Active : ".$active ."</td></tr>" ;?>
                          <tr>
                            <th>S.No</th>
                            <th>Name</th>
                            <th>Block</th>
                            <th>Room No</th>
                            <th>IN</th>
                            <th>Out</th>
                            <th>Out Remark</th>
                            <th>W.H</th>
                            
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $count=1;
                          foreach($report as $user){
                            ?>
                            <tr>
                              <td><?php echo $count++;?></td>
                              <td><?php echo $user['name'];?></td>
                              <td><?php
							 // echo $user['user_id'];
							  $hostel = $this->web->getHostelByUserId($user['user_id'],$bid); 
						       $blid=$hostel[0]->block;
						       $block = $this->web->getBlock($blid,$bid);
							   echo $block[0]->name;
							  
							  // echo $user['designation'];?></td>
                              <td>
							  <?php echo $hostel[0]->room_no;
							   ?>
                               </td>
                              <td>
                                <?php
                                foreach($user['data'] as $day_data){
                                  if($day_data['mode']=="in"){
                                    
                                    echo "<span class='".$spanClass."'>".date('h:i:A', $day_data['time'])."</span></br>";
                                  }
                                }
                                ?>
                                </td>
                                <td>
                                  <?php
                                  foreach($user['data'] as $day_data){
                                    if($day_data['mode']=="out"){
                                    
                                      echo "<span class='".$spanClass."'>".date('h:i:A', $day_data['time']).")</span></br>";
                                    }
                                  }
                                  ?>
                                  </td>
                                  
                                  <td> <?php
                                  foreach($user['data'] as $day_data){
                                    
                                    
                                      echo "<span class='".$spanClass."'>". $day_data['comment']."</span></br>";
                                    
                                  }
                                  ?>
                                  
                                  </td>
                                  <td><?php echo $user['workingHrs'];?></td>
                                  
                                </tr>
                                <?php
                              }
                              ?>
                            </tbody>
                            <tfoot>
                            </tfoot>
                          </table>
                        </div>
                      <?php }
                      ?>
                    </div>
                    <!-- /.card-body -->
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
      <script src="<?php echo base_url('adminassets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')?>"></script>
      <script src="<?php echo base_url('adminassets/dist/js/adminlte.min.js')?>"></script>
      <!-- AdminLTE for demo purposes -->
      <script src="<?php echo base_url('adminassets/dist/js/demo.js')?>"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.3.0/exceljs.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
      <script>
      $(function () {
        var table = $('#example1').DataTable({
          "responsive": true,
          "autoWidth": false,
          paging: false,
        });

      });
      function setAction(action){
        $("#action").val(action);
        $("#actionSubmit").click();
        showLoader();
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
    </script>

    
  
</body>
</html>
