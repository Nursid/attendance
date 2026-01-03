 <?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Kolkata');
class Api extends CI_Controller {
	function __construct(){
        parent::__construct();
		$this->load->database();
		$this->load->model('Api_Model','app');
		$this->load->helper('url');

	}
	public function sample($data){
	   // print_r($data);
	$string = explode('/', $data);
	 $res = $this->app->getIdByMid($string['5'])['id'];
		return($res);
	}
	public function login()
	{
		$data=json_decode(file_get_contents("php://input"));
		$mobile=$data->checkon->mobile;
		
		$token=$data->checkon->token;
		if(key($data)=="checkon"){
			$check=$this->app->checkMobile($mobile);
		
			
			if(empty($check)){
				//---------------CREATE MID
				$omid = $this->app->getMaxMid()['m_id'];
				$temp_ = "MID";
				if($omid == ''){
					$nmid = $temp_.'0000';
				}else{
					$str1 = substr($omid,3);
					$str1 = $str1 + 1;
					$str2 = str_pad($str1 , 4 , 0 , STR_PAD_LEFT);
					$nmid = $temp_.$str2;
				}
				$otp=rand(1000,9999);
				$saveData=array(
					'mobile'=>$mobile,
					'firebassid'=>$token,
					'otp'=>$otp,
					'm_id'=>$nmid,
				);
				$msg="Your Otp verfication no is:$otp";
				$this->sendsms($mobile,$msg);
				$checkData=$this->app->AddUser($saveData);
				$id = $this->db->insert_id();
				if($checkData > 0){
					$response=array('msg'=>'OTP Send Successfully!');
					echo $response= json_encode(array('checkon'=>$response,'hash'=>$str1,"str2"=>$str2));
				}
			}
			else{
			    
			    
			    		$check=$this->app->checkMobile($mobile);
			    	//	print_r($check);
			    		 $usergoup=$check['user_group'];
			    		if($usergoup==1)
			    		{
			    		    	//print_r($check);
			    	 $chec=$check['id'];
			    	 
			    	$status=$this->app->userwebcheck($chec);
			    //print_r($status);
			    if($status['status']==1)
			    {
			      $otp=rand(1000,9999);
				$data=array(
					'otp'=>$otp,
					'firebassid'=>$token
				);
				$msg="Your Otp verfication no is:$otp";
				$this->sendsms($mobile,$msg);
				$this->db->where('mobile',$mobile);
				$update=$this->db->update('login',$data);
				if($update > 0){
					$response=array('msg'=>'OTP Send Successfully!');
					echo $response= json_encode(array('checkon'=>$response,));
				}   
			    }
			    else
			    {
			        	$response=array('msg'=>'Not A Valid Id!');
					echo $response= json_encode(array('checkon'=>$response,));
			    }
			    		    
			    		}
			    		else
			    		{
			    		    	$otp=rand(1000,9999);
			    		    $data=array(
					'otp'=>$otp,
					'firebassid'=>$token
				);
				$msg="Your Otp verfication no is:$otp";
				$this->sendsms($mobile,$msg);
				$this->db->where('mobile',$mobile);
				$update=$this->db->update('login',$data);
				if($update > 0){
					$response=array('msg'=>'OTP Send Successfully!');
					echo $response= json_encode(array('checkon'=>$response,));
				}   
			    		    
			    		}
			    
			    
			   
			}
		}
	}
	public function verifyOtp(){
		$data=json_decode(file_get_contents("php://input"));
		$mobile=$data->checkon->mobile;
		$otp=$data->checkon->otp;
		if(key($data)=="checkon"){
			$data=$this->app->checkotp($mobile,$otp);
			if(!empty($data)){
				$value=array(
					'otp'=>0
				);
				$this->db->where('mobile',$mobile);
				$update=$this->db->update('login',$value);
				if($update > 0){
					$response=array('Data'=>$data);
					echo $response= json_encode(array('checkon'=>$data));
				}
			}
			else{
				$respons=array('msg'=>'OTP is Wrong!');
				echo $response= json_encode(array('checkon'=>$respons));
			}
		}
	}
	public function registered(){
		$data=json_decode(file_get_contents("php://input"));
		$mobile=$data->checkon->mobile;
	
		if(key($data)=="checkon"){
			$data=$this->app->registered($mobile);
						$response=array('Data'=>$data);
					echo $response= json_encode(array('checkon'=>$data));
				
			
		
		}
	}
	public function AddUser(){
		$data=json_decode(file_get_contents("php://input"));
		$mobile=$data->checkon->mobile;
		$image=$data->checkon->image;
		$name=$data->checkon->name;
		$active=$data->checkon->active;
		$emailid=$data->checkon->emailid;
		$group=$data->checkon->group;
		$address=$data->checkon->address;
		$token=$data->checkon->token;
		$appid=$data->checkon->appid; 
		if(key($data)=="checkon"){
		    if(!empty($image))
		    {
		      	$img=base64_decode($image);
				$imgs = imagecreatefromstring($img);
			if($imgs != false){ 
				$date = strtotime(date('Y-m-d h:i:s'));
				$i=$date.".jpg";
			   	imagejpeg($imgs, 'upload/'.$date.'.jpg'); 
			}   
		    }
		    else
		    {
		         $i='upload/nextpng.png';
		    }
		    $nmid = $this->app->getMidByMobile($mobile)['m_id'];
			$data=array(
				'name'=>$name,
				'mobile'=>$mobile,
				'user_group'=>$group,
				'email'=>$emailid,
				'address'=>$address,
				'token'=>$token,
				'app_id'=>$appid,
				'image'=>$i,
				'active'=>$active,
				'login'=>md5($mobile),
				'date'=>time(),
				'baseurl'=>base_url().'User/profile/'.$nmid
			);
			$this->db->where('mobile',$mobile);
			$update=$this->db->update('login',$data);
			if($update > 0){
				$response=array('msg'=>'Register Successfully!');
				echo $response= json_encode(array('checkon'=>$response));
			}
		}
	}
	public function Addoffer(){
		$data=json_decode(file_get_contents("php://input"));
		$shopid=$data->checkon->loginid;
		$offers=$data->checkon->offers;
		$expireddate=$data->checkon->expireddate;
		$image=$data->checkon->offerimage;
		$img=base64_decode($image);
			$imgs = imagecreatefromstring($img);
			if($imgs != false){ 
				$date = strtotime(date('Y-m-d h:i:s'));
				$i="upload/$date.jpg";
			   	imagejpeg($imgs, "$i"); 
			}
		$currentdate=time();
		$title="MID";
		$get=$this->app->shopuser($shopid);
		// print_r($get);
		foreach($get as $noti)
		{
			$usertoken=$this->app->userdetails($noti->scanby);
			 $firebasetoken=$usertoken['firebassid'];
			 $this->push_notification_android($offers,$firebasetoken,$title);
			//print_r($usertoken);
		}
		if(key($data)=="checkon"){
		    if($i=="")
		    {
		       	$data=array(
				'shopid'=>$shopid,
				'offer'=>$offers,
				'expireddate'=>strtotime($expireddate),
				'date'=>$currentdate
				
			); 
		    }
		    else{
			$data=array(
				'shopid'=>$shopid,
				'offer'=>$offers,
				'expireddate'=>strtotime($expireddate),
				'date'=>$currentdate,
				'offerimage'=>$i,
			);
		    }
			//print_r($data);
			$update=$this->db->insert('offer',$data);
			if($update > 0){
				$response=array('msg'=>'Offer Added Successfully!');
				echo $response= json_encode(array('checkon'=>$response));	
			}

		}
	}
	
	///requestqr
	
