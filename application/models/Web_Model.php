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
	
	
	
	
	
	
	public function getUserAttendanceReportByDate($start_time,$end_time,$uid,$bid,$verified){
		$sql = "SELECT * FROM `attendance` WHERE status=1 and io_time BETWEEN $start_time and $end_time and user_id='$uid' and bussiness_id='$bid' and verified='$verified' and manual!='2' and mode!='Log' order by io_time DESC";
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

public function getCompanyUsers($id){
		$sql = "SELECT user_request.user_id,user_request.doj,user_request.left_date,user_request.rule_id,user_request.hostel,(select name from login WHERE login.id = user_request.user_id) as name,(select image from login WHERE login.id = user_request.user_id) as image,(select business_group from login WHERE login.id = user_request.user_id) as business_group,user_request.user_status,(select login.designation from login WHERE login.id = user_request.user_id) as designation,(select login.m_id from login WHERE login.id = user_request.user_id) as mid,(select login.emp_code from login WHERE login.id = user_request.user_id) as emp_code,(select login.section from login WHERE login.id = user_request.user_id) as section,(select login.department from login WHERE login.id = user_request.user_id) as department FROM `user_request` WHERE user_request.business_id='$id'";
		$res = $this->db->query($sql);
		return $res->result();
	}
	


public function getallclassbyid($bid){
	return $this->db->query("SELECT * FROM class WHERE bid='$bid' and status=1 order by id DESC ")->result();
}

public function getSchoolStudentList($id){
	return $this->db->query("SELECT * FROM student WHERE bid = '$id' and left_date ='' and status='1' order by doj")->result();
}
public function getEXSchoolStudentList($id){
	return $this->db->query("SELECT * FROM student WHERE bid = '$id' and left_date !='' and status='1' order by doj")->result();
}	

public function getclassById($id){
	return $this->db->query("SELECT * FROM class WHERE id='$id' ")->result();
}

public function getallperiodbyid($bid){
	return $this->db->query("SELECT * FROM S_period WHERE bid='$bid' and status=1 order by STR_TO_DATE(start_time, '%h:%i %p') ASC")->result();
}




function import_school_student(){	
	include_once('excel_reader2.php');
	include_once('SpreadsheetReader.php');
	
	 
	  $loginID=$this->web->session->userdata('login_id');

	

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
				$Enroll_id = isset($Row[0]) ? $Row[0] : '';
				$Stu_code = isset($Row[1]) ? $Row[1] : '';
				$Device_id = isset($Row[2]) ? $Row[2] : '';
				$Card = isset($Row[3]) ? $Row[3] : '';
				$Name = isset($Row[4]) ? $Row[4] : '';
				$Roll_no = isset($Row[5]) ? $Row[5] : '';
				$Class = isset($Row[7]) ? $Row[7] : '';
				$Department = isset($Row[9]) ? $Row[9] : '';
				$Batch = isset($Row[11]) ? $Row[11] : '';
				$Section = isset($Row[13]) ? $Row[13] : '';
			
				$Semester = isset($Row[14]) ? $Row[14] : '';
				$Session = isset($Row[15]) ? $Row[15] : '';
			
				$Address = isset($Row[16]) ? $Row[16] : '';
				$Email = isset($Row[17]) ? $Row[17] : '';
				$Gender   = isset($Row[18]) ? $Row[18] : '';
				$Blood_group = isset($Row[19]) ? $Row[19] : '';
				$dob = isset($Row[20]) ? $Row[20] : '';
				$ParentName = isset($Row[21]) ? $Row[21] : '';
				$ParentMb = isset($Row[22]) ? $Row[22] : '';
				$ParentRel = isset($Row[23]) ? $Row[23] : '';
				$doj = isset($Row[24]) ? $Row[24] : '';
			  
				
				$im='upload/nextpng.png';

			
				if(isset($Enroll_id)){
				$umobile=$this->web->getexistingstudent($Enroll_id,$loginID);
				if (!empty($umobile)){
					$student_id=$umobile[0]->id;
				//$userCmp = $this->web->getActiveUserCompany($umobile[0]->id,$loginID);
				
				if(isset($student_id)){
					$datau = array(
						'student_code' => $Stu_code,
					   'bio_id'  => $Device_id,
						'rfid'  => $Card,
						'name'  => $Name,
						'class_id'  => $Class,
						'roll_no'  => $Roll_no,
						
						'section'  => $Section,
						'batch'  => $Batch,
						'semester'  => $Semester,
						'session'  => $Session,
						'department'  => $Department,
						
						
						'address'  => $Address,
						'email'  => $Email,
					  'gender'  => $Gender,
						'doj'=> strtotime($doj),
						'blood_group' => $Blood_group,
						'dob' => $dob,
						'parent_name' => $ParentName,
						'parent_mobile'=>$ParentMb,
					  'parent_relation' => $ParentRel
					  
						);
						 $this->db->where('id',$student_id);
				$update= $this->db->update('student',$datau);
			
				}
			
				
				} else {
				
			
				$data = array(
				        'bid' => $loginID,
					    'enroll_id' => $Enroll_id,
					   'student_code' => $Stu_code,
					   'bio_id'  => $Device_id,
						'rfid'  => $Card,
						'name'  => $Name,
						'class_id'  => $Class,
						'roll_no'  => $Roll_no,
							'section'  => $Section,
						'batch'  => $Batch,
						'semester'  => $Semester,
						'session'  => $Session,
						'department'  => $Department,
							'email'  => $Email,
						
						'address'  => $Address,
					  'gender'  => $Gender,
						'doj'=> strtotime($doj),
						'blood_group' => $Blood_group,
						'dob' => $dob,
						'parent_name' => $ParentName,
						'parent_mobile'=>$ParentMb,
					  'parent_relation' => $ParentRel,
					  'date_time' =>time()
						);
						
				$this->db->insert('student', $data);
				//$id = $this->db->insert_id();
				


		 }
				}
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
		redirect('Students_list');
	}
	else
	{
		echo "<script>alert('Please select valid excel file!.') </script>";  	
	 /*  echo "<script language=\"javascript\">window.open('https://app.shinerweb.com/index.php/import_excel/', '_self');  </script>";*/
	}
	
}



