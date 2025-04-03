
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
              <li class="breadcrumb-item active"Add >Request</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
    <section class="content">
      <div class="container-fluid">
       
           <div class="row">
            <div class="col-md-12">
                <div class="card card-success">
                  <div class="card-header">
                     <h3 class="card-title">Request Users List</h3><?php echo '<br>'.$this->session->flashdata('msgg'); ?>
                  </div>
                  <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>S. No</th>
                    <th>Download QR</th>
                    <th>QR Code</th>
                    <th>Request Name</th>
                    <th>Request Type</th>
                    <th>Action</th>
                   
                  </tr>
                  </thead>
                  <tbody>
					
					
					<?php 
					$count=1;
					foreach($qr as $qus){
						$uu=$this->web->getUserss($qus->request_id);
						$user_type=$this->web->TypeGet($uu['user_group']);
					?>
					  <tr>
                        <td><?php echo $count++; ?></td>
                        <?php 
                        if($uu['Generated_Qr']==''){
                            ?>
                            <td><h5>No Available</h5></td>
                            <?php
                        }else{
                            ?>
                            <td>
							<a class="btn btn-primary " download="<?php echo base_url('assets/qrimage/'.$uu['Generated_Qr'])?>" href="<?php echo base_url('assets/qrimage/'.$uu['Generated_Qr'])?>">Download</a>
							
							</td>
                            <?php
                        }
                        ?>
                        <?php 
                        if($uu['Generated_Qr']==''){
                            ?>
                            <td><h5>Not Generated QR</h5></td>
                            <?php
                        }else{
                            ?>
                            <td><a download="<?php echo base_url('assets/qrimage/'.$uu['Generated_Qr'])?>" href="<?php echo base_url('assets/qrimage/'.$uu['Generated_Qr'])?>"><img src="<?php echo base_url('assets/qrimage/'.$uu['Generated_Qr'])?>" title="Download QR code" style="width:30%; height:30%;"  ></a></td>
                            <?php
                        }
                        ?>
                       
                        <td><?php echo $uu['name']; ?></td>
                        <td><?php echo $user_type['name'];?></td>
                        <td><a  href="<?php echo base_url()?>User/print_qr/<?php echo $uu['id'];?>" class="btn btn-success ">Generate QR</a></td>
						
                       
					 </tr>
					 
					 <?php 
					 
					 
					 }?>
                    
                    </tbody>
                    <tfoot>
                      
                  </tfoot>
                </table>
              </div>
                </div>
            </div>
        </div>
      </div>
    </section>
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
  $('#myModal').on('show.bs.modal', function(e) {
    var cname = $(e.relatedTarget).data('cname');
    var lid = $(e.relatedTarget).data('lid');
    $(e.currentTarget).find('input[name="cname"]').val(cname);
    $(e.currentTarget).find('input[name="lid"]').val(lid);
  });

  $('#bt_form').on('click', function(){
        var fdata = $("#from").serialize();
      $.ajax({
        type: "POST",
        url: "User/editCounter",
        data: fdata,
        datatype: "json",
        success: function(res){
        if(res != ''){
          var obj = JSON.parse(res);
          var cname = obj.name;
          var clid = obj.id;
          console.log(cname);
          console.log(clid);
          $('#msg').html('New Name Updated!');
          $('#uname'+clid).html(cname);
        }
      }
    });
  });

  $(function () {
   var table = $('#example1').DataTable({
     "responsive": true,
      "autoWidth": false,
    });
   
  });

  $(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })
  });
 
 $('#busines').on('change', function() {
  var id = this.value;
  var datatype = "businesslist";
  $.ajax({
    type: "post",
    url: "User/getajaxRequest",
    data: {id,datatype},
    success: function(data){
      $('#departs').html(data);
    }
  });
});
</script>


<!-- avtive inactive test-->

<script>
function activate(id){
	$.ajax({
		type:"POST",
		url:"User/activation",
		data:{id},
		success: function(id1){
		var table = $('#example1').DataTable();
		var cell = table.cell('#statt'+id1);
		cell.data( '<button class="btn btn-success" onclick="inactivate(' + id1 + ')">Active</button>').draw();
		}
	})
}



function inactivate(id){
	$.ajax({
		type:"POST",
		url:"User/inactivation",
		data:{id},
		 success: function(id1){
		var table = $('#example1').DataTable();
		var cell = table.cell('#statt'+id1);
		cell.data( '<button class="btn btn-danger" onclick="activate(' + id1 + ')">Inactive</button>').draw();
		 }
	})
}
</script>




<script>
 function actionn(id){
	  $.ajax({
		  type:"POST",
		  data:{id},
		  url:"User/GenerateLogin",
		  success:function(id){
			  alert(id);
			  
		  }
	  })
 }
</script>




<script>
$(document).ready(function () { 
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
