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
  <?php $this->load->view('student/student_menu')?>
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
         // $role=$this->web->getRollbyid($this->web->session->userdata('login_id'),$bid);
        } else {
          $bid = $this->web->session->userdata('login_id');
        }
        $dep = $this->web->getBusinessDepByUserId($dept);
                    if(!empty($dep)){
                      $department=$dep[0]->name;  
                    }
                     $sess = $this->web->getSessionById($session);
                    if(!empty($sess)){
                      $ses=$sess[0]->session_name;  
                    }
                     $sec = $this->web->getsectionById($section);
                    if(!empty($sec)){
                      $sect=$sec[0]->name;  
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
                    <h3 class="card-title">Daily Report</h3>
                  </div>
                  <div class="card-body">
                    
                    <div class="row">
                      <div class="col-lg-12 float-left">
                        <form action="<?php echo base_url('User/students_daily_report')?>" method="POST">
                          <div class="row">
                              
                            <div class="from-group col-sm-2">
                          
                    <?php
                        $data = $this->web->getBusinessDepByBusinessId($bid);
                    ?>

                    <select class="select2"  id="departs" style="width: 100%;" name="dept">
                      
                     <?php if($load) {?>  <option value="" ><?php echo $department; ?></option>
                     <?php }else{?>
                     <option value="" disabled selected>Select Branch</option>
                     <?php } ?>

                        <?php foreach($data as $key => $val){
                          
                            echo "<option value=" . $val->id . ">" .$val->name."</option>";                          
                        } ?>


                    </select>
                       
                  </div>  
                              
                      <div class="col-md-2">
                <div class="form-group">
                 
                 
                 
                <select class="select2" id="semester" data-placeholder="Select Semester" style="width: 100%;" name="semester">
            <?php if($load) { ?>  
                <option value="<?php echo $session; ?>" selected><?php echo $ses; ?></option>
            <?php } else { ?>
                <option value="" disabled selected>Select Semester</option>
            <?php } ?>
        </select>
                </div>
                <!-- /.form-group -->
              </div>
              
               <div class="col-md-2">
                <div class="form-group">
                      <?php //if($load) {?>
                      <!--<option value="" ><?php echo $sect; ?></option>-->
                     <?php // } ?>
                     <select class="select2" id="section" data-placeholder="Select Section" style="width: 100%;" name="section">
            <?php if($load) { ?>
                <option value="<?php echo $section; ?>" selected><?php echo $sect; ?></option>
            <?php } ?>
        </select>
                </div>
                <!-- /.form-group -->
              </div>
                           
                              
                              
                              
                              
                              
                              
                              
                              
                              
                            <div class="col-sm-2">

                              <input type="date" name="start_date" id="start_date"  value="<?php echo $start_date; ?>" class="form-control"  max="<?php echo date('Y-m-d'); ?>" onchange="startChange(event);">
                            </div>
                            
                            
                          <!--   <div class="col-sm-2">

                              <select name="class" class="form-control"  id="block" >
                              <option value="">Select CLass</option>    
                                 
                   <?php
				  
				   $classes = $this->web->getallclassbyid($bid);
                    if(!empty($classes)){
                      foreach($classes as $clas):
                        echo "<option value=".$clas->id .">".$clas->name."</option>";
                      endforeach;
                   }
				   
                   ?></select>
                   
                            </div>-->
                            
                            
                            <div class="col-1">
                              <button type="submit" id="actionSubmit" class="btn btn-success btn-fill btn-block" onclick="showLoader()">Show</button>
                            </div>
                          </div>
                          <br>
                          <input type="hidden" id="action" name="action" value="active"/>
                       <!--   <div class="row">
                            <div class="col-2">
                              <button type="button" id="" onClick="setAction('active');" class="btn btn-success btn-fill btn-block">Active : <?php echo $totalActive;?></button>
                            </div>
                            <div class="col-2">
                              <button type="button" id="" onClick="setAction('present');" class="btn btn-success btn-fill btn-block">Present : <?php echo $totalPresent;?></button>
                            </div>
                            <div class="col-2">
                              <button type="button" id="" onClick="setAction('absent');" class="btn btn-danger btn-fill btn-block">Absent: <?php echo $totalAbsent;?></button>
                            </div>
                            
                            
                          </div-->
                        </form>
                      </div>
                    </div>
                    <br>
                    <?php

                    if($load) {
                      $stdate=strtotime($start_date);
                    
                      $dep = $this->web->getBusinessDepByUserId($dept);
                    if(!empty($dep)){
                      $department=$dep[0]->name;  
                    }
                     $sess = $this->web->getSessionById($session);
                    if(!empty($sess)){
                      $ses=$sess[0]->session_name;  
                    }
                     $sec = $this->web->getsectionById($section);
                    if(!empty($sec)){
                      $sect=$sec[0]->name;  
                    }


                    
                      ?>
                     <!-- <h5>Attendance for Date:-<?php echo date("d-M-Y ",$stdate)  ;?>  and department <?php echo $department ;?>  </h5>-->
                    <div align="right">
                        <!-- <input type="button" onClick="exportExcel()" value="Export To Excel" /> -->
                        <button onclick="exportTableToExcel('example1', 'Daily_Report.xlsx')">Export to Excel</button>
                         <!-- <input type="button"  id="btnExport" value="Export To Pdf" onclick="exportPDF()" /> -->
                        <br>
                     </div>
                     <br>
                     <div style="overflow-x: auto;">
  <table id="example1" class="table table-bordered table-striped">
    <thead>
      <?php
      echo "<tr> <td colspan='12'> Attendance For Date:-".$start_date." And  Department:- ".$department. "  And Session:- ".$ses. "  And Section:- ". $sect." </td></tr>";
      ?>
      <tr>
        <th>S.No</th>
        <th>Class</th>
        <th>Semester</th>
        <th>Roll No</th>
        <th>Name</th>
        <th>Time Log</th>
        <?php
        $res = $this->web->getallperiodbyid($bid);

        $dayOfWeek = date('w', strtotime($start_date));
        foreach($res as $period){
            $subject = $this->web->getSubjectByPeriodAndDay($period->id, $dayOfWeek);
            ?>
            <th> <?php echo $period->name . "<br> (" . $period->start_time . "-" . $period->end_time . ")<br>Subject: " . $subject->name; ?> </th>
        <?php } ?>
      </tr>
    </thead>
    <tbody>
      <?php
      $count=1;
      foreach($report as $user){
        $val=$this->web->getstudentnamebyid($user['user_id']);
        if(!empty($val[0]->roll_no)){
          $sid=$val[0]->roll_no;  
        }else{
          $sid=$val[0]->student_code;   
        }
        if(($val[0]->class_id) !="0"){
          $classname = $this->web->getclassnamebyid($val['0']->class_id);
          $classn=$classname[0]->name;  
        }else{
          $classn="";    
        }
        ?>
        <tr>
          <td><?php echo $count++;?></td>
          <td><?php echo $classn;?></td>
          <td><?php echo $val[0]->semester;?></td>
          <td><?php echo $sid;?></td>
          <td><?php echo $user['name'];?></td>
          <td>
            <?php
            foreach($user['data'] as $day_data){
              echo "<span class='".$spanClass."'>".date('h:i:A', $day_data['time'])."</span></br>";
            }
            ?>
          </td>
          <?php
          $res2=$this->web->getallperiodbyid($bid);
          foreach($res2 as $res2){ 
            $start_times=strtotime ($res2->start_time);
            $end_times=strtotime ($res2->end_time);
            ?>  
            <td>
              <?php
              $anytime=0;
              foreach($user['data'] as $day_data){ 
                if((strtotime(date("h:i A" ,$day_data['time']))>=$start_times) && (strtotime (date("h:i A" ,$day_data['time']))<=$end_times) ){
                  $anytime=date("h:i A" ,$day_data['time'])."<br>" ;
                } 
              }
              if($anytime!=0){ echo "P" ;
              }else {
                echo "A";
              }
              ?>  
            </td>
          <?php } ?>
        </tr>
      <?php } ?>
    </tbody>
    <tfoot>
    </tfoot>
  </table>
</div>
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
      <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script> -->
      <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>

      <script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.3.0/exceljs.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>

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
$(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })
  });
 
 

