<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Kolkata');
class User extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->helper('url');
		$this->load->library(array('session','Ciqrcode','zip'));
		$this->load->model('Web_Model','web');
		//$this->load->model('Api_Model_v11','app');
		$this->load->helper('cookie');

	}
	public function index(){
		$this->load->view('users/login');
	}
	public function login(){
		$post=$this->input->post();
		$page=$post['page'];
		$getLogin=$this->web->login($post['username'],md5($post['password']));
		if(!empty($getLogin)){
		    
		   
			
			
			$linked = $this->web->getAllLinked($getLogin['username']);
			$linkedData = array();	if($getLogin['type']=='P'){
			}
			$linkedData[]=$getLogin;
			if(!empty($linked)){
				foreach($linked as $link){
					$linkedData[]=$this->web->getLinkedWeb($link->mobile);
				}
			}
			if(!empty($linkedData)){
				$this->session->set_userdata('linked',$linkedData);
			}

				if(!empty($linkedData)){
					$this->session->set_userdata($linkedData[0]);
				}
				
				 	if($getLogin['type']=='P'){
		    	    $getUserCompanies  = $this->web->getUserCompanies($getLogin['login_id']);
		    	    if($getUserCompanies){
				$this->session->set_userdata('empCompany',$getUserCompanies[0]->bid);
			}
			else{
			   	$this->session->set_flashdata('msg', 'User Not Authrised ');
			   		redirect('user-login');
			}
		    	 //	$getPLogin=$this->web->Plogin($getUserCompanies[0]->bid,$getLogin['login_id']);   
		     }
				
				
				
				if($getLogin['type']=='P'){
					$bid =$getUserCompanies[0]->bid;
					} else {
						$bid=$getLogin['login_id'];
						}
				
				$actdata=array(
			   'bid'=>$bid,
				'uid'=>$getLogin['login_id'],
				'activity'=>"Login to portal",
				'date_time'=>time()
				
			);
			$data=$this->db->insert('activity',$actdata);	
				
				
				
				
				if($page==2){
				 redirect('page_hostel');   
				}elseif($page==3){
					redirect('page_school');   
				   }else{
			redirect('page');}
		}
		else{
			$res = $this->web->checkUserStatus($post['username'],md5($post['password']));
			if (empty($res)) {
				$this->session->set_flashdata('msg', 'Incorrect username or password!');
			}elseif($res['status'] == 0){
				$this->session->set_flashdata('msg', 'User account not ACTIVE!');
			}
			redirect('user-login');
		}
	}
	public function dashboard(){
		if(!empty($this->session->userdata('id'))){
			$data['bookappoinment']=$this->web->GetBookCount();
			$data['counter']=$this->web->GetCountersCount();
			$data['count']=$this->web->GetUsersCount();
			$this->load->view('users/dashboard',$data);
		}
		else{
			redirect('user-login');
		}
	}
	

	public function getajaxRequest(){
		if(!empty($this->session->userdata('id'))){
			$this->load->view('department/edit');
		}
		else{
			redirect('user-login');
		}
	}
	public function userslist(){
		if(!empty($this->session->userdata('id'))){
			$data['users']=$this->web->getallusers();
			$data['business']=$this->web->getallbusiness();
			$this->load->view('users/users',$data);
		}
		else{
			redirect('user-login');
		}
	}
	
	public function activateUser(){
		if (!empty($this->session->userdata('id'))) {
			$id = $this->input->post('id');
			$res= $this->web->statusActivate($id);
			if ($res) {
				echo $id;
				return($id);
			}
		} else {
			redirect('user-login');
		}

	}
	public function inactivateUser(){
		if (!empty($this->session->userdata('id'))) {
			$id = $this->input->post('id');
			$res= $this->web->statusInctivate($id);
			if ($res) {
				echo $id;
				return($id);
			}
		} else {
			redirect('user-login');
		}

	}
	
	public function changePass(){
		if (!empty($this->session->userdata('id'))) {
			$this->load->view('setting/pass');
		}else{
			redirect('user-login');
		}
	}
	
	//-------------------PUSH NOTIFICATION-----------------------

	function push_notification_android($offers,$firebasetoken,$title){
		//API URL of FCM
		$url = 'https://fcm.googleapis.com/fcm/send';

		/*api_key available in:
		Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key*/
		$api_key = 'AAAAPoWBUlE:APA91bEc5rknh3hGlP1wL2VTz38yYArAlv0wXWoyqmzpfx33OFPI7O4Q6Z0N3bT3ZrddlrGDRmFgmqQBPbKQVmx_cp_xd7_OwnB-ZZpxfVBt-93VOrOtcmsMqGtpqZ3NM-7w22spOhIi'; //Replace with yours

		//$target = "cfcMQz6JGVo:APA91bFjoKN45oDIEMMH9xz537JQnSuu4CBNjHzYpN5acihRPJkK6hoA9UXlu7rjv72LOeBJGsCukDz5lEA-9gmR-YN_0gTec-51lLrBy4cxeO8CsjQ_o6LxL5xXRFUDwPUW78v4c4Yt";
		$target = $firebasetoken;

		$fields = array();
		$fields['priority'] = "high";
		$fields['notification'] = [ "title" => $title,
		"body" => $offers,
		'data' => ['message' => $offers],
		"sound" => "default"];
		if (is_array($target)){
			$fields['registration_ids'] = $target;
		} else{
			$fields['to'] = $target;
		}

		//header includes Content type and api key
		$headers = array(
			'Content-Type:application/json',
			'Authorization:key='.$api_key
		);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
		$result = curl_exec($ch);
		if ($result === FALSE) {
			die('FCM Send Error: ' . curl_error($ch));
		}
		curl_close($ch);
		return $result;
		print_r($result);
	}



	public function logout(){
		$linked = $this->session->userdata('linked');
		$newLinked = array();
		if(count($linked)>1){
			foreach($linked as $account){
				if($this->session->userdata('id')!=$account['id']){
					$newLinked[]=$account;
				}
			}
		}
		if(!empty($newLinked) && !empty($newLinked[0])){
			$this->session->set_userdata('linked',$newLinked);
			$this->session->set_userdata($newLinked[0]);
			redirect('page');
		}else{
			$this->session->unset_userdata('id');
			$this->session->sess_destroy();
			redirect('user-login');
		}
	}

	public function profile($id)
	{
		$this->load->view('users/profile');
	}
	
		public function activation(){
			$id=$this->input->post('id');
			$res=$this->web->statusInctivation($id);
			if ($res){
				echo $id;
				return $id;
			}

		}

		public function inactivation(){
			$id=$this->input->post('id');
			$res=$this->web->statusactivation($id);
			if ($res){
				echo $id;
				return $id;
			}
		}

		

		public function attendance(){
			if(!empty($this->session->userdata('id'))){
				$data['attendance']=$this->web->attendance($this->session->userdata('login_id'));
				$this->load->view('attendance',$data);
			}else{
				redirect('user-login');
			}
		}

		public function businessUsers2(){
			if(!empty($this->session->userdata('id'))){
			    		$end_time=time();
						$start_time=strtotime('-60 day',$end_time);
			
				$data['premium']=$this->web->getallpremium2($start_time,$end_time);
			    
				//$data['premium']=$this->web->getallpremium();
				$this->load->view('users/exportbusers',$data);
			}
			else{
				redirect('user-login');
			}
		}
		
		
		
		
		public function businessUsers(){
			if(!empty($this->session->userdata('id'))){
				$data['premium']=$this->web->getallpremium();
				$this->load->view('users/business_users',$data);
			}
			else{
				redirect('user-login');
			}
		}

		public function changeDate(){
			if (!empty($this->session->userdata('id'))) {
				$id = $this->input->post("id");
				$validity = $this->input->post("validity");
				$info = $this->web->updateValidity($id,strtotime($validity));
			}else{
				redirect('user-login');
			}
		}

		public function changeStartDate(){
			if (!empty($this->session->userdata('id'))) {
				$id = $this->input->post("id");
				$startDate = $this->input->post("startDate");
				$info = $this->web->updateStartDate($id,strtotime($startDate));
			}else{
				redirect('user-login');
			}

		}


		/////////////arpit/////////




		public function employees(){
			if(!empty($this->session->userdata('id'))){
				$this->load->view('attendance/employees');
			}
			else{
				redirect('user-login');
			}
		}


		public function editemployees(){
			if(!empty($this->session->userdata('id'))){
				$id = $this->input->post("id");
				//	$this->load->view('attendance/editemployees',$id);
				//$id = $_post['id'];
				//$id = $this->input->post('id');
				//$res= $this->web->statusInctivate($id);
				//$this->load->view('users/users',$data);

				//$val=$this->web->getNameByUserId($id);

				//$data = $this->input->post('id');
				//$val = $this->web->getNameByUserId($id);
				//$data['value'] = $dep;
				//$data['option'] = 'edit_dep';
				$this->load->view('attendance/editemployees');
			}

			else{
				redirect('user-login');
			}
		}
		public function addemployee(){
			if(!empty($this->session->userdata('id'))){
				$this->load->view('attendance/addemployee');
			}
			else{
				redirect('user-login');
			}
		}


		public function activateEmployee(){
			if (!empty($this->session->userdata('id'))) {
				$id = $this->input->post('id');
				$res= $this->web->statusActivateEmp($id);
				if ($res) {
					echo $id;
					return($id);
				}
			} else {
				redirect('user-login');
			}

		}
		public function inactivateEmployee(){
			if (!empty($this->session->userdata('id'))) {
				$id = $this->input->post('id');
				$res= $this->web->statusInctivateEmp($id);
				if ($res) {
					echo $id;
					return($id);
				}
			} else {
				redirect('user-login');
			}

		}

		public function addnewemployee(){
				if(!empty($this->session->userdata('id'))){
					if($this->session->userdata()['type']=='P'){
						$uid = $this->session->userdata('empCompany');
					} else {
						$uid=$this->web->session->userdata('login_id');
					}
				
					// if($this->session->userdata('type')=="P"){
					// 	$userCmp = $this->app->getUserCompany($loginId);
					// 	if(isset($userCmp) && ($userCmp['left_date']=="" || $userCmp['left_date']>time())){
					// 		$uid = $userCmp['business_id'];
					// 	}
					// }
					$omid = $this->web->getMaxMid()['m_id'];
					$temp_ = "MI";
					if($omid == ''){
						$nmid = $temp_.'00000';
					}else{
						$str1 = substr($omid,4);
						$str1 = $str1 + 1;
						$str2 = str_pad($str1 , 5 , 0 , STR_PAD_LEFT);
						$nmid = $temp_.$str2;
					}

					$postdata=$this->input->post();
					 $doj=strtotime($_POST['doj']);
					$otp=rand(1000,9999);
					$i='upload/nextpng.png';

					$postdata=array(
						'name'=>$postdata['name'],
						'mobile'=>trim($postdata['mobile']),
						'address'=>$postdata['address'],
						'user_group'=>"2",
						'email'=>$postdata['email'],
						'emp_code'=>$postdata['empcode'],
						'dob'=>$postdata['dob'],
						'bio_id'=>$postdata['devcode'],
						'gender'=>$postdata['gender'],
						'designation'=>$postdata['desig'],
						'business_group'=>$postdata['group'],
						'department'=>$postdata['department'],
						'manager'=>$postdata['post'],
						'doj'=>strtotime($postdata['doj']),
						'active'=>0,
						'date'=>time(),
						'baseurl'=>base_url().'User/profile/'.$nmid,
						'login'=>md5($mobile),
						'image'=>$i,
						'company'=>$uid,
						'm_id'=>$nmid,
						'otp'=>$otp

					);
					$data=$this->db->insert('login',$postdata);
					$id = $this->db->insert_id();

					if($data > 0){
						if($id){
						   
							$cmpInData = array(
								'business_id'=>$uid,
								'user_id'=>$id,
								'doj'=>$doj,
								'date'=>time(),
								'user_status'=>"1"
							);
							$data=$this->db->insert('user_request',$cmpInData);
						}
                      $uname = $this->web->getNameByUserId($id);
                                     //echo $uname[0]->name;	
							$actdata=array(
			                            'bid'=>$uid,
				                        'uid'=>$this->web->session->userdata('login_id'),
				                        'activity'=>"New Employee ".$uname[0]->name. " added",
				                        'date_time'=>time()
				
			                             );
			                  $data=$this->db->insert('activity',$actdata);	
				
						}
                       
						$this->session->set_flashdata('msg','New Employee Added!');
						redirect('employees');
					
				}
				else{
					redirect('user-login');
				}
			}




	public function updateemployee(){
		if(!empty($this->session->userdata('id'))){
			echo $id=$_POST['id'];
			echo $bid=$_POST['bid'];
			//echo $id = $_POST['id'];
			echo $name = $_POST['name'];
			
			
			echo $phone = $_POST['phone'];
			echo $father_name = $_POST['father_name'];
		    echo $blood_group = $_POST['blood_group'];
			echo $experience = $_POST['experience'];
			echo $employement = $_POST['employement'];
			echo $doreg = strtotime($_POST['doreg']);
			
			echo $email = $_POST['email'];
			echo $address = $_POST['address'];
			echo $empcode = $_POST['empcode'];
			echo $bio_id = $_POST['bio_id'];
			echo $dob = $_POST['dob'];
			echo $gender = $_POST['gender'];
			echo $desig = $_POST['desig'];
			echo $edu = $_POST['edu'];
			echo $post = $_POST['post'];
			echo $department = $_POST['department'];
			
			echo $doj = strtotime($_POST['doj']);
			echo $dol = strtotime($_POST['dol']);
			echo $trf =$_POST['trf'];
			echo $group = $_POST['group'];
			$data=array(
						'name' => $name,
						'email' => $email,
						'address' => $address,
						'emp_code' => $empcode,
						'bio_id' => $bio_id,
						'gender' => $gender,
						'designation' => $desig,
						'education' => $edu,
						'manager' => $post,
						'doj' => $doj,
						'dob' => $dob,
						'company' => $bid,
						'phone' => $phone,
						'father_name' => $father_name,
						'blood_group' => $blood_group,
						'experience' => $experience,
						'start_date' => $doreg,
						
						'business_group' => $group,
						'department' => $department
				
					);
			//$data=$this->db->update('login',$postdata);
			$this->db->where('id',$id);
			$data= $this->db->update('login',$data);
			
			$uname = $this->web->getNameByUserId($id);
			$actdata=array(
			                            'bid'=>$bid,
				                        'uid'=>$this->web->session->userdata('login_id'),
				                        'activity'=>"Employee data ".$uname[0]->name. " updated",
				                        'date_time'=>time()
				
			                             );
			                  $data=$this->db->insert('activity',$actdata);	
			if($doj!=''){
			
			$jdata=array('doj' => $doj
						//'left_date' => $dol
						
						);
			$this->db->where('user_id',$id);
			$data= $this->db->update('user_request',$jdata);
			}
			
			if($dol!=''){
			
			$ldata=array(//'doj' => $doj,
						'left_date' => $dol
						
						);
			$this->db->where('user_id',$id);
			$data= $this->db->update('user_request',$ldata);
			}
			if($trf!=''){
			
			$ldata=array(
						'left_date' =>time()
						);
			$this->db->where('user_id',$id);
			$data= $this->db->update('user_request',$ldata);			
			$tdata=array(
			             'business_id' => $trf,
			             'user_id' => $id,
						 'user_status' => 1,
						 'date' => time(),
						 'doj' =>time()
						);			
			
			$tdata= $this->db->insert('user_request',$tdata);
			}
			
		
			
				$this->session->set_flashdata('msg','Employee Updated Successfully!');
				redirect('employees');
			
		}
		else{
			redirect('user-login');
		}
	}




	public function addstaff(){
			if(!empty($this->session->userdata('id'))){
				if($this->session->userdata()['type']=='P'){
					$uid = $this->session->userdata('empCompany');
				} else {
					$uid=$this->web->session->userdata('login_id');
				}
				$postdata=$this->input->post();
				$id=$_POST['usid'];
				$userCmp = $this->web->getUserCompany($id);
				if(isset($userCmp) && ($userCmp['left_date']=="" || $userCmp['left_date']>time())){
						$this->session->set_flashdata('msg','Already Added in a Company!');
						redirect('employees');
				}else{
					$data1=array(
						'doj'=>strtotime(date("d-m-Y 00:00:00",time())),
						'company'=>$uid
					);
					$this->db->where('id',$id);
					$data= $this->db->update('login',$data1);
					$cmpInData = array(
						'business_id'=>$uid,
						'user_id'=>$postdata['usid'],
						'doj'=>strtotime(date("d-m-Y 00:00:00",time())),
						'date'=>time(),
						'user_status'=>"1"
					);
					$data=$this->db->insert('user_request',$cmpInData);
					if($data > 0){
						$this->session->set_flashdata('msg','New Employee Added!');
						redirect('employees');
					}
				}
			}
			else{
				redirect('user-login');
			}
		}




	
	

	


















	


	public function changeAttToDate(){
		if (!empty($this->session->userdata('id'))) {
			$i = $this->input->post("i");
			$buid = $this->input->post("buid");
			$id = $this->input->post("id");
			$in_times = $this->input->post("in_times");
			$in=date("Y-m-d",$i);
			//$io_time=date('Y-m-d H-i-s',strtotime("$in $in_time"));

			$postdata=array(
				//'bussiness_id'=>$postdata['name'],
				//'user_id'=>$postdata['mobile'],
				//'io_time'=>strtotime("$in_time"),

				'io_time'=>strtotime("$in $in_times"),
				'date'=>strtotime("$in $in_times"),
				'user_id'=>"$id",
				'bussiness_id'=>"$buid",
				'mode'=>"in",
				'manual'=>"1",
				'verified'=>"1",
				'status'=>"1"

			);
			$data=$this->db->insert('attendance',$postdata);
		}else{
			redirect('user-login');
		}
	}




	public function changeOutToDate(){
		if (!empty($this->session->userdata('id'))) {
			$i = $this->input->post("i");
			$buid = $this->input->post("buid");
			$id = $this->input->post("id");
			$out_times = $this->input->post("out_times");
			$in=date("Y-m-d",$i);
			//$io_time=date('Y-m-d H-i-s',strtotime("$in $in_time"));

			$postdata=array(
				//'bussiness_id'=>$postdata['name'],
				//'user_id'=>$postdata['mobile'],
				//'io_time'=>strtotime("$in_time"),

				'io_time'=>strtotime("$in $out_times"),
				'date'=>strtotime("$in $in_times"),
				'user_id'=>"$id",
				'bussiness_id'=>"$buid",
				'mode'=>"out",
				'manual'=>"1",
				'verified'=>"1",
				'status'=>"1"

			);
			$data=$this->db->insert('attendance',$postdata);
		}else{
			redirect('user-login');
		}
	}




	public function aproveUser(){
		if (!empty($this->session->userdata('id'))) {
			$id = $this->input->post('id');
			$uid = $this->input->post('uid');
			$fromdate = $this->input->post('fromdate');
			$uname = $this->web->getNameByUserId($uid);
			$res= $this->web->statusaprove($id);
			if ($res) {
			    	 if ($this->session->userdata()['type'] == 'P') {
          
          $loginId = $this->session->userdata('empCompany');
         // $role=$this->web->getRollbyid($this->web->session->userdata('login_id'),$loginId);
        } else {
          $loginId = $this->web->session->userdata('login_id');
        }
			$actdata=array(
			                            'bid'=>$loginId ,
				                        'uid'=>$this->web->session->userdata('login_id'),
				                        'activity'=>"employee ".$uname[0]->name. " Leave Apoved  for date ".$fromdate."",
				                        'date_time'=>time()
				
			                             );
			                  $data=$this->db->insert('activity',$actdata);
				echo $id;
				return($id);
			}
		} else {
			redirect('user-login');
		}
	}

	public function rejectUser(){
		if (!empty($this->session->userdata('id'))) {
			$id = $this->input->post('id');
				$uid = $this->input->post('uid');
			$fromdate = $this->input->post('fromdate');
			$res= $this->web->statusreject($id);
			if ($res) {
			    	 if ($this->session->userdata()['type'] == 'P') {
          
          $loginId = $this->session->userdata('empCompany');
         // $role=$this->web->getRollbyid($this->web->session->userdata('login_id'),$loginId);
        } else {
          $loginId = $this->web->session->userdata('login_id');
        }
        $uname = $this->web->getNameByUserId($uid);
			$actdata=array(
			                            'bid'=>$loginId ,
				                        'uid'=>$this->web->session->userdata('login_id'),
				                        'activity'=>"employee ".$uname[0]->name. " Leave Rejected  for date ".$fromdate."",
				                        'date_time'=>time()
				
			                             );
			                  $data=$this->db->insert('activity',$actdata);
				echo $id;
				return($id);
			}
		} else {
			redirect('user-login');
		}
	}




	









