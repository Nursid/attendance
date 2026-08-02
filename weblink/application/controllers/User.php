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
	//	$this->load->model('Api_Model_v11','app');
		$this->load->model('Api_Model_v12','app');
		$this->load->helper('cookie');

	}
	public function index(){
		$this->load->view('users/login');
	}
	public function login(){
		$post=$this->input->post();
		$page=$post['page'];
		$bid=0;
		$getLogin=$this->web->login($post['username'],md5($post['password']));
		if(!empty($getLogin)){
		$getUserCompanies  = $this->web->getUserCompanies($getLogin['login_id']); 
	 if(($getLogin['type']=='P') && (!empty($getUserCompanies))){
     	$bid =$getUserCompanies[0]->bid;
	     } else {
		  $bid=$getLogin['login_id'];
		  }
		}
	$val = $this->web->getNameByUserId($bid);
	if(!empty($val)){
	  $validity=$val[0]->validity;
}
		if((!empty($getLogin) && $validity> time()) ||($getLogin['login_id']==0)  ){
		   
			
			
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
			}elseif($validity < time()){
			$this->session->set_flashdata('msg', 'Licence Validity Expired please Contact Your Service Provider');
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


		public function dailyreport(){
			if(!empty($this->session->userdata('id'))){

				$this->load->view('attendance/dailyreport');
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
												$minutes = floor($day_seconds / 60%60);
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
																	$minutes = floor($early_seconds / 60%60);
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
										     if($leave->type!="" && $leave->type!="unpaid" && $leave->status==1 ){
											$from_date_leave=date_create(date("Y-m-d",$leave->from_date));
											$to_date_leave=date_create(date("Y-m-d",$leave->to_date));
											$leave_diff=date_diff($from_date_leave,$to_date_leave);
										//	$leave_days = $leave_diff->format("%a");
										$half_day=$leave->half_day;
										if($half_day>0){
										   $leave_days=$half_day; 
										}else{
										    $leave_days = $leave_diff->format("%a");
										}
											
											for($l=0;$l<$leave_days;$l++){
												$leave_start_date = strtotime(date("d-m-Y",$leave->from_date)." +".$l." days");
												$leaves_array[] = array(
													'date'=>date('d.m.Y',$leave_start_date),
												);
											}
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
                                    $auto_wo_on = 0;
									$auto_wo = 0;
									 $edw_on = 0;
									$edw_days = 0;
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
										$auto_wo_on = $rules['auto_wo_on'];
										$auto_wo = $rules['auto_wo'];
										$edw_on = $rules['edw_on'];
										$edw_days = $rules['edw_days'];
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
								//	$monthStartTime = strtotime(date("d-m-Y 00:00:00",strtotime($start_date)));
								    $monthStartTime = strtotime(date("d-m-Y 00:00:00",strtotime($start_date))." -3 days");
									$monthEndTime = strtotime(date("d-m-Y 23:59:59",strtotime($start_date))." +".$num_month." days");
									$old_time = strtotime(date("31-08-2025 23:59:00"));
									$old_time2 = strtotime(date("31-01-2026 23:59:00"));
								    if($monthStartTime<$old_time){
								    $monthUserAt = $this->web->getUserAttendanceOldReportByDate($monthStartTime,$monthEndTime,$user->user_id,$loginId,1);    
								    }else if($monthStartTime<$old_time2){
								    $monthUserAt = $this->web->getUserAttendanceOld2ReportByDate($monthStartTime,$monthEndTime,$user->user_id,$loginId,1);    
								    }else{
								        
									$monthUserAt = $this->web->getUserAttendanceReportByDate($monthStartTime,$monthEndTime,$user->user_id,$loginId,1);
								    }
									for($d=0; $d<$num_month;$d++){
									    
									    
										$new_start_time = strtotime(date("d-m-Y 00:00:00",strtotime($start_date))." +".$d." days");
										$new_end_time = strtotime(date("d-m-Y 23:59:59",strtotime($start_date))." +".$d." days");
										$next_day_start_time = strtotime(date("d-m-Y 00:00:00",strtotime($start_date))." +".($d+1)." days");
										$next_day_end_time = strtotime(date("d-m-Y 23:59:59",strtotime($start_date))." +".($d+1)." days");

										$pre_day_start_time = strtotime(date("d-m-Y 00:00:00",strtotime($start_date))." +".($d-1)." days");
										$pre_day_end_time = strtotime(date("d-m-Y 23:59:59",strtotime($start_date))." +".($d-1)." days");
								    	$back_day_start_time = strtotime(date("d-m-Y 00:00:00",strtotime($start_date))." +".($d-3)." days");
								    	$back_day_end_time = strtotime(date("d-m-Y 23:59:59",strtotime($start_date))." +".($d-1)." days");
									  // $days_array[]= date("d D",$new_start_time);
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
											
											
											$backAt = array_filter($monthUserAt, function($val) use($back_day_start_time, $back_day_end_time){
												return ($val->io_time>=$back_day_start_time and $val->io_time<=$back_day_end_time);
										});
										$backAt = array_reverse($backAt);
											
											
											
											
											
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
										//	if(!is_bool($off)){
										if((!is_bool($off)) && (!empty($backAt))){
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
												// 		if($weekOff=="1" || $holiday=="1"){
												// 			$totalOT++;
												// 		}else{
															
												// 		}
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
										$minutes = floor($seconds / 60%60);
										$totalWorkingHrs = "$hours:$minutes Hr";
									}
									if(count($months_array)>0){
									if($auto_wo_on=="1"){
											$totalWeekOffs=intval(($auto_wo/22)*$totalPresent);
											if($totalWeekOffs>$auto_wo){
											 $totalWeekOffs=$auto_wo;  
											}
											$totalAbsent=$totalAbsent-$totalWeekOffs;
											if($totalAbsent<0){
											    
											    $totalAbsent=0;
											    
											}
											 }
										if($totalWeekOffs>0){
										   $totalWeekOff= $totalWeekOffs;
										}
										
										$nwds = $totalPresent+($totalP2/2)+$totalWeekOff+$totalHoliday+$totalLeaves+$totalOD+$totalwfh+$totalShortLeave;
										if($edw_on=="1"  && $edw_days=="0" && $nwds >$num_month ){
										   $nwd=$num_month ;
										}else{
										    $nwd=$nwds;
										}
										
										
										$new_array[] =array(
											'user_id'=>$user->user_id,
											'mid'=>$user->mid,
											'emp_code'=>$user->emp_code,
											'name'=>$user->name,
											'dep'=>$user->department,
											'section'=>$user->section,
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
											'totalOD'=>$totalOD+$totalwfh,
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
			$id="";
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
				if($postdata['type']=="edw_on"){
					$col = "edw_on";
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
				if($postdata['type']=="edw_days"){
					$col = "edw_days";
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
				
				if($postdata['type']=="ot_time"){
					$col = "ot_time";
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
					$limit_type = "0";
						$fixed_limit = "0";
						$carry = "0";

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
              $data['salEmpList'] = $this->web->insertDaysReport($this->input->post());
			$data['salEmpList'] = $this->web->insertSalleryReport($this->input->post());
			$data['salEmpList'] 	= $this->web->getSalleryEmply($this->input->post());
			$data['salEmpList'] = $this->web->getSallaryReport($this->input->post());
			$data['date_from'] = $this->input->post()['date_from'];
		}
		else
		{    $data['salEmpList'] = $this->web->insertDaysReport($this->input->post());
			$data['salEmpList'] 	= $this->web->insertSalleryReport();
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
					$empId="";
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
			//if($shiftStartTime<=date("23:59:59",time()) &&  $shiftEndTime <=date("09:00:00",time())){
           	if(strtotime($shiftStartTime)<=strtotime(date("23:59:59",time())) &&  strtotime($shiftEndTime) <=strtotime(date("09:00:00",time()))){
				$night="1";
			 }else{
			   	$night="0";  
			 }
			 
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
				"night"=>$night,
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
			
			if(strtotime($shiftStartTime)<=strtotime(date("23:59:59",time())) &&  strtotime($shiftEndTime) <=strtotime(date("09:00:00",time()))){

				$night="1";
			 }else{
			   	$night="0";  
			 }
			 
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
 
			$this->web->updateShift($shiftId,$loginId,$shiftName,$shiftStartTime,$shiftEndTime,$weeklyOff,$WeekOff,$dayStartTime,$dayEndTime,$night);
			
				
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
			echo $rfid = $_POST['rfid'];
			//echo $trf =$_POST['trf'];
		//	echo $group = $_POST['group'];
			$data=array(
						'name' => $name,
						'email' => $email,
						'address' => $address,
						//'emp_code' => $empcode,
						'gender' => $gender,
						//'designation' => $desig,
						'education' => $edu,
					     'company' => $bid,
						'doj' => $doj,
						'bio_id' => $bio_id,
						'rfid' => $rfid,
						'dob' => $dob,
						//'business_group' => $group,
						//'department' => $department
				
					);
			//$data=$this->db->update('login',$postdata);
			$this->db->where('id',$id);
			$data= $this->db->update('login',$data);
			
			if($doj!=''){
			
			$jdata=array('doj' => $doj
						//'left_date' => $dol
						
						);
			$this->db->where('user_id',$id);
			$data= $this->db->update('user_request',$jdata);
			}
			
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
	
	
	
	public function access_report5(){
		if(!empty($this->session->userdata('id'))){
				$postdata=$this->input->post();
					$start_date = date("Y-m-d");
					$end_date = date("Y-m-d");
					$bio=0;
						$event_name=0;
					$true = 0;
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
						$event_name = $postdata['event_name'];
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
							'event_name'=>$event_name,
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
	
 
    
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	public function access_report_old_not(){
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
						  'status'=> "2"
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
	
	
public function getCurrentCtcDetailsnew()
	{


		$selectedUserID = $this->input->post('userID');
		$business_id  = $this->web->session->userdata('login_id');
		$date = $this->input->post('date_from');

		$checkExist 	= $this->db->query("SELECT * FROM salary WHERE bid = '".$business_id."' AND  uid = '".$selectedUserID."' AND  YEAR(date) = '".date('Y',strtotime($date))."' AND MONTH(date) = '".date('m',strtotime($date))."' ")->row_array();

		if(empty($checkExist))
		{
			$checkExist = $this->db->query("SELECT * FROM salary WHERE bid = '".$business_id."' AND  uid = '".$selectedUserID."' ORDER BY date DESC ")->row_array();
		}


		if(!empty($checkExist))
		{
			// echo '<pre>'; print_r($checkExist[$FormData.'_type']); die();

			$allowance = '';
			$deduction = '';

			$allColumnArray = array('DA','HRA','MEAL', 'CONVEYANCE','MEDICAL','SPECIAL','TA', 'PF','ESI','Other');
			$deductionForm  = array('PF','ESI','Other');


			foreach ($allColumnArray as $key => $FormData) {
				/* $form_data  = strtolower($FormData);
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

*/
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


/////hostel New
	public function add_student(){
			if(!empty($this->session->userdata('id'))){
				$this->load->view('hostel/addstudent');
			}
			else{
				redirect('user-login');
			}
		}

	public function addnewstudent(){
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
					$temp_ = "MID";
					if($omid == ''){
						$nmid = $temp_.'0000';
					}else{
						$str1 = substr($omid,3);
						$str1 = $str1 + 1;
						$str2 = str_pad($str1 , 5 , 0 , STR_PAD_LEFT);
						$nmid = $temp_.$str2;
					}

					$postdata=$this->input->post();
					 $doj=strtotime($_POST['doj']);
					 $block=$_POST['block'];
					$floor=$_POST['floor'];
					$room_no=$_POST['room'];
					$room_type=$_POST['roomtype'];
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
						'rfid'=>$postdata['rfid'],
						'gender'=>$postdata['gender'],
						'education'=>$postdata['edu'],
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
								'hostel'=>"1",
								'user_status'=>"1"
							);
							$data=$this->db->insert('user_request',$cmpInData);


							$detailData = array(
								'bid'=>$uid,
								'uid'=>$id,
								'block'=>$block,
						       'floor'=>$floor,
								'room_no'=>$room_no,
								'room_type'=>$room_type,
								'date_time'=>time()
							);
							$data=$this->db->insert('hostel_detail',$detailData);


						}
                     
				
						}
                       
						$this->session->set_flashdata('msg','New Student Added!');
						redirect('student_list');
					
				}
				else{
					redirect('user-login');
				}
			}




public function hostel_student_report(){
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
			$this->load->view('hostel/student_report',$data);
		}else{
			redirect('user-login');
		}
	}







	public function canteen_rule(){
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
			$this->load->view('hostel/canteen_rule',$data);
		}
		else{
			redirect('user-login');
		}
	}





public function canteen_device(){
		if(!empty($this->session->userdata('id'))){
			$this->load->view('hostel/device_list');
		}
		else{
			redirect('user-login');
		}
	}


public function add_canteendevice(){
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
			                 // $data=$this->db->insert('activity',$actdata);
				$this->session->set_flashdata('msg','New Device Added!');
				redirect('canteen_device');
			}
		}
		else{
			redirect('user-login');
		}
	}



	public function timing(){
		if(!empty($this->session->userdata('id'))){
			$this->load->view('hostel/timing');
		}
		else{
			redirect('user-login');
		}
	}
	

public function add_membership(){
			if(!empty($this->session->userdata('id'))){
				$this->load->view('hostel/addmembership');
			}
			else{
				redirect('user-login');
			}
		}


public function membership(){
			if(!empty($this->session->userdata('id'))){
				$this->load->view('hostel/membership');
			}
			else{
				redirect('user-login');
			}
		}



public function addcanteentiming(){
		if(!empty($this->session->userdata('id'))){
			$postdata=$this->input->post();
			$name = $postdata['name'];
		//	$shiftstart =strtotime($postdata['shift_start']);
		    $shiftstart =strtotime(date("01-01-2024 H:i",strtotime($postdata['shift_start'])));
		//	$shiftend = strtotime($postdata['shift_end']);
			$shiftend =strtotime(date("01-01-2024 H:i",strtotime($postdata['shift_end'])));
			$off = $postdata['off'];

			$postdata = array(
				"bid"=>$this->session->userdata('login_id'),
				"name"=>$name,
				"start_time"=>$shiftstart,
				"end_time"=>$shiftend,
				"off"=>$off
				
			);

			$this->web->addtiming($postdata);
		
			
			redirect('timing');
		}else{
			redirect('user-login');
		}
	}


    public function editTiming(){
		if(!empty($this->session->userdata('id'))){
			$postdata=$this->input->post();
			 $loginId = $this->web->session->userdata('login_id');
        	$shift_id = $postdata['shift_id'];
			$shiftName = $postdata['shift_name'];
		$shiftStartTime =strtotime(date("01-01-2024 H:i",strtotime($postdata['shift_start'])));
		$shiftEndTime =strtotime(date("01-01-2024 H:i",strtotime($postdata['shift_end'])));
			$off = $postdata['off'];
	    	$this->web->updateTiming($shift_id,$shiftName,$shiftStartTime,$shiftEndTime,$off);
			redirect('timing');
		}else{
			redirect('user-login');
		}
	}






	public function deleteTiming(){
		if(!empty($this->session->userdata('id'))){
			$postdata=$this->input->post();
			$shiftId = $postdata['shift_id'];
			$this->web->deleteTiming($shiftId,$this->session->userdata('login_id'));
			redirect('timing');
		}else{
			redirect('user-login');
		}
	}


public function addnewmembership(){
		if(!empty($this->session->userdata('id'))){
			$postdata=$this->input->post();
			$sid = $postdata['usid'];
			$from_date = strtotime($postdata['val_from']);
			$to_date =strtotime($postdata['val_to']);
			$limit = $postdata['limit'];

			$data = array(
				"bid"=>$this->session->userdata('login_id'),
				"sid"=>$sid,
				"from_date"=>$from_date,
				"to_date"=>$to_date,
				"access_limit"=>$limit,
				
			);

			$this->web->addnewmembership($data);
		
			
			redirect('add_membership');
		}else{
			redirect('user-login');
		}
	}



	public function canteen_daily_report(){
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
				
			
			
			$this->load->view('hostel/canteen_dailyreport',$data);
		}
		else{
			redirect('user-login');
		}
	}	
	
		
		
		
		
		
		
	public function canteen_monthly_report(){
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
					
			
				
				$this->load->view('hostel/canteen_monthly_report',$data);
			}
			else{
				redirect('user-login');
			}
		}	
			

		public function exstudent_list(){
			if(!empty($this->session->userdata('id'))){
				$this->load->view('hostel/exstudent');
			}
			else{
				redirect('user-login');
			}
		}


		function import_student()
		{
			if(!empty($this->session->userdata('id'))){
				
			
			$data=$this->web->import_student();
			$this->load->view('hostel/student_list',$data);
			
				
			}
			else{
				redirect('user-login');
			}
		  }



public function canteenchangePass(){
		if (!empty($this->session->userdata('id'))) {
			$this->load->view('hostel/pass');
		}else{
			redirect('user-login');
		}
	}


public function updatepassword(){
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
						redirect('canteen-pass');
					}
				}else{
					$this->session->set_flashdata('msg','Confirm password does not match!');
					redirect('canteen-pass');
				}
			}else{
				$this->session->set_flashdata('msg','Incorrect old password!');
				redirect('canteen-pass');
			}
		}else{
			redirect('user-login');
		}
	}


 public function access_plan(){
			if(!empty($this->session->userdata('id'))){
				$this->load->view('hostel/access_plan');
			}
			else{
				redirect('user-login');
			}
		}
	
	
	public function add_access_plan(){
			if(!empty($this->session->userdata('id'))){
				$postdata=$this->input->post();
				$postdata=array(
							 'bid'=>$postdata['bid'],
							 'name'=>$postdata['name'],
							 'description'=>$postdata['descp'],
							  'days'=>$postdata['days'],
							  'access_limit'=>$postdata['limit'],
							  'type'=>$postdata['type'],
							  'date'=>time(),
							  'status'=> 1
							// 'date'=>strtotime($holiday->date)
						);
				$data=$this->db->insert('access_plan',$postdata);
				if($data > 0){
				   
					$this->session->set_flashdata('msg','New Plan Added!');
					redirect('access_plan');
				}
			}
			else{
				redirect('user-login');
			}
		}
	


		public function editplan(){
			if(!empty($this->session->userdata('id'))){
				$check=$_REQUEST;
				print_r($check);
				echo $name = $_POST['name'];
				echo $description = $_POST['descp'];
				echo $days = $_POST['days'];
				 echo $access_limit = $_POST['limit'];
				 echo $type = $_POST['type'];
				echo $id = $_POST['id'];
				$data = array(
					'name' => $name,
					'description' => $description,
					'days' => $days,
					'access_limit' => $access_limit,
					'type' => $type
				);
				print_r($data);
				$this->db->where('id',$id);
				$res = $this->db->update('access_plan',$data);
				
				if($res > 0){
				   
					$this->session->set_flashdata('msg',' Plan Updated!');
					redirect('access_plan');
				};
			}
			else{
				redirect('user-login');
			}
		}


		public function delete_plan(){
			if (!empty($this->session->userdata('id'))) {
				$id = $this->input->post('id');
				$res= $this->web->delete_plan($id);
				if ($res) {
					
					echo $id;
					return($id);
				}
			} else {
				redirect('user-login');
			}
			
		}
		public function delete_membership(){
			if (!empty($this->session->userdata('id'))) {
				$id = $this->input->post('id');
				$res= $this->web->delete_membership($id);
				if ($res) {
					
					echo $id;
					return($id);
				}
			} else {
				redirect('user-login');
			}
			
		}
		

		public function add_access_limit(){
			if(!empty($this->session->userdata('id'))){
				$postdata=$this->input->post();
				echo $plan = $_POST['plan'];
				$bid = $_POST['bid'];
                $uid = $_POST['uid'];
				echo $from_date = strtotime($_POST['from_date']);
				$res2 =$this->web->getplanbyid($plan);
				$days=$res2[0]->days;
				$access_limit=$res2[0]->access_limit;
				$to_date=strtotime(date("d-m-Y 00:00:00",strtotime($_POST['from_date']))." +".$days." days");
				$uname = $this->web->getNameByUserId($uid);
				 $bioid=$uname[0]->bio_id;
				 $res=$this->web->getdevice($bid);
                 $devid=$res[0]->deviceid;
				
				$postdata=array(
							 'bid'=>$postdata['bid'],
							 'sid'=>$postdata['uid'],
							 'plan_id'=>$postdata['plan'],
							 'days'=>$days,
							 'access_limit'=>$access_limit,
							 'from_date'=>$from_date,
							 'to_date'=>$to_date,
							 'status'=>1,
							 'date'=>time()	  
						);
				$data=$this->db->insert('membership_history',$postdata);
				if($data > 0){
				   
					$res= $this->web->Activatecstudent($devid,$bioid,$uid);
				   
					$this->session->set_flashdata('msg','New Plan Added!');
					redirect('membership_detail');
				}
			}
			else{
				redirect('user-login');
			}
		}



		public function edit_plan_validity(){
			if(!empty($this->session->userdata('id'))){
				$check=$_REQUEST;
				print_r($check);
				echo $days = $_POST['days'];
				 echo $access_limit = $_POST['access_limit'];
				$to_date=strtotime(date("d-m-Y 00:00:00",strtotime($_POST['from_date']))." +".$days." days");
				echo $id = $_POST['id'];
				$data = array(
					
					'to_date' => $to_date,
					'access_limit' => $access_limit,
					'days' => $days
					
				);

				$this->db->where('id',$id);
				$data = $this->db->update('membership_history',$data);
				if($data > 0){
				   
					$this->session->set_flashdata('msg','Data updated');
					redirect('membership_detail');
				
			}
			else{
				redirect('user-login');
			} }
		}

		public function device_access(){
			if(!empty($this->session->userdata('id'))){
				$this->load->view('hostel/device_access');
			}
			else{
				redirect('user-login');
			}
		}

/// live report new

/*
    public function hostel_student_timing_report() {	
			$this->load->library("pagination");
			$this->load->helper("url");
		
			if(!empty($this->session->userdata('id'))) {
				$uri = $_SERVER['REQUEST_URI'];
				$this->load->library('pagination');
		
				$config = array();
				$config["base_url"] = base_url('hostel_student_timing_report') . '?data=' . $_GET['data'].'&offset='.$_GET['offset'];
				
				$config["uri_segment"] = 3;
				$config['full_tag_open'] = '<ul class="pagination">';
				$config['full_tag_close'] = '</ul>';
				$config['first_link'] = 'First';
				$config['last_link'] = 'Last';
				$config['next_link'] = 'Next';
				$config['prev_link'] = 'Previous';
				$config['cur_tag_open'] = '<li class="active"><a href="#">';
				$config['cur_tag_close'] = '</a></li>';
				$config['num_tag_open'] = '<li>';
				$config['num_tag_close'] = '</li>';
		
				//$offset = (isset($_GET['offset'])) ? (int)$_GET['offset'] : 0;
				$offset = $_GET['offset'];
				//echo'<pre>';print_r($offset);die;

				$data['total_rows_in'] = $this->web->get_student_indata_timimg_count();
				$data['total_rows_out'] = $this->web->get_student_outdata_timimg_count();
				$data['total_rows_count_num'] = $this->web->get_student_out_timimg_count();
		
				$id = $this->session->userdata('login_id');
				$hostel_student_list = $this->web->getHostelStudentList($id);
		
				$config["per_page_six"] = 50;
				$config["per_page_eight"] = 84;
		
				$offsetsix = $offset * $config["per_page_six"];
				$offseteight = $offset * $config["per_page_eight"];
		
				// Set total rows based on query parameter
				if($_GET['data'] == 'in') {
					$config['total_rows'] = $this->web->get_student_indata_timimg_count();
				} elseif($_GET['data'] == 'out') {
					$config['total_rows'] = $this->web->get_student_outdata_timimg_count();
				} else {
					$config['total_rows'] = $this->web->get_student_out_timimg_count();
				}
		
				$this->pagination->initialize($config);
		
				if($_GET['data'] == 'in') {
					$data['studentTimingDetailsSix'] = $this->web->get_student_indata_timimg($config["per_page_six"], $offsetsix);
					$data['studentTimingDetailsEight'] = $this->web->get_student_indata_timimg($config["per_page_eight"], $offseteight);
					$data['links1'] = $this->pagination->create_links();
				} elseif($_GET['data'] == 'out') {
					$data['studentTimingDetailsSix'] = $this->web->get_student_outdata_timimg($config["per_page_six"], $offsetsix);
					$data['studentTimingDetailsEight'] = $this->web->get_student_outdata_timimg($config["per_page_eight"], $offseteight);
					$data['links2'] = $this->pagination->create_links();
				} else {
					$data['studentTimingDetailsSix'] = $this->web->get_student_out_timimg($config["per_page_six"], $offsetsix);
					$data['studentTimingDetailsEight'] = $this->web->get_student_out_timimg($config["per_page_eight"], $offseteight);
					$data['total_row_six'] = round($config['total_rows'] / $config["per_page_six"]);
					$data['total_row_eight'] = round($config['total_rows'] / $config["per_page_eight"]);
					$data['links3'] = $this->pagination->create_links();
				}
		
				$this->load->view('hostel/hostel_student_timing_report', $data);
			} else {
				redirect('user-login');
			}
		}


public function hostel_student_timing_report($type=null,$offset=null) {
		//print_r($type);die;
		$this->load->library('pagination');
		$this->load->helper('url');
		$data = $this->input->post();
		$segments = $this->uri->segment_array();
		$resultCount = $this->web->get_student_out_timimg_count('all');
		$resultCountIn = $this->web->get_student_out_timimg_count('in');
		$resultCountOut = $this->web->get_student_out_timimg_count('out');
		//echo'<pre>';print_r($segments[2]);die;
		$config['base_url'] = base_url().'hostel_student_timing_report/'.$segments[2].'/';
		$config['total_rows'] = $resultCount;
		$config['per_page'] = 119;
		$config["use_page_numbers"] = TRUE;
		$config['full_tag_open'] = '<ul class="pagination">';
		$config['full_tag_close'] = '</ul>';
		$config['first_tag_open'] = '<li class="page-item">';
		$config['first_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li class="page-item">';
		$config['last_tag_close'] = '</li>';
		$config['next_link'] = '&raquo;';
		$config['next_tag_open'] = '<li class="page-item">';
		$config['next_tag_close'] = '</li>';
		$config['prev_link'] = '&laquo;';
		$config['prev_tag_open'] = '<li class="page-item">';
		$config['prev_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li class="page-item">';
		$config['num_tag_close'] = '</li>';
		$config['attributes'] = array('class' => 'page-link');
		$this->pagination->initialize($config);	
		$page = $this->input->post('page') ? $this->input->post('page') : 1;
		if(isset($segments[3])){
			$offset = $config['per_page']*$segments[3];
		}else{
			$offset = $config['per_page']*1;
		}
		if($segments[2]=='all'){
			$data["results"] = $this->web->getAllDataofHostelStudent($config["per_page"], $offset, $segments[2]);
		}
		if($segments[2]=='in'){
			$data["results"] = $this->web->get_student_indata_timimg($config["per_page"], $offset, $segments[2]);
		}
		if($segments[2]=='out'){
			$data["results"] = $this->web->get_student_outdata_timimg($config["per_page"], $offset, $segments[2]);
		}
		$data["total_count"] = $resultCount;
		$data["total_count_in"] = $resultCountIn;
		$data["total_count_out"] = $resultCountOut;
		$data["links"] = $this->pagination->create_links();
		$this->load->view('hostel/hostel_student_timing_report', $data);
	}
	
	public function hostel_student_timing_report_sm($offset=null) {	
		$this->load->library('pagination');
		$this->load->helper('url');
		$data = $this->input->post();
		$resultCount = $this->web->get_student_out_timimg_count('all');
		$resultCountIn = $this->web->get_student_out_timimg_count('in');
		$resultCountOut = $this->web->get_student_out_timimg_count('out');
		$config['base_url'] = base_url().'hostel_student_timing_report_sm';
		$config['total_rows'] = $resultCount;
		$config['per_page'] = 65;
		$config["use_page_numbers"] = TRUE;
		$config['full_tag_open'] = '<ul class="pagination">';
		$config['full_tag_close'] = '</ul>';
		$config['first_tag_open'] = '<li class="page-item">';
		$config['first_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li class="page-item">';
		$config['last_tag_close'] = '</li>';
		$config['next_link'] = '&raquo;';
		$config['next_tag_open'] = '<li class="page-item">';
		$config['next_tag_close'] = '</li>';
		$config['prev_link'] = '&laquo;';
		$config['prev_tag_open'] = '<li class="page-item">';
		$config['prev_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li class="page-item">';
		$config['num_tag_close'] = '</li>';
		$config['attributes'] = array('class' => 'page-link');
		$this->pagination->initialize($config);	
		$page = $this->input->post('page') ? $this->input->post('page') : 1;
		$segments = $this->uri->segment_array();
		if(isset($segments[2])){
			$offset = $config['per_page']*$segments[2];
		}else{
			$offset = $config['per_page']*1;
		}
		$data["results"] = $this->web->getAllDataofHostelStudent($config["per_page"], $offset);
		$data["total_count"] = $resultCount;
		$data["total_count_in"] = $resultCountIn;
		$data["total_count_out"] = $resultCountOut;
		$data["links"] = $this->pagination->create_links();
		$this->load->view('hostel/hostel_student_timing_reports', $data);
	}
	
	
	public function add_user_out_timing(){
		$postdata=$this->input->post();
		$result = $this->web->add_user_out($postdata);
		if($result){
			$data['studentDetails'] = $this->web->get_student_student();
			$data['studentOutData'] = $this->web->get_out_student();
			$this->load->view('hostel/hostel_student_timing_report',$data);
		}
	}
	public function add_user_in_timing(){
		$postdata=$this->input->post();
		
		$result = $this->web->updateUserInData($postdata);
		if($result){
			$data['studentDetails'] = $this->web->get_student_student();
			$data['studentOutData'] = $this->web->get_out_student();
			$this->load->view('hostel/hostel_student_timing_report',$data);
		}
	}
		


*/



public function hostel_student_timing_report_old() {	
	$this->load->library("pagination");
	$this->load->helper("url");

	if(!empty($this->session->userdata('id'))) {
		$uri = $_SERVER['REQUEST_URI'];
		$this->load->library('pagination');

		$config = array();
		$config["base_url"] = base_url('live_report') . '?data=' . $_GET['data'].'&offset='.$_GET['offset'];
		
		$config["uri_segment"] = 3;
		$config['full_tag_open'] = '<ul class="pagination">';
		$config['full_tag_close'] = '</ul>';
		$config['first_link'] = 'First';
		$config['last_link'] = 'Last';
		$config['next_link'] = 'Next';
		$config['prev_link'] = 'Previous';
		$config['cur_tag_open'] = '<li class="active"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';

		//$offset = (isset($_GET['offset'])) ? (int)$_GET['offset'] : 0;
		$offset = $_GET['offset'];
		//echo'<pre>';print_r($offset);die;
        $id = $this->session->userdata('login_id');
		$data['total_rows_in'] = $this->web->get_student_indata_timimg_count($id);
		$data['total_rows_out'] = $this->web->get_student_outdata_timimg_count($id);
		$data['total_rows_count_num'] = $this->web->get_student_out_timimg_count($id);

		
		//$hostel_student_list = $this->web->getHostelStudentList($id);

		$config["per_page_six"] = 50;
		$config["per_page_eight"] = 84;

		$offsetsix = $offset * $config["per_page_six"];
		$offseteight = $offset * $config["per_page_eight"];

		// Set total rows based on query parameter
		if($_GET['data'] == 'in') {
			$config['total_rows'] = $this->web->get_student_indata_timimg_count($id);
		} elseif($_GET['data'] == 'out') {
			$config['total_rows'] = $this->web->get_student_outdata_timimg_count($id);
		} else {
			$config['total_rows'] = $this->web->get_student_out_timimg_count($id);
		}

		$this->pagination->initialize($config);

		if($_GET['data'] == 'in') {
			$data['studentTimingDetailsSix'] = $this->web->get_student_indata_timimg($config["per_page_six"], $offsetsix,$id);
			$data['studentTimingDetailsEight'] = $this->web->get_student_indata_timimg($config["per_page_eight"], $offseteight,$id);
			$data['links1'] = $this->pagination->create_links();
		} elseif($_GET['data'] == 'out') {
			$data['studentTimingDetailsSix'] = $this->web->get_student_outdata_timimg($config["per_page_six"], $offsetsix,$id);
			$data['studentTimingDetailsEight'] = $this->web->get_student_outdata_timimg($config["per_page_eight"], $offseteight,$id);
			$data['links2'] = $this->pagination->create_links();
		} else {
			$data['studentTimingDetailsSix'] = $this->web->get_student_out_timimg($config["per_page_six"], $offsetsix,$id);
			$data['studentTimingDetailsEight'] = $this->web->get_student_out_timimg($config["per_page_eight"], $offseteight,$id);
			$data['total_row_six'] = round($config['total_rows'] / $config["per_page_six"]);
			$data['total_row_eight'] = round($config['total_rows'] / $config["per_page_eight"]);
			$data['links3'] = $this->pagination->create_links();
		}

		$this->load->view('hostel/live_report', $data);
	} else {
		redirect('user-login');
	}
}


public function hostel_student_timing_report() {	
	$this->load->library("pagination");
	$this->load->helper("url");

	if(!empty($this->session->userdata('id'))) {
		$uri = $_SERVER['REQUEST_URI'];
		$this->load->library('pagination');

		$config = array();
		$config["base_url"] = base_url('live_report') . '?data=' . $_GET['data'].'&offset='.$_GET['offset'];
		
		$config["uri_segment"] = 3;
		$config['full_tag_open'] = '<ul class="pagination">';
		$config['full_tag_close'] = '</ul>';
		$config['first_link'] = 'First';
		$config['last_link'] = 'Last';
		$config['next_link'] = 'Next';
		$config['prev_link'] = 'Previous';
		$config['cur_tag_open'] = '<li class="active"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';

		$offset = (isset($_GET['offset'])) ? (int)$_GET['offset'] : 0;
		// $offset = $_GET['offset'];
		
		//echo'<pre>';print_r($offset);die;
        $id = $this->session->userdata('login_id');

	

		$data['total_rows_in'] = $this->web->get_student_indata_timimg_count($id);

	

		$data['total_rows_out'] = $this->web->get_student_outdata_timimg_count($id);
		$data['total_rows_count_num'] = $this->web->get_student_out_timimg_count($id);

		
		//$hostel_student_list = $this->web->getHostelStudentList($id);

		$config["per_page_six"] = 50;
		$config["per_page_eight"] = 84;

		$offsetsix = $offset * $config["per_page_six"];
		$offseteight = $offset * $config["per_page_eight"];

		// Set total rows based on query parameter
		if($_GET['data'] == 'in') {
			$config['total_rows'] = $this->web->get_student_indata_timimg_count($id);
		} elseif($_GET['data'] == 'out') {
			$config['total_rows'] = $this->web->get_student_outdata_timimg_count($id);
		} else {
			$config['total_rows'] = $this->web->get_student_out_timimg_count($id);
		}

		$this->pagination->initialize($config);

		if($_GET['data'] == 'in') {
			$data['studentTimingDetailsSix'] = $this->web->get_student_indata_timimg($config["per_page_six"], $offsetsix,$id);
			$data['studentTimingDetailsEight'] = $this->web->get_student_indata_timimg($config["per_page_eight"], $offseteight,$id);
			$data['links1'] = $this->pagination->create_links();
		} elseif($_GET['data'] == 'out') {
			$data['studentTimingDetailsSix'] = $this->web->get_student_outdata_timimg($config["per_page_six"], $offsetsix,$id);
			$data['studentTimingDetailsEight'] = $this->web->get_student_outdata_timimg($config["per_page_eight"], $offseteight,$id);
			$data['links2'] = $this->pagination->create_links();
		} else {
			$data['studentTimingDetailsSix'] = $this->web->get_student_out_timimg($config["per_page_six"], $offsetsix,$id);
			$data['studentTimingDetailsEight'] = $this->web->get_student_out_timimg($config["per_page_eight"], $offseteight,$id);
			$data['total_row_six'] = ceil($config['total_rows'] / $config["per_page_six"]);
			$data['total_row_eight'] = ceil($config['total_rows'] / $config["per_page_eight"]);
			$data['links3'] = $this->pagination->create_links();
		}

		// 	echo '<pre>';
		// print_r($data);
		// echo '</pre>';
		// die();

		$this->load->view('hostel/live_report', $data);
	} else {
		redirect('user-login');
	}
}









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
		$bid = $this->session->userdata('login_id');
		if(!empty($bid)){
			$data['total_branches'] = $this->web->getTotalBranches($bid);
			
			$data['total_students'] = $this->web->getTotalStudents($bid);

			
			$data['total_staff'] = $this->web->getTotalStaff($bid);

			
			$data['total_subjects'] = $this->web->getTotalSubjects($bid);

			  // Get all branches for initial dropdown
			  $data['branches'] = $this->web->getBusinessDepByBusinessId($bid);
        
			  $data['total_branches'] = $this->web->getTotalBranches($bid);
			  $data['total_students'] = $this->web->getTotalStudents($bid);
			  $data['total_staff'] = $this->web->getTotalStaff($bid);
			  $data['total_subjects'] = $this->web->getTotalSubjects($bid);

			$this->load->view('student/school_dashboard', $data);
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
				$semester = $postdata['semester'];
				$section = $postdata['section'];
				$true= 1;
			}
			
			$data=array(
				//'start_date'=>$start_date,
			//	'end_date'=>$end_date,
				'dept'=>$dept,
				'semester'=>$semester,
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

        $postdata = $this->input->post();

        $start_date = date("Y-m-d");
        $true = 0;
        $totalActive = 0;
        $totalPresent = 0;
        $totalAbsent = 0;
        $days_array = array();
        $new_array = array();

        if ($this->session->userdata()['type'] == 'P') {
            $loginId = $this->session->userdata('empCompany');
            $role = $this->web->getRollbyid($this->web->session->userdata('login_id'), $loginId);
        } else {
            $loginId = $this->web->session->userdata('login_id');
        }

        $cmpName = $this->web->getBusinessById($loginId);
        $action = "active";

        if (isset($postdata['start_date']) && isset($postdata['dept'])) {
            $start_date = $postdata['start_date'];
            $dept = $postdata['dept'];
            $semester = $postdata['semester'];
            $section = $postdata['section'];
            $true = 1;
            $action = $postdata['action'];
        }

        $users_data = $this->web->getSchoolStudentListbysection_new($loginId, $dept, $semester, $section);

        $start_time = strtotime(date("d-m-Y 00:00:00", strtotime($start_date)));
        $end_time = strtotime(date("d-m-Y 23:59:59", strtotime($start_date)));

        if (!empty($users_data)) {
            foreach ($users_data as $user) {
                $days_array[] = date("d", $start_time);
                $data = array();
                $day_hrs = "00:00 Hr";

                // if (($user->doj != "" || $start_time >= $user->doj) && ($user->left_date == "" || $start_time < $user->left_date)) {
                    $totalActive++;
                    $user_at = $this->web->getStudentAttendanceReportByDate($start_time, $end_time, $user->id, $loginId);

                    if (!empty($user_at)) {
                        $totalPresent++;
                        $uniqueTimes = array(); // <-- Add for deduplication

                        foreach ($user_at as $at) {
                            if (!in_array($at->time, $uniqueTimes)) { // <-- Skip duplicate time
                                $uniqueTimes[] = $at->time;
                                $data[] = array(
                                    'mode' => $at->student_status,
                                    'time' => $at->time,
                                    'device' => $at->device,
                                   // 'class' => $at->class_id,
                                    'Att_status' => "P"
                                );
                                $Attstatus = "P";
                            }
                        }
                    } else {
                        $totalAbsent++;
                        $data = array();
                        $Attstatus = "A";
                    }

                    if (($action == "active") || ($action == "present" && count($data) > 0) || ($action == "absent" && empty($data))) {
                        $new_array[] = array(
                            'user_id' => $user->id,
                            'name' => $user->name,
                            'Att_status' => $Attstatus,
                            'data' => $data,
                        );
                    }
                // }
            }
        }

        $data = array(
            'start_date' => $start_date,
            'dept' => $dept,
            'semester' => $semester,
            'section' => $section,
            'load' => $true,
            'report' => $new_array,
            'totalActive' => $totalActive,
            // 'class' => $class ?? '', // avoid undefined
            'totalAbsent' => $totalAbsent,
            'totalPresent' => $totalPresent,
            'cmp_name' => $cmpName['name']
        );

        $this->load->view('student/students_dailyreport', $data);
    } else {
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
				'user_id'=>$user->roll_no,
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




function import_excellog()
    {
		if(!empty($this->session->userdata('id'))){
			
		
		$data=$this->web->import_log();
		$this->load->view('attendance/device_access',$data);
		
			
		}
		else{
			redirect('user-login');
		}
	  }



	public function visitor_log(){
		if(!empty($this->session->userdata('id'))){
				$postdata=$this->input->post();
					$start_date = date("Y-m-d");
					$end_date = date("Y-m-d");
					$bio=0;
					$true = 0;
					//$option= "all";
					//$days_array = array();
					$new_array = array();
					$loginId = $this->session->userdata('login_id');
				/*	if($this->session->userdata('type')=="P"){
						$userCmp = $this->app->getUserCompany($loginId);
						if(isset($userCmp) && ($userCmp['left_date']=="" || $userCmp['left_date']>time())){
							$loginId = $userCmp['business_id'];
						}
					}*/
										
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
					$this->load->view('hostel/visitor_log',$data);
		
		}
		else{
			redirect('user-login');
		}
	}



    public function visitor_log_demo(){
		if(!empty($this->session->userdata('id'))){
				$postdata=$this->input->post();
					$start_date = date("Y-m-d");
					$end_date = date("Y-m-d");
					$bio=0;
					$true = 0;
					//$option= "all";
					//$days_array = array();
					$new_array = array();
					$loginId = $this->session->userdata('login_id');
				/*	if($this->session->userdata('type')=="P"){
						$userCmp = $this->app->getUserCompany($loginId);
						if(isset($userCmp) && ($userCmp['left_date']=="" || $userCmp['left_date']>time())){
							$loginId = $userCmp['business_id'];
						}
					}*/
										
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
					$this->load->view('hostel/visitor_log_demo',$data);
		
		}
		else{
			redirect('user-login');
		}
	}

	
	public function add_studentdevice(){
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
			                 // $data=$this->db->insert('activity',$actdata);
				$this->session->set_flashdata('msg','New Device Added!');
				redirect('student_device');
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
	
	
	
	
	
	
// 		public function add_newsection(){
// 		if(!empty($this->session->userdata('id'))){
// 			$postdata=$this->input->post();
// 			$postdata=array(
// 			  //  'class_id'=>$postdata['class'],
// 			  'dep_id'=>$postdata['dept'],
// 			  'session_id'=>$postdata['session'],
// 				'name'=>$postdata['name'],
// 				'bid'=>$postdata['bid'],
// 				'date_time'=>time()
// 			);
// 			$data=$this->db->insert('S_section',$postdata);
// 			if($data > 0){
			   
			
// 				$this->session->set_flashdata('msg','New Section Added!');
// 				redirect('add_s_section');
// 			}
// 		}
// 		else{
// 			redirect('user-login');
// 		}
// 	}


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


// 	public function edit_S_Section(){
// 		if(!empty($this->session->userdata('id'))){
// 			$check=$_REQUEST;
// 			print_r($check);
// 			echo $name = $_POST['name'];
// 			echo $id = $_POST['id'];
// 			$data = array(
// 				'name' => $name
			
// 			);
// 			print_r($data);
// 			$this->db->where('id',$id);
// 			$res = $this->db->update('S_section',$data);
// 			echo $res;
// 			return($res);
// 		}
// 		else{
// 			redirect('user-login');
// 		}
// 	}
	
	
	
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


// public function add_newsession(){
// 		if(!empty($this->session->userdata('id'))){
// 			$postdata=$this->input->post();
// 			$postdata=array(
// 			    'dep_id'=>$postdata['dept'],
// 				'session_name'=>$postdata['session'],
// 				'bid'=>$postdata['bid'],
// 				'date_time'=>time()
// 			);
// 			$data=$this->db->insert('S_Session',$postdata);
// 			if($data > 0){
			   
			
// 				$this->session->set_flashdata('msg','New Session Added!');
// 				redirect('add_session');
// 			}
// 		}
// 		else{
// 			redirect('user-login');
// 		}
// 	}


// public function delete_S_session(){
// 		if (!empty($this->session->userdata('id'))) {
// 			$id = $this->input->post('id');
// 			$res= $this->web->delete_S_session($id);
// 			if ($res) {
			    
// 				echo $id;
// 				return($id);
// 			}
// 		} else {
// 			redirect('user-login');
// 		}
		
// 	}



/// nur  



// New Controller by Nursid 
public function students_monthly_report_new(){
	if(!empty($this->session->userdata('id'))){
		
		$postdata=$this->input->post();
			$start_date = date("Y-m-d");
			$end_date = date("Y-m-d");
			$dept=0;
			$session=0;
			$section=0;
			$semester=0; // Adding semester parameter
			$subject=0; // Adding subject parameter
			$true = 0;
			$days_array = array();
			$new_array = array();
			$subject_wise_data = array(); // Added for subject-wise attendance
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
			$semester = isset($postdata['semester']) ? $postdata['semester'] : 0; // Get semester if available
			$subject = isset($postdata['subject']) ? $postdata['subject'] : 0; // Get subject if available
			
			$true= 1;
			
			$data2 = array();
			// get all student by branches -> batch -> semester - > section 
			$users_data = $this->web->getSchoolStudentListbysection_new($loginId,$dept,$semester,$section);
			
			// Calculate date range info
			$date1 = date_create(date("Y-m-d",strtotime($start_date)));
			$date2 = date_create(date("Y-m-d",strtotime($end_date)));
			$diff = date_diff($date1,$date2);
			$num_month = $diff->format("%a");
			$num_month++;
			if($num_month > 31){
				$num_month = 31;
			}
			
			// First collect all days that have periods assigned
			$period_days = array();
			$sequential_index = 1; // Start numbering from 1
			
			for($d=0; $d<$num_month; $d++){
				$new_start_time = strtotime(date("d-m-Y 00:00:00",strtotime($start_date))." +".$d." days");
				$day_number = date('w', $new_start_time);
				$calendar_day = date("d", $new_start_time);
				$day_name = date("l", $new_start_time);

				
				
				// Check if this day has a period
				$getperiodTime = $this->web->getperiodTime($subject, $day_number);
				if(!empty($getperiodTime)) {
					// Get teacher name
					$teacher_name = '';
					if(!empty($getperiodTime->teacher)) {
						$teacher_name = $this->web->getTeacherNameById($getperiodTime->teacher, $loginId);
					}
					
					$period_days[] = array(
						'sequential_day' => $sequential_index++, // Use numbered sequence
						'original_index' => $d,
						'calendar_day' => $calendar_day,
						'day_name' => $day_name,
						'timestamp' => $new_start_time,
						'day_number' => $day_number,
						'period_info' => $getperiodTime,
						'teacher_name' => $teacher_name
					);
					
					// Add to days array for the view (sequential numbering)
					$days_array[] = $sequential_index - 1; // Sequential day number
				}
			}
	  
			if(!empty($users_data)){
				foreach($users_data as $user){
					$months_array = array();
					
					foreach($period_days as $day_info){
						$d = $day_info['original_index'];
						$new_start_time = $day_info['timestamp'];
						$day_number = $day_info['day_number'];
						$getperiodTime = $day_info['period_info'];
						
						$start_time_period = $getperiodTime->start_time;
						$end_time_period = $getperiodTime->end_time;
						
						$start_time_stamp = strtotime(date("Y-m-d", strtotime($start_date)) . " " . $start_time_period . " +".$d." days");
						$end_time_stamp = strtotime(date("Y-m-d", strtotime($start_date)) . " " . $end_time_period . " +".$d." days");
						
						

						$holiday_name = $this->web->getHolidayByBusinessId_new($loginId, $new_start_time);
        
						if ($holiday_name) {
							$data = array(
								'status' => 'Holiday: ' . $holiday_name,
								'time' => ''
							);
						} else {
							$dayUserAt = $this->web->getStudentAttendanceReportByDate($start_time_stamp, $end_time_stamp, $user->id, $loginId);
							
							$data = array(
								'status' => 'A',
								'time' => ''
							);
							
							if(!empty($dayUserAt)) {
								$data = array(
									'status' => 'P',
									'time' => date('H:i', $dayUserAt[0]->time)
								);
							}
						}
						
						$months_array[] = array(
							'date' => $day_info['sequential_day'], // Use sequential day number
							'calendar_day' => $day_info['calendar_day'], // Keep original calendar day too
							'day' => $day_info['day_name'],
							'teacher_name' => $day_info['teacher_name'],
							'data' => $data
						);
					}
					
					if(count($months_array) > 0){
						$new_array[] = array(
							'user_id' => $user->id,
							'name' => $user->name,
							'data' => $months_array
						);
					}
				}
			}
			
			// Get branch, batch, and semester information
			$branch_info = $this->web->getBusinessDepByUserId($dept);
			$branch_name = !empty($branch_info) ? $branch_info[0]->name : '';
			
			$batch_info = $this->web->getbatchById($session);
			$batch_name = !empty($batch_info) ? $batch_info[0]->session_name : '';
			
			$semester_info = $this->web->getSemesterById($postdata['semester']);
			$semester_name = !empty($semester_info) ? $semester_info[0]->semestar_name : '';
			
			$section_info = $this->web->getsectionById($section);
			$section_name = !empty($section_info) ? $section_info[0]->name : '';
			
			// Get subject information if subject is selected
			$subject_name = '';
			if($subject > 0) {
				$subject_info = $this->web->getsubjectnamebyid($subject);
				$subject_name = !empty($subject_info) ? $subject_info->name : '';
			}
			
			$data = array(
				'start_date' => $start_date,
				'end_date' => $end_date,
				'dept' => $dept,
				'session' => $session,
				'section' => $section,
				'semester' => $semester,
				'subject' => $subject,
				'load' => $true,
				'report' => $new_array,
				'days' => $days_array,
				'period_days' => $period_days,
				'branch_name' => $branch_name,
				'batch_name' => $batch_name,
				'semester_name' => $semester_name,
				'section_name' => $section_name,
				'subject_name' => $subject_name,
				'cmp_name' => $cmpName['name']
			);
			
			// Count days with time periods
			$days_with_time_periods = count($period_days);
			$data['days_with_time_periods'] = $days_with_time_periods;
			
			$this->load->view('student/students_monthly_report',$data);
		}
		else{
			redirect('user-login');
		}
	}
}
public function add_newtimetable(){
	if(!empty($this->session->userdata('id'))){
		$postdata=$this->input->post();

		// Handle multi-select arrays by converting to comma-separated strings
		$branch_ids = is_array($postdata['branch_id']) ? implode(',', $postdata['branch_id']) : $postdata['branch_id'];
		$semester_ids = is_array($postdata['semester_id']) ? implode(',', $postdata['semester_id']) : $postdata['semester_id'];
		$section_ids = is_array($postdata['section_id']) ? implode(',', $postdata['section_id']) : $postdata['section_id'];
		
		$timetable_data=array(
			'name'=>$postdata['name'],
			'start_date'=>strtotime($postdata['start']),
			'end_date'=>strtotime($postdata['end']),
			'dept'=>$branch_ids,
			'session'=>$postdata['batch_id'], // batch_id remains single value
			'semester_id'=>$semester_ids,
			'section'=>$section_ids,
			'bid'=>$postdata['bid']
		);


		$data=$this->db->insert('time_table_name',$timetable_data);
		$timetable_id = $this->db->insert_id(); // Get the ID of the newly inserted timetable
		
		if($data > 0){
			// Get all periods for this bid
			$periods = $this->web->getallperiodbyid($timetable_data['bid']);
			
			// Add entries for all days and periods
			if(!empty($periods)){
				foreach($periods as $period){
					// Add for all days (0=Sunday, 1=Monday, 2=Tuesday, etc.)
					for($day = 0; $day <= 6; $day++){
						$period_data = array(
							'bid' => $timetable_data['bid'],
							'days' => $day,
							'period' => $period->id,
							'subject' => '',
							'class_room' => '',
							'teacher' => '',
							'timetable_id' => $timetable_id
						);
						$this->db->insert('time_table', $period_data);
					}
				}
			}
			
			$this->session->set_flashdata('msg','New Time Table Added!');
			redirect('time_table');
		}
	}
	else{
		redirect('user-login');
	}
}

public function get_semester_by_branch() {
    if(!empty($this->session->userdata('id'))) {
        $branch_id = $this->input->post('branch_id');
        $bid = $this->session->userdata('login_id');
        
        $semesters = $this->web->getallSemesters($bid);
        $filtered_semesters = array();
        
        foreach($semesters as $semester) {
            $dep_ids = explode(',', $semester->dep_id);
            if(in_array($branch_id, $dep_ids)) {
                $filtered_semesters[] = $semester;
            }
        }
        
        echo json_encode($filtered_semesters);
    }
}

public function get_sections_by_batch_semester() {
    if(!empty($this->session->userdata('id'))) {
        $batch_id = $this->input->post('batch_id');
        $semester_id = $this->input->post('semester_id');
        $bid = $this->session->userdata('login_id');
        
        $sections = $this->web->getall_S_sectionbyid($bid);
        $filtered_sections = array();
        
        foreach($sections as $section) {
            if($section->session_id == $batch_id) {
                $filtered_sections[] = $section;
            }
        }
        
        echo json_encode($filtered_sections);
    }
}

public function time_table(){
	if(!empty($this->session->userdata('id'))){
		$this->load->view('student/add_timetable');
	}
	else{
		redirect('user-login');
	}
}

public function get_sections_by_session(){
	if(!empty($this->session->userdata('id'))){
		$session_id = $this->input->post('id');
		$result = $this->web->getSectionBySessionId($session_id);
		
		echo '<option value="" disabled selected>Select Section</option>';
		if(!empty($result)){
			foreach($result as $section):
				echo "<option value=".$section->id.">".$section->name."</option>";
			endforeach;
		} else {
			echo '<option value="" disabled>No sections found</option>';
		}
	}
	else{
		redirect('user-login');
	}
}

public function period_timetable($timetable_id = null) {

	
	if(!empty($this->session->userdata('id'))){
		if($timetable_id) {
			$data['timetable'] = $this->web->get_timetable_by_id($timetable_id);
			
			$data['timetable_entries'] = $this->web->get_timetable_entries($timetable_id);
			
			$data['teachers'] = $this->web->get_all_teachers($this->session->userdata('login_id'));

			
			$data['subjects'] = $this->web->get_all_subjects($this->session->userdata('login_id'));
			
			$this->load->view('student/period_timetable', $data);
		} else {
			redirect('time_table');
		}
	} else {
		redirect('user-login');
	}
}

public function save_timetable_entry() {
	if(!empty($this->session->userdata('id'))){
		$postdata = $this->input->post();
	
		// Check if entry already exists
		$existing = null;
		if(!empty($postdata['entry_id'])) {
			$existing = $this->db->where('id', $postdata['entry_id'])->get('time_table')->row();
		}

		$entry = array(
			'bid' => $this->session->userdata('login_id'),
			'timetable_id' => $postdata['timetable_id'],
			'days' => $postdata['days'],
			'period' => $postdata['period'],
			'subject' => $postdata['subject'],
			'class_room' => $postdata['class_room'],
			'teacher' => $postdata['teacher']
		);
		
		if($existing) {
			// Update existing entry
			$this->db->where('id', $existing->id);
			$result = $this->db->update('time_table', $entry);
		} else {
			// Insert new entry
			$result = $this->db->insert('time_table', $entry);
		}
		
		if($result) {
			echo json_encode(['status' => 'success']);
		} else {
			echo json_encode(['status' => 'error']);
		}
	} else {
		redirect('user-login');
	}
}

public function getallsubjectbyid() {
    if(!empty($this->session->userdata('id'))) {
        if ($this->input->is_ajax_request()) {
            $bid = $this->input->post('bid');
           
            $subjects = $this->web->getallsubjectbybranchid($bid);
            echo json_encode($subjects);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
        }
    } else {
        redirect('user-login');
    }
}

public function get_batch_by_id(){
	if(!empty($this->session->userdata('id'))){
		$id = $this->input->post('id');
		$batch = $this->web->getSessionById($id);
		echo json_encode($batch[0]);
	}
}


public function update_batch(){
	if(!empty($this->session->userdata('id'))){
		$postdata = $this->input->post();
		$id = $postdata['batch_id'];
		
		// Handle multiple branches
		$branches = '';
		if(isset($postdata['dept']) && is_array($postdata['dept'])) {
			$branches = implode(',', $postdata['dept']); 
		}
		
		// Update data in database
		$data = array(
			'dep_id' => $branches,
			'session_name' => $postdata['session']
		);
		
		$this->db->where('id', $id);
		$result = $this->db->update('S_Session', $data);
		
		if($result){
			$this->session->set_flashdata('msg', 'Batch Updated Successfully!');
		}
		redirect('add_batch');
	}
	else{
		redirect('user-login');
	}
}

public function add_newsession(){
	if(!empty($this->session->userdata('id'))){
		$postdata=$this->input->post();
		
		
		// Handle multiple branches
		$branches = '';
		if(isset($postdata['dept']) && is_array($postdata['dept'])) {
			$branches = implode(',', $postdata['dept']); 
		}
		
		
		// Create array with session data
		$postdata=array(
			'dep_id'=>$branches,
			'session_name'=>$postdata['session'], 
			'bid'=>$postdata['bid'],
			'date_time'=>time()
		);
		
		
		// Insert into database
		$data=$this->db->insert('S_Session',$postdata);
		if($data > 0){
			$this->session->set_flashdata('msg','New Session Added!');
			redirect('add_batch');
		}
	}
	else{
		redirect('user-login');
	}
}


public function editsession(){
	if(!empty($this->session->userdata('id'))){
		$check=$_REQUEST;
		print_r($check);
		
		$name = $_POST['name'];
		$id = $_POST['id'];
		$branches = '';
		
		if(isset($_POST['dept']) && is_array($_POST['dept'])) {
			$branches = implode(',', $_POST['dept']);
		}
		
		$data = array(
			'session_name' => $name,
			'dep_id' => $branches
		);
		
		print_r($data);
		$this->db->where('id',$id);
		$res = $this->db->update('S_Session',$data);
		echo $res;
		return($res);
	}
	else{
		redirect('user-login');
	}
}


public function delete_S_session(){
	if (!empty($this->session->userdata('id'))) {
		$id = $this->input->post('id');
		
		// Get current status
		$session = $this->web->getSessionById($id);
		$current_status = $session[0]->status;
		
		// Toggle status between 0 and 1
		$new_status = ($current_status == 1) ? 0 : 1;
		
		$res = $this->web->delete_S_session($id, $new_status);
		
		if ($res) {
			echo $id;
			return($id);
		}
	} else {
		redirect('user-login');
	}
}

public function add_newsection() {
    if (!empty($this->session->userdata('id'))) {
        $postdata = $this->input->post();

        // Decode the structured branch-semester data
        $structuredData = [];
        if (isset($postdata['structured_data']) && !empty($postdata['structured_data'])) {
            $structuredData = json_decode($postdata['structured_data'], true);
        }

        // Store section details (name, bid, etc.)
        $data = array(
            'name' => $postdata['name'],
            'bid' => $postdata['bid'],
            'date_time' => time()
        );

        // Insert into S_section
        $result = $this->db->insert('S_section', $data);

        if ($result) {
            $section_id = $this->db->insert_id(); // Get newly created section ID

            // Insert branch-semester combinations
            foreach ($structuredData as $branchId => $semesters) {
                foreach ($semesters as $semesterId) {
                    $this->db->insert('section_semesters', [
                        'section_id' => $section_id,
                        'branch_id' => $branchId,
                        'semester_id' => $semesterId
                    ]);
                }
            }

            $this->session->set_flashdata('msg', 'New Section Added!');
            redirect('add_s_section');
        } else {
            $this->session->set_flashdata('msg', 'Something went wrong.');
            redirect('add_s_section');
        }
    } else {
        redirect('user-login');
    }
}

	

public function edit_S_Section(){
	if(!empty($this->session->userdata('id'))){
		$id = $this->input->post('id');
		$name = $this->input->post('name');
		$structuredData = json_decode($this->input->post('structured_data'), true);
		
		// Update section name
		$data = array(
			'name' => $name
		);
		
		$this->db->where('id', $id);
		$res = $this->db->update('S_section', $data);
		
		if($res) {
			// Delete existing branch-semester relationships
			$this->db->where('section_id', $id);
			$this->db->delete('section_semesters');
			
			// Insert new branch-semester combinations
			foreach($structuredData as $branchId => $semesters) {
				foreach($semesters as $semesterId) {
					$this->db->insert('section_semesters', [
						'section_id' => $id,
						'branch_id' => $branchId,
						'semester_id' => $semesterId
					]);
				}
			}
		}
		
		echo $res;
		return($res);
	} else {
		redirect('user-login');
	}
}

public function add_semester(){
	if(!empty($this->session->userdata('id'))){
		$this->load->view('student/add_semester');
	}
	else{
		redirect('user-login');
	}
}

public function get_semester_by_id(){
	if(!empty($this->session->userdata('id'))){
		$id = $this->input->post('id');
		$semester = $this->web->getSemesterById($id);
		echo json_encode($semester[0]);
	}
}

public function update_semester(){
	if(!empty($this->session->userdata('id'))){
		$postdata = $this->input->post();
		$id = $postdata['semester_id'];
		
		// Update data in database
		$data = array(
			'session_id' => $postdata['session_id'],
			'semester_name' => $postdata['semester_name']
		);
		
		$this->db->where('id', $id);
		$result = $this->db->update('S_Semester', $data);
		
		if($result){
			$this->session->set_flashdata('msg', 'Semester Updated Successfully!');
		}
		redirect('add_semester');
	}
	else{
		redirect('user-login');
	}
}

public function add_newsemester(){
	if(!empty($this->session->userdata('id'))){
		$postdata = $this->input->post();
		
		$branches = '';
		if(isset($postdata['dept']) && is_array($postdata['dept'])) {
			$branches = implode(',', $postdata['dept']); 
		}
		// Create array with semester data
		$data = array(
			'semestar_name' => $postdata['semestar_name'],
			'bid' => $this->session->userdata('login_id'),
			'status' => 1,
			'year' => $postdata['year'],
			'dep_id' => $branches
		);
	
		// Insert into database
		$result = $this->db->insert('S_Semester', $data);
		
		if($result){
			$this->session->set_flashdata('msg', 'New Semester Added!');
			redirect('add_semester');
		}
	}
	else{
		redirect('user-login');
	}
}

public function update_newsemester(){
	if(!empty($this->session->userdata('id'))){
		$postdata = $this->input->post();
		
		$branches = '';
		if(isset($postdata['dept']) && is_array($postdata['dept'])) {
			$branches = implode(',', $postdata['dept']); 
		}

		// Create array with updated semester data
		$data = array(
			'semestar_name' => $postdata['semestar_name'],
			'year' => $postdata['year'],
			'dep_id' => $branches
		);

		// Update database
		$this->db->where('id', $postdata['id']);
		$result = $this->db->update('S_Semester', $data);
		
		if($result){
			$this->session->set_flashdata('msg', 'Semester Updated Successfully!');
			redirect('add_semester');
		}
	}
	else{
		redirect('user-login');
	}
}
public function delete_semester(){
	if(!empty($this->session->userdata('id'))){
		$id = $this->input->post('id');
		
		// Get current status
		$semester = $this->web->getSemesterById($id);
		$current_status = $semester[0]->status;
		
		// Toggle status between 0 and 1
		$new_status = ($current_status == 1) ? 0 : 1;
		
		$res = $this->web->delete_semester($id, $new_status);
		
		if($res){
			echo $id;
			return($id);
		}
	}
	else{
		redirect('user-login');
	}
}

public function get_batches_by_dept(){
	if(!empty($this->session->userdata('id'))){
		$dept_id = $this->input->post('dept_id');
		$bid = $this->session->userdata('login_id');
		
		$batches = $this->web->getBatchesByDeptId($dept_id, $bid);
		
		echo json_encode($batches);
	}
}

public function get_section_by_branch_semester() {
    $branchId = $this->input->post('branch_id');
    $semesterId = $this->input->post('semester_id');

    // Fetch sections based on branch and semester
    $sections = $this->web->getSectionsByBranchAndSemester($branchId, $semesterId);

    // Return the sections as a JSON response
    echo json_encode($sections);
}

public function get_batch_by_branch() {
    $branchId = $this->input->post('branch_id');

    // Fetch batches based on branch
    $batches = $this->web->getBatchesByDeptId($branchId, $this->session->userdata('login_id'));

    // Return the batches as a JSON response
    echo json_encode($batches);
}

public function update_subject() {
    if (!empty($this->session->userdata('id'))) {
        $postdata = $this->input->post();
        $this->db->where('id', $postdata['id']);
        $this->db->update('subject', [
            'name' => $postdata['name'],
            'Subject_code' => $postdata['subcode'],
            'dep_id' => $postdata['dept'],
        ]);
        $this->session->set_flashdata('msg', 'Subject Updated!');
        redirect('add_subject');
    } else {
        redirect('user-login');
    }
}

function import_subject()
		{
			if(!empty($this->session->userdata('id'))){
				
			
			$data=$this->web->import_subject();
			$this->load->view('student/add_subject',$data);
			
				
			}
			else{
				redirect('user-login');
			}
		  }
		  
		  
		  
	public function update_period() {
    if (!empty($this->session->userdata('id'))) {
        $postdata = $this->input->post();
        $this->db->where('id', $postdata['id']);
        $this->db->update('S_period', [
            'name' => $postdata['name'],
            'start_time' => $postdata['start_time'],
            'end_time' => $postdata['end_time'],
        ]);
        $this->session->set_flashdata('msg', 'Period Updated!');
        redirect('add_period');
    } else {
        redirect('user-login');
    }
}	  
		  
		
// new code 19/04/


public function student_dashboard_api() {

	if(!empty($this->session->userdata('id'))){
    // Validate request
    if (!$this->input->is_ajax_request()) {
        echo json_encode(['status' => 'error', 'message' => 'Direct access not allowed']);
        return;
    }

	$bid = $this->session->userdata('login_id');

    $action = $this->input->post('action');
    
	
    switch ($action) {
        case 'get_dashboard_data':
            $branch_id = $this->input->post('branch_id');
            $semester_id = $this->input->post('semester_id');
            $section_id = $this->input->post('section_id');
            	
            // Allow branch-only filtering
            if (!$branch_id) {
                echo json_encode(['status' => 'error', 'message' => 'Branch ID is required']);
                return;
            }
            
            // Get current date
            $current_date = date('Y-m-d');
            $start_time = strtotime(date('Y-m-d 00:00:00'));
            $end_time = strtotime(date('Y-m-d 23:59:59'));
            
            // Build where conditions for students
            $student_conditions = ['student.department' => $branch_id, 'student.status' => 1];
            if ($semester_id) {
                $student_conditions['student.semester'] = $semester_id;
            }
            if ($section_id) {
                $student_conditions['student.section'] = $section_id;
            }
            
            // Get all students in this section/branch
            $this->db->select('student.*');
            $this->db->from('student');
            $this->db->where($student_conditions);
            $query = $this->db->get();
            $students = $query->result();
            $total_students = count($students);
            
            // Get periods for the day
            $dayOfWeek = date('w'); // 0 (for Sunday) through 6 (for Saturday)
            $periods = $this->db->get_where('S_period', ['bid' => $bid, 'status' => 1])->result();
            
            // Get subjects for this branch and semester
            $subject_conditions = [
                'subject.bid' => $bid, 
                'subject.dep_id' => $branch_id, 
                'subject.status' => 1
            ];
            $this->db->select('subject.*');
            $this->db->from('subject');
            $this->db->where($subject_conditions);
            $query = $this->db->get();
            $subjects = $query->result();
            
            // Process student attendance
            $student_data = [];
            $present_count = 0;
            $absent_count = 0;
            
            foreach ($students as $student) {
                // Get attendance logs for this student today
                $this->db->select('*');
                $this->db->from('student_attendance');
                $this->db->where('student_id', $student->id);
                $this->db->where('bid', $bid);
                $this->db->where('status', 1);
                $this->db->where('time >=', $start_time);
                $this->db->where('time <=', $end_time);
                $this->db->order_by('time', 'ASC');
                $attendance_query = $this->db->get();
                $attendance_logs = $attendance_query->result();
                
                $student_attendance = [];
                $is_present = false;
                
                if (!empty($attendance_logs)) {
                    $is_present = true;
                    $present_count++;
                    
                    foreach ($attendance_logs as $log) {
                        $student_attendance[] = [
                            'time' => $log->time,
                            'formatted_time' => date('h:i A', $log->time),
                            'student_status' => $log->student_status
                        ];
                    }
                } else {
                    $absent_count++;
                }
                
                // Get class name
                $class_name = "";
                if (!empty($student->class_id)) {
                    $class_info = $this->db->get_where('class', ['id' => $student->class_id])->row();
                    if ($class_info) {
                        $class_name = $class_info->name;
                    }
                }
                
                // Get semester name
                $semester_name = $student->semester;
                
                // Get period attendance for this student
                $period_attendance = [];
                foreach ($periods as $period) {
                    $period_start = strtotime(date('Y-m-d ') . $period->start_time);
                    $period_end = strtotime(date('Y-m-d ') . $period->end_time);
                    
                    // Get subject for this period and day
                    $subject_name = "";
                    $subject_info = $this->web->getSubjectByPeriodAndDay($period->id, $dayOfWeek);
                    if ($subject_info) {
                        $subject_name = $subject_info->name;
                    }
                    
                    $is_present_in_period = false;
                    foreach ($attendance_logs as $log) {
                        $log_time = $log->time;
                        if ($log_time >= $period_start && $log_time <= $period_end) {
                            $is_present_in_period = true;
                            break;
                        }
                    }
                    
                    $period_attendance[] = [
                        'period_id' => $period->id,
                        'period_name' => $period->name,
                        'start_time' => $period->start_time,
                        'end_time' => $period->end_time,
                        'subject' => $subject_name,
                        'status' => $is_present_in_period ? 'P' : 'A'
                    ];
                }
                
                // Get roll number
                $roll_no = !empty($student->roll_no) ? $student->roll_no : $student->student_code;
                
                $student_data[] = [
                    'id' => $student->id,
                    'name' => $student->name,
                    'roll_no' => $roll_no,
                    'class' => $class_name,
                    'semester' => $semester_name,
                    'attendance_status' => $is_present ? 'P' : 'A',
                    'attendance_logs' => $student_attendance,
                    'period_attendance' => $period_attendance
                ];
            }
            
            // Get period headers for the UI
            $period_headers = [];
            foreach ($periods as $period) {
                $subject_info = $this->web->getSubjectByPeriodAndDay($period->id, $dayOfWeek);
                $subject_name = $subject_info ? $subject_info->name : "N/A";
                
                $period_headers[] = [
                    'id' => $period->id,
                    'name' => $period->name,
                    'time' => $period->start_time . '-' . $period->end_time,
                    'subject' => $subject_name
                ];
            }
            
            // Process subjects attendance data
            $subject_attendance = [];
            foreach ($subjects as $subject) {
                $subject_present = 0;
                $subject_absent = 0;
                
                // Count present students for this subject
                foreach ($student_data as $student) {
                    $found_in_period = false;
                    foreach ($student['period_attendance'] as $period) {
                        if ($period['subject'] == $subject->name && $period['status'] == 'P') {
                            $found_in_period = true;
                            break;
                        }
                    }
                    
                    if ($found_in_period) {
                        $subject_present++;
                    } else {
                        $subject_absent++;
                    }
                }
                
                $subject_attendance[] = [
                    'id' => $subject->id,
                    'name' => $subject->name,
                    // 'code' => $subject->subject_code ?? 'N/A',
                    'present' => $subject_present,
                    'absent' => $subject_absent
                ];
            }
            
            // Get class attendance data
            $class_attendance = [];
            $classes = $this->db->get_where('class', ['bid' => $branch_id, 'status' => 1])->result();
            
            foreach ($classes as $class) {
                $class_students_count = 0;
                $class_present_count = 0;
                
                foreach ($student_data as $student) {
                    if ($student['class'] == $class->name) {
                        $class_students_count++;
                        if ($student['attendance_status'] == 'P') {
                            $class_present_count++;
                        }
                    }
                }
                
                $percentage = $class_students_count > 0 ? round(($class_present_count / $class_students_count) * 100) : 0;
                
                $class_attendance[] = [
                    'id' => $class->id,
                    'name' => $class->name,
                    'total' => $class_students_count,
                    'present' => $class_present_count,
                    'absent' => $class_students_count - $class_present_count,
                    'percentage' => $percentage
                ];
            }
            
            // Summary data
            $summary = [
                'total_students' => $total_students,
                'present_students' => $present_count,
                'absent_students' => $absent_count,
                'attendance_date' => $current_date,
                'total_staff' => $this->web->getTotalStaff($branch_id),
                'total_branches' => $this->web->getTotalBranches($bid),
                'total_subjects' => $this->web->getTotalSubjects($branch_id)
            ];
            
            // Get attendance trend for last 7 days
            $days = [];
            $present_by_day = [];
            $absent_by_day = [];
            
            for ($i = 6; $i >= 0; $i--) {
                $day_date = date('Y-m-d', strtotime("-$i days"));
                $day_start = strtotime("$day_date 00:00:00");
                $day_end = strtotime("$day_date 23:59:59");
                $days[] = date('D', strtotime($day_date));
                
                $day_present = 0;
                $day_absent = 0;
                
                foreach ($students as $student) {
                    $this->db->select('*');
                    $this->db->from('student_attendance');
                    $this->db->where('student_id', $student->id);
                    $this->db->where('bid', $branch_id);
                    $this->db->where('status', 1);
                    $this->db->where('time >=', $day_start);
                    $this->db->where('time <=', $day_end);
                    $attendance_count = $this->db->count_all_results();
                    
                    if ($attendance_count > 0) {
                        $day_present++;
                    } else {
                        $day_absent++;
                    }
                }
                
                $present_by_day[] = $day_present;
                $absent_by_day[] = $day_absent;
            }
            
            // Get student counts by branch for the branch chart
            $branches_data = [];
            $all_branches = $this->web->getBusinessDepByBusinessId($bid);
            $total_branch_count = count($all_branches); // Count total branches
            
            foreach ($all_branches as $branch) {
                $this->db->where('department', $branch->id);
                $this->db->where('status', 1);
                $student_count = $this->db->count_all_results('student');
                
                $branches_data[] = [
                    'id' => $branch->id,
                    'name' => $branch->name,
                    'student_count' => $student_count
                ];
            }
            
            // Count total students across all branches
            $this->db->where('bid', $bid);
            $this->db->where('status', 1);
            $total_all_students = $this->db->count_all_results('student');
            
            // Count total staff
            $this->db->where('company', $bid);
            $this->db->where('deleted', 1);
            $total_staff_count = $this->db->count_all_results('login');
            
            // Count total subjects/courses
            $this->db->where('bid', $bid);
            $this->db->where('status', 1);
            $total_subject_count = $this->db->count_all_results('subject');
            
            // Update summary with correct counts
            $summary['total_branches'] = $total_branch_count;
            $summary['total_staff'] = $total_staff_count;
            $summary['total_subjects'] = $total_subject_count;
            $summary['total_students'] = $total_all_students; // Total students across all branches
            
            // Prepare response data
            $data = [
                'summary' => $summary,
                'students' => $student_data,
                'period_headers' => $period_headers,
                'days' => $days,
                'present_by_day' => $present_by_day,
                'absent_by_day' => $absent_by_day,
                'class_attendance' => $class_attendance,
                'subject_attendance' => $subject_attendance,
                'branches_data' => $branches_data,
                'overall' => [
                    'present' => $present_count,
                    'absent' => $absent_count,
                    'total' => $total_students
                ]
            ];

            echo json_encode(['status' => 'success', 'data' => $data]);
            break;
        
        default:
            echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
            break;
    }
}
}

public function update_day_timetable() {
	if(!empty($this->session->userdata('id'))){
		// Get POST data - expecting JSON array of entries
		$entries = json_decode($this->input->post('entries'), true);
		$timetable_id = $this->input->post('timetable_id');
		$day = $this->input->post('day');
		
		// Validate input
		if(empty($entries) || !is_array($entries) || empty($timetable_id) || !isset($day)) {
			echo json_encode([
				'status' => 'error', 
				'message' => 'Invalid input data'
			]);
			return;
		}
		
		// Process each entry
		$success_count = 0;
		$error_count = 0;
		$bid = $this->session->userdata('login_id');
		
		foreach($entries as $entry_data) {
			// Validate entry has required fields
			if(empty($entry_data['entry_id'])) {
				$error_count++;
				continue;
			}
			
			// Prepare data for update
			$entry = array(
				'bid' => $bid,
				'timetable_id' => $timetable_id,
				'days' => $day
			);
			
			// Add optional fields if present
			if(isset($entry_data['subject']) && !empty($entry_data['subject'])) {
				$entry['subject'] = $entry_data['subject'];
			}
			
			if(isset($entry_data['teacher']) && !empty($entry_data['teacher'])) {
				$entry['teacher'] = $entry_data['teacher'];
			}
			
			if(isset($entry_data['class_room']) && !empty($entry_data['class_room'])) {
				$entry['class_room'] = $entry_data['class_room'];
			}
			
			// Update entry
			$this->db->where('id', $entry_data['entry_id']);
			$result = $this->db->update('time_table', $entry);
			
			if($result) {
				$success_count++;
			} else {
				$error_count++;
			}
		}
		
		// Return results
		echo json_encode([
			'status' => 'success',
			'message' => "Updated $success_count entries successfully. $error_count entries failed.",
			'success_count' => $success_count,
			'error_count' => $error_count
		]);
	} else {
		redirect('user-login');
	}
}

public function delete_S_Table(){
		if (!empty($this->session->userdata('id'))) {
			$id = $this->input->post('id');
			$res= $this->web->delete_S_Table($id);
			if ($res) {
			    
				echo $id;
				return($id);
			}
		} else {
			redirect('user-login');
		}
		
	}




public function add_s_holiday(){
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
			                 // $data=$this->db->insert('activity',$actdata);
				$this->session->set_flashdata('msg','New Holiday Added!');
				redirect('School_holiday');
			}
		}
		else{
			redirect('user-login');
		}
	}






public function School_holiday(){
	if(!empty($this->session->userdata('id'))){
		$this->load->view('student/holidays_list');
	}
	else{
		redirect('user-login');
	}
}
public function add_holiday_new() {
    if(!empty($this->session->userdata('id'))) {
        $postdata = $this->input->post();
        
        // Extract data from POST
        $name = $postdata['name'];
        $business_id = $postdata['bid'];
        $dates = explode(', ', $postdata['h_dates']);
        
        // Prepare data for insertion
        $insert_data = array();
        foreach ($dates as $date_str) {
            // Convert date to Unix timestamp
            $date_timestamp = strtotime($date_str);
            
            $insert_data[] = array(
                'business_id' => $business_id,
                'name' => $name,
                'date' => $date_timestamp,
                'status' => 1 // Assuming default status is 1
            );
        }
        
        // Insert into database
        if (!empty($insert_data)) {
            $this->db->insert_batch('holiday', $insert_data);
        }
        
        // Redirect or load view as needed
        $this->load->view('student/holidays_list');
    } else {
        redirect('user-login');
    }
}


// update by Nursid 21-05-2025
public function get_assigned_class_by_teacher() {
    header('Content-Type: application/json');
    // $json_input = json_decode(file_get_contents('php://input'), true);

	$postdata = $this->input->post();
        
	// Extract data from POST
	$teacher_mobile = $postdata['teacher_mobile'];
	


    $teacher_row = $this->db->query("SELECT id, company FROM login WHERE mobile = ?", [$teacher_mobile])->row();
    if (!$teacher_row) {
        echo json_encode(['status' => 'error', 'message' => 'Teacher not found']);
        return;
    }
    
  
	
    $teacher_id = $teacher_row->id;
    
    

    $assigned_classes = $this->web->getAllAssignedClassesByTeacher($teacher_id);
    if (!$assigned_classes) {
        echo json_encode(['status' => 'success', 'count' => 0, 'data' => [], 'message' => 'No assigned classes found for this teacher']);
        return;
    }
    

    $days_map = [
        0 => 'sunday',
        1 => 'monday',
        2 => 'tuesday',
        3 => 'wednesday',
        4 => 'thursday',
        5 => 'friday',
        6 => 'saturday'
    ];

    $result = [];
    foreach ($assigned_classes as $class) {
        $class_arr = (array)$class;
        $day_name = isset($days_map[$class->days]) ? $days_map[$class->days] : $class->days;
        $period_time = $class->start_time . ' - ' . $class->end_time;
        $subject_display = $class->subject_name ? $class->subject_name : '';

		$semester = $this->web->getSemesterById($class->semester_id);
		$semester_name =$semester[0]->semestar_name;


		$department = $this->web->getBusinessDepByUserId($class->dept);
		$department_name =$department[0]->name;
		
		$class_room = $this->web->getclassnamebyid($class->class_room);
		$class_name =$class_room[0]->name;
		

        $result[] = array_merge(
            $class_arr,
            [
                'day' => $day_name,
                'period' => $period_time,
                'subject' => $subject_display,
				'semester_name' => $semester_name,
				'dept_name' => $department_name,
				'class_room' => $class_name
            ]
        );
    }

    echo json_encode([
        'status' => 'success',
        'data' => $result
    ]);
}

public function getteacher_class(){
	if(!empty($this->session->userdata('id'))){
		if ($this->session->userdata()['type'] == 'P') {
			$loginId = $this->session->userdata('empCompany');
		} else {
			$loginId = $this->web->session->userdata('login_id');
		}
		
		// Get all teachers with login details
		$teachers_data = $this->web->getSchoolTeachersList_with_login($loginId);
		
		$data = array(
			'teachers' => $teachers_data
		);
		
		$this->load->view('student/getteacher_class', $data);
	}
	else{
		redirect('user-login');
	}
}


// 28 - 05-2025 by Nursid 



public function teachers_attendance_list(){
	if(!empty($this->session->userdata('id'))){
		
		$postdata = $this->input->post();
		$start_date = date("Y-m-d");
		$true = 0;
		$action = "active";
		
		if ($this->session->userdata()['type'] == 'P') {
			$loginId = $this->session->userdata('empCompany');
			$role = $this->web->getRollbyid($this->web->session->userdata('login_id'), $loginId);
		} else {
			$loginId = $this->web->session->userdata('login_id');
		}
		
		$cmpName = $this->web->getBusinessById($loginId);
		
		if(isset($postdata['start_date'])){
			$start_date = $postdata['start_date'];
			$true = 1;
			$action = isset($postdata['action']) ? $postdata['action'] : 'active';
		}
		
		// Use the optimized method
		$report_result = $this->web->getTeachersAttendanceListOptimized($loginId, $start_date, $action);
		
		$data = array(
			'start_date' => $start_date,	
			'load' => $true,
			'report' => $report_result['teachers'],	
			'totalAbsent' => $report_result['totalAbsent'],
			'totalPresent' => $report_result['totalPresent'],
			'totalActive' => $report_result['totalActive']
		);
		
		$this->load->view('student/teachers_attendance_list', $data);
	}
	else{
		redirect('user-login');
	}
}

public function teachers_monthly_report(){
		if(!empty($this->session->userdata('id'))){
			
			$postdata = $this->input->post();
			$start_date = date("Y-m-d");
			$end_date = date("Y-m-d");
			$true = 0;
			
			if ($this->session->userdata()['type'] == 'P') {
				$loginId = $this->session->userdata('empCompany');
				$role = $this->web->getRollbyid($this->web->session->userdata('login_id'), $loginId);
			} else {
				$loginId = $this->web->session->userdata('login_id');
			}
			
			$cmpName = $this->web->getBusinessById($loginId);
			
			if(isset($postdata['start_date']) && isset($postdata['end_date'])){
				$start_date = $postdata['start_date'];
				$end_date = $postdata['end_date'];
				$true = 1;
				
				// Use the optimized method
				$report_result = $this->web->getTeachersMonthlyReportOptimized($loginId, $start_date, $end_date);
				
				$data = array(
					'start_date' => $start_date,
					'end_date' => $end_date,
					'load' => $true,
					'report' => $report_result['teachers'],
					'period_days' => $report_result['period_days'],
					'cmp_name' => $cmpName['name']
				);
				
				$this->load->view('student/teachers_monthly_report', $data);
			} else {
				$data = array(
					'start_date' => $start_date,
					'end_date' => $end_date,
					'load' => false,
					'report' => array(),
					'period_days' => array(),
					'cmp_name' => $cmpName['name']
				);
				$this->load->view('student/teachers_monthly_report', $data);
			}
		} else {
			redirect('user-login');
		}
	}


/// 14-07-2025

public function teachers_attendance_list_api(){
		if(!empty($this->session->userdata('id'))){
			$postdata = $this->input->post();
			$bid = $this->session->userdata('login_id');
			
			$date = $postdata['date'];
			$action = isset($postdata['action']) ? $postdata['action'] : 'active';
			
			$result = $this->web->getTeachersAttendanceListOptimized($bid, $date, $action);
			
			echo json_encode([
				'status' => 'success',
				'data' => $result
			]);
		} else {
			echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
		}
	}

	// Multi-select methods for timetable functionality
	public function get_batches_by_multiple_dept(){
		if(!empty($this->session->userdata('id'))){
			$dept_ids = $this->input->post('dept_ids');
			$bid = $this->session->userdata('login_id');
			
			if(empty($dept_ids) || !is_array($dept_ids)) {
				echo json_encode([]);
				return;
			}
			
			$batches = $this->web->getBatchesByMultipleDeptIds($dept_ids, $bid);
			
			echo json_encode($batches);
		}
	}

	public function get_semester_by_multiple_branch() {
		if(!empty($this->session->userdata('id'))) {
			$branch_ids = $this->input->post('branch_ids');
			$bid = $this->session->userdata('login_id');
			
			if(empty($branch_ids) || !is_array($branch_ids)) {
				echo json_encode([]);
				return;
			}
			
			$semesters = $this->web->getSemestersByMultipleBranches($branch_ids, $bid);
			
			echo json_encode($semesters);
		}
	}

	public function get_section_by_multiple_branch_semester() {
		if(!empty($this->session->userdata('id'))) {
			$branch_ids = $this->input->post('branch_ids');
			$semester_ids = $this->input->post('semester_ids');
			$bid = $this->session->userdata('login_id');
			
			if(empty($branch_ids) || !is_array($branch_ids)) {
				echo json_encode([]);
				return;
			}
			
			$sections = $this->web->getSectionsByMultipleBranchesAndSemesters($branch_ids, $semester_ids, $bid);
			
			echo json_encode($sections);
		}
	}





	

	public function promote_student()
{
    $student_id = $this->input->post('student_id');

    // Get student info
    $student = $this->web->getStudentById($student_id);

    if (!$student) {
        echo json_encode(['success' => false, 'msg' => 'Student not found']);
        return;
    }

    $current_sem = $student->semester;
    $department = $student->department;
    $max_semester = 6;   // You can also fetch dynamically if stored

    // If NOT last semester → Promote
    if ($current_sem < $max_semester) {

        $new_semester = $current_sem + 1;

        // Find new section from mapping table
        $newSection = $this->web->getSectionByDeptAndSemester($department, $new_semester);

        $updateData = [
            'semester' => $new_semester,
            // 'section'  => $newSection ? $newSection->section_id : $student->section
        ];

        $updated = $this->web->updateStudent($student_id, $updateData);

        if ($updated) {
            echo json_encode([
                'success' => true, 
                'msg' => "Student promoted to Semester $new_semester"
            ]);
        }

    } else {

        // Last Semester → Passout
        $updated = $this->web->updateStudent($student_id, ['status' => 'Passout']);

        if ($updated) {
            echo json_encode([
                'success' => true, 
                'msg' => 'Student successfully Passout'
            ]);
        }
    }
}


public function update_student_promotion()
{
    $id = $this->input->post('student_id');
    $semester = $this->input->post('semester');
    $section = $this->input->post('section');

    if ($semester == "passout") {
		$year = date("Y"); // current year
        $today = date("Y-m-d");

        $this->db->where('id', $id)->update('student', [
			'semester' => null,   // optional
            'section' => null,   // optional
			'is_passout' => 1,
            'passout_year' => date("Y"),
            'passout_date' => date("Y-m-d"),
            // status ko 1 hi rehne do so that cancel aur passout me fark rahe
            'status' => 1 
        ]);
        echo json_encode(['msg' => 'Student Successfully Passout']);
        return;
    }

    // Update Regular Promotion
    $this->db->where('id', $id)->update('student', [
        'semester' => $semester,
        'section'  => $section
    ]);

    echo json_encode(['msg' => 'Student Promoted Successfully']);
}

public function bulk_update_student_promotion()
{
    $ids = $this->input->post('student_ids');
    $semester = $this->input->post('semester');
    $section = $this->input->post('section');

    if ($semester == "passout") {

		$year = date("Y"); // current year
        $today = date("Y-m-d");

        $data = [
            'semester' => null,   // optional
            'section' => null,   // optional
			'is_passout' => 1,
            'passout_year' => date("Y"),
            'passout_date' => date("Y-m-d"),
            // status ko 1 hi rehne do so that cancel aur passout me fark rahe
            'status' => 1 
        ];

        $this->db->where_in('id', $ids)->update('student', $data);


        // $this->db->where_in('id', $ids)->update('student', [
        //     'status' => 'Passout'
        // ]);
        echo json_encode(['msg' => 'All Students Passed Out']);
        return;
    }

    $this->db->where_in('id', $ids)->update('student', [
        'semester' => $semester,
        'section' => $section
    ]);

    echo json_encode(['msg' => 'Students Bulk Promoted Successfully']);
}



///new code visitor arpit 

public function Assign_secttion_device(){
		if(!empty($this->session->userdata('id'))){
			$postdata=$this->input->post();
		//	$section= $_POST['section'];
		//	$shiftarray= implode(",",$shift);
			$postdata = array(
            'bid'=>$postdata['bid'],
            'device_id'=>$postdata['device_id'],
            'from_date'=>strtotime(date("d-m-Y 00:00:00",strtotime($postdata['from_date']))),
             'section_id'=>$postdata['section']
	  );
			$data=$this->db->insert('assign_section',$postdata);
			if($data > 0){
			
				redirect('User/device_list');
			}
		}
		else{
			redirect('user-login');
		}
	}

public function access_report(){
		if(!empty($this->session->userdata('id'))){
				$postdata=$this->input->get();
					$start_date = date("Y-m-d");
					$end_date = date("Y-m-d");
					$bio=0;
						$event_name=0;
					$true = 0;
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
			
					if(isset($postdata['start_date']) && isset($postdata['end_date'])){
						$start_date = $postdata['start_date'];
						$end_date = $postdata['end_date'];
						$bio = $postdata['bio'];
						$event_name = $postdata['event_name'];
						$true= 1;
					}

					$this->load->library('pagination');
				$start_time = strtotime($start_date . ' 00:00:00');
				$end_time   = strtotime($end_date . ' 23:59:59');
	
				$config['base_url'] = base_url('User/access_report');
				$config['total_rows'] = $this->web->countVisitorLogs($start_time, $end_time, $bio, $event_name);
				$config['per_page'] = 50;
				$config['uri_segment'] = 3;
				$config['reuse_query_string'] = TRUE; 
				$config['full_tag_open']    = '<ul class="pagination pagination-sm m-0 float-right">';
				$config['full_tag_close']   = '</ul>';
				$config['first_link']       = '«';
				$config['first_tag_open']   = '<li class="page-item">';
				$config['first_tag_close']  = '</li>';
				$config['last_link']        = '»';
				$config['last_tag_open']    = '<li class="page-item">';
				$config['last_tag_close']   = '</li>';
				$config['next_link']        = 'Next';
				$config['next_tag_open']    = '<li class="page-item">';
				$config['next_tag_close']   = '</li>';
				$config['prev_link']        = 'Prev';
				$config['prev_tag_open']    = '<li class="page-item">';
				$config['prev_tag_close']   = '</li>';
				$config['cur_tag_open']     = '<li class="page-item active"><span class="page-link">';
				$config['cur_tag_close']    = '</span></li>';
				$config['num_tag_open']     = '<li class="page-item">';
				$config['num_tag_close']    = '</li>';
				$config['attributes']       = ['class' => 'page-link'];
				


				$this->pagination->initialize($config);

				$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
			
				$data['logs'] = $this->web->getvisitoraccessbyevent(
					$start_time,
					$end_time,
					$bio,
					$event_name,
					$config['per_page'],
					$page,
					$loginId
				);

				$data['pagination'] = $this->pagination->create_links();
									
					
				$data['start_date'] = $start_date;
				$data['end_date']   = $end_date;
				$data['bio']        = $bio;
				$data['event_name'] = $event_name;
				$data['load']       = $true;
				$data['offset']       = $page;
					
					$this->load->view('attendance/access_report2',$data);
		
		}
		else{
			redirect('user-login');
		}
	}
	
	public function export_access_report()
{
    $start_date = $this->input->get('start_date');
    $end_date   = $this->input->get('end_date');
    $bio        = $this->input->get('bio');
    $event_name = $this->input->get('event_name');

    $loginId = $this->session->userdata('login_id');

    $start_time = strtotime($start_date . ' 00:00:00');
    $end_time   = strtotime($end_date . ' 23:59:59');

    $logs = $this->web->getvisitoraccessbyevent_export(
        $start_time,
        $end_time,
        $bio,
        $event_name,
        $loginId
    );

    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=Access_Report.xls");

    echo "S.No\tEmp Id\tName\tMobile\tFather\tDesignation\tDevice\tSection\tTime\tLat/Long\tLocation\n";

    $count = 1;

    foreach ($logs as $row) {
        echo $count++ . "\t";
        echo $row->user_id . "\t";
        echo $row->name . "\t";
        echo $row->mobile . "\t";
        echo $row->father_name . "\t";
        echo $row->designation . "\t";
        echo $row->device_name . "\t";
        echo $row->event_name . "\t";
        echo date('d-M h:i A', $row->io_time) . "\t";
        echo $row->latitude . "," . $row->longitude . "\t";
        echo $row->location . "\n";
    }

    exit;
}





}

?>
