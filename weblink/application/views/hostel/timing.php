
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
 <?php $this->load->view('hostel/hostel_menu')?>
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
              <li class="breadcrumb-item active">Timing Slot</li>
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
                <h3 class="card-title">Timing Slot List</h3>
                <span style="color: red"><?php echo $this->session->flashdata('msg');?></span>
              </div>
              <div class="row p-2">
                <div class="col-sm-4">
                  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addShiftModal">
                    Add timing
                  </button>
                </div>
              </div>
              <div class="card-body">
               <?php 
			 // $bid=$this->web->session->userdata('login_id');
			  
			  ?>
              <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>S.No</th>
                    <th>Slot Name</th>
                    <th>Timing Start</th>
                    <th>Timing End</th>
                    <th>Off</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                      <?php
                      $res=$this->web->getTimingByBusinessId($bid);
                      $count=1;
                      foreach($res as $res){
						
						  
                      ?>
                      <tr>
                        <td><?php echo $count++; ?></td>
                        <td><?php echo $res->name; ?></td>
                       <td><?php echo date("H:i:A",$res->start_time); ?></td>
                       <td><?php echo date("H:i:A",$res->end_time); ?></td>
                       <td><?php echo $res->off; ?></td>
                     
                        <td>
                          
                          <form action="<?php echo base_url('User/deleteTiming')?>" method="POST">
                            <button type="button" class="btn btn-info btn-circle btn-x" data-toggle="modal" data-target="#editShiftModal<?= $res->id;?>">
                              <i class="fa fa-edit" style="color:white"></i>
                            </button>
                            <input type="text" value="<?= $res->id?>" name="shift_id" hidden/>
                            <button type="submit" class="btn btn-danger btn-circle btn-x">
                              <i class="fa fa-trash" style="color:white"></i>
                            </button>
                          </form>
                        </td>
                      </tr>
                      <div class="modal fade" id="editShiftModal<?= $res->id;?>" tabindex="-1" role="dialog" aria-labelledby="editShiftModalLabel<?= $res->id;?>" aria-hidden="true">
                      <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="editShiftModalLabel<?= $res->id;?>">Edit Timing</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <form action="<?php echo base_url('User/editTiming')?>" method="POST">
                            <div class="modal-body">
                            <input type="text" name="shift_id" id="shift_id" value="<?= $res->id;?>" hidden>
                              <div class="row">
                                <div class="col-sm-3">
                                  <label for="shift_name">Name</label>
                                  <input type="text" name="shift_name" id="shift_name" class="form-control" placeholder="Enter Shift Name" value="<?= $res->name;?>" required>
                                </div>
                             
                                <div class="col-sm-2">
                                  <label for="shift_start">Start Time</label>
                                  <input type="time" name="shift_start" id="shift_name" class="form-control" value="<?= date("H:i",($res->start_time));?>"/>
                                </div>
                                <div class="col-sm-2">
                                  <label for="shift_end"> End Time</label>
                                  <input type="time" name="shift_end" id="shift_end" class="form-control" value="<?= date("H:i",($res->end_time));?>">
                                </div>
                                
                                <div class="col-sm-2">
              <label for="off"> Off Day</label>
             <select name="off" class="form-control" id="off">
             <option value="<?php echo $res->off; ?>"> <?php echo $res->off; ?></option>
                                    <option value="NoOff">NoOff</option>
                                    <option value="Sun">Sun</option>
                                    <option value="Mon">Mon</option>
                                    <option value="Tue">Tue</option>
                                    <option value="Wed">Wed</option>
                                    <option value="Thur">Thur</option>
                                    <option value="Fri">Fri</option>
                                    <option value="Sat">Sat</option>
                                    
                                </select>
            </div>
                              </div>
                              
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                              <button type="submit" class="btn btn-primary">Update Shift</button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                      <?php 
                      }
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

<!-- Modal -->
<div class="modal fade" id="addShiftModal" tabindex="-1" role="dialog" aria-labelledby="addShiftModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addShiftModalLabel">Timing Detail</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="<?php echo base_url('User/addcanteentiming')?>" method="POST">
        <div class="modal-body">
          <div class="row">
            <div class="col-sm-3">
              <label for="shift_name">Name</label>
              <input type="text" name="name" id="shift_name" class="form-control" placeholder="Enter Name" required>
            </div>
         
            <div class="col-sm-2">
              <label for="shift_start">Start Time</label>
              <input type="time" name="shift_start"  class="form-control" >
            </div>
            <div class="col-sm-2">
              <label for="shift_end"> End Time</label>
              <input type="time" name="shift_end" id="shift_end" class="form-control">
            </div>
             <div class="col-sm-2">
              <label for="off"> Weekly Off Day</label>
             <select name="off" class="form-control" id="off">
                                    <option value="NoOff">NoOff</option>
                                    <option value="Sun">Sun</option>
                                    <option value="Mon">Mon</option>
                                    <option value="Tue">Tue</option>
                                    <option value="Wed">Wed</option>
                                    <option value="Thur">Thur</option>
                                    <option value="Fri">Fri</option>
                                    <option value="Sat">Sat</option>
                                    
                                </select>
            </div>
          </div>
         
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Add Timing</button>
        </div>
      </form>
    </div>
  </div>
</div>

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
