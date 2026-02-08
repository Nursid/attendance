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
		$this->load->model('Api_Model_v11','app');
		$this->load->helper('cookie');

	}
	public function index(){
		$this->load->view('users/login');
	}
	public function login(){
		// $post=$this->input->post();
		// $page=$post['page'];
		// $getLogin=$this->web->login($post['username'],md5($post['password']));

		// echo $getLogin;
		// die();
		$post = $this->input->post();
		
		$page = $post['page'];
	
		$getLogin = $this->web->login(
			$post['username'],
			md5($post['password'])
		);

		
	
	
	

		$getUserCompanies  = $this->web->getUserCompanies($getLogin['login_id']);
	 if($getLogin['type']=='P'){
     	$bid =$getUserCompanies[0]->bid;
	     } else {
		  $bid=$getLogin['login_id'];
		  }
	$val = $this->web->getNameByUserId($bid);
	  $validity=$val[0]->validity;


	
		if(!empty($getLogin) && $validity> time() ){
		   
			
			
			$linked = $this->app->getAllLinked($getLogin['username']);
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
				
				
				
				//if($getLogin['type']=='P'){
				//	$bid =$getUserCompanies[0]->bid;
				//	} else {
					//	$bid=$getLogin['login_id'];
					//	}
				
				$actdata=array(
			   'bid'=>$bid,
				'uid'=>$getLogin['login_id'],
				'activity'=>"Login to portal",
				'date_time'=>time()
				
			);
			$data=$this->db->insert('activity',$actdata);	
				
				
				
				if($page==2){
				 redirect('page_hostel');   
				}
				else{
			redirect('page');}
		}
		else{
			$res = $this->web->checkUserStatus($post['username'],md5($post['password']));
			if (empty($res)) {
				$this->session->set_flashdata('msg', 'Incorrect username or password!');
			}elseif($res['status'] == 0){
				$this->session->set_flashdata('msg', 'User account not ACTIVE!');
			}
		// 	elseif($validity < time()){
		// 	$this->session->set_flashdata('msg', 'Licence Validity Expired please Contact Your Service Provider');
		// }
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
	public function adddepartment(){
		if(!empty($this->session->userdata('id'))){
			$this->load->view('department/add_department');
		}
		else{
			redirect('user-login');
		}
	}
	public function department(){
		if(!empty($this->session->userdata('id'))){
			$postdata=$this->input->post();
			$postdata=array(
				'department'=>$postdata['department'],
				'Dep_code'=>$postdata['prefix'],
				'remark'=>$postdata['remark']
			);
			$data=$this->db->insert('department',$postdata);
			if($data > 0){
			   // $uname = $this->web->getNameByUserId($id);
				 if ($this->session->userdata()['type'] == 'P') {
          
          $loginId = $this->session->userdata('empCompany');
         // $role=$this->web->getRollbyid($this->web->session->userdata('login_id'),$loginId);
        } else {
          $loginId = $this->web->session->userdata('login_id');
        }
			$actdata=array(
			                            'bid'=>$loginId ,
				                        'uid'=>$this->web->session->userdata('login_id'),
				                        'activity'=>"New Department Added",
				                        'date_time'=>time()
				
			                             );
			                  $data=$this->db->insert('activity',$actdata);
				$this->session->set_flashdata('msg','New Department Added!');
				redirect('add-depart');
			}
		}
		else{
			redirect('user-login');
		}
	}
	public function editdepart(){
		if(!empty($this->session->userdata('id'))){
			$id = $this->input->post('data');
			$dep = $this->web->getDepartById($id);
			$data['value'] = $dep;
			$data['option'] = 'edit_dep';
			$this->load->view('department/edit',$data);
			
		}
		else{
			redirect('user-login');
		}
	}
	public function editdepartment(){
		if(!empty($this->session->userdata('id'))){
			$check=$_REQUEST;
			print_r($check);
			echo $name = $_POST['name'];
			echo $prefix = $_POST['prefix'];
			echo $remark = $_POST['remark'];
			echo $id = $_POST['id'];
			$data = array(
				'department' => $name,
				'Dep_code' => $prefix,
				'remark' => $remark
			);
			print_r($data);
			$this->db->where('id',$id);
			$res = $this->db->update('department',$data);
			
			$uname = $this->web->getNameByUserId($id);
				 if ($this->session->userdata()['type'] == 'P') {
          
          $loginId = $this->session->userdata('empCompany');
         // $role=$this->web->getRollbyid($this->web->session->userdata('login_id'),$loginId);
        } else {
          $loginId = $this->web->session->userdata('login_id');
        }
			$actdata=array(
			                            'bid'=>$loginId ,
				                        'uid'=>$this->web->session->userdata('login_id'),
				                        'activity'=>"Department data updated",
				                        'date_time'=>time()
				
			                             );
			                  $data=$this->db->insert('activity',$actdata);
			echo $res;
			return($res);
		}
		else{
			redirect('user-login');
		}
	}
	public function addsubdepartment(){
		if (!empty($this->session->userdata('id'))) {
			$data['users']=$this->web->getBusinessUser();
			$this->load->view('department/add_sub_department',$data);
		}
		else{
			redirect('user-login');
		}
	}
	public function create_subdepartment(){
		if(!empty($this->session->userdata('id'))){
			$id = $this->input->post("dept");
			$subd = $this->input->post("subdepartment");

			$data = array(
				'depart_name' => $subd,
				'department_id' => $id,
				'date' => time()
			);

			$res = $this->db->insert('department_sub', $data);

			if ($res) {
				$this->session->set_flashdata('msg','Sub-department Added Successfully!');
				redirect('add-sub-depart');
			}
		}
		else{
			redirect('user-login');
		}
	}
	public function assigndepart(){
		if(!empty($this->session->userdata('id'))){
			$data['users']=$this->web->getBusinessUser();
			$data['department']=$this->web->getDepartmentList();
			$this->load->view('department/assign_department',$data);
		}
		else{
			redirect('user-login');
		}
	}
	public function assign(){
		if(!empty($this->session->userdata('id'))){
			$department=$this->input->post('department');
			$userid=$this->input->post('userid');
			$d=count($department);
			$res = 0;
			for($i=0;$i<$d;$i++){
				//echo $i;
				$check = $this->web->checkAssignDepart($userid, $department[$i]);
				if (!empty($check)) {
					continue;
				}
				$data=array(
					'department_id'=>$department[$i],
					'user_bussiness_id'=>$userid,
					'type'=>0
				);
				//print_r($data);
				$res=$this->web->assigndata($data);
			}

			if($res > 0){
				$this->session->set_flashdata('msg','Department Assigned Successfully!');
				redirect('assign-depart');
			}else{
				$this->session->set_flashdata('msg','Departments Already Assigned!');
				redirect('assign-depart');
			}

		}
		else{
			redirect('user-login');
		}
	}
	public function assignsubdepart(){
		if (!empty($this->session->userdata('id'))) {
			$data['department']=$this->web->getDepartmentList();
			$data['users']=$this->web->getBusinessUser();
			$this->load->view('department/assign_sdepart',$data);
		}else{
			redirect('user-login');
		}
	}
	public function assignsubdepartment(){
		if(!empty($this->session->userdata('id'))){
			$id = $this->input->post("userid");
			$dept = $this->input->post("department");
			$subd = $this->input->post("subdepartment");

			$data = array(
				'user_business_id' => $id,
				'depart_id' => $dept,
				'subdepart_id' => $subd,
				'date' => time()
			);

			$res = $this->db->insert('assigned_sdepartment', $data);

			if ($res) {
				$this->session->set_flashdata('msg','Subdepartment Assigned Successfully!');
				redirect('assign-sdepart');
			}
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
	public function GenLogin(){
		if (!empty($this->session->userdata('id'))) {
			$id = $this->input->post("id");
			$info = $this->web->getBusinessById($id);
			$uname = $info['mobile'];
			$pass = '123';

			$data = array(
				'login_id' => $id,
				'username' => $uname,
				'password' => md5($pass),
				'type' => 'B',
				'date' => time()
			);
			$res = $this->db->insert("web_login", $data);
			if($res){
				redirect('users');
			}
		}else{
			redirect('user-login');
		}
	}
	public function GenPersonalLogin(){
		if (!empty($this->session->userdata('id'))) {
			$id = $this->input->post("id");
			$info = $this->web->getBusinessById($id);
			$uname = $info['mobile'];
			$pass = '123';

			$data = array(
				'login_id' => $id,
				'username' => $uname,
				'password' => md5($pass),
				'type' => 'P',
				'date' => time()
			);
			$res = $this->db->insert("web_login", $data);
			if($res){
				redirect('users');
			}
		}else{
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
	public function showBusinessDeparts(){
		if (!empty($this->session->userdata('id'))) {
			$id = $this->session->userdata('login_id');
			$deptid = $this->web->getDepartByBusiness($id);
			foreach ($deptid as $key => $value) {
				$deptname[] = $this->web->getDepartById($value->depid);
			}
			$data['names'] = $deptname;
			$data['id'] = $id;
			$this->load->view('department/b_depart', $data);
		} else {
			redirect('user-login');
		}
	}
	public function businessTokens(){
		if(!empty($this->session->userdata('id'))){
			$id = $this->session->userdata('login_id');
			$deptid = $this->web->getDepartByBusiness($id);
			$data['depids'] = $deptid;
			$data['bid'] = $id;
			$this->load->view('token/tokens', $data);
		}else{
			redirect('user-login');
		}
	}
	public function counterTokens(){
		if(!empty($this->session->userdata('id'))){
			$id = $this->session->userdata('login_id');
			$cinfo = $this->web->getCounterInfo($id);
			$subdepts = $this->web->getSubDepartByBusiness($cinfo['business_id'],$cinfo['depart_id']);
			$data['depids'] = $cinfo['depart_id'];
			$data['cid'] = $cinfo['business_id'];
			$data['sdepts'] = $subdepts;
			$this->load->view('token/tokens', $data);
		}else{
			redirect('user-login');
		}
	}
	public function activateToken(){
		if(!empty($this->session->userdata('id'))){
			$id = $this->input->post('id');
			$uid = $this->input->post('uid');
			$cid = $this->input->post('cid');
			$bid = $this->input->post('bid');
			$res= $this->web->tokenActivate($id,$cid);
			if ($res) {
				$msg = "You token has been called!";
				$topic = "Token Called";
				$auth_key = $this->web->getUserAuthKey($uid)['fid'];
				$send = $this->push_notification_android($msg,$auth_key,$topic);
				if ($send) {
					echo json_encode(array("id" => $id,"uid" => $uid,"cid" => $cid,"bid" => $bid)) ;
					//return($resp);
				}

			}
		}else{
			redirect('user-login');
		}
	}
	public function closeToken(){
		if(!empty($this->session->userdata('id'))){
			$id = $this->input->post('id');
			$uid = $this->input->post('uid');
			$cid = $this->input->post('cid');
			$bid = $this->input->post('bid');
			$response = $this->web->tokenClose($id,$cid);
			if ($response) {
				echo json_encode(array("id" => $id,"uid" => $uid,"cid" => $cid,"bid" => $bid));
			}
		}else{
			redirect('user-login');
		}
	}
	public function callNextToken(){
		if(!empty($this->session->userdata('id'))){
			$type = $this->input->post('calltype');
			$cid = $this->input->post('tid');
			$bid = $this->input->post('bid');
			if($type == 1){
				$result = $this->web->getTokenInfo($cid,$bid);	//Check depart for open tokens
			}elseif ($type == 2) {
				$result = $this->web->getTokenBySubDept($cid,$bid);
			}
			//print_r($result);
			if(!empty($result)){
				$check = 0;
				$check1 = 0;
				foreach($result as $val){
					if($val->status == 1 && $check1 == 0){//if open token's no. more than closed token activate
						$id = $val->id;
						$user = $val->userid;
						$response = $this->web->tokenClose($id,$cid); //Close Active Token
						$check1 = 1;
						if($response){
							$send = true;	$lbreak = 0;
							foreach($result as $value){

								if($value->status == 0 && $value->id > $id){
									$res= $this->web->tokenActivate($value->id,$cid);
									$newid = $value->id;
									$msg = "Your token has been Called!";
									$topic = "Token Called";
									$newuser = $value->userid;
									$auth_key = $this->web->getUserAuthKey($newuser)['fid'];
									$send = $this->push_notification_android($msg,$auth_key,$topic);
									$lbreak = 1;
								}
								if ($lbreak > 0) {	break;	}
							}
							if ($send) {
								if($lbreak == 0){
									echo json_encode(array("type"=>"2","id" => $id,"uid" => $user,"cid" => $cid,"bid" => $bid));
									exit;
								}else{
									echo json_encode(array("type"=>"1","id" => $id,"nid" => $newid,"uid" => $user,"nuid" => $newuser,"cid" => $cid,"bid" => $bid));
									exit;
								}
							}
						}
					}
				}
				if($check1 == 0){
					foreach($result as $values){
						if($values->status == 0){

							$res= $this->web->tokenActivate($values->id,$cid);
							$newid = $values->id;
							$msg = "Your token has been Called!";
							$topic = "Token Called";
							$newuser = $values->userid;
							$auth_key = $this->web->getUserAuthKey($newuser)['fid'];
							$send = $this->push_notification_android($msg,$auth_key,$topic);
							if ($send) {
								echo json_encode(array("type"=>"3","id" => $newid,"uid" => $newuser,"cid" => $cid,"bid" => $bid));
								exit;
							}
						}
					}
				}
			}
		}else{
			redirect('user-login');
		}
	}
	public function closeTokendemo(){
		if(!empty($this->session->userdata())){
			$uid = $this->session->userdata('login_id');
			$id = $this->input->post('id');
			$userid = $this->input->post('uid');
			$response = $this->web->tokenClose($id);
			if ($response) {
				$deptid = $this->web->getDepartByBusiness($uid);	//Get all departs of user
				$check1 = 0; $check2 = 0;
				foreach($deptid as $business){
					$result = $this->web->getTokenInfo($business->depid);	//Check each depart for open tokens

					if(!empty($result)){
						foreach($result as $val){
							if($val->token > $id && $check1 == 0){	//if open token's no. more than closed token activate
								$res= $this->web->tokenActivate($val->token);
								$newid = $val->token;
								$msg = "You token has been activated!";
								$topic = "Token activated";
								$newuser = $val->userid;
								$auth_key = $this->web->getUserAuthKey($newuser)['fid'];
								$send = $this->push_notification_android($msg,$auth_key,$topic);
								if ($send) {
									//echo json_encode(array("id"=>$id,"nid"=>$newid,"uid"=>$newuser));
									$data = array(
										'id'=>$id,
										'nid'=>$newid,
										'nuser'=>$newuser
									);
									print_r($data);
									//echo $id; echo $newid; echo $newuser;
									return;
								}
								$check1 = 1;
							}

						}
					}
				}
			}
		}else{
			redirect('users');
		}
	}
	public function AssignCounter(){
		if (!empty($this->session->userdata('id'))) {
			$id = $this->session->userdata('id');
			$data['users']=$this->web->getBusinessUser();
			$data['counters']=$this->web->getAllCounters();
			$this->load->view('counter/assign',$data);
		}else{
			redirect('user-login');
		}
	}
	public function CounterAssign(){
		if (!empty($this->session->userdata('id'))) {
			echo $bname = $this->input->post('userid');
			echo $dname = $this->input->post('department');
			echo $num = $this->input->post('ncounter');
			$date = time();
			$name = 'User';
			$mdepid = $this->web->checkMaxCounterByDepart($bname,$dname);
			if($mdepid['mid'] != ''){
				$check = $mdepid['mid']+1;
				$num = $num + $mdepid['mid'];
			}else{
				$check = 1;
			}
			while($check <= $num){

				$uname = $name.$bname.$dname.$check;
				$data = array(
					'name'=>$uname,
					'mobile'=>'',
					'user_group'=>0,
					'otp'=>'0'
				);
				$res = $this->db->insert('login',$data);

				if($res){
					$logid = $this->web->getLoginIdByName($uname)['lid'];
					$data2 = array(
						'business_id'=>$bname,
						'depart_id'=>$dname,
						'counter_id'=>$check,
						'login'=>$logid,
						'date'=>$date
					);
					$res2 = $this->db->insert('counters',$data2);
					if($res2){
						$data3 = array(
							'login_id'=>$logid,
							'username'=>$uname,
							'password'=>md5('123'),
							'type'=>'C',
							'status'=>0,
							'date'=>$date
						);
						$res3 = $this->db->insert('web_login',$data3);
					}
				}
				$check++;
			}
			if($res3){
				$this->session->set_flashdata('msg','Counters Assigned Successfully!');
				redirect('assi-counter');
			}

		}else{
			redirect('user-login');
		}
	}
	public function editCounter(){
		if(!empty($this->session->userdata('id'))){
			$name = $_POST['cname'];
			$id = $_POST['lid'];
			$data = array(
				'name' => $name,
			);
			$data2 = array(
				'username' => $name,
			);
			$this->db->where('id',$id);
			$response = $this->db->update('login',$data);
			if($response){
				$this->db->where('login_id',$id);
				$res = $this->db->update('web_login',$data2);
			}
			echo json_encode(array('name' => $name,'id' => $id));
		}
		else{
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
	public function update(){
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
						redirect('c-pass');
					}
				}else{
					$this->session->set_flashdata('msg','Confirm password does not match!');
					redirect('c-pass');
				}
			}else{
				$this->session->set_flashdata('msg','Incorrect old password!');
				redirect('c-pass');
			}
		}else{
			redirect('user-login');
		}
	}
	public function updateDispName(){
		if (!empty($this->session->userdata('id'))) {
			$wid = $this->session->userdata('id');
			$id = $this->session->userdata('login_id');
			$pass = $this->input->post('pass');
			$uname = $this->input->post('name');
			$check = $this->web->checkOPass($wid,md5($pass));
			if (!empty($check)) {
				$data = array(
					'display_name' => $uname
				);
				$this->db->where('login',$id);
				$response = $this->db->update('counters',$data);
				if ($response) {
					$this->session->set_flashdata('msg','Display Name Updated!');
					redirect('c-pass');
				}
			}else{
				$this->session->set_flashdata('msg','Incorrect Password!');
				redirect('c-pass');
			}
		}else{
			redirect('user-login');
		}
	}
	public function updateUserName(){
		if(!empty($this->session->userdata('id'))){
			$wid = $this->session->userdata('id');
			$id = $this->session->userdata('login_id');
			$name = $this->input->post('uname');
			$pass = $this->input->post('password');
			$check = $this->web->checkOPass($wid,md5($pass));
			if (!empty($check)) {
				$nameCheck = $this->web->getLoginIdByName($name);
				if (!empty($nameCheck)) {
					echo 0;
					exit;
				}
				$data = array(
					'name' => $name,
				);
				$data2 = array(
					'username' => $name,
				);
				$this->db->where('id',$id);
				$response = $this->db->update('login',$data);
				if($response){
					$this->db->where('login_id',$id);
					$res = $this->db->update('web_login',$data2);
					if($res){
						echo 1;
					}
				}
			}
			else{
				echo $pass.'<br>';	print_r($check);
				$this->session->set_flashdata('msg','Incorrect Password!');
			}
		}else{
			redirect('user-login');
		}
	}

	public function updateappointPage($login_id,$business_id,$depart_id,$sub_depart_id){
		if (!empty($this->session->userdata('id'))) {
			$id = $this->session->userdata('login_id');
			$data['login_id']=$login_id;
			$data['business_id']=$business_id;
			$data['depart_id']=$depart_id;
			$data['subdepart_id']=$sub_depart_id;
			$data['get'] = $this->web->Get_appoiment_data($login_id,$business_id,$depart_id,$sub_depart_id);
			$this->load->view('setting/updateappoin',$data);
		}else{
			redirect('user-login');
		}

	}


	public function appointPage(){
		if (!empty($this->session->userdata('id'))) {
			$id = $this->session->userdata('login_id');
			$deptid = $this->web->getDepartByBusiness($id);
			foreach ($deptid as $key => $value) {
				$deptname[] = $this->web->getDepartById($value->depid);
			}
			$data['names'] = $deptname;
			$data['id'] = $id;
			$this->load->view('setting/appoint', $data);
		}else{
			redirect('user-login');
		}
	}
	public function appointUpdate(){
		if (!empty($this->session->userdata('id'))) {
			$id = $this->session->userdata('id');
			$login_id = $this->session->userdata('login_id');
			$post_data = $_POST;
			$idd=$this->input->post('id');
			//print_r($post_data);
			if (isset($post_data['monday'])) {
				$data['monday'] = 'open';
			}else{
				$data['monday'] = 'close';
			}
			if (isset($post_data['tuesday'])) {
				$data['tuesday'] = 'open';
			}else{
				$data['tuesday'] = 'close';
			}
			if (isset($post_data['wednesday'])) {
				$data['wednesday'] = 'open';
			}else{
				$data['wednesday'] = 'close';
			}
			if (isset($post_data['thursday'])) {
				$data['thursday'] = 'open';
			}else{
				$data['thursday'] = 'close';
			}
			if (isset($post_data['friday'])) {
				$data['friday'] = 'open';
			}else{
				$data['friday'] = 'close';
			}
			if (isset($post_data['saturday'])) {
				$data['saturday'] = 'open';
			}else{
				$data['saturday'] = 'close';
			}
			if (isset($post_data['sunday'])) {
				$data['sunday'] = 'open';
			}else{
				$data['sunday'] = 'close';
			}

			$data['login_id'] = $id;
			$data['bussiness_id'] = $login_id;
			$data['open_time'] = strtotime($post_data['opent']);
			$data['close_time'] = strtotime($post_data['closet']);
			$data['break_start_time'] = strtotime($post_data['breakst']);
			$data['break_end_time']= strtotime($post_data['breakct']);
			$data['slot_diff']= $post_data['timediff'];
			$data['department']=$post_data['department'];
			$data['subdepart']=$post_data['sdepartment'];
			print_r($data);
			$chk = $this->db->query("SELECT * FROM appoint_setting WHERE id = '$idd' ")->row_array();
			//print_r($chk);

			if($data['login_id']==$chk['login_id'] && $data['bussiness_id']==$chk['bussiness_id'] && $data['department']==$chk['department'] && $data['subdepart']==$chk['subdepart']){
				$this->db->where('id',$idd);
				$response = $this->db->update('appoint_setting',$data);
			}
			else{
				$response = $this->db->insert('appoint_setting',$data);
			}
			if ($response) {
				$this->session->set_flashdata('msg','Settings Updated Successfully!');
				redirect('update-appointment');
			}
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
	public function appointment(){
		if ($this->session->userdata('type')=="A") {
			$data['get_login'] = $this->session->userdata();
			$data['mm']=$this->web->GetAssin($data['get_login']['id']);
			$data['users']=$this->web->getBusinessUser();
			$data['page']=$this->web->getPages();
			$this->load->view('appointment/assign',$data);
		}
	}
	public function add_assign_page(){
		if ($this->session->userdata()!=""){
			$postdata=$this->input->post();
			$oo=$this->web->getAssignPage($postdata['userid'],$postdata['pageId']);
			if(!empty($oo['assign_menu_id'] && $oo['assign_bussiness_id'])){
				$this->session->set_flashdata('msg', 'Page already assigned!');
				redirect('assign-appointment');
			}else{

				$res=$this->db->insert('assign_menu',array(
					'assign_menu_id'=>$postdata['pageId'],
					'assign_by'=>$this->session->userdata('id'),
					'assign_bussiness_id'=>$postdata['userid'],
					'date'=>time()));
					if($res > 0){
						$this->session->set_flashdata('msg', 'Page assigned successfully!');
						redirect('assign-appointment');
					}
				}
			}
			else{
				redirect('index');
			}
		}


		public function appoinments(){
			if (!empty($this->session->userdata('id'))) {
				$uid = $this->session->userdata('login_id');
				//print_r($uid);
				$data['appoime']=$this->web->GetBookAppo($uid);
				$this->load->view('appointment/view-appoiments',$data);
			}else{
				redirect('user-login');
			}
		}
		public function checkIn($id){
			$data['mid']=$id;
			$this->load->view('users/checkIn',$data);
		}
		public function verify(){
			$data=$this->input->post();
			$check=$this->web->checkMobile($data['mobile']);
			$otp=rand(1000,9999);
			if(empty($check)){
				$array=array('mobile'=>$data['mobile'],'otp'=>$otp);
				$this->db->insert('login',$array);
				$msg="Your Checkin OTP:$otp";
				$this->sendsms($data['mobile'],$msg);
				$data=$this->session->set_userdata(array('data'=>$array,'session'=>$data['session']));
				$this->session->flashdata('msg','OTP Send Successfully!');
				redirect('User/verfityotp');
			}
			else{
				$array=array('mobile'=>$data['mobile'],'otp'=>$otp);
				$this->db->where('mobile',$data['mobile']);
				$this->db->update('login',array('otp'=>$otp));
				$msg="Your Checkin OTP:$otp";
				$this->sendsms($data['mobile'],$msg);
				$data=$this->session->set_userdata(array('data'=>$array,'session'=>$data['session']));
				$this->session->flashdata('msg','OTP Send Successfully!');
				redirect('User/verfityotp');
			}
		}
		public function sendsms($mobile,$msg){
			$url="http://185.136.166.131/domestic/sendsms/bulksms.php?username=checkon&password=checkon&type=TEXT&sender=checkk&mobile=$mobile&message=".urlencode($msg);
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$output = curl_exec($ch);
			curl_close($ch);
		}
		public function verfityotp(){
			$data['gg']=$this->session->userdata();
			$mobile_no=$data['gg']['data']['mobile'];
			$data['mobile']=$this->web->checkMobile($mobile_no);
			$this->load->view('users/verify',$data);
		}
		public function verfiyData(){
			$postdata=$this->input->post();
			$verify=$this->session->userdata('data');
			$check=$this->web->checkMobile($verify['mobile']);
			$ID=$this->web->DataGet(base64_decode($this->session->userdata('session')));
			$mid=$ID['m_id'];
			$IDS=$ID['id'];
			if($postdata['otp']==$verify['otp']){
				$this->db->insert('userqrdetails',array('scanby'=>$checkID,'scanid'=>$IDS,'user_group'=>2,'ShareType'=>'entry','date'=>date('Y-m-d h:i:s')));
				$this->db->insert('userqrdetails',array('scanby'=>$IDS,'scanid'=>$checkID,'user_group'=>2,'ShareType'=>'entry','date'=>date('Y-m-d h:i:s')));
				$this->db->where('mobile',$verify['mobile']);
				$this->db->update('login',array('name'=>$postdata['name'],'email'=>$postdata['email'],'otp'=>'0'));
				$this->session->unset_userdata('data');
				$this->session->unset_userdata('session');
				$this->session->set_flashdata('msg','You have Check-in Successfully!');
				redirect("User/checkIn/".$mid);
			}
			else{
				$this->session->set_flashdata('msg','OTP is wrong!');
				redirect('User/verfityotp');
			}

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

		public function request_data(){
			if (!empty($this->session->userdata('id'))) {
				$data['qr']=$this->web->getRequest();
				$this->load->view('request/request',$data);
			}else{
				redirect('user-login');
			}
		}

		function print_qr($id){
			$info = $this->web->getUsers($id);
			$base_url=$info['baseurl'];
			$folder="assets/qrimage/";
			$file_name=uniqid().'.png';
			QRcode::png($base_url,$folder.$file_name);
			$this->db->where('id',$id);
			$oo=$this->db->update('login',array('Generated_Qr'=>$file_name));
			if($oo){
				$this->session->set_flashdata('msgg','QR Code Generated!');
				redirect('view-request');
			}

		}

		public function activeNewQR(){
			if(!empty($this->session->userdata('id'))){
				$data['new_qr']=$this->web->getallNewQr();
				$this->load->view('new_qr',$data);
			}
			else{
				redirect('user-login');
			}
		}

	public function generateNewQR(){
			if(!empty($this->session->userdata('id'))){
				$postdata=$this->input->post();
				if(is_numeric($postdata['generate']) && $postdata['generate']>0){
					$count = count($this->web->getallNewQr());

					$data = array();
					$folder="assets/new_qr/";
					$licence=0;
				// 	if(isset($postdata['silverbase'])){
				// 		$licence = 2;
				// 	}
				// 	if(isset($postdata['goldbase'])){
				// 		$licence = 1;
				// 	}
				// 	if(isset($postdata['silverboost'])){
				// 		$licence = 3;
				// 	}
				// 	if(isset($postdata['goldboost'])){
				// 		$licence = 4;
				// 	}
				    if(isset($postdata['licence'])){
						if($postdata['licence']=="qr"){
							$licence = 0;
						}else if($postdata['licence']=="silver_base"){
							$licence = 2;
						}else if($postdata['licence']=="gold_base"){
							$licence = 3;
						}else if($postdata['licence']=="silver_boost"){
							$licence = 4;
						}else if($postdata['licence']=="gold_boost"){
							$licence = 5;
						}
					}
					for($num=0; $num<$postdata['generate'];$num++){
						$text = uniqid().$count;
						$data[] = array(
							'qr_code'=>$text,
							'licence'=>$licence
						);
						$file_name=$text.'.jpeg';
						QRcode::jpg(base_url("User/qrProfile/".$text),$folder.$file_name);
						$this->zip->read_file($folder.$file_name);
						$count++;
					}
					$insert=$this->db->insert_batch('new_qr',$data);
					if($insert > 0){
						$this->zip->download('my_backup.zip');
						$this->session->set_flashdata('msg','New QR Generated!');
						redirect('new-qr');
					}
				}else{
					redirect('new-qr');
				}
			}
			else{
				redirect('user-login');
			}
		}

		public function qrProfile($id)
		{
			$this->load->view('users/qrProfile');
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


		public function updateEmployeeDocuments()
		{
			$empId = $this->input->post('emp_id');

			if (!$empId) {
				$this->session->set_flashdata('msg', 'Invalid Employee');
				redirect('employees');
			}

			$uploadPath = './uploads/employee_documents/' . $empId . '/';

			if (!is_dir($uploadPath)) {
				mkdir($uploadPath, 0777, true);
			}

			$config['upload_path']   = $uploadPath;
			$config['allowed_types'] = 'jpg|jpeg|png|pdf';
			$config['max_size']      = 500; // KB
			$config['encrypt_name']  = true;

			$this->load->library('upload');

			$documents = [
				'bank_proof',
				'medical_certificate',
				'adhar_doc',
				'pan_doc',
				'photo'
			];

			$data = [];

			foreach ($documents as $doc) {

				if (!empty($_FILES[$doc]['name'])) {

					$config['file_name'] = $doc . '_' . time();
					$this->upload->initialize($config);

					if (!$this->upload->do_upload($doc)) {

						$this->session->set_flashdata(
							'msg',
							$this->upload->display_errors()
						);
						redirect($_SERVER['HTTP_REFERER']);
					}

					$uploadData = $this->upload->data();
					$data[$doc] = $uploadData['file_name'];
				}
			}

			// 🔹 Save / Update in DB (example)
			if (!empty($data)) {
				$this->web->updateEmployeeDocuments($empId, $data);
			}

			$this->session->set_flashdata('msg', 'Documents updated successfully');
			redirect($_SERVER['HTTP_REFERER']);
		}



		public function dailyreport(){
			if(!empty($this->session->userdata('id'))){
				$loginId = $this->web->session->userdata('login_id');
				$sections = $this->app->getSections($loginId);
				$departments = $depart = $this->app->getDepartmentSections($loginId);
				$shifts = $this->app->getBusinessGroups($loginId);
				$data=array(
					'sections'=>$sections,
					'departments'=>$departments,
					'shifts'=>$shifts,
				);
				$this->load->view('attendance/dailyreport',$data);
			}
			else{
				redirect('user-login');
			}
		}
		
		public function daily_report(){
				if(!empty($this->session->userdata('id'))){
					$postdata=$this->input->post();
					$start_date = date("Y-m-d");
					$true = 0;
					$days_array = array();
					$days_arrayn = array();
					$new_array = array();
					if ($this->session->userdata()['type'] == 'P') {
					$loginId = $this->session->userdata('empCompany');
					$role=$this->web->getRollbyid($this->web->session->userdata('login_id'),$loginId);
					} else {
					$loginId = $this->web->session->userdata('login_id');
					}
					// if($this->session->userdata('type')=="P"){
					// 	$userCmp = $this->app->getUserCompany($loginId);
					// 	if(isset($userCmp) && ($userCmp['left_date']=="" || $userCmp['left_date']>time())){
					// 		$loginId = $userCmp['business_id'];
					// 	}
					// }
					$cmpName = $this->web->getBusinessById($loginId);
					$sections = $this->app->getSections($loginId);
					$departments = $depart = $this->app->getDepartmentSections($loginId);
					$shifts = $this->app->getBusinessGroups($loginId);
					$depart="all";
					$section="all";
					$shift="all";
					$action="active";

					if(isset($postdata['start_date'])){
						$start_date = $postdata['start_date'];
						$depart = $postdata['depart'];
						$section = $postdata['section'];
						$shift = $postdata['shift'];
						$action = $postdata['action'];
					}
					$true= 1;
					$totalActive = 0;
					$totalPresent = 0;
					$totalAbsent = 0;
					$totalMispunch = 0;
					$totalHalfDay = 0;
					$totalLate = 0;
					$totalEarly = 0;
					$totalShortLeave = 0;
					$totalUnverified = 0;
					$totalFieldDuty = 0;

					$totalWeekOff = 0;
					$totalHoliday = 0;
					$totalLeaves = 0;
					$totalManual = 0;
					$totalGps = 0;

					$users_data = $this->app->getCompanyUsers($loginId);
					
					$start_time = strtotime(date("d-m-Y 00:00:00",strtotime($start_date)));

					
					$end_time = strtotime(date("d-m-Y 23:59:59",strtotime($start_date)));

					$holidays = $this->app->getHoliday($loginId);
					
					$holiday_array = array();
					if($holidays){
						foreach($holidays as $holiday){
							$holiday_array[] = array(
								'date'=>date('d.m.Y',$holiday->date),
							);
						}
					}

				
					if($this->session->userdata()['type']=='P'){
						$role=$this->web->getRollbyid($this->web->session->userdata('login_id'),$loginId);
						if($role[0]->type!=1){
							$roleDepartments = explode(",",$role[0]->department);
							$roleSections = explode(",",$role[0]->section);
							 $team = explode(",",$role[0]->team);

							foreach($departments as $dK=> $dp){
								$checkDp = array_search($dp->id,$roleDepartments);
								if(!is_bool($checkDp)){

								}else{
									unset($departments[$dK]);
								}
							}

							foreach($sections as $sK=> $se){
								$checkSe = array_search($se->type,$roleSections);
								if(!is_bool($checkSe)){

								}else{
									unset($sections[$sK]);
								}
							}
							if(!empty($roleDepartments[0]) || !empty($roleSections[0]) || !empty($team[0])){
								foreach ($users_data as $key => $dataVal) {
								$uname = $this->web->getNameByUserId($dataVal->user_id);
								$roleDp = array_search($uname[0]->department,$roleDepartments);
								$roleSection = array_search($uname[0]->section,$roleSections);
								 $roleTeam = array_search($dataVal->user_id,$team);
                   
								if(!is_bool($roleTeam) ||!is_bool($roleDp) || !is_bool($roleSection)){
									
								}else{
									unset($users_data[$key]);
								}
								} 
							}
						}
					}

					if(!empty($users_data)){
						$seconds = 0;
						foreach($users_data as $user){
							if($section=="all" || $user->section==$section){
								if($depart=="all" || $user->department==$depart){
									if($shift=="all" || $user->business_group==$shift){
										$groups = $this->app->getUserGroup($user->business_group);
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
										$sl = "s";
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

										$new_start_time = strtotime(date("d-m-Y 00:00:00",strtotime($start_date)));
										$new_end_time = strtotime(date("d-m-Y 23:59:59",strtotime($start_date)));
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
										$unverified = "0";
										$fieldDuty = "0";
										$manual = "0";
										$gps = "0";
										$day_status="";
										$day_sub_status="";
										if(($user->doj!="" || $start_time>=$user->doj) && ($user->left_date=="" || $start_time<$user->left_date)){
											$totalActive++;
											$user_at = $this->app->getUserAttendanceReportByDate($new_start_time,$new_end_time,$user->user_id,$loginId,1);

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
												    $timeSearch = array_search($at->io_time,array_column($data,'time'));
													if(is_bool($timeSearch)){
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
    													if($at->manual=="1"){
    														$manual="1";
    													}
    													if($at->location!=""){
    														$gps="1";
    													}
													}
												}//at
												if($fieldDuty=="1"){
													$totalFieldDuty++;
												}
												if($unverified=="1"){
													$totalUnverified++;
												}
												if($manual=="1"){
													$totalManual++;
												}
												if($gps=="1"){
													$totalGps++;
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
														$minutes = floor($late_seconds / 60)%60;
														$late_hrs = "$hours:$minutes Hr";
														$totalLate++;
														if($sl_late_on=="1" && ($late_seconds > $sl_late_time)){
															$sl ="SL";
														}
													}
													if($outs_array[count($outs_array)-1]!="0"){
																$out_end = strtotime(date("d-m-Y h:i A",strtotime(date("h:i A",$outs_array[count($outs_array)-1]))));
																if($sh_end>$out_end && $out_end!=0){
																	$early_seconds = $sh_end-$out_end;
																	$hours = floor($early_seconds / 3600);
																	$minutes = floor($early_seconds / 60)%60;
																	$early_hrs = "EL $hours:$minutes Hr";
																	$totalEarly++;
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
															$minutes = floor($ot_seconds / 60)%60;
															$ot_hrs = "$hours:$minutes Hr";
														}
													}
												} //shift

												if($overtime_wh_on=="1" &&($day_seconds>$ov_wo_time)){
													$ot_seconds = $day_seconds-$ov_wo_time;
													if($ot_seconds>0){
														$hours = floor($ot_seconds / 3600);
														$minutes = floor($ot_seconds / 60)%60;
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
													$totalMispunch++;
													$mhsStatus="ms";
												}else if($halfday=="1"){
													$totalHalfDay++;
													$mhsStatus="hf";
												}else if($sl=="SL"){
													$totalShortLeave++;
													$mhsStatus="sl";
												}
											}

											if(($action=="active")||($action=="present" && count($data)>0)||($action=="absent" && empty($data))||($action=="mispunch" && $mhsStatus=="ms")||($action=="halfday" && $mhsStatus=="hf")||($action=="late" && $late_seconds>0)||($action=="early" && $early_seconds>0)||($action=="shortLeave" && $mhsStatus=="sl")||($action=="unverified" && $unverified=="1")||($action=="fieldDuty" && $fieldDuty=="1") ||($action=="manual" && $manual=="1") ||($action=="gps" && $gps=="1")){
												$new_array[] =array(
													'user_id'=>$user->user_id,
													'mid'=>$user->mid,
													'emp_code'=>$user->emp_code,
													'name'=>$user->name,
													'image'=>$user->image,
													'user_status'=>$user->user_status,
													'shift_start'=>$shift_start,
													'shift_end'=>$shift_end,
													'group_name'=>$group_name,
													'designation'=>$user->designation,
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

										}
									}
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
						//'ins_array'=>$ins_array,
						'departments'=>$departments,
						'sections'=>$sections,
						'shifts'=>$shifts,
						'depart'=>$depart,
						'section'=>$section,
						'totalActive'=>$totalActive,
						'totalAbsent'=>$totalAbsent,
						'totalPresent'=>$totalPresent,
						'totalMispunch'=>$totalMispunch,
						'totalHalfDay'=>$totalHalfDay,
						'totalLate'=>$totalLate,
						'totalEarly'=>$totalEarly,
						'totalShortLeave'=>$totalShortLeave,
						'totalUnverified'=>$totalUnverified,
						'totalFieldDuty'=>$totalFieldDuty,
						'totalManual'=>$totalManual,
						'totalGps'=>$totalGps,
						'shift'=>$shift,
						'cmp_name'=>$cmpName['name']
					);

					$this->load->view('attendance/dailyreport',$data);
				}
				else{
					redirect('user-login');
				}
		}

		public function monthly_report_back(){
				if(!empty($this->session->userdata('id'))){
					$postdata=$this->input->post();
					$start_date = date("Y-m-d");
					$end_date = date("Y-m-d");
					$true = 0;
					$days_array = array();
					$new_array = array();
					$loginId = $this->session->userdata('login_id');
					if($this->session->userdata('type')=="P"){
						$userCmp = $this->app->getUserCompany($loginId);
						if(isset($userCmp) && ($userCmp['left_date']=="" || $userCmp['left_date']>time())){
							$loginId = $userCmp['business_id'];
						}
					}
					$cmpName = $this->web->getBusinessById($loginId);
					$sections = $this->app->getSections($loginId);
					$departments = $depart = $this->app->getDepartmentSections($loginId);
					$shifts = $this->app->getBusinessGroups($loginId);
					$depart="all";
					$section="all";
					$shift="all";
					$status_check = 1;
					$working_check = 0;
					$totals_check = 1;
					$all_check = 1;
					$two_check = 0;
					$late_check = 0;
					$early_check = 0;
					if(isset($postdata['start_date']) && isset($postdata['end_date'])){
						$start_date = $postdata['start_date'];
						$end_date = $postdata['end_date'];
						$depart = $postdata['depart'];
						$section = $postdata['section'];
						$shift = $postdata['shift'];
						if(isset($postdata['status_check'])){
							$status_check=1;
						}else{
							$status_check=0;
						}
						if(isset($postdata['working_check'])){
							$working_check=1;
						}else{
							$working_check=0;
						}
						if(isset($postdata['totals_check'])){
							$totals_check=1;
						}else{
							$totals_check=0;
						}
						if(isset($postdata['all_check'])){
							$all_check=1;
						}else{
							$all_check=0;
						}
						if(isset($postdata['two_check'])){
							$two_check=1;
						}else{
							$two_check=0;
						}
						if(isset($postdata['late_check'])){
							$late_check=1;
						}else{
							$late_check=0;
						}
						if(isset($postdata['early_check'])){
							$early_check=1;
						}else{
							$early_check=0;
						}
						$true= 1;

						$users_data = $this->app->getCompanyUsers($loginId);
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
								if($section=="all" || $user->section==$section){
									if($depart=="all" || $user->depart==$depart){
										if($shift=="all" || $user->business_group==$shift){
											$date1=date_create(date("Y-m-d",strtotime($start_date)));
											$date2=date_create(date("Y-m-d",strtotime($end_date)));
											$diff=date_diff($date1,$date2);
											$num_month = $diff->format("%a");

											$num_month++;
											if($num_month>31){
												$num_month=31;
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
											$totalShortLeave = 0;
											$totalP2 = 0;
											$totalOT = 0;
											$totalWorkingHrs = "00:00 Hr";
											$totalLate = "00:00 Hr";
											$totalEarly = "00:00 Hr";
											$days_array = array();
											$seconds = 0;
											for($d=0; $d<$num_month;$d++){
												$new_start_time = strtotime(date("d-m-Y 00:00:00",strtotime($start_date))." +".$d." days");
												$new_end_time = strtotime(date("d-m-Y 23:59:59",strtotime($start_date))." +".$d." days");
												$days_array[]= date("d",$new_start_time);
												$data = array();
												$day_seconds=0;
												$late_seconds=0;
												$early_seconds=0;
												$ot_seconds=0;
												$day_hrs = "W.H 00:00 Hr";
												$late_hrs = "LT 00:00 Hr";
												$early_hrs = "EL 00:00 Hr";
												$ot_hrs = "OT 00:00 Hr";
												$halfday = "0";
												$absentWo = "0";
												$sl = "s";
												if(($user->doj!="" || $start_time>=$user->doj) && ($user->left_date=="" || $start_time<$user->left_date)){
										$user_at = $this->app->getUserAttendanceReportByDate($new_start_time,$new_end_time,$user->user_id,$loginId,1);

													$off = array_search(date('N',$new_start_time),array_column($grp,'day_off'));
													$holi = array_search(date('d.m.Y',$new_start_time),array_column($holiday_array,'date'));
													$lv = array_search(date('d.m.Y',$new_start_time),array_column($leaves_array,'date'));
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

														$hours = floor($day_seconds / 3600);
														$minutes = floor($day_seconds / 60%60);
														$day_hrs = "W.H $hours:$minutes Hr";

														if($day_seconds>0 && $halfday_on=="1" &&($day_seconds<$half_wo_time)){
															$halfday="1";
															$totalP2++;
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
																$late_hrs = "LT $hours:$minutes Hr";
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
																	$early_hrs = "EL $hours:$minutes Hr";
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
																	$ot_hrs = "OT $hours:$minutes Hr";
																}
															}
														}

														if($overtime_wh_on=="1" &&($day_seconds>$ov_wo_time)){
															$ot_seconds = $day_seconds-$ov_wo_time;
															if($ot_seconds>0){
																$hours = floor($ot_seconds / 3600);
																$minutes = floor($ot_seconds / 60%60);
																$ot_hrs = "OT $hours:$minutes Hr";
															}
														}
														if($sl!="SL"){
															if($weekOff=="1" || $holiday=="1"){
																$totalOT++;
															}else{
																if($halfday=="0"){
																	$totalPresent++;																	
																}
															}
														}else{
														    $totalShortLeave++;
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
														'sl'=>$sl
													);
												}
											}
											if($seconds>0){
												$hours = floor($seconds / 3600);
												$minutes = floor($seconds / 60%60);
												$totalWorkingHrs = "$hours:$minutes Hr";
											}
											if(count($months_array)>0){
											    $nwd = $totalPresent+($totalP2/2)+$totalWeekOff+$totalHoliday+$totalLeaves+$totalShortLeave+$totalOT;
												$new_array[] =array(
													'user_id'=>$user->user_id,
													'mid'=>$user->mid,
													'emp_code'=>$user->emp_code,
													'name'=>$user->name,
													'image'=>$user->image,
													'user_status'=>$user->user_status,
													'shift_start'=>$shift_start,
													'shift_end'=>$shift_end,
													'group_name'=>$group_name,
													'designation'=>$user->designation,
													'totalAbsent'=>$totalAbsent,
													'totalPresent'=>$totalPresent,
													'totalWeekOff'=>$totalWeekOff,
													'totalHoliday'=>$totalHoliday,
													'totalLeaves'=>$totalLeaves,
													'totalShortLeave'=>$totalShortLeave,
													'totalWorkingHrs'=>$totalWorkingHrs,
													'totalLate'=>$totalLate,
													'totalEarly'=>$totalEarly,
													'totalP2'=>$totalP2,
													'totalOT'=>$totalOT,
													'nwd'=>$nwd,
													'data'=> $months_array
												);
											}
										}
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
						'departments'=>$departments,
						'sections'=>$sections,
						'shifts'=>$shifts,
						'depart'=>$depart,
						'section'=>$section,
						'status_check'=>$status_check,
						'working_check'=>$working_check,
						'totals_check'=>$totals_check,
						'all_check'=>$all_check,
						'two_check'=>$two_check,
						'late_check'=>$late_check,
						'early_check'=>$early_check,
						'shift'=>$shift,
						'cmp_name'=>$cmpName['name']
					);
					//print_r($new_array);
					$this->load->view('attendance/monthly',$data);
				}else{
					redirect('user-login');
				}
			}
			
		public function monthly_report(){
		if(!empty($this->session->userdata('id'))){
			$postdata=$this->input->post();
			$start_date = date("Y-m-d");
			$end_date = date("Y-m-d");
			$true = 0;
			$days_array = array();
			$daysn_array = array();
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
			$sections = $this->app->getSections($loginId);
			$departments = $depart = $this->app->getDepartmentSections($loginId);
			$shifts = $this->app->getBusinessGroups($loginId);
			$depart="all";
			$section="all";
			$shift="all";
			$status_check = 1;
			$working_check = 0;
			$totals_check = 1;
			$all_check = 1;
			$two_check = 0;
			$late_check = 0;
			$early_check = 0;
			$recalc_check = 0;
			$action = 0;
			if(isset($postdata['start_date']) && isset($postdata['end_date'])){
				$start_date = $postdata['start_date'];
				$end_date = $postdata['end_date'];
				$depart = $postdata['depart'];
				$section = $postdata['section'];
				$shift = $postdata['shift'];
				$action = $postdata['action'];
				if(isset($postdata['status_check'])){
					$status_check=1;
				}else{
					$status_check=0;
				}
				if(isset($postdata['working_check'])){
					$working_check=1;
				}else{
					$working_check=0;
				}
				if(isset($postdata['totals_check'])){
					$totals_check=1;
				}else{
					$totals_check=0;
				}
				if(isset($postdata['all_check'])){
					$all_check=1;
				}else{
					$all_check=0;
				}
				if(isset($postdata['two_check'])){
					$two_check=1;
				}else{
					$two_check=0;
				}
				if(isset($postdata['late_check'])){
					$late_check=1;
				}else{
					$late_check=0;
				}
				if(isset($postdata['early_check'])){
					$early_check=1;
				}else{
					$early_check=0;
				}
				if(isset($postdata['recalculate_check'])){
					$recalc_check=1;
				}else{
					$recalc_check=0;
				}
				$true= 1;
				
				if($action==1){
					$status_check = 1;
					$working_check = 0;
					$totals_check = 1;
					$all_check = 0;
					$two_check = 0;
					$late_check = 0;
					$early_check = 0;
				}else if($action==2){
					$status_check = 0;
					$working_check = 1;
					$totals_check = 1;
					$all_check = 0;
					$two_check = 1;
					$late_check = 0;
					$early_check = 0;
				}else if($action==3){
					$status_check = 1;
					$working_check = 1;
					$totals_check = 1;
					$all_check = 0;
					$two_check = 1;
					$late_check = 0;
					$early_check = 0;
				}else if($action==4){
					$status_check = 0;
					$working_check = 0;
					$totals_check = 0;
					$all_check = 1;
					$two_check = 0;
					$late_check = 0;
					$early_check = 0;
				}else if($action==5){
					$status_check = 0;
					$working_check = 0;
					$totals_check = 0;
					$all_check = 0;
					$two_check = 0;
					$late_check = 1;
					$early_check = 1;
				}

				$users_data = $this->app->getCompanyUsers($loginId);
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
                $news_start_date=$start_date;
				if($this->session->userdata()['type']=='P'){
					//$role=$this->web->getRollbyid($this->web->session->userdata('login_id'),$loginId);
					if($role[0]->type!=1){
						$roleDepartments = explode(",",$role[0]->department);
						$roleSections = explode(",",$role[0]->section);
						$team = explode(",",$role[0]->team);

						foreach($departments as $dK=> $dp){
							$checkDp = array_search($dp->id,$roleDepartments);
							if(!is_bool($checkDp)){

							}else{
								unset($departments[$dK]);
							}
						}

						foreach($sections as $sK=> $se){
							$checkSe = array_search($se->type,$roleSections);
							if(!is_bool($checkSe)){

							}else{
								unset($sections[$sK]);
							}
						}

						if(!empty($roleDepartments[0]) || !empty($roleSections[0]) || !empty($team[0])){
							foreach ($users_data as $key => $dataVal) {
							$uname = $this->web->getNameByUserId($dataVal->user_id);
							$roleDp = array_search($uname[0]->department,$roleDepartments);
							$roleSection = array_search($uname[0]->section,$roleSections);
							 $roleTeam = array_search($dataVal->user_id,$team);
							if(!is_bool($roleTeam) ||!is_bool($roleDp) || !is_bool($roleSection)){
								
							}else{
								unset($users_data[$key]);
							}
							} 
						} 
					}
				}

				if(!empty($users_data)){
					foreach($users_data as $user){
						if($section=="all" || $user->section==$section){
							if($depart=="all" || $user->department==$depart){
								if($shift=="all" || $user->business_group==$shift){
								    	if($user->doj>=strtotime($start_date)){
											$news_start_date=date("Y-m-d",$user->doj);
									    	}else{
									    	$news_start_date=$start_date;
									    	}
									    //	$news_start_date=$start_date;
									    
									$date1=date_create(date("Y-m-d",strtotime($start_date)));
									$date2=date_create(date("Y-m-d",strtotime($end_date)));
									$diff=date_diff($date1,$date2);
									$num_month = $diff->format("%a");

									$num_month++;
									if($num_month>31){
										$num_month=31;
									}

									$groups = $this->app->getUserGroup($user->business_group);
									$grp = array();
									$day_shift_start = array();
									$day_shift_end = array();
                                  $start_time3 = strtotime(date("d-m-Y 06:00:00",strtotime($start_date)));
									if($groups){
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

									$leaves = $this->app->getEmpLeaves($user->user_id);
									$leaves_array = array();
									$leave_days=0;
									if($leaves){
										foreach($leaves as $leave){
											$from_date_leave=date_create(date("Y-m-d",$leave->from_date));
											$to_date_leave=date_create(date("Y-m-d",$leave->to_date));
											$leave_diff=date_diff($from_date_leave,$to_date_leave);
										//	$leave_days = $leave_diff->format("%a");
										$half_day=$leave->half_day;
											$leave_days=$half_day;
											for($l=0;$l<$leave_days;$l++){
												$leave_start_date = strtotime(date("d-m-Y",$leave->from_date)." +".$l." days");
												$leaves_array[] = array(
													'date'=>date('d.m.Y',$leave_start_date),
												);
											}
										}
									}
									
					$onduty =$this->web->getUserOTbyID($user->user_id);
						$od_array = array();
			//	$od_days =0;
				if($onduty){
					
					foreach($onduty as $onduty){
				 
							  $from_date_od=date_create(date("Y-m-d",$onduty->date));
							  $to_date_od=date_create(date("Y-m-d",$onduty->end_date));
							  $od_diff=date_diff($from_date_od,$to_date_od);
							  $od_days = $od_diff->format("%a");
							  $od_days++;
							  for($c=0;$c<$od_days;$c++){
												$od_start_date = strtotime(date("d-m-Y",$onduty->date)." +".$c." days");
												$od_array[] = array(
													'date'=>date('d.m.Y',$od_start_date),
												);
											}
                      }
                 }
                 
                 	$wfh =$this->web->getUserbywfhbyID($user->user_id);
						$wfh_array = array();
			//	$od_days =0;
				if($wfh){
					
					foreach($wfh as $wfh){
				 
							  $from_date_wfh=date_create(date("Y-m-d",$wfh->date));
							  $to_date_wfh=date_create(date("Y-m-d",$wfh->end_date));
							  $wfh_diff=date_diff($from_date_wfh,$to_date_wfh);
							  $wfh_days = $wfh_diff->format("%a");
							  $wfh_days++;
							  for($c=0;$c<$wfh_days;$c++){
												$wfh_start_date = strtotime(date("d-m-Y",$wfh->date)." +".$c." days");
												$wfh_array[] = array(
													'date'=>date('d.m.Y',$wfh_start_date),
												);
											}
                      }
                 }
                 
                 
                 
                 
                 
				// $od_dayst=$od_days;	
									
							

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
									$totalOD = 0;
									$totalwfh = 0;
									$totalShortLeave = 0;
									$totalP2 = 0;
									$totalOT = 0;
									$totalWorkingHrs = "00:00 Hr";
									$totalLate = "00:00 Hr";
									$totalEarly = "00:00 Hr";
									$days_array = array();
									$daysn_array = array();
									$seconds = 0;
									$previousAt = array();
									$nextAt = array();
									$monthStartTime = strtotime(date("d-m-Y 00:00:00",strtotime($start_date)));
									$monthEndTime = strtotime(date("d-m-Y 23:59:59",strtotime($start_date))." +".$num_month." days");
									$monthEndTime2 = strtotime(date("d-m-Y 23:59:59",strtotime($start_date))." +".($num_month-1)." days");
								//	$monthUserAt = $this->web->getUserAttendanceReportByDate($monthStartTime,$monthEndTime,$user->user_id,$loginId,1);
									$old_time = strtotime(date("31-12-2023 23:59:00"));
									$old_time2 = strtotime(date("30-06-2024 23:59:00"));
								    if($monthStartTime<$old_time){
								    $monthUserAt = $this->web->getUserAttendanceOldReportByDate($monthStartTime,$monthEndTime,$user->user_id,$loginId,1);    
								    }else if($monthStartTime<$old_time2){
								    $monthUserAt = $this->web->getUserAttendanceOld2ReportByDate($monthStartTime,$monthEndTime,$user->user_id,$loginId,1);    
								    }else{
								        
									$monthUserAt = $this->web->getUserAttendanceReportByDate($monthStartTime,$monthEndTime,$user->user_id,$loginId,1);
								    }
									
									
									
									
									
										$leavestime =$this->web->getUserActiveLeaves($user->user_id,$monthStartTime,$monthEndTime2);
				//$leaves =$this->getEmpLeaves($uid);
				//$leaves_array = array();
				$leave_days =0;
				if($leavestime){
					
					foreach($leavestime as $leavee){
				  //if($leave->date_time>=$user['open_date'] && $leave->date_time<=$user['close_date']){
                      if($leavee->type!="" && $leavee->type!="unpaid" && $leavee->status==1 ){
						  $half_day=$leavee->half_day;
                          $leave_days=$leave_days+$half_day;
                                  }
                    }
                 }
				 $leave_dayst=$leave_days;
									
									
								
									for($d=0; $d<$num_month;$d++){
									    
									    
										$new_start_time = strtotime(date("d-m-Y 00:00:00",strtotime($start_date))." +".$d." days");
										$new_end_time = strtotime(date("d-m-Y 23:59:59",strtotime($start_date))." +".$d." days");
										$next_day_start_time = strtotime(date("d-m-Y 00:00:00",strtotime($start_date))." +".($d+1)." days");
										$next_day_end_time = strtotime(date("d-m-Y 23:59:59",strtotime($start_date))." +".($d+1)." days");

										$pre_day_start_time = strtotime(date("d-m-Y 00:00:00",strtotime($start_date))." +".($d-1)." days");
										$pre_day_end_time = strtotime(date("d-m-Y 23:59:59",strtotime($start_date))." +".($d-1)." days");
									//	$days_array[]= date("d D",$new_start_time);
										$days_array[]= date("d",$new_start_time);
										$daysn_array[]= date("d D",$new_start_time);
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
										 if(($user->doj=="" || strtotime($end_date)>=$user->doj) && ($user->left_date=="" || strtotime($start_date)<$user->left_date)){

											//if(($user->doj!="" || $new_start_time >=$user->doj)){
											if(($user->doj =="" || $new_start_time >=$user->doj) && ($user->left_date=="" || $new_start_time < $user->left_date)){
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
											
											
							//$news_start_time = strtotime(date("d-m-Y 00:00:00",strtotime($news_start_date))." +".$d." days");
											
											
											
											
											
											
					if($month_weekly_off!=0){	$off = array_search(date('d.m.Y',$news_start_time),array_column($grp,'day_off'));
												}else{
					                       $off = array_search(date('N',$news_start_time),array_column($grp,'day_off'));}
										
					                        $holi = array_search(date('d.m.Y',$news_start_time),array_column($holiday_array,'date'));
					                        $lv = array_search(date('d.m.Y',$new_start_time),array_column($leaves_array,'date'));
					                         $ods = array_search(date('d.m.Y',$new_start_time),array_column($od_array,'date'));
				                               $wfhs = array_search(date('d.m.Y',$new_start_time),array_column($wfh_array,'date'));

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
											if(!is_bool($ods)){
												$totalOD++;
												$day_OD="1";
											}else{
												$day_OD="0";
											}
											if(!is_bool($wfhs)){
												$totalwfh++;
												$day_wfh="1";
											}else{
												$day_wfh="0";
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
															$minutes = floor($ot_seconds / 60)%60;
															$ot_hrs = "$hours:$minutes";
														}
													}
												}

												if($overtime_wh_on=="1" &&($day_seconds>$ov_wo_time)){
													$ot_seconds = $day_seconds-$ov_wo_time;
													if($ot_seconds>0){
														$hours = floor($ot_seconds / 3600);
														$minutes = floor($ot_seconds / 60)%60;
														$ot_hrs = "$hours:$minutes";
													}
												}
												if($absentWo=="1"){
													$totalAbsent++;
												}else{
													if($sl!="SL"){
												// 		if($weekOff=="1" || $holiday=="1"){
												// 			$totalOT++;
												// 		}else{
															
												// 		}
														if($halfday=="0" && $weekOff=="0" && $holiday=="0" ){
														 	$totalPresent++;																	
														}elseif($weekOff=="1" || $holiday=="1") 
														{ $totalOT++; }
														
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
												if($weekOff=="0" && $holiday=="0" && $day_leave=="0" && $day_OD=="0" && $day_wfh=="0" ){
													$totalAbsent++;
												}
												$data = array();
											}

											$day_status = "A";

											if($day_leave=="1"){
												$day_status = "L";
											}
												if($day_OD=="1"){
												$day_status = "OD";
											}
											
												if($day_wfh=="1"){
												$day_status = "WFH";
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
													$day_status="W OT";
													if($mispunch=="1" && $msOut){
														$day_status="W MS";
													}
													if($halfday=="1"){
														$day_status="W OT/2";
													}
												}
												if($holiday=="1"){
													$day_status="H OT";
													if($mispunch=="1" && $msOut){
														$day_status="H MS";
													}
													if($halfday=="1"){
														$day_status="H OT/2";
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
												'onduty'=>$day_OD,
												'wfhduty'=>$day_wfh,
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
									if($seconds>0){
										$hours = floor($seconds / 3600);
										$minutes = floor($seconds / 60)%60;
										$totalWorkingHrs = "$hours:$minutes Hr";
									}
									if(count($months_array)>0){
										$nwd = $totalPresent+($totalP2/2)+$totalWeekOff+$totalHoliday+$totalLeaves+$totalOD+$totalwfh;
										$new_array[] =array(
											'user_id'=>$user->user_id,
											'mid'=>$user->mid,
											'emp_code'=>$user->emp_code,
											'name'=>$user->name,
											'image'=>$user->image,
											'user_status'=>$user->user_status,
											'shift_start'=>$shift_start,
											'shift_end'=>$shift_end,
											'group_name'=>$group_name,
											'designation'=>$user->designation,
											'totalAbsent'=>$totalAbsent,
											'totalPresent'=>$totalPresent+$totalOD+$totalwfh,
											'totalWeekOff'=>$totalWeekOff,
											'totalHoliday'=>$totalHoliday,
											'totalLeaves'=>$leave_dayst,
											'totalOD'=>0,
											'totalShortLeave'=>$totalShortLeave,
											'totalWorkingHrs'=>$totalWorkingHrs,
											'totalLate'=>$totalLate,
											'totalEarly'=>$totalEarly,
											'totalP2'=>$totalP2,
											'totalOT'=>$totalOT,
											'nwd'=>$nwd,
											'data'=> $months_array
										);
										usort($new_array, function($a, $b) {
										    if(empty($a['emp_code'])){
										        return -1;
										    }elseif ($a['emp_code'] > $b['emp_code']) {
                                                return 1;
                                            } elseif ($a['emp_code'] < $b['emp_code']) {
                                                return -1;
                                            }
                                            return 0;
                                        });
									}
								}
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
				'daysweek'=>$daysn_array,
				'departments'=>$departments,
				'sections'=>$sections,
				'shifts'=>$shifts,
				'depart'=>$depart,
				'section'=>$section,
				'status_check'=>$status_check,
				'working_check'=>$working_check,
				'totals_check'=>$totals_check,
				'all_check'=>$all_check,
				'two_check'=>$two_check,
				'late_check'=>$late_check,
				'early_check'=>$early_check,
				'shift'=>$shift,
				'action'=>$action,
				'cmp_name'=>$cmpName['name']
			);
			
			$this->load->view('attendance/monthly',$data);
		}else{
			redirect('user-login');
		}
	}

	

	public function monthlyreport2()
	{
		$this->load->view('attendance/monthly');
	}

	public function monthly_report2()
	{
		$start_date = $this->input->post('start_date');
		$end_date   = $this->input->post('end_date');
		$depart     = $this->input->post('depart');
		$section    = $this->input->post('section');
		$action     = $this->input->post('action');

		// companyId resolve
		if ($this->session->userdata()['type'] == 'P') {
			$companyId = $this->session->userdata('empCompany');
		} else {
			$companyId = $this->session->userdata('login_id');
		}

		/* ================= NODE API CALL ================= */

		$payload = json_encode([
			"companyId"  => 29643,
			"start_date" => $start_date,
			"end_date"   => $end_date,
			"department"=> $depart,
			"section"   => $section,
			"action"    => $action
		]);

		// echo '<pre>';
		// print_r($payload);
		// echo '<pre>';
		// die();

		$ch = curl_init("http://31.97.230.189:3000/api/attendance/monthly");

		curl_setopt_array($ch, [
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POST           => true,
			CURLOPT_HTTPHEADER     => ["Content-Type: application/json"],
			CURLOPT_POSTFIELDS     => $payload,
			CURLOPT_TIMEOUT        => 60
		]);

		$response = curl_exec($ch);
		curl_close($ch);

		$api = json_decode($response, true);

		if (!$api || $api["success"] !== true) {
			show_error("Attendance service unavailable");
		}

		/* ================= PASS DATA TO VIEW ================= */

		$data = [];
		$data["load"]     = $api["data"]["load"];
		$data["report"]   = $api["data"]["report"];
		$data["days"]     = $api["data"]["days"];
		$data["daysweek"] = $api["data"]["daysweek"];
		$data["cmp_name"] = $api["data"]["cmp_name"];
		$data["start_date"] = $api["data"]["start_date"];
		$data["end_date"]   = $api["data"]["end_date"];
		$ui = $api["data"]["uiFlags"];
		$data["status_check"]  = $ui["status"];
		$data["working_check"] = $ui["working"];
		$data["totals_check"]  = $ui["totals"];
		$data["all_check"]     = $ui["all"];
		$data["two_check"]     = $ui["two"];
		$data["late_check"]    = $ui["late"];
		$data["early_check"]   = $ui["early"];


		$this->load->view('attendance/monthly', $data);
	}

	// ---------- STATUS LOGIC (JS getStatus ka PHP version) ----------
private function getStatusPHP($u, $start_date)
{
    $st = "P";

    if (!empty($u['data'])) {

        if ($u['absent'] === "1") $st = "A";
        if ($u['weekly_off'] === "1") $st = "W";
        if ($u['holiday'] === "1") $st = "H";
        if ($u['leave'] === "1") $st = "L";

        $hasOut = false;
        foreach ($u['data'] as $d) {
            if ($d['mode'] === "out") {
                $hasOut = true;
                break;
            }
        }

        if ($u['mispunch'] === "1" && !$hasOut) {
            if ($start_date !== date("Y-m-d")) {
                $st = "MS";
            }
        } elseif ($u['halfday'] === "1") {
            $st = "P/2";
        } elseif ($u['sl'] === "SL") {
            $st = "SL";
        }

    } else {
        $st = "A";
        if ($u['weekly_off'] === "1") $st = "W";
        if ($u['holiday'] === "1") $st = "H";
        if ($u['leave'] === "1") $st = "L";
    }

    return $st;
}

// ---------- TIME CHECK (JS hasTime ka PHP version) ----------
private function hasTimePHP($timeStr)
{
    if (empty($timeStr)) return false;

    if (preg_match('/(\d+):(\d+)/', $timeStr, $m)) {
        $minutes = ((int)$m[1] * 60) + (int)$m[2];
        return $minutes > 0;
    }

    return false;
}


	public function daily_report2()
		{
			// ✅ POST DATA
			$postdata   = $this->input->post();

			$start_date = $postdata['start_date'] ?? '';
			$depart     = $postdata['depart'] ?? '';
			$section    = $postdata['section'] ?? '';
			$shift      = $postdata['shift'] ?? '';
			$action     = $postdata['action'] ?? '';

			// ✅ Company ID
			if ($this->session->userdata('type') == 'P') {
				$companyId = $this->session->userdata('empCompany');
			} else {
				$companyId = $this->session->userdata('login_id');
			}

			// ✅ NODE API PAYLOAD
			$payload = json_encode([
				"companyId"  => 29643,
				"start_date" => $start_date,
				"department"=> $depart,
				"section"   => $section,
				"shift"     => $shift,
				"action"    => $action
			]);

			$ch = curl_init("http://31.97.230.189:3000/api/attendance/daily");

			curl_setopt_array($ch, [
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_POST           => true,
				CURLOPT_HTTPHEADER     => ["Content-Type: application/json"],
				CURLOPT_POSTFIELDS     => $payload,
				CURLOPT_TIMEOUT        => 60
			]);

			$response = curl_exec($ch);
			curl_close($ch);

			$api = json_decode($response, true);

			if (!$api || $api["success"] !== true) {
				show_error("Attendance service unavailable");
			}

			// ✅ RESPONSE DATA MAPPING
			$data = [];

			$data['start_date'] = $api['data']['start_date'] ?? $start_date;
			$data['load']       = $api['data']['load'] ?? 0;

			// Main report array
			$data['report']     = $api['data']['report'] ?? [];

			$action = $postdata['action'] ?? 'active';

			$filteredReport = [];

			foreach ($data['report'] as $u) {

				$status = $this->getStatusPHP($u, $start_date);

				switch ($action) {

					case 'active':
						$filteredReport[] = $u;
						break;

					case 'present':
						if (!empty($u['data']) && !in_array($status, ['A','W','H','L'])) {
							$filteredReport[] = $u;
						}
						break;

					case 'absent':
						if ($status === 'A') {
							$filteredReport[] = $u;
						}
						break;

					case 'mispunch':
						if ($u['mispunch'] === "1") {
							$filteredReport[] = $u;
						}
						break;

					case 'halfday':
						if ($u['halfday'] === "1") {
							$filteredReport[] = $u;
						}
						break;

					case 'late':
						if ($this->hasTimePHP($u['late_hrs'])) {
							$filteredReport[] = $u;
						}
						break;

					case 'early':
						if ($this->hasTimePHP($u['early_hrs'])) {
							$filteredReport[] = $u;
						}
						break;

					case 'shortLeave':
						if ($u['sl'] === "SL") {
							$filteredReport[] = $u;
						}
						break;

					case 'unverified':
						if ($u['unverified'] === "1") {
							$filteredReport[] = $u;
						}
						break;

					case 'fieldDuty':
						if ($u['fieldDuty'] === "1") {
							$filteredReport[] = $u;
						}
						break;

					case 'manual':
						if ($u['manual'] === "1") {
							$filteredReport[] = $u;
						}
						break;

					case 'gps':
						if ($u['gps'] === "1") {
							$filteredReport[] = $u;
						}
						break;
				}
			}

			// 🔥 IMPORTANT: report ko filtered data se replace karo
			$data['report'] = $filteredReport;


			// Summary mapping
			$summary = $api['data']['summary'] ?? [];

			$data['totalActive']      = $summary['totalActive'] ?? 0;
			$data['totalPresent']     = $summary['totalPresent'] ?? 0;
			$data['totalAbsent']      = $summary['totalAbsent'] ?? 0;
			$data['totalMispunch']    = $summary['totalMispunch'] ?? 0;
			$data['totalHalfDay']     = $summary['totalHalfDay'] ?? 0;
			$data['totalLate']        = $summary['totalLate'] ?? 0;
			$data['totalEarly']       = $summary['totalEarly'] ?? 0;
			$data['totalShortLeave']  = $summary['totalShortLeave'] ?? 0;
			$data['totalUnverified']  = $summary['totalUnverified'] ?? 0;
			$data['totalFieldDuty']   = $summary['totalFieldDuty'] ?? 0;
			$data['totalManual']      = $summary['totalManual'] ?? 0;
			$data['totalGps']         = $summary['totalGps'] ?? 0;

			// Filters back to view
			$data['depart']  = $depart;
			$data['section'] = $section;
			$data['shift']   = $shift;
			

			// Company Name (if needed)
			$data['cmp_name'] = $this->session->userdata('company_name') ?? '';

			// ✅ LOAD VIEW
			$this->load->view('attendance/dailyreport2.php', $data);
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
		
			echo '<pre>';
			print_r($data);
			echo '</pre>';
			die();
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




	public function leave(){
		if(!empty($this->session->userdata('id'))){

			$this->load->view('attendance/leave');
		}
		else{
			redirect('user-login');
		}
	}

	public function employee_report(){
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
				$users_data = $this->app->getCompanyUsers($loginId);
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

				if($this->session->userdata()['type']=='P'){
					$role=$this->web->getRollbyid($this->web->session->userdata('login_id'),$loginId);
					if($role[0]->type!=1){
						$departments = explode(",",$role[0]->department);
						$sections = explode(",",$role[0]->section);
						$team = explode(",",$role[0]->team);
						if(!empty($departments[0]) || !empty($sections[0]) || !empty($team[0])){
							foreach ($users_data as $key => $dataVal) {
							$uname = $this->web->getNameByUserId($dataVal->user_id);
							$roleDp = array_search($uname[0]->department,$departments);
							$roleSection = array_search($uname[0]->section,$sections);
							$roleTeam = array_search($dataVal->user_id,$team);
							 if(!is_bool($roleTeam) || !is_bool($roleSection) || !is_bool($roleDp)){
								
							}else{
								unset($users_data[$key]);
							}
							}
						}  
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
										$month_weekly_off = explode(",",$groups->month_weekly_off);
										$day_shift_start = explode(",",$groups->day_start_time);
										$day_shift_end = explode(",",$groups->day_end_time);
										$shift_start = $groups->shift_start;
										$shift_end = $groups->shift_end;
										$group_name = $groups->name;
										if($month_weekly_off!=0){
										 
										foreach($month_weekly_off as $key=>$off){
		                            	if($off==1){	
		                            	$N=date('N',$start_time);
		                              	$key2=$key-$N+1;
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

							$leaves = $this->app->getEmpLeaves($user->user_id);
									$leaves_array = array();
									$leave_days=0;
									if($leaves){
										foreach($leaves as $leave){
											$from_date_leave=date_create(date("Y-m-d",$leave->from_date));
											$to_date_leave=date_create(date("Y-m-d",$leave->to_date));
											$leave_diff=date_diff($from_date_leave,$to_date_leave);
										//	$leave_days = $leave_diff->format("%a");
										$half_day=$leave->half_day;
											$leave_days=$half_day;
											for($l=0;$l<$leave_days;$l++){
												$leave_start_date = strtotime(date("d-m-Y",$leave->from_date)." +".$l." days");
												$leaves_array[] = array(
													'date'=>date('d.m.Y',$leave_start_date),
												);
											}
										}
									}
									
					$onduty =$this->web->getUserOTbyID($user->user_id);
						$od_array = array();
			//	$od_days =0;
				if($onduty){
					
					foreach($onduty as $onduty){
				 
							  $from_date_od=date_create(date("Y-m-d",$onduty->date));
							  $to_date_od=date_create(date("Y-m-d",$onduty->end_date));
							  $od_diff=date_diff($from_date_od,$to_date_od);
							  $od_days = $od_diff->format("%a");
							  $od_days++;
							  for($c=0;$c<$od_days;$c++){
												$od_start_date = strtotime(date("d-m-Y",$onduty->date)." +".$c." days");
												$od_array[] = array(
													'date'=>date('d.m.Y',$od_start_date),
												);
											}
                      }
                 }
                 
                 	$wfh =$this->web->getUserbywfhbyID($user->user_id);
						$wfh_array = array();
			//	$od_days =0;
				if($wfh){
					
					foreach($wfh as $wfh){
				 
							  $from_date_wfh=date_create(date("Y-m-d",$wfh->date));
							  $to_date_wfh=date_create(date("Y-m-d",$wfh->end_date));
							  $wfh_diff=date_diff($from_date_wfh,$to_date_wfh);
							  $wfh_days = $wfh_diff->format("%a");
							  $wfh_days++;
							  for($c=0;$c<$wfh_days;$c++){
												$wfh_start_date = strtotime(date("d-m-Y",$wfh->date)." +".$c." days");
												$wfh_array[] = array(
													'date'=>date('d.m.Y',$wfh_start_date),
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
							$totalOD = 0;
							$totalwfh = 0;
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
								    
								    if(($user->doj =="" || $new_start_time >=$user->doj) && ($user->left_date=="" || $new_start_time < $user->left_date)){
												$news_start_time =$new_start_time;
											}
								   
									$user_at = array_filter($monthUserAt, function($val) use($new_start_time, $new_end_time){
										return ($val->io_time>=$new_start_time and $val->io_time<=$new_end_time);
									});

								if($month_weekly_off!=0){	$off = array_search(date('d.m.Y',$news_start_time),array_column($grp,'day_off'));
												}else{
					                       $off = array_search(date('N',$news_start_time),array_column($grp,'day_off'));}
										
					                        $holi = array_search(date('d.m.Y',$news_start_time),array_column($holiday_array,'date'));
					                        $lv = array_search(date('d.m.Y',$new_start_time),array_column($leaves_array,'date'));
					                         $ods = array_search(date('d.m.Y',$new_start_time),array_column($od_array,'date'));
				                               $wfhs = array_search(date('d.m.Y',$new_start_time),array_column($wfh_array,'date'));
									
									
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
									
									if(!is_bool($ods)){
												$totalOD++;
												$day_OD="1";
											}else{
												$day_OD="0";
											}
									if(!is_bool($wfhs)){
												$totalwfh++;
												$day_wfh="1";
											}else{
												$day_wfh="0";
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
											'onduty'=>$day_OD,
											'wfhduty'=>$day_wfh,
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
									'shift_start'=>$shift_start,
									'shift_end'=>$shift_end,
									'group_name'=>$group_name,
									'designation'=>$user->designation,
									'totalAbsent'=>$totalAbsent,
									'totalPresent'=>$totalPresent,
									'totalWeekOff'=>$totalWeekOff,
									'totalHoliday'=>$totalHoliday,
									'totalLeaves'=>$totalLeaves,
									'totalWorkingHrs'=>$totalWorkingHrs,
									'totalLate'=>$totalLate,
									'totalEarly'=>$totalEarly,
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
				//'departments'=>$departments,
				//'sections'=>$sections,
				// 'shifts'=>$shifts,
				//'depart'=>$depart,
				//'section'=>$section,
				//'status_check'=>$status_check,
				//'working_check'=>$working_check,
				//'totals_check'=>$totals_check,
				//'all_check'=>$all_check,
				//'two_check'=>$two_check,
				////'late_check'=>$late_check,
				//'early_check'=>$early_check,
				// 'shift'=>$shift,
				'cmp_name'=>$cmpName['name']
			);
			//print_r($new_array);
			$this->load->view('attendance/employee_report',$data);
		}else{
			redirect('user-login');
		}
	}

	public function manual_attendance(){
		if(!empty($this->session->userdata('id'))){
			$postdata=$this->input->post();
			$start_date = date("Y-m-d");
			$end_date = date("Y-m-d");
			$id=0;
			$true = 0;  
			if(isset($postdata['start_date']) && isset($postdata['end_date'])){
				$start_date = $postdata['start_date'];
				$end_date = $postdata['end_date'];
				$id = $postdata['emp'];
				$true= 1;
			}
			
			$data=array(
				'start_date'=>$start_date,
				'end_date'=>$end_date,
				'id'=>$id,
				'load'=>$true
			);
			$this->load->view('attendance/manual',$data);
		}else{
			redirect('user-login');
		}
	}

	public function addManualAttendance(){
		if(!empty($this->session->userdata('id'))){
			$buid = $this->input->post("buid");
			$uid = $this->input->post("uid");
			$startDate = $this->input->post("startDate");
			$endDate = $this->input->post("endDate");
			$addTime = $this->input->post("addTime");
			$addDate = $this->input->post("addDate");
			$mode = $this->input->post("mode");
			$addDate=date("Y-m-d",$addDate);
			if ($addTime!='' && ($mode=="in" || $mode=="out")){
				$data=array(
					'io_time'=>strtotime("$addTime $addDate"),
					'date'=>time(),
					'user_id'=>"$uid",
					'bussiness_id'=>"$buid",
					'mode'=>$mode,
					'manual'=>"1",
					'verified'=>"1",
					'status'=>"1"
				);
				$res=$this->db->insert('attendance',$data);
				if($res > 0){
					$this->session->set_flashdata('msg','Attendance added!');
					
					$uname = $this->web->getNameByUserId($uid);
				 if ($this->session->userdata()['type'] == 'P') {
          
          $loginId = $this->session->userdata('empCompany');
        
        } else {
          $loginId = $this->web->session->userdata('login_id');
        }
			$actdata=array(
			                            'bid'=>$loginId ,
				                        'uid'=>$this->web->session->userdata('login_id'),
				                        'activity'=>"Manual Att of employee ".$uname[0]->name. " attendance date".date("d-m-Y",strtotime($addDate)) . $addTime ." Added",
				                        'date_time'=>time()
				
			                             );
			                  $data=$this->db->insert('activity',$actdata);
					
				}
			}
			$data=array(
				'start_date'=>date("Y-m-d", $startDate),
				'end_date'=>date("Y-m-d", $endDate),
				'id'=>$uid,
				'load'=>1
			);
			$this->load->view('attendance/manual',$data);
		}else{
			redirect('user-login');
		}
	}

	public function removeManualAtt(){
		if (!empty($this->session->userdata('id'))) {
			$id = $this->input->post('id');
			$res= $this->web->removeManualAtt($id);
			if ($res) {
				return($id);
			}
		} else {
			redirect('user-login');
		}

	}



	public function addmanual2(){
		if(!empty($this->session->userdata('id'))){

			//$uid = $this->session->userdata('login_id');
			// $postdata=$this->input->post();

			$i = $this->input->post("i");
			$buid = $this->input->post("buid");
			$id = $this->input->post("id");
			$out_times = $this->input->post("out_time");
			$in_times = $this->input->post("in_time");
			$start_time = $this->input->post("start_time");
			$start_times=strtotime("$start_time");
			$end_time = $this->input->post("end_time");
			$end_times=strtotime("$end_time");

			$in=date("Y-m-d",$i);
			//$io_time=date('Y-m-d H-i-s',strtotime("$in $in_time"));
			if($in_times!=''){
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

			}
			if ($out_times!=''){
				$postdata2=array(
					//'bussiness_id'=>$postdata['name'],
					//'user_id'=>$postdata['mobile'],
					//'io_time'=>strtotime("$in_time"),

					'io_time'=>strtotime("$in $out_times"),
					'date'=>strtotime("$in $out_times"),
					'user_id'=>"$id",
					'bussiness_id'=>"$buid",
					'mode'=>"out",
					'manual'=>"1",
					'verified'=>"1",
					'status'=>"1"

				);
				$data=$this->db->insert('attendance',$postdata2);
			}

			// $data=$this->db->insert('user_request',$cmpInData);
			//if($data > 0){

			$this->session->set_flashdata('msg','Attendance changed!');
			redirect('manual_attendance?emp='.$id.'&start='.$start_time.'&end='.$end_time);
			//}


		}else{
			redirect('user-login');
		}
	}



















	public function changeLeaveFmDate(){
		if (!empty($this->session->userdata('id'))) {
			$id = $this->input->post("id");
			$from_date = $this->input->post("from_date");
			$info = $this->web->updateFromLDate($id,strtotime($from_date));
		}else{
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




	public function attendance_rule(){
		if(!empty($this->session->userdata('id'))){
		    if($this->session->userdata()['type']=='P'){
      // $busi=$this->web->getBusinessbyUser($this->web->session->userdata('login_id'));
      // $id=$busi[0]->business_id;
      $bid = $this->session->userdata('empCompany');
      $role=$this->web->getRollbyid($this->web->session->userdata('login_id'),$bid);
  
    } else {
      $bid=$this->web->session->userdata('login_id');
    }
		    
			$data=array(
				'rules'=>$this->web->getAttendanceRules($bid)
			);
			$this->load->view('attendance/attendance_rule',$data);
		}
		else{
			redirect('user-login');
		}
	}
	
	public function add_attendance_rule(){
		if(!empty($this->session->userdata('id'))){
			$mispunch = "0";
			$ca_wo_lofi = "1";
			$mark_ab_week = "0";
			$ov_shift = "0";
			$sl_late_time = 1800;
			$sl_early_time = 1800;
			$half_wo_time = 21600;
			$ab_wo_time = 10800;
			$ov_out_time = 0;
			$ov_wo_time = 28800;

			$rule_id = $this->web->getMaxRuleid($this->session->userdata('login_id'))['rule_id'];
			if($rule_id==''){
				$rule_id=1;
			}else{
				$rule_id++;
			}

			$rule = array(
				'bid'=>$this->session->userdata('login_id'),
				'rule_id'=>$rule_id,
				'name'=>"Default Rule",
				'mispunch'=>$mispunch,
				'sl_late'=>$sl_late_time,
				'sl_early'=>$sl_early_time,
				'halfday'=>$half_wo_time,
				'absent'=>$ab_wo_time,
				'overtime_shiftout'=>$ov_out_time,
				'overtime_wh'=>$ov_wo_time,
				'wh_cal'=>$ca_wo_lofi,
				'wo_absent'=>$mark_ab_week,
				'overtime_shift'=>$ov_shift,
				'date_time'=>time()
			);
			$res = $this->web->addAttendanceRule($rule);
			redirect('attendance_rule');
		}
		else{
			redirect('user-login');
		}
	}

	public function update_attendance_rule(){
		if(!empty($this->session->userdata('id'))){
			$postdata=$this->input->post();
			if(isset($postdata['rule_name']) && isset($postdata['rule_id'])){
				$res = $this->web->updateAttendanceRule($postdata['rule_id'],$postdata['rule_name']);
			}
			redirect('attendance_rule');
		}
		else{
			redirect('user-login');
		}
	}

public function update_attendance_rule_by_id(){
		if(!empty($this->session->userdata('id'))){
			$postdata=$this->input->post();

			if(isset($postdata['checked']) && isset($postdata['rule_id']) && isset($postdata['type'])){
				$update = false;
				$col = "m";
				$val = "1";
				if($postdata['checked']=="true"){
					$val = "1";
				}else{
					$val = "0";
				}
				if($postdata['type']=="mispunch"){
					$col = "mispunch";
					$update = true;
				}

				if($postdata['type']=="sl_late_on"){
					$col = "sl_late_on";
					$update = true;
				}
				if($postdata['type']=="sl_early_on"){
					$col = "sl_early_on";
					$update = true;
				}
				if($postdata['type']=="halfday_on"){
					$col = "halfday_on";
					$update = true;
				}
				if($postdata['type']=="absent_on"){
					$col = "absent_on";
					$update = true;
				}
				if($postdata['type']=="overtime_shift"){
					$col = "overtime_shift";
					$update = true;
				}
				if($postdata['type']=="overtime_wh_on"){
					$col = "overtime_wh_on";
					$update = true;
				}
				if($postdata['type']=="wh_cal"){
					$col = "wh_cal";
					$update = true;
				}
				if($postdata['type']=="wo_absent"){
					$col = "wo_absent";
					$update = true;
				}
				
				
				
				if($postdata['type']=="sl_late_time"){
					$col = "sl_late";
					$update = true;
					$hr = 0;
					$min = 0;
					if($postdata['checked']>0){
						$hr=$postdata['checked']*60*60;
					}
					if($postdata['checkedMin']>0){
						$min=$postdata['checkedMin']*60;
					}
					$val = $hr+$min;
				}
				if($postdata['type']=="sl_early_time"){
					$col = "sl_early";
					$update = true;
					$hr = 0;
					$min = 0;
					if($postdata['checked']>0){
						$hr=$postdata['checked']*60*60;
					}
					if($postdata['checkedMin']>0){
						$min=$postdata['checkedMin']*60;
					}
					$val = $hr+$min;
				}
				if($postdata['type']=="halfday_time"){
					$col = "halfday";
					$update = true;
					$hr = 0;
					$min = 0;
					if($postdata['checked']>0){
						$hr=$postdata['checked']*60*60;
					}
					if($postdata['checkedMin']>0){
						$min=$postdata['checkedMin']*60;
					}
					$val = $hr+$min;
				}
				if($postdata['type']=="absent_time"){
					$col = "absent";
					$update = true;
					$hr = 0;
					$min = 0;
					if($postdata['checked']>0){
						$hr=$postdata['checked']*60*60;
					}
					if($postdata['checkedMin']>0){
						$min=$postdata['checkedMin']*60;
					}
					$val = $hr+$min;
				}
				if($postdata['type']=="overtime_shiftout_time"){
					$col = "overtime_shiftout";
					$update = true;
					$hr = 0;
					$min = 0;
					if($postdata['checked']>0){
						$hr=$postdata['checked']*60*60;
					}
					if($postdata['checkedMin']>0){
						$min=$postdata['checkedMin']*60;
					}
					$val = $hr+$min;
				}
				if($postdata['type']=="overtime_wh_time"){
					$col = "overtime_wh";
					$update = true;
					$hr = 0;
					$min = 0;
					if($postdata['checked']>0){
						$hr=$postdata['checked']*60*60;
					}
					if($postdata['checkedMin']>0){
						$min=$postdata['checkedMin']*60;
					}
					$val = $hr+$min;
				}
				//////////new role/////
				
				
				if($postdata['type']=="auto_wo_on"){
					$col = "auto_wo_on";
					$update = true;
				}
				if($postdata['type']=="lt_punchin_on"){
					$col = "lt_punchin_on";
					$update = true;
				}
				
				if($postdata['type']=="el_punchout_on"){
					$col = "el_punchout_on";
					$update = true;
				}
				
				if($postdata['type']=="sl_on"){
					$col = "sl_on";
					$update = true;
				}
				if($postdata['type']=="hf_sl_on"){
					$col = "hf_sl_on";
					$update = true;
				}
				
				if($postdata['type']=="ex_absent_on"){
					$col = "ex_absent_on";
					$update = true;
				}
				
				if($postdata['type']=="ab_leave_fine_on"){
					$col = "ab_leave_fine_on";
					$update = true;
				}
				if($postdata['type']=="incentive_hl_on"){
					$col = "incentive_hl_on";
					$update = true;
				}
				if($postdata['type']=="ot_on"){
					$col = "ot_on";
					$update = true;
				}
				
				
				if($postdata['type']=="auto_wo"){
					$col = "auto_wo";
					$val=$postdata['checked'];
					$update = true;
					
				}
				
				if($postdata['type']=="sl_days"){
					$col = "sl_days";
					$val=$postdata['checked'];
					$update = true;
					
				}
				
				if($postdata['type']=="incentive_hl"){
					$col = "incentive_hl";
					$val=$postdata['checked'];
					$update = true;
					
				}
				
				if($postdata['type']=="ot_amount"){
					$col = "ot_amount";
					$val=$postdata['checked'];
					$update = true;
					
				}
				
				if($postdata['type']=="ex_absent_fine"){
					$col = "ex_absent_fine";
					$val=$postdata['checked'];
					$update = true;
					
				}
				
				if($postdata['type']=="ab_leave_fine"){
					$col = "ab_leave_fine";
					$val=$postdata['checked'];
					$update = true;
					
				}
				
				if($postdata['type']=="ex_absent_days"){
					$col = "ex_absent_days";
					$val=$postdata['checked'];
					$update = true;
					
				}
				
				if($postdata['type']=="sl_fine"){
					$col = "sl_fine";
					$val=$postdata['checked'];
					$update = true;
					
				}
				
				if($postdata['type']=="hf_sl_days"){
					$col = "hf_sl_days";
					$val=$postdata['checked'];
					$update = true;
					
				}
				
				
				if($postdata['type']=="lt_punchin"){
					$col = "lt_punchin";
					$val=$postdata['checked'];
					$update = true;
					
				}
				
				if($postdata['type']=="el_punchout"){
					$col = "el_punchout";
					$val=$postdata['checked'];
					$update = true;
					
				}
				
				
				if($postdata['type']=="lt_punchin_time"){
					$col = "lt_punchin_time";
					$update = true;
					$hr = 0;
					$min = 0;
					if($postdata['checked']>0){
						$hr=$postdata['checked']*60*60;
					}
					if($postdata['checkedMin']>0){
						$min=$postdata['checkedMin']*60;
					}
					$val = $hr+$min;
				}
				
				if($postdata['type']=="el_punchout_time"){
					$col = "el_punchout_time";
					$update = true;
					$hr = 0;
					$min = 0;
					if($postdata['checked']>0){
						$hr=$postdata['checked']*60*60;
					}
					if($postdata['checkedMin']>0){
						$min=$postdata['checkedMin']*60;
					}
					$val = $hr+$min;
				}
				
				
				
				
			

				if($update){
					$res = $this->web->updateAttendanceRulebyId($postdata['rule_id'],$col,$val);
				}
			}
		}
	}
	
	
public function open_leave(){
		if(!empty($this->session->userdata('id'))){
			$users_array=array();
			if ($this->session->userdata()['type'] == 'P') {
				$loginId = $this->session->userdata('empCompany');
				$role=$this->web->getRollbyid($this->web->session->userdata('login_id'),$loginId);
			} else {
				$loginId = $this->web->session->userdata('login_id');
			}					
			$users_data = $this->app->getCompanyUsers($loginId);
			if($this->session->userdata()['type']=='P'){
				if($role[0]->type!=1){
				  $departments = explode(",",$role[0]->department);
				  $sections = explode(",",$role[0]->section);
				  $team = explode(",",$role[0]->team);
				  if(!empty($departments[0]) || !empty($sections[0]) || !empty($team[0])){
					foreach ($users_data as $key => $dataVal) {
						$uname = $this->web->getNameByUserId($dataVal->user_id);
						$roleDp = array_search($uname[0]->department,$departments);
						$roleSection = array_search($uname[0]->section,$sections);
						$roleTeam = array_search($dataVal->user_id,$team);
						if(!is_bool($roleTeam) || !is_bool($roleSection) || !is_bool($roleDp)){
						
						}else{
						unset($users_data[$key]);
						}
					}
				  }
				}
			  }
			  //$date = $_GET['date'];
			  	$month = isset($_GET['getDate']) ? $_GET['getDate'] : date("Y-m");
			if(!empty($users_data)){
				foreach($users_data as $user){

					$open_date = "";
					$close_date = "";
					$cl = "0";
					$pl = "0";
					$el = "0";
					$sl = "0";
					$other = "0";
					$hl = 0;
				$rh = 0;
				$comp_off = 0;

					$leaves = $this->web->getactEmpLeaves($user->user_id);
					$id=$user->user_id;
					$bid=$loginId;

					$open_leaves = $this->web->getOpenLeave($loginId,$user->user_id);
					if($open_leaves){
						$open_date = $open_leaves['open_date'];
						$close_date = $open_leaves['close_date'];
						$cl = $open_leaves['cl'];
						$pl = $open_leaves['pl'];
						$el = $open_leaves['el'];
						$sl = $open_leaves['sl'];
						$other = $open_leaves['other'];
						$rh = $open_leaves['rh'];
						$hl = $open_leaves['hl'];
						$comp_off = $open_leaves['comp_off'];
						$limit_type = $open_leaves['limit_type'];
						$fixed_limit = $open_leaves['fixed_limit'];
						$carry = $open_leaves['carry'];
					}
					if($open_date!=""){
						$open_date = date('d-m-Y',$open_date);
					}
					if($close_date!=""){
						$close_date = date('d-m-Y',$close_date);
					}
						$yearName  = date('Y', strtotime($month));
		$monthName = date('m', strtotime($month));
		 $d = (cal_days_in_month(CAL_GREGORIAN,date('m',strtotime($month)),date('Y',strtotime($month))))-1;
		$start_time = strtotime(date("d-m-Y 00:00:00",strtotime($yearName."-".$monthName."-01")));
		$end_time = strtotime(date("d-m-Y 23:59:59",strtotime($yearName."-".$monthName."-01"))." +".$d." days"); 
			
		$data['leaven'] = $this->db->select_sum('leaves.half_day')
                                              ->from('leaves')
                                              ->where('leaves.status',1)
                                              ->where("uid",$id)
                                              ->where("bid",$bid)
                                              ->where('leaves.type!=',"unpaid")
                                              ->where('leaves.type!=',"comp_off")
                                             // ->where("DATE_FORMAT(from_date,%Y-%m)",$monthName)
                                              ->where("from_date >=",$start_time )
                                              ->where("from_date <=",$end_time)
                                             // ->where("2024",$yearName)
                                             // ->where("date('m', strtotime(from_date))",$monthName)
                                             //->where(YEAR(from_date)='$yearName' AND MONTH(from_date)='$monthName')
                                              ->get()
                                              ->row();
        $data['usedleavetotal'] = $this->db->select_sum('leaves.half_day')
                                              ->from('leaves')
                                              ->where('leaves.status',1)
                                              ->where("uid",$id)
                                              ->where("bid",$bid)
                                               ->where('leaves.type!=',"unpaid")
                                              ->where('leaves.type!=',"comp_off")
                                             // ->where("DATE_FORMAT(from_date,%Y-%m)",$monthName)
                                              //->where("from_date >=",$start_time )
                                              ->where("from_date <=",$end_time)
                                             // ->where("2024",$yearName)
                                             // ->where("date('m', strtotime(from_date))",$monthName)
                                             //->where(YEAR(from_date)='$yearName' AND MONTH(from_date)='$monthName')
                                              ->get()
                                              ->row();                                      
        $data['leaveold'] = $this->db->select_sum('leaves.half_day')
                                              ->from('leaves')
                                             // ->join('payroll_master','payroll_master.id=payroll_history.payroll_master_id')
                                             // ->where_in('payroll_master_id',[2])
                                              //->where("payroll_id",0)
                                              ->where('leaves.status',1)
                                              ->where('leaves.type!=',"other")
                                               ->where('leaves.type!=',"unpaid")
                                              ->where('leaves.type!=',"comp_off")
                                              ->where("uid",$id)
                                              ->where("bid",$bid)
                                             // ->where("DATE_FORMAT(from_date,%Y-%m)",$monthName)
                                              ->where("from_date <",$start_time )
                                             // ->where("from_date <",$end_time)
                                             // ->where("2024",$yearName)
                                             // ->where("date('m', strtotime(from_date))",$monthName)
                                             //->where(YEAR(from_date)='$yearName' AND MONTH(from_date)='$monthName')
                                              ->get()
                                              ->row();
          $data['leaveoldothern'] = $this->db->select_sum('leaves.half_day')
                                              ->from('leaves')
                                             ->where('leaves.type',"other")
                                              ->where('leaves.status',1)
                                              ->where("uid",$id)
                                              ->where("bid",$bid)
                                             
                                              ->where("from_date <",$start_time )
                                             
                                              ->get()
                                              ->row();                                    
                                                                                    
         
       // $data['usedleave']=		$end_time;
        
         $usedoldleave=$data['leaveold'] ? $data['leaveold']->half_day :0;
         $usedleavetotalY=$data['usedleavetotal'] ? $data['usedleavetotal']->half_day :0; 
        $leaveoldother=$data['leaveoldothern'] ? $data['leaveoldothern']->half_day :0; 
        
       // $data['total_leave'] =	$data['open_leave'] ? $data['open_leave']->cl+$data['open_leave']->el+$data['open_leave']->pl+$data['open_leave']->sl+$data['open_leave']->hl+$data['open_leave']->rh-$data['usedoldleave']:0;
       // $data['balanceleave']=$data['total_leave']- $data['usedleave'] ;
       
        
         $openleavedate=isset($data['open_leave']) ? $data['open_leave']->open_date:0;
       // $openleavemonth=isset($data['open_date']) ? date('m', $open_leaves['open_date']):0;
       //	$month = isset($_GET['getDate']) ? $_GET['getDate'] : date("Y-m");
      // $openleavemonth=isset($open_date) ? $open_date : date("Y-m");
      $openleavemonth = date('m', strtotime($open_date));
         $monthdiff=$monthName-$openleavemonth+1;
           $usedleavem=$data['leaven'] ? $data['leaven']->half_day :0;
           $entitleleave=isset($data['open_leave']) ? $data['open_leave']->fixed_limit :0;
        //$balleave=$data['entitleleave']?$data['entitleleave']:0;
        $fixedLimit = !empty($open_leaves['fixed_limit']) ? $open_leaves['fixed_limit']:0;

        $opening_leave= ($fixedLimit* $monthdiff)-$usedoldleave- $leaveoldother;
     //  $opening_leave= $openleavedate;
        // $opening_leave= $usedoldleave;
     // $carry_bal=$other-$data['leaveoldother']- $data['usedoldleave']+ $data['balanced_leave']  ;
         $carry_bal=$other-$leaveoldother;
					
				
					$new_array[] =array(
						'user_id'=>$user->user_id,
						'mid'=>$user->mid,
						'emp_code'=>$user->emp_code,
						'name'=>$user->name,
						'open_date'=>$open_date,
						'close_date'=>$close_date,
						'cl'=>$cl,
						'pl'=>$pl,
						'el'=>$el,
						'sl'=>$sl,
						'other'=>$other,
						'rh'=>$rh,
						'hl'=>$hl,
						'comp_off'=>$comp_off,
						'limit_type'=>$limit_type,
						'fixed_limit'=>$fixed_limit,
						'carry'=>$carry,
						'usedleavem'=>$usedleavem,
						'usedleavetotalY'=>$usedleavetotalY,
					    'opening_leave'=>$opening_leave,
					    'carry_bal'=>$carry_bal,
						'leaves'=>$leaves
					);
				}
			}

			$data = array('users'=>$new_array);
			$this->load->view('attendance/open_leave',$data);
		}
		else{
			redirect('user-login');
		}
	}

    public function update_open_leave(){
		if(!empty($this->session->userdata('id'))){
			$postdata=$this->input->post();
			if ($this->session->userdata()['type'] == 'P') {
				$bid = $this->session->userdata('empCompany');
			} else {
				$bid = $this->web->session->userdata('login_id');
			}
			if(isset($postdata['open_date']) && isset($postdata['user_id']) && isset($postdata['close_date'])){
				$open_date = strtotime(date("d-m-Y",strtotime($postdata['open_date'])));
				$close_date = strtotime(date("t-m-Y",strtotime($postdata['close_date'])));
				$cl = 0;
				$pl = 0;
				$sl = 0;
				$el = 0;
				$other = 0;
				$hl = 0;
				$rh = 0;
				$comp_off = 0;
				$limit_type=$postdata['limit_type'];
				$fixed_limit=$postdata['fixed_limit'];
				
              if(isset($postdata['carry'])){
				$carry = "1";
			}else{
				$carry="0";;
			}
			
			
			
				if(isset($postdata['cl'])){
					$cl = $postdata['cl'];
				}
				if(isset($postdata['pl'])){
					$pl = $postdata['pl'];
				}
				if(isset($postdata['el'])){
					$el = $postdata['el'];
				}
				if(isset($postdata['sl'])){
					$sl = $postdata['sl'];
				}
				if(isset($postdata['other'])){
					$other = $postdata['other'];
				}
				
				if(isset($postdata['hl'])){
					$hl = $postdata['hl'];
				}
				if(isset($postdata['rh'])){
					$rh = $postdata['rh'];
				}
				if(isset($postdata['comp_off'])){
					$comp_off = $postdata['comp_off'];
				}

				$date1=date_create(date("Y-m-d",$open_date));
				$date2=date_create(date("Y-m-d",$close_date));
				$diff=date_diff($date1,$date2);
				$num_month = $diff->format("%m");
				
					$open_leaves = $this->web->getOpenLeaveByDate($bid,$postdata['user_id']);
					if($open_leaves){
						$this->web->updateOpenLeave($bid,$postdata['user_id'],$open_date,$close_date,$cl,$pl,$el,$sl,$other,$hl,$rh,$comp_off,$limit_type,$fixed_limit,$carry,time());
						//echo "ot value";
					}else{
						//echo "ot value";
						$data =array(
							'bid'=>$bid,
							'uid'=>$postdata['user_id'],
							'open_date'=>$open_date,
							'close_date'=>$close_date,
							'cl'=>$cl,
							'pl'=>$pl,
							'el'=>$el,
							'sl'=>$sl,
							'hl'=>$hl,
								'rh'=>$rh,
						'comp_off'=>$comp_off,
								'other'=>$other,
								'limit_type'=>$limit_type,
								'fixed_limit'=>$fixed_limit,
								'carry'=>$carry,
								'date_time'=>time()
							);
						$this->web->addOpenLeave($data);
					}
				//}
				
				redirect('open_leave');
			}
		}else{
			redirect('user-login');
		}

	}









	/*   *********************************         */
	/*   *************** KRISHNA NAND 14-062022 FOR SALLERY MODULE  ****************** ****** */
	/*   *********************************         */


	//salaryEmployees
	public function salaryEmployees()
	{
		$data['page']  		= 'salary/emplist';
		$data['title'] 		= 'Manage - Salary';
		$data['lMenu']  	= 'Sallery';


		// $abc = $this->db->query("SELECT * FROM payroll_history WHERE MONTH(pay_date) = MONTH(CURRENT_DATE()) ")->result_array();
		// echo $this->web->session->userdata('login_id');
		// die();
		// $abc = $this->db->query("SELECT * FROM payroll_history WHERE YEAR(pay_date) = '2022' AND MONTH(pay_date) = '06' ")->result_array();
        $cmpName = $this->web->getBusinessById($this->web->session->userdata('login_id'));


		if($this->input->post()){

			$data['salEmpList'] 	= $this->web->getSallaryReport($this->input->post());
			$data['date_from'] = $this->input->post()['date_from'];
		}
		else
		{
			$data['salEmpList'] 	= $this->web->getSallaryReport();
			$data['date_from'] = date("Y-m");
		}

    $data['cmp_name']=$cmpName['name'];


		$data['payrollList'] 	= $this->web->getData('payroll_master', array('status' => 1), '', 'ASC');
		// echo '<pre>'; print_r(	$data['payrollList']); die();
		$this->load->view('salary/include/page',$data);
	}

	public function salaryReport()
	{
		$data['page']  		= 'salary/emplist';
		$data['title'] 		= 'Manage - Salary';
		$data['lMenu']  	= 'Sallery';

        $cmpName = $this->web->getBusinessById($this->web->session->userdata('login_id'));

		if($this->input->post()){

			$data['salEmpList'] 	= $this->web->getSalleryEmply($this->input->post());
			$data['salEmpList'] = $this->web->getSallaryReport($this->input->post());
			$data['date_from'] = $this->input->post()['date_from'];
		}
		else
		{
			$data['salEmpList'] 	= $this->web->getSalleryEmply();
			$data['salEmpList'] = $this->web->getSallaryReport();
			$data['date_from'] = date("Y-m");
		}
		$data['cmp_name']=$cmpName['name'];

		// redirect('salary-employees');
		$data['payrollList'] 	= $this->web->getData('payroll_master', array('status' => 1), '', 'ASC');
		$this->load->view('salary/include/page',$data);
	}


	public function getCurrentCtcDetails()
	{


		$selectedUserID = $this->input->post('userID');
		$business_id  = $this->web->session->userdata('login_id');
		$date = $this->input->post('date_from');

		$checkExist 	= $this->db->query("SELECT * FROM user_ctc WHERE business_id = '".$business_id."' AND  user_id = '".$selectedUserID."' AND  YEAR(date) = '".date('Y',strtotime($date))."' AND MONTH(date) = '".date('m',strtotime($date))."' ")->row_array();

		if(empty($checkExist))
		{
			$checkExist = $this->db->query("SELECT * FROM user_ctc WHERE business_id = '".$business_id."' AND  user_id = '".$selectedUserID."' ORDER BY date DESC ")->row_array();
		}


		if(!empty($checkExist))
		{
			// echo '<pre>'; print_r($checkExist[$FormData.'_type']); die();

			$allowance = '';
			$deduction = '';

			$allColumnArray = array('DA','HRA','MEAL', 'CONVEYANCE','MEDICAL','SPECIAL','TA', 'PF','ESI','Other');
			$deductionForm  = array('PF','ESI','Other');


			foreach ($allColumnArray as $key => $FormData) {
				$form_data  = strtolower($FormData);
				$html = '';
				$html .= '<div class="row">';
				$html .= '<div class="col-md-5">';
				$html .= '<div class="form-group">';
				$html .= '<div class="input-group">';
				$html .= '<input type="text" class="form-control inp_allowance" readonly="" value="'.$FormData.'" name="allowance[]">';
				$html .= '<div class="input-group-append">';
				$html .= '<select name="'.$form_data.'_type" class="bg-light" onchange="setBasicCTC();">';
				$html .= '<option value="Manual" '.(($checkExist[$form_data.'_type']=='Manual')?'selected': '').' >Manual</option>';
				$html .= '<option value="%" '.(($checkExist[$form_data.'_type']=='%')?'selected': '').' >%</option>';
				$html .= '</select>';
				$html .= '</div>';
				$html .= '</div>';
				$html .= '</div>';
				$html .= '</div>';

				$html .= '<div class="col-md-3">';
				$html .= '<div class="form-group">';
				$html .= '<div class="input-group">';
				$html .= '<div class="input-group-append  '.$form_data.'_manual '.(($checkExist[$form_data.'_type']=='Manual')?'': 'd-none').' ">';
				$html .= '<span class="input-group-text">'.INDIAN_SYMBOL.'</span>';
				$html .= '</div>';
				$html .= '<input type="number" name="'.$form_data.'_value" value="'.$checkExist[$form_data.'_value'].'" oninput="setBasicCTC();" min="0" step="0.01" class="form-control" id="'.$form_data.'_value" placeholder="0">';
				$html .= '<div class="input-group-append '.$form_data.'_percent  '.(($checkExist[$form_data.'_type']=='Manual')?'d-none': '').' ">';
				$html .= '<span class="input-group-text">%</span>';
				$html .= '</div>';
				$html .= '</div>';
				$html .= '</div>';
				$html .= '</div>';

				$html .= '<div class="col-md-4">';
				$html .= ' <div class="form-group">';
				$html .= ' <div class="input-group">';
				$html .= ' <div class="input-group-append">';
				$html .= ' <span class="input-group-text">'.INDIAN_SYMBOL.'</span>';
				$html .= ' </div>';
				$html .= ' <input type="number" name="'.$form_data.'_amount" value="'.$checkExist[$form_data.'_amount'].'" readonly="" min="0" class="form-control" id="allowance_value" placeholder="0">';
				$html .= ' </div>';
				$html .= ' </div>';
				$html .= ' </div>';
				$html .= ' </div>';


				if(in_array($FormData, $deductionForm))
				{
					$deduction .= $html;
				}
				else
				{
					$allowance .= $html;
				}

			}

			$response = array('status'    => 1,
			'details'   => $checkExist,
			'deduction' => $deduction,
			'allowance' => $allowance,
		);
	}
	else
	{
		$response = array('status' => 0,   );

	}

	echo json_encode($response);

}



public function saveCtc()
{
	$in_data = $this->input->post();
	$date = $this->input->post('date_from');
	$business_id  = $this->web->session->userdata('login_id');
	$saveCtcArray = array(
		'business_id' 	=> $business_id,
		'user_id' 		=> $in_data['select_user_id'],
		'basic' 			=> $in_data['basic'],
		'basic_value' 	=> $in_data['basic_value'],
		'total_ctc_amount' 	=> $in_data['input_total_ctc_amount'],
		'date'=>date("Y-m-d H:i:s",strtotime($date))
	);


	if(!empty($in_data['allowance']))
	{
		foreach ($in_data['allowance'] as $key => $allData) {

			$dataType = strtolower($allData);

			$saveCtcArray[$dataType] = $allData;
			$saveCtcArray[$dataType.'_type']   = $in_data[$dataType.'_type'];
			$saveCtcArray[$dataType.'_value']  = $in_data[$dataType.'_value'];
			$saveCtcArray[$dataType.'_amount'] = $in_data[$dataType.'_amount'];

		}
	}
	// $saveCtcArray['date']   = time();
	$saveCtcArray['status'] = 1;

	$checkExist = $this->db->query("SELECT id FROM user_ctc WHERE  business_id = '".$business_id."' AND  user_id = '".$in_data['select_user_id']."' AND  YEAR(date) = '".date('Y',strtotime($date))."' AND MONTH(date) = '".date('m',strtotime($date))."' ")->row_array();
	if(!empty($checkExist))
	{
		$save = $this->web->UpdateData('user_ctc' ,$saveCtcArray, array('id' => $checkExist['id']));
	}
	else
	{
		$save = $this->web->saveData('user_ctc' ,$saveCtcArray);
	}


	if($save > 0)
	{
		$response = array('message' 	=> 'CTC have successfully saved.',
		'status'  => '1'
	);
}
else
{
	$response = array('message' 	=> 'Sorry! somthings wents wrong.',
	'status'  => '0'
);
}

echo json_encode($response);

}

public function settleAmount()
{
	$in_data = $this->input->post();
	if($in_data){
		$saveData = array();
		$this->db->query("update payroll_history set status=2 where payroll_id=".$in_data['deduct_id']);
		for($i=1; $i<=$in_data['maxcount'];$i++){
			$saveData = array(
			'business_id' 		=> $this->web->session->userdata('login_id'),
			'payroll_master_id'	=> 2,
			'user_id	' 		=> $in_data['user_id'],
			'pay_date' 			=> $in_data['settleDate'.$i],
			'amount	' 			=> $in_data['settleAmount'.$i],
			'remarks' 			=> "Deduction",
			'status' 			=> 1,
			'payroll_id'		=>$in_data['deduct_id'],
			'date' 			=> $in_data['settleDate'.$i],
			);
			$save = $this->web->saveData('payroll_history' ,$saveData);
		}
		if($save > 0)
		{
			$payRow = $this->db->query("select * from payroll_history where id=".$in_data['deduct_id'])->row_array();
			$updateData = array('settled'=>1,'date'=>$payRow['date']);
			$this->db->where('id',$in_data['deduct_id']);
			$res = $this->db->update('payroll_history',$updateData);
			$response = array('message' 	=> 'Payrol have successfully saved',
			'status'  => '1'
		);
	}
	else
	{
		$response = array('message' 	=> 'Sorry! somthings wents wrong.',
		'status'  => '0'
	);
}
echo json_encode($response);
}
}


public function addDeductAmount()
{
	$in_data = $this->input->post();
	if($in_data){
		$settle = 1;
		$paid = 1;
		$payrollId = 0;
		$masterId = $in_data['payroll_master_id'];
		if($in_data['payroll_master_id']==0){
			$masterId = 2;
			$paid = 1;
			$settle = 1;
		}
		if($in_data['payroll_master_id']==2){
			$masterId = 2;
			$paid = 0;
			$settle = 0;
		}
		$saveArray = array(
			'business_id' 		=> $this->web->session->userdata('login_id'),
			'payroll_master_id'	=> $masterId,
			'user_id	' 		=> $in_data['add_deduct_user_id'],
			'pay_date' 			=> $in_data['date'],
			'amount	' 			=> $in_data['amount'],
			'remarks' 			=> $in_data['note'],
			'status' 			=> 1,
			'settled'			=>$settle,
			'paid'				=>$paid,
			'payroll_id'		=>$payrollId,
			'date' 			=> $in_data['date']
		);

		$save = $this->web->saveData('payroll_history' ,$saveArray);
		if($save > 0)
		{
			$response = array('message' 	=> 'Payrol have successfully saved',
			'status'  => '1'
		);
	}
	else
	{
		$response = array('message' 	=> 'Sorry! somthings wents wrong.',
		'status'  => '0'
	);
}
echo json_encode($response);
}
}


/*  GET PAYROLL HISTORY  */

public function payrolHidtory(){
	$in_data = $this->input->post();
	$response = array('list' 		=> '<tr><th colspan="4"><p class="text-center text-danger">Data notfound.</p></th></tr>',
	'status'  	=> '1',
	'totalAmount' => '0',
	);
	$business_id = $this->web->session->userdata('login_id');
	if($in_data['payrolID']){
		$payrolID 		= $in_data['payrolID'];
		$user_id 		= $in_data['user_id'];
		$paid = 1;
		if($payrolID==2){
			$paid = 0;
		}
		if($payrolID==10){
			$payrolID = 2;
		}
		$payrollHist 	= $this->web->getData('payroll_history', array('business_id' => $business_id, 'payroll_master_id' => $payrolID, 'user_id' => $user_id,'paid'=>$paid), '', 'DESC');

		$html = '';
		if(!empty($payrollHist)){
			$sr =1;
			foreach ($payrollHist as $key => $value) {
				$payRollAmount = $value['amount'];
				$payRollId = $value['id'];
				$html .='<tr>';
				$html .=' <td>'.$sr.'</td>';
				$html .=' <td>'.$value['amount'].'</td>';
				$html .=' <td>'.$value['pay_date'].'</td>';
				$html .=' <td><p> '.$value['remarks'].'</p></td>';
				if($value['settled']==0){
					$html .=' <td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#settleAmountModal" onclick="setSettleModalAmount('."$user_id".','."$payRollAmount".','."$payRollId".')">Deduct</button></td>';
				}else if($value['settled']==1 && $in_data['payrolID']==2){
					$html .=' <td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#settleAmountModal" onclick="editSettleModalAmount('."$user_id".','."$payRollAmount".','."$payRollId".')">Edit</button></td>';
				}else{
					$html .=' <td></td>';
				}
				$html .=' </tr>';
				$sr++;
			}
			$response = array('list' 		=> $html ,
			'status'  		=> '1' ,
			'totalAmount' 	=> array_sum(array_column($payrollHist,'amount') ) ,
			);
		}
	}
	echo json_encode($response);
}

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

public function GenPersonalLogin_new(){
		if (!empty($this->session->userdata('id'))) {
			$bid=$this->input->post("bid");
			$id = $this->input->post("id");
			$info = $this->web->getBusinessById($id);
			$uname = $info['mobile'];
			$pass = '123';

			$data = array(
				'login_id' => $id,
				'username' => $uname,
				'password' => md5($pass),
				'type' => 'P',
				'status' => '1',
				'date' => time()
			);
			$data2 = array(
				'uid' => $id,
				'bid' => $bid,
				'employee_list' => '0',
				'add_emp' => '0',
				'att_option' => '0',
				'manual_att' => '0',
				'pending_att' => '0',
				'daily_report' => '0',
				'other_report' => '0',
				'att_setting' => '0',
				'leave_manage' => '0',
				'salary' => '0',
				'assign' => '0',
				'type' => '0',
				
				'manager_role' => '0'
		
				
			);
			$check = $this->web->checkGeneratedLogin($id);
            if(empty($check)){
			$res = $this->db->insert("web_login", $data);
			
			
			$res2 = $this->db->insert("emp_role", $data2);
			} else{
				
				$res2 = $this->db->insert("emp_role", $data2);
				
				}
			
			if($res2){
				redirect('users');
			}
		}else{
			redirect('user-login');
		}
	}

	public function getRoll(){
		if(!empty($this->session->userdata('id'))){
			$this->load->view('attendance/roll');
		}
		else{
			redirect('user-login');
		}
	}

public function editroll(){
		if(!empty($this->session->userdata('id'))){
			$check=$_REQUEST;
			//$departmentarray=array();
			
			print_r($check);
			echo $employee_list = $_POST['employee_list'];
			echo $add_emp = $_POST['add_emp'];
			echo $att_option = $_POST['att_option'];
			echo $manual_att = $_POST['manual_att'];
			echo $pending_att = $_POST['pending_att'];
			echo $daily_report= $_POST['daily_report'];
			echo $other_report = $_POST['other_report'];
			echo $att_setting = $_POST['att_setting'];
			echo $leave_manage = $_POST['leave_manage'];
			echo $salary = $_POST['salary'];
			echo $assign = $_POST['assign'];
			echo $manager_role = $_POST['manager_role'];
			
			echo $employee_login = $_POST['employee_login'];
			echo $activity = $_POST['activity'];
			echo $add_leave = $_POST['add_leave'];
			echo $add_salary = $_POST['add_salary'];
			echo $gps_report = $_POST['gps_report'];
			echo $log_report = $_POST['log_report'];
			echo $earn = $_POST['earn'];
			echo $add_earn = $_POST['add_earn'];
			echo $bid = $_POST['bid'];
			echo $uid = $_POST['id'];
			$empType = $_POST['empType'];
			$department= $_POST['department'];
			$section = $_POST['section'];
			$emp = $_POST['emp'];
			//$d=count($department);
			$res = 0;
			//for($i=0;$i<$d;$i++){
			//$department2[]=$department[$i] ;
			//$departmentarray[]=array($department.",");
			//}
				$departmentarray= implode(",",$department);
				$sectionarray= implode(",",$section);
				$emparray= implode(",",$emp);
				
			$data = array(
					'employee_list' =>$employee_list,
					'add_emp' =>$add_emp,
					'att_option' =>$att_option,
					'manual_att' =>$manual_att,
					'pending_att' =>$pending_att,
					'daily_report' =>$daily_report,
					'other_report' =>$other_report,
					'att_setting' =>$att_setting,
					'leave_manage' =>$leave_manage,
					'salary' =>$salary,
					'assign' =>$assign,
					'manager_role' =>$manager_role,
						'employee_login' =>$employee_login,
							'activity' =>$activity,
					'add_leave' =>$add_leave,
					'add_salary' =>$add_salary,
					'earn' =>$earn,
					'add_earn' =>$add_earn,
					'gps_report' =>$gps_report,
					'log_report' =>$log_report,
					'type'=>$empType,
					'department'=>$departmentarray,
					'section'=>$sectionarray,
					'team'=>$emparray
				);
			//print_r($data);
			$this->db->where('uid',$uid);
			$this->db->where('bid',$bid);
			$res = $this->db->update('emp_role',$data);
			echo $res;
			if($data > 0){
					$this->session->set_flashdata('msg','Roll Updated');
						redirect('manager_roll');
			}
			//return($res);
		
		
		}
		else{
			redirect('user-login');
		}
	}


public function assign_emp(){
		if(!empty($this->session->userdata('id'))){
			echo $id=$_POST['id'];
	    	echo $section = $_POST['section'];
			echo $department = $_POST['department'];
			echo $group = $_POST['group'];
			$data=array(
						'section' => $section,
						'business_group' => $group,
						'department' => $department
				
					);
			//$data=$this->db->update('login',$postdata);
			$this->db->where('id',$id);
			$data= $this->db->update('login',$data);
			$this->db->where('user_id',$id);
			$data= $this->db->update('user_request',['rule_id'=>$_POST['rule']]);
			if($data>0){
			$uname = $this->web->getNameByUserId($id);
				 if ($this->session->userdata()['type'] == 'P') {
          
          $loginId = $this->session->userdata('empCompany');
         // $role=$this->web->getRollbyid($this->web->session->userdata('login_id'),$loginId);
        } else {
          $loginId = $this->web->session->userdata('login_id');
        }
			$actdata=array(
			                            'bid'=>$loginId ,
				                        'uid'=>$this->web->session->userdata('login_id'),
				                        'activity'=>"Assign data of employee ".$uname[0]->name. " updated",
				                        'date_time'=>time()
				
			                             );
			                  $data=$this->db->insert('activity',$actdata);
			}
			
			
			
			
		    $this->session->set_flashdata('msg','Assigned Successfully!');
				redirect('assign_shift');
			
		}
		else{
			redirect('user-login');
		}
	}

	public function add_leave(){
		if(!empty($this->session->userdata('id'))){
			$postdata=$this->input->post();
			
			$postdata = array(
            'bid'=>$postdata['bid'],
            'uid'=>$postdata['uid'],
            'from_date'=>strtotime(date("d-m-Y 00:00:00",strtotime($postdata['from_date']))),
            'to_date'=>strtotime(date("d-m-Y 00:00:00",strtotime($postdata['to_date']))),
            'reason'=>$postdata['reason'],
            'type'=>$postdata['type'],
			'half_day'=>$postdata['days'],
            'date_time'=>time(),
			'status'=>1
			
          );
			$data=$this->db->insert('leaves',$postdata);
			if($data > 0){
			//	$this->session->set_flashdata('msg','New Leave Added!');
				$uname = $this->web->getNameByUserId($uid);
				 if ($this->session->userdata()['type'] == 'P') {
          
          $loginId = $this->session->userdata('empCompany');
         // $role=$this->web->getRollbyid($this->web->session->userdata('login_id'),$loginId);
        } else {
          $loginId = $this->web->session->userdata('login_id');
        }
			$actdata=array(
			                            'bid'=>$loginId ,
				                        'uid'=>$this->web->session->userdata('login_id'),
				                        'activity'=>"New Leave of employee ".$uname[0]->name. " Added",
				                        'date_time'=>time()
				
			                             );
			                  $data=$this->db->insert('activity',$actdata);
				redirect('open_leave');
			}
		}
		else{
			redirect('user-login');
		}
	}


public function verifypending(){
		if (!empty($this->session->userdata('id'))) {
			$id = $this->input->post('id');
		     $uid = $this->input->post('uid');
			$fromdate = $this->input->post('fromdate');
			$res= $this->web->verifypending($id);
			if ($res) {
				
				// $pending=$this->web->getGpsByDate($bid);
			//	$uname = $this->web->getNameByUserId($uid);
				 if ($this->session->userdata()['type'] == 'P') {
          
          $loginId = $this->session->userdata('empCompany');
        
        } else {
          $loginId = $this->web->session->userdata('login_id');
        }
        $uname = $this->web->getNameByUserId($uid);
			$actdata=array(
			                            'bid'=>$loginId ,
				                        'uid'=>$this->web->session->userdata('login_id'),
				                        'activity'=>"Attendance Verified of Employee ".$uname[0]->name. " for date ".$fromdate."",
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
	public function cancelpending(){
		if (!empty($this->session->userdata('id'))) {
			$id = $this->input->post('id');
			$uid = $this->input->post('uid');
			$fromdate = $this->input->post('fromdate');
			$res= $this->web->cancelpending($id);
			if ($res) {
			    
				// $pending=$this->web->getGpsByDate($bid);
			//	$uname = $this->web->getNameByUserId($uid);
				 if ($this->session->userdata()['type'] == 'P') {
          
          $loginId = $this->session->userdata('empCompany');
        
        } else {
          $loginId = $this->web->session->userdata('login_id');
        }
        $uname = $this->web->getNameByUserId($uid);
			$actdata=array(
			                            'bid'=>$loginId ,
				                        'uid'=>$this->web->session->userdata('login_id'),
				                       'activity'=>"Attendance Canceled of Employee ".$uname[0]->name. " for date ".$fromdate."",
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
	
	public function delete_department(){
		if (!empty($this->session->userdata('id'))) {
			$id = $this->input->post('id');
			$res= $this->web->delete_department($id);
			if ($res) {
			   // $uname = $this->web->getNameByUserId($id);
				 if ($this->session->userdata()['type'] == 'P') {
          
          $loginId = $this->session->userdata('empCompany');
         // $role=$this->web->getRollbyid($this->web->session->userdata('login_id'),$loginId);
        } else {
          $loginId = $this->web->session->userdata('login_id');
        }
			$actdata=array(
			                            'bid'=>$loginId ,
				                        'uid'=>$this->web->session->userdata('login_id'),
				                        'activity'=>"Department deleted from department list",
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
	
	public function delete_holiday(){
		if (!empty($this->session->userdata('id'))) {
			$id = $this->input->post('id');
			$res= $this->web->delete_holiday($id);
			if ($res) {
			   // $uname = $this->web->getNameByUserId($id);
				 if ($this->session->userdata()['type'] == 'P') {
          
          $loginId = $this->session->userdata('empCompany');
         // $role=$this->web->getRollbyid($this->web->session->userdata('login_id'),$loginId);
        } else {
          $loginId = $this->web->session->userdata('login_id');
        }
			$actdata=array(
			                            'bid'=>$loginId ,
				                        'uid'=>$this->web->session->userdata('login_id'),
				                        'activity'=>"Holiday Deleted from Holiday List",
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
	
	
	public function department_list(){
		if(!empty($this->session->userdata('id'))){
			$this->load->view('attendance/department_list');
		}
		else{
			redirect('user-login');
		}
	}
	public function section_list(){
		if(!empty($this->session->userdata('id'))){
			$this->load->view('attendance/section_list');
		}
		else{
			redirect('user-login');
		}
	}
	public function shifts(){
		if(!empty($this->session->userdata('id'))){
			$this->load->view('attendance/shifts');
		}
		else{
			redirect('user-login');
		}
	}
	
	public function holidays_list(){
		if(!empty($this->session->userdata('id'))){
			$this->load->view('attendance/holidays_list');
		}
		else{
			redirect('user-login');
		}
	}
	
	
	public function add_bdepartment(){
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
			$actdata=array(
			                            'bid'=>$loginId ,
				                        'uid'=>$this->web->session->userdata('login_id'),
				                        'activity'=>"New Department Added",
				                        'date_time'=>time()
				
			                             );
			                  $data=$this->db->insert('activity',$actdata);
				$this->session->set_flashdata('msg','New Department Added!');
				redirect('department_list');
			}
		}
		else{
			redirect('user-login');
		}
	}
	
	public function editbdepartment(){
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
			$res = $this->db->update('department_section',$data);
			//$uname = $this->web->getNameByUserId($id);
				 if ($this->session->userdata()['type'] == 'P') {
          
          $loginId = $this->session->userdata('empCompany');
         // $role=$this->web->getRollbyid($this->web->session->userdata('login_id'),$loginId);
        } else {
          $loginId = $this->web->session->userdata('login_id');
        }
			$actdata=array(
			                            'bid'=>$loginId ,
				                        'uid'=>$this->web->session->userdata('login_id'),
				                        'activity'=>"Department name updated",
				                        'date_time'=>time()
				
			                             );
			                  $data=$this->db->insert('activity',$actdata);
			echo $res;
			return($res);
		}
		else{
			redirect('user-login');
		}
	}
	
	public function edit_bsection(){
		if(!empty($this->session->userdata('id'))){
			$check=$_REQUEST;
			print_r($check);
			echo $name = $_POST['name'];
			echo $strength = $_POST['strength'];
			echo $radius = $_POST['radius'];
			echo $id = $_POST['id'];
			$data = array(
					'name' => $name,
					'strength' => $strength,
					'radius' => $radius
					
					
				);
			print_r($data);
			$this->db->where('id',$id);
			$res = $this->db->update('sections',$data);
			echo $res;
			return($res);
		}
		else{
			redirect('user-login');
		}
	}
	
	public function add_holiday(){
		if(!empty($this->session->userdata('id'))){
			$postdata=$this->input->post();
			$postdata=array(
			             'business_id'=>$postdata['bid'],
						'name'=>$postdata['name'],
						 'date'=>strtotime($postdata['h_date']),
						 'status'=> 1
						// 'date'=>strtotime($holiday->date)
					);
			$data=$this->db->insert('holiday',$postdata);
			if($data > 0){
			    $uname = $this->web->getNameByUserId($id);
				 if ($this->session->userdata()['type'] == 'P') {
          
          $loginId = $this->session->userdata('empCompany');
         // $role=$this->web->getRollbyid($this->web->session->userdata('login_id'),$loginId);
        } else {
          $loginId = $this->web->session->userdata('login_id');
        }
			$actdata=array(
			                            'bid'=>$loginId ,
				                        'uid'=>$this->web->session->userdata('login_id'),
				                        'activity'=>"Holiday Added",
				                        'date_time'=>time()
				
			                             );
			                  $data=$this->db->insert('activity',$actdata);
				$this->session->set_flashdata('msg','New Holiday Added!');
				redirect('holidays_list');
			}
		}
		else{
			redirect('user-login');
		}
	}
public function field_duty(){
		if(!empty($this->session->userdata('id'))){
			$start_date = date("Y-m-d");
			$data=array(
				'start_date'=>$start_date);
			$this->load->view('attendance/field_duty');
		}
		else{
			redirect('user-login');
		}
	}
	
	public function pending_att(){
		if(!empty($this->session->userdata('id'))){
			
			$this->load->view('attendance/pending_att');
		}
		else{
			redirect('user-login');
		}
	}
	public function manager_roll(){
		if(!empty($this->session->userdata('id'))){
			$loginId = $this->session->userdata('login_id');
			if($this->session->userdata('type')=="P"){
				$userCmp = $this->app->getUserCompany($loginId);
				if(isset($userCmp) && ($userCmp['left_date']=="" || $userCmp['left_date']>time())){
					$loginId = $userCmp['business_id'];
				}
			}
			$data['userRolls'] = $this->web->getUserRolls($loginId);
			$data['loginId'] = $loginId;
			
			$this->load->view('attendance/manager_roll',$data);
		}
		else{
			redirect('user-login');
		}
	}
	
	
	public function assign_shift(){
		if(!empty($this->session->userdata('id'))){
			
			$this->load->view('attendance/assign');
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
	
	
	
	public function delete_att(){
		if (!empty($this->session->userdata('id'))) {
			$id = $this->input->post('id');
			$res= $this->web->delete_att($id);
			if ($res) {
			    //$uname = $this->web->getNameByUserId($uid);
				 if ($this->session->userdata()['type'] == 'P') {
          
          $loginId = $this->session->userdata('empCompany');
        
        } else {
          $loginId = $this->web->session->userdata('login_id');
        }
			$actdata=array(
			                            'bid'=>$loginId ,
				                        'uid'=>$this->web->session->userdata('login_id'),
				                        'activity'=>"Manual Attendance Deleted",
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
	
	
	
	public function delete_user(){
		if (!empty($this->session->userdata('id'))) {
			$id = $this->input->post('id');
			$res= $this->web->delete_user($id);
			if ($res) {
				echo $id;
				return($id);
			}
		} else {
			redirect('user-login');
		}
		
	}
	
	
	
public function gps_report(){
		if(!empty($this->session->userdata('id'))){
				$postdata=$this->input->post();
					$start_date = date("Y-m-d");
					$end_date = date("Y-m-d");
					$true = 0;
					$option= "all";
					$days_array = array();
					$new_array = array();
					$loginId = $this->session->userdata('login_id');
					if($this->session->userdata('type')=="P"){
						$userCmp = $this->app->getUserCompany($loginId);
						if(isset($userCmp) && ($userCmp['left_date']=="" || $userCmp['left_date']>time())){
							$loginId = $userCmp['business_id'];
						}
					}
										
					$cmpName = $this->web->getBusinessById($loginId);

					if(isset($postdata['start_date']) && isset($postdata['end_date'])){
						$start_date = $postdata['start_date'];
						$end_date = $postdata['end_date'];
						$empId = $postdata['emp'];
						$option = $postdata['option'];
						$true= 1;
						$users_data = $this->app->getCompanyUsers($loginId);
						$start_time = strtotime(date("d-m-Y 00:00:00",strtotime($start_date)));
						$end_time = strtotime(date("d-m-Y 23:59:59",strtotime($end_date)));
	
					}
					
					if(!empty($users_data)){
							foreach($users_data as $user){
						
						
						if(($user->doj!="" || $start_time>=$user->doj) && ($user->left_date=="" || $start_time<$user->left_date)){
											
											
						}
								
								
								
							}}
					
			     $data=array(
						'start_date'=>$start_date,
						'end_date'=>$end_date,
						'empId'=>$empId,
						
						'load'=>$true,
						'option'=>$option,
						'cmp_name'=>$cmpName['name']
					);
					//print_r($new_array);
					$this->load->view('attendance/gps_report',$data);
		
		}
		else{
			redirect('user-login');
		}
	}
	
	public function update_working_days(){
		if(!empty($this->session->userdata('id'))){
			$postdata=$this->input->post();
			if(isset($postdata['user_id'])){
				$present = 0;
				$halfDay = 0;
				$weekOff = 0;
				$holiday = 0;
				$leaves = 0;
				$shortLeave = 0;
				$ed = 0;


				if(isset($postdata['wdPresent'])){
					$present = $postdata['wdPresent'];
				}
				if(isset($postdata['wdHalfDay'])){
					$halfDay = $postdata['wdHalfDay'];
				}
				if(isset($postdata['wdWeekOff'])){
					$weekOff = $postdata['wdWeekOff'];
				}
				if(isset($postdata['wdHoliday'])){
					$holiday = $postdata['wdHoliday'];
				}
				if(isset($postdata['wdLeaves'])){
					$leaves = $postdata['wdLeaves'];
				}
				if(isset($postdata['wdShortLeave'])){
					$shortLeave = $postdata['wdShortLeave'];
				}
				if(isset($postdata['wdED'])){
					$ed = $postdata['wdED'];
				}

				if(isset($postdata['date_from']) && !empty($postdata['date_from'])){
					 $year = date('Y', strtotime($postdata['date_from']));
					$month = date('m', strtotime($postdata['date_from']));
					$this->web->updateWorkingDays($this->session->userdata('login_id'),$postdata['user_id'],$year,$month,$present,$halfDay,$weekOff,$holiday,$leaves,$shortLeave,$ed);
					
					$data['page']  		= 'salary/emplist';
            		$data['title'] 		= 'Manage - Salary';
            		$data['lMenu']  	= 'Sallery';

            		$data['salEmpList'] = $this->web->getSallaryReport($this->input->post());
            		$data['date_from'] = $this->input->post()['date_from'];
            		$data['payrollList'] 	= $this->web->getData('payroll_master', array('status' => 1), '', 'ASC');
            		$uname = $this->web->getNameByUserId($postdata['user_id']);
				 if ($this->session->userdata()['type'] == 'P') {
          
          $loginId = $this->session->userdata('empCompany');
         // $role=$this->web->getRollbyid($this->web->session->userdata('login_id'),$loginId);
        } else {
          $loginId = $this->web->session->userdata('login_id');
        }
			$actdata=array(
			                            'bid'=>$loginId ,
				                        'uid'=>$this->web->session->userdata('login_id'),
				                        'activity'=>"Working of employee ".$uname[0]->name. " changed",
				                        'date_time'=>time()
				
			                             );
			                  $data=$this->db->insert('activity',$actdata);
            		$this->load->view('salary/include/page',$data);
				}else{
				redirect('salary-employees');    
				}
			}
		}else{
			redirect('user-login');
		}

	}
	
		public function addShift(){
		if(!empty($this->session->userdata('id'))){
			$postdata=$this->input->post();
			$shiftName = $postdata['shift_name'];
			$shiftStartTime = date("h:i A",strtotime($postdata['shift_start']));
			$shiftEndTime = date("h:i A",strtotime($postdata['shift_end']));
			$weeklyOff = 0;

			$dayStartTime = date("h:i A",strtotime($postdata['monday_start'])).",".date("h:i A",strtotime($postdata['tuesday_start'])).",".date("h:i A",strtotime($postdata['wednesday_start'])).",".date("h:i A",strtotime($postdata['thursday_start'])).",".date("h:i A",strtotime($postdata['friday_start'])).",".date("h:i A",strtotime($postdata['saturday_start'])).",".date("h:i A",strtotime($postdata['sunday_start']));

			$dayEndTime = date("h:i A",strtotime($postdata['monday_end'])).",".date("h:i A",strtotime($postdata['tuesday_end'])).",".date("h:i A",strtotime($postdata['wednesday_end'])).",".date("h:i A",strtotime($postdata['thursday_end'])).",".date("h:i A",strtotime($postdata['friday_end'])).",".date("h:i A",strtotime($postdata['saturday_end'])).",".date("h:i A",strtotime($postdata['sunday_end']));

			if(isset($postdata['monday_checkbox'])){
				$weeklyOff = "1";
			}else{
				$weeklyOff = "0";
			}
			if(isset($postdata['tuesday_checkbox'])){
				$weeklyOff = $weeklyOff.",1";
			}else{
				$weeklyOff = $weeklyOff.",0";
			}
			if(isset($postdata['wednesday_checkbox'])){
				$weeklyOff = $weeklyOff.",1";
			}else{
				$weeklyOff = $weeklyOff.",0";
			}
			if(isset($postdata['thursday_checkbox'])){
				$weeklyOff = $weeklyOff.",1";
			}else{
				$weeklyOff = $weeklyOff.",0";
			}
			if(isset($postdata['friday_checkbox'])){
				$weeklyOff = $weeklyOff.",1";
			}else{
				$weeklyOff = $weeklyOff.",0";
			}
			if(isset($postdata['saturday_checkbox'])){
				$weeklyOff = $weeklyOff.",1";
			}else{
				$weeklyOff = $weeklyOff.",0";
			}
			if(isset($postdata['sunday_checkbox'])){
				$weeklyOff = $weeklyOff.",1";
			}else{
				$weeklyOff = $weeklyOff.",0";
			}
		$montweeklyOff=$weeklyOff.",".$weeklyOff.",".$weeklyOff.",".$weeklyOff.",".$weeklyOff.",".$weeklyOff;
			$shiftData = array(
				"business_id"=>$this->session->userdata('login_id'),
				"name"=>$shiftName,
				"shift_start"=>$shiftStartTime,
				"shift_end"=>$shiftEndTime,
				"weekly_off"=>$weeklyOff,
				"month_weekly_off"=>$montweeklyOff,
				"day_start_time"=>$dayStartTime,
				"day_end_time"=>$dayEndTime
			);

			$this->web->addShift($shiftData);
			
		//	$uname = $this->web->getNameByUserId($postdata['user_id']);
				 if ($this->session->userdata()['type'] == 'P') {
          
          $loginId = $this->session->userdata('empCompany');
         // $role=$this->web->getRollbyid($this->web->session->userdata('login_id'),$loginId);
        } else {
          $loginId = $this->web->session->userdata('login_id');
        }
			$actdata=array(
			                            'bid'=>$loginId ,
				                        'uid'=>$this->web->session->userdata('login_id'),
				                        'activity'=>"New Shift Added",
				                        'date_time'=>time()
				
			                             );
			                  $data=$this->db->insert('activity',$actdata);
			
			
			
			redirect('shifts');
		}else{
			redirect('user-login');
		}
	}


public function editShift(){
		if(!empty($this->session->userdata('id'))){
			$postdata=$this->input->post();
			 if ($this->session->userdata()['type'] == 'P') {
          
          $loginId = $this->session->userdata('empCompany');
         // $role=$this->web->getRollbyid($this->web->session->userdata('login_id'),$loginId);
        }else {
          $loginId = $this->web->session->userdata('login_id');
        }
			$shiftId = $postdata['shift_id'];
			$shiftName = $postdata['shift_name'];
			$shiftStartTime = date("h:i A",strtotime($postdata['shift_start']));
			$shiftEndTime = date("h:i A",strtotime($postdata['shift_end']));
			$weeklyOff = 0;
			$WeekOff = 0;

			$dayStartTime = date("h:i A",strtotime($postdata['monday_start'])).",".date("h:i A",strtotime($postdata['tuesday_start'])).",".date("h:i A",strtotime($postdata['wednesday_start'])).",".date("h:i A",strtotime($postdata['thursday_start'])).",".date("h:i A",strtotime($postdata['friday_start'])).",".date("h:i A",strtotime($postdata['saturday_start'])).",".date("h:i A",strtotime($postdata['sunday_start']));

			$dayEndTime = date("h:i A",strtotime($postdata['monday_end'])).",".date("h:i A",strtotime($postdata['tuesday_end'])).",".date("h:i A",strtotime($postdata['wednesday_end'])).",".date("h:i A",strtotime($postdata['thursday_end'])).",".date("h:i A",strtotime($postdata['friday_end'])).",".date("h:i A",strtotime($postdata['saturday_end'])).",".date("h:i A",strtotime($postdata['sunday_end']));

			if(isset($postdata['monday_checkbox1'])){
				$mon1 = "1";
			}else{
				$mon1 = "0";
			}
			if(isset($postdata['monday_checkbox2'])){
				$mon2 = "1";
			}else{
				$mon2 = "0";
			}
			if(isset($postdata['monday_checkbox3'])){
				$mon3 = "1";
			}else{
				$mon3 = "0";
			}
			if(isset($postdata['monday_checkbox4'])){
				$mon4 = "1";
			}else{
				$mon4 = "0";
			}
			if(isset($postdata['monday_checkbox5'])){
				$mon5 = "1";
			}else{
				$mon5 = "0";
			}
			
			
		
			
			
			if(isset($postdata['tuesday_checkbox1'])){
				$tue1 = "1";
			}else{
				$tue1 = "0";
			}
			if(isset($postdata['tuesday_checkbox2'])){
				$tue2 = "1";
			}else{
				$tue2 = "0";
			}
			
			
			if(isset($postdata['tuesday_checkbox3'])){
				$tue3 = "1";
			}else{
				$tue3 = "0";
			}
			
			
			if(isset($postdata['tuesday_checkbox4'])){
				$tue4 = "1";
			}else{
				$tue4 = "0";
			}
			if(isset($postdata['tuesday_checkbox5'])){
				$tue5 = "1";
			}else{
				$tue5 = "0";
			}
			
		
			
			if(isset($postdata['wed_checkbox1'])){
				$wed1 = "1";
			}else{
				$wed1 =  "0";
			}
			if(isset($postdata['wed_checkbox2'])){
				$wed2 = "1";
			}else{
				$wed2 =  "0";
			}
			if(isset($postdata['wed_checkbox3'])){
				$wed3 = "1";
			}else{
				$wed3 =  "0";
			}
			if(isset($postdata['wed_checkbox4'])){
				$wed4 ="1";
			}else{
				$wed4 =  "0";
			}if(isset($postdata['wed_checkbox5'])){
				$wed5 = "1";
			}else{
				$wed5 =  "0";
				
			}
			
			
			
			
			if(isset($postdata['tur_checkbox1'])){
				$tur1 = "1";
			}else{
				$tur1 =  "0";
			}
			if(isset($postdata['tur_checkbox2'])){
				$tur2 = "1";
			}else{
				$tur2 =  "0";
			}
			if(isset($postdata['tur_checkbox3'])){
				$tur3 = "1";
			}else{
				$tur3 =  "0";
			}
			if(isset($postdata['tur_checkbox4'])){
				$tur4 ="1";
			}else{
				$tur4 =  "0";
			}if(isset($postdata['tur_checkbox5'])){
				$tur5 = "1";
			}else{
				$tur5 =  "0";
			}
			
			
			
			if(isset($postdata['fri_checkbox1'])){
				$fri1 = "1";
			}else{
				$fri1 =  "0";
			}
			if(isset($postdata['fri_checkbox2'])){
				$fri2 = "1";
			}else{
				$fri2 =  "0";
			}
			if(isset($postdata['fri_checkbox3'])){
				$fri3 = "1";
			}else{
				$fri3 =  "0";
			}
			if(isset($postdata['fri_checkbox4'])){
				$fri4 ="1";
			}else{
				$fri4 =  "0";
			}if(isset($postdata['fri_checkbox5'])){
				$fri5 = "1";
			}else{
				$fri5 =  "0";
			}
			
			if(isset($postdata['sat_checkbox1'])){
				$sat1 = "1";
			}else{
				$sat1 =  "0";
			}
			if(isset($postdata['sat_checkbox2'])){
				$sat2 = "1";
			}else{
				$sat2 =  "0";
			}
			if(isset($postdata['sat_checkbox3'])){
				$sat3 = "1";
			}else{
				$sat3 =  "0";
			}
			if(isset($postdata['sat_checkbox4'])){
				$sat4 ="1";
			}else{
				$sat4 =  "0";
			}if(isset($postdata['sat_checkbox5'])){
				$sat5 = "1";
			}else{
				$sat5 =  "0";
			}
			
			if(isset($postdata['sun_checkbox1'])){
				$sun1 = "1";
			}else{
				$sun1 =  "0";
			}
			if(isset($postdata['sun_checkbox2'])){
				$sun2 = "1";
			}else{
				$sun2 =  "0";
			}
			if(isset($postdata['sun_checkbox3'])){
				$sun3 = "1";
			}else{
				$sun3 =  "0";
			}
			if(isset($postdata['sun_checkbox4'])){
				$sun4 ="1";
			}else{
				$sun4 =  "0";
			}if(isset($postdata['sun_checkbox5'])){
				$sun5 = "1";
			}else{
				$sun5 =  "0";
			}
			


$WeekOff=$mon1.",".$tue1.",".$wed1.",".$tur1.",".$fri1.",".$sat1.",".$sun1.",".$mon2.",".$tue2.",".$wed2.",".$tur2.",".$fri2.",".$sat2.",".$sun2.",".$mon3.",".$tue3.",".$wed3.",".$tur3.",".$fri3.",".$sat3.",".$sun3.",".$mon4.",".$tue4.",".$wed4.",".$tur4.",".$fri4.",".$sat4.",".$sun4.",".$mon5.",".$tue5.",".$wed5.",".$tur5.",".$fri5.",".$sat5.",".$sun5.",".$mon1.",".$tue1.",".$wed1.",".$tur1.",".$fri1.",".$sat1.",".$sun1;

 $weeklyOff=$mon1.",".$tue1.",".$wed1.",".$tur1.",".$fri1.",".$sat1.",".$sun1 ;
 
			$this->web->updateShift($shiftId,$loginId,$shiftName,$shiftStartTime,$shiftEndTime,$weeklyOff,$WeekOff,$dayStartTime,$dayEndTime);
			
				
			$actdata=array(
			                            'bid'=>$loginId ,
				                        'uid'=>$this->web->session->userdata('login_id'),
				                        'activity'=>"Shift timing changed",
				                        'date_time'=>time()
				
			                             );
			                  $data=$this->db->insert('activity',$actdata);
			redirect('shifts');
		}else{
			redirect('user-login');
		}
	}















	public function deleteShift(){
		if(!empty($this->session->userdata('id'))){
			$postdata=$this->input->post();
			$shiftId = $postdata['shift_id'];
			$this->web->deleteShift($shiftId,$this->session->userdata('login_id'));
			redirect('shifts');
		}else{
			redirect('user-login');
		}
	}
	
		public function attendanceOptions(){
		if(!empty($this->session->userdata('id'))){
			if($this->session->userdata()['type']=='P'){
      
      $bid = $this->session->userdata('empCompany');
      $role=$this->web->getRollbyid($this->web->session->userdata('login_id'),$bid);
  
    } else {
      $bid=$this->web->session->userdata('login_id');
    }
    
			$data = array(
				"options"=>$this->web->getCmpOptions($bid),
			);
			$this->load->view('attendance/attendance_options',$data);
		}
		else{
			redirect('user-login');
		}
	}

	public function update_attendance_option(){
		if(!empty($this->session->userdata('id'))){
			$postdata=$this->input->post();

			if(isset($postdata['checked']) && isset($postdata['type'])){
				$update = false;
				$col = "m";
				$val = "1";
				if($postdata['checked']=="true"){
					$val = "1";
				}else{
					$val = "0";
				}
				if($postdata['type']=="qrcheck"){
					$col = "qr";
					$update = true;
				}

				if($postdata['type']=="gpscheck"){
					$col = "gps";
					$update = true;
				}
				if($postdata['type']=="facecheck"){
					$col = "face";
					$update = true;
				}
				if($postdata['type']=="teamcheck"){
					$col = "colleague";
					$update = true;
				}
				if($postdata['type']=="autogpscheck"){
					$col = "auto_gps";
					$update = true;
				}
				if($postdata['type']=="gpstrackingcheck"){
					$col = "gps_tracking";
					$update = true;
				}
				if($postdata['type']=="fieldcheck"){
					$col = "field_duty";
					$update = true;
				}
				if($postdata['type']=="fourlayercheck"){
					$col = "four_layer_security";
					$update = true;
				}
				if($postdata['type']=="gpsselfiecheck"){
					$col = "selfie_with_gps";
					$update = true;
				}
				if($postdata['type']=="fieldselfiecheck"){
					$col = "selfie_with_field_duty";
					$update = true;
				}

				if($update){
					$options = $this->web->getCmpOptions($this->session->userdata('login_id'));
					if(empty($options)){
						$data = array(
							'bid'=>$this->session->userdata('login_id'),
							'$col'=>$val,
							'date_time'=>time()
						);
						$res = $this->web->addAttendanceOption($data);
					}else{
						$res = $this->web->updateAttendanceOption($this->session->userdata('login_id'),$col,$val);
					}

					$options = $this->web->getCmpOptions($this->session->userdata('login_id'));
					$res = $this->web->updateEmpOptions($this->session->userdata('login_id'),$options['auto_gps'],$options['qr'],$options['gps'],$options['field_duty'],$options['four_layer_security'],$options['face'],$options['selfie_with_gps'],$options['selfie_with_field_duty']);
				}
			}
		}
	}
	
	public function manualReport(){
		if(!empty($this->session->userdata('id'))){
			$postdata=$this->input->post();
			$start_date = date("Y-m-d");
			$end_date = date("Y-m-d");
			$true = 0;  
			$new_array = array();
			if(isset($postdata['start_date']) && isset($postdata['end_date'])){
				$start_date = $postdata['start_date'];
				$end_date = $postdata['end_date'];
				$id = $postdata['emp'];
				$true= 1;
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
				$users_data = $this->app->getCompanyUsers($loginId);
				$start_time = strtotime(date("d-m-Y 00:00:00",strtotime($start_date)));
				$end_time = strtotime(date("d-m-Y 23:59:59",strtotime($end_date)));
		
				if(!empty($users_data)){
					
					foreach($users_data as $user){
						if($id==$user->user_id || $id==0){
							if(($user->doj!="" || $start_time>=$user->doj) && ($user->left_date=="" || $start_time<$user->left_date)){
								$user_at = $this->web->manualAttendance($loginId,$user->user_id,$start_time,$end_time);
								$data = array();
	
								if(!empty($user_at)){
									foreach($user_at as $at){
										$comment = $at->comment;
										if($at->comment=="null"){
											$comment = $at->emp_comment;
										}
										$data[] = array(
										'id'=>$at->id,
										'date'=>date("d M Y",$at->io_time),
										'time'=>date('h:i A', $at->io_time),
										'mode'=>$at->mode,
										'latitude'=>$at->latitude,
										'longitude'=>$at->longitude,
										'location'=>$at->location,
										'comment'=>$at->comment
										);
									}
									$new_array[] =array(
										'user_id'=>$user->user_id,
										'name'=>$user->name,
										'image'=>$user->image,
										'date'=>date("d M Y",$start_time),
										'user_status'=>$user->user_status,
										'data'=> $data
										);
								}
							}
						}
					}

					if($this->session->userdata()['type']=='P'){
						$role=$this->web->getRollbyid($this->web->session->userdata('login_id'),$loginId);
						if($role[0]->type!=1){
							$departments = explode(",",$role[0]->department);
							$sections = explode(",",$role[0]->section);
							if(!empty($departments[0]) || !empty($sections[0])){
								foreach ($new_array as $key => $dataVal) {
								$uname = $this->web->getNameByUserId($dataVal->user_id);
								$roleDp = array_search($uname[0]->department,$departments);
								$roleSection = array_search($uname[0]->section,$sections);
								if(!is_bool($roleDp) || !is_bool($roleSection)){
									
								}else{
									unset($new_array[$key]);
								}
								}
							} 
						}
					}
				}
			}
			
			$data=array(
				'start_date'=>$start_date,
				'end_date'=>$end_date,
				'id'=>$id,
				'usersData'=>$new_array,
				'load'=>$true
			);
			$this->load->view('attendance/manual_report',$data);
		}else{
			redirect('user-login');
		}
	}

    public function update_open_leave_all(){
		if(!empty($this->session->userdata('id'))){
			$postdata=$this->input->post();
			if ($this->session->userdata()['type'] == 'P') {
				$bid = $this->session->userdata('empCompany');
				$role=$this->web->getRollbyid($this->web->session->userdata('login_id'),$bid);
			} else {
				$bid = $this->web->session->userdata('login_id');
			}
			if(isset($postdata['open_date']) && isset($postdata['close_date'])){
				$open_date = strtotime($postdata['open_date']);
				$close_date = strtotime($postdata['close_date']);
				$cl = 0;
				$pl = 0;
				$sl = 0;
				$el = 0;
				$other = 0;
				$hl = 0;
				$rh = 0;
				$comp_off = 0;
				$limit_type=$postdata['limit_type'];
				$fixed_limit=$postdata['fixed_limit'];
				
              if(isset($postdata['carry'])){
				$carry = "1";
			}else{
				$carry="0";;
			}
			
			
			
				if(isset($postdata['cl'])){
					$cl = $postdata['cl'];
				}
				if(isset($postdata['pl'])){
					$pl = $postdata['pl'];
				}
				if(isset($postdata['el'])){
					$el = $postdata['el'];
				}
				if(isset($postdata['sl'])){
					$sl = $postdata['sl'];
				}
				if(isset($postdata['other'])){
					$other = $postdata['other'];
				}
				
				if(isset($postdata['hl'])){
					$hl = $postdata['hl'];
				}
				if(isset($postdata['rh'])){
					$rh = $postdata['rh'];
				}
				if(isset($postdata['comp_off'])){
					$comp_off = $postdata['comp_off'];
				}
				$users_data = $this->app->getCompanyUsers($bid);
				if($this->session->userdata()['type']=='P'){
					if($role[0]->type!=1){
					  $departments = explode(",",$role[0]->department);
					  $sections = explode(",",$role[0]->section);
					  if(!empty($departments[0]) || !empty($sections[0])){
						foreach ($users_data as $key => $dataVal) {
							$uname = $this->web->getNameByUserId($dataVal->user_id);
							$roleDp = array_search($uname[0]->department,$departments);
							$roleSection = array_search($uname[0]->section,$sections);
							if(!is_bool($roleDp) || !is_bool($roleSection)){
							
							}else{
							unset($users_data[$key]);
							}
						}
					  }
					}
				}
				if(!empty($users_data)){
					foreach($users_data as $user){
						$open_leaves = $this->web->getOpenLeave($bid,$user->user_id);
						if($open_leaves){
							$this->web->updateOpenLeave($bid,$user->user_id,$open_date,$close_date,$cl,$pl,$el,$sl,$other,$hl,$rh,$comp_off,$limit_type,$fixed_limit,$carry,time());
								 if ($this->session->userdata()['type'] == 'P') {
          
          $loginId = $this->session->userdata('empCompany');
         // $role=$this->web->getRollbyid($this->web->session->userdata('login_id'),$loginId);
        } else {
          $loginId = $this->web->session->userdata('login_id');
        }
			$actdata=array(
			                            'bid'=>$loginId ,
				                        'uid'=>$this->web->session->userdata('login_id'),
				                        'activity'=>"Opened Leave Changed for all Employee",
				                        'date_time'=>time()
				
			                             );
			                  $data=$this->db->insert('activity',$actdata);
						}else{
							$data =array(
								'bid'=>$bid,
								'uid'=>$user->user_id,
								'open_date'=>$open_date,
								'close_date'=>$close_date,
								'cl'=>$cl,
							'pl'=>$pl,
							'el'=>$el,
							'sl'=>$sl,
							'hl'=>$hl,
								'rh'=>$rh,
						'comp_off'=>$comp_off,
								'other'=>$other,
								'limit_type'=>$limit_type,
								'fixed_limit'=>$fixed_limit,
								'carry'=>$carry,
								'date_time'=>time()
							);
							$this->web->addOpenLeave($data);
						}
					}
				}
					 if ($this->session->userdata()['type'] == 'P') {
          
          $loginId = $this->session->userdata('empCompany');
         // $role=$this->web->getRollbyid($this->web->session->userdata('login_id'),$loginId);
        } else {
          $loginId = $this->web->session->userdata('login_id');
        }
			$actdata=array(
			                            'bid'=>$loginId ,
				                        'uid'=>$this->web->session->userdata('login_id'),
				                        'activity'=>"New Leave Opened for all employee",
				                        'date_time'=>time()
				
			                             );
			                  $data=$this->db->insert('activity',$actdata);
				
				
				
				redirect('open_leave');
			}
		}else{
			redirect('user-login');
		}

	}
	
	public function addAllCtc(){
		$in_data = $this->input->post();
		$date = $this->input->post('date_from');
		$business_id  = $this->web->session->userdata('login_id');
		$maxId = $in_data['all_ctc_max'];
		if($maxId>0){
			for($s=1; $s<=$maxId; $s++){
				$salaryCtc = $in_data['all_basic_value_'.$s];
				$salaryPfValue = $in_data['pf_value_'.$s];
				$salaryEsiValue = $in_data['esi_value_'.$s];
				$salaryEmpId = $in_data['salary_emp_id_'.$s];
				$salaryChanged = $in_data['salary_changed_'.$s];
				if($salaryChanged==1 && $salaryCtc>0){
					
					$salaryPf = round(round($salaryCtc)*($salaryPfValue/100));
					$salaryEsi = round(round($salaryCtc)*($salaryEsiValue/100));
					$totalCtcAmount = $salaryCtc-$salaryPf-$salaryEsi;

					$saveCtcArray = array(
						'business_id' 	=> $business_id,
						'user_id' 		=> $salaryEmpId,
						'basic' 			=> "Monthly",
						'basic_value' 	=> $salaryCtc,
						'total_ctc_amount' 	=> $totalCtcAmount,
						'pf' 	=> "PF",
						'pf_type' 	=> "%",
						'pf_value' 	=> $salaryPfValue,
						'pf_amount' 	=> $salaryPf,
						'esi' 	=> "ESI",
						'esi_type' 	=> "%",
						'esi_value' 	=> $salaryEsiValue,
						'esi_amount' 	=> $salaryEsi,
						'date'=>date("Y-m-d H:i:s",strtotime($date))
					);

					$allowanceForm = array('DA', 'HRA', 'MEAL', 'CONVEYANCE', 'MEDICAL', 'SPECIAL', 'TA','Other');
					foreach ($allowanceForm as $key => $allData) {
						$dataType = strtolower($allData);
						$saveCtcArray[$dataType] = $allData;
						$saveCtcArray[$dataType.'_type']   = "Manual";
						$saveCtcArray[$dataType.'_value']  = "0";
						$saveCtcArray[$dataType.'_amount'] = "0";
					}

					$saveCtcArray['status'] = 1;
	
					$checkExist = $this->db->query("SELECT id FROM user_ctc WHERE  business_id = '".$business_id."' AND  user_id = '".$salaryEmpId."' AND  YEAR(date) = '".date('Y',strtotime($date))."' AND MONTH(date) = '".date('m',strtotime($date))."' ")->row_array();
					// print_r($checkExist);
					if(!empty($checkExist)){
						$save = $this->web->UpdateData('user_ctc' ,$saveCtcArray, array('id' => $checkExist['id']));
					}
					else{
						$save = $this->web->saveData('user_ctc' ,$saveCtcArray);
					}
				}
			}
		}
	
		if($save > 0){
			$response = array('message' 	=> 'CTC have successfully saved.',
			'status'  => '1'
			);
		}
		else{
			$response = array('message' 	=> 'Sorry! somthings wents wrong.',
			'status'  => '0'
			);
		}
    	redirect('User/salaryEmployees');
	}
	
	public function assignAllEmp(){
		if(!empty($this->session->userdata('id'))){
			$maxId = $_POST['user_max'];
			if($maxId>0){
				for($s=1; $s<$maxId; $s++){
					$changed = $_POST['user_changed_'.$s];
					if($changed==1){
						$data=array(
							'section' => $_POST['section_'.$s],
							'business_group' => $_POST['group_'.$s],
							'department' => $_POST['department_'.$s]
						);
						$this->db->where('id',$_POST['user_id_'.$s]);
						$data= $this->db->update('login',$data);
						$this->db->where('user_id',$_POST['user_id_'.$s]);
						$data= $this->db->update('user_request',['rule_id'=>$_POST['rule_'.$s]]);
								
					}
				}
						
					if ($this->session->userdata()['type'] == 'P') {
          
          $loginId = $this->session->userdata('empCompany');
         // $role=$this->web->getRollbyid($this->web->session->userdata('login_id'),$loginId);
        } else {
          $loginId = $this->web->session->userdata('login_id');
        }
			$actdata=array(
			                            'bid'=>$loginId ,
				                        'uid'=>$this->web->session->userdata('login_id'),
				                        'activity'=>"Assign data of all employee ",
				                        'date_time'=>time()
				
			                             );
			                  $data=$this->db->insert('activity',$actdata);	
						
						
				
				$this->session->set_flashdata('msg','Assigned Successfully!');
			}
			redirect('assign_shift');
		}
		else{
			redirect('user-login');
		}
	}
	
	
	public function verifyAllPending(){
		if (!empty($this->session->userdata('id'))) {
			if($this->session->userdata()['type']=='P'){
				$busi=$this->web->getBusinessbyUser($this->web->session->userdata('login_id'));
				$bid=$busi[0]->business_id;
			} else {
					$bid=$this->web->session->userdata('login_id');
			}
			$res= $this->web->verifyAllPending($bid);
			
		//	$uname = $this->web->getNameByUserId($uid);
				 if ($this->session->userdata()['type'] == 'P') {
          
          $loginId = $this->session->userdata('empCompany');
        
        } else {
          $loginId = $this->web->session->userdata('login_id');
        }
			$actdata=array(
			                            'bid'=>$loginId ,
				                        'uid'=>$this->web->session->userdata('login_id'),
				                        'activity'=>"All Pending Attendance Verified",
				                        'date_time'=>time()
				
			                             );
			                  $data=$this->db->insert('activity',$actdata);
			                  
			$this->session->set_flashdata('msg','Verifed All Successfully!');
			redirect('pending_att');
		} else {
			redirect('user-login');
		}
	}

	public function cancelAllPending(){
		if (!empty($this->session->userdata('id'))) {
			if($this->session->userdata()['type']=='P'){
				$busi=$this->web->getBusinessbyUser($this->web->session->userdata('login_id'));
				$bid=$busi[0]->business_id;
			} else {
					$bid=$this->web->session->userdata('login_id');
			}
			$res= $this->web->cancelAllPending($bid);
		//	$uname = $this->web->getNameByUserId($uid);
				 if ($this->session->userdata()['type'] == 'P') {
          
          $loginId = $this->session->userdata('empCompany');
        
        } else {
          $loginId = $this->web->session->userdata('login_id');
        }
			$actdata=array(
			                            'bid'=>$loginId ,
				                        'uid'=>$this->web->session->userdata('login_id'),
				                        'activity'=>"All Pending Attendance Canceled",
				                        'date_time'=>time()
				
			                             );
			                  $data=$this->db->insert('activity',$actdata);
			$this->session->set_flashdata('msg','Canceled All Successfully!');
			redirect('pending_att');
		} else {
			redirect('user-login');
		}
	}
	
	public function getPayrollHistory(){
		$in_data = $this->input->post();
		$response = array(
			'list' => array(),
			'status'  => '0' );
		$business_id = $this->web->session->userdata('login_id');
		if($in_data['payrolID']){
			$payrolID 		= $in_data['payrolID'];
			$user_id 		= $in_data['user_id'];
			$paid = 1;
			if($payrolID==2){
				$paid = 0;
			}
			if($payrolID==10){
				$payrolID = 2;
			}
			$payrollHist 	= $this->db->query("Select * from payroll_history where payroll_id='$payrolID' and status=1")->result();
			$response = array(
				'list' => $payrollHist,
				'status'  => '1' );
		}
		echo json_encode($response);
	}

	public function addEmpRole(){
		if(!empty($this->session->userdata('id'))){
			$loginId = $this->session->userdata('login_id');
			if($this->session->userdata('type')=="P"){
				$userCmp = $this->app->getUserCompany($loginId);
				if(isset($userCmp) && ($userCmp['left_date']=="" || $userCmp['left_date']>time())){
					$loginId = $userCmp['business_id'];
				}
			}
			
			$mobile = $this->input->post('empMobile');
			$checkEmpCompany = $this->web->getnameBymobile($mobile);
			if(!empty($checkEmpCompany)){
				$checkEmpRole = $this->web->checkEmpRoleCmp($checkEmpCompany[0]->id,$loginId);
				if(!isset($checkEmpRole['type'])){
					$data = array(
						'login_id' => $checkEmpCompany[0]->id,
						'username' => $mobile,
						'password' => md5("123"),
						'type' => 'P',
						'date' => time()
					);
					$check = $this->web->checkGeneratedLogin($checkEmpCompany[0]->id);
					if(empty($check)){
						$res = $this->db->insert("web_login", $data);
					}
					$data = array(
						'bid'=>$loginId,
						'uid'=>$checkEmpCompany[0]->id,
						'type'=>$this->input->post('empType')
						);
						$addUserRole = $this->web->addUserRole($data);
					$response = array('message' 	=> 'Employee Added',
					'status'  => '1');
				}else{
					$response = array('message' 	=> 'Employee Already Added',
					'status'  => '1');
				}
			}else{
				$response = array('message' 	=> 'Mobile Not Found',
				'status'  => '0');
			}
			
		}
		else{
			$response = array('message' 	=> 'Sorry! somthings wents wrong.',
			'status'  => '0');
		}
		echo json_encode($response);
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
		
		public function licence_history(){
			if(!empty($this->session->userdata('id'))){
				$data['lic']=$this->web->getlicencelogin();
				$this->load->view('users/licence_history',$data);
			}
			else{
				redirect('user-login');
			}
		}
	
		
		
		
		public function premiumbusinessusers(){
			if(!empty($this->session->userdata('id'))){
				$data['premium']=$this->web->getpremiumonly();
				$this->load->view('users/premium_business',$data);
			}
			else{
				redirect('user-login');
			}
		}


public function activebusinessusers(){
			if(!empty($this->session->userdata('id'))){
				
				
						$end_time=time();
						$start_time=strtotime('-10 day',$end_time);
			
				$data['premium']=$this->web->activebusiness($start_time,$end_time);
			
				$this->load->view('users/active_business',$data);
			}
			else{
				redirect('user-login');
			}
		}
		
		public function inactivebusinessusers(){
			if(!empty($this->session->userdata('id'))){
					$end_time=time();
						$start_time=strtotime('-60 day',$end_time);
			
				$data['premium']=$this->web->inactivebusiness($start_time,$end_time);
			
				//$data['premium']=$this->web->inactivebusiness();
				$this->load->view('users/inactive_business',$data);
			}
			else{
				redirect('user-login');
			}
		}
		
		public function activeusers(){
			if(!empty($this->session->userdata('id'))){
				$end_time=time();
						$start_time=strtotime('-10 day',$end_time);
			
				$data['users']=$this->web->activeusers($start_time,$end_time);
				$this->load->view('users/active_users',$data);
			}
			else{
				redirect('user-login');
			}
		}
		
		public function inactiveusers(){
			if(!empty($this->session->userdata('id'))){
				$end_time=time();
						$start_time=strtotime('-10 day',$end_time);
			
				$data['users']=$this->web->inactiveusers($start_time,$end_time);
				$this->load->view('users/inactive_users',$data);
			}
			else{
				redirect('user-login');
			}
		}


public function editreference(){
		if(!empty($this->session->userdata('id'))){
			$check=$_REQUEST;
			print_r($check);
			echo $reference = $_POST['ref'];
			
			echo $id = $_POST['id'];
			$data = array(
				'reference' => $reference
				
			);
			print_r($data);
			$this->db->where('id',$id);
			$res = $this->db->update('login',$data);
			echo $res;
			
		}
		else{
			redirect('user-login');
		}
	}
	
	
	public function editlicence(){
		if(!empty($this->session->userdata('id'))){
			$check=$_REQUEST;
			print_r($check);
			echo $licence = $_POST['lic'];
			
			echo $id = $_POST['id'];
				if ($this->db->where('login_id',$id )) { 
			$data = array(
				'assign_id' => $licence
			);
			print_r($data);
			$res = $this->db->update('new_qr',$data);
			echo $res;
				} else{
				    
				 	$data2 = array(
				'assign_id' => $licence,
				'login_id' => $id,
				'qr_code' => "09787",
				'licence' => "1",
				'status' => "1"
				
			);
			print_r($data);
			$res = $this->db->insert('new_qr',$data2);
			echo $res;   
				    
				}		
				
		}
		else{
			redirect('user-login');
		}
	}
	
	

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
					$users_data = $this->app->getCompanyUsers($loginId);
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
					$users_data = $this->app->getCompanyUsers($loginId);
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
			



	
	
	public function device_list(){
		if(!empty($this->session->userdata('id'))){
			$this->load->view('attendance/device_list');
		}
		else{
			redirect('user-login');
		}
	}


public function add_device(){
		if(!empty($this->session->userdata('id'))){
			$postdata=$this->input->post();
			$postdata=array(
			             'bid'=>$postdata['bid'],
					     'name'=>$postdata['name'],
						 'deviceid'=>$postdata['serial'],
					      'mode'=>$postdata['mode'],
						  'model'=>$postdata['model'],
						  'update_date'=>time(),
						  'active'=> 1
						// 'date'=>strtotime($holiday->date)
					);
			$data=$this->db->insert('Business_bioid',$postdata);
			if($data > 0){
			   // $uname = $this->web->getNameByUserId($id);
				 if ($this->session->userdata()['type'] == 'P') {
          
          $loginId = $this->session->userdata('empCompany');
         // $role=$this->web->getRollbyid($this->web->session->userdata('login_id'),$loginId);
        } else {
          $loginId = $this->web->session->userdata('login_id');
        }
			$actdata=array(
			                            'bid'=>$loginId ,
				                        'uid'=>$this->web->session->userdata('login_id'),
				                        'activity'=>"New Device Added",
				                        'date_time'=>time()
				
			                             );
			                  $data=$this->db->insert('activity',$actdata);
				$this->session->set_flashdata('msg','New Device Added!');
				redirect('device_list');
			}
		}
		else{
			redirect('user-login');
		}
	}




	
		public function delete_device(){
		if (!empty($this->session->userdata('id'))) {
			$id = $this->input->post('id');
			$res= $this->web->delete_device($id);
			if ($res) {
			    //$uname = $this->web->getNameByUserId($id);
				 if ($this->session->userdata()['type'] == 'P') {
          
          $loginId = $this->session->userdata('empCompany');
         // $role=$this->web->getRollbyid($this->web->session->userdata('login_id'),$loginId);
        } else {
          $loginId = $this->web->session->userdata('login_id');
        }
			$actdata=array(
			                            'bid'=>$loginId ,
				                        'uid'=>$this->web->session->userdata('login_id'),
				                        'activity'=>"Device Deleted from Deviice List",
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
	
	
	
	public function access_report(){
		if(!empty($this->session->userdata('id'))){
				$postdata=$this->input->post();
					$start_date = date("Y-m-d");
					$end_date = date("Y-m-d");
					$true = 0;
					$bio="";
					//$option= "all";
					//$days_array = array();
					$new_array = array();
					$loginId = $this->session->userdata('login_id');
					if($this->session->userdata('type')=="P"){
						$userCmp = $this->app->getUserCompany($loginId);
						if(isset($userCmp) && ($userCmp['left_date']=="" || $userCmp['left_date']>time())){
							$loginId = $userCmp['business_id'];
						}
					}
										
				//	$cmpName = $this->web->getBusinessById($loginId);

					if(isset($postdata['start_date']) && isset($postdata['end_date'])){
						$start_date = $postdata['start_date'];
						$end_date = $postdata['end_date'];
						$bio = $postdata['bio'];
						//$option = $postdata['option'];
						$true= 1;
						//$users_data = $this->app->getCompanyUsers($loginId);
						//$start_time = strtotime(date("d-m-Y 00:00:00",strtotime($start_date)));
						//$end_time = strtotime(date("d-m-Y 23:59:59",strtotime($end_date)));
					}
					
					
			     $data=array(
						'start_date'=>$start_date,
						'end_date'=>$end_date,
						'bio'=>$bio,
						'load'=>$true,
						//'option'=>$option,
					//	'cmp_name'=>$cmpName['name']
					);
					//print_r($new_array);
					$this->load->view('attendance/access_report',$data);
		
		}
		else{
			redirect('user-login');
		}
	}
	
	
	function GetBioAttendance(){
    if(!empty($this->session->userdata('id'))){
     // $check=$this->app->checkMobile($data->checkon->mobile);
     // if(!empty($check['id']) && $check['user_group']==1){
		  
		$postdata=$this->input->post();
					$start_date = date("Y-m-d");
					$end_date = date("Y-m-d");
					$true = 0;
					//$option= "all";
					//$days_array = array();
					//$new_array = array();
					$loginId = $this->session->userdata('login_id');
					if($this->session->userdata('type')=="P"){
						$userCmp = $this->app->getUserCompany($loginId);
						if(isset($userCmp) && ($userCmp['left_date']=="" || $userCmp['left_date']>time())){
							$loginId = $userCmp['business_id'];
						}
					}
		 if(isset($postdata['start_date']) && isset($postdata['end_date'])){
						$start_date = $postdata['start_date'];
						$end_date = $postdata['end_date'];
						//$bio = $postdata['bio'];
						//$option = $postdata['option'];
						$true= 1;
						$users_data = $this->app->getCompanyUsers($loginId);
						$start_time = strtotime(date("d-m-Y 00:00:00",strtotime($start_date)));
						$end_time = strtotime(date("d-m-Y 23:59:59",strtotime($end_date)));
					}
		 
		 
       $getBioDevice = $this->app->getBioDevice($loginId);
        $Msg = "No Device Found";
        foreach($getBioDevice as $device){
          $ch = curl_init();
          $headers  = ['Content-Type: application/x-www-form-urlencoded'];
          $fromDate = date("Y-m-d",$start_time);
		  // $fromDate ="2024-01-09";
         // if($device->update_date>0){
          //  $fromDate = date("Y-m-d",$device->update_date);
          //}
          $toDate = $fromDate = date("Y-m-d",$end_time);
          $postData = 'TXN_NAME=GetAttendanceLog&DATA={"DeviceSlNo":"'.$device->deviceid.'","FromDate":"'.$fromDate.'","ToDate":"'.$toDate.'"}';
          curl_setopt($ch, CURLOPT_URL,"http://103.30.72.34:7788/WebService.asmx/liveEmsTransaction");
          curl_setopt($ch, CURLOPT_POST, 1);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);         
          curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
          $result = json_decode(curl_exec($ch));
          $Msg = $result->Msg;
          if($result->Status=="Success" && !empty($result->data)){
            foreach($result->data as $row){
				$enroll_id=(int)$row->EnrollmentNo;
				
			
			//	 $staff = $this->web->getUserbyBioid($enroll_id);
					 // if(isset($staff)){    
					//foreach($staff as $staff){
						
								 //  $staffid = $this->web->getStaffbyBioid($staff->id,$loginId);
								  	// $userCmp = $this->app->getUserCompany($staff->id);	
					 //if($staffid!=0){
				
					// }
					// }}
				
				
              $getUserByBioId = $this->app->getUserByBioId($enroll_id,$loginId);
              if(isset($getUserByBioId)){
                $userCmp = $this->app->getUserCompany($getUserByBioId['id']);
                if( !empty($userCmp['business_id']) && $userCmp['business_id']==$loginId){
                  $checkOffline = $this->app->checkIoTime($getUserByBioId['id'],$loginId,strtotime($row->PunchDateTime));
                  if(empty($checkOffline)){
                    $start_time = strtotime(date("d-m-Y 00:00:00",strtotime($row->PunchDateTime)));
                    $end_time = strtotime(date("d-m-Y 23:59:59",strtotime($row->PunchDateTime)));
                    $offline_at = $this->app->checkOfflineAt($getUserByBioId['id'],$enroll_id,$start_time,$end_time);
                    $mode = "in";
                    if($userCmp['hostel']==1){
                        $mode = "out";
                    }
                    
                    if(!empty($offline_at)){
                      if($offline_at['mode']=="in"){
                        $mode = "out";
                      }else{
                        $mode = "in";
                      }
                    }
                    
                    $insertData = array(
                      'bussiness_id'=>$loginId,
                      'user_id'=>$getUserByBioId['id'],
                      'mode'=>$mode,
					  'device'=>$device->deviceid,
                      'comment'=>"",
                      'manual'=>"4",
                      'hostel'=>$userCmp['hostel'],
                      'io_time'=>strtotime($row->PunchDateTime),
                      'date'=>time()
                    );
                    
                  $res = $this->app->insertAttendance($insertData);
                  }
                }
              }
            }
            $updateData = array(
              'update_date'=>time()
            );
            $this->db->where('id',$device->id);
            $res=$this->db->update('Business_bioid',$updateData);
          }
        }
        $res=array('msg'=>$Msg,'status'=>'1');
        //echo $response= json_encode(array('checkon'=>$res));
		
	 
   $data=array(
						'start_date'=>$start_date,
						'end_date'=>$end_date,
						//'bio'=>$bio,
						'load'=>$true,
						'option'=>$option,
					//'cmp_name'=>$cmpName['name']
					);
					//print_r($new_array);
					$this->load->view('attendance/access_report',$insertdata);
		
		}
		else{
			redirect('user-login');
		}
	}
	
 
    
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	public function access_report_old(){
		if(!empty($this->session->userdata('id'))){
				$postdata=$this->input->post();
					$start_date = date("Y-m-d");
					$end_date = date("Y-m-d");
					$true = 0;
					$option= "all";
					$days_array = array();
					$new_array = array();
					$loginId = $this->session->userdata('login_id');
					if($this->session->userdata('type')=="P"){
						$userCmp = $this->app->getUserCompany($loginId);
						if(isset($userCmp) && ($userCmp['left_date']=="" || $userCmp['left_date']>time())){
							$loginId = $userCmp['business_id'];
						}
					}
										
				//	$cmpName = $this->web->getBusinessById($loginId);

					if(isset($postdata['start_date']) && isset($postdata['end_date'])){
						$start_date = $postdata['start_date'];
						$end_date = $postdata['end_date'];
						$bio = $postdata['bio'];
						$option = $postdata['option'];
						$true= 1;
						$users_data = $this->app->getCompanyUsers($loginId);
						$start_time = strtotime(date("d-m-Y 00:00:00",strtotime($start_date)));
						$end_time = strtotime(date("d-m-Y 23:59:59",strtotime($end_date)));
	
					}
					
					
			     $data=array(
						'start_date'=>$start_date,
						'end_date'=>$end_date,
						'bio'=>$bio,
						'load'=>$true,
						'option'=>$option,
					//	'cmp_name'=>$cmpName['name']
					);
					//print_r($new_array);
					$this->load->view('attendance/access_report',$data);
		
		}
		else{
			redirect('user-login');
		}
	}
	
	
	function import_staff()
    {
		if(!empty($this->session->userdata('id'))){
			
		
		$data=$this->web->import_staff();
		$this->load->view('attendance/employees',$data);
		
			
		}
		else{
			redirect('user-login');
		}
	  }
	  
	  
	  
	  function import_staff_detail()
    {
		if(!empty($this->session->userdata('id'))){
		
       // $this->load->model('import_excel_model');
		$data=$this->web->import_staff_detail();
		$this->load->view('attendance/employees',$data);
		
			
		}
		else{
			redirect('user-login');
		}
	  }
	  
	  
	  
	  function import_salary()
    {
		if(!empty($this->session->userdata('id'))){
	
		$data=$this->web->import_s();
		$this->load->view('attendance/employees',$data);
		
			
		}
		else{
			redirect('user-login');
		}
	  }


public function editdevice(){
		if(!empty($this->session->userdata('id'))){
			$check=$_REQUEST;
			print_r($check);
			echo $name = $_POST['name'];
			echo $deviceid = $_POST['deviceid'];
			echo $mode = $_POST['mode'];
         	echo $model = $_POST['model'];
			echo $id = $_POST['id'];
			$data = array(
				'name' => $name,
				'deviceid' => $deviceid,
				'mode' => $mode,
				'model' => $model
			);
			print_r($data);
			$this->db->where('id',$id);
			$res = $this->db->update('Business_bioid',$data);
			//$uname = $this->web->getNameByUserId($id);
				 if ($this->session->userdata()['type'] == 'P') {
          
          $loginId = $this->session->userdata('empCompany');
         // $role=$this->web->getRollbyid($this->web->session->userdata('login_id'),$loginId);
        } else {
          $loginId = $this->web->session->userdata('login_id');
        }
			$actdata=array(
			                            'bid'=>$loginId ,
				                        'uid'=>$this->web->session->userdata('login_id'),
				                        'activity'=>"Device data updated from device List",
				                        'date_time'=>time()
				
			                             );
			                  $data=$this->db->insert('activity',$actdata);
			echo $res;
			return($res);
		}
		else{
			redirect('user-login');
		}
	}
		
public function left_employee(){
			if(!empty($this->session->userdata('id'))){
				$this->load->view('attendance/left_employee');
			}
			else{
				redirect('user-login');
			}
		}	
	
	
	public function generate_login(){
		if(!empty($this->session->userdata('id'))){
			
			$this->load->view('attendance/generate_login');
		}
		else{
			redirect('user-login');
		}
	}
	
	
///////till new	
		
public function salary_head(){
		if(!empty($this->session->userdata('id'))){
			$this->load->view('payroll/salary_head');
		}
		else{
			redirect('user-login');
		}
	}	
	
public function edit_head(){
		if(!empty($this->session->userdata('id'))){
			$this->load->view('payroll/edit_head');
		}
		else{
			redirect('user-login');
		}
	}
	
	public function editctchead(){
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
			$res = $this->db->update('ctc_head',$data);
			
			 if ($this->session->userdata()['type'] == 'P') {
          
          $loginId = $this->session->userdata('empCompany');
        } else {
          $loginId = $this->web->session->userdata('login_id');
        }
			$actdata=array(
			                            'bid'=>$loginId ,
				                        'uid'=>$this->web->session->userdata('login_id'),
				                        'activity'=>"CTC Head Name Edited",
				                        'date_time'=>time()
				
			                             );
			                  $data=$this->db->insert('activity',$actdata);
			
			
			echo $res;
			return($res);
		}
		else{
			redirect('user-login');
		}
	}
	
/////new emp detail
	
	public function emp_detail(){
			if(!empty($this->session->userdata('id'))){
				$id = $this->input->post("id");
				
				$this->load->view('attendance/emp_detail');
			}

			else{
				redirect('user-login');
			}
		}	
	
	
///login
	public function staff_login(){
		$this->load->view('employee/staff_login');
	}
	public function staff_log(){
		$post=$this->input->post();
		$getLogin=$this->web->login($post['username'],md5($post['password']));
		if(!empty($getLogin)){
			$linked = $this->app->getAllLinked($getLogin['username']);
			$linkedData = array();
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
			
			
			redirect('page_staff');
		}
		else{
			$res = $this->web->checkUserStatus($post['username'],md5($post['password']));
			if (empty($res)) {
				$this->session->set_flashdata('msg', 'Incorrect username or password!');
			}elseif($res['status'] == 0){
				$this->session->set_flashdata('msg', 'User account not ACTIVE!');
			}
			redirect('staff-login');
		}
	}
	
	public function dashboard_staff(){
		if(!empty($this->session->userdata('id'))){
			$data['bookappoinment']=$this->web->GetBookCount();
			$data['counter']=$this->web->GetCountersCount();
			$data['count']=$this->web->GetUsersCount();
			$this->load->view('employee/dashboard_staff',$data);
		}
		else{
			redirect('user-login');
		}
	}	


public function Assign_working(){
		$this->load->view('attendance/Assign_working');
	}
	
	
public function assign_att(){
		if(!empty($this->session->userdata('id'))){
			$postdata=$this->input->post();
			$postdata=array(
			             'bid'=>$postdata['bid'],
					     'uid'=>$postdata['uid'],
						 'date'=>strtotime($postdata['start_date']),
						 'end_date'=>strtotime($postdata['end_date']),
					      'type'=>$postdata['type'],
						  'status'=> 1
						// 'date'=>strtotime($holiday->date)
					);
			$data=$this->db->insert('assign_working',$postdata);
			if($data > 0){
			    $uname = $this->web->getNameByUserId($uid);
				 if ($this->session->userdata()['type'] == 'P') {
          
          $loginId = $this->session->userdata('empCompany');
        
        } else {
          $loginId = $this->web->session->userdata('login_id');
        }
			$actdata=array(
			                            'bid'=>$loginId ,
				                        'uid'=>$this->web->session->userdata('login_id'),
				                        'activity'=>"New Attendance of employee ".$uname[0]->name. " Assigned",
				                        'date_time'=>time()
				
			                             );
			                  $data=$this->db->insert('activity',$actdata);
			    
			    
			    
			    
			    
			    
			    
			    
				$this->session->set_flashdata('msg','New Data Added!');
				redirect('User/Assign_working');
			}
		}
		else{
			redirect('user-login');
		}
	}
	
	public function delete_working(){
		if (!empty($this->session->userdata('id'))) {
			$id = $this->input->post('id');
			$res= $this->web->delete_working($id);
			if ($res) {
			    
			   // $uname = $this->web->getNameByUserId($uid);
				 if ($this->session->userdata()['type'] == 'P') {
          
          $loginId = $this->session->userdata('empCompany');
        
        } else {
          $loginId = $this->web->session->userdata('login_id');
        }
			$actdata=array(
			                            'bid'=>$loginId ,
				                        'uid'=>$this->web->session->userdata('login_id'),
				                        'activity'=>"Deleted Assigned Working ",
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



	public function updateemployeeAddress()
{
    if (empty($this->session->userdata('id'))) {
        redirect('user-login');
    }

    $id  = $this->input->post('id');
    $bid = $this->input->post('bid');

    /* ================= PRESENT ADDRESS ================= */
    $present_address = json_encode([
        'country'  => $this->input->post('present_country'),
        'state'    => $this->input->post('present_state'),
        'district' => $this->input->post('present_district'),
        'block'    => $this->input->post('present_block'),
        'street'   => $this->input->post('present_street'),
        'pincode'  => $this->input->post('present_pincode'),
    ]);

    /* ================= PERMANENT ADDRESS ================= */
    $permanent_address = json_encode([
        'country'  => $this->input->post('permanent_country'),
        'state'    => $this->input->post('permanent_state'),
        'district' => $this->input->post('permanent_district'),
        'block'    => $this->input->post('permanent_block'),
        'street'   => $this->input->post('permanent_street'),
        'pincode'  => $this->input->post('permanent_pincode'),
    ]);

    /* ================= UPDATE LOGIN TABLE ================= */
    $this->db->where('id', $id)->update('login', [
        'present_address'   => $present_address,
        'permanent_address' => $permanent_address
    ]);

    /* ================= ACTIVITY LOG ================= */
    if ($this->session->userdata('type') === 'P') {
        $loginId = $this->session->userdata('empCompany');
    } else {
        $loginId = $this->session->userdata('login_id');
    }

    $uname = $this->web->getNameByEmployeeId($id);

    $actdata = [
        'bid'       => $loginId,
        'uid'       => $this->session->userdata('login_id'),
        'activity'  => 'Employee address updated for ' . ($uname[0]->name ?? ''),
        'date_time' => time()
    ];
    $this->db->insert('activity', $actdata);

    /* ================= RESPONSE ================= */
    $this->session->set_flashdata('msg', 'Employee Address Updated Successfully!');
    redirect('employees');
}

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
public function left_emp(){
		if(!empty($this->session->userdata('id'))){
			$check=$_REQUEST;
			print_r($check);
			echo $dol = strtotime($_POST['dol']);
			echo $bid =$_POST['bid']; 
			echo $id = $_POST['id'];
			$data = array(
				'left_date' => $dol
				
			);
			print_r($data);
			
			$luser=$this->web->getuserById($bid,$id);
			if(!empty($luser)){
			$tid=$luser['0']->id;
			$this->db->where('id',$tid);
			//$this->db->where('id',$id);
			$res = $this->db->update('user_request',$data);
			
			$uname = $this->web->getNameByUserId($id);
				 if ($this->session->userdata()['type'] == 'P') {
          
          $loginId = $this->session->userdata('empCompany');
        
        } else {
          $loginId = $this->web->session->userdata('login_id');
        }
			$actdata=array(
			                            'bid'=>$loginId ,
				                        'uid'=>$this->web->session->userdata('login_id'),
				                        'activity'=>" Employee ".$uname[0]->name. " Left from Company",
				                        'date_time'=>time()
				
			                             );
			                  $data=$this->db->insert('activity',$actdata);
			echo $res;
			return($res);
			}
		}
		else{
			redirect('user-login');
		}
	}
	
	
	public function edit_emproll(){
		if(!empty($this->session->userdata('id'))){
			$check=$_REQUEST;
			print_r($check);
			echo $Type = $_POST['empType'];
			echo $bid =$_POST['bid']; 
			echo $id = $_POST['id'];
			$data = array(
				'type' => $Type
				
			);
			print_r($data);
			
			$this->db->where('uid',$id);
			$this->db->where('bid',$bid);
			$res = $this->db->update('emp_role',$data);
			echo $res;
			return($res);
			
		}
		else{
			redirect('user-login');
		}
	}
	
	
	


public function staff_attendance(){
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
				$empId = $this->web->session->userdata('login_id');	
			 	$userCmp = $this->app->getUserCompany($empId );
				$loginId = $userCmp['business_id'];
				if(isset($userCmp) && ($userCmp['left_date']=="" || $userCmp['left_date']>time())){
				
			 	}
			// }
			
			//$loginId = 				
			$cmpName = $this->web->getBusinessById($loginId);

			if(isset($postdata['start_date']) && isset($postdata['end_date'])){
				$start_date = $postdata['start_date'];
				$end_date = $postdata['end_date'];
				$empId = $this->web->session->userdata('login_id');	
				$option = $postdata['option'];
				$true= 1;
				//$users_data = $this->app->getCompanyUsers($loginId);
				 $user=$this->web->getNameByUserId($empId);
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

				

				if(!empty($empId)){
					//foreach($users_data as $user){
						//if($user->user_id==$empId || $empId=="0"){
							$date1=date_create(date("Y-m-d",strtotime($start_date)));
							$date2=date_create(date("Y-m-d",strtotime($end_date)));
							$diff=date_diff($date1,$date2);
							$num_month = $diff->format("%a");

							$num_month++;
							if($num_month>31){
								$num_month=31;
							}

							$groups = $this->app->getUserGroup($user[0]->business_group);
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

							$leaves = $this->app->getEmpLeaves($empId);
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
							
							
							
						$onduty =$this->web->getUserOTbyID($empId);
						$od_array = array();
			//	$od_days =0;
				if($onduty){
					
					foreach($onduty as $onduty){
				 
							  $from_date_od=date_create(date("Y-m-d",$onduty->date));
							  $to_date_od=date_create(date("Y-m-d",$onduty->end_date));
							  $od_diff=date_diff($from_date_od,$to_date_od);
							  $od_days = $od_diff->format("%a");
							  $od_days++;
							  for($c=0;$c<$od_days;$c++){
												$od_start_date = strtotime(date("d-m-Y",$onduty->date)." +".$c." days");
												$od_array[] = array(
													'date'=>date('d.m.Y',$od_start_date),
												);
											}
                      }
                 }
                 
                 	
                 	$wfh =$this->web->getUserbywfhbyID($empId);
						$wfh_array = array();
			//	$od_days =0;
				if($wfh){
					
					foreach($wfh as $wfh){
				 
							  $from_date_wfh=date_create(date("Y-m-d",$wfh->date));
							  $to_date_wfh=date_create(date("Y-m-d",$wfh->end_date));
							  $wfh_diff=date_diff($from_date_wfh,$to_date_wfh);
							  $wfh_days = $wfh_diff->format("%a");
							  $wfh_days++;
							  for($c=0;$c<$wfh_days;$c++){
												$wfh_start_date = strtotime(date("d-m-Y",$wfh->date)." +".$c." days");
												$wfh_array[] = array(
													'date'=>date('d.m.Y',$wfh_start_date),
												);
											}
                      }
                 }
                 
                 	
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							


                          $rules = $this->web->getRule($loginId,$empId);
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
							$totalod = 0;
							$totalwfh = 0;
							$totalWorkingHrs = "00:00 Hr";
							$totalLate = "00:00 Hr";
							$totalEarly = "00:00 Hr";
							$days_array = array();
							$seconds = 0;
							$monthStartTime = strtotime(date("d-m-Y 00:00:00",strtotime($start_date)));
							$monthEndTime = strtotime(date("d-m-Y 23:59:59",strtotime($start_date))." +".$num_month." days");
							$monthUserAt = $this->app->getUserAttendanceReportByDate($monthStartTime,$monthEndTime,$empId,$loginId,1);
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
								//if(($user->doj!="" || $start_time>=$user->doj) && ($user->left_date=="" || $start_time<$user->left_date)){
									$user_at = array_filter($monthUserAt, function($val) use($new_start_time, $new_end_time){
										return ($val->io_time>=$new_start_time and $val->io_time<=$new_end_time);
									});

									$off = array_search(date('N',$new_start_time),array_column($grp,'day_off'));
									$holi = array_search(date('d.m.Y',$new_start_time),array_column($holiday_array,'date'));
									$lv = array_search(date('d.m.Y',$new_start_time),array_column($leaves_array,'date'));
										$ods = array_search(date('d.m.Y',$new_start_time),array_column($od_array,'date'));
											$wfhs = array_search(date('d.m.Y',$new_start_time),array_column($wfh_array,'date'));
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
									
										if(!is_bool($ods)){
										$totalod++;
										$day_od="1";
									}else{
										$day_od="0";
									}
										if(!is_bool($wfhs)){
										$totalwfh++;
										$day_wfh="1";
									}else{
										$day_wfh="0";
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
											'od'=>$day_od,
											'wfh'=>$day_wfh,
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
							//}// user
							if($seconds>0){
								$hours = floor($seconds / 3600);
								$minutes = floor($seconds / 60%60);
								$totalWorkingHrs = "$hours:$minutes Hr";
							}
							if(count($months_array)>=1){
								$new_array[] =array(
									'user_id'=>$empId,
									//'mid'=>$user->mid,
									//'emp_code'=>$user->emp_code,
								      'name'=>$user->name,
									//'image'=>$user->image,
									//'user_status'=>$user->user_status,
									'shift_start'=>$shift_start,
									'shift_end'=>$shift_end,
									'group_name'=>$group_name,
									//'designation'=>$user->designation,
									'totalAbsent'=>$totalAbsent,
									'totalPresent'=>$totalPresent,
									'totalWeekOff'=>$totalWeekOff,
									'totalHoliday'=>$totalHoliday,
									'totalLeaves'=>$totalLeaves,
									'totalWorkingHrs'=>$totalWorkingHrs,
									'totalLate'=>$totalLate,
									'totalEarly'=>$totalEarly,
									'data'=> $months_array
								);
							}
						}
					}
				//}
			//}


			$data=array(
				'start_date'=>$start_date,
				'end_date'=>$end_date,
				'load'=>$true,
				'report'=>$new_array,
				'days'=>$days_array,
				'option'=>$option,
				'empId'=>$empId,
				'loginId'=>$loginId,
				//'departments'=>$departments,
				//'sections'=>$sections,
				// 'shifts'=>$shifts,
				//'depart'=>$depart,
				//'section'=>$section,
				//'status_check'=>$status_check,
				//'working_check'=>$working_check,
				//'totals_check'=>$totals_check,
				//'all_check'=>$all_check,
				//'two_check'=>$two_check,
				////'late_check'=>$late_check,
				//'early_check'=>$early_check,
				   //'shift'=>$shift,
				'cmp_name'=>$cmpName['name']
			);
			//print_r($new_array);
			$this->load->view('employee/attendance',$data);
		}else{
			redirect('user-login');
		}
	}

public function staff_leave(){
		if(!empty($this->session->userdata('id'))){
			$postdata=$this->input->post();
		
			$this->load->view('employee/leave');
		}
		else{
			redirect('user-login');
		}
	}

public function add_staffleave(){
		if(!empty($this->session->userdata('id'))){
			$postdata=$this->input->post();
			
			$postdata = array(
            'bid'=>$postdata['bid'],
            'uid'=>$postdata['uid'],
            'from_date'=>strtotime(date("d-m-Y 00:00:00",strtotime($postdata['from_date']))),
            'to_date'=>strtotime(date("d-m-Y 00:00:00",strtotime($postdata['to_date']))),
            'reason'=>$postdata['reason'],
            'type'=>$postdata['type'],
			'half_day'=>$postdata['days'],
            'date_time'=>time(),
			'status'=>$postdata['status']
			
          );
			$data=$this->db->insert('leaves',$postdata);
			if($data > 0){
				$this->session->set_flashdata('msg','New Leave Added!');
				redirect('User/staff_leave');
			}
		}
		else{
			redirect('user-login');
		}
	}



public function dashboard_hostel(){
		if(!empty($this->session->userdata('id'))){
			$data['bookappoinment']=$this->web->GetBookCount();
			$data['counter']=$this->web->GetCountersCount();
			$data['count']=$this->web->GetUsersCount();
			$this->load->view('hostel/hostel_dashboard',$data);
		}
		else{
			redirect('user-login');
		}
	}

public function log_report(){
	if(!empty($this->session->userdata('id'))){
		$this->load->view('employee/log_report');
	}else{
			redirect('user-login');
		}
	}



public function request_attendance(){
		if(!empty($this->session->userdata('id'))){
			$this->load->view('employee/request_attendance');
		}
		else{
			redirect('user-login');
		}
	}
public function req_att(){
		if(!empty($this->session->userdata('id'))){
			$postdata=$this->input->post();
			$addTime = $this->input->post("time");
			$addDate = $this->input->post("date");
			$postdata=array(
				'comment'=>$postdata['reason'],
				'bussiness_id'=>$postdata['bid'],
				'user_id'=>$postdata['uid'],
				'mode'=>$postdata['mode'],
				'io_time'=>strtotime("$addTime $addDate"),
				'verified'=> '0',
				'manual'=> '1',
				'status'=> '1'
				
			);
			$data=$this->db->insert('attendance',$postdata);
			if($data > 0){
				$this->session->set_flashdata('msg','Attendance Added!');
				$this->load->view('employee/request_attendance');
			}
		}
		else{
			redirect('user-login');
		}
	}

public function staffattendanceOptions(){
		if(!empty($this->session->userdata('id'))){
			$id = $this->input->post("id");
			$data = array(
				"options"=>$this->web->getUserOptions($this->session->userdata('login_id'),$id),
			);
			$this->load->view('attendance/attendance_options',$data);
		}
		else{
			redirect('user-login');
		}
	}	
	
public function activity_report(){
		if(!empty($this->session->userdata('id'))){
				$postdata=$this->input->post();
				
				if($this->session->userdata()['type']=='P'){
      
      $buid = $this->session->userdata('empCompany');
      $role=$this->web->getRollbyid($this->web->session->userdata('login_id'),$buid);
  
    } else {
      $buid=$this->web->session->userdata('login_id');
    }
				
	if($this->session->userdata()['type']=='B' || $role[0]->manager_role=="1" || $role[0]->type=="1"){			
				  $activity=$this->web->getUseractivity($buid); 
				}else{
				  $activity=$this->web->getUserPactivity($buid,$this->session->userdata('login_id'));   
				}
					
			     $data=array(
				 "activity"=>$activity
					);
					//print_r($new_array);  activity _log
					$this->load->view('attendance/activity_log',$data);
		
		}
		else{
			redirect('user-login');
		}
	}
		
	public function leave_history(){
		if(!empty($this->session->userdata('id'))){

			$this->load->view('attendance/leave_history');
		}
		else{
			redirect('user-login');
		}
	}
public function delete_leave(){
		if (!empty($this->session->userdata('id'))) {
			$id = $this->input->post('id');
			$uid = $this->input->post('uid');
			$fromdate = $this->input->post('fromdate');
			$res= $this->web->delete_leave($id);
			if ($res) {
			    //$uname = $this->web->getNameByUserId($id);
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
				                        'activity'=>"employee ".$uname[0]->name. " Leave Deleted  for date ".$fromdate."",
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
	
	public function edit_leave(){
		if(!empty($this->session->userdata('id'))){
			$postdata=$this->input->post();
			$id=$postdata['id'];
			$postdata = array(
            'from_date'=>strtotime(date("d-m-Y 00:00:00",strtotime($postdata['from_date']))),
            'to_date'=>strtotime(date("d-m-Y 00:00:00",strtotime($postdata['to_date']))),
            'reason'=>$postdata['reason'],
            'type'=>$postdata['type'],
			'half_day'=>$postdata['days']
            
			
          );
		     $this->db->where('id',$id);
			$data=$this->db->update('leaves',$postdata);
			if($data > 0){
				//$this->session->set_flashdata('msg','Leave Updated!');
				$uname = $this->web->getNameByUserId($uid);
				 if ($this->session->userdata()['type'] == 'P') {
          
          $loginId = $this->session->userdata('empCompany');
         // $role=$this->web->getRollbyid($this->web->session->userdata('login_id'),$loginId);
        } else {
          $loginId = $this->web->session->userdata('login_id');
        }
			$actdata=array(
			                            'bid'=>$loginId ,
				                        'uid'=>$this->web->session->userdata('login_id'),
				                        'activity'=>"employee ".$uname[0]->name. " Leave Edited  for date ".$fromdate."",
				                        'date_time'=>time()
				
			                             );
			                  $data=$this->db->insert('activity',$actdata);
				redirect('leave_history');
			}
		}
		else{
			redirect('user-login');
		}
	}
	
	
public function staff_payslip(){
		if(!empty($this->session->userdata('id'))){
			$postdata=$this->input->post();
		
			$this->load->view('employee/payslip');
		}
		else{
			redirect('user-login');
		}
	}	
	
	public function staffPass(){
		if (!empty($this->session->userdata('id'))) {
			$this->load->view('employee/password');
		}else{
			redirect('user-login');
		}
	}	
	
public function staff_profile(){
			if(!empty($this->session->userdata('id'))){
				$id = $this->input->post("id");
				
				$this->load->view('employee/profile');
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
	
	public function staff_Sleave(){
		if(!empty($this->session->userdata('id'))){
			$postdata=$this->input->post();
		
			$this->load->view('employee/Sleave');
		}
		else{
			redirect('user-login');
		}
	}
	
	public function add_staffSleave(){
		if(!empty($this->session->userdata('id'))){
			$postdata=$this->input->post();
			
			$postdata = array(
            'bid'=>$postdata['bid'],
            'uid'=>$postdata['uid'],
            'from_date'=>strtotime(date("d-m-Y 00:00:00",strtotime($postdata['from_date']))),
           // 'to_date'=>strtotime(date("d-m-Y 00:00:00",strtotime($postdata['to_date']))),
            'reason'=>$postdata['reason'],
            'type'=>$postdata['type'],
			'hour'=>$postdata['time'],
            'date_time'=>time(),
			'status'=>$postdata['status']
			
          );
			$data=$this->db->insert('Sleaves',$postdata);
			if($data > 0){
				$this->session->set_flashdata('msg','New Short Leave Requsted!');
				redirect('User/staff_Sleave');
			}
		}
		else{
			redirect('user-login');
		}
	}
	
	public function S_leave(){
		if(!empty($this->session->userdata('id'))){

			$this->load->view('attendance/S_leave');
		}
		else{
			redirect('user-login');
		}
	}
	
	public function aproveSUser(){
		if (!empty($this->session->userdata('id'))) {
			$id = $this->input->post('id');
			$uid = $this->input->post('uid');
			$fromdate = $this->input->post('fromdate');
			$res= $this->web->statusaproveSleave($id);
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
				                        'activity'=>"employee ".$uname[0]->name. " Short Leave Aproved  for date ".$fromdate."",
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

	public function rejectSUser(){
		if (!empty($this->session->userdata('id'))) {
			$id = $this->input->post('id');
			$uid = $this->input->post('uid');
			$fromdate = $this->input->post('fromdate');
			$res= $this->web->statusrejectSleave($id);
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
				                        'activity'=>"employee ".$uname[0]->name. " Short Leave Rejected  for date ".$fromdate."",
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

	
	
	public function leave_report(){
		if(!empty($this->session->userdata('id'))){
			$users_array=array();
			if ($this->session->userdata()['type'] == 'P') {
				$loginId = $this->session->userdata('empCompany');
				$role=$this->web->getRollbyid($this->web->session->userdata('login_id'),$loginId);
			} else {
				$loginId = $this->web->session->userdata('login_id');
			}					
			$users_data = $this->app->getCompanyUsers($loginId);
			if($this->session->userdata()['type']=='P'){
				if($role[0]->type!=1){
				  $departments = explode(",",$role[0]->department);
				  $sections = explode(",",$role[0]->section);
				  if(!empty($departments[0]) || !empty($sections[0])){
					foreach ($users_data as $key => $dataVal) {
						$uname = $this->web->getNameByUserId($dataVal->user_id);
						$roleDp = array_search($uname[0]->department,$departments);
						$roleSection = array_search($uname[0]->section,$sections);
						if(!is_bool($roleDp) || !is_bool($roleSection)){
						
						}else{
						unset($users_data[$key]);
						}
					}
				  }
				}
			  }
			  	$date = $_GET['date'];
			  	$month = isset($_GET['getDate']) ? $_GET['getDate'] : date("Y-m");
			if(!empty($users_data)){
				foreach($users_data as $user){

					$open_date = "";
					$close_date = "";
					$cl = "0";
					$pl = "0";
					$el = "0";
					$sl = "0";
					$other = "0";
					$hl = 0;
				$rh = 0;
				$comp_off = 0;

					$leaves = $this->web->getEmpLeaves($user->user_id);
					$id=$user->user_id;
					$bid=$loginId;

					$open_leaves = $this->web->getOpenLeave($loginId,$user->user_id);
					if($open_leaves){
						$open_date = $open_leaves['open_date'];
						$close_date = $open_leaves['close_date'];
						$cl = $open_leaves['cl'];
						$pl = $open_leaves['pl'];
						$el = $open_leaves['el'];
						$sl = $open_leaves['sl'];
						$other = $open_leaves['other'];
						$rh = $open_leaves['rh'];
						$hl = $open_leaves['hl'];
						$comp_off = $open_leaves['comp_off'];
						$limit_type = $open_leaves['limit_type'];
						$fixed_limit = $open_leaves['fixed_limit'];
						$carry = $open_leaves['carry'];
					}
					if($open_date!=""){
						$open_date = date('d-m-Y',$open_date);
					}
					if($close_date!=""){
						$close_date = date('d-m-Y',$close_date);
					}
					
		$yearName  = date('Y', strtotime($month));
		$monthName = date('m', strtotime($month));
		 $d = (cal_days_in_month(CAL_GREGORIAN,date('m',strtotime($month)),date('Y',strtotime($month))))-1;
		$start_time = strtotime(date("d-m-Y 00:00:00",strtotime($yearName."-".$monthName."-01")));
		$end_time = strtotime(date("d-m-Y 23:59:59",strtotime($yearName."-".$monthName."-01"))." +".$d." days"); 
			
		$data['leaven'] = $this->db->select_sum('leaves.half_day')
                                              ->from('leaves')
                                              ->where('leaves.status',1)
                                              ->where("uid",$id)
                                              ->where("bid",$bid)
                                             // ->where("DATE_FORMAT(from_date,%Y-%m)",$monthName)
                                              ->where("from_date >",$start_time )
                                              ->where("from_date <",$end_time)
                                             // ->where("2024",$yearName)
                                             // ->where("date('m', strtotime(from_date))",$monthName)
                                             //->where(YEAR(from_date)='$yearName' AND MONTH(from_date)='$monthName')
                                              ->get()
                                              ->row();
        $data['leaveold'] = $this->db->select_sum('leaves.half_day')
                                              ->from('leaves')
                                             // ->join('payroll_master','payroll_master.id=payroll_history.payroll_master_id')
                                             // ->where_in('payroll_master_id',[2])
                                              //->where("payroll_id",0)
                                              ->where('leaves.status',1)
                                              ->where('leaves.type!=',"other")
                                              ->where("uid",$id)
                                              ->where("bid",$bid)
                                             // ->where("DATE_FORMAT(from_date,%Y-%m)",$monthName)
                                              ->where("from_date <",$start_time )
                                             // ->where("from_date <",$end_time)
                                             // ->where("2024",$yearName)
                                             // ->where("date('m', strtotime(from_date))",$monthName)
                                             //->where(YEAR(from_date)='$yearName' AND MONTH(from_date)='$monthName')
                                              ->get()
                                              ->row();
          $data['leaveoldothern'] = $this->db->select_sum('leaves.half_day')
                                              ->from('leaves')
                                             ->where('leaves.type',"other")
                                              ->where('leaves.status',1)
                                              ->where("uid",$id)
                                              ->where("bid",$bid)
                                             
                                              ->where("from_date <",$start_time )
                                             
                                              ->get()
                                              ->row();                                    
                                                                                    
         
       // $data['usedleave']=		$end_time;
        
        $usedoldleave=$data['leaveold'] ? $data['leaveold']->half_day :0; 
        $leaveoldother=$data['leaveoldothern'] ? $data['leaveoldothern']->half_day :0; 
        
       // $data['total_leave'] =	$data['open_leave'] ? $data['open_leave']->cl+$data['open_leave']->el+$data['open_leave']->pl+$data['open_leave']->sl+$data['open_leave']->hl+$data['open_leave']->rh-$data['usedoldleave']:0;
       // $data['balanceleave']=$data['total_leave']- $data['usedleave'] ;
       
        
        //$openleavedate=$data['open_leave'] ? $data['open_leave']->open_date:0;
        $openleavemonth=date('m', $open_date);
        $monthdiff=$monthName-$openleavemonth;
        $usedleave=$data['leaven'] ? $data['leaven']->half_day :0;
        //$entitleleave=$data['open_leave'] ? $data['open_leave']->fixed_limit :0;
        // $balanceleave=$data['entitleleave']?$data['entitleleave']:0;
        $opening_leave= ($fixed_limit* $monthdiff)-$usedoldleave;
     // $carry_bal=$other-$data['leaveoldother']- $data['usedoldleave']+ $data['balanced_leave']  ;
      $carry_bal=$other-$leaveoldother;
					
				
					$new_array[] =array(
						'user_id'=>$user->user_id,
						'mid'=>$user->mid,
						'emp_code'=>$user->emp_code,
						'name'=>$user->name,
						'open_date'=>$open_date,
						'close_date'=>$close_date,
						'cl'=>$cl,
						'pl'=>$pl,
						'el'=>$el,
						'sl'=>$sl,
						'other'=>$other,
						'rh'=>$rh,
						'hl'=>$hl,
						'comp_off'=>$comp_off,
						'limit_type'=>$limit_type,
						'fixed_limit'=>$fixed_limit,
						'carry'=>$carry,
						'usedleave'=>$usedleave,
					    'opening_leave'=>$opening_leave,
					    'carry_bal'=>$carry_bal,
						'leaves'=>$leaves
					);
				}
				
				
				
			}
			 

			$data = array('users'=>$new_array);
			$this->load->view('attendance/add_leave',$data);
		}
		else{
			redirect('user-login');
		}
	}	
	
public function activity_log_report(){
		if(!empty($this->session->userdata('id'))){
				$postdata=$this->input->post();
				$start_date = date("Y-m-d");
			 $end_date = date("Y-m-d");
			 if($this->session->userdata()['type']=='P'){
      
      $buid = $this->session->userdata('empCompany');
     $role=$this->web->getRollbyid($this->web->session->userdata('login_id'),$buid);
  
    } else {
      $buid=$this->web->session->userdata('login_id');
    }
			 
			 
			 if(isset($postdata['start_date']) && isset($postdata['end_date'])){
				$start_date = $postdata['start_date'];
				$end_date = $postdata['end_date'];
				$empId = $postdata['emp'];
		     	$start_time = strtotime(date("d-m-Y 00:00:00",strtotime($start_date)));
				$end_time = strtotime(date("d-m-Y 23:59:59",strtotime($end_date)));
			if(!empty($empId)){
				//$users_data = $this->app->getCompanyUsers($loginId);
			
			 
			 
				   $activity=$this->web->getUseractivityfilter($buid,$empId,$start_time,$end_time); 
			} else{
			    $activity=$this->web->getUseractivityfilterP($buid,$start_time,$end_time); 
			}
			 	  if($this->session->userdata()['type']=='P'){
              if($role[0]->type!=1){
               $departments = explode(",",$role[0]->department);
               $sections = explode(",",$role[0]->section);
               $team = explode(",",$role[0]->team);
                
               if(!empty($departments[0]) || !empty($sections[0]) || !empty($team[0])){
                  foreach ($activity as $key => $dataVal) {
                 $uname = $this->web->getNameByUserId($dataVal->uid);
                  $roleDp = array_search($uname[0]->department,$departments);
                   $roleSection = array_search($uname[0]->section,$sections);
                   $roleTeam = array_search($dataVal->uid,$team);
                   
                  if(!is_bool($roleTeam) || !is_bool($roleSection) || !is_bool($roleDp)){
            
                    }else{
                    unset($activity[$key]);
                   }
                 }
                }
             }
            } 
				   
					
					
					
					
					
					
			     $data=array(
				 "activity"=>$activity
					);
					//print_r($new_array);  activity _log
					$this->load->view('attendance/activity_log',$data);
			 }
		}
		else{
			redirect('user-login');
		}
	}
	
	public function edit_managerroll(){
			if(!empty($this->session->userdata('id'))){
				$id = $this->input->post("id");
				
				$bid = $this->input->post("bid");
				
				$this->load->view('attendance/edit_roll');
			}

			else{
				redirect('user-login');
			}
		}
		public function resetpassword(){
		if (!empty($this->session->userdata('id'))) {
			//$id = $this->input->post('id');
			$bid=$this->input->post("bid");
			$id = $this->input->post("id");
			$pass = '123';
			$data = array(
				
				'password' => md5($pass)
				
			);
			
			$check = $this->web->checkGeneratedLogin($id);
           
				$this->db->where('login_id',$id);
			
			$res = $this->db->update('web_login',$data);
			
			$uname = $this->web->getNameByUserId($id);
				 if ($this->session->userdata()['type'] == 'P') {
          
          $loginId = $this->session->userdata('empCompany');
         
        } else {
          $loginId = $this->web->session->userdata('login_id');
        }
			$actdata=array(
			                            'bid'=>$loginId ,
				                        'uid'=>$this->web->session->userdata('login_id'),
				                        'activity'=>" Employee ".$uname[0]->name. " password reset Done",
				                        'date_time'=>time()
				
			                             );
			                  $data=$this->db->insert('activity',$actdata);
			
			
			if($res){
				redirect('users');
			}
		}else{
			redirect('user-login');
		}
	}
	
public function request_working(){
		if(!empty($this->session->userdata('id'))){
			$this->load->view('employee/assign_working');
		}
		else{
			redirect('user-login');
		}
	}
	
	
	public function request_wfh(){
		if(!empty($this->session->userdata('id'))){
			$postdata=$this->input->post();
			$postdata=array(
			             'bid'=>$postdata['bid'],
					     'uid'=>$postdata['uid'],
						 'date'=>strtotime($postdata['start_date']),
						 'end_date'=>strtotime($postdata['end_date']),
					      'type'=>$postdata['type'],
						  'status'=> 2
						// 'date'=>strtotime($holiday->date)
					);
			$data=$this->db->insert('assign_working',$postdata);
			if($data > 0){
			    $uname = $this->web->getNameByUserId($uid);
				 if ($this->session->userdata()['type'] == 'P') {
          
          $loginId = $this->session->userdata('empCompany');
        
        } else {
          $loginId = $this->web->session->userdata('login_id');
        }
			$actdata=array(
			                            'bid'=>$loginId ,
				                        'uid'=>$this->web->session->userdata('login_id'),
				                        'activity'=>"New Attendance of employee ".$uname[0]->name. " Requested",
				                        'date_time'=>time()
				
			                             );
			                  $data=$this->db->insert('activity',$actdata);
			    
			    
			    
			    
			    
			    
			    
			    
				$this->session->set_flashdata('msg','New Data Added!');
				redirect('User/request_working');
			}
		}
		else{
			redirect('user-login');
		}
	}
	
	public function verifyworking(){
		if (!empty($this->session->userdata('id'))) {
			$id = $this->input->post('id');
		     $uid = $this->input->post('uid');
			$fromdate = $this->input->post('fromdate');
			$res= $this->web->verifyworking($id);
			if ($res) {
				
				// $pending=$this->web->getGpsByDate($bid);
			//	$uname = $this->web->getNameByUserId($uid);
				 if ($this->session->userdata()['type'] == 'P') {
          
          $loginId = $this->session->userdata('empCompany');
        
        } else {
          $loginId = $this->web->session->userdata('login_id');
        }
        $uname = $this->web->getNameByUserId($uid);
			$actdata=array(
			                            'bid'=>$loginId ,
				                        'uid'=>$this->web->session->userdata('login_id'),
				                        'activity'=>"Requested Working Verified of Employee ".$uname[0]->name. " for date ".$fromdate."",
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
	public function cancelworking(){
		if (!empty($this->session->userdata('id'))) {
			$id = $this->input->post('id');
			$uid = $this->input->post('uid');
			$fromdate = $this->input->post('fromdate');
			$res= $this->web->cancelworking($id);
			if ($res) {
			    
				// $pending=$this->web->getGpsByDate($bid);
			//	$uname = $this->web->getNameByUserId($uid);
				 if ($this->session->userdata()['type'] == 'P') {
          
          $loginId = $this->session->userdata('empCompany');
        
        } else {
          $loginId = $this->web->session->userdata('login_id');
        }
        $uname = $this->web->getNameByUserId($uid);
			$actdata=array(
			                            'bid'=>$loginId ,
				                        'uid'=>$this->web->session->userdata('login_id'),
				                       'activity'=>"Requested Working Canceled of Employee ".$uname[0]->name. " for date ".$fromdate."",
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
	
	
	public function manage_shift(){
		if(!empty($this->session->userdata('id'))){
			
			$this->load->view('attendance/manage_shift');
		}
		else{
			redirect('user-login');
		}
	}
	
public function add_shift_detail(){
		if(!empty($this->session->userdata('id'))){
			$postdata=$this->input->post();
			$shift= $_POST['shift'];
			$shiftarray= implode(",",$shift);
			$postdata = array(
            'bid'=>$postdata['bid'],
            'uid'=>$postdata['uid'],
            'from_date'=>strtotime(date("d-m-Y 00:00:00",strtotime($postdata['from_date']))),
            'to_date'=>strtotime(date("d-m-Y 11:59:59",strtotime($postdata['to_date']))),
             'shift'=>$shiftarray,
            'rotation'=>$postdata['type'],
			'status'=>1
			
          );
			$data=$this->db->insert('shift_roster',$postdata);
			if($data > 0){
			//	$this->session->set_flashdata('msg','New Leave Added!');
				$uname = $this->web->getNameByUserId($uid);
				 if ($this->session->userdata()['type'] == 'P') {
          
          $loginId = $this->session->userdata('empCompany');
         // $role=$this->web->getRollbyid($this->web->session->userdata('login_id'),$loginId);
        } else {
          $loginId = $this->web->session->userdata('login_id');
        }
			$actdata=array(
			                            'bid'=>$loginId ,
				                        'uid'=>$this->web->session->userdata('login_id'),
				                        'activity'=>"New Shift Rotation Assigned to employee ".$uname[0]->name. " Added",
				                        'date_time'=>time()
				
			                             );
			                  $data=$this->db->insert('activity',$actdata);
				redirect('User/manage_shift');
			}
		}
		else{
			redirect('user-login');
		}
	}	
	
public function delete_shift_rost(){
		if (!empty($this->session->userdata('id'))) {
			$id = $this->input->post('id');
			$uid = $this->input->post('uid');
			$fromdate = $this->input->post('fromdate');
			$res= $this->web->delete_shift_rost($id);
			if ($res) {
			    
			   // $uname = $this->web->getNameByUserId($uid);
				 if ($this->session->userdata()['type'] == 'P') {
          
          $loginId = $this->session->userdata('empCompany');
        
        } else {
          $loginId = $this->web->session->userdata('login_id');
        }
			 $uname = $this->web->getNameByUserId($uid);
			 $actdata=array(
			                            'bid'=>$loginId ,
				                        'uid'=>$this->web->session->userdata('login_id'),
				                         'activity'=>"Delete Assigned Shift Rost of Employee ".$uname[0]->name. " for date ".$fromdate."",
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
	
public function shift_report(){
		if(!empty($this->session->userdata('id'))){
			
			$this->load->view('employee/manage_shift');
		}
		else{
			redirect('user-login');
		}
	}	
	
	
	
	
	
	
}

?>
