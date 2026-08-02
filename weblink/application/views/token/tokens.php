
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>MID</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

 <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url('adminassets/plugins/fontawesome-free/css/all.min.css')?>">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  
  <!-- Select2 -->
  <link rel="stylesheet" href="<?php echo base_url('adminassets/plugins/select2/css/select2.min.css')?>">
  <link rel="stylesheet" href="<?php echo base_url('adminassets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')?>">
  <link rel="stylesheet" href="<?php echo base_url('adminassets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')?>">
  <link rel="stylesheet" href="<?php echo base_url('adminassets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')?>">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url('adminassets/dist/css/adminlte.min.css')?>">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
 <?php $this->load->view('menu/menu')?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
         
          <div class="col-sm-12">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active"Add >Tokens</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <?php
   if($this->session->userdata()['type']=='B')
      { 
    ?>
      <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <div class="card card-danger">
              <div class="card-header">
                <h3 class="card-title">Tokens</h3>
              </div>
              <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>S. No</th>
                    <th>Department</th>
                    <th>Sub-Depart</th>
                    <th>User Name</th>
                    <th>Mobile</th>
                    <th>Token No.</th>
                    <th>Query</th>
                    <th>Date</th>
                    <th>Status</th>
                  </tr>
                  </thead>
                  <tbody>
                      <?php
            
                      $count=1;
                      foreach($depids as $business){
                      $result = $this->web->getTokenInfo($business->depid,$bid);
                      //print_r($result);
                      foreach($result as $val){
                      ?>

                      <tr>
                        <td><?php echo $count++?></td>
                        <td>
                          <?php 
                                $dname = $this->web->getDepartById($val->Dept_id);
                                echo $dname[0]->department; ?>
                          </td>
                        <td>
                          <?php 
                                $sdname = $this->web->getSubDepartById($val->Sub_deptid);
                                echo $sdname['sdname']; ?>
                          </td>
                        <td><?php 
                                $uname=$this->web->getBusinessById($val->userid);
                                echo $uname['name']; ?>        
                          </td>
                        <td><?php echo $uname['mobile']?></td>
                        <td><?php echo $dname[0]->Dep_code.'_'.$val->token; ?></td>
                        <td><?php echo $val->Query?></td>
                        <td><?php echo $val->date?></td>
                        
                        <td id="stat<?php echo $val->token.$val->Dept_id; ?>">
                          <?php
                              if ($val->status == "0") {
                          ?>    
                            <button class="btn btn-warning" id="stat" >Waiting</button>
                          <?php
                              }elseif($val->status == "1"){
                          ?>
                            <button class="btn btn-success" id="stat" >Calling</button>
                          <?php
                            }elseif($val->status == "2"){
                          ?>
                            <button class="btn btn-primary" id="stat">Done</button>
                          <?php
                            }
                          ?>
                        </td>
                      
                      </tr>
                      <?php 
                        }
                      }
                      ?>
                    </tbody>
                    <tfoot>

                  </tfoot>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>



  <?php 
        }
    if($this->session->userdata()['type']=='C')
      { 
    ?>
      <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <div class="card card-danger">
              <div class="card-header">
                <h2 class="card-title">Tokens</h2><br>
                <div class="row">
                  <div class="col-md-12">
                    <select class="select2" id="sdepart" data-placeholser="Select a Business" name="sdepart" style="width: 100%">
                      <option selected disabled>Select a Sub-Department</option>
                      <option value="allTokens">View All Tokens</option>
                      <?php
                        foreach ($sdepts as $key => $value) {
                          $sname = $this->web->getSubDepartById($value->subdepart_id)['sdname'];
                          echo '<option value="'.$value->subdepart_id.'">'.$sname.'</option>';
                        }
                      ?>
                    </select>
                  </div>
                  <div id="test"></div>
                </div>
              </div>
              <div class="card-body" id="fetch">
              <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>S. No</th>
                    <th>Department</th>
                    <th>Sub-Depart</th>
                    <th>User Name</th>
                    <th>Mobile</th>
                    <th>Token No.</th>
                    <th>Query</th>
                    <th>Date</th>
                    <th>Status</th>
                  </tr>
                  </thead>
                  <tbody>
                      <?php
                      $type = 'D';
                      $count=1;
                      $countid = $this->session->userdata('login_id');
                      $result = $this->web->getTokenInfo($depids,$cid);
                      //print_r($result);
                      foreach($result as $val){
                      ?>

                      <tr>
                        <td><?php echo $count++?></td>
                        <td>
                          <?php 
                                $dname = $this->web->getDepartById($val->Dept_id);
                                echo $dname[0]->department; ?>
                          </td>
                        <td>
                          <?php 
                                $sdname = $this->web->getSubDepartById($val->Sub_deptid);
                                echo $sdname['sdname']; ?>
                          </td>
                        <td>
                          <?php 
                                $uname=$this->web->getBusinessById($val->userid);
                                echo $uname['name']; ?>        
                          </td>
                        <td><?php echo $uname['mobile']?></td>
                        <td><?php echo $dname[0]->Dep_code.'_'.$val->token; ?></td>
                        <td><?php echo $val->Query?></td>
                        <td><?php echo $val->date?></td>
                        
                        <td id="stat<?php echo $val->id; ?>"  data-order="
                                <?php if($val->status == 0){echo $num='2';}elseif($val->status == '1'){echo $num='1';}else{echo $num='3';} ?>         ">
                          <?php
                              if ($val->status == "0") {
                          ?>    
                            <button class="btn btn-warning" id="stat" onclick="active('<?php echo $val->id; ?>','<?php echo $val->userid; ?>','<?php echo $countid; ?>','<?php echo $cid; ?>')">Waiting</button>
                          <?php
                              }elseif($val->status == "1"){
                          ?>
                            <button class="btn btn-success" id="stat" onclick="Close('<?php echo $val->id; ?>','<?php echo $val->userid; ?>','<?php echo $countid; ?>','<?php echo $cid; ?>')">Calling</button>
                          <?php
                            }elseif($val->status == "2"){
                          ?>
                            <button class="btn btn-primary" id="stat" >Done</button>
                          <?php
                            }
                          ?>
                        </td>
                      
                      </tr>
                      <?php 
                        }
                      ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <td><button class="btn btn-success" onclick="callNext('<?php echo $cid?>')">NEXT</button></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr>
                  </tfoot>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>



  <?php 
        }
   ?>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <?php $this->load->view('menu/footer')?>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="<?php echo base_url('adminassets/plugins/jquery/jquery.min.js')?>"></script>
