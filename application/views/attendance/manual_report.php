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
                <li class="breadcrumb-item active">Manual Report</li>
              </ol>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>

      <?php
      if ($this->session->userdata()['type'] == 'B' || $this->session->userdata()['type'] == 'P') {

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
        if($this->session->userdata()['type']=='B' || $role[0]->manual_att=="1"){?>
          <div class="container-fluid">
            <div class="row">
              <!-- left column -->
              <div class="col-md-12">
                <span style="color: red"><?php echo $this->session->flashdata('msg'); ?></span>
                <!-- /.card -->
              </div>
            </div>

            <div class="row">
              <!-- left column -->
              <div class="col-md-12">
                <div class="card card-primary">
                  <div class="card-header">
                    <h3 class="card-title">Manual Report</h3>
                  </div>
                  <div class="card-body">
                    <h5> Select Employee</h5>
                    <div class="row">
                      <div class="col-lg-7 float-left">
                        <form action="<?php echo base_url('User/manualReport') ?>" method="POST">
                          <div class="row">
                            <div class="col-lg-3 ">
                              <select name="emp" class="form-control" name="emp" id="emp" required>
                                <option value="0">All Employee</option>
                              <?php
                            $res = $this->web->getActiveEmployeesList($bid);
                            if($this->session->userdata()['type']=='P'){
                              $role=$this->web->getRollbyid($this->web->session->userdata('login_id'),$bid);
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
                                } }
                              }
                            }
                            foreach ($res as $res) :
                              $uname = $this->web->getNameByUserId($res->user_id); ?>
                                <option value="<?php echo $uname[0]->id  ?>" <?php if($res->user_id==$id){ echo "selected";}?>><?php echo $uname[0]->name; ?></option>
                              <?php endforeach; ?>
                              </select>
                            </div>
                            <div class="col-lg-3 ">
                              <input type="date" name="start_date" id="start_date" value="<?php echo $start_date; ?>" class="form-control" max="<?php echo $end_date; ?>" onchange="startChange(event);">

                            </div>
                            <div class="col-lg-3">
                              <input type="date" name="end_date" id="end_date" value="<?php echo $end_date; ?>" class="form-control" max="<?php echo date('Y-m-d'); ?>" min="<?php echo $start_date; ?>" onchange="endChange(event);">
                            </div>
                            <div class="col-lg-2">
                              <button type="submit" class="btn btn-success btn-fill btn-block">Show</button>
                            </div>
                          </div>
                        </form>
                      </div>
                    </div>
                    <br><br>
                    <?php
                    if ($load) {
                      $stdate = strtotime($start_date);
                      $endate = strtotime($end_date);
                    ?>
                      <h5>Attendance for Date:-<?php echo date("d-M-Y ", $stdate) ?> to Date:- <?php echo date("d-M-Y ", $endate) ?> </h5>
                      <?php 
                        // print_r($usersData);
                      ?>
                      <?php 
                      foreach($usersData as $user){
                        echo "<h4>".$user['name']."</h4>";
                      ?>
                      <table id="exam" class="table table-bordered table-striped">
                        <thead>
                          <tr>
                            <th>Date</th>
                            <th>Mode</th>
                            <th>Time</th>
                            <th>Remark</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php 
                            foreach($user['data'] as $day){
                          ?>
                          <tr>
                            <td><?= $day['date']?></td>
                            <td><?= $day['time']?></td>
                            <td><?= $day['mode']?></td>
                            <td><?= $day['comment']?></td>
                          </tr>
                          <?php
                            }
                          ?>
                        </tbody>
                      </table>
                    <?php
                      }
                    }
                    ?>
                  </div>
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
        <?php }?>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <div class="modal fade" id="addInModal" tabindex="-1" role="dialog" aria-labelledby="addInModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" id="addInModalLabel">Add In Attendance</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          </div>
          <form action="<?php echo base_url('User/addManualAttendance') ?>" method="post">
            <div class="modal-body">
              <div id="modform">
                <div class="card-body">
                  <div class="row">
                    <div class="from-group col-md-4">
                      <label for="depart">Time</label>
                      <input type="time" class="form-control" name="addTime" id="addTime">
                    </div>
                    <div class="from-group col-md-8">
                      <label for="remark">Reason</label>
                      <input type="textarea" class="form-control" name="reason" placeholder="Enter reason" id="reason">
                    </div>
                    <input type="text" class="form-control" value=" <?php echo $bid ?>" name="buid" hidden>
                    <input type="text" class="form-control" value="<?php echo $id ?>" name="uid" hidden>
                    <input type="text" class="form-control" id="addDate" name="addDate" hidden>
                    <input type="text" class="form-control" id="mode" name="mode" value="in" hidden>
                    <input type="text" class="form-control" id="startDate" name="startDate" value="<?= $stdate?>" hidden>
                    <input type="text" class="form-control" id="endDate" name="endDate" value="<?= $endate?>" hidden>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="submit" class=" btn btn-success">Add</button>
            </div>
          </form>
        </div>
      </div>
    </div>
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

  <!-- jQuery  -->

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
    $(function() {
      var table = $('#example1').DataTable({
        "responsive": true,
        "autoWidth": false,
      });

    });

    function changeAddDate(date, mode) {
      $("#addDate").val(date);
      $("#mode").val(mode);
      $("#addInModalLabel").text("Add " + mode + " Attendance");
    }
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
    function changeAttendDate(e, id) {
      $.ajax({
        type: "POST",
        url: "User/changeAttendDate",
        data: {
          id: id,
          AttendDate: e.target.value
        },
        success: function() {

        }
      });
    }
  </script>
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
      let data = document.getElementById('example3');
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
      html2canvas(document.getElementById('example3'), {
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
  <script>
    function changeOutTo(e, buid, id, i) {


      $.ajax({
        type: "POST",
        url: "User/changeOutToDate",
        data: {
          out_times: e.target.value,
          buid: buid,
          id: id,
          i: i
        },
        success: function() {

        }
      });
    }

    function changeAttTo(e, buid, id, i) {


      $.ajax({
        type: "POST",
        url: "User/changeAttToDate",
        data: {
          in_times: e.target.value,
          buid: buid,
          id: id,
          i: i
        },
        success: function() {

        }
      });
    }
    $(function() {
      var table = $('#example3').DataTable({
        "responsive": true,
        "autoWidth": false,
      });
    });
  </script>

  <script>
    function removeManualAtt(id) {
      $.ajax({
        type: "POST",
        url: "removeManualAtt",
        data: {
          id
        },
        success: function() {
          location.reload();
        }

      })
    }
  </script>
</body>

</html>