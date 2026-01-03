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

      <!-- Content Header (Page header) -->
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">

            <div class="col-sm-12">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Assign_working</li>
              </ol>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>

      <?php
      if ($this->session->userdata()['type'] == 'B' || $this->session->userdata()['type'] == 'P') {

        if ($this->session->userdata()['type'] == 'P') {
          //$busi = $this->web->getBusinessbyUser($this->web->session->userdata('login_id'));
          //$bid = $busi[0]->business_id;
          $bid = $this->session->userdata('empCompany');
          $role=$this->web->getRollbyid($this->web->session->userdata('login_id'),$bid);
        } else {
          $bid = $this->web->session->userdata('login_id');
        }
      ?>
        <!-- Main content -->
        <section class="content">
        <?php
        if($this->session->userdata()['type']=='B' || $role[0]->pending_att=="1" || $role[0]->type=="1"){?>
          <div class="container-fluid">
            <div class="row">
              <!-- left column -->
              <div class="col-md-12">
                <span style="color: red"><?php echo $this->session->flashdata('msgr'); ?></span>
                <!-- /.card -->
              </div>
            </div>

            <div class="row">
              <!-- left column -->
              <div class="col-md-12">
                <div class="card card-primary">
                  <div class="card-header">
                    <h3 class="card-title">Assign Employee Working</h3>
                  </div>
                  <div class="card-body">
                    <h5> Select Employee</h5>
                    <div class="row">
                      <div class="col-lg-12 float-left">
                        <form action="<?php echo base_url('User/assign_att') ?>" method="POST">
                          <div class="row">
                            <div class="col-sm-2">
                              <select class="form-control" name="uid" id="uid" required>
                                <option value="">Select Employee </option>
                              <?php 
                              $id=0;
                            $res = $this->web->getActiveEmployeesList($bid);
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
                            foreach ($res as $res) :
                              $uname = $this->web->getNameByUserId($res->user_id); 
                              $start_date=date("Y-m-d");
                              $end_date=date("Y-m-d");
                              ?>
                                <option value="<?php echo $uname[0]->id  ?>" <?php if($res->user_id=$id){ echo "selected";}?>><?php echo $uname[0]->name; ?></option>
                              <?php endforeach; ?>
                              </select>
                            </div>
                            <div class="col-sm-2">
                               <select name="type" class="form-control"  id="type" required>
                                <option value="0">Work from Home </option>
                                <option value="1">OUT Duty </option>
                                <option value="2">Paid Leave </option>
                                <option value="3">Unpaid Leave</option>
                                </select>

                            </div>
                            <div class="col-sm-2">
                              <input type="date" name="start_date" id="start_date"  value="<?php echo $start_date; ?>" class="form-control" max="<?php echo $end_date; ?>" onchange="startChange(event);" required>
                            </div>
                            <div class="col-sm-2">
                              <input type="date" name="end_date" id="end_date"  value="<?php echo $end_date; ?>"class="form-control"  min="<?php echo $start_date;?>" onchange="endChange(event);" required>
                            </div>
                            
                            
                            <div class="col-sm-2">
                             <!-- <input type="date" name="date" id="date" value="" class="form-control">-->
                              <input type="hidden" name="bid"  value="<?php echo $bid ;?>" class="form-control">
                              
                            </div>
                            <div class="col-sm-2">
                              <button type="submit" class="btn btn-success btn-fill btn-block">Assign</button>
                            </div>
                            
                          </div>
                        </form>
                      </div>
                    </div>
                    <br><br>
                    <?php
                   
                      $stdate = strtotime($start_date);
                      $endate = strtotime($end_date);
                    ?>
                      <h5>Assigned Employee Attendance</h5>
                      <table id="example1" class="table table-bordered table-striped">
                        <thead>
                          <tr>
                            <th>S/no</th>
                            <th>Date From</th>
                            <th>Date To</th>
                            <th>No Days</th>
                            <th>Employee</th>
                            <th>Type</th>
                             <th>Aprove</th>
                             <th> Delete</th>
                           
                          </tr>
                        </thead>
                        <tbody>
                         <?php
					  $res = $this->web->getassignworking($bid);
                     
                     if($this->session->userdata()['type']=='P'){
              if($role[0]->type!=1){
                $departments = explode(",",$role[0]->department);
                $sections = explode(",",$role[0]->section);
                $team = explode(",",$role[0]->team);
                
                if(!empty($departments[0]) || !empty($sections[0]) || !empty($team[0])){
                  foreach ($res as $key => $dataVal) {
                    $uname = $this->web->getNameByUserId($dataVal->uid);
                    $roleDp = array_search($uname[0]->department,$departments);
                    $roleSection = array_search($uname[0]->section,$sections);
                    $roleTeam = array_search($dataVal->uid,$team);
                   
                    if(!is_bool($roleTeam) || !is_bool($roleSection) || !is_bool($roleDp)){
            
                    }else{
                      unset($res[$key]);
                    }
                  }
                }
              }
            }
                     
                      
                     
                      $count=1;
                      foreach($res as $work){
						  $uname = $this->web->getNameByUserId($work->uid); ?>
                      
                         <tr>
                         
                         <td><?php echo $count++; ?></td>
                          <td><?php echo date("d-M ", $work->date); ?></td>
                          <td><?php echo date("d-M ", $work->end_date); ?></td>
                            <td><?php echo date("l", $work->date); ?></td>
                            <td><?php echo $uname[0]->name; ?></td>
                           <td><?php 
                           if($work->type==0) {
                               echo "Work From Home";
                           }elseif($work->type==1) {
                               echo "OUT Duty";
                           }elseif($work->type==2){
                                echo "Paid Leave";
                           }else{ 
                               echo "Unpaid Leave";
                               
                           }
                           ?>
                           </td>
                           <?php
                       
						  ?>
						 
                          <td  width="20%" id="verify<?php echo $work->id; ?>">
                       <?php 
					   $id=$work->id;
						 $uid=$work->uid; 
						 $fromdate=date("d-m-Y" , $work->date);
						 
					     if($work->status==1){
						  echo "Approved";
						   }else{ ?>
                     
      <button class="btn btn-danger" onclick="verify('<?php echo $id ;?>' ,' <?php echo $uid ;?>',' <?php echo $fromdate ;?>')" >Verify</button>  &nbsp;&nbsp;
                    <button class="btn btn-success" onclick="cancelatt('<?php echo $id ;?>' ,' <?php echo $uid ;?>',' <?php echo $fromdate ;?>')" >Cancel</button>
                  <?php  
				  
						   }
                    ?>
                  </td> 
                          
                          <td id="delete<?php echo $work->id; ?>">
                         <button class="btn btn-danger" onclick="delete_work('<?php echo $id ;?>' ,' <?php echo $uid ;?>',' <?php echo $fromdate ;?>')" >
                          <i class="fa fa-times" style="color:white"></i>
                          </button>
                        </td>
                          
                       </tr>  
                          <?php 
                      }
                      ?> 
                          
                          </tbody>
                          
                      </table>
                   
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
  function delete_work(id,uid,fromdate){
    $.ajax({
      type: "POST",
      url: "delete_working",
      data: {id,uid,fromdate},
     success: function(){
    $('#delete'+id).text("deleted");
    }
	
    })
  }
</script>

<script>
function verify(id,uid,fromdate){
    $.ajax({
      type: "POST",
      url: "verifyworking",
      data: {id,uid,fromdate},
     success: function(){
    $('#verify'+id).text("verified");
    }
    })
  }
  </script>
<script>
  function cancelatt(id,uid,fromdate){
    $.ajax({
      type: "POST",
      url: "cancelworking",
      data: {id,uid,fromdate},
     success: function(){
    $('#verify'+id).text("Canceled");
    }
	
    })
  }
</script>





</body>

</html>