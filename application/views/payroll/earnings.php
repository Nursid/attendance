<style type="text/css">
  .nav-pills-custom .nav-link {
    color: #aaa;
    background: #fff;
    position: relative;
  }

  .nav-pills-custom .nav-link.active {
    color: #45b649;
    background: #fff;
  }


  /* Add indicator arrow for the active tab */
  @media (min-width: 992px) {
    .nav-pills-custom .nav-link::before {
      content: '';
      display: block;
      border-top: 8px solid transparent;
      border-left: 10px solid #fff;
      border-bottom: 8px solid transparent;
      position: absolute;
      top: 50%;
      right: -10px;
      transform: translateY(-50%);
      opacity: 0;
    }
  }

  .nav-pills-custom .nav-link.active::before {
    opacity: 1;
  }






  /*  CSS FOR COLLSPAS   */
  /*  CSS FOR COLLSPAS   */
  .card-header .title {
    font-size: 17px;
    color: #000;
  }

  .card-header .accicon {
    float: right;
    font-size: 20px;
    width: 1.2em;
  }

  .card-header {
    cursor: pointer;
    border-bottom: none;
  }

  .card {
    border: 1px solid #ddd;
  }

  .card-body {
    border-top: 1px solid #ddd;
  }

  .card-header:not(.collapsed) .rotate-icon {
    transform: rotate(180deg);
  }