$('#departs').on('change', function() {
  var branchId = this.value;
  $.ajax({
    type: "post",
    url: "<?php echo base_url('User/get_semester_by_branch'); ?>",
    data: {branch_id: branchId},
    success: function(data){
      var semesters = JSON.parse(data);
      var options = '<option value="" disabled selected>Select Semester</option>';
      semesters.forEach(function(semester) {
        options += '<option value="' + semester.id + '">' + semester.semestar_name + '</option>';
      });
      $('#semester').html(options);
    }
  });
});

// $('#semester').on('change', function() {
//   var id = this.value;
//   var branchId = $('#departs').val();

//   $('#section').html('<option value="" disabled selected>Select Section</option>');

//   $.ajax({
//     type: "post",
//     url: "<?php // echo base_url('User/get_section_by_branch_semester'); ?>",
//     data: {branch_id: branchId, semester_id: id},
//     success: function(data){
//       var sections = JSON.parse(data);
//       console.log(sections)
//       var options = '<option value="" disabled selected>Select Section</option>';
//       sections.forEach(function(section) {
//         // var selected = (typeof <?php // echo $section; ?> !== 'undefined' && section.id == <?php //echo $section; ?>) ? 'selected' : '';
//         options += '<option value="' + section.id + '" '  + '>' + section.name + '</option>';
//       });
//       console.log(options)
//       $('#section').html(options);
//     },
//     error: function() {
//       console.log("Error loading sections");
//     }
//   });
// });

