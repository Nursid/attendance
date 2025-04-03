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
    <!-- Sidebar user panel (optional) -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <p><?php if($this->session->userdata()['type']=='B'){echo $res=$this->web->getBusinessById($this->session->userdata()['login_id'])['name'];
              } else{ print_r($this->session->userdata()['username']); } ?>
                <i class="fas fa-angle-left right"></i></p>
            </a>
            <?php
            $linked = $this->session->userdata('linked');
            if(count($linked)>0){
              ?>
            <ul class="nav nav-treeview">
              <?php

              foreach($linked as $account){
                if(!empty($account)){
                  $name=$this->web->getBusinessById($account['login_id'])['name'];
                  if($account['login_id']!=$this->session->userdata('login_id')){
                    echo '<li class="nav-item">
                      <a onclick="switchAccount('.$account['login_id'].')" class="nav-link">
                        <p>'.$name.'</p>
                      </a>
                    </li>';
                  }
                }
              }?>
            </ul>
          <?php }
            ?>
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
              <a href="<?php echo base_url('licence_history')?>" class="nav-link">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>
                  Licence History

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

         </li>
         
         
        

            <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fa fa-users"></i>
              <p>
                Employee Master
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              
              <li class="nav-item">
                <a href="<?php echo base_url('addemployee')?>" class="nav-link">
                  <i class="far fa-user nav-icon"></i>
                  <p>Add Employee </p>
                </a>
              </li>
                
                
                
              <li class="nav-item">
                <a href="<?php echo base_url('employees')?>" class="nav-link">
                  <i class="fa fa-users nav-icon"></i>
                  <p>Employees List </p>
                </a>
              </li>
              
              <li class="nav-item">
                <a href="<?php echo base_url('left_employee')?>" class="nav-link">
                  <i class="far fa-user nav-icon"></i>
                  <p> Ex Employee </p>
                </a>
              </li>
               <li class="nav-item">
                <a href="<?php echo base_url('assign_shift')?>" class="nav-link">
                  <i class="far fa-user nav-icon"></i>
                  <p>Assign  </p>
                </a>
              </li> 
              <li class="nav-item">
                <a href="<?php echo base_url('User/manage_shift')?>" class="nav-link">
                  <i class="far fa-user nav-icon"></i>
                  <p>Manage Shift  </p>
                </a>
              </li> 
              
               </ul>
          </li>
              
              <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fa fa-users"></i>
              <p>
                Employee Login
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
                
              <li class="nav-item">
                <a href="<?php echo base_url('manager_roll')?>" class="nav-link">
                  <i class="far fa-user nav-icon"></i>
                  <p>Manager Access Role </p>
                </a>
              </li>
              
               </li>
               <li class="nav-item">
                <a href="<?php echo base_url('generate_login')?>" class="nav-link">
                  <i class="far fa-user nav-icon"></i>
                  <p>Generate Login </p>
                </a>
              </li>
              
              </li>
               <li class="nav-item">
                <a href="<?php echo base_url('User/activity_report')?>" class="nav-link">
                  <i class="far fa-user nav-icon"></i>
                  <p>Activity Log </p>
                </a>
              </li>
              
              
            
              
              
              </ul>
          </li>
          
           <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fa fa-calendar-minus"></i>
              <p>
                 Attendance Master
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
         
           <li class="nav-item">
                <a href="<?php echo base_url('manual_attendance')?>" class="nav-link">
                  <i class="nav-icon fa fa-book-reader"></i>
                  <p>Manual Attendance</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo base_url('User/Assign_working')?>" class="nav-link">
                  <i class="nav-icon fa fa-book-reader"></i>
                  <p>Assign Working</p>
                </a>
              </li>
               <li class="nav-item">
                <a href="<?php echo base_url('pending_att')?>" class="nav-link">
                  <i class="nav-icon fa fa-book-reader"></i>
                  <p>Pending Attendance</p>
                </a>
              </li>
              
             
          </ul>
          </li>
          
          
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fa fa-calendar-minus"></i>
              <p>
                Report
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
             
              <li class="nav-item">
                <a href="<?php echo base_url('dailyreport')?>" class="nav-link">
                  <i class="fa fa-calendar-day nav-icon"></i>
                  <p>Daily Report </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo base_url('monthly_report')?>" class="nav-link">
                  <i class="fa fa-calendar-alt nav-icon"></i>
                  <p>Monthly Report</p>
                </a>
              </li>
             
             <li class="nav-item">
                <a href="<?php echo base_url('employee_report')?>" class="nav-link">
                  <i class="far fa-calendar-times nav-icon"></i>
                  <p>Employee Report</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo base_url('field_duty')?>" class="nav-link">
                  <i class="fa fa-map-marked nav-icon"></i>
                  <p>Field Duty Report</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo base_url('gps_report')?>" class="nav-link">
                  <i class="fa fa-map-marked nav-icon"></i>
                  <p>GPS Report</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo base_url('access_report')?>" class="nav-link">
                  <i class="fa fa-map-marked nav-icon"></i>
                  <p>Access Log Report</p>
                </a>
              </li>
              
             </ul>
             </li>
             
            
              <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fa fa-cog"></i>
              <p>
                Attendance Setting
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
             <li class="nav-item">
                <a href="<?php echo base_url('section_list')?>" class="nav-link">
                  <i class="fa fa-home nav-icon"></i>
                  <p>Section</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo base_url('device_list')?>" class="nav-link">
                  <i class="fa fa-map-marked nav-icon"></i>
                  <p>Device Manager</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo base_url('department_list')?>" class="nav-link">
                  <i class="fa fa-hospital nav-icon"></i>
                  <p>Department</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo base_url('shifts')?>" class="nav-link">
                  <i class="fa fa-clock nav-icon"></i>
                  <p>Shifts</p>
                </a>
              </li>
              
              <li class="nav-item">
                <a href="<?php echo base_url('holidays_list')?>" class="nav-link">
                  <i class="fa fa-clock nav-icon"></i>
                  <p>Holidays</p>
                </a>
              </li>
              
             <li class="nav-item">
                <a href="<?php echo base_url('attendance_rule')?>" class="nav-link">
                  <i class="fa fa-random nav-icon"></i>
                  <p>Attendance Rule</p>
                </a>
              </li>
               <li class="nav-item">
                <a href="<?php echo base_url('attendance_option')?>" class="nav-link">
                  <i class="fa fa-random nav-icon"></i>
                  <p>Attendance Option</p>
                </a>
              </li>
             </ul></li> 
              
              
             
             <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fa fa-hotel"></i>
              <p>
                Leave Management
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
             
             <li class="nav-item">
                <a href="<?php echo base_url('open_leave')?>" class="nav-link">
                  <i class="fa fa-history nav-icon"></i>
                  <p>Open Leave Limit</p>
                </a>
              </li>
              
            <!--  <li class="nav-item">
                <a href="<?php echo base_url('leave_report')?>" class="nav-link">
                  <i class="fa fa-history nav-icon"></i>
                  <p> Add Leave</p>
                </a>
              </li>-->
           
              <li class="nav-item">
                <a href="<?php echo base_url('leave')?>" class="nav-link">
                  <i class="fa fa-book nav-icon"></i>
                  <p>Pending Leave</p>
                </a>
              </li>
              
              <li class="nav-item">
                <a href="<?php echo base_url('Sleave')?>" class="nav-link">
                  <i class="fa fa-book nav-icon"></i>
                  <p>Pending Short Leave</p>
                </a>
              </li>
              
               <li class="nav-item">
                 <a href="<?php echo base_url('leave_history')?>" class="nav-link">
                  <i class="fa fa-book nav-icon"></i>
                  <p>Leave History</p>
                </a>
              </li>
              
              
              </ul></li>
           

           
          
          
       <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-money-bill-alt"></i>
              <p>
                Payroll
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
             
             <li class="nav-item">
                <a href="<?php echo base_url('salary_head')?>" class="nav-link">
                  <i class="fa fa-history nav-icon"></i>
                  <p>Salary_head</p>
                </a>
              </li>
           
              <li class="nav-item">
                 <a href="<?php echo base_url('Payroll/employeesSalary')?>" class="nav-link">
                  <i class="fa fa-book nav-icon"></i>
                  <p>Salary Report</p>
                </a>
              </li>
              
              <li class="nav-item">
               <a href="<?php echo base_url('Payroll/employeesNetSalary')?>" class="nav-link">
                  <i class="fa fa-history nav-icon"></i>
                  <p>Net Payable</p>
                </a>
              </li>
               
               
              <li class="nav-item">
                <a href="<?php echo base_url('payroll/earnings')?>" class="nav-link">
                  <i class="fa fa-history nav-icon"></i>
                  <p>Earnings&Deduc</p>
                </a>
              </li>
           
              
              
               <li class="nav-item">
                <a href="<?php echo base_url('salary_rule')?>" class="nav-link">
                  <i class="fa fa-book nav-icon"></i>
                  <p>Salary Rule</p>
                </a>
              </li>
              
              
              </ul></li>   
          
          
          
           <li class="nav-item has-treeview">
            <a href="<?php echo base_url('salary-employees')?>" class="nav-link">
              <i class="nav-icon fas fa-money-bill-alt"></i>
              <p>
                Direct Salary 
                
              </p>
            </a>
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
            if($this->session->userdata()['type']=='P'){
            
            
             $uid=$this->session->userdata('login_id') ;
		  //$busi=$this->web->getBusinessbyUser($uid);
		      //$bid=$busi[0]->business_id;
        $bid = $this->session->userdata('empCompany');
			  $role=$this->web->getRollbyid($uid,$bid);
			  
	  
		  ?>    
     

            <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fa fa-users"></i>
              <p>
                Employee Master
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
            
            <?php
			  if($role[0]->add_emp=="1" || $role[0]->type=="1"){ ?>
              <li class="nav-item">
                <a href="<?php echo base_url('addemployee')?>" class="nav-link">
                  <i class="far fa-user nav-icon"></i>
                  <p>Add Employee </p>
                </a>
              </li>
               <?php } 
            
            if($role[0]->employee_list=="1" || $role[0]->type=="1"){ ?> 
              <li class="nav-item">
                <a href="<?php echo base_url('employees')?>" class="nav-link">
                  <i class="fa fa-users nav-icon"></i>
                  <p>Employees List </p>
                </a>
              </li>
              <?php } 
              
              if($role[0]->employee_list=="1" || $role[0]->type=="1"){ ?> 
              <li class="nav-item">
                <a href="<?php echo base_url('left_employee')?>" class="nav-link">
                  <i class="far fa-user nav-icon"></i>
                  <p> Ex Employee </p>
                </a>
              </li>
              
              <?php } 
			    if($role[0]->assign=="1"  || $role[0]->type=="1"){ ?>
             <li class="nav-item">
                <a href="<?php echo base_url('assign_shift')?>" class="nav-link">
                  <i class="far fa-user nav-icon"></i>
                  <p>Assign  </p>
                </a>
              </li> 
              <li class="nav-item">
                <a href="<?php echo base_url('User/manage_shift')?>" class="nav-link">
                  <i class="far fa-user nav-icon"></i>
                  <p>Manage Shift  </p>
                </a>
              </li> 
              
              
              <?php } ?>
              
       </ul>
          </li>
               <?php  
			    if($role[0]->manager_role=="1"  || $role[0]->type=="1"){ ?>
                
              <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fa fa-users"></i>
              <p>
                Employee Login
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
                
              <li class="nav-item">
                <a href="<?php echo base_url('manager_roll')?>" class="nav-link">
                  <i class="far fa-user nav-icon"></i>
                  <p>Manager Access Role </p>
                </a>
              </li>
              
               </li>
               <li class="nav-item">
                <a href="<?php echo base_url('generate_login')?>" class="nav-link">
                  <i class="far fa-user nav-icon"></i>
                  <p>Generate Login </p>
                </a>
              </li>
              
              </li>
               <li class="nav-item">
                <a href="<?php echo base_url('User/activity_report')?>" class="nav-link">
                  <i class="far fa-user nav-icon"></i>
                  <p>Activity Log </p>
                </a>
              </li>
              
             
              
              </ul>
          </li>
           <?php } ?>
		  
           <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fa fa-calendar-minus"></i>
              <p>
                 Attendance Master
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
          <?php if($role[0]->manual_att=="1" || $role[0]->type=="1"){?>
           <li class="nav-item">
                <a href="<?php echo base_url('manual_attendance')?>" class="nav-link">
                  <i class="nav-icon fa fa-book-reader"></i>
                  <p>Manual Attendance</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo base_url('User/Assign_working')?>" class="nav-link">
                  <i class="nav-icon fa fa-book-reader"></i>
                  <p>Assign Working</p>
                </a>
              </li>
              
               <?php } 
			   if($role[0]->pending_att=="1" || $role[0]->type=="1"){ ?>
               <li class="nav-item">
                <a href="<?php echo base_url('pending_att')?>" class="nav-link">
                  <i class="nav-icon fa fa-book-reader"></i>
                  <p>Pending Attendance</p>
                </a>
              </li>
            <?php }?>
             
          </ul>
          </li>
          
           <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fa fa-calendar-minus"></i>
              <p>
                Report
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <?php   
		   if($role[0]->daily_report=="1" || $role[0]->type=="1"){ ?> 
              <li class="nav-item">
                <a href="<?php echo base_url('dailyreport')?>" class="nav-link">
                  <i class="fa fa-calendar-day nav-icon"></i>
                  <p>Daily Report </p>
                </a>
              </li>
               <?php } 
			    if($role[0]->other_report=="1" || $role[0]->type=="1"){ ?>
              <li class="nav-item">
                <a href="<?php echo base_url('monthly_report')?>" class="nav-link">
                  <i class="fa fa-calendar-alt nav-icon"></i>
                  <p>Monthly Report</p>
                </a>
              </li>
             
             <li class="nav-item">
                <a href="<?php echo base_url('employee_report')?>" class="nav-link">
                  <i class="far fa-calendar-times nav-icon"></i>
                  <p>Employee Report</p>
                </a>
              </li>
              <?php } 
			    if($role[0]->gps_report=="1" || $role[0]->type=="1"){ ?>
              <li class="nav-item">
                <a href="<?php echo base_url('field_duty')?>" class="nav-link">
                  <i class="fa fa-map-marked nav-icon"></i>
                  <p>Field Duty Report</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo base_url('gps_report')?>" class="nav-link">
                  <i class="fa fa-map-marked nav-icon"></i>
                  <p>GPS Report</p>
                </a>
              </li>
              <?php } 
			    if($role[0]->log_report=="1" || $role[0]->type=="1"){ ?>
               <li class="nav-item">
                <a href="<?php echo base_url('access_report')?>" class="nav-link">
                  <i class="fa fa-map-marked nav-icon"></i>
                  <p>Access Log Report</p>
                </a>
              </li>
              
              
              <?php }?>
             </ul>
             </li>
             
             
           <?php 
		   if($role[0]->att_setting=="1" || $role[0]->type=="1"){ ?>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fa fa-cog"></i>
              <p>
                Attendance Setting
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
             <li class="nav-item">
                <a href="<?php echo base_url('section_list')?>" class="nav-link">
                  <i class="fa fa-home nav-icon"></i>
                  <p>Section</p>
                </a>
              </li>
              
              <li class="nav-item">
                <a href="<?php echo base_url('device_list')?>" class="nav-link">
                  <i class="fa fa-map-marked nav-icon"></i>
                  <p>Device Manager</p>
                </a>
              </li> 
              <li class="nav-item">
                <a href="<?php echo base_url('department_list')?>" class="nav-link">
                  <i class="fa fa-hospital nav-icon"></i>
                  <p>Department</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo base_url('shifts')?>" class="nav-link">
                  <i class="fa fa-clock nav-icon"></i>
                  <p>Shifts</p>
                </a>
              </li>
              
              <li class="nav-item">
                <a href="<?php echo base_url('holidays_list')?>" class="nav-link">
                  <i class="fa fa-clock nav-icon"></i>
                  <p>Holidays</p>
                </a>
              </li>
              
             <li class="nav-item">
                <a href="<?php echo base_url('attendance_rule')?>" class="nav-link">
                  <i class="fa fa-random nav-icon"></i>
                  <p>Attendance Rule</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo base_url('attendance_option')?>" class="nav-link">
                  <i class="fa fa-random nav-icon"></i>
                  <p>Attendance Option</p>
                </a>
              </li>
              
             </ul></li> 
              
               <?php }   ?>
             
             <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fa fa-hotel"></i>
              <p>
                Leave Management
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
           <?php  if($role[0]->leave_manage=="1" || $role[0]->type=="1"){ ?>
             <li class="nav-item">
                <a href="<?php echo base_url('open_leave')?>" class="nav-link">
                  <i class="fa fa-history nav-icon"></i>
                  <p>Leave Limit</p>
                </a>
              </li>
               <li class="nav-item">
                 <a href="<?php echo base_url('leave_history')?>" class="nav-link">
                  <i class="fa fa-book nav-icon"></i>
                  <p>Leave History</p>
                </a>
              </li>
              <?php } if($role[0]->add_leave=="1" || $role[0]->type=="1"){  ?>
              <li class="nav-item">
                <a href="<?php echo base_url('leave')?>" class="nav-link">
                  <i class="fa fa-book nav-icon"></i>
                  <p>Aprove Leave</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo base_url('Sleave')?>" class="nav-link">
                  <i class="fa fa-book nav-icon"></i>
                  <p>Pending Short Leave</p>
                </a>
              </li>
              
               <?php } ?>
              
              </ul></li>
              
             <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-money-bill-alt"></i>
              <p>
                Payroll
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
            <?php  if($role[0]->add_salary=="1" || $role[0]->type=="1"){ ?>
             <li class="nav-item">
                <a href="<?php echo base_url('salary_head')?>" class="nav-link">
                  <i class="fa fa-history nav-icon"></i>
                  <p>Salary_head</p>
                </a>
              </li>
           <?php } if($role[0]->salary=="1" || $role[0]->type=="1"){  ?>

              <li class="nav-item">
                 <a href="<?php echo base_url('Payroll/employeesSalary')?>" class="nav-link">
                  <i class="fa fa-book nav-icon"></i>
                  <p>Salary Report</p>
                </a>
              </li>
              
              <li class="nav-item">
               <a href="<?php echo base_url('Payroll/employeesNetSalary')?>" class="nav-link">
                  <i class="fa fa-history nav-icon"></i>
                  <p>Net Payable</p>
                </a>
              </li>
               <?php } if($role[0]->earn=="1" || $role[0]->type=="1"){  ?>
         
              <li class="nav-item">
                <a href="<?php echo base_url('payroll/earnings')?>" class="nav-link">
                  <i class="fa fa-history nav-icon"></i>
                  <p>Earnings&Deduc</p>
                </a>
              </li>
           
               <?php }?>
              
            <!--   <li class="nav-item">
                <a href="<?php echo base_url('salary_rule')?>" class="nav-link">
                  <i class="fa fa-book nav-icon"></i>
                  <p>Salary Rule</p>
                </a>
              </li>-->
              
              
              </ul></li>   
          
          
          <!---
           <li class="nav-item has-treeview">
            <a href="<?php echo base_url('salary-employees')?>" class="nav-link">
              <i class="nav-icon fas fa-money-bill-alt"></i>
              <p>
                Direct Salary 
                
              </p>
            </a>
          </li>-->
            
            
            
            
            
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
                    <a href="<?php echo base_url('active_users')?>" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Active Users</p>
                    </a>
                  </li>
                  
                  <li class="nav-item">
                    <a href="<?php echo base_url('inactive_users')?>" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>inactive Users</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="<?php echo base_url('business_users')?>" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Business Users</p>
                    </a>
                  </li>
                  
                  <li class="nav-item">
                    <a href="<?php echo base_url('premium_business_users')?>" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Premium business Users</p>
                    </a>
                  </li>
                  
                  <li class="nav-item">
                    <a href="<?php echo base_url('active_business_users')?>" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Active Business Users</p>
                    </a>
                  </li>
                  
                  <li class="nav-item">
                    <a href="<?php echo base_url('inactive_business_users')?>" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>InActive Business Users</p>
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
            if($this->session->userdata()['type']=='C'){
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