		public function Requestqr(){
		$data=json_decode(file_get_contents("php://input"));
		$userid=$data->checkon->loginid;
		$currentdate=time();
	
		if(key($data)=="checkon"){
			$data=array(
				'request_id'=>$userid,
				'date_time'=>$currentdate
			);
			//print_r($data);
			$update=$this->db->insert('qr_request',$data);
			if($update > 0){
				$response=array('msg'=>'Requested Successfully!');
				echo $response= json_encode(array('checkon'=>$response));	
			}

		}
	}
	
	
	//////
	public function getGroup(){
		$getGroup=$this->app->getGroups();
		foreach ($getGroup as $key => $group) {
			if($group->active=="0"){
				$value[]=array('id'=>$group->id,'name'=>$group->name,'type'=>$group->type,'icon'=>base_url('icon').'/'.$group->icon);
			}	
		}
		echo $data=json_encode(array('checkon'=>$value));
		
	}
////////////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
	public function Qrapi(){
		$data=json_decode(file_get_contents("php://input"));
		//print_r($data);
		$date = date('Y-m-d H:i:s');
		 $res = $this->sample($data->checkon->userid);
		$userid=$res;
		$loginid=$data->checkon->loginid;
		$user_group=$data->checkon->user_group;
		$share=$data->checkon->share;
		if(key($data)=="checkon"){
			if($user_group==1){
				$data2=array(
					'scanid'=>$loginid,
					'scanby'=>$userid,
					'ShareType'=>$share,
					'user_group'=>'1',
					'date'=>$date
				);
				$data=array(
					'scanid'=>$userid,
					'scanby'=>$loginid,
					'ShareType'=>$share,
					'user_group'=>'2',
					'date'=>$date
				);
				$upd=$this->db->insert('userqrdetails',$data);
				$up=$this->db->insert('userqrdetails',$data2);
				
				if($up > 0){
					$response=array('msg'=>'Register Successfully!');
					echo $response= json_encode(array('checkon'=>$response));
				}
			}
			if($user_group==2){
				$data2=array(
					'scanid'=>$loginid,
					'scanby'=>$userid,
					'ShareType'=>$share,
					'user_group'=>'2',
					'date'=>$date
				);
				$data=array(
					'scanid'=>$userid,
					'scanby'=>$loginid,
					'ShareType'=>$share,
					'user_group'=>'2',
					'date'=>$date
				);
				$upd=$this->db->insert('userqrdetails',$data);
				$up=$this->db->insert('userqrdetails',$data2);
				if($up > 0){
					$response=array('msg'=>'Register Successfully!');
					echo $response= json_encode(array('checkon'=>$response));
				}
			}
		}
	}
	public function getContact(){
		$data=json_decode(file_get_contents("php://input"));
		$loginid=$data->checkon->loginid;
		if(key($data)=="checkon"){
			$get=$this->app->getUserData($loginid);
			foreach ($get as $key =>$get) {
				$userid[]=$get->scanby;
				$date[]=$get->date;
			}
			$c=0;
			foreach ($userid as $key => $usd) {
				$data=$this->app->getUserDetail($usd);
				foreach ($data as $key => $value) {
					$count=$c++;
					$values[]=array(
						'mobile'=>$value->mobile,
						'name'=>$value->name,
						'date'=>$date[$count]
					);
				}
			}
			if(!empty($values)){
				echo $response= json_encode(array('checkon'=>$values));
			}	
		}	
	}
	public function getHistoryData(){
		$data=json_decode(file_get_contents("php://input"));
		//print_r($data);
		$loginid=$data->checkon->loginid;

		if(key($data)=="checkon"){
			$get=$this->app->getHistory($loginid);
			
			//
			$gettoken=$this->app->gettoken($loginid);
			//print_r($gettoken);
			
			foreach($gettoken as $tokendetails)
			{
				
				//print_r($tokendetails);
				$token=$this->app->getUserDetail($tokendetails->userid);
				
				// print_r($getdepart);
				
				foreach($token as $dd)
				{
					 $getdepart=$this->app->getdeptnew($tokendetails->Dept_id);
					 foreach($getdepart as $deps)
					 {
						 
						 $depmobile[]=$dd->mobile;
						 // $dataws[]=array(
				 // 'mobile'=>$dd->mobile,
				 // 'token'=>$tokendetails->token,
				 // 'Department'=>$deps->department,
				// 'status'=>$tokendetails->status
				
				//);
					 }
					
					//print_r($data);
				}
				
			}
			//
			foreach ($get as $key =>$get) {
				$userid[]=$get->scanby;
				$date[]=$get->date;
				$ShareType[]=$get->ShareType;
			}	
			$c=0;
			foreach($userid as $key => $usd) {
				$data=$this->app->getUserDetail($usd);
				foreach ($data as $key => $value) {
					$count=$c++;
					$values[]=array(
						'mobile'=>$value->mobile,
						'address'=>$value->address,
						'ShareType'=>$ShareType[$count],
						'dep'=> $depmobile,
						'name'=>$value->name,
						'date'=>strtotime($date[$count]),
						'id'=>$value->id
					);
				}
			}
			
			if(!empty($values)){
				echo $response= json_encode(array('checkon'=>$values));
			}
			
		}
		
	}
	public function getContactData(){
		$data=json_decode(file_get_contents("php://input"));
		 $loginid=$data->checkon->loginid;
		 
		if(key($data)=="checkon"){
			$get=$this->app->getConatctData($loginid);
		
			foreach ($get as $key =>$get) {
				$userid[]=$get->scanid;
				$date[]=$get->date;
			}
			$c=0;
			
			foreach ($userid as $key => $usd) {
				$data=$this->app->getShopDetail($usd);
				//	print_r($data);
				foreach ($data as $key => $value) {
					$count=$c++;
					$values[]=array(
						'mobile'=>$value->mobile,
						'name'=>$value->name,
						'id'=>$value->id,
						'date'=>$date[$count]
					);
				}
			}
			if(!empty($values)){
				echo $response= json_encode(array('checkon'=>$values));
			}
			
		}
		
	}
	//
	
		public function UpdateUser(){
		$data=json_decode(file_get_contents("php://input"));
//print_r($data);		
	
	
		

		$image=$data->checkon->image;	
		$oldimage=$data->checkon->oldimage;	


if(!empty($image))
{

	$name=$data->checkon->name;		
	$BussinessType=$data->checkon->BussinessType;		
		$emailid=$data->checkon->emailid;		
		$address=$data->checkon->address;
		
		$twitterid=$data->checkon->twitterid;
		$facebookid=$data->checkon->facebookid;
		$instagramid=$data->checkon->instagramid;
		$description=$data->checkon->description;
		$youtubeid=$data->checkon->youtubeid;
		
		$website=$data->checkon->website;
		$comname=$data->checkon->comname;
		$id=$data->checkon->loginid;
		$img=base64_decode($image);
			$imgs = imagecreatefromstring($img);
			if($imgs != false){ 
				$date = strtotime(date('Y-m-d h:i:s'));
				$i="upload/$date.jpg";
			   	imagejpeg($imgs, "$i"); 
			}

			$data=array(
				'name'=>$name,			
				'company'=>$comname,
				'website'=>$website,		
				'email'=>$emailid,
				'address'=>$address,				
				'facebookid'=>$facebookid,
				'twitterid'=>$twitterid,
				'instagramid'=>$instagramid,
				'description'=>$description,
				'youtube'=>$youtubeid,
				'BussinessType'=>$BussinessType,			
				'image'=>$i,				
			);
			$this->db->where('id',$id);
			$update=$this->db->update('login',$data);
			if($update > 0){
				$response=array('msg'=>'Update Successfully!');
				echo $response= json_encode(array('checkon'=>$response));
			}
				
}	
else
{
	$name=$data->checkon->name;		
	$BussinessType=$data->checkon->BussinessType;		
		$emailid=$data->checkon->emailid;		
		$address=$data->checkon->address;
		
		$twitterid=$data->checkon->twitterid;
		$facebookid=$data->checkon->facebookid;
		$instagramid=$data->checkon->instagramid;
		$description=$data->checkon->description;
		$youtubeid=$data->checkon->youtubeid;
		
		$website=$data->checkon->website;
		$comname=$data->checkon->comname;
		$id=$data->checkon->loginid;
		$img=base64_decode($image);
			$imgs = imagecreatefromstring($img);
			if($imgs != false){ 
				$date = strtotime(date('Y-m-d h:i:s'));
				$i="upload/$date.jpg";
			   	imagejpeg($imgs, "$i"); 
			}

			$data=array(
				'name'=>$name,
			
				
				'company'=>$comname,
				'website'=>$website,
		
				'email'=>$emailid,
				'address'=>$address,
				
				'facebookid'=>$facebookid,
				'twitterid'=>$twitterid,
				'instagramid'=>$instagramid,
				'description'=>$description,
				'youtube'=>$youtubeid,
				'BussinessType'=>$BussinessType,
			
			
				'image'=>$oldimage,
			
			
				
			);
			$this->db->where('id',$id);
			$update=$this->db->update('login',$data);
			if($update > 0){
				$response=array('msg'=>'Update Successfully!');
				echo $response= json_encode(array('checkon'=>$response));
			}
			
	


		
		if(key($data)=="checkon"){

		}
}
		
	}
	//
	
