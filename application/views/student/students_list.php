
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
<?php $this->load->view('student/student_menu')?>  <!-- Content Wrapper. Contains page content -->
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
              <li class="breadcrumb-item active">Student List</li>
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
     // $role=$this->web->getRollbyid($this->web->session->userdata('login_id'),$id);
  
    } else {
      $bid=$this->web->session->userdata('login_id');
    }
    ?>
    <!-- Main content -->
    <section class="content">
      <?php
      if($this->session->userdata()['type']=='B' ){?>
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-2">
            <div class="card card-info">
              
              
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
                <h3 class="card-title">Student List
                </h3>
              </div>
              
              <div class="card-body">
                
                
              <div class="row">
                      <div class="col-lg-12 float-left">
                        <form action="<?php echo base_url('User/Students_list') ?>" method="POST">
                          <div class="row">
                           <!-- <div class="col-sm-2">
                              <select name="class_id" class="form-control"  id="emp" required>
                                <option value="">Select Class</option>
                              <?php 
                            $res = $this->web->getallclassbyid($id);
                                    foreach ($res as $res) :
                             // $uname = $this->web->getNameByUserId($res->user_id); ?>
                                <option value="<?php echo $res->id  ?>" <?php if($res->id==$id){ echo "selected";}?>><?php echo $res->name; ?></option>
                              <?php endforeach; ?>
                              </select>
                            </div>-->
                            
                              <div class="from-group col-sm-2">
                 
                    <?php
                        $data = $this->web->getBusinessDepByBusinessId($bid);
                    ?>

                    <select class="select2"  id="departs" style="width: 100%;" name="dept">
                      <option value="" disabled selected>Select Branch</option>

                        <?php foreach($data as $key => $val){
                          
                            echo "<option value=" . $val->id . ">" .$val->name."</option>";                          
                        } ?>


                    </select>
                       
                  </div>  
                              
                      <div class="col-md-2">
                <div class="form-group">
                 
                    <select class="select2"  id="sdeparts" data-placeholder="Select Batch" style="width: 100%;" name="session">
                    </select>
                </div>
                <!-- /.form-group -->
              </div>
              
               <div class="col-md-2">
                <div class="form-group">
                 
                    <select class="select2"  id="section" data-placeholder="Select Section" style="width: 100%;" name="section">
                    </select>
                </div>
                <!-- /.form-group -->
              </div>
                     
                            
                            
                            
                         
                            <div class="col-sm-2">
                              <button type="submit" class="btn btn-success btn-fill btn-block">Show</button>
                            </div>
                            
                          </div>
                        </form>
                      </div>
                    </div>
                    <br><br>  
                
                
             <?php
                    if ($load) {
                    //  $stdate = strtotime($start_date);
                     // $endate = strtotime($end_date);
                    ?>
                  <?php // echo "sod=".$sid; ?>
              <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>S.No</th>
                    <th>Enroll Id</th>
                    <th>Student Id</th>
                    <th>Name</th>
                    <th>Class/Section</th>
                    <th>Roll No</th>
                     <th>Semester</th>
                      <th>Session</th>
                       <th>Batch</th>
                        <th>department</th>
                  <!--  <th>Address</th>-->
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
               <?php
			           
				   // $left=strtotime(date("d-m-Y",time()));
				 // $cudate = date("Y-m-d");
				 // $cudate= '2022-04-15';
			//	$cdate=strtotime($cudate);
				
				$start_time=time();
                     // $res=$this->web->getSchoolStudentListbyclass($id,$sid);
                      	$res = $this->web->getSchoolStudentListbysection($bid,$dept,$session,$section);
					  $count=1;
            
                      foreach($res as $val){
						         //  $userid=$val->user_id;
                      ?>
                      <tr>
                       <td><?php echo $count++; ?></td>
                       <td><?php  echo $val->enroll_id; ?></td>
                       <td><?php  echo $val->student_code; ?></td>
                        <td><?php //$uname = $this->web->getNameByUserId($val->user_id);
                                echo $val->name; ?></td>
                                
                                 <td>
                          
                        
                          <?php
						  $classname = $this->web->getclassById($val->class_id); 
						   $sectionname = $this->web->getsectionById($val->section); 
						    $batchname = $this->web->getbatchById($val->batch); 
						       if(!empty($classname)){
							   echo $classname[0]->name."</br>";
						       }
						       if(!empty($sectionname)){
							   echo $sectionname[0]->name;
						       }
						    
                               ?>
                          </td> 
                          
                          
                          <td><?php  echo $val->roll_no; ?></td>
                          <td><?php  echo $val->semester; ?></td>
                          <td><?php  echo $val->session; ?></td>
                          <td><?php  
                           if(!empty($batchname)){
							   echo $batchname[0]->session_name;
						       }
                           ?>
                          
                          </td>
                           <td><?php 
                            $depname = $this->web->getBusinessDepByUserId($val->department); 
                            if(!empty($depname)){
							   echo $depname[0]->name ;
						       }
						       ?>
						       </td>
                                
                       <!-- <td><?php 
                                echo $val->address; ?></td>-->
                         
                           
                           <?php
						     // echo  $hostel[0]->parent_name;
							  // echo "<br> ".$hostel[0]->parent_mobile;
							  //  echo" <br> ". $hostel[0]->parent_relation;
								 ?>
                           
                          
                        
                        
                        
                     <!--   <td>  
                        <button class="btn btn-danger"data-toggle="modal" data-target="#myModal" onclick="mclick('<?php echo $val->id; ?>')" >Left</button>
                        
                        <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal" onclick="mclick('<?php echo $res->id; ?>')">
                            <i class="fas fa-edit"></i>
                          </button>
                        </td> -->
                        <td> 
                        <a href="<?php echo base_url('User/edit_S_student?id=') . $val->id ?>"
                       
                 
                  
               
                          <button type="button"  class="btn btn-primary btn-lg"  value="('<?php echo $val->id; ?>')">
                            <i class="fas fa-edit"></i>
                          </button>  </a>
                     
                        </td>
                      </tr>
                      <?php 
                      }
                      ?>
                  </tfoot>
                </table>
                 <?php
                    }
                    ?>
                
                
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
function active(id){
    $.ajax({
      type: "POST",
      url: "User/activateEmployee",
      data: {id},
    success: function(id1){
      $('#activate'+id1).html('<button class="btn btn-success" onclick="inactive(' + id1 + ')">Active</button>');
    }
    })
  }

  function inactive(id){
    $.ajax({
      type: "POST",
      url: "User/inactivateEmployee",
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
$(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })
  });
 
 

 $('#departs').on('change', function() {
  var id = this.value;
  var datatypes_session = "sessionlist";
  $.ajax({
    type: "post",
    url: "User/getajaxRequest",
    data: {id,datatypes_session},
    success: function(data){
      $('#sdeparts').html(data);
    }
  });
});

$('#sdeparts').on('change', function() {
  var id = this.value;
  var datatypes_section = "sectionlist";
  $.ajax({
    type: "post",
    url: "User/getajaxRequest",
    data: {id,datatypes_section},
    success: function(data){
      $('#section').html(data);
    }
  });
});
</script>
</body>
</html>