/*  GET PAYROLL HISTORY  */


public function switchAccount(){
	if (!empty($this->session->userdata('id'))) {
		$id = $this->input->post('id');
		$linked = $this->session->userdata('linked');
		if(!empty($linked)){
			foreach($linked as $account){
				if($account['login_id']==$id){
					$this->session->set_userdata($account);
				}
			}
		}
	} else {
		redirect('user-login');
	}
}

/////////////////////////new/////



	public function getRoll(){
		if(!empty($this->session->userdata('id'))){
			$this->load->view('attendance/roll');
		}
		else{
			redirect('user-login');
		}
	}


	
	public function emp_roll(){
		if(!empty($this->session->userdata('id'))){
			//$id = $this->input->post("id");
		//	$this->load->view('attendance/editemployees',$id);
			//$id = $_post['id'];
			//$id = $this->input->post('id');
			//$res= $this->web->statusInctivate($id);
			//$this->load->view('users/users',$data);
			
			   //$val=$this->web->getNameByUserId($id);
			
			//$data = $this->input->post('id');
			//$val = $this->web->getNameByUserId($id);
			//$data['value'] = $dep;
			//$data['option'] = 'edit_dep';
			
			$data=array(
				'rules'=>$this->web->getAttendanceRules($this->session->userdata('login_id')),
			);
			$this->load->view('attendance/roll');
		}
		
		else{
			redirect('user-login');
		}
	}
	
	
	
	
	
	





	public function switchCompany(){
		if (!empty($this->session->userdata('id'))) {
			$id = $this->input->post('id');
			$this->session->set_userdata('empCompany',$id);
		} else {
			redirect('user-login');
		}
	}
	
/////new admin arpit june-23 ////
		
		



	
	

//// hostel//


public function student_list(){
			if(!empty($this->session->userdata('id'))){
				$this->load->view('hostel/students');
			}
			else{
				redirect('user-login');
			}
		}


		public function editstudent(){
			if(!empty($this->session->userdata('id'))){
				
				$this->load->view('hostel/editstudent');
			}

			else{
				redirect('user-login');
			}
		}
		


		
		
		
		



public function updatestudent(){
		if(!empty($this->session->userdata('id'))){
			echo $id=$_POST['id'];
			echo $bid=$_POST['bid'];
			echo $edu = $_POST['edu'];
			echo $name = $_POST['name'];
			echo $email = $_POST['email'];
			echo $address = $_POST['address'];
			echo $block = $_POST['block'];
			echo $dob = $_POST['dob'];
			echo $gender = $_POST['gender'];
		    echo $floor = $_POST['floor'];
		    echo $room = $_POST['room'];
		    echo $roomtype = $_POST['roomtype'];
			echo $parent = $_POST['parent'];
			echo $parent_mobile = $_POST['parent_mobile'];
			echo $parent_relation = $_POST['parent_relation'];
			echo $doj = strtotime($_POST['doj']);
			echo $dol = strtotime($_POST['dol']);
			echo $bio_id = $_POST['bio_id'];
			//echo $trf =$_POST['trf'];
			//echo $group = $_POST['group'];
			$data=array(
						'name' => $name,
						'email' => $email,
						'address' => $address,
						//'emp_code' => $empcode,
						'gender' => $gender,
						//'designation' => $desig,
						'education' => $edu,
						//'manager' => $post,
						'doj' => $doj,
						'bio_id' => $bio_id,
						'dob' => $dob,
						//'business_group' => $group,
						//'department' => $department
				
					);
			//$data=$this->db->update('login',$postdata);
			$this->db->where('id',$id);
			$data= $this->db->update('login',$data);
			if($dol!=''){
			
			
			$ldata=array('doj' => $doj,
					     'left_date' => $dol
						
						);
			$this->db->where('user_id',$id);
			$data= $this->db->update('user_request',$ldata);
			}
						
			$tdata=array(
			             'floor' => $floor,
			             'room_no' => $room,
						 'room_type' => $roomtype,
						 'block' => $block,
			             'parent_name' => $parent,
						 'parent_relation' => $parent_relation,
						 'parent_mobile' => $parent_mobile,
						  'bid' => $bid,
						  'date_time' =>time()
						);			
			$this->db->where('uid',$id);
			$tdata= $this->db->update('hostel_detail',$tdata);
			
			
		
			
				$this->session->set_flashdata('msg','Student Updated Successfully!');
				redirect('student_list');
			
		}
		else{
			redirect('user-login');
		}
	}


