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
                <li class="breadcrumb-item active">Student Report</li>
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
        if($this->session->userdata()['type']=='B' || $role[0]->other_report=="1"){?>
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
                    <h3 class="card-title">Student Report</h3>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <table id="example1" class="table table-bordered table-striped">
                        <thead>
                          <tr>
                            <th>S.No</th>
                            <th>Student Name</th>
                            <th>Class</th>
                            <th>Bus No.</th>
                            <th>Route</th>
                            <th>Mode</th>
                            <th>Time</th>
                          </tr>
                        </thead>
                        <tbody>
                            <?php
                            $count = 1;
                            foreach($student_attendance as $attendance){?>
                                <tr>
                                    <td><?= $count?></td>
                                    <td><?= $attendance->name?></td>
                                    <td><?= $attendance->class_name?></td>
                                    <td><?= $attendance->assign_to?></td>
                                    <td><?= $attendance->route?></td>
                                    <td><?php if($attendance->student_status==2){echo "IN";}else{echo "OUT";} ?></td>
                                    <td><?= date("d-m-y h:s a",$attendance->time)?></td>
                                </tr>
                            <?php
                            $count++;
                            }
                            ?>
                        </tbody>
                        </table>
                        </div>
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
          paging: true,
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
