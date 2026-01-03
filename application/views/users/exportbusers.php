
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
              
              
                <div align="right"> 
                 <input type="button" onClick="export_datas()" value="Export To Excel" />
        <!-- <input type="button"  id="btnExport" value="Export To Pdf" onclick="Export()" />-->
      
    
               <br>
               </div>
 
 
              <div class="card-body">
              <table id="example1" class="table table-bordered table-responsive">
                  <thead>
                  <tr>
                     <th>SN</th>
                    <th>Name</th>
                     <th>Mobile</th>
                     <th>Address</th>
                     <th>Premium Type</th>
                     <th>Start Date</th>
                     <th>end Date</th>
                    <th>Login</th>
                   <th>Linked Login</th>
                  
                    <th>Total Atte</th>
                    <th>Total Staff</th>
                     <th>Referal</th>
                      <th>Licence</th>
                      <th>Status</th>
                     
                     
                  </tr>
                  </thead>
                  <tbody>
                      <?php
            
                      $counts=1;
					  
                     
					foreach($premium as $act){
						  $buid=$act->actbuis;
						 $activeb = $this->web->getuserByidStatus($buid);
						   foreach($activeb as $pre){
						       
						       
						         $att_all="SELECT count(id) as attend_all FROM attendance WHERE bussiness_id='$pre->id' ";
                      $atten= $this->db->query($att_all)->result();
                   $attendance_all= $atten[0]->attend_all;

				

                      ?>
                      <tr>
                          <td>
                              <?php  
                              echo $counts++; 
                              ?>
                          </td>
                        
						 <td><?php echo $pre->name;
						 $links=$pre->linked;
						 $unames = $this->web->getnameBymobile($links);
					    echo "<br>  (".$unames[0]->name.")";
						 ?></td>
                         
                       <td><?php echo $pre->mobile;
						 echo "<br> (".$pre->linked.")"?>
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
                        
                        
                   <td><?php echo date("Y-m-d",(Int)$pre->start_date); ?>
                        
						</td>
                        
                         <td><?php echo date("Y-m-d",(Int)$pre->validity); ?>
                        
						</td>
                      
                    <td>
                          <div >
                          
                          <?php 
						  $check = $this->web->checkGeneratedLogin($pre->id);
                               if(empty($check)){
                               
                             echo "Gen";
                                 }else{
								
								 echo "Not Gen";
                                 
								 } ?>   
                                 
                    
                      <br>
                          <?php
						  
                              if ($check['status'] == "1") {
                          echo "(active)";
                              }else{
                          echo "(inactive)";
							  }
                          ?>
                       
                        </td>
                     <td>
                          <div >
                          <?php 
						  
					   $links=$pre->linked;
						 $unames = $this->web->getnameBymobile($links);
					   $linked_id=$unames[0]->id;
						  ?>
                          
                          
                          
                          <span >
                          <?php	 $check2 = $this->web->checkGeneratedLogin($linked_id);
                               if(empty($check2)){

                                echo "Not Gen";
                                  }else{
                                 echo  "Gen";
                                 }
							    ?>
                                
                               <br><br> </span> 
                            
                      
                      <span >
                          <?php
                             if ($check2['status'] == "1") {
                            echo "(active)";
                              }else{
                          echo "(inactive)";
                           }
                          ?>
                        </span>   </div>
                        </td>
                      
               
                  
                   <td><?php 
				    echo $attendance_all;
				   ?>
                 
                   </td>
                      
                  
                      
                    <td>
                    <?php $act="SELECT count(id) as actemp FROM user_request WHERE business_id='$pre->id' and left_date ='' ";
                      $aemp= $this->db->query($act)->result();
                    $active= $aemp[0]->actemp;  
                       echo $active;   
                       ?> 
                       </td>
                       <td>
                       <?php echo $ref=$pre->reference;
						 $unames = $this->web->getnameBymobile($ref);
					    echo "<br>  (".$unames[0]->name.")"; 
						?>
                       
                       </td>
                       <td>
                     <?php $licence = $this->web->getactivelicence($pre->id);
					//echo $id;
				   $assign_id=$licence[0]->assign_id;
					 $assigned_by = $this->web->getNameByAssignId($assign_id);
					  echo $assigned_by[0]->name;                     
					  ?>
					    </td>
                       <td>
                        <?php  if($pre->deleted=="0"){echo "Live";}
						else{
							echo "Deleted";
							}
						?>
                       </td>
                       
                      </tr>
                      <?php 
                      }}
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

