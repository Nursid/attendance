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
      <div class="row">
        <div class="col-sm-12">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Net Salary</h3>
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item">Employee List</li>
              </ol>
            </div>
          </div>
        </div>
      </div>
    </div><!-- /.container-fluid -->
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
  if($this->session->userdata()['type']=='B' || $role[0]->salary=="1" || $role[0]->type=="1"){
    $month = isset($_GET['getDate']) ? $_GET['getDate'] : date("Y-m");
  ?>
    <!-- Main content -->
      <div class="container-fluid">


        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <div class="card card-primary">
              <div class="card-body">
                <div class="row">
                 <div class="col-sm-9">
                    <form action="<?php echo base_url('User/salaryEmployees') ?>" method="post">
                      <div class="row mb-4">
                        <div class="col-sm-3">
                          <input type="month" title="Start Date" placeholder="Date From" value="<?php echo $month; ?>" onchange="setDate()" id="getDate" class="form-control">
                        </div>
                        <div class="col-sm-3">
                          <!-- <input type="submit" class="btn btn-primary" name="filter_btn" value="Show" /> -->
                          <?php if (isset($_POST['filter_btn'])) { ?>
                            <a href="" class="btn btn btn-danger fa fa-times-circle"></a>
                          <?php } ?>
                        </div>
                        <?php 
                          $totalPaid = 0;
                          $totalNetPayable = 0;
                          foreach ($salEmpList as $item) {
                              $totalPaid += $item->getTotalPaid;
                              $totalNetPayable += $item->netPayable;
                          }
                        ?>
                     <!---  <div class="col-sm-6">
                          <div class="row">
                            <div class="col-sm-6"> <strong>Total Paid: <?= INDIAN_SYMBOL; ?></strong> <?= $totalPaid; ?> </div>
                            <div class="col-sm-6"> <strong>Total Net Payable: <?= INDIAN_SYMBOL; ?></strong> <?= $totalNetPayable; ?> </div>
                          </div>
                        </div> -->

                      </div>
                    </form>
                  </div>
                 
                  <div class="col-sm-2">
                    <form action="<?php echo base_url('Payroll/salaryReport') ?>" method="post">
                      <input type="month" title="Start Date" min="2020" max="2025" placeholder="Date From" onchange="checkDate()" value="<?php echo isset($_GET['getDate']) ? $_GET['getDate'] : date("Y-m") ?>" class="form-control" name="date_from" dd1="date_from" required id="txtFrom" hidden>
                      <input type="submit" class="btn btn-primary" name="filter_btn" value="Recalculate Attendance" />
                    </form>
                  </div>
                  <div class="col-sm-1">
                   <!-- <div align="right">
                      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#allCtcModal">All CTC</button>
                    </div>-->
                  </div>
                </div>
                  
                  <!-- <div align="right">
                    <input type="button" onClick="exportData()" value="Export To Excel" />
                    <input type="button"  id="btnExport" value="Export To Pdf" onclick="exportPDF()" />
                    <br>
                  </div> -->
                  <table id="newsalaryReport" class="table table-bordered table-striped table-responsive">
                    <thead>
                      <tr>
                        <th>S.No</th>
                        <th>EmpCode</th>
                        <th>Name</th>
                        <th>CTC</th>
                        <th>NWD</th>
                        <th>Salary</th>
                        <th>PF</th>
                        <th>ESI</th>
                        <th>TDS</th>
                        <th>Advance</th>
                        <th>Earnings</th>
                        <th>Deduction</th>
                        <th>NetPayable</th>
                         <th>Pay Mode</th>
                          <th>A/C Detail</th>
                        <th class="notPrintable">Action</th>
                      </tr>
                    </thead>
                    <tbody>

                      <?php
                      $this->db->order_by("id", "asc");
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
                         // $this->db->order_by('id','DESC');
                          $month = isset($_GET['getDate']) ? $_GET['getDate'] : date("Y-m");
                           $salary_month = $this->db->select('DATE_FORMAT(date, "%Y-%m") as added_date')->from('salary')->where(['uid'=>$empData->emp_id])->where("DATE_FORMAT(salary.date,'%Y-%m') <=",$month)->order_by("date", "asc")->get()->result_array();
                          if($salary_month){
                              if(!in_array($month,array_column($salary_month, 'added_date'))){
                                if(current(array_column($salary_month, 'added_date')) < $month){
                                  unset($month);
                                  $month = @end(array_column($salary_month, 'added_date'));
                                }
                              }
                          }
                       $salary = $this->db->get_where('salary',['uid'=>$empData->emp_id,"DATE_FORMAT(salary.date,'%Y-%m')"=>$month])->row();
                       $emp_more_details  = $this->db->get_where('staff_detail',['uid'=>$empData->emp_id])->row();
                       
                          $number_of_days = cal_days_in_month(CAL_GREGORIAN,date('m',strtotime(isset($_GET['getDate']) ? $_GET['getDate'] : date("Y-m"))),date('Y',strtotime(isset($_GET['getDate']) ? $_GET['getDate'] : date("Y-m"))));

                        //  $salary = $this->db->get_where('salary',['uid'=>$empData->emp_id,"DATE_FORMAT(salary.date,'%Y-%m')"=>$month])->row();
                          $allowance = $this->db->select('SUM(salary_basic.amount) as total')
                                            ->from('salary_basic')
                                            ->join('ctc_head','ctc_head.id=salary_basic.header_id','left')
                                            ->join('salary','salary.id=salary_basic.sid','left')
                                            ->where(['salary.uid'=>$empData->emp_id])
                                            ->where(['ctc_head.type'=>'Allowance'])
                                            ->where("DATE_FORMAT(salary.date,'%Y-%m')", $month)
                                            ->get()
                                            ->row();
                          // $deduction = $this->db->select('SUM(salary_basic.amount) as total,ctc_head.name,salary_basic.amount')
                          $deduction = $this->db->select('SUM(salary_basic.amount) as total')
                                            ->from('salary_basic')
                                            ->join('ctc_head','ctc_head.id=salary_basic.header_id','left')
                                            ->join('salary','salary.id=salary_basic.sid','left')
                                            ->where(['salary.uid'=>$empData->emp_id])
                                            ->where(['ctc_head.type'=>'Deduction'])
                                            ->where(['ctc_head.name !='=>'TDS'])
                                            ->where("DATE_FORMAT(salary.date,'%Y-%m')", $month)
                                            ->get()
                                            ->row();
                          $pf = $this->db->select('salary_basic.amount,salary_basic.header_type,salary_basic.header_value')
                                            ->from('salary_basic')
                                            ->join('ctc_head','ctc_head.id=salary_basic.header_id','left')
                                            ->join('salary','salary.id=salary_basic.sid','left')
                                            ->where(['salary.uid'=>$empData->emp_id])
                                            ->where(['ctc_head.type'=>'Deduction'])
                                            ->where(['ctc_head.name'=>'PF'])
                                            ->where("DATE_FORMAT(salary.date,'%Y-%m')", $month)
                                            ->get()
                                            ->row();
                          $esi = $this->db->select('salary_basic.amount,salary_basic.header_type,salary_basic.header_value')
                                            ->from('salary_basic')
                                            ->join('ctc_head','ctc_head.id=salary_basic.header_id','left')
                                            ->join('salary','salary.id=salary_basic.sid','left')
                                            ->where(['salary.uid'=>$empData->emp_id])
                                            ->where(['ctc_head.type'=>'Deduction'])
                                            ->where(['ctc_head.name'=>'ESI'])
                                            ->where("DATE_FORMAT(salary.date,'%Y-%m')", $month)
                                            ->get()
                                            ->row();
                          $tds = $this->db->select('salary_basic.amount,salary_basic.header_type,salary_basic.header_value')
                                            ->from('salary_basic')
                                            ->join('ctc_head','ctc_head.id=salary_basic.header_id','left')
                                            ->join('salary','salary.id=salary_basic.sid','left')
                                            ->where(['salary.uid'=>$empData->emp_id])
                                            ->where(['ctc_head.type'=>'Deduction'])
                                            ->where(['ctc_head.name'=>'TDS'])
                                            ->where("DATE_FORMAT(salary.date,'%Y-%m')", $month)
                                            ->get()
                                            ->row();
                            $earning = $this->db->select_sum('payroll_history.amount')
                                              ->from('payroll_history')
                                              ->join('payroll_master','payroll_master.id=payroll_history.payroll_master_id')
                                              ->where_in('payroll_master_id',[1,3,5,6])
                                              ->where("user_id",$empData->emp_id)
                                              ->where('payroll_history.status',1)
                                              ->where("DATE_FORMAT(pay_date,'%Y-%m')", isset($_GET['getDate']) ? $_GET['getDate'] : date("Y-m"))
                                              ->get()
                                              ->row();
                            $deduction_master = $this->db->select_sum('payroll_history.amount')
                                              ->from('payroll_history')
                                              ->join('payroll_master','payroll_master.id=payroll_history.payroll_master_id')
                                              ->where_in('payroll_master_id',[7,8])
                                              ->where("user_id",$empData->emp_id)
                                              ->where('payroll_history.status',1)
                                              ->where("DATE_FORMAT(payroll_history.pay_date,'%Y-%m')", isset($_GET['getDate']) ? $_GET['getDate'] : date("Y-m"))
                                              ->get()
                                              ->row();
                            $advance = $this->db->select_sum('payroll_history.amount')
                                              ->from('payroll_history')
                                              ->join('payroll_master','payroll_master.id=payroll_history.payroll_master_id')
                                              ->where_in('payroll_master_id',[2])
                                              ->where("payroll_id",0)
                                              ->where('payroll_history.status',1)
                                              ->where("user_id",$empData->emp_id)
                                              ->where("DATE_FORMAT(payroll_history.pay_date,'%Y-%m')", isset($_GET['getDate']) ? $_GET['getDate'] : date("Y-m"))
                                              ->get()
                                              ->row();
                            $imi = $this->db->select_sum('payroll_history.amount')
                                              ->from('payroll_history')
                                              ->join('payroll_master','payroll_master.id=payroll_history.payroll_master_id')
                                              ->where_in('payroll_master_id',[2])
                                              ->where("payroll_id !=",0)
                                              ->where('payroll_history.status',1)
                                              ->where("user_id",$empData->emp_id)
                                              ->where("DATE_FORMAT(payroll_history.pay_date,'%Y-%m') ", isset($_GET['getDate']) ? $_GET['getDate'] : date("Y-m"))
                                              ->get()
                                              ->row();
                             $oldimi = $this->db->select_sum('payroll_history.amount')
                                              ->from('payroll_history')
                                              ->join('payroll_master','payroll_master.id=payroll_history.payroll_master_id')
                                              ->where_in('payroll_master_id',[2])
                                              ->where("payroll_id !=",0)
                                              ->where('payroll_history.status',1)
                                              ->where("user_id",$empData->emp_id)
                                              ->where("DATE_FORMAT(payroll_history.pay_date,'%Y-%m') <", isset($_GET['getDate']) ? $_GET['getDate'] : date("Y-m"))
                                              ->get()
                                              ->row();                 
                            
                            $loan = $this->db->select_sum('payroll_history.amount')
                                              ->from('payroll_history')
                                              ->join('payroll_master','payroll_master.id=payroll_history.payroll_master_id')
                                              ->where_in('payroll_master_id',[4])
                                              ->where("user_id",$empData->emp_id)
                                              ->where('payroll_history.status',1)
                                              ->where("DATE_FORMAT(payroll_history.pay_date,'%Y-%m') <=", isset($_GET['getDate']) ? $_GET['getDate'] : date("Y-m"))
                                              ->get()
                                              ->row();

                            $total_salary = $salary ? round((($salary->basic_value+$deduction->total+$allowance->total)/$number_of_days)*$empData->nwd) : 0;

                            $total_pf = isset($pf->amount) ?  ($pf->header_type=="Manual" ? $pf->amount : round((((($salary->basic_value)/$number_of_days)*$empData->nwd)*$pf->header_value))/100) : 0;
                            $total_esi = isset($esi->amount) ?  ($esi->header_type=="Manual" ? $esi->amount : round((((($salary->basic_value)/$number_of_days)*$empData->nwd)*$esi->header_value))/100) : 0;
                            
                            $total_tds = isset($tds->amount) ?  ($tds->header_type=="Manual" ? $tds->amount : round((((($salary->basic_value)/$number_of_days)*$empData->nwd)*(!empty($tds->header_value) ? $tds->header_value : 0)))/100) : 0;
                            $earning_total = isset($earning->amount) ? $earning->amount : 0;
                            $deduction_total = isset($deduction_master->amount) ? $deduction_master->amount : 0;
                            $advance_total = isset($advance->amount) ? $advance->amount : 0;
                            $netPayable = ($total_salary+$earning_total)-($total_pf+$total_esi+$total_tds+$deduction_total+$empData->getTotalPaid);
                            $selectDate = isset($_GET['getDate']) ? $_GET['getDate'] : $month;

                            $loan_amount = isset($loan->amount) ? $loan->amount : 0;
                            $imi_amount = isset($imi->amount) ? $imi->amount : 0;
                            $oldimi_amount = isset($oldimi->amount) ? $oldimi->amount : 0;
                          ?>
                          <tr>
                            <td><?= $sr; ?><?php #print_r($advance); echo "<br />"; print_r($imi); ?> </td>
                            <td><?= $empData->emp_code; ?></td>
                            <td><?= $empData->empName; ?></td>
                            <td><?= isset($allowance->total) ? $salary->basic_value+$deduction->total+$allowance->total : 0; ?></td>
                            <td><?= $empData->nwd; ?></td>
                            <td><?= $total_salary; ?></td>
                            <td><?= $total_pf ?></td>
                            <td><?= $total_esi; ?></td>
                           <td><?= $total_tds; ?></td>
                            <td><?= $advance_total+$loan_amount-$oldimi_amount; ?></td>
                            <td><?= $earning_total; ?></td>
                            <td><?= $deduction_total+$advance_total+$imi_amount; ?></td>
                            <td><?= $netPayable-($imi_amount+$advance_total); ?></td>
                            <td > <?=$empData->pay_mode; ?> <br>
                            <input type="text" value="<?=$empData->pay_mode; ?>"  onchange="changeLeaveFrom2(event,'<?=$empData->id; ?>')" />
                        </td>
                              <td><?= isset($emp_more_details) ? $emp_more_details->bank_name :"" ;?> <br>
                              <?= isset($emp_more_details) ? $emp_more_details->account_no :"";?> <br>
                              <?= isset($emp_more_details) ? $emp_more_details->ifsc_code :"" ;?>
                              </td>
                            <td>
                              <a target="_blank" href="<?php echo base_url('Payroll/employeesPayslip/'); ?>?id=<?= $empData->emp_id."&date=".$month."&netPayable=".$netPayable."&selectDate=".$selectDate; ?>" class="btn btn-xs mt-1 btn-primary">Pay Slip</a>
                              <br>
                              <a href="#" class="btn btn-xs mt-1 btn-info" data-toggle="modal" data-target="#nwd<?php echo $empData->user_id; ?>"> <i class="fa fa-cog"></i>Working Days</a> <br>
                            </td>
                          </tr>
                          <!-- Modal -->
                          <div class="modal fade" id="nwd<?php echo $empData->user_id; ?>" tabindex="-1" role="dialog" aria-labelledby="nwd<?php echo $empData->user_id; ?>" aria-hidden="true">
                            <div class="modal-dialog modal-md" role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title" id="nwdLabel<?php echo $empData->user_id; ?>">Working Days</h5>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                                <form action="<?php echo base_url('Payroll/update_working_days') ?>" method="POST">
                                  <div class="modal-body">
                                    <div class="row text-center">
                                      <input type="text" value="<?php echo $empData->user_id; ?>" name="user_id" hidden>
                                      <input type="text" value="<?php if (isset($date_from)) {
                                                                  echo $date_from;
                                                                } else if (isset($empData->startDate)) {
                                                                  echo date("Y-m", $empData->startDate);
                                                                } ?>" name="date_from" hidden>
                                      <div class="col">
                                        <label for="cl">Present</label>
                                        <input type="number" name="wdPresent" id="wdPresent" value="<?= $empData->present; ?>" min="0" class="form-control">
                                      </div>
                                      <div class="col">
                                        <label for="el">P2</label>
                                        <input type="number" name="wdHalfDay" id="wdHalfDay" value="<?= $empData->half_day; ?>" min="0" class="form-control">
                                      </div>
                                      <div class="col">
                                        <label for="sl">WeekOff</label>
                                        <input type="number" name="wdWeekOff" id="wdWeekOff" value="<?= $empData->week_off; ?>" min="0" class="form-control">
                                      </div>
                                      <div class="col">
                                        <label for="pl">Holiday</label>
                                        <input type="number" name="wdHoliday" id="wdHoliday" value="<?= $empData->holiday; ?>" min="0" class="form-control">
                                      </div>
                                      <div class="col">
                                        <label for="other">Leaves</label>
                                        <input type="number" name="wdLeaves" id="wdLeaves" value="<?= $empData->leaves; ?>" min="0" step="0.5"  class="form-control">
                                      </div>
                                    <!--  <div class="col">
                                        <label for="other">SL</label>
                                        <input type="number" name="wdShortLeave" id="wdShortLeave" value="<?= $empData->short_leave; ?>" min="0" class="form-control">
                                      </div>-->
                                      <div class="col">
                                        <label for="other">ED</label>
                                        <input type="number" name="wdED" id="wdED" value="<?= $empData->ed; ?>" min="0" class="form-control">
                                      </div>
                                    </div>
                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-success">Save</button>
                                  </div>
                                </form>
                              </div>
                            </div>
                          </div>
                          <!-- Modal -->
                      <?php $sr++;
                        }
                      }  ?>
                    </tbody>

                  </table>
                <!-- MODAL START  -->
                <!-- Modal -->
               

                <!-- Modal -->
                

                <!-- END START  -->
                <!-- SALLARY ADDICTIO   -->
                <!-- SALLARY ADDICTIO   -->
                <!-- MODAL START  -->
                <!-- Modal -->
                
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

 
  

  function setDate() {

    var date_from = $("#getDate").val();
        $.ajax({
            type: "get",
            url: "<?php base_url('Payroll/employeesSalary'); ?>",
            data: {'date_from': date_from},
            success: function (data) {
                window.location.href = "<?php base_url('Payroll/employeesNetSalary'); ?>?getDate="+date_from;
            }
    });
  }
</script>
<script>
  function changeLeaveFrom2(e,id){
    $.ajax({
    type: "POST",
    url: "changeLeaveFmDate2",
    data: {id : id,from_date: e.target.value},
    success: function(){
      
    }
    });
  }
  </script>