public function getexistingstudent($id,$bid){
	return $this->db->query("SELECT * FROM student WHERE enroll_id = '$id' and bid='$bid' and status =1")->result();

}

 public function getclassnamebyid($id){
        return $this->db->query("SELECT * FROM class WHERE id = '$id' ")->result();
		// return $this->db->query("SELECT * FROM login WHERE id = '$id'")->row_array();
    }
    
     public function getstudentnamebyid($id){
        return $this->db->query("SELECT * FROM student WHERE id = '$id' ")->result();
		// return $this->db->query("SELECT * FROM login WHERE id = '$id'")->row_array();
    }

public function delete_class($id){
        $val = $this->db->query("UPDATE class SET status = 0 WHERE id = '$id'");
        return $val;
    }
 
 // new au
 public function getNightBusinessGroup($id){
	return $this->db->query("SELECT * FROM business_groups WHERE business_id = '$id' and status=1 and night=1")->result();
	// return $this->db->query("SELECT * FROM login WHERE id = '$id'")->row_array();
}

public function getStudentByBioId($uid,$bid){
	$sql = "SELECT * FROM `student` WHERE bio_id='$uid' and bid='$bid'and status=1";
	$res = $this->db->query($sql);
	return $res->row_array();
}
public function insertstudentbioAttendance($data){
	$res = $this->db->insert("student_attendance",$data);
	return $res;
}
 