<script src="<?php echo base_url('adminassets/dist/js/demo.js')?>"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.3.0/exceljs.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/fixedcolumns/4.2.1/js/dataTables.fixedColumns.min.js"></script>



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

<script>
function export_datasold(){
      var wb = new ExcelJS.Workbook();
      var sh = wb.addWorksheet("Report");
      <?php
	  // if (!empty($salEmpList)) {
      
	 ?>
      sh.columns = [
        {header: 'SNo.', key: 'SNo', width: 10},
        {header: 'name', key: 'name', width: 40,},
        {header: 'Mobile', key: 'mobile', width: 15,},
		 {header: 'Admin', key: 'admin', width: 15,},
		  {header: 'Linked', key: 'linked', width: 20,},
		{header: 'premium', key: 'premium', width: 20,},
        {header: 'start', key: 'start', width: 15,},
        {header: 'valid', key: 'valid', width: 20,},
         {header: 'Login', key: 'login', width: 15,},
         {header: 'Active', key: 'active', width: 15,},
         {header: 'Total Att', key: 'attendance', width: 25,},
		  {header: 'employee', key: 'employee', width: 25,},
		 {header: 'refer', key: 'refer', width: 15,},
          {header: 'licence', key: 'licence', width: 25,}
         
        ];
		
    //  sh.addRow(["SNo.","Empcode","Name","CTC","P","W/H","L","ED","NWD","Salary","PF","ESI","Advance","Addition","Deduction","NetPayable"]);
      <?php
     
	   $count=1;
	   $val=$this->web->getallpremium();
            foreach($val as $pre){
				
			//$uname = $this->web->getNameByUserId($val->id);
			// $info=$this->web->getstaffinfoByUserId($val->user_id,$id);
			//$gender=$uname[0]->gender;
			// if($uname['0']->gender==0){$gender="Male"; }else{ $gender="female";} 
			// $dp = $this->web->getBusinessDepByUserId($uname['0']->department);
			// $gp = $this->web->getBusinessGroupByUserId($uname[0]->business_group);
          ?>
         sh.addRow({
			 SNo:'<?php echo $count++;?>',
			 name:'<?=$pre->mobile;?>',
			 mobile:'<?=$pre->mobile; ?>',
			  linked:'<?=$pre->linked; ?>',
			  admin:'<?=$pre->validity; ?>',
			 premium:'<?=$pre->validity;?>',
			 start:'<?=$pre->id;?>',
			 valid:'<?=$pre->id;?>',
			 login:'<?=$pre->id;?>',
		     active:'<?=$pre->id;?>',
			 attendance:'<?=$pre->id;?>',
			 employee:'<?=$pre->id;?>',
			 refer:'<?=$pre->reference;?>',
		     licence:'<?=$pre->id;?>'
			
		
		 });
      <?php 
          echo "sh.getRow(".$sr++.").alignment = { wrapText: true,vertical: 'top',horizontal: 'center' };";
          //echo "sh.getRow(".$sr.").border = {top: {style:'thin'},left: {style:'thin'},bottom: {style:'thin'},right: {style:'thin'}};";
       }
        echo "sh.getRow(".$sr.").alignment = { wrapText: true,vertical: 'top',horizontal: 'center' };";
        echo "sh.insertRow(1, ['$cmp_name']);";
      //  $new_start_date = date('F Y',$salEmpList[0]->startDate);
     //   $new_end_date = date('F Y',$salEmpList[0]->endDate);
        echo "sh.insertRow(2, ['Business Detail']);";
        echo "sh.mergeCells('A1:Q1');";
        echo "sh.mergeCells('A2:Q2');";
        echo "sh.getRow(1).alignment = {horizontal: 'center' };";
        echo "sh.getRow(2).alignment = {horizontal: 'center' };";
        $sr+=4;
       // echo "sh.insertRow($sr,['Total CTC:$salaryTotalCtc, Total Salary:$salaryTotalSalary, Total Advance:$salaryTotalPaid, Total Deduction:$salaryTotalDeduction, Total Net Payable:$salaryNetPayable']);";
        echo "sh.mergeCells('A$sr:Q$sr');";
       // echo "sh.getRow($sr).alignment = {horizontal: 'center' };";
     // }
	 ?>
      wb.xlsx.writeBuffer().then((data) => {
            const blob = new Blob([data], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8' });
            saveAs(blob, 'Employee Report.xlsx');
      });
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
	XLSX.writeFile(fp, 'business detail.xlsx');
}
</script>



</body>
</html>
