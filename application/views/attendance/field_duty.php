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
  <link rel="stylesheet" href="<?php echo base_url('adminassets/plugins/fontawesome-free/css/all.min.css') ?>">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

  <!-- Select2 -->
  <link rel="stylesheet" href="<?php echo base_url('adminassets/plugins/select2/css/select2.min.css') ?>">
  <link rel="stylesheet" href="<?php echo base_url('adminassets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') ?>">
  <link rel="stylesheet" href="<?php echo base_url('adminassets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
  <link rel="stylesheet" href="<?php echo base_url('adminassets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url('adminassets/dist/css/adminlte.min.css') ?>">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>

<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <?php $this->load->view('menu/menu') ?>
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
                <li class="breadcrumb-item active">Monthly Report</li>
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
        if($this->session->userdata()['type']=='B' || $role[0]->gps_report=="1" || $role[0]->type=="1"){?>
          <div class="container-fluid">
            <div class="row">
              <!-- left column -->
              <div class="col-md-12">
                <div class="card card-danger">
                  <div class="card-header">
                    <h3 class="card-title">Field Duty Report</h3>
                  </div>
                  <div class="card-body">
                    <h5> Select Date Range</h5>
                    <div class="row">
                      <div class="col-sm-12 float-left">
                        <?php
                        $start = $_POST['start'];
                        $end = $_POST['end'];
                        $cudate = date("Y-m-d");
                        ?>
                        <form action="" method="POST">
                          <div class="row">
                            <div class="col-sm-3">
                              <input type="date" name="start" value="<?php if ($start != '') { echo $start;} else {echo $cudate;} ?>" class="form-control">
                            </div>
                            <div class="col-sm-3">
                              <input type="date" name="end" value="<?php if ($end != '') {echo $end;} else {echo $cudate;} ?>" class="form-control">
                            </div>
                            <div class="col-sm-2 ">
                              <!--<label for="employee">Employee</label>-->
                              <select name="emp" class="form-control" name="emp" id="emp">
                                <?php $usname = $this->web->getNameByUserId($id);
                                if ($id != '') {
                                ?>
                                  <option value="<?php echo $usname[0]->id  ?>"><?php echo $usname[0]->name;  ?></option>
                                <?php
                                } else { ?>
                                  <option value="0"> All Employee </option>
                                <?php }
                                // $loginId = $this->session->userdata('login_id');
                                // if ($this->session->userdata('type') == "P") {
                                //   $userCmp = $this->app->getUserCompany($loginId);
                                //   if (isset($userCmp) && ($userCmp['left_date'] == "" || $userCmp['left_date'] > time())) {
                                //     $loginId = $userCmp['business_id'];
                                //   }
                                // }
                                $res = $this->web->getActiveEmployeesList($loginId);
                                if($this->session->userdata()['type']=='P'){
                                  if($role[0]->type!=1){
                                    $departments = explode(",",$role[0]->department);
                                    $sections = explode(",",$role[0]->section);
                                    if(!empty($departments[0]) || !empty($sections[0])){
                                    foreach ($res as $key => $dataVal) {
                                      $uname = $this->web->getNameByUserId($dataVal->user_id);
                                      $roleDp = array_search($uname[0]->department,$departments);
                                      $roleSection = array_search($uname[0]->section,$sections);
                                      if(!is_bool($roleDp) || !is_bool($roleSection)){
                                        
                                      }else{
                                        unset($res[$key]);
                                      }
                                    }}
                                  }
                                }
                                foreach ($res as $res) :
                                  $uname = $this->web->getNameByUserId($res->user_id);
                                  $select = "";
                                  if (!empty($_POST['emp']) && $_POST['emp'] == $res->user_id) {
                                    $select = "selected";
                                  }
                                ?>
                                  <option value="<?php echo $uname[0]->id  ?>" <?php echo $select; ?>><?php echo $uname[0]->name; ?></option>
                                <?php
                                endforeach;
                                ?>
                              </select>
                            </div>
                            <div class="">
                              <button type="submit" class="btn btn-success btn-fill btn-block">Show</button>
                            </div>
                          </div>
                        </form>
                      </div>
                    </div>
                    <br>
                    <?php
                    $start = $_POST['start'];
                    $end = $_POST['end'];
                    if ($start != '' && $end != '') {
                      $stdate = strtotime($start);
                      $endate = strtotime($end);
                    ?>
                      <h5>Attendance for Date:-<?php echo date("d-M-Y ", $stdate) ?> to Date:- <?php echo date("d-M-Y ", $endate) ?> </h5>
                      <div align="right">
                        <input type="button" onClick="export_datas()" value="Export To Excel" />
                        <input type="button" id="btnExport" value="Export To Pdf" onclick="Export()" hidden />
                      </div>
                      <br>
                      <table id="example1" class="table table-bordered ">
                        <thead>
                          <tr>
                            <th>SNo.</th>
                            <th>Name</th>
                            <th>Time</th>
                            <th>Remark</th>
                            <th>Location</th>
                            <th>Map</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          for ($i = $stdate; $i <= $endate; $i = $i + 86400) {
                           
                          ?>
                            <?php
                            $start_time = strtotime(date("d-m-Y 00:00:00", $i));
                            $end_time = strtotime(date("d-m-Y 23:59:59", $i));
                            if(isset($_POST['emp'])){
                              if($_POST['emp']==0){
                                $fd = $this->web->getUserFdByDate($start_time, $end_time, $id = $this->web->session->userdata('login_id'));
                              }else{
                                $fd = $this->web->getUserIdFdByDate($start_time, $end_time,$id = $this->web->session->userdata('login_id'),$_POST['emp']);
                              }
                            }
                            if (!empty($fd) && $fd[0]->io_time != 0) {
                              echo " <tr> <td colspan='5'> <h5> " . date("d-M", $i) . " </h5></td></tr>";
                            ?>
                              <tr>
                                <?php
                                if($this->session->userdata()['type']=='P'){
                                  if($role[0]->type!=1){
                                    $departments = explode(",",$role[0]->department);
                                    $sections = explode(",",$role[0]->section);
                                    foreach ($fd as $key => $dataVal) {
                                      $uname = $this->web->getNameByUserId($dataVal->user_id);
                                      $roleDp = array_search($uname[0]->department,$departments);
                                      $roleSection = array_search($uname[0]->section,$sections);
                                      if(!is_bool($roleDp) || !is_bool($roleSection)){
                                        
                                      }else{
                                        unset($fd[$key]);
                                      }
                                    }
                                  }
                                }
                                $count = 1;
                                foreach ($fd as $fd) {
                                ?>
                                  <td><?php echo $count++; ?></td>
                                  <td> <?php $uname = $this->web->getNameByUserId($fd->user_id);
                                        echo $uname[0]->name; ?>
                                  </td>
                                  <td> <?php
                                        $field = $fd->io_time;
                                        echo date('h:i:A', $field); ?>
                                  </td>
                                  <td>
                                    <?php
                                    echo $field = $fd->emp_comment;
                                    ?>
                                  </td>
                                  <td> <?php
                                        echo $field = $fd->location;
                                        ?>
                                  </td>
                                  <td>
                                    <a href="http://maps.google.com/?q=<?=$fd->latitude;?>,<?=$fd->longitude;?>" target="_blank"><i class="fa fa-map-marker" aria-hidden="true"></i></a>
                                  </td>
                                  <?php ?>
                              </tr>
                        <?php }
                              }
                            } ?>
                        </tbody>
                      </table>
                    <?php }
                    ?>
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
    <?php $this->load->view('menu/footer') ?>

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
  <script src="<?php echo base_url('adminassets/plugins/jquery/jquery.min.js') ?>"></script>
  <!-- Bootstrap 4 -->
  <script src="<?php echo base_url('adminassets/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
  <!-- bs-custom-file-input -->
  <script src="<?php echo base_url('adminassets/plugins/bs-custom-file-input/bs-custom-file-input.min.js') ?>"></script>
  <!-- AdminLTE App -->
  <script src="<?php echo base_url('adminassets/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
  <!-- Select2 -->
  <script src="<?php echo base_url('adminassets/plugins/select2/js/select2.full.min.js') ?>"></script>
  <script src="<?php echo base_url('adminassets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
  <script src="<?php echo base_url('adminassets/plugins/datatables-responsive/js/dataTables.responsive.min.js') ?>"></script>
  <script src="<?php echo base_url('adminassets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') ?>"></script>
  <script src="<?php echo base_url('adminassets/dist/js/adminlte.min.js') ?>"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="<?php echo base_url('adminassets/dist/js/demo.js') ?>"></script>

  <script>
    $(document).ready(function() {
      $('.nav-link').click(function(e) {
        $('.nav-link').removeClass('active');
        $(this).addClass("active");

      });
    });

    $(function() {
      var url = window.location;
      // for single sidebar menu
      $('ul.nav-sidebar a').filter(function() {
        return this.href == url;
      }).addClass('active');

      // for sidebar menu and treeview
      $('ul.nav-treeview a').filter(function() {
          return this.href == url;
        }).parentsUntil(".nav-sidebar > .nav-treeview")
        .css({
          'display': 'block'
        })
        .addClass('menu-open').prev('a')
        .addClass('active');
    });
  </script>



  <script>
    function export_datas() {
      let data = document.getElementById('example1');
      var fp = XLSX.utils.table_to_book(data, {
        sheet: 'Report'
      });
      XLSX.write(fp, {
        bookType: 'xlsx',
        type: 'base64'
      });
      XLSX.writeFile(fp, 'Employee Attendance.xlsx');
    }
  </script>

  <script type="text/javascript">
    function Export() {
      html2canvas(document.getElementById('example1'), {
        onrendered: function(canvas) {
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