
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
 <?php $this->load->view('employee/staff_menu')?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
         
          <div class="col-sm-12">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Leave</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Request Sort Leave</h3><br>
                <span style="color: red"><?php echo $this->session->flashdata('msg');?></span>
              </div>
              <?php 
			 
				$empId=$this->web->session->userdata('login_id');
				// $loginId=$this->session->userdata('empCompany');
				$userCmp = $this->app->getUserCompany($empId );
			  $loginId = $userCmp['business_id'];	
				
				 $Sleaves = $this->web->getEmpSLeaves($empId);

					     
				
                
                
			   ?>
             <form action="<?php echo base_url('User/add_staffSleave')?>" method="post">
              <div class="card-body">
                <div class="row">
                  <div class="from-group col-md-2">
                    <label for="depart">Date</label>
                  <!--  <input type="date" class="form-control" name="from_date" placeholder="Select Date" id="from_date" required>-->
                     <input type="date"  name="from_date" placeholder="Select Date" id="start_date" value="<?php echo $start_date; ?>" class="form-control" required>
                  </div>
     
                    <div class="from-group col-md-2"> 
                  
                  <label for="remark">Type</label>
                    <select name="type" class="form-control"  id="type">
                                    <option value="0">First Half </option>
                                    <option value="1">Second Half </option>
                                   
                                </select>
                                </div>
                                
               <div class="from-group col-md-2"> 
                  
                  <label for="remark">Time</label>
                    <select name="time" class="form-control"  id="time">
                                    <option value="0">Half an Hour </option>
                                    <option value="1">One Hour </option>
                                    <option value="2">Two Hours </option>
                                    
                                   
                                </select>
                                </div>


                        
                  <div class="from-group col-md-5">
                    <label for="remark">Reason</label>
                    <input type="textarea" class="form-control" name="reason" placeholder="Enter reason" id="reason" required>
                    <input type="hidden" name="uid" value="<?php echo $empId;?>">
                     <input type="hidden" name="bid" value="<?php echo $loginId; ?>">
                      <input type="hidden" name="status" value="2">
                    
                  </div>


                  <div class="from-group col-md-1">
                  <button class=" btn btn-success mt-4 mx-auto">Add</button>
                  </div>
                </div>
              </div>
              </form> 
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>

        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">History</h3>
               
              </div>
              <div class="card-body">
               <?php 
                           
                                   
                                  ?>
                                 
                                   <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                      <tr>
                                        <th>SNo.</th>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Time</th>
                                        <th>Reason</th>
                                        <th>Status</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <?php
                                      $leavecount=1;
                                      foreach($Sleaves as $leave){
                                        
                                        ?>
                                        <tr>
                                          <td><?= $leavecount++;?></td>
                                          <td><?= date('d-m-Y',$leave->from_date);?></td>
                                          <td> <?php if($leave->type==0){
                                             echo "First Half"; 
                                              
                                          } else {
                                           echo "Second Half";     
                                          }
                                          
                                         ?> 
                                          
                                          </td>
                                         
                                          <td> <?php if($leave->hour==0){
                                             echo "Half an Hour"; 
                                              
                                          }elseif($leave->hour==1)
                                          {
                                           echo "One Hour";    
                                          } else {
                                           echo "Two Hours";     
                                          }
                                          
                                         ?> 
                                          
                                          </td>
                                          
                                          <td><?= $leave->reason;?></td>
                                          <td> <?php if($leave->status==1){
                                             echo "Verified"; 
                                              
                                          }elseif($leave->status==3)
                                          {
                                           echo "Cancelled";    
                                          } else {
                                           echo "Pending";     
                                          }
                                          
                                         ?> 
                                          
                                          </td>
                                        </tr>
                                      <?php }?>
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


<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">Edit Device</h4>
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
	
  var add_bio_data = "add_device";
  $.ajax({
      type: "POST",
      url: "User/getajaxRequest",
      data: {data,add_bio_data},
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
<script>
  function delete_device(id){
    $.ajax({
      type: "POST",
      url: "User/delete_device",
      data: {id},
     success: function(){
    $('#delete'+id).text("deleted");
    }
	
    })
  }
</script>
</body>
</html>
