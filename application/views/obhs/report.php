<?php
date_default_timezone_set('Asia/Kolkata');

// Route alias per report key (for form action / links)
$report_urls = array(
	'master'=>'obhs-master-search','train'=>'obhs-train-report','coach'=>'obhs-coach-report',
	'janitor'=>'obhs-janitor-report','psi'=>'obhs-psi-report','monthly'=>'obhs-monthly-report',
	'complaints'=>'obhs-complaints'
);
$self_url = $report_urls[$report];

// Build query string preserving state, with overrides
function obhs_qs($overrides=array()){
	$params = array_merge($_GET,$overrides);
	unset($params['print']);
	return http_build_query($params);
}
$export_qs = obhs_qs();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo $title;?> - OBHS</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="<?php echo base_url('adminassets/plugins/fontawesome-free/css/all.min.css')?>">
  <link rel="stylesheet" href="<?php echo base_url('adminassets/dist/css/adminlte.min.css')?>">
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo base_url('adminassets/dist/css/mid.css')?>">
  <style>
    th a{color:inherit;}
    @media print{
      .main-sidebar,.main-header,.content-header,.obhs-filters,.obhs-actions,.obhs-pagination,.no-print{display:none !important;}
      .content-wrapper{margin-left:0 !important;}
      .print-title{display:block !important;}
    }
    .print-title{display:none;}
  </style>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <?php $this->load->view('menu/menu')?>
  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6"><h1 class="m-0 text-dark"><?php echo $title;?></h1></div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url('obhs-dashboard')?>">OBHS Dashboard</a></li>
              <li class="breadcrumb-item active"><?php echo $title;?></li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title"><?php echo $title;?> (<?php echo $total;?> records)</h3>
          </div>
          <div class="card-body">

            <!-- Filters -->
            <div class="obhs-filters">
              <form action="<?php echo base_url($self_url)?>" method="GET">
                <div class="row">
                  <div class="col-sm-2 mb-2">
                    <input type="date" name="start_date" class="form-control form-control-sm" placeholder="From" value="<?php echo isset($filters['start_date']) ? $filters['start_date'] : '';?>">
                  </div>
                  <div class="col-sm-2 mb-2">
                    <input type="date" name="end_date" class="form-control form-control-sm" value="<?php echo isset($filters['end_date']) ? $filters['end_date'] : '';?>">
                  </div>
                  <div class="col-sm-2 mb-2">
                    <select name="train_no" class="form-control form-control-sm">
                      <option value="">All Trains</option>
                      <?php foreach($options['trains'] as $t){
                        $sel = (isset($filters['train_no']) && $filters['train_no']==$t['train_no']) ? 'selected' : '';
                        echo "<option value='".$t['train_no']."' $sel>".$t['train_no']." ".$t['train_name']."</option>";
                      }?>
                    </select>
                  </div>
                  <div class="col-sm-2 mb-2">
                    <select name="coach_no" class="form-control form-control-sm">
                      <option value="">All Coaches</option>
                      <?php foreach($options['coaches'] as $co){
                        $sel = (isset($filters['coach_no']) && $filters['coach_no']==$co['coach_no']) ? 'selected' : '';
                        echo "<option value='".$co['coach_no']."' $sel>".$co['coach_no']."</option>";
                      }?>
                    </select>
                  </div>
                  <div class="col-sm-2 mb-2">
                    <select name="uid" class="form-control form-control-sm">
                      <option value="">All Janitors</option>
                      <?php foreach($options['janitors'] as $j){
                        $sel = (isset($filters['uid']) && $filters['uid']==$j['uid']) ? 'selected' : '';
                        echo "<option value='".$j['uid']."' $sel>".$j['name']."</option>";
                      }?>
                    </select>
                  </div>
                  <div class="col-sm-2 mb-2">
                    <select name="status" class="form-control form-control-sm">
                      <option value="">All Status</option>
                      <?php foreach(array('Pending','Working','Done') as $st){
                        $sel = (isset($filters['status']) && $filters['status']==$st) ? 'selected' : '';
                        echo "<option value='$st' $sel>$st</option>";
                      }?>
                    </select>
                  </div>
                </div>
                <div class="row">
                  <?php if($report!='complaints'){ ?>
                  <div class="col-sm-2 mb-2">
                    <select name="feedback_type" class="form-control form-control-sm">
                      <option value="">All Types</option>
                      <?php foreach(array('Feedback','Complaint') as $ft){
                        $sel = (isset($filters['feedback_type']) && $filters['feedback_type']==$ft) ? 'selected' : '';
                        echo "<option value='$ft' $sel>$ft</option>";
                      }?>
                    </select>
                  </div>
                  <?php } ?>
                  <div class="col-sm-1 mb-2">
                    <input type="number" name="psi_min" min="0" max="100" class="form-control form-control-sm" placeholder="PSI Min" value="<?php echo isset($filters['psi_min']) ? $filters['psi_min'] : '';?>">
                  </div>
                  <div class="col-sm-1 mb-2">
                    <input type="number" name="psi_max" min="0" max="100" class="form-control form-control-sm" placeholder="PSI Max" value="<?php echo isset($filters['psi_max']) ? $filters['psi_max'] : '';?>">
                  </div>
                  <div class="col-sm-3 mb-2">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Search train / PNR / passenger / mobile / station / remarks" value="<?php echo isset($filters['search']) ? html_escape($filters['search']) : '';?>">
                  </div>
                  <div class="col-sm-3 mb-2">
                    <button type="submit" class="btn btn-sm btn-primary">Search</button>
                    <a href="<?php echo base_url($self_url)?>" class="btn btn-sm btn-secondary">Reset</a>
                  </div>
                </div>
              </form>
            </div>

            <!-- Actions -->
            <div class="obhs-actions mb-2 text-right">
              <a href="<?php echo base_url('obhs-export/'.$report).'?'.$export_qs;?>" class="btn btn-sm btn-success"><i class="fas fa-file-excel"></i> Excel</a>
              <button type="button" onclick="exportPdf()" class="btn btn-sm btn-danger"><i class="fas fa-file-pdf"></i> PDF</button>
              <button type="button" onclick="window.print()" class="btn btn-sm btn-info"><i class="fas fa-print"></i> Print</button>
            </div>

            <h4 class="print-title"><?php echo $title;?> - <?php echo date('d-m-Y');?></h4>

            <!-- Table -->
            <div class="table-responsive">
              <table class="table table-bordered table-hover table-sm text-nowrap" id="reportTable">
                <thead>
                  <tr>
                    <th>S.No</th>
                    <?php foreach($columns as $key => $label){
                      if(in_array($key,$sortable)){
                        $dir = ($sort['sort_by']==$key && $sort['sort_dir']=='DESC') ? 'ASC' : 'DESC';
                        $icon = '';
                        if($sort['sort_by']==$key){
                          $icon = ($sort['sort_dir']=='ASC') ? ' <i class="fas fa-sort-up"></i>' : ' <i class="fas fa-sort-down"></i>';
                        }
                        echo "<th><a href='".base_url($self_url).'?'.obhs_qs(array('sort_by'=>$key,'sort_dir'=>$dir,'pg'=>1))."'>$label$icon</a></th>";
                      }else{
                        echo "<th>$label</th>";
                      }
                    }?>
                    <?php if($detail_link){ echo '<th class="no-print"></th>'; } ?>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $sno = ($page-1)*$per_page;
                  foreach($rows as $row){
                    $sno++;
                    echo '<tr>';
                    echo '<td>'.$sno.'</td>';
                    foreach($columns as $key => $label){
                      $val = isset($row[$key]) ? $row[$key] : '';
                      if($key=='journey_date' && $val!=''){ $val = date('d-m-Y',strtotime($val)); }
                      if($key=='psi_score' || $key=='avg_psi'){
                        $badge = ($val>=80)?'success':(($val>=60)?'info':(($val>=40)?'warning':'danger'));
                        $val = "<span class='badge badge-$badge'>$val</span>";
                      }
                      if($key=='status'){
                        $badge = ($val=='Done')?'success':(($val=='Working')?'info':'warning');
                        $val = "<span class='badge badge-$badge'>$val</span>";
                      }
                      if($key=='remarks'){ $val = html_escape(character_limiter((string)$val,60)); }
                      echo '<td>'.$val.'</td>';
                    }
                    if($detail_link){
                      echo '<td class="no-print"><a href="'.base_url('obhs-feedback/'.$row['id']).'" class="btn btn-xs btn-outline-primary">View</a></td>';
                    }
                    echo '</tr>';
                  }
                  if(empty($rows)){
                    echo '<tr><td colspan="'.(count($columns)+2).'" class="text-center">No records found</td></tr>';
                  }
                  ?>
                </tbody>
              </table>
            </div>

            <!-- Pagination -->
            <?php if($total_pages > 1){ ?>
            <div class="obhs-pagination">
              <ul class="pagination pagination-sm m-0 float-right">
                <?php
                if($page > 1){
                  echo '<li class="page-item"><a class="page-link" href="'.base_url($self_url).'?'.obhs_qs(array('pg'=>$page-1)).'">Prev</a></li>';
                }
                $from = max(1,$page-3); $to = min($total_pages,$page+3);
                for($p=$from;$p<=$to;$p++){
                  $active = ($p==$page) ? ' active' : '';
                  echo '<li class="page-item'.$active.'"><a class="page-link" href="'.base_url($self_url).'?'.obhs_qs(array('pg'=>$p)).'">'.$p.'</a></li>';
                }
                if($page < $total_pages){
                  echo '<li class="page-item"><a class="page-link" href="'.base_url($self_url).'?'.obhs_qs(array('pg'=>$page+1)).'">Next</a></li>';
                }
                ?>
              </ul>
              <span>Page <?php echo $page;?> of <?php echo $total_pages;?></span>
            </div>
            <?php } ?>

          </div>
        </div>
      </div>
    </section>
  </div>
</div>

<script src="<?php echo base_url('adminassets/plugins/jquery/jquery.min.js')?>"></script>
<script src="<?php echo base_url('adminassets/plugins/bootstrap/js/bootstrap.bundle.min.js')?>"></script>
<script src="<?php echo base_url('adminassets/dist/js/adminlte.min.js')?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
<script>
function exportPdf(){
  var doc = new jspdf.jsPDF('l','pt','a4');
  doc.text("<?php echo addslashes($title);?> - <?php echo date('d-m-Y');?>", 40, 30);
  doc.autoTable({
    html: '#reportTable',
    startY: 40,
    styles: {fontSize: 7},
    headStyles: {fillColor: [0,123,255]}
  });
  doc.save("OBHS_<?php echo ucfirst($report);?>_Report_<?php echo date('Ymd');?>.pdf");
}
<?php if($print_mode){ echo 'window.onload=function(){window.print();};'; } ?>
</script>
</body>
</html>
