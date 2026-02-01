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
  <link rel="stylesheet"
    href="<?php echo base_url('adminassets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')?>">
  <link rel="stylesheet"
    href="<?php echo base_url('adminassets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')?>">
  <link rel="stylesheet"
    href="<?php echo base_url('adminassets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')?>">
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
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">

            <div class="col-sm-12">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Edit Employee</li>
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
                  <h3 class="card-title">Edit Employee</h3><br>
                  <span style="color: red"><?php echo $this->session->flashdata('msg');?></span>
                </div>

                <?php   
        
      $id=$_POST['id'];
      
    if($this->session->userdata()['type']=='P'){
        
        $bid = $this->session->userdata('empCompany');
      } else {
        $bid=$this->web->session->userdata('login_id');
      } 

      function showDocPreview($file, $empId) {
        if (!$file) return '';
    
        $path = base_url("uploads/employee_documents/$empId/$file");
        $ext = pathinfo($file, PATHINFO_EXTENSION);
    
        if ($ext === 'pdf') {
            return '<embed src="'.$path.'" type="application/pdf" width="100%" height="200px">';
        } else {
            return '<img src="'.$path.'" style="max-width:150px;border:1px solid #ddd;padding:5px">';
        }
    }
      
      //$bid=$this->web->session->userdata('login_id');
    // $id = $this->input->post('data');   
      $val=$this->web->getNameByEmployeeId($id);
          ?>

                <div class="card-body">
                  <ul class="nav nav-tabs" id="employeeTabs" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link active" id="basic-tab" data-toggle="tab" href="#basic" role="tab">
                        Basic Details
                      </a>
                    </li>

                    <li class="nav-item">
                      <a class="nav-link" id="additional-tab" data-toggle="tab" href="#additional" role="tab">
                        Additional Details
                      </a>
                    </li>

                    <li class="nav-item">
                      <a class="nav-link" id="bank-tab" data-toggle="tab" href="#bank" role="tab">
                        Bank Details
                      </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="documents-tab" data-toggle="tab" href="#documents" role="tab">
                        Documents
                      </a>
                    </li>

                  </ul>

                  <div class="tab-content mt-4" id="employeeTabsContent">

                    <div class="tab-pane fade show active" id="basic" role="tabpanel">

                      <form action="<?php echo base_url('User/updateemployee')?>" method="post">
                        <div class="card-body">
                          <div class="row">
                            <div class="from-group col-md-5">

                              <label for="name">Name</label>
                              <input type="text" class="form-control" name="name" value="<?php echo $val['0']->name; ?>"
                                placeholder="Enter  name" id="name" required>
                              <input type="hidden" class="form-control" name="bid" value="<?php echo $bid; ?>">
                            </div>

                            <div class="from-group col-md-5">
                              <label for="mobile">EnrollNo/Mobile No</label>
                              <input type="text" class="form-control" name="mobile"
                                value=" <?php echo $val['0']->mobile; ?> " id="mobile" readonly>
                            </div>

                            <div class="from-group col-md-5">
                              <label for="email">Email_Id</label>
                              <input type="email" class="form-control" name="email"
                                value="<?php echo $val['0']->email; ?>" placeholder="Enter Email_id" id="email">
                            </div>
                            <div class="from-group col-md-5">
                              <label for="phone">Phone No</label>
                              <input type="text" class="form-control" name="phone"
                                value="<?php echo $val['0']->phone; ?>" placeholder="Enter phone no" id="phone">
                            </div>

                            <div class="from-group col-md-5">
                              <label for="address">Address</label>
                              <input type="text" class="form-control" name="address"
                                value="<?php echo $val['0']->address; ?>" placeholder="Enter Address" id="address">
                            </div>

                            <div class="from-group col-md-5">
                              <label for="empcode">Emp.Code</label>
                              <input type="text" class="form-control" name="empcode" id="empcode"
                                value="<?php echo $val['0']->emp_code; ?>" placeholder="Enter Employee Code">
                            </div>

                            <div class="from-group col-md-5">
                              <label for="empcode">Device ID</label>
                              <input type="text" class="form-control" name="bio_id" id="bio_id"
                                value="<?php echo $val['0']->bio_id; ?>" placeholder="Enter Device ID">
                            </div>

                            <div class="from-group col-md-5">
                              <label for="blood">Father Name</label>
                              <input type="text" class="form-control" value="<?php echo $val['0']->father_name; ?>"
                                name="father_name" placeholder="Father Name" id="father_name">
                            </div>

                            <div class="from-group col-md-5">
                              <label for="dob">DOB</label>

                              <input type="date" class="form-control" name="dob" value="<?php echo $val['0']->dob; ?>"
                                placeholder="Enter Date of Birth" id="dob">
                            </div>

                            <div class="from-group col-md-5">
                              <label for="Gender">Gender</label>

                              <select name="gender" class="form-control" name="gender" id="gender">
                                <option value="<?php echo $val['0']->gender; ?>">
                                  <?php if($val['0']->gender==0){echo "Male";} else {echo "female";} ?></option>
                                <option value="0">Male</option>
                                <option value="1">Female</option>
                              </select>
                            </div>

                            <div class="from-group col-md-5">
                              <label for="desig">Designation</label>
                              <input type="text" class="form-control" name="desig" id="desig"
                                value="<?php echo $val['0']->designation; ?>" placeholder="Enter desig">
                            </div>

                            <div class="from-group col-md-5">
                              <label for="group">Shift</label>
                              <select name="group" class="form-control" name="group" id="group">
                                <?php
                      $gp = $this->web->getBusinessGroupByUserId($val[0]->business_group);
                        ?>
                                <option value="<?php echo $val[0]->business_group  ?>"><?php echo $gp[0]->name;  ?>
                                </option>

                                <?php
            $group = $this->web->getBusinessGroupByBusinessId($business_id=$this->web->session->userdata('login_id'));
            
                      if(!empty($group)){
                        foreach($group as $group):
                          echo "<option value=".$group->id .">".$group->name."</option>";
                        endforeach;
                    }
            
                    ?></select>

                            </div>

                            <div class="from-group col-md-5">
                              <label for="department">Department</label>
                              <select name="department" class="form-control" name="department" id="department">
                                <?php
                      $dp = $this->web->getBusinessDepByUserId($val[0]->department);
                        ?>
                                <option value="<?php echo $val[0]->department  ?>"><?php echo $dp[0]->name;  ?></option>

                                <?php
            
            
            $department = $this->web->getBusinessDepByBusinessId($business_id=$this->web->session->userdata('login_id'));
                      if(!empty($department)){
                        foreach($department as $dep):
                          echo "<option value=".$dep->id .">".$dep->name."</option>";
                        endforeach;
                    }
            
            
            
            
                    ?></select>

                            </div>

                            <div class="from-group col-md-5">
                              <label for="edu">Education</label>
                              <input type="text" class="form-control" value="<?php echo $val['0']->education; ?>"
                                name="edu" placeholder="Education" id="edu">
                            </div>

                            <!--   <div class="from-group col-md-5">
                      <label for="edu">Education</label>
                      <input type="text" class="form-control" value="<?php echo $val['0']->education; ?>" name="edu" placeholder="Education" id="edu">
                    </div>
                    -->
                            <div class="from-group col-md-5">
                              <label for="blood">Blood Group</label>
                              <input type="text" class="form-control" value="<?php echo $val['0']->blood_group; ?>"
                                name="blood_group" placeholder="blood_group" id="blood_group">
                            </div>

                            <div class="from-group col-md-5">
                              <label for="blood">Experience</label>
                              <input type="text" class="form-control" value="<?php echo $val['0']->experience; ?>"
                                name="experience" placeholder="experience" id="experience">
                            </div>

                            <div class="from-group col-md-5">
                              <label for="blood">Employment</label>
                              <!--<input type="text" class="form-control" value="<?php echo $val['0']->employement; ?>" name="employement" placeholder="employement" id="employement">-->

                              <select name="employement" class="form-control" id="employement">
                                <option value="<?php echo $val['0']->employement; ?>">
                                  <?php if($val['0']->gender==0){echo "Permanent";} else {echo "Temporary";} ?></option>
                                <option value="0">Permanent</option>
                                <option value="1">Temporary</option>
                              </select>
                            </div>

                            <div class="from-group col-md-5">
                              <label for="doj">Date of Joining</label>

                              <input type="date" class="form-control" name="doj"
                                value="<?php echo date("Y-m-d",$val['0']->doj); ?>" id="doreg">

                            </div>

                            <div class="from-group col-md-5">
                              <label for="doj">Date of Attendance</label>

                              <input type="date" class="form-control" name="dor"
                                value="<?php echo date("Y-m-d",$val['0']->doj); ?>" id="doj">

                            </div>

                            <div class="from-group col-md-5">
                              <label for="doj">Left Employee</label>

                              <input type="date" class="form-control" name="dol" id="dol">

                            </div>

                            <div class="from-group col-md-5">
                              <label for="post">Authority </label>
                              <select name="post" class="form-control" name="post" id="post" required>
                                <option value="<?php echo $val['0']->manager; ?>">
                                  <?php if($val['0']->manager==0){echo "Employee";} else {echo "Manager";} ?></option>
                                <option value="0">Employee</option>
                                <option value="1">Manager</option>
                              </select>
                            </div>

                            <?php
            
            
            $linked = $this->session->userdata('linked');
                    if(count($linked)>0){
              ?>
                            <div class="from-group col-md-5">
                              <label for="doj">Transfer Employee To</label>
                              <select name="trf" class="form-control" id="trf">
                                <option value=""> Select Branch</option>
                                <?php	  
          foreach($linked as $account){
                  if(!empty($account)){
                  $name=$this->web->getBusinessById($account['login_id'])['name'];
          $user_group=$this->web->getBusinessById($account['login_id'])['user_group'];
          if($account['login_id']!=$this->session->userdata('login_id') && $user_group!=2){	  
                      
                          echo "<option value=".$account['login_id'].">".$name."</option>";
                        //endforeach;
          }}}}
            
                    ?>

                              </select>
                            </div>
                            <div class="from-group col-md-5">

                              <input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
                              <button class=" btn btn-success mt-4 mx-auto">Update Now</button>

                              <a href="<?php echo base_url('employees')?>" <button
                                class=" btn btn-success mt-4 mx-auto">Cancel</button> </a>
                            </div>
                          </div>
                        </div>
                      </form>
                    </div>

                    <div class="tab-pane fade" id="additional" role="tabpanel">
                    <form action="<?php echo base_url('User/updateemployeeAddress')?>" method="post">
  <input type="hidden" name="id" value="<?php echo $id; ?>">
  <input type="hidden" name="bid" value="<?php echo $bid; ?>">
  <input type="hidden" name="present_address" value="<?php echo $val[0]->present_address; ?>">
  <input type="hidden" name="permanent_address" value="<?php echo $val[0]->permanent_address; ?>">
  <?php
  $present = json_decode($val[0]->present_address, true);
  $permanent = json_decode($val[0]->permanent_address, true);
  ?>
    <div class="row">

      <!-- ================= PRESENT ADDRESS ================= -->
      <div class="col-md-6">
        <h6 class="mb-3">Present Address</h6>

        <div class="form-group">
          <label>Country</label>
          <input type="text" class="form-control" name="present_country" value="<?php echo $present['country']; ?>">
        </div>

        <div class="form-group">
          <label>State</label>
          <input type="text" class="form-control" name="present_state" value="<?php echo $present['state'] ?? ''; ?>">
        </div>

        <div class="form-group">
          <label>District</label>
          <input type="text" class="form-control" name="present_district" value="<?php echo $present['district'] ?? ''; ?>">
        </div>

        <div class="form-group">
          <label>Block</label>
          <input type="text" class="form-control" name="present_block" value="<?php echo $present['block'] ?? ''; ?>">
        </div>

        <div class="form-group">
          <label>Street</label>
          <input type="text" class="form-control" name="present_street" value="<?php echo $present['street'] ?? ''; ?>">
        </div>

        <div class="form-group">
          <label>Pincode</label>
          <input type="text" class="form-control" name="present_pincode" value="<?php echo $present['pincode'] ?? ''; ?>">
        </div>
      </div>

      

      <!-- ================= PERMANENT ADDRESS ================= -->
      <div class="col-md-6">
      <div class="form-check mb-2">
  <input class="form-check-input" type="checkbox" id="sameAddress">
  <label class="form-check-label" for="sameAddress">
    Same as Present Address
  </label>