public function hostel_detail(){
			if(!empty($this->session->userdata('id'))){
				$this->load->view('hostel/hostel_detail');
			}
			else{
				redirect('user-login');
			}
		}


public function addblock(){
		if(!empty($this->session->userdata('id'))){
			$postdata=$this->input->post();
			$postdata=array(
				'name'=>$postdata['block'],
				'bid'=>$postdata['bid'],
				'date_time'=> time(),
				'status'=> '1'
				
			);
			$data=$this->db->insert('blocks',$postdata);
			if($data > 0){
				$this->session->set_flashdata('msg','New Block Added!');
				redirect('hostel_detail');
			}
		}
		else{
			redirect('user-login');
		}
	}
	
	public function addroomtype(){
		if(!empty($this->session->userdata('id'))){
			$postdata=$this->input->post();
			$postdata=array(
				'name'=>$postdata['roomtype'],
				'bid'=>$postdata['bid'],
				'date_time'=> time(),
				'status'=> '1'
				
			);
			$data=$this->db->insert('room_types',$postdata);
			if($data > 0){
				$this->session->set_flashdata('msg','New Room types  Added!');
				redirect('hostel_detail');
			}
		}
		else{
			redirect('user-login');
		}
	}
	
	public function editblock(){
		if(!empty($this->session->userdata('id'))){
			$check=$_REQUEST;
			print_r($check);
			echo $name = $_POST['name'];
			echo $id = $_POST['id'];
			$data = array(
				'name' => $name
				
			);
			print_r($data);
			$this->db->where('id',$id);
			$res = $this->db->update('blocks',$data);
			echo $res;
			return($res);
		}
		else{
			redirect('user-login');
		}
	}
	
	
	public function editroomtype(){
		if(!empty($this->session->userdata('id'))){
			$check=$_REQUEST;
			print_r($check);
			echo $name = $_POST['name'];
			echo $id = $_POST['id'];
			$data = array(
				'name' => $name
				
			);
			print_r($data);
			$this->db->where('id',$id);
			$res = $this->db->update('room_types',$data);
			echo $res;
			return($res);
		}
		else{
			redirect('user-login');
		}
	}
	
	
	
	
	
		
	public function hostel_daily_report(){
			if(!empty($this->session->userdata('id'))){
				
				$postdata=$this->input->post();
					$start_date = date("Y-m-d");
					$true = 0;
					$days_array = array();
					$new_array = array();
				if ($this->session->userdata()['type'] == 'P') {
					$loginId = $this->session->userdata('empCompany');
					$role=$this->web->getRollbyid($this->web->session->userdata('login_id'),$loginId);
					} else {
					$loginId = $this->web->session->userdata('login_id');
					}
					
					$cmpName = $this->web->getBusinessById($loginId);
					$action="active";
					if(isset($postdata['start_date'])){
					$start_date = $postdata['start_date'];
					$action = $postdata['action'];
					}
					$true= 1;
					$totalActive = 0;
					$totalPresent = 0;
					$totalAbsent = 0;
					//$totalMispunch = 0;
					$users_data = $this->web->getCompanyUsers($loginId);
					$start_time = strtotime(date("d-m-Y 00:00:00",strtotime($start_date)));
					$end_time = strtotime(date("d-m-Y 23:59:59",strtotime($start_date)));
					if(!empty($users_data)){
						$seconds = 0;
						foreach($users_data as $user){
					     if($user->hostel=="1"){
					$days_array[]= date("d",$start_time);
					$data = array();
					$day_hrs = "00:00 Hr";
					
				if(($user->doj!="" || $start_time>=$user->doj) && ($user->left_date=="" || $start_time<$user->left_date)){
											$totalActive++;
									$user_at = $this->web->getUserAttendanceReportByDate($start_time,$end_time,$user->user_id,$loginId,1);
					
					
					
					if(!empty($user_at)){
												$totalPresent++;
												$ins_array = array();
												$outs_array = array();
												$comment_array = array();
												$user_at = array_reverse($user_at);
												foreach($user_at as $at){
												   $timeSearch = array_search($at->io_time,array_column($data,'time'));
													if(is_bool($timeSearch)){
    													$data[] = array(
    														'mode'=>$at->mode,
    														'time'=>$at->io_time,
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
												
											
													$day_seconds = $data[count($data)-1]['time']-$data[0]['time'];
												$hours = floor($day_seconds / 3600);
												$minutes = floor($day_seconds / 60%60);
												$day_hrs = "$hours:$minutes Hr";

					                                 $comment_array = $at->comment;
													}}
													
												}
												//userat
												
												else{
												$totalAbsent++;
												$data = array();
											}
					
					$new_array[] =array(
													'user_id'=>$user->user_id,
													'mid'=>$user->mid,
													'name'=>$user->name,
													'image'=>$user->image,
													'comment'=>$comment,
													'workingHrs'=>$day_hrs,
													'data'=>$data,
													
												);
					
					}
						}
					}
					}
					
			$data=array(
						'start_date'=>$start_date,
						//'end_date'=>$end_date,
						'load'=>$true,
						'report'=>$new_array,
						'days'=>$days_array,
						'totalActive'=>$totalActive,
						'totalAbsent'=>$totalAbsent,
						'totalPresent'=>$totalPresent,
						'cmp_name'=>$cmpName['name']
					);		
					
				
				
				$this->load->view('hostel/hostel_dailyreport',$data);
			}
			else{
				redirect('user-login');
			}
		}	
		
		
		
		
		
		
		
	public function hostel_monthly_report(){
			if(!empty($this->session->userdata('id'))){
				
				$postdata=$this->input->post();
					$start_date = date("Y-m-d");
					$end_date = date("Y-m-d");
					$true = 0;
				    $days_array = array();
					$new_array = array();
				if ($this->session->userdata()['type'] == 'P') {
					$loginId = $this->session->userdata('empCompany');
					$role=$this->web->getRollbyid($this->web->session->userdata('login_id'),$loginId);
					} else {
					$loginId = $this->web->session->userdata('login_id');
					}
					
					$cmpName = $this->web->getBusinessById($loginId);
					//$action="active";
					if(isset($postdata['start_date']) && isset($postdata['end_date'])){
				$start_date = $postdata['start_date'];
				$end_date = $postdata['end_date'];
					//$action = $postdata['action'];
					
					$true= 1;
					
					//$totalMispunch = 0;
					$users_data = $this->web->getCompanyUsers($loginId);
				$start_time = strtotime(date("d-m-Y 00:00:00",strtotime($start_date)));
				$end_time = strtotime(date("d-m-Y 23:59:59",strtotime($end_date)));

					if(!empty($users_data)){
						//$seconds = 0;
						foreach($users_data as $user){
							if($user->hostel=="1"){
							
						$date1=date_create(date("Y-m-d",strtotime($start_date)));
									$date2=date_create(date("Y-m-d",strtotime($end_date)));
									$diff=date_diff($date1,$date2);

									$num_month = $diff->format("%a");

									$num_month++;
									if($num_month>31){
										$num_month=31;
									}	
							
							$months_array = array();
							$days_array = array();
						//	
             // $monthStartTime = strtotime(date("d-m-Y 00:00:00",strtotime($mid->checkon->datefrom)));
            //  $monthEndTime = strtotime(date("d-m-Y 23:59:59",strtotime($mid->checkon->datefrom))." +".$num_month." days");
		                           $monthStartTime = strtotime(date("d-m-Y 00:00:00",strtotime($start_date)));
									$monthEndTime = strtotime(date("d-m-Y 23:59:59",strtotime($start_date))." +".$num_month." days");
			$monthUserAt = $this->web->getUserAttendanceReportByDate($monthStartTime,$monthEndTime,$user->user_id,$loginId,1);
											
			               // $monthUserAt = $this->app->getUserAttendanceReportByDate($monthStartTime,$monthEndTime,$user->user_id,$check['id'],1);
              for($d=0; $d<$num_month;$d++){
               $new_start_time = strtotime(date("d-m-Y 00:00:00",strtotime($start_date))." +".$d." days");
				$new_end_time = strtotime(date("d-m-Y 23:59:59",strtotime($start_date))." +".$d." days");
                $days_array[]= date("d",$new_start_time);
                $data = array();
				
				
				if(($user->doj!="" || strtotime($start_date)>=$user->doj) && ($user->left_date=="" || strtotime($start_date)<$user->left_date)){
											$user_at = array_filter($monthUserAt, function($val) use($new_start_time, $new_end_time){
												return ($val->io_time>=$new_start_time and $val->io_time<=$new_end_time);
											});
											$user_at = array_reverse($user_at);
											
											
                    if(!empty($user_at)){
                      foreach($user_at as $at){
                        if($at->hostel=="1"){
                          $data[] = array(
                            'mode'=>$at->mode,
                            'time'=>$at->io_time,
                            'comment'=>$at->comment
                          );
                        }
                      }
                    }else{
                      $data = array();
                    }
				
				$months_array[] = array(
                      'date'=>date("j",$new_start_time),
                      'day'=>date("l",$new_start_time),
                      'data'=>$data
                    );
                }
              }
			  
			  
			  
			  if(count($months_array)>0){
                    $new_array[] =array(
                    'user_id'=>$user->user_id,
                    'mid'=>$user->mid,
                    'emp_code'=>$user->emp_code,
                    'name'=>$user->name,
                    'image'=>$user->image,
                    'user_status'=>$user->user_status,
                    'data'=> $months_array
                  );
              }
				
			  
					
				// close users and post		
					}
					}}
					}
					
			$data=array(
						'start_date'=>$start_date,
						'end_date'=>$end_date,
						'load'=>$true,
						'report'=>$new_array,
						'days'=>$days_array,
						
						'cmp_name'=>$cmpName['name']
					);	
					
			
				
				$this->load->view('hostel/hostel_monthly_report',$data);
			}
			else{
				redirect('user-login');
			}
		}	
			



	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
///////till new	
		

	
/////new emp detail
	
	
///login
	
	
	




public function updateemployeedetail(){
		if(!empty($this->session->userdata('id'))){
			echo $id=$_POST['id'];
			echo $bid=$_POST['bid'];
			//echo $uid = $_POST['uid'];
			echo $pay_mode = $_POST['pay_mode'];
			echo $bank_name = $_POST['bank_name'];
			echo $ifsc_code = $_POST['ifsc_code'];
		    echo $account_no = $_POST['account_no'];
			echo $upi = $_POST['upi'];
			echo $pan = $_POST['pan'];
			echo $adhar = $_POST['adhar'];
			echo $epf = $_POST['epf'];
			echo $uan = $_POST['uan'];
			echo $esic = $_POST['esic'];
			
			$data=array(
						//'uid' => $id,
						//'bid' => $bid,
						'pay_mode' => $pay_mode,
						'bank_name' => $bank_name,
						'ifsc_code' => $ifsc_code,
						'account_no' => $account_no,
						'upi' => $upi,
						'pan' => $pan,
						'adhar' => $adhar,
						'epf' => $epf,
						'uan' => $uan,
						'esic' => $esic
				);
			$detail=$this->web->getstaffinfoByUserId($id,$bid);
			if(!empty($detail)){
				$detailid=$detail[0]->id;
				
			$this->db->where('id',$detailid);
			$udata= $this->db->update('staff_detail',$data);
		//$uname = $this->web->getNameByUserId($uid);
				 if ($this->session->userdata()['type'] == 'P') {
          
          $loginId = $this->session->userdata('empCompany');
        
        } else {
          $loginId = $this->web->session->userdata('login_id');
        }
			$actdata=array(
			                            'bid'=>$loginId ,
				                        'uid'=>$this->web->session->userdata('login_id'),
				                        'activity'=>"Employee Data updated ",
				                        'date_time'=>time()
				
			                             );
			                  $data=$this->db->insert('activity',$actdata);
			}else{
				
			$newdata=array(
						'uid' => $id,
						'bid' => $bid,
						'pay_mode' => $pay_mode,
						'bank_name' => $bank_name,
						'ifsc_code' => $ifsc_code,
						'account_no' => $account_no,
						'upi' => $upi,
						'pan' => $pan,
						'adhar' => $adhar,
						'epf' => $epf,
						'uan' => $uan,
						'esic' => $esic
				);	
				
		  $tdata= $this->db->insert('staff_detail',$newdata);
		  $uname = $this->web->getNameByUserId($id);
				 if ($this->session->userdata()['type'] == 'P') {
          
          $loginId = $this->session->userdata('empCompany');
        
        } else {

          $loginId = $this->web->session->userdata('login_id');
        }
			$actdata=array(
			                            'bid'=>$loginId ,
				                        'uid'=>$this->web->session->userdata('login_id'),
				                        'activity'=>"Employee data of employee ".$uname[0]->name. " Added",
				                        'date_time'=>time()
				
			                             );
			                  $data=$this->db->insert('activity',$actdata);
			}
			
			$this->session->set_flashdata('msg','Employee Updated Successfully!');
				redirect('employees');
			
		}
		else{
			redirect('user-login');
		}
	}




public function dashboard_hostel(){
		if(!empty($this->session->userdata('id'))){
			
			$this->load->view('hostel/hostel_dashboard');
		}
		else{
			redirect('user-login');
		}
	}



	

	
public function update_password(){
		if (!empty($this->session->userdata('id'))) {
			$id = $this->session->userdata('id');
			$opass = $this->input->post('opass');
			$npass = $this->input->post('npass');
			$cnpass = $this->input->post('cnpass');
			$check = $this->web->checkOPass($id,md5($opass));
			if (!empty($check)) {
				if($npass === $cnpass){
					$res = $this->web->upPass($id,md5($npass));
					if ($res) {
						$this->session->set_flashdata('msg','Password updated successfully!');
						redirect('page_staff');
					}
				}else{
					$this->session->set_flashdata('msg','Confirm password does not match!');
					redirect('User/staffPass');
				}
			}else{
				$this->session->set_flashdata('msg','Incorrect old password!');
				redirect('User/staffPass');
			}
		}else{
			redirect('user-login');
		}
	}
	
	
///// new student code starts


//// live finis
public function activatecstudent(){
		if (!empty($this->session->userdata('id'))) {
			$id = $this->input->post('id');
			echo $bid=$this->web->session->userdata('login_id');
			$uname = $this->web->getNameByUserId($id);
			echo $bioid=$uname[0]->bio_id;
			$res=$this->web->getdevice($bid);
			echo $devid=$res[0]->deviceid;
          $res= $this->web->Activatecstudent($devid,$bioid,$id);
			if ($res) {
				echo $id;
				return($id);
			}
		} else {
			redirect('user-login');
		}

	}
	public function inactivatecstudent(){
		if (!empty($this->session->userdata('id'))) {
			$id = $this->input->post('id');
			$bid=$this->web->session->userdata('login_id');
			$uname = $this->web->getNameByUserId($id);
			$bioid=$uname[0]->bio_id;
			$res=$this->web->getdevice($bid);
			$devid=$res[0]->deviceid;
          $res= $this->web->Inactivatecstudent($devid,$bioid,$id);
   
			if ($res) {
				echo $id;
				return($id);
			}
		} else {
			redirect('user-login');
		}

	}
	
		public function device_access_att(){
			if(!empty($this->session->userdata('id'))){
				$this->load->view('attendance/device_access');
			}
			else{
				redirect('user-login');
			}
		}
		
			public function device_access_admin(){
			if(!empty($this->session->userdata('id'))){
				$this->load->view('attendance/device_access_admin');
			}
			else{
				redirect('user-login');
			}
		}
		
		
		public function bio_detail(){
			if(!empty($this->session->userdata('id'))){
				$this->load->view('users/bio_detail');
			}
			else{
				redirect('user-login');
			}
		}
		
	/// student attendance new///
	
	
	public function dashboard_school(){
		if(!empty($this->session->userdata('id'))){
			//$data['bookappoinment']=$this->web->GetBookCount();
			//$data['counter']=$this->web->GetCountersCount();
			$data['count']=$this->web->GetUsersCount();
			$this->load->view('student/school_dashboard',$data);
		}
		else{
			redirect('user-login');
		}
	}


	public function add_Students(){
		if(!empty($this->session->userdata('id'))){
			$this->load->view('student/addstudents');
		}
		else{
			redirect('user-login');
		}
	}
	
	
		public function Students_list(){
			if(!empty($this->session->userdata('id'))){
			 	$postdata=$this->input->post();
		//	$start_date = date("Y-m-d");
		//	$end_date = date("Y-m-d");
			$sid="";
			$true = 0;  
			if(isset($postdata['dept'])){
			//	$start_date = $postdata['start_date'];
			//	$end_date = $postdata['end_date'];
					$dept = $postdata['dept'];
				$session = $postdata['session'];
				$section = $postdata['section'];
				$true= 1;
			}
			
			$data=array(
				//'start_date'=>$start_date,
			//	'end_date'=>$end_date,
				'dept'=>$dept,
				'session'=>$session,
				'section'=>$section,
				'load'=>$true
			);
		//	$this->load->view('attendance/manual',$data);  
			    
			    
			    
			    
				$this->load->view('student/students_list',$data);
			}
			else{
				redirect('user-login');
			}
		}


public function addnew_S_student(){
			if(!empty($this->session->userdata('id'))){
				if($this->session->userdata()['type']=='P'){
					$uid = $this->session->userdata('empCompany');
				} else {
					$uid=$this->web->session->userdata('login_id');
				}
			
				$postdata=$this->input->post();
				
				$i='upload/nextpng.png';

				$postdata=array(
					'bid'=>$postdata['bid'],
					'name'=>$postdata['name'],
					'enroll_id'=>trim($postdata['mobile']),
					'address'=>$postdata['address'],
					'roll_no'=>$postdata['rollno'],
					'student_code'=>$postdata['stuid'],
					'dob'=>$postdata['dob'],
					'bio_id'=>$postdata['devcode'],
					'rfid'=>$postdata['rfid'],
					'blood_group'=>$postdata['blood'],
					'image'=>$i,
					'gender'=>$postdata['gender'],
					'class_id'=>$postdata['class'],
					
						'section'=>$postdata['section'],
							'batch'=>$postdata['batch'],
								'semester'=>$postdata['semester'],
									'session'=>$postdata['session'],
										'department'=>$postdata['department'],
											'email'=>$postdata['email'],
				   'doj'=>strtotime($postdata['doj']),
				  'parent_name'=>$postdata['par_name'],
				   'parent_mobile'=>$postdata['par_mobile'],
				   'parent_relation'=>$postdata['relation'],
					'status'=>1,
					'date_time'=>time()
				
				);
				$data=$this->db->insert('student',$postdata);
				//$id = $this->db->insert_id();
  
					$this->session->set_flashdata('msg','New Student Added!');
					redirect('Students_list');
				
			}
			else{
				redirect('user-login');
			}
		}





	

		public function edit_S_student(){
			if(!empty($this->session->userdata('id'))){
				
				$this->load->view('student/editstudents');
			}

			else{
				redirect('user-login');
			}
		}
		


		
		
		
		



public function update_S_student(){
		if(!empty($this->session->userdata('id'))){
			echo $id=$_POST['id'];
			echo $bid=$_POST['bid'];
			echo $enroll_id = $_POST['mobile'];
			echo $name = $_POST['name'];
			echo $roll_no = $_POST['roll_no'];
			echo $address = $_POST['address'];
			echo $class = $_POST['class'];
			echo $dob = $_POST['dob'];
			echo $gender = $_POST['gender'];
		    echo $student_code = $_POST['student_code'];
		   	echo $parent_name = $_POST['parent_name'];
			echo $parent_mobile = $_POST['parent_mobile'];
			echo $parent_relation = $_POST['parent_relation'];
			echo $doj = strtotime($_POST['doj']);
			echo $dol = strtotime($_POST['dol']);
			echo $bio_id = $_POST['bio_id'];
			echo $rfid = $_POST['rfid'];
			//echo $trf =$_POST['trf'];
			//echo $group = $_POST['group'];
			$data=array(
						'name' => $name,
						'roll_no' => $roll_no,
						'address' => $address,
						'student_code' => $student_code,
						'gender' => $gender,
						'class_id' => $class,
						'department' =>$_POST['department'],
						'section' =>$_POST['section'],
						'session' =>$_POST['session'],
						'batch' =>$_POST['batch'],
						'semester' =>$_POST['semester'],
						'email' =>$_POST['email'],
						
						
						
						'parent_name' => $parent_name,
						'parent_mobile' => $parent_mobile,
						'parent_relation' => $parent_relation,
						//'manager' => $post,
						'doj' => $doj,
						//'left_date' => $dol,
						'bio_id' => $bio_id,
						'rfid' => $rfid,
						'dob' => $dob,
						//'business_group' => $group,
						//'department' => $department
				
					);
			//$data=$this->db->update('login',$postdata);
			$this->db->where('id',$id);
			$data= $this->db->update('student',$data);
			
		
				$this->session->set_flashdata('msg','Student Updated Successfully!');
				redirect('Students_list');
			
		}
		else{
			redirect('user-login');
		}
	}


	public function Exstudents_list(){
		if(!empty($this->session->userdata('id'))){
			$this->load->view('student/Exstudents');
		}
		else{
			redirect('user-login');
		}
	}



	public function students_daily_report(){
		if(!empty($this->session->userdata('id'))){
			
			$postdata=$this->input->post();
				$start_date = date("Y-m-d");
			//	$class=0;
				$true = 0;
					$totalActive = 0;
				$totalPresent = 0;
				$totalAbsent = 0;
				$days_array = array();
				$new_array = array();
			if ($this->session->userdata()['type'] == 'P') {
				$loginId = $this->session->userdata('empCompany');
				$role=$this->web->getRollbyid($this->web->session->userdata('login_id'),$loginId);
				} else {
				$loginId = $this->web->session->userdata('login_id');
				}
				
				$cmpName = $this->web->getBusinessById($loginId);
				$action="active";
				if(isset($postdata['start_date']) && isset($postdata['dept']) ){
				$start_date = $postdata['start_date'];
				$dept = $postdata['dept'];
				$session = $postdata['session'];
				$section = $postdata['section'];
				$true= 1;
				$action = $postdata['action'];
			
				}
				//$totalMispunch = 0;
				$users_data = $this->web->getSchoolStudentListbysection($loginId,$dept,$session,$section);
				$start_time = strtotime(date("d-m-Y 00:00:00",strtotime($start_date)));
				$end_time = strtotime(date("d-m-Y 23:59:59",strtotime($start_date)));
				if(!empty($users_data)){
					$seconds = 0;
					foreach($users_data as $user){
					// if($user->hostel=="1"){
				$days_array[]= date("d",$start_time);
				$data = array();
				$day_hrs = "00:00 Hr";
				
			if(($user->doj!="" || $start_time>=$user->doj) && ($user->left_date=="" || $start_time<$user->left_date)){
										$totalActive++;
								$user_at = $this->web->getStudentAttendanceReportByDate($start_time,$end_time,$user->id,$loginId);
				
				
				
				if(!empty($user_at)){
											$totalPresent++;
									
											foreach($user_at as $at){
											 
											    
												$data[] = array(
														'mode'=>$at->student_status,
														'time'=>$at->time,
														'device'=>$at->device,
														'class'=>$at->class_id,
														'Att_status'=>"P"
														
													);    
											    
											    	$Attstatus="P";	   
											    
											}
												
											}
											//userat
											
											else{
											$totalAbsent++;
											$data = array(
											    	//'Att_status'=>"A"
											    );
										$Attstatus="A";	    
										}
				  if(($action=="active")||($action=="present" && count($data)>0)||($action=="absent" && empty($data))){
				$new_array[] =array(
												'user_id'=>$user->id,
												//'mid'=>$user->mid,
												'name'=>$user->name,
												'Att_status'=>$Attstatus,
												
												//'image'=>$user->image,
												//'comment'=>$comment,
												//'workingHrs'=>$day_hrs,
												'data'=>$data,
												
											);
				  }
				
				}
				//	}
				}
				}
				
		$data=array(
					'start_date'=>$start_date,
					//'end_date'=>$end_date,
						'dept'=>$dept,
							'session'=>$session,
								'section'=>$section,
					'load'=>$true,
					'report'=>$new_array,
				//	'days'=>$days_array,
					'totalActive'=>$totalActive,
					'class'=>$class,
					'totalAbsent'=>$totalAbsent,
					'totalPresent'=>$totalPresent,
					'cmp_name'=>$cmpName['name']
				);		
				
			
			
			$this->load->view('student/students_dailyreport',$data);
		}
		else{
			redirect('user-login');
		}
	}	
	
	
	
	
	
	
	
public function students_monthly_report(){
		if(!empty($this->session->userdata('id'))){
			
			$postdata=$this->input->post();
				$start_date = date("Y-m-d");
				$end_date = date("Y-m-d");
				$dept=0;
				$session=0;
				$section=0;
				$true = 0;
				$days_array = array();
				$new_array = array();
			if ($this->session->userdata()['type'] == 'P') {
				$loginId = $this->session->userdata('empCompany');
				$role=$this->web->getRollbyid($this->web->session->userdata('login_id'),$loginId);
				} else {
				$loginId = $this->web->session->userdata('login_id');
				}
				
				$cmpName = $this->web->getBusinessById($loginId);
				//$action="active";
				if(isset($postdata['start_date']) && isset($postdata['end_date'])){
			$start_date = $postdata['start_date'];
			$end_date = $postdata['end_date'];
				//$action = $postdata['action'];
					$dept = $postdata['dept'];
				$session = $postdata['session'];
				$section = $postdata['section'];
				
				$true= 1;
				
				//$totalMispunch = 0;
			//	$users_data = $this->web->getSchoolStudentList($loginId);
					$users_data = $this->web->getSchoolStudentListbysection($loginId,$dept,$session,$section);
			$start_time = strtotime(date("d-m-Y 00:00:00",strtotime($start_date)));
			$end_time = strtotime(date("d-m-Y 23:59:59",strtotime($end_date)));

				if(!empty($users_data)){
					//$seconds = 0;
					foreach($users_data as $user){
					//	if($user->hostel=="1"){
						
					$date1=date_create(date("Y-m-d",strtotime($start_date)));
								$date2=date_create(date("Y-m-d",strtotime($end_date)));
								$diff=date_diff($date1,$date2);
								$num_month = $diff->format("%a");

								$num_month++;
								if($num_month>31){
									$num_month=31;
								}	
						
						$months_array = array();
						$days_array = array();
					//	
		 // $monthStartTime = strtotime(date("d-m-Y 00:00:00",strtotime($mid->checkon->datefrom)));
		//  $monthEndTime = strtotime(date("d-m-Y 23:59:59",strtotime($mid->checkon->datefrom))." +".$num_month." days");
							   $monthStartTime = strtotime(date("d-m-Y 00:00:00",strtotime($start_date)));
								$monthEndTime = strtotime(date("d-m-Y 23:59:59",strtotime($start_date))." +".$num_month." days");
							//	$monthUserAt = $this->app->getUserAttendanceReportByDate($monthStartTime,$monthEndTime,$user->user_id,$loginId,1);
									$monthUserAt= $this->web->getStudentAttendanceReportByDate($monthStartTime,$monthEndTime,$user->id,$loginId);
										
					   // $monthUserAt = $this->app->getUserAttendanceReportByDate($monthStartTime,$monthEndTime,$user->user_id,$check['id'],1);
		  for($d=0; $d<$num_month;$d++){
		   $new_start_time = strtotime(date("d-m-Y 00:00:00",strtotime($start_date))." +".$d." days");
			$new_end_time = strtotime(date("d-m-Y 23:59:59",strtotime($start_date))." +".$d." days");
			$days_array[]= date("d",$new_start_time);
			$data = array();
			
			
		//	if(($user->doj!="" || strtotime($start_date)>=$user->doj) && ($user->left_date=="" || strtotime($start_date)<$user->left_date)){
										$user_at = array_filter($monthUserAt, function($val) use($new_start_time, $new_end_time){
											return ($val->time>=$new_start_time and $val->time<=$new_end_time);
										});
										$user_at = array_reverse($user_at);
										
										
				if(!empty($user_at)){
				  foreach($user_at as $at){
				//	if($at->hostel=="1"){
					  $data[] = array(
					//	'mode'=>$at->mode,
						'time'=>$at->time,
					//	'comment'=>$at->comment
					  );
				//	}
				  }
				}else{
				  $data = array();
				}
			
			$months_array[] = array(
				  'date'=>date("j",$new_start_time),
				  'day'=>date("l",$new_start_time),
				  'data'=>$data
				);
		//	}
		  }
		  
		  
		  
		  if(count($months_array)>0){
				$new_array[] =array(
				'user_id'=>$user->id,
			//	'mid'=>$user->mid,
			//	'emp_code'=>$user->emp_code,
				'name'=>$user->name,
			//	'image'=>$user->image,
			////	'user_status'=>$user->user_status,
				'data'=> $months_array
			  );
		  }
			
		  
				
			// close users and post		
			//	}
				}}
				}
				
		$data=array(
					'start_date'=>$start_date,
					'end_date'=>$end_date,
						'dept'=>$dept,
							'session'=>$session,
								'section'=>$section,
					'load'=>$true,
					'report'=>$new_array,
					'days'=>$days_array,
					
					'cmp_name'=>$cmpName['name']
				);	
				
		
			
			$this->load->view('student/students_monthly_report',$data);
		}
		else{
			redirect('user-login');
		}
	}	
		
	public function students_report(){
		if(!empty($this->session->userdata('id'))){
			$postdata=$this->input->post();
			$start_date = date("Y-m-d");
			$end_date = date("Y-m-d");
			$true = 0;
			$option= "all";
			$days_array = array();
			$new_array = array();
			// $loginId = $this->session->userdata('login_id');
			// if($this->session->userdata('type')=="P"){
			// 	$userCmp = $this->app->getUserCompany($loginId);
			// 	if(isset($userCmp) && ($userCmp['left_date']=="" || $userCmp['left_date']>time())){
			// 		$loginId = $userCmp['business_id'];
			// 	}
			// }
			if ($this->session->userdata()['type'] == 'P') {
				$loginId = $this->session->userdata('empCompany');
				$role=$this->web->getRollbyid($this->web->session->userdata('login_id'),$loginId);
			} else {
				$loginId = $this->web->session->userdata('login_id');
			}					
			$cmpName = $this->web->getBusinessById($loginId);

			if(isset($postdata['start_date']) && isset($postdata['end_date'])){
				$start_date = $postdata['start_date'];
				$end_date = $postdata['end_date'];
				$empId = $postdata['emp'];
				$option = $postdata['option'];
				$true= 1;
				$users_data = $this->web->getSchoolStudentList($loginId);
				$start_time = strtotime(date("d-m-Y 00:00:00",strtotime($start_date)));
				$end_time = strtotime(date("d-m-Y 23:59:59",strtotime($end_date)));

				$holidays = $this->app->getHoliday($loginId);
				$holiday_array = array();
				if($holidays){
					foreach($holidays as $holiday){
						$holiday_array[] = array(
							'date'=>date('d.m.Y',$holiday->date),
						);
					}
				}

			

				if(!empty($users_data)){
					foreach($users_data as $user){
						if($user->user_id==$empId || $empId=="0"){
							$date1=date_create(date("Y-m-d",strtotime($start_date)));
							$date2=date_create(date("Y-m-d",strtotime($end_date)));
							$diff=date_diff($date1,$date2);
							$num_month = $diff->format("%a");

							$num_month++;
							if($num_month>31){
								//$num_month=31;
							}

							$groups = $this->app->getUserGroup($user->business_group);
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
								foreach($weekly_off as $key=>$off){
									if($off==1){
										$grp[] = array(
											'day_off'=>$key+1
										);
									}
								}
							}else{
								$shift_start = "";
								$shift_end = "";
								$group_name = "";
							}

							$leaves = $this->app->getEmpLeaves($user->user_id);
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

							$rules = $this->web->getRule($loginId,$user->rule_id);
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
							$totalWorkingHrs = "00:00 Hr";
							$totalLate = "00:00 Hr";
							$totalEarly = "00:00 Hr";
							$days_array = array();
							$seconds = 0;
							$monthStartTime = strtotime(date("d-m-Y 00:00:00",strtotime($start_date)));
							$monthEndTime = strtotime(date("d-m-Y 23:59:59",strtotime($start_date))." +".$num_month." days");
							$monthUserAt = $this->app->getUserAttendanceReportByDate($monthStartTime,$monthEndTime,$user->user_id,$loginId,1);
							for($d=0; $d<$num_month;$d++){
								$new_start_time = strtotime(date("d-m-Y 00:00:00",strtotime($start_date))." +".$d." days");
								$new_end_time = strtotime(date("d-m-Y 23:59:59",strtotime($start_date))." +".$d." days");
								$days_array[]= date("d",$new_start_time);
								$data = array();
								$day_seconds=0;
								$late_seconds=0;
								$early_seconds=0;
								$ot_seconds=0;
								$day_hrs = "00:00 Hr";
								$late_hrs = "00:00 Hr";
								$early_hrs = "00:00 Hr";
								$ot_hrs = "00:00 Hr";
								$halfday = "0";
								$absentWo = "0";
								$sl = "s";
								$unverified = "0";
								$fieldDuty = "0";
								if(($user->doj!="" || $start_time>=$user->doj) && ($user->left_date=="" || $start_time<$user->left_date)){
									$user_at = array_filter($monthUserAt, function($val) use($new_start_time, $new_end_time){
										return ($val->io_time>=$new_start_time and $val->io_time<=$new_end_time);
									});

									$off = array_search(date('N',$new_start_time),array_column($grp,'day_off'));
									$holi = array_search(date('d.m.Y',$new_start_time),array_column($holiday_array,'date'));
									$lv = array_search(date('d.m.Y',$new_start_time),array_column($leaves_array,'date'));
									if(!empty($day_shift_start)){
										$shift_start = $day_shift_start[date('N',$new_start_time)-1];
									}
									if(!empty($day_shift_end)){
										$shift_end = $day_shift_end[date('N',$new_start_time)-1];
									}

									if(!is_bool($off)){
										$weekOff = "1";
										$totalWeekOff++;
									}else{
										$weekOff = "0";
									}

									if(!is_bool($holi)){
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

									if(!empty($user_at)){
										$totalPresent++;
										$ins_array = array();
										$outs_array = array();
										$user_at = array_reverse($user_at);
										foreach($user_at as $at){
											$data[] = array(
												'mode'=>$at->mode,
												'time'=>$at->io_time,
												'comment'=>$at->comment."\n".$at->emp_comment,
												'manual'=>$at->manual,
												'location'=>$at->location
											);
											if($at->mode=='in' && !in_array($at->io_time,$ins_array)){
														$ins_array[]=$at->io_time;
													}
													if($at->mode=='out' && !in_array($at->io_time,$outs_array)){
														$outs_array[]=$at->io_time;
													}
											if($at->manual=="2"){
												$fieldDuty="1";
											}
											if($at->verified=="0"){
												$unverified="1";
											}
											$day_seconds2 = $data[count($data)-1]['time']-$data[0]['time'];
										}//at
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
										if($ca_wo_lofi=="1"){
											$day_out = "0";
											for($o=count($outs_array)-1;$o>=0;$o--){
												if($outs_array[count($outs_array)-1]!="0"){
													$day_out = $outs_array[$o];
													break;
												}
											}
											if($day_out=="0"){
												$day_seconds = 0;
											}else{
												if(count($ins_array)>0){
													$day_seconds = $day_out-$ins_array[0];
												}else{
													$day_seconds = 0;
												}
											}
										}
                                        
										$hours = floor($day_seconds2 / 3600);
										$minutes = floor($day_seconds2 / 60%60);
										$day_hrs = "$hours:$minutes Hr";

										if($day_seconds>0 && $halfday_on=="1" &&($day_seconds<$half_wo_time)){
													$halfday="1";
												}

												if($day_seconds>0 && $absent_on=="1" &&($day_seconds<$ab_wo_time)){
													$absentWo="1";
												}

										if($shift_start!=""){
											$in_start = strtotime(date("d-m-Y h:i A",strtotime(date("h:i A",$ins_array[0]))));
											$sh_start = strtotime(date("d-m-Y h:i A",strtotime($shift_start)));
											$sh_end = strtotime(date("d-m-Y h:i A",strtotime($shift_end)));
											if($in_start>$sh_start){
												$late_seconds = $in_start-$sh_start;
												$hours = floor($late_seconds / 3600);
												$minutes = floor($late_seconds / 60%60);
												$late_hrs = "$hours:$minutes Hr";
												$late_seconds." ".$sl_late_time;
												if($sl_late_on=="1" && ($late_seconds > $sl_late_time)){
													$sl ="SL";
												}
											}
											if($outs_array[count($outs_array)-1]!="0"){
														$out_end = strtotime(date("d-m-Y h:i A",strtotime(date("h:i A",$outs_array[count($outs_array)-1]))));
														if($sh_end>$out_end && $out_end!=0){
															$early_seconds = $sh_end-$out_end;
															$hours = floor($early_seconds / 3600);
															$minutes = floor($early_seconds / 60%60);
															$early_hrs = "EL $hours:$minutes Hr";
															if($sl_early_on=="1" && ($early_seconds > $sl_early_time) && $halfday=="0"){
																$sl = "SL";
															}
														}
													}

											if($outs_array[count($outs_array)-1]!="0"){
												$out_end = strtotime(date("d-m-Y h:i A",strtotime(date("h:i A",$outs_array[count($outs_array)-1]))));
												$ot_seconds = $out_end-$sh_end;
												if($ot_seconds>0 && $ov_shift=="1" && ($ot_seconds > $ov_out_time)){
													$hours = floor($ot_seconds / 3600);
													$minutes = floor($ot_seconds / 60%60);
													$ot_hrs = "$hours:$minutes Hr";
												}
											}
										} //shift

										if($overtime_wh_on=="1" &&($day_seconds>$ov_wo_time)){
											$ot_seconds = $day_seconds-$ov_wo_time;
											if($ot_seconds>0){
												$hours = floor($ot_seconds / 3600);
												$minutes = floor($ot_seconds / 60%60);
												$ot_hrs = "$hours:$minutes Hr";
											}
										}
									}//user at
									else{
										$totalAbsent++;
										$data = array();
									}
									$msOut = "1";
									foreach($data as $day_data){
										if($day_data['mode']=="out"){
											$msOut = "0";
										}
									}
									$mhsStatus="";
									if(!empty($data)){
										if($mispunch=="1" && $msOut=="1"){
											$mhsStatus="ms";
										}else if($halfday=="1"){
											$mhsStatus="hf";
										}else if($sl=="SL"){
											$mhsStatus="sl";
										}
									}
									if($option=="all" || ($option=="present" && !empty($data)) || ($option=="absent" && empty($data)) || ($option=="mispunch" && $mhsStatus=="ms")||($option=="halfday" && $mhsStatus=="hf") ||($option=="late" && $late_seconds>0)||($option=="early" && $early_seconds>0)||($option=="shortLeave" && $mhsStatus=="sl")||($option=="unverified" && $unverified=="1")||($option=="fieldDuty" && $fieldDuty=="1")){
										$months_array[] = array(
											'date'=>date("d-M",$new_start_time),
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
											'sl'=>$sl
										);
									}
								}//   days
							}// user
							if($seconds>0){
								$hours = floor($seconds / 3600);
								$minutes = floor($seconds / 60%60);
								$totalWorkingHrs = "$hours:$minutes Hr";
							}
							if(count($months_array)>=1){
								$new_array[] =array(
									'user_id'=>$user->user_id,
									'mid'=>$user->mid,
									'emp_code'=>$user->emp_code,
									'name'=>$user->name,
									'image'=>$user->image,
									'user_status'=>$user->user_status,
								//	'shift_start'=>$shift_start,
								//	'shift_end'=>$shift_end,
								//	'group_name'=>$group_name,
									'designation'=>$user->designation,
									'totalAbsent'=>$totalAbsent,
									'totalPresent'=>$totalPresent,
									'totalWeekOff'=>$totalWeekOff,
									'totalHoliday'=>$totalHoliday,
								//	'totalLeaves'=>$totalLeaves,
									'totalWorkingHrs'=>$totalWorkingHrs,
									'totalLate'=>$totalLate,
									//'totalEarly'=>$totalEarly,
									'data'=> $months_array
								);
							}
						}
					}
				}
			}


			$data=array(
				'start_date'=>$start_date,
				'end_date'=>$end_date,
				'load'=>$true,
				'report'=>$new_array,
				'days'=>$days_array,
				'option'=>$option,
				
				'cmp_name'=>$cmpName['name']
			);
			//print_r($new_array);
			$this->load->view('student/student_report',$data);
		}else{
			redirect('user-login');
		}
	}




public function student_device(){
	if(!empty($this->session->userdata('id'))){
		$this->load->view('student/device_list');
	}
	else{
		redirect('user-login');
	}
}

public function stu_device_access(){
	if(!empty($this->session->userdata('id'))){
		$this->load->view('student/device_access');
	}
	else{
		redirect('user-login');
	}
}


public function studentsPass(){
	if (!empty($this->session->userdata('id'))) {
		$this->load->view('student/pass');
	}else{
		redirect('user-login');
	}
}

function import_school_student()
		{
			if(!empty($this->session->userdata('id'))){
				
			
			$data=$this->web->import_school_student();
			$this->load->view('student/students_list',$data);
			
				
			}
			else{
				redirect('user-login');
			}
		  }
	
	
public function add_class(){
		if(!empty($this->session->userdata('id'))){
			$this->load->view('student/add_class');
		}
		else{
			redirect('user-login');
		}
	}	



	public function add_newclass(){
		if(!empty($this->session->userdata('id'))){
			$postdata=$this->input->post();
			$postdata=array(
				'name'=>$postdata['class'],
				'bid'=>$postdata['bid'],
				'date_time'=>time()
			);
			$data=$this->db->insert('class',$postdata);
			if($data > 0){
			   
			
				$this->session->set_flashdata('msg','New Class Added!');
				redirect('add_class');
			}
		}
		else{
			redirect('user-login');
		}
	}


public function delete_class(){
		if (!empty($this->session->userdata('id'))) {
			$id = $this->input->post('id');
			$res= $this->web->delete_class($id);
			if ($res) {
			    
				echo $id;
				return($id);
			}
		} else {
			redirect('user-login');
		}
		
	}


	public function editclass(){
		if(!empty($this->session->userdata('id'))){
			$check=$_REQUEST;
			print_r($check);
			echo $name = $_POST['name'];
			echo $id = $_POST['id'];
			$data = array(
				'name' => $name
			
			);
			print_r($data);
			$this->db->where('id',$id);
			$res = $this->db->update('class',$data);
			echo $res;
			return($res);
		}
		else{
			redirect('user-login');
		}
	}

	
	
	
	
		public function canteen_summary(){
		if(!empty($this->session->userdata('id'))){
			
			$postdata=$this->input->post();
				$start_date = date("Y-m-d");
				$true = 0;
				$days_array = array();
				$new_array = array();
			if ($this->session->userdata()['type'] == 'P') {
				$loginId = $this->session->userdata('empCompany');
				$role=$this->web->getRollbyid($this->web->session->userdata('login_id'),$loginId);
				} else {
				$loginId = $this->web->session->userdata('login_id');
				}
				
				$cmpName = $this->web->getBusinessById($loginId);
				$action="active";
				if(isset($postdata['start_date'])){
				$start_date = $postdata['start_date'];
				$action = $postdata['action'];
				}
				$true= 1;
				$totalActive = 0;
				$totalPresent = 0;
				$totallog = 0;
				$punchtime=0;
				//$totalMispunch = 0;
				$users_data = $this->app->getCompanyUsers($loginId);
				$start_time = strtotime(date("d-m-Y 00:00:00",strtotime($start_date)));
				$end_time = strtotime(date("d-m-Y 23:59:59",strtotime($start_date)));
				$timedata=$this->web->getTimingByBusinessId($loginId);
				foreach($timedata as $res2){ 
				$start_times=strtotime (date(" h:i A" ,$res2->start_time));
					  $end_times=strtotime (date(" h:i A" ,$res2->end_time));
	                   	}
				if(!empty($users_data)){
					$seconds = 0;
					foreach($users_data as $user){
					 if($user->hostel=="1"){
				$days_array[]= date("d",$start_time);
				$data = array();
				$day_hrs = "00:00 Hr";
				
			if(($user->doj!="" || $start_time>=$user->doj) && ($user->left_date=="" || $start_time<$user->left_date)){
										$totalActive++;
								$user_at = $this->app->getUserAttendanceReportByDate($start_time,$end_time,$user->user_id,$loginId,1);
				
				
				
				if(!empty($user_at)){
											$totalPresent++;
											$ins_array = array();
											$outs_array = array();
											$comment_array = array();
											$user_at = array_reverse($user_at);
											foreach($user_at as $at){
											   $timeSearch = array_search($at->io_time,array_column($data,'time'));
												if(is_bool($timeSearch)){
													$data[] = array(
														'mode'=>$at->mode,
														'time'=>$at->io_time,
														'comment'=>$at->comment,
														'manual'=>$at->manual,
														'location'=>$at->location
													);
													$totallog++;
												
												}}
												
											}
											//userat
											
											else{
											//$totalAbsent++;
											$data = array();
										}
				
				$new_array[] =array(
												'user_id'=>$user->user_id,
												'mid'=>$user->mid,
												'name'=>$user->name,
												'data'=>$data,
												
											);
				
				}
					}
				}
				}
				
		$data=array(
					'start_date'=>$start_date,
					'res'=>$timedata,
					'load'=>$true,
					'report'=>$new_array,
					'days'=>$days_array,
					'totalActive'=>$totalActive,
					'totallog'=>$totallog,
					'totalPresent'=>$totalPresent,
					'cmp_name'=>$cmpName['name']
				);		
				
			
			
			$this->load->view('hostel/canteen_summary',$data);
		}
		else{
			redirect('user-login');
		}
	}	
	
	
	
public function student_access_report(){
			if(!empty($this->session->userdata('id'))){
				
				$postdata=$this->input->post();
					$start_date = date("Y-m-d");
					$end_date = date("Y-m-d");
					$true = 0;
				    $days_array = array();
					$new_array = array();
				if ($this->session->userdata()['type'] == 'P') {
					$loginId = $this->session->userdata('empCompany');
					$role=$this->web->getRollbyid($this->web->session->userdata('login_id'),$loginId);
					} else {
					$loginId = $this->web->session->userdata('login_id');
					}
					
					$cmpName = $this->web->getBusinessById($loginId);
					//$action="active";
					if(isset($postdata['start_date']) && isset($postdata['end_date'])){
				$start_date = $postdata['start_date'];
				$end_date = $postdata['end_date'];
					//$action = $postdata['action'];
					
					$true= 1;
					
					//$totalMispunch = 0;
					$users_data = $this->app->getCompanyUsers($loginId);
				$start_time = strtotime(date("d-m-Y 00:00:00",strtotime($start_date)));
				$end_time = strtotime(date("d-m-Y 23:59:59",strtotime($end_date)));
                $totalf=0;
				$totalActive=0;
					if(!empty($users_data)){
						//$seconds = 0;
						foreach($users_data as $user){
							if($user->hostel=="1"){
							
						$date1=date_create(date("Y-m-d",strtotime($start_date)));
									$date2=date_create(date("Y-m-d",strtotime($end_date)));
									$diff=date_diff($date1,$date2);
									$num_month = $diff->format("%a");

									$num_month++;
									if($num_month>31){
										$num_month=31;
									}	
							
							$months_array = array();
							$days_array = array();
							$total=0;
							
						//	
             // $monthStartTime = strtotime(date("d-m-Y 00:00:00",strtotime($mid->checkon->datefrom)));
            //  $monthEndTime = strtotime(date("d-m-Y 23:59:59",strtotime($mid->checkon->datefrom))." +".$num_month." days");
		$monthStartTime = strtotime(date("d-m-Y 00:00:00",strtotime($start_date)));
		$monthEndTime = strtotime(date("d-m-Y 23:59:59",strtotime($start_date))." +".$num_month." days");
		$monthUserAt = $this->app->getUserAttendanceReportByDate($monthStartTime,$monthEndTime,$user->user_id,$loginId,1);
											
			               // $monthUserAt = $this->app->getUserAttendanceReportByDate($monthStartTime,$monthEndTime,$user->user_id,$check['id'],1);
              for($d=0; $d<$num_month;$d++){
               $new_start_time = strtotime(date("d-m-Y 00:00:00",strtotime($start_date))." +".$d." days");
				$new_end_time = strtotime(date("d-m-Y 23:59:59",strtotime($start_date))." +".$d." days");
                $days_array[]= date("d",$new_start_time);
                $data = array();
				
	if(($user->doj!="" || strtotime($start_date)>=$user->doj) && ($user->left_date=="" || strtotime($start_date)<$user->left_date)){
		
											$user_at = array_filter($monthUserAt, function($val) use($new_start_time, $new_end_time){
												return ($val->io_time>=$new_start_time and $val->io_time<=$new_end_time);
											});
											$user_at = array_reverse($user_at);
											
											
                    if(!empty($user_at)){
                      foreach($user_at as $at){
                        if($at->hostel=="1"){
                          $data[] = array(
                            'mode'=>$at->mode,
                            'time'=>$at->io_time,
                            'comment'=>$at->comment
                          );
						  $total++;
                        }
                      }
                    }else{
                      $data = array();
                    }
				
				 $months_array[] = array(
                      'date'=>date("j",$new_start_time),
                      'day'=>date("l",$new_start_time),
					 
                      'data'=>$data
                    );
                }
              }
			  $totalf=$totalf+$total;
			  $totalActive++;
			  
			  if(count($months_array)>0){
                    $new_array[] =array(
                    'user_id'=>$user->user_id,
                    'mid'=>$user->mid,
                    'emp_code'=>$user->emp_code,
					'total'=>$total,
                    'name'=>$user->name,
                    'image'=>$user->image,
                    'user_status'=>$user->user_status,
                    'data'=> $months_array
                  );
              }
				
			  
			 	
				// close users and post		
					}
					//
				}
					
				
				}
					
					}
					
			$data=array(
						'start_date'=>$start_date,
						'end_date'=>$end_date,
						'totalf'=>$totalf,
						'totalActive'=>$totalActive,
						'load'=>$true,
						'report'=>$new_array,
						'days'=>$days_array,
						'cmp_name'=>$cmpName['name']
					);	
					
			
				
				$this->load->view('hostel/student_access_report',$data);
			}
			else{
				redirect('user-login');
			}
		}	
			



public function access_report2(){
		if(!empty($this->session->userdata('id'))){
				
if (isset($_POST['submit'])) {
    // Handle file upload
    $file = $_FILES['file']['tmp_name'];
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];
	 $dev_id= $_POST['device'];
    $uname = $this->web->getbidbydeviceid($dev_id);
       $buid=$uname[0]->bid;
  
    $device=$uname[0]->deviceid;
    

    if (is_uploaded_file($file)) {
        // Convert the from_date and to_date to timestamps using strtotime
        $from_timestamp = strtotime($from_date);
        $to_timestamp = strtotime($to_date);

        // Read the file and parse the contents
        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $filtered_data = [];
		//$insert_data=[];
         
        // Loop through each line in the text file
        foreach ($lines as $line) {
            // Split the line by one or more spaces
           $columns = preg_split('/\s+/', $line); // Split by one or more spaces
		 $countcol=count($columns) ;
	   $dates = $columns[ $countcol -3]; // Second to last column
    $times = $columns[count($columns) - '2']; // Last column
//$countdata=count($columns);
    // Combine date and time into a single string
    $date2 = $dates . ' ' . $times;
	//var_dump($date2);
	$dateTime = preg_replace('/[\x00-\x1F\x7F]/', '', $date2);


   $timest = strtotime($dateTime);

    // Ensure there are at least 10 columns (check if we have a valid row)
            if (count($columns) >= 10) {
                

                // Compare if the datetime in the file is between from_date and to_date
               if ($timest >= $from_timestamp && $timest <= $to_timestamp ) {
                    // Store the data in different variables
                    $column1 = $columns[0];
                    $column2 = $columns[1];
                    $column3 = $columns[2];
                    $column4 = $columns[3];
                    $column9 =  $timest;
					 //$column10 =$columns2[9];
                    $column10 = $date2 ; // Date and time column
					

                    // Add the filtered data to the result array
                    $filtered_data[] = [
                        'column1' => $column1,
                        'column2' => $column2,
                        'Enrllno' => $column3,
                        'column4' => $column4,
						'timestamp' => $column9,
                        'Datetime' => $column10
						
                    ];
						
					
					
               }
            }
        }

        // Display the filtered data
        if (count($filtered_data) > 0) {
           
            echo "<h3>Filtered Results:</h3>";
           // echo "<table border='1'>";
            //echo "<tr><th>S/No</th><th>Enroll No</th><th>Emp Id</th><th>Name</th><th>DateTime</th><th>DateTime</th></tr>";
			$countsn=0;
			$count=0;
            foreach ($filtered_data as $row) {
                
                 $new_id=$row['Enrllno'];
                 $new_time=$row['timestamp'];
                 $name=$row['column4'];
                 
                 $number = preg_replace('/[\x00-\x1F\x7F]/', '', $row['Enrllno']);
                $new_id2=ltrim($number, '0');
                $getUserByBioId = $this->app->getUserByBioId( $new_id2,$buid);
              
               
              if(isset($getUserByBioId)){
                
				$countsn++;
              
                 $userCmp = $this->app->getUserCompany($getUserByBioId['id']);
               // $uname = $this->web->getNameByUserId($getUserByBioId['id']);
               // $name=$uname[0]->name;
                 
               if( !empty($userCmp['business_id']) && $userCmp['business_id']==$buid){
                 $checkOffline = $this->app->checkIoTime($getUserByBioId['id'],$buid,$new_time);
                 $dateTimes=date("d-M-y h:m:s" ,$new_time);
                 echo " Found Data: ".$countsn." Enroll Id: ". $new_id2." Name: ". $name." Time: ". $dateTimes ."<br>";
                  
                  if(empty($checkOffline)){
                      echo "(New Data Found) <br> " ; 
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
                      'device'=>$device,
                      'manual'=>"4",
                      'io_time'=>$new_time,
                      'date'=>time()
                    );
                   
                 
		$res = $this->db->insert('attendance', $insertData2);
                
                
                
                
                
                
             $count++;
                 echo " New Data: ".$count." Enroll Id: ". $new_id2."  Name: ".$name." Time: ".$dateTimes."(Updated) <br>"; 
                } 
                else{
                  echo "(Already Added) <br>" ; 
                  }
                }
              } //$count2++;   
                
                
                
            }
            
         echo $countsn ." Data Found <br> ";
        echo $count ." New data Added ";
            
            
           // echo "</table>";
			
			
			
        } else {
            echo "No data found within the selected date range.";
        }
    } else {
        echo "Error uploading file.";
    }
}




}
		else{
			redirect('user-login');
		}
	}
	
	
	
	
	public function import_txtlog(){
		if(!empty($this->session->userdata('id'))){
				
if (isset($_POST['submit'])) {
    // Handle file upload
    $file = $_FILES['file']['tmp_name'];
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];
	 $dev_id= $_POST['device'];
    $uname = $this->web->getbidbydeviceid($dev_id);
     
    $device=$uname[0]->deviceid;
    if($this->session->userdata()['type']=='P'){
      
      $buid = $this->session->userdata('empCompany');
      $role=$this->web->getRollbyid($this->web->session->userdata('login_id'),$buid);
  
    } else {
      $buid=$this->web->session->userdata('login_id');
    }
    

    if (is_uploaded_file($file)) {
        // Convert the from_date and to_date to timestamps using strtotime
        $from_timestamp = strtotime($from_date);
        $to_timestamp = strtotime($to_date);

        // Read the file and parse the contents
        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $filtered_data = [];
		//$insert_data=[];
         
        // Loop through each line in the text file
        foreach ($lines as $line) {
            // Split the line by one or more spaces
           $columns = preg_split('/\s+/', $line); // Split by one or more spaces
		 $countcol=count($columns) ;
	   $dates = $columns[ $countcol -3]; // Second to last column
    $times = $columns[count($columns) - '2']; // Last column
//$countdata=count($columns);
    // Combine date and time into a single string
    $date2 = $dates . ' ' . $times;
	//var_dump($date2);
	$dateTime = preg_replace('/[\x00-\x1F\x7F]/', '', $date2);


   $timest = strtotime($dateTime);

    // Ensure there are at least 10 columns (check if we have a valid row)
            if (count($columns) >= 10) {
                

                // Compare if the datetime in the file is between from_date and to_date
               if ($timest >= $from_timestamp && $timest <= $to_timestamp ) {
                    // Store the data in different variables
                    $column1 = $columns[0];
                    $column2 = $columns[1];
                    $column3 = $columns[2];
                    $column4 = $columns[3];
                    $column9 =  $timest;
					 //$column10 =$columns2[9];
                    $column10 = $date2 ; // Date and time column
					

                    // Add the filtered data to the result array
                    $filtered_data[] = [
                        'column1' => $column1,
                        'column2' => $column2,
                        'Enrllno' => $column3,
                        'column4' => $column4,
						'timestamp' => $column9,
                        'Datetime' => $column10
						
                    ];
						
					
					
               }
            }
        }

        // Display the filtered data
        if (count($filtered_data) > 0) {
           
            echo "<h3>Filtered Results:</h3>";
           // echo "<table border='1'>";
            //echo "<tr><th>S/No</th><th>Enroll No</th><th>Emp Id</th><th>Name</th><th>DateTime</th><th>DateTime</th></tr>";
			$countsn=0;
			$count=0;
            foreach ($filtered_data as $row) {
                
                 $new_id=$row['Enrllno'];
                 $new_time=$row['timestamp'];
                 $name=$row['column4'];
                 
                 $number = preg_replace('/[\x00-\x1F\x7F]/', '', $row['Enrllno']);
                $new_id2=ltrim($number, '0');
                $getUserByBioId = $this->app->getUserByBioId( $new_id2,$buid);
              
               
              if(isset($getUserByBioId)){
                
				$countsn++;
              
                 $userCmp = $this->app->getUserCompany($getUserByBioId['id']);
               // $uname = $this->web->getNameByUserId($getUserByBioId['id']);
               // $name=$uname[0]->name;
                 
               if( !empty($userCmp['business_id']) && $userCmp['business_id']==$buid){
                 $checkOffline = $this->app->checkIoTime($getUserByBioId['id'],$buid,$new_time);
                 $dateTimes=date("d-M-y h:m:s" ,$new_time);
                 echo " Found Data: ".$countsn." Enroll Id: ". $new_id2." Name: ". $name." Time: ". $dateTimes ."<br>";
                  
                  if(empty($checkOffline)){
                      echo "(New Data Found) <br> " ; 
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
                      'device'=>$device,
                      'manual'=>"4",
                      'io_time'=>$new_time,
                      'date'=>time()
                    );
                   
                 
		$res = $this->db->insert('attendance', $insertData2);
                
                
                
                
                
                
             $count++;
                 echo " New Data: ".$count." Enroll Id: ". $new_id2."  Name: ".$name." Time: ".$dateTimes."(Updated) <br>"; 
                } 
                else{
                  echo "(Already Added) <br>" ; 
                  }
                }
              } //$count2++;   
                
                
                
            }
            
         echo $countsn ." Data Found <br> ";
        echo $count ." New data Added ";
            
            
           // echo "</table>";
			
			
			
        } else {
            echo "No data found within the selected date range.";
        }
    } else {
        echo "Error uploading file.";
    }
}




}
		else{
			redirect('user-login');
		}
	}





	
	
	//school new
	
	
	
	public function add_branch(){
		if(!empty($this->session->userdata('id'))){
			$this->load->view('student/add_branch');
		}
		else{
			redirect('user-login');
		}
	}

	public function add_s_section(){
		if(!empty($this->session->userdata('id'))){
			$this->load->view('student/add_section');
		}
		else{
			redirect('user-login');
		}
	}
	public function add_period(){
		if(!empty($this->session->userdata('id'))){
			$this->load->view('student/add_period');
		}
		else{
			redirect('user-login');
		}
	}

	public function add_subject(){
		if(!empty($this->session->userdata('id'))){
			$this->load->view('student/add_subject');
		}
		else{
			redirect('user-login');
		}
	}


	public function add_teachers(){
		if(!empty($this->session->userdata('id'))){
			$this->load->view('student/addteacher');
		}
		else{
			redirect('user-login');
		}
	}
	
		public function teachers_list(){
			if(!empty($this->session->userdata('id'))){
				$this->load->view('student/teachers');
			}
			else{
				redirect('user-login');
			}
		}
		
		
		
	public function addnewteachers(){
				if(!empty($this->session->userdata('id'))){
					if($this->session->userdata()['type']=='P'){
						$uid = $this->session->userdata('empCompany');
					} else {
						$uid=$this->web->session->userdata('login_id');
					}
				
					// if($this->session->userdata('type')=="P"){
					// 	$userCmp = $this->app->getUserCompany($loginId);
					// 	if(isset($userCmp) && ($userCmp['left_date']=="" || $userCmp['left_date']>time())){
					// 		$uid = $userCmp['business_id'];
					// 	}
					// }
					$omid = $this->web->getMaxMid()['m_id'];
					$temp_ = "MI";
					if($omid == ''){
						$nmid = $temp_.'00000';
					}else{
						$str1 = substr($omid,4);
						$str1 = $str1 + 1;
						$str2 = str_pad($str1 , 5 , 0 , STR_PAD_LEFT);
						$nmid = $temp_.$str2;
					}

					$postdata=$this->input->post();
					 $doj=strtotime($_POST['doj']);
					$otp=rand(1000,9999);
					$i='upload/nextpng.png';
                   //  $class_ids=$postdata['class'];
                    // $class_room=$postdata['class_room'];
					$subject=$postdata['subject'];
				 //  $semester=$postdata['semester'];
				   //  $session=$postdata['session'];
					$department=$postdata['department'];
				//	 $section=$postdata['section'];
                     
                     
                     
                     
                     
					$postdata=array(
						'name'=>$postdata['name'],
						'mobile'=>trim($postdata['mobile']),
						'address'=>$postdata['address'],
						'user_group'=>"2",
						'email'=>$postdata['email'],
						'emp_code'=>$postdata['empcode'],
						'dob'=>$postdata['dob'],
						'bio_id'=>$postdata['devcode'],
						'gender'=>$postdata['gender'],
					//	'designation'=>$postdata['desig'],
					//	'business_group'=>$postdata['class'],
						'department'=>$postdata['department'],
					//	'manager'=>$postdata['post'],
						'doj'=>strtotime($postdata['doj']),
						'active'=>0,
						'date'=>time(),
						'baseurl'=>base_url().'User/profile/'.$nmid,
						'login'=>md5($mobile),
						'image'=>$i,
						'company'=>$uid,
						'm_id'=>$nmid,
						'otp'=>$otp

					);
					$data=$this->db->insert('login',$postdata);
					$id = $this->db->insert_id();

					if($data > 0){
						if($id){
						   
							$cmpInData = array(
								'bid'=>$uid,
								'uid'=>$id,
							//	'class_id'=>$class_ids,
						//	'section'=>$section,
					     // 'class_room'=>$class_room,
					    //  'batch'=>$batch,
				 // 	'semester'=>$semester,
				 'subject'=>$subject,
					'department'=>$department,
						'date_time'=>time(),
						'update_date'=>time(),
						'status'=>"1"
							);
							$data2=$this->db->insert('class_teacher',$cmpInData);
						}
                      //$uname = $this->web->getNameByUserId($id);
                                     //echo $uname[0]->name;	
						//	$actdata=array(
			                          //  'bid'=>$uid,
				                      //  'uid'=>$this->web->session->userdata('login_id'),
				                      //  'activity'=>"New Employee ".$uname[0]->name. " added",
				                      //  'date_time'=>time()
				
			                          //   );
			                //  $data=$this->db->insert('activity',$actdata);	
				
						}
                       
						$this->session->set_flashdata('msg','New Teacher Added!');
						redirect('teachers_list');
					
				}
				else{
					redirect('user-login');
				}
			}
	
		
		
		
			public function editTeachers(){
			if(!empty($this->session->userdata('id'))){
				$id = $this->input->post("id");
				
				$this->load->view('student/editTeachers');
			}

			else{
				redirect('user-login');
			}
		}
		
		
		public function updateTeacher(){
		if(!empty($this->session->userdata('id'))){
			echo $id=$_POST['id'];
			echo $bid=$_POST['bid'];
			//echo $id = $_POST['id'];
			echo $name = $_POST['name'];
			
			
		
			echo $father_name = $_POST['father_name'];
		    echo $blood_group = $_POST['blood_group'];
			echo $experience = $_POST['experience'];
		
		
			
			echo $email = $_POST['email'];
			echo $address = $_POST['address'];
			echo $empcode = $_POST['empcode'];
			echo $bio_id = $_POST['bio_id'];
			echo $class = $_POST['class'];
			echo $dob = $_POST['dob'];
			echo $gender = $_POST['gender'];
			echo $desig = $_POST['desig'];
			echo $edu = $_POST['edu'];
			echo $post = $_POST['post'];
			echo $department = $_POST['department'];
			
			echo $doj = strtotime($_POST['doj']);
		//	echo $dol = strtotime($_POST['dol']);
		
			$data=array(
						'name' => $name,
						'email' => $email,
						'address' => $address,
						'emp_code' => $empcode,
						'bio_id' => $bio_id,
						'gender' => $gender,
						'designation' => $desig,
						'education' => $edu,
						'manager' => $post,
						'doj' => $doj,
						'dob' => $dob,
						'company' => $bid,
						
						'father_name' => $father_name,
						'blood_group' => $blood_group,
						'experience' => $experience,
						//'start_date' => $doreg,
						
					//	'business_group' => $group,
						'department' => $department
				
					);
			//$data=$this->db->update('login',$postdata);
			$this->db->where('id',$id);
			$data= $this->db->update('login',$data);
			
		//	$uname = $this->web->getNameByUserId($id);
		
			                 // $data=$this->db->insert('activity',$actdata);	
			if($class!=''){
			
			$jdata=array('class_id' => $class,
					'update_date'=>time()
						
						);
			$this->db->where('uid',$id);
			$data= $this->db->update('class_teacher',$jdata);
			}
			
		
			
		
			
				$this->session->set_flashdata('msg','Teacher Updated Successfully!');
				redirect('teachers_list');
			
		}
		else{
			redirect('user-login');
		}
	}
	
