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
            <li class="breadcrumb-item active">Earning List</li>
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
   <!-- Main content -->
    <section class="content">
        
      <?php
      if($this->session->userdata()['type']=='B' || $role[0]->salary=="1" || $role[0]->type=="1"){?>  
        
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Edit Earning</h3><br>
                <span style="color: red"><?php echo $this->session->flashdata('msg');?></span>
              </div>
            
              
              
              <form action="<?php echo base_url('Payroll/earningUpdate')?>" method="post">
              <div class="card-body">
                <div class="row">
                  <div class="from-group col-md-4">
                 
                    <label for="name">Payroll</label>
                    <select name="payroll_master_id" class="form-control" required="">
                        <option value="">Select payrol</option>
                        <?php 
                          foreach ($payrollList as $key => $payrltData) { ?>
                              <option value="<?= $payrltData['id']; ?>" <?php echo $earning->payroll_master_id==$payrltData['id'] ? "selected" : ''; ?>>
                                <?= $payrltData['name']; ?>
                              </option>
                        <?php } ?>
                      </select>
                  </div>
 
                <div class="from-group col-md-4">
                    <label for="mobile">Date</label>
                    <input type="date" class="form-control" name="pay_date" value="<?php echo $earning->pay_date; ?>" id="pay_date">
                  </div>
                  
                  <div class="from-group col-md-4">
                    <label for="amount">Amount</label>
                    <input type="number" class="form-control" name="amount" value="<?php echo $earning->amount; ?>" id="amount" >
                  </div>
                   <div class="from-group col-md-12 mt-4">
                    <label for="phone">Notes </label>
                    <textarea class="form-control" name="remarks" id="remarks"><?php echo $earning->remarks; ?></textarea>
                  </div>
                  <div class="from-group col-md-5">
                  <input type="hidden" name="id" id="id" value="<?php echo $earning->id; ?>">
                  <input type="hidden" name="user_id" id="user_id" value="<?php echo $earning->user_id; ?>">
                   <button  class=" btn btn-success mt-4 mx-auto" >Update Now</button>
                  
                 
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
