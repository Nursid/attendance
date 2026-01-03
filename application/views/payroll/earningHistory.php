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
  if ($this->session->userdata()['type'] == 'B'  || $this->session->userdata()['type']=='P') {
   if($this->session->userdata()['type']=='P'){
      
      $bid = $this->session->userdata('empCompany');
      $role=$this->web->getRollbyid($this->web->session->userdata('login_id'),$bid);
  
    } else {
      $bid=$this->web->session->userdata('login_id');
    }
  ?>
    <!-- Main content -->
    <section class="content">
        <?php
      if($this->session->userdata()['type']=='B' || $role[0]->salary=="1" || $role[0]->type=="1"){?>
      <div class="container-fluid">


        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <div class="card card-primary">

              <div class="card-header">
                <h3 class="card-title"><?=$title?> <?php if (isset($salEmpList[0]->startDate)) { ?>From: <?php echo date("d-M-Y", $salEmpList[0]->startDate); ?> To: <?php echo date("d-M-Y", $salEmpList[0]->endDate);
                                                                                                                                                                      } ?></h3>
              </div>

              <div class="card-body">
                <div class="row">
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
                </div>
                <table id="salaryReport" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>S.No</th>
                      <th>Date</th>
                      <th>Type</th>
                      <th>Amount</th>
                      <th>Note</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>

                    <?php
                    if (!empty($list)) {
                      $sr = 1;
                      foreach ($list as $key => $empData) { ?>

                        <tr>
                          <td><?= $sr; ?></td>
                          <td><?= Date("d-m-Y",strtotime($empData->pay_date)); ?></td>
                          <td><?= $empData->name; ?></td>
                          <td><?= $empData->amount; ?></td>
                          <td><?= $empData->remarks; ?></td>
                          <td>
                            <a href="<?php echo base_url('Payroll/earningEdit/').$empData->id; ?>" class="btn btn-xs mt-1 btn-primary"> 
                              <i class="fa fa-life-ring"></i> Edit</a>
                            <a href="<?php echo base_url('Payroll/earningDelete?id=').$empData->id.'&uid='.$_GET['id']; ?>" class="btn btn-xs mt-1 btn-primary"
                              onclick="return confirm('Are you sure you want to delete this item?');">
                               <i class="fa fa-trash"></i> Delete
                            </a>

                          </td>
                        </tr>
                    <?php $sr++;
                      }
                    }  ?>
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
