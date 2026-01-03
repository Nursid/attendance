
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
              <li class="breadcrumb-item active">Leave Report</li>
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
        if($this->session->userdata()['type']=='B' || $role[0]->add_leave=="1" || $role[0]->type=="1"){?>
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            
                
                <span style="color: red"><?php echo $this->session->flashdata('msg');?></span>
             
            <!-- /.card -->
          </div>
        </div>

        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Leave List</h3>
              </div>
              <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>S. No</th>
                    <th>Employee Name</th>
                    <th>Leave from</th>
                    <th>leave to</th>
                    <th>Total days</th>
                    <th>Type</th>
                    <th>Reason</th>
                    <th>Request date</th>
                    <th width="20%">Status</th>
                  </tr>
                  </thead>
                  <tbody>
                      <?php
                      $res=$this->web->getLeaveByBusinessId($bid);
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
                      foreach($res as $res){
                        if($res->status==2){
                      ?>
                      <tr>
                        <td><?php echo $count++; ?></td>
                        <td><?php 
						            $uname = $this->web->getNameByUserId($res->uid);
                        echo $uname[0]->name; ?>
            						</td>
                       <td><?php echo date("d/m/Y",(Int)$res->from_date) ;
                        ?></td>
                         <td><?php echo date("d/m/Y",(Int)$res->to_date);
                        ?></td>
                       <!-- <td><?php echo  date("d/m/Y",$res->to_date); ?></td>-->
                        <td><?php
                        $froml=$res->from_date;
                        $tol=$res->to_date;
                        $days=($tol-$froml);
                       // echo $ldays= intval($days/60/60/24+1)."Days";
					     echo $res->half_day."Days";
                        ?>
                        </td>
                        <td><?php echo $res->type; ?></td>
                        <td><?php echo $res->reason; ?></td>
                        <td>
						            <?php echo  date("d/m/Y",$res->date_time);?></td>
                        <td id="statusb<?php echo $res->id; ?>"> 
                        <?php 
                        $id=$res->id;
						 $uid=$res->uid; 
						 $fromdate=date("d-m-Y" , $res->from_date);
                        if ($res->status==2){ echo "Pending";
						         
						 
						 ?>
						 
                         &nbsp;&nbsp; <br><br>
                        <button class="btn btn-success"  onclick="aprove('<?php echo $id ;?>' ,' <?php echo $uid ;?>',' <?php echo $fromdate ;?>')">Aprove</button>
                       &nbsp;&nbsp;  <button class="btn btn-danger" onclick="reject('<?php echo $id ;?>' ,' <?php echo $uid ;?>',' <?php echo $fromdate ;?>')">Reject</button>
                      <?php   } elseif($res->status==1){ echo "Aproved";  ?>
                     &nbsp;&nbsp;
                     <button class="btn btn-danger" onclick="reject('<?php echo $res->id; ?>')">Reject </button>
					            <?php	} elseif($res->status==3){ echo "Rejected"; ?>
						          &nbsp;&nbsp;
                     <button class="btn btn-success" onclick="aprove('<?php echo $res->id; ?>')">Aprove</button>
                    <?php     } ?>
                        </td>
                    <!--    
                        <td id="stat<?php echo $pre->id; ?>">
                          <?php
                              if ($check['status'] == "1") {
                          ?>    
                            <button class="btn btn-success" onclick="inactive('<?php echo $pre->id; ?>')">Active</button>
                          <?php
                              }else{
                          ?>
                            <button class="btn btn-danger" onclick="active('<?php echo $pre->id; ?>')">Inactive</button>
                          <?php
                            }
                          ?>
                        </td>-->
                      </tr>
                      <?php 
                      }}
                      ?>
                  </tfoot>
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
<script>
  $(function () {
   var table = $('#example1').DataTable({
     "responsive": true,
      "autoWidth": false,
    });
   
  });
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
  function changeLeaveFrom(e,id){
    $.ajax({
    type: "POST",
    url: "User/changeLeaveFmDate",
    data: {id : id,from_date: e.target.value},
    success: function(){
      
    }
    });
  }
  function changeLeaveTo(e,id){
    $.ajax({
    type: "POST",
    url: "User/changeLeaveToDate",
    data: {id : id,to_date: e.target.value},
    success: function(){
      
    }
    });
  }
  $(function () {
    var table = $('#example3').DataTable({
     "responsive": true,
      "autoWidth": false,
    });
  });
</script>




<script type="text/javascript">
 
  function aprove(id,uid,fromdate){
    $.ajax({
      type: "POST",
      url: "User/aproveUser",
      data: {id,uid,fromdate},
    success: function(result){
		 alert('Leave Aproved ');
		// $("#status").html("Aproved");
		//  $("#statusb").html("Aproved");
     $('#statusb'+id).html("Aproved");
    }
    })
  }

  function reject(id,uid,fromdate){
    $.ajax({
      type: "POST",
      url: "User/rejectUser",
      data: {id,uid,fromdate},
    success: function(result){
		alert('Leave Rejected ');
		 //$("#status").html("Aproved");
		 // $("#statusb").html("Rejected");
		  $('#statusb'+id).html("Rejected");
     // $('#status'+id1).html('<button class="btn btn-danger" onclick="aprove('+ id1 + ')">Rejected</button>');
    }
    })
  }
</script>


</body>
</html>
