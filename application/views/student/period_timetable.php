<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Period Timetable</title>
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
  <style>
    .timetable-cell {
      cursor: pointer;
    }
    .timetable-cell:hover {
      background-color: #f0f0f0;
    }
  </style>
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
          <div class="col-sm-6">
            <h1>Period Timetable: <?php echo $timetable->name; ?></h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item"><a href="<?php echo base_url('time_table'); ?>">Time Table</a></li>
              <li class="breadcrumb-item active">Period Timetable</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-primary card-outline card-tabs">
              <div class="card-header p-0 pt-1 border-bottom-0">
                <ul class="nav nav-tabs" id="timetable-tabs" role="tablist">
                  <?php
                  $days = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
                  foreach($days as $index => $day) {
                    $active = ($index == 0) ? 'active' : '';
                    echo '<li class="nav-item">
                            <a class="nav-link '.$active.'" id="'.$day.'-tab" data-toggle="pill" href="#'.$day.'" role="tab" aria-controls="'.$day.'" aria-selected="'.($index == 0 ? 'true' : 'false').'">'.$day.'</a>
                          </li>';
                  }
                  ?>
                </ul>
              </div>
              <div class="card-body">
                <div class="tab-content" id="timetable-tabContent">
                  <?php
                  foreach($days as $index => $day) {
                    $active = ($index == 0) ? 'active' : '';
                    ?>
                    <div class="tab-pane fade show <?php echo $active; ?>" id="<?php echo $day; ?>" role="tabpanel" aria-labelledby="<?php echo $day; ?>-tab">
                      <div class="row mb-3">
                        <div class="col-md-12">
                          <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addPeriodModal" data-day="<?php echo $day; ?>">
                            <i class="fas fa-plus"></i> Add Period
                          </button>
                          <button type="button" class="btn btn-success float-right mr-2 save-all-periods" data-day="<?php echo $day; ?>" data-day-num="<?php echo $index; ?>">
                            <i class="fas fa-save"></i> Save All Periods
                          </button>
                        </div>
                      </div>
                      <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                          <thead>
                            <tr>
                              <th width="15%">Period</th>
                              <th width="25%">Course</th>
                              <th width="25%">Teacher</th>
                              <th width="25%">Classroom</th>
                              <th width="10%">Actions</th>
                            </tr>
                          </thead>
                          <tbody id="<?php echo $day; ?>-periods">
                            <?php

                    
                             // Display teacher and subject from the first entry
                            if(!empty($timetable_entries)) {
                              foreach($timetable_entries as $entry) {
                                // Convert numeric day to string day (0 = Sunday, 1 = Monday, etc.)
                                $dayNumber = $entry->days;
                                $dayName = '';
                                
                                // Handle both numeric and string values for compatibility
                                if(is_numeric($dayNumber)) {
                                  switch(intval($dayNumber)) {
                                    case 0: $dayName = 'Sunday'; break;
                                    case 1: $dayName = 'Monday'; break;
                                    case 2: $dayName = 'Tuesday'; break;
                                    case 3: $dayName = 'Wednesday'; break;
                                    case 4: $dayName = 'Thursday'; break;
                                    case 5: $dayName = 'Friday'; break;
                                    case 6: $dayName = 'Saturday'; break;
                                  }
                                } else {
                                  // If days is already stored as a string (for backward compatibility)
                                  $dayName = $dayNumber;
                                }
                                
                                // Only show entries for the current day tab
                                if($dayName === $day) {
                                  echo '<tr>
                                    <td>'.(($period = $this->web->getperiodnamebyid($entry->period)) ? $period[0]->start_time . ' - ' . $period[0]->end_time : $entry->period).'</td>
                                    <td>
                                      <select class="form-control select-subject" data-entry-id="'.$entry->id.'">
                                        <option value="">Select Subject</option>';
                                        $subjects = $this->web->getAllSubjectsById_new($timetable->bid, $timetable->dept);
                                        if(!empty($subjects)) {
                                          foreach($subjects as $subject) {
                                            $selected = ($entry->subject == $subject->id) ? 'selected' : '';
                                            echo '<option value="'.$subject->id.'" '.$selected.'>'.$subject->name.'</option>';
                                          }
                                        }
                                      echo '</select>
                                    </td>
                                    <td>
                                      <select class="form-control select-teacher" data-entry-id="'.$entry->id.'">
                                        <option value="">Select Teacher</option>';
                                        if(!empty($teachers)) {
                                          foreach($teachers as $teacher) {
                                            $selected = ($entry->teacher == $teacher->id) ? 'selected' : '';
                                            echo '<option value="'.$teacher->id.'" '.$selected.'>'.$teacher->name.'</option>';
                                          }
                                        }
                                      echo '</select>
                                    </td>
                                    <td>
                                      <select class="form-control select-classroom" data-entry-id="'.$entry->id.'">
                                        <option value="">Select Classroom</option>';
                                        // Get classrooms or create sample ones if method doesn't exist
                                        $classrooms = method_exists($this->web, 'getAllClassrooms') ? 
                                            $this->web->getAllClassrooms($timetable->bid) : 
                                            [
                                                (object)['id' => 'Room 101', 'name' => 'Room 101'],
                                                (object)['id' => 'Room 102', 'name' => 'Room 102'],
                                                (object)['id' => 'Room 103', 'name' => 'Room 103'],
                                                (object)['id' => 'Lab 1', 'name' => 'Lab 1'],
                                                (object)['id' => 'Lab 2', 'name' => 'Lab 2']
                                            ];
                                        
                                        if(!empty($classrooms)) {
                                          foreach($classrooms as $classroom) {
                                            $selected = ($entry->class_room == $classroom->id) ? 'selected' : '';
                                            echo '<option value="'.$classroom->id.'" '.$selected.'>'.$classroom->name.'</option>';
                                          }
                                        }
                                      echo '</select>
                                    </td>
                                    <td>
                                      <button class="btn btn-sm btn-info edit-period" data-toggle="modal" data-target="#addPeriodModal" 
                                        data-id="'.$entry->id.'" data-day="'.$day.'" data-period="'.$entry->period.'" 
                                        data-subject="'.$entry->subject.'" data-teacher="'.$entry->teacher.'" 
                                        data-classroom="'.$entry->class_room.'">
                                        <i class="fas fa-edit"></i>
                                      </button>
                                    </td>
                                  </tr>';
                                }
                              }
                              
                              // Check if any entries were displayed for this day
                              $entriesForDay = false;
                              foreach($timetable_entries as $entry) {
                                $dayNumber = $entry->days;
                                $dayName = '';
                                
                                // Handle both numeric and string values for compatibility
                                if(is_numeric($dayNumber)) {
                                  switch(intval($dayNumber)) {
                                    case 0: $dayName = 'Sunday'; break;
                                    case 1: $dayName = 'Monday'; break;
                                    case 2: $dayName = 'Tuesday'; break;
                                    case 3: $dayName = 'Wednesday'; break;
                                    case 4: $dayName = 'Thursday'; break;
                                    case 5: $dayName = 'Friday'; break;
                                    case 6: $dayName = 'Saturday'; break;
                                  }
                                } else {
                                  // If days is already stored as a string (for backward compatibility)
                                  $dayName = $dayNumber;
                                }
                                
                                if($dayName === $day) {
                                  $entriesForDay = true;
                                  break;
                                }
                              }
                              
                              if(!$entriesForDay) {
                                echo '<tr><td colspan="5" class="text-center">No periods found for this day</td></tr>';
                              }
                            } else {
                              echo '<tr><td colspan="5" class="text-center">No periods found for this day</td></tr>';
                            }
                            ?>
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <?php
                  }
                  ?>
                </div>
              </div>
              <!-- /.card -->
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
  
  <!-- Add/Edit Period Modal -->
  <div class="modal fade" id="addPeriodModal" tabindex="-1" role="dialog" aria-labelledby="addPeriodModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addPeriodModalLabel">Add Period</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="periodForm">
            <input type="hidden" name="timetable_id" value="<?php echo $timetable->id; ?>">
            <input type="hidden" name="days" id="days">
            <input type="hidden" name="entry_id" id="entry_id">
            
            <div class="form-group">
              <label for="period">Period</label>
              <select class="form-control" id="period" name="period" required>
                <option value="">Select Period</option>
                <?php
                $bid = $this->session->userdata('login_id');
                $periods = $this->web->getallperiodbyid($bid);
                if(!empty($periods)) {
                  foreach($periods as $period) {
                    echo '<option value="'.$period->id.'">'.$period->start_time.' - '.$period->end_time.'</option>';
                  }
                }
                ?>
              </select>
            </div>
            <div class="form-group">
              <label for="subject">Subject</label>
              <select class="form-control" id="subject" name="subject" required>
                <option value="">Select Subject</option>
                <?php
                $subjects = $this->web->getAllSubjectsById_new($timetable->bid, $timetable->dept);
                if(!empty($subjects)) {
                  foreach($subjects as $subject) {
                    echo '<option value="'.$subject->id.'">'.$subject->name.'</option>';
                  }
                }
                ?>
              </select>
            </div>
            <div class="form-group">
              <label for="teacher">Teacher</label>
              <select class="form-control" id="teacher" name="teacher" required>
                <option value="">Select Teacher</option>
                <?php
                if(!empty($teachers)) {
                  foreach($teachers as $teacher) {
                    echo '<option value="'.$teacher->id.'">'.$teacher->name.'</option>';
                  }
                }
                ?>
              </select>
            </div>
            <div class="form-group">
              <label for="class_room">Classroom</label>
              <select class="form-control" id="class_room" name="class_room" required>
                <option value="">Select Classroom</option>
                <?php
                // Get classrooms or create sample ones if method doesn't exist
                $classrooms = method_exists($this->web, 'getAllClassrooms') ? 
                    $this->web->getAllClassrooms($timetable->bid) : 
                    [
                        (object)['id' => 'Room 101', 'name' => 'Room 101'],
                        (object)['id' => 'Room 102', 'name' => 'Room 102'],
                        (object)['id' => 'Room 103', 'name' => 'Room 103'],
                        (object)['id' => 'Lab 1', 'name' => 'Lab 1'],
                        (object)['id' => 'Lab 2', 'name' => 'Lab 2']
                    ];
                
                if(!empty($classrooms)) {
                  foreach($classrooms as $classroom) {
                    echo '<option value="'.$classroom->id.'">'.$classroom->name.'</option>';
                  }
                }
                ?>
              </select>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="savePeriod">Save</button>
        </div>
      </div>
    </div>
  </div>
  
  <?php $this->load->view('menu/footer')?>
