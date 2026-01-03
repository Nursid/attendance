
  <?php $this->load->view('menu/footer')?>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

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
<script src="https://cdn.datatables.net/buttons/2.3.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.print.min.js"></script>

<!-- AdminLTE for demo purposes -->
<script src="<?php echo base_url('adminassets/dist/js/demo.js')?>"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.3.0/exceljs.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
  $(function () {
   var table = $('#example1').DataTable({
     "responsive": true,
      "autoWidth": false,
    });
   
  });
  $(document).ready(function() {
    $('#salaryReport').DataTable( {
      "responsive": false,
      "paging":false,
      "autoWidth":false,
      "ordering":false
    } );
  } );
  $(document).ready(function() {
    $('#newsalaryReport').DataTable( {
      "responsive": false,
      "paging":false,
      "autoWidth":false,
      "ordering":false,
      'dom': 'Bfrtip',
      "buttons": [
        {
            extend: 'excel',
            exportOptions: {
           columns: ':not(:last-child)',
          }
        }
      ]
    } );
  } );
  function exportData(){
      var wb = new ExcelJS.Workbook();
      var sh = wb.addWorksheet("Report");
      <?php if (!empty($salEmpList)) {
      $sr = 1;?>
      sh.columns = [
        {header: 'SNo.', key: 'SNo', width: 10},
        {header: 'Empcode', key: 'Empcode', width: 15},
        {header: 'Name', key: 'Name', width: 20,},
        {header: 'CTC', key: 'CTC', width: 6,},
        {header: 'P', key: 'P', width: 6,},
        {header: 'W/H', key: 'WH', width: 6,},
        {header: 'L', key: 'L', width: 6,},
        {header: 'ED', key: 'ED', width: 6,},
        {header: 'NWD', key: 'NWD', width: 6,},
        {header: 'Salary', key: 'Salary', width: 6,},
        {header: 'PF', key: 'PF', width: 6,},
        {header: 'ESI', key: 'ESI', width: 6,},
        {header: 'Advance', key: 'Advance', width: 16,},
        {header: 'Addition', key: 'Addition', width: 16,},
        {header: 'Deduction', key: 'Deduction', width: 16,},
        {header: 'NetPayable', key: 'NetPayable', width: 16,}
      ];
      //sh.addRow(["SNo.","Empcode","Name","CTC","P","W/H","L","ED","NWD","Salary","PF","ESI","Advance","Addition","Deduction","NetPayable"]);
      <?php
        $salaryTotalPaid = 0;
        $salaryNetPayable = 0;
        $salaryTotalCtc = 0;
        $salaryTotalSalary = 0;
        $salaryTotalDeduction = 0;
        usort($salEmpList, function($a, $b) {
          if(empty($a->emp_code)){
              return -1;
          }elseif ($a->emp_code > $b->emp_code) {
              return 1;
          } elseif ($a->emp_code < $b->emp_code) {
              return -1;
          }
          return 0;
        });
        foreach ($salEmpList as $key => $empData) { 
          $salaryTotalPaid+=$empData->getTotalPaid;
          $salaryNetPayable+=$empData->netPayable;
          $salaryTotalCtc+=$empData->ctc;
          $salaryTotalSalary+=$empData->total;
          $salaryTotalDeduction+=$empData->deductionAmount;
          ?>
          sh.addRow({SNo:'<?php echo $sr;?>',Empcode:'<?= $empData->emp_code; ?>',Name:'<?= $empData->empName; ?>',CTC:<?= $empData->ctc; ?>,P:<?= $empData->present+($empData->half_day/2); ?>,WH:<?= $empData->week_off+$empData->holiday; ?>,L:<?= $empData->leaves; ?>,ED:<?= $empData->ed; ?>,NWD:<?= $empData->nwd; ?>,Salary:<?= $empData->total; ?>,PF:<?= $empData->pf; ?>,ESI:<?= $empData->esi; ?>,Advance:<?= $empData->getTotalPaid; ?>,Addition:<?= $empData->additionAmount; ?>,Deduction:<?= $empData->deductionAmount; ?>,NetPayable:<?= $empData->netPayable; ?>});
      <?php 
          echo "sh.getRow(".$sr++.").alignment = { wrapText: true,vertical: 'top',horizontal: 'center' };";
          //echo "sh.getRow(".$sr.").border = {top: {style:'thin'},left: {style:'thin'},bottom: {style:'thin'},right: {style:'thin'}};";
        }
        echo "sh.getRow(".$sr.").alignment = { wrapText: true,vertical: 'top',horizontal: 'center' };";
        echo "sh.insertRow(1, ['$cmp_name']);";
        $new_start_date = date('F Y',$salEmpList[0]->startDate);
        $new_end_date = date('F Y',$salEmpList[0]->endDate);
        echo "sh.insertRow(2, ['Salary Sheet for the Month of $new_start_date']);";
        echo "sh.mergeCells('A1:Q1');";
        echo "sh.mergeCells('A2:Q2');";
        echo "sh.getRow(1).alignment = {horizontal: 'center' };";
        echo "sh.getRow(2).alignment = {horizontal: 'center' };";
        $sr+=4;
        echo "sh.insertRow($sr,['Total CTC:$salaryTotalCtc, Total Salary:$salaryTotalSalary, Total Advance:$salaryTotalPaid, Total Deduction:$salaryTotalDeduction, Total Net Payable:$salaryNetPayable']);";
        echo "sh.mergeCells('A$sr:Q$sr');";
        echo "sh.getRow($sr).alignment = {horizontal: 'center' };";
      }?>
      wb.xlsx.writeBuffer().then((data) => {
            const blob = new Blob([data], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8' });
            saveAs(blob, 'Salary Report.xlsx');
      });
  }

  function createHeaders(keys) {
    var result = [];
    result.push({id: 'SNo',name: 'SNo',prompt: 'SNo.',width: 15,align: 'center',padding: 0});
    result.push({id: 'Name',name: 'Name',prompt: 'Name',width: 35,align: 'center',padding: 0});
    result.push({id: 'CTC',name: 'CTC',prompt: 'CTC',width: 25,align: 'center',padding: 0});
    result.push({id: 'P',name: 'P',prompt: 'P',width: 20,align: 'center',padding: 0});
    result.push({id: 'WH',name: 'WH',prompt: 'WH',width: 20,align: 'center',padding: 0});
    result.push({id: 'L',name: 'L',prompt: 'L',width: 20,align: 'center',padding: 0});
    result.push({id: 'ED',name: 'ED',prompt: 'ED',width: 20,align: 'center',padding: 0});
    result.push({id: 'NWD',name: 'NWD',prompt: 'NWD',width: 20,align: 'center',padding: 0});
    result.push({id: 'Salary',name: 'Salary',prompt: 'Salary',width: 25,align: 'center',padding: 0});
    result.push({id: 'PF',name: 'PF',prompt: 'PF',width: 20,align: 'center',padding: 0});
    result.push({id: 'ESI',name: 'ESI',prompt: 'ESI',width: 20,align: 'center',padding: 0});
    result.push({id: 'Advance',name: 'Advance',prompt: 'Advance',width: 30,align: 'center',padding: 0});
    result.push({id: 'Addition',name: 'Addition',prompt: 'Addition',width: 30,align: 'center',padding: 0});
    result.push({id: 'Deduction',name: 'Deduction',prompt: 'Deduction',width: 30,align: 'center',padding: 0});
    result.push({id: 'NetPayable',name: 'NetPayable',prompt: 'NetPayable',width: 30,align: 'center',padding: 0});
    return result;
  }

  function exportPDF(){
    var result = [];
    <?php if (!empty($salEmpList)) {
      $sr = 1;
      $salaryTotalPaid = 0;
      $salaryNetPayable = 0;
      $salaryTotalCtc = 0;
      $salaryTotalSalary = 0;
      $salaryTotalDeduction = 0;
      foreach ($salEmpList as $key => $empData) { 
        $salaryTotalPaid+=$empData->getTotalPaid;
        $salaryNetPayable+=$empData->netPayable;
        $salaryTotalCtc+=$empData->ctc;
        $salaryTotalSalary+=$empData->total;
        $salaryTotalDeduction+=$empData->deductionAmount;
        ?>
        var data = {id:"<?php echo $sr;?>",SNo: "<?php echo $sr;?>",Name: "<?= $empData->empName; ?>",CTC:"<?= $empData->ctc; ?>",P:"<?= $empData->present+($empData->half_day/2); ?>",WH:"<?= $empData->week_off+$empData->holiday; ?>",L:"<?= $empData->leaves; ?>",ED:"<?= $empData->ed; ?>",NWD:"<?= $empData->nwd; ?>",Salary:"<?= $empData->total; ?>",PF:"<?= $empData->pf; ?>",ESI:"<?= $empData->esi; ?>",Advance:"<?= $empData->getTotalPaid; ?>",Addition:"<?= $empData->additionAmount; ?>",Deduction:"<?= $empData->deductionAmount; ?>",NetPayable:"<?= $empData->netPayable; ?>"};
        result.push(Object.assign({}, data));
    <?php
        $sr++;
      }
      $sr+=4;
      $new_start_date = date('F Y',$salEmpList[0]->startDate);
      $new_end_date = date('F Y',$salEmpList[0]->endDate);
      ?>
      var headers = createHeaders();
      var doc = new jspdf.jsPDF("landscape");
      doc.setFontSize(14);
      doc.text("<?php echo $cmp_name;?>",135,15,null,null,"center");
      doc.text("Salary Sheet for the Month of <?= $new_start_date;?>",145,20,null,null,"center");
      doc.setFontSize(12);
      doc.text("Total CTC:<?= $salaryTotalCtc;?>, Total Salary:<?= $salaryTotalSalary;?>, Total Advance:<?= $salaryTotalPaid;?>, Total Deduction:<?= $salaryTotalDeduction;?>, Total Net Payable:<?= $salaryNetPayable;?>",145,30,null,null,"center");
      doc.table(10, 40, result, headers, { autoSize: false,fontSize:9,padding:1,margins:{left:0,top:3,bottom:3, right:0} });
      doc.save("salaryReport.pdf");
    <?php
    }?>
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
</body>
</html>