function import_log()
	{	
		include_once('excel_reader2.php');
		include_once('SpreadsheetReader.php');
		//$mimes = ['application/vnd.ms-excel','text/xls','text/xlsx','application/vnd.oasis.opendocument.spreadsheet','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
		
		//echo $_FILES["excel_file"]["type"];
		//exit;
		 if($this->session->userdata()['type']=='P'){
      
     	$buid = $this->session->userdata('empCompany');
      
  
    } else {
      	$buid=$this->web->session->userdata('login_id');
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
					$Name = isset($Row[1]) ? $Row[1] : '';
					$datetimes = isset($Row[2]) ? $Row[2] : '';
					$new_time=strtotime($datetimes);
					
					$getUserByBioId = $this->app->getUserByBioId($Emp_code,$buid);
					
			 if(isset($getUserByBioId)){
			      $userCmp = $this->app->getUserCompany($getUserByBioId['id']);
			      if( !empty($userCmp['business_id']) && $userCmp['business_id']==$buid){
                 $checkOffline = $this->app->checkIoTime($getUserByBioId['id'],$buid,$new_time);
                   if(empty($checkOffline)){
                       $start_time = strtotime(date("d-m-Y 00:00:00",$new_time));
                   $end_time = strtotime(date("d-m-Y 23:59:59",$new_time));
                   $offline_at = $this->app->checkOfflineAt($getUserByBioId['id'],$buid,$start_time,$end_time);
                    $mode = "in";
                  if(!empty($offline_at)){
                     if($offline_at['mode']=="in"){
                      $mode = "out";
                      }else{
                       $mode = "in";
                      }
                  }
                 
					 $insertData2 = array(
                      'bussiness_id'=>$buid,
                      'user_id'=>$getUserByBioId['id'],
                      'mode'=>$mode,
                      'device'=>"",
                      'manual'=>"4",
                      'io_time'=>$new_time,
                      'date'=>time()
                    );
                   
                 
		$res = $this->db->insert('attendance', $insertData2);
					
			
                   }			
			 }
			 }
				    
				}
				
				$row_count++;
				
			  }
			}
			$row_counts=$row_count+1;
			
			/* echo "<script>alert('".$row_counts."".$row_count."".$i." Record(s) has been inserted! Thank you.') </script>";  	
		  echo "<script language=\"javascript\">window.open('https://app.shinerweb.com/index.php/import_excel/', '_self');  </script>";*/
			//$this->load->view('attendance/employees');
			$this->session->set_flashdata('msg',' New Data Added!');
				redirect('device_access_att');
		//	redirect('employees');
		}
		else
		{
			echo "<script>alert('Please select valid excel file!.') </script>";  	
		 /*  echo "<script language=\"javascript\">window.open('https://app.shinerweb.com/index.php/import_excel/', '_self');  </script>";*/
		}
		
	}


public function insertvisitorlog($data){
	$res = $this->db->insert("visitor_log",$data);
	return $res;
}
 



/*	public function getVisitorLogbyDate($start_time,$end_time){
		$sql = "SELECT * FROM `visitor_log` WHERE io_time BETWEEN $start_time and $end_time order by io_time DESC ";
		$res = $this->db->query($sql);
		return $res->result();
	}
	*/
		public function getvisitorlog(){
		$sql = "SELECT * FROM `visitor_log` where device_id='AYSF21074928' order by io_time DESC ";
		$res = $this->db->query($sql);
		return $res->result();
	}
	
	
	public function getVisitorLogbyBioid($start_time,$end_time,$loginid,$bio){
		$sql = "SELECT * FROM `visitor_log` WHERE  io_time BETWEEN $start_time and $end_time and device_id='$bio'  order by io_time DESC ";
		$res = $this->db->query($sql);
		return $res->result();
		// return $this->db->query("SELECT * FROM login WHERE id = '$id'")->row_array();
	}
	
		public function getVisitorLogbyDate($start_time,$end_time){
		$sql = "SELECT * FROM `visitor_log` WHERE  io_time BETWEEN $start_time and $end_time and  device_id='AYSF21074928' order by io_time DESC ";
		$res = $this->db->query($sql);
		return $res->result();
		// return $this->db->query("SELECT * FROM login WHERE id = '$id'")->row_array();
	}

public function getaddress($lat,$lng)
  {
     $url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($lat).','.trim($lng).'&sensor=false';
     $json = @file_get_contents($url);
     $data=json_decode($json);
     $status = $data->status;
     if($status=="OK")
     {
       return $data->results[0]->formatted_address;
     }
     else
     {
       return false;
     }
  }

 public function getdevicebyserialno($sn){
		$sql = "SELECT * FROM Business_bioid where deviceid='$sn' ";
		$res = $this->db->query($sql);
		return $res->result();
	}
 public function getAllLoginLimit($offset,$limit){
		return $this->db->query("SELECT * FROM login order by id limit $offset,$limit")->result();
	}
    
   public function getEventById($eventid){
		$sql = "SELECT * FROM event where id='$eventid' ";
		$res = $this->db->query($sql);
		return $res->result();
	} 
    
  	public function getStudentAttendanceReportByDate($start_time,$end_time,$uid,$bid){
		$sql = "SELECT * FROM `student_attendance` WHERE status=1 and time BETWEEN $start_time and $end_time and student_id='$uid' and bid='$bid' order by time DESC";
		$res = $this->db->query($sql);
		return $res->result();
	}
  
  public function getSchoolTeachersList($id){
		return $this->db->query("SELECT * FROM class_teacher WHERE bid = '$id' and status='1' order by update_date")->result();
	}
	
    public function getall_S_sectionbyid($bid){
	return $this->db->query("SELECT * FROM S_section WHERE bid='$bid' and status=1 order by id DESC ")->result();
}