</div>

        <h6 class="mb-3">Permanent Address</h6>

        <div class="form-group">
          <label>Country</label>
          <input type="text" class="form-control" name="permanent_country" value="<?php echo $permanent['country'] ?? ''; ?>">
        </div>

        <div class="form-group">
          <label>State</label>
          <input type="text" class="form-control" name="permanent_state" value="<?php echo $permanent['state'] ?? ''; ?>">
        </div>

        <div class="form-group">
          <label>District</label>
          <input type="text" class="form-control" name="permanent_district" value="<?php echo $permanent['district'] ?? ''; ?>">
        </div>

        <div class="form-group">
          <label>Block</label>
          <input type="text" class="form-control" name="permanent_block" value="<?php echo $permanent['block'] ?? ''; ?>">
        </div>

        <div class="form-group">
          <label>Street</label>
          <input type="text" class="form-control" name="permanent_street" value="<?php echo $permanent['street'] ?? ''; ?>">
        </div>

        <div class="form-group">
          <label>Pincode</label>
          <input type="text" class="form-control" name="permanent_pincode" value="<?php echo $permanent['pincode'] ?? ''; ?>">
        </div>
      </div>
      <button class="btn btn-success mt-3" type="submit">Update Address</button>
    </div>
  </form>
  </div>

                    <div class="tab-pane fade" id="bank" role="tabpanel">

                      <form action="<?php echo base_url('User/updateemployeedetail')?>" method="post">
                        <?php $info=$this->web->getstaffinfoByUserId($id,$bid); ?>
                        <div class="row">
                          <div class="from-group col-md-5">

                            <label for="name">Payment Mode</label>
                            <input type="text" class="form-control" name="pay_mode"
                              value="<?php echo $info['0']->pay_mode; ?>" placeholder="" id="name">
                            <input type="hidden" class="form-control" name="bid" value="<?php echo $bid; ?>">
                          </div>

                          <div class="from-group col-md-5">

                            <label for="name">Bank Name</label>
                            <input type="text" class="form-control" name="bank_name"
                              value="<?php echo $info['0']->bank_name; ?>" placeholder="Enter  Bank name" id="name">

                          </div>

                          <div class="from-group col-md-5">

                            <label for="name">A/C No</label>
                            <input type="text" class="form-control" name="account_no"
                              value="<?php echo $info['0']->account_no; ?>" placeholder="Enter A/C no" id="name">

                          </div>

                          <div class="from-group col-md-5">

                            <label for="name">IFSC Code</label>
                            <input type="text" class="form-control" name="ifsc_code"
                              value="<?php echo $info['0']->ifsc_code; ?>" placeholder="" id="name">

                          </div>

                          <div class="from-group col-md-5">

                            <label for="name">UPI</label>
                            <input type="text" class="form-control" name="upi" value="<?php echo $info['0']->upi; ?>"
                              placeholder="" id="name">

                          </div>

                          <div class="from-group col-md-5">

                            <label for="name">PAN No</label>
                            <input type="text" class="form-control" name="pan" value="<?php echo $info['0']->pan; ?>"
                              placeholder="" id="name">

                          </div>

                          <div class="from-group col-md-5">

                            <label for="name">Adhar No</label>
                            <input type="text" class="form-control" name="adhar"
                              value="<?php echo $info['0']->adhar; ?>" placeholder="" id="name">

                          </div>

                          <div class="from-group col-md-5">

                            <label for="name">EPF</label>
                            <input type="text" class="form-control" name="epf" value="<?php echo $info['0']->epf; ?>"
                              placeholder="Enter  name" id="name">

                          </div>

                          <div class="from-group col-md-5">

                            <label for="name">UAN</label>
                            <input type="text" class="form-control" name="uan" value="<?php echo $info['0']->uan; ?>"
                              placeholder="Enter  name" id="name">

                          </div>

                          <div class="from-group col-md-5">

                            <label for="name">ESIC</label>
                            <input type="text" class="form-control" name="esic" value="<?php echo $info['0']->esic; ?>"
                              placeholder="Enter  name" id="name">

                          </div>

                          <div class="from-group col-md-5">

                            <input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
                            <button class=" btn btn-success mt-4 mx-auto">Update Now</button>

                            <a href="<?php echo base_url('employees')?>" <button
                              class=" btn btn-success mt-4 mx-auto">Cancel</button> </a>
                          </div>

                        </div>
                        </form>
                    </div>

                    <div class="tab-pane fade" id="documents" role="tabpanel">

                    <form action="<?php echo base_url('User/updateEmployeeDocuments'); ?>"
                      method="post"
                      enctype="multipart/form-data">
                      <input type="hidden" name="emp_id" value="<?php echo $id; ?>">



  <div class="row">

    <!-- Bank Proof -->
    <div class="from-group col-md-5">
    <label>Bank Account Proof</label>
    <input type="file" class="form-control file-check preview-file"
          data-preview="bank_preview"
          name="bank_proof"
          accept=".png,.jpg,.jpeg,.pdf">

    <div id="bank_preview" class="mt-2">
    <?php if (!empty($val[0]->bank_proof)) { ?>
        <img src="<?php echo base_url('uploads/employee_documents/'.$id.'/'.$val[0]->bank_proof); ?>"
             style="max-width:150px; border:1px solid #ccc; padding:5px;">
    <?php } ?>
    </div>
  </div>


    <!-- Medical -->
    <div class="from-group col-md-5">
  <label>Medical Certificate</label>
  <input type="file" class="form-control file-check preview-file"
         data-preview="medical_preview"
         name="medical_certificate"
         accept=".png,.jpg,.jpeg,.pdf">

  <div id="medical_preview" class="mt-2">
  <?php if (!empty($val[0]->medical_certificate)) { ?>
        <img src="<?php echo base_url('uploads/employee_documents/'.$id.'/'.$val[0]->medical_certificate); ?>"
             style="max-width:150px; border:1px solid #ccc; padding:5px;">
    <?php } ?>
  </div>
