
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
    <?php $this->load->view('student/student_menu')?>
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
                <li class="breadcrumb-item active">Add Student</li>
              </ol>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>
      <!-- Main content -->
      
      
       <?php
	  if($this->session->userdata()['type']=='P'){
					// $busi=$this->web->getBusinessbyUser($this->web->session->userdata('login_id'));
		      //  $buid=$busi[0]->business_id;
           $buid = $this->session->userdata('empCompany');
           $role=$this->web->getRollbyid($this->web->session->userdata('login_id'),$buid);
		 			} else {
				$buid=$this->web->session->userdata('login_id');
					}
	  
	  ?>
      
      <section class="content">
      <?php
      if($this->session->userdata()['type']=='B' || $role[0]->add_emp=="1" || $role[0]->type=="1"){?>
        <div class="container-fluid">
          <div class="row">
            <!-- left column -->
            <div class="col-md-12">
              <div class="card card-info">
                <div class="card-header">
                  <h3 class="card-title">Add Student</h3><br>
                  <span style="color: red"><?php echo $this->session->flashdata('msg2');?></span>
                </div>
                <div class="card-body">
                  <?php   // $res=$this->web->getEmployeesList($id=$this->web->session->userdata('$buid'));
                  $mob=0;
				 // $maxid = $this->web->getMaxeid($buid);
				//  $maxdevid = $this->web->getMaxdevid($buid);
				//  $maxempcode = $this->web->getMaxempcode($buid);
				  // $max_mobile=($maxid[0]->mobile)+1;
				  // $max_bio_id=($maxdevid[0]->bio_id)+1;
				 //  $max_emp_code=($maxempcode[0]->emp_code)+1;
				  // $max_emp_code="1";
	 	 
                  if(isset($_GET['mob'])){
                    $mob=$_GET['mob'];
					$emp_code=$_GET['emp_code'];
					$bio_id=$_GET['bio_id'];
                  }
                  if ($mob==0){
                    //echo "please Enter Mobile no";
                    ?>
                    <h5> Enter Enrollment Id </h5>
                    <div class="row">
                      <div class="col-lg-7 float-left">
                        <form action="" method="GET">
                          <div class="row">
                            <div class="col-5 ">
                             <!-- <input type="text" class="form-control" pattern="[0-9]{10}" name="mob"  placeholder="Enter 10 Digit ID " maxlength="10" required>-->
                             
                               <input type="text" class="form-control"  name="mob"  placeholder="Enter 10 Digit ID " maxlength="10" required>
                            </div>
                            <div class="col-3">
                              <button type="submit" class="btn btn-success btn-fill btn-block">Register</button>
                            </div>
                          </div>
                        </form>
                      </div>
                       </div>
                       <br>
                 <!--  <h6> Click Here To Register With Auto Enroll Id</h6>
                      <div class="row" > 
                     <div class="col-lg-7 ">
                        <form action="" method="GET">
                          <div class="row">
                            
                            <div class="col-3">
                   <input type="hidden" class="form-control"  name="mob" id="mob" value="">
                 <input type="hidden" class="form-control"  name="bio_id" id="bio_id" value="">
              <input type="hidden" class="form-control"  name="emp_code" id="emp_code" value="">
                              <button type="submit" class="btn btn-success btn-fill btn-block">Register</button>
                            </div>
                          </div>
                        </form>
                      </div>  
                     </div> -->
                      
                      
                      
                      
                      
                      
                      
                      
                    
                  
                  
                                      <br>
                    
                    
                    <div class="row">
                      <div class="col-lg-7 float-left">   
                <h6> Import Student from Excel. </h6>    
             <form method="post" enctype="multipart/form-data" action="<?php echo base_url('User/import_school_student')?>">
<input type="file" id="excel_file" name="excel_file">
<input type="hidden" id="business_id" name="business_id">
  <input type="submit" value="Upload">
</form>
<a download href="<?php echo base_url() ?>/upload/template/import_student.xlsx">Download Student Sample file</a>
</div>

<!-- <div class="col-5 float-right">
      
  <h6> Import bank detail. </h6>
 <form method="post" enctype="multipart/form-data" action="<?php echo base_url('User/import_staff_detail')?>">
  
<input type="file" id="excel_file" name="excel_file">
<input type="hidden" id="business_id" name="business_id">
  <input type="submit" value="Upload">
</form>
<a download href="<?php echo base_url() ?>/upload/template/staff_bank_detail.xlsx"> Download bank detail Sample file</a>                
  </div>-->
  </div>                
                 

                  
                  
                  
                  
                  <?php
                  
                }
                else {
                  $umobile=$this->web->getIdByMb($mob);
                  // $mbid2=$umobile[0]->id;
                  if (!empty($umobile)){
                    $userCmp = $this->web->getUserCompany($umobile[0]->id);
                    if(isset($userCmp) && ($userCmp['left_date']=="" || $userCmp['left_date']>time())){
                      echo "<h5> User Already Added in a Company</h5>";
                    }else{
                      echo "<h5> User Already Registered</h5>";
                    }
                    //  echo "User Already Registered ";
                    ?>
                    <form action="<?php echo base_url('User/addstaff')?>" method="post">
                      <div class="card-body">
                        <div class="row">
                          <input type="hidden" class="form-control" value="<?php echo $umobile[0]->id?>"  name="usid" id="usid" readonly>
                          <div class="from-group col-md-5">
                            <label for="mobile">Enroll ID</label>
                            <input type="text" class="form-control" value="<?php echo $umobile[0]->mobile?>"  name="mobile" id="mobile" readonly>
                          </div>
                          <div class="from-group col-md-5">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" name="name" value=" <?php echo $umobile[0]->name?>" id="name"  readonly>
                          </div>
                          <div class="from-group col-md-5">
                            <br>
                            <?php
                            if(isset($userCmp) && ($userCmp['left_date']=="" || $userCmp['left_date']>time())){

                            }else{
                              echo '<button  class=" btn btn-success mt-4 mx-auto" >Add to company</button>';
                            }?>
                            <a href="<?php echo base_url('student_list')?>"    <button class=" btn btn-success mt-4 mx-auto" >Cancel</button> </a>
                          </div>
                        </div></div></form>
                        <?php
                      }else {
                        // echo "Register New Empoyee ";
                        ?>
                        <h5> Please Register New Student </h5>
                        <form action="<?php echo base_url('User/addnew_S_student')?>" method="post">
                          <div class="card-body">
                            <div class="row">
                              <div class="from-group col-md-5">
                                <label for="mobile">Enroll ID</label>
                                <input type="text" class="form-control" value="<?php echo $mob; ?>"  name="mobile" id="mobile" readonly>
                              </div>
                              <div class="from-group col-md-5">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" name="name" placeholder="Enter  name" id="name" required>
                              </div>
                              <div class="from-group col-md-5">
                                <label for="email">Roll No</label>
                                <input type="text" class="form-control" name="rollno" placeholder="Enter Roll No" id="email" >
                              </div>
                              
                              
                              
                               <div class="from-group col-md-5">
                                  <label for="department">Branch</label>
                                  <select class="select2"  id="departs" style="width: 100%;" name="department">
                      <option value="" disabled selected>Select Branch</option>
                                
                                   
                                    <?php
                                     $department = $this->web->getBusinessDepByBusinessId($buid);
                                    if(!empty($department)){
                                      foreach($department as $dep){
                                        echo "<option value=".$dep->id .">".$dep->name."</option>";
                                      }
                                    }
                                    ?></select>
                                  </div>
                              
                              
                              
                              
                                 <div class="from-group col-md-5">
                                      <label for="email">Batch</label>
                 
                    <select class="select2"  id="sdeparts" data-placeholder="Select Session" style="width: 100%;" name="batch">
                    <option value="" disabled selected>Select Batch</option>
                        <?php 
                        if(isset($dept) && $dept > 0) {
                            $batches = $this->web->getSessionByDeptId($dept, $bid);
                            if(!empty($batches)) {
                                foreach($batches as $batch) {
                                    $selected = ($session == $batch->id) ? 'selected' : '';
                                    echo "<option value='" . $batch->session_name . "' " . $selected . ">" . $batch->session_name . "</option>";
                                }
                            }
                        }
                        ?> 
                        
                        
                        
                    </select>
                </div>
                
                
                                 <div class="from-group col-md-5">
                                      <label for="semester">Semester</label>
                 
                   <select class="form-control" id="semester" name="semester">
                        <option value="" disabled selected>Select Semester</option>
                        <?php 
                        if(isset($dept) && $dept > 0) {
                            $semesters = $this->web->getallSemesters($bid);
                            if(!empty($semesters)) {
                                foreach($semesters as $sem) {
                                    $selected = ($semester == $sem->id) ? 'selected' : '';
                                    echo "<option value='" . $sem->id . "' " . $selected . ">" . $sem->semestar_name . "</option>";
                                }
                            }
                        }
                        ?>
                    </select>
                </div>
                <!-- /.form-group -->
                
                
                
             
              
              <div class="from-group col-md-5">
                     <label for="block">Section</label>
                   <select class="form-control" id="section" name="section">
                    <option value="" disabled selected>Select Section</option>
                    <?php 
                    if(isset($session) && $session > 0) {
                        $sections = $this->web->getSectionsByBranchAndSemester($dept, $semester);
                        if(!empty($sections)) {
                            foreach($sections as $sec) {
                                $selected = ($section == $sec->id) ? 'selected' : '';
                                echo "<option value='" . $sec->id . "' " . $selected . ">" . $sec->name . "</option>";
                            }
                        }
                    }
                    ?>
                </select>
                </div>
                <!-- /.form-group -->

                              
                              
                              
                           
                          
                  
                  
                             
                              
                  
                  <div class="from-group col-md-5">
                                <label for="email">session</label>
                                <input type="text" class="form-control" name="session" >
                              </div>
                              
                              
                           
                              
                              
                              
                              <div class="from-group col-md-5">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" name="email"  >
                              </div>
                              
                              <div class="from-group col-md-5">
                                <label for="address">Address</label>
                                <input type="text" class="form-control" name="address" placeholder="Enter Address" id="address" >
                              </div>
                              <div class="from-group col-md-5">
                                <label for="empcode">Student ID </label>

                                <input type="text" class="form-control" name="stuid" id="empcode" value="<?php echo $emp_code; ?>">
                              </div>
                              
                              <div class="from-group col-md-5">
                                <label for="empcode">Device ID</label>
                                <input type="text" class="form-control" name="devcode" id="devcode" value="<?php echo $bio_id; ?>" >
                              </div>
                              
                               <div class="from-group col-md-5">
                                <label for="empcode">Card ID</label>
                                <input type="text" class="form-control" name="rfid" id="devcode"  >
                              </div>
                              
                              
                              
                              <div class="from-group col-md-5">
                                <label for="dob">DOB</label>
                                <input type="date" class="form-control" name="dob" placeholder="Enter Date of Birth" id="dob">
                              </div>
                              <div class="from-group col-md-5">
                                <label for="Gender">Gender</label>
                                <select name="gender" class="form-control"  id="gender">
                                  <option value="">Select Gender</option>
                                  <option value="Male">MALE</option>
                                  <option value="Female">FEMALE</option>
                                </select>
                              </div>


                              <div class="from-group col-md-5">
                                <label for="dob">Blood Group</label>
                                <input type="text" class="form-control" name="blood" placeholder="Enter Blood Group " id="dob">
                              </div>
                              
                             
                  
                  <div class="from-group col-md-5">
                    <label for="floor">Parents Name</label>
                  
                    <input type="text" class="form-control" name="par_name" placeholder="" id="floor">  
                    
                  </div>
                  
                  <div class="from-group col-md-5">
                    <label for="room">Parents Mobile</label>
                    
                    <input type="text" class="form-control" name="par_mobile" placeholder="" id="room">
                  </div>
                  
                  <div class="from-group col-md-5">
                    <label for="room">Relation</label>
                    
                    <select name="relation" class="form-control"  id="gender">
                                  <option value="">Select </option>
                                  <option value="Father">Father</option>
                                  <option value="Mother">Mother</option>
                                  <option value="Other">Other</option>
                                </select>
                  </div>
                  
                 <div class="from-group col-md-5">
                    <label for="doj">Date of Joining</label>
                    <input type="date" class="form-control" name="doj" value="<?php echo date("Y-m-d");?>"  id="doj">
                      </div>
                      <input type="hidden" class="form-control" value="<?php echo $buid?>"  name="bid" id="usid" readonly>
                       <div class="from-group col-md-5">
                                    <br>
                                    <button  class=" btn btn-success mt-4 mx-auto" >Add Student</button>
                                    <a href="<?php echo base_url('Students_list')?>"<button class=" btn btn-success mt-4 mx-auto" >Cancel</button> </a>
                                  </div>
                                </div>
                              </div>
                            </form>
                            <!-- /.card-body -->
                            <?php
                          } }
                          ?>
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
      
      
       <script>
$(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })
  });
 
