
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
  <link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
  <script src="<?php echo base_url('adminassets/plugins/jquery/jquery.min.js')?>"></script>
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
              <li class="breadcrumb-item active">Attendance Options</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <?php
   if($this->session->userdata()['type']=='B' || $this->session->userdata()['type']=='P' ){ 
	  
	  if($this->session->userdata()['type']=='P'){
      // $busi=$this->web->getBusinessbyUser($this->web->session->userdata('login_id'));
      // $id=$busi[0]->business_id;
      $bid = $this->session->userdata('empCompany');
      $role=$this->web->getRollbyid($this->web->session->userdata('login_id'),$bid);
  
    } else {
      $bid=$this->web->session->userdata('login_id');
    }
    ?>

    <!-- Main content -->
    <section class="content">
        <?php
      if($this->session->userdata()['type']=='B' || $role[0]->att_setting=="1" || $role[0]->type=="1"){?>
      
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Attendance Options</h3>
                <span style="color: red"><?php echo $this->session->flashdata('msg');?></span>
              </div>
              <div class="card-body">
                <?php
                    $qrcheck = "";
                    $gpscheck = "";
                    $facecheck = "";
                    $teamcheck = "";
                    $autogpscheck = "";
                    $gpstrackingcheck = "";
                    $fieldcheck = "";
                    $fourlayercheck = "";
                    $gpsselfiecheck = "";
                    $fieldselfiecheck = "";
                    if(!empty($options)){
                        if($options['qr']==1){
                            $qrcheck = "checked";
                        }
                        if($options['gps']==1){
                            $gpscheck = "checked";
                        }
                        if($options['face']==1){
                            $facecheck = "checked";
                        }
                        if($options['colleague']==1){
                            $teamcheck = "checked";
                        }
                        if($options['auto_gps']==1){
                            $autogpscheck = "checked";
                        }
                        if($options['gps_tracking']==1){
                            $gpstrackingcheck = "checked";
                        }
                        if($options['field_duty']==1){
                            $fieldcheck = "checked";
                        }
                        if($options['four_layer_security']==1){
                            $fourlayercheck = "checked";
                        }
                        if($options['selfie_with_gps']==1){
                            $gpsselfiecheck = "checked";
                        }
                        if($options['selfie_with_field_duty']==1){
                            $fieldselfiecheck = "checked";
                        }
                    }
                ?>
                <div class="row">
                    <div class="col-2">
                        <p class="text-sm-left font-weight-bold">S.No.</p>
                    </div>
                    <div class="col">
                        <h5></h5>
                    </div>
                    <div class="col-2">
                        <p class="text-sm-left font-weight-bold">On/Off</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-1">
                    <p class="text-sm-left">1)</p>
                    </div>
                    <div class="col">
                        <p class="text-sm-left">QR Code Attendance</p>
                    </div>
                    <div class="col-2">
                        <input id="qrcheck" type="checkbox" data-toggle="toggle" data-onstyle="success" data-size="sm" <?= $qrcheck;?>>
                        <script>
                            $(function() {
                            $('#qrcheck').change(function() {
                                $.ajax({
                                url:'<?php echo base_url('User/update_attendance_option')?>',
                                data:{checked:$(this).prop('checked'),type:'qrcheck'},
                                type:'post'
                                });
                            });
                            });
                        </script>
                    </div>
                </div>
                <div class="row">
                    <div class="col-1">
                    <p class="text-sm-left">2)</p>
                    </div>
                    <div class="col">
                        <p class="text-sm-left">GPS Attendance</p>
                    </div>
                    <div class="col-2">
                        <input id="gpscheck" type="checkbox" data-toggle="toggle" data-onstyle="success" data-size="sm" <?= $gpscheck;?>>
                        <script>
                            $(function() {
                            $('#gpscheck').change(function() {
                                $.ajax({
                                url:'<?php echo base_url('User/update_attendance_option')?>',
                                data:{checked:$(this).prop('checked'),type:'gpscheck'},
                                type:'post'
                                });
                            });
                            });
                        </script>
                    </div>
                </div>
                <div class="row">
                    <div class="col-1">
                    <p class="text-sm-left">3)</p>
                    </div>
                    <div class="col">
                        <p class="text-sm-left">Face Attendance</p>
                    </div>
                    <div class="col-2">
                        <input id="facecheck" type="checkbox" data-toggle="toggle" data-onstyle="success" data-size="sm" <?= $facecheck;?>>
                        <script>
                            $(function() {
                            $('#facecheck').change(function() {
                                $.ajax({
                                url:'<?php echo base_url('User/update_attendance_option')?>',
                                data:{checked:$(this).prop('checked'),type:'facecheck'},
                                type:'post'
                                });
                            });
                            });
                        </script>
                    </div>
                </div>
                <div class="row">
                    <div class="col-1">
                    <p class="text-sm-left">4)</p>
                    </div>
                    <div class="col">
                        <p class="text-sm-left">Team Attendance</p>
                    </div>
                    <div class="col-2">
                        <input id="teamcheck" type="checkbox" data-toggle="toggle" data-onstyle="success" data-size="sm" <?= $teamcheck;?>>
                        <script>
                            $(function() {
                            $('#teamcheck').change(function() {
                                $.ajax({
                                url:'<?php echo base_url('User/update_attendance_option')?>',
                                data:{checked:$(this).prop('checked'),type:'teamcheck'},
                                type:'post'
                                });
                            });
                            });
                        </script>
                    </div>
                </div>
                <div class="row">
                    <div class="col-1">
                    <p class="text-sm-left">5)</p>
                    </div>
                    <div class="col">
                        <p class="text-sm-left">Auto verify GPS</p>
                    </div>
                    <div class="col-2">
                        <input id="autogpscheck" type="checkbox" data-toggle="toggle" data-onstyle="success" data-size="sm" <?= $autogpscheck;?>>
                        <script>
                            $(function() {
                            $('#autogpscheck').change(function() {
                                $.ajax({
                                url:'<?php echo base_url('User/update_attendance_option')?>',
                                data:{checked:$(this).prop('checked'),type:'autogpscheck'},
                                type:'post'
                                });
                            });
                            });
                        </script>
                    </div>
                </div>
                <div class="row">
                    <div class="col-1">
                    <p class="text-sm-left">6)</p>
                    </div>
                    <div class="col">
                        <p class="text-sm-left">GPS Tracking</p>
                    </div>
                    <div class="col-2">
                        <input id="gpstrackingcheck" type="checkbox" data-toggle="toggle" data-onstyle="success" data-size="sm" <?= $gpstrackingcheck;?>>
                        <script>
                            $(function() {
                            $('#gpstrackingcheck').change(function() {
                                $.ajax({
                                url:'<?php echo base_url('User/update_attendance_option')?>',
                                data:{checked:$(this).prop('checked'),type:'gpstrackingcheck'},
                                type:'post'
                                });
                            });
                            });
                        </script>
                    </div>
                </div>
                <div class="row">
                    <div class="col-1">
                    <p class="text-sm-left">7)</p>
                    </div>
                    <div class="col">
                        <p class="text-sm-left">Field Duty</p>
                    </div>
                    <div class="col-2">
                        <input id="fieldcheck" type="checkbox" data-toggle="toggle" data-onstyle="success" data-size="sm" <?= $fieldcheck;?>>
                        <script>
                            $(function() {
                            $('#fieldcheck').change(function() {
                                $.ajax({
                                url:'<?php echo base_url('User/update_attendance_option')?>',
                                data:{checked:$(this).prop('checked'),type:'fieldcheck'},
                                type:'post'
                                });
                            });
                            });
                        </script>
                    </div>
                </div>
                <div class="row">
                    <div class="col-1">
                    <p class="text-sm-left">8)</p>
                    </div>
                    <div class="col">
                        <p class="text-sm-left">Four Layer Security</p>
                    </div>
                    <div class="col-2">
                        <input id="fourlayercheck" type="checkbox" data-toggle="toggle" data-onstyle="success" data-size="sm" <?= $fourlayercheck;?>>
                        <script>
                            $(function() {
                            $('#fourlayercheck').change(function() {
                                $.ajax({
                                url:'<?php echo base_url('User/update_attendance_option')?>',
                                data:{checked:$(this).prop('checked'),type:'fourlayercheck'},
                                type:'post'
                                });
                            });
                            });
                        </script>
                    </div>
                </div>
                <div class="row">
                    <div class="col-1">
                    <p class="text-sm-left">9)</p>
                    </div>
                    <div class="col">
                        <p class="text-sm-left">GPS Attendance with Selfie</p>
                    </div>
                    <div class="col-2">
                        <input id="gpsselfiecheck" type="checkbox" data-toggle="toggle" data-onstyle="success" data-size="sm" <?= $gpsselfiecheck;?>>
                        <script>
                            $(function() {
                            $('#gpsselfiecheck').change(function() {
                                $.ajax({
                                url:'<?php echo base_url('User/update_attendance_option')?>',
                                data:{checked:$(this).prop('checked'),type:'gpsselfiecheck'},
                                type:'post'
                                });
                            });
                            });
                        </script>
                    </div>
                </div>
                <div class="row">
                    <div class="col-1">
                    <p class="text-sm-left">10)</p>
                    </div>
                    <div class="col">
                        <p class="text-sm-left">Field Duty with Selfie</p>
                    </div>
                    <div class="col-2">
                        <input id="fieldselfiecheck" type="checkbox" data-toggle="toggle" data-onstyle="success" data-size="sm" <?= $fieldselfiecheck;?>>
                        <script>
                            $(function() {
                            $('#fieldselfiecheck').change(function() {
                                $.ajax({
                                url:'<?php echo base_url('User/update_attendance_option')?>',
                                data:{checked:$(this).prop('checked'),type:'fieldselfiecheck'},
                                type:'post'
                                });
                            });
                            });
                        </script>
                    </div>
                </div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid --><?php
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
<script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>
<script>
  $(function () {
   var table = $('#example1').DataTable({
     "responsive": true,
      "autoWidth": false,
    });
   
  });
</script>
<script>
function mclick(data){
  var add_d_data = "add_depart";
  $.ajax({
      type: "POST",
      url: "User/getajaxRequest",
      data: {data,add_d_data},
    success: function(response){
      $('#modform').html(response);
    }
    })
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
