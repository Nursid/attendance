<?php
date_default_timezone_set('Asia/Kolkata');
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>MidApp</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- AdminLTE CSS -->
  <link rel="stylesheet" href="<?= base_url('adminassets/plugins/fontawesome-free/css/all.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('adminassets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('adminassets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('adminassets/dist/css/adminlte.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('adminassets/dist/css/mid.css') ?>">
</head>

<body class="hold-transition sidebar-mini">
<div class="wrapper">

<?php $this->load->view('menu/menu') ?>

<div class="content-wrapper">

<section class="content-header">
  <div class="container-fluid">
    <h4>Daily Attendance Report</h4>
  </div>
</section>

<section class="content">
<?php if($this->session->userdata('type') === 'B' || $role[0]->daily_report == "1"): ?>

<div class="container-fluid">

<div class="card card-primary">
<div class="card-body">

<!-- FILTERS -->
<div class="row">
  <div class="col-sm-2">
    <input type="date" id="start_date"
      value="<?= date('Y-m-d') ?>"
      max="<?= date('Y-m-d') ?>"
      class="form-control">
  </div>

  <div class="col-sm-2">
    <select name="depart" class="form-control">
      <option value="all">All Departments</option>
      <?php foreach($departments as $d): ?>
        <option value="<?= $d->id ?>"><?= $d->name ?></option>
      <?php endforeach ?>
    </select>
  </div>

  <div class="col-sm-2">
    <select name="shift" class="form-control">
      <option value="all">All Shifts</option>
      <?php foreach($shifts as $s): ?>
        <option value="<?= $s->id ?>"><?= $s->name ?></option>
      <?php endforeach ?>
    </select>
  </div>

  <div class="col-sm-2">
    <select name="section" class="form-control">
      <option value="all">All Sections</option>
      <?php foreach($sections as $s): ?>
        <option value="<?= $s->type ?>"><?= $s->name ?></option>
      <?php endforeach ?>
    </select>
  </div>

  <div class="col-sm-2">
    <button class="btn btn-success btn-block" onclick="loadDailyReport()">
      Show
    </button>
  </div>
</div>

<hr>

<!-- SUMMARY -->
<div class="row">
                            <div class="col-2">
                              <button type="button" id="sumActive" onClick="setAction('active');" class="btn btn-success btn-fill btn-block">Active   </button>
                            </div>
                            <div class="col-2">
                              <button type="button" id="sumPresent" onClick="setAction('present');" class="btn btn-success btn-fill btn-block">Present </button>
                            </div>
                            <div class="col-2">
                              <button type="button" id="sumAbsent" onClick="setAction('absent');" class="btn btn-danger btn-fill btn-block">Absent:</button>
                            </div>
                            <div class="col-2">
                              <button type="button" id="sumMispunch" onClick="setAction('mispunch');" class="btn btn-success btn-fill btn-block">Mispunch: </button>
                            </div>
                            <div class="col-2">
                              <button type="button" id="sumHalf" onClick="setAction('halfday');" class="btn btn-success btn-fill btn-block">Half day: </button>
                            </div>
                            <div class="col-2">
                              <button type="button" id="manual" onClick="setAction('manual');" class="btn btn-success btn-fill btn-block">Manual : </button>
                            </div>
                          </div>
                          <br>
                          <div class="row">
                            <div class="col-2">
                              <button type="button" id="sumLate" onClick="setAction('late');" class="btn btn-success btn-fill btn-block">Late: </button>
                            </div>

                            <div class="col-2">
                              <button type="button" id="sumEarly" onClick="setAction('early');" class="btn btn-success btn-fill btn-block">Early :</button>
                            </div>
                            <div class="col-2">
                              <button type="button" id="shortLeave" onClick="setAction('shortLeave');" class="btn btn-success btn-fill btn-block">Short Leave :</button>
                            </div>

                            <div class="col-2">
                              <button type="button" id="Unverified" onClick="setAction('unverified');" class="btn btn-success btn-fill btn-block">Unverified:- </button>
                            </div>

                            <div class="col-2">
                              <button type="button" id="fieldDuty" onClick="setAction('fieldDuty');" class="btn btn-success btn-fill btn-block">Field Duty : </button>
                            </div>
                            <div class="col-2">
                              <button type="button" id="gps" onClick="setAction('gps');" class="btn btn-success btn-fill btn-block">Gps : </button>
                            </div>
                          </div>

