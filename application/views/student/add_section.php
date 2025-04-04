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
<?php $this->load->view('student/student_menu')?>
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
         
          <div class="col-sm-12">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Add Section</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>


<?php 

$buid=$this->web->session->userdata('login_id');
?>
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column - Add Section Form -->
          <div class="col-md-4">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Add Section</h3><br>
                <span style="color: red"><?php echo $this->session->flashdata('msg');?></span>
              </div>
              <form action="<?php echo base_url('User/add_newsection')?>" method="post">
              <div class="card-body">
                <div class="row">
                  <div class="col-12 mb-3">
                    <?php
                        $data = $this->web->getBusinessDepByBusinessId($buid);
                    ?>

                    <select class="select2" id="departs" style="width: 100%;" name="dept[]" multiple="multiple" data-placeholder="Select Branches">
                      <option value="" disabled>Select Branch(es)</option>

                        <?php foreach($data as $key => $val){
                          
                            echo "<option value=" . $val->id . ">" .$val->name."</option>";                          
                        } ?>
                    </select>
                  </div>
                  
                  <!-- <div class="col-12 mb-3">
                    <select class="select2" id="sdeparts" data-placeholder="Select a Session" style="width: 100%;" name="session[]" multiple="multiple">
                      <option value="" disabled>Select Batch(es)</option>
                    </select>
                  </div> -->
                  
                  <div class="col-12 mb-3">
                    <input type="text" class="form-control" name="name" placeholder="Enter a name" id="depart" required>
                  </div>

                  <input type="hidden" class="form-control" name="bid" value="<?php echo $buid ; ?>">
                  
                  <div class="col-12">
                    <button type="submit" class="btn btn-danger btn-block">Add Now</button>
                  </div>
                </div>
              </div>
              </form>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>

          <!-- right column - Section List -->
          <div class="col-md-8">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Section List</h3>
              </div>
              <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>S.No</th>
                    <th>Branch</th>
                    <th>Batch </th>
                    <th>Section</th>
                    <th>Actions</th>
                  </tr>
                  </thead>
                  <tbody>
                      <?php
                      $res=$this->web->getall_S_sectionbyid($buid);
                      $count=1;
                      foreach($res as $res){
                      ?>
                      <tr>
                        <td><?php echo $count++?></td>
                       
                        <td><?php $dname=$this->web->getBusinessDepByUserId($res->dep_id); 
                                  echo $dname[0]->name;
                                // echo $res->dep_id;
                        ?>
                        </td>
                         <td><?php $sname=$this->web->getSessionById($res->session_id); 
                                  echo $sname[0]->session_name;
                                  ?></td>

                         <td><?php echo $res->name."(Code:- ".$res->id.")"; ?></td>
                          <td id="delete<?php echo $res->id; ?>">
                          <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal" onclick="mclick('<?php echo $res->id; ?>')">
                            <i class="fas fa-edit" style="color:white"></i>
                          </button>
                          <button class="btn btn-danger btn-sm" onclick="delete_classname('<?php echo $res->id; ?>')" >
                          <i class="fa fa-times" style="color:white"></i>
                          </button>
                        </td>
                      </tr>
                      <?php 
                      }
                      ?>
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
        <h4 class="modal-title" id="myModalLabel">Edit Section</h4>
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
   var table = $('#example1').DataTable({
     "responsive": true,
      "autoWidth": false,
    });
   
  });
</script>
<script>
function mclick(data){
  var add_section_data = "add_section";
  $.ajax({
      type: "POST",
      url: "User/getajaxRequest",
      data: {data,add_section_data},
    success: function(response){
      $('#modform').html(response);
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
  function delete_classname(id){
    $.ajax({
      type: "POST",
      url: "User/delete_S_Section",
      data: {id},
     success: function(){
    $('#delete'+id).text("deleted");
    }
	
    })
  }
</script>

<script>
$(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })
  });
 
 

 $('#departs').on('change', function() {
  var id = $(this).val();
  var datatypes_session = "sessionlist";
  $.ajax({
    type: "post",
    url: "User/getajaxRequest",
    data: {id,datatypes_session},
    success: function(data){
      $('#sdeparts').html(data);
    }
  });
});
</script>











</body>
</html>
