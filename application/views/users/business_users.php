
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
              <li class="breadcrumb-item active"Add >Users</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <?php
    if($this->session->userdata()['type']=='A'){
    ?>
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <div class="card card-success">
              <div class="card-header">
                <h3 class="card-title">Premium Users</h3>
              </div>
              <div class="card-body">
              <table id="example3" class="table table-bordered table-responsive">
                  <thead>
                  <tr>
                     <th>SN</th>
                     <th>id</th>
                     <th>Name</th>
                     <th>Mobile</th>
                     <th>Address</th>
                     <th>Premium Type</th>
                     <th>Validity</th>
                    <th>Login</th>
                    <th>Linked Login</th>
                    <!-- <th>Referal</th>-->
                    <th>Detail</th>
                    <th>Status</th>
                     
                     
                  </tr>
                  </thead>
                  <tbody>
                      <?php
            
                      $count=1;
                      foreach($premium as $pre){
                      ?>
                      <tr>
                          <td>
                              <?php  
                              echo $count++; 
                              ?>
                          </td>
                        <td><?php 
						echo $pre->id ;
					    echo " &nbsp;&nbsp;". $pre->m_id ?>
						</td>
						 <td><?php echo $pre->name;
						 $links=$pre->linked;
						 $unames = $this->web->getnameBymobile($links);
					    echo "<br> (".$unames[0]->name.")";
						 ?></td>
                         
                       <td><?php echo $pre->mobile;
						 echo "<br>(".$pre->linked.")"?>
                         </td>
                        <td><?php echo $pre->address?></td>
                        <td><?php 
                        if($pre->validity!="" && $pre->validity>time()){
                          echo "Premium";
                        }else{
                          if($pre->start_date!="" && strtotime('+10 day',$pre->start_date)>time()){
                            echo "Trial";
                          }else{
                            echo "Basic";
                          }
                        }
                        if($pre->$premium==3){
                          echo "Requested";
                        }
                        ?></td>
                        
                        
                   <td><?php if($pre->start_date!=""){echo '<input type="date" value="'.date("Y-m-d",(Int)$pre->start_date).'" onchange="changeStartDate(event,'.$pre->id.');"/>';
                        }else{
                          echo '<input type="date" onchange="changeStartDate(event,'.$pre->id.');"/>';
                      }?>
                      <br> <br>
                      <?php if($pre->validity!=""){
                          echo '<input type="date" value="'.date("Y-m-d",(Int)$pre->validity).'" onchange="changeDate(event,'.$pre->id.');"/>';
                      }else{
                          echo '<input type="date" onchange="changeDate(event,'.$pre->id.');"/>';
                      }?></td>
                      <td>
                          <div >
                          <span id="bt<?php echo $pre->id; ?>">
                          <?php $check = $this->web->checkGeneratedLogin($pre->id);
                                if(empty($check)){
                                ?>
                          <button class="btn btn-success" onclick="action('<?php echo $pre->id; ?>')">Generate login</button>
                                <?php 
                                  }else{
                                ?>
                          Login generated
                                <?php } ?>
                                
                               <br><br> </span> 
                            
                      <!--  </td>
                      <td id="stat<?php echo $pre->id; ?>">-->
                      <span id="stat<?php echo $pre->id; ?>">
                          <?php
                              if ($check['status'] == "1") {
                          ?>    
                            <button class="btn btn-success" onclick="inactive('<?php echo $pre->id; ?>')">Active</button>
                          <?php
                              }else{
                          ?>
                            <button class="btn btn-danger" onclick="active('<?php echo $pre->id; ?>')">Inactive</button>
                          <?php
                            }
                          ?>
                        </span>   </div>
                        </td>
                       <td>
                          <div >
                          <?php 
						  
						   $links=$pre->linked;
						 $unames = $this->web->getnameBymobile($links);
					    $linked_id=$unames[0]->id;
						  ?>
                          
                          
                          
                          <span id="bt2<?php echo $linked_id; ?>">
                          <?php $check2 = $this->web->checkGeneratedLogin($linked_id);
                                if(empty($check2)){
                                ?>
                          <button class="btn btn-success" onclick="action2('<?php echo $linked_id; ?>')">Generate login</button>
                                <?php 
                                  }else{
                                ?>
                          Login generated
                                <?php } ?>
                                
                               <br><br> </span> 
                            
                      <!--  </td>
                      <td id="stat<?php echo $pre->id; ?>">-->
                      <span id="stat2<?php echo $linked_id;?>">
                          <?php
                              if ($check2['status'] == "1") {
                          ?>    
                            <button class="btn btn-success" onclick="inactive2('<?php echo $linked_id; ?>')">Active</button>
                          <?php
                              }else{
                          ?>
                            <button class="btn btn-danger" onclick="active2('<?php echo $linked_id; ?>')">Inactive</button>
                          <?php
                            }
                          ?>
                        </span>   </div>
                        </td>
                       
               
                   
                   <td>
                 <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal" onclick="mclick('<?php echo $pre->id; ?>')">
                            <i class="fa fa-info-circle"></i>
                          </button>
                   </td> 
                      
                    <td id="delete<?php echo $pre->id; ?>">
                       <?php
					   $status=$pre->deleted;
					   if ($status==0) {
						   echo Active; ?>
						    <button class="btn"  onclick="deleted_user('<?php echo $pre->id; ?>')" >
                          <i class="fa fa-times" style="color:red"></i>
                          </button>
						  <?php  
					   }
					   else 
					   { echo Deleted;} 
					  ?>
                       </td>     
                        
                        
                        
                      </tr>
                      <?php 
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

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">Business Detail </h4>
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
  function changeStartDate(e,id){
    $.ajax({
    type: "POST",
    url: "User/changeStartDate",
    data: {id : id,startDate: e.target.value},
    success: function(){
      
    }
    });
  }
  function changeDate(e,id){
    $.ajax({
    type: "POST",
    url: "User/changeDate",
    data: {id : id,validity: e.target.value},
    success: function(){
      
    }
    });
  }
  $(function () {
    var table = $('#example3').DataTable({
     "responsive": true,
      "autoWidth": true,
    });
  });
</script>
<script>
$('.toastsDefaultSuccess').click(function() {
      $(document).Toasts('create', {
        class: 'bg-success', 
        title: 'Toast Title',
        subtitle: 'Subtitle',
        body: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
      })
    });
</script>
<script type="text/javascript">
  function action(id){
    $.ajax({
    type: "POST",
    url: "User/GenLogin",
    data: {id},
  success: function(){
    $('#bt'+id).text("Login generated");
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

<script type="text/javascript">
  function action2(id){
    $.ajax({
    type: "POST",
    url: "User/GenLogin",
    data: {id},
  success: function(){
    $('#bt2'+id).text("Login generated");
  }
});
  }

  function active2(id){
    $.ajax({
      type: "POST",
      url: "User/activateUser",
      data: {id},
    success: function(id1){
      $('#stat2'+id1).html('<button class="btn btn-success" onclick="inactive2(' + id1 + ')">Active</button>');
    }
    })
  }

  function inactive2(id){
    $.ajax({
      type: "POST",
      url: "User/inactivateUser",
      data: {id},
    success: function(id1){
      $('#stat2'+id1).html('<button class="btn btn-danger" onclick="active2('+ id1 + ')">Inactive</button>');
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
<script>
  function deleted_user(id){
    $.ajax({
      type: "POST",
      url: "User/delete_user",
      data: {id},
     success: function(){
    $('#delete'+id).text("deleted");
    }
	
    })
  }
</script>



<script>
function mclick(data){
  var add_detail = "att_detail";
  $.ajax({
      type: "POST",
      url: "User/getajaxRequest",
      data: {data,add_detail},
    success: function(response){
      $('#modform').html(response);
    }
    })
}
</script>
</body>
</html>
