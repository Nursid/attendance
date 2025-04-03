<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>MID | Log in</title>
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
      <img src="<?php echo base_url('adminassets/dist/img/logo.png')?>" >
  </div>
   
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body" style="text-align: center;">

      <span style="color: red; width: 100%;"><?php echo $this->session->flashdata('msg'); ?></span>

      <p class="login-box-msg">Start Check-in</p>

      <form action="<?php echo base_url('User/verify')?>" method="post">
        <div class="input-group mb-3">
          <input type="text" class="form-control" placeholder="Enter your Mobile No." name="mobile" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-mobile"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <input type="hidden" name="session" value="<?php echo base64_encode($mid)?>">
            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

     
     
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
