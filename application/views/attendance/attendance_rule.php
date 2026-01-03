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
                <li class="breadcrumb-item active">Attendance Rules</li>
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
                <div class="card card-danger">
                  <div class="card-header">
                    <h3 class="card-title">Attendance Rules</h3>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-lg-12 float-left">
                        <!-- Button trigger modal -->
                        <div align="right">
                          <a href="<?php echo base_url('User/add_attendance_rule')?>"><button type="button" class="btn btn-success">Add New Rule</button></a>
                        </div>
                      </div>
                    </div>
                    <!-- /.card-body -->
                  </div>
                  <!-- /.card -->
                </div>
                <div id="accordion">
                  <?php
                  function getHrs($seconds){
                    $hours =0;
                    if($seconds>0){
                      $hours = floor($seconds / 3600);
                    }
                    return $hours;
                  }
                  function getMin($seconds){
                    $minutes =0;
                    if($seconds>0){
                      $minutes = floor($seconds / 60%60);
                    }
                    return $minutes;
                  }
                  $count=-1;
                  foreach($rules as $rule){
                    $count++;
                    ?>
                    <div class="card">
                      <div class="card-body">
                        <div class="row">
                          <h3 class="card-title col <?php if($count>0){echo 'collapsed';}?>" data-toggle="collapse" data-target="#collapse<?php echo $rule->id;?>" aria-expanded="<?php if($count==0){echo 'false';} ?>" aria-controls="collapse<?php echo $rule->id;?>"><?php echo $rule->name;?></h3><i class="fas fa-edit" data-toggle="modal" data-target="#attendanceRuleModal<?php echo $rule->id;?>"></i>
                        </div>
                        <div id="collapse<?php echo $rule->id;?>" class="collapse <?php if($count==0){echo 'show';} ?>" aria-labelledby="heading<?php echo $rule->id;?>" data-parent="#accordion">

                          <div class="row">
                            <div class="col-2">
                              <p class="text-sm-left font-weight-bold">S.No.</p>
                            </div>
                            <div class="col">
                              <h5></h5>
                            </div>
                            <div class="col-2">
                              <p class="text-sm-left font-weight-bold">Time</p>
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
                              <p class="text-sm-left">Show MisPunch if out not marked</p>
                            </div>
                            <div class="col-2">
                              <p class="text-sm-left"></p>
                            </div>
                            <div class="col-2">
                              <input id="mispunch<?php echo $rule->id;?>" type="checkbox" data-toggle="toggle" data-onstyle="success" data-size="sm" <?php if($rule->mispunch=="1"){echo "checked";} ?>>
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
                            <div class="col">
                              <p class="text-sm-left">Short leave if late more than</p>
                            </div>
                            <div class="col-2">
                              <div class="input-group">
                                <input id="sl_late_time_hr<?php echo $rule->id;?>" type="number" placeholder="Hr" name="sl_late_time_hr" min="0" max="23" value="<?php echo getHrs($rule->sl_late);?>">
                                <input id="sl_late_time_mn<?php echo $rule->id;?>" type="number" placeholder="Min" name="sl_late_time_mn" min="0" max="59" value="<?php echo getMin($rule->sl_late);?>">Hr
                                <script>
                                $(function() {
                                  $('#sl_late_time_hr<?php echo $rule->id;?>').change(function() {
                                    $.ajax({
                                      url:'<?php echo base_url('User/update_attendance_rule_by_id')?>',
                                      data:{checked:$(this).val(),rule_id:<?php echo $rule->id;?>,type:'sl_late_time',checkedMin:$("#sl_late_time_mn<?php echo $rule->id;?>").val()},
                                      type:'post'
                                    });
                                  })
                                });
                                $(function() {
                                  $('#sl_late_time_mn<?php echo $rule->id;?>').change(function() {
                                    $.ajax({
                                      url:'<?php echo base_url('User/update_attendance_rule_by_id')?>',
                                      data:{checked:$("#sl_late_time_hr<?php echo $rule->id;?>").val(),rule_id:<?php echo $rule->id;?>,type:'sl_late_time',checkedMin:$(this).val()},
                                      type:'post'
                                    });
                                  })
                                });
                              </script>
                              </div>
                            </div>
                            <div class="col-2">
                              <input id="sl_late_on<?php echo $rule->id;?>" type="checkbox" data-toggle="toggle" data-onstyle="success" data-size="sm" <?php if($rule->sl_late_on>0){echo "checked";} ?>>
                              <script>
                              $(function() {
                                $('#sl_late_on<?php echo $rule->id;?>').change(function() {
                                  $.ajax({
                                    url:'<?php echo base_url('User/update_attendance_rule_by_id')?>',
                                    data:{checked:$(this).prop('checked'),rule_id:<?php echo $rule->id;?>,type:'sl_late_on'},
                                    type:'post'
                                  });
                                })
                              })
                            </script>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-1">
                              <p class="text-sm-left">3)</p>
                            </div>
                            <div class="col">
                              <p class="text-sm-left">Short leave if out Early more than</p>
                            </div>
                            <div class="col-2">
                              <div class="input-group">
                                <input id="sl_early_time_hr<?php echo $rule->id;?>" type="number" placeholder="Hr" name="sl_early_time_hr" min="0" max="23" value="<?php echo getHrs($rule->sl_early);?>">
                                <input id="sl_early_time_mn<?php echo $rule->id;?>" type="number" placeholder="Min" name="sl_early_time_mn" min="0" max="59" value="<?php echo getMin($rule->sl_early);?>">Hr
                                <script>
                                $(function() {
                                  $('#sl_early_time_hr<?php echo $rule->id;?>').change(function() {
                                    $.ajax({
                                      url:'<?php echo base_url('User/update_attendance_rule_by_id')?>',
                                      data:{checked:$(this).val(),rule_id:<?php echo $rule->id;?>,type:'sl_early_time',checkedMin:$("#sl_early_time_mn<?php echo $rule->id;?>").val()},
                                      type:'post'
                                    });
                                  })
                                });
                                $(function() {
                                  $('#sl_early_time_mn<?php echo $rule->id;?>').change(function() {
                                    $.ajax({
                                      url:'<?php echo base_url('User/update_attendance_rule_by_id')?>',
                                      data:{checked:$("#sl_early_time_hr<?php echo $rule->id;?>").val(),rule_id:<?php echo $rule->id;?>,type:'sl_early_time',checkedMin:$(this).val()},
                                      type:'post'
                                    });
                                  })
                                });
                              </script>
                              </div>
                            </div>
                            <div class="col-2">
                              <input id="sl_early_on<?php echo $rule->id;?>" type="checkbox" data-toggle="toggle" data-onstyle="success" data-size="sm" <?php if($rule->sl_early_on>0){echo "checked";} ?>>
                              <script>
                              $(function() {
                                $('#sl_early_on<?php echo $rule->id;?>').change(function() {
                                  $.ajax({
                                    url:'<?php echo base_url('User/update_attendance_rule_by_id')?>',
                                    data:{checked:$(this).prop('checked'),rule_id:<?php echo $rule->id;?>,type:'sl_early_on'},
                                    type:'post'
                                  });
                                })
                              })
                            </script>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-1">
                              <p class="text-sm-left">4)</p>
                            </div>
                            <div class="col">
                              <p class="text-sm-left">Half day if working hour is less than</p>
                            </div>
                            <div class="col-2">
                              <div class="input-group">
                                <input id="halfday_hr<?php echo $rule->id;?>" type="number" placeholder="Hr" name="halfday_hr" min="0" max="23" value="<?php echo getHrs($rule->halfday);?>">
                                <input id="halfday_mn<?php echo $rule->id;?>" type="number" placeholder="Min" name="halfday_mn" min="0" max="59" value="<?php echo getMin($rule->halfday);?>">Hr
                                <script>
                                $(function() {
                                  $('#halfday_hr<?php echo $rule->id;?>').change(function() {
                                    $.ajax({
                                      url:'<?php echo base_url('User/update_attendance_rule_by_id')?>',
                                      data:{checked:$(this).val(),rule_id:<?php echo $rule->id;?>,type:'halfday_time',checkedMin:$("#halfday_mn<?php echo $rule->id;?>").val()},
                                      type:'post'
                                    });
                                  })
                                });
                                $(function() {
                                  $('#halfday_mn<?php echo $rule->id;?>').change(function() {
                                    $.ajax({
                                      url:'<?php echo base_url('User/update_attendance_rule_by_id')?>',
                                      data:{checked:$("#halfday_hr<?php echo $rule->id;?>").val(),rule_id:<?php echo $rule->id;?>,type:'halfday_time',checkedMin:$(this).val()},
                                      type:'post'
                                    });
                                  })
                                });
                              </script>
                              </div>
                            </div>
                            <div class="col-2">
                              <input id="halfday_on<?php echo $rule->id;?>" type="checkbox" data-toggle="toggle" data-onstyle="success" data-size="sm" <?php if($rule->halfday_on>0){echo "checked";} ?>>
                              <script>
                              $(function() {
                                $('#halfday_on<?php echo $rule->id;?>').change(function() {
                                  $.ajax({
                                    url:'<?php echo base_url('User/update_attendance_rule_by_id')?>',
                                    data:{checked:$(this).prop('checked'),rule_id:<?php echo $rule->id;?>,type:'halfday_on'},
                                    type:'post'
                                  });
                                })
                              })
                            </script>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-1">
                              <p class="text-sm-left">5)</p>
                            </div>
                            <div class="col">
                              <p class="text-sm-left">Absent if working hour is less than</p>
                            </div>
                            <div class="col-2">
                              <div class="input-group">
                                <input id="absent_hr<?php echo $rule->id;?>" type="number" placeholder="Hr" name="absent_hr" min="0" max="23" value="<?php echo getHrs($rule->absent);?>">
                                <input id="absent_mn<?php echo $rule->id;?>" type="number" placeholder="Min" name="absent_mn" min="0" max="59" value="<?php echo getMin($rule->absent);?>">Hr
                                <script>
                                $(function() {
                                  $('#absent_hr<?php echo $rule->id;?>').change(function() {
                                    $.ajax({
                                      url:'<?php echo base_url('User/update_attendance_rule_by_id')?>',
                                      data:{checked:$(this).val(),rule_id:<?php echo $rule->id;?>,type:'absent_time',checkedMin:$("#absent_mn<?php echo $rule->id;?>").val()},
                                      type:'post'
                                    });
                                  })
                                });
                                $(function() {
                                  $('#absent_mn<?php echo $rule->id;?>').change(function() {
                                    $.ajax({
                                      url:'<?php echo base_url('User/update_attendance_rule_by_id')?>',
                                      data:{checked:$("#absent_hr<?php echo $rule->id;?>").val(),rule_id:<?php echo $rule->id;?>,type:'absent_time',checkedMin:$(this).val()},
                                      type:'post'
                                    });
                                  })
                                });
                              </script>
                              </div>
                            </div>
                            <div class="col-2">
                              <input id="absent_on<?php echo $rule->id;?>" type="checkbox" data-toggle="toggle" data-onstyle="success" data-size="sm" <?php if($rule->absent_on>0){echo "checked";} ?>>
                              <script>
                              $(function() {
                                $('#absent_on<?php echo $rule->id;?>').change(function() {
                                  $.ajax({
                                    url:'<?php echo base_url('User/update_attendance_rule_by_id')?>',
                                    data:{checked:$(this).prop('checked'),rule_id:<?php echo $rule->id;?>,type:'absent_on'},
                                    type:'post'
                                  });
                                })
                              })
                            </script>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-1">
                              <p class="text-sm-left">6)</p>
                            </div>
                            <div class="col">
                              <p class="text-sm-left">Overtime calculate if shift out more than</p>
                            </div>
                            <div class="col-2">
                              <div class="input-group">
                                <input id="overtime_shiftout_hr<?php echo $rule->id;?>" type="number" placeholder="Hr" name="overtime_shiftout_hr" min="0" max="23" value="<?php echo getHrs($rule->overtime_shiftout);?>">
                                <input id="overtime_shiftout_mn<?php echo $rule->id;?>" type="number" placeholder="Min" name="overtime_shiftout_mn" min="0" max="59" value="<?php echo getMin($rule->overtime_shiftout);?>">Hr
                                <script>
                                $(function() {
                                  $('#overtime_shiftout_hr<?php echo $rule->id;?>').change(function() {
                                    $.ajax({
                                      url:'<?php echo base_url('User/update_attendance_rule_by_id')?>',
                                      data:{checked:$(this).val(),rule_id:<?php echo $rule->id;?>,type:'overtime_shiftout_time',checkedMin:$("#overtime_shiftout_mn<?php echo $rule->id;?>").val()},
                                      type:'post'
                                    });
                                  })
                                });
                                $(function() {
                                  $('#overtime_shiftout_mn<?php echo $rule->id;?>').change(function() {
                                    $.ajax({
                                      url:'<?php echo base_url('User/update_attendance_rule_by_id')?>',
                                      data:{checked:$("#overtime_shiftout_hr<?php echo $rule->id;?>").val(),rule_id:<?php echo $rule->id;?>,type:'overtime_shiftout_time',checkedMin:$(this).val()},
                                      type:'post'
                                    });
                                  })
                                });
                              </script>
                              </div>
                            </div>
                            <div class="col-2">
                              <input id="overtime_shift<?php echo $rule->id;?>" type="checkbox" data-toggle="toggle" data-onstyle="success" data-size="sm" <?php if($rule->overtime_shift>0){echo "checked";} ?>>
                              <script>
                              $(function() {
                                $('#overtime_shift<?php echo $rule->id;?>').change(function() {
                                  $.ajax({
                                    url:'<?php echo base_url('User/update_attendance_rule_by_id')?>',
                                    data:{checked:$(this).prop('checked'),rule_id:<?php echo $rule->id;?>,type:'overtime_shift'},
                                    type:'post'
                                  });
                                })
                              })
                            </script>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-1">
                              <p class="text-sm-left">7)</p>
                            </div>
                            <div class="col">
                              <p class="text-sm-left">Overtime calculate if Working hours  more than</p>
                            </div>
                            <div class="col-2">
                              <div class="input-group">
                                <input id="overtime_wh_hr<?php echo $rule->id;?>" type="number" placeholder="Hr" name="overtime_wh_hr" min="0" max="23" value="<?php echo getHrs($rule->overtime_wh);?>">
                                <input id="overtime_wh_mn<?php echo $rule->id;?>" type="number" placeholder="Min" name="overtime_wh_mn" min="0" max="59" value="<?php echo getMin($rule->overtime_wh);?>">Hr
                                <script>
                                $(function() {
                                  $('#overtime_wh_hr<?php echo $rule->id;?>').change(function() {
                                    $.ajax({
                                      url:'<?php echo base_url('User/update_attendance_rule_by_id')?>',
                                      data:{checked:$(this).val(),rule_id:<?php echo $rule->id;?>,type:'overtime_wh_time',checkedMin:$("#overtime_wh_mn<?php echo $rule->id;?>").val()},
                                      type:'post'
                                    });
                                  })
                                });
                                $(function() {
                                  $('#overtime_wh_mn<?php echo $rule->id;?>').change(function() {
                                    $.ajax({
                                      url:'<?php echo base_url('User/update_attendance_rule_by_id')?>',
                                      data:{checked:$("#overtime_wh_hr<?php echo $rule->id;?>").val(),rule_id:<?php echo $rule->id;?>,type:'overtime_wh_time',checkedMin:$(this).val()},
                                      type:'post'
                                    });
                                  })
                                });
                              </script>
                              </div>
                            </div>
                            <div class="col-2">
                              <input id="overtime_wh_on<?php echo $rule->id;?>" type="checkbox" data-toggle="toggle" data-onstyle="success" data-size="sm" <?php if($rule->overtime_wh_on>0){echo "checked";} ?>>
                              <script>
                              $(function() {
                                $('#overtime_wh_on<?php echo $rule->id;?>').change(function() {
                                  $.ajax({
                                    url:'<?php echo base_url('User/update_attendance_rule_by_id')?>',
                                    data:{checked:$(this).prop('checked'),rule_id:<?php echo $rule->id;?>,type:'overtime_wh_on'},
                                    type:'post'
                                  });
                                })
                              })
                            </script>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-1">
                              <p class="text-sm-left">8)</p>
                            </div>
                            <div class="col">
                              <p class="text-sm-left">Calculate Working hours as Last Out and first in</p>
                            </div>
                            <div class="col-2">
                              <p class="text-sm-left"></p>
                            </div>
                            <div class="col-2">
                              <input id="wh_cal<?php echo $rule->id;?>" type="checkbox" data-toggle="toggle" data-onstyle="success" data-size="sm" <?php if($rule->wh_cal>0){echo "checked";} ?>>
                              <script>
                              $(function() {
                                $('#wh_cal<?php echo $rule->id;?>').change(function() {
                                  $.ajax({
                                    url:'<?php echo base_url('User/update_attendance_rule_by_id')?>',
                                    data:{checked:$(this).prop('checked'),rule_id:<?php echo $rule->id;?>,type:'wh_cal'},
                                    type:'post'
                                  });
                                })
                              })
                            </script>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-1">
                              <p class="text-sm-left">9)</p>
                            </div>
                            <div class="col">
                              <p class="text-sm-left">Mark  absent if absent before and after weekly off</p>
                            </div>
                            <div class="col-2">
                              <p class="text-sm-left"></p>
                            </div>
                            <div class="col-2">
                              <input id="wo_absent<?php echo $rule->id;?>" type="checkbox" data-toggle="toggle" data-onstyle="success" data-size="sm" <?php if($rule->wo_absent>0){echo "checked";} ?>>
                              <script>
                              $(function() {
                                $('#wo_absent<?php echo $rule->id;?>').change(function() {
                                  $.ajax({
                                    url:'<?php echo base_url('User/update_attendance_rule_by_id')?>',
                                    data:{checked:$(this).prop('checked'),rule_id:<?php echo $rule->id;?>,type:'wo_absent'},
                                    type:'post'
                                  });
                                })
                              })
                            </script>
                            </div>
                          </div>
                          
                           <div class="row">
                            <div class="col-1">
                              <p class="text-sm-left">11)</p>
                            </div>
                            <div class="col">
                              <p class="text-sm-left">Auto Weekly off Applied in a month </p>
                            </div>
                            <div class="col-5"> 
                            </div>
                           <div class="col-1">
                              <div class="input-group">
                                <input id="auto_wo<?php echo $rule->id;?>" type="number" placeholder="Days" name="auto_wo" min="0" max="20" value="<?php echo ($rule->auto_wo);?>"> 
                               
                                <script>
                                $(function() {
                                  $('#auto_wo<?php echo $rule->id;?>').change(function() {
                                    $.ajax({
                                      url:'<?php echo base_url('User/update_attendance_rule_by_id')?>',
                                      data:{checked:$(this).val(),rule_id:<?php echo $rule->id;?>,type:'auto_wo'},
                                      type:'post'
                                    });
                                  })
                                });
                                
                              </script>
                              </div>
                              <span> Days</span>
                            </div> 
                           
                            
                           
                            <div class="col-2">
                              <input id="auto_wo_on<?php echo $rule->id;?>" type="checkbox" data-toggle="toggle" data-onstyle="success" data-size="sm" <?php if($rule->auto_wo_on>0){echo "checked";} ?>>
                              <script>
                              $(function() {
                                $('#auto_wo_on<?php echo $rule->id;?>').change(function() {
                                  $.ajax({
                                    url:'<?php echo base_url('User/update_attendance_rule_by_id')?>',
                                    data:{checked:$(this).prop('checked'),rule_id:<?php echo $rule->id;?>,type:'auto_wo_on'},
                                    type:'post'
                                  });
                                })
                              })
                            </script>
                            </div>
                          </div>           
                          
                          
         <!--       new salay rule  -->
               
               <div class="row">
                            
                            <div class="col">
                            <br>
                            <br>
                            
                              <h5>
                              
                              Salary Rule:- Fine and Overtime
                              
                              
                              </h5>
                            </div>
                            
                          </div>
               
               
               
               <div class="row">
                            <div class="col-2">
                              <p class="text-sm-left font-weight-bold">S.No.</p>
                            </div>
                            <div class="col">
                              <h5></h5>
                            </div>
                            <div class="col-3">
                              <p class="text-sm-left font-weight-bold">Amount</p>
                            </div>
                            <div class="col-2">
                              <p class="text-sm-left font-weight-bold">Time </p>
                            </div
                            ><div class="col-2">
                              <p class="text-sm-left font-weight-bold">On/Off</p>
                            </div>
                          </div>
               
               
               
               
               
               <div class="row">
                            <div class="col-1">
                              <p class="text-sm-left">12)</p>
                            </div>
                            <div class="col-4">
                              <p class="text-sm-left">Late Punch in  Fine after </p>
                            </div>
                               <!-- <input id="lt_punchin<?php echo $rule->id;?>" type="number" placeholder="Amount" name="lt_punchin" min="0"  value="<?php echo ($rule->lt_punchin);?>"> Rs.-->
                                <div class="col-1">
                              <div class="input-group">
                                 
                                 <select name="lt_punchin" id="lt_punchin<?php echo $rule->id;?>"  class="bg-light"> 
                                <option value="<?php if($rule->lt_punchin>0){?>"> Manual <?php } else { ?> Auto <?php } ?> </option>
                                                                <option value="0">Auto As Salary</option>
                                                                <option value="100">Manual</option>
                                                           </select>
                           &nbsp
                                                         
                                              <?php if($rule->lt_punchin>0){?>
                                             <input id="lt_punchin2<?php echo $rule->id;?>" type="number" placeholder="Amount" name="lt_punchin2" min="1"  value="<?php echo ($rule->lt_punchin);?>"> 
                                         <?php }?>    
                                                           
                               
                                <script>
                                $(function() {
                                  $('#lt_punchin<?php echo $rule->id;?>').change(function() {
                                    $.ajax({
                                      url:'<?php echo base_url('User/update_attendance_rule_by_id')?>',
                                      data:{checked:$(this).val(),rule_id:<?php echo $rule->id;?>,type:'lt_punchin'},
                                      type:'post'
                                    });
                                  })
                                });
                                
                              </script>
                              
                              <script>
                                $(function() {
                                  $('#lt_punchin2<?php echo $rule->id;?>').change(function() {
                                    $.ajax({
                                      url:'<?php echo base_url('User/update_attendance_rule_by_id')?>',
                                      data:{checked:$(this).val(),rule_id:<?php echo $rule->id;?>,type:'lt_punchin'},
                                      type:'post'
                                    });
                                  })
                                });
                                
                              </script>
                              
                            </div> 
                           </div>
                            
                            
                           <div class="col-2">
                           </div> 
                            
                          
                            
                            
                           <div class="col-2">
                              <div class="input-group">
                                <input id="lt_punchin_time_hr<?php echo $rule->id;?>" type="number" placeholder="Hr" name="lt_punchin_time_hr" min="0" max="23" value="<?php echo getHrs($rule->lt_punchin_time);?>">
                                <input id="lt_punchin_time_mn<?php echo $rule->id;?>" type="number" placeholder="Min" name="lt_punchin_time_mn" min="0" max="59" value="<?php echo getMin($rule->lt_punchin_time);?>">Hr
                                <script>
                                $(function() {
                                  $('#lt_punchin_time_hr<?php echo $rule->id;?>').change(function() {
                                    $.ajax({
                                      url:'<?php echo base_url('User/update_attendance_rule_by_id')?>',
                                      data:{checked:$(this).val(),rule_id:<?php echo $rule->id;?>,type:'lt_punchin_time',checkedMin:$("#lt_punchin_time_mn<?php echo $rule->id;?>").val()},
                                      type:'post'
                                    });
                                  })
                                });
                                $(function() {
                                  $('#lt_punchin_time_mn<?php echo $rule->id;?>').change(function() {
                                    $.ajax({
                                      url:'<?php echo base_url('User/update_attendance_rule_by_id')?>',
                                      data:{checked:$("#lt_punchin_time_hr<?php echo $rule->id;?>").val(),rule_id:<?php echo $rule->id;?>,type:'lt_punchin_time',checkedMin:$(this).val()},
                                      type:'post'
                                    });
                                  })
                                });
                              </script>
                              </div>
                            </div> 
                            
                           
                            <div class="col-2" align="left">
                              <input id="lt_punchin_on<?php echo $rule->id;?>" type="checkbox" data-toggle="toggle" data-onstyle="success" data-size="sm" <?php if($rule->lt_punchin_on>0){echo "checked";} ?>>
                              <script>
                              $(function() {
                                $('#lt_punchin_on<?php echo $rule->id;?>').change(function() {
                                  $.ajax({
                                    url:'<?php echo base_url('User/update_attendance_rule_by_id')?>',
                                    data:{checked:$(this).prop('checked'),rule_id:<?php echo $rule->id;?>,type:'lt_punchin_on'},
                                    type:'post'
                                  });
                                })
                              })
                            </script>
                            </div>
                          </div>           
                          
                          
                          
                           &nbsp 
                          
                          
                          
                          
                           <div class="row">
                            <div class="col-1">
                              <p class="text-sm-left">13)</p>
                            </div>
                            <div class="col-4">
                              <p class="text-sm-left">Early  Punch out  Fine before</p>
                            </div>
                           
                            <div class="col-1">
                              <div class="input-group">
                                
                                 <select name="el_punchout" id="el_punchout<?php echo $rule->id;?>"  class="bg-light"> 
                                <option value="<?php if($rule->el_punchout>0){?>"> Manual <?php } else { ?> Auto <?php } ?> </option>
                                                                <option value="0">Auto As Salary</option>
                                                                <option value="100">Manual</option>
                                                             </select>
                                                             
                                    &nbsp                          
                                                             
                                              <?php if($rule->el_punchout>0){?>
                                             <input id="el_punchout2<?php echo $rule->id;?>" type="number" placeholder="Amount" name="el_punchout2" min="1"  value="<?php echo ($rule->el_punchout);?>">  Rs.
                                         <?php }?>    
                                                           
                               
                                <script>
                                $(function() {
                                  $('#el_punchout<?php echo $rule->id;?>').change(function() {
                                    $.ajax({
                                      url:'<?php echo base_url('User/update_attendance_rule_by_id')?>',
                                      data:{checked:$(this).val(),rule_id:<?php echo $rule->id;?>,type:'el_punchout'},
                                      type:'post'
                                    });
                                  })
                                });
                                
                              </script>
                              
                              <script>
                                $(function() {
                                  $('#el_punchout2<?php echo $rule->id;?>').change(function() {
                                    $.ajax({
                                      url:'<?php echo base_url('User/update_attendance_rule_by_id')?>',
                                      data:{checked:$(this).val(),rule_id:<?php echo $rule->id;?>,type:'el_punchout'},
                                      type:'post'
                                    });
                                  })
                                });
                                
                              </script>
                              </div>
                            </div>
                            
                           
                            
                            
                           <div class="col-2">
                           </div> 
                            
                          
                            
                            
                           <div class="col-2">
                              <div class="input-group">
                                <input id="el_punchout_time_hr<?php echo $rule->id;?>" type="number" placeholder="Hr" name="el_punchout_time_hr" min="0" max="23" value="<?php echo getHrs($rule->el_punchout_time);?>">
                                <input id="el_punchout_time_mn<?php echo $rule->id;?>" type="number" placeholder="Min" name="el_punchout_time_mn" min="0" max="59" value="<?php echo getMin($rule->el_punchout_time);?>">Hr
                                <script>
                                $(function() {
                                  $('#el_punchout_time_hr<?php echo $rule->id;?>').change(function() {
                                    $.ajax({
                                      url:'<?php echo base_url('User/update_attendance_rule_by_id')?>',
                                      data:{checked:$(this).val(),rule_id:<?php echo $rule->id;?>,type:'el_punchout_time',checkedMin:$("#el_punchout_time_mn<?php echo $rule->id;?>").val()},
                                      type:'post'
                                    });
                                  })
                                });
                                $(function() {
                                  $('#el_punchout_time_mn<?php echo $rule->id;?>').change(function() {
                                    $.ajax({
                                      url:'<?php echo base_url('User/update_attendance_rule_by_id')?>',
                                      data:{checked:$("#el_punchout_time_hr<?php echo $rule->id;?>").val(),rule_id:<?php echo $rule->id;?>,type:'el_punchout_time',checkedMin:$(this).val()},
                                      type:'post'
                                    });
                                  })
                                });
                              </script>
                              </div>
                            </div> 
                            
                           
                            <div class="col-2">
                              <input id="el_punchout_on<?php echo $rule->id;?>" type="checkbox" data-toggle="toggle" data-onstyle="success" data-size="sm" <?php if($rule->el_punchout_on>0){echo "checked";} ?>>
                              <script>
                              $(function() {
                                $('#el_punchout_on<?php echo $rule->id;?>').change(function() {
                                  $.ajax({
                                    url:'<?php echo base_url('User/update_attendance_rule_by_id')?>',
                                    data:{checked:$(this).prop('checked'),rule_id:<?php echo $rule->id;?>,type:'el_punchout_on'},
                                    type:'post'
                                  });
                                })
                              })
                            </script>
                            </div>
                          </div>             
                                
                          
                         <div class="row">
                            <div class="col-1">
                              <p class="text-sm-left">14)</p>
                            </div>
                            <div class="col">
                              <p class="text-sm-left">Sort Leave Fine after</p>
                            </div>
                          
                            <div class="col-sm-2">
                           
                              <div class="input-group">
                                <input id="sl_fine<?php echo $rule->id;?>" type="number" placeholder="Amount" name="sl_fine" min="0"  value="<?php echo ($rule->sl_fine);?>">
                               
                                <script>
                                $(function() {
                                  $('#sl_fine<?php echo $rule->id;?>').change(function() {
                                    $.ajax({
                                      url:'<?php echo base_url('User/update_attendance_rule_by_id')?>',
                                      data:{checked:$(this).val(),rule_id:<?php echo $rule->id;?>,type:'sl_fine'},
                                      type:'post'
                                    });
                                  })
                                });
                                
                              </script>
                              </div>
                              Rs.
                            </div> 
               
                           <div class="col-1">