</div>

<!-- jQuery -->
<script src="<?php echo base_url('adminassets/plugins/jquery/jquery.min.js')?>"></script>
<!-- Bootstrap 4 -->
<script src="<?php echo base_url('adminassets/plugins/bootstrap/js/bootstrap.bundle.min.js')?>"></script>
<!-- Select2 -->
<script src="<?php echo base_url('adminassets/plugins/select2/js/select2.full.min.js')?>"></script>
<!-- DataTables -->
<script src="<?php echo base_url('adminassets/plugins/datatables/jquery.dataTables.min.js')?>"></script>
<script src="<?php echo base_url('adminassets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')?>"></script>
<script src="<?php echo base_url('adminassets/plugins/datatables-responsive/js/dataTables.responsive.min.js')?>"></script>
<script src="<?php echo base_url('adminassets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')?>"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url('adminassets/dist/js/adminlte.min.js')?>"></script>

<script>
$(function () {
  $('.select2').select2();
  
  // Initialize select2 on period, subject and teacher dropdowns with dropdown parent to avoid z-index issues in modal
  $('#period, #subject, #teacher').select2({
    theme: 'bootstrap4',
    dropdownParent: $('#addPeriodModal')
  });
  
  // Initialize select2 for inline selects
  $('.select-subject, .select-teacher, .select-classroom').select2({
    theme: 'bootstrap4',
    width: '100%'
  });
  
  // Handle the Save All Periods button
  $('.save-all-periods').on('click', function() {
    const day = $(this).data('day');
    const dayNum = $(this).data('day-num');
    const timetableId = $('input[name="timetable_id"]').val();
    
    // Get all rows for this day
    const rows = $(`#${day}-periods tr`);
    const updateData = [];
    
    // Collect data from each row
    rows.each(function() {
      const entryId = $(this).find('.select-subject').data('entry-id');
      
      // Skip rows without entry ID (like "No periods found" message row)
      if (!entryId) return;
      
      const subject = $(this).find('.select-subject').val();
      const teacher = $(this).find('.select-teacher').val();
      const classroom = $(this).find('.select-classroom').val();
      
      updateData.push({
        entry_id: entryId,
        subject: subject,
        teacher: teacher,
        class_room: classroom
      });
    });

    console.log(updateData);
    
    // Don't proceed if no entries to update
    if (updateData.length === 0) {
      alert('No entries to update for this day.');
      return;
    }
    
    // Show loading indicator or disable button
    const button = $(this);
    const originalText = button.html();
    button.html('<i class="fas fa-spinner fa-spin"></i> Saving...');
    button.prop('disabled', true);
    
    // Send data to the controller
    $.ajax({
      url: '<?php echo base_url("User/update_day_timetable"); ?>',
      type: 'POST',
      data: {
        entries: JSON.stringify(updateData),
        timetable_id: timetableId,
        day: dayNum
      },
      dataType: 'json',
      success: function(response) {
        button.html(originalText);
        button.prop('disabled', false);
        
        if (response.status === 'success') {
          alert(response.message);
        } else {
          alert('Failed to save changes: ' + response.message);
        }
      },
      error: function() {
        button.html(originalText);
        button.prop('disabled', false);
        alert('An error occurred while saving. Please try again.');
      }
    });
  });
  
  // Close select2 dropdown when modal closes to prevent stuck UI
  $('#addPeriodModal').on('hidden.bs.modal', function () {
    $('#period, #subject, #teacher').select2('close');
  });
  
  // Open modal with day selected
  $('#addPeriodModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var day = button.data('day');
    var modal = $(this);
    
    // Reset form
    $('#periodForm')[0].reset();
    $('#entry_id').val('');
    
    // Reset Select2 dropdowns
    $('#period, #subject, #teacher').val(null).trigger('change');
    
    // Set the day
    $('#days').val(day);
    modal.find('.modal-title').text('Add Period for ' + day);
    
    // If editing an existing entry
    if (button.hasClass('edit-period')) {
      var id = button.data('id');
      var period = button.data('period');
      var subject = button.data('subject');
      var teacher = button.data('teacher');
      var classroom = button.data('classroom');
      
      $('#entry_id').val(id);
      
      // Set select dropdowns and trigger change event for Select2
      $('#period').val(period).trigger('change');
      $('#subject').val(subject).trigger('change');
      $('#teacher').val(teacher).trigger('change');
      $('#class_room').val(classroom);
      
      modal.find('.modal-title').text('Edit Period for ' + day);
    }
  });
  
  // Function to convert day name to numeric value
  function getDayNumericValue(dayName) {
    const dayMapping = {
      'Sunday': 0,
      'Monday': 1,
      'Tuesday': 2,
      'Wednesday': 3,
      'Thursday': 4,
      'Friday': 5,
      'Saturday': 6
    };
    return dayMapping[dayName] !== undefined ? dayMapping[dayName] : dayName;
  }
  
  // Save period
  $('#savePeriod').click(function() {
    // Get the day name from the hidden field
    var dayName = $('#days').val();
    
    // Convert day name to numeric value
    var dayNumeric = getDayNumericValue(dayName);
    
    // Create a form data object to manipulate before sending
    var formData = new FormData($('#periodForm')[0]);
    
    // Replace the day name with numeric value
    formData.set('days', dayNumeric);
    
    // Convert FormData to URL-encoded string for ajax request
    var serializedData = new URLSearchParams(formData).toString();
    
    $.ajax({
      url: '<?php echo base_url("User/save_timetable_entry"); ?>',
      type: 'POST',
      data: serializedData,
      dataType: 'json',
      success: function(response) {
        if (response.status === 'success') {
          $('#addPeriodModal').modal('hide');
          // Reload the page to show updated data
          location.reload();
        } else {
          alert('Failed to save period. Please try again.');
        }
      },
      error: function() {
        alert('An error occurred. Please try again.');
      }
    });
  });
});
</script>
</body>
</html> 