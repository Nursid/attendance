<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Web_Model extends CI_Model
{
	function __construct(){
		parent::__construct();
		$this->load->database();
	}
	public function login($username,$password){
		return $this->db->where('username',$username)->where('password',$password)->where('status',1)->get('web_login')->row_array();
	}
	public function getDepartmentList(){
		return $this->db->get('department')->result();
	}
	public function getSubdepartmentList(){
		return $this->db->get('department_sub')->result();
	}
	public function getAssignSubDepartList(){
		return $this->db->get('assigned_sdepartment')->result();
	}
	public function getAssignDepartList(){
		return $this->db->get('assigned_department')->result();
	}
	public function getDepartById($id){
		return $this->db->query("SELECT id,department,Dep_code,remark FROM department WHERE id='$id'")->result();
	}
	public function getSubDepartById($id){
		return $this->db->query("SELECT * FROM department_sub WHERE id='$id'")->row_array();
	}
	public function getSubDepartByDepartId($id){
		return $this->db->query("SELECT * FROM department_sub WHERE department_id='$id'")->result();
	}
	public function getSubDepartByBusiness($id,$did){
		return $this->db->query("SELECT * FROM assigned_sdepartment WHERE user_business_id = '$id' AND depart_id = '$did'")->result();
	}
	public function getBusinessUser(){
		return $this->db->query("SELECT groups.id,login.name,login.id as userid FROM groups,login WHERE groups.id=login.user_group AND groups.id='1'order by id DESC")->result();
	}
	public function getallbusiness(){
		// return $this->db->query("SELECT groups.id,login.* FROM groups,login WHERE groups.id=login.user_group AND groups.id='1'")->result();
		return $this->db->query("SELECT * FROM login WHERE login.user_group='1'order by id DESC")->result();
	}
	public function getallusers(){
		return $this->db->query("SELECT * FROM login WHERE login.user_group='2'order by id DESC")->result();
	}
	// public function getallusers(){
	//     return $this->db->query("SELECT groups.id,login.* FROM groups,login WHERE groups.id=login.user_group AND groups.id='2'")->result();
	// }
	public function assigndata($data){
		$val=$this->db->insert('assigned_department',$data);
		return $val;
	}
	public function checkGeneratedLogin($id){
		return $this->db->query("SELECT * FROM web_login WHERE login_id = '$id'")->row_array();
	}
	public function getBusinessById($id){
		return $this->db->query("SELECT * FROM login WHERE id = '$id'")->row_array();
	}
	public function statusActivate($id){
		$val = $this->db->query("UPDATE web_login SET status = 1 WHERE login_id = '$id'");
		return $val;
	}
	public function statusInctivate($id){
		$val = $this->db->query("UPDATE web_login SET status = 0 WHERE login_id = '$id'");
		return $val;
	}
	public function checkUserStatus($u,$p){
		return $this->db->query("SELECT status AS status FROM web_login WHERE username= '$u' AND password= '$p'")->row_array();
	}
	public function getDepartByBusiness($id){
		return $this->db->query("SELECT department_id AS depid FROM assigned_department WHERE user_bussiness_id = '$id' ")->result();
	}
	public function getTokenInfo($id,$bid){
		return $this->db->query("SELECT * FROM token WHERE Dept_id = '$id' AND user_bussiness_id = '$bid'")->result();
	}
	public function getTokenBySubDept($id,$bid){
		return $this->db->query("SELECT * FROM token WHERE user_bussiness_id = '$bid' AND Sub_deptid = '$id' ")->result();
	}
	public function getNextTokenByDept($did,$token){
		return $this->db->query("SELECT * FROM token WHERE Dept_id = '$did' AND token > '$token' AND status = 0 ")->row_array();
	}
	public function getUserAuthKey($auth){
		return $this->db->query("SELECT firebassid AS fid FROM login WHERE id= '$auth' ")->row_array();
	}
	public function tokenActivate($id,$cid){
		$val = $this->db->query("UPDATE token SET status = 1, counter_id = '$cid' WHERE id = '$id' ");
		return $val;
	}
	public function tokenClose($id,$cid){
		$val = $this->db->query("UPDATE token SET status = 2, counter_id = '$cid' WHERE id = '$id' ");
		return $val;
	}
	public function checkAssignDepart($bid,$did){
		return $this->db->query("SELECT * FROM `assigned_department` WHERE user_bussiness_id = '$bid' AND department_id = '$did' ")->result();
	}
	public function checkMaxCounterByDepart($bid,$did){
		return $this->db->query("SELECT MAX(counter_id) AS mid FROM counters WHERE business_id = '$bid' AND depart_id = '$did'")->row_array();
	}
	public function getLoginIdByName($name){
		return $this->db->query("SELECT id AS 'lid' FROM login WHERE name = '$name'")->row_array();
	}
	public function getAllCounters(){
		return $this->db->query("SELECT * FROM counters ")->result();
	}
	public function getNameByLoginId($id){
		return $this->db->query("SELECT name FROM login WHERE id = '$id'")->row_array();
	}
	public function getCounterInfo($id){
		return $this->db->query("SELECT * FROM counters WHERE login = '$id'")->row_array();
	}
	public function getCounterByBusiness($id,$did){
		return $this->db->query("SELECT * FROM counters WHERE business_id = '$id' AND depart_id = '$did'")->result();
	}
	public function checkOPass($id,$p){
		return $this->db->query("SELECT * FROM web_login WHERE id = '$id' AND password = '$p'")->result();
	}
	public function upPass($id,$p){
		$res = $this->db->query("UPDATE web_login SET password = '$p' WHERE id = '$id'");
		return $res;
	}
	public function DataGet($id){
		return $this->db->query("SELECT * FROM login WHERE m_id = '$id'")->row_array();

	}
	public function TypeGet($id){
		return $this->db->query("SELECT * FROM groups WHERE id = '$id'")->row_array();

	}
	public function getPages(){
		return $this->db->query("SELECT * FROM pages")->result();
	}
	public function checkPermission($id){
		return $this->db->query("SELECT * FROM `assign_menu` where assign_bussiness_id='$id'")->row_array();
	}
	public function getSubPages($id){
		return $this->db->query("SELECT * FROM Sub_Page where page_id='$id'")->result();
	}
	//Get Book appoiment Data
	public function GetBookAppo($id){
		return $this->db->query("SELECT * FROM book_appointment where bussiness_id='$id'")->result();
	}
	public function GetBookToken($id){
		return $this->db->query("SELECT * FROM department id='$id'")->result();
	}
	public function getUsers($id){
		return $this->db->where('id',$id)->get('login')->row_array();
	}
	public function checkMobile($mobile){
		return $this->db->where('mobile',$mobile)->get('login')->row_array();
	}

	public function Get_appoiment_data($login_id,$business_id,$depart_id,$sub_depart_id){
		$data="select * from appoint_setting where login_id='$login_id' and bussiness_id='$business_id' and department='$depart_id' and subdepart='$sub_depart_id'";
		return $this->db->query($data)->row_array();
	}
	public function GetDepartNameId($id){
		return $this->db->query("select * from department where id='$id'")->row_array();
	}
	public function GetSubDepartNameId($id){
		return $this->db->query("select * from department_sub where id='$id'")->row_array();
	}
	public function GetAssin($id){
		return $this->db->query("select * from assign_menu  where assign_by='$id'")->result();

	}

	public function getUserss($id){
		return $this->db->query("SELECT * FROM login where id='$id'")->row_array();

	}
	public function getPagesId($id){
		return $this->db->query("SELECT * FROM pages where page_id='$id'")->row_array();
	}

	public function statusInctivation($id){
		$val = $this->db->query("UPDATE assign_menu SET status = 1 WHERE id = '$id'");
		return $val;
	}
	public function statusactivation($id){
		$val = $this->db->query("UPDATE assign_menu SET status = 0 WHERE id = '$id'");
		return $val;
	}
	public function getRequest(){
		return $this->db->query("SELECT * FROM qr_request group by request_id ")->result();
	}
	public function GetUsersCount(){
		return $this->db->query("SELECT COUNT(id) as countData FROM login")->row_array();

	}
	public function GetCountersCount(){
		return $this->db->query("SELECT COUNT(id) as counterData FROM counters")->row_array();

	}
	public function GetBookCount(){
		return $this->db->query("SELECT COUNT(id) as bookAppoinment FROM book_appointment")->row_array();

	}
	public function getAllLogin(){
		return $this->db->query("SELECT * FROM login ")->result();
	}
	public function getAssignPage($user_id,$page_id){
		return $this->db->query("SELECT * FROM assign_menu where assign_bussiness_id='$user_id' and assign_menu_id='$page_id'")->row_array();
	}
	public function getallNewQr(){
		return $this->db->query("SELECT *,(SELECT login.name FROM login WHERE login.id=new_qr.login_id)as name,(SELECT login.mobile FROM login WHERE login.id=new_qr.login_id)as mobile FROM `new_qr` WHERE status=1")->result();
	}

	public function qrProfile($id){
		return $this->db->query("SELECT * FROM new_qr WHERE qr_code = '$id'")->row_array();
	}

	public function getQrProfile($id){
		return $this->db->query("SELECT * FROM login WHERE id = '$id'")->row_array();

	}

	public function attendance($id){
		return $this->db->query("SELECT user_request.user_id,user_request.doj,user_request.left_date,(select name from login WHERE login.id = user_request.user_id) as name,(select image from login WHERE login.id = user_request.user_id) as image,(select business_group from login WHERE login.id = user_request.user_id) as business_group,user_request.user_status,(select login.designation from login WHERE login.id = user_request.user_id) as designation,(select login.m_id from login WHERE login.id = user_request.user_id) as mid,(select login.emp_code from login WHERE login.id = user_request.user_id) as emp_code FROM `user_request` WHERE user_request.business_id='$id'")->result();
	}
	public function getallpremium(){
		return $this->db->query("SELECT * FROM login WHERE login.user_group='1' order by id DESC")->result();
	}
	public function getallpremium2($start_time,$end_time){
		return $this->db->query("SELECT DISTINCT bussiness_id as actbuis FROM attendance WHERE io_time BETWEEN $start_time and $end_time order by id DESC")->result();
	}
	
	

	public function updateValidity($id,$validity){
		$sql = "UPDATE login SET validity='$validity',prime_att=1,premium=2 WHERE id='$id'";
		$res = $this->db->query($sql);
		return $res;
	}

	public function updateStartDate($id,$validity){
		$sql = "UPDATE login SET start_date='$validity',prime_att=1,premium=1 WHERE id='$id'";
		$res = $this->db->query($sql);
		return $res;
	}



	///////arpit//////


	public function getemployees(){
		return $this->db->get('user_request')->result();
	}
	public function getNameByUserId($id){
		return $this->db->query("SELECT * FROM login WHERE id = '$id' ")->result();
	}


	public function getEmployeesList($id){
		return $this->db->query("SELECT * FROM user_request WHERE business_id = '$id'  ")->result();
	}
	public function getWorkingEmployeesList($id){
		return $this->db->query("SELECT * FROM user_request WHERE business_id = '$id' and left_date ='' order by doj")->result();
	}
	public function getActiveEmployeesList($id){
		return $this->db->query("SELECT * FROM user_request WHERE business_id = '$id' and left_date ='' ")->result();
	}

	public function getUserAttendanceByDate($id,$start_time,$end_time){
		$sql = "SELECT * FROM `attendance` WHERE status=1 and io_time BETWEEN $start_time and $end_time and user_id='$id' order by io_time ASC";
		$res = $this->db->query($sql);
		return $res->result();
		// return $this->db->query("SELECT * FROM login WHERE id = '$id'")->row_array();
	}



	public function getUserOutAttendanceByDate($id,$start_time,$end_time){
		$sql = "SELECT * FROM `attendance` WHERE status=1 and mode='out' and io_time BETWEEN $start_time and $end_time and user_id='$id' order by io_time ASC";
		$res = $this->db->query($sql);
		return $res->result();
		// return $this->db->query("SELECT * FROM login WHERE id = '$id'")->row_array();
	}

	public function getUserInAttendanceByDate($id,$start_time,$end_time){
		$sql = "SELECT * FROM `attendance` WHERE status=1 and mode='in' and io_time BETWEEN $start_time and $end_time and user_id='$id' order by io_time ASC";
		$res = $this->db->query($sql);
		return $res->result();
		// return $this->db->query("SELECT * FROM login WHERE id = '$id'")->row_array();
	}




	//public function getMinAttendanceByDate($id,$start_time,$end_time){
	//$sql = "SELECT * FROM `attendance` WHERE status=1 and io_time BETWEEN $start_time and $end_time and user_id='$id ' ";
	//$res = $this->db->query($sql);
	//return $res->result();
	// return $this->db->query("SELECT * FROM login WHERE id = '$id'")->row_array();
	//	}



	public function getDailyReport($id){

		return $this->db->query("SELECT * FROM attendance WHERE bussiness_id = '$id' ")->result();
	}


	public function getAttendByUserId($id,$cdate){
		return $this->db->query("SELECT * FROM attendance WHERE user_id = '$id' AND date = '$cdate' ")->result();
	}



	public function statusActivateEmp($id){
		$val = $this->db->query("UPDATE user_request SET user_status = 1 WHERE user_id = '$id'");
		return $val;
	}
	public function statusInctivateEmp($id){
		$val = $this->db->query("UPDATE user_request SET user_status = 0 WHERE user_id = '$id'");
		return $val;
	}

	public function getBusinessGroupByUserId($id){
		return $this->db->query("SELECT * FROM business_groups WHERE id = '$id' ")->result();
		// return $this->db->query("SELECT * FROM login WHERE id = '$id'")->row_array();
	}
	public function getBusinessGroupByBusinessId($id){
		return $this->db->query("SELECT * FROM business_groups WHERE business_id = '$id' and status=1")->result();
		// return $this->db->query("SELECT * FROM login WHERE id = '$id'")->row_array();
	}

	public function getUserGroup($gid){
		//  return $this->db->query("SELECT * FROM business_groups WHERE id = '$gid' AND status='1'")->result();
		$sql = "SELECT * FROM business_groups where id='$gid' AND status='1'";
		$res = $this->db->query($sql);
		return $res->row();
	}


	public function getLeaveList(){
		return $this->db->get('leaves')->result();
	}

	public function getLeaveByBusinessId($id){
		return $this->db->query("SELECT * FROM leaves WHERE bid = '$id' ")->result();

	}

	public function getLeaveByUserId($id){
		return $this->db->query("SELECT * FROM leaves WHERE uid = '$id' ")->result();

	}


	public function getMaxUserAttendanceByDate($id,$start_time,$end_time){

		return $this->db->query("SELECT MAX(io_time) AS outtime FROM `attendance` WHERE status=1 and mode='out' and io_time BETWEEN $start_time and $end_time and user_id='$id' ")->result();

	}

	public function getMinUserAttendanceByDate($id,$start_time,$end_time){

		return $this->db->query("SELECT MIN(io_time) AS intime FROM `attendance` WHERE status=1 and mode='in' and io_time BETWEEN $start_time and $end_time and user_id='$id' ")->result();

	}


	public function updateFromLDate($id,$from_date){
		$sql = "UPDATE leaves SET from_date='$from_date' WHERE id='$id'";
		$res = $this->db->query($sql);
		return $res;
	}

	public function updateToLDate($id,$to_date){
		$sql = "UPDATE leaves SET to_date='$to_date' WHERE id='$id'";
		$res = $this->db->query($sql);
		return $res;
	}
	public function statusaprove($id){
		$val = $this->db->query("UPDATE leaves SET status = 1 WHERE id = '$id'");
		return $val;
	}
	public function statusreject($id){
		$val = $this->db->query("UPDATE leaves SET status = 3 WHERE id = '$id'");
		return $val;
	}



	public function getHolidayByBusinessId($buid,$i){
		return $this->db->query("SELECT * FROM holiday WHERE business_id = '$buid' and date='$i' and status=1  ")->result();

	}
	public function getLeaveByDate($id,$i){
		return $this->db->query("SELECT * FROM leaves WHERE uid = '$id' and '$i' BETWEEN from_date and to_date and status=1 ")->result();

	}


	public function updateinAttDate($id,$to_date){
		$sql = "UPDATE attendance SET to_date='$to_date' WHERE id='$id'";
		$res = $this->db->query($sql);
		return $res;
	}


	public function getIdByMb($mob){
		return $this->db->query("SELECT * FROM login WHERE mobile = '$mob'")->result();

	}



	public function getMaxMid(){
		return $this->db->query("SELECT MAX(m_id) AS m_id FROM login")->row_array();
	}


	public function getMaxRuleid($id){
		return $this->db->query("SELECT max(rule_id) as rule_id FROM attendance_rule WHERE bid='$id'")->row_array();
	}

	public function addAttendanceRule($data){
		$val=$this->db->insert('attendance_rule',$data);
		return $val;
	}

	public function getAttendanceRules($id){
		return $this->db->query("SELECT * FROM attendance_rule WHERE bid ='$id'")->result();
	}

	public function getRule($bid,$ruleId){
		return $this->db->query("SELECT * from attendance_rule WHERE bid='$bid' and rule_id='$ruleId'")->row_array();
	}
	public function updateAttendanceRule($id,$name){
		$sql = "UPDATE attendance_rule SET name='$name' WHERE id='$id'";
		$res = $this->db->query($sql);
		return $res;
	}

	public function updateAttendanceRulebyId($id,$col,$val){
		$sql = "UPDATE attendance_rule SET $col='$val' WHERE id='$id'";
		$res = $this->db->query($sql);
		return $res;
	}

	public function getOpenLeave($bid,$uid){
		return $this->db->query("SELECT * from open_leave WHERE bid='$bid' and uid='$uid'")->row_array();
	}

	public function addOpenLeave($data){
		$val=$this->db->insert('open_leave',$data);
		return $val;
	}

	public function updateOpenLeave($bid,$uid,$openDate,$closeDate,$cl,$pl,$el,$sl,$other,$hl,$rh,$comp_off,$limit_type,$fixed_limit,$carry,$date){
		$sql = "UPDATE open_leave SET open_date='$openDate',close_date='$closeDate',cl='$cl',pl='$pl',el='$el',sl='$sl',other='$other',hl='$hl',rh='$rh',comp_off='$comp_off',limit_type='$limit_type',fixed_limit='$fixed_limit',carry='$carry',date_time='$date' WHERE bid='$bid' and uid='$uid' and deleted=0";
		$res = $this->db->query($sql);
		return $res;
	}

	/*   *********************************         */
	/*   *************** KRISHNA NAND 14-062022 FOR SALLERY MODULE  ****************** ****** */
	/*   *********************************         */



		public function getSalleryEmply($postData = ''){

		$loginID = $this->web->session->userdata('login_id'); // THIS IS BUSINESS ID
		$this->db->select('user_request.*, login.name as empName, login.mobile as empMobile, login.emp_code, login.designation as empDesignation, login.business_group');
		$this->db->join('login', 'login.id = user_request.user_id', 'LEFT');
		$empList =  $this->db->get_where('user_request', array('user_request.business_id' => $loginID))->result();

		// echo '<pre>'; print_r($empList[0]->business_id); die();

		if(!empty($empList))
		{
			if(!empty($postData))
			{
				$yearName  = date('Y', strtotime($postData['date_from']));
				$monthName = date('m', strtotime($postData['date_from']));
			}
			else
			{
				$yearName = date('Y');
				$monthName = date('m');
			}

			$holidays = $this->getHoliday($loginID);
			$holiday_array = array();
			if($holidays){
				foreach($holidays as $holiday){
					$holiday_array[] = array(
						'date'=>date('d.m.Y',$holiday->date),
					);
				}
			}

			foreach ($empList as $key => $dataVal) {

				//HARE GET AND CALCULATE // ALLOWANCE + OVERTIME + BONUS + INCENTIVE
				$getAmount = $this->getTypeTotalAmount($dataVal->user_id, $yearName, $monthName, '1' );


				// HARE GET USER TOTAL CTC
				$getCTC  = $this->db->query("SELECT * FROM user_ctc WHERE  business_id = '".$loginID."' AND user_id = '".$dataVal->user_id."' AND  YEAR(date) = '".$yearName."' AND MONTH(date) = '".$monthName."' ")->row_array();

				if(empty($getCTC))
				{
					$getCTC  = $this->db->query("SELECT * FROM user_ctc WHERE  business_id = '".$loginID."' AND user_id = '".$dataVal->user_id."'  ORDER BY date DESC ")->row_array();
				}

				/// HARE CALCULATE TOTAL SALLARY
				$empList[$key]->totalSalary = @$getAmount['addAmount']+@$getCTC['total_ctc_amount'];


				// HARA GET TOTAL DEDUCTION AMOUNT
				$getTotalDeduction = $this->getTypeTotalAmount($dataVal->user_id, $yearName, $monthName, '2' );

				// HARA GET TOTAL PAID AMOUNT
				$getTotalPaid                = $this->getTypeTotalAmount($dataVal->user_id, $yearName, $monthName, '3' );
				$empList[$key]->getTotalPaid =  $getTotalPaid['addAmount'];

				$empList[$key]->deductionAmount =  $getTotalDeduction['addAmount'];

				$groups = $this->getUserGroup($dataVal->business_group);
				$grp = array();
				$day_shift_start = array();
				$day_shift_end = array();

				if($groups){
					$weekly_off = explode(",",$groups->weekly_off);
					$day_shift_start = explode(",",$groups->day_start_time);
					$day_shift_end = explode(",",$groups->day_end_time);
					$shift_start = $groups->shift_start;
					$shift_end = $groups->shift_end;
					$group_name = $groups->name;
					foreach($weekly_off as $k=>$off){
						if($off==1){
							$grp[] = array(
								'day_off'=>$k+1
							);
						}
					}
				}else{
					$shift_start = "";
					$shift_end = "";
					$group_name = "";
				}

				$leaves = $this->getEmpLeaves($dataVal->user_id);
				$leaves_array = array();
				if($leaves){
					foreach($leaves as $leave){
						$from_date_leave=date_create(date("Y-m-d",$leave->from_date));
						$to_date_leave=date_create(date("Y-m-d",$leave->to_date));
						$leave_diff=date_diff($from_date_leave,$to_date_leave);
						$leave_days = $leave_diff->format("%a");
						$leave_days++;
						for($l=0;$l<$leave_days;$l++){
							$leave_start_date = strtotime(date("d-m-Y",$leave->from_date)." +".$l." days");
							$leaves_array[] = array(
								'date'=>date('d.m.Y',$leave_start_date),
							);
						}
					}
				}

				$rules = $this->getRule($loginID,$dataVal->rule_id);
				$mispunch = "0";
				$ca_wo_lofi = "0";
				$mark_ab_week = "0";
				$ov_shift = "0";
				$sl_late_on = "0";
				$sl_early_on = "0";
				$halfday_on = "0";
				$absent_on = "0";
				$overtime_wh_on = "0";
				$sl_late_time = 0;
				$sl_early_time = 0;
				$half_wo_time = 0;
				$ab_wo_time = 0;
				$ov_out_time = 0;
				$ov_wo_time = 0;

				if($rules){
					$mispunch = $rules['mispunch'];
					$sl_late_time = $rules['sl_late'];
					$sl_early_time = $rules['sl_early'];
					$half_wo_time = $rules['halfday'];
					$ab_wo_time = $rules['absent'];
					$ov_out_time = $rules['overtime_shiftout'];
					$ov_wo_time = $rules['overtime_wh'];
					$ca_wo_lofi = $rules['wh_cal'];
					$mark_ab_week = $rules['wo_absent'];
					$ov_shift = $rules['overtime_shift'];
					$sl_late_on = $rules['sl_late_on'];
					$sl_early_on = $rules['sl_early_on'];
					$halfday_on = $rules['halfday_on'];
					$absent_on = $rules['absent_on'];
					$overtime_wh_on = $rules['overtime_wh_on'];
				}
				$months_array = array();
				$totalPresent = 0;
				$totalAbsent = 0;
				$totalWeekOff = 0;
				$totalHoliday = 0;
				$totalLeaves = 0;
				$totalShortLeave = 0;
				$totalP2 = 0;
				$totalOT = 0;
				$totalWorkingHrs = "00:00 Hr";
				$totalLate = "00:00 Hr";
				$totalEarly = "00:00 Hr";
				$days_array = array();
				$seconds = 0;
				$previousAt = array();
				$nextAt = array();
				$maxDays = date("t",strtotime($yearName."-".$monthName."-01"));
				$monthStartTime = strtotime(date("d-m-Y 00:00:00",strtotime($yearName."-".$monthName."-01")));
				$monthEndTime = strtotime(date("d-m-Y 23:59:59",strtotime($yearName."-".$monthName."-01"))." +".$maxDays." days");
				$monthUserAt = $this->app->getUserAttendanceReportByDate($monthStartTime,$monthEndTime,$dataVal->user_id,$loginID,1);
				if($empList[$key]->totalSalary>0){
					
					for($d=0; $d<$maxDays;$d++){
						$new_start_time = strtotime(date("d-m-Y 00:00:00",strtotime($yearName."-".$monthName."-01"))." +".$d." days");
						$new_end_time = strtotime(date("d-m-Y 23:59:59",strtotime($yearName."-".$monthName."-01"))." +".$d." days");
						$next_day_start_time = strtotime(date("d-m-Y 00:00:00",strtotime($yearName."-".$monthName."-01"))." +".($d+1)." days");
						$next_day_end_time = strtotime(date("d-m-Y 23:59:59",strtotime($yearName."-".$monthName."-01"))." +".($d+1)." days");

						$pre_day_start_time = strtotime(date("d-m-Y 00:00:00",strtotime($yearName."-".$monthName."-01"))." +".($d-1)." days");
						$pre_day_end_time = strtotime(date("d-m-Y 23:59:59",strtotime($yearName."-".$monthName."-01"))." +".($d-1)." days");
						$days_array[]= date("d",$new_start_time);
						$data = array();
						$day_seconds=0;
						$late_seconds=0;
						$early_seconds=0;
						$ot_seconds=0;
						$day_hrs = "W.H 00:00 Hr";
						$late_hrs = "00:00";
						$early_hrs = "00:00";
						$ot_hrs = "00:00";
						$halfday = "0";
						$absentWo = "0";
						$sl = "s";
						$day_status="";
						$day_sub_status="";
						if(($dataVal->doj!="" || $monthStartTime>=$user->doj) && ($dataVal->left_date=="" || $monthStartTime<$dataVal->left_date)){
							$user_at = array_filter($monthUserAt, function($val) use($new_start_time, $new_end_time){
								return ($val->io_time>=$new_start_time and $val->io_time<=$new_end_time);
							});
							$user_at = array_reverse($user_at);
							$nextAt = array_filter($monthUserAt, function($val) use($next_day_start_time, $next_day_end_time){
								return ($val->io_time>=$next_day_start_time and $val->io_time<=$next_day_end_time);
							});
							$nextAt = array_reverse($nextAt);
							if($d==0){
								$previousAt = array_filter($monthUserAt, function($val) use($pre_day_start_time, $pre_day_end_time){
									return ($val->io_time>=$pre_day_start_time and $val->io_time<=$pre_day_end_time);
								});
								$previousAt = array_reverse($previousAt);
							}
							
							$off = array_search(date('N',$new_start_time),array_column($grp,'day_off'));
							$holi = array_search(date('d.m.Y',$new_start_time),array_column($holiday_array,'date'));
							$lv = array_search(date('d.m.Y',$new_start_time),array_column($leaves_array,'date'));

							$prevWeekOff = array_search(date('N',$pre_day_start_time),array_column($grp,'day_off'));
							$nextWeekOff = array_search(date('N',$next_day_start_time),array_column($grp,'day_off'));
							$prevHoliOff = array_search(date('d.m.Y',$pre_day_start_time),array_column($holiday_array,'date'));
							$nextHoliOff = array_search(date('d.m.Y',$next_day_start_time),array_column($holiday_array,'date'));
							
							if(!empty($day_shift_start)){
								if($day_shift_start[date('N',$new_start_time)-1]!=null){
									$shift_start = $day_shift_start[date('N',$new_start_time)-1];
								}
							}
							if(!empty($day_shift_end)){
								if($day_shift_end[date('N',$new_start_time)-1]!=null){
									$shift_end = $day_shift_end[date('N',$new_start_time)-1];
								}
							}

							$prevPresent = false;
							$nextPresent = false;
							if($mark_ab_week==1){
							    if(!empty($previousAt) || !is_bool($prevWeekOff) || !is_bool($prevHoliOff)){
								$prevPresent = true;
								}

								if(!empty($nextAt) || !is_bool($nextWeekOff) || !is_bool($nextHoliOff)){
									$nextPresent = true;
								}
							}else{
							    $prevPresent = true;
							}
							
							if($d==0){
							    $prevPresent = true;
							}

							if(!is_bool($off) && ($prevPresent || $nextPresent)){
								$weekOff = "1";
								$totalWeekOff++;
							}else{
								$weekOff = "0";
							}

							if(!is_bool($holi) && ($prevPresent || $nextPresent)){
								$holiday="1";
								$totalHoliday++;
							}else{
								$holiday="0";
							}

							if(!is_bool($lv)){
								$totalLeaves++;
								$day_leave="1";
							}else{
								$day_leave="0";
							}
							$previousAt = $user_at;
							$nextAt = array();
							if(!empty($user_at)){
								$ins_array = array();
								$outs_array = array();
								
								foreach($user_at as $at){
									$timeSearch = array_search($at->io_time,array_column($data,'time'));
									if(is_bool($timeSearch)){
										$data[] = array(
											'mode'=>$at->mode,
											'time'=>$at->io_time,
											'io_time'=>$at->io_time,
											'comment'=>$at->comment,
											'manual'=>$at->manual,
											'location'=>$at->location
										);
										if($at->mode=='in' && !in_array($at->io_time,$ins_array)){
											$ins_array[]=$at->io_time;
										}
										if($at->mode=='out' && !in_array($at->io_time,$outs_array)){
											$outs_array[]=$at->io_time;
										}
									}
								}
								$io_end = count($ins_array)-count($outs_array);
								if(count($outs_array)<count($ins_array)){
									for($io=0; $io<$io_end;$io++){
										$outs_array[]="0";
									}
								}
								foreach($ins_array as $k => $ins){
									if($outs_array[$k]!="0"){
										if($outs_array[$k]>$ins_array[$k]){
											$seconds += $outs_array[$k]-$ins_array[$k];
										}
										$day_seconds += $outs_array[$k]-$ins_array[$k];
									}
								}
								// if($ca_wo_lofi=="1"){
								// 	$day_out = "0";
								// 	for($o=count($outs_array)-1;$o>=0;$o--){
								// 		if($outs_array[count($outs_array)-1]!="0"){
								// 			$day_out = $outs_array[$o];
								// 			break;
								// 		}
								// 	}
								// 	if($day_out=="0"){
								// 		$day_seconds = 0;
								// 	}else{
								// 		if(count($ins_array)>0){
								// 			$day_seconds = $day_out-$ins_array[0];
								// 		}else{
								// 			$day_seconds = 0;
								// 		}
								// 	}
								// }
								
								if($ca_wo_lofi=="1"){
									$day_seconds = $data[count($data)-1]['time']-$data[0]['time'];
								}

								$hours = floor($day_seconds / 3600);
								$minutes = floor($day_seconds / 60%60);
								$day_hrs = "W.H $hours:$minutes Hr";

								if($day_seconds>0 && $absent_on=="1" &&($day_seconds<$ab_wo_time)){
									$absentWo="1";
								}

								if($day_seconds>0 && $absentWo=="0" && $halfday_on=="1" &&($day_seconds<$half_wo_time)){
									$halfday="1";
									$totalP2++;
								}

								if($shift_start!="" && !empty($ins_array)){
									$in_start = strtotime(date("d-m-Y h:i A",strtotime(date("h:i A",$ins_array[0]))));
									$sh_start = strtotime(date("d-m-Y h:i A",strtotime($shift_start)));
									$sh_end = strtotime(date("d-m-Y h:i A",strtotime($shift_end)));
									if($in_start>$sh_start){
										$late_seconds = $in_start-$sh_start;
										$hours = floor($late_seconds / 3600);
										$minutes = floor($late_seconds / 60%60);
										$late_hrs = "$hours:$minutes";
										if($sl_late_on=="1" && ($late_seconds > $sl_late_time) && $halfday=="0"){
											$sl ="SL";
										}
									}
									if($outs_array[count($outs_array)-1]!="0"){
										$out_end = strtotime(date("d-m-Y h:i A",strtotime(date("h:i A",$outs_array[count($outs_array)-1]))));
										if($sh_end>$out_end && $out_end!=0){
											$early_seconds = $sh_end-$out_end;
											$hours = floor($early_seconds / 3600);
											$minutes = floor($early_seconds / 60%60);
											$early_hrs = "$hours:$minutes";
											if($sl_early_on=="1" && ($early_seconds > $sl_early_time) && $halfday=="0"){
												$sl = "SL";
											}
										}
									}
									// if($day_seconds!=0 && $day_seconds<($sh_end-$sh_start)){
									// 	$early_seconds = ($sh_end-$sh_start)-$day_seconds;
									// 	$hours = floor($early_seconds / 3600);
									// 	$minutes = floor($early_seconds / 60%60);
									// 	$early_hrs = "EL $hours:$minutes Hr";
									// 	if($sl_early_on=="1" && ($early_seconds > $sl_early_time) && $halfday=="0"){
									// 		$sl = "SL";
									//
									// 	}
									// }

									if($outs_array[count($outs_array)-1]!="0"){
										if($ot_seconds>0 && $ov_shift=="1" && ($ot_seconds > $ov_out_time)){
											$out_end = strtotime(date("d-m-Y h:i A",strtotime(date("h:i A",$outs_array[count($outs_array)-1]))));
											$ot_seconds = $out_end-$sh_end;
											$hours = floor($ot_seconds / 3600);
											$minutes = floor($ot_seconds / 60%60);
											$ot_hrs = "$hours:$minutes";
										}
									}
								}

								if($overtime_wh_on=="1" &&($day_seconds>$ov_wo_time)){
									$ot_seconds = $day_seconds-$ov_wo_time;
									if($ot_seconds>0){
										$hours = floor($ot_seconds / 3600);
										$minutes = floor($ot_seconds / 60%60);
										$ot_hrs = "$hours:$minutes";
									}
								}
								if($absentWo=="1"){
									$totalAbsent++;
								}else{
									if($sl!="SL"){
    								// 	if($weekOff=="1" || $holiday=="1"){
    								// 		$totalOT++;
    								// 	}else{
    										
    								// 	}
										if($halfday=="0"){
											$totalPresent++;																	
										}
									}else{
										$totalShortLeave++;
									}
								}
								
							}else{
						// 		if($weekOff=="1"){
						// 			$totalWeekOff++;
						// 		}
						// 		if($holiday=="1"){
						// 			$totalHoliday++;
						// 		}
								if($weekOff=="0" && $holiday=="0" && $day_leave=="0"){
									$totalAbsent++;
								}
								$data = array();
							}

							$day_status = "A";

							if($day_leave=="1"){
								$day_status = "L";
							}

							if($holiday=="1"){
								$day_status = "H";
							}

							if($weekOff=="1"){
								$day_status = "W";
							}

							if(!empty($data)){
								if($absentWo=="1"){
									$day_status="A";
								}else{
									$day_status = "P";
								if($halfday=="1"){
									$day_status="P/2";
								}
								$msOut = true;
								foreach($data as $day_data){
									if($day_data['mode']=="out"){
									$msOut = false;
									}
								}
								if($mispunch=="1" && $msOut){
									$day_status="MS";
								}
								if($weekOff=="1"){
									$day_status="WP";
									if($mispunch=="1" && $msOut){
										$day_status="W MS";
									}
									if($halfday=="1"){
										$day_status="WP/2";
									}
								}
								if($holiday=="1"){
									$day_status="HP";
									if($mispunch=="1" && $msOut){
										$day_status="H MS";
									}
									if($halfday=="1"){
										$day_status="HP/2";
									}
								}

								if($sl=="SL"){
									if(strlen($day_sub_status)==0){
										$day_sub_status.="SL";
									}else{
										$day_sub_status.=",SL";
									}
								}
								if($ot_seconds>0){
									if(strlen($day_sub_status)==0){
										$day_sub_status.="OT";
									}else{
										$day_sub_status.=",OT";
									}
								}
								}
							}
							// if($late_seconds>0){
							// 	if(strlen($day_sub_status)==0){
							// 		$day_sub_status.="L";
							// 	}else{
							// 		$day_sub_status.=",L";
							// 	}
							// }

							// if($early_seconds>0){
							// 	if(strlen($day_sub_status)==0){
							// 		$day_sub_status.="E";
							// 	}else{
							// 		$day_sub_status.=",E";
							// 	}
							// }
							
							$months_array[] = array(
								'date'=>date("j",$new_start_time),
								'day'=>date("l",$new_start_time),
								'weekly_off'=>$weekOff,
								'holiday'=>$holiday,
								'leave'=>$day_leave,
								'data'=>$data,
								'workingHrs'=>$day_hrs,
								'late_hrs'=>$late_hrs,
								'early_hrs'=>$early_hrs,
								'ot_hrs'=>$ot_hrs,
								'mispunch'=>$mispunch,
								'sl_late'=>$sl_late_time,
								'sl_early'=>$sl_early_time,
								'halfday'=>$halfday,
								'absent'=>$absentWo,
								'overtime_shiftout'=>$ov_out_time,
								'overtime_wh'=>$ov_wo_time,
								'wh_cal'=>$ca_wo_lofi,
								'wo_absent'=>$mark_ab_week,
								'overtime_shift'=>$ov_shift,
								'ot_seconds'=>$ot_seconds,
								'day_status'=>$day_status,
								'day_sub_status'=>$day_sub_status,
								'sl'=>$sl
							);
						}
					}
				}

				$perDay = $empList[$key]->totalSalary/$maxDays;
				$newPayable = $perDay*($totalPresent+$totalWeekOff+$totalHoliday+$totalLeaves);

				$salaryStartDate = strtotime(date("d-m-Y 00:00:00",strtotime($yearName."-".$monthName."-01"))." +".'0'." days");
				$salaryEndDate = strtotime(date("d-m-Y 23:59:59",strtotime($yearName."-".$monthName."-01"))." +".($maxDays-1)." days");
				if($salaryEndDate>time()){
					$salaryEndDate = time();
				}
				$salaryRes = $this->db->query("SELECT * FROM salary_report where uid='$dataVal->user_id' and bid='$loginID' AND  YEAR(date_time)='$yearName' AND MONTH(date_time)='$monthName'")->row_array();
				$salaryData = array(
					'bid'=>$loginID,
					'uid'=>$dataVal->user_id,
					'start_date'=>$salaryStartDate,
					'end_date'=>$salaryEndDate,
					'days'=>$maxDays,
					'present'=>$totalPresent,
					'absent'=>$totalAbsent,
					'half_day'=>$totalP2,
					'week_off'=>$totalWeekOff,
					'holiday'=>$totalHoliday,
					'leaves'=>$totalLeaves,
					'ed'=>$totalOT,
					'short_leave'=>$totalShortLeave,
					'net_payable'=>$newPayable,
					'date_time'=>date("Y-m-d H:i:s",$salaryStartDate)
				);
				if(!empty($salaryRes)){
					$this->db->where(array('id' => $salaryRes['id']))->update('salary_report',$salaryData);
				}else{
					$this->db->insert('salary_report',$salaryData);
				}
				
				$empList[$key]->netPayable = round(($newPayable-$getTotalDeduction['addAmount'])-$getTotalPaid['addAmount']);
			}
		}
		return $empList;
	}




	public function getTypeTotalAmountByUsers($yearName, $monthName, $payRolType)
{
    $loginID = $this->web->session->userdata('login_id');

    $this->db->select('
        tbl1.user_id,
        tbl2.name as payrollName,
        SUM(tbl1.amount) as addAmount
    ');
    $this->db->from('payroll_history as tbl1');
    $this->db->join('payroll_master as tbl2', 'tbl2.id = tbl1.payroll_master_id', 'INNER');

    $this->db->where([
        'tbl2.type'        => $payRolType,
        'tbl1.business_id' => $loginID,
        'tbl1.paid'        => 1,
        'tbl1.status'      => 1
    ]);

    $this->db->where('YEAR(tbl1.date)', $yearName);
    $this->db->where('MONTH(tbl1.date)', $monthName);

    $this->db->group_by('tbl1.user_id');

    $result = $this->db->get()->result_array();

    // ✅ Map by user_id
    $mapped = [];
    foreach ($result as $row) {
        $mapped[$row['user_id']] = $row;
    }

    return $mapped;
}
public function getEmployeeCTCMap($yearName, $monthName)
{
    $loginID = $this->web->session->userdata('login_id');

    $ctcMap = [];

    // 1️⃣ Selected Month CTC (High Priority)
    $monthCTC = $this->db->select('*')
        ->from('user_ctc')
        ->where('business_id', $loginID)
        ->where('YEAR(date)', $yearName)
        ->where('MONTH(date)', $monthName)
        ->get()
        ->result_array();

    foreach ($monthCTC as $row) {
        $ctcMap[$row['user_id']] = $row;
    }

    // 2️⃣ Latest CTC (Fallback)
    $latestCTC = $this->db->query("
        SELECT uc.*
        FROM user_ctc uc
        INNER JOIN (
            SELECT user_id, MAX(date) as max_date
            FROM user_ctc
            WHERE business_id = ?
            GROUP BY user_id
        ) latest
        ON latest.user_id = uc.user_id AND latest.max_date = uc.date
        WHERE uc.business_id = ?
    ", [$loginID, $loginID])->result_array();

    foreach ($latestCTC as $row) {
        // sirf wahi user jinke month ka CTC nahi mila
        if (!isset($ctcMap[$row['user_id']])) {
            $ctcMap[$row['user_id']] = $row;
        }
    }

    return $ctcMap;
}

public function getAdvanceList()
{
    $loginID = $this->web->session->userdata('login_id');

    return $this->db->select('*')
        ->from('payroll_history')
        ->where([
            'business_id'       => $loginID,
            'payroll_master_id' => 2,
            'paid'              => 0,
            'status'            => 1
        ])
        ->get()
        ->result();
}


public function getAdvanceSettlementMap()
{
    $loginID = $this->web->session->userdata('login_id');

    $result = $this->db->select('*')
        ->from('payroll_history')
        ->where([
            'business_id' => $loginID,
            'status'      => 1
        ])
        ->where('payroll_id IS NOT NULL', null, false)
        ->get()
        ->result();

    // map by payroll_id (advance id)
    $map = [];
    foreach ($result as $row) {
        $map[$row->payroll_id][] = $row;
    }

    return $map;
}


public function getSalaryReportMap($yearName, $monthName)
{
    $loginID = $this->web->session->userdata('login_id');

    $result = $this->db->select('*')
        ->from('salary_report')
        ->where('bid', $loginID)
        ->where('YEAR(date_time)', $yearName)
        ->where('MONTH(date_time)', $monthName)
        ->get()
        ->result_array();

    // ✅ Map by user_id (uid)
    $mapped = [];
    foreach ($result as $row) {
        $mapped[$row['uid']] = $row;
    }

    return $mapped;
}





    public function getSallaryReport($postData = '', $limit = null, $offset = null, $search = null){

		if($this->session->userdata()['type']=='P'){
      
      	$loginID = $this->session->userdata('empCompany');
      	$role=$this->web->getRollbyid($this->web->session->userdata('login_id'),$loginID);
  
    } else {
      	$loginID=$this->web->session->userdata('login_id');
    }
		$this->db->select('
		user_request.*,
		login.name as empName,
		login.mobile as empMobile,
		login.emp_code,
		login.designation as empDesignation,
		login.business_group,
		login.id as emp_id,
		login.department,
		login.section
	');
	$this->db->join('login', 'login.id = user_request.user_id', 'LEFT');
	$this->db->where('user_request.business_id', $loginID);
	$this->db->order_by('emp_code', 'ASC');
	$this->db->order_by('doj', 'ASC');
	$this->db->limit(10, 0);

	if ($limit !== null) {
		$this->db->limit($limit, $offset);
	}

	$empList = $this->db->get('user_request')->result();
	
		if(!empty($empList)){
			if(!empty($postData)){
				$yearName  = date('Y', strtotime($postData['date_from']));
				$monthName = date('m', strtotime($postData['date_from']));
				$selectedStartTime = strtotime(date("01-m-Y 00:00:00",strtotime($postData['date_from'])));
				$selectedEndTime = strtotime(date("30-m-Y 00:00:00",strtotime($postData['date_from'])));
				$d=30;
				$selectedEndTime=strtotime(date("01-m-Y 23:59:59",strtotime($postData['date_from']))." +".$d." days");
			}
			else
			{
				$yearName = date('Y');
				$monthName = date('m');
				$selectedStartTime = strtotime(date("01-m-Y 00:00:00"));
				$selectedEndTime = strtotime(date("30-m-Y 00:00:00"));
			}

			$additionMap   = $this->getTypeTotalAmountByUsers($yearName, $monthName, 1);
			$deductionMap  = $this->getTypeTotalAmountByUsers($yearName, $monthName, 2);
			$paidMap       = $this->getTypeTotalAmountByUsers($yearName, $monthName, 3);
			$ctcMap = $this->getEmployeeCTCMap($yearName, $monthName);
			$salaryMap = $this->getSalaryReportMap($yearName, $monthName);

			$advanceList     = $this->getAdvanceList();
			$settlementMap  = $this->getAdvanceSettlementMap();
						
			foreach ($empList as $key => $dataVal) {
			    
				if(($dataVal->doj=="" || $selectedEndTime >= $dataVal->doj) && ($dataVal->left_date=="" || $selectedStartTime<$dataVal->left_date)){
				}else{
					unset($empList[$key]);
				}
			}

		

			if($this->session->userdata()['type']=='P'){
						if($role[0]->type!=1){
							$departments = explode(",",$role[0]->department);
							$sections = explode(",",$role[0]->section);
							$team = explode(",",$role[0]->team);
							
							if(!empty($departments[0]) || !empty($sections[0]) || !empty($team[0])){
							foreach ($empList as $key => $dataVal) {
								$uname = $this->web->getNameByUserId($dataVal->user_id);
							$roleDp = array_search($uname[0]->department,$departments);
								$roleSection = array_search($uname[0]->section,$sections);
								$roleTeam = array_search($dataVal->user_id,$team);
								if(!is_bool($roleTeam) || !is_bool($roleSection) || !is_bool($roleDp)){
						
								}else{
								unset($empList[$key]);
								}
							}
							}
						}
						}

			foreach ($empList as $key => $dataVal) {


				
				$userId = $dataVal->user_id;

				$addition   = $additionMap[$userId]['addAmount'] ?? 0;
				$deduction  = $deductionMap[$userId]['addAmount'] ?? 0;
				$paid       = $paidMap[$userId]['addAmount'] ?? 0;

				
				$getCTC = $getCTCMap[$userId] ?? [];

				$totalCtc  = $getCTC['total_ctc_amount'] ?? 0;
				$basicCtc  = $getCTC['basic_value'] ?? 0;
				$pfAmount  = $getCTC['pf_amount'] ?? 0;
				$esiAmount = $getCTC['esi_amount'] ?? 0;
				$otherAmt  = $getCTC['other_amount'] ?? 0;
				
				$totalAdvance = 0;
				$netTotalAdvance = 0;
				
				$netSubAmount = 0;
			
				foreach ($advanceList as $advance) {

					if ($advance->user_id != $userId) continue;
			
					$advanceStartTime = strtotime(date("t-m-Y 00:00:00", strtotime($advance->date)));
			
					// 🔹 Current month advance
					if ($advanceStartTime == $selectedStartTime) {
						$netTotalAdvance += $advance->amount;
					}
			
					if ($advanceStartTime <= $selectedStartTime) {
			
						$subAmount = 0;
			
						$settlements = $settlementMap[$advance->id] ?? [];
			
						foreach ($settlements as $settle) {
			
							$monthStartTime = strtotime(date("t-m-Y 00:00:00", strtotime($settle->date)));
			
							if ($monthStartTime <= $selectedStartTime) {
								$subAmount += $settle->amount;
							}
			
							if ($monthStartTime == $selectedStartTime) {
								$netSubAmount += $settle->amount;
							}
						}
			
						$totalAdvance += ($advance->amount - $subAmount);
					}
				}
				$empList[$key]->getTotalPaid = $totalAdvance;
				//Calculate Add and Deduction
				$salaryAddition = 0;
				$salaryDeduction = 0;
				$empList[$key]->basicCtc = 0;
				if(!empty($getCTC)){
					$salaryDeduction = $pfAmount + $esiAmount+$otherAmt;
					$salaryAddition = ($totalCtc+$salaryDeduction)-$basicCtc;
					$empList[$key]->basicCtc = $basicCtc;
				}
				// HARE CALCULATE TOTAL SALLARY
				$empList[$key]->totalSalary = $addition +$totalCtc;
				$empList[$key]->ctc = $totalCtc + $salaryDeduction;

				//Calculate Add and Deduction
				$salaryAddition = 0;
				$salaryDeduction = 0;
				
				if(!empty($getCTC)){
					$salaryDeduction = $pfAmount+$esiAmount+$otherAmt;
					$salaryAddition = ($totalCtc+$salaryDeduction)-$basicCtc;
				}
				// HARE CALCULATE TOTAL SALLARY
				$empList[$key]->totalSalary = $addition+$totalCtc;
				$empList[$key]->ctc = $totalCtc +$salaryDeduction;

				if($deduction!=""){
					$empList[$key]->deductionAmount = $deduction;
				}else{
					$empList[$key]->deductionAmount =  "0";
				}
				if($paid!=""){
					$empList[$key]->deductionAmount +=  $paid;
				}
				// $empList[$key]->deductionAmount+= $netSubAmount;

				if($addition!=""){
					$empList[$key]->additionAmount = $addition;
				}else{
					$empList[$key]->additionAmount = "0";
				}
				
				$empList[$key]->total = $empList[$key]->totalSalary-$deduction-$paid;
				$maxDays = date("t",strtotime($yearName."-".$monthName."-01"));

				$salaryData = $salaryMap[$userId] ?? [];

				$perDay = $empList[$key]->ctc/$maxDays;
				$newPayable = 0;
				$salaryPf = 0;
				$salaryEsi = 0;
				$empList[$key]->nwd = 0;
				$empList[$key]->present = 0;
				$empList[$key]->half_day = 0;
				$empList[$key]->week_off = 0;
				$empList[$key]->holiday = 0;
				$empList[$key]->leaves = 0;
				$empList[$key]->short_leave = 0;
				$empList[$key]->ed = 0;
				$empList[$key]->pfValue = 0;
				$empList[$key]->esiValue = 0;
				$empList[$key]->id = 0;
				$empList[$key]->pay_mode = 0;
				if($salaryData){
					$newPayable = $perDay*($salaryData['present']+($salaryData['half_day']/2)+$salaryData['week_off']+$salaryData['holiday']+(!empty($salaryData['leaves'])?$salaryData['leaves']:0)+$salaryData['short_leave']+$salaryData['ed']);
					$empList[$key]->nwd = $salaryData['present']+($salaryData['half_day']/2)+$salaryData['week_off']+$salaryData['holiday']+(!empty($salaryData['leaves'])?$salaryData['leaves']:0)+$salaryData['short_leave']+$salaryData['ed'];

					$empList[$key]->present = $salaryData['present'];
					$empList[$key]->half_day = $salaryData['half_day'];
					$empList[$key]->week_off = $salaryData['week_off'];
					$empList[$key]->holiday = $salaryData['holiday'];
					$empList[$key]->leaves = $salaryData['leaves'];
					$empList[$key]->short_leave = $salaryData['short_leave'];
					$empList[$key]->ed = $salaryData['ed'];
					$empList[$key]->startDate = $salaryData['start_date'];
					$empList[$key]->endDate = $salaryData['end_date'];
					$empList[$key]->id = $salaryData['id'];
					$empList[$key]->pay_mode = $salaryData['pay_mode'];
					if (!empty($getCTC)) {

						if (($getCTC['pf_type'] ?? '') === "Manual") {
							$salaryPf = $getCTC['pf_value'] ?? 0;
						} else {
							$pfPercent = $getCTC['pf_value'] ?? 0;
							$salaryPf = round($newPayable * ($pfPercent / 100));
						}
					
						if (($getCTC['esi_type'] ?? '') === "Manual") {
							$salaryEsi = $getCTC['esi_value'] ?? 0;
						} else {
							$esiPercent = $getCTC['esi_value'] ?? 0;
							$salaryEsi = round($newPayable * ($esiPercent / 100));
						}
					}
				}
				$empList[$key]->pf = $salaryPf;
				$empList[$key]->esi = $salaryEsi;
				$empList[$key]->total = round($newPayable);
				$empList[$key]->netPayable = round((($newPayable+$addition)-$deduction)-$paid)-$salaryPf-$salaryEsi;
			}
		}

		return $empList;

	}




	public function getTypeTotalAmount($user_id, $yearName, $monthName, $payRolType){
		$loginID = $this->web->session->userdata('login_id'); // THIS IS BUSINESS ID
		//$getTotalPaidAmount = $this->getTypeTotalAmount($dataVal->user_id, $yearName, $monthName, '3' );
		$this->db->select('tbl1.*, tbl2.name as payrolName,SUM(tbl1.amount) as addAmount ');
		$this->db->join('payroll_master as tbl2','tbl2.id = tbl1.payroll_master_id', 'INNER');
		$this->db->where(array('tbl2.type' => $payRolType, 'YEAR(tbl1.date)' => $yearName, 'MONTH(date)' => $monthName,'paid'=>1,'tbl1.status'=>1));
		$this->db->where(array('tbl1.business_id' => $loginID, 'tbl1.user_id' => $user_id ));
		return  $this->db->get('payroll_history as tbl1')->row_array();
	}


	public function getData($table,$condition='',$id='',$asc=''){

		if (!empty($condition)) {
			$this->db->where($condition);
		}
		if (!empty($id))
		{
			$data = $this->db->get($table)->row_array();
		}
		else
		{
			if(!empty($asc)){
				$this->db->order_by('id','ASC');
			}else{
				$this->db->order_by('id','DESC');
			}

			$data = $this->db->get($table)->result_array();
		}
		return $data;

	}

	public function saveData($table,$data){
		$this->db->insert($table,$data);
		return $this->db->insert_id();
	}

	public function UpdateData($table,$data,$condition){
		return $this->db->where($condition)->update($table,$data);
		// return $this->db->affected_rows();
	}

	public function getAllLinked($mobile){
		$this->db->select('*');
		$this->db->where('linked',$mobile);
		$this->db->where('active',0);
		$this->db->where('deleted',0);
		$this->db->from('login');
		$get=$this->db->get();
		return $get->result();
	}

	public function getLinkedWeb($username){
		return $this->db->where('username',$username)->where('status',1)->get('web_login')->row_array();
	}

	public function getHoliday($bid){
		$sql = "SELECT * FROM holiday where business_id='$bid' AND status='1'";
		$res = $this->db->query($sql);
		return $res->result();
	}


	
	public function getBusinessDepByBusinessId($id){
		return $this->db->query("SELECT * FROM department_section WHERE bid = '$id' and status= 1 ")->result();
		// return $this->db->query("SELECT * FROM login WHERE id = '$id'")->row_array();
	}

	public function getUserCompany($id){
		$sql = "SELECT * from user_request where user_id='$id' order by id DESC LIMIT 1";
		$res = $this->db->query($sql);
		return $res->row_array();
	}
	
	///// new/////
 public function checkEmpRoll($id,$bid){
        return $this->db->query("SELECT * FROM emp_role WHERE uid = '$id' and bid = '$bid' ")->row_array();
    }
 public function getRollbyid($id,$bid){
        
		return $this->db->query("SELECT * FROM emp_role WHERE uid ='$id' and bid ='$bid' and status=1 and deleted=0")->result();
    }
	public function getBusinessbyUser($id){
        return $this->db->query("SELECT * FROM user_request WHERE user_id = '$id' and left_date ='' ")->result();
    }
public function getBusinessSecByUserId($bid,$id){
        return $this->db->query("SELECT * FROM sections WHERE bid = '$bid' and type = '$id' ")->result();
		// return $this->db->query("SELECT * FROM login WHERE id = '$id'")->row_array();
    }
	public function getBusinessSecByBId($bid){
        return $this->db->query("SELECT * FROM sections WHERE bid = '$bid' ")->result();
		// return $this->db->query("SELECT * FROM login WHERE id = '$id'")->row_array();
    }
public function verifypending($id){
        $val = $this->db->query("UPDATE attendance SET verified = 1 WHERE id = '$id'");
        return $val;
    }
	public function cancelpending($id){
        $val = $this->db->query("UPDATE attendance SET verified = 2 WHERE id = '$id'");
        return $val;
    }

public function delete_department($id){
        $val = $this->db->query("UPDATE department_section SET status = 0 WHERE id = '$id'");
        return $val;
    }
	
	public function delete_holiday($id){
        $val = $this->db->query("UPDATE holiday SET status = 0 WHERE id = '$id'");
        return $val;
    }
	
	public function getBusinessSectionById($id){
        return $this->db->query("SELECT * FROM sections WHERE id = '$id' ")->result();
		// return $this->db->query("SELECT * FROM login WHERE id = '$id'")->row_array();
    }
	
	public function getHolidays($bid){
        return $this->db->query("SELECT * FROM holiday WHERE business_id = '$bid' and status= 1  ")->result();
	
    }
    public function getBusinessDepByUserId($id){
        return $this->db->query("SELECT * FROM department_section WHERE id = '$id' ")->result();
		// return $this->db->query("SELECT * FROM login WHERE id = '$id'")->row_array();
    }
    public function getGpsByDate($id){
		$sql = "SELECT * FROM `attendance` WHERE status=1 and verified=0 and bussiness_id='$id' order by io_time DESC";
		$res = $this->db->query($sql);
		return $res->result();
		// return $this->db->query("SELECT * FROM login WHERE id = '$id'")->row_array();
	}
    public function getUserFdByDate($start_time,$end_time,$id){
		$sql = "SELECT * FROM `attendance` WHERE status=1 and io_time BETWEEN $start_time and $end_time and bussiness_id='$id' and manual=2 order by user_id ASC";
		$res = $this->db->query($sql);
		return $res->result();
		// return $this->db->query("SELECT * FROM login WHERE id = '$id'")->row_array();
	}
	public function delete_att($id){
        $val = $this->db->query("UPDATE attendance SET status = 0 WHERE id = '$id'");
        return $val;
    }
    public function delete_user($id){
        $val = $this->db->query("UPDATE login SET deleted = 1 WHERE id = '$id'");
        return $val;
    }
	
	
	public function getnameBymobile($mb){
		return $this->db->query("SELECT * FROM login WHERE mobile = '$mb' and deleted=0 ")->result();
	}
	public function getwifiByid($busid){
		return $this->db->query("SELECT * FROM sections WHERE bid = '$busid' and status=1 ")->result();
	}
public function getUsergps($start_time,$end_time,$loginid,$id){
		$sql = "SELECT * FROM `attendance` WHERE status=1 and io_time BETWEEN $start_time and $end_time and bussiness_id='$loginid' and user_id=$id and manual=0 and location!=0 order by io_time ASC ";
		$res = $this->db->query($sql);
		return $res->result();
		// return $this->db->query("SELECT * FROM login WHERE id = '$id'")->row_array();
	}
	
	public function updateWorkingDays($bid,$uid,$yearName,$monthName,$present,$half_day,$week_off,$holiday,$leaves,$ed,$short_leave){
		$sql = "UPDATE salary_report SET present='$present',half_day='$half_day',week_off='$week_off',holiday='$holiday',leaves='$leaves',ed='$ed',short_leave='$short_leave' WHERE bid='$bid' and uid='$uid' AND  YEAR(date_time)='$yearName' AND MONTH(date_time)='$monthName'";
		$res = $this->db->query($sql);
		return $res;
	}

	
	public function removeManualAtt($id){
        $val = $this->db->query("UPDATE attendance SET status =0 WHERE id = '$id'");
        return $val;
    }
    
    public function getUserIdFdByDate($start_time,$end_time,$id,$uid){
		$sql = "SELECT * FROM `attendance` WHERE status=1 and io_time BETWEEN $start_time and $end_time and bussiness_id='$id' and manual=2 and user_id='$uid' order by user_id ASC";
		$res = $this->db->query($sql);
		return $res->result();
	}
	
	public function getCmpGps($start_time,$end_time,$loginid){
		$sql = "SELECT * FROM `attendance` WHERE status=1 and io_time BETWEEN $start_time and $end_time and bussiness_id='$loginid' and manual=0 and location!=0 order by io_time ASC ";
		$res = $this->db->query($sql);
		return $res->result();
	}
	
		public function addShift($data){
		return $this->db->insert('business_groups',$data);
	}
	
	public function updateShift($id,$bid,$name,$start,$end,$weeklyOff,$WeekOff,$dayStart,$dayEnd){
		$sql = "UPDATE business_groups SET name='$name', shift_start='$start', shift_end='$end', weekly_off='$weeklyOff', month_weekly_off='$WeekOff', day_start_time='$dayStart', day_end_time='$dayEnd' WHERE id='$id' and business_id='$bid'";
		$res = $this->db->query($sql);
		return $res;
	}
	
	public function deleteShift($id,$bid){
		$sql = "UPDATE `business_groups` SET `status`='0' WHERE id='$id' and business_id='$bid'";
		$res = $this->db->query($sql);
		return $res;
	}
	
	
	public function getCmpOptions($bid){
		$sql = "SELECT * from business_at_option where bid='$bid'";
		$res = $this->db->query($sql);
		return $res->row_array();
	}

	public function addAttendanceOption($data){
		return $this->db->insert('business_at_option',$data);
	}

	public function updateAttendanceOption($id,$col,$val){
		$sql = "UPDATE business_at_option SET $col='$val' WHERE bid='$id'";
		$res = $this->db->query($sql);
		return $res;
	}

	public function updateEmpOptions($bid,$auto_gps,$qr,$gps,$field,$security,$face,$gpsSelfie,$fieldSelfie){
		$sql = "UPDATE user_request SET auto_gps='$auto_gps',qr='$qr',gps='$gps',field_duty='$field',four_layer_security='$security',face='$face',selfie_with_gps='$gpsSelfie',selfie_with_field_duty='$fieldSelfie' WHERE business_id='$bid'";
		$res = $this->db->query($sql);
		return $res;
	}
	
	public function manualAttendance($bid,$uid,$startTime,$endTime){
		$sql = "SELECT * FROM `attendance` WHERE status=1 and io_time BETWEEN $startTime and $endTime and bussiness_id='$bid' and manual=1 and user_id='$uid' and verified=1";
		$res = $this->db->query($sql);
		return $res->result();
	}
	
	public function getUserRequest($bid,$uid){
		$sql = "SELECT * from user_request where business_id='$bid' and user_id='$uid' order by id desc";
		$res = $this->db->query($sql);
		return $res->row_array();
	}

	public function getBusinessRules($bid){
		return $this->db->query("SELECT * from attendance_rule WHERE bid='$bid'")->result();
	}
	
	public function verifyAllPending($id){
		$sql = "UPDATE `attendance` SET verified=1 WHERE status=1 and verified=0 and location!='' and bussiness_id='$id'";
		$res = $this->db->query($sql);
		return $res;
	}

	public function cancelAllPending($id){
		$sql = "UPDATE `attendance` SET verified=2 WHERE status=1 and verified=0 and location!='' and bussiness_id='$id'";
		$res = $this->db->query($sql);
		return $res;
	}
	
	public function getOpenLeaveByDate($bid,$uid){
		return $this->db->query("SELECT * from open_leave WHERE bid='$bid' and uid='$uid' and deleted=0")->row_array();
	}

	public function getUserRolls($bid){
		$sql = "SELECT *,(select name from login WHERE login.id = emp_role.uid) as name,(select mobile from login WHERE login.id = emp_role.uid) as mobile,(select business_group from login WHERE login.id = emp_role.uid) as business_group,(select login.designation from login WHERE login.id = emp_role.uid) as designation,(select login.m_id from login WHERE login.id = emp_role.uid) as mid,(select login.emp_code from login WHERE login.id = emp_role.uid) as emp_code,(select login.section from login WHERE login.id = emp_role.uid) as section FROM `emp_role` WHERE bid='$bid' and type!=0 and deleted=0";
		$res = $this->db->query($sql);
		return $res->result();
	}

	public function checkEmpCompany($mobile){
		return $this->db->query("SELECT login.id,(SELECT user_request.business_id FROM user_request WHERE user_request.user_id=login.id ORDER BY id desc LIMIT 1) as bid,(SELECT user_request.left_date FROM user_request WHERE user_request.user_id=login.id ORDER BY id desc LIMIT 1) as left_date from login WHERE mobile='$mobile'")->row_array();
	}

	public function checkEmpRole($uid,$bid){
		return $this->db->query("SELECT * from emp_role where bid='$bid' and uid='$uid' and status=1 and deleted=0")->row_array();
	}

	public function checkEmpRoleCmp($uid,$bid){
		return $this->db->query("SELECT * from emp_role where bid='$bid' and uid='$uid' and deleted=0")->result();
	}

	public function addUserRole($data){
		return $this->db->insert('emp_role',$data);
	}

	public function getUserCompanies($id){
		$sql = "SELECT *,(select name from login WHERE login.id = emp_role.bid) as name,(select mobile from login WHERE login.id = emp_role.bid) as mobile FROM `emp_role` WHERE uid='$id' and status=1 and deleted=0 and type!=0";
		$res = $this->db->query($sql);
		return $res->result();
	}
	
	/// new after 21 june-2023 //
	
	public function getpremiumonly(){
		return $this->db->query("SELECT * FROM login WHERE user_group='1' and validity!='' and validity > UNIX_TIMESTAMP() order by id DESC")->result();
	}
	public function activebusiness($start_time,$end_time){
		return $this->db->query("SELECT DISTINCT bussiness_id as actbuis FROM attendance WHERE io_time BETWEEN $start_time and $end_time order by id DESC")->result();
	}
	
	public function inactivebusiness($start_time,$end_time){
		return $this->db->query("SELECT DISTINCT bussiness_id as actbuis FROM attendance WHERE io_time BETWEEN $start_time and $end_time='' order by id DESC")->result();
	}
	public function activeusers($start_time,$end_time){
		return $this->db->query("SELECT DISTINCT user_id as actuse FROM attendance WHERE io_time BETWEEN $start_time and $end_time order by id DESC")->result();
	}
	public function inactiveusers($start_time,$end_time){
		return $this->db->query("SELECT DISTINCT user_id as actuse FROM attendance WHERE io_time BETWEEN $start_time and $end_time=''  order by id DESC")->result();
	}
	public function getactivelicence($buid){
		return $this->db->query("SELECT * FROM new_qr WHERE login_id='$buid' and licence='1'")->result();
	}
	public function getNameByAssignId($assign_id){
		return $this->db->query("SELECT * FROM login WHERE id = '$assign_id'")->result();
	}
	public function getuserByidStatus($id){
		return $this->db->query("SELECT * FROM login WHERE id = '$id' and deleted='0' ")->result();
	}
	public function getuserByidActive($id){
		return $this->db->query("SELECT * FROM login WHERE id = '$id' ")->result();
	}
	
	public function checkActiveUser($id){
		return $this->db->query("SELECT * FROM user_request WHERE user_id = '$id'")->row_array();
	}
	public function getlicence(){
		return $this->db->query("SELECT * FROM licence_history WHERE transfer='1' order by id DESC")->result();
	}
	public function getlicencelogin(){
		return $this->db->query("SELECT DISTINCT assign_id FROM new_qr WHERE licence='1' order by id DESC")->result();
	}
	/// hostel//
public function getHostelStudentList($id){
		return $this->db->query("SELECT * FROM user_request WHERE business_id = '$id' and left_date ='' and hostel='1' order by doj")->result();
	}	
	public function getHostelByUserId($userid,$id){
		return $this->db->query("SELECT * FROM hostel_detail WHERE uid='$userid' and  bid='$id'")->result();
	}
	public function getBlock($blid,$id){
		return $this->db->query("SELECT * FROM blocks WHERE id='$blid' and bid='$id'")->result();
	}
	public function getallBlock($bid){
		return $this->db->query("SELECT * FROM blocks WHERE bid='$bid'")->result();
	}
	public function getallrooms($bid){
		return $this->db->query("SELECT * FROM room_types WHERE bid='$bid'")->result();
	}
	public function getRoomtype($rmid,$id){
		return $this->db->query("SELECT * FROM room_types WHERE id='$rmid' and bid='$id'")->result();
	}
	public function getRoomtypebyid($id){
		return $this->db->query("SELECT * FROM room_types WHERE id='$id'")->result();
	}
	public function getblockbyid($id){
		return $this->db->query("SELECT * FROM blocks WHERE id='$id'")->result();
	}	
	
	
	/// student
	
	public function getStudentCheckInReport($id){
		return $this->db->query("SELECT *,(Select Business_bioid.Assin_to FROM Business_bioid WHERE Business_bioid.id=student_attendance.added_by)as assign_to,(Select Business_bioid.Route FROM Business_bioid WHERE Business_bioid.id=student_attendance.added_by)as route,(Select student.name FROM student WHERE student.id=student_attendance.student_id)as name,(Select class.name from class where class.id=(Select student.class_id FROM student WHERE student.id=student_attendance.student_id)) as class_name FROM student_attendance WHERE bid='$id' and student_status=2 or student_status=3 order by id desc")->result();
	}

	
	public function getUserByBioId($uid,$bid){
	    $sql = "SELECT * FROM `login` WHERE bio_id='$uid' and company='$bid'";
		$res = $this->db->query($sql);
		return $res->row_array();
	}
	
	public function getBioDevice($bid){
		$sql = "SELECT * FROM `Business_bioid` WHERE bid='$bid' and active=1";
		$res = $this->db->query($sql);
		return $res->result();
	}
	
	public function checkUserAlready($bid,$uid){
		$sql = "SELECT * FROM `user_request` WHERE business_id=$bid and user_id=$uid";
		$res = $this->db->query($sql);
		return $res->result();
	}
	
	public function addUserCmpStatus($data){
		$res = $this->db->insert("user_request",$data);
		return $res;
	}
	
	public function updateUserCompany($id,$bussinessid,$doj){
		$res = $this->db->query("UPDATE login SET company = '$bussinessid',doj='$doj' WHERE  id = '$id'");
		return $res;
	}
	
	public function updateUserDojHostel($id,$bid,$left,$doj,$hostel){
		$sql = "UPDATE user_request SET left_date='$left',doj='$doj',hostel='$hostel' WHERE user_id='$id' and business_id='$bid'";
		$res = $this->db->query($sql);
		return $res;
	}
	
   ////////new


public function getdevice($bid){
		$sql = "SELECT * FROM Business_bioid where bid='$bid' AND active='1'";
		$res = $this->db->query($sql);
		return $res->result();
	}	
	public function getdevicebyid($bio){
		$sql = "SELECT * FROM Business_bioid where id='$bio' ";
		$res = $this->db->query($sql);
		return $res->result();
	}	
	
public function delete_device($id){
        $val = $this->db->query("UPDATE Business_bioid SET active = 0 WHERE id = '$id'");
        return $val;
    }
    public function getDeviceAccess($start_time,$end_time,$loginid,$bio){
		$sql = "SELECT * FROM `attendance` WHERE status=1 and io_time BETWEEN $start_time and $end_time and bussiness_id='$loginid' and device=$bio and manual=4 order by io_time DESC ";
		$res = $this->db->query($sql);
		return $res->result();
		// return $this->db->query("SELECT * FROM login WHERE id = '$id'")->row_array();
	}
	public function getCmpAccess($start_time,$end_time,$loginid){
		$sql = "SELECT * FROM `attendance` WHERE status=1 and io_time BETWEEN $start_time and $end_time and bussiness_id='$loginid' and manual=4 order by io_time DESC ";
		$res = $this->db->query($sql);
		return $res->result();
	}
	
	
////canes	
	public function getUserbyBioid2($bio_id){
		$sql = "SELECT * FROM login where bio_id='$bio_id' ";
		$res = $this->db->query($sql);
		return $res->result();
	}
	
	
	//////
	public function getStaffbyBioid($staff_id,$loginId){
		$sql = "SELECT * FROM login where bio_id='$bio_id' ";
		$res = $this->db->query($sql);
		return $res->result();
	}	
	
	
	public function getActiveIdByMb($mob){
		return $this->db->query("SELECT * FROM login WHERE mobile = '$mob' and deleted =0")->result();

	}
	public function getStaffDetailid($staff_id,$loginID){
		return $this->db->query("SELECT * FROM staff_detail WHERE uid='$staff_id' and bid='$loginID'")->result();

	}
	public function getStafSalaryid($staff_id,$loginID){
		return $this->db->query("SELECT * FROM user_ctc WHERE user_id='$staff_id' and business_id='$loginID'")->result();

	}
	
	public function getActiveUserCompany($id,$loginID){
		$sql = "SELECT * from user_request where user_id='$id' and business_id='$loginID' order by id DESC LIMIT 1";
		$res = $this->db->query($sql);
		return $res->row_array();
	}
	
	
	
	function import_staff()
	{	
		include_once('excel_reader2.php');
		include_once('SpreadsheetReader.php');
		//$mimes = ['application/vnd.ms-excel','text/xls','text/xlsx','application/vnd.oasis.opendocument.spreadsheet','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
		
		//echo $_FILES["excel_file"]["type"];
		//exit;
		 if($this->session->userdata()['type']=='P'){
      
     	$loginID = $this->session->userdata('empCompany');
      
  
    } else {
      	$loginID=$this->web->session->userdata('login_id');
    }
		
	
		if($_FILES["excel_file"]["type"]){
			$uploadFilePath = 'upload/'.basename($_FILES['excel_file']['name']);
			move_uploaded_file($_FILES['excel_file']['tmp_name'], $uploadFilePath);
			$Reader = new SpreadsheetReader($uploadFilePath);
			$totalSheet = count($Reader->sheets());

			/* For Loop for all sheets */
			for($i=0;$i<$totalSheet;$i++){
			  $Reader->ChangeSheet($i);
			  $row_count=0;
			  foreach ($Reader as $Row)
			  {
				if($row_count!=0)
				{
					$Emp_code = isset($Row[0]) ? $Row[0] : '';
					$Device_id = isset($Row[1]) ? $Row[1] : '';
					$Name = isset($Row[2]) ? $Row[2] : '';
					$Mobile = isset($Row[3]) ? $Row[3] : '';
					$Phone = isset($Row[4]) ? $Row[4] : '';
					$Address = isset($Row[5]) ? $Row[5] : '';
					$email = isset($Row[6]) ? $Row[6] : '';
					$Gender   = isset($Row[7]) ? $Row[7] : '';
					$Blood_group = isset($Row[8]) ? $Row[8] : '';
					$Qualification= isset($Row[9]) ? $Row[9] : '';
					$dob = isset($Row[10]) ? $Row[10] : '';
					$Father_Name = isset($Row[11]) ? $Row[11] : '';
					$Designation = isset($Row[12]) ? $Row[12] : '';
					$Experience = isset($Row[13]) ? $Row[13] : '';
					$dor = isset($Row[14]) ? $Row[14] : '';
					$doj = isset($Row[15]) ? $Row[15] : '';
					$Employement = isset($Row[16]) ? $Row[16] : '';
					
					$omid = $this->web->getMaxMid()['m_id'];
					$temp_ = "MID";
					if($omid == ''){
						$nmid = $temp_.'0000';
					}else{
						$str1 = substr($omid,3);
						$str1 = $str1 + 1;
						$str2 = str_pad($str1 , 4 , 0 , STR_PAD_LEFT);
						$nmid = $temp_.$str2;
					}

					$im='upload/nextpng.png';

				
					
					$umobile=$this->web->getActiveIdByMb($Mobile);
					if (!empty($umobile)){
						$staff_id=$umobile[0]->id;
                    $userCmp = $this->web->getActiveUserCompany($umobile[0]->id,$loginID);
					
                    if(isset($userCmp) && ($userCmp['left_date']=="" || $userCmp['left_date']>time())){
						$datau = array(
							'emp_code' => $Emp_code,
							'bio_id'  => $Device_id,
							'name'  => $Name,
							'mobile'  => $Mobile,
							'phone'  => $Phone,
							'address'  => $Address,
							'email'  => $email,
							'gender'  => $Gender,
							'company'=>$loginID,
							'blood_group' => $Blood_group,
							'education'  => $Qualification,
							'dob' => $dob,
							'father_name'  => $Father_Name,
							'designation'  => $Designation,
							'experience'  => $Experience
							);
							 $this->db->where('id',$staff_id);
			        $update= $this->db->update('login',$datau);
					
						$data2u = array(
					         //'business_id'=> $loginID,
							// 'user_id'=>$id,
							'doreg'  => strtotime($dor),
							'doj'  =>strtotime($doj),
							'employement' =>$Employement,
							//'user_status'=>"1",
							//'date' =>time()
							);
							$this->db->where('user_id',$staff_id);
			            $updateuser= $this->db->update('user_request',$data2u);
                     
					}
				
					
					} else {
					
					$data = array(
							'emp_code' => $Emp_code,
							'bio_id'  =>$Device_id,
							'name'  => $Name,
							'mobile'  => $Mobile,
							'phone'  => $Phone,
							'address'  => $Address,
							'email'  => $email,
							'gender'  => $Gender,
							'blood_group' => $Blood_group,
							'education'  => $Qualification,
							'dob' => $dob,
						    'father_name'  => $Father_Name,
							'designation'  => $Designation,
							'experience'  => $Experience,
							'doj'=> strtotime($dor),
						      'active'=>0,
						    'date'=>time(),
						     'baseurl'=>base_url().'User/profile/'.$nmid,
					    	'login'=>md5($Mobile),
						     'image'=>$im,
					     	'company'=>$loginID,
					     	'user_group'  =>"2",
						  'm_id'=>$nmid,
							'start_date' =>time()
							);
							
					$this->db->insert('login', $data);
					$id = $this->db->insert_id();
					$data2 = array(
					         'business_id'=> $loginID,
							 'user_id'=>$id,
							'doreg'  => strtotime($dor),
							'doj'  =>strtotime($doj),
							'employement' =>$Employement,
							'user_status'=>"1",
							'date' =>time()
							);
					$this->db->insert('user_request', $data2);
				}}
				
				$row_count++;
				
			  }
			}
			$row_counts=$row_count+1;
			
			/* echo "<script>alert('".$row_counts."".$row_count."".$i." Record(s) has been inserted! Thank you.') </script>";  	
		  echo "<script language=\"javascript\">window.open('https://app.shinerweb.com/index.php/import_excel/', '_self');  </script>";*/
			//$this->load->view('attendance/employees');
			$this->session->set_flashdata('msg',' New Data Added!');
				//redirect('device_list');
			redirect('employees');
		}
		else
		{
			echo "<script>alert('Please select valid excel file!.') </script>";  	
		 /*  echo "<script language=\"javascript\">window.open('https://app.shinerweb.com/index.php/import_excel/', '_self');  </script>";*/
		}
		
	}
	




function import_staff_detail()
	{	
		include_once('excel_reader2.php');
		include_once('SpreadsheetReader.php');
		 if($this->session->userdata()['type']=='P'){
      
     	$loginID = $this->session->userdata('empCompany');
      
  
    } else {
      	$loginID=$this->web->session->userdata('login_id');
    }
		if($_FILES["excel_file"]["type"]){
			$uploadFilePath = 'upload/'.basename($_FILES['excel_file']['name']);
			move_uploaded_file($_FILES['excel_file']['tmp_name'], $uploadFilePath);
			$Reader = new SpreadsheetReader($uploadFilePath);
			$totalSheet = count($Reader->sheets());

			/* For Loop for all sheets */
			for($i=0;$i<$totalSheet;$i++){
			  $Reader->ChangeSheet($i);
			  $row_count=0;
			  foreach ($Reader as $Row)
			  {
				if($row_count!=0)
				{
					$Emp_code = isset($Row[0]) ? $Row[0] : '';
					$mobile = isset($Row[1]) ? $Row[1] : '';
					$Name = isset($Row[2]) ? $Row[2] : '';
					$paymode = isset($Row[3]) ? $Row[3] : '';
					$bankname = isset($Row[4]) ? $Row[4] : '';
					$Account = isset($Row[5]) ? $Row[5] : '';
					$ifsc = isset($Row[6]) ? $Row[6] : '';
					$upi   = isset($Row[7]) ? $Row[7] : '';
					$pan = isset($Row[8]) ? $Row[8] : '';
					$epf= isset($Row[9]) ? $Row[9] : '';
					$uan = isset($Row[10]) ? $Row[10] : '';
					$esic = isset($Row[11]) ? $Row[11] : '';
					$adhar = isset($Row[12]) ? $Row[12] : '';
					
					
					$umobile=$this->web->getActiveIdByMb($mobile);
					if (!empty($umobile)){
						$staff_id=$umobile[0]->id;
                    $userCmp = $this->web->getActiveUserCompany($umobile[0]->id,$loginID);
					$detailid =$this->web->getStaffDetailid($staff_id,$loginID);
						$detail_staff_id= $detailid[0]->id;
                  if(isset($userCmp) && ($userCmp['left_date']=="" || $userCmp['left_date']>time())){
						
						 
						 if (!empty($detailid)){
						$datau = array(
							
							'pay_mode'  => $paymode,
							'bank_name'  => $bankname,
							'account_no'=>$Account,
							'ifsc_code'  => $ifsc,
							'upi'  => $upi,
							'pan'  => $pan,
							'epf' => $epf,
							'uan'  => $uan,
							'esic' => $esic,
							'adhar' => $adhar,
							//'status' => "1"
							
							);
							 $this->db->where('id',$detail_staff_id);
			        $update= $this->db->update('staff_detail',$datau);
					
					} else {
					
					$data = array(
							'uid' => $staff_id,
							'bid'  => $loginID,
							'pay_mode'  => $paymode,
							'bank_name'  => $bankname,
							'account_no'  => $Account,
							'ifsc_code'  => $ifsc,
							'upi'  => $upi,
							'pan'  => $pan,
							'epf' => $epf,
							'uan'  => $uan,
							'esic' => $esic,
							'adhar' => $adhar,
							'status' => "1"
							
							);
							
					$this->db->insert('staff_detail', $data);
					
				}
				}}
					
				
				}
				
				$row_count++;
				
			  }
			}
			$row_counts=$row_count+1;
			
			/* echo "<script>alert('".$row_counts."".$row_count."".$i." Record(s) has been inserted! Thank you.') </script>";  	
		  echo "<script language=\"javascript\">window.open('https://app.shinerweb.com/index.php/import_excel/', '_self');  </script>";*/
			//$this->load->view('attendance/employees');
			$this->session->set_flashdata('msg',' New Data Added!');
				//redirect('device_list');
			redirect('employees');
		}
		else
		{
			echo "<script>alert('Please select valid excel file!.') </script>";  	
		 /*  echo "<script language=\"javascript\">window.open('https://app.shinerweb.com/index.php/import_excel/', '_self');  </script>";*/
		}
		
	}

function import_salary()
	{	
		include_once('excel_reader2.php');
		include_once('SpreadsheetReader.php');
		$loginID = $this->web->session->userdata('login_id');
		if($_FILES["excel_file"]["type"]){
			$uploadFilePath = 'upload/'.basename($_FILES['excel_file']['name']);
			move_uploaded_file($_FILES['excel_file']['tmp_name'], $uploadFilePath);
			$Reader = new SpreadsheetReader($uploadFilePath);
			$totalSheet = count($Reader->sheets());

			/* For Loop for all sheets */
			for($i=0;$i<$totalSheet;$i++){
			  $Reader->ChangeSheet($i);
			  $row_count=0;
			  foreach ($Reader as $Row)
			  {
				if($row_count!=0)
				{
					$Emp_code = isset($Row[0]) ? $Row[0] : '';
					$mobile = isset($Row[1]) ? $Row[1] : '';
					$Name = isset($Row[2]) ? $Row[2] : '';
					$monthly = isset($Row[3]) ? $Row[3] : '';
					$cycle = isset($Row[4]) ? $Row[4] : '';
					$basic = isset($Row[5]) ? $Row[5] : '';
					$ta = isset($Row[6]) ? $Row[6] : '';
					$da   = isset($Row[7]) ? $Row[7] : '';
					$hra = isset($Row[8]) ? $Row[8] : '';
					$meal= isset($Row[9]) ? $Row[9] : '';
					$CONVEYANCE = isset($Row[10]) ? $Row[10] : '';
					$medical = isset($Row[11]) ? $Row[11] : '';
					$special = isset($Row[12]) ? $Row[12] : '';
					$pfstaff = isset($Row[13]) ? $Row[13] : '';
					$pfbus  = isset($Row[14]) ? $Row[14] : '';
					$esicstaff = isset($Row[15]) ? $Row[15] : '';
					$esicbus= isset($Row[16]) ? $Row[16] : '';
					$tds = isset($Row[17]) ? $Row[17] : '';
					$total = isset($Row[18]) ? $Row[18] : '';
					
					
					
					
					$umobile=$this->web->getActiveIdByMb($mobile);
					if (!empty($umobile)){
						$staff_id=$umobile[0]->id;
                    $userCmp = $this->web->getActiveUserCompany($umobile[0]->id,$loginID);
					$salary =$this->web->getStafSalaryid($staff_id,$loginID);
						$salary_id= $salary[0]->id;
                  if(isset($userCmp) && ($userCmp['left_date']=="" || $userCmp['left_date']>time())){
						
						 
						if (!empty($salary)){
						$datau = array(
							
							'basic'  => $monthly ,
							'basic_value'  => $basic,
							//'cycle'=>$cycle,
							'ta_amount'  => $ta,
							'da_amount'  => $da,
							'hra_amount'  => $hra,
							'meal_amount' => $meal,
							'conveyance_amount'  => $CONVEYANCE,
							'medical_amount' => $medical,
							'special_amount' =>$special,
							'pf_amount'  => $pfstaff,
							//'esic' => $pfbus,
							'esi_amount' =>$esicstaff,
							//'adhar' =>$esicbus,
							'other_amount'  => $tds,
							'total_ctc_amount' => $total,
							//'date' =>Now()
							//'status' => "1"
							);
							 $this->db->where('id',$salary_id);
			        $update= $this->db->update('user_ctc',$datau);
					
					} else {
					
					$data = array(
							'user_id' => $staff_id,
							'business_id'  => $loginID,
							'basic'  => $monthly ,
							'basic_value'  => $basic,
							//'cycle'=>$cycle,
							'ta_amount'  => $ta,
							'da_amount'  => $da,
							'hra_amount'  => $hra,
							'meal_amount' => $meal,
							'conveyance_amount'  => $CONVEYANCE,
							'medical_amount' => $medical,
							'special_amount' =>$special,
							'pf_amount'  => $pfstaff,
							//'esic' => $pfbus,
							'esi_amount' =>$esicstaff,
							//'adhar' =>$esicbus,
							'other_amount'  => $tds,
							'total_ctc_amount' => $total,
							//'date' =>Now(),
							'status' => "1"
							
							);
							
					$this->db->insert('user_ctc', $data);
					
					}}
				}}
					
				
				
				
				$row_count++;
				
			  }
			}
			$row_counts=$row_count+1;
			
			/* echo "<script>alert('".$row_counts."".$row_count."".$i." Record(s) has been inserted! Thank you.') </script>";  	
		  echo "<script language=\"javascript\">window.open('https://app.shinerweb.com/index.php/import_excel/', '_self');  </script>";*/
			//$this->load->view('attendance/employees');
			$this->session->set_flashdata('msg',' New Data Added!');
				//redirect('device_list');
			redirect('employees');
		}
		else
		{
			echo "<script>alert('Please select valid excel file!.') </script>";  	
		 /*  echo "<script language=\"javascript\">window.open('https://app.shinerweb.com/index.php/import_excel/', '_self');  </script>";*/
		}
		
	}

public function getLeftEmployeesList($id){
		return $this->db->query("SELECT * FROM user_request WHERE business_id = '$id' and left_date !='' ")->result();
	}
	
	
//	public function getUserAttendanceReportByDate($start_time,$end_time,$uid,$bid,$verified){
	//	$sql = "SELECT * FROM `attendance` WHERE status=1 and io_time BETWEEN $start_time and $end_time and user_id='$uid' and bussiness_id='$bid' and verified='$verified' and manual!='2' and //mode!='Log' order by io_time DESC";
	//	$res = $this->db->query($sql);
	//	return $res->result();
//	}
	
	
		public function getUserAttendanceReportByDate($start_time,$end_time,$uid,$bid,$verified){
		$sql = "SELECT * FROM `attendance` WHERE status=1 and io_time BETWEEN $start_time and $end_time and user_id='$uid' and bussiness_id='$bid' and verified='$verified' and manual!='2' and mode!='Log' order by io_time DESC";
		$res = $this->db->query($sql);
		return $res->result();
	}
	public function getUserAttendanceOldReportByDate($start_time,$end_time,$uid,$bid,$verified){
		$sql = "SELECT * FROM `attendance_back23` WHERE status=1 and io_time BETWEEN $start_time and $end_time and user_id='$uid' and bussiness_id='$bid' and verified='$verified' and manual!='2' and mode!='Log' order by io_time DESC";
		$res = $this->db->query($sql);
		return $res->result();
	}
	
	
	public function getUserAttendanceOld2ReportByDate($start_time,$end_time,$uid,$bid,$verified){
		$sql = "SELECT * FROM `attendance_back_24` WHERE status=1 and io_time BETWEEN $start_time and $end_time and user_id='$uid' and bussiness_id='$bid' and verified='$verified' and manual!='2' and mode!='Log' order by io_time DESC";
		$res = $this->db->query($sql);
		return $res->result();
	}
	
	
	
	
	
	
	
	

	public function getHeadByBusinessId($id){
		return $this->db->query("SELECT * FROM ctc_head WHERE bid = '$id' and active= 1 ")->result();
		// return $this->db->query("SELECT * FROM login WHERE id = '$id'")->row_array();
	}
public function getheaders($bid){
		$sql = "SELECT * from ctc_head where bid='$bid'";
		$res = $this->db->query($sql);
		return $res->row_array();
	}
	public function addheaders($data){
		return $this->db->insert('ctc_head',$data);
	}
public function getHeadbyId($id){
		
		return $this->db->query("SELECT id,name FROM ctc_head WHERE id='$id'")->result();
	}	
	//// staff dinfo
	public function getstaffinfoByUserId($id,$bid){
		return $this->db->query("SELECT * FROM staff_detail WHERE uid = '$id' and bid='$bid'")->result();
	}
	
// assign working	
public function getassignworking($id){
		return $this->db->query("SELECT * FROM assign_working WHERE bid = '$id' and status!=0  order by date desc ")->result();
		
	}
	public function delete_working($id){
        $val = $this->db->query("UPDATE assign_working SET status = 0 WHERE id = '$id'");
        return $val;
    }	
	
public function getuserById($bid,$id){
		return $this->db->query("SELECT * FROM user_request WHERE business_id = '$bid' and user_id='$id'")->result();
	}
		
	
	
	
	
	
	///salary changes
	public function insertSalleryReport($postData = '')
	{ 
	    if($this->session->userdata()['type']=='P'){
     
       $loginID = $this->session->userdata('empCompany');
        } else {
        $loginID=$this->web->session->userdata('login_id');
        }
        //$loginID = $this->web->session->userdata('login_id'); // THIS IS BUSINESS ID
		$this->db->select('user_request.*, login.name as empName, login.mobile as empMobile, login.emp_code, login.designation as empDesignation, login.business_group');
		$this->db->join('login', 'login.id = user_request.user_id', 'LEFT');
		$empList =  $this->db->get_where('user_request', array('user_request.business_id' => $loginID))->result();
		if(!empty($empList))
		{
			if(!empty($postData))
			{
				$yearName  = date('Y', strtotime($postData['date_from']));
				$monthName = date('m', strtotime($postData['date_from']));
			}
			else
			{
				$yearName = date('Y');
				$monthName = date('m');
			}

			$holidays = $this->getHoliday($loginID);
			$holiday_array = array();
			if($holidays){
				foreach($holidays as $holiday){
					$holiday_array[] = array(
						'date'=>date('d.m.Y',$holiday->date),
					);
				}
			}

			foreach ($empList as $key => $dataVal) {

				//HARE GET AND CALCULATE // ALLOWANCE + OVERTIME + BONUS + INCENTIVE
				$getAmount = $this->getTypeTotalAmount($dataVal->user_id, $yearName, $monthName, '1' );


				// HARE GET USER TOTAL CTC
				$getCTC  = $this->db->query("SELECT * FROM salary WHERE  bid = '".$loginID."' AND uid = '".$dataVal->user_id."' AND  YEAR(date) = '".$yearName."' AND MONTH(date) = '".$monthName."' ")->row_array();

				if(empty($getCTC))
				{
					$getCTC  = $this->db->query("SELECT * FROM salary WHERE  bid = '".$loginID."' AND uid = '".$dataVal->user_id."'  ORDER BY date DESC ")->row_array();
				}


				/// HARE CALCULATE TOTAL SALLARY
				$empList[$key]->totalSalary = @$getAmount['addAmount']+@$getCTC['total_ctc_amount'];


				// HARA GET TOTAL DEDUCTION AMOUNT
				$getTotalDeduction = $this->getTypeTotalAmount($dataVal->user_id, $yearName, $monthName, '2' );

				// HARA GET TOTAL PAID AMOUNT
				$getTotalPaid                = $this->getTypeTotalAmount($dataVal->user_id, $yearName, $monthName, '3' );
				$empList[$key]->getTotalPaid =  $getTotalPaid['addAmount'];

				$empList[$key]->deductionAmount =  $getTotalDeduction['addAmount'];

				$groups = $this->getUserGroup($dataVal->business_group);
				$grp = array();
				$day_shift_start = array();
				$day_shift_end = array();
			    $start_date=date("Y-m-d",strtotime($yearName."-".$monthName."-01"));
                $start_time3 = strtotime(date("d-m-Y 06:00:00",strtotime($start_date)));
				if($groups){
					$weekly_off = explode(",",$groups->weekly_off);
					$month_weekly_off = explode(",",$groups->month_weekly_off);
					$day_shift_start = explode(",",$groups->day_start_time);
					$day_shift_end = explode(",",$groups->day_end_time);
					$shift_start = $groups->shift_start;
					$shift_end = $groups->shift_end;
					$group_name = $groups->name;
				//	$start_date=date("Y-m-d",strtotime($yearName."-".$monthName."-01"));
				//	$start_time = strtotime(date("d-m-Y 00:00:00",strtotime($start_date)));
					$start_time =strtotime(date("d-m-Y 00:00:00",strtotime($yearName."-".$monthName."-01")));
				//	$start_time="1711929600";
					if($month_weekly_off!=0){
										 
										foreach($month_weekly_off as $k=>$off){
		                            	if($off==1){	
		                            //	$N=date('N',$start_time);
		                             // 	$key2=$k-$N+1;
		                             
		                             	$N=date('N',$start_time3);
		                            	if ($N==7 && $key==19){
		                            	    $DN=0;
		                            	} else { $DN=$N;}
		                           
		                              	$key2=$k-$DN+1;
		                             
		                             
		                            	$week_start_date = strtotime(date("d-m-Y",$start_time)." +".$key2." days");
		                             	$grp[] = array('day_off'=>date('d.m.Y',$week_start_date),);
									
										} 
										}
										}else{
									

					
					
					
					
					foreach($weekly_off as $k=>$off){
						if($off==1){
							$grp[] = array(
								'day_off'=>$k+1
							);
						}
					}}
				}else{
					$shift_start = "";
					$shift_end = "";
					$group_name = "";
				}
			/*	if($groups){
										$weekly_off = explode(",",$groups->weekly_off);
										$month_weekly_off = explode(",",$groups->month_weekly_off);
										$day_shift_start = explode(",",$groups->day_start_time);
										$day_shift_end = explode(",",$groups->day_end_time);
										$shift_start = $groups->shift_start;
										$shift_end = $groups->shift_end;
										$group_name = $groups->name;
										if($month_weekly_off!=0){
										 
										foreach($month_weekly_off as $key=>$off){
		                            	if($off==1){	
		                            	$N=date('N',$start_time3);
		                            	if ($N==7 && $key==19){
		                            	    $DN=0;
		                            	} else { $DN=$N;}
		                           
		                              	$key2=$key-$DN+1;
		                            	$week_start_date = strtotime(date("d-m-Y",$start_time)." +".$key2." days");
		                             	$grp[] = array('day_off'=>date('d.m.Y',$week_start_date),);
											}} 
										    
										}else{
										foreach($weekly_off as $key=>$off){
											if($off==1){
												$grp[] = array(
													'day_off'=>$key+1
												);
											}
										}
									}
									}else{
										$shift_start = "";
										$shift_end = "";
										$group_name = "";
									}
				
				*/
				
				
				
                $leaves_array = array();
			/*	$leaves = $this->getEmpLeaves($dataVal->user_id);
				$leaves_array = array();
				if($leaves){
					foreach($leaves as $leave){
						$from_date_leave=date_create(date("Y-m-d",$leave->from_date));
						$to_date_leave=date_create(date("Y-m-d",$leave->to_date));
						$leave_diff=date_diff($from_date_leave,$to_date_leave);
						$leave_days = $leave_diff->format("%a");
						$leave_days++;
						for($l=0;$l<$leave_days;$l++){
							$leave_start_date = strtotime(date("d-m-Y",$leave->from_date)." +".$l." days");
							$leaves_array[] = array(
								'date'=>date('d.m.Y',$leave_start_date),
							);
						}
					}
				}
				
*/





				$rules = $this->getRule($loginID,$dataVal->rule_id);
				$mispunch = "0";
				$ca_wo_lofi = "0";
				$mark_ab_week = "0";
				$ov_shift = "0";
				$sl_late_on = "0";
				$sl_early_on = "0";
				$halfday_on = "0";
				$absent_on = "0";
				$overtime_wh_on = "0";
				$sl_late_time = 0;
				$sl_early_time = 0;
				$half_wo_time = 0;
				$ab_wo_time = 0;
				$ov_out_time = 0;
				$ov_wo_time = 0;
				$late_deduction_on = 0;
				$late_deduction_time = 0;
				$late_deduction_amount = 0;
				$early_deduction_on = 0;
				$early_deduction_time = 0;
				$early_deduction_amount = 0;

				$short_leave_deduction_on = 0;
				$short_leave_deduction_amount = 0;
				$short_leave_deduction_days = 0;
				$extra_absent_deduction_on = 0;
				$extra_absent_deduction_amount = 0;
				$extra_absent_deduction_days = 0;
				$absent_without_leave_on = 0;
				$absent_without_leave_amount = 0;
				$present_holiday_weekly_on = 0;
				$present_holiday_weekly_amount = 0;
				$overtime_bonus_on = 0;
				$overtime_bonus_amount = 0;

				if($rules){
					$mispunch = $rules['mispunch'];
					$sl_late_time = $rules['sl_late'];
					$sl_early_time = $rules['sl_early'];
					$half_wo_time = $rules['halfday'];
					$ab_wo_time = $rules['absent'];
					$ov_out_time = $rules['overtime_shiftout'];
					$ov_wo_time = $rules['overtime_wh'];
					$ca_wo_lofi = $rules['wh_cal'];
					$mark_ab_week = $rules['wo_absent'];
					$ov_shift = $rules['overtime_shift'];
					$sl_late_on = $rules['sl_late_on'];
					$sl_early_on = $rules['sl_early_on'];
					$halfday_on = $rules['halfday_on'];
					$absent_on = $rules['absent_on'];
					$overtime_wh_on = $rules['overtime_wh_on'];

					$late_deduction_on = $rules['lt_punchin_on'];
					$late_deduction_time = $rules['lt_punchin_time'];
					$late_deduction_amount = $rules['lt_punchin'];

					$early_deduction_on = $rules['el_punchout_on'];
					$early_deduction_time = $rules['el_punchout_time'];
					$early_deduction_amount = $rules['el_punchout'];

					$short_leave_deduction_on = $rules['sl_on'];
					$short_leave_deduction_amount = $rules['sl_fine'];
					$short_leave_deduction_days = $rules['sl_days'];

					$extra_absent_deduction_on = $rules['ex_absent_on'];
					$extra_absent_deduction_amount = $rules['ex_absent_fine'];
					$extra_absent_deduction_days = $rules['ex_absent_days'];

					$absent_without_leave_on = $rules['ab_leave_fine_on'];
					$absent_without_leave_amount = $rules['ab_leave_fine'];

					$present_holiday_weekly_on = $rules['incentive_hl_on'];
					$present_holiday_weekly_amount = $rules['incentive_hl'];

					$overtime_bonus_on = $rules['ot_on'];
					$overtime_bonus_amount = $rules['ot_amount'];
				}
				$months_array = array();
				$totalPresent = 0;
				$totalAbsent = 0;
				$totalWeekOff = 0;
				$totalHoliday = 0;
				$totalLeaves = 0;
				$totalShortLeave = 0;
				$totalP2 = 0;
				$totalOT = 0;
				$totalWorkingHrs = "00:00 Hr";
				$totalLate = "00:00 Hr";
				$totalEarly = "00:00 Hr";
				$days_array = array();
				$seconds = 0;
				$previousAt = array();
				$nextAt = array();
				
			 $start_date=date("Y-m-d",strtotime($yearName."-".$monthName."-01"));
			$end_date=date("Y-m-d");
				//$left_date=date("Y-m-d",$dataVal->left_date);
			//if($left_date<$end_date){
		    //$end_date=$left_date;
		//}
			$date1=date_create(date("Y-m-d",strtotime($start_date)));
									$date2=date_create(date("Y-m-d",strtotime($end_date)));
									$diff=date_diff($date1,$date2);
									$num_month = $diff->format("%a");

									$num_month++;
									$maxDays = date("t",strtotime($yearName."-".$monthName."-01"));
									if($num_month>$maxDays){
										$num_month=$maxDays;
									}
				
				$monthStartTime = strtotime(date("d-m-Y 00:00:00",strtotime($yearName."-".$monthName."-01")));
				$monthEndTime = strtotime(date("d-m-Y 23:59:59",strtotime($yearName."-".$monthName."-01"))." +".$num_month." days");
				
				//	$monthStartTime = strtotime(date("d-m-Y 00:00:00",strtotime($yearName."-".$monthName."-01")));
				$monthEndTime2 = strtotime(date("d-m-Y 23:59:59",strtotime($yearName."-".$monthName."-01"))." +".($num_month-1)." days");
				
				$uid=$dataVal->user_id;
				
					$onduty =$this->getUserOD($uid,$monthStartTime,$monthEndTime2);
				//$leaves =$this->getEmpLeaves($uid);
				//$leaves_array = array();
				$od_days =0;
				if($onduty){
					
					foreach($onduty as $onduty){
				
							  $from_date_od=date_create(date("Y-m-d",$onduty->date));
							  $to_date_od=date_create(date("Y-m-d",$onduty->end_date));
							  $od_diff=date_diff($from_date_od,$to_date_od);
							  $od_dayso = $od_diff->format("%a");
							  $od_dayso++;
                              $od_days=$od_days+$od_dayso;
                   }
                 }
				 $od_dayst=$od_days;

				
				
				
				
				
				
				$monthUserAt = $this->app->getUserAttendanceReportByDate($monthStartTime,$monthEndTime,$dataVal->user_id,$loginID,1);
				if($empList[$key]->totalSalary>0){
					
					for($d=0; $d<$num_month;$d++){
						$new_start_time = strtotime(date("d-m-Y 00:00:00",strtotime($yearName."-".$monthName."-01"))." +".$d." days");
						$new_end_time = strtotime(date("d-m-Y 23:59:59",strtotime($yearName."-".$monthName."-01"))." +".$d." days");
						$next_day_start_time = strtotime(date("d-m-Y 00:00:00",strtotime($yearName."-".$monthName."-01"))." +".($d+1)." days");
						$next_day_end_time = strtotime(date("d-m-Y 23:59:59",strtotime($yearName."-".$monthName."-01"))." +".($d+1)." days");

						$pre_day_start_time = strtotime(date("d-m-Y 00:00:00",strtotime($yearName."-".$monthName."-01"))." +".($d-1)." days");
						$pre_day_end_time = strtotime(date("d-m-Y 23:59:59",strtotime($yearName."-".$monthName."-01"))." +".($d-1)." days");
						$days_array[]= date("d",$new_start_time);
						$data = array();
						$day_seconds=0;
						$late_seconds=0;
						$early_seconds=0;
						$ot_seconds=0;
						$day_hrs = "W.H 00:00 Hr";
						$late_hrs = "00:00";
						$early_hrs = "00:00";
						$ot_hrs = "00:00";
						$halfday = "0";
						$absentWo = "0";
						$sl = "s";
						$day_status="";
						$day_sub_status="";
						if(($dataVal->doj!="" || $monthStartTime>=$user->doj) && ($dataVal->left_date=="" || $monthStartTime<$dataVal->left_date)){
						    
						    if(($dataVal->doj =="" || $new_start_time >=$dataVal->doj) && ($dataVal->left_date=="" || $new_start_time < $dataVal->left_date)){
												$news_start_time =$new_start_time;
											}
						    
							$user_at = array_filter($monthUserAt, function($val) use($new_start_time, $new_end_time){
								return ($val->io_time>=$new_start_time and $val->io_time<=$new_end_time);
							});
							$user_at = array_reverse($user_at);
							$nextAt = array_filter($monthUserAt, function($val) use($next_day_start_time, $next_day_end_time){
								return ($val->io_time>=$next_day_start_time and $val->io_time<=$next_day_end_time);
							});
							$nextAt = array_reverse($nextAt);
							if($d==0){
								$previousAt = array_filter($monthUserAt, function($val) use($pre_day_start_time, $pre_day_end_time){
									return ($val->io_time>=$pre_day_start_time and $val->io_time<=$pre_day_end_time);
								});
								$previousAt = array_reverse($previousAt);
							}
							
						if($month_weekly_off!=0){	$off = array_search(date('d.m.Y',$news_start_time),array_column($grp,'day_off'));
												}else{
							$off = array_search(date('N',$news_start_time),array_column($grp,'day_off'));}
							
						//	$off = array_search(date('N',$new_start_time),array_column($grp,'day_off'));
							$holi = array_search(date('d.m.Y',$news_start_time),array_column($holiday_array,'date'));
							$lv = array_search(date('d.m.Y',$new_start_time),array_column($leaves_array,'date'));

							$prevWeekOff = array_search(date('N',$pre_day_start_time),array_column($grp,'day_off'));
							$nextWeekOff = array_search(date('N',$next_day_start_time),array_column($grp,'day_off'));
							$prevHoliOff = array_search(date('d.m.Y',$pre_day_start_time),array_column($holiday_array,'date'));
							$nextHoliOff = array_search(date('d.m.Y',$next_day_start_time),array_column($holiday_array,'date'));
							
							if(!empty($day_shift_start)){
								if($day_shift_start[date('N',$new_start_time)-1]!=null){
									$shift_start = $day_shift_start[date('N',$new_start_time)-1];
								}
							}
							if(!empty($day_shift_end)){
								if($day_shift_end[date('N',$new_start_time)-1]!=null){
									$shift_end = $day_shift_end[date('N',$new_start_time)-1];
								}
							}

							$prevPresent = false;
							$nextPresent = false;
							if($mark_ab_week==1){
							    if(!empty($previousAt) || !is_bool($prevWeekOff) || !is_bool($prevHoliOff)){
								$prevPresent = true;
								}

								if(!empty($nextAt) || !is_bool($nextWeekOff) || !is_bool($nextHoliOff)){
									$nextPresent = true;
								}
							}else{
							    $prevPresent = true;
							}
							
							if($d==0){
							    $prevPresent = true;
							}

							//if(!is_bool($off) && ($prevPresent || $nextPresent)){
								if(!is_bool($off)){
								$weekOff = "1";
								$totalWeekOff++;
							}else{
								$weekOff = "0";
							}

							if(!is_bool($holi) && ($prevPresent || $nextPresent)){
								$holiday="1";
								$totalHoliday++;
							}else{
								$holiday="0";
							}

							if(!is_bool($lv)){
								$totalLeaves++;
								$day_leave="1";
							}else{
								$day_leave="0";
							}
							$previousAt = $user_at;
							$nextAt = array();
							if(!empty($user_at)){
								$ins_array = array();
								$outs_array = array();
								
								foreach($user_at as $at){
									$timeSearch = array_search($at->io_time,array_column($data,'time'));
									if(is_bool($timeSearch)){
										$data[] = array(
											'mode'=>$at->mode,
											'time'=>$at->io_time,
											'io_time'=>$at->io_time,
											'comment'=>$at->comment,
											'manual'=>$at->manual,
											'location'=>$at->location
										);
										if($at->mode=='in' && !in_array($at->io_time,$ins_array)){
											$ins_array[]=$at->io_time;
										}
										if($at->mode=='out' && !in_array($at->io_time,$outs_array)){
											$outs_array[]=$at->io_time;
										}
									}
								}
								$io_end = count($ins_array)-count($outs_array);
								if(count($outs_array)<count($ins_array)){
									for($io=0; $io<$io_end;$io++){
										$outs_array[]="0";
									}
								}
								foreach($ins_array as $k => $ins){
									if($outs_array[$k]!="0"){
										if($outs_array[$k]>$ins_array[$k]){
											$seconds += $outs_array[$k]-$ins_array[$k];
										}
										$day_seconds += $outs_array[$k]-$ins_array[$k];
									}
								}
								// if($ca_wo_lofi=="1"){
								// 	$day_out = "0";
								// 	for($o=count($outs_array)-1;$o>=0;$o--){
								// 		if($outs_array[count($outs_array)-1]!="0"){
								// 			$day_out = $outs_array[$o];
								// 			break;
								// 		}
								// 	}
								// 	if($day_out=="0"){
								// 		$day_seconds = 0;
								// 	}else{
								// 		if(count($ins_array)>0){
								// 			$day_seconds = $day_out-$ins_array[0];
								// 		}else{
								// 			$day_seconds = 0;
								// 		}
								// 	}
								// }
								
								if($ca_wo_lofi=="1"){
									$day_seconds = $data[count($data)-1]['time']-$data[0]['time'];
								}

								$hours = floor($day_seconds / 3600);
								$minutes = floor($day_seconds / 60)%60;
								$day_hrs = "W.H $hours:$minutes Hr";

								if($day_seconds>0 && $absent_on=="1" &&($day_seconds<$ab_wo_time)){
									$absentWo="1";
								}

								if($day_seconds>0 && $absentWo=="0" && $halfday_on=="1" &&($day_seconds<$half_wo_time)){
									$halfday="1";
									if ($weekOff=="0" && $holiday=="0") 
												{	$totalP2++;
												}else {
												    $totatlOT=$totatlOT+0.5;
												}	
								}

								if($shift_start!="" && !empty($ins_array)){
									$in_start = strtotime(date("d-m-Y h:i A",strtotime(date("h:i A",$ins_array[0]))));
									$sh_start = strtotime(date("d-m-Y h:i A",strtotime($shift_start)));
									$sh_end = strtotime(date("d-m-Y h:i A",strtotime($shift_end)));
									if($in_start>$sh_start){
										$late_seconds = $in_start-$sh_start;
										$hours = floor($late_seconds / 3600);
										$minutes = floor($late_seconds / 60)%60;
										$late_hrs = "$hours:$minutes";
										if($sl_late_on=="1" && ($late_seconds > $sl_late_time) && $halfday=="0"){
											$sl ="SL";
										}
									}
									if($outs_array[count($outs_array)-1]!="0"){
										$out_end = strtotime(date("d-m-Y h:i A",strtotime(date("h:i A",$outs_array[count($outs_array)-1]))));
										if($sh_end>$out_end && $out_end!=0){
											$early_seconds = $sh_end-$out_end;
											$hours = floor($early_seconds / 3600);
											$minutes = floor($early_seconds / 60)%60;
											$early_hrs = "$hours:$minutes";
											if($sl_early_on=="1" && ($early_seconds > $sl_early_time) && $halfday=="0"){
												$sl = "SL";
											}
										}
									}
									// if($day_seconds!=0 && $day_seconds<($sh_end-$sh_start)){
									// 	$early_seconds = ($sh_end-$sh_start)-$day_seconds;
									// 	$hours = floor($early_seconds / 3600);
									// 	$minutes = floor($early_seconds / 60%60);
									// 	$early_hrs = "EL $hours:$minutes Hr";
									// 	if($sl_early_on=="1" && ($early_seconds > $sl_early_time) && $halfday=="0"){
									// 		$sl = "SL";
									//
									// 	}
									// }

									if($outs_array[count($outs_array)-1]!="0"){
										if($ot_seconds>0 && $ov_shift=="1" && ($ot_seconds > $ov_out_time)){
											$out_end = strtotime(date("d-m-Y h:i A",strtotime(date("h:i A",$outs_array[count($outs_array)-1]))));
											$ot_seconds = $out_end-$sh_end;
											$hours = floor($ot_seconds / 3600);
											$minutes = floor($ot_seconds / 60%60);
											$ot_hrs = "$hours:$minutes";
										}
									}
								}

								if($overtime_wh_on=="1" &&($day_seconds>$ov_wo_time)){
									$ot_seconds = $day_seconds-$ov_wo_time;
									if($ot_seconds>0){
										$hours = floor($ot_seconds / 3600);
										$minutes = floor($ot_seconds / 60%60);
										$ot_hrs = "$hours:$minutes";
									}
								}
								if($absentWo=="1"){
									$totalAbsent++;
								}else{
									if($sl!="SL"){
    								// 	if($weekOff=="1" || $holiday=="1"){
    								// 		$totalOT++;
    								// 	}else{
    										
    								// 	}
										if($halfday=="0" && $weekOff=="0" && $holiday=="0" ){
														 	$totalPresent++;																	
													//	}elseif($weekOff=="1" || $holiday=="1") 
										}elseif($weekOff=="1" || $holiday=="1") 
														{ $totalOT++;
														}
									}else{
										$totalShortLeave++;
									}
								}
								
							}else{
						// 		if($weekOff=="1"){
						// 			$totalWeekOff++;
						// 		}
						// 		if($holiday=="1"){
						// 			$totalHoliday++;
						// 		}
								if($weekOff=="0" && $holiday=="0" && $day_leave=="0"){
									$totalAbsent++;
								}
								$data = array();
							}

							$day_status = "A";

							if($day_leave=="1"){
								$day_status = "L";
							}

							if($holiday=="1"){
								$day_status = "H";
							}

							if($weekOff=="1"){
								$day_status = "W";
							}

							if(!empty($data)){
								if($absentWo=="1"){
									$day_status="A";
								}else{
									$day_status = "P";
								if($halfday=="1"){
									$day_status="P/2";
								}
								$msOut = true;
								foreach($data as $day_data){
									if($day_data['mode']=="out"){
									$msOut = false;
									}
								}
								if($mispunch=="1" && $msOut){
									$day_status="MS";
								}
								if($weekOff=="1"){
									$day_status="WP";
									if($mispunch=="1" && $msOut){
										$day_status="W MS";
									}
									if($halfday=="1"){
										$day_status="WP/2";
									}
								}
								if($holiday=="1"){
									$day_status="HP";
									if($mispunch=="1" && $msOut){
										$day_status="H MS";
									}
									if($halfday=="1"){
										$day_status="HP/2";
									}
								}

								if($sl=="SL"){
									if(strlen($day_sub_status)==0){
										$day_sub_status.="SL";
									}else{
										$day_sub_status.=",SL";
									}
								}
								if($ot_seconds>0){
									if(strlen($day_sub_status)==0){
										$day_sub_status.="OT";
									}else{
										$day_sub_status.=",OT";
									}
								}
								}
							}

							if($overtime_bonus_on>0 && $ot_seconds>0){
								if($overtime_bonus_amount>0){
									$overtime_one_sec_amount  = $overtime_bonus_amount/3600;
									$overtime_addition = $overtime_one_sec_amount*$ot_seconds;
									$payDate = date("Y-m-d",$new_start_time);
									$insertDeduction = array(
										'business_id' 		=> $loginID,
										'payroll_master_id'	=> 5,
										'user_id	' 		=> $dataVal->user_id,
										'pay_date' 			=> date("Y-m-d",$new_start_time),
										'amount	' 			=> round($overtime_addition),
										'remarks' 			=> "Overtime_Bonus",
										'status' 			=> 1,
										'settled'			=>1,
										'paid'				=>1,
										'payroll_id'		=>1,
										'date' 			=> date("Y-m-d h:m:s",$new_start_time)
									);
									$lateEarlyReport = $this->db->query("SELECT * FROM payroll_history where user_id='$dataVal->user_id' and business_id='$loginID' AND pay_date='$payDate' and remarks='Overtime_Bonus'")->row_array();
									if(empty($lateEarlyReport)){
										$this->db->insert('payroll_history',$insertDeduction);
									}
								}
							}
							

							if($present_holiday_weekly_on>0 && ($day_status=="WP" || $day_status=="HP")){
								if($present_holiday_weekly_amount>0){
									$payDate = date("Y-m-d",$new_start_time);
									$insertDeduction = array(
										'business_id' 		=> $loginID,
										'payroll_master_id'	=> 7,
										'user_id	' 		=> $dataVal->user_id,
										'pay_date' 			=> date("Y-m-d",$new_start_time),
										'amount	' 			=> round($present_holiday_weekly_amount),
										'remarks' 			=> "Present_On_Weekly_Off_Or_Holiday",
										'status' 			=> 1,
										'settled'			=>1,
										'paid'				=>1,
										'payroll_id'		=>1,
										'date' 			=> date("Y-m-d h:m:s",$new_start_time)
									);
									$lateEarlyReport = $this->db->query("SELECT * FROM payroll_history where user_id='$dataVal->user_id' and business_id='$loginID' AND pay_date='$payDate' and remarks='Present_On_Weekly_Off_Or_Holiday'")->row_array();
									if(empty($lateEarlyReport)){
										$this->db->insert('payroll_history',$insertDeduction);
									}
								}
							}


							if($absent_without_leave_on>0 && $day_status=="A" && $day_leave=="0"){
								if($absent_without_leave_amount>0){
									$payDate = date("Y-m-d",$new_start_time);
									$insertDeduction = array(
										'business_id' 		=> $loginID,
										'payroll_master_id'	=> 8,
										'user_id	' 		=> $dataVal->user_id,
										'pay_date' 			=> date("Y-m-d",$new_start_time),
										'amount	' 			=> round($absent_without_leave_amount),
										'remarks' 			=> "Absent_Without_Leave",
										'status' 			=> 1,
										'settled'			=>1,
										'paid'				=>1,
										'payroll_id'		=>2,
										'date' 			=> date("Y-m-d h:m:s",$new_start_time)
									);
									$lateEarlyReport = $this->db->query("SELECT * FROM payroll_history where user_id='$dataVal->user_id' and business_id='$loginID' AND pay_date='$payDate' and remarks='Absent_Without_Leave'")->row_array();
									if(empty($lateEarlyReport)){
										$this->db->insert('payroll_history',$insertDeduction);
									}
								}
							}
							
							if($late_seconds>0 && $late_deduction_on>0){
								if($late_deduction_amount>0){
									if($late_seconds>$late_deduction_time){
										$late_one_sec_amount  = $late_deduction_amount/3600;
										$late_deduction = $late_one_sec_amount*$late_seconds;
										$payDate = date("Y-m-d",$new_start_time);
										$insertDeduction = array(
											'business_id' 		=> $loginID,
											'payroll_master_id'	=> 8,
											'user_id	' 		=> $dataVal->user_id,
											'pay_date' 			=> date("Y-m-d",$new_start_time),
											'amount	' 			=> round($late_deduction),
											'remarks' 			=> "Late",
											'status' 			=> 1,
											'settled'			=>1,
											'paid'				=>1,
											'payroll_id'		=>2,
											'date' 			=> date("Y-m-d h:m:s",$new_start_time)
										);
										$lateEarlyReport = $this->db->query("SELECT * FROM payroll_history where user_id='$dataVal->user_id' and business_id='$loginID' AND pay_date='$payDate' and remarks='Late'")->row_array();
										if(empty($lateEarlyReport)){
											$this->db->insert('payroll_history',$insertDeduction);
										}
									}
								}
							}

							if($early_seconds>0 && $early_deduction_on>0){
								if($early_deduction_amount>0){
									if($early_seconds>$early_deduction_time){
										$early_one_sec_amount  = $early_deduction_amount/3600;
										$early_deduction = $early_one_sec_amount*$early_seconds;
										$payDate = date("Y-m-d",$new_start_time);
										$insertDeduction = array(
											'business_id' 		=> $loginID,
											'payroll_master_id'	=> 8,
											'user_id	' 		=> $dataVal->user_id,
											'pay_date' 			=> date("Y-m-d",$new_start_time),
											'amount	' 			=> round($early_deduction),
											'remarks' 			=> "Early",
											'status' 			=> 1,
											'settled'			=>1,
											'paid'				=>1,
											'payroll_id'		=>2,
											'date' 			=> date("Y-m-d h:m:s",$new_start_time)
										);
										$lateEarlyReport = $this->db->query("SELECT * FROM payroll_history where user_id='$dataVal->user_id' and business_id='$loginID' AND pay_date='$payDate' and remarks='Early'")->row_array();
										if(empty($lateEarlyReport)){
											$this->db->insert('payroll_history',$insertDeduction);
										}
									}
								}
							}

							// if($early_seconds>0){
							// 	if(strlen($day_sub_status)==0){
							// 		$day_sub_status.="E";
							// 	}else{
							// 		$day_sub_status.=",E";
							// 	}
							// }
							
							$months_array[] = array(
								'date'=>date("j",$new_start_time),
								'day'=>date("l",$new_start_time),
								'weekly_off'=>$weekOff,
								'holiday'=>$holiday,
								'leave'=>$day_leave,
								'data'=>$data,
								'workingHrs'=>$day_hrs,
								'late_hrs'=>$late_hrs,
								'early_hrs'=>$early_hrs,
								'ot_hrs'=>$ot_hrs,
								'mispunch'=>$mispunch,
								'sl_late'=>$sl_late_time,
								'sl_early'=>$sl_early_time,
								'halfday'=>$halfday,
								'absent'=>$absentWo,
								'overtime_shiftout'=>$ov_out_time,
								'overtime_wh'=>$ov_wo_time,
								'wh_cal'=>$ca_wo_lofi,
								'wo_absent'=>$mark_ab_week,
								'overtime_shift'=>$ov_shift,
								'ot_seconds'=>$ot_seconds,
								'day_status'=>$day_status,
								'day_sub_status'=>$day_sub_status,
								'sl'=>$sl
							);
						}
					}
				}


				$perDay = $empList[$key]->totalSalary/$maxDays;
				$newPayable = $perDay*($totalPresent+$totalWeekOff+$totalHoliday+$totalLeaves);

				$salaryStartDate = strtotime(date("d-m-Y 00:00:00",strtotime($yearName."-".$monthName."-01"))." +".'0'." days");
				$salaryEndDate = strtotime(date("d-m-Y 23:59:59",strtotime($yearName."-".$monthName."-01"))." +".($maxDays-1)." days");
				if($salaryEndDate>time()){
					$salaryEndDate = time();
				}
				$salaryRes = $this->db->query("SELECT * FROM salary_report where uid='$dataVal->user_id' and bid='$loginID' AND  YEAR(date_time)='$yearName' AND MONTH(date_time)='$monthName'")->row_array();
				$salaryData = array(
					'bid'=>$loginID,
					'uid'=>$dataVal->user_id,
					'start_date'=>$salaryStartDate,
					'end_date'=>$salaryEndDate,
					'days'=>$num_month,
					'present'=>$totalPresent+$od_dayst,
					'absent'=>$totalAbsent,
					'half_day'=>$totalP2,
					'week_off'=>$totalWeekOff,
					'holiday'=>$totalHoliday,
				//	'leaves'=>$totalLeaves,
					'ed'=>$totalOT,
					'short_leave'=>$totalShortLeave,
					'net_payable'=>$newPayable,
					'date_time'=>date("Y-m-d H:i:s",$salaryStartDate)
				);
				if($short_leave_deduction_on>0 && $totalShortLeave>$short_leave_deduction_days){
					$payDate = date("Y-m-d",$salaryEndDate);
					$insertDeduction = array(
						'business_id' 		=> $loginID,
						'payroll_master_id'	=> 8,
						'user_id	' 		=> $dataVal->user_id,
						'pay_date' 			=> date("Y-m-d",$salaryEndDate),
						'amount	' 			=> round($short_leave_deduction_amount),
						'remarks' 			=> "Short_Leave",
						'status' 			=> 1,
						'settled'			=>1,
						'paid'				=>1,
						'payroll_id'		=>2,
						'date' 			=> date("Y-m-d h:m:s",$salaryEndDate)
					);
					$lateEarlyReport = $this->db->query("SELECT * FROM payroll_history where user_id='$dataVal->user_id' and business_id='$loginID' AND pay_date='$payDate' and remarks='Short_Leave'")->row_array();
					if(empty($lateEarlyReport)){
						$this->db->insert('payroll_history',$insertDeduction);
					}
				}

				if($extra_absent_deduction_on>0 && $totalAbsent>$extra_absent_deduction_days){
					$payDate = date("Y-m-d",$salaryEndDate);
					$insertDeduction = array(
						'business_id' 		=> $loginID,
						'payroll_master_id'	=> 8,
						'user_id	' 		=> $dataVal->user_id,
						'pay_date' 			=> date("Y-m-d",$salaryEndDate),
						'amount	' 			=> round($extra_absent_deduction_amount),
						'remarks' 			=> "Extra_Absent",
						'status' 			=> 1,
						'settled'			=>1,
						'paid'				=>1,
						'payroll_id'		=>2,
						'date' 			=> date("Y-m-d h:m:s",$salaryEndDate)
					);
					$lateEarlyReport = $this->db->query("SELECT * FROM payroll_history where user_id='$dataVal->user_id' and business_id='$loginID' AND pay_date='$payDate' and remarks='Extra_Absent'")->row_array();
					if(empty($lateEarlyReport)){
						$this->db->insert('payroll_history',$insertDeduction);
					}
				}

				if(!empty($salaryRes)){
					$this->db->where(array('id' => $salaryRes['id']))->update('salary_report',$salaryData);
				}else{
					$this->db->insert('salary_report',$salaryData);
				}
				
				$empList[$key]->netPayable = round(($newPayable-$getTotalDeduction['addAmount'])-$getTotalPaid['addAmount']);
			}
		}
		
		return $empList;
	}
	
public function getlogAccess($start_time,$end_time,$loginid,$uid){
		$sql = "SELECT * FROM `attendance` WHERE status=1 and io_time BETWEEN $start_time and $end_time and bussiness_id='$loginid' and user_id='$uid'  and manual=4 order by io_time ASC ";
		$res = $this->db->query($sql);
		return $res->result();
	}

	public function getpendingatt($loginid,$uid){
		$sql = "SELECT * FROM `attendance` WHERE manual=1 and bussiness_id='$loginid' and user_id='$uid' order by io_time DESC ";
		$res = $this->db->query($sql);
		return $res->result();
	}	
	
		public function getempAccess($start_time,$end_time,$loginid,$uid){
		$sql = "SELECT * FROM `attendance` WHERE status=1 and io_time BETWEEN $start_time and $end_time and bussiness_id='$loginid' and user_id='$uid' and manual=4 order by io_time ASC ";
		$res = $this->db->query($sql);
		return $res->result();
	}
	
	public function getUserOptions($bid,$id){
		$sql = "SELECT * from user_request where business_id='$bid' and user_id=$id and left_date!=0";
		$res = $this->db->query($sql);
		return $res->row_array();
	}
	public function getUseractivity($bid){
		$sql = "SELECT * from activity where bid='$bid'order by date_time desc LIMIT 200";
		$res = $this->db->query($sql);
		return $res->result();
	}
	public function getUserPactivity($bid,$uid){
		$sql = "SELECT * from activity where bid='$bid'and uid='$uid'order by date_time desc LIMIT 200";
		$res = $this->db->query($sql);
		return $res->result();
	}
	
	
	
	public function delete_leave($id){
        $val = $this->db->query("UPDATE leaves SET status = 0 WHERE id = '$id'");
        return $val;
    }
	
	public function getLeaveByBusinessId_new($id){
		return $this->db->query("SELECT * FROM leaves WHERE bid = '$id' and status!=0 order by from_date DESC  ")->result();

	}	


	public function insertDaysReport($postData = '')
	{
	    if($this->session->userdata()['type']=='P'){
     
       $loginID = $this->session->userdata('empCompany');
        } else {
        $loginID=$this->web->session->userdata('login_id');
        }
		//$loginID = $this->web->session->userdata('login_id'); // THIS IS BUSINESS ID
		$this->db->select('user_request.*, login.name as empName, login.mobile as empMobile, login.emp_code, login.designation as empDesignation, login.business_group');
		$this->db->join('login', 'login.id = user_request.user_id', 'LEFT');
		$empList =  $this->db->get_where('user_request', array('user_request.business_id' => $loginID))->result();
		if(!empty($empList))
		{
			if(!empty($postData))
			{
				$yearName  = date('Y', strtotime($postData['date_from']));
				$monthName = date('m', strtotime($postData['date_from']));
				$monthNa = date('M', strtotime($postData['date_from']));
			}
			else
			{
				$yearName = date('Y');
				$monthName = date('m');
				$monthNa = date('M');
			}
			foreach ($empList as $key => $dataVal) {
				$start_date=date("Y-m-d",strtotime($yearName."-".$monthName."-01"));
			$end_date=date("Y-m-d");
		
			$date1=date_create(date("Y-m-d",strtotime($start_date)));
									$date2=date_create(date("Y-m-d",strtotime($end_date)));
									$diff=date_diff($date1,$date2);
									$num_month = $diff->format("%a");

									$num_month++;
									$maxDays = date("t",strtotime($yearName."-".$monthName."-01"));
									if($num_month>$maxDays){
										$num_month=$maxDays;
									}
				
				$monthStartTime = strtotime(date("d-m-Y 00:00:00",strtotime($yearName."-".$monthName."-01")));
				$monthEndTime = strtotime(date("d-m-Y 23:59:59",strtotime($yearName."-".$monthName."-01"))." +".($num_month-1)." days");
				 $salaryStartDate = strtotime(date("d-m-Y 00:00:00",strtotime($yearName."-".$monthName."-01"))." +".'0'." days");
				 $salaryEndDate = strtotime(date("d-m-Y 23:59:59",strtotime($yearName."-".$monthName."-01"))." +".($maxDays-1)." days");
				$uid=$dataVal->user_id;
				$emp_more_details  = $this->db->get_where('staff_detail',['uid'=>$dataVal->user_id])->row();
				$pay_mode= isset($emp_more_details) ? $emp_more_details->pay_mode :"";
				$leaves =$this->getUserActiveLeaves($uid,$monthStartTime,$monthEndTime);
				//$leaves =$this->getEmpLeaves($uid);
				//$leaves_array = array();
				$leave_days =0;
				if($leaves){
					
					foreach($leaves as $leave){
				  //if($leave->date_time>=$user['open_date'] && $leave->date_time<=$user['close_date']){
                      if($leave->type!="" && $leave->type!="unpaid" && $leave->status==1 ){
						  $half_day=$leave->half_day;
                          $leave_days=$leave_days+$half_day;
                                  }
                    }
                 }
				 $leave_dayst=$leave_days;
			
				// od on duty 
				
			/*	$onduty =$this->getUserOD($uid,$monthStartTime,$monthEndTime);
				//$leaves =$this->getEmpLeaves($uid);
				//$leaves_array = array();
				$od_days =0;
				if($onduty){
					
					foreach($onduty as $onduty){
				  //if($leave->date_time>=$user['open_date'] && $leave->date_time<=$user['close_date']){
                     // if($leave->type!="" && $leave->type!="unpaid" && $leave->status==1 ){
						 // $half_day=$leave->half_day;
                         // $leave_days=$leave_days+$half_day;
                              //    }
							  $from_date_od=date_create(date("Y-m-d",$onduty->date));
							  $to_date_od=date_create(date("Y-m-d",$onduty->end_date));
							  $od_diff=date_diff($from_date_od,$to_date_od);
							  $od_dayso = $od_diff->format("%a");
							  $od_dayso++;
                              $od_days=$od_days+$od_dayso;
                   }
                 }
				 $od_dayst=$od_days;
				 
				 
				*/ 
				 
				 
				 
				 
				 
				 
				 if($salaryEndDate>time()){
					 $salaryEndDate = time();
				 }
				 $salaryRes = $this->db->query("SELECT * FROM salary_report where uid='$dataVal->user_id' and bid='$loginID' AND  YEAR(date_time)='$yearName' AND MONTH(date_time)='$monthName'")->row_array();
				 $salaryData = array(
					// 'bid'=>$loginID,
					// 'uid'=>$dataVal->user_id,
					 //'start_date'=>$salaryStartDate,
					// 'end_date'=>$salaryEndDate,
					 //'days'=>$maxDays,
					 //'present'=>$totalPresent,
					 //'absent'=>$totalAbsent,
					 //'half_day'=>$totalP2,
					// 'week_off'=>$totalWeekOff,
					 'pay_mode'=>$pay_mode,
					 'leaves'=>$leave_dayst,
					// 'ed'=>$od_dayst
					// 'short_leave'=>$totalShortLeave,
					// 'net_payable'=>$newPayable,
					 //'date_time'=>date("Y-m-d H:i:s",$salaryStartDate)
				 );

				 if(!empty($salaryRes)){
					$this->db->where(array('id' => $salaryRes['id']))->update('salary_report',$salaryData);
				}else{
					$this->db->insert('salary_report',$salaryData);
				}
				
			
			
					
				
				
				
			}
			$actdata=array(
			                   'bid'=>$loginID ,
				             'uid'=>$this->web->session->userdata('login_id'),
				            'activity'=>"Salary of all employee calculated  for the month of ".$monthNa. " ",
				            'date_time'=>time()
				
			                             );
			                  $data=$this->db->insert('activity',$actdata);
		}
		return $empList;
			
	}



public function getUserLeaves($uid,$start,$end){
		$sql = "SELECT * FROM `leaves` WHERE uid=$uid and status!=0 and from_date BETWEEN $start and $end";
		//$sql = "SELECT * FROM `attendance` WHERE status=1 and io_time BETWEEN $start_time and $end_time and user_id='$id' order by io_time ASC";
		$res = $this->db->query($sql);
		return $res->result();
	}
	
	public function getUserActiveLeaves($uid,$start,$end){
		$sql = "SELECT * FROM `leaves` WHERE uid=$uid and status=1 and from_date BETWEEN $start and $end";
		//$sql = "SELECT * FROM `attendance` WHERE status=1 and io_time BETWEEN $start_time and $end_time and user_id='$id' order by io_time ASC";
		$res = $this->db->query($sql);
		return $res->result();
	}
	
	public function getUserOD($uid,$start,$end){
		$sql = "SELECT * FROM `assign_working` WHERE uid=$uid and status!=0 and date BETWEEN $start and $end";
		//$sql = "SELECT * FROM `attendance` WHERE status=1 and io_time BETWEEN $start_time and $end_time and user_id='$id' order by io_time ASC";
		$res = $this->db->query($sql);
		return $res->result();
	}
	public function getUserODbyID($uid){
		$sql = "SELECT * FROM `assign_working` WHERE uid=$uid  and status!=0 ";
		//$sql = "SELECT * FROM `attendance` WHERE status=1 and io_time BETWEEN $start_time and $end_time and user_id='$id' order by io_time ASC";
		$res = $this->db->query($sql);
		return $res->result();
	}
		public function getUserOTbyID($uid){
		$sql = "SELECT * FROM `assign_working` WHERE uid=$uid  and type=1 and status!=0 ";
		//$sql = "SELECT * FROM `attendance` WHERE status=1 and io_time BETWEEN $start_time and $end_time and user_id='$id' order by io_time ASC";
		$res = $this->db->query($sql);
		return $res->result();
	}
		public function getUserbywfhbyID($uid){
		$sql = "SELECT * FROM `assign_working` WHERE uid=$uid and type=0 and status!=0 ";
		//$sql = "SELECT * FROM `attendance` WHERE status=1 and io_time BETWEEN $start_time and $end_time and user_id='$id' order by io_time ASC";
		$res = $this->db->query($sql);
		return $res->result();
	}
	
	
	public function getUserAccess($start_time,$end_time,$loginid,$uid){
		$sql = "SELECT * FROM `attendance` WHERE status=1 and io_time BETWEEN $start_time and $end_time and bussiness_id='$loginid' and user_id='$uid' and manual=4 order by io_time ASC ";
		$res = $this->db->query($sql);
		return $res->result();
		
	}
	public function getEmpLeaves($uid){
		$sql = "SELECT * FROM `leaves` WHERE uid=$uid and status!=0 order by id desc";
		$res = $this->db->query($sql);
		return $res->result();
	}
	public function getCompanyById($id){
		return $this->db->query("SELECT * FROM login WHERE id = '$id'")->row_array();
	}
	
	public function getEmpSLeaves($uid){
		$sql = "SELECT * FROM `Sleaves` WHERE uid=$uid and status!=0 order by id desc";
		$res = $this->db->query($sql);
		return $res->result();
	}
	public function getSLeaveByBusinessId($id){
		return $this->db->query("SELECT * FROM Sleaves WHERE bid = '$id' ")->result();

	}
	
		public function statusaproveSleave($id){
		$val = $this->db->query("UPDATE Sleaves SET status = 1 WHERE id = '$id'");
		return $val;
	}
	public function statusrejectSleave($id){
		$val = $this->db->query("UPDATE Sleaves SET status = 3 WHERE id = '$id'");
		return $val;
	}
	
	public function getMaxeid($buid){
		return $this->db->query("SELECT MAX(mobile) AS mobile FROM login WHERE company=$buid ")->result();
	}
	public function getMaxdevid($buid){
		return $this->db->query("SELECT MAX(bio_id) AS bio_id FROM login WHERE company=$buid ")->result();
	}
	public function getMaxempcode($buid){
		return $this->db->query("SELECT MAX(emp_code) AS emp_code FROM login WHERE company=$buid ")->result();
	}
	public function updateFromLDate2($id,$from_date){
		$sql = "UPDATE salary_report SET pay_mode='$from_date' WHERE id='$id'";
		$res = $this->db->query($sql);
		return $res;
	}
	public function getUserAccess2($start_time,$end_time,$uid){
		$sql = "SELECT * FROM `attendance` WHERE status=1 and io_time BETWEEN $start_time and $end_time and user_id='$uid' and manual=4 order by io_time ASC ";
		$res = $this->db->query($sql);
		return $res->result();
		
	}
	public function getUseractivityfilter($bid,$empId,$start_time,$end_time){
		$sql = "SELECT * from activity where bid='$bid'and uid='$empId'and date_time BETWEEN $start_time and $end_time order by date_time desc LIMIT 200";
		$res = $this->db->query($sql);
		return $res->result();
	}
	public function getactEmpLeaves($uid){
		$sql = "SELECT * FROM `leaves` WHERE uid=$uid and status=1 order by id desc";
		$res = $this->db->query($sql);
		return $res->result();
	}
	public function getUseractivityfilterP($bid,$start_time,$end_time){
		$sql = "SELECT * from activity where bid='$bid'and date_time BETWEEN $start_time and $end_time order by date_time desc LIMIT 200";
		$res = $this->db->query($sql);
		return $res->result();
	}
	
	public function getempassignworking($id){
		return $this->db->query("SELECT * FROM assign_working WHERE uid = '$id' and status!=0 order by date desc ")->result();
		
	}
	public function verifyworking($id){
        $val = $this->db->query("UPDATE assign_working SET status = 1 WHERE id = '$id'");
        return $val;
    }
	public function cancelworking($id){
        $val = $this->db->query("UPDATE assign_working SET status = 3 WHERE id = '$id'");
        return $val;
    }
	
	public function getshiftrotation($id){
		return $this->db->query("SELECT * FROM shift_roster WHERE uid = '$id' and status!=0 order by from_date desc ")->result();
		
	}
	
	
	
	public function getAssignedShiftList($uid,$new_start_time){
		return $this->db->query("SELECT * FROM shift_roster WHERE uid = '$uid' and status!=0 and $new_start_time BETWEEN from_date and to_date ")->result();
	}
	
	
	
	public function delete_shift_rost($id){
        $val = $this->db->query("UPDATE shift_roster SET status = 0 WHERE id = '$id'");
        return $val;
    }


	public function updateEmployeeDocuments($empId, $data)
	{
		$this->db->where('emp_id', $empId);
		$exists = $this->db->get('employee_documents')->row();

		if ($exists) {
			$this->db->where('emp_id', $empId)
					->update('employee_documents', $data);
		} else {
			$data['emp_id'] = $empId;
			$this->db->insert('employee_documents', $data);
		}
	}
	public function getNameByEmployeeId($id){
		return $this->db->query("SELECT * FROM login LEFT JOIN employee_documents on login.id = employee_documents.emp_id WHERE login.id = '$id' ")->result();
	}




	public function getSallaryReportnew($postData = '', $limit = null, $offset = null, $search = null)

	{
		/* ================= BASIC SETUP ================= */
		if ($this->session->userdata()['type'] == 'P') {
			$loginID = $this->session->userdata('empCompany');
			$role = $this->web->getRollbyid($this->session->userdata('login_id'), $loginID);
		} else {
			$loginID = $this->session->userdata('login_id');
		}

		/* ================= DATE RANGE ================= */
		if (!empty($postData)) {
			$yearName  = date('Y', strtotime($postData['date_from']));
			$monthName = date('m', strtotime($postData['date_from']));
		} else {
			$yearName  = date('Y');
			$monthName = date('m');
		}

		$monthKey = $yearName . '-' . $monthName;
		/* ================= EMPLOYEE LIST (ONE QUERY) ================= */
		$this->db->select('
		ur.*, 
		l.name AS empName,
		l.mobile AS empMobile,
		l.emp_code,
		l.designation AS empDesignation,
		l.business_group,
		l.department,
		l.section,
		l.id AS emp_id
	');
	$this->db->from('user_request ur');
	$this->db->join('login l', 'l.id = ur.user_id', 'LEFT');
	$this->db->where('ur.business_id', $loginID);

	if (!empty($search)) {
		$this->db->group_start();
		$this->db->like('l.name', $search);
		$this->db->or_like('l.emp_code', $search);
		$this->db->or_like('l.mobile', $search);
		$this->db->group_end();
	}


	$this->db->order_by('l.emp_code', 'ASC');

	if ($limit !== null) {
		$this->db->limit($limit, $offset);
	}

	$empList = $this->db->get()->result();

    if (empty($empList)) return [];

    /* ================= ROLE FILTER (NO DB CALLS) ================= */
    if ($this->session->userdata()['type'] == 'P' && $role[0]->type != 1) {
        $departments = explode(",", $role[0]->department);
        $sections    = explode(",", $role[0]->section);
        $teams       = explode(",", $role[0]->team);

        foreach ($empList as $k => $e) {
            if (
                !in_array($e->department, $departments) &&
                !in_array($e->section, $sections) &&
                !in_array($e->user_id, $teams)
            ) {
                unset($empList[$k]);
            }
        }
    }

	

    if (empty($empList)) return [];

    $empIds = array_column($empList, 'user_id');
	

    /* ================= BULK CTC ================= */
    $ctcRaw = $this->db
        ->where('business_id', $loginID)
        ->where_in('user_id', $empIds)
        ->order_by('date', 'DESC')
        ->get('user_ctc')
        ->result_array();

    $ctcMap = [];
    foreach ($ctcRaw as $c) {
        $ctcMap[$c['user_id']] = $c;
    }
	

    /* ================= BULK PAYROLL (ADD / DEDUCT / PAID) ================= */
    $payrollRaw = $this->db->select('user_id, payroll_master_id, SUM(amount) amount')
        ->where('business_id', $loginID)
        ->where_in('user_id', $empIds)
        ->where('YEAR(date)', $yearName)
        ->where('MONTH(date)', $monthName)
        ->group_by('user_id, payroll_master_id')
        ->get('payroll_history')
        ->result_array();

    $payrollMap = [];
    foreach ($payrollRaw as $p) {
        $payrollMap[$p['user_id']][$p['payroll_master_id']] = $p['amount'];
    }


	

    /* ================= BULK SALARY REPORT ================= */
    $salaryReportRaw = $this->db
        ->where('bid', $loginID)
        ->where('YEAR(date_time)', $yearName)
        ->where('MONTH(date_time)', $monthName)
        ->where_in('uid', $empIds)
        ->get('salary_report')
        ->result_array();

    $salaryReportMap = [];
    foreach ($salaryReportRaw as $s) {
        $salaryReportMap[$s['uid']] = $s;
    }
    /* ================= FINAL CALCULATION LOOP ================= */
    // foreach ($empList as $k => $emp) {

    //     $ctc = $ctcMap[$emp->user_id] ?? [];

    //     $basic     = $ctc['basic_value'] ?? 0;
    //     $totalCTC = $ctc['total_ctc_amount'] ?? 0;

    //     $pfDed  = $ctc['pf_amount'] ?? 0;
    //     $esiDed = $ctc['esi_amount'] ?? 0;
    //     $othDed = $ctc['other_amount'] ?? 0;

    //     $salaryDeduction = $pfDed + $esiDed + $othDed;

    //     $addition  = $payrollMap[$emp->user_id][1] ?? 0;
    //     $deduction = $payrollMap[$emp->user_id][2] ?? 0;
    //     $paid      = $payrollMap[$emp->user_id][3] ?? 0;

    //     $emp->basicCtc        = $basic;
    //     $emp->additionAmount  = $addition;
    //     $emp->deductionAmount = $deduction + $paid;
    //     $emp->ctc             = $totalCTC + $salaryDeduction;

    //     $maxDays = date("t", strtotime("$yearName-$monthName-01"));
    //     $perDay  = $maxDays ? ($emp->ctc / $maxDays) : 0;

    //     $sr = $salaryReportMap[$emp->user_id] ?? [];

    //     $nwd =
    //         ($sr['present'] ?? 0) +
    //         (($sr['half_day'] ?? 0) / 2) +
    //         ($sr['week_off'] ?? 0) +
    //         ($sr['holiday'] ?? 0) +
    //         ($sr['leaves'] ?? 0) +
    //         ($sr['short_leave'] ?? 0) +
    //         ($sr['ed'] ?? 0);

    //     $payable = round($perDay * $nwd);

    //     $pf = ($ctc['pf_type'] ?? '') == 'Manual'
    //         ? ($ctc['pf_value'] ?? 0)
    //         : round($payable * (($ctc['pf_value'] ?? 0) / 100));

    //     $esi = ($ctc['esi_type'] ?? '') == 'Manual'
    //         ? ($ctc['esi_value'] ?? 0)
    //         : round($payable * (($ctc['esi_value'] ?? 0) / 100));

    //     $emp->pf = $pf;
    //     $emp->esi = $esi;

    //     $emp->total       = $payable;
    //     $emp->netPayable  = round(($payable + $addition) - $deduction - $paid - $pf - $esi);
    // }

	foreach ($empList as $k => $emp) {

		$ctc = $ctcMap[$emp->user_id] ?? [];
	
		$basic    = (float) ($ctc['basic_value'] ?? 0);
		$totalCTC = (float) ($ctc['total_ctc_amount'] ?? 0);
	
		$pfDed  = (float) ($ctc['pf_amount'] ?? 0);
		$esiDed = (float) ($ctc['esi_amount'] ?? 0);
		$othDed = (float) ($ctc['other_amount'] ?? 0);
	
		$salaryDeduction = $pfDed + $esiDed + $othDed;
	
		$addition  = (float) ($payrollMap[$emp->user_id][1] ?? 0);
		$deduction = (float) ($payrollMap[$emp->user_id][2] ?? 0);
		$paid      = (float) ($payrollMap[$emp->user_id][3] ?? 0);
	
		$emp->basicCtc        = $basic;
		$emp->additionAmount  = $addition;
		$emp->deductionAmount = $deduction + $paid;
		$emp->ctc             = $totalCTC + $salaryDeduction;
	
		$maxDays = (int) date("t", strtotime("$yearName-$monthName-01"));
		$perDay  = $maxDays > 0 ? ($emp->ctc / $maxDays) : 0;
	
		$sr = $salaryReportMap[$emp->user_id] ?? [];
	
		$nwd =
			(float) ($sr['present'] ?? 0) +
			((float) ($sr['half_day'] ?? 0) / 2) +
			(float) ($sr['week_off'] ?? 0) +
			(float) ($sr['holiday'] ?? 0) +
			(float) ($sr['leaves'] ?? 0) +
			(float) ($sr['short_leave'] ?? 0) +
			(float) ($sr['ed'] ?? 0);
	
		$payable = round($perDay * $nwd);
	
		$pfValue  = (float) ($ctc['pf_value'] ?? 0);
		$esiValue = (float) ($ctc['esi_value'] ?? 0);
	
		$pf = ($ctc['pf_type'] ?? '') === 'Manual'
			? $pfValue
			: round($payable * ($pfValue / 100));
	
		$esi = ($ctc['esi_type'] ?? '') === 'Manual'
			? $esiValue
			: round($payable * ($esiValue / 100));
	
		$emp->pf  = $pf;
		$emp->esi = $esi;
	
		$emp->total = $payable;
	
		$emp->netPayable = round(
			($payable + $addition)
			- $deduction
			- $paid
			- $pf
			- $esi
		);
	}
	

    return $empList;
}

// public function countSalaryEmployees($search = null)
// {
//     if ($this->session->userdata()['type'] == 'P') {
//         $loginID = $this->session->userdata('empCompany');
//     } else {
//         $loginID = $this->session->userdata('login_id');
//     }

//     return $this->db
//         ->where('business_id', $loginID)
//         ->count_all_results('user_request');
// }


public function countSalaryEmployees($search = null)
{
    $loginID = $this->session->userdata('login_id');

    $this->db->from('user_request ur');
    $this->db->join('login l', 'l.id = ur.user_id', 'LEFT');
    $this->db->where('ur.business_id', $loginID);

    if (!empty($search)) {
        $this->db->group_start();
        $this->db->like('l.name', $search);
        $this->db->or_like('l.emp_code', $search);
        $this->db->or_like('l.mobile', $search);
        $this->db->group_end();
    }

    return $this->db->count_all_results();
}

public function getSalaryBasic($empId, $month, $type = null, $name = null, $sum = false)
{
    $this->db->from('salary_basic')
             ->join('ctc_head','ctc_head.id=salary_basic.header_id','left')
             ->join('salary','salary.id=salary_basic.sid','left')
             ->where('salary.uid', $empId)
             ->where("DATE_FORMAT(salary.date,'%Y-%m')", $month);

    if ($type) {
        $this->db->where('ctc_head.type', $type);
    }

    if ($name) {
        $this->db->where('ctc_head.name', $name);
    }

    if ($sum) {
        $this->db->select_sum('salary_basic.amount', 'total');
        return $this->db->get()->row()->total ?? 0;
    }

    $this->db->select('salary_basic.amount, salary_basic.header_type, salary_basic.header_value');
    return $this->db->get()->row();
}

private function getPayrollSum($empId, $month, $masterIds, $extraWhere = [])
{
    $this->db->select_sum('payroll_history.amount', 'total')
             ->from('payroll_history')
             ->join('payroll_master','payroll_master.id=payroll_history.payroll_master_id')
             ->where_in('payroll_master_id', (array)$masterIds)
             ->where('payroll_history.status', 1)
             ->where('user_id', $empId);

    foreach ($extraWhere as $key => $value) {
        $this->db->where($key, $value);
    }

    if (strpos($month, '<') !== false || strpos($month, '<=') !== false) {
        $this->db->where("DATE_FORMAT(payroll_history.pay_date,'%Y-%m') ".$month);
    } else {
        $this->db->where("DATE_FORMAT(payroll_history.pay_date,'%Y-%m')", $month);
    }

    return $this->db->get()->row()->total ?? 0;
}






}
?>