</div>
    <!-- Aadhaar -->
    <div class="from-group col-md-5">
    <label>Aadhaar</label>
    <input type="file" class="form-control file-check preview-file"
          data-preview="adhar_preview"
          name="adhar_doc"
          accept=".png,.jpg,.jpeg,.pdf">

    <div id="adhar_preview" class="mt-2">
    <?php if (!empty($val[0]->adhar_doc)) { ?>
        <img src="<?php echo base_url('uploads/employee_documents/'.$id.'/'.$val[0]->adhar_doc); ?>"
             style="max-width:150px; border:1px solid #ccc; padding:5px;">
    <?php } ?>

    </div>
  </div>


    <!-- PAN -->
    <div class="from-group col-md-5">
  <label>PAN</label>
  <input type="file" class="form-control file-check preview-file"
         data-preview="pan_preview"
         name="pan_doc"
         accept=".png,.jpg,.jpeg,.pdf">

  <div id="pan_preview" class="mt-2">
  <?php if (!empty($val[0]->pan_doc)) { ?>
        <img src="<?php echo base_url('uploads/employee_documents/'.$id.'/'.$val[0]->pan_doc); ?>"
             style="max-width:150px; border:1px solid #ccc; padding:5px;">
    <?php } ?>
  </div>
</div>


    <!-- Photo -->
    <div class="from-group col-md-5">
  <label>Photo</label>
  <input type="file" class="form-control file-check preview-file"
         data-preview="photo_preview"
         name="photo"
         accept=".png,.jpg,.jpeg">

         <div id="photo_preview" class="mt-2">
    <?php if (!empty($val[0]->photo)) { ?>
        <img src="<?php echo base_url('uploads/employee_documents/'.$id.'/'.$val[0]->photo); ?>"
             style="max-width:150px; border:1px solid #ccc; padding:5px;">
    <?php } ?>
