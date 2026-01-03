<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>MidApp | Log in</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url('adminassets/plugins/fontawesome-free/css/all.min.css')?>">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="<?php echo base_url('adminassets/plugins/icheck-bootstrap/icheck-bootstrap.min.css')?>">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url('adminassets/dist/css/adminlte.min.css')?>">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <link rel="icon" type="image/png" href="<?php echo base_url('adminassets/dist/img/logo.png')?>"/>
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
      <img src="<?php echo base_url('adminassets/dist/img/1709239494.jpg')?>" width="40%">
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body" style="text-align: center;">

      <span style="color: red; width: 100%;"><?php echo $this->session->flashdata('msg'); ?></span>

      <h5 class="login-box-msg">MCS Business Login</h5>

      <form action="<?php echo base_url('log')?>" method="post">
        <div class="input-group mb-3">
          <input type="username" class="form-control" placeholder="Username or Mobile No" name="username">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Password" name="password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
       <div class="row">
        <div class="col-6">
                       <!-- <label for="empType">Type</label>
                      &nbsp; &nbsp;-->
                        <select class="form-control" id="page" name="page">
                          <option value="1">Attendance Mgt</option>
                         
                        </select>
                         
                        </div>  &nbsp; &nbsp; &nbsp; &nbsp;
                        <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
          </div> 
                        
                        </div>
     <!--       
        <br>
        
        
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="remember">
              <label for="remember">
                Remember Me
              </label>
            </div>
      </div>
          
          
          
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
          </div>
         
        </div>-->
      </form> <br>
   <div> <h5><a href="<?php echo base_url('staff-login')?>" class="nav-link"> Click Here for Employee Login </a> </h5> </div>
     
     
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="<?php echo base_url('adminassets/plugins/jquery/jquery.min.js')?>"></script>
<!-- Bootstrap 4 -->
<script src="<?php echo base_url('adminassets/plugins/bootstrap/js/bootstrap.bundle.min.js')?>"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url('adminassets/dist/js/adminlte.min.js')?>"></script>

</body>
</html>