		public function UpdateShop(){
		$data=json_decode(file_get_contents("php://input"));
//print_r($data);		
	
	
		$image=$data->checkon->image;	
		$id=$data->checkon->loginid;
		$oldimage=$data->checkon->oldimage;	
		
		$longitude=$data->checkon->Longitude;
		$googleprofile=$data->checkon->googleprofile;
		$aboutus=$data->checkon->aboutus;
		$paymentlink=$data->checkon->paymentlink;
if(!empty($longitude))
{
	$longitude=$data->checkon->Longitude;
	$latitude=$data->checkon->Latitude;
}	
else
{
	 
	$details=$this->app->userdetails($id);
	 $longitude=$details['Longitude'];
	 $latitude=$details['Latitude'];
	//print_r($details);
}
if(!empty($paymentlink))
{
$paymentlink=$data->checkon->paymentlink;
}	
else
{
	 
	$details=$this->app->userdetails($id);
	 $paymentlink=$details['paymentlink'];
	
	//print_r($details);
}



if(!empty($image))
{
	$name=$data->checkon->name;		
	$BussinessType=$data->checkon->BussinessType;		
		$emailid=$data->checkon->emailid;		
		$address=$data->checkon->address;
		
		$twitterid=$data->checkon->twitterid;
		$facebookid=$data->checkon->facebookid;
		$instagramid=$data->checkon->instagramid;
		$description=$data->checkon->description;
		$youtubeid=$data->checkon->youtubeid;
		$bussinessType=$data->checkon->BussinessType;
		$website=$data->checkon->website;
		$comname=$data->checkon->comname;
		$id=$data->checkon->loginid;
		$img=base64_decode($image);
			$imgs = imagecreatefromstring($img);
			if($imgs != false){ 
				$date = strtotime(date('Y-m-d h:i:s'));
				$i="upload/$date.jpg";
			   	imagejpeg($imgs, "$i"); 
			}

			$data=array(
				'name'=>$name,
			
				
				'company'=>$comname,
				'website'=>$website,
		
				'email'=>$emailid,
				'address'=>$address,
				
				'facebookid'=>$facebookid,
				'twitterid'=>$twitterid,
				'instagramid'=>$instagramid,
				'description'=>$description,
				'youtube'=>$youtubeid,
				'BussinessType'=>$BussinessType,
				'Longitude'=>$longitude,
				'latitude'=>$latitude,
				'googleprofile'=>$googleprofile,
			    'paymentlink'=>$paymentlink,
			    'BussinessType'=>$bussinessType,
		    	'about_us'=>$aboutus,
				'image'=>$i,
			
			
				
			);
			$this->db->where('id',$id);
			$update=$this->db->update('login',$data);
			if($update > 0){
				$response=array('msg'=>'Update Successfully!');
				echo $response= json_encode(array('checkon'=>$response));
			}
			
	


		
		
	
}	
else
{
	$name=$data->checkon->name;		
	 $BussinessType=$data->checkon->BussinessType;		
		$emailid=$data->checkon->emailid;		
		$address=$data->checkon->address;
		
		$twitterid=$data->checkon->twitterid;
		$facebookid=$data->checkon->facebookid;
		$instagramid=$data->checkon->instagramid;
		$description=$data->checkon->description;
		$youtubeid=$data->checkon->youtubeid;
		
		$website=$data->checkon->website;
		$comname=$data->checkon->comname;
		$id=$data->checkon->loginid;
		$img=base64_decode($image);
			$imgs = imagecreatefromstring($img);
			if($imgs != false){ 
				$date = strtotime(date('Y-m-d h:i:s'));
				$i="upload/$date.jpg";
			   	imagejpeg($imgs, "$i"); 
			}

			$data=array(
				'name'=>$name,
			
				
				'company'=>$comname,
				'website'=>$website,
		
				'email'=>$emailid,
				'address'=>$address,
				'facebookid'=>$facebookid,
				'twitterid'=>$twitterid,
				'instagramid'=>$instagramid,
				'description'=>$description,
				'youtube'=>$youtubeid,
				'googleprofile'=>$googleprofile,
				'BussinessType'=>$BussinessType,
			'Longitude'=>$longitude,
				'latitude'=>$latitude,
				'about_us'=>$aboutus,
			'paymentlink'=>$paymentlink,
				'image'=>$oldimage,
			
			
				
			);
			$this->db->where('id',$id);
			$update=$this->db->update('login',$data);
			if($update > 0){
				$response=array('msg'=>'Update Successfully!');
				echo $response= json_encode(array('checkon'=>$response));
			}
			
	


		
		if(key($data)=="checkon"){

		}
}
		
	}
	
	
		// public function UpdateShop(){
		// $data=json_decode(file_get_contents("php://input"));	
		// $image=$data->checkon->image;
		
		// $name=$data->checkon->name;		
		// $emailid=$data->checkon->emailid;		
		// $address=$data->checkon->address;
		
		// $website=$data->checkon->website;
		// $comname=$data->checkon->comname;
	
		// $id=$data->checkon->loginid;
		
		// $twitterid=$data->checkon->twitterid;
		// $facebookid=$data->checkon->facebookid;
		// $instagramid=$data->checkon->instagramid;
		// $description=$data->checkon->description;
		// $youtubeid=$data->checkon->youtubeid;
	
		
		// if(key($data)=="checkon"){
			// $img=base64_decode($image);
			// $imgs = imagecreatefromstring($img);
			// if($imgs != false){ 
				// $date = strtotime(date('Y-m-d h:i:s'));
				// $i="upload/$date.jpg";
			   	// imagejpeg($imgs, "$i"); 
			// } 
			// $data=array(
				// 'name'=>$name,
			
			
				
				// 'company'=>$comname,
				// 'facebookid'=>$facebookid,
				// 'twitterid'=>$twitterid,
				// 'instagramid'=>$instagramid,
				// 'description'=>$description,
				// 'youtube'=>$youtubeid,
				// 'website'=>$website,
		
				// 'email'=>$emailid,
				// 'address'=>$address,
			
			
				// 'image'=>$i,
			
			
				
			// );
			// $this->db->where('id',$id);
			// $update=$this->db->update('login',$data);
			// if($update > 0){
				// $response=array('msg'=>'Update Successfully!');
				// echo $response= json_encode(array('checkon'=>$response));
			// }

		// }
	// }
	
	
	public function searchData(){
	    
	    
	    	$data=json_decode(file_get_contents("php://input"));
		    //print_r($data);
			$loginid=$data->checkon->loginid;
			$start="00:00:00";
			$end="23:59:00";
			$from=$data->checkon->from;
			$to=$data->checkon->to;
			$newfrom=$from." ".$start;
			$newto=$to." ".$end;
		    if(key($data)=="checkon"){
			$get=$this->app->search($loginid,$newfrom,$newto);
			//print_r($get);
			foreach ($get as $key =>$get) {
				$userid[]=$get->scanby;
				$date[]=$get->date;
				$ShareType[]=$get->ShareType;
			}	
			$c=0;
			foreach($userid as $key => $usd) {
				$data=$this->app->getUserDetail($usd);
				foreach ($data as $key => $value) {
					$count=$c++;
					$values[]=array(
						'mobile'=>$value->mobile,
						'address'=>$value->address,
						'ShareType'=>$ShareType[$count],
						'name'=>$value->name,
						'date'=>strtotime($date[$count]),
						'id'=>$value->id
					);
				}
				
			}
			$da=count($values);
			if(!empty($values)){
				echo $response= json_encode(array('checkon'=>$values,'total'=>"Total is:$da"));
			}
			
		}
	  
	}
	
	public function Generatepdf()
	{
			$this->load->library('pdf/fpdf');
			$pdf=new FPDF();
			$pdf->AddPage();
			$pdf->SetFont('Arial','B','16');
						
			$data=$pdf->Output('abc.pdf','I');
			print_r($CI);
	}
	
