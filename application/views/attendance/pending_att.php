<?php 

date_default_timezone_set('Asia/Kolkata');
?>
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
    <?php
  if($this->session->userdata()['type']=='P'){
    $getUserCompanies  = $this->web->getUserCompanies($this->session->userdata('login_id'));
    ?>
  
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mt-4">
        <div class="col-sm-2 ml-auto">
          <div class="form-group">
          <select class="form-control" id="selectBusiness" name="selectBusiness" onchange="switchCompany(this)">
            <?php 
              foreach($getUserCompanies as $empCompany){
                $businessSelected = "";
                if($this->session->userdata('empCompany')==$empCompany->bid){
                  $businessSelected = "selected";
                }
                echo "<option value='$empCompany->bid' $businessSelected>$empCompany->name</option>";
              }
            ?>
          </select>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php
  }
  
  ?>
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-12">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Aprove Pending Attendance</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <?php
  if($this->session->userdata()['type']=='B' || $this->session->userdata()['type']=='P' ){ 
	  
	  if($this->session->userdata()['type']=='P'){
      $bid = $this->session->userdata('empCompany');
      $role=$this->web->getRollbyid($this->web->session->userdata('login_id'),$bid);
		} else {
				$bid=$this->web->session->userdata('login_id');
		}
    ?>
      <!-- Main content -->
    <section class="content">
      <?php
        if($this->session->userdata()['type']=='B' || $role[0]->pending_att=="1" || $role[0]->type=="1"){?>
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <div class="card card-danger">
              <div class="card-header">
                <h3 class="card-title">Pending Attendance List</h3>
              </div>
              <div class="card-body">
              <div align="right">
                <a class="btn btn-success" href="User/verifyAllPending" role="button">Verify All</a>
                <a class="btn btn-danger" href="User/cancelAllPending" role="button">Cancel All</a>
              </div>
              <table id="example1" class="table table-bordered ">
                <thead>
                  <tr>
                    <th>SNo.</th>
                    <th>Name</th>
                    <th>Time</th>
                    <th>Remark</th>
                    <th>Location</th>
                    <th>Selfie</th>
                    <th>Action</th>
                  </tr>   
                </thead>
                <tbody>
              <?php
			        $pending=$this->web->getGpsByDate($bid);
			        
              if($this->session->userdata()['type']=='P'){
                $role=$this->web->getRollbyid($this->web->session->userdata('login_id'),$bid);
                if($role[0]->type!=1){
                  $departments = explode(",",$role[0]->department);
                  $sections = explode(",",$role[0]->section);
                  $team = explode(",",$role[0]->team);
                  
                  foreach ($pending as $key => $dataVal) {
                    $uname = $this->web->getNameByUserId($dataVal->user_id);
                    $roleDp = array_search($uname[0]->department,$departments);
                    $roleSection = array_search($uname[0]->section,$sections);
                     $roleTeam = array_search($dataVal->user_id,$team);
                    if(!is_bool($roleTeam) || !is_bool($roleSection) || !is_bool($roleDp)){
                      
                    }else{
                      unset($pending[$key]);
                    }
                  }  
                }
              }
			        if($pending[0]->io_time!=0 ){
              $count=1;
              foreach($pending as $gps){
              ?>    
                  <td><?php echo $count++; ?></td>
                  <td>
                    <?php $uname = $this->web->getNameByUserId($gps->user_id);
                    echo $uname[0]->name; ?>
                  </td> 
                  <td> <?php  
                    $gps_time= $gps->io_time ;
                    $gps_mode= $gps->mode ;
                    echo date('d-M-Y',$gps_time)."<br>". date('h:i:A',$gps_time)."<br>".$gps_mode; 
                  ?>
                  </td>
                  <td>
                  <?php  
                    echo $gps->comment ;
                    ?>
                  </td>
                  <td width="25%"><?php  
                    echo '<a href="http://maps.google.com/?q='.$gps->latitude.','.$gps->longitude.'" target="_blank">'.$gps->location.'</a>';
                  ?>
                  </td> 
                  <td>
                  <?php  $file_name=$gps->selfie ;
                  if(!empty($file_name)){
				  echo '<a href="javascript:void(0);" onclick="newPopup(\''.$file_name.'\');">Selfie</a>'; 
				  } 
				 
				 ?> </td>
				  
                  <td  width="20%" id="verify<?php echo $gps->id; ?>">
                      
                      <?php
                       $id=$gps->id;
						 $uid=$gps->user_id; 
						 $fromdate=date("d-m-Y" , $gps->io_time);
                      
                      
                      ?>
                    <button class="btn btn-danger" onclick="verify('<?php echo $id ;?>' ,' <?php echo $uid ;?>',' <?php echo $fromdate ;?>')" >Verify</button>  &nbsp;&nbsp;
                    <button class="btn btn-success" onclick="cancelatt('<?php echo $id ;?>' ,' <?php echo $uid ;?>',' <?php echo $fromdate ;?>')" >Cancel</button>
                  </td>
                </tr>
               <?php 
              }}
              ?>
                </tbody>
              <tfoot>
            </tfoot>
          </table>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
      <?php
        }else{?>
          <div class="container-fluid">
            <div class="col-sm-4 mx-auto">
              <h4>Not Authorized to Access This Page</h4>
            </div>
          </div>
        <?php 
      }?>
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.22/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>

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
function newPopup(url) {
    popupWindow = window.open(
        url,'popUpWindow','height=700,width=800,left=10,top=10,resizable=yes,scrollbars=no,toolbar=no,menubar=no,location=no,directories=no,status=yes')
}
</script>
<script>
function verify(id,uid,fromdate){
    $.ajax({
      type: "POST",
      url: "User/verifypending",
      data: {id,uid,fromdate},
     success: function(){
    $('#verify'+id).text("verified");
    }
    })
  }
  </script>
<script>
  function cancelatt(id,uid,fromdate){
    $.ajax({
      type: "POST",
      url: "User/cancelpending",
      data: {id,uid,fromdate},
     success: function(){
    $('#verify'+id).text("Canceled");
    }
	
    })
  }
</script>


<script>

function export_datas(){
	let data=document.getElementById('example1');
	var fp=XLSX.utils.table_to_book(data,{sheet:'Report'});
	XLSX.write(fp,{
		bookType:'xlsx',
		type:'base64'
	});
	XLSX.writeFile(fp, 'Employee Attendance.xlsx');
}
</script>

<script type="text/javascript">
        function Export() {
            html2canvas(document.getElementById('example1'), {
                onrendered: function (canvas) {
                    var data = canvas.toDataURL();
                    var docDefinition = {
                        content: [{
                            image: data,
                            width: 500
                        }]
                    };
                    pdfMake.createPdf(docDefinition).download("Employee Attendance.pdf");
                }
            });
        }
    </script>

</body>
</html>