</style>


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
            <li class="breadcrumb-item active">Employee List</li>
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
    
  <?php
  //if ($this->session->userdata()['type'] == 'B') {
      if($this->session->userdata()['type']=='B' || $role[0]->earn=="1" || $role[0]->type=="1"){
    $month = isset($_GET['getDate']) ? $_GET['getDate'] : date("Y-m");
  ?>
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">


        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <div class="card card-primary">

              <div class="card-header">
                <h3 class="card-title">Earnings </h3>
              </div>

              <div class="card-body">
                <div class="row">
                  <div class="col-sm-10">
                    <!-- <form action="<?php echo base_url('User/salaryEmployees') ?>" method="post"> -->
                      <div class="row mb-4">
                        <div class="col-sm-3">

                          <input type="month" title="Start Date" class="form-control" placeholder="Date From" value="<?php echo $month; ?>" onchange="setDate()" id="setDate">
                        </div>

                      </div>
                    <!-- </form> -->
                  </div>
                  <div class="modal fade" id="allCtcModal" tabindex="-1" role="dialog" aria-labelledby="allCtcModalLabel" aria-hidden="true">
                  <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="allCtcModalLabel">All CTC</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <form action="<?php echo base_url('User/addAllCtc')?>" method="POST">
                        <div class="modal-body">
                          <div class="row text-center font-weight-bold">
                            <div class="col-sm-3">Name</div>
                            <div class="col-sm-3">Basic CTC</div>
                            <div class="col-sm-3">PF</div>
                            <div class="col-sm-3">ESI</div>
                          </div>
                          <?php
                            $sr = 0;
                            if (!empty($salEmpList)) {
                              foreach ($salEmpList as $key => $empData) {
                                $sr++; ?>
                              <div class="row">
                                <div class="col-sm-3 text-center">
                                  <?= $empData->empName; ?>
                                </div>
                                <div class="col-sm-3">
                                <input type="number" name="all_basic_value_<?= $sr?>" min="0" class="form-control" placeholder="0" required="" value="<?= $empData->basicCtc ?>" oninput="setSalaryChanged('<?= $sr?>');">
                                <input type="hidden" name="salary_emp_id_<?= $sr?>" value="<?= $empData->user_id; ?>">
                                <input type="hidden" name="salary_changed_<?= $sr?>" value="0">
                                </div>
                                <div class="col-sm-3">
                                  <div class="form-group">
                                    <div class="input-group">
                                      <input type="number" name="pf_value_<?= $sr?>" step="0.01" class="form-control" placeholder="0" value="<?= $empData->pfValue ?>" oninput="setSalaryChanged('<?= $sr?>');">
                                      <div class="input-group-append">
                                        <span class="input-group-text">%</span>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                <div class="col-sm-3">
                                  <div class="form-group">
                                    <div class="input-group">
                                      <input type="number" name="esi_value_<?= $sr?>" step="0.01" class="form-control" placeholder="0" value="<?= $empData->esiValue ?>" oninput="setSalaryChanged('<?= $sr?>');">
                                      <div class="input-group-append">
                                        <span class="input-group-text">%</span>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                          <?php
                              }
                            }?>
                          <input type="hidden" name="date_from" value="<?php if (isset($date_from)) {
                                                                echo $date_from;
                                                              } else if (isset($empData->startDate)) {
                                                                echo date("Y-m", $empData->startDate);
                                                              } ?>">
                          <input type="hidden" name="all_ctc_max" value="<?= $sr; ?>">
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                          <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
                  <!-- <div class="col-sm-2">
                    <form action="<?php echo base_url('User/salaryReport') ?>" method="post">
                      <input type="month" title="Start Date" min="2020" max="2025" placeholder="Date From" onchange="checkDate()" value="<?php if (isset($date_from)) {echo $date_from;} ?>" class="form-control" name="date_from" dd1="date_from" required id="txtFrom" hidden>
                      <input type="submit" class="btn btn-primary" name="filter_btn" value="Recalculate Attendance" />
                    </form>
                  </div> -->
                </div>
                <!-- <div align="right">
                    <button type="button" class="btn btn-primary m-4" data-toggle="modal" data-target="#allCtcModal">All CTC</button>
                  </div>
                <div align="right">
                          <input type="button" onClick="exportData()" value="Export To Excel" />
                          <input type="button"  id="btnExport" value="Export To Pdf" onclick="exportPDF()" />
                          <br>
                        </div> -->
                <table id="newsalaryReport" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>S.No</th>
                      <th>EmpCode</th>
                      <th>Name</th>
                      <th>Advance</th>
                      <th>Paid</th>
                      <th>Earnings</th>
                      <th>Deduction</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>

                    <?php
                    if (!empty($salEmpList)) {
                      $sr = 1;
                        usort($salEmpList, function($a, $b) {
                            if(empty($a->emp_code)){
                                return -1;
                            }elseif ($a->emp_code > $b->emp_code) {
                                return 1;
                            } elseif ($a->emp_code < $b->emp_code) {
                                return -1;
                            }
                            return 0;
                        });
                      foreach ($salEmpList as $key => $empData) { 
                        $loan = $this->db->select_sum("amount")
                                              ->from("payroll_history")
                                              ->join('payroll_master','payroll_master.id=payroll_history.payroll_master_id')
                                              ->where_in('payroll_master_id',[4])
                                              ->where('payroll_history.status',1)
                                              ->where("user_id",$empData->emp_id)
                                              ->where("DATE_FORMAT(pay_date,'%Y-%m')", $month)
                                              ->get()->row();
                        $earnings = $this->db->select_sum("amount")
                                              ->from("payroll_history")
                                              ->join('payroll_master','payroll_master.id=payroll_history.payroll_master_id')
                                              ->where_in('payroll_master_id',[1,3,5,6])
                                              ->where('payroll_history.status',1)
                                              ->where("user_id",$empData->emp_id)
                                              ->where("DATE_FORMAT(pay_date,'%Y-%m')", $month)
                                              ->get()->row();
                        $deduction = $this->db->select_sum("amount")
                                              ->from("payroll_history")
                                              ->join('payroll_master','payroll_master.id=payroll_history.payroll_master_id')
                                              ->where_in('payroll_master_id',[7,8])
                                              ->where('payroll_history.status',1)
                                              ->where("user_id",$empData->emp_id)
                                              ->where("DATE_FORMAT(pay_date,'%Y-%m')", $month)
                                              ->get()->row();
                        $paid = $this->db->select_sum("amount")
                                              ->from("payroll_history")
                                              ->join('payroll_master','payroll_master.id=payroll_history.payroll_master_id')
                                              ->where_in('payroll_master_id',[2])
                                              ->where('payroll_history.status',1)
                                              ->where("payroll_id",0)
                                              ->where("user_id",$empData->emp_id)
                                              ->where("DATE_FORMAT(pay_date,'%Y-%m')", $month)
                                              ->get()->row();

                        ?>
                        <tr>
                          <td><?= $sr; ?></td>
                          <td><?= $empData->emp_code; ?></td>
                          <td><?= $empData->empName; ?></td>
                          <td><?= $loan->amount; ?></td>
                          <td><?= $paid->amount; ?></td>
                          <td><?= $earnings->amount; ?></td>
                          <td><?= $deduction->amount; ?></td>
                          <td>
                              
                              <button type="button" class="btn btn-xs mt-1 btn-primary" id="addDeductModlbtn" onclick="clickAddDeductForm(<?=$empData->emp_id; ?>);addDeductBtn('type_addition');" data-toggle="modal" data-target="#addDeductModl">Add </button>
                              
                            <a href="<?=base_url('Payroll/earningHistory');?>?id=<?=$empData->emp_id; ?>" class="btn btn-xs mt-1 btn-primary"> <i class="fa fa-life-ring"></i> History</a>
                            
                           <a href="#" class="btn btn-xs mt-1 btn-primary" onclick="setModalUserID(<?=$empData->emp_id; ?>);" data-toggle="modal" data-target="#salleryModal"> 
                              <i class="fa fa-life-ring"></i> 
                              Loan
                            </a>
                            
                             
                          </td>
                        </tr>
                    <?php $sr++;
                      }
                    }  ?>
                  </tbody>

                </table>
                <!-- MODAL START  -->
                <!-- Modal -->
                <div class="modal fade" data-backdrop="static" data-keyboard="false" id="salleryModal" tabindex="-1" role="dialog" aria-labelledby="salleryModalLabel" aria-hidden="true">
                  <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="salleryModalLabel">Loan </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <!-- MODAL  BODY START -->


                        <div class="col-md-12">
                          <div class="row">


                            <!-- Demo header-->
                            <section class="header" style="width: -webkit-fill-available;">
                              <div class="container" id="nnvtab">

                                <div class="row">
                                  <div class=" col-md-12">
                                    <!-- Tabs content -->
                                    <div class="tab-content pl-3" id="v-pills-tabContent">

                                      <!-- START CTC -->
                                      <div class="tab-pane fade   rounded bg-white nvtbbox data-0 show active " id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">

                                        <form id="ctcForm" action="<?= base_url('User/saveCtc'); ?>" method="post">
                                          <div class="row">
                                            <div class="col-md-6">
                                              <div class="form-group">
                                                <label class="d-inherit " for="exampleInputEmail1">Basic</label>

                                                <select class="form-control" name="basic" onchange="setBasicCTC();">
                                                  <option value="Monthly">Monthly</option>
                                                  <option value="Daily">Daily</option>
                                                  <option value="Hourly">Hourly</option>
                                                </select>
                                              </div>
                                            </div>



                                            <div class="col-md-6">
                                              <div class="form-group">
                                                <label for="basic_value" class="float-right"><b>In hand salary : </b><span id="totalamountctc"></span></label>
                                                <input type="hidden" name="input_total_ctc_amount">
                                                <input type="number" name="basic_value" oninput="setBasicCTC();" min="0" class="form-control" id="basic_value" placeholder="0" required="">
                                              </div>
                                            </div>
                                            <input type="hidden" name="select_user_id">
                                            <input type="hidden" name="date_from" value="<?php if (isset($date_from)) {
                                                                echo $date_from;
                                                              } else if (isset($empData->startDate)) {
                                                                echo date("Y-m", $empData->startDate);
                                                              } ?>">
                                          </div>

                                          <div class="accordion" id="accordionExample">
                                            <div class="card">
                                              <div class="card-header" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true">
                                                <span class="title">Allowance</span>
                                                <span class="accicon"><i class="fas fa-angle-down rotate-icon"></i></span>
                                              </div>
                                              <div id="collapseOne" class="collapse show" data-parent="#accordionExample">
                                                <div class="card-body cardbody_allowance">



                                                  <?php
                                                  $allowanceForm = array('DA', 'HRA', 'MEAL', 'CONVEYANCE', 'MEDICAL', 'SPECIAL', 'TA');
                                                  foreach ($allowanceForm as $key => $FormData) {
                                                  ?>

                                                    <!-- ********************************* -->
                                                    <!-- 1 DA ALLOWANCE START -->
                                                    <div class="row">
                                                      <div class="col-md-5">
                                                        <div class="form-group">
                                                          <div class="input-group">
                                                            <input type="text" class="form-control inp_allowance" readonly="" value="<?= $FormData; ?>" name="allowance[]">
                                                            <div class="input-group-append">
                                                              <select name="<?= strtolower($FormData); ?>_type" class="bg-light" onchange="setBasicCTC();">
                                                                <option value="Manual">Manual</option>
                                                                <option value="%">%</option>
                                                              </select>
                                                            </div>
                                                          </div>
                                                        </div>
                                                      </div>

                                                      <div class="col-md-3">

                                                        <div class="form-group">
                                                          <div class="input-group">

                                                            <div class="input-group-append <?= strtolower($FormData); ?>_manual">
                                                              <span class="input-group-text"><?= INDIAN_SYMBOL; ?></span>
                                                            </div>

                                                            <input type="number" name="<?= strtolower($FormData); ?>_value" oninput="setBasicCTC();" min="0" class="form-control" id="<?= strtolower($FormData); ?>_value" placeholder="0">
                                                            <div class="input-group-append <?= strtolower($FormData); ?>_percent d-none">
                                                              <span class="input-group-text">%</span>
                                                            </div>
                                                          </div>
                                                        </div>

                                                      </div>

                                                      <div class="col-md-4">
                                                        <div class="form-group">
                                                          <div class="input-group">
                                                            <div class="input-group-append">
                                                              <span class="input-group-text"><?= INDIAN_SYMBOL; ?></span>
                                                            </div>
                                                            <input type="number" name="<?= strtolower($FormData); ?>_amount" readonly="" min="0" class="form-control " id="allowance_value" placeholder="0">
                                                          </div>

                                                        </div>
                                                      </div>
                                                    </div>
                                                    <!-- 1 DA ALLOWANCE CLOSE -->

                                                  <?php } ?>

                                                </div>
                                              </div>
                                            </div>
                                            <div class="card">
                                              <div class="card-header collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                <span class="title">Deduction </span>
                                                <span class="accicon"><i class="fas fa-angle-down rotate-icon"></i></span>
                                              </div>
                                              <div id="collapseTwo" class="collapse" data-parent="#accordionExample">
                                                <div class="card-body cardbody_deduction">

                                                  <?php
                                                  $deductionForm = array('PF', 'ESI', 'Other');
                                                  foreach ($deductionForm as $key => $FormData) {
                                                  ?>

                                                    <!-- ********************************* -->
                                                    <!-- 1 DA ALLOWANCE START -->
                                                    <div class="row">
                                                      <div class="col-md-5">
                                                        <div class="form-group">
                                                          <div class="input-group">
                                                            <input type="text" class="form-control inp_allowance" readonly="" value="<?= $FormData; ?>" name="allowance[]">
                                                            <div class="input-group-append">
                                                              <select name="<?= strtolower($FormData); ?>_type" class="bg-light" onchange="setBasicCTC();">
                                                                <option value="Manual">Manual</option>
                                                                <option value="%">%</option>
                                                              </select>
                                                            </div>
                                                          </div>
                                                        </div>
                                                      </div>

                                                      <div class="col-md-3">

                                                        <div class="form-group">
                                                          <div class="input-group">

                                                            <div class="input-group-append <?= strtolower($FormData); ?>_manual">
                                                              <span class="input-group-text"><?= INDIAN_SYMBOL; ?></span>
                                                            </div>

                                                            <input type="number" name="<?= strtolower($FormData); ?>_value" oninput="setBasicCTC();" min="0" step="0.01" class="form-control" id="<?= strtolower($FormData); ?>_value" placeholder="0">
                                                            <div class="input-group-append <?= strtolower($FormData); ?>_percent d-none">
                                                              <span class="input-group-text">%</span>
                                                            </div>
                                                          </div>
                                                        </div>

                                                      </div>

                                                      <div class="col-md-4">
                                                        <div class="form-group">
                                                          <div class="input-group">
                                                            <div class="input-group-append">
                                                              <span class="input-group-text"><?= INDIAN_SYMBOL; ?></span>
                                                            </div>
                                                            <input type="number" name="<?= strtolower($FormData); ?>_amount" readonly="" min="0" class="form-control read_amount" id="allowance_value" placeholder="0">
                                                          </div>

                                                        </div>
                                                      </div>
                                                    </div>
                                                    <!-- 1 DA ALLOWANCE CLOSE -->

                                                  <?php } ?>

                                                </div>
                                              </div>
                                            </div>

                                            <input type="submit" class="d-none" name="saveBtnCtcForm">
                                          </div>
                                        </form>
                                      </div>
                                      <!-- CLOSE CTC -->

                                      <div class="tab-pane fade   rounded bg-white nvtbbox data-1  data-2  data-3  data-4  data-5 data-6 data-7" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">

                                        <div class="row">
                                          <div class="col-md-12">

                                            <!-- <label class="float-right">Total Amount: <span id="totalAmount"></span></label> -->

                                            <table class="table" id="payrolListtableID">
                                              <thead>
                                                <tr>
                                                  <th>SrNo.</th>
                                                  <th>Type</th>
                                                  <th>Amount</th>
                                                  <th>Date</th>
                                                  <th>Remark</th>
                                                </tr>
                                              </thead>
                                              <tbody>
                                                <tr>

                                                </tr>
                                              </tbody>

                                            </table>

                                          </div>

                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </section>
                          </div>

                        </div>
                        <!-- MODAL  BODY CLOSE -->
                      </div>
                      <!-- <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" id="sallerySaveFormBtn" onclick="saveForm('ctcForm');" class="btn btn-primary">Save changes</button>
                        <button type="button" class="btn btn-primary d-none" id="addDeductModlbtn" onclick="clickAddDeductForm();addDeductBtn('type_addition');" data-toggle="modal" data-target="#addDeductModl">Add Or Deduct </button>



                      </div> -->
                    </div>
                  </div>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="settleAmountModal" tabindex="-1" role="dialog" aria-labelledby="settleAmountModalLabel" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="settleAmountModalLabel">Settle</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <form id="settleAmountForm" action="<?= base_url('User/settleAmount'); ?>" method="POST">
                      <div class="modal-body">
                        <div class="row">
                          <div class="col-sm-2">
                            <label for="settleAmountOption">Amount</label>
                            <h6 id="setttleTotalAmount">0</h6>
                            <input type="hidden" name="user_id">
                            <input type="hidden" name="deduct_id">
                            <input type="hidden" name="maxcount">
                          </div>
                          <div class="col-sm-4">
                            <div class="form-group">
                              <label for="settleAmountOption">Number of Months</label>
                              <input class="form-control" type="number" name="settleAmountOption" id="settleAmountOption" min="1" value="1" oninput="settleAmountChange();" required/>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                        <table class="table" id="settleAmountTable">
                          <thead>
                            <tr>
                              <th>SrNo.</th>
                              <th>Amount</th>
                              <th>Date</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                            </tr>
                          </tbody>
                        </table>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" onclick="calculateSalaryAmount()" class="btn btn-primary">Save changes</button>
                      </div>
                    </form>
                    </div>
                  </div>
                </div>

                <!-- END START  -->
                <!-- SALLARY ADDICTIO   -->
                <!-- SALLARY ADDICTIO   -->
                <!-- MODAL START  -->
                <!-- Modal -->
                <div class="modal fade" data-backdrop="static" data-keyboard="false" id="addDeductModl" tabindex="-1" role="dialog" aria-labelledby="addDeductModlLabel" aria-hidden="true">
                  <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="addDeductModlLabel">Add Sallary Amount</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>


                      <form action="<?= base_url('Payroll/addDeductAmount'); ?>" id="payrol_pay_deductform" method="POST">
                        <div class="modal-body">
                          <!-- MODAL  BODY START -->
                          <div class="col-md-12">

                            <div class="row">
                              <div class="col-md-4">
                                <div class="form-group">
                                  <label for="exampleInputEmail1">Payroll </label>
                                  <select name="payroll_master_id" class="form-control" required="">
                                    <option value="">Select payrol</option>
                                    <!-- <option class="type_addition" value="0" data-type="3">Paid</option> -->
                                    <?php 
                                      foreach ($payrollList as $key => $payrltData) { ?>
                                          <option class="" value="<?= $payrltData['id']; ?>" data-type="<?= $payrltData['type']; ?>"><?= $payrltData['name']; ?></option>
                                    <?php } ?>
                                  </select>

                                  <input type="hidden" name="add_deduct_user_id">
                                  <input type="hidden" name="selectDate" id="selectDate" value="<?= $month; ?>" >
                                </div>
                              </div>

                              <div class="col-md-4">
                                <div class="form-group">
                                  <label for="exampleInputEmail1">Date</label>
                                  <input type="date" name="date" class="form-control" max="<?php echo date('Y-m-t'); ?>" value="<?php echo date('Y-m-d'); ?>" required="">
                                </div>
                              </div>

                              <div class="col-md-4">
                                <div class="form-group">
                                  <label for="exampleInputEmail1">Amount</label>
                                  <input type="number" min="0" name="amount" class="form-control" required="" placeholder="0">
                                </div>
                              </div>

                            </div>

                            <div class="row">
                              <div class="col-md-12">
                                <div class="form-group">
                                  <label for="exampleInputEmail1">Notes</label>
                                  <textarea class="form-control" rows="6" name="note"></textarea>
                                </div>
                              </div>
                            </div>

                          </div>
                          <!-- MODAL  BODY CLOSE -->
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                          <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                      </form>

                    </div>
                  </div>
                </div>
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

