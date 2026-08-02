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
  <a href="#" class="brand-link">
    <img src="<?php echo base_url('adminassets/dist/img/logo.png')?>" alt="AdminLTE Logo" class="brand-image img-squre elevation-3"
    style="opacity: .8">
    <span class="brand-text font-weight-light">Midapp</span>
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
            <a href="<?php echo base_url('page_hostel')?>" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard

              </p>
            </a>
          </li>
            
 <li class="nav-item">
                <a href="<?php echo base_url('live_report')?>" class="nav-link">
                  <i class="fa fa-users nav-icon"></i>
                  <p>Live Report</p>
                </a>
              </li>
           
           
           
           
          <?php
          if($this->session->userdata()['type']=='B'){
            ?>

        
         
         
           <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fa fa-box"></i>
              <p>
                Student Master
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
            
            <li class="nav-item">
                <a href="<?php echo base_url('add_student')?>" class="nav-link">
                  <i class="fa fa-users nav-icon"></i>
                  <p>Add Student </p>
                </a>
              </li>

              <li class="nav-item">
                <a href="<?php echo base_url('student_list')?>" class="nav-link">
                  <i class="fa fa-users nav-icon"></i>
                  <p>Student List </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo base_url('exstudent_list')?>" class="nav-link">
                  <i class="fa fa-users nav-icon"></i>
                  <p>EX Student List </p>
                </a>
              </li>
              
              </ul>
          </li>
     
              
              <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fa fa-users"></i>
              <p>
                Hostel Log Report
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              
              
              
              
              <li class="nav-item">
                <a href="<?php echo base_url('hostel_daily_report')?>" class="nav-link">
                  <i class="far fa-user nav-icon"></i>
                  <p>Daily Report </p>
                </a>
              </li>
              
              
			   
             <li class="nav-item">
                <a href="<?php echo base_url('hostel_monthly_report')?>" class="nav-link">
                  <i class="far fa-user nav-icon"></i>
                  <p>Monthly Report  </p>
                </a>
              </li>
              
           <!--   <li class="nav-item">
                <a href="<?php echo base_url('hostel_student_report')?>" class="nav-link">
                  <i class="far fa-user nav-icon"></i>
                  <p>Student Report  </p>
                </a>
              </li> -->
            
               </ul>
          </li>
     
              <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fa fa-users"></i>
              <p>
               Canteen Mgt
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              
             
             
              <li class="nav-item">
                <a href="<?php echo base_url('timing')?>" class="nav-link">
                  <i class="fa fa-business-time nav-icon"></i>
                  <p>Canteen Timing </p>
                </a>
              </li>
              
               <li class="nav-item">
                <a href="<?php echo base_url('access_plan')?>" class="nav-link">
                  <i class="fa fa-book-dead nav-icon"></i>
                  <p> Access Plan </p>
                </a>
              </li>
              
              <li class="nav-item">
                <a href="<?php echo base_url('membership_detail')?>" class="nav-link">
                  <i class="fa fa-id-card nav-icon"></i>
                  <p>Access Detail </p>
                </a>
              </li>
              
              <li class="nav-item">
                <a href="<?php echo base_url('canteen_daily_report')?>" class="nav-link">
                  <i class="far fa-user nav-icon"></i>
                  <p>Daily Access Report </p>
                </a>
              </li>
              
              <li class="nav-item">
                <a href="<?php echo base_url('canteen_summary')?>" class="nav-link">
                  <i class="far fa-user nav-icon"></i>
                  <p>Monthly  Access Summary</p>
                </a>
              </li>
              
              <li class="nav-item">
                <a href="<?php echo base_url('student_access_report')?>" class="nav-link">
                  <i class="far fa-user nav-icon"></i>
                  <p>Student Access Report </p>
                </a>
              </li>
              
              
              <li class="nav-item">
                <a href="<?php echo base_url('canteen_monthly_report')?>" class="nav-link">
                  <i class="far fa-user nav-icon"></i>
                  <p>Monthly  Access Report </p>
                </a>
              </li>
              
               
              
               <!--<li class="nav-item">
                <a href="<?php echo base_url('canteen_rule')?>" class="nav-link">
                  <i class="fa fa-pen-fancy nav-icon"></i>
                  <p>Canteen Rule </p>
                </a>
              </li>-->
              
             
              
             </ul>
          </li>
              
              
        
              
              <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fa fa-hotel"></i>
              <p>
                Hostel Setting
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              
             
             
              <li class="nav-item">
                <a href="<?php echo base_url('canteen_device')?>" class="nav-link">
                  <i class="far fa-user nav-icon"></i>
                  <p>Device Manager  </p>
                </a>
              </li> 
              
              <li class="nav-item">
                <a href="<?php echo base_url('device_access')?>" class="nav-link">
                  <i class="far fa-user nav-icon"></i>
                  <p>Device Access  </p>
                </a>
              </li> 
              
              
             <li class="nav-item">
                <a href="<?php echo base_url('hostel_detail')?>" class="nav-link">
                  <i class="far fa-user nav-icon"></i>
                  <p>Hostel Detail </p>
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
                    <a href="<?php echo base_url('canteen-pass')?>" class="nav-link">
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