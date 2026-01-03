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
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
         
          <div class="col-sm-12">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Monthly Report</li>
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
                <h3 class="card-title">Monthly Report</h3>
              </div>
              <div class="card-body">
              
              
               <h5> Select Date Range
			     </h5> 
			   
              
              <div class="row">
    <div class="col-lg-5 float-left">
     <?php 
		  
		   $start=$_GET['start'];
			  $end=$_GET['end'];
		  $cudate = date("Y-m-d"); 
		  ?>
      <form action="" method="GET">
        <div class="row">
          <div class="col-5 ">
            <input type="date" name="start"  value="<?php
			
			if ($start!=''){ echo $start ; }else {echo $cudate;} ?>" class="form-control">
            
          </div>
          <div class="col-5">
            <input type="date" name="end"  value="<?php
			
			if ($end!=''){ echo $end ; }else {echo $cudate;} ?>"class="form-control">
           
          </div>
        
          <div class="">
            <button type="submit" class="btn btn-success btn-fill btn-block">Show</button>
          </div>
        </div>
      </form>
    </div>
  </div>
              
           <br>   
              
              <?php 
			  
			 // $sdate=1638349410;
			 // $edate=1640966610 ;
			  $start=$_GET['start'];
			  $end=$_GET['end'];
			  if($start!='' && $end!='') {
			  
			  $stdate=strtotime($start);
			  $endate=strtotime($end);
		  
			  ?>
              <h5>Attendance for Date:-<?php echo date("d-M-Y ",$stdate)?> to Date:- <?php echo date("d-M-Y ",$endate)?> </h5>
              
              
              <div align="right"> 
                 <input type="button" onClick="export_datas()" value="Export To Excel" />
         <input type="button"  id="btnExport" value="Export To Pdf" onclick="Export()" />
      
    
               <br>
               </div>
              
              
              <table id="example1" class="table table-bordered table-responsive">
                  <thead>
                  <tr>
                     <th>SNo.</th>
                     <th>Empcode</th>
                    <th>Name</th>
                   <th>TotalPresent</th>
                    <th>Total Absent</th>
                    <th>Total Weakly Off</th>
                     <th>Total Holiday</th>
                     <?php 
					   
					  for($i=$stdate; $i<=$endate;$i=$i+86400){
					  echo  "<th>".date("d",$i)."</th>"; } ?>
				  </tr>
                  </thead>
                  <tbody>
                  
              
                    <?php
			      
                     $res=$this->web->getActiveEmployeesList($id=$this->web->session->userdata('login_id'));
					  $count=1;
                      foreach($res as $val){
					   ?>
                  
                   <?php 
				 
				  $start=$_GET['start'];
			      $end=$_GET['end'];
			     $uname = $this->web->getNameByUserId($val->user_id);
                  $id=$uname[0]->id;
				  $buid=$this->web->session->userdata('login_id');
		         $stdate=strtotime($start);
			      $endate=strtotime($end);
			    
				
				 //Holiday  Count
                 $holiday="SELECT count(id) as holiday FROM holiday WHERE status=1 and date BETWEEN $stdate and $endate and business_id='$buid'";
		       $hld= $this->db->query($holiday)->result();
			   $hlday=$hld[0]->holiday;
			    
			   
			   //Leave Count
			   
			   $Uleave="SELECT count(id) as uleave FROM leaves WHERE status=1 and uid='$id'";
		       $lv= $this->db->query($Uleave)->result();
			   $lev=$lv[0]->uleave;
			    // echo "Total Leaves".$lev.",,,";
			   
			   
			   
			   
			   // Weakly off Count
			   $uname = $this->web->getNameByUserId($id);
               $groups = $this->web->getUserGroup($uname[0]->business_group);
              $grp = array();
               $weekly_off = explode(",",$groups->weekly_off);
		     	foreach($weekly_off as $off){	   
               $offs = array_search( '1',$off);
      		 }
		
		for($n=0;$n<7;$n++) {
        $weeko=$weekly_off[$n];
	   if ($weeko=='1'){
        $m=$n+1;
		$dt = Array ();
       for($i=$stdate; $i<=$endate;$i=$i+86400) {
       if(date("N",$i)==$m)  {
	   $dt[] = date("l Y-m-d ", $i);
 	       }
            }

       //echo "Found ".count($dt). "  First lop<br>";
       for($i=0;$i<count($dt);$i++) {
	 //echo $dt[$i]."<br>";
       $w++;
            }
          }
	     }
	   
			   
			   
			   /// Present // absent
			   
			      $j=0;
			      $k=0;
 			    for($i=$stdate; $i<=$endate;$i=$i+86400){
				 $start_times = strtotime(date("d-m-Y 00:00:00",$i));
			     $end_times = strtotime(date("d-m-Y 23:59:59",$i));
			   // $holiday=$this->web->getHolidayByBusinessId($id,$start_times,$end_times);
				   $intime=$this->web->getUserAttendanceByDate($id,$start_times,$end_times);
				  if($intime[0]->io_time!=0 )
				  {
					  $j++;
					  }  
				  else {
					  $k++;
					  }
			        }
                  
                  
                  
                  ?>
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                      <tr>
                      
                     <td><?php echo $count++; ?></td>
                        <td><?php $uname = $this->web->getNameByUserId($val->user_id);
                                echo $uname[0]->emp_code; ?></td>
                        <td><?php $uname = $this->web->getNameByUserId($val->user_id);
                                echo $uname[0]->name; ?></td>
                         <td> <?php echo $j; ?> </td>
                          <td><?php echo $k; ?>  </td>
                           <td><?php echo $woo; ?>  </td>
                            <td><?php echo $hlday; ?>  </td>
                            <?php 
							 $firstdate=date("d-m-Y",$stdate);
						     $lastdate=date("d-m-Y",$endate);
					 for($i=$stdate; $i<=$endate;$i=$i+86400){
					  
						?>
                        <td>
                        <?php	
							$start_time = strtotime(date("d-m-Y 00:00:00",$i));
					$end_time = strtotime(date("d-m-Y 23:59:59",$i));	
                  $intime=$this->web->getUserAttendanceByDate($val->user_id,$start_time,$end_time);
				 if($intime[0]->io_time!=0 ){
                   foreach($intime as $intime){
               $attendanceintime=$intime->io_time;
				 $mode=$intime->mode;
				  echo date('h:i:A', $attendanceintime)."&nbsp;&nbsp;(".$mode. ")"; 
				   
				?> <br> <?php   }
				
				 echo "P"  ;
				 }else 
				{ echo " A"; }
				 
				?> 
                    </td>
                   <?php  } ?>
				     </tr>
                      <?php 
                      }
                      ?>
                     
                     
                     
                     
                    </tbody>
                    <tfoot>

                  </tfoot>
                </table>
              </div>
              
              <?php } 
			  
			  
			  
			  ?>
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