</div>

</div>


    <!-- Buttons -->
    <div class="from-group col-md-5 mt-4">
      <input type="hidden" name="id" value="<?php echo $id; ?>">
      <button class="btn btn-success">Add / Update Documents</button>
    </div>

  </div>

</form>
                    </div>


              

                  </div>

                  

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
  <script src="<?php echo base_url('adminassets/plugins/datatables-responsive/js/dataTables.responsive.min.js')?>">
  </script>
  <script src="<?php echo base_url('adminassets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')?>">
  </script>
  <script src="<?php echo base_url('adminassets/dist/js/adminlte.min.js')?>"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="<?php echo base_url('adminassets/dist/js/demo.js')?>"></script>
  <script>
    $(function() {
      var table = $('#example1').DataTable({
        "responsive": true,
        "autoWidth": false,
      });
    });
  </script>

  <script>
    // $(document).ready(function () { 
    // $('.nav-link').click(function(e) {
    // $('.nav-link').removeClass('active');        
    // $(this).addClass("active");
    // });
    // });
    $(function() {
      var url = window.location;
      // for single sidebar menu
      $('ul.nav-sidebar a').filter(function() {
        return this.href == url;
      }).addClass('active');
      // for sidebar menu and treeview
      $('ul.nav-treeview a').filter(function() {
          return this.href == url;
        }).parentsUntil(".nav-sidebar > .nav-treeview")
        .css({
          'display': 'block'
        })
        .addClass('menu-open').prev('a')
        .addClass('active');
    });
  </script>

