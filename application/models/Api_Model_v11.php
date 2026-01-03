<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Api_Model_v11 extends CI_Model
{
	function __construct(){
        parent::__construct();
		$this->load->database();
	}
	public function checkMobile($mobile){
		return $this->db->where('mobile',$mobile)->where('deleted',0)->get('login')->row_array();
	}

	public function getMaxMid(){
		return $this->db->query("SELECT MAX(m_id) AS m_id FROM login")->row_array();
	}

	public function getMidByMobile($m){
		return $this->db->query("SELECT m_id AS m_id FROM login WHERE mobile = '$m' and deleted=0")->row_array();
	}

	public function getIdByMid($id){
	    $sql="SELECT id from login WHERE m_id ='$id'";
		return $this->db->query($sql)->row_array();
	}

	public function userdetails($id){
		return $this->db->where('id',$id)->get('login')->row_array();
	}

	public function userdetailsnew($id){
		return $this->db->where('id',$id)->get('login')->result();
	}

	public function offerdetails($id){
		//return $this->db->where('shopid',$id)->get('offer')->result();
		$sql="SELECT * FROM `offer` WHERE shopid='$id' and status='0'";
	   $query=$this->db->query($sql);
		return $query->result();
	}
	public function AddUser($data){
		return $this->db->insert('login',$data);
	}
	public function checkotp($mobile,$otp){
		$this->db->select('*');
		$this->db->where('mobile',$mobile);
		$this->db->where('otp',$otp);
		$this->db->where('deleted',0);
		$this->db->from('login');
		$get=$this->db->get();
		return $get->row_array();
	}

	public function registered($mobile){
		$this->db->select('*');
		$this->db->where('mobile',$mobile);
		$this->db->where('deleted',0);
		$this->db->from('login');
		$get=$this->db->get();
		return $get->row_array();
	}
	public function getGroups(){
		return $this->db->get('groups')->result();
	}

	public function getBussiness(){
		return $this->db->get('bussinesstype')->result();
	}
	public function getUserData($id){
		$this->db->select('*');
		$this->db->where('scanid',$id);
		$this->db->from('userqrdetails');
		$this->db->order_by('id','DESC');
		$this->db->limit('5');
		$get=$this->db->get();
		return $get->result();

	}
	public function getUserDetail($id){
		$this->db->select('*');
		$this->db->where('id',$id);
		$this->db->from('login');
		$this->db->limit('20');
		$get=$this->db->get();
		return $get->result();
	}
	public function getHistory($id){
// 		// return $this->db->where('scanid',$id)->get('userqrdetails')->result();
// 		$this->db->select('*');
// 		$this->db->where('scanid',$id);

// 		$this->db->from('userqrdetails');
// 		$get=$this->db->get();


	//	return $get->result();
$sqll="select * FROM `userqrdetails` WHERE scanid='$id' ORDER BY id DESC";
	$query=$this->db->query($sqll);
		return $query->result();
	}
	public function getConatctData($id){
		$this->db->select('*');
		$this->db->where('scanby',$id);

		$this->db->from('userqrdetails');
		$this->db->group_by('scanid','DESC');

		$get=$this->db->get();
		return $get->result();

	}

	public function getShopDetail($id){
		$this->db->select('*');

// 		$this->db->where('user_group','2');
		$this->db->where('id',$id);
		$this->db->from('login');

			$this->db->limit('20');
		$get=$this->db->get();
		return $get->result();
	}



	public function search($id,$from,$to){
	     $sql="SELECT * FROM userqrdetails WHERE scanid='$id' AND date between '$from' and '$to'";
		$query=$this->db->query($sql);
		return $query->result();
	}

	//

	public function getUserscan($id){
		$query=$this->db->query("SELECT * FROM `userqrdetails` WHERE scanby='$id' GROUP by scanid");
		return $query->result();
	}

	public function getUsersoffers($id){
		$query=$this->db->query("SELECT * FROM `offer` WHERE shopid='$id' and status='0'");
		return $query->result();
	}

	public function getBussinessname($id){
		 $query=$this->db->query("SELECT * FROM `bussinesstype` WHERE id='$id'");
		return $query->result();
	}

	public function userdetailscheck($userid,$mobileno){
		 $sql="SELECT * FROM `login` WHERE (mobile='$mobileno' OR id='$userid') and deleted=0";
		 $query=$this->db->query($sql);
		return $query->row_array();
	}
	public function usertypescheck($userid,$mobileno){
	 $sql="SELECT * FROM `login` WHERE  (id='$userid' or mobile='$mobileno') and deleted=0";
		 $query=$this->db->query($sql);
		return $query->row_array();
	}



	public function getassigneddept($userid){
		$sql="SELECT * FROM `assigned_department` WHERE  user_bussiness_id='$userid'";
		 $query=$this->db->query($sql);
		return $query->result();
	}

		public function getappointdept($userid){
		$sql="SELECT * FROM `appoint_setting` WHERE  bussiness_id='$userid'";
		 $query=$this->db->query($sql);
		return $query->result();
	}

	public function getdept($id){
		  $sql="SELECT * FROM `department` WHERE  id='$id'";
		 $query=$this->db->query($sql);
		return $query->row_array();
	}
	public function getdeptnew($id){
		  $sql="SELECT * FROM `department` WHERE  id='$id'";
		 $query=$this->db->query($sql);
		return $query->result();
	}

	public function getsubdept($id){
		 $sql="SELECT * FROM `department_sub` WHERE  department_id='$id'";
		 $query=$this->db->query($sql);
		return $query->result();
	}
	public function getsubdeptnew($id){
		 $sql="SELECT * FROM `department_sub` WHERE  id='$id'";
		 $query=$this->db->query($sql);
		return $query->row_array();
	}


	public function gettokendate($today,$depid){
		  $sql="SELECT * FROM `token` WHERE date='$today'  AND Dept_id='$depid'";
		 $query=$this->db->query($sql);
		return $query->result();
	}
	public function getMaxtoken($depid){
		  $sql="SELECT MAX(token) as token FROM `token` WHERE Dept_id='$depid'";
		 $query=$this->db->query($sql);
		return $query->row_array();
	}

	public function getlivetoken($depid){
		 $sql="SELECT MAX(token) as token FROM `token` WHERE Dept_id='$depid' AND status='1'";
		 $query=$this->db->query($sql);
		return $query->row_array();
	}

	public function gettoken($loginid,$today){
		    $sql="SELECT * FROM `token` WHERE userid='$loginid' AND date='$today'";
		 $query=$this->db->query($sql);
		return $query->result();
	}

	public function getBussinesstoken($loginid,$today){
		    $sql="SELECT * FROM `token` WHERE user_bussiness_id='$loginid' AND date='$today'";
		 $query=$this->db->query($sql);
		return $query->result();
	}

	//

	public function Qrimageupdate($i,$loginid){
		 $sql="UPDATE  login SET   qrimage = '$i' WHERE  id = '$loginid'";
		 $query=$this->db->query($sql);
		return $query->result();
	}

	public function shopuser($loginid){
		 $sql="Select * from userqrdetails where scanid='$loginid' GROUP BY scanby";
		 $query=$this->db->query($sql);
		return $query->result();
	}
		public function getCounter($loginid){
		 $sql="SELECT * FROM `counters` WHERE login='$loginid'";
		 $query=$this->db->query($sql);
		return $query->row_array();
	}
	public function getappointmenttime($loginid,$departmentid,$subdepartmentid){
		   $sql="SELECT * FROM `appoint_setting` WHERE bussiness_id='$loginid' and department='$departmentid' and subdepart='$subdepartmentid'";
		 $query=$this->db->query($sql);
		return $query->row_array();
	}
	public function getbookedtime($loginid,$departmentid,$subdepartmentid,$day){
		 $sql="SELECT * FROM `book_appointment` WHERE bussiness_id ='$loginid' and bookingdate='$day' and departmentid='$departmentid' and subdepartment='$subdepartmentid' and status='0'";
		 $query=$this->db->query($sql);
		return $query->result();
	}

	public function getappointmentdata(){
		 $sql="SELECT * FROM `appoint_setting` group by bussiness_id";
		 $query=$this->db->query($sql);
		return $query->result();
	}

	public function getappointsub($bussinessid,$depid){
		  $sql="SELECT * FROM `assigned_sdepartment` where user_business_id='$bussinessid' and depart_id='$depid'";
		 $query=$this->db->query($sql);
		return $query->result();
	}


	public function getappointsubss($bussinessid,$depid){
		   $sql="SELECT * FROM `appoint_setting` where bussiness_id='$bussinessid' and department='$depid'";
		 $query=$this->db->query($sql);
		return $query->result();
	}
	public function getAppointmenthistory($loginid){
		  $sql="SELECT * FROM `book_appointment` where user_id='$loginid' ORDER BY `id` DESC";
		 $query=$this->db->query($sql);
		return $query->result();
	}
	public function getAppointmentbussiness($loginid){
		  $sql="SELECT * FROM `book_appointment` where 	bussiness_id='$loginid' ORDER BY `id` DESC";
		 $query=$this->db->query($sql);
		return $query->result();
	}
	public function getbussnames($id){
		  $sql="SELECT id,name,mobile,image,address,user_group,image,mac,strength,m_id FROM `login` where id='$id'";
		 $query=$this->db->query($sql);
		return $query->row_array();
	}

	public function getappoitmentdate($bookingdate,$departmentid){
		  $sql="SELECT * FROM `book_appointment` WHERE bookingdate='$bookingdate'  AND departmentid='$departmentid'";
		 $query=$this->db->query($sql);
		return $query->result();
	}
		public function getappoitmentcancel($bookingdate, $departmentid,$subdepartmentid,$bookingtime){
		    $sql="SELECT * FROM `book_appointment` WHERE bookingdate='$bookingdate'  AND departmentid='$departmentid' AND booking_time='$bookingtime' AND subdepartment='$subdepartmentid'";
		 $query=$this->db->query($sql);
		return $query->row_array();
	}
	public function getMaxappoint($bookingdate,$departmentid){
		  $sql="SELECT MAX(appointmenttoken) as appointmenttoken FROM `book_appointment` WHERE 	bookingdate='$bookingdate ' and departmentid='$departmentid'";
		 $query=$this->db->query($sql);
		return $query->row_array();
	}
	public function getappointmentno(){
		 $sql="SELECT MAX(appointmenttoken) as  appointmenttoken from book_appointment";
		 $query=$this->db->query($sql);
		 return $query->row_array();
	}
	///

	// RITIK

	public function getUserAttendance($id,$start,$end){
		$res = $this->db->query("SELECT * FROM attendance WHERE user_id='$id' AND status='1' AND io_time >=$start AND io_time <$end and manual!='2' order by id DESC");
		return $res->result();
	}

	public function insertAttendance($data){
		$res = $this->db->insert("attendance",$data);
		return $res;
	}

	public function updateUserCompany($id,$bussinessid,$doj){
		$res = $this->db->query("UPDATE login SET company = '$bussinessid',doj='$doj' WHERE  id = '$id'");
		return $res;
	}

	public function getbyMid($mid){
		$sql="SELECT id,name,address,user_group FROM `login` where m_id='$mid'";
	   $query=$this->db->query($sql);
	  return $query->row_array();
  	}

  	public function userCmpStatus($userid,$businessid){
		$sql="SELECT user_status FROM `user_request` WHERE user_id='$userid' AND business_id='$businessid'";
	    $query=$this->db->query($sql);
		return $query->row_array();
	}

	public function addUserCmpStatus($data){
		$res = $this->db->insert("user_request",$data);
		return $res;
	}

	public function getCompanyUsers($id){
		$sql = "SELECT user_request.user_id,user_request.doj,user_request.left_date,user_request.rule_id,user_request.hostel,(select name from login WHERE login.id = user_request.user_id) as name,(select image from login WHERE login.id = user_request.user_id) as image,(select business_group from login WHERE login.id = user_request.user_id) as business_group,user_request.user_status,(select login.designation from login WHERE login.id = user_request.user_id) as designation,(select login.m_id from login WHERE login.id = user_request.user_id) as mid,(select login.emp_code from login WHERE login.id = user_request.user_id) as emp_code,(select login.section from login WHERE login.id = user_request.user_id) as section,(select login.department from login WHERE login.id = user_request.user_id) as department FROM `user_request` WHERE user_request.business_id='$id'";
		$res = $this->db->query($sql);
		return $res->result();
	}
	public function getUserAttendanceByDate($start_time,$end_time,$uid,$bid,$verified){
		$sql = "SELECT * FROM `attendance` WHERE status=1 and io_time BETWEEN $start_time and $end_time and user_id='$uid' and bussiness_id='$bid' and manual!='2' order by io_time DESC";
		$res = $this->db->query($sql);
		return $res->result();
	}

	public function getUserAttendanceReportByDate($start_time,$end_time,$uid,$bid,$verified){
		$sql = "SELECT * FROM `attendance` WHERE status=1 and io_time BETWEEN $start_time and $end_time and user_id='$uid' and bussiness_id='$bid' and verified='$verified' and manual!='2' order by io_time DESC";
		$res = $this->db->query($sql);
		return $res->result();
	}

	public function updateCompanyMac($id,$ssid,$mac,$strength){
		$sql = "UPDATE login SET ssid='$ssid', mac='$mac' , strength='$strength' WHERE  id = '$id'";
		$res = $this->db->query($sql);
		return $res;
	}

	public function getCompanyMac($id){
		$sql = "SELECT ssid,mac,strength FROM `login` WHERE id='$id'";
		$res = $this->db->query($sql);
		return $res->result();
	}

	public function changeUserStatus($status,$id,$bid){
		$sql = "UPDATE user_request SET user_status='$status' WHERE user_id='$id' and business_id='$bid'";
		$res = $this->db->query($sql);
		return $res;
	}

	public function attStartMonth($id){
		$sql = "SELECT io_time FROM attendance WHERE user_id='$id' AND status='1' and manual!='2' order by id ASC LIMIT 1";
		$res = $this->db->query($sql);
		return $res->row();
	}

	public function getCompanyUsersByStatus($id,$status){
		$sql = "SELECT user_request.user_id,user_request.doj,user_request.left_date,(select name from login WHERE login.id = user_request.user_id) as name,(select image from login WHERE login.id = user_request.user_id) as image,user_request.user_status,(select login.designation from login WHERE login.id = user_request.user_id) as designation,(select business_group from login WHERE login.id = user_request.user_id) as business_group FROM `user_request` WHERE user_request.business_id='$id' AND user_request.user_status='$status'";
		$res = $this->db->query($sql);
		return $res->result();
	}
	public function getEmpProfile($id,$bid){
		$sql = "SELECT *,(select business_groups.name FROM business_groups WHERE business_groups.id = login.business_group) as business_group_name,(SELECT user_request.doj FROM user_request WHERE user_request.user_id='$id' and user_request.business_id='$bid' order by user_request.id desc LIMIT 1) as user_doj,(SELECT user_request.qr FROM user_request WHERE user_request.user_id='$id' and user_request.business_id='$bid' order by user_request.id desc LIMIT 1) as qr,(SELECT user_request.gps FROM user_request WHERE user_request.user_id='$id' and user_request.business_id='$bid' order by user_request.id desc LIMIT 1) as gps,(SELECT user_request.face FROM user_request WHERE user_request.user_id='$id' and user_request.business_id='$bid' order by user_request.id desc LIMIT 1) as face,(SELECT user_request.colleague FROM user_request WHERE user_request.user_id='$id' and user_request.business_id='$bid' order by user_request.id desc LIMIT 1) as colleague,(SELECT user_request.auto_gps FROM user_request WHERE user_request.user_id='$id' and user_request.business_id='$bid' order by user_request.id desc LIMIT 1) as auto_gps,(SELECT user_request.gps_tracking FROM user_request WHERE user_request.user_id='$id' and user_request.business_id='$bid' order by user_request.id desc LIMIT 1) as gps_tracking,(SELECT user_request.field_duty FROM user_request WHERE user_request.user_id='$id' and user_request.business_id='$bid' order by user_request.id desc LIMIT 1) as field_duty,(SELECT user_request.four_layer_security FROM user_request WHERE user_request.user_id='$id' and user_request.business_id='$bid' order by user_request.id desc LIMIT 1) as four_layer_security,(SELECT user_request.rule_id FROM user_request WHERE user_request.user_id='$id' and user_request.business_id='$bid' order by user_request.id desc LIMIT 1) as rule_id,(SELECT user_request.selfie_with_gps FROM user_request WHERE user_request.user_id='$id' and user_request.business_id='$bid' order by user_request.id desc LIMIT 1) as selfie_with_gps,(SELECT user_request.selfie_with_field_duty FROM user_request WHERE user_request.user_id='$id' and user_request.business_id='$bid' order by user_request.id desc LIMIT 1) as selfie_with_field_duty,(SELECT user_request.qr_mode FROM user_request WHERE user_request.user_id='$id' and user_request.business_id='$bid' order by user_request.id desc LIMIT 1) as qr_mode,(SELECT user_request.month_weekly_off FROM user_request WHERE user_request.user_id='$id' and user_request.business_id='$bid' order by user_request.id desc LIMIT 1) as month_weekly_off FROM `login` where id ='$id' and user_group ='2'";
		$res = $this->db->query($sql);
		return $res->row();
	}

	public function addBusinessGroup($data){
		$res = $this->db->insert("business_groups",$data);
		return $res;
	}

	public function getBusinessGroups($bid){
		$sql = "SELECT * FROM business_groups where business_id='$bid' AND status='1'";
		$res = $this->db->query($sql);
		return $res->result();
	}

	public function removeBusinessGroup($id){
		$sql = "UPDATE `business_groups` SET `status` = '0' WHERE `business_groups`.`id` = '$id'";
		$res = $this->db->query($sql);
		return $res;
	}

	public function updateBusinessGroup($id,$name,$startTime,$endTime,$weekOff,$dayStartTime,$dayEndTime,$monthWeeklyOff){
		$sql = "UPDATE `business_groups` SET name ='$name',shift_start='$startTime',shift_end='$endTime',weekly_off='$weekOff',day_start_time='$dayStartTime',day_end_time='$dayEndTime',month_weekly_off='$monthWeeklyOff' WHERE `business_groups`.`id` = '$id'";
		$res = $this->db->query($sql);
		return $res;
	}

	public function removeHoliday($id){
		$sql = "UPDATE `holiday` SET `status` = '0' WHERE `business_id` = '$id'";
		$res = $this->db->query($sql);
		return $res;
	}

	public function addHoliday($data){
		$res = $this->db->insert_batch("holiday",$data);
		return $res;
	}

	public function getHoliday($bid){
		$sql = "SELECT * FROM holiday where business_id='$bid' AND status='1'";
		$res = $this->db->query($sql);
		return $res->result();
	}

	public function getUserGroup($gid){
		$sql = "SELECT * FROM business_groups where id='$gid' AND status='1'";
		$res = $this->db->query($sql);
		return $res->row();
	}
	public function updateEmpProfile($id,$name,$image,$address,$email,$group,$designation,$dob,$gender,$doj,$education,$bluetoothSsid,$bluetoothMac,$empCode,$manager,$section,$department){
		$sql = "UPDATE login SET name ='$name',image='$image',address='$address',email='$email',business_group='$group',designation='$designation',dob='$dob',gender='$gender',doj='$doj',education='$education',bluetooth_ssid='$bluetoothSsid',bluetooth_mac='$bluetoothMac',emp_code='$empCode',manager='$manager',section='$section',department='$department' WHERE `login`.`id` = '$id'";
		$res = $this->db->query($sql);
		return $res;
	}
	public function getCompanyUserById($id,$empid){
		$sql = "SELECT user_request.user_id,user_request.doj,user_request.left_date,user_request.rule_id,(select name from login WHERE login.id = user_request.user_id) as name,(select image from login WHERE login.id = user_request.user_id) as image,(select business_group from login WHERE login.id = user_request.user_id) as business_group,user_request.user_status,(select login.designation from login WHERE login.id = user_request.user_id) as designation,(select login.m_id from login WHERE login.id = user_request.user_id) as mid,(select login.emp_code from login WHERE login.id = user_request.user_id) as emp_code FROM `user_request` WHERE user_request.business_id='$id' AND user_request.user_id='$empid'";
		$res = $this->db->query($sql);
		return $res->result();
	}
	public function getBusinessToken($bid,$date){
		$sql = "SELECT id,Dept_id,(SELECT department.department FROM department WHERE department.id=token.Dept_id) as department,(SELECT department.Dep_code FROM department WHERE department.id=token.Dept_id) as depcode,(SELECT department_sub.depart_name FROM department_sub WHERE department_sub.id=token.Sub_deptid) as subdepartment,(SELECT login.name FROM login WHERE login.id=token.userid) as username,(SELECT login.mobile FROM login WHERE login.id=token.userid) as mobile,date,token,Query,status,counter_id FROM token where user_bussiness_id='$bid' and date='$date' ORDER by case status when 0 then 'B' when 1 then 'A' WHEN 2 THEN 'C' end";
		$res = $this->db->query($sql);
		return $res->result();
	}

	public function changeTokenStatus($id,$cid,$bid,$status){
		$sql = "UPDATE token SET status = '$status', counter_id = '$cid' WHERE id = '$id' and user_bussiness_id='$bid'";
		$res = $this->db->query($sql);
		return $res;
	}

	public function businessTokenStatus($id,$status){
		$sql = "UPDATE login SET token_status = '$status' WHERE id = '$id'";
		$res = $this->db->query($sql);
		return $res;
	}

	public function closeAllToken($depid,$date){
	    	$sql = "UPDATE token SET status = '2' WHERE status = '1' and Dep_id ='$depid' and date='$date'";
		$res = $this->db->query($sql);
		return $res;
	}

	public function getBusinessTokenStatus($id){
		$sql = "SELECT token_status FROM login where id='$id'";
		$res = $this->db->query($sql);
		return $res->row();
	}

	public function getUserIdByToken($id){
		$sql = "SELECT userid FROM token where id='$id'";
		$res = $this->db->query($sql);
		return $res->row();
	}

	public function getDepNullToken(){
		  $sql="SELECT MAX(token) as token FROM `token` WHERE Dept_id is NULL";
		 $query=$this->db->query($sql);
		return $query->row_array();
	}

	public function closeAccount($id){
		$sql = "UPDATE login SET deleted = '1' WHERE id = '$id'";
		$res = $this->db->query($sql);
		return $res;
	}

	public function changeMobileNumber($id,$mobile){
		$sql = "UPDATE login SET mobile = '$mobile' WHERE id = '$id'";
		$res = $this->db->query($sql);
		return $res;
	}

	public function checkNewQR($qr){
		$sql = "SELECT * FROM new_qr WHERE qr_code='$qr' and login_id IS NULL";
		$res = $this->db->query($sql);
		return $res->row_array();
	}

	public function assignNewQR($id,$qr){
		$sql = "UPDATE new_qr SET login_id = '$id' WHERE qr_code = '$qr'";
		$res = $this->db->query($sql);
		return $res;
	}

	public function getLoginIdByQr($qr){
		$sql = "SELECT login_id,(SELECT login.user_group FROM login WHERE login.id=new_qr.login_id) as user_group,(SELECT login.mobile FROM login WHERE login.id=new_qr.login_id) as mobile FROM new_qr WHERE qr_code='$qr' and login_id IS NOT NULL";
		$res = $this->db->query($sql);
		return $res->row_array();
	}

	public function getCompanyUsersByBluetooth($id){
		$sql = "SELECT user_request.user_id,user_request.doj,user_request.left_date,(select name from login WHERE login.id = user_request.user_id) as name,(select image from login WHERE login.id = user_request.user_id) as image,(select business_group from login WHERE login.id = user_request.user_id) as business_group,user_request.user_status,(select login.designation from login WHERE login.id = user_request.user_id) as designation,(select login.m_id from login WHERE login.id = user_request.user_id) as mid,(select login.bluetooth_ssid from login WHERE login.id = user_request.user_id) as bluetooth_ssid,(select login.bluetooth_mac from login WHERE login.id = user_request.user_id) as bluetooth_mac FROM `user_request` WHERE user_request.business_id='$id'";
		$res = $this->db->query($sql);
		return $res->result();
	}

	public function getAttendanceByVerify($start_time,$end_time,$uid,$bid){
		$sql = "SELECT * FROM `attendance` WHERE status=1 and io_time BETWEEN $start_time and $end_time and user_id='$uid' and bussiness_id='$bid' and location='' and manual!='2' order by id DESC";
		$res = $this->db->query($sql);
		return $res->result();
	}

	public function verifyAttendance($id){
		$sql = "UPDATE attendance SET verified = '1' WHERE id = '$id'";
		$res = $this->db->query($sql);
		return $res;
	}

	public function cancelAttendance($id){
		$sql = "UPDATE attendance SET status = '0' WHERE id = '$id'";
		$res = $this->db->query($sql);
		return $res;
	}
	public function checkUserAlready($bid,$uid){
		$sql = "SELECT * FROM `user_request` WHERE business_id=$bid and user_id=$uid";
		$res = $this->db->query($sql);
		return $res->result();
	}
	public function userLeft($id,$bid){
		$sql = "UPDATE login SET company='' WHERE id='$id' and company='$bid'";
		$res = $this->db->query($sql);
		return $res;
	}
	public function bannerAds(){
		$sql = "SELECT * FROM banner_ads WHERE status=1";
		$res = $this->db->query($sql);
		return $res->result();
	}

	public function features(){
		$sql = "SELECT * FROM features WHERE status=1";
		$res = $this->db->query($sql);
		return $res->result();
	}
	public function requestPremium($id){
		$sql = "UPDATE login SET premium='3' WHERE id='$id'";
		$res = $this->db->query($sql);
		return $res;
	}
	public function getEmpLeaves($uid){
		$sql = "SELECT * FROM `leaves` WHERE uid=$uid and status=1 order by id desc";
		$res = $this->db->query($sql);
		return $res->result();
	}
	public function insertLeave($data){
		$res = $this->db->insert("leaves",$data);
		return $res;
	}
	public function userLeftRequest($id,$bid,$left){
		$sql = "UPDATE user_request SET left_date='$left' WHERE user_id='$id' and business_id='$bid'";
		$res = $this->db->query($sql);
		return $res;
	}

	public function getDoj($id,$bid){
		$sql = "SELECT doj from user_request where business_id='$bid' and user_id='$id'";
		$res = $this->db->query($sql);
		return $res->row_array();
	}

	public function getUserCompany($id){
		$sql = "SELECT * from user_request where user_id='$id' order by id DESC LIMIT 1";
		$res = $this->db->query($sql);
		return $res->row_array();
	}

	public function updateEmpLeave($id,$from,$to,$reason,$type){
		$sql = "UPDATE leaves SET from_date='$from',to_date='$to',reason='$reason',type='$type' WHERE id='$id'";
		$res = $this->db->query($sql);
		return $res;
	}

	public function updateUserDoj($id,$bid,$left,$doj){
		$sql = "UPDATE user_request SET left_date='$left',doj='$doj' WHERE user_id='$id' and business_id='$bid'";
		$res = $this->db->query($sql);
		return $res;
	}
	
	public function updateUserDojHostel($id,$bid,$left,$doj,$hostel){
		$sql = "UPDATE user_request SET left_date='$left',doj='$doj',hostel='$hostel' WHERE user_id='$id' and business_id='$bid'";
		$res = $this->db->query($sql);
		return $res;
	}

	public function updateDoj($id,$bid,$doj,$rule_id,$qr,$gps,$face,$colleague,$auto_gps,$gps_tracking,$field,$security,$gpsSelfie,$fieldSelfie,$qrMode,$month_weekly_off){
		$sql = "UPDATE user_request SET doj='$doj',rule_id='$rule_id',qr='$qr',gps='$gps',face='$face',colleague='$colleague',auto_gps='$auto_gps',gps_tracking='$gps_tracking',field_duty='$field',four_layer_security='$security',selfie_with_gps='$gpsSelfie',selfie_with_field_duty='$fieldSelfie',qr_mode='$qrMode',month_weekly_off='$month_weekly_off' WHERE user_id='$id' and business_id='$bid'";
		$res = $this->db->query($sql);
		return $res;
	}

	public function allUserRequest(){
		$sql = "SELECT * FROM user_request";
		$res = $this->db->query($sql);
		return $res->result();
	}

	public function checkLicence($qr){
		$sql = "SELECT * FROM new_qr WHERE qr_code='$qr' and login_id IS NULL and licence=1";
		$res = $this->db->query($sql);
		return $res->row_array();
	}

	public function updateValidity($id,$start,$validity){
		$sql = "UPDATE login SET validity='$validity',prime_att=1,premium=2 WHERE id='$id'";
		$res = $this->db->query($sql);
		return $res;
	}

	public function updatePrimeAtt($id,$prime){
		$sql = "UPDATE login SET prime_att=$prime WHERE id='$id'";
		$res = $this->db->query($sql);
		return $res;
	}

	public function checkOfflineAt($id,$bid,$startTime,$endTime){
		$sql = "SELECT * FROM attendance WHERE bussiness_id=$bid and user_id=$id and io_time BETWEEN '$startTime' and '$endTime' and manual!=2 and status=1 order by id DESC LIMIT 1";
		$res = $this->db->query($sql);
		return $res->row_array();
	}

	public function getUserAttendanceAll($id){
		$res = $this->db->query("SELECT * FROM attendance WHERE user_id='$id' order by id DESC");
		return $res->result();
	}

	public function getCompanyNewQr($id){
		$res = $this->db->query("SELECT * FROM new_qr WHERE login_id='$id' and licence=0 and status=1");
		return $res->result();
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

	public function getCompanyMacByType($id,$type){
		$sql = "SELECT * FROM `sections` WHERE bid='$id' AND type='$type' AND status=1";
		$res = $this->db->query($sql);
		return $res->result();
	}

	public function updateCompanyMacByType($id,$name,$ssid,$mac,$strength,$type,$loc,$lat,$lon,$radius,$bluetoothName,$bluetoothMac,$bluetoothStrength){
		$sql = "UPDATE sections SET ssid='$ssid', mac='$mac',strength='$strength',name='$name',location='$loc', latitude='$lat',longitude='$lon',radius='$radius',bluetooth_name='$bluetoothName',bluetooth_mac='$bluetoothMac',bluetooth_strength='$bluetoothStrength' WHERE  bid='$id' AND type='$type'";
		$res = $this->db->query($sql);
		return $res;
	}

	public function insertCompanyMacByType($data){
		$res = $this->db->insert("sections",$data);
		return $res;
	}

	public function getSections($id){
		$sql = "SELECT * FROM `sections` WHERE bid=$id AND status=1";
		$res = $this->db->query($sql);
		return $res->result();
	}

	public function updateCompanyLocationByType($id,$loc,$lat,$lon,$radius,$type){
		$sql = "UPDATE sections SET location='$loc', latitude='$lat',longitude='$lon',radius='$radius' WHERE  bid='$id' AND type='$type'";
		$res = $this->db->query($sql);
		return $res;
	}

	public function deactivateNewQR($id){
		$sql = "UPDATE new_qr SET login_id = null WHERE login_id = '$id'";
		$res = $this->db->query($sql);
		return $res;
	}

	public function hasNewQR($id){
		$sql = "SELECT * FROM `new_qr` WHERE login_id='$id'";
		$res = $this->db->query($sql);
		return $res->result();
	}

	public function getDepartmentSections($id){
		$sql = "SELECT * FROM `department_section` WHERE bid=$id AND status=1";
		$res = $this->db->query($sql);
		return $res->result();
	}

	public function insertDepartmentSection($data){
		$res = $this->db->insert("department_section",$data);
		return $res;
	}

	public function editDepartmentSections($id,$bid,$name){
		$sql = "UPDATE department_section SET name='$name' WHERE id = '$id' AND bid='$bid'";
		$res = $this->db->query($sql);
		return $res;
	}

	public function allLogin(){
		$sql = "SELECT * FROM login where user_group=1";
		$res = $this->db->query($sql);
		return $res->result();
	}

	public function updateEmpOptions($bid,$auto_gps,$qr,$gps,$field,$security,$face,$gpsSelfie,$fieldSelfie,$gpsTracking,$qrMode){
		$sql = "UPDATE user_request SET auto_gps='$auto_gps',qr='$qr',gps='$gps',field_duty='$field',four_layer_security='$security',face='$face',selfie_with_gps='$gpsSelfie',selfie_with_field_duty='$fieldSelfie',gps_tracking='$gpsTracking',qr_mode='$qrMode' WHERE business_id='$bid'";
		$res = $this->db->query($sql);
		return $res;
	}

	public function getEmpOptions($id,$bid){
		$sql = "SELECT * from user_request where user_id='$id' and business_id='$bid'";
		$res = $this->db->query($sql);
		return $res->row_array();
	}

	public function getCmpOptions($bid){
		$sql = "SELECT * from business_at_option where bid='$bid'";
		$res = $this->db->query($sql);
		return $res->result();
	}

	public function updateCmpOptions($bid,$auto_gps,$qr,$gps,$field,$security,$face,$gpsSelfie,$fieldSelfie,$gpsTracking,$qrMode){
		$sql = "UPDATE business_at_option SET auto_gps='$auto_gps',qr='$qr',gps='$gps',field_duty='$field',four_layer_security='$security',face='$face',selfie_with_gps='$gpsSelfie',selfie_with_field_duty='$fieldSelfie',gps_tracking='$gpsTracking',qr_mode='$qrMode' WHERE bid='$bid'";
		$res = $this->db->query($sql);
		return $res;
	}
	public function insertCmpOptions($data){
		$res = $this->db->insert("business_at_option",$data);
		return $res;
	}

	public function getAttendanceRules($id){
		return $this->db->query("SELECT * FROM attendance_rule WHERE bid ='$id'")->result();
	}

	public function getUserPendingAttendance($uid,$bid,$verified){
		$sql = "SELECT * FROM `attendance` WHERE status=1 and user_id='$uid' and bussiness_id='$bid' and verified='$verified' and manual!='2' order by io_time DESC";
		$res = $this->db->query($sql);
		return $res->result();
	}

	public function getOpenLeave($bid,$uid,$time){
		return $this->db->query("SELECT * from open_leave WHERE bid='$bid' and uid='$uid' and open_date<=$time and close_date>=$time")->row_array();
	}

	public function getUserOdAttendanceByDate($start_time,$end_time,$uid,$bid){
		$sql = "SELECT * FROM `attendance` WHERE status=1 and io_time BETWEEN $start_time and $end_time and user_id='$uid' and bussiness_id='$bid' and manual='2' order by io_time DESC";
		$res = $this->db->query($sql);
		return $res->result();
	}
	
	public function getCompanyLicence($id){
		$sql = "SELECT * FROM new_qr WHERE assign_id='$id' and login_id IS NULL and licence=1";
		$res = $this->db->query($sql);
		return $res->result();
	}
	
	public function transferLicence($id,$assign,$licence){
		$sql = "UPDATE new_qr SET assign_id = '$assign' WHERE id = '$licence' and assign_id='$id'";
		$res = $this->db->query($sql);
		return $res;
	}

	public function insertLicenceHistory($data){
		return $this->db->insert('licence_history',$data);
	}
	
	public function licenceHistory($id){
		$sql = "SELECT *,(SELECT login.name FROM login WHERE login.id = licence_history.licence_to) as name,(SELECT login.mobile FROM login WHERE login.id = licence_history.licence_to) as mobile,(SELECT login.name FROM login WHERE login.id = licence_history.licence_from) as from_name,(SELECT login.mobile FROM login WHERE login.id = licence_history.licence_from) as from_mobile FROM licence_history WHERE licence_from='$id' or licence_to='$id' ORDER BY id DESC";
		$res = $this->db->query($sql);
		return $res->result();
	}
	
	public function getCompanyClients($id){
		$sql = "SELECT * FROM login WHERE reference='$id'";
		$res = $this->db->query($sql);
		return $res->result();
	}
	
	public function updatePunchMode($bid,$uid,$mode){
		$sql = "UPDATE user_request SET punch_mode='$mode' where business_id='$bid' and user_id='$uid'";
		$res = $this->db->query($sql);
		return $res;
	}
	
	
	public function getCompanyRoleUsers($id){
		$sql = "SELECT *,(SELECT login.name FROM login WHERE login.id=emp_role.uid) as name,(SELECT login.mobile FROM login WHERE login.id = emp_role.uid) as mobile,(SELECT login.image FROM login WHERE login.id = emp_role.uid) as image FROM `emp_role` WHERE bid='$id' and type=1 and deleted=0";
		$res = $this->db->query($sql);
		return $res->result();
	}

	public function addUserRole($data){
		return $this->db->insert('emp_role',$data);
	}
	
	
	public function changeUserRoleStatus($bid,$uid,$status){
		$sql = "UPDATE emp_role SET status='$status' where bid='$bid' and uid='$uid'";
		$res = $this->db->query($sql);
		return $res;
	}
	
	public function getAllAdmins($id){
		$sql = "SELECT * from login WHERE login.id in (SELECT emp_role.bid FROM emp_role WHERE emp_role.uid='$id' and emp_role.status=1 and emp_role.type=1 and emp_role.deleted=0) and login.active=0 and login.deleted=0";
		$res = $this->db->query($sql);
		return $res->result();
	}
	
	public function checkUserRoleStatus($bid,$uid){
		$sql = "SELECT * from emp_role WHERE bid='$bid' and uid='$uid' and type=1 ORDER BY id DESC LIMIT 1";
		$res = $this->db->query($sql);
		return $res->row_array();
	}
	
	public function checkIoTime($id,$bid,$time){
		$sql = "SELECT * FROM attendance WHERE bussiness_id=$bid and user_id=$id and io_time='$time' and status=1 order by id DESC LIMIT 1";
		$res = $this->db->query($sql);
		return $res->row_array();
	}
	


	
	
	
	
	
	public function deleteUserRole($bid,$uid){
		$sql = "UPDATE emp_role SET deleted='1' where bid='$bid' and uid='$uid'";
		$res = $this->db->query($sql);
		return $res;
	}
    
    
	public function checkGpsTrackingTime($id,$bid,$time){
		$sql = "SELECT * FROM gps_tracking WHERE bid=$bid and uid=$id and time='$time' and status=1 order by id DESC LIMIT 1";
		$res = $this->db->query($sql);
		return $res->row_array();
	}

	public function insertGpsTracking($data){
		return $this->db->insert('gps_tracking',$data);
	}

	public function getUserGpsTrackingAll($id){
		$res = $this->db->query("SELECT * FROM gps_tracking WHERE uid='$id' AND status='1' order by id DESC");
		return $res->result();
	}

	public function getUserGpsTrackingByDate($start_time,$end_time,$uid,$bid){
		$sql = "SELECT * FROM `gps_tracking` WHERE status=1 and time BETWEEN $start_time and $end_time and uid='$uid' and bid='$bid' order by time DESC";
		$res = $this->db->query($sql);
		return $res->result();
	}
	
	public function checkGpsLocationTime($id,$bid,$time){
		$sql = "SELECT * FROM gps_location WHERE bid=$bid and uid=$id and time='$time' and status=1 order by id DESC LIMIT 1";
		$res = $this->db->query($sql);
		return $res->row_array();
	}

	public function insertGpsLocation($data){
		return $this->db->insert('gps_location',$data);
	}

	public function getUserGpsLocationAll($id){
		$res = $this->db->query("SELECT * FROM gps_location WHERE uid='$id' AND status='1' order by id DESC");
		return $res->result();
	}
	
	public function getUserGpsLocationByDate($start_time,$end_time,$uid,$bid){
		$sql = "SELECT * FROM `gps_location` WHERE status=1 and time BETWEEN $start_time and $end_time and uid='$uid' and bid='$bid' order by time DESC";
		$res = $this->db->query($sql);
		return $res->result();
	}
	
	public function getUserAttendanceByCompany($id,$bid,$start_time,$end_time){
		$res = $this->db->query("SELECT * FROM attendance WHERE user_id='$id' and io_time BETWEEN $start_time and $end_time and bussiness_id='$bid' AND status='1'");
		return $res->result();
	}

	public function getCompanyUserRequest($id){
		$sql = "SELECT * from user_request WHERE business_id='$id'";
		$res = $this->db->query($sql);
		return $res->result();
	}

	public function getCompanyUser($id){
		$sql = "SELECT * from login WHERE id='$id'";
		$res = $this->db->query($sql);
		return $res->row_array();
	}
	
	public function updateGpsTracking($id,$exitTime){
		$sql = "UPDATE gps_tracking SET exit_time='$exitTime' where id='$id'";
		$res = $this->db->query($sql);
		return $res;
	}
	
	public function getCompanyAllMac($id){
		$sql = "SELECT * FROM `sections` WHERE bid='$id' AND status=1";
		$res = $this->db->query($sql);
		return $res->result();
	}
	
	public function getRule($bid,$ruleId){
		return $this->db->query("SELECT * from attendance_rule WHERE bid='$bid' and rule_id='$ruleId'")->row_array();
	}

	public function getDayReport($bid,$uid,$startTime,$endTime){
		$sql = "SELECT * FROM `daily_report` WHERE day_start_time='$startTime' and day_end_time='$endTime' and bid='$bid' and uid='$uid' and status=1";
		$res = $this->db->query($sql);
		return $res->row_array();
	}

	public function addDayReport($data){
		return $this->db->insert('daily_report',$data);
	}
	
	public function getUserTask($bid,$uid){
		$sql = "SELECT *,(SELECT login.name FROM login WHERE login.id=task.assign_id)as assign_name FROM `task` WHERE bid='$bid' AND uid='$uid' or assign_id='$uid'";
		$res = $this->db->query($sql);
		return $res->result();
	}
	
	public function addTask($data){
		return $this->db->insert('task',$data);
	}
	
	public function updateUserTask($id,$data){
		$res = $this->db->where('id', $id);
		$res = $this->db->update('task', $data);
		return $res;
	}
	
	public function getCompanyTask($bid){
		$sql = "SELECT *,(SELECT login.name FROM login WHERE login.id=task.assign_id)as assign_name FROM `task` WHERE bid='$bid'";
		$res = $this->db->query($sql);
		return $res->result();
	}
	
	public function getCompanyLeaves($bid){
		$sql = "SELECT *,(Select login.mobile from login where login.id=leaves.uid) as mobile FROM `leaves` WHERE bid='$bid'";
		$res = $this->db->query($sql);
		return $res->result();
	}
	
	public function updateCompanyLeave($id,$data){
		$res = $this->db->where('id', $id);
		$res = $this->db->update('leaves', $data);
		return $res;
	}
	
	public function getCompanyAttendanceByDate($bid,$start_time,$end_time){
		$res = $this->db->query("SELECT * FROM attendance WHERE bussiness_id='$bid' and io_time BETWEEN $start_time and $end_time");
		return $res->result();
	}
	
	public function getMonthLeaves($bid,$uid,$monthStartTime,$monthEndTime){
		$sql = "SELECT * FROM `leaves` WHERE uid=$uid and bid=$bid and date_time BETWEEN $monthStartTime and $monthEndTime";
		$res = $this->db->query($sql);
		return $res->result();
	}
	
	public function addComplain($data){
		return $this->db->insert('complain',$data);
	}
	
	public function getUserComplain($bid,$uid){
		$sql = "SELECT * FROM `complain` WHERE bid='$bid' AND uid='$uid'";
		$res = $this->db->query($sql);
		return $res->result();
	}

	public function updateUserComplain($id,$data){
		$res = $this->db->where('id', $id);
		$res = $this->db->update('complain', $data);
		return $res;
	}
	
	public function getCompanyComplains($bid){
		$sql = "SELECT *,(SELECT login.name FROM login WHERE login.id=complain.uid)as name FROM `complain` WHERE bid='$bid'";
		$res = $this->db->query($sql);
		return $res->result();
	}
	
	public function addCompanyBlock($data){
		return $this->db->insert('blocks',$data);
	}

	public function updateCompanyBlock($id,$data){
		$res = $this->db->where('id', $id);
		$res = $this->db->update('blocks', $data);
		return $res;
	}
	
	public function getCompanyBlocks($bid){
		$sql = "SELECT * FROM `blocks` WHERE bid='$bid'";
		$res = $this->db->query($sql);
		return $res->result();
	}
	
	public function addCompanyRoomType($data){
		return $this->db->insert('room_types',$data);
	}

	public function updateCompanyRoomType($id,$data){
		$res = $this->db->where('id', $id);
		$res = $this->db->update('room_types', $data);
		return $res;
	}

	public function getCompanyRoomType($bid){
		$sql = "SELECT * FROM `room_types` WHERE bid='$bid'";
		$res = $this->db->query($sql);
		return $res->result();
	}
	
	public function getHostelDetails($bid,$uid){
		return $this->db->query("SELECT * FROM hostel_detail WHERE bid='$bid' and uid='$uid' order by date_time LIMIT 1")->row_array();
	}

	public function getHostelEmpProfile($uid){
		return $this->db->query("SELECT name,mobile,email,address,image,gender,(SELECT hostel_detail.block from hostel_detail WHERE uid=login.id order by date_time limit 1) as block,(SELECT hostel_detail.floor from hostel_detail WHERE uid=login.id order by date_time limit 1) as floor,(SELECT hostel_detail.room_no from hostel_detail WHERE uid=login.id order by date_time limit 1) as room_no,(SELECT hostel_detail.room_type from hostel_detail WHERE uid=login.id order by date_time limit 1) as room_type,(SELECT hostel_detail.parent_name from hostel_detail WHERE uid=login.id order by date_time limit 1) as parent_name,(SELECT hostel_detail.parent_relation from hostel_detail WHERE uid=login.id order by date_time limit 1) as parent_relation,(SELECT hostel_detail.parent_mobile from hostel_detail WHERE uid=login.id order by date_time limit 1) as parent_mobile FROM `login` WHERE id='$uid'")->row();
	}
	
	public function updateHostelEmpProfile($id,$data){
		$res = $this->db->where('id', $id);
		$res = $this->db->update('login', $data);
		return $res;
	}

	public function addHostelDetail($data){
		return $this->db->insert('hostel_detail',$data);
	}

	public function updateHostelDetail($id,$data){
		$res = $this->db->where('id', $id);
		$res = $this->db->update('hostel_detail', $data);
		return $res;
	}
	
	
	///arpit new	
	
	public function getHostelUserRequest($id){
		$sql = "SELECT * from add_hostel WHERE business_id='$id'";
		$res = $this->db->query($sql);
		return $res->result();
	}
	
	public function getHostelAttendanceByDate($bid,$start_time,$end_time){
		$res = $this->db->query("SELECT * FROM hostel_attendance WHERE business_id='$bid' and io_time BETWEEN $start_time and $end_time and status='1'");
		return $res->result();
	}
	public function getUserHostel($id){
		$sql = "SELECT * from add_hostel where user_id='$id' order by id DESC LIMIT 1";
		$res = $this->db->query($sql);
		return $res->row_array();
	}
	public function checkStudentAlready($bid,$uid){
		$sql = "SELECT * FROM `add_hostel` WHERE business_id=$bid and user_id=$uid";
		$res = $this->db->query($sql);
		return $res->result();
	}
	public function add_Student_hostel($data){
		$res = $this->db->insert("add_hostel",$data);
		return $res;
	}
	public function studentHostelStatus($userid,$businessid){
		$sql="SELECT user_status FROM `add_hostel` WHERE user_id='$userid' AND business_id='$businessid'";
	    $query=$this->db->query($sql);
		return $query->row_array();
	}
	public function insert_Hostel_Attendance($data){
		$res = $this->db->insert("hostel_attendance",$data);
		return $res;
	}
	
	public function getChild($mobile){
		return $this->db->query("SELECT *,(SELECT login.mobile from login where login.id=hostel_detail.uid) as mobile FROM hostel_detail WHERE parent_mobile='$mobile' order by date_time LIMIT 1")->row_array();
	}
	
	public function updateMaxEmp($id,$limit){
		$sql = "UPDATE business_at_option SET max_emp='$limit' WHERE bid='$id'";
		$res = $this->db->query($sql);
		return $res;
	}
	
	public function getCompanyAllLicence($id){
		$sql = "SELECT * FROM new_qr WHERE assign_id='$id' and login_id IS NULL and licence!=0";
		$res = $this->db->query($sql);
		return $res->result();
	}

	public function addCompanyClass($data){
		return $this->db->insert('class',$data);
	}

	public function updateCompanyClass($id,$data){
		$res = $this->db->where('id', $id);
		$res = $this->db->update('class', $data);
		return $res;
	}
    
    public function checkTeacher($bid,$uid,$cid){
		$sql = "SELECT * FROM `class_teacher` WHERE bid='$bid' and uid='$uid' and class_id='$cid' and status=1";
		$res = $this->db->query($sql);
		return $res->row_array();
	}
	
	public function addStudentToClass($data){
		return $this->db->insert('student',$data);
	}

	public function getMaxRollNo(){
		return $this->db->query("SELECT MAX(student_code) AS student_code FROM student")->row_array();
	}
	
	public function getCompanyClasses($bid,$time){
		$sql = "SELECT * FROM `class` WHERE bid='$bid' and update_date>='$time'";
		$res = $this->db->query($sql);
		return $res->result();
	}

	public function getCompanyTeachers($bid,$time){
		$sql = "SELECT *,(SELECT login.name FROM login where login.id=class_teacher.uid order by login.id desc limit 1) as name,(SELECT login.mobile FROM login where login.id=class_teacher.uid order by login.id desc limit 1) as mobile FROM `class_teacher` WHERE bid='$bid' and update_date>='$time'";
		$res = $this->db->query($sql);
		return $res->result();
	}

	public function getCompanyStudents($bid,$time){
		$sql = "SELECT * FROM `student` WHERE bid='$bid' and update_date>='$time'";
		$res = $this->db->query($sql);
		return $res->result();
	}

	public function getCompanyStudentAttendance($bid,$time){
		$sql = "SELECT * FROM `student_attendance` WHERE bid='$bid' and update_date>='$time'";
		$res = $this->db->query($sql);
		return $res->result();
	}
	
	public function getStudentByRollNo($bid,$rollno){
		$sql = "SELECT * FROM `student` WHERE bid='$bid' and student_code='$rollno' and status=1";
		$res = $this->db->query($sql);
		return $res->row_array();
	}

	public function getTeacherById($id){
		$sql = "SELECT * FROM `class_teacher` WHERE uid='$id' and status=1";
		$res = $this->db->query($sql);
		return $res->row_array();
	}
	
	public function getStudentAttendanceByTime($bid,$sid,$startTime,$endTime){
		$sql = "SELECT * FROM `student_attendance` WHERE time BETWEEN $startTime and $endTime and bid='$bid' and student_id='$sid' and status=1 and student_status in (0,1)";
		$res = $this->db->query($sql);
		return $res->row_array();
	}
	
		public function getBusinessByDeviceId($deviceId){
	return $this->db->where('deviceid',$deviceId)->get('Business_bioid')->row_array();
	}
	
		public function getBusinessByDeviceIdtest($deviceId){
		$sql = "SELECT * FROM `Business_bioid` WHERE deviceid='$deviceId'";
		$res = $this->db->query($sql);
		return $res->row_array();
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
	
	public function getParentStudents($mobile){
		$sql = "SELECT * FROM `student` WHERE parent_mobile='$mobile'";
		$res = $this->db->query($sql);
		return $res->result();
	}

	public function getStudentClassById($id){
		$sql = "SELECT * FROM `class` WHERE id='$id'";
		$res = $this->db->query($sql);
		return $res->row_array();
	}

	public function getStudentAttendanceById($sid,$time){
		$sql = "SELECT * FROM `student_attendance` WHERE student_id='$sid' and update_date>='$time'";
		$res = $this->db->query($sql);
		return $res->result();
	}
}
?>