$('#semester').on('change', function() {
    var id = this.value;
    var branchId = $('#departs').val();

    $('#section').html('<option value="" disabled selected>Select Section</option>');

    $.ajax({
        type: "post",
        url: "<?php echo base_url('User/get_section_by_branch_semester'); ?>",
        data: {branch_id: branchId, semester_id: id},
        success: function(data){
            var sections = JSON.parse(data);
            var options = '<option value="" disabled selected>Select Section</option>';
            sections.forEach(function(section) {
                var selected = (section.id == '<?php echo isset($section) ? $section : ""; ?>') ? 'selected' : '';
                options += '<option value="' + section.id + '" ' + selected + '>' + section.name + '</option>';
            });
            $('#section').html(options);
        },
        error: function() {
            console.log("Error loading sections");
        }
    });
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

  function exportExcel2(){
    var wb = new ExcelJS.Workbook();
      var sh = wb.addWorksheet("Report");
      sh.columns = [
        {header: 'SNo.', key: 'SNo', width: 10},
         {header: 'Class', key: 'Class', width: 20},
        {header: 'Roll No', key: 'Roll', width: 20},
        {header: 'Name', key: 'Name', width: 20},
      
      <?php
                            $res=$this->web->getallperiodbysectionid($bid,$section);
							 foreach($res as $res){
							//echo $res->name;
                            ?>
                            
                            {header: '<?php echo $res->name."(".$res->start_time."-".$res->end_time.")"; ?>', key: 'IN', width: 20},
                            <?php } ?>
                            
      
      
     //   {header: 'IN', key: 'IN', width: 20},
      //  {header: 'Out', key: 'Out', width: 20},
      //  {header: 'Status', key: 'Status', width: 20}
       
      ];
      <?php
        $count=1;
        foreach($report as $user){
             $val=$this->web->getstudentnamebyid($user['user_id']);
                                  
                                   if(!empty($val[0]->roll_no)){
                                      $sid=$val[0]->roll_no;  
                                   }else{
                                     $sid=$val[0]->student_code;   
                                   }
                                     
                                     
                                     if(($val[0]->class_id) !="0"){
                                    $classname = $this->web->getclassnamebyid($val['0']->class_id);
                                      $classn=$classname[0]->name;  
                                   }else{
                                     $classn="";    
                                   }
                                     
          
            
            
            
         // $shift = $user['group_name'].'\n'.$user['shift_start'].'\n'.$user['shift_end'];
        //  $allIns = "";
         // foreach($user['data'] as $day_data){
           // if($day_data['mode']=="in"){
           //   $time_st = "QR";
            //  if($day_data['manual']=="1"){
            //    $time_st = "M";
            //  }
             // if($day_data['location']!=""){
             //   $time_st = "G";
             // }
             // $allIns= $allIns.date('h:i:A', $day_data['time']).' '.$time_st.'\n';
            //}
         // }
         // $allOuts = "";
          foreach($user['data'] as $day_data){
               $allIns= date('h:i:A', $day_data['time']);
               // if($day_data['mode']=="2"){
                  //  $allOuts=date('h:i:A', $day_data['time']);
                //}
          }  
        ?>
        sh.addRow({SNo:'<?php echo $count++;?>',Class:'<?php echo $classn ;?>',Roll:'<?php echo $sid;?>',Name:'<?php echo $user['name'];?>',
<?php // $res=$this->web->getallperiodbysectionid($bid,$section);
						//	 foreach($res as $res){ ?>
        IN:'<?= $allIns;?>'				     
							 });
        
        
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

function exportTableToExcel(tableID, filename = 'student_daily_report.xlsx') {

  var start_date = "<?php echo $start_date; ?>";
  var department = "<?php echo $department; ?>";
  var ses = "<?php echo $ses; ?>";
  var sect = "<?php echo $sect; ?>";
  
  // Create a new workbook and add a worksheet
  var workbook = new ExcelJS.Workbook();
  var worksheet = workbook.addWorksheet('Sheet1');

  // Get the table element
  var table = document.getElementById(tableID);

  // Merge the first two rows and set the text
  worksheet.mergeCells('A1:P2');
  var headerCell = worksheet.getCell('A1');
  headerCell.value = "Attendance For Date: " + start_date + " And Department: " + department + " And Session: " + ses + " And Section: " + sect;
  headerCell.alignment = { vertical: 'middle', horizontal: 'center' };
  headerCell.font = { size: 14, bold: true };

  // Add the table headers
  var headers = [];
  table.querySelectorAll('thead th').forEach((th, index) => {
      headers.push(th.innerText);
  });
  worksheet.addRow(headers).font = { bold: true };

  // Add the table data
  table.querySelectorAll('tbody tr').forEach((row) => {
      var rowData = [];
      row.querySelectorAll('td').forEach((td) => {
          rowData.push(td.innerText);
      });
      worksheet.addRow(rowData);
  });

  // Write the workbook to a file
  workbook.xlsx.writeBuffer().then((data) => {
      const blob = new Blob([data], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8' });
      saveAs(blob, filename);
  });
}

</script>
</body>
</html>