<hr>

  <div align="right">
    <input type="button" onClick="exportExcel()" value="Export To Excel" />
    <input type="button"  id="btnExport" value="Export To Pdf" onclick="exportPDF()" />
    <br>
  </div>  

<!-- TABLE -->
<table id="example1" class="table table-bordered table-striped">
  <thead id="reportHead"></thead>
  <tbody id="reportBody"></tbody>
</table>


</div>
</div>

</div>
<?php endif ?>
</section>
</div>

<?php $this->load->view('menu/footer') ?>
</div>

<!-- JS -->
<script src="<?= base_url('adminassets/plugins/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('adminassets/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('adminassets/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('adminassets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('adminassets/plugins/datatables-responsive/js/dataTables.responsive.min.js') ?>"></script>
<script src="<?= base_url('adminassets/dist/js/adminlte.min.js') ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/exceljs@4.4.0/dist/exceljs.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/file-saver@2.0.5/dist/FileSaver.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>


<script>
let table;
let dailyReportData = [];
let selectedReportDate = "";
let filteredReportData = [];

function loadDailyReport(){
  const date = $("#start_date").val();

  $.ajax({
    url: "http://31.97.230.189:3000/api/attendance/daily",
    method: "POST",
    contentType: "application/json",
    data: JSON.stringify({
      date,
      department: $("select[name='depart']").val(),
      shift: $("select[name='shift']").val(),
      section: $("select[name='section']").val(),
      companyId: <?= $this->session->userdata('login_id') ?> 
    }),
    success(res){
      if(!res.success) return alert("Failed");
      dailyReportData = res.data.report;   // 👈 store report
      selectedReportDate = date;           // 👈 store date

      filteredReportData = [...dailyReportData]; // copy

      renderTable(res.data.report);
      updateSummary(res.data.summary);
    }
  });
}

function getStatus(u, selectedDate) {
  let st = "P";

  if (u.data && u.data.length > 0) {
    if (u.absent === "1") st = "A";
    if (u.weekly_off === "1") st = "W";
    if (u.holiday === "1") st = "H";
    if (u.leave === "1") st = "L";

    let hasOut = false;
    u.data.forEach(d => {
      if (d.mode === "out") hasOut = true;
    });

    if (u.mispunch === "1" && !hasOut) {
      if (selectedDate !== new Date().toISOString().slice(0, 10)) {
        st = "MS";
      }
    } else if (u.halfday === "1") {
      st = "P/2";
    } else if (u.sl === "SL") {
      st = "SL";
    }

  } else {
    st = "A";
    if (u.weekly_off === "1") st = "W";
    if (u.holiday === "1") st = "H";
    if (u.leave === "1") st = "L";
  }

  return st;
}


function formatTime(unixTime) {
  const d = new Date(unixTime * 1000);
  return d.toLocaleTimeString("en-US", {
    hour: "2-digit",
    minute: "2-digit",
    hour12: true
  });
}