<!-- Bootstrap 4 -->
<script src="<?php echo base_url('adminassets/plugins/bootstrap/js/bootstrap.bundle.min.js')?>"></script>
<!-- bs-custom-file-input -->
<script src="<?php echo base_url('adminassets/plugins/bs-custom-file-input/bs-custom-file-input.min.js')?>"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url('adminassets/plugins/datatables/jquery.dataTables.min.js')?>"></script>
<!-- Select2 -->
<script src="<?php echo base_url('adminassets/plugins/select2/js/select2.full.min.js')?>"></script>
<script src="<?php echo base_url('adminassets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')?>"></script>
<script src="<?php echo base_url('adminassets/plugins/datatables-responsive/js/dataTables.responsive.min.js')?>"></script>
<script src="<?php echo base_url('adminassets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')?>"></script>
<script src="<?php echo base_url('adminassets/dist/js/adminlte.min.js')?>"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo base_url('adminassets/dist/js/demo.js')?>"></script>
<script>
  $(function () {
   var table = $('#example1').DataTable({
     "responsive": true,
      "autoWidth": false,
      "order": [ 8, 'asc' ]
    });
   
  });
  
</script>

  <?php 
    if($this->session->userdata()['type']=='C')
      { 
    ?>

<script type="text/javascript">
  $(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })
  });
  var calltype = 1;
  var tid = <?php echo $depids;?>;
  $('#sdepart').on('change', function() {
    var sdid = this.value;
    var did = <?php echo $depids ?>;
    var bid = <?php echo $cid ?>;
    var tSDept="getTokenBySdept";
    if (sdid != "allTokens") {
        calltype = 2;
        tid = sdid;
    }else{
        calltype = 1;
        tid = did;
    }
    //console.log(calltype,tid);
    $.ajax({
      type: "post",
      url: "User/getajaxRequest",
      data: {sdid,did,bid,tSDept},
      success: function(data){
        $('#fetch').html(data);
        var table = $('#example1').DataTable();
        table.destroy();
 
        table = $('#example1').DataTable( {
            "responsive": true,
            "autoWidth": false,
            "order": [ 8, 'asc' ]
        } );
      }
    });
});

  function callNext(bid){
    $.ajax({
      type: "post",
      url: "User/callNextToken",
      data: {calltype,tid,bid},
      datatype: "json",
    success: function(data){
      //console.log(data);
      var obj = JSON.parse(data);
      //console.log(obj);
        var id1 = obj.id;
        var id2 = obj.uid;
        var id3 = obj.cid;
        var id4 = obj.bid;
        var table = $('#example1').DataTable();
      if (obj.type == 3) {

        var cell = table.cell( '#stat'+id1 );
        cell.data( '<button class="btn btn-success" onclick="Close(\'' + id1+'\',\''+ id2 + '\',\''+ id3 + '\',\''+ id4 + '\')">Calling</button>' ).draw();
        $('#stat'+id1).attr('data-order', '1');

      }else if(obj.type == 1){

        var id5 = obj.nid;
        var id6 = obj.nuid;
        var cell = table.cell( '#stat'+id1 );
        cell.data( '<button class="btn btn-primary">Done</button>' ).draw();
        $('#stat'+id1).attr('data-order', '3');
        var cell = table.cell( '#stat'+id5 );
        cell.data( '<button class="btn btn-success" onclick="Close(\'' + id5+'\',\''+ id6 + '\',\''+ id3 + '\',\''+ id4 + '\')">Calling</button>' ).draw();
        $('#stat'+id5).attr('data-order', '1');
      }else if(obj.type == 2){

        var cell = table.cell( '#stat'+id1 );
        cell.data( '<button class="btn btn-primary">Done</button>' ).draw();
        $('#stat'+id1).attr('data-order', '3');
      }
      table.destroy();
 
        table = $('#example1').DataTable( {
            "responsive": true,
            "autoWidth": false,
            "order": [ 8, 'asc' ]
        } );
      }
    })
  }
  function active(id,uid,cid,bid){
    $.ajax({
      type: "POST",
      url: "User/activateToken",
      data: {id,uid,cid,bid},
      datatype: "json",
    success: function(data){
      var obj = JSON.parse(data);
      var id1 = obj.id;
      var id2 = obj.uid;
      var id3 = obj.cid;
      var id4 = obj.bid;
      var table = $('#example1').DataTable();
      var cell = table.cell( '#stat'+id1 );
      $('#stat'+id1).attr('data-order', '1');
      cell.data( '<button class="btn btn-success" onclick="Close(\'' + id1+'\',\''+ id2 + '\',\''+ id3 + '\',\''+ id4 + '\')">Calling</button>' ).draw();
        table.destroy();
 
        table = $('#example1').DataTable( {
            "responsive": true,
            "autoWidth": false,
            "order": [ 8, 'asc' ]
        } );
    }
    })
  }

  function Close(id,uid,cid,bid){
    $.ajax({
      type: "POST",
      url: "User/closeToken",
      data: {id,uid,cid,bid},
      datatype: "json",      
      success: function(data){
      var obj = JSON.parse(data);
      var id1 = obj.id;
      var id2 = obj.uid;
      var id3 = obj.cid;
      var id4 = obj.bid;
      var table = $('#example1').DataTable();
      var cell = table.cell( '#stat'+id1 );
      $('#stat'+id1).attr('data-order', '3');
      cell.data( '<button class="btn btn-primary">Done</button>' ).draw();
      table.destroy();
 
        table = $('#example1').DataTable( {
            "responsive": true,
            "autoWidth": false,
            "order": [ 8, 'asc' ]
        } );
    }
    })
  }
</script>
<?php } ?>
<script>$(document).ready(function () { 
$('.nav-link').click(function(e) {
$('.nav-link').removeClass('active');        
$(this).addClass("active");

});
});

$(function () {
    var url = window.location;
    // for single sidebar menu
    $('ul.nav-sidebar a').filter(function () {
        return this.href == url;
    }).addClass('active');

    // for sidebar menu and treeview
    $('ul.nav-treeview a').filter(function () {
        return this.href == url;
    }).parentsUntil(".nav-sidebar > .nav-treeview")
        .css({'display': 'block'})
        .addClass('menu-open').prev('a')
        .addClass('active');
});
</script>
</body>
</html>
