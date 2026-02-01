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
                <li class="breadcrumb-item active">Daily Report</li>
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
        if($this->session->userdata()['type']=='B' || $role[0]->daily_report=="1" || $role[0]->type=="1"){?>
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
                        <form action="<?php echo base_url('User/daily_report2')?>" method="POST">
                          <div class="row">
                            <div class="col-sm-2">

                              <input type="date" name="start_date" id="start_date"  value="<?php echo $start_date; ?>" class="form-control"  max="<?php echo date('Y-m-d'); ?>" onchange="startChange(event);">
                            </div>
                            <div class="col-sm-2">
                              <select name="depart" class="form-control">
                                <option value='all'>All Departments</option>
                                <?php foreach($departments as $dp){
                                  $sl="";
                                  if($dp->id==$depart){
                                    $sl="selected";
                                  }
                                  echo "<option value='".$dp->id."' $sl>$dp->name</option>";
                                }?>
                              </select>
                            </div>
                            <div class="col-sm-2">
                              <select name="shift" class="form-control">
                                <option value='all'>All Shifts</option>
                                <?php foreach($shifts as $sf){
                                  $sl="";
                                  if($sf->id==$shift){
                                    $sl="selected";
                                  }
                                  echo "<option value='".$sf->id."' $sl>$sf->name</option>";
                                }?>
                              </select>
                            </div>
                            <div class="col-sm-2">
                              <select name="section" class="form-control">
                                <option value='all'>All Sections</option>
                                <?php foreach($sections as $sec){
                                  $sl="";
                                  if($sec->type==$section){
                                    $sl="selected";
                                  }
                                  echo "<option value='".$sec->type."' $sl>$sec->name</option>";
                                }?>
                              </select>
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
                              <button type="button" id="" onClick="setAction('present');" class="btn btn-success btn-fill btn-block">Present : <?php echo $totalPresent;?></button>
                            </div>
                            <div class="col-2">
                              <button type="button" id="" onClick="setAction('absent');" class="btn btn-danger btn-fill btn-block">Absent: <?php echo $totalAbsent;?></button>
                            </div>
                            <div class="col-2">
                              <button type="button" id="" onClick="setAction('mispunch');" class="btn btn-success btn-fill btn-block">Mispunch: <?php echo $totalMispunch;?></button>
                            </div>
                            <div class="col-2">
                              <button type="button" id="" onClick="setAction('halfday');" class="btn btn-success btn-fill btn-block">Half day: <?php echo $totalHalfDay;?></button>
                            </div>
                            <div class="col-2">
                              <button type="button" id="" onClick="setAction('manual');" class="btn btn-success btn-fill btn-block">Manual : <?php echo $totalManual;?></button>
                            </div>
                          </div>
                          <br>
                          <div class="row">
                            <div class="col-2">
                              <button type="button" id="" onClick="setAction('late');" class="btn btn-success btn-fill btn-block">Late: <?php echo $totalLate;?></button>
                            </div>

                            <div class="col-2">
                              <button type="button" id="" onClick="setAction('early');" class="btn btn-success btn-fill btn-block">Early : <?php echo $totalEarly;?></button>
                            </div>
                            <div class="col-2">
                              <button type="button" id="" onClick="setAction('shortLeave');" class="btn btn-success btn-fill btn-block">Short Leave : <?php echo $totalShortLeave;?></button>
                            </div>

                            <div class="col-2">
                              <button type="button" id="" onClick="setAction('unverified');" class="btn btn-success btn-fill btn-block">Unverified:- <?php echo $totalUnverified;?></button>
                            </div>

                            <div class="col-2">
                              <button type="button" id="" onClick="setAction('fieldDuty');" class="btn btn-success btn-fill btn-block">Field Duty : <?php echo $totalFieldDuty;?></button>
                            </div>
                            <div class="col-2">
                              <button type="button" id="" onClick="setAction('gps');" class="btn btn-success btn-fill btn-block">Gps : <?php echo $totalGps;?></button>
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
                      <h5>Attendance for Date new:-<?php echo date("d-M-Y ",$stdate)?>  </h5>
                      <div align="right">
                        <input type="button" onClick="exportExcel()" value="Export To Excel" />
                        <input type="button"  id="btnExport" value="Export To Pdf" onclick="exportPDF()" />
                        <br>
                      </div>
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
                            <th>Desig</th>
                            <th>Shift</th>
                            <th>IN</th>
                            <th>Out</th>
                            <th>Status</th>
                            <th>W.H</th>
                            <th>Late IN</th>
                            <th>Early Out</th>
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
                              <td><?php echo $user['designation'];?></td>
                              <td><?php echo $user['group_name']."<br>".$user['shift_start']."<br>".$user['shift_end']; ?></td>
                              <td>
                                <?php
                                foreach($user['data'] as $day_data){
                                  if($day_data['mode']=="in"){
                                    $time_st = "QR";
                                    $spanClass= "";
                                    if($day_data['manual']=="1"){
                                      $time_st = "M";
                                      $spanClass= "text-danger";
                                    }
                                    if($day_data['location']!=""){
                                      $time_st = "G";
                                      $spanClass= "text-primary";
                                    }
                                    echo "<span class='".$spanClass."'>".date('h:i:A', $day_data['time'])."&nbsp;".$time_st."</span></br>";
                                  }
                                }
                                ?>
                                </td>
                                <td>
                                  <?php
                                  foreach($user['data'] as $day_data){
                                    if($day_data['mode']=="out"){
                                      $time_st = "QR";
                                      $spanClass= "";
                                      if($day_data['manual']=="1"){
                                        $time_st = "M";
                                        $spanClass= "text-danger";
                                      }
                                      if($day_data['location']!=""){
                                        $time_st = "G";
                                        $spanClass= "text-primary";
                                      }
                                      echo "<span class='".$spanClass."'>".date('h:i:A', $day_data['time'])."&nbsp;".$time_st."</span></br>";
                                    }
                                  }
                                  ?>
                                  </td>
                                  <td>
                                    <?php
                                    if(!empty($user['data'])){
                                      $st = "P";
                                      if($user['absent']=="1"){
                                        $st = "A";
                                      }
                                      if($user['weekly_off']=="1"){
                                        $st = "W";
                                      }
                                      if($user['holiday']=="1"){
                                        $st = "H";
                                      }
                                      if($user['leave']=="1"){
                                        $st = "L";
                                      }
                                      $msOut = true;
                                      foreach($user['data'] as $day_data){
                                        if($day_data['mode']=="out"){
                                          $msOut = false;
                                        }
                                      }
                                      if($user['mispunch']=="1" && $msOut){
                                        if($start_date!=date("Y-m-d")){
                                          $st="MS";
                                        }
                                        
                                      }else if($user['halfday']=="1"){
                                        $st="P/2";
                                      }else if($user['sl']=="SL"){
                                        $st = "SL";
                                      }
                                    }else{
                                      $st = "A";
                                      if($user['weekly_off']=="1"){
                                        $st = "W";
                                      }
                                      if($user['holiday']=="1"){
                                        $st = "H";
                                      }
                                      if($user['leave']=="1"){
                                        $st = "L";
                                      }
                                    }
                                    echo $st;
                                     ?>
                                  </td>
                                  <td><?php echo $user['workingHrs'];?></td>
                                  <td><?php echo $user['late_hrs'];?></td>
                                  <td><?php echo $user['early_hrs'];?></td>
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
      <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" id="myModalLabel">Edit Department</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
              <div id="modform">

              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>

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

    <script>

    function export_datas(){
      let data=document.getElementById('example1');
      var fp=XLSX.utils.table_to_book(data,{sheet:'Report'});
      XLSX.write(fp,{
        bookType:'xlsx',
        type:'base64'
      });
      XLSX.writeFile(fp, 'Employee Attendance.xlsx');
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

  function exportExcel(){
    var wb = new ExcelJS.Workbook();
      var sh = wb.addWorksheet("Report");
      sh.columns = [
        {header: 'SNo.', key: 'SNo', width: 10},
        {header: 'Name', key: 'Name', width: 20},
        {header: 'Designation', key: 'Designation', width: 30},
        {header: 'Shift', key: 'Shift', width: 20},
        {header: 'IN', key: 'IN', width: 20},
        {header: 'Out', key: 'Out', width: 20},
        {header: 'Status', key: 'Status', width: 20},
        {header: 'WH', key: 'WH', width: 20},
        {header: 'LateIn', key: 'LateIn', width: 20},
        {header: 'EarlyOut', key: 'EarlyOut', width: 20}
      ];
      <?php
        $count=1;
        foreach($report as $user){
          $shift = $user['group_name'].'\n'.$user['shift_start'].'\n'.$user['shift_end'];
          $allIns = "";
          foreach($user['data'] as $day_data){
            if($day_data['mode']=="in"){
              $time_st = "QR";
              if($day_data['manual']=="1"){
                $time_st = "M";
              }
              if($day_data['location']!=""){
                $time_st = "G";
              }
              $allIns= $allIns.date('h:i:A', $day_data['time']).' '.$time_st.'\n';
            }
          }
          $allOuts = "";
          foreach($user['data'] as $day_data){
            if($day_data['mode']=="out"){
              $time_st = "QR";
              if($day_data['manual']=="1"){
                $time_st = "M";
              }
              if($day_data['location']!=""){
                $time_st = "G";
              }
              $allOuts= $allOuts.date('h:i:A', $day_data['time']).' '.$time_st.'\n';
            }
          }
          if(!empty($user['data'])){
            $st = "P";
            if($user['absent']=="1"){
              $st = "A";
            }
            if($user['weekly_off']=="1"){
              $st = "W";
            }
            if($user['holiday']=="1"){
              $st = "H";
            }
            if($user['leave']=="1"){
              $st = "L";
            }
            $msOut = true;
            foreach($user['data'] as $day_data){
              if($day_data['mode']=="out"){
                $msOut = false;
              }
            }
            if($user['mispunch']=="1" && $msOut){
              if($start_date!=date("Y-m-d")){
                $st="MS";
              }
              
            }else if($user['halfday']=="1"){
              $st="P/2";
            }else if($user['sl']=="SL"){
              $st = "SL";
            }
          }else{
            $st = "A";
            if($user['weekly_off']=="1"){
              $st = "W";
            }
            if($user['holiday']=="1"){
              $st = "H";
            }
            if($user['leave']=="1"){
              $st = "L";
            }
          }
        ?>
        sh.addRow({SNo:'<?php echo $count++;?>',Name:'<?php echo $user['name'];?>',Designation:'<?php echo $user['designation'];?>',Shift:'<?= $shift;?>',IN:'<?= $allIns;?>',Out:'<?= $allOuts;?>',Status:'<?= $st;?>',WH:'<?= $user['workingHrs'];?>',LateIn:'<?= $user['late_hrs'];?>',EarlyOut:'<?= $user['early_hrs'];?>'});
      <?php 
        }?>
      wb.xlsx.writeBuffer().then((data) => {
        const blob = new Blob([data], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8' });
        saveAs(blob, 'Daily Attendance.xlsx');
      });
  }

  function createHeaders(keys) {
    var result = [];
    result.push({id: 'SNo',name: 'SNo',prompt: 'SNo.',width: 25,align: 'center',padding: 0});
    result.push({id: 'Name',name: 'Name',prompt: 'Name',width: 45,align: 'center',padding: 0});
    result.push({id: 'Designation',name: 'Designation',prompt: 'Designation',width: 45,align: 'center',padding: 0});
    result.push({id: 'Shift',name: 'Shift',prompt: 'Shift',width: 40,align: 'center',padding: 0});
    result.push({id: 'IN',name: 'IN',prompt: 'IN',width: 40,align: 'center',padding: 0});
    result.push({id: 'Out',name: 'Out',prompt: 'Out',width: 40,align: 'center',padding: 0});
    result.push({id: 'Status',name: 'Status',prompt: 'Status',width: 30,align: 'center',padding: 0});
    result.push({id: 'WH',name: 'WH',prompt: 'WH',width: 30,align: 'center',padding: 0});
    result.push({id: 'LateIn',name: 'LateIn',prompt: 'LateIn',width: 40,align: 'center',padding: 0});
    result.push({id: 'EarlyOut',name: 'EarlyOut',prompt: 'EarlyOut',width: 40,align: 'center',padding: 0});
    return result;
  }
  function exportPDF(){
    var result = [];
    <?php
        $count=1;
        foreach($report as $user){
          $shift = $user['group_name'].'\n'.$user['shift_start'].'\n'.$user['shift_end'];
          $allIns = "";
          foreach($user['data'] as $day_data){
            if($day_data['mode']=="in"){
              $time_st = "QR";
              if($day_data['manual']=="1"){
                $time_st = "M";
              }
              if($day_data['location']!=""){
                $time_st = "G";
              }
              $allIns= $allIns.date('h:i:A', $day_data['time']).' '.$time_st.'\n';
            }
          }
          if($allIns==""){
            $allIns=" ";
          }
          $allOuts = "";
          foreach($user['data'] as $day_data){
            if($day_data['mode']=="out"){
              $time_st = "QR";
              if($day_data['manual']=="1"){
                $time_st = "M";
              }
              if($day_data['location']!=""){
                $time_st = "G";
              }
              $allOuts= $allOuts.date('h:i:A', $day_data['time']).' '.$time_st.'\n';
            }
          }
          if($allOuts==""){
            $allOuts=" ";
          }
          if(!empty($user['data'])){
            $st = "P";
            if($user['absent']=="1"){
              $st = "A";
            }
            if($user['weekly_off']=="1"){
              $st = "W";
            }
            if($user['holiday']=="1"){
              $st = "H";
            }
            if($user['leave']=="1"){
              $st = "L";
            }
            $msOut = true;
            foreach($user['data'] as $day_data){
              if($day_data['mode']=="out"){
                $msOut = false;
              }
            }
            if($user['mispunch']=="1" && $msOut){
              if($start_date!=date("Y-m-d")){
                $st="MS";
              }
              
            }else if($user['halfday']=="1"){
              $st="P/2";
            }else if($user['sl']=="SL"){
              $st = "SL";
            }
          }else{
            $st = "A";
            if($user['weekly_off']=="1"){
              $st = "W";
            }
            if($user['holiday']=="1"){
              $st = "H";
            }
            if($user['leave']=="1"){
              $st = "L";
            }
          }
          ?>
        var data = {id:"<?php echo $count;?>",SNo:"<?php echo $count++;?>",Name:"<?php echo $user["name"];?>",Designation:"<?php echo $user["designation"];?> ",Shift:"<?= $shift;?>",IN:"<?= $allIns;?>",Out:"<?= $allOuts;?>",Status:"<?= $st;?>",WH:"<?= $user["workingHrs"];?>",LateIn:"<?= $user["late_hrs"];?>",EarlyOut:"<?= $user["early_hrs"];?>"};
        result.push(Object.assign({}, data));
    <?php }?>
    var headers = createHeaders();
    var doc = new jspdf.jsPDF("landscape");
    doc.setFontSize(10);
    doc.table(3, 5, result, headers, { autoSize: false,fontSize:10,padding:1,margins:{left:0,top:3,bottom:3, right:0} });
    doc.save("Daily-Report.pdf");
  }
</script>
</body>
</html>