public function delete_S_section($id){
        $val = $this->db->query("UPDATE S_section SET status = 0 WHERE id = '$id'");
        return $val;
    }

public function delete_period($id){
        $val = $this->db->query("UPDATE S_period SET status = 0 WHERE id = '$id'");
        return $val;
    }
 

public function getallsubjectbyid($bid){
	return $this->db->query("SELECT * FROM subject WHERE bid='$bid' and status=1 order by id DESC ")->result();
}
public function getallsubjectbybranchid($branch_id){
	return $this->db->query("SELECT * FROM subject WHERE dep_id='$branch_id' and status=1 order by id DESC ")->result();
}
public function delete_subject($id){
        $val = $this->db->query("UPDATE subject SET status = 0 WHERE id = '$id'");
        return $val;
    }
    
    public function getSchoolStudentListbyclass($id,$sid){
	return $this->db->query("SELECT * FROM student WHERE bid = '$id'and class_id= '$sid' and left_date ='' and status='1' order by roll_no")->result();
}

public function getsectionById($id){
	return $this->db->query("SELECT * FROM S_section WHERE id='$id' ")->result();
}

    
 public function getallperiodbyclassid($bid,$class){
	return $this->db->query("SELECT * FROM S_period WHERE bid='$bid' and class_id='$class' and status=1 order by id DESC ")->result();
}   
   
   
  public function getperiodnamebyid($id){
        return $this->db->query("SELECT * FROM S_period WHERE id = '$id' ")->result();
		// return $this->db->query("SELECT * FROM login WHERE id = '$id'")->row_array();
    } 
   
   public function getall_S_Session($bid){
	return $this->db->query("SELECT * FROM S_Session WHERE bid='$bid' and status=1 order by id DESC ")->result();
}
 
 	public function getSessionByDeptId($id,$bid){
		return $this->db->query("SELECT * FROM S_Session WHERE dep_id='$id' and bid=$bid and status=1")->result();
	}
	
	public function getSessionById($id){
        return $this->db->query("SELECT * FROM S_Session WHERE id = '$id' ")->result();
		// return $this->db->query("SELECT * FROM login WHERE id = '$id'")->row_array();
    }
   
    
  
    
    public function getSectionBySessionId($id){
		return $this->db->query("SELECT * FROM S_section WHERE session_id='$id' and status=1")->result();
	}
	
    
  public function getSchoolStudentListbysection($id,$dept,$session,$semester){
	return $this->db->query("SELECT * FROM student WHERE bid = '$id' and department= '$dept' and batch= '$session' and semester= '$semester' and left_date ='' and status='1' order by roll_no")->result();
}  

public function getallperiodbysectionid($bid,$section){
	return $this->db->query("SELECT * FROM S_period WHERE bid='$bid' and status=1 order by STR_TO_DATE(start_time, '%h:%i %p') ASC")->result();
} 

public function getbatchById($id){
	return $this->db->query("SELECT * FROM S_Session WHERE id='$id' ")->result();
}

	
// In your model



//new functions for get period by subject 
public function getperiodTime($subject, $day) {
    $this->db->select('p.id, p.bid, p.name, tt.subject, tt.teacher, p.start_time, p.end_time, p.status, p.date_time');
    $this->db->from('s_period p');
    $this->db->join('time_table tt', 'tt.period = p.id', 'left');
    $this->db->where('tt.subject', $subject);
    $this->db->where('tt.days', $day);
    return $this->db->get()->row();
}

/// new model by Nursid 

public function delete_S_session($id, $status = 0){
	$val = $this->db->query("UPDATE S_Session SET status = '$status' WHERE id = '$id'");
	return $val;
}

public function getSemesterById($id){
	return $this->db->query("SELECT * FROM S_Semester WHERE id = '$id' ")->result();
}

public function delete_semester($id, $status = 0){
	$val = $this->db->query("UPDATE S_Semester SET status = '$status' WHERE id = '$id'");
	return $val;
}

public function getallSemesters($bid){
	return $this->db->query("SELECT * FROM S_Semester WHERE bid='$bid' and status=1 order by id DESC ")->result();
}