<script src="<?php echo base_url('adminassets/plugins/jquery/jquery.min.js') ?>"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script type="text/javascript">
  function calculateTotalAmount() {

    var basic_value = $("input[name='basic_value']").val();
    basic_value = basic_value ? parseFloat(basic_value) : 0;

    var totalAmount = 0;
    var deductionForm = ['PF', 'ESI', 'Other'];
    var totalDeduction = 0;
    deductionForm.forEach(element => {
      var name = element.toLowerCase();
      var amount = $("input[name='" + name + "_amount']").val();
      amount = amount ? parseFloat(amount) : 0;
      totalDeduction = totalDeduction + amount;
    });

    // alert(totalDeduction);

    var allColumnArray = ['DA', 'HRA', 'MEAL', 'CONVEYANCE', 'MEDICAL', 'SPECIAL', 'TA'];
    var totalaaddition = 0;
    allColumnArray.forEach(element => {
      var name = element.toLowerCase();
      var amount = $("input[name='" + name + "_amount']").val();
      amount = amount ? parseFloat(amount) : 0;
      totalaaddition = totalaaddition + amount;
    });

    totalaaddition = totalaaddition + basic_value;

    // alert(totalaaddition);

    totalAmount = totalaaddition - totalDeduction;
    // alert(totalAmount);
    $("#totalamountctc").text(totalAmount.toFixed(2));
    $("input[name='input_total_ctc_amount']").val(totalAmount.toFixed(2));

  }


  $(document).ready(function() {


    $("#ctcForm").submit(function(e) {
      e.preventDefault();
      var formData = new FormData(this);
      $.ajax({
        type: 'POST',
        url: $(this).attr('action'),
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function() {
          $('button').prop('disabled', true);
        },
        success: function(result) {
          var result = JSON.parse(result);
          if (result.status > 0) {
            $('#ctcForm')[0].reset();
            swal("Success ", result.message, "success");
            setTimeout(function() {
              $('button').prop('disabled', false);
              $('#salleryModal').modal('hide');
            }, 2500);

          } else {
            swal("Faild ", result.message, "error");
            setTimeout(function() {
              $('button').prop('disabled', false);
            }, 2500);
          }

        }
      });
    });


    /* PAYROLL ADD OR DEDUCT  */
    $("#payrol_pay_deductform").submit(function(e) {
      e.preventDefault();
      var formData = new FormData(this);
      $.ajax({
        type: 'POST',
        url: $(this).attr('action'),
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function() {
          $('button').prop('disabled', true);
        },
        success: function(result) {
          var result = JSON.parse(result);
          if (result.status > 0) {
            $('#payrol_pay_deductform')[0].reset();
            swal("Success ", result.message, "success");
            setTimeout(function() {
              $('button').prop('disabled', false);
              $('#addDeductModl').modal('hide');
              // window.location.reload();
            }, 2500);

          } else {
            swal("Faild ", result.message, "error");
            setTimeout(function() {
              $('button').prop('disabled', false);
            }, 2500);
          }

        }
      });
    });



    $(document).on('click', '[data-dismiss="modal"]', function() {
      window.location.reload();
    });

  });


  function clickAddDeductForm(id) {
    var select_user_id = id;
    setModalUserID(id);
    $('#salleryModal').modal('hide');
  }


  function saveForm(formID) {
    $("input[name='saveBtnCtcForm']").click();
  }



  function navTabs(key, payrolID) {
    $('.nvtbbox').removeClass('show active');
    $('.data-' + key).addClass('show active');

    if (key > 0) {

      $("#sallerySaveFormBtn").addClass('d-none');
      $("#addDeductModlbtn").removeClass('d-none');

      var select_user_id = $("input[name='select_user_id']").val();
      var url = "<?= base_url('Payroll/payrolHidtory'); ?>";
      $.ajax({
        type: 'POST',
        url: url,
        data: {
          "payrolID": payrolID,
          "user_id": select_user_id
        },
        // cache: false,
        // contentType: false,
        // processData: false,
        beforeSend: function() {
          // $('button').prop('disabled', true);
          $("#payrolListtableID tbody").html('<tr><th colspan="4"><p class="text-center p-3">Loading...</p></th></tr>');

        },
        success: function(result) {
          var result = JSON.parse(result);
          $("#payrolListtableID tbody").html(result.list);
          $("#totalAmount").text(result.totalAmount);
        }
      });
    } else {
      $("#sallerySaveFormBtn").removeClass('d-none');
      $("#addDeductModlbtn").addClass('d-none');
    }

  }

  function setModalUserID(userid) {
    $("input[name='select_user_id']").val(userid);
    $("input[name='add_deduct_user_id']").val(userid);

    document.getElementById('ctcForm').reset();
    // getCurrentCtcDetails(user_id);
    navTabs(1,2)
  }



  function setBasicCTC() {
    $('.inp_allowance').each(function() {
      var name = $(this).attr('value');
      setBasicCTC1(name.toLowerCase());
    });
  }


  function setBasicCTC1(name) {

    var basic = $("select[name='basic'] option:selected").val();
    var basic_value = $("input[name='basic_value']").val();
    basic_value = basic_value ? parseFloat(basic_value) : 0;


    if (basic == 'Monthly') {

      // HIDE SHOW RUPEE AND PERCENT  ACORDING BASIC TYPE
      var da_type = $("select[name='" + name + "_type'] option:selected").val();

      var da_value = $("input[name='" + name + "_value']").val();
      da_value = da_value ? parseFloat(da_value) : 0;

      if (da_type == 'Manual') {
        $("." + name + "_manual").removeClass('d-none');
        $("." + name + "_percent").addClass('d-none');
        // basic_value       = basic_value+da_value;
        basic_value = da_value;
      } else {
        $("." + name + "_percent").removeClass('d-none');
        $("." + name + "_manual").addClass('d-none');

        var percent = basic_value * da_value / 100;
        // basic_value       = basic_value+percent;
        basic_value = percent;
      }

      $("input[name='" + name + "_amount']").val(basic_value);

      calculateTotalAmount();

    } else {
      $("input[name='" + name + "_amount']").val('0');
      $("input[name='" + name + "_value']").val('0');
    }

  }



  function getCurrentCtcDetails(userID) {

    $.ajax({
      type: "POST",
      url: "<?= base_url('User/getCurrentCtcDetails'); ?>",
      data: {
        "userID": userID,
        "date_from":"<?php if (isset($date_from)) {echo $date_from;} else if (isset($empData->startDate)) {echo date("Y-m", $empData->startDate);} ?>"
      },
      dataType: "json",
      success: function(result) {
        if (result.status > 0) {

          $("select[name='basic'] option[value='" + result.details.basic + "']").attr('selected', 'selected');
          $("input[name='basic_value']").val(result.details.basic_value);
          $("input[name='input_total_ctc_amount']").val(result.details.total_ctc_amount);
          $("#totalamountctc").text(result.details.total_ctc_amount);


          $(".cardbody_allowance").html(result.allowance);
          $(".cardbody_deduction").html(result.deduction);

        }

      }
    });

  }



  function checkDate() {
    var date_from = $("input[name='date_from']").val();
    var date_to = $("input[name='date_to']").val();

    if (date_to == '') {
      $("input[name='date_to']").val(date_from);
    }
    if (date_from == '') {
      $("input[name='date_from']").val(date_to);
    }
    setCheckDate();
  }

  function setCheckDate() {
    var date_from = $("input[name='date_from']").val();
    $("input[name='date_to']").attr('min', date_from);
  }

  function addDeductBtn(type) {

    if (type == 'type_addition') {
      $("select[name='payroll_master_id'] .type_deduct").addClass('d-none');
      $("select[name='payroll_master_id'] .type_addition").removeClass('d-none');
      $("#addDeductModlLabel").text('Earnings');
    } else {
      $("select[name='payroll_master_id'] .type_addition").addClass('d-none');
      $("select[name='payroll_master_id'] .type_deduct").removeClass('d-none');
      $("#addDeductModlLabel").text('Deduct Sallery Amount');
    }

  }

  function setSettleModalAmount(user_id,amount,payrolId) {
    $("input[name='user_id']").val(user_id);
    $("input[name='maxcount']").val(0);
    $("input[name='deduct_id']").val(payrolId);
    $("#setttleTotalAmount").text(amount);
    var deductAmount = amount/1;
    var date = new Date();
    $.ajax({
      type: "POST",
      url: "<?= base_url('User/getCurrentCtcDetails'); ?>",
      data: {
        "userID": user_id,
        "date_from":"<?php if (isset($date_from)) {echo $date_from;} else if (isset($empData->startDate)) {echo date("Y-m", $empData->startDate);} ?>"
      },
      dataType: "json",
      success: function(result) {
        if (result.status > 0) {
          var deductData = "";
          var count = 1;
          while(amount>0){
            if(amount>deductAmount){
              amount = amount - deductAmount;
            }else{
              deductAmount = amount;
              amount = 0;
            }
            date.setMonth(date.getMonth()+1);
            var month = date.getMonth()+1;
            if(month<10){
              month="0"+month;
            }
            var settleDate = date.getFullYear()+"-"+(month)+"-0"+1;
            deductData = deductData + "<tr><td>"+count+"</td><td><input class='form-control settleAmountInput' type='number' name='settleAmount"+count+"' value='"+deductAmount+"' min='1'/></td><td><input class='form-control' type='date' name='settleDate"+count+"' value='"+settleDate+"'/></td></tr>";
            count++;
          }
          $("input[name='maxcount']").val(count-1);
          $("#settleAmountTable tbody").html(deductData);
        }
      }
    });
  }

  function editSettleModalAmount(user_id,amount,payrolId) {
    $("input[name='user_id']").val(user_id);
    $("input[name='maxcount']").val(0);
    $("input[name='deduct_id']").val(payrolId);
    $("#setttleTotalAmount").text(amount);
    var deductAmount = amount/1;
    var date = new Date();
    $.ajax({
      type: "POST",
      url: "<?= base_url('User/getPayrollHistory'); ?>",
      data: {
        "payrolID": payrolId,
        "userID": user_id,
        "date_from":"<?php if (isset($date_from)) {echo $date_from;} else if (isset($empData->startDate)) {echo date("Y-m", $empData->startDate);} ?>"
      },
      dataType: "json",
      success: function(result) {
        if (result.status > 0) {
          var deductData = "";
          var count = 1;
          for (let i = 0; i < result.list.length; i++) {
            deductData = deductData + "<tr><td>"+count+"</td><td><input class='form-control settleAmountInput' type='number' name='settleAmount"+count+"' value='"+result.list[i].amount+"' min='1'/></td><td><input class='form-control' type='date' name='settleDate"+count+"' value='"+result.list[i].pay_date+"'/></td></tr>";
            count++;
          }
          $("input[name='maxcount']").val(count-1);
          $("#settleAmountTable tbody").html(deductData);
        }
      }
    });
  }
  
  function settleAmountChange() {
    var amount = $("#setttleTotalAmount").text();
    var maxMonths = $('#settleAmountOption').val();
    var deductAmount = Math.round(amount/maxMonths);
    var user_id = $("input[name='user_id']").val();
    console.log(user_id);
    var date = new Date();
    $.ajax({
      type: "POST",
      url: "<?= base_url('User/getCurrentCtcDetails'); ?>",
      data: {
        "userID": user_id,
        "date_from":"<?php if (isset($date_from)) {echo $date_from;} else if (isset($empData->startDate)) {echo date("Y-m", $empData->startDate);} ?>"
      },
      dataType: "json",
      success: function(result) {
        if (result.status > 0) {
          var deductData = "";
          var count = 1;
          while(amount>0){
            if(count==maxMonths){
              deductAmount = amount;
              amount = 0;
            }else{
              if(amount>deductAmount){
              amount = amount - deductAmount;
              }else{
                deductAmount = amount;
                amount = 0;
              }
            }
            
            date.setMonth(date.getMonth()+1);
            var month = date.getMonth()+1;
            if(month<10){
              month="0"+month;
            }
            var settleDate = date.getFullYear()+"-"+(month)+"-0"+1;
            deductData = deductData + "<tr><td>"+count+"</td><td><input class='form-control settleAmountInput' type='number' name='settleAmount"+count+"' value='"+deductAmount+"' min='1'/></td><td><input class='form-control' type='date' name='settleDate"+count+"' value='"+settleDate+"'/></td></tr>";
            count++;
          }
          $("input[name='maxcount']").val(count-1);
          $("#settleAmountTable tbody").html(deductData);
        }
      }
    });
  }

  function calculateSalaryAmount(){
    var settleAmountInput = 0;
    $(".settleAmountInput").each(function(){
      settleAmountInput+=parseInt($(this).val());
    });
    var amount = parseInt($("#setttleTotalAmount").text());
    if(amount==settleAmountInput){
      $("#settleAmountForm").submit(function(e) {
      e.preventDefault();
      var formData = new FormData(this);
      $.ajax({
        type: 'POST',
        url: $(this).attr('action'),
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function() {
          $('button').prop('disabled', true);
        },
        success: function(result) {
          var result = JSON.parse(result);
          if (result.status > 0) {
            //$('#payrol_pay_deductform')[0].reset();
            swal("Success ", result.message, "success");
            setTimeout(function() {
              $('button').prop('disabled', false);
              $('#settleAmountModal').modal('hide');
              window.location.reload();
            }, 2500);

          } else {
            swal("Faild ", result.message, "error");
            setTimeout(function() {
              $('button').prop('disabled', false);
            }, 2500);
          }

        }
      });
    });
    $("#settleAmountForm").submit();
    }else{
      alert("Settle Amount must be equal to Advance Amount");
    }
    
  }

  function setSalaryChanged($num){
    $("input[name='salary_changed_"+$num+"']").val(1);
  }

  function setDate() {

    var date_from = $("#setDate").val();
        $.ajax({
            type: "get",
            url: "<?php base_url('Payroll/employeesSalary'); ?>",
            data: {'date_from': date_from},
            success: function (data) {
                window.location.href = "<?php base_url('Payroll/earnings'); ?>?getDate="+date_from;
            }
    });
  }
</script>