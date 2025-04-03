
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
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
         
          <div class="col-sm-12">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Edit Student</li>
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
                <h3 class="card-title">Edit Student</h3><br>
                <span style="color: red"><?php echo $this->session->flashdata('msg');?></span>
              </div>
              
              <?php   
			//  $res=$this->web->getEmployeesList($id=$this->web->session->userdata('login_id'));
	//$id='$id';	
	//$id = $_POST['id'];
	//$id = $this->input->post('id');
	//$value = $this->web->getDepartById($id);
	  $id=$_GET['id'];
	  $bid=$this->web->session->userdata('login_id');
	// $id = $this->input->post('data');   
     $val=$this->web->getNameByUserId($id);
	 // $doj_user=$this->web->getDojByUserId($id);
	 // echo "userid==". $val->name;
	 // echo $val->name;
		
     // $value = $this->web->getDepartById($id);
	 	  
			  ?>
              
              
              <form action="<?php echo base_url('User/updatestudent')?>" method="post">
              <div class="card-body">
                <div class="row">
                  <div class="from-group col-md-5">
                 
                    <label for="name">Name</label>
                    <input type="text" class="form-control" name="name"  value="<?php echo $val['0']->name; ?>" placeholder="Enter  name" id="name" required>
                    <input type="hidden" class="form-control" name="bid"  value="<?php echo $bid; ?>" >
                  </div>
 
                <div class="from-group col-md-5">
                    <label for="mobile">Mobile No</label>
                    <input type="text" class="form-control" name="mobile" value=" <?php echo $val['0']->mobile; ?> " id="mobile" readonly>
                  </div>
                  
                  <div class="from-group col-md-5">
                    <label for="email">Email_Id</label>
                    <input type="email" class="form-control" name="email" value="<?php echo $val['0']->email; ?>" placeholder="Enter Email_id" id="email" >
                  </div>
                  
                   <div class="from-group col-md-5">
                    <label for="address">Address</label>
                    <input type="text" class="form-control" name="address"value="<?php echo $val['0']->address; ?>" placeholder="Enter Address" id="address" required>
                  </div>

                   <div class="from-group col-md-5">
                 <label for="block">Block</label>
                    
                    <?php
					$hostel = $this->web->getHostelByUserId($id,$bid); 
						       $blid=$hostel[0]->block;
						       $block = $this->web->getBlock($blid,$bid);
							  // echo $block[0]->name;

                    // $dp = $this->web->getBusinessDepByUserId($val[0]->department);
						          ?>
                                  <select name="block" class="form-control" name="block"  id="block" >
                                    <option value="<?php echo $block[0]->id  ?>"><?php echo $block[0]->name; ?></option>
                                 
                   <?php
				  
				   $blocks = $this->web->getallBlock($bid);
                    if(!empty($blocks)){
                      foreach($blocks as $blocks):
                        echo "<option value=".$blocks->id .">".$blocks->name."</option>";
                      endforeach;
                   }
				   
				   
				   
				   
                   ?></select>
                               
                  </div>
                  
                  
                  <div class="from-group col-md-5">
                    <label for="floor">Floor</label>
                  
                    <input type="text" class="form-control" name="floor"value="<?php echo $hostel['0']->floor; ?>" placeholder="Enter Floor" id="floor">  
                    
                  </div>
                  
                  <div class="from-group col-md-5">
                    <label for="room">Room No</label>
                    
                    <input type="text" class="form-control" name="room"value="<?php echo $hostel['0']->room_no; ?>" placeholder="Enter Room No" id="room">
                  </div>
                  
                   <div class="from-group col-md-5">
                 <label for="Roomtype">Room Type</label>
                     <?php
					 
						       
					$hostel = $this->web->getHostelByUserId($id,$bid); 
						       $rmid=$hostel[0]->room_type;
						       $rooms= $this->web->getRoomtype($rmid,$bid);
							   //echo $rooms[0]->name;

                    // $dp = $this->web->getBusinessDepByUserId($val[0]->department);
						          ?>
                                  <select name="roomtype" class="form-control" name="roomtype"  id="roomtype" >
                                    <option value="<?php echo $rooms[0]->id  ?>"><?php echo $rooms[0]->name; ?></option>
                                 
                   <?php
				  
				   $blocks = $this->web->getallrooms($bid);
                    if(!empty($blocks)){
                      foreach($blocks as $blocks):
                        echo "<option value=".$blocks->id .">".$blocks->name."</option>";
                      endforeach;
                   }
				   
				   
				   
				   
                   ?></select>
                  </div>
                  
                 
                   <div class="from-group col-md-5">
                    <label for="Gender">Gender</label>
                   
                    
                    <select name="gender" class="form-control" name="gender" id="gender">
                                    <option value="<?php echo $val['0']->gender; ?>"> <?php if($val['0']->gender==0){echo "Male";} else {echo "female";} ?></option>
                                    <option value="0">Male</option>
                                    <option value="1">Female</option>
                                </select>
                  </div> 
                 

                  
                 
                  
                  
                  
                 
                  <div class="from-group col-md-5">
                    <label for="edu">Education</label>
                    <input type="text" class="form-control" value="<?php echo $val['0']->education; ?>" name="edu" placeholder="Education" id="edu">
                  </div>
              
                   
                  
                  <div class="from-group col-md-5">
                    <label for="parents_name">Parent Name</label>
                    
                   
                    <input type="text" class="form-control" value="<?php echo $hostel['0']->parent_name; ?>" name="parent" placeholder="Parants Name" id="edu">
                  
                  </div>
                  
                  <div class="from-group col-md-5">
                    <label for="mobile">Parents Mobile No</label>
                    
                   
                   <input type="text" class="form-control" value="<?php echo $hostel['0']->parent_mobile; ?>" name="parent_mobile" placeholder="Parants Mobile" id="edu">
                  
                  </div>
                  
                  <div class="from-group col-md-5">
                    <label for="relation">Relation</label>
                    
                   
                    <input type="text" class="form-control" value="<?php echo $hostel['0']->parent_relation; ?>" name="parent_relation" placeholder="Parants relation" id="edu">
                  
                  </div>
                  
                  <div class="from-group col-md-5">
                    <label for="doj">Date of Joining</label>
                    
                   
                    <input type="date" class="form-control" name="doj" value="<?php echo date("Y-m-d",$val['0']->doj); ?>" id="doj">
                  
                  </div>
                  
                  <div class="from-group col-md-5">
                    <label for="doj">Device ID</label>
                    
                   
                    <input type="text" class="form-control" name="bio_id" value="<?php echo $val['0']->bio_id; ?>" id="bio_id">
                  
                  </div>
                  
                   <div class="from-group col-md-5">
                    <label for="rfid">RFID</label>
                    
                   
                    <input type="text" class="form-control" name="rfid" value="<?php echo $val['0']->rfid; ?>" id="bio_id">
                  
                  </div>
                  
                  
                  
                   <div class="from-group col-md-5">
                    <label for="doj">Left Student</label>
                    
                   
                    <input type="date" class="form-control" name="dol" id="dol">
                  
                  </div>
                  
                  
            

                  <div class="from-group col-md-5">
                 <br> 
                  <input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
        <button  class=" btn btn-success mt-4 mx-auto" >Update Now</button>
                  
                 
              
              <a href="<?php echo base_url('employees')?>"    <button class=" btn btn-success mt-4 mx-auto" >Cancel</button> </a>
                  </div>
                  
                </div>
              </div>
              </form>
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
</body>
</html>
