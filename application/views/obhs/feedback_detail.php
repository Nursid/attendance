<?php
date_default_timezone_set('Asia/Kolkata');
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
    @media print{
      .main-sidebar,.main-header,.content-header,.no-print{display:none !important;}
      .content-wrapper{margin-left:0 !important;}
    }
  </style>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <?php $this->load->view('menu/menu')?>
  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6"><h1 class="m-0 text-dark">OBHS <?php echo $title;?></h1></div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url('obhs-dashboard')?>">OBHS Dashboard</a></li>
              <li class="breadcrumb-item"><a href="<?php echo base_url('obhs-master-search')?>">Master Search</a></li>
              <li class="breadcrumb-item active"><?php echo $title;?></li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">
        <?php if($this->session->flashdata('obhs_msg')){ ?>
          <div class="alert alert-success no-print"><?php echo $this->session->flashdata('obhs_msg');?></div>
        <?php } ?>

        <div class="row">
          <!-- Journey + Passenger -->
          <div class="col-md-6">
            <div class="card card-primary">
              <div class="card-header"><h3 class="card-title">Journey Details</h3></div>
              <div class="card-body p-0">
                <table class="table table-sm">
                  <tr><th width="40%">Train</th><td><?php echo $feedback['train_no'].' '.$feedback['train_name'];?></td></tr>
                  <tr><th>Coach</th><td><?php echo $feedback['coach_no'];?></td></tr>
                  <tr><th>Journey Date</th><td><?php echo date('d-m-Y',strtotime($feedback['journey_date']));?></td></tr>
                  <tr><th>From</th><td><?php echo $feedback['boarding_station'];?></td></tr>
                  <tr><th>To</th><td><?php echo $feedback['destination_station'];?></td></tr>
                  <tr><th>Submitted On</th><td><?php echo date('d-m-Y h:i A',(int)$feedback['date_time']);?></td></tr>
                  <tr><th>Janitor</th><td><?php echo !empty($feedback['janitor_name']) ? $feedback['janitor_name'] : $feedback['staff_name'];?> (<?php echo $feedback['staff_mobile'];?>)</td></tr>
                </table>
              </div>
            </div>
            <div class="card card-info">
              <div class="card-header"><h3 class="card-title">Passenger Details</h3></div>
              <div class="card-body p-0">
                <table class="table table-sm">
                  <tr><th width="40%">Name</th><td><?php echo $feedback['passenger_name'];?></td></tr>
                  <tr><th>Mobile</th><td><?php echo $feedback['passenger_mobile'];?></td></tr>
                  <tr><th>Email</th><td><?php echo $feedback['passenger_email'];?></td></tr>
                  <tr><th>PNR</th><td><?php echo $feedback['pnr_no'];?></td></tr>
                  <tr><th>Seat</th><td><?php echo $feedback['seat_no'];?></td></tr>
                </table>
              </div>
            </div>
          </div>

          <!-- Ratings + PSI -->
          <div class="col-md-6">
            <div class="card card-success">
              <div class="card-header"><h3 class="card-title">Service Ratings &amp; PSI</h3></div>
              <div class="card-body p-0">
                <table class="table table-sm">
                  <?php foreach($rating_fields as $field => $label){
                    $val = (int)$feedback[$field];
                    $stars = '';
                    for($s=1;$s<=5;$s++){
                      $stars .= ($s <= $val) ? '<i class="fas fa-star text-warning"></i>' : '<i class="far fa-star text-muted"></i>';
                    }
                    echo "<tr><th width='40%'>$label</th><td>$stars ".($val>0 ? "($val/5)" : '(Not rated)')."</td></tr>";
                  }?>
                  <tr class="bg-light">
                    <th>PSI Score</th>
                    <td>
                      <?php $psi = $feedback['psi_score'];
                      $badge = ($psi>=80)?'success':(($psi>=60)?'info':(($psi>=40)?'warning':'danger')); ?>
                      <span class="badge badge-<?php echo $badge;?>" style="font-size:16px"><?php echo $psi;?> / 100</span>
                    </td>
                  </tr>
                </table>
              </div>
            </div>

            <div class="card card-warning">
              <div class="card-header"><h3 class="card-title">Remarks / Status</h3></div>
              <div class="card-body">
                <p><strong>Type:</strong> <?php echo $feedback['feedback_type'];?>
                  <?php if(!empty($feedback['complaint_id'])){ echo ' (Complaint Ref #'.$feedback['complaint_id'].')'; } ?></p>
                <p style="white-space:pre-line"><strong>Remarks:</strong> <?php echo html_escape((string)$feedback['remarks']);?></p>
                <p><strong>Status:</strong> <?php echo $feedback['status'];?></p>

                <form action="<?php echo base_url('obhs-update-feedback')?>" method="POST" class="no-print">
                  <input type="hidden" name="id" value="<?php echo $feedback['id'];?>">
                  <div class="form-row">
                    <div class="col-sm-4">
                      <select name="status" class="form-control form-control-sm">
                        <?php foreach(array('Pending','Working','Done') as $st){
                          $sel = ($feedback['status']==$st) ? 'selected' : '';
                          echo "<option value='$st' $sel>$st</option>";
                        }?>
                      </select>
                    </div>
                    <div class="col-sm-5">
                      <input type="text" name="admin_remarks" class="form-control form-control-sm" placeholder="Admin remark (optional)">
                    </div>
                    <div class="col-sm-3">
                      <button type="submit" class="btn btn-sm btn-primary">Update</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <!-- GPS + Photo -->
          <div class="col-md-6">
            <div class="card">
              <div class="card-header"><h3 class="card-title">GPS Location</h3></div>
              <div class="card-body">
                <?php if(!empty($feedback['latitude']) && !empty($feedback['longitude'])){ ?>
                  <p><?php echo $feedback['location'];?></p>
                  <p><?php echo $feedback['latitude'];?>, <?php echo $feedback['longitude'];?>
                    <a target="_blank" class="btn btn-xs btn-outline-info no-print" href="https://www.google.com/maps?q=<?php echo $feedback['latitude'];?>,<?php echo $feedback['longitude'];?>">Open in Maps</a>
                  </p>
                <?php }else{ echo '<p class="text-muted">No GPS data</p>'; } ?>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card">
              <div class="card-header"><h3 class="card-title">Photo</h3></div>
              <div class="card-body">
                <?php if(!empty($feedback['photo'])){ ?>
                  <img src="<?php echo base_url($feedback['photo']);?>" class="img-fluid" style="max-height:300px" alt="Feedback photo">
                <?php }else{ echo '<p class="text-muted">No photo attached</p>'; } ?>
              </div>
            </div>
          </div>
        </div>

        <div class="mb-3 no-print">
          <a href="javascript:history.back()" class="btn btn-sm btn-secondary">Back</a>
          <button type="button" onclick="window.print()" class="btn btn-sm btn-info"><i class="fas fa-print"></i> Print</button>
        </div>

      </div>
    </section>
  </div>
</div>

<script src="<?php echo base_url('adminassets/plugins/jquery/jquery.min.js')?>"></script>
<script src="<?php echo base_url('adminassets/plugins/bootstrap/js/bootstrap.bundle.min.js')?>"></script>
<script src="<?php echo base_url('adminassets/dist/js/adminlte.min.js')?>"></script>
</body>
</html>
