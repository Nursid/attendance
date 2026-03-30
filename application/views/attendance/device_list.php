
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
    
    
<div id="loader" style="
    display:none;
    position:fixed;
    top:0; left:0;
    width:100%; height:100%;
    background:rgba(0,0,0,0.5);
    z-index:9999;
    text-align:center;
    color:#fff;
    font-size:20px;
    padding-top:200px;
">
    Loading... Please wait
</div>





    
    
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
         
          <div class="col-sm-12">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Add Device</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
<?php
   if($this->session->userdata()['type']=='B' || $this->session->userdata()['type']=='P' ){ 
	  
	  if($this->session->userdata()['type']=='P'){
      // $busi=$this->web->getBusinessbyUser($this->web->session->userdata('login_id'));
      // $id=$busi[0]->business_id;
      $bid = $this->session->userdata('empCompany');
      $role=$this->web->getRollbyid($this->web->session->userdata('login_id'),$bid);
  
    } else {
      $bid=$this->web->session->userdata('login_id');
    }
    ?>
    <!-- Main content -->
    <section class="content">
        <?php
      if($this->session->userdata()['type']=='B' || $role[0]->att_setting=="1" || $role[0]->type=="1"){?>
      
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Add Device</h3><br>
                <span style="color: red"><?php echo $this->session->flashdata('msg');?></span>
              </div>
              <?php 
			  // $bid=$this->web->session->userdata('login_id');
			   ?>
              <form action="<?php echo base_url('User/add_device')?>" method="post">
              <div class="card-body">
                <div class="row">
                  <div class="from-group col-md-3">
                    <label for="name">Device Name</label>
                    <input type="text" class="form-control" name="name" placeholder="Enter a name" id="name" required>
                    <input type="hidden" name="bid" value="<?php echo $bid;?>">
                  </div>
                  
                  <div class="from-group col-md-2">
                    <label for="serial">Serail No</label>
                    <input type="text" class="form-control" name="serial" placeholder="Enter Serail No" id="serial" required>
                  </div>
                  
                  <div class="from-group col-md-2">
                    <label for="type">Model</label>
                    <select name="model" class="form-control">
                                 
                                    <option value="0">MidApp</option>
                                    <option value="1">Syrotech</option>
                                      <option value="2">Secureye</option>
                                        <option value="3">Mantra</option>
                                </select>
                  </div>
                  <div class="from-group col-md-2">
                    <label for="type">Mode</label>
                    <select name="mode" class="form-control">
                                  
                                    <option value="0">Attendance</option>
                                    <option value="1">In</option>
                                      <option value="2">Out</option>
                                        <option value="3">Access</option>
                                </select>
                  </div>
                <div class="from-group col-md-3">
                  <button class=" btn btn-success mt-4 mx-auto">Add Now</button>
                  </div>
                </div>
              </div>
              </form>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>

   
  
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <div class="card card-primary">
                           <div class="card-body">
<div class="mt-3">
<div><button onclick="openLogModal()" class="btn btn-warning">
    Sync Attendance Log
</button>
        <button onclick="callDeviceAPI('GETCMDUSERDATA')" class="btn btn-warning">Sync User Data</button>
        <button onclick="getUserData()" class="btn btn-primary">
    View Users</button> 
       <button onclick="callDeviceAPI('CLEARALLUSER')" class="btn btn-danger">Clear User</button>
       <button onclick="callDeviceAPI('CLEARADMIN')" class="btn btn-danger">Clear Admin</button>
      <button onclick="callDeviceAPI('CLEARALLATTENDANCELOG')" class="btn btn-danger">Clear Log</button>
  <button onclick="callDeviceAPI('OPENDOOR')" class="btn btn-danger">Open Door</button>

<button onclick="callDeviceAPI('REBOOTDEVICE')" class="btn btn-danger">Restart Machine</button>
</div>
<br> <div>
<button onclick="callDeviceAPI('SETTIME')" class="btn btn-danger">Set Time</button>
<button onclick="callDeviceAPI('ENABLEDEVICE')" class="btn btn-danger">Set TimeZone</button>
<button onclick="callDeviceAPI('DISABLEDEVICE')" class="btn btn-danger">Set Event</button>

</div>

    </div>



    </div>
 </div> </div> </div>








        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Device List</h3>
              </div>
              <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                   <th><input type="checkbox" id="select_all"></th>
                    <th>S.No</th>
                     <th>Model</th>
                    <th>Mode</th>
                     <th>Name</th>
                     <th>Serial No</th>
                      <th>Status</th> 
                     <th>Last Update</th>
                   <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                      <?php
					   
			    
			  
			 
                      $res=$this->web->getdevice($bid);
                      $count=1;
                      foreach($res as $res){
                      ?>
                      <tr>
                   <td>
                       <input type="checkbox" class="device_checkbox"
                       value="<?php echo $res->deviceid; ?>">
                          </td>
                        <td><?php echo $count++; ?></td>
                        <td><?php  $model=$res->model; 
                        if($model==0){
                           echo "MidApp";
                        }elseif($model==1){
                           echo "Syrotech";
                        }
                         elseif($model==2){
                           echo "Secureye";
                        } 
                        else{
                           echo "Mantra";
                        }
                        
                        ?>
                        
                        </td>
                        <td><?php $mode= $res->mode; 
                         
                        if($mode==0){
                           echo "Attendance";
                        }elseif($mode==1){
                           echo "IN";
                        }
                         elseif($mode==2){
                           echo "Out";
                        } 
                        else{
                           echo "Access Control" ;
                        }
                        
                        
                        
                        ?></td>
                        <td><?php echo $res->name; ?></td>
                        <td><?php echo $res->deviceid; ?></td>
            <td>   <button onclick="callDeviceAPI('GETDEVICEONLINESTATUS')" >Check Online</button> </td>
                       <td>

                       <?php echo date('d-M-y',$res->update_date); ?>
                       <br> 
                       <?php echo date('h:i',$res->update_date); ?>
                       </td>
                       
                        <td id="delete<?php echo $res->id; ?>">
                        <button type="button" class="btn btn-info btn-circle btn-x" data-toggle="modal" data-target="#myModal" onclick="mclick('<?php echo $res->id; ?>')">
                            <i class="fa fa-edit" style="color:white"></i>
                          </button>
                          <button class="btn btn-danger" onclick="delete_device('<?php echo $res->id; ?>')" >
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
      </div><!-- /.container-fluid -->  <?php
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


<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">Edit Device</h4>
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


<div id="logModal" style="
    display:none;
    position:fixed;
    top:50%; left:50%;
    transform:translate(-50%, -50%);
    width:320px;
    background:#fff;
    z-index:10000;
    padding:20px;
    border-radius:10px;
    box-shadow:0 0 10px rgba(0,0,0,0.3);
">

    <h4 style="margin-bottom:15px;">Select Date Range</h4>

    <label style="font-size:13px;">From Date:</label>
    <input type="datetime-local" id="from_date" style="
        width:100%;
        padding:6px;
        margin-bottom:10px;
    ">

    <label style="font-size:13px;">To Date:</label>
    <input type="datetime-local" id="to_date" style="
        width:100%;
        padding:6px;
        margin-bottom:15px;
    ">

    <div style="text-align:right;">
        <button onclick="submitAttendanceLog()" class="btn btn-primary btn-sm">
            Submit
        </button>
        <button onclick="closeLogModal()" class="btn btn-danger btn-sm">
            Close
        </button>
    </div>

</div>

<div id="userDataModal" style="
    display:none;
    position:fixed;
    top:50%; left:50%;
    transform:translate(-50%, -50%);
    width:85%;
    max-height:85%;
    background:#fff;
    z-index:10000;
    padding:15px;
    border-radius:10px;
    box-shadow:0 0 10px rgba(0,0,0,0.3);
    overflow:auto;
">

    <button onclick="closeUserModal()" style="float:right;">Close</button>

    <h4>User Data</h4>

    <!-- Summary -->
    <div id="deviceSummary" style="margin-bottom:10px; font-weight:bold;"></div>

    <!-- Table -->
    <table id="userDataTable" class="display" style="width:100%">
    <thead>
        <tr>
            <th>S.No</th>
            <th>Enroll ID</th>
            <th>Name</th>
            <th>Role</th>
            <th>Finger</th>
            <th>Face</th>
            <th>Card</th>
            <th>Password</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>
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


<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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
	
  var add_bio_data = "add_device";
  $.ajax({
      type: "POST",
      url: "User/getajaxRequest",
      data: {data,add_bio_data},
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
  function delete_device(id){
    $.ajax({
      type: "POST",
      url: "User/delete_device",
      data: {id},
     success: function(){
    $('#delete'+id).text("deleted");
    }
	
    })
  }
</script>

<script>
document.getElementById('select_all').addEventListener('click', function(){
    document.querySelectorAll('.device_checkbox').forEach(cb => {
        cb.checked = this.checked;
    });
});
</script>

<script>

function callDeviceAPI(apiName, extraData = {}){

    let devices = [];

    document.querySelectorAll('.device_checkbox:checked').forEach(function(el){

        if(el.value.trim() !== ""){

            let obj = {
                DEVICESLNO: el.value.trim()
            };

            // merge extra data (like dates)
            Object.assign(obj, extraData);

            devices.push(obj);
        }
    });

    if(devices.length === 0){
        alert("Select at least one device");
        return;
    }

    document.getElementById("loader").style.display = "block";

    fetch("<?php echo base_url('device/common_api'); ?>", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            api: apiName,
            data: devices
        })
    })
    .then(res => res.json())
    .then(data => {

        document.getElementById("loader").style.display = "none";

        console.log(data);

        if(data.msg){
            alert(data.msg);
        } else {
            alert("No response from API");
        }

    })
    .catch(err => {
        document.getElementById("loader").style.display = "none";
        console.error(err);
        alert("Error in API");
    });
}

</script>

<script>

// Open modal
function openLogModal(){

    let selected = document.querySelectorAll('.device_checkbox:checked');

    if(selected.length === 0){
        alert("Select at least one device");
        return;
    }

    document.getElementById("logModal").style.display = "block";
}

// Close modal
function closeLogModal(){
    document.getElementById("logModal").style.display = "none";
}

// Format date for API
function formatDateTime(dt){
    return dt.replace("T", " "); // fast & correct
}

// Submit function
function submitAttendanceLog(){

    let fromDate = document.getElementById("from_date").value;
    let toDate   = document.getElementById("to_date").value;

    if(!fromDate || !toDate){
        alert("Select both dates");
        return;
    }

    // Call COMMON API
    callDeviceAPI("SENDCMDATTENDANCELOG", {
        FROMDATE: formatDateTime(fromDate),
        TODATE: formatDateTime(toDate)
    });

    closeLogModal();
}

</script>

<script>

let userTable = null;



function getUserData(){

    let devices = [];

    document.querySelectorAll('.device_checkbox:checked').forEach(function(el){
        if(el.value.trim() !== ""){
            devices.push({ DEVICESLNO: el.value.trim() });
        }
    });

    if(devices.length === 0){
        alert("Select at least one device");
        return;
    }

    document.getElementById("loader").style.display = "block";

    fetch("<?php echo base_url('device/common_api'); ?>", {
        method: "POST",
        headers: {"Content-Type": "application/json"},
        body: JSON.stringify({
            api: "GETUSERDATA",
            data: devices
        })
    })
    .then(res => res.json())
    .then(data => {

        console.log("FULL RESPONSE:", data);

        document.getElementById("loader").style.display = "none";

        // ? REMOVE THIS BLOCK (IMPORTANT)
        // if(data.status !== "Success"){ return; }

        let tbody = document.querySelector("#userDataTable tbody");
        tbody.innerHTML = "";

        if(data.data && data.data.length > 0){

            data.data.forEach((row, index) => {

    // Role
    let role = (row.Admin == 0) ? "User" : "Admin";

    // Finger check
    let finger = (row.FingerData && Object.keys(row.FingerData).length > 0) 
        ? "Yes" 
        : "No";

    // Face image
    let faceHtml = "No";

    if(row.FaceData && typeof row.FaceData === "object" && Object.keys(row.FaceData).length > 0){

        // If API returns base64 inside object (example: FaceData.data)
        let base64 = row.FaceData.data || "";

        if(base64){
            faceHtml = `<img src="data:image/png;base64,${base64}" width="40" height="40"/>`;
        } else {
            faceHtml = "Yes";
        }
    }

    // Card
    let card = row.Card && row.Card.data ? row.Card.data : "";

    // Password
    let pwd = row.PWD && row.PWD.data ? row.PWD.data : "";

    let tr = `
        <tr>
            <td>${index+1}</td>
            <td>${row.EnrollmentNo || ''}</td>
            <td>${row.Name || ''}</td>
            <td>${role}</td>
            <td>${finger}</td>
            <td>${faceHtml}</td>
            <td>${card}</td>
            <td>${pwd}</td>
        </tr>
    `;

    tbody.innerHTML += tr;
});
        } else {
            tbody.innerHTML = "<tr><td colspan='7'>No Data Found</td></tr>";
        }

        // ? FORCE SHOW MODAL (NO CONDITION)
        document.getElementById("userDataModal").style.display = "block";

        // ? SAFE DATATABLE INIT
        try {
            if($.fn.DataTable){
                if($.fn.DataTable.isDataTable('#userDataTable')){
                    $('#userDataTable').DataTable().destroy();
                }

                $('#userDataTable').DataTable({
                    pageLength: 25
                });
            }
        } catch(e){
            console.log("DataTable error:", e);
        }

    })
    .catch(err => {
        document.getElementById("loader").style.display = "none";
        console.error(err);
        alert("Error in API");
    });
}





function closeUserModal(){
    document.getElementById("userDataModal").style.display = "none";
}
</script>


</body>
</html>