public function add_sdepartment(){
		if(!empty($this->session->userdata('id'))){
			$postdata=$this->input->post();
			$postdata=array(
			             'bid'=>$postdata['bid'],
						'name'=>$postdata['name'],
						 'date_time'=>time(),
						 'status'=> 1
						 
					);
			$data=$this->db->insert('department_section',$postdata);
			if($data > 0){
			   // $uname = $this->web->getNameByUserId($id);
				 if ($this->session->userdata()['type'] == 'P') {
          
          $loginId = $this->session->userdata('empCompany');
         // $role=$this->web->getRollbyid($this->web->session->userdata('login_id'),$loginId);
        } else {
          $loginId = $this->web->session->userdata('login_id');
        }
			
				$this->session->set_flashdata('msg','New Department Added!');
				redirect('add_branch');
			}
		}
		else{
			redirect('user-login');
		}
	}		
	
	
	
	
	
	
		public function add_newsection(){
		if(!empty($this->session->userdata('id'))){
			$postdata=$this->input->post();
			$postdata=array(
			  //  'class_id'=>$postdata['class'],
			  'dep_id'=>$postdata['dept'],
			  'session_id'=>$postdata['session'],
				'name'=>$postdata['name'],
				'bid'=>$postdata['bid'],
				'date_time'=>time()
			);
			$data=$this->db->insert('S_section',$postdata);
			if($data > 0){
			   
			
				$this->session->set_flashdata('msg','New Section Added!');
				redirect('add_s_section');
			}
		}
		else{
			redirect('user-login');
		}
	}


