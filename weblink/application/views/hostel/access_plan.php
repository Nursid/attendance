
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
              <li class="breadcrumb-item active">Add Plan</li>
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
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Add Access Plan</h3><br>
                <span style="color: red"><?php echo $this->session->flashdata('msg');?></span>
              </div>
              <?php 
			  // $bid=$this->web->session->userdata('login_id');
			   ?>
              <form action="<?php echo base_url('User/add_access_plan')?>" method="post">
              <div class="card-body">
                <div class="row">
                  <div class="from-group col-md-3">
                    <label for="name">Plan Name</label>
                    <input type="text" class="form-control" name="name" placeholder="Enter a name" id="name" required>
                    <input type="hidden" name="bid" value="<?php echo $bid;?>">
                  </div>
                  
                  <div class="from-group col-md-3">
                    <label for="desc">Decription</label>
                    <input type="text" class="form-control" name="descp" placeholder="Enter Decription" id="Decription" >
                  </div>
                  
                  <div class="from-group col-md-2">
                    <label for="type">Valid Days</label>
                    <input type="number" class="form-control" name="days"  required>
                  </div>
                  <div class="from-group col-md-1">
                    <label for="type">Limit</label>
                    <input type="number" class="form-control" name="limit" >
                  </div>
                  <div class="from-group col-md-2">
                    <label for="type">Limit Type</label>
                    <select name="type" class="form-control">
                                  
                                    <option value="0">Logs Total</option>
                                    <option value="1">Logs per Shift</option>
                                      
                                </select>
                  </div>
                <div class="from-group col-md-12" align="right">
                  <button class=" btn btn-success mt-4 mx-auto">Add Now</button>
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
                <h3 class="card-title">Plan List</h3>
              </div>
              <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>S.No</th>
                     <th>Name</th>
                    <th>Decription</th>
                     <th>Valid Days</th>
                     <th>Acces Limit</th>
                    
                   <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                      <?php
					   
			    
			  
			 
                      $res=$this->web->getplan($bid);
                      $count=1;
                      foreach($res as $plan){
                      ?>
                      <tr>
                        <td><?php echo $count++; ?></td>
                        <td><?php  echo $plan->name; 
                         ?>
                       </td>
                        <td><?php  echo $plan->description; 
                         ?>
                       </td>
                       <td><?php  echo $plan->days; 
                         ?>
                       </td>
                        <td><?php echo $plan->access_limit; 
						$mode=$plan->type; 
                         
                        if($mode==0){
                           echo " &nbsp; Logs Total";
                        }else{
                           echo "&nbsp; Logs per Shift";
                        }
                        
                        ?>
                        
                        
                        </td>
                        
                        <td id="delete<?php echo $plan->id; ?>">
                      <!--  <button type="button" class="btn btn-info btn-circle btn-x" data-toggle="modal" data-target="#editModal<?php echo $res->id;?>"> <i class="fa fa-edit" style="color:white"></i>
                          </button>-->
                          <button type="button" class="btn btn-info btn-circle btn-x" data-toggle="modal" data-target="#editModal<?php echo $plan->id ;?>"><i class="fa fa-edit" style="color:white"></i></button>


                          <button class="btn btn-danger" onclick="delete_plan('<?php echo $plan->id; ?>')" >
                          <i class="fa fa-times" style="color:white"></i>
                          </button>
                        </td>
                      </tr>
                      <?php 
                      }
                      ?>
                  </tfoot>
                </table>
                
                 <?php
                        foreach($res as $plan){
							$membplan = $this->web->getplanbyid($plan->id);
							?>
                          <!-- Modal -->
                          <div class="modal fade" id="editModal<?php echo $plan->id;?>" tabindex="-1" role="dialog" aria-labelledby="editModal<?php echo $plan->id;?>" aria-hidden="true">
                            <div class="modal-dialog modal-md" role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title" id="historyModalLabel<?php echo $plan->id;?>">Edit Plan</h5>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                                <div class="modal-body">
                        <form action="<?php echo base_url('User/editplan')?>" method="post">
              <div class="card-body">
                <div class="row">
                
                  <div class="from-group col-md-5">
                    <label for="pfix"> Name</label>
                  <!--  <input type="date" class="form-control" name="to_date" id="to_date" placeholder="Select Date" required>-->
                    <input type="text" name="name" id="end_date"   value="<?php echo $membplan['0']->name; ?>"class="form-control">
                  </div>
                  <div class="from-group col-md-5">
                    <label for="pfix"> Description</label>
                  <!--  <input type="date" class="form-control" name="to_date" id="to_date" placeholder="Select Date" required>-->
                    <input type="text" name="descp" id="end_date"   value="<?php echo $membplan['0']->description; ?>"class="form-control"   >
                  </div>
                  
                  <div class="from-group col-md-3">
                    <label for="pfix">Days</label>
                  <!--  <input type="date" class="form-control" name="to_date" id="to_date" placeholder="Select Date" required>-->
                    <input type="no" name="days" id="end_date"   value="<?php echo $membplan['0']->days; ?>"class="form-control"  required>
                  </div>
                  
                  
               <div class="from-group col-md-3">
                    <label for="pfix"> Log Limit</label>
                  <!--  <input type="date" class="form-control" name="to_date" id="to_date" placeholder="Select Date" required>-->
                    <input type="number" name="limit"   value="<?php echo $membplan['0']->access_limit; ?>"class="form-control"  required>
                  </div>
                  <div class="from-group col-md-5">
                    <label for="pfix"> Limit Type </label>
                  <!--  <input type="date" class="form-control" name="to_date" id="to_date" placeholder="Select Date" required>-->
                    <select name="type" class="form-control">
                                  
                                    <option value="0">Logs Total</option>
                                    <option value="1">Logs per Shift</option>
                                      
                                </select>
                  </div>
                  
                  
                     <input type="hidden" name="id" value="<?php echo $membplan[0]->id; ?>">
                
                  <div class="from-group col-md-5">
                  <button class=" btn btn-success mt-4 mx-auto">Update Now</button>
                  </div>
                </div>
              </div>
              </form>            
                            
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                              </div>
                            </div>
                          </div>
                          <!-- Modal -->
                  <?php } ?>   
                
                
                
                
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->  <?php
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


<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">Edit Plan</h4>
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
  function delete_plan(id){
    $.ajax({
      type: "POST",
      url: "User/delete_plan",
      data: {id},
     success: function(){
    $('#delete'+id).text("deleted");
    }
	
    })
  }
</script>
</body>
</html>