function buildTime(dayData = [], mode) {
  let html = "";

  dayData.forEach(d => {
    if (d.mode === mode) {
      let timeSt = "QR";
      let spanClass = "";

      if (d.manual === "1") {
        timeSt = "M";
        spanClass = "text-danger";
      }

      if (d.location && d.location !== "") {
        timeSt = "G";
        spanClass = "text-primary";
      }

      html += `
        <span class="${spanClass}">
          ${formatTime(d.time)}&nbsp;${timeSt}
        </span><br/>
      `;
    }
  });

  return html || "-";
}
function getStatus(u, selectedDate) {
  let st = "P";

  if (u.data && u.data.length > 0) {
    if (u.absent === "1") st = "A";
    if (u.weekly_off === "1") st = "W";
    if (u.holiday === "1") st = "H";
    if (u.leave === "1") st = "L";

    let hasOut = false;
    u.data.forEach(d => {
      if (d.mode === "out") hasOut = true;
    });

    if (u.mispunch === "1" && !hasOut) {
      if (selectedDate !== new Date().toISOString().slice(0, 10)) {
        st = "MS";
      }
    } else if (u.halfday === "1") {
      st = "P/2";
    } else if (u.sl === "SL") {
      st = "SL";
    }

  } else {
    st = "A";
    if (u.weekly_off === "1") st = "W";
    if (u.holiday === "1") st = "H";
    if (u.leave === "1") st = "L";
  }

  return st;
}

function renderTable(data) {

  console.log(data)

const selectedDate = $("#start_date").val();

// ---------- HEADER ----------
const theadHtml = `
  <tr>
    <td colspan="11" class="fw-bold text-center">
      Attendance For Date :- ${selectedDate}
    </td>
  </tr>
  <tr>
    <th>S.No</th>
    <th>Emp Code</th>
    <th>Name</th>
    <th>Desig</th>
    <th>Shift</th>
    <th>IN</th>
    <th>OUT</th>
    <th>Status</th>
    <th>W.H</th>
    <th>Late IN</th>
    <th>Early Out</th>
  </tr>
`;

$("#reportHead").html(theadHtml);

// ---------- INIT / GET TABLE ----------
if (!table) {
  table = $('#example1').DataTable({
    pageLength: 50,
    searching: true,
    ordering: false,
    destroy: true
  });
}

// ---------- CLEAR OLD ROWS ----------
table.clear();

// ---------- EMPTY STATE ----------
if (!data || data.length === 0) {
  const colCount = $('#example1 thead tr:last th').length;

  $("#reportBody").html(`
    <tr>
      <td colspan="${colCount}" class="text-center fw-bold text-muted">
        No record found
      </td>
    </tr>
  `);

  if (table) table.destroy();
  table = $('#example1').DataTable({ pageLength: 50 });

  return;
}


// ---------- ADD ROWS ----------
data.forEach((u, i) => {
  table.row.add([
    i + 1,
    u.emp_code,
    u.name,
    u.designation || "",
    `${u.group_name}<br>${u.shift_start || ""}<br>${u.shift_end || ""}`,
    buildTime(u.data, "in"),
    buildTime(u.data, "out"),
    getStatus(u, selectedDate),
    u.workingHrs || "-",
    u.late_hrs || "-",
    u.early_hrs || "-"
  ]);
});

table.draw();
}



function updateSummary(s){
  $("#sumActive").text(`Active : ${s.totalActive}`);
  $("#sumPresent").text(`Present : ${s.totalPresent}`);
  $("#sumAbsent").text(`Absent : ${s.totalAbsent}`);
  $("#sumLate").text(`Late : ${s.totalLate}`);
  $("#sumMispunch").text(`Mispunch : ${s.totalMispunch}`);
  $("#sumHalf").text(`Half Day : ${s.totalHalfDay}`);
  $("#sumEarly").text(`Early : ${s.totalEarly}`);
  $("#shortLeave").text(`ShortLeave : ${s.totalShortLeave}`);
  $("#Unverified").text(`Unverified : ${s.totalUnverified}`);
  $("#fieldDuty").text(`Field Duty : ${s.totalFieldDuty}`);
  $("#manual").text(`Manual : ${s.totalManual}`);
  $("#gps").text(`Gps : ${s.totalGps}`);
}
$(document).ready(function () {
  loadDailyReport();
});


