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
  <link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">

  <style>
  #accordion .card-title:after {
    content: "\f107";
    font-family: 'Font Awesome 5 Free';
    font-weight: 900;
    float: right;
  }
  #accordion .card-title.collapsed:after {
    content: "\f105";
  }
  </style>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
  <script src="<?php echo base_url('adminassets/plugins/jquery/jquery.min.js')?>"></script>
</head>
<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <?php $this->load->view('menu/menu')?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">

            <div class="col-sm-12">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Employee Roll</li>
              </ol>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>
      <?php
      if($this->session->userdata()['type']=='B')
      {?>
        <!-- Main content -->
        <section class="content">
          <div class="container-fluid">
            <div class="row">
              <!-- left column -->
              <div class="col-md-12">
                <div class="card card-danger">
                  <div class="card-header">
                    <h3 class="card-title">Employee Roll</h3>
                  </div>
                  
                  <!-- /.card -->
                </div>
               
                        
                          <div class="row">
                            <div class="col-1">
                              <p class="text-sm-left font-weight-bold">S.No.</p>
                            </div>
                            <div class="col-3">
                              <h5></h5>
                            </div>
                            
                            <div class="col-2">
                              <p class="text-sm-left font-weight-bold">ON/Off</p>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-1">
                              <p class="text-sm-left">1)</p>
                            </div>
                            <div class="col-3">
                              <p class="text-sm-left">Show Employee List</p>
                            </div>
                         <!--   <div class="col-2">
                              <p class="text-sm-left"></p>
                            </div>-->
                            
                            <?php $res = $this->web->addAttendanceRule($rule);?>
                            
                            
                            <div class="col-2">
                              <input id="emp_list<?php echo $rule->id;?>" type="checkbox" data-toggle="toggle" data-onstyle="success" data-size="sm" <?php if($rule->mispunch=="1"){echo "checked";} ?> class="text-sm-left">
                              <script>
                              $(function() {
                                $('#mispunch<?php echo $rule->id;?>').change(function() {
                                  $.ajax({
                                    url:'<?php echo base_url('User/update_attendance_rule_by_id')?>',
                                    data:{checked:$(this).prop('checked'),rule_id:<?php echo $rule->id;?>,type:'mispunch'},
                                    type:'post'
                                  });
                                })
                              })
                            </script>
                            </div>
                          </div>
                          
                         <div class="row">
                            <div class="col-1">
                              <p class="text-sm-left">2)</p>
                            </div>
                            <div class="col-3">
                              <p class="text-sm-left">Add Employee</p>
                            </div>
                         <!--   <div class="col-2">
                              <p class="text-sm-left"></p>
                            </div>-->
                            <div class="col-2">
                              <input id="mispunch<?php echo $rule->id;?>" type="checkbox" data-toggle="toggle" data-onstyle="success" data-size="sm" <?php if($rule->mispunch=="1"){echo "checked";} ?>>
                              
                            </div>
                          </div> 
                          <div class="row">
                            <div class="col-1">
                              <p class="text-sm-left">3)</p>
                            </div>
                            <div class="col-3">
                              <p class="">Manual Attendance</p>
                            
                         <!--   <div class="col-2">
                              <p class="text-sm-left"></p>
                            </div>-->
                            </div>
                           <div class="col-2">
                              <input id="mispunch<?php echo $rule->id;?>" type="checkbox" data-toggle="toggle" data-onstyle="success" data-size="sm" <?php if($rule->mispunch=="1"){echo "checked";} ?>>
                              
                            </div>
                          </div> 
                          
                          <div class="row">
                            <div class="col-1">
                              <p class="text-sm-left">4)</p>
                            </div>
                            <div class="col-3">
                              <p class="text-sm-left">Daily Report</p>
                            </div>
                         <!--   <div class="col-2">
                              <p class="text-sm-left"></p>
                            </div>-->
                            <div class="col-2">
                              <input id="mispunch<?php echo $rule->id;?>" type="checkbox" data-toggle="toggle" data-onstyle="success" data-size="sm" <?php if($rule->mispunch=="1"){echo "checked";} ?>>
                              
                            </div>
                          </div> 
                          
                          <div class="row">
                            <div class="col-1">
                              <p class="text-sm-left">5)</p>
                            </div>
                            <div class="col-3">
                              <p class="text-sm-left">Other Attendance Report</p>
                            </div>
                         <!--   <div class="col-2">
                              <p class="text-sm-left"></p>
                            </div>-->
                            <div class="col-2">
                              <input id="mispunch<?php echo $rule->id;?>" type="checkbox" data-toggle="toggle" data-onstyle="success" data-size="sm" <?php if($rule->mispunch=="1"){echo "checked";} ?>>
                              
                            </div>
                          </div> 
                          
                          
                          <div class="row">
                            <div class="col-1">
                              <p class="text-sm-left">6)</p>
                            </div>
                            <div class="col-3">
                              <p class="text-sm-left">Attendanec Setting</p>
                            </div>
                         <!--   <div class="col-2">
                              <p class="text-sm-left"></p>
                            </div>-->
                            <div class="col-2">
                              <input id="mispunch<?php echo $rule->id;?>" type="checkbox" data-toggle="toggle" data-onstyle="success" data-size="sm" <?php if($rule->mispunch=="1"){echo "checked";} ?>>
                              
                            </div>
                          </div> 
                          
                          <div class="row">
                            <div class="col-1">
                              <p class="text-sm-left">7)</p>
                            </div>
                            <div class="col-3">
                              <p class="text-sm-left">Pending Attendanec </p>
                            </div>
                         <!--   <div class="col-2">
                              <p class="text-sm-left"></p>
                            </div>-->
                            <div class="col-2">
                              <input id="mispunch<?php echo $rule->id;?>" type="checkbox" data-toggle="toggle" data-onstyle="success" data-size="sm" <?php if($rule->mispunch=="1"){echo "checked";} ?>>
                              
                            </div>
                          </div> 
                          
                          <div class="row">
                            <div class="col-1">
                              <p class="text-sm-left">8)</p>
                            </div>
                            <div class="col-3">
                              <p class="text-sm-left">Salary </p>
                            </div>
                         <!--   <div class="col-2">
                              <p class="text-sm-left"></p>
                            </div>-->
                            <div class="col-2">
                              <input id="mispunch<?php echo $rule->id;?>" type="checkbox" data-toggle="toggle" data-onstyle="success" data-size="sm" <?php if($rule->mispunch=="1"){echo "checked";} ?>>
                              
                            </div>
                          </div> 
                          
                          <div class="row">
                            <div class="col-1">
                              <p class="text-sm-left">9)</p>
                            </div>
                            <div class="col-3">
                              <p class="text-sm-left">Attendance Option </p>
                            </div>
                         <!--   <div class="col-2">
                              <p class="text-sm-left"></p>
                            </div>-->
                            <div class="col-2">
                              <input id="mispunch<?php echo $rule->id;?>" type="checkbox" data-toggle="toggle" data-onstyle="success" data-size="sm" <?php if($rule->mispunch=="1"){echo "checked";} ?>>
                              
                            </div>
                          </div> 
                          
                          
                          <div class="row">
                            <div class="col-1">
                              <p class="text-sm-left">10)</p>
                            </div>
                            <div class="col-3">
                              <p class="text-sm-left">Leave Management </p>
                            </div>
                         <!--   <div class="col-2">
                              <p class="text-sm-left"></p>
                            </div>-->
                            <div class="col-2">
                              <input id="mispunch<?php echo $rule->id;?>" type="checkbox" data-toggle="toggle" data-onstyle="success" data-size="sm" <?php if($rule->mispunch=="1"){echo "checked";} ?>>
                              
                            </div>
                          </div> 
                          
                          
                          <div class="row">
                            <div class="col-1">
                              <p class="text-sm-left">11)</p>
                            </div>
                            <div class="col-3">
                              <p class="text-sm-left">Assign Employee </p>
                            </div>
                         <!--   <div class="col-2">
                              <p class="text-sm-left"></p>
                            </div>-->
                            <div class="col-2">
                              <input id="mispunch<?php echo $rule->id;?>" type="checkbox" data-toggle="toggle" data-onstyle="success" data-size="sm" <?php if($rule->mispunch=="1"){echo "checked";} ?>>
                              
                            </div>
                          </div> 
                          
                         
                         
                         <div class="row">
                            <div class="col-1">
                              <p class="text-sm-left">12)</p>
                            </div>
                            <div class="col-3">
                              <p class="text-sm-left">Manager Roll </p>
                            </div>
                         <!--   <div class="col-2">
                              <p class="text-sm-left"></p>
                            </div>-->
                            <div class="col-2">
                              <input id="mispunch<?php echo $rule->id;?>" type="checkbox" data-toggle="toggle" data-onstyle="success" data-size="sm" <?php if($rule->mispunch=="1"){echo "checked";} ?>>
                              
                            </div>
                          </div>  
                          
                       
                      </div>
                    </div>
                  <?php ?>
                </div>
              </div>
              <!-- /.row -->
          
          </div><!-- /.container-fluid -->
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
  <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>

  <script>
  $(document).ready(function () {
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
