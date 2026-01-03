
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
              <li class="breadcrumb-item active"> Employee Detail </li>
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
                <h3 class="card-title">Employee Detail</h3><br>
                <span style="color: red"><?php echo $this->session->flashdata('msg');?></span>
              </div>
              
              <?php   
			//  $res=$this->web->getEmployeesList($id=$this->web->session->userdata('login_id'));
	//$id='$id';	
	//$id = $_POST['id'];
	//$id = $this->input->post('id');
	//$value = $this->web->getDepartById($id);
	 // $id=$_POST['id'];
	  $id=$this->web->session->userdata('login_id');
	// $id = $this->input->post('data');   
     $val=$this->web->getNameByUserId($id);
	 // $doj_user=$this->web->getDojByUserId($id);
	 // echo "userid==". $val->name;
	 // echo $val->name;
		
     // $value = $this->web->getDepartById($id);
	 	  
			  ?>
              
              
             
              <div class="card-body">
               <table id="example1" class="table table-bordered ">
                  <thead>
                  
                  </thead>
                  <tbody>
                  <tr>
                    <th>Name</th>
                    <th><?php echo $val['0']->name; ?></th>
                    <th bgcolor="#000077" width="2px">  </th>
                    
                    <th>EnrollNo/Mobile No</th>
                  <th><?php echo $val['0']->mobile; ?></th>
                    </tr>
                    
                    
                    
                    <tr>
                    <th>Email_Id</th>
                    <th><?php echo $val['0']->email; ?></th>
                     <th bgcolor="#000077" width="2px">  </th>
                     
                    <th>Phone No</th>
                    <th><?php echo $val['0']->phone; ?></th> 
                  </tr>
                  
                  <tr>
                    <th>Address</th>
                    <th><?php echo $val['0']->address; ?></th>
                    <th bgcolor="#000077" width="2px">  </th>
                    
                    <th>Gender</th>
                  <th> <?php if($val['0']->gender==0){echo "Male";} else {echo "female";} ?>
                  </th>
                    </tr>
                    
                    
                    
                    <tr>
                    <th>Emp Code</th>
                    <th><?php echo $val['0']->emp_code; ?></th>
                     <th bgcolor="#000077" width="2px">  </th>
                     
                    <th>Device Id</th>
                    <th><?php echo $val['0']->bio_id; ?></th> 
                  </tr>
                  
                  <tr>
                    <th>Father Name</th>
                    <th><?php echo $val['0']->father_name; ?></th>
                    <th bgcolor="#000077" width="2px">  </th>
                    
                    <th>Dob</th>
                  <th><?php echo $val['0']->dob; ?></th>
                    </tr>
                    
                    
                    
                    <tr>
                    <th>Designation</th>
                    <th><?php echo $val['0']->designation; ?></th>
                     <th bgcolor="#000077" width="2px">  </th>
                     
                    <th>Shift</th>
                    <th> <?php
                     $gp = $this->web->getBusinessGroupByUserId($val[0]->business_group);
					 echo $gp[0]->name;  ?>
                    
                   
                    </th> 
                  </tr>
                  
                  
                  <tr>
                    <th>Department</th>
                    <th><?php
                     $dp = $this->web->getBusinessDepByUserId($val[0]->department);
					 echo $dp[0]->name;  ?>
                   
                    
                    </th>
                    <th bgcolor="#000077" width="2px">  </th>
                    
                    <th>Education</th>
                  <th><?php echo $val['0']->education; ?></th>
                    </tr>
                    
                    
                    
                    <tr>
                    <th>Blood Group</th>
                    <th><?php echo $val['0']->blood_group; ?></th>
                     <th bgcolor="#000077" width="2px">  </th>
                     
                    <th>Experience</th>
                    <th><?php echo $val['0']->experience; ?></th> 
                  </tr>
                  
                  <tr>
                    <th>Employement</th>
                    <th> <?php if($val['0']->parmanent==0){echo "Permanent";} else {echo "Temporary";} ?>
                    
                    
                    </th>
                    <th bgcolor="#000077" width="2px">  </th>
                    
                    <th>Date Of Joining</th>
                  <th><?php echo date("d-m-Y",$val['0']->doj); ?> </th>
                    </tr>
                    
                     <?php $info=$this->web->getstaffinfoByUserId($id,$bid); ?>
                    
                    
                    <tr>
                    <th>Payment Mode</th>
                    <th><?php echo $info['0']->pay_mode; ?></th>
                     <th bgcolor="#000077" width="2px">  </th>
                     
                    <th>Bank Name</th>
                    <th><?php echo $info['0']->bank_name; ?></th> 
                  </tr>
                  
                  <tr>
                    <th>A/c NO</th>
                    <th><?php echo $info['0']->account_no; ?></th>
                     <th bgcolor="#000077" width="2px">  </th>
                     
                    <th>IFSC Code</th>
                    <th><?php echo $info['0']->ifsc_code; ?></th> 
                  </tr>
                  
                  
                  
                  <tr>
                    <th>UPI</th>
                    <th><?php echo $info['0']->upi; ?></th>
                     <th bgcolor="#000077" width="2px">  </th>
                     
                    <th>PAN</th>
                    <th><?php echo $info['0']->pan; ?></th> 
                  </tr>
                  
                  
                  <tr>
                    <th>Adhar No</th>
                    <th><?php echo $info['0']->adhar; ?></th>
                     <th bgcolor="#000077" width="2px">  </th>
                     
                    <th>EPF No</th>
                    <th><?php echo $info['0']->epf; ?></th> 
                  </tr>
                  
                  <tr>
                    <th>UAN NO</th>
                    <th><?php echo $info['0']->uan; ?></th>
                     <th bgcolor="#000077" width="2px">  </th>
                     
                    <th>ESIC NO</th>
                    <th><?php echo $info['0']->esic; ?></th> 
                  </tr>
                  
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