function exportExcel() {
  const wb = new ExcelJS.Workbook();
  const sh = wb.addWorksheet("Report");

  sh.columns = [
    { header: "SNo.", key: "SNo", width: 8 },
    { header: "Emp Code", key: "emp_code", width: 22 },
    { header: "Name", key: "Name", width: 22 },
    { header: "Designation", key: "Designation", width: 30 },
    { header: "Shift", key: "Shift", width: 22 },
    { header: "IN", key: "IN", width: 22 },
    { header: "Out", key: "Out", width: 22 },
    { header: "Status", key: "Status", width: 12 },
    { header: "WH", key: "WH", width: 12 },
    { header: "LateIn", key: "LateIn", width: 12 },
    { header: "EarlyOut", key: "EarlyOut", width: 12 }
  ];

  /* ---------- HEADER STYLE ---------- */
  sh.getRow(1).font = { bold: true };
  sh.views = [{ state: "frozen", ySplit: 1 }];
  sh.autoFilter = "A1:J1";

  let count = 1;

  dailyReportData .forEach(user => {
    /* ---------- SHIFT ---------- */
    const shift = `${user.group_name || ""}\n${user.shift_start || ""}\n${user.shift_end || ""}`;

    let allIns = "";
    let allOuts = "";

    user.data?.forEach(d => {
      let timeType = "QR";
      if (d.manual === "1") timeType = "M";
      if (d.location) timeType = "G";

      const timeStr = new Date(d.time * 1000)
        .toLocaleTimeString("en-US", {
          hour: "2-digit",
          minute: "2-digit",
          hour12: true
        });

      if (d.mode === "in") allIns += `${timeStr} ${timeType}\n`;
      if (d.mode === "out") allOuts += `${timeStr} ${timeType}\n`;
    });

    /* ---------- STATUS LOGIC (PHP SAME) ---------- */
    let st = "A";

    if (user.data && user.data.length) {
      st = "P";

      if (user.absent === "1") st = "A";
      if (user.weekly_off === "1") st = "W";
      if (user.holiday === "1") st = "H";
      if (user.leave === "1") st = "L";

      const hasOut = user.data.some(d => d.mode === "out");

      if (user.mispunch === "1" && !hasOut) {
        if (selectedReportDate  !== new Date().toISOString().split("T")[0]) {
          st = "MS";
        }
      } else if (user.halfday === "1") {
        st = "P/2";
      } else if (user.sl === "SL") {
        st = "SL";
      }
    } else {
      st = "A";
      if (user.weekly_off === "1") st = "W";
      if (user.holiday === "1") st = "H";
      if (user.leave === "1") st = "L";
    }

    const row = sh.addRow({
      SNo: count++,
      emp_code: user.emp_code,
      Name: user.name,
      Designation: user.designation,
      Shift: shift,
      IN: allIns.trim(),
      Out: allOuts.trim(),
      Status: st,
      WH: user.workingHrs,
      LateIn: user.late_hrs,
      EarlyOut: user.early_hrs
    });

    /* ---------- WRAP TEXT ---------- */
    ["Shift", "IN", "Out"].forEach(key => {
      row.getCell(key).alignment = { wrapText: true, vertical: "top" };
    });
  });

  /* ---------- DOWNLOAD ---------- */
  wb.xlsx.writeBuffer().then(data => {
    const blob = new Blob([data], {
      type:
        "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8"
    });
    saveAs(blob, "Daily Attendance.xlsx");
  });
}

function createPdfHeaders() {
  return [
    { header: "SNo.", dataKey: "SNo" },
    { header: "Emp Code", dataKey: "emp_code" },
    { header: "Name", dataKey: "Name" },
    { header: "Designation", dataKey: "Designation" },
    { header: "Shift", dataKey: "Shift" },
    { header: "IN", dataKey: "IN" },
    { header: "Out", dataKey: "Out" },
    { header: "Status", dataKey: "Status" },
    { header: "WH", dataKey: "WH" },
    { header: "LateIn", dataKey: "LateIn" },
    { header: "EarlyOut", dataKey: "EarlyOut" }
  ];
}