<script>

document.querySelectorAll('.file-check').forEach(input => {
  input.addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;

    const allowedTypes = ['image/png', 'image/jpeg', 'application/pdf'];
    const maxSize = 500 * 1024; // 500KB

    if (!allowedTypes.includes(file.type)) {
      alert('Only PNG, JPG, JPEG & PDF allowed');
      this.value = '';
      return;
    }

    if (file.size > maxSize) {
      alert('File size must be under 500KB');
      this.value = '';
      return;
    }
  });
});
</script>

<script>
document.querySelectorAll('.preview-file').forEach(input => {
  input.addEventListener('change', function () {

    const previewId = this.dataset.preview;
    const previewDiv = document.getElementById(previewId);
    previewDiv.innerHTML = '';

    const file = this.files[0];
    if (!file) return;

    const fileType = file.type;

    if (fileType === 'application/pdf') {
      previewDiv.innerHTML = `
        <embed src="${URL.createObjectURL(file)}"
               type="application/pdf"
               width="100%"
               height="200px">
      `;
    } else {
      const img = document.createElement('img');
      img.src = URL.createObjectURL(file);
      img.style.maxWidth = '150px';
      img.style.border = '1px solid #ddd';
      img.style.padding = '5px';
      img.style.borderRadius = '4px';
      previewDiv.appendChild(img);
    }

  });
});
</script>
<script>
document.getElementById('sameAddress').addEventListener('change', function () {
  if (this.checked) {
    document.querySelector('[name="permanent_country"]').value =
      document.querySelector('[name="present_country"]').value;
    document.querySelector('[name="permanent_state"]').value =
      document.querySelector('[name="present_state"]').value;
    document.querySelector('[name="permanent_district"]').value =
      document.querySelector('[name="present_district"]').value;
    document.querySelector('[name="permanent_block"]').value =
      document.querySelector('[name="present_block"]').value;
    document.querySelector('[name="permanent_street"]').value =
      document.querySelector('[name="present_street"]').value;
    document.querySelector('[name="permanent_pincode"]').value =
      document.querySelector('[name="present_pincode"]').value;
  }
});


</script>

</body>

</html>