public function delete_S_Section(){
		if (!empty($this->session->userdata('id'))) {
			$id = $this->input->post('id');
			$res= $this->web->delete_S_section($id);
			if ($res) {
			    
				echo $id;
				return($id);
			}
		} else {
			redirect('user-login');
		}
		
	}


	public function edit_S_Section(){
		if(!empty($this->session->userdata('id'))){
			$check=$_REQUEST;
			print_r($check);
			echo $name = $_POST['name'];
			echo $id = $_POST['id'];
			$data = array(
				'name' => $name
			
			);
			print_r($data);
			$this->db->where('id',$id);
			$res = $this->db->update('S_section',$data);
			echo $res;
			return($res);
		}
		else{
			redirect('user-login');
		}
	}
	
	
	
public function upload_image() {
    if (!empty($this->session->userdata('id'))) {

        $postdata = $this->input->post();
        $id = $_POST['id'];

        if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
            $target_dir = "upload/hostel_student/"; // Ensure this directory exists and has write permissions
            $file_name = basename($_FILES["image"]["name"]);
            $unique_name = uniqid() . "_" . $file_name; // Add unique prefix
            $target_file = $target_dir . $unique_name;
            $imageFileType = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            // Check if the file is a JPEG image
            if ($imageFileType != "jpg" && $imageFileType != "jpeg") {
                $this->session->set_flashdata('msg', 'Only JPEG files are allowed.');
                redirect('student_list');
                return;
            }

            // Validate if it's actually a JPEG image
            $check = getimagesize($_FILES["image"]["tmp_name"]);
            if ($check === false || $check["mime"] !== "image/jpeg") {
                $this->session->set_flashdata('msg', 'File is not a valid JPEG image.');
                redirect('student_list');
                return;
            }

            // **Move the uploaded file to the target directory**
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                // Update the database with the image path
                $data = array(
                    'image' => $target_file
                );

                $this->db->where('id', $id);
                $res = $this->db->update('login', $data);

                if ($res) {
                    $this->session->set_flashdata('msg', 'Photo uploaded successfully!');
                } else {
                    $this->session->set_flashdata('msg', 'Database update failed.');
                }
            } else {
                $this->session->set_flashdata('msg', 'Error moving uploaded file.');
            }
        } else {
            $this->session->set_flashdata('msg', 'No file uploaded or error occurred.');
        }

        redirect('student_list');
    }
}