public function getBatchesByDeptId($id,$bid){
	return $this->db->query("SELECT * FROM S_Session WHERE FIND_IN_SET('$id', dep_id) and bid=$bid and status=1")->result();
}

public function getSectionBranchSemesters($section_id) {
    // return $this->db->where('section_id', $section_id)
    //                 ->get('section_semesters')
    //                 ->result();
					return $this->db->query("SELECT * FROM section_semesters WHERE section_id='$section_id' ")->result();
}

public function getBranchNameById($branch_id) {
    $row = $this->db->select('name')
                    ->where('id', $branch_id)
                    ->get('branches') // replace with your actual branch table name
                    ->row();
    return $row ? $row->name : '';
}

public function getSemesterNameById($semester_id) {
    $row = $this->db->query("SELECT semestar_name FROM S_Semester WHERE id = '$semester_id'")->row();
    return $row ? $row->semestar_name : '';
}


public function getNameByUserId($id){
	return $this->db->query("SELECT name FROM login WHERE id = '$id' ")->row()->name;
}

public function getsubjectnamebyid($id){
	return $this->db->query("SELECT * FROM subject WHERE id = '$id' ")->row();
	// return $this->db->query("SELECT * FROM login WHERE id = '$id'")->row_array();
}



public function getall_timetable($bid){
	return $this->db->query("SELECT * FROM time_table_name WHERE bid='$bid' and deleted=0 order by id DESC ")->result();
}

public function get_timetable_by_id($id){
	return $this->db->query("SELECT * FROM time_table_name WHERE id='$id' AND deleted=0")->row();
}

public function get_timetable_entries($timetable_id){
	return $this->db->query("SELECT * FROM time_table WHERE timetable_id='$timetable_id' ORDER BY days, period")->result();
}

public function get_timetable_entry($entry_id){
	return $this->db->query("SELECT * FROM time_table WHERE id='$entry_id'")->row();
}

public function get_all_teachers($bid){
	return $this->db->query("SELECT id, name FROM login WHERE company='$bid'  AND deleted=1 ORDER BY name")->result();
}

public function get_all_subjects($bid){
	return $this->db->query("SELECT id, name FROM subject WHERE bid='$bid' AND status=0 ORDER BY name")->result();
}


// 13-04-2025 

public function getTotalBranches($id) {
	$this->db->where('bid', $id);
	$this->db->where('status', 1);
	return $this->db->count_all_results('department_section');
}
public function getTotalStudents($bid) {
    $this->db->where('bid', $bid);
    $this->db->where('status', 1);
    return $this->db->count_all_results('student');
}
public function getTotalStaff($bid) {
    $this->db->where('company', $bid);
    $this->db->where('deleted', 1);
    return $this->db->count_all_results('login');
}

public function getTotalSubjects($bid) {
    $this->db->where('bid', $bid);
    $this->db->where('status', 1);
    return $this->db->count_all_results('subject');
}


// 13-04-2025 by nursid  new


	public function getSectionsByBranchAndSemester($branchId, $semesterId) {
		$this->db->select('s_section.id, s_section.name');
		$this->db->from('s_section');
		$this->db->join('section_semesters', 's_section.id = section_semesters.section_id');
		$this->db->where('section_semesters.branch_id', $branchId);
		$this->db->where('section_semesters.semester_id', $semesterId);
		$this->db->where('s_section.status', 1); // Assuming you only want active sections
		$query = $this->db->get();

		return $query->result();
	}

	public function getAllSubjectsById_new($bid, $dept){
		$this->db->select('*');
		$this->db->from('subject');
		$this->db->where('bid', $bid);
		$this->db->where('dep_id', $dept);
		$this->db->where('status', 1);
		$this->db->order_by('id', 'DESC');
		$query = $this->db->get();
		return $query->result();
	}

	public function getSchoolStudentListbysection_new($id,$dept,$semester,$section){
		return $this->db->query("SELECT * FROM student WHERE bid = '$id' and department= '$dept' and semester= '$semester' and section= '$section' and left_date ='' and status='1' order by roll_no")->result();
	}  
	public function getSubjectByPeriodAndDay($period, $days) {
		$this->db->select('time_table.id, time_table.bid, time_table.days, time_table.period, time_table.subject, time_table.class_room, time_table.teacher, time_table.timetable_id, subject.name, subject.dep_id, subject.Subject_code, subject.status, subject.date_time');
		$this->db->from('time_table');
		$this->db->join('subject', 'subject.id = time_table.subject');
		$this->db->where('time_table.days', $days);
		$this->db->where('time_table.period', $period);
		$query = $this->db->get();
		return $query->row();
	}

