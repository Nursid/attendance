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
    $fromDate = isset($_GET['fromDate']) ? $_GET['fromDate'] : date("Y-m-01");
    $toDate = isset($_GET['toDate']) ? $_GET['toDate'] : date("Y-m-t");
    $month = date('Y-m', strtotime($fromDate)); // Fallback for selectDate hidden field
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
                  <div class="col-sm-12">
                     <div class="row mb-3 align-items-end">
                        <div class="col-sm-3 col-md-2">
                           <label for="fromDate" class="form-label mb-1 text-muted text-sm"><small>From Date</small></label>
                           <input type="date" title="From Date" class="form-control form-control-sm" value="<?php echo $fromDate; ?>" id="fromDate">
                        </div>
                        <div class="col-sm-3 col-md-2">
                           <label for="toDate" class="form-label mb-1 text-muted text-sm"><small>To Date</small></label>
                           <input type="date" title="To Date" class="form-control form-control-sm" value="<?php echo $toDate; ?>" id="toDate">
                        </div>
                        <div class="col-sm-3 col-md-2">
                           <button class="btn btn-primary btn-sm mt-4 w-100" type="button" onclick="filterData()">
                               <i class="fas fa-filter"></i> Filter
                           </button>
                        </div>
                     </div>

                     <div class="row mb-4 align-items-center">
                        <div class="col-12 pb-3 border-bottom">
                           <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                              <div class="mb-2 mb-md-0">
                                 <span class="mr-2 font-weight-bold text-secondary"><i class="fas fa-list"></i> Select Payroll:</span>
                                 <?php foreach ($payrollList as $payroll) { ?>
                                    <button class="btn btn-sm btn-outline-primary payroll-btn mr-1 mt-1" onclick="selectPayroll(<?= $payroll['id'] ?>, '<?= $payroll['name'] ?>')">
                                       <?= $payroll['name'] ?>
                                    </button>
                                 <?php } ?>
                              </div>
                              <div class="d-flex mt-2 mt-md-0">
                                 <button class="btn btn-sm btn-success mr-2" onclick="exportPayroll()">
                                     <i class="fas fa-file-excel mr-1"></i> Export
                                 </button>
                                 <button class="btn btn-sm btn-info" onclick="$('#importPayrollExcel').click()">
                                     <i class="fas fa-upload mr-1"></i> Import
                                 </button>
                                 <input type="file" id="importPayrollExcel" style="display:none;" accept=".xls,.xlsx" onchange="uploadPayrollBulk(this)">
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                </div>
              
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
                                              ->where("pay_date >=", $fromDate)
                                              ->where("pay_date <=", $toDate)
                                              ->get()->row();
                        $earnings = $this->db->select_sum("amount")
                                              ->from("payroll_history")
                                              ->join('payroll_master','payroll_master.id=payroll_history.payroll_master_id')
                                              ->where_in('payroll_master_id',[1,3,5,6])
                                              ->where('payroll_history.status',1)
                                              ->where("user_id",$empData->emp_id)
                                              ->where("pay_date >=", $fromDate)
                                              ->where("pay_date <=", $toDate)
                                              ->get()->row();
                        $deduction = $this->db->select_sum("amount")
                                              ->from("payroll_history")
                                              ->join('payroll_master','payroll_master.id=payroll_history.payroll_master_id')
                                              ->where_in('payroll_master_id',[7,8])
                                              ->where('payroll_history.status',1)
                                              ->where("user_id",$empData->emp_id)
                                              ->where("pay_date >=", $fromDate)
                                              ->where("pay_date <=", $toDate)
                                              ->get()->row();
                        $paid = $this->db->select_sum("amount")
                                              ->from("payroll_history")
                                              ->join('payroll_master','payroll_master.id=payroll_history.payroll_master_id')
                                              ->where_in('payroll_master_id',[2])
                                              ->where('payroll_history.status',1)
                                              ->where("payroll_id",0)
                                              ->where("user_id",$empData->emp_id)
                                              ->where("pay_date >=", $fromDate)
                                              ->where("pay_date <=", $toDate)
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
                              
                          </td>
                        </tr>
                    <?php $sr++;
                      }
                    }  ?>
                  </tbody>

                </table>
              
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>


<script type="text/javascript">
  
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

let selectedPayrollId = '';
let selectedFrom = document.getElementById('fromDate').value;
let selectedTo = document.getElementById('toDate').value;

function filterData() {
  window.location.href = window.location.pathname + '?fromDate=' + $("#fromDate").val() + '&toDate=' + $("#toDate").val();
}

function selectPayroll(id, name) {
  selectedPayrollId = id;

  // Active UI
  document.querySelectorAll('.payroll-btn').forEach(btn => {
    btn.classList.remove('btn-primary');
    btn.classList.add('btn-outline-primary');
  });

  event.target.classList.remove('btn-outline-primary');
  event.target.classList.add('btn-primary');
}


