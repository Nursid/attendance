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
 <?php $this->load->view('hostel/hostel_menu')?>
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
      if($this->session->userdata()['type']=='B' || $this->session->userdata()['type']=='P')
      {
        if ($this->session->userdata()['type'] == 'P') {
          //$busi = $this->web->getBusinessbyUser($this->web->session->userdata('login_id'));
          $bid = $this->session->userdata('empCompany');
          $role=$this->web->getRollbyid($this->web->session->userdata('login_id'),$bid);
        } else {
          $bid = $this->web->session->userdata('login_id');
        }
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
    <div class="col-lg-12 float-left">
    
      <form action="<?php echo base_url('User/canteen_monthly_report')?>" method="POST" id="hostelmonthlyReport">
                          <div class="row">
                            <div class="col-sm-2">
                              <input type="date" name="start_date" id="start_date"  value="<?php echo $start_date; ?>" class="form-control" max="<?php echo $end_date; ?>" onchange="startChange(event);">
                            </div>
                            <div class="col-sm-2">
                              <input type="date" name="end_date" id="end_date"  value="<?php echo $end_date; ?>"class="form-control" max="<?php echo date('Y-m-d'); ?>" min="<?php echo $start_date;?>" onchange="endChange(event);">
                            </div>
                            
                             
                             
                            <div class="col-sm-1">
                            
                              <button type="submit" id="actionSubmit" class="btn btn-success btn-fill btn-block" onclick="showLoader()">Show</button>
                            </div>
                          
                           </div>
                                             
                     
                     </form>          
                            
                            
                            
                            
                            
    </div>
  </div>
              
           
              
             <?php
                      if($load) {
                        $stdate=strtotime($start_date);
                        $endate=strtotime($end_date);
                        ?>
                       
                       <!-- <h5>Access Log of:-<?php echo date("d-M-Y ",$stdate)?> to Date:- <?php echo date("d-M-Y ",$endate)?> </h5>
                        <h5>Totals Log:-<?php echo $totalf;?> </h5>-->
                        
                        <div align="right">
                          <input type="button" onClick="export_datas()" value="Export To Excel" />
                          <input type="button"  id="btnExport" value="Export To Pdf" onclick="exportPDF22()" />
                          <br>
                        </div>
                        <table id="example1" class="table table-bordered table-responsive">
                          <thead>
                          <?php
                          echo "<tr> <td colspan='12'> <h5>Log Detail of:-".date("d-M-Y ",$stdate)."to Date:-".date("d-M-Y ",$endate) ."</h5></td></tr>";
                          echo " <tr> <td colspan='12'> <h5> Total Log : ".$totalf." , ";
                         echo "Active User : ".$totalActive ."</h5></td></tr>" ;?>



                            <tr>
                              <th>SNo.</th>
                              <th>Name</th>
                              <th>Block</th>
                            <th>Room No</th>
                              <?php
                              
                                foreach($days as $day){
                                  echo "<th>$day</th>";
                                }
                              
                              ?>
                            <th>Total</th>  
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                            $count=1;
                            foreach($report as $user){
                              ?>
                              <tr>
                                <td><?php echo $count++;?></td>
                               
                                <td><?php echo $user['name'];?></td>
                                 <td><?php
							//  echo $user['user_id'];
							  $hostel = $this->web->getHostelByUserId($user['user_id'],$bid); 
						       $blid=$hostel[0]->block;
						      $block = $this->web->getBlock($blid,$bid);
							   echo $block[0]->name;
							  
							  ?></td>
                              <td>
							  <?php echo $hostel[0]->room_no;
							   ?>
                               </td>
                                <?php
                                
                                  foreach($user['data'] as $day){
                                    if(!empty($day['data'])){
                                      echo "<td>";
                                     
                                        foreach($day['data'] as $day_data){
                                         //echo  $day_data['time']; 
										// echo  $day_data['mode']; 
                                         echo date('h:i:A',$day_data['time'])."</br>";
                                        }
										echo "</td>";
									}

                                  else{
                                      echo "<td></td>";
                                    }   
								  }
                                
                                ?>
                                
                               <td><?php echo $user['total']; ?> </td> 
                                
                              </tr>
                            <?php }?>
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



  



  <?php 
        }
   ?>
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
    <!--<script src="<?php echo base_url('adminassets/plugins/datatables/jquery.dataTables.min.js')?>"></script>-->
    <!-- Select2 -->
    <script src="<?php echo base_url('adminassets/plugins/select2/js/select2.full.min.js')?>"></script>
    <script src="<?php echo base_url('adminassets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')?>"></script>
    <script src="<?php echo base_url('adminassets/plugins/datatables-responsive/js/dataTables.responsive.min.js')?>"></script>
    <script src="<?php echo base_url('adminassets/plugins/datatables-buttons/js/dataTables.buttons.min.js')?>"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="<?php echo base_url('adminassets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js')?>"></script>
    <script src="<?php echo base_url('adminassets/plugins/datatables-buttons/js/buttons.html5.min.js')?>"></script>
    <script src="<?php echo base_url('adminassets/plugins/datatables-buttons/js/buttons.print.min.js')?>"></script>
    <script src="<?php echo base_url('adminassets/plugins/datatables-buttons/js/buttons.colVis.min.js')?>"></script>

    <script src="<?php echo base_url('adminassets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')?>"></script>
    <script src="<?php echo base_url('adminassets/dist/js/adminlte.min.js')?>"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="<?php echo base_url('adminassets/dist/js/demo.js')?>"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.3.0/exceljs.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/fixedcolumns/4.2.1/js/dataTables.fixedColumns.min.js"></script>

 <script>
    $(document).ready(function () {
        var table = $('#example1').DataTable( {
            scrollY:        "500px",
            scrollX:        true,
            scrollCollapse: true,
            paging:         false,
            fixedColumns:   {
                leftColumns: 2
            }
        } );
      $('.nav-link').click(function(e) {
        $('.nav-link').removeClass('active');
        $(this).addClass("active");

      });
      // var table = $('#example1').DataTable({
      //   searching:false,
      // });
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

    function startChange(e){
      //alert(e.target.value);
      $('#end_date').attr('min', e.target.value);
    }
    function endChange(e){
      //alert(e.target.value);
      $('#start_date').attr('max', e.target.value);
    }
    function showLoader(){
      $(".loading").css("display","block");
    }

   
    </script>
<script>

function export_datas(){
  let data=document.getElementById('example1');
  //var fp=XLSX.utils.table_to_book(data,{sheet:'Report'});
  // XLSX.write(fp,{
  //   bookType:'xlsx',
  //   type:'base64',
  // });
  //fp["!cos"] = [ { wch: 10 } ];
  //console.log(fp);
  //XLSX.writeFile(fp, 'Employee Attendance.xlsx');

  var wb = new ExcelJS.Workbook();
  var sh = wb.addWorksheet("Report");
  sh.columns = [
    {header: 'SNo.', key: 'SNo', width: 10},
    {header: 'Empcode', key: 'Empcode', width: 15},
    {header: 'Name', key: 'Name', width: 20,},
    <?php
   
      foreach($days as $day){
        echo "{header: '$day', key: 'd$day', width: 10,},";
      }
    
   ?>
      {header: 'Total', key: 'Total', width: 6,}
     
      
    ];
    <?php
    
    $count=1;
    foreach($report as $user){
      ?>
      sh.addRow({SNo:'<?php echo $count;?>',Empcode:'<?php echo $user['emp_code'];?>',Name:'<?php echo $user['name'];?>',
      <?php
    
          $dayCount = 0;
        foreach($user['data'] as $day){
          if(!empty($day['data'])){
              $val = "";
          foreach($day['data'] as $day_data){
                                          
            $val = $val.date('h:i:A', $day_data['time']).'\n';
                                        
          }
          echo "d".$days[$dayCount].":'$val',";
             
              }

           
          $dayCount++;
          ?>

          <?php
        }
    
      ?>
        Total:<?php echo $user['total'];?>
        
        <?php  
        echo "});";
        echo "sh.getRow(".$count++.").alignment = { wrapText: true,vertical: 'top',horizontal: 'center' };";
        echo "sh.getRow(".$count.").border = {top: {style:'thin'},left: {style:'thin'},bottom: {style:'thin'},right: {style:'thin'}};";
      }
      echo "sh.getRow(".$count.").alignment = { wrapText: true,vertical: 'top',horizontal: 'center' };";

    echo "sh.insertRow(1, ['$cmp_name']);";
      $new_start_date = date('d F Y',strtotime($start_date));
      $new_end_date = date('d F Y',strtotime($end_date));
      echo "sh.insertRow(2, ['Monthly Report from $new_start_date to $new_end_date']);";
      
     // echo "sh.insertRow(3, ['Department:-$department, Shift:-$sft, Section:-$sect']);";
      echo "sh.mergeCells('A1:Z1');";
      echo "sh.mergeCells('A2:Z2');";
      //echo "sh.mergeCells('A3:Z3');";
      echo "sh.getRow(1).alignment = {horizontal: 'center' };";
      echo "sh.getRow(2).alignment = {horizontal: 'center' };";
    //  echo "sh.getRow(3).alignment = {horizontal: 'center' };";

      ?>
      // sh.getRow(2).font = { name: 'Comic Sans MS', family: 4, size: 16, underline: 'double', bold: true };
      // sh.getRow(2).alignment = { wrapText: true };
      wb.xlsx.writeBuffer().then((data) => {
        const blob = new Blob([data], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8' });
        saveAs(blob, 'Canteen Report.xlsx');
      });
      console.log(sh);
    }
    </script>
