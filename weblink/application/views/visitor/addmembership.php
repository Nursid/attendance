
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
                <li class="breadcrumb-item active">Add membership</li>
              </ol>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>
      <!-- Main content -->
      
      
       
      <section class="content">
      <?php
	  $buid=$this->web->session->userdata('login_id');
      if($this->session->userdata()['type']=='B' ){?>
        <div class="container-fluid">
          <div class="row">
            <!-- left column -->
            <div class="col-md-12">
              <div class="card card-info">
                <div class="card-header">
                  <h3 class="card-title">Add Membership</h3><br>
                  <span style="color: red"><?php echo $this->session->flashdata('msg2');?></span>
                </div>
                <div class="card-body">
                  <?php    //$res=$this->web->getEmployeesList($id=$this->web->session->userdata('$buid'));
                  $mob=0;
				 
	 	 
                  if(isset($_GET['mob'])){
                    $mob=$_GET['mob'];
					//$emp_code=$_GET['emp_code'];
					//$bio_id=$_GET['bio_id'];
                  }
                  if ($mob==0){
                    //echo "please Enter Mobile no";
                    ?>
                    <h5> Search Enroll Id/Mobile No. </h5>
                    <div class="row">
                      <div class="col-lg-7 float-left">
                        <form action="" method="GET">
                          <div class="row">
                            <div class="col-5 ">
                              <input type="text" class="form-control" pattern="[0-9]{10}" name="mob"  placeholder="Enter 10 Digit ID " maxlength="10" required>
                            </div>
                            <div class="col-3">
                              <button type="submit" class="btn btn-success btn-fill btn-block">Check</button>
                            </div>
                          </div>
                        </form>
                      </div>
                       </div>
                       <br>
              
                  
                  
                  <?php
                  
                }
                else {
                  $umobile=$this->web->getIdByMb($mob);
				    
                  // $mbid2=$umobile[0]->id;
                  if (!empty($umobile)){
                    $userCmp = $this->web->getHostelUser($umobile[0]->id,$buid);
                  //  if(isset($userCmp) && ($userCmp['left_date']=="" || $userCmp['left_date']>time())){
                    //  echo "<h5> User Already Added in a Company</h5>";
                   // }else{
                    //  echo "<h5> User Already Registered</h5>";
                   // }
                    //  echo "User Already Registered ";
					 if(isset($userCmp) && ($userCmp['left_date']=="")){
                    ?>
                    
                    <form action="<?php echo base_url('User/addnewmembership')?>" method="post">
                      <div class="card-body">
                        <div class="row">
                          <input type="hidden" class="form-control" value="<?php echo $umobile[0]->id?>"  name="usid" id="usid" readonly>
                          <div class="from-group col-md-5">
                            <label for="mobile">Enroll ID/Mobile No</label>
                            <input type="text" class="form-control" value="<?php echo $umobile[0]->mobile?>"  name="mobile" id="mobile" readonly>
                          </div>
                          <div class="from-group col-md-5">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" name="name" value=" <?php echo $umobile[0]->name?>" id="name"  readonly>
                          </div>
                          
                           <div class="from-group col-md-5">
                                <label for="email">Validity From</label>
                                <input type="date" class="form-control" name="val_from" >
                              </div>
                              <div class="from-group col-md-5">
                                <label for="address">Validity To</label>
                                <input type="date" class="form-control" name="val_to" >
                              </div>
                              <div class="from-group col-md-5">
                                <label for="empcode">Max Access Limit  </label>
                                <input type="number" class="form-control" name="limit" >
                              </div>
                          
                          
                          
                          
                          <div class="from-group col-md-5">
                            <br>
                            <?php
                            
                              echo '<button  class=" btn btn-success mt-4 mx-auto" >Add Membership</button>';
                          ?>
                            <a href="<?php echo base_url('add_membership')?>"    <button class=" btn btn-success mt-4 mx-auto" >Cancel</button> </a>
                          </div>
                        </div></div></form>
                        <?php
					 }else {
                        // echo "Register New Empoyee ";
                        ?>
                        <h5> Wrong Student ID/ Mobile No  </h5>
                        
                            <!-- /.card-body -->
                            <?php
					 }
					 
						  
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
    </body>
    </html>