$(document).ready(function () {
  // Use event delegation to handle change events
  $(document).on('change', '#departs', function() {
    var branchId = this.value;
    $('#sdeparts').html('<option value="" disabled selected>Select Batch</option>');
    $('#semester').html('<option value="" disabled selected>Select Semester</option>');
    $('#section').html('<option value="" disabled selected>Select Section</option>');

    $.ajax({
      type: "post",
      url: "<?php echo base_url('User/get_batch_by_branch'); ?>",
      data: {branch_id: branchId},
      success: function(data){
        var batches = JSON.parse(data);
        var options = '<option value="" disabled selected>Select Batch</option>';
        batches.forEach(function(batch) {
          options += '<option value="' + batch.id + '">' + batch.session_name + '</option>';
        });
        $('#sdeparts').html(options);
      },
      error: function() {
        console.log("Error loading batches");
      }
    });

    $.ajax({
      type: "post",
      url: "<?php echo base_url('User/get_semester_by_branch'); ?>",
      data: {branch_id: branchId},
      success: function(data){
        var semesters = JSON.parse(data);
        var options = '<option value="" disabled selected>Select Semester</option>';
        semesters.forEach(function(semester) {
          options += '<option value="' + semester.id + '">' + semester.semestar_name + '</option>';
        });
        $('#semester').html(options);
      }
    });
  });

  $(document).on('change', '#semester', function() {
    var branchId = $('#departs').val();
    var semesterId = this.value;
    $('#section').html('<option value="" disabled selected>Select Section</option>');

$.ajax({
    type: "post",
    url:"<?php echo base_url('User/get_section_by_branch_semester'); ?>",
    data: {branch_id: branchId, semester_id: semesterId},
    success: function(data){
        var sections = JSON.parse(data);
        var options = '<option value="" disabled selected>Select Section</option>';
        sections.forEach(function(section) {
            var selected = ''; // or add your comparison logic here
            options += '<option value="' + section.id + '" ' + selected + '>' + section.name + '</option>';
        });
        $('#section').html(options);
    },
    error: function() {
        console.log("Error loading sections");
    }
});
});
  
}); 


</script>
    </body>
    </html>
