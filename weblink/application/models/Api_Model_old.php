<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Api_Model extends CI_Model
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
		$sql="SELECT * FROM `offer` WHERE shopid='$id' and status='0' ORDER BY oid DESC";
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
// 		$this->db->select('*');
// 		$this->db->where('scanby',$id);
				
// 		$this->db->from('userqrdetails');
// 		$this->db->group_by('scanid','DESC');
		
// 		$get=$this->db->get();
$sq="SELECT * FROM `userqrdetails` WHERE scanby='$id' GROUP BY scanid DESC ";
			$query=$this->db->query($sq);
		return $query->result();
		
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
		$query=$this->db->query("SELECT * FROM `offer` WHERE shopid='$id' and status='0' ORDER BY oid DESC");
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
	
	 public function userwebcheck($userid){
	 $sql="SELECT * FROM `web_login` WHERE login_id ='$userid'";
		 $query=$this->db->query($sql);
		return $query->row_array();
	}
	
	
	 public function appointstatus($userid){
	 $sql="SELECT * FROM `assign_menu` WHERE  assign_bussiness_id ='$userid' and assign_menu_id ='1'";
		 $query=$this->db->query($sql);
		return $query->row_array();
	}
	
	 public function tokstatus($userid){
	 $sql="SELECT * FROM `assign_menu` WHERE  assign_bussiness_id ='$userid' and assign_menu_id ='2'";
		 $query=$this->db->query($sql);
		return $query->row_array();
	}
	
	
	public function getassigneddept($userid){
		$sql="SELECT * FROM `assigned_department` WHERE  user_bussiness_id='$userid'";
		 $query=$this->db->query($sql);
		return $query->result();
	}
	
		public function getappointdept($userid){
		$sql="SELECT * FROM `appoint_setting` WHERE  bussiness_id='$userid' group by  department";
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
		  $sql="SELECT id,name,address FROM `login` where id='$id'";
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
}
?>