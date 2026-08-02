
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
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
 <?php $this->load->view('hostel/hostel_menu')?>
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
              <li class="breadcrumb-item active">User List</li>
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
      $id = $this->session->userdata('empCompany');
      $role=$this->web->getRollbyid($this->web->session->userdata('login_id'),$id);
  
    } else {
      $id=$this->web->session->userdata('login_id');
    }
    ?>
    <!-- Main content -->
    <section class="content">
      <?php
      if($this->session->userdata()['type']=='B' || $role[0]->employee_list=="1"){?>
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-2">
            <div class="card card-info">
              <div class="card-header">
                <span style="color: red"><?php echo $this->session->flashdata('msg');?></span>
              </div>
              
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
                <h3 class="card-title">User List
                </h3>
              </div>
              <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>S.No</th>
                    <th>Name</th>
                     <th>Room</th>
                     <th>Mobile No.</th>
                    <th>Valid From</th>
                    <th>Valid To</th>
                     <th>Access Limit</th>
                    <th>Status</th>
                    <th>Action</th>
                   <!--  <th>Action</th>-->
                  </tr>
                  </thead>
                  <tbody>
               <?php
			           
				   // $left=strtotime(date("d-m-Y",time()));
				  $cudate = date("Y-m-d");
				 // $cudate= '2022-04-15';
				$cdate=strtotime($cudate);
				
				$start_time=time();
                      $res=$this->web->getHostelStudentList($id);
					  $count=1;
            
                      foreach($res as $val){
						          $userid=$val->user_id;
                      ?>
                      <tr>
                       <td><?php echo $count++; ?></td>
                       
                        <td><?php $uname = $this->web->getNameByUserId($val->user_id);
                                echo $uname[0]->name; ?></td>
                                
                                 <td>
                          
                        
                          <?php
						  $hostel = $this->web->getHostelByUserId($userid,$id); 
						       $blid=$hostel[0]->block;
						       $block = $this->web->getBlock($blid,$id);
							   echo $block[0]->name."</br>";
                             
						  
						      echo $hostel[0]->floor."</br>" ; 
                              
						  
						      echo $hostel[0]->room_no."</br>"; 
                               
						     $rmid=$hostel[0]->room_type;
						       $rooms= $this->web->getRoomtype($rmid,$id);
							   echo $rooms[0]->name;
                               ?>
                          </td> 
                          
                          
                          
                        <td><?php $uname = $this->web->getNameByUserId($val->user_id);
                                echo $uname[0]->mobile; 
							     // echo time();
							   
							   ?>
                               
                               </td>
                                
                        <td><?php $memb =$this->web->getMembershipDetail($val->user_id,$id);
						        if(!empty($memb[0])){
					$fvalid=date("d-M-y",$memb[0]->from_date);
				}else{
				     $fvalid=date("d-M-y",$val->doj);
						}
						   echo $fvalid;
								 ?></td>
                               <td>
                             <?php  if(!empty($memb[0])){
					$lastvalid=date("d-M-y",$memb[0]->to_date);
				}else{
				     $lastvalid=date("d-M-y",$val->doj);
						}
						echo $lastvalid;
						?>
                               
                               
                               </td>
                                 
                                 

                            <td><?php 
                                echo $memb[0]->access_limit; ?></td>

                       
                         <td >
                          
                
                        <?php
                              if ($memb[0]->active == "1") {
                          ?>    
                            <button class="btn btn-success" onclick="inactive2('<?php echo $memb[0]->sid; ?>')">Active</button>
                          <?php
                              }else{
                          ?>
                            <button class="btn btn-danger" onclick="active2('<?php echo $memb[0]->sid; ?>')">Inactive</button>
                          <?php
                            }
                          ?>


                            </td>
                         
                        
                       <td> 
                       <button type="button" class="btn btn-danger btn-sm mt-4 mx-auto" data-toggle="modal" data-target="#addplanModal<?php echo $val->user_id;?>">Add New </button>
                      <button type="button" class="btn btn-info btn-sm mt-4 mx-auto" data-toggle="modal" data-target="#editModal<?php echo $val->user_id;?>">Edit</button>
                       
                       
                     <button type="button" class="btn btn-info btn-sm mt-4 mx-auto" data-toggle="modal" data-target="#historyModal<?php echo $val->user_id;?>">History</button> 
                        
                       <br>
                        
                         
                        </td> 
                     
                      </tr>
                      <?php 
                      }
                      ?>
                  </tfoot>
                </table>
               
               
           
                 
                 <?php
                        foreach($res as $user){?>
                          <!-- Modal -->
                          <div class="modal fade" id="historyModal<?php echo $user->user_id ;?>" tabindex="-1" role="dialog" aria-labelledby="historyModal<?php echo $user->user_id;?>" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title" id="historyModalLabel<?php echo $user->user_id;?>">History</h5>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                                <div class="modal-body">
                                 <table id="example1" class="table table-bordered table-responsive">
                                    <thead>
                                      <tr>
                                        <th>SNo.</th>
                                        <th>Plan Name</th>
                                        <th>Description</th>
                                        <th>Days</th>
                                        <th>Limit</th>
                                        <th>From Date</th>
                                        <th>Valid date</th>
                                        <th>Applied Date</th>
                                        <th>Delete</th>
                                      
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <?php
                                      $count=1;
									  $memdetail = $this->web->getMembershipDetail($user->user_id,$id);
                                      foreach($memdetail as $mem){
                                        $plan=$this->web->getplanbyid($mem->plan_id);
                                        ?>
                                        <tr>
                                          <td><?= $count++;?></td>
                                          <td><?= $plan[0]->name;?></td>
                                          <td><?= $plan[0]->description;?></td>
                                          <td><?= $mem->days;?></td>
                                          <td><?= $mem->access_limit;?></td>
                                          <td><?= date('d-M-y',$mem->from_date);?></td>
                                          <td><?= date('d-M-y',$mem->to_date);?></td>
                                          <td><?= date('d-M-y',$mem->date);?></td>
                                           <td id="delete<?php echo $mem->id; ?>">
                                         
                        <button class="btn btn-danger" onclick="delete_membership('<?php echo $mem->id; ?>')" >
                          <i class="fa fa-times" style="color:white"></i>
                          </button>
                        </td>
                                        </tr>
                                      <?php  }?>
                                    </tbody>
                                  </table>
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                              </div>
                            </div>
                          </div>
                          <!-- Modal -->
                  <?php } ?>  
                
            <?php
                        foreach($res as $user){
							$memb = $this->web->getMembershipDetail($user->user_id,$id);
							?>
                          <!-- Modal -->
                          <div class="modal fade" id="editModal<?php echo $user->user_id;?>" tabindex="-1" role="dialog" aria-labelledby="editModal<?php echo $user->user_id;?>" aria-hidden="true">
                            <div class="modal-dialog modal-md" role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title" id="historyModalLabel<?php echo $user->user_id;?>">Edit Validity</h5>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                                <div class="modal-body">
                                  
                                  
                                  <form action="<?php echo base_url('User/edit_plan_validity')?>" method="post">
              <div class="card-body">
                <div class="row">
                
                  <div class="from-group col-md-5">
                    <label for="pfix"> Valid From</label>
                  <!--  <input type="date" class="form-control" name="to_date" id="to_date" placeholder="Select Date" required>-->
                    <input type="text" name="from_date" id="end_date"   value="<?php echo date("d-M-Y",$memb[0]->from_date); ?>"class="form-control"   readonly>
                  </div>
                  <div class="from-group col-md-5">
                    <label for="pfix"> Valid Till</label>
                  <!--  <input type="date" class="form-control" name="to_date" id="to_date" placeholder="Select Date" required>-->
                    <input type="text" name="to_date" id="end_date"   value="<?php echo date("d-M-Y",$memb[0]->to_date); ?>"class="form-control"   readonly>
                  </div>
                  
                  <div class="from-group col-md-3">
                    <label for="pfix">Valid Days</label>
                  <!--  <input type="date" class="form-control" name="to_date" id="to_date" placeholder="Select Date" required>-->
                    <input type="no" name="days" id="end_date"   value="<?php echo $memb[0]->days; ?>"class="form-control"  required>
                  </div>
                  
                  
               <div class="from-group col-md-3">
                    <label for="pfix"> Log Limit</label>
                  <!--  <input type="date" class="form-control" name="to_date" id="to_date" placeholder="Select Date" required>-->
                    <input type="number" name="access_limit"   value="<?php echo $memb[0]->access_limit; ?>"class="form-control"  required>
                  </div>
                  
                  
                     <input type="hidden" name="id" value="<?php echo $memb[0]->id; ?>">
                
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
                
                
                
                
                 
                <?php
                        foreach($res as $user){?>
                          <!-- Modal -->
                          <div class="modal fade" id="addplanModal<?php echo $user->user_id;?>" tabindex="-1" role="dialog" aria-labelledby="#addplanModal<?php echo $user->user_id;?>" aria-hidden="true">
                            <div class="modal-dialog modal-md" role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title" id="addplanModalLabel<?php echo $user->user_id;?>">Add Plan</h5>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                                <div class="modal-body">
                                 <form action="<?php echo base_url('User/add_access_limit')?>" method="post">
              <div class="card-body">
                <div class="row">
                
                  <div class="from-group col-md-5">
                    <label for="depart">From Date</label>
                 <?php  
                $memb = $this->web->getMembershipDetail($user->user_id,$id);
				if(!empty($memb)){
					$lastvalid=date("Y-m-d",$memb[0]->to_date);
				}else{
				     $lastvalid=date("Y-m-d",$user->doj);
						}
                 ?>
                     <input type="date"  name="from_date"  value="<?php echo $lastvalid; ?>" class="form-control" required>
                  </div>
      
                  
                <div class="col-md-5 form-group">
            <label for="Plan">Plan</label>
           <select name="plan" class="form-control"  style="width: 100%;" required>
         <!-- <select name="department" class="form-control">-->
            <!--  <option value=''>All Department</option>-->
            <?php
             $res=$this->web->getplan($id);
            
              if(!empty($res)){
                            foreach($res as $res):
                              echo "<option value=".$res->id .">".$res->name."</option>";
                            endforeach;
                          }
			
            ?></select>
          </div>  
                  
                 
                                          
                    <input type="hidden" name="uid" value="<?php echo $user->user_id;?>">
                     <input type="hidden" name="bid" value="<?php echo $id; ?>">
                    
                 

                  <div class="from-group col-md-5">
                  <button class=" btn btn-success mt-4 mx-auto">Add Now</button>
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
      "paging": false,
      order: [[1, 'asc']],
    });
   
  });
</script>
<script>
function active2(id){
    $.ajax({
      type: "POST",
      url: "User/activatecstudent",
      data: {id},
    success: function(id1){
      $('#activate'+id1).html('<button class="btn btn-success" onclick="inactive(' + id1 + ')">Active</button>');
    }
    })
  }

  function inactive2(id){
    $.ajax({
      type: "POST",
      url: "User/inactivatecstudent",
      data: {id},
    success: function(id1){
      $('#activate'+id1).html('<button class="btn btn-danger" onclick="active('+ id1 + ')">Inactive</button>');
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
  function delete_membership(id){
    $.ajax({
      type: "POST",
      url: "User/delete_membership",
      data: {id},
     success: function(){
    $('#delete'+id).text("deleted");
    }
	
    })
  }
</script>


</body>
</html>
