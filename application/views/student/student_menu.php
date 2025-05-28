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
            <a href="<?php echo base_url('page_school')?>" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard

              </p>
            </a>
          


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
                <a href="<?php echo base_url('add_Students')?>" class="nav-link">
                  <i class="fa fa-users nav-icon"></i>
                  <p>Add Student </p>
                </a>
              </li>

              <li class="nav-item">
                <a href="<?php echo base_url('Students_list')?>" class="nav-link">
                  <i class="fa fa-users nav-icon"></i>
                  <p>Student List </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo base_url('Exstudents_list')?>" class="nav-link">
                  <i class="fa fa-users nav-icon"></i>
                  <p>Old Student List </p>
                </a>
              </li>
              
              </ul>
          </li>
          
          
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fa fa-box"></i>
              <p>
                Faculty Master
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
            
            <li class="nav-item">
                <a href="<?php echo base_url('add_teachers')?>" class="nav-link">
                  <i class="fas fa-user-plus nav-icon"></i>
                  <p>Add Faculty </p>
                </a>
              </li>

              <li class="nav-item">
                <a href="<?php echo base_url('teachers_list')?>" class="nav-link">
                  <i class="fas fa-chalkboard-teacher nav-icon"></i>
                  <p>Faculty List </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo base_url('getteacher_class')?>" class="nav-link">
                  <i class="fas fa-search nav-icon"></i>
                  <p>Get Assign Teacher  </p>
                </a>
              </li>

              <li class="nav-item">
                <a href="<?php echo base_url('teachers_attendance_list')?>" class="nav-link">
                  <i class="fas fa-calendar-check nav-icon"></i>
                  <p>Teacher Daily Attendance  </p>
                </a>
              </li>

              <li class="nav-item">
                <a href="<?php echo base_url('teachers_monthly_report')?>" class="nav-link">
                  <i class="fas fa-chart-line nav-icon"></i>
                  <p>Teacher Monthly Report  </p>
                </a>
              </li>
             <!-- <li class="nav-item">
                <a href="<?php echo base_url('Exstudents_list')?>" class="nav-link">
                  <i class="fa fa-users nav-icon"></i>
                  <p>EX Student List </p>
                </a>
              </li>-->
              
              </ul>
          </li>
          
          
          
     
              
              <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fa fa-users"></i>
              <p>
                 Attendance Report
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              
              
              
              
              <li class="nav-item">
                <a href="<?php echo base_url('students_daily_report')?>" class="nav-link">
                  <i class="far fa-user nav-icon"></i>
                  <p>Daily Report </p>
                </a>
              </li>
              
              
			   
             <li class="nav-item">
                <a href="<?php echo base_url('students_monthly_report')?>" class="nav-link">
                  <i class="far fa-user nav-icon"></i>
                  <p>Monthly Report  </p>
                </a>
              </li>
              
          <!--   <li class="nav-item">
                <a href="<?php echo base_url('school_students_report')?>" class="nav-link">
                  <i class="far fa-user nav-icon"></i>
                  <p>Student Report  </p>
                </a>
              </li> -->
            
               </ul>
          </li>
     
             
             
              
              
        
              
              <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fa fa-hotel"></i>
              <p>
                Academic Master
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              
             
             
             <li class="nav-item">
                <a href="<?php echo base_url('add_branch')?>" class="nav-link">
                  <i class="far fa-user nav-icon"></i>
                  <p>Branch  </p>
                </a>
              </li> 
              <li class="nav-item">
                <a href="<?php echo base_url('add_batch')?>" class="nav-link">
                  <i class="far fa-user nav-icon"></i>
                  <p>Batch </p>
                </a>
              </li> 
              <li class="nav-item">
                <a href="<?php echo base_url('add_semester')?>" class="nav-link">
                  <i class="far fa-user nav-icon"></i>
                  <p>Semester </p>
                </a>
              </li> 
              <li class="nav-item">
                <a href="<?php echo base_url('add_s_section')?>" class="nav-link">
                  <i class="far fa-user nav-icon"></i>
                  <p>Section  </p>
                </a>
              </li> 
              
              
              
              <li class="nav-item">
                <a href="<?php echo base_url('add_class')?>" class="nav-link">
                  <i class="far fa-user nav-icon"></i>
                  <p>Class Room </p>
                </a>
              </li> 
              <li class="nav-item">
                <a href="<?php echo base_url('add_period')?>" class="nav-link">
                  <i class="far fa-user nav-icon"></i>
                  <p>Class Period  </p>
                </a>
              </li> 
              
              <li class="nav-item">
                <a href="<?php echo base_url('add_subject')?>" class="nav-link">
                  <i class="far fa-user nav-icon"></i>
                  <p>Course  </p>
                </a>
              </li> 
              
             
             
            
              
              
             </ul>
          </li>
          
          
          
           <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fa fa-users"></i>
              <p>
                 Assign
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              
              
              
              
              <li class="nav-item">
                <a href="<?php echo base_url('assign_class')?>" class="nav-link">
                  <i class="far fa-user nav-icon"></i>
                  <p>Assign Class Room </p>
                </a>
              </li>
              
              
			
              
            <li class="nav-item">
                <a href="<?php echo base_url('assign_subject')?>" class="nav-link">
                  <i class="far fa-user nav-icon"></i>
                  <p>Assign Course </p>
                </a>
              </li> 
            
            
            
               
             <li class="nav-item">
                <a href="<?php echo base_url('time_table')?>" class="nav-link">
                  <i class="far fa-user nav-icon"></i>
                  <p>Period Time Table  </p>
                </a>
              </li>
              
               </ul>
          </li>
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
           <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fa fa-hotel"></i>
              <p>
                Device Setting
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              
            
              <li class="nav-item">
                <a href="<?php echo base_url('student_device')?>" class="nav-link">
                  <i class="far fa-user nav-icon"></i>
                  <p>Device Manager  </p>
                </a>
              </li> 
              
              <li class="nav-item">
                <a href="<?php echo base_url('stu_device_access')?>" class="nav-link">
                  <i class="far fa-user nav-icon"></i>
                  <p>Device Access  </p>
                </a>
              </li> 
            
              
              
             </ul>
          </li>
          
          
          
          
          
     
         
              <li class="nav-item has-treeview">
                <a href="#" class="nav-link">
                  <i class="nav-icon fa fa-user"></i>
                  <p>
                    User Settings
                    <i class="fas fa-angle-left right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                
                  <li class="nav-item">
                    <a href="<?php echo base_url('student_pass')?>" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Change Password</p>
                    </a>
                  </li>
                </ul>
              </li>

              <li class="nav-item">
              <a href="<?php echo base_url('holidays_list')?>" class="nav-link">
                <i class="nav-icon fa fa-hotel"></i>
                <p>
                  Holiday
                </p>
             </a>
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