///new scholl code


	public function add_newperiod(){
		if(!empty($this->session->userdata('id'))){
			$postdata=$this->input->post();
			$postdata=array(
				'name'=>$postdata['name'],
				'bid'=>$postdata['bid'],
			//	'class_id'=>$postdata['class'],
			//	'subject'=>$postdata['subject'],
				'start_time'=> date("h:i A",strtotime($postdata['start'])),
				'end_time'=> date("h:i A",strtotime($postdata['end'])),
				'date_time'=>time()
			);
			$data=$this->db->insert('S_period',$postdata);
			if($data > 0){
			   
			
				$this->session->set_flashdata('msg','New Period Added!');
				redirect('add_period');
			}
		}
		else{
			redirect('user-login');
		}
	}


public function delete_period(){
		if (!empty($this->session->userdata('id'))) {
			$id = $this->input->post('id');
			$res= $this->web->delete_period($id);
			if ($res) {
			    
				echo $id;
				return($id);
			}
		} else {
			redirect('user-login');
		}
		
	}


	public function editperiod(){
		if(!empty($this->session->userdata('id'))){
			$check=$_REQUEST;
			print_r($check);
			echo $name = $_POST['name'];
			echo $id = $_POST['id'];
			$data = array(
				'name' => $name
			
			);
			print_r($data);
			$this->db->where('id',$id);
			$res = $this->db->update('S_period',$data);
			echo $res;
			return($res);
		}
		else{
			redirect('user-login');
		}
	}





	public function add_newsubject(){
		if(!empty($this->session->userdata('id'))){
			$postdata=$this->input->post();
			$postdata=array(
			    'dep_id'=>$postdata['dept'],
			    'Subject_code'=>$postdata['subcode'],
				'name'=>$postdata['name'],
				'bid'=>$postdata['bid'],
				'date_time'=>time()
			);
			$data=$this->db->insert('subject',$postdata);
			if($data > 0){
			   
			
				$this->session->set_flashdata('msg','New Subject Added!');
				redirect('add_subject');
			}
		}
		else{
			redirect('user-login');
		}
	}


