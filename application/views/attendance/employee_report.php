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
  <link rel="stylesheet" href="<?php echo base_url('adminassets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')?>">
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
                <li class="breadcrumb-item active">Employee Report</li>
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
        if($this->session->userdata()['type']=='B' || $role[0]->other_report=="1" || $role[0]->type=="1"){?>
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
                    <h3 class="card-title">Employee Report</h3>
                  </div>
                  <div class="card-body">
                    <!-- /.Form starts -->
                    <h5> Select Employee</h5>
                    <div class="row">
                      <div class="col-lg-12 float-left">
                        <form action="<?php echo base_url('User/employee_report')?>" method="POST">
                          <div class="row">
                            <div class="col-sm-2">
                              <input type="date" name="start_date" id="start_date"  value="<?php echo $start_date; ?>" class="form-control" max="<?php echo $end_date; ?>" onchange="startChange(event);">
                            </div>
                            <div class="col-sm-2">
                              <input type="date" name="end_date" id="end_date"  value="<?php echo $end_date; ?>"class="form-control" max="<?php echo date('Y-m-d'); ?>" min="<?php echo $start_date;?>" onchange="endChange(event);">
                            </div>
                            <div class="col-sm-2 ">
                              <!--<label for="employee">Employee</label>-->
                              <select name="emp" class="form-control" name="emp"  id="emp" >
                                <?php $usname = $this->web->getNameByUserId($id);
                                if ($id!=''){
                                  ?>
                                  <option value="<?php echo $usname[0]->id  ?>"><?php echo $usname[0]->name;  ?></option>
                                  <?php
                                } else { ?>
                                  <option value="0"> All Employee </option>
                                <?php }
                                //       $loginId = $this->session->userdata('login_id');
                                // if($this->session->userdata('type')=="P"){
                                //   $userCmp = $this->app->getUserCompany($loginId);
                                //   if(isset($userCmp) && ($userCmp['left_date']=="" || $userCmp['left_date']>time())){
                                //     $loginId = $userCmp['business_id'];
                                //   }
                                // }
                                $res=$this->web->getActiveEmployeesList($loginId);
                                if($this->session->userdata()['type']=='P'){
              if($role[0]->type!=1){
                $departments = explode(",",$role[0]->department);
                $sections = explode(",",$role[0]->section);
                $team = explode(",",$role[0]->team);
                
                if(!empty($departments[0]) || !empty($sections[0]) || !empty($team[0])){
                  foreach ($res as $key => $dataVal) {
                    $uname = $this->web->getNameByUserId($dataVal->user_id);
                    $roleDp = array_search($uname[0]->department,$departments);
                    $roleSection = array_search($uname[0]->section,$sections);
                    $roleTeam = array_search($dataVal->user_id,$team);
                   
                    if(!is_bool($roleTeam) || !is_bool($roleSection) || !is_bool($roleDp)){
            
                    }else{
                      unset($res[$key]);
                    }
                  }
                }
              }
            }
                                
                                foreach($res as $res):
                                  $uname = $this->web->getNameByUserId($res->user_id);
                                  $select = "";
                                  if(!empty($report) && $report['user_id']==$res->user_id){
                                    $select="selected";
                                  }
                                  ?>
                                  <option value="<?php echo $uname[0]->id  ?>" <?php echo $select; ?>><?php echo $uname[0]->name; ?></option>
                                  <?php
                                endforeach;
                                ?></select>

                              </div>
                              <div class ="col-sm-2">
                                <select name="option" class="form-control" name="option"  id="emp">

                                  <option value="all" <?php if($option=="all"){echo "selected";}?>>All</option>
                                  <option value="present" <?php if($option=="present"){echo "selected";}?>>Present</option>
                                  <option value="absent" <?php if($option=="absent"){echo "selected";}?>>Absent</option>
                                  <option value="mispunch" <?php if($option=="mispunch"){echo "selected";}?>>Mispunch</option>
                                  <option value="fieldDuty" <?php if($option=="fieldDuty"){echo "selected";}?>>Field Duty</option>
                                  <option value="leave" <?php if($option=="leave"){echo "selected";}?>>Leave</option>
                                  <option value="halfday" <?php if($option=="halfday"){echo "selected";}?>>Halfday</option>
                                  <option value="shortLeave" <?php if($option=="shortLeave"){echo "selected";}?>>Short Leave</option>
                                  <option value="late" <?php if($option=="late"){echo "selected";}?>>Late</option>
                                  <option value="early" <?php if($option=="early"){echo "selected";}?>>Early</option>
                                </select>
                              </div>
                              <div class="col-sm-2">
                                <button type="submit" class="btn btn-success btn-fill btn-block" onclick="showLoader()">Show</button>
                              </div>
                            </div>
                          </form>
                        </div>
                      </div>
                      <br><br>
                      <!-- /.total starts -->
                      <?php
                      if($load) {
                        $stdate=strtotime($start_date);
                        $endate=strtotime($end_date);
                        ?>
                        <h5>Attendance for Date:-<?php echo date("d-M-Y ",$stdate)?> to Date:- <?php echo date("d-M-Y ",$endate)?> </h5>
                        <div align="right">
                          <input type="button" onClick="exportData()" value="Export To Excel" />
                          <input type="button"  id="btnExport" value="Export To Pdf" onclick="exportPDF()"/>
                          <br>
                        </div>
                        <?php
                        if(!empty($report)){
                          foreach($report as $user){?>
                        <br>
                        <h4><?php echo $user['name'];?></h4>
                        <table id="reportTable" class="table table-bordered table-striped">
                          <thead>
                            <tr>
                              <th>Date</th>
                              <th>Day</th>
                              <th>Status</th>
                              <th>IN</th>
                              <th>Out</th>
                              <th>W.H</th>
                              <th>Late In</th>
                              <th>Early Out</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                            foreach($user['data'] as $day){?>
                              <tr>
                              <td><?php echo $day['date'];?></td>
                              <td><?php echo $day['day'];?></td>
                              <td>
                                <?php
                                if(!empty($day['data'])){
                                  $st = "P";
                                  if($day['absent']=="1"){
                                    $st = "A";
                                  }
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
                                }else{
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
                                }
                                echo $st;
                                 ?>
                              </td>
                              <td>
                                <?php
                                //foreach($day['data'] as $day_data){
                                  //if($day_data['mode']=="in"){
                                    //echo date('h:i:A', $day_data['time'])."</br>";
                                  //}
                               // }
                                if(count($day['data'])>0){
                                    echo date('h:i:A', $day['data'][0]['time']);
                                   //echo date('h:i:A', $day['data'][count($day['data'])-1]['time']);
                                  }
                               
                               
                               
                                ?>
                              </td>
                              <td>
                                <?php
                                
                                //foreach($day['data'] as $day_data){
                                 if(count($day['data'])>0){
                                    //echo date('h:i:A', $day['data'][0]['time']);
                                   echo date('h:i:A', $day['data'][count($day['data'])-1]['time']);
                                  }
                                //}
                                ?>
                              </td>
                              <td><?php echo $day['workingHrs'];?></td>
                              <td><?php echo $day['late_hrs'];?></td>
                              <td><?php echo $day['early_hrs'];?></td>
                              </tr>
                            <?php }
                          ?>
                        </tbody>
                        <tfoot>
                        </tfoot>
                      </table>
                    <?php }
                  }?>
                    </div>
                    <?php
                  }
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
        <!-- /.content -->
        <?php
      }
      ?>
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

  <script>
    function exportData(){
      var wb = new ExcelJS.Workbook();
      var sh = wb.addWorksheet("Report");
      <?php
        $count=1;
        foreach($report as $user){
      ?>
      sh.addRow(["<?= $user['name']?>"]);
      sh.addRow(["Date","Day","Status","In","Out","W.H","Late In","Early Out"]);
      <?php 
        foreach($user['data'] as $day){
          if(!empty($day['data'])){
            $st = "P";
            if($day['absent']=="1"){
              $st = "A";
            }
            if($day['weekly_off']=="1"){
              $st = "W";
            }
            if($day['holiday']=="1"){
              $st = "H";
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
          }else{
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
          }
          $allIns = "";
          foreach($day['data'] as $key=>$day_data){
            if($day_data['mode']=="in"){
              if($key>0){
                $allIns = $allIns.'\n';
              }
              $allIns = $allIns.date('h:i:A', $day_data['time']);
            }
          } 
          $allOuts = "";
          foreach($day['data'] as $k=>$day_data){
            if($day_data['mode']=="out"){
              if($k>0){
                $allOuts = $allOuts.'\n';
              }
              $allOuts= $allOuts.date('h:i:A', $day_data['time']);
            }
          }               
          ?>
          sh.addRow(["<?= $day['date']?>","<?= $day['day']?>","<?= $st?>","<?= $allIns?>","<?= $allOuts?>","<?= $day['workingHrs']?>","<?= $day['late_hrs']?>","<?= $day['early_hrs']?>"]);    
      <?php }?>
      sh.addRow([""]);
      <?php  
        }?>
      wb.xlsx.writeBuffer().then((data) => {
        const blob = new Blob([data], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8' });
        saveAs(blob, 'Employee Attendance.xlsx');
      });
    }

  function createHeaders(keys) {
    var result = [];
    result.push({id: 'Date',name: 'Date',prompt: 'Date',width: 40,align: 'center',padding: 0});
    result.push({id: 'Day',name: 'Day',prompt: 'Day',width: 45,align: 'center',padding: 0});
    result.push({id: 'Status',name: 'Status',prompt: 'Status',width: 45,align: 'center',padding: 0});
    result.push({id: 'IN',name: 'IN',prompt: 'IN',width: 40,align: 'center',padding: 0});
    result.push({id: 'Out',name: 'Out',prompt: 'Out',width: 40,align: 'center',padding: 0});
    result.push({id: 'WH',name: 'WH',prompt: 'WH',width: 50,align: 'center',padding: 0});
    result.push({id: 'LateIn',name: 'LateIn',prompt: 'LateIn',width: 40,align: 'center',padding: 0});
    result.push({id: 'EarlyOut',name: 'EarlyOut',prompt: 'EarlyOut',width: 40,align: 'center',padding: 0});
    return result;
  }

  function exportPDF(){
    var result = [];
    <?php
        $count=1;
        $user = $report[0];
          foreach($user['data'] as $day){
            if(!empty($day['data'])){
              $st = "P";
              if($day['absent']=="1"){
                $st = "A";
              }
              if($day['weekly_off']=="1"){
                $st = "W";
              }
              if($day['holiday']=="1"){
                $st = "H";
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
            }else{
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
            }
            $allIns = " ";
            foreach($day['data'] as $key=>$day_data){
              if($day_data['mode']=="in"){
                if($key>0){
                  $allIns = $allIns.'\n';
                }
                $allIns = $allIns.date('h:i:A', $day_data['time']);
              }
            } 
            $allOuts = " ";
            foreach($day['data'] as $k=>$day_data){
              if($day_data['mode']=="out"){
                if($k>0){
                  $allOuts = $allOuts.'\n';
                }
                $allOuts= $allOuts.date('h:i:A', $day_data['time']);
              }
            }     
          ?>
        var data = {id:"<?php echo $count;?>",Date:"<?= $day['date']?>",Day:"<?= $day['day']?>",Status:"<?= $st?>",IN:"<?= $allIns;?>",Out:"<?= $allOuts;?>",WH:"<?= $day["workingHrs"];?>",LateIn:"<?= $day["late_hrs"];?>",EarlyOut:"<?= $day["early_hrs"];?>"};
        result.push(Object.assign({}, data));
    <?php }?>
    var headers = createHeaders();
    var doc = new jspdf.jsPDF("landscape");
    doc.setFontSize(12);
    doc.text(10,10,"<?php echo $user['name'];?>");
    doc.table(3, 15, result, headers, { autoSize: false,fontSize:10,padding:1,margins:{left:0,top:3,bottom:3, right:0} });
    doc.save("Employee-Report.pdf");
  }

  </script>

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
</script>
</body>
</html>