public function getTeacherNameById($teacher_id, $bid) {
    $this->db->select('name');
    $this->db->from('login');
    $this->db->where('id', $teacher_id);
    $this->db->where('company', $bid);
    $query = $this->db->get();
    if ($query->num_rows() > 0) {
        return $query->row()->name;
    }
    return '';
}



public function getHolidayByBusinessId_new($buid, $i) {
    // Convert input timestamp to date string
    $inputDate = date('Y-m-d', $i);
    
    // Use query builder to prevent potential SQL errors
    $this->db->select('name');
    $this->db->from('holiday');
    $this->db->where('business_id', $buid);
    $this->db->where('DATE(FROM_UNIXTIME(date))', $inputDate);
    $this->db->where('status', 1);
    $query = $this->db->get();
    
	if ($query->num_rows() > 0) {
        return $query->row()->name;
    }
    return '';
}


public function getperiodTimeByteacher($period, $day, $teacher) {
    $this->db->select('p.id, p.bid, p.name, tt.subject, tt.teacher, p.start_time, p.end_time, p.status, p.date_time, tt.timetable_id, ttn.section, ss.name as section_name');
    $this->db->from('s_period p');
    $this->db->join('time_table tt', 'tt.period = p.id', 'left');
    $this->db->join('time_table_name ttn', 'tt.timetable_id = ttn.id', 'left');
    $this->db->join('s_section ss', 'ttn.section = ss.id', 'left');
    $this->db->where('p.id', $period);
    $this->db->where('tt.teacher', $teacher);
    $this->db->where('tt.days', $day);
    return $this->db->get()->row();
}

public function getAllAssignedClassesByTeacher($teacher) {
    $this->db->select('time_table.id, time_table.bid, time_table.days, time_table.period, time_table.subject, time_table.class_room, time_table.teacher, time_table.timetable_id, s_period.start_time, s_period.end_time, subject.name as subject_name, time_table_name.section, s_section.name as section_name, time_table_name.semester_id,
	time_table_name.dept ');
    $this->db->from('time_table');
    $this->db->join('s_period', 'time_table.period = s_period.id');
    $this->db->join('subject', 'time_table.subject = subject.id', 'left');
    $this->db->join('time_table_name', 'time_table.timetable_id = time_table_name.id', 'left');
    $this->db->join('s_section', 'time_table_name.section = s_section.id', 'left');
    $this->db->where('time_table.teacher', $teacher);
    return $this->db->get()->result();
}


public function getSchoolStudentListbysection_new_api($section){
	return $this->db->query("SELECT * FROM student WHERE section = '$section' and left_date = '' and status = '1' order by roll_no")->result();
}  


public function getAllPeriods($bid) {
    $this->db->select('*');
    $this->db->from('s_period');
    $this->db->where('bid', $bid);
    $this->db->where('status', 1);
    $query = $this->db->get();
    return $query->result();
}


public function getUserAttendanceReportByDate_new($start_time, $end_time, $uid, $bid, $verified) {
    // Use query builder to avoid null array issues and ensure correct parameter binding
    $this->db->from('attendance');
    $this->db->where('status', 1);
    $this->db->where("io_time BETWEEN $start_time AND $end_time");
    $this->db->where('user_id', $uid);
    $this->db->where('bussiness_id', $bid);
    $this->db->where('verified', $verified);
    $this->db->where('manual !=', 2);
    $this->db->where('mode !=', 'Log');
    $this->db->order_by('io_time', 'DESC');
    $query = $this->db->get();
    if ($query && $query->num_rows() > 0) {
        return $query->result();
    }
    return [];
}

//getSchoolTeachersList 


public function getSchoolTeachersList_with_login($id){
    $this->db->select('l.name, l.mobile');
    $this->db->from('class_teacher ct');
    $this->db->join('login l', 'ct.uid = l.id');
    $this->db->where('ct.bid', $id);
    $this->db->where('ct.status', '1');
    $this->db->order_by('ct.update_date');
    return $this->db->get()->result();
}

public function getClassRoomById($id){
	return $this->db->query("SELECT name FROM room_types WHERE id='$id'")->row();
}

}
?>
