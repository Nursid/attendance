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
  <link rel="stylesheet" href="<?php echo base_url('adminassets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')?>">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url('adminassets/dist/css/adminlte.min.css')?>">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo base_url('adminassets/dist/css/mid.css')?>">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css"/>
  <link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/4.2.1/css/fixedColumns.dataTables.min.css"/>
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
          $bid = $this->session->userdata('empCompany');
          $role=$this->web->getRollbyid($this->web->session->userdata('login_id'),$bid);
        } else {
          $bid = $this->web->session->userdata('login_id');
        }
        ?>
        <!-- Main content -->
        <section class="content">
        <?php
        if($this->session->userdata()['type']=='B' || $role[0]->other_report=="1" || $role[0]->type=="1"){?>
          <div class="container-fluid">
            <div class="loading">Loading&#8230;</div>
            <div class="row">
              <!-- left column -->
              <div class="col-md-12">
                <div class="card card-danger">
                  <div class="card-header">
                    <h3 class="card-title">Monthly Report</h3>
                  </div>
                  <div class="card-body">
                    <h5> Select Date Range</h5>
                    <div class="row">
                      <div class="col-lg-12 float-left">
                        <form action="<?php echo base_url('User/monthly_report2')?>" method="POST" id="monthlyReport">
                          <div class="row">
                            <div class="col-sm-2">
                              <input type="date" name="start_date" id="start_date"  value="<?php echo $start_date; ?>" class="form-control" max="<?php echo $end_date; ?>" onchange="startChange(event);">
                            </div>
                            <div class="col-sm-2">
                              <input type="date" name="end_date" id="end_date"  value="<?php echo $end_date; ?>"class="form-control" max="<?php echo date('Y-m-d'); ?>" min="<?php echo $start_date;?>" onchange="endChange(event);">
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
                            <div class="col-sm-2" hidden>
                              <button type="button" class="btn btn-primary btn-fill btn-block" onclick="reCalculateReport('1')">ReCalculate</button>
                            </div>
                          </div>
                          <br>
                          <div class="row" hidden>
                            <div class="col-xs mr-4 form-check">
                              <input class="form-check-input" type="checkbox" name="status_check" value="status" <?php if($status_check==1){echo 'checked';}?>>
                              <label class="form-check-label">Status</label>
                            </div>
                            <div class="col-xs mr-4 form-check">
                              <input class="form-check-input" type="checkbox" name="working_check" value="working" <?php if($working_check==1){echo 'checked';}?>>
                              <label class="form-check-label">Working Hours</label>
                            </div>
                            <div class="col-xs mr-4 form-check">
                              <input class="form-check-input" type="checkbox" name="totals_check" value="totals" <?php if($totals_check==1){echo 'checked';}?>>
                              <label class="form-check-label">Totals</label>
                            </div>
                            <div class="col-xs mr-4 form-check">
                              <input class="form-check-input" type="checkbox" name="all_check" value="all" <?php if($all_check==1){echo 'checked';}?>>
                              <label class="form-check-label">All Punch</label>
                            </div>
                            <div class="col-xs mr-4 form-check">
                              <input class="form-check-input" type="checkbox" name="two_check" value="two" <?php if($two_check==1){echo 'checked';}?>>
                              <label class="form-check-label">Two Punch</label>
                            </div>
                            <div class="col-xs mr-4 form-check">
                              <input class="form-check-input" type="checkbox" name="late_check" value="late" <?php if($late_check==1){echo 'checked';}?>>
                              <label class="form-check-label">Late</label>
                            </div>
                            <div class="col-xs mr-4 form-check">
                              <input class="form-check-input" type="checkbox" name="early_check" value="early" <?php if($early_check==1){echo 'checked';}?>>
                              <label class="form-check-label">Early</label>
                            </div>
                          </div>
                          <div class="row" hidden>
                            <div class="col-xs form-check">
                              <input class="form-check-input" type="checkbox" name="recalculate_check" value="recalculate_check" id="recalculateCheck">
                              <label class="form-check-label">Re Calculate</label>
                            </div>
                            <div class="col-xs">
                            <input type="text" name="action" value="<?= $action?>" id="action" hidden>
                            </div>
                          </div>
                          <div class="row" hidden>
                            <div class="col-sm-1">
                              <button type="submit" id="show_report" class="btn btn-success btn-fill btn-block" onclick="showLoader()">Show</button>
                            </div>
                          </div>
                          <div class="row">
                              <div class="col-sm-2">
                                <button type="button" class="btn btn-primary btn-fill btn-block" onclick="getReport('1')">Attendance Register</button>
                              </div>
                              <div class="col-sm-2">
                                <button type="button" class="btn btn-primary btn-fill btn-block" onclick="getReport('2')">Performance Summary</button>
                              </div>
                              <div class="col-sm-2">
                                <button type="button" class="btn btn-primary btn-fill btn-block" onclick="getReport('3')">Performance Report</button>
                              </div>
                              <div class="col-sm-2">
                                <button type="button" class="btn btn-primary btn-fill btn-block" onclick="getReport('4')">Punch Logs</button>
                              </div>
                              <div class="col-sm-2">
                                <button type="button" class="btn btn-primary btn-fill btn-block" onclick="getReport('5')">Late Register</button>
                              </div>
                              
                            </div>
                          </form>
                        </div>
                      </div>
                      <br>
                      <?php
                      if($load) {
                        $stdate=strtotime($start_date);
                        $endate=strtotime($end_date);
                        ?>
                        <div class="row">
                          <div class="col-sm-2">
                            <h6>P= Present</h6>
                          </div>
                          <div class="col-sm-2">
                            <h6>P/2= HalfDay</h6>
                          </div>
                          <div class="col-sm-2">
                            <h6>A= Absent</h6>
                          </div>
                          <div class="col-sm-2">
                            <h6>W= WeeklyOff</h6>
                          </div>
                          <div class="col-sm-2">
                            <h6>H= Holiday</h6>
                          </div>
                          <div class="col-sm-2">
                            <h6>MS= Mispunch</h6>
                          </div>
                          <div class="col-sm-2">
                            <h6>L= Leave</h6>
                          </div>
                          <div class="col-sm-2">
                            <h6>SL= ShortLeave</h6>
                          </div>
                          <div class="col-sm-2">
                            <h6>ED= Extra Day</h6>
                          </div>
                          <div class="col-sm-2">
                            <h6>OT= Overtime</h6>
                          </div>
                          <div class="col-sm-2">
                            <h6>NWD= NetWorkingDay</h6>
                          </div>
                          <div class="col-sm-2">
                            <h6>WH= Working Hour</h6>
                          </div>
                        </div>
                        <h5>Attendance for Date:-<?php echo date("d-M-Y ",$stdate)?> to Date:- <?php echo date("d-M-Y ",$endate)?> </h5>
                        
                        <div align="right">
                          <input type="button" onClick="export_datas()" value="Export To Excel" />
                          <input type="button"  id="btnExport" value="Export To Pdf" onclick="exportPDF()" />
                          <br>
                        </div>
                        <table id="example1" class="table table-bordered table-responsive">
                          <thead>
                            <tr>
                              <th>SNo.</th>
                              <th>Empcode</th>
                              <th>Name</th>
                              <?php
                              if($status_check=="1" || $working_check=="1" || $all_check=="1" || $two_check=="1" || $late_check=="1" || $early_check=="1"){
                               /* foreach($days as $day){
                                  echo "<th>$day </th>";
                                }*/
                               foreach($daysweek as $dayweek){
                                  echo "<th>$dayweek </th>";
                                }
                              }
                              ?>
                              <?php if($totals_check==1){?>
                                <th>P</th>
                                <th>P2</th>
                                <th>A</th>
                                <th>WO</th>
                                <th>H</th>
                                <th>L</th>
                                <th>SL</th>
                               <th>ED</th>
                                <th>NWD</th>
                                <th>WH</th>
                              <?php }?>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                            $count=1;
                            foreach($report as $user){
                              ?>
                              <tr>
                                <td><?php echo $count++;?></td>
                                <td><?php echo $user['emp_code'];?></td>
                                <td><?php echo $user['name'];?></td>
                                <?php
                                if($status_check=="1" || $working_check=="1" || $all_check=="1" || $two_check=="1" || $late_check=="1" || $early_check=="1"){
                                  foreach($user['data'] as $day){
                                    if(!empty($day['data'])){
                                      echo "<td>";
                                      if($all_check==1){
                                        foreach($day['data'] as $day_data){
                                          $time_st = "";
                                          if($day_data['manual']=="1"){
                                            $time_st = "M";
                                          }
                                          if($day_data['location']!=""){
                                            $time_st = "G";
                                          }
                                          echo strtoupper($day_data['mode'])."&nbsp;".date('h:i:A', $day_data['time'])."&nbsp;".$time_st."</br>";
                                        }
                                      }else if($two_check==1){
                                        if(count($day['data'])>=2){
                                          $time_st = "";
                                          if($day['data'][0]['manual']=="1"){
                                            $time_st = "M";
                                          }
                                          if($day['data'][0]['location']!=""){
                                            $time_st = "G";
                                          }
                                          echo date('h:i:A', $day['data'][0]['time'])."&nbsp;".$time_st."</br>";
                                          $time_st = "";
                                          if($day['data'][count($day['data'])-1]['manual']=="1"){
                                            $time_st = "M";
                                          }
                                          if($day['data'][count($day['data'])-1]['location']!=""){
                                            $time_st = "G";
                                          }
                                          echo strtoupper($day['data'][count($day['data'])-1]['mode'])."&nbsp;".date('h:i:A', $day['data'][count($day['data'])-1]['time'])."&nbsp;".$time_st."</br>";
                                        }else{
                                          $time_st = "";
                                          if($day['data'][0]['manual']=="1"){
                                            $time_st = "M";
                                          }
                                          if($day['data'][0]['location']!=""){
                                            $time_st = "G";
                                          }
                                          echo date('h:i:A', $day['data'][0]['time'])."&nbsp;".$time_st."</br>";
                                        }
                                      }

                                      $wtes = "";
                                      if($working_check==1){
                                        $wtes = $day['workingHrs']."<br>";
                                      }
                                      if($late_check==1){
                                        $wtes = $wtes."LT&nbsp;".$day['late_hrs']."&nbsp;Hr<br>";
                                      }
                                      if($early_check==1){
                                        $wtes = $wtes."EL&nbsp;".$day['early_hrs']."&nbsp;Hr<br>";
                                      }
                                      if($day['ot_seconds']>60){
                                        $wtes = $wtes."OT&nbsp;".$day['ot_hrs']."&nbsp;Hr<br>";
                                      }
                                      if($status_check==1){
                                        $st = "P";
                                        if($day['weekly_off']=="1"){
                                          $st = "ED";
                                        }
                                        if($day['holiday']=="1"){
                                          $st = "ED";
                                        }
                                        if($day['absent']=="1"){
                                          $st = "A";
                                        }
                                        if($day['leave']=="1"){
                                          $st = "L";
                                        }
                                        $msOut = true;
                                        foreach($day['data'] as $day_data){
                                          if($day_data['mode']=="out"){
                                            $msOut = false;
                                          }
                                        }
                                        if($day['mispunch']=="1" && $msOut){
                                          $st="MS";
                                        }else if($day['halfday']=="1"){
                                          $st="P/2";
                                        }else if($day['sl']=="SL"){
                                          $st = "SL";
                                        }
                                        //$wtes = $wtes.$st;

                                        $wtes = $wtes.$day['day_status']."<br>".$day['day_sub_status'];
                                      }
                                      echo $wtes."</td>";
                                    }else{ if($status_check==1){
                                      $st = "A";
                                      if($day['weekly_off']=="1"){
                                        $st = "W";
                                      }
                                      if($day['holiday']=="1"){
                                        $st = "H";
                                      }
                                      if($day['leave']=="1"){
                                        $st = "L";
                                      }
                                      if($day['onduty']=="1"){
                                        $st = "OD";
                                      }
                                      if($day['wfhduty']=="1"){
                                        $st = "WFH";
                                      }
                                      // echo "<td>$st</td>";
                                      echo "<td>".$day['day_status']."<br>".$day['day_sub_status']."</td>";
                                    }else{
                                      echo "<td></td>";
                                    }}
                                  }
                                }
                                ?>
                                <?php if($totals_check==1){?>
                                  <td><?php echo $user['totalPresent'];?></td>
                                  <td><?php echo $user['totalP2'];?></td>
                                  <td><?php echo $user['totalAbsent'];?></td>
                                  <td><?php echo $user['totalWeekOff'];?></td>
                                  <td><?php echo $user['totalHoliday'];?></td>
                                  <td><?php echo $user['totalLeaves'];?></td>
                                  <td><?php echo $user['totalShortLeave'];?></td>
                                  <td><?php echo $user['totalOT'];?></td>
                                  <td><?php echo $user['nwd'];?></td>
                                  <td><?php echo $user['totalWorkingHrs'];?></td>
                                <?php }?>
                              </tr>
                            <?php }?>
                          </tbody>
                          <tfoot>
                          </tfoot>
                        </table>
                      </div>
                    <?php }
                    ?>
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
          <?php
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
    <!--<script src="<?php echo base_url('adminassets/plugins/datatables/jquery.dataTables.min.js')?>"></script>-->
    <!-- Select2 -->
    <script src="<?php echo base_url('adminassets/plugins/select2/js/select2.full.min.js')?>"></script>
    <script src="<?php echo base_url('adminassets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')?>"></script>
    <script src="<?php echo base_url('adminassets/plugins/datatables-responsive/js/dataTables.responsive.min.js')?>"></script>
    <script src="<?php echo base_url('adminassets/plugins/datatables-buttons/js/dataTables.buttons.min.js')?>"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="<?php echo base_url('adminassets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js')?>"></script>
    <script src="<?php echo base_url('adminassets/plugins/datatables-buttons/js/buttons.html5.min.js')?>"></script>
    <script src="<?php echo base_url('adminassets/plugins/datatables-buttons/js/buttons.print.min.js')?>"></script>
    <script src="<?php echo base_url('adminassets/plugins/datatables-buttons/js/buttons.colVis.min.js')?>"></script>

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

    function getReport(id){
      $("#action").val(id);
      $("#monthlyReport").submit();
      $(".loading").css("display","block");
    }
    function reCalculateReport(id){
      $("#action").val(id);
      $("#recalculateCheck").prop("checked",true);
      $("#monthlyReport").submit();
      $(".loading").css("display","block");
    }
    </script>



    <script>

    function export_datas(){
      let data=document.getElementById('example1');
      //var fp=XLSX.utils.table_to_book(data,{sheet:'Report'});
      // XLSX.write(fp,{
      //   bookType:'xlsx',
      //   type:'base64',
      // });
      //fp["!cos"] = [ { wch: 10 } ];
      //console.log(fp);
      //XLSX.writeFile(fp, 'Employee Attendance.xlsx');

      var wb = new ExcelJS.Workbook();
      var sh = wb.addWorksheet("Report");
      sh.columns = [
        {header: 'SNo.', key: 'SNo', width: 10},
        {header: 'Empcode', key: 'Empcode', width: 15},
        {header: 'Name', key: 'Name', width: 20,},
        <?php
        if($status_check=="1" || $working_check=="1" || $all_check=="1" || $two_check=="1" || $late_check=="1" || $early_check=="1"){
          foreach($days as $day){
            echo "{header: '$day', key: 'd$day', width: 15,},";
          }
        }
        if($totals_check==1){?>
          {header: 'P', key: 'TotalPresent', width: 6,},
          {header: 'P2', key: 'TotalP2', width: 6,},
          {header: 'A', key: 'TotalAbsent', width: 6,},
          {header: 'WO', key: 'TotalWeaklyOff', width: 6,},
          {header: 'H', key: 'TotalHoliday', width: 6,},
          {header: 'L', key: 'TotalLeaves', width: 6,},
          {header: 'SL', key: 'totalShortLeave', width: 6,},
          {header: 'OD', key: 'totalOT', width: 6,},
          {header: 'NWD', key: 'nwd', width: 6,},
          {header: 'WH', key: 'TotalWorkingHrs', width: 10,}
          <?php }
          ?>
        ];
        <?php
        $count=1;
        foreach($report as $user){
          ?>
          sh.addRow({SNo:'<?php echo $count;?>',Empcode:'<?php echo $user['emp_code'];?>',Name:'<?php echo $user['name'];?>',
          <?php
          if($status_check=="1" || $working_check=="1" || $all_check=="1" || $two_check=="1" || $late_check=="1" || $early_check=="1"){
              $dayCount = 0;
            foreach($user['data'] as $day){
              if(!empty($day['data'])){
                $val="";
                if($all_check==1){
                  $val = "";
                  foreach($day['data'] as $day_data){
                    $time_st = "QR";
                    if($day_data['manual']=="1"){
                      $time_st = "M";
                    }
                    if($day_data['location']!=""){
                      $time_st = "G";
                    }
                    $val = $val.strtoupper($day_data['mode'])." ".date('h:i:A', $day_data['time'])." ".$time_st.'\n';
                  }

                }else if($two_check==1){
                  if(count($day['data'])>=2){
                    $val = "";
                    $time_st = "";
                    if($day['data'][0]['manual']=="1"){
                      $time_st = "M";
                    }
                    if($day['data'][0]['location']!=""){
                      $time_st = "G";
                    }
                    $val = $val.date('h:i:A', $day['data'][0]['time'])." ".$time_st.'\n';
                    $time_st = "";
                    if($day['data'][count($day['data'])-1]['manual']=="1"){
                      $time_st = "M";
                    }
                    if($day['data'][count($day['data'])-1]['location']!=""){
                      $time_st = "G";
                    }
                    $val = $val.strtoupper($day['data'][count($day['data'])-1]['mode'])." ".date('h:i:A', $day['data'][count($day['data'])-1]['time'])." ".$time_st.'\n';
                  }else{
                    $val = "";
                    $time_st = "";
                    if($day['data'][0]['manual']=="1"){
                      $time_st = "M";
                    }
                    if($day['data'][0]['location']!=""){
                      $time_st = "G";
                    }
                    $val = date('h:i:A', $day['data'][0]['time'])." ".$time_st.'\n';
                  }
                }
                $wtes = "";
                if($working_check==1){
                  $wtes = $day['workingHrs'].'\n';
                }
                if($late_check==1){
                  $wtes = $wtes.$day['late_hrs'].'\n';
                }
                if($early_check==1){
                  $wtes = $wtes.$day['early_hrs'].'\n';
                }
                if($day['ot_seconds']>60){
                  $wtes = $wtes.$day['ot_hrs'].'\n';
                }
                if($status_check==1){
                  $st = "P";
                  if($day['weekly_off']=="1"){
                    $st = "ED";
                  }
                  if($day['holiday']=="1"){
                    $st = "ED";
                  }
                  if($day['absent']=="1"){
                    $st = "A";
                  }
                  if($day['leave']=="1"){
                    $st = "L";
                  }
                  if($day['onduty']=="1"){
                    $st = "OT";
                  }
                  if($day['wfhduty']=="1"){
                    $st = "WFH";
                  }
                  $msOut = true;
                  foreach($day['data'] as $day_data){
                    if($day_data['mode']=="out"){
                      $msOut = false;
                    }
                  }
                  if($day['mispunch']=="1" && $msOut){
                    $st="MS";
                  }else if($day['halfday']=="1"){
                    $st="P/2";
                  }else if($day['sl']=="SL"){
                    $st = "SL";
                  }
                  //$wtes = $wtes.$st;
                  $wtes = $wtes.$day['day_status'].'\n'.$day['day_sub_status'];
                }
                $val = $val.$wtes;
                echo "d".$days[$dayCount].":'$val',";
              }else{ if($status_check==1){
                $st = "A";
                if($day['weekly_off']=="1"){
                  $st = "W";
                }
                if($day['holiday']=="1"){
                  $st = "H";
                }
                if($day['leave']=="1"){
                  $st = "L";
                }
                if($day['onduty']=="1"){
                  $st = "D";
                }
                $st = $day['day_status'];
                echo "d".$days[$dayCount].":'$st',";
              }else{
                echo "";
              }}
              $dayCount++;
              ?>

              <?php
            }
          }
          if($totals_check==1){?>
            TotalPresent:<?php echo $user['totalPresent'];?>,
            TotalP2:<?php echo $user['totalP2'];?>,
            TotalAbsent:<?php echo $user['totalAbsent'];?>,
            TotalWeaklyOff:<?php echo $user['totalWeekOff'];?>,
            TotalHoliday:<?php echo $user['totalHoliday'];?>,
            TotalLeaves:<?php echo $user['totalLeaves'];?>,
            totalShortLeave:<?php echo $user['totalShortLeave'];?>,
           totalOT:<?php echo $user['totalOD'];?>,
            nwd:<?php echo $user['nwd'];?>,
            TotalWorkingHrs:'<?php echo $user['totalWorkingHrs'];?>'
            <?php  }
            echo "});";
            echo "sh.getRow(".$count++.").alignment = { wrapText: true,vertical: 'top',horizontal: 'center' };";
            echo "sh.getRow(".$count.").border = {top: {style:'thin'},left: {style:'thin'},bottom: {style:'thin'},right: {style:'thin'}};";
          }
          echo "sh.getRow(".$count.").alignment = { wrapText: true,vertical: 'top',horizontal: 'center' };";

          echo "sh.insertRow(1, ['$cmp_name']);";
          $new_start_date = date('d F Y',strtotime($start_date));
          $new_end_date = date('d F Y',strtotime($end_date));
          echo "sh.insertRow(2, ['Monthly Report from $new_start_date to $new_end_date']);";
          $department="All";
          $sft="All";
          $sect="All";
          foreach($departments as $dp){
            if($dp->id==$depart){
              $department = $dp->name;
            }
          }
          foreach($shifts as $sf){
            if($sf->id==$shift){
              $sft=$sf->name;
            }
          }
          foreach($sections as $sec){
            if($sec->type==$section){
              $sect=$sec->name;
            }
          }
          echo "sh.insertRow(3, ['Department:-$department, Shift:-$sft, Section:-$sect']);";
          echo "sh.mergeCells('A1:Z1');";
          echo "sh.mergeCells('A2:Z2');";
          echo "sh.mergeCells('A3:Z3');";
          echo "sh.getRow(1).alignment = {horizontal: 'center' };";
          echo "sh.getRow(2).alignment = {horizontal: 'center' };";
          echo "sh.getRow(3).alignment = {horizontal: 'center' };";

          ?>
          // sh.getRow(2).font = { name: 'Comic Sans MS', family: 4, size: 16, underline: 'double', bold: true };
          // sh.getRow(2).alignment = { wrapText: true };
          wb.xlsx.writeBuffer().then((data) => {
            const blob = new Blob([data], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8' });
            saveAs(blob, 'Employee Attendance.xlsx');
          });
          console.log(sh);
        }
        </script>
        
        
        
        <script type="text/javascript">
        function Export() {
          html2canvas(document.getElementById('example1'), {
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
        <script type="text/javascript">
        function createHeaders(keys) {
          var result = [];
          result.push({id: 'SNo',name: 'SNo',prompt: 'SNo.',width: 7,align: 'center',padding: 0});
          result.push({id: 'Name',name: 'Name',prompt: 'Name',width: 10,align: 'center',padding: 0});
          <?php
          if($status_check=="1" || $working_check=="1" || $all_check=="1" || $two_check=="1" || $late_check=="1" || $early_check=="1"){
            foreach($days as $day){
              echo "result.push({id: 'd$day',name: 'd$day',prompt: '$day',width: 10,align: 'center',padding: 0});";
            }
          }if($totals_check==1){?>
            result.push({id: 'P',name: 'P',prompt: 'P',width: 6,align: 'center',padding: 0});
            result.push({id: 'P2',name: 'P2',prompt: 'P2',width: 7,align: 'center',padding: 0});
            result.push({id: 'A',name: 'A',prompt: 'A',width: 6,align: 'center',padding: 0});
            result.push({id: 'WO',name: 'WO',prompt: 'WO',width: 8,align: 'center',padding: 0});
            result.push({id: 'H',name: 'H',prompt: 'H',width: 6,align: 'center',padding: 0});
            // result.push({id: 'L',name: 'L',prompt: 'L',width: 6,align: 'center',padding: 0});
            // result.push({id: 'SL',name: 'SL',prompt: 'SL',width: 7,align: 'center',padding: 0});
           // result.push({id: 'ED',name: 'ED',prompt: 'ED',width: 7,align: 'center',padding: 0});
            result.push({id: 'NWD',name: 'NWD',prompt: 'NWD',width: 7,align: 'center',padding: 0});
            result.push({id: 'WH',name: 'WH',prompt: 'WH',width: 10,align: 'center',padding: 0});
            <?php }
            ?>
            return result;
          }
        function exportPDF(){
            var result = [];
            <?php
            $count=1;
            foreach($report as $user){?>
              var data = {id:"<?php echo $count;?>",SNo: "<?php echo $count;?>",Name: "<?php echo $user['name'];?>"};
              <?php
              if($status_check=="1" || $working_check=="1" || $all_check=="1" || $two_check=="1" || $late_check=="1" || $early_check=="1"){
                  $dayCount =0;
                foreach($user['data'] as $day){
                  if(!empty($day['data'])){
                    $val="";
                    if($all_check==1){
                      $val = "";
                      foreach($day['data'] as $day_data){
                        $time_st = "QR";
                        if($day_data['manual']=="1"){
                          $time_st = "M";
                        }
                        if($day_data['location']!=""){
                          $time_st = "G";
                        }
                        //$val = $val.strtoupper($day_data['mode']).'\n'.date('h:i:A', $day_data['time']).'\n'.$time_st.'\n';
                        $val = $val.date('G:i', $day_data['time']).'\n';
                      }

                    }else if($two_check==1){
                      if(count($day['data'])>=2){
                        $val = "";
                        $time_st = "";
                        if($day['data'][0]['manual']=="1"){
                          $time_st = "M";
                        }
                        if($day['data'][0]['location']!=""){
                          $time_st = "G";
                        }
                        //$val = $val.strtoupper($day['data'][0]['mode']).'\n'.date('h:i:A', $day['data'][0]['time']).'\n'.$time_st.'\n';
                        $val = $val.date('G:i', $day['data'][0]['time']).'\n';
                        $time_st = "QR";
                        if($day['data'][count($day['data'])-1]['manual']=="1"){
                          $time_st = "M";
                        }
                        if($day['data'][count($day['data'])-1]['location']!=""){
                          $time_st = "G";
                        }
                        //$val = $val.strtoupper($day['data'][count($day['data'])-1]['mode']).'\n'.date('h:i:A', $day['data'][count($day['data'])-1]['time']).'\n'.$time_st.'\n';
                        $val = $val.date('G:i', $day['data'][count($day['data'])-1]['time']).'\n';
                      }else{
                        $val = "";
                        $time_st = "";
                        if($day['data'][0]['manual']=="1"){
                          $time_st = "M";
                        }
                        if($day['data'][0]['location']!=""){
                          $time_st = "G";
                        }
                        //$val = strtoupper($day['data'][0]['mode']).'\n'.date('h:i:A', $day['data'][0]['time']).'\n'.$time_st.'\n';
                        $val = date('G:i', $day['data'][0]['time']).'\n';
                      }
                    }
                    $wtes = "";
                    if($working_check==1){
                      $wtes = $day['workingHrs'].'\n';
                    }
                    if($late_check==1){
                      $wtes = $wtes."LT ".$day['late_hrs']." Hr".'\n';
                    }
                    if($early_check==1){
                      $wtes = $wtes."EL ".$day['early_hrs']." Hr".'\n';
                    }
                    if($day['ot_seconds']>60){
                      $wtes = $wtes."OT ".$day['ot_hrs']." Hr".'\n';
                    }
                    if($status_check==1){
                      $st = "P";
                      if($day['weekly_off']=="1"){
                        $st = "ED";
                      }
                      if($day['holiday']=="1"){
                        $st = "ED";
                      }
                      if($day['absent']=="1"){
                        $st = "A";
                      }
                      if($day['leave']=="1"){
                        $st = "L";
                      }
                      $msOut = true;
                      foreach($day['data'] as $day_data){
                        if($day_data['mode']=="out"){
                          $msOut = false;
                        }
                      }
                      if($day['mispunch']=="1" && $msOut){
                        $st="MS";
                      }else if($day['halfday']=="1"){
                        $st="P/2";
                      }else if($day['sl']=="SL"){
                        $st = "SL";
                      }
                      // $wtes = $wtes.$st;
                      //$wtes = $st;
                      $wtes = $wtes.$day['day_status'].'\n'.$day['day_sub_status'];
                    }
                    $val = $val.$wtes;
                    echo "data.d".$days[$dayCount]."='$val';";
                  }else{ if($status_check==1){
                    $st = "A";
                    if($day['weekly_off']=="1"){
                      $st = "W";
                    }
                    if($day['holiday']=="1"){
                      $st = "H";
                    }
                    if($day['leave']=="1"){
                      $st = "L";
                    }
                    $sl = $day['day_status'];
                    echo "data.d".$days[$dayCount]."='$st';";
                  }else{
                    echo "data.d".$days[$dayCount]."=' ';";
                  }
                }
                $dayCount++;
              }
            } if($totals_check==1){?>
              data.P='<?php echo $user['totalPresent'];?>';
              data.P2='<?php echo $user['totalP2'];?>';
              data.A='<?php echo $user['totalAbsent'];?>';
              data.WO='<?php echo $user['totalWeekOff'];?>';
              data.H='<?php echo $user['totalHoliday'];?>';
            //   data.L='<?php echo $user['totalLeaves'];?>';
            //   data.SL='<?php echo $user['totalShortLeave'];?>';
             // data.ED='<?php echo $user['totalOT'];?>';
              data.NWD='<?php echo $user['nwd'];?>';
              data.WH='<?php echo $user['totalWorkingHrs'];?>';
              <?php  }?>
              result.push(Object.assign({}, data));
              <?php $count++;}
              $new_start_date = date('d F Y',strtotime($start_date));
              $new_end_date = date('d F Y',strtotime($end_date));
              $department="All";
              $sft="All";
              $sect="All";
              foreach($departments as $dp){
                if($dp->id==$depart){
                  $department = $dp->name;
                }
              }
              foreach($shifts as $sf){
                if($sf->id==$shift){
                  $sft=$sf->name;
                }
              }
              foreach($sections as $sec){
                if($sec->type==$section){
                  $sect=$sec->name;
                }
              }
              ?>
              var headers = createHeaders();
              var doc = new jspdf.jsPDF("landscape");
              doc.setFontSize(10);
              doc.text("<?php echo $cmp_name;?>",145,10,null,null,"center");
              doc.text("Monthly Report from <?php echo $new_start_date; ?> to <?php echo $new_end_date;?>",145,15,null,null,"center");
              doc.text("Department:-<?php echo $department;?>, Shift:-<?php echo $sft;?>, Section:-<?php echo $sect;?>",145,20,null,null,"center");
              doc.table(3, 25, result, headers, { autoSize: false,fontSize:6,padding:1,margins:{left:0,top:3,bottom:3, right:0} });
              doc.save("Attendace-Report.pdf");
            }
            </script>
            </body>
            </html>
