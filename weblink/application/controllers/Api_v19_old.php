<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Kolkata');
class Api_v19 extends CI_Controller {
 function __construct(){
       parent::__construct();
   $this->load->database();
   $this->load->model('Api_Model_v12','app');
   $this->load->helper('url');

 }
 public function sample($data){
   $string = explode('/', $data);
   $res = $this->app->getIdByMid($string['6'])['id'];
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
       //$otp="4444";
       $saveData=array(
         'mobile'=>$mobile,
         'firebassid'=>$token,
         'otp'=>$otp,
         'm_id'=>$nmid,
         'start_date'=>time()
       );
      $msg="Dear User, Your mobile verification code for login in MID App is $otp. Please Use this code to verify your mobile No.";
	$this->sendsms($mobile,$msg);
       $checkData=$this->app->AddUser($saveData);
       $id = $this->db->insert_id();
       if($checkData > 0){
         $response=array('msg'=>'OTP Send Successfully!','mobile'=>$mobile,'user_group'=>$data->checkon->user_group);
         echo $response= json_encode(array('checkon'=>$response,'hash'=>$str1,"str2"=>$str2));
       }
     }
     else{
       $otp=rand(1000,9999);
       //$otp="4444";
       if(isset($data->checkon->linked)){
         $omid = $this->app->getMaxMid()['m_id'];
         if($omid == ''){
           $nmid = '0000';
         }else{
           $str1 = substr($omid,3);
           $str1 = $str1 + 1;
           $str2 = str_pad($str1 , 4 , 0 , STR_PAD_LEFT);
           $nmid = $str2;
         }

         $newMobile = "22".rand(1000,9999).$nmid;

         $saveData=array(
           'mobile'=>$newMobile,
           'linked'=>$mobile,
           'firebassid'=>$token,
           'otp'=>$otp,
           'm_id'=>"MID".$nmid,
           'start_date'=>time()
         );
        $msg="Dear User, Your mobile verification code for login in MID App is $otp. Please Use this code to verify your mobile No.";
  				$this->sendsms($mobile,$msg);
         $checkData=$this->app->AddUser($saveData);
         $id = $this->db->insert_id();
         if($checkData > 0){
           $response=array('msg'=>'OTP Send Successfully!','mobile'=>$newMobile,'user_group'=>"1");
           echo $response= json_encode(array('checkon'=>$response,'hash'=>$str1,"str2"=>$str2));
         }
       }else{
         if($mobile=="1234567890" || $mobile=="1234567888" || $mobile=="0888088808" || $mobile=="9689689689"){
             $otp="8888";
         }
         $data=array(
           'otp'=>$otp,
           'firebassid'=>$token
         );
         $msg="Dear User, Your mobile verification code for login in MID App is $otp. Please Use this code to verify your mobile No.";

         if(!empty($check['linked'])){
             $this->sendsms($check['linked'],$msg);
         }else{
             $this->sendsms($mobile,$msg);
         }

         $this->db->where('mobile',$mobile);
         $this->db->where('deleted',0);
         $update=$this->db->update('login',$data);
         if($update > 0){
          $response=array('msg'=>'OTP Send Successfully!','mobile'=>$mobile,'user_group'=>$check['user_group']);
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
   $device_id=$data->checkon->device_id;
   if(key($data)=="checkon"){
     $data=$this->app->checkotp($mobile,$otp);
     if(!empty($data)){
         if($data['user_group']==2){
             if($data['device_id']!=$device_id){
                 $this->app->changeUserStatus(0,$data['id'],$data['company']);

             }
         }
        $linked = $this->app->getAllLinked($mobile);
        $linkedAdmin = $this->app->getAllAdmins($data['id']);
        $linked = array_merge($linked,$linkedAdmin);
       $value=array(
         'otp'=>0,
         'device_id'=>$device_id
       );
       $this->db->where('mobile',$mobile);
       $this->db->where('deleted',0);
       $update=$this->db->update('login',$value);
       if($update > 0){
         $response=array('Data'=>$data);
         echo $response= json_encode(array('checkon'=>$data,'linked'=>$linked));
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
   $ref=$data->checkon->reference;
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
       'baseurl'=>base_url().'User/profile/'.$nmid,
       'reference'=>$ref
     );
     $this->db->where('mobile',$mobile);
     $this->db->where('deleted',0);
     $update=$this->db->update('login',$data);
     
     $check=$this->app->checkMobile($mobile);
      if(!empty($check['id']) && $check['user_group']==1){
         $options=array(
          'bid'=>$check['id'],
          'date_time'=>time()
        );
        $res = $this->app->insertCmpOptions($options);
        $validity = strtotime('+10 days');
        $data=array(
       'premium'=>"1",
       'validity'=>$validity,
       'start_date'=>time()
     );
     $this->db->where('mobile',$mobile);
     $this->db->where('deleted',0);
     $update=$this->db->update('login',$data);
      }
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
     $data=array(
       'shopid'=>$shopid,
       'offer'=>$offers,
       'expireddate'=>strtotime($expireddate),
       'date'=>$currentdate,
       'offerimage'=>$i,
     );
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
   $date = date('Y-m-d H:i:s');

   $string = explode('/', $data->checkon->userid);
   $newQr = $this->app->getLoginIdByQr(end($string));

   if(!empty($newQr)){
     $userid=$newQr['login_id'];
   }else{
     $res = $this->sample($data->checkon->userid);
     $userid=$res;
   }

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
       $up=$this->db->insert('userqrdetails',$data2);
       if($up > 0){
         $response=array('msg'=>'Register Successfully!','id'=>$userid);
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
         $response=array('msg'=>'Register Successfully!','id'=>$userid);
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
 