	//
	public function profiledetails(){
		$data=json_decode(file_get_contents("php://input"));
		$mobile=$data->checkon->loginid;

		if(key($data)=="checkon"){
			$data=$this->app->userdetailsnew($mobile);
			if(!empty($data)){
				//echo $bussinessid=$data['BussinessType'];
				$buss=$this->app->getBussinessname($data['BussinessType']);
				//print_r($buss);
				 foreach($buss as $qq)
				 {
					 $busq=$qq->catagory;
				 }
				 foreach($data as $dd)
				 {
				     //print_r($dd);
				     $url=base_url();
				     if(!empty($dd->image))
				     {
				         $image=$dd->image;
				     }
				     else
				     {
				          $image=$url.'/upload/nextpng.png';
				     }
				     $ss=array(
				            'id'=>$dd->id,
				         	'mobile'=>$dd->mobile,
						    'name'=>$dd->name,
					    	'address'=>$dd->address,
							'user_group'=>$dd->user_group,
							'email'=>$dd->email,
							'image'=>$image,
							'website'=>$dd->website,
							'facebookid'=>$dd->facebookid,
							'twitterid'=>$dd->twitterid,
							'company'=>$dd->company,
							'instagramid'=>$dd->instagramid,
							'description'=>$dd->description,
							'BussinessType'=>$dd->BussinessType,
							'googleprofile'=>$dd->googleprofile,
							'aboutus'=>$dd->about_us,
							'youtube'=>$dd->youtube,
							'BussinessType'=>$dd->BussinessType,
							'Latitude'=>$dd->Latitude,
							'Longitude'=>$dd->Longitude
				         );
				     
				 }
				 
				
				$this->db->where('mobile',$mobile);
			
				
					$response=array('Data'=>$data);
					echo $response= json_encode(array('checkon'=>$ss,'busssine'=>$busq));
			
			}
			else{
				$respons=array('msg'=>'OTP is Wrong!');
				echo $response= json_encode(array('checkon'=>$respons));
			}
		}
	}
	//Get Offer Details
	public function offerdetails(){
		$data=json_decode(file_get_contents("php://input"));
		$loginid=$data->checkon->loginid;
       
		if(key($data)=="checkon"){
			$data=$this->app->offerdetails($loginid);
			//print_r($data);
			if(!empty($data)){
			
			foreach($data as $response)
			{
			   
			    $ss=$this->app->userdetails($response->shopid);
			   // print_r($ss);
			   $base=base_url();
			    $res[]=array(
			        'oid'=>$response->oid,
			        'offer'=>$response->offer,
			        'expireddate'=>$response->expireddate,
			        'offerimage'=>$base.''.$response->offerimage,
			        'shopname'=>$ss['name'],
			        'address'=>$ss['address'],
			        'image'=>$base.''.$ss['image']
			        );
			}
					echo $response= json_encode(array('checkon'=>$res));
			
			}
			else{
				$respons=array('msg'=>' Wrong!');
				echo $response= json_encode(array('checkon'=>$respons));
			}
		}
	}
	
	
	
	
	
	///
	
	public function Updateoffer(){
		$data=json_decode(file_get_contents("php://input"));
		$oid=$data->checkon->oid;
		$offers=$data->checkon->offers;
		$expireddate=$data->checkon->expireddate;
		$currentdate=time();
$image=$data->checkon->offerimage;	
		if(key($data)=="checkon"){
			 $img=base64_decode($image);
			$imgs = imagecreatefromstring($img);
			if($imgs != false){ 
				$date = strtotime(date('Y-m-d h:i:s'));
				$i="upload/$date.jpg";
			   	imagejpeg($imgs, "$i"); 
			}
			$data=array(
				
				'offer'=>$offers,
				'expireddate'=>strtotime($expireddate),
				'date'=>$currentdate,
				'offerimage'=>$i
				
			);
			//print_r($data);
			$this->db->where('oid',$oid);
			$update=$this->db->update('offer',$data);
			if($update > 0){
				$response=array('msg'=>'Offer Added Successfully!');
				echo $response= json_encode(array('checkon'=>$response));
			}

		}
	}
	///Get Offer For User
	
	public function Useroffer(){
		$data=json_decode(file_get_contents("php://input"));
		if(key($data)=="checkon"){
			$loginid=$data->checkon->loginid;
			
			$getRecord=$this->app->getUserscan($loginid);
			//print_r($getRecord);
			
			foreach ($getRecord as $key => $get) {
				$data=$this->app->getUsersoffers($get->scanid);
				//print_r($data);
				
				foreach ($data as $key => $value) {
				     $ss=$this->app->userdetails($value->shopid);
					 //print_r($value);
					  $base=base_url();
				     //echo $value->expireddate.'--'.time();
				     $curr=date("m/d/Y");
				     $expiredate=date("m/d/Y",$value->expireddate);
				     if($curr <= $expiredate){
    					$values[]=array(
    						'offer'=>$value->offer,
    						'oid'=>$value->oid,
    						'expireddate'=>$value->expireddate,
    						'shopname'=>$ss['name'],
    						'address'=>$ss['address'],
    						'offerimage'=>$base.''.$value->offerimage,
    						'image'=>$base.''.$ss['image'],
    						
    	    					);
				     }
				     //print_r($values);
				}
			}
			
			if(!empty($values)){
				echo $response= json_encode(array('checkon'=>$values));
			}

		}
	}
	//
	////
	public function getBussiness(){
		$getGroup=$this->app->getBussiness();
		foreach ($getGroup as $key => $group) {
	
				$value[]=array('id'=>$group->id,'catagory'=>$group->catagory);
			
		}
		echo $data=json_encode(array('checkon'=>$value));
		
	}
	public function sendsms($mobile,$msg){
	    $url="http://185.136.166.131/domestic/sendsms/bulksms.php?username=checkon&password=checkon&type=TEXT&sender=CHEKON&mobile=$mobile&message=".urlencode($msg);
	    $ch = curl_init($url);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    	$output = curl_exec($ch);
    	curl_close($ch);
	}
	
	
	
//////////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
	public function Gettoken(){
		$data = json_decode(file_get_contents("php://input"));
		$date = date('Y-m-d H:i:s');
		
		if($data->checkon->user_group=='2')
		{
		
		if(!empty($data->checkon->userid)){
		    $userid = $this->sample($data->checkon->userid);
		    
		}
		else
		{
		$userid="0";
		}
		 

		  
		 
		$loginid = $data->checkon->loginid;
		//$user_group=$data->checkon->user_group;
			$mobileno = $data->checkon->mobileno;
			
			 $checkuser = $this->app->usertypescheck($userid,$mobileno);
			 //print_r($checkuser);	
			//print_r($getRecords);
			 $sss = $checkuser['user_group'];
			if($checkuser['user_group'] == '2')
			{
				$getRecords = $this->app->userdetailscheck($userid,$mobileno);
				
				//print_r($getRecords);
				
				$id = $getRecords['id'];
				$bussinessname = $getRecords['name'];
				$getdepart = $this->app->getassigneddept($getRecords['id']);
		
				foreach($getdepart as $dep)
				{
					//print_r($dep);
					$ss = $this->app->getdept($dep->department_id);
						//print_r($ss);
						 $res[] = array(
			        'id' => $ss['id'],
			        'department' => $ss['department'],
			         'bussiness_id' => $id
			         
			        );
				}
			echo $response= json_encode(array('checkon'=>$res,'Bussinessname'=>$bussinessname));
			}
			else if($checkuser['user_group']=='1')
			{
			    
			 
			     $name=$checkuser['name'];
			     $status=$this->app->userwebcheck($checkuser['id']);
			     //print_r($status);
			      $st=$status['status'];
			      
			      $tkdata=$this->app->tokstatus($checkuser['id']);
			      
			      $tsdata=$tkdata['status'];
			      
			      if($tsdata==1)
			      {
			          if($st==1)
			     {
			         
				$getdepart=$this->app->getassigneddept($checkuser['id']);
			//print_r($getdepart);
				
				foreach($getdepart as $dep)
				{
					//print_r($dep);
					$ss=$this->app->getdept($dep->department_id);
						//print_r($ss);
						 $res[]=array(
			        'id'=>$ss['id'],
			        'department'=>$ss['department'],
			       'bussiness_id'=>$dep->user_bussiness_id
			        );
				}
			echo $response= json_encode(array('checkon'=>$res,'Bussinessname'=>$name));
			     }
			     else
			     {
			        	$response=array('msg'=>'Department Not Found!');
				echo $response= json_encode(array('checkon'=>$response));
			     }
			          
			      }
			      
			     else
			     {
			        	$response=array('msg'=>'Department Not Found!');
				echo $response= json_encode(array('checkon'=>$response));
			     }
			    
			    
			}
					
					
			else
			{
				$response=array('msg'=>'Department Not Found!');
				echo $response= json_encode(array('checkon'=>$response));
				
			}
			
			
		}
		else
		{
		    $userid=$data->checkon->loginid;
		    	$getRecords = $this->app->userdetailscheck($userid,$mobileno);
				
				//print_r($getRecords);
				
				$id = $getRecords['id'];
				$bussinessname = $getRecords['name'];
				$getdepart = $this->app->getassigneddept($getRecords['id']);
		
				foreach($getdepart as $dep)
				{
					//print_r($dep);
					$ss = $this->app->getdept($dep->department_id);
						//print_r($ss);
						 $res[] = array(
			        'id' => $ss['id'],
			        'department' => $ss['department'],
			         'bussiness_id' => $id
			         
			        );
				}
			echo $response= json_encode(array('checkon'=>$res,'Bussinessname'=>$bussinessname));
		    
		}
	}
	
	
	//
	