</div>
                            
                            
                              <div class="col-1">
                              <div class="input-group">
                                <input id="sl_days<?php echo $rule->id;?>" type="number" placeholder="Days" name="sl_days" min="0" max="20" value="<?php echo ($rule->sl_days);?>"> Days
                               
                                <script>
                                $(function() {
                                  $('#sl_days<?php echo $rule->id;?>').change(function() {
                                    $.ajax({
                                      url:'<?php echo base_url('User/update_attendance_rule_by_id')?>',
                                      data:{checked:$(this).val(),rule_id:<?php echo $rule->id;?>,type:'sl_days'},
                                      type:'post'
                                    });
                                  })
                                });
                                
                              </script>
                              </div>
                            </div> 
                            
                            
                            
                           
                            
                            <div class="col-1">
                              <p class="text-sm-left"></p>
                            </div>
                            <div class="col-2">
                              <input id="sl_on<?php echo $rule->id;?>" type="checkbox" data-toggle="toggle" data-onstyle="success" data-size="sm" <?php if($rule->sl_on>0){echo "checked";} ?>>
                              <script>
                              $(function() {
                                $('#sl_on<?php echo $rule->id;?>').change(function() {
                                  $.ajax({
                                    url:'<?php echo base_url('User/update_attendance_rule_by_id')?>',
                                    data:{checked:$(this).prop('checked'),rule_id:<?php echo $rule->id;?>,type:'sl_on'},
                                    type:'post'
                                  });
                                })
                              })
                            </script>
                            </div>
                          </div>   
                          
                          
                          
                          
                          
                          
                          
                          
                          
                           <div class="row">
                            <div class="col-1">
                              <p class="text-sm-left">15)</p>
                            </div>
                            <div class="col-4">
                              <p class="text-sm-left">Deduct Half day if sort leave more than</p>
                            </div>
                           
                                                   
                                                   
                                 <div class="col-3">
                              <p class="text-sm-left"></p>
                            </div>
                               <div class="col-2">
                              <div class="input-group">
                                <input id="hf_sl_days<?php echo $rule->id;?>" type="number" placeholder="Days" name="hf_sl_days" min="0" max="20" value="<?php echo ($rule->hf_sl_days);?>"> 
                               
                                <script>
                                $(function() {
                                  $('#hf_sl_days<?php echo $rule->id;?>').change(function() {
                                    $.ajax({
                                      url:'<?php echo base_url('User/update_attendance_rule_by_id')?>',
                                      data:{checked:$(this).val(),rule_id:<?php echo $rule->id;?>,type:'hf_sl_days'},
                                      type:'post'
                                    });
                                  })
                                });
                                
                              </script>
                              </div>
                              <span>Days</span>
                            </div> 
                           
                            
                           
                            <div class="col-2">
                              <input id="hf_sl_on<?php echo $rule->id;?>" type="checkbox" data-toggle="toggle" data-onstyle="success" data-size="sm" <?php if($rule->hf_sl_on>0){echo "checked";} ?>>
                               
                              <script>
                              $(function() {
                                $('#hf_sl_on<?php echo $rule->id;?>').change(function() {
                                  $.ajax({
                                    url:'<?php echo base_url('User/update_attendance_rule_by_id')?>',
                                    data:{checked:$(this).prop('checked'),rule_id:<?php echo $rule->id;?>,type:'hf_sl_on'},
                                    type:'post'
                                  });
                                })
                              })
                            </script>
                            </div>
                           
                          </div>    
                          
                          
                          
                          
                         <div class="row">
                            <div class="col-1">
                              <p class="text-sm-left">16)</p>
                            </div>
                            <div class="col">
                              <p class="text-sm-left">Extra Absent fine After </p>
                            </div>
                            <div class="col-2">
                              <p class="text-sm-left"></p>
                            </div>
                            <div class="col-sm-2">
                              <div class="input-group">
                                <input id="ex_absent_fine<?php echo $rule->id;?>" type="number" placeholder="Days" name="ex_absent_fine" min="0"  value="<?php echo ($rule->ex_absent_fine);?>"> 
                               
                                <script>
                                $(function() {
                                  $('#ex_absent_fine<?php echo $rule->id;?>').change(function() {
                                    $.ajax({
                                      url:'<?php echo base_url('User/update_attendance_rule_by_id')?>',
                                      data:{checked:$(this).val(),rule_id:<?php echo $rule->id;?>,type:'ex_absent_fine'},
                                      type:'post'
                                    });
                                  })
                                });
                                
                              </script>
                              </div>
                              <span> Rs.</span>
                            </div> 
                            
                            
                            <div class="col-1">
                            
                            </div>
                            
                            
                            
                              <div class="col-1">
                              <div class="input-group">
                                <input id="ex_absent_days<?php echo $rule->id;?>" type="number" placeholder="Days" name="ex_absent_days" min="0" max="20" value="<?php echo ($rule->ex_absent_days);?>">                                
                                <script>
                                $(function() {
                                  $('#ex_absent_days<?php echo $rule->id;?>').change(function() {
                                    $.ajax({
                                      url:'<?php echo base_url('User/update_attendance_rule_by_id')?>',
                                      data:{checked:$(this).val(),rule_id:<?php echo $rule->id;?>,type:'ex_absent_days'},
                                      type:'post'
                                    });
                                  })
                                });
                                
                              </script>
                              </div>
                              <span> Days</span>
                            </div> 
                            
                            
                           
                           
                            
                            <div class="col-1">
                              <p class="text-sm-left"></p>
                            </div>
                            <div class="col-2">
                              <input id="ex_absent_on<?php echo $rule->id;?>" type="checkbox" data-toggle="toggle" data-onstyle="success" data-size="sm" <?php if($rule->ex_absent_on>0){echo "checked";} ?>>
                              <script>
                              $(function() {
                                $('#ex_absent_on<?php echo $rule->id;?>').change(function() {
                                  $.ajax({
                                    url:'<?php echo base_url('User/update_attendance_rule_by_id')?>',
                                    data:{checked:$(this).prop('checked'),rule_id:<?php echo $rule->id;?>,type:'ex_absent_on'},
                                    type:'post'
                                  });
                                })
                              })
                            </script>
                            </div>
                          </div>    
                         
                        <div class="row">
                            <div class="col-1">
                              <p class="text-sm-left">17)</p>
                            </div>
                            <div class="col">
                              <p class="text-sm-left">Fine if absent without approval leave</p>
                            </div>
                           
                                                   
                                
                               <div class="col-sm-1">
                              <div class="input-group">
                                <input id="ab_leave_fine<?php echo $rule->id;?>" type="number" placeholder="Amount" name="ab_leave_fine" min="0" value="<?php echo ($rule->ab_leave_fine);?>">
                               
                                <script>
                                $(function() {
                                  $('#ab_leave_fine<?php echo $rule->id;?>').change(function() {
                                    $.ajax({
                                      url:'<?php echo base_url('User/update_attendance_rule_by_id')?>',
                                      data:{checked:$(this).val(),rule_id:<?php echo $rule->id;?>,type:'ab_leave_fine'},
                                      type:'post'
                                    });
                                  })
                                });
                                
                              </script>
                              </div>
                              <span>Rs. </span>
                            </div> 
                           
                            
                            <div class="col-4">
                              <p class="text-sm-left"></p>
                            </div>
                            <div class="col-2">
                              <input id="ab_leave_fine_on<?php echo $rule->id;?>" type="checkbox" data-toggle="toggle" data-onstyle="success" data-size="sm" <?php if($rule->ab_leave_fine_on>0){echo "checked";} ?>>
                              <script>
                              $(function() {
                                $('#ab_leave_fine_on<?php echo $rule->id;?>').change(function() {
                                  $.ajax({
                                    url:'<?php echo base_url('User/update_attendance_rule_by_id')?>',
                                    data:{checked:$(this).prop('checked'),rule_id:<?php echo $rule->id;?>,type:'ab_leave_fine_on'},
                                    type:'post'
                                  });
                                })
                              })
                            </script>
                            </div>
                          </div>    
                         
                         
                        
                        
                        
                         
                         
                         
                          <div class="row">
                            <div class="col-1">
                              <p class="text-sm-left">18)</p>
                            </div>
                            <div class="col">
                              <p class="text-sm-left">Incentive if present on Holiday/ weekly off</p>
                            </div>
                           
                                                   
                                                   
                                
                               <div class="col-sm-1">
                              <div class="input-group">
                                <input id="incentive_hl<?php echo $rule->id;?>" type="number" placeholder="Amount" name="incentive_hl" min="0" value="<?php echo ($rule->incentive_hl);?>"> 
                               
                                <script>
                                $(function() {
                                  $('#incentive_hl<?php echo $rule->id;?>').change(function() {
                                    $.ajax({
                                      url:'<?php echo base_url('User/update_attendance_rule_by_id')?>',
                                      data:{checked:$(this).val(),rule_id:<?php echo $rule->id;?>,type:'incentive_hl'},
                                      type:'post'
                                    });
                                  })
                                });
                                
                              </script>
                              </div>
                              <span> Rs.</span>
                            </div> 
                           
                            
                            <div class="col-4">
                              <p class="text-sm-left"></p>
                            </div>
                            <div class="col-2">
                              <input id="incentive_hl_on<?php echo $rule->id;?>" type="checkbox" data-toggle="toggle" data-onstyle="success" data-size="sm" <?php if($rule->incentive_hl_on>0){echo "checked";} ?>>
                              <script>
                              $(function() {
                                $('#incentive_hl_on<?php echo $rule->id;?>').change(function() {
                                  $.ajax({
                                    url:'<?php echo base_url('User/update_attendance_rule_by_id')?>',
                                    data:{checked:$(this).prop('checked'),rule_id:<?php echo $rule->id;?>,type:'incentive_hl_on'},
                                    type:'post'
                                  });
                                })
                              })
                            </script>
                            </div>
                          </div>    
                         
                         
                         
                         
                          
                          
                                    
                           <div class="row">
                            <div class="col-1">
                              <p class="text-sm-left">19)</p>
                            </div>
                            <div class="col">
                              <p class="text-sm-left">Overtime applied / Hours </p>
                            </div>
                           
                                    
                             <div class="col-sm-1">
                              <div class="input-group">
                               <input id="ot_amount<?php echo $rule->id;?>" type="number" placeholder="Amount" name="ot_amount" min="0" value="<?php echo ($rule->ot_amount);?>"> 
                               
                                <script>
                                $(function() {
                                  $('#ot_amount<?php echo $rule->id;?>').change(function() {
                                    $.ajax({
                                      url:'<?php echo base_url('User/update_attendance_rule_by_id')?>',
                                      data:{checked:$(this).val(),rule_id:<?php echo $rule->id;?>,type:'ot_amount'},
                                      type:'post'
                                    });
                                  })
                                });
                                
                              </script>
                              </div>
                              <span> Rs.</span>
                            </div> 
                           
                            
                            <div class="col-4">
                              <p class="text-sm-left"></p>
                            </div>
                            <div class="col-2">
                              <input id="ot_on<?php echo $rule->id;?>" type="checkbox" data-toggle="toggle" data-onstyle="success" data-size="sm" <?php if($rule->ot_on>0){echo "checked";} ?>>
                              <script>
                              $(function() {
                                $('#ot_on<?php echo $rule->id;?>').change(function() {
                                  $.ajax({
                                    url:'<?php echo base_url('User/update_attendance_rule_by_id')?>',
                                    data:{checked:$(this).prop('checked'),rule_id:<?php echo $rule->id;?>,type:'ot_on'},
                                    type:'post'
                                  });
                                })
                              })
                            </script>
                            </div>
                          </div>        
                           
                          
                         
                          
                          
                          
                        </div>
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        <!-- Modal -->
                        <div class="modal fade" id="attendanceRuleModal<?php echo $rule->id;?>" tabindex="-1" role="dialog" aria-labelledby="attendanceRule<?php echo $rule->id;?>" aria-hidden="true">
                          <div class="modal-dialog modal-sm" role="document">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel<?php echo $rule->name;?>">Edit Attendance Rule</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <form action="<?php echo base_url('User/update_attendance_rule')?>" method="POST">
                                <div class="modal-body">
                                  <div class="input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Rule Name" name="rule_name" value="<?php echo $rule->name;?>" required>
                                    <input type="text" value="<?php echo $rule->id; ?>" name="rule_id" hidden>
                                  </div>
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                  <button type="submit" class="btn btn-success">Save</button>
                                </div>
                              </form>
                            </div>
                          </div>
                        </div>
                        <!-- Modal -->
                      </div>
                    </div>
                  <?php }?>
                </div>
              </div>
              <!-- /.row -->
            </div>
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
