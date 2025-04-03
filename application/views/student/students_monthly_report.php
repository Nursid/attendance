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
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
<?php $this->load->view('student/student_menu')?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
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
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <div class="card card-danger">
              <div class="card-header">
                <h3 class="card-title">Monthly Report</h3>
              </div>
              <div class="card-body">
              
              <h5> Select Date Range
			     </h5> 
			   
              <div class="row">
    <div class="col-lg-12 float-left">
    
      <form action="<?php echo base_url('User/students_monthly_report')?>" method="POST" id="hostelmonthlyReport">
                          <div class="row">
                              
                              
                            <div class="from-group col-sm-2">
                 
                    <?php
                        $data = $this->web->getBusinessDepByBusinessId($bid);
                    ?>

                    <select class="select2"  id="departs" style="width: 100%;" name="dept">
                      <option value="" disabled selected>Select Branch</option>

                        <?php foreach($data as $key => $val){
                          
                            echo "<option value=" . $val->id . ">" .$val->name."</option>";                          
                        } ?>


                    </select>
                       
                  </div>  
                              
                      <div class="col-md-2">
                <div class="form-group">
                 
                    <select class="select2"  id="sdeparts" data-placeholder="Select Batch" style="width: 100%;" name="session">
                    </select>
                </div>
                <!-- /.form-group -->
              </div>
              
               <div class="col-md-2">
                <div class="form-group">
                 
                    <select class="select2"  id="section" data-placeholder="Select Section" style="width: 100%;" name="section">
                    </select>
                </div>
                <!-- /.form-group -->
              </div>
                          
                              
                              
                              
                              
                              
                              
                              
                            <div class="col-sm-2">
                              <input type="date" name="start_date" id="start_date"  value="<?php echo $start_date; ?>" class="form-control" max="<?php echo $end_date; ?>" onchange="startChange(event);">
                            </div>
                            <div class="col-sm-2">
                              <input type="date" name="end_date" id="end_date"  value="<?php echo $end_date; ?>"class="form-control" max="<?php echo date('Y-m-d'); ?>" min="<?php echo $start_date;?>" onchange="endChange(event);">
                            </div>
                            
                             
                             
                            <div class="col-sm-1">
                            
                              <button type="submit" id="actionSubmit" class="btn btn-success btn-fill btn-block" onclick="showLoader()">Show</button>
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
                       
                        <h5>Attendance for Date:-<?php echo date("d-M-Y ",$stdate)?> to Date:- <?php echo date("d-M-Y ",$endate)." And  Department:- ".$department. "  And Session:- ".$ses. "  And Section:- ". $sect ?> </h5>
                        
                      <!--  <div align="right">
                          <input type="button" onClick="export_datas()" value="Export To Excel" />
                          <input type="button"  id="btnExport" value="Export To Pdf" onclick="exportPDF()" />
                          <br>
                        </div>-->
                        <table id="example1" class="table table-bordered table-responsive">
                          <thead>
                            <tr>
                              <th>SNo.</th>
                             <th>Class</th>
                               <th>Semester</th>
                             <th>Roll No</th>
                            <th>Name</th>
                            
                             
                              <?php
                              
                                foreach($days as $day){
                                  echo "<th>$day</th>";
                                }
                              
                              ?>
                              
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
                                
                                <?php
                                
                                  foreach($user['data'] as $day){
                                    if(!empty($day['data'])){
                                      echo "<td>";
                                     
                                        foreach($day['data'] as $day_data){
                                         //echo  $day_data['time']; 
										// echo  $day_data['mode']; 
                                         echo strtoupper($day_data['mode'])."&nbsp;".date('h:i:A',$day_data['time'])."</br>";
                                        }
										echo "</td>";
									}

                                  else{
                                      echo "<td></td>";
                                    }   
								  }
                                
                                ?>
                                
                                
                                
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
  var id = this.value;
  var datatypes_session = "sessionlist";
  $.ajax({
    type: "post",
    url: "User/getajaxRequest",
    data: {id,datatypes_session},
    success: function(data){
      $('#sdeparts').html(data);
    }
  });
});

$('#sdeparts').on('change', function() {
  var id = this.value;
  var datatypes_section = "sectionlist";
  $.ajax({
    type: "post",
    url: "User/getajaxRequest",
    data: {id,datatypes_section},
    success: function(data){
      $('#section').html(data);
    }
  });
});
</script>