function exportPDF() {

if (!dailyReportData.length) {
  alert("No data to export");
  return;
}

const { jsPDF } = window.jspdf;
const doc = new jsPDF("landscape");

doc.setFontSize(10);

doc.text(
  `Daily Attendance Report : ${selectedReportDate}`,
  14,
  10
);

const rows = [];
let count = 1;

dailyReportData.forEach(user => {

  const shift = `${user.group_name || ""}\n${user.shift_start || ""}\n${user.shift_end || ""}`;

  let allIns = "";
  let allOuts = "";

  user.data?.forEach(d => {
    let type = "QR";
    if (d.manual === "1") type = "M";
    if (d.location) type = "G";

    const t = new Date(d.time * 1000).toLocaleTimeString("en-US", {
      hour: "2-digit",
      minute: "2-digit",
      hour12: true
    });

    if (d.mode === "in") allIns += `${t} ${type}\n`;
    if (d.mode === "out") allOuts += `${t} ${type}\n`;
  });

  if (!allIns) allIns = " ";
  if (!allOuts) allOuts = " ";

  const status = getStatus(user, selectedReportDate);

  rows.push({
    SNo: count++,
    emp_code: user.emp_code || "",
    Name: user.name,
    Designation: user.designation || "",
    Shift: shift,
    IN: allIns,
    Out: allOuts,
    Status: status,
    WH: user.workingHrs || "",
    LateIn: user.late_hrs || "",
    EarlyOut: user.early_hrs || ""
  });
});

doc.autoTable({
  startY: 15,
  head: [createPdfHeaders().map(h => h.header)],
  body: rows.map(r => Object.values(r)),
  styles: {
    fontSize: 8,
    cellPadding: 2,
    valign: "middle"
  },
  headStyles: {
    fillColor: [52, 58, 64],
    textColor: 255,
    halign: "center"
  },
  columnStyles: {
    0: { halign: "center", cellWidth: 10 },   // SNo
    1: { halign: "center", cellWidth: 25 },   // Emp Code
    2: { cellWidth: 35 },                     // Name
    3: { cellWidth: 40 },                     // Designation
    4: { cellWidth: 40 },                     // Shift
    5: { cellWidth: 35 },                     // IN
    6: { cellWidth: 35 },                     // OUT
    7: { halign: "center", cellWidth: 20 },   // Status
    8: { halign: "center", cellWidth: 20 },   // WH
    9: { halign: "center", cellWidth: 25 },   // LateIn
    10:{ halign: "center", cellWidth: 25 }    // EarlyOut
  },
  margin: { left: 5, right: 5 }
});

doc.save(`Daily_Attendance_${selectedReportDate}.pdf`);
}


function hasTime(timeStr) {
  if (!timeStr) return false;

  // "2:15 Hr" → [2,15]
  const match = timeStr.match(/(\d+):(\d+)/);
  if (!match) return false;

  const hours = parseInt(match[1], 10);
  const minutes = parseInt(match[2], 10);

  return (hours * 60 + minutes) > 0;
}


function setAction(action) {

if (!dailyReportData.length) return;

filteredReportData = dailyReportData.filter(u => {
  const status = getStatus(u, selectedReportDate);

  switch (action) {

    case "active":
      return true;

    case "present":
      // ✔ data hai AND absent nahi AND W/H/L nahi
      return (
        u.data &&
        u.data.length > 0 &&
        status !== "A" &&
        status !== "W" &&
        status !== "H" &&
        status !== "L"
      );

    case "absent":
      return status === "A";

    case "mispunch":
      return u.mispunch === "1";

    case "halfday":
      return u.halfday === "1";

      case "late":
        return hasTime(u.late_hrs);

      case "early":
        return hasTime(u.early_hrs);


    case "shortLeave":
      return u.sl === "SL";

    case "unverified":
      return u.unverified === "1";

    case "fieldDuty":
      return u.fieldDuty === "1";

    case "manual":
      return u.manual === "1";

    case "gps":
      return u.gps === "1";

    default:
      return true;
  }
});

console.log(filteredReportData)
renderTable(filteredReportData);
}



</script>



</body>
</html>
