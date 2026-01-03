
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>MidApp</title>
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
 <?php $this->load->view('employee/staff_menu')?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->

      
    
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
         
          <div class="col-sm-12">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Shift Report</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
<?php
   if($this->session->userdata()['type']=='B' || $this->session->userdata()['type']=='P')
      {
        if($this->session->userdata()['type']=='P'){
          $bid = $this->session->userdata('empCompany');
          $role=$this->web->getRollbyid($this->web->session->userdata('login_id'),$bid);
      
        } else {
          $bid=$this->web->session->userdata('login_id');
        }
        $login_id=$this->web->session->userdata('login_id');
    ?>
    <!-- Main content -->
    <section class="content">
    
    
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-2">
            <div class="card card-info">
           <!--   <div class="card-header">
              <a href="<?php echo base_url('addemployee')?>" >
                  
                  Add Employee
                </a>
               
                <span style="color: red"><?php echo $this->session->flashdata('msg');?></span>
              </div>-->
              
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>
 
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Shift Report
                 </h3>
              </div>
              <div class="card-body">
              
               <?php
                    $cudate = date("Y-m-d");
                   // $cdate=strtotime($cudate);
                   // $start_time= strtotime(date("Y-m-d 00:00:00",$cdate));
					//$end_time= strtotime(date("Y-m-d 00:00:00",$cdate))+604800;
					?>
              
              <table id="example2" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                   
                     <?php
                    for($d=0; $d<7;$d++){
						$new_start_time=strtotime(date("d-m-Y 00:00:00",strtotime($cudate))." +".$d." days");
						$dayweek= date("d D",$new_start_time);
                                  echo "<th>$dayweek </th>";
                                } ?>
                    
                  </tr>
                  </thead>
                  <tbody>
                  
                      <tr>
                        
                        
                        <?php
                       
						
                    for($d=0; $d<7;$d++){
						$new_start_time=strtotime(date("d-m-Y 00:00:00",strtotime($cudate))." +".$d." days");
						$shiftlist=$this->web->getAssignedShiftList($login_id,$new_start_time);
						
							
						//$gpl = $this->web->getBusinessGroupByUserId($shiftlist);
                        // $gplisted=$shiftl;
						 ?>
						<td> 
                        <?php
                        if(!empty($shiftlist[0])){
						$shiftl=explode(",", $shiftlist[0]->shift);
							foreach($shiftl as $shiftl){
								$gpl = $this->web->getBusinessGroupByUserId($shiftl);
						  echo $gpl[0]->name."<br>".$gpl[0]->shift_start."<br>".$gpl[0]->shift_end; 
							} 
                            
                        }else {
                            $uname=$this->web->getNameByUserId($login_id);
						     $group = $this->web->getBusinessGroupByUserId($uname[0]->business_group);
							  echo $group[0]->name."<br>".$group[0]->shift_start."<br>".$group[0]->shift_end;  
								
							}
                        
                        
                        
						 }
                                  //echo "<td>$gplisted <br></td>";
						?>
                        </td>
                        
                        
                       
                      </tr>
                    
                    
                  
                </tbody>
              </table>
              
               
                  
                  
                  
                  
                  
                  
                  
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
      
    </section> <?php 
    }?>
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

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">Edit Authority</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <div id="modform">
          
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
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
   var table = $('#example2').DataTable({
     "responsive": true,
      "autoWidth": false,
      "paging": false
    });
   
  });
</script>
<script>
function mclick(id,bid){
  var add_eroll = "add_roll";
  $.ajax({
      type: "POST",
      url: "User/getajaxRequest",
      data: {id,bid,add_eroll},
    success: function(response){
      $('#modform').html(response);
    }
    })
}
</script>

<script type="text/javascript">
 
  function actionPersonal(id,bid){
    $.ajax({
    type: "POST",
    url: "User/GenPersonalLogin_new",
    data: {id,bid},
  success: function(){
    $('#pt'+id).text("Login generated");
  }
});
  }

  function active(id){
    $.ajax({
      type: "POST",
      url: "User/activateUser",
      data: {id},
    success: function(id1){
      $('#stat'+id1).html('<button class="btn btn-success" onclick="inactive(' + id1 + ')">Active</button>');
    }
    })
  }

  function inactive(id){
    $.ajax({
      type: "POST",
      url: "User/inactivateUser",
      data: {id},
    success: function(id1){
      $('#stat'+id1).html('<button class="btn btn-danger" onclick="active('+ id1 + ')">Inactive</button>');
    }
    })
  }
</script>


<script>$(document).ready(function () { 
$('.nav-link').click(function(e) {
$('.nav-link').removeClass('active');        
$(this).addClass("active");

});
});
$(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })

  })
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

function setAssign(name,count,val){
    $("input[name='user_changed_"+count+"']").val(1);
    $("input[name='"+name+count+"']").val(val.value);
}
function startChange(e){
      //alert(e.target.value);
      $('#end_date').attr('min', e.target.value);
	   //$('#end_date').attr('max', e.target.value);
    }
    function endChange(e){
      //alert(e.target.value);
      $('#start_date').attr('max', e.target.value);
    }
function saveAll(){
  $("#saveAllForm").submit();
}
</script>
<script>
  function delete_work(id,uid,fromdate){
    $.ajax({
      type: "POST",
      url: "delete_shift_rost",
      data: {id,uid,fromdate},
     success: function(){
    $('#delete'+id).text("deleted");
    }
	
    })
  }
</script>
</body>
</html>
