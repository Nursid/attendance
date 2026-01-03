<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
  </ul>


  <?php
  $image=$this->web->getBusinessById($this->session->userdata()['login_id'])['image'];
  $name=$this->web->getBusinessById($this->session->userdata()['login_id'])['name'];
  ?>
  <div class="center ml-auto">
    <h3><img src="<?php echo base_url($image)?>" class="brand-image img-squre" width="50px" height="50px"/> 
	<?php echo $name;?></h3>
  </div>
  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">
    <li class="nav-item">
      <a class="nav-link"  data-slide="true" href="<?php echo base_url('User/logout')?>"  role="button">Signout  <i class="fas fa-sign-out-alt"></i> </a>
    </li>
  </ul>
</nav>
<!-- /.navbar -->

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="<?php echo base_url('page_staff')?>" class="brand-link">
    <img src="<?php echo base_url('adminassets/dist/img/1709239494.jpg')?>" alt="AdminLTE Logo" class="brand-image img-squre elevation-3"
    style="opacity: .8">
    <span class="brand-text font-weight-light">MCS</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional)-->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <p><?php if($this->session->userdata()['type']=='B'){echo $res=$this->web->getBusinessById($this->session->userdata()['login_id'])['name'];
              } else{ print_r($this->session->userdata()['username']); } ?>
                <i class="fas fa-angle-left right"></i></p>
            </a>
           
          </li>
        </ul>
      </nav>
      <div> 
        <hr style="background-color: #f8f9fa;"/>
      </div>
      <!-- Sidebar Menu -->

      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
          with font-awesome or any other icon font library -->
          <li class="nav-item has-treeview menu-open">
            <a href="<?php echo base_url('page_staff')?>" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard

              </p>
            </a>
          </li>
            

          <?php
          if($this->session->userdata()['type']=='P'){
            ?>

        
         
         
         <li class="nav-item has-treeview">
            <a href="" class="nav-link">
              <i class="nav-icon fa fa-users"></i>
              <p>
                Attendance Mgt
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
            
        
              <li class="nav-item">
                <a href="<?php echo base_url('User/staff_attendance')?>" class="nav-link">
                  <i class="fa fa-users nav-icon"></i>
                  <p>Attendance Report</p>
                </a>
              </li>
              
             <li class="nav-item">
                <a href="<?php echo base_url('User/log_report')?>" class="nav-link">
                  <i class="far fa-user nav-icon"></i>
                  <p>Log Report </p>
                </a>
              </li>
              
              <li class="nav-item">
                <a href="<?php echo base_url('User/shift_report')?>" class="nav-link">
                  <i class="far fa-user nav-icon"></i>
                  <p>Shift Report </p>
                </a>
              </li>
              
              
              <li class="nav-item">
                <a href="<?php echo base_url('User/request_attendance')?>" class="nav-link">
                  <i class="far fa-user nav-icon"></i>
                  <p>Pending Attendance </p>
                </a>
              </li>
              
              <li class="nav-item">
                <a href="<?php echo base_url('User/request_working')?>" class="nav-link">
                  <i class="far fa-user nav-icon"></i>
                  <p> Request Working </p>
                </a>
              </li>
              
              
			   
             <li class="nav-item">
                <a href="<?php echo base_url('User/staff_leave')?>" class="nav-link">
                  <i class="far fa-user nav-icon"></i>
                  <p>Leave  </p>
                </a>
              </li> 
              
              <li class="nav-item">
                <a href="<?php echo base_url('User/staff_Sleave')?>" class="nav-link">
                  <i class="far fa-user nav-icon"></i>
                  <p>Short Leave   </p>
                </a>
              </li> 
            
              
              
             <li class="nav-item">
                <a href="<?php echo base_url('User/staff_payslip')?>" class="nav-link">
                  <i class="far fa-user nav-icon"></i>
                  <p>Salary Payslip  </p>
                </a>
              </li> 
          

              
              
              </ul>
          </li>
     
         
              <li class="nav-item has-treeview">
                <a href="#" class="nav-link">
                  <i class="nav-icon fa fa-user"></i>
                  <p>
                    Settings
                    <i class="fas fa-angle-left right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="<?php echo base_url('User/staff_profile')?>" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>My Profile</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="<?php echo base_url('User/staffPass')?>" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Change Password</p>
                    </a>
                  </li>
                </ul>
              </li>
              <?php
            }
            ?>
          </ul>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>
    <script type="text/javascript">
    function switchAccount(id){
      $.ajax({
        type: "POST",
        url: "<?php echo base_url('User/switchAccount')?>",
        data: {id},
        success: function(id1){
          location.reload();
        }
      })
    }
  </script>
  <script>
    function switchCompany(sl){
      var id = sl.value;
      $.ajax({
        type: "POST",
        url: "<?php echo base_url('User/switchCompany')?>",
        data: {id},
        success: function(id1){
          location.reload();
        }
      });
    }
    
</script>