	///Get Offer For User
	
	public function SubDepartment(){
		$data=json_decode(file_get_contents("php://input"));
		if(key($data)=="checkon"){
			 $depid=$data->checkon->depid;
			
				 $subdep=$this->app->getsubdept($depid);
				// print_r($subdep);
					if(!empty($subdep)){
					$response=array('Data'=>$subdep);
					echo $response= json_encode(array('checkon'=>$subdep));
					}
					else
					{
						$response=array('msg'=>'Department Not Found!');
				echo $response= json_encode(array('checkon'=>$response));
					}
					
		}
		
	}
	//
		public function GenerateToken(){
		$data=json_decode(file_get_contents("php://input"));
		if(key($data)=="checkon"){
			  $loginid=$data->checkon->loginid;
			 $group=$data->checkon->group;
			 $subid=$data->checkon->subid;
			 $depid=$data->checkon->depid;
			 $mobileno=$data->checkon->mobileno;
			 $bussinessid=$data->checkon->bussinessid;
			 //$submitdate=$data->checkon->submitdate;
			 $Quee=$data->checkon->Query;
			 
			 if(empty($Quee))
			 {
			     $Que="";
			 }
			 else
			 {
			     $Que=$data->checkon->Query;
			 }
			 $checkmobile=$this->app->checkMobile($mobileno);
			 //print_r($checkmobile);
			 if(empty($mobileno))
			 {
				
				   $today = date("Y-m-d"); 
				 $subdep=$this->app->gettokendate($today, $depid);
				// print_r($subdep);
				 if(empty($subdep))
				 {
					 
					 $today = date("Y-m-d");
//					 
					 	 $message="Your Token No. is:1";
						 $title="MID";
						$userdetails=$this->app->userdetails($loginid);
				foreach($userdetails as $noti)
				{
				
				$usertoken=$this->app->userdetails($loginid);
				$firebasetoken=$usertoken['firebassid'];
				$this->push_notification_android($message,$firebasetoken,$title);
			   //print_r($usertoken);
				}
						 //
					 $token=$subdep['token']+1;
				
				 $userdetails=
				 $data=array(
				'Dept_id'=>$depid,
				'Sub_deptid'=>$subid,
				'userid'=>$loginid,
				'token'=>"1",
				'date'=>$today,
				'Query'=>$Que,
				'status'=>"0",
				'user_bussiness_id'=>$bussinessid
				
			);
			//print_r($data);
			
			$update=$this->db->insert('token',$data);
			if($update > 0){
				$response=array('msg'=>'token submitted Successfully!');
				echo $response= json_encode(array('checkon'=>$response));
					 
					 			
			}
				
				 }
				 ///
				  //print_r($subdep);
				  else
				  {
					  
					  
					  ///
					  
					  
					  
					  ////
					
				   $subdeps=$this->app->getMaxtoken($depid);
				   //print_r($subdep)
				   $token=$subdeps['token']+1;
				   $usertoken=$this->app->userdetails($loginid);
				   $firebasetoken=$usertoken['firebassid'];
				   $title="MID";
				   $offers="Your Token No." ." ".$token;
				  $this->push_notification_android($offers,$firebasetoken,$title);
				   //
				   
				   
				   
				   
				 
				 $data=array(
				'Dept_id'=>$depid,
				'Sub_deptid'=>$subid,
				'userid'=>$loginid,
				'token'=>$token,
				'date'=>$today,
				'Query'=>$Que,
				'status'=>"0",
					'user_bussiness_id'=>$bussinessid
			);
			//print_r($data);
			
			$update=$this->db->insert('token',$data);
			if($update > 0){
				$response=array('msg'=>'token submitted Successfully!');
				echo $response= json_encode(array('checkon'=>$response));
				
				
				///
				

				////
				
				
			}
				  }
				 
			 }
			 
			 //By mobilenumber start
			 else
			 {
			 //print_r($checkmobile);
			 if(empty($checkmobile))
			 {
				 
				 $data=array(
				 'mobile'=>$mobileno,
				 'user_group'=>'2',
				 'active'=>'1'
				 );
				 $insert=$this->db->insert('login',$data);
				 
				 $checkmobile=$this->app->checkMobile($mobileno);
				 
				 if(!empty($checkmobile))
				 {
					 
					 //
					
					 
			  $userid=$checkmobile['id'];
			  $today = date("Y-m-d"); 
			  
			  $subdep=$this->app->gettokendate($today,$depid);
			  $depcode=$this->app->getdept($depid);
			//print_r($depcode);
			 //$dep_code=$depcode['Dep_code'];
			
			if(empty($subdep))
			{
				
				$data=array(
				'Dept_id'=>$depid,
				'Sub_deptid'=>$subid,
				'userid'=>$userid,
				'token'=>"1",
				'date'=>$today,
				'Query'=>$Que,
					'user_bussiness_id'=>$bussinessid,
				'status'=>"0"
				
			);
			//print_r($data);
			
			$update=$this->db->insert('token',$data);
			if($update > 0){
				$response=array('msg'=>'token submitted Successfully!');
				echo $response= json_encode(array('checkon'=>$response));
			}
			}
			
			else
			{
				
				$checkmobile=$this->app->checkMobile($mobileno);
				
			
				
				  $subdep=$this->app->getMaxtoken($depid);
				  //print_r($subdep);
				   $token=$subdep['token']+1;
				   
				   $usertoken=$this->app->userdetails($userid);
				   $firebasetoken=$usertoken['firebassid'];
				   $title="MID";
				   $offers="Your Token No." ." ".$token;
				  $this->push_notification_android($offers,$firebasetoken,$title);
				  
				$data=array(
				'Dept_id'=>$depid,
				'Sub_deptid'=>$subid,
				'userid'=>$userid,
				'token'=>$token,
				'date'=>$today,
					'user_bussiness_id'=>$bussinessid,
				'Query'=>$Que,
				'status'=>"0"
				
			);
			//print_r($data);
			$update=$this->db->insert('token',$data);
			if($update > 0){
				$response=array('msg'=>'token submitted Successfully!');
				echo $response= json_encode(array('checkon'=>$response));
			}
			
				  
				//echo"1";
			}
					 
					 ///
				 }
				 
				 
				
			 }
			 else{
				 
				 
				 if($group=="2")
				 {
					 
			
			  $userid=$checkmobile['id'];
			  $today = date("Y-m-d"); 
			  
			   $subdep=$this->app->gettokendate($today, $depid);
			  $depcode=$this->app->getdept($depid);
			  
			//print_r($depcode);
			
			 
			
			 //$dep_code=$depcode['Dep_code'];
			
			if(empty($subdep))
			{
				
				$data=array(
				'Dept_id'=>$depid,
				'Sub_deptid'=>$subid,
				'userid'=>$loginid,
				'token'=>"1",
				'date'=>$today,
					'user_bussiness_id'=>$bussinessid,
				'Query'=>$Que,
				'status'=>"0"
				
			);
			//print_r($data);
			
				   $usertoken=$this->app->userdetails($loginid);
				   $firebasetoken=$usertoken['firebassid'];
				   $title="MID";
				   $offers="Your Token No. is 1" ;
				  $this->push_notification_android($offers,$firebasetoken,$title);
			
			$update=$this->db->insert('token',$data);
			if($update > 0){
				$response=array('msg'=>'token submitted Successfully!');
				echo $response= json_encode(array('checkon'=>$response));
			}
			}
			
			else
			{
				
				  $subdep=$this->app->getMaxtoken($depid);
				  //print_r($subdep);
				   $token=$subdep['token']+1;
				   
				   $usertoken=$this->app->userdetails($loginid);
				   $firebasetoken=$usertoken['firebassid'];
				   $title="MID";
				   $offers="Your Token No. is"." " .$token;
				  $this->push_notification_android($offers,$firebasetoken,$title);
				  
				$data=array(
				'Dept_id'=>$depid,
				'Sub_deptid'=>$subid,
				'userid'=>$loginid,
				'token'=>$token,
				'date'=>$today,
					'user_bussiness_id'=>$bussinessid,
				'Query'=>$Que,
				'status'=>"0"
				
			);
			//print_r($data);
			$update=$this->db->insert('token',$data);
			if($update > 0){
				$response=array('msg'=>' token submitted Successfully!');
				echo $response= json_encode(array('checkon'=>$response));
			}
			
				  
				//echo"1";
			} 
					 
				 }
				 else
				 {
				 
				 
			   $userid=$checkmobile['id'];
			  $today = date("Y-m-d"); 
			  
			   $subdep=$this->app->gettokendate($today,$depid);
			  $depcode=$this->app->getdept($depid);
			//print_r($depcode);
			 //$dep_code=$depcode['Dep_code'];
			 
			 $usertoken=$this->app->userdetails($userid);
				   $firebasetoken=$usertoken['firebassid'];
				   $title="MID";
				   $offers="Your Token No. is 1" ;
				  $this->push_notification_android($offers,$firebasetoken,$title);
			
			if(empty($subdep))
			{
				
				$data=array(
				'Dept_id'=>$depid,
				'Sub_deptid'=>$subid,
				'userid'=>$userid,
				'token'=>"1",
					'user_bussiness_id'=>$bussinessid,
				'date'=>$today,
				'Query'=>$Que,
				'status'=>"0"
				
			);
			//print_r($data);
			
			$update=$this->db->insert('token',$data);
			if($update > 0){
				$response=array('msg'=>'token submitted Successfully!');
				echo $response= json_encode(array('checkon'=>$response));
			}
			}
			
			else
			{
				  $subdep=$this->app->getMaxtoken($depid);
				  //print_r($subdep);
				   $token=$subdep['token']+1;
				   
				    $usertoken=$this->app->userdetails($userid);
				   $firebasetoken=$usertoken['firebassid'];
				   $title="MID";
				   $offers="Your Token No. is"." ".$token;
				  $this->push_notification_android($offers,$firebasetoken,$title);
				  
				$data=array(
				'Dept_id'=>$depid,
				'Sub_deptid'=>$subid,
				'userid'=>$userid,
				'token'=>$token,
					'user_bussiness_id'=>$bussinessid,
				'date'=>$today,
				'Query'=>$Que,
				'status'=>"0"
				
			);
			//print_r($data);
			$update=$this->db->insert('token',$data);
			if($update > 0){
				$response=array('msg'=>'token submitted Successfully!');
				echo $response= json_encode(array('checkon'=>$response));
			}
			
				  
				//echo"1";
			}
				 }
					
		}
			 }
		//By mobilenumber start end
		}
		
	}
	
//////////////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
	public function getUsertoken(){
		$data=json_decode(file_get_contents("php://input"));
	
		//$getid = $this->sample($data->checkon->loginid);
		$loginid=$data->checkon->loginid;
	
		if(key($data)=="checkon"){
			$get=$this->app->getHistory($loginid);
			  $today = date("Y-m-d"); 
			//
			$gettoken=$this->app->gettoken($loginid,$today);
			//($gettoken);
			
			
			foreach($gettoken as $tokendetails)
			{
				
				
			//	print_r($tokendetails);
			
			$counter=$this->app->getCounter($tokendetails->counter_id);
			
				$token=$this->app->getUserDetail($tokendetails->userid);
				//print_r($counter);
			 $counterno=$counter['counter_id'];
				
				$getbussinessname=$this->app->userdetails($tokendetails->user_bussiness_id);
				// print_r($getbussinessname);
				
				 
				 

				foreach($token as $dd)
				{
				   // print_r($tokendetails);
					 $getdepart=$this->app->getdeptnew($tokendetails->Dept_id);
					 
					 $subdepid=$tokendetails->Sub_deptid;
					 $depid=$tokendetails->Dept_id;
					 
					 
					 
					 $getlivetoken=$this->app->getlivetoken($depid,$today);
					// print_r($getlivetoken);
					
					 foreach($getdepart as $deps)
					 {		
					     
					     
					      if($getlivetoken['token']=="")
					 {
					     $livetoken="0";
					 }
					 
					 else
					 {
					     $livetoken="Live "." ". $deps->Dep_code ." ".$getlivetoken['token'];
					     
					 }
					     
				  $dataws[]=array(
				  'mobile'=>$dd->mobile,
				  'token'=>"Token "." ". $deps->Dep_code ." ".$tokendetails->token,
			      'livetoken'=>$livetoken,
				  'Department'=>$deps->department,				 
				  'status'=>$tokendetails->status,	
				  'Bussinessname'=>$getbussinessname['name'],
				  'Counter'=>$counterno
				);
					 }
					
				
				
					//print_r($dataws);
				}
				
				
			}
			//
			
			
			if(!empty($dataws)){
				echo $response= json_encode(array('checkon'=>$dataws));
			}
			else
		{
			
				$response=array('msg'=>'No Data Foud');
				echo $response= json_encode(array('checkon'=>$response));
			
		}
			
		}
		
		
		
	}
	///
	
	
	//Bussiness
	
