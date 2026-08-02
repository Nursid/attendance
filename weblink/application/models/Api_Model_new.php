<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Api_Model_new extends CI_Model
{
	function __construct(){
        parent::__construct();
		$this->load->database();
	}
	public function checkMobile($mobile){
		return $this->db->where('mobile',$mobile)->get('login')->row_array();
	}
	
	public function getMaxMid(){
		return $this->db->query("SELECT MAX(m_id) AS m_id FROM login")->row_array();
	}

	public function getMidByMobile($m){
		return $this->db->query("SELECT m_id AS m_id FROM login WHERE mobile = '$m'")->row_array();	
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
		$this->db->from('login');
		$get=$this->db->get();
		return $get->row_array();
	}
	
	public function registered($mobile){
		$this->db->select('*');
		$this->db->where('mobile',$mobile);
	
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
		 $sql="SELECT * FROM `login` WHERE mobile='$mobileno' OR id='$userid'";
		 $query=$this->db->query($sql);
		return $query->row_array();
	}
	public function usertypescheck($userid,$mobileno){
	 $sql="SELECT * FROM `login` WHERE  id='$userid' or mobile='$mobileno'";
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
		  $sql="SELECT id,name,address,user_group,image,mac,strength FROM `login` where id='$id'";
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
		$res = $this->db->query("SELECT * FROM attendance WHERE user_id='$id' AND status='1' AND io_time >=$start AND io_time <$end order by id DESC");
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
		$sql = "SELECT user_request.user_id,(select name from login WHERE login.id = user_request.user_id) as name,(select image from login WHERE login.id = user_request.user_id) as image,(select business_group from login WHERE login.id = user_request.user_id) as business_group,user_request.user_status,(select login.designation from login WHERE login.id = user_request.user_id) as designation,(select login.m_id from login WHERE login.id = user_request.user_id) as mid,(select login.doj from login WHERE login.id = user_request.user_id) as doj FROM `user_request` WHERE user_request.business_id='$id'";
		$res = $this->db->query($sql);
		return $res->result();
	}
	public function getUserAttendanceByDate($start_time,$end_time,$uid,$bid){
		$sql = "SELECT * FROM `attendance` WHERE status=1 and io_time BETWEEN $start_time and $end_time and user_id='$uid' and bussiness_id='$bid' order by id DESC";
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
		$sql = "SELECT io_time FROM attendance WHERE user_id='$id' AND status='1' order by id ASC LIMIT 1";
		$res = $this->db->query($sql);
		return $res->row();
	}
	
	public function getCompanyUsersByStatus($id,$status){
		$sql = "SELECT user_request.user_id,(select name from login WHERE login.id = user_request.user_id) as name,(select image from login WHERE login.id = user_request.user_id) as image,user_request.user_status,(select login.designation from login WHERE login.id = user_request.user_id) as designation,(select login.doj from login WHERE login.id = user_request.user_id) as doj FROM `user_request` WHERE user_request.business_id='$id' AND user_request.user_status='$status'";
		$res = $this->db->query($sql);
		return $res->result();
	}
	public function getEmpProfile($id,$bid){
		$sql = "SELECT *,(select business_groups.name FROM business_groups WHERE business_groups.id = login.business_group) as business_group_name FROM `login` where id ='$id' and company ='$bid'";
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
	
	public function updateBusinessGroup($id,$name,$startTime,$endTime,$weekOff){
		$sql = "UPDATE `business_groups` SET name ='$name',shift_start='$startTime',shift_end='$endTime',weekly_off='$weekOff' WHERE `business_groups`.`id` = '$id'";
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
	public function updateEmpProfile($id,$name,$address,$email,$group,$designation,$dob,$gender,$doj,$education){
		$sql = "UPDATE login SET name ='$name',address='$address',email='$email',business_group='$group',designation='$designation',dob='$dob',gender='$gender',doj='$doj',education='$education' WHERE `login`.`id` = '$id'";
		$res = $this->db->query($sql);
		return $res;
	}
	public function getCompanyUserById($id,$empid){
		$sql = "SELECT user_request.user_id,(select name from login WHERE login.id = user_request.user_id) as name,(select image from login WHERE login.id = user_request.user_id) as image,(select business_group from login WHERE login.id = user_request.user_id) as business_group,user_request.user_status,(select login.designation from login WHERE login.id = user_request.user_id) as designation,(select login.m_id from login WHERE login.id = user_request.user_id) as mid FROM `user_request` WHERE user_request.business_id='$id' AND user_request.user_id='$empid'";
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
}
?>