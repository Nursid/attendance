<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
   <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
     </ul>

   

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Messages Dropdown Menu -->
    
      <!-- Notifications Dropdown Menu -->
     
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
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
       
        <div class="info">
          <a href="#" class="d-block">
                 <?php if($this->session->userdata()['type']=='B'){echo $res=$this->web->getBusinessById($this->session->userdata()['login_id'])['name'];
                } else{ print_r($this->session->userdata()['username']); } ?></a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item has-treeview menu-open">
            <a href="<?php echo base_url('page')?>" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
                
              </p>
            </a>
          </li>
          <?php
          if($this->session->userdata('type')=='A'){

          ?>
          <li class="nav-item has-treeview">
            <a href="<?php echo base_url('new-qr')?>" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Active New QR

              </p>
            </a>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fa fa-users"></i>
              <p>
                Permissions
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
               <li class="nav-item">
                <a href="<?php echo base_url('assign-appointment')?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Change Permission</p>
                </a>
              </li>
            </ul>
          </li>
          <?php
            }
          ?>
         
            <?php
            $page=$this->web->getPages();
            foreach ($page as $key => $page) {
              $data=$this->web->checkPermission($this->session->userdata('login_id'));
              if($data['assign_menu_id']==$page->page_id && $data['status']==0){
                  
                 //print_r($data['assign_menu_id']);
                $sub_page=$this->web->getSubPages($data['assign_menu_id']);
               
            ?>
            <li class="nav-item has-treeview">
              <a href="#" class="nav-link">
                <i class="nav-icon fa fa-users"></i>
                <p>
                  <?php echo $page->page_name?>
                  <i class="fas fa-angle-left right"></i>
                </p>
              </a>
              <?php 
               foreach($sub_page as $gg ){
              ?>
              
              <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="<?php echo base_url($gg->sub_page_url)?>" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p><?php echo $gg->sub_page_name?> </p>
                    </a>
                  </li>
              </ul>
              
              <?php   }?>
            </li>
            
            <?php
              }
             }
            ?>
          <?php
          if($this->session->userdata()['type']=='B'){
          ?>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fa fa-users"></i>
              <p>
                Attendance
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?php echo base_url('employees')?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Employees </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo base_url('dailyreport')?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Daily Report </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo base_url('monthly_report')?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Monthly Report</p>
                </a>
              </li>
             
             <li class="nav-item">
                <a href="<?php echo base_url('employee_report')?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Employee Report</p>
                </a>
              </li>
             
             
             
             
              <li class="nav-item">
                <a href="<?php echo base_url('leave')?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Leave Report</p>
                </a>
              </li>
              
              <li class="nav-item">
                <a href="<?php echo base_url('manual_attendance')?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Manual Attendance</p>
                </a>
              </li>
             <li class="nav-item">
                <a href="<?php echo base_url('attendance_rule')?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Attendance Rule</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo base_url('open_leave')?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Open Leave</p>
                </a>
              </li>
              
            </ul>
          </li>
          
          <?php
          }?>
          <?php
          if($this->session->userdata()['type']=='A'){
          ?>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fa fa-users"></i>
              <p>
                Department
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?php echo base_url('add-depart')?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Department </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo base_url('add-sub-depart')?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Sub-department </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo base_url('assign-depart')?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Assign Department</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo base_url('assign-sdepart')?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Assign Sub-department</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fa fa-user"></i>
              <p>
                Users
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?php echo base_url('users')?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>View Users</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo base_url('business-users')?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Business Users</p>
                </a>
              </li>
            </ul>
          </li> 
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fa fa-user"></i>
              <p>
                Counters
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?php echo base_url('assi-counter')?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Assign Counters</p>
                </a>
              </li>
            </ul>
          </li> 
           <li class="nav-item has-treeview">
              <a href="#" class="nav-link">
                <i class="nav-icon fa fa-user"></i>
                <p>
                  Request 
                  <i class="fas fa-angle-left right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="<?php echo base_url('view-request')?>" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>View Request</p>
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
                  <a href="<?php echo base_url('c-pass')?>" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Change Password</p>
                  </a>
                </li>
              </ul>
            </li> 
          <?php }?>
          <?php
          if($this->session->userdata()['type']=='B'){
          ?>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fa fa-user"></i>
              <p>
                Departments
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?php echo base_url('b-departs')?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>View Departments</p>
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
                  <a href="<?php echo base_url('c-pass')?>" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Change Password</p>
                  </a>
                </li>
              </ul>
              
            </li> 
          <?php
          }
          if($this->session->userdata()['type']=='C'){
          ?> 
            <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fa fa-user"></i>
              <p>
                Tokens
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?php echo base_url('counter-tokens')?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>View Tokens</p>
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
                  <a href="<?php echo base_url('c-pass')?>" class="nav-link">
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