	//
	public function getBussinesstoken(){
		$data=json_decode(file_get_contents("php://input"));
		//print_r($data);
		$loginid=$data->checkon->loginid;
		
		
	
		if(key($data)=="checkon"){
		
			  $today = date("Y-m-d"); 
			//
			$gettoken=$this->app->getBussinesstoken($loginid,$today);
			//print_r($gettoken);
			
			
			foreach($gettoken as $tokendetails)
			{
				
				
				//print_r($tokendetails);
				$token=$this->app->getUserDetail($tokendetails->userid);
				$getbussinessname=$this->app->userdetails($tokendetails->userid);
				 //print_r($getbussinessname);
				$counter=$this->app->getCounter($tokendetails->counter_id);
				 
				 	 $counterno=$counter['counter_id'];

				foreach($token as $dd)
				{
				   // print_r($tokendetails);
					 $getdepart=$this->app->getdeptnew($tokendetails->Dept_id);
					 
					 $subdepid=$tokendetails->Sub_deptid;
					 $depid=$tokendetails->Dept_id;
					 
					 
					 
					 $getlivetoken=$this->app->getlivetoken($depid,$today);
					// print_r($getlivetoken);
					
					 foreach($getdepart as $deps)
					 {		
					     
					     
					      if($getlivetoken['token']=="")
					 {
					     $livetoken="0";
					 }
					 
					 else
					 {
					     $livetoken="Live "." ". $deps->Dep_code ." ".$getlivetoken['token'];
					 }
					     
				  $dataws[]=array(
				  'mobile'=>$dd->mobile,
				  'token'=>"Token "." ". $deps->Dep_code ." ".$tokendetails->token,
			      'livetoken'=>$livetoken,
				  'Department'=>$deps->department,				 
				  'status'=>$tokendetails->status,	
				  'Bussinessname'=>$getbussinessname['name'],
				    'Counter'=>$counterno
				);
					 }
					
				
				
					//print_r($dataws);
				}
				
				
			}
			//
			
			
			if(!empty($dataws)){
				echo $response= json_encode(array('checkon'=>$dataws));
			}
			else
		{
			
				$response=array('msg'=>'No Data Foud');
				echo $response= json_encode(array('checkon'=>$response));
			
		}
			
		}
		
		
		
	}
	///
	
	
	///
	public function Qrimage(){
		$data=json_decode(file_get_contents("php://input"));
		$loginid=$data->checkon->loginid;
		$image=$data->checkon->image;
		
		if(key($data)=="checkon"){
		    if(!empty($img))
		    {
		      $img=base64_decode($image);
			$imgs = imagecreatefromstring($img);
			if($imgs != false){ 
				$date = strtotime(date('Y-m-d h:i:s'));
				$i="upload/$date.jpg";
			   	imagejpeg($imgs, "$i"); 
			}   
		    }
		    else
		    {
		         //$i=$url.'/upload/nextpng.png';
				 
				 
		    }
		    
			
			
			// $this->db->where('mobile',$mobile);
			// $update=$this->db->update('login',$data);
			$getdepart=$this->app->Qrimageupdate($i,$loginid);
			if($update > 0){
				$response=array('msg'=>'Register Successfully!');
				echo $response= json_encode(array('checkon'=>$response));
			}

		}
	}
	
	////
	
	
	////
	