public function delete_subject(){
		if (!empty($this->session->userdata('id'))) {
			$id = $this->input->post('id');
			$res= $this->web->delete_subject($id);
			if ($res) {
			    
				echo $id;
				return($id);
			}
		} else {
			redirect('user-login');
		}
		
	}



	public function editsubject(){
		if(!empty($this->session->userdata('id'))){
			$check=$_REQUEST;
			print_r($check);
			echo $name = $_POST['name'];
			echo $id = $_POST['id'];
			$data = array(
				'name' => $name
			
			);
			print_r($data);
			$this->db->where('id',$id);
			$res = $this->db->update('subject',$data);
			echo $res;
			return($res);
		}
		else{
			redirect('user-login');
		}
	}



public function add_session(){
		if(!empty($this->session->userdata('id'))){
			$this->load->view('student/add_session');
		}
		else{
			redirect('user-login');
		}
	}
	
	public function add_batch(){
		if(!empty($this->session->userdata('id'))){
			$this->load->view('student/add_batch');
		}
		else{
			redirect('user-login');
		}
	}


public function add_newsession(){
		if(!empty($this->session->userdata('id'))){
			$postdata=$this->input->post();
			$postdata=array(
			    'dep_id'=>$postdata['dept'],
				'session_name'=>$postdata['session'],
				'bid'=>$postdata['bid'],
				'date_time'=>time()
			);
			$data=$this->db->insert('S_Session',$postdata);
			if($data > 0){
			   
			
				$this->session->set_flashdata('msg','New Session Added!');
				redirect('add_session');
			}
		}
		else{
			redirect('user-login');
		}
	}


public function delete_S_session(){
		if (!empty($this->session->userdata('id'))) {
			$id = $this->input->post('id');
			$res= $this->web->delete_S_session($id);
			if ($res) {
			    
				echo $id;
				return($id);
			}
		} else {
			redirect('user-login');
		}
		
	}









	
	
}

?>