// function exportPayroll(payrollId) {

// const selectedMonth = $("#setDate").val();
//   // ❌ Payroll not selected
//   if (!selectedPayrollId) {
//       alert("Please select payroll (Deduction / Loan / etc.)");
//       return;
//   }

//   // ❌ Month not selected
//   if (!selectedMonth) {
//       alert("Please select month");
//       return;
//   }


// $.when(
//     $.get("<?= base_url('User/getAllEmployeesForExport') ?>", {
//         date: selectedMonth
//     }),
//     $.get("<?= base_url('User/getActiveSalaryHeads') ?>")
// ).done(function(empRes, headRes) {

//     const employees = empRes[0].data || [];
//     const heads = headRes[0].data || [];

//     if (employees.length === 0) {
//         alert("No employee data found");
//         return;
//     }

//     // ✅ Create Header Row
//     let headerRow = [
//         "User ID",
//         "Employee Name",
//         "Mobile",
//         "Basic Salary"
//     ];

//     // Add dynamic salary heads
//     heads.forEach(h => {
//         headerRow.push(h.name);
//     });

//     headerRow.push("Date (YYYY-MM)");

//     const data = [headerRow];

//     // ✅ Create Employee Rows
//     employees.forEach(emp => {

//         let row = [
//             emp.user_id,
//             emp.empName,
//             emp.empMobile || "",
//             emp.basic_value || ""
//         ];

//         // 🔥 Insert dynamic head values properly
//         heads.forEach(h => {
//             if (emp.heads && emp.heads[h.name]) {
//                 row.push(emp.heads[h.name]);
//             } else {
//                 row.push("");
//             }
//         });

//         row.push(selectedMonth);

//         data.push(row);
//     });

//     // ✅ Generate Excel
//     const ws = XLSX.utils.aoa_to_sheet(data);
//     const wb = XLSX.utils.book_new();
//     XLSX.utils.book_append_sheet(wb, ws, "Salary Sample");

//     XLSX.writeFile(wb, `salary_sample_${selectedMonth}.xlsx`);

// }).fail(function() {
//     alert("Something went wrong");
// });

// }


function uploadPayrollBulk(input) {
    if (!input.files || input.files.length === 0) return;

    var file = input.files[0];
    var reader = new FileReader();

    reader.onload = function(e) {
        var data = new Uint8Array(e.target.result);
        var workbook = XLSX.read(data, {type: 'array'});
        
        var firstSheet = workbook.SheetNames[0];
        var worksheet = workbook.Sheets[firstSheet];
        var json = XLSX.utils.sheet_to_json(worksheet, {header: 1, defval: ""});

        if (json.length < 2) {
            swal("Error", "Excel file is empty or missing data rows.", "error");
            input.value = "";
            return;
        }

        $.ajax({
            url: "<?= base_url('Payroll/importBulkPayroll') ?>",
            type: "POST",
            data: JSON.stringify({ excelData: json }),
            processData: false,
            contentType: "application/json",
            dataType: "json",
            success: function(res) {
                if (res.status == 1) {
                    swal("Success", res.message, "success").then(() => {
                        window.location.reload();
                    });
                } else {
                    swal("Error", res.message, "error");
                }
                input.value = ""; // reset
            },
            error: function() {
                swal("Error", "Something went wrong during upload.", "error");
                input.value = ""; // reset
            }
        });
    };
    reader.readAsArrayBuffer(file);
}

function exportPayroll() {

const fromDate = $("#fromDate").val();
const toDate = $("#toDate").val();

if (!selectedPayrollId) {
    alert("Please select payroll");
    return;
}

if (!fromDate || !toDate) {
    alert("Please select both from and to dates");
    return;
}

$.get("<?= base_url('User/getPayrollDataForExport') ?>", {
    fromDate: fromDate,
    toDate: toDate,
    payroll_id: selectedPayrollId
}, function(res) {

    const employees = res.data || [];
    const headName = res.head;
    const dates = res.dates || [];

    let headerRow = [
        "User ID",
        "Employee Name",
        "Mobile"
    ];
    
    dates.forEach(date => {
        headerRow.push(`${headName} (${date})`);
    });

    const data = [headerRow];

    employees.forEach(emp => {
        let row = [
            emp.user_id,
            emp.empName,
            emp.empMobile || ""
        ];
        dates.forEach(date => {
            row.push(emp.dates ? (emp.dates[date] || 0) : 0);
        });
        data.push(row);
    });

    const ws = XLSX.utils.aoa_to_sheet(data);
    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, "Payroll Report");

    XLSX.writeFile(wb, `${headName}_${fromDate}_to_${toDate}.xlsx`);

}, 'json');
}
</script>