	public function satechsdeck(){
		$data=json_decode(file_get_contents("php://input"));
	
	  $loginid=$data->checkon->loginid;
	  $departmentid=$data->checkon->departmentid;
	  $subdepartmentid=$data->checkon->subdepartmentid;
	  
		 $day=$data->checkon->day;
		
		 if(key($data)=="checkon"){
		      $getdepart=$this->app->getappointmenttime($loginid,$departmentid,$subdepartmentid);
			  // print_r($getdepart);
			   
			 if(isset($getdepart[$day])){
				//echo $getdepart[$day];
				
		 $dataws=array(
				  'status'=>$getdepart[$day]
				  
				 
				);
	
		if(!empty($dataws)){
				echo $response= json_encode($dataws);
			}
			else
		{
			
				$response=array('msg'=>'No Data Foud');
				echo $response= json_encode(array('checkon'=>$response));
			
		}
				
			}
		    
			
		

		 }
	}
	
	///
	///appointment list
	public function appointmentdata(){
		$data=json_decode(file_get_contents("php://input"));
			 if(key($data)=="checkon"){
		      $getdepart=$this->app->getappointmentdata();
			   //print_r($getdepart);
			 
			   foreach($getdepart as $dep)
			   {
			       
				  $get=$this->app->userdetails($dep->bussiness_id);
				 // print_r($get);
				  
				  ////
				  $status=$this->app->userwebcheck($dep->bussiness_id);
				  $appointstatus=$this->app->appointstatus($dep->bussiness_id);
				  //print_r($appointstatus);
				  if($appointstatus['status']==1)
				  {
				      if($status['status']==1)
				  {
				     $dataws[]=array(
				
				  'name'=>$get['name'],
				  'address'=>$get['address'],
				  'id'=>$get['id']				  
				); 
				  }
				      
				  }
				  
				  
				  ///////
				 	
			    
				
				
			 
			   }
			if(!empty($dataws)){
				echo $response= json_encode(array('data'=>$dataws));
			}
			else
		{
			
				$response=array('msg'=>'No Data Foud');
				echo $response= json_encode(array('checkon'=>$response));
			
		}
			
		 }
	}
	
// 	///appointment book
// 	public function AddAppintment(){
// 		$data=json_decode(file_get_contents("php://input"));
// 		$bookingdate=$data->checkon->bookingdate;
// 		$bookingtime=$data->checkon->bookingtime;
// 		$loginid=$data->checkon->loginid;
// 		$bussinessid=$data->checkon->bussinessid;
// 		$departmentid=$data->checkon->departmentid;
// 		$subdepartmentid=$data->checkon->subdepartmentid;
// 		$currentdate=time();
// 		$subdep=$this->app->getappoitmentdate($bookingdate, $departmentid);
		 
		
// 		if(key($data)=="checkon"){
		    
// 		   if(empty($subdep))
// 		   {
// 			$data=array(
// 				'bussiness_id'=>$bussinessid,
// 				'bookingdate'=>$bookingdate,
// 				'booking_time'=>$bookingtime,
// 				'user_id'=>$loginid,
// 				'appointmenttoken'=>"1",
// 				'departmentid'=>$departmentid,
// 				'subdepartment'=>$subdepartmentid,
// 				'date'=>time()
				
// 			);
// 		   }
// 		   else
// 		   {
// 			    $subdep=$this->app->getMaxappoint($bookingdate,$departmentid);
				
// 				   $token=$subdep['appointmenttoken']+1;
// 			   $data=array(
// 				'bussiness_id'=>$bussinessid,
// 				'bookingdate'=>$bookingdate,
// 				'booking_time'=>$bookingtime,
// 				'user_id'=>$loginid,
// 				'appointmenttoken'=>$token,
// 				'departmentid'=>$departmentid,
// 				'subdepartment'=>$subdepartmentid,
// 				'date'=>time()
				
// 			);
// 		   }
		
// 			$update=$this->db->insert('book_appointment',$data);
// 			if($update > 0){
// 				$response=array('msg'=>'Booking Done !');
// 				echo $response= json_encode(array('checkon'=>$response));
// 			}
// 		}
// 	}
	
// 	/////
	
	///appointment book
	public function AddAppintment(){
		$data=json_decode(file_get_contents("php://input"));
		//print_r($data);
		$bookingdate=$data->checkon->bookingdate;
		$bookingtime=$data->checkon->bookingtime;
		$loginid=$data->checkon->loginid;
		$bussinessid=$data->checkon->bussinessid;
		$departmentid=$data->checkon->departmentid;
		$subdepartmentid=$data->checkon->subdepartmentid;
		$currentdate=time();
		$subdep=$this->app->getappoitmentdate($bookingdate, $departmentid);
		 
		if(key($data)=="checkon"){
		    
		$ss=$this->app->getappointmentno();
	
		$tag="MB";
		 $code=$ss['appointmenttoken'];
		  if($code!=""){
			$value=substr("$code",7);
			 $start_valu="$value";
			 $start_value=str_pad((int)$start_valu+1,6,"0",STR_PAD_LEFT);
			$stud_code=$tag.$start_value;
		  } 
		  else{
			$number="000001";
			$stud_code=$tag.$number;
		  }
			$data=array(
				'bussiness_id'=>$bussinessid,
				'bookingdate'=>$bookingdate,
				'booking_time'=>$bookingtime,
				'user_id'=>$loginid,
				'appointmenttoken'=>$stud_code,
				'departmentid'=>$departmentid,
				'subdepartment'=>$subdepartmentid,
				'date'=>time()
				
			);
		   
		  
		 
		
			$update=$this->db->insert('book_appointment',$data);
			if($update > 0){
				$response=array('msg'=>'Booking Done !');
				echo $response= json_encode(array('checkon'=>$response));
			}
		}
	}
	
	/////
	
	///
	
		///appointment book
	public function cancelappointment(){
		$data=json_decode(file_get_contents("php://input"));
		//print_r($data);
		 $bookingdate=$data->checkon->day;
		$bookingtime=$data->checkon->bookingtime;
		$loginid=$data->checkon->loginid;
		$bussinessid=$data->checkon->bussiness_id;
		$departmentid=$data->checkon->departmentid;
		$subdepartmentid=$data->checkon->subdepartmentid;
		$currentdate=time();
		$subdep=$this->app->getappoitmentcancel($bookingdate, $departmentid,$subdepartmentid,$bookingtime);
		//print_r($subdep);
		  $id=$subdep['id'];
		if(key($data)=="checkon"){
		    
		$ss=$this->app->getappointmentno();
	
	
		 
			$data=array(
				
				'status'=>'1',
				'date'=>time()
				
			);
		   
		  
		 
 		    $this->db->where('id',$id);
 			$update=$this->db->update('book_appointment',$data);
			if($update > 0){
				$response=array('msg'=>'Booking Done !');
				echo $response= json_encode(array('checkon'=>$response));
			}
		}
	}
	
	/////
	
		///appointment book
	public function canceloffer(){
		$data=json_decode(file_get_contents("php://input"));
		//print_r($data);

	
	    $oid=$data->checkon->oid;
		$currentdate=time();
	
		if(key($data)=="checkon"){
		    
		$ss=$this->app->getappointmentno();
	
	
		 
			$data=array(
				
				'status'=>'1',
				'date'=>time()
				
			);
		   
		  
		 
 		    $this->db->where('oid',$oid);
 			$update=$this->db->update('offer',$data);
			if($update > 0){
				$response=array('msg'=>'Booking Done !');
				echo $response= json_encode(array('checkon'=>$response));
			}
		}
	}
	
	/////
	///
	
	public function getappointment(){
		$data=json_decode(file_get_contents("php://input"));
		 $loginid=$data->checkon->bussiness_id;
		  $departmentid=$data->checkon->departmentid;
	      $subdepartmentid=$data->checkon->subdepartmentid;
		  $day=$data->checkon->day;
			 if(key($data)=="checkon"){
			 
			 $getdepart=$this->app->getappointmenttime($loginid,$departmentid,$subdepartmentid);
			//print_r($getdepart);
			 
						 $userdetails=$this->app->userdetails($loginid);
			 //$day=$this->app->getavailabletime($day);
			 
			   $opentime=$getdepart['open_time'];
			    $open_time = date('g:i A',  $opentime);
			 
			  
			  
				$closetime=$getdepart['close_time'];
			
			  $close_time = date('g:i A',  $closetime);
			 $slotdifference=$getdepart['slot_diff'];
			 $currenttime=date('g:i A'); 
			$breakstarttime=$getdepart['break_start_time'];
		   $break_start_time=date('g:i A',  $breakstarttime);
		
		  $currentdated=date('D M d yy');
			  $breakendtime=$getdepart['break_end_time'];
			  $break_end_time=date('g:i A',  $breakendtime);
				
			 $time_slots = $this->prepare($open_time, $close_time, $slotdifference);
			 $breaktime= $this->prepare($break_start_time, $break_end_time, $slotdifference);
			
			array_pop($breaktime);
			
			
			$curr= $this->prepare($open_time, $currenttime, "1");
			//print_r($curr);
				 $getbookedtime=$this->app->getbookedtime($loginid,$departmentid,$subdepartmentid,$day);
			  //print_r($getbookedtime);
			  
			  if(empty($getbookedtime))
			  {
				  if($day==$currentdated)
			{
				 $array_without_strawberries = array_diff($time_slots, $breaktime,$curr);
			}
			else
			{
				 $array_without_strawberries = array_diff($time_slots, $breaktime);
			}
				  
				
		
			//print_r($ww);
				
			  }
			  else
			  {
				 
				  foreach($getbookedtime as $book)
			  {
				 $gettime[]=$book->booking_time;
			  }
			  if($day==$currentdated)
			  {
			
				  $array_without_strawberries = array_diff($time_slots, $breaktime,$gettime,$curr);
				 
			  }
			  else
			  {
				   $array_without_strawberries = array_diff($time_slots, $breaktime,$gettime);
				   
			  }
				
			  }
			  
			    
			 	//print_r($gettime);
				
			
			//print_r($ww);
			$qw=array_values($array_without_strawberries);	
			//print_r($array_without_strawberries);
			$ss=count($qw);
		$morning=array();
		$afternoon=array();
		$evening=array();
			for($i=0; $i<$ss;$i++)
			{
				
				

				
					
				
				//print_r($qw[$i]);
				if($qw[$i]<"12:00 PM")
				{
					
					//print_r($qw[$i]);
					$morning[]=$qw[$i];
					
					
				}
				
				elseif($qw[$i]>="12:00 PM" && $qw[$i]<"16:00 PM")
				{
					//print_r($qw[$i]);
					$afternoon[]=$qw[$i];
				}
				elseif($qw[$i]<=$close_time)
				{
					//print_r($qw[$i]);
					$evening[]=$qw[$i];
				}
				
			
			}
			
			
			$dataws=array(
				  'name'=>$userdetails['name'],
				  'morning'=>$morning,
				  'afternoon'=>$afternoon,
				  'evening'=>$evening
				 
				);
			
			//print_r($dataws);
			if(!empty($dataws)){
				echo $response= json_encode($dataws);
			}
			else
		{
			
				$response=array('msg'=>'No Data Foud');
				echo $response= json_encode(array('checkon'=>$response));
			
		}
	
			
			
		
	}
	}	
	
	/////
	public function AppointDepartment(){
		$data=json_decode(file_get_contents("php://input"));
		if(key($data)=="checkon"){
			$dd=$data->checkon->scanurl;
			if(empty($dd))
			{
				 $depid=$data->checkon->bussiness_id;
			}
			else
			{
				 $depid=$this->sample($dd);
	
			}
				
			 
			
				 $dep=$this->app->getappointdept($depid);
					// print_r($dep);
				foreach($dep as $depart)
				{
				
					$dds[]=$this->app->getdept($depart->department);
				
					
				}
					if(!empty($dds)){
				echo $response= json_encode(array('checkon'=>$dds,'bussinessid'=>$depid));
					
					}
					else
					{
						$response=array('msg'=>'Department Not Found!');
						echo $response= json_encode(array('checkon'=>$response));
					}
					
					
					
		}
		
		
	}
	
	public function AppointsubDepartment(){
		$data=json_decode(file_get_contents("php://input"));
		if(key($data)=="checkon"){
			 $bussinessid=$data->checkon->bussiness_id;
			 $dep=$data->checkon->depid;
			
				 $dep=$this->app->getappointsubss($bussinessid,$dep);
				 
		//	print_r($dep);
				foreach($dep as $depart)
				{
					//print_r($depart);
					$dd[]=$this->app->getsubdeptnew($depart->subdepart);
					//print_r($dd);
					
				}
				if(!empty($dd)){
					$response=array('Data'=>$dd);
					echo $response= json_encode(array('checkon'=>$dd));
					}
					else
					{
						$response=array('msg'=>'Department Not Found!');
						echo $response= json_encode(array('checkon'=>$response));
					}
					
					
		}
		
	}
	///Appointment Booking History
	
	public function Appointhistory(){
		$data=json_decode(file_get_contents("php://input"));
		if(key($data)=="checkon"){
			$dd=$data->checkon->loginid;
			$dep=$this->app->getAppointmenthistory($dd);
			
			foreach($dep as $data)
			{
				$bussinessname=$this->app->getbussnames($data->bussiness_id);
				
				$departmentname=$this->app->getdept($data->departmentid);
				
				
				$subdepartment=$this->app->getsubdeptnew($data->subdepartment);
			    	//print_r($departmentname);
				$dataws[]=array(
				  'bussinessid'=>$bussinessname['id'],
				  'bussinessname'=>$bussinessname['name'],
				  'address'=>$bussinessname['address'],
				  'depid'=>$departmentname['id'],
				  'depname'=>$departmentname['department'],	
				  'subid'=>$subdepartment['id'],
				  'subname'=>$subdepartment['depart_name'],
				  'time'=>$data->booking_time,
				  'status'=>$data->status,
				  'appointdate'=>$data-> bookingdate,
				  'appointmenttoken'=>$data->appointmenttoken
				);
			}
			
			
			
			//print_r($dataws);
			if(!empty($dataws)){
				echo $response= json_encode(array('checkon'=>$dataws));
			}
			else
		{
			
				$response=array('msg'=>'No Data Foud');
				echo $response= json_encode(array('checkon'=>$response));
			
		}
		}
		
	}
	
	
	///////////
	public function prepare($open_time,$close_time,$slotdifference){
	 
	$time_slots = array();
	$start_time    = strtotime($open_time); //change to strtotime
	$end_time      = strtotime($close_time); //change to strtotime
	 
	$add_mins  = $slotdifference * 60;
	 
	while ($start_time <= $end_time) // loop between time
	{
	   $time_slots[] = date("H:i A", $start_time);
	   $start_time += $add_mins; // to check endtime
	}

	return $time_slots;
}


///Appointment Booking History
	
	public function Appointhistorybussiness(){
		$data=json_decode(file_get_contents("php://input"));
		if(key($data)=="checkon"){
			$dd=$data->checkon->loginid;
			$dep=$this->app->getAppointmentbussiness($dd);
			
			foreach($dep as $data)
			{
				$bussinessname=$this->app->getbussnames($data->user_id);
				//print_r($bussinessname);
				$departmentname=$this->app->getdept($data->departmentid);
				
				
				$subdepartment=$this->app->getsubdeptnew($data->subdepartment);
			    	//print_r($departmentname);
				$dataws[]=array(
				  'bussinessid'=>$bussinessname['id'],
				  'bussinessname'=>$bussinessname['name'],
				  'address'=>$bussinessname['address'],
				  'depid'=>$departmentname['id'],
				  'depname'=>$departmentname['department'],	
				  'subid'=>$subdepartment['id'],
				  'subname'=>$subdepartment['depart_name'],
				  'time'=>$data->booking_time,
				  'status'=>$data->status,
				  'appointdate'=>$data-> bookingdate,
				 
				  'appointmenttoken'=>$data->appointmenttoken
				);
			}
			
			
			
			//print_r($dataws);
			if(!empty($dataws)){
				echo $response= json_encode(array('checkon'=>$dataws));
			}
			else
		{
			
				$response=array('msg'=>'No Data Foud');
				echo $response= json_encode(array('checkon'=>$response));
			
		}
		}
		
	}
    public function pay(){
		$data=json_decode(file_get_contents("php://input"));
		if(key($data)=="checkon"){
		
			$getid = $this->sample($data->checkon->loginid);
			$ss=$this->app->userdetails($getid);
		
			$datas=array(
			'name'=>$ss['name'],
			'scanurl'=>$ss['paymentlink']
			);
			if(!empty($datas)){
				echo $response= json_encode(array('checkon'=>$datas));
			}
			else
		{
			
				$response=array('msg'=>'No Data Foud');
				echo $response= json_encode(array('checkon'=>$response));
			
		}
		
		}
		
	}
	
	////
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
	
	
		
}
