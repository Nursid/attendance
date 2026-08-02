<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Kolkata');
class Api_v8 extends CI_Controller {
 function __construct(){
       parent::__construct();
   $this->load->database();
   $this->load->model('Api_Model_v8','app');
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
         if($mobile=="1234567890"){
             $otp="4444";
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
      //  $buss=$this->app->getBussinessname($data['BussinessType']);
      //  //print_r($buss);
      //   foreach($buss as $qq)
      //   {
      //     $busq=$qq->catagory;
      //   }
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

            $premium = $dd->premium;
            $requested = 0;
            if($premium==3){
              $requested=1;
            }
            if($dd->validity!="" && $dd->validity>time()){
              $premium = "2";
            }else{
              if($dd->start_date!="" && strtotime('+10 day',$dd->start_date)>time()){
                $premium = "1";
              }else{
                $premium = "0";
              }
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
             'Longitude'=>$dd->Longitude,
             'premium'=>$premium,
             'requested'=>$requested,
             'validity'=>date("d-M-Y",(Int)$dd->validity)
                );

        }


       $this->db->where('mobile',$mobile);
       $this->db->where('deleted',0);

         $response=array('Data'=>$data);
         echo $response= json_encode(array('checkon'=>$ss));

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
            $curr=time();
            if($curr < $value->expireddate){
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
 $url="http://185.136.166.131/domestic/sendsms/bulksms.php?username=checkon&password=checkon&type=TEXT&sender=MIDAPP&mobile=$mobile&entityId=1301160517752005345&templateId=1307161872754873036&message=".urlencode($msg);
 $ch = curl_init($url);
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 $output = curl_exec($ch);
 curl_close($ch);
 }



//////////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
 public function Gettoken(){
   $data = json_decode(file_get_contents("php://input"));
   $date = date('Y-m-d H:i:s');

   if(!empty($data->checkon->userid)){
     $string = explode('/', $data->checkon->userid);
     $newQr = $this->app->getLoginIdByQr(end($string));
     if(!empty($newQr)){
       $userid=$newQr['login_id'];
     }else{
       $userid = $this->sample($data->checkon->userid);
     }

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
         $resSub = $this->app->getsubdept($dep->department_id);
         $hasSub = 0;
         if($resSub){
           $hasSub = 1;
         }
           //print_r($ss);
            $res[] = array(
             'id' => $ss['id'],
             'department' => $ss['department'],
             'hasSub'=>$hasSub,
              'bussiness_id' => $id

             );
       }
     echo $response= json_encode(array('checkon'=>$res,'Bussinessname'=>$bussinessname));
     }
     else if($checkuser['user_group']=='1')
     {


          $name=$checkuser['name'];

       $getdepart=$this->app->getassigneddept($checkuser['id']);
     //print_r($getdepart);

       foreach($getdepart as $dep)
       {
         //print_r($dep);
         $ss=$this->app->getdept($dep->department_id);
         $resSub = $this->app->getsubdept($dep->department_id);
         $hasSub = 0;
         if($resSub){
           $hasSub = 1;
         }
           //print_r($ss);
            $res[]=array(
             'id'=>$ss['id'],
             'department'=>$ss['department'],
             'hasSub'=>$hasSub,
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


   if(key($data)=="checkon"){



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
     $tokenStatus = $this->app->getBusinessTokenStatus($data->checkon->bussinessid);
         if($tokenStatus->token_status==1){
     $loginid=$data->checkon->loginid;
    $group=$data->checkon->group;
    //$subid=$data->checkon->subid;
    //$depid=$data->checkon->depid;
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
        if(isset($data->checkon->depid)){
      $subdep=$this->app->gettokendate($today, $data->checkon->depid);
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
     if(isset($data->checkon->subid)){
          $userdetails=
          $data=array(
         'Dept_id'=>$data->checkon->depid,
         'Sub_deptid'=>$data->checkon->subid,
         'userid'=>$loginid,
         'token'=>"1",
         'date'=>$today,
         'Query'=>$Que,
         'status'=>"0",
         'user_bussiness_id'=>$bussinessid
         );
     }else{
         $userdetails=
          $data=array(
         'Dept_id'=>$data->checkon->depid,
         'userid'=>$loginid,
         'token'=>"1",
         'date'=>$today,
         'Query'=>$Que,
         'status'=>"0",
         'user_bussiness_id'=>$bussinessid
         );
     }
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

        $subdeps=$this->app->getMaxtoken($data->checkon->depid);
        //print_r($subdep)
        $token=$subdeps['token']+1;
        $usertoken=$this->app->userdetails($loginid);
        $firebasetoken=$usertoken['firebassid'];
        $title="MID";
        $offers="Your Token No." ." ".$token;
       $this->push_notification_android($offers,$firebasetoken,$title);
        //



        if(isset($data->checkon->subid)){

      $data=array(
     'Dept_id'=>$data->checkon->depid,
     'Sub_deptid'=>$data->checkon->subid,
     'userid'=>$loginid,
     'token'=>$token,
     'date'=>$today,
     'Query'=>$Que,
     'status'=>"0",
       'user_bussiness_id'=>$bussinessid
   );
        }else{
            $data=array(
     'Dept_id'=>$data->checkon->depid,
     'userid'=>$loginid,
     'token'=>$token,
     'date'=>$today,
     'Query'=>$Que,
     'status'=>"0",
       'user_bussiness_id'=>$bussinessid
   );
        }
   //print_r($data);

   $update=$this->db->insert('token',$data);
   if($update > 0){
     $response=array('msg'=>'token submitted Successfully!');
     echo $response= json_encode(array('checkon'=>$response));


     ///


     ////


   }
       }

    }else{
      $subdeps=$this->app->getDepNullToken();
      $token=$subdeps['token']+1;
      $usertoken=$this->app->userdetails($loginid);
      $firebasetoken=$usertoken['firebassid'];
      $title="MID";
      $offers="Your Token No." ." ".$token;
     $this->push_notification_android($offers,$firebasetoken,$title);
      //

      $data=array(
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
 }
    }
  }

    //By mobilenumber start
    else
    {
    //print_r($checkmobile);
    if(isset($data->checkon->depid)){
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

     $subdep=$this->app->gettokendate($today,$data->checkon->depid);
     $depcode=$this->app->getdept($data->checkon->depid);
   //print_r($depcode);
    //$dep_code=$depcode['Dep_code'];

   if(empty($subdep))
   {
       if(isset($data->checkon->subid)){

     $data=array(
     'Dept_id'=>$data->checkon->depid,
     'Sub_deptid'=>$data->checkon->subid,
     'userid'=>$userid,
     'token'=>"1",
     'date'=>$today,
     'Query'=>$Que,
       'user_bussiness_id'=>$bussinessid,
     'status'=>"0"

   );
       }else{
           $data=array(
     'Dept_id'=>$data->checkon->depid,
     'userid'=>$userid,
     'token'=>"1",
     'date'=>$today,
     'Query'=>$Que,
       'user_bussiness_id'=>$bussinessid,
     'status'=>"0"

   );
       }
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



       $subdep=$this->app->getMaxtoken($data->checkon->depid);
       //print_r($subdep);
        $token=$subdep['token']+1;

        $usertoken=$this->app->userdetails($userid);
        $firebasetoken=$usertoken['firebassid'];
        $title="MID";
        $offers="Your Token No." ." ".$token;
       $this->push_notification_android($offers,$firebasetoken,$title);
       if(isset($data->checkon->subid)){
     $data=array(
     'Dept_id'=>$data->checkon->depid,
     'Sub_deptid'=>$data->checkon->subid,
     'userid'=>$userid,
     'token'=>$token,
     'date'=>$today,
       'user_bussiness_id'=>$bussinessid,
     'Query'=>$Que,
     'status'=>"0"

   );

       }else{
       $data=array(
     'Dept_id'=>$data->checkon->depid,
     'userid'=>$userid,
     'token'=>$token,
     'date'=>$today,
       'user_bussiness_id'=>$bussinessid,
     'Query'=>$Que,
     'status'=>"0"

   );
   }
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

      $subdep=$this->app->gettokendate($today, $data->checkon->depid);
     $depcode=$this->app->getdept($data->checkon->depid);

   //print_r($depcode);



    //$dep_code=$depcode['Dep_code'];

   if(empty($subdep))
   {
     if(isset($data->checkon->subid)){
     $data=array(
     'Dept_id'=>$data->checkon->depid,
     'Sub_deptid'=>$data->checkon->subid,
     'userid'=>$loginid,
     'token'=>"1",
     'date'=>$today,
       'user_bussiness_id'=>$bussinessid,
     'Query'=>$Que,
     'status'=>"0"

   );
     }else{
         $data=array(
     'Dept_id'=>$data->checkon->depid,
     'userid'=>$loginid,
     'token'=>"1",
     'date'=>$today,
       'user_bussiness_id'=>$bussinessid,
     'Query'=>$Que,
     'status'=>"0"

   );
     }
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

       $subdep=$this->app->getMaxtoken($data->checkon->depid);
       //print_r($subdep);
        $token=$subdep['token']+1;

        $usertoken=$this->app->userdetails($loginid);
        $firebasetoken=$usertoken['firebassid'];
        $title="MID";
        $offers="Your Token No. is"." " .$token;
       $this->push_notification_android($offers,$firebasetoken,$title);
       if(isset($data->checkon->subid)){
     $data=array(
     'Dept_id'=>$data->checkon->depid,
     'Sub_deptid'=>$data->checkon->subid,
     'userid'=>$loginid,
     'token'=>$token,
     'date'=>$today,
       'user_bussiness_id'=>$bussinessid,
     'Query'=>$Que,
     'status'=>"0"

   );
       }else{
          $data=array(
     'Dept_id'=>$data->checkon->depid,
     'userid'=>$loginid,
     'token'=>$token,
     'date'=>$today,
       'user_bussiness_id'=>$bussinessid,
     'Query'=>$Que,
     'status'=>"0"

   );
       }
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

      $subdep=$this->app->gettokendate($today,$data->checkon->depid);
     $depcode=$this->app->getdept($data->checkon->depid);
   //print_r($depcode);
    //$dep_code=$depcode['Dep_code'];

    $usertoken=$this->app->userdetails($userid);
        $firebasetoken=$usertoken['firebassid'];
        $title="MID";
        $offers="Your Token No. is 1" ;
       $this->push_notification_android($offers,$firebasetoken,$title);

   if(empty($subdep))
   {
   if(isset($data->checkon->subid)){
     $data=array(
     'Dept_id'=>$data->checkon->depid,
     'Sub_deptid'=>$data->checkon->subid,
     'userid'=>$userid,
     'token'=>"1",
       'user_bussiness_id'=>$bussinessid,
     'date'=>$today,
     'Query'=>$Que,
     'status'=>"0"

   );
   }else{
       $data=array(
     'Dept_id'=>$data->checkon->depid,
     'userid'=>$userid,
     'token'=>"1",
       'user_bussiness_id'=>$bussinessid,
     'date'=>$today,
     'Query'=>$Que,
     'status'=>"0"

   );
   }
   //print_r($data);

   $update=$this->db->insert('token',$data);
   if($update > 0){
     $response=array('msg'=>'token submitted Successfully!');
     echo $response= json_encode(array('checkon'=>$response));
   }
   }

   else
   {
       $subdep=$this->app->getMaxtoken($data->checkon->depid);
       //print_r($subdep);
        $token=$subdep['token']+1;

         $usertoken=$this->app->userdetails($userid);
        $firebasetoken=$usertoken['firebassid'];
        $title="MID";
        $offers="Your Token No. is"." ".$token;
       $this->push_notification_android($offers,$firebasetoken,$title);
       if(isset($data->checkon->subid)){
     $data=array(
     'Dept_id'=>$data->checkon->depid,
     'Sub_deptid'=>$data->checkon->subid,
     'userid'=>$userid,
     'token'=>$token,
       'user_bussiness_id'=>$bussinessid,
     'date'=>$today,
     'Query'=>$Que,
     'status'=>"0"

   );
       }else{
          $data=array(
     'Dept_id'=>$data->checkon->depid,
     'userid'=>$userid,
     'token'=>$token,
       'user_bussiness_id'=>$bussinessid,
     'date'=>$today,
     'Query'=>$Que,
     'status'=>"0"

   );
       }
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
 }else{
   $subdeps=$this->app->getDepNullToken();
   $token=$subdeps['token']+1;
   $usertoken=$this->app->userdetails($loginid);
   $firebasetoken=$usertoken['firebassid'];
   $title="MID";
   $offers="Your Token No." ." ".$token;
  $this->push_notification_android($offers,$firebasetoken,$title);
   //

   $data=array(
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
}
 }
 //By mobilenumber start end
 }
 }
 }
}

//////////////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
 public function getUsertoken(){
   $data=json_decode(file_get_contents("php://input"));
   //print_r($data);
   //$getid = $this->sample($data->checkon->loginid);
   $loginid=$data->checkon->loginid;

   if(key($data)=="checkon"){
     $get=$this->app->getHistory($loginid);
       $today = date("Y-m-d");
     //
     $gettoken=$this->app->gettoken($loginid,$today);
     //print_r($gettoken);


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
         'tokenid'=>$tokendetails->id,
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

         $dataws[]=array(

         'name'=>$get['name'],
         'id'=>$get['id'],
         'address'=>$get['address']
       );



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

 //RITIK

 function getAttendance(){
   $data=json_decode(file_get_contents("php://input"));
   if(key($data)=="checkon"){
     $check=$this->app->checkMobile($data->checkon->mobile);
     if(!empty($check['id']) && $check['user_group']==2){
         if($check['device_id']==$data->checkon->device_id){

           $userCmp = $this->app->getUserCompany($check['id']);

           if(isset($userCmp) && ($userCmp['left_date']=="" || $userCmp['left_date']>time())){

             $company = $this->app->getbussnames($userCmp['business_id']);
             $user_status = $this->app->userCmpStatus($check['id'],$userCmp['business_id']);

             $startMonth = $this->app->attStartMonth($check['id']);

             $holidays = $this->app->getHoliday($userCmp['business_id']);

             $doj = $this->app->getDoj($check['id'],$userCmp['business_id']);

             $holiday_array = array();
             if($holidays){
               foreach($holidays as $holiday){
                 $holiday_array[] = array(
                   'date'=>date('d.m.Y',$holiday->date),
                 );
               }
             }

             $groups = $this->app->getUserGroup($check['business_group']);
             $grp = array();

             if($groups){
               $weekly_off = explode(",",$groups->weekly_off);
               $shift_start = $groups->shift_start;
               $shift_end = $groups->shift_end;
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
             }

             $currentMonth = strtotime(date("d-m-Y",strtotime($data->checkon->month." months")));
             $startTimestamp = strtotime(date("01-m-Y 00:00:00",$currentMonth));
             $endTimestamp = strtotime(date("t-m-Y 12:59:59",$currentMonth));
             $month = date("F Y",$currentMonth);
             $res = $this->app->getUserAttendance($check['id'],$startTimestamp,$endTimestamp);
             $prev = 0;
             $next = 0;

             if($startMonth->io_time<$startTimestamp){
               $prev=1;
             }
             if($startTimestamp<strtotime(date("01-m-Y 00:00:00",time()))){
               $next = 1;
             }

             $cmp_array = array(
               'company_name'=>$company['name'],
               'company_image'=>$company['image'],
               'company_mac'=>$company['mac'],
               'wifi_strength'=>$company['strength'],
               'status'=>$user_status['user_status'],
               'shift_start'=>$shift_start,
               'shift_end'=>$shift_end,
               'preMonth'=>$prev,
               'nextMonth'=>$next,
               'month'=>$month,
               'manager'=>$check['manager']
             );

             $new_array=array();
             if(!empty($res)){
               foreach($res as $at){
                 $date = date("d.m.Y");
                 $match_date = date('d.m.Y', $at->io_time);

                 $id = array_search($match_date,array_column($new_array,'date'));
                 $off = array_search(date('N',$at->io_time),array_column($grp,'day_off'));
                 $holi = array_search($match_date,array_column($holiday_array,'date'));

                 if(!is_bool($off)){
                   $weekOff = "1";
                 }else{
                   $weekOff = "0";
                 }

                 if(!is_bool($holi)){
                   $holiday="1";
                 }else{
                   $holiday="0";
                 }
                 $data = array();
                 if(!is_bool($id)){
                   $new_array[$id]['data'][] = array(
                    'id'=>$at->id,
                     'mode'=>$at->mode,
                     'time'=>date('h:i A', $at->io_time),
                          'comment'=>$at->comment."\n".$at->emp_comment,
                         'latitude'=>$at->latitude,
                         'longitude'=>$at->longitude,
                         'verified'=>$at->verified,
                         'location'=>$at->location
                   );
                 }else{
                   $data[] = array(
                    'id'=>$at->id,
                     'mode'=>$at->mode,
                     'time'=>date('h:i A', $at->io_time),
                                       'comment'=>$at->comment."\n".$at->emp_comment,
                         'latitude'=>$at->latitude,
                         'longitude'=>$at->longitude,
                         'verified'=>$at->verified,
                         'location'=>$at->location
                   );
                   $res_day = date('d M', $at->io_time);
                   if($date==$match_date){
                     $res_day = "Today";
                   }

                   $new_array[] =array(
                     'date'=>$match_date,
                     'day'=>$res_day,
                     'weekly_off'=>$weekOff,
                     'holiday'=>$holiday,
                     'data'=> $data
                   );
                 }
               }
             }
             $month_array=array();
             $m_length = date("d",$endTimestamp);
             if($endTimestamp>time()){
               $m_length = date("d");
             }
             for($d=0;$d<$m_length;$d++){
               $checkDate =date("d.m.Y",strtotime(date("Y-m-d",$startTimestamp)." +".$d." days"));
               $id = array_search($checkDate,array_column($new_array,'date'));
               $off = array_search(date('N',strtotime($checkDate)),array_column($grp,'day_off'));
               $holi = array_search($checkDate,array_column($holiday_array,'date'));

               if($doj['doj']!="" && strtotime($checkDate)>=$doj['doj']){
                   if(!is_bool($off)){
                     $weekOff = "1";
                   }else{
                     $weekOff = "0";
                   }

                   if(!is_bool($holi)){
                     $holiday="1";
                   }else{
                     $holiday="0";
                   }

                   if(!is_bool($id)){
                     $month_array[] = $new_array[$id];
                   }else{
                     $date = date("d.m.Y");
                     $res_day = date('d M', strtotime($checkDate));
                     if($date==$checkDate){
                       $res_day = "Today";
                     }

                     $month_array[] =array(
                       'date'=>$checkDate,
                       'day'=>$res_day,
                       'weekly_off'=>$weekOff,
                       'holiday'=>$holiday,
                       'data'=> []
                     );
                   }
               }

             }
               if($user_status['user_status']==1){
             rsort($month_array);
                   echo $response= json_encode(array('checkon'=>$month_array,'company_data'=>$cmp_array,'offline'=>$res,'holiday'=>$holidays,'group'=>$groups));
           }else{
               $res = array('msg'=>'Currently Inactive','status'=>'3');
               echo $response= json_encode(array('checkon'=>$res,'company_data'=>$cmp_array));
           }

           }else{
             $cmp_array = array('company_name'=>"",'company_image'=>"",'status'=>'0','preMonth'=>0,'nextMonth'=>0,'month'=>'0','manager'=>'0');
             $res = array('msg'=>'Please Add Company','status'=>'0');
             echo $response= json_encode(array('checkon'=>$res,'company_data'=>$cmp_array));
           }
         }else{
           $cmp_array = array('logout'=>'1');
           echo $response= json_encode(array('checkon'=>$cmp_array));
       }
     }
   }
 }

 function addUserAttendance(){
   $data=json_decode(file_get_contents("php://input"));
   if(key($data)=="checkon"){
     $check=$this->app->checkMobile($data->checkon->mobile);
     if(!empty($check['id']) && $check['user_group']==2){

       $string = explode('/', $data->checkon->business_id);
       $newQr = $this->app->getLoginIdByQr(end($string));
       if(!empty($newQr)){
         $loginid=$newQr['login_id'];
       }else{
         $cmp = $this->app->getbyMid($data->checkon->business_id);
         $loginid = $cmp['id'];
       }

       $user_status = $this->app->userCmpStatus($check['id'],$loginid);

       $userCmp = $this->app->getUserCompany($check['id']);

       if( !empty($userCmp['business_id']) && $userCmp['business_id']==$loginid){
         if($user_status['user_status']=="1"){
           $data = array(
             'bussiness_id'=>$loginid,
             'user_id'=>$check['id'],
             'mode'=>$data->checkon->mode,
             'comment'=>"",
             'latitude'=>$data->checkon->latitude,
             'longitude'=>$data->checkon->longitude,
             'location'=>$data->checkon->location,
             'emp_comment'=>$data->checkon->empComment,
             'verified'=>$data->checkon->verified,
             'manual'=>$data->checkon->manual,
             'io_time'=>time(),
             'date'=>time()
           );
           $res = $this->app->insertAttendance($data);
           if($res == 1){
             $sendRes = array('msg'=>'Attendance added Successfully','status'=>'1');
           }else{
             $sendRes = array('msg'=>'Failed to Add','status'=>'0');
           }
           echo $response= json_encode(array('checkon'=>$sendRes));
         }else{
           $sendRes = array('msg'=>'Not Active','status'=>'0');
           echo $response= json_encode(array('checkon'=>$sendRes));
         }

       }else{
         $sendRes = array('msg'=>'Wrong Company QR','status'=>'0');
         echo $response= json_encode(array('checkon'=>$sendRes));
       }
     }
   }
 }

 function addCompany(){
   $data=json_decode(file_get_contents("php://input"));
   if(key($data)=="checkon"){
     $check=$this->app->checkMobile($data->checkon->mobile);
     if(!empty($check['id']) && $check['user_group']==2){
      $userCmp = $this->app->getUserCompany($check['id']);

      if(empty($userCmp) || ($userCmp['left_date']=="" || $userCmp['left_date']<time())){

         $string = explode('/', $data->checkon->business_id);
         $newQr = $this->app->getLoginIdByQr(end($string));
         if(!empty($newQr)){
           $loginid=$newQr['login_id'];
           $cmp_group = $newQr['user_group'];
         }else{
           $cmp = $this->app->getbyMid($data->checkon->business_id);
           $loginid = $cmp['id'];
           $cmp_group = $cmp['user_group'];
         }

         if($cmp_group==1){
           $data = array(
             'business_id'=>$loginid,
             'user_id'=>$check['id'],
             'doj'=>strtotime(date('d-m-Y')),
             'date'=>time()
           );
           $uCheck = $this->app->checkUserAlready($loginid,$check['id']);
           if(count($uCheck)==0){
              $req = $this->app->addUserCmpStatus($data);
              if($req){
                 $res = $this->app->updateUserCompany($check['id'],$loginid,strtotime(date('d-m-Y')));
                 $res = $this->app->updateUserDoj($check['id'],$loginid,'',strtotime(date('d-m-Y')));
                 if($res){
                  $sendRes = array('msg'=>'Company Added','status'=>'1','company_id'=>$loginid);
                  echo $response= json_encode(array('checkon'=>$sendRes));
                 }
              }
           }else{
               $res = $this->app->updateUserCompany($check['id'],$loginid,strtotime(date('d-m-Y')));
               $res = $this->app->updateUserDoj($check['id'],$loginid,'',strtotime(date('d-m-Y')));
               $sendRes = array('msg'=>'Company Added','status'=>'1','company_id'=>$loginid);
           echo $response= json_encode(array('checkon'=>$sendRes));
           }

         }else{
           $sendRes = array('msg'=>'Please Scan Company QR','status'=>'0');
           echo $response= json_encode(array('checkon'=>$sendRes));
         }

       }else{
         $sendRes = array('msg'=>'Failed to Add Company','status'=>'0');
         echo $response= json_encode(array('checkon'=>$sendRes));
       }
     }
   }
 }

 function getCompanyUsers(){
   $data=json_decode(file_get_contents("php://input"));
   if(key($data)=="checkon"){
     $check=$this->app->checkMobile($data->checkon->mobile);
      if($check['validity']!="" && $check['validity']>time()){

      }else{
        if($check['start_date']!="" && strtotime('+10 day',$check['start_date'])>time()){
          $this->app->updatePrimeAtt($check['id'],1);
        }else{
          $this->app->updatePrimeAtt($check['id'],0);
        }
      }
      $check=$this->app->checkMobile($data->checkon->mobile);

     if(!empty($check['id']) && $check['user_group']==1 && $check['prime_att']==1){
       $users_data = $this->app->getCompanyUsers($check['id']);
       $start_time = strtotime(date("d-m-Y 00:00:00",strtotime($data->checkon->date)));
       $end_time = strtotime(date("d-m-Y 23:59:59",strtotime($data->checkon->date)));

       $holidays = $this->app->getHoliday($check['id']);
       $holiday_array = array();
       if($holidays){
         foreach($holidays as $holiday){
           $holiday_array[] = array(
             'date'=>date('d.m.Y',$holiday->date),
           );
         }
       }
       if(!empty($users_data)){
         $new_array = array();
         foreach($users_data as $user){
             if(($user->doj!="" || $start_time>=$user->doj) && ($user->left_date=="" || $start_time<$user->left_date)){
                 $user_at = $this->app->getUserAttendanceByDate($start_time,$end_time,$user->user_id,$check['id'],1);
                 $odAttendance = $this->app->getUserOdAttendanceByDate($start_time,$end_time,$user->user_id,$check['id']);
                 $odData = array();
               $data = array();

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

               $off = array_search(date('N',$start_time),array_column($grp,'day_off'));
               $holi = array_search(date('d.m.Y',$start_time),array_column($holiday_array,'date'));
            if(!empty($day_shift_start)){
                 if($day_shift_start[date('N',$start_time)-1]!=null){
                    $shift_start = $day_shift_start[date('N',$start_time)-1];
                 }

                }
              if(!empty($day_shift_end)){
                 if($day_shift_end[date('N',$start_time)-1]!=null){
                    $shift_end = $day_shift_end[date('N',$start_time)-1];
                 }
              }
               if(!is_bool($off)){
                 $weekOff = "1";
               }else{
                 $weekOff = "0";
               }

               if(!is_bool($holi)){
                 $holiday="1";
               }else{
                 $holiday="0";
               }
               if(!empty($user_at)){
                 foreach($user_at as $at){
                   $data[] = array(
                    'id'=>$at->id,
                     'mode'=>$at->mode,
                     'time'=>date('h:i A', $at->io_time),
                     'comment'=>$at->comment."\n".$at->emp_comment,
                     'latitude'=>$at->latitude,
                     'longitude'=>$at->longitude,
                     'verified'=>$at->verified,
                     'selfie'=>$at->selfie,
                     'manual'=>$at->manual,
                     'location'=>$at->location
                   );
                 }
               }else{
                 $data = array();
               }
               if(!empty($odAttendance)){
                 foreach($odAttendance as $od){
                     $odselfie = $od->selfie;
                      if(!empty($odselfie)){
                          $url=base_url();
                          $odselfie=$url.'/'.$odselfie;
                          $im = file_get_contents($odselfie);
                          $odselfie = base64_encode($im);
                      }
                   $odData[] = array(
                    'id'=>$od->id,
                     'mode'=>$od->mode,
                     'time'=>$od->io_time,
                     'comment'=>$od->emp_comment,
                     'latitude'=>$od->latitude,
                     'longitude'=>$od->longitude,
                     'verified'=>$od->verified,
                     'location'=>$od->location,
                     'selfie'=>$odselfie
                   );
                 }
               }else{
                 $odData = array();
               }
               $new_array[] =array(
                 'user_id'=>$user->user_id,
                 'name'=>$user->name,
                 'image'=>$user->image,
                 'date'=>date("d M Y",$start_time),
                 'user_status'=>$user->user_status,
                 'weekly_off'=>$weekOff,
                 'holiday'=>$holiday,
                 'shift_start'=>$shift_start,
                 'shift_end'=>$shift_end,
                 'group_name'=>$group_name,
                 'designation'=>$user->designation,
                 'data'=> $data,
                 'odData'=>$odData
               );
             }
         }
         echo $response= json_encode(array('checkon'=>$new_array));
       }else{
         $res=array('msg'=>'No Data Foud');
         echo $response= json_encode(array('checkon'=>$res));
       }
     }else{
         $res=array('msg'=>'Currently Inactive','prime'=>'0');
         echo $response= json_encode(array('checkon'=>$res));
     }
   }
 }

   function updateComapanyMac(){
   $data=json_decode(file_get_contents("php://input"));
   if(key($data)=="checkon"){
     $check=$this->app->checkMobile($data->checkon->mobile);
     $cmpMac = $this->app->getCompanyMac($check['id']);
     if(!empty($check['id']) && $check['user_group']==1 && !empty($data->checkon->mac)){
       $res = $this->app->updateCompanyMac($check['id'],$data->checkon->ssid,$data->checkon->mac,$data->checkon->strength);
       if($res){
         $cmpMac = $this->app->getCompanyMac($check['id']);
         $sendRes = array('msg'=>'Wifi Added','status'=>'1','data'=>$cmpMac);
         echo $response= json_encode(array('checkon'=>$sendRes));
       }else{
         $sendRes = array('msg'=>'Failed to Add Wifi','status'=>'0','data'=>$cmpMac);
         echo $response= json_encode(array('checkon'=>$sendRes));
       }
     }else{
       $sendRes = array('msg'=>'','status'=>'0','data'=>$cmpMac);
       echo $response= json_encode(array('checkon'=>$sendRes));
     }
   }
 }

 function changeUserStatus(){
   $data=json_decode(file_get_contents("php://input"));
   if(key($data)=="checkon"){
     $check=$this->app->checkMobile($data->checkon->mobile);
     if(!empty($check['id']) && $check['user_group']==1){
       $res = $this->app->changeUserStatus($data->checkon->status,$data->checkon->user_id,$check['id']);
       if($res){
         if($data->checkon->status==1){
           $sendRes = array('msg'=>'Employee Activated Successfully','status'=>'1');
         }else{
           $sendRes = array('msg'=>'Employee Inactivated Successfully','status'=>'1');
         }
         echo $response= json_encode(array('checkon'=>$sendRes));
       }else{
         $sendRes = array('msg'=>'Failed to change user status','status'=>'0');
         echo $response= json_encode(array('checkon'=>$sendRes));
       }
     }
   }
 }

 function getPresentAtt(){
   $data=json_decode(file_get_contents("php://input"));
   if(key($data)=="checkon"){
     $check=$this->app->checkMobile($data->checkon->mobile);
     if(!empty($check['id']) && $check['user_group']==2){
      $userCmp = $this->app->getUserCompany($check['id']);

      if($userCmp['left_date']=="" || $userCmp['left_date']>time()){
         $company = $this->app->getbussnames($userCmp['business_id']);
         $user_status = $this->app->userCmpStatus($check['id'],$userCmp['business_id']);
         $startMonth = $this->app->attStartMonth($check['id']);

         $holidays = $this->app->getHoliday($userCmp['business_id']);

         $holiday_array = array();
         if($holidays){
           foreach($holidays as $holiday){
             $holiday_array[] = array(
               'date'=>date('d.m.Y',$holiday->date),
             );
           }
         }

         $groups = $this->app->getUserGroup($check['business_group']);
         $grp = array();

         if($groups){
           $weekly_off = explode(",",$groups->weekly_off);
           $shift_start = $groups->shift_start;
           $shift_end = $groups->shift_end;
           foreach($weekly_off as $key=>$off){
             if($off==1){
               $grp[] = array(
                 'day_off'=>$key+1
               );
             }
           }
         }

         $currentMonth = strtotime(date("d-m-Y",strtotime($data->checkon->month." months")));
         $startTimestamp = strtotime(date("01-m-Y 00:00:00",$currentMonth));
         $endTimestamp = strtotime(date("t-m-Y 12:59:59",$currentMonth));
         $month = date("F Y",$currentMonth);
         $res = $this->app->getUserAttendance($check['id'],$startTimestamp,$endTimestamp);

         $new_array=array();
         if(!empty($res)){
           foreach($res as $at){
             $date = date("d.m.Y");
             $match_date = date('d.m.Y', $at->io_time);

             $id = array_search($match_date,array_column($new_array,'date'));
             $off = array_search(date('N',$at->io_time),array_column($grp,'day_off'));
             $holi = array_search($match_date,array_column($holiday_array,'date'));

             if(!is_bool($off)){
               $weekOff = "1";
             }else{
               $weekOff = "0";
             }

             if(!is_bool($holi)){
               $holiday="1";
             }else{
               $holiday="0";
             }
             $data = array();
             if(!is_bool($id)){
               $new_array[$id]['data'][] = array(
                'id'=>$at->id,
                 'mode'=>$at->mode,
                 'time'=>date('h:i A', $at->io_time),
                                   'comment'=>$at->comment."\n".$at->emp_comment,
                     'latitude'=>$at->latitude,
                     'longitude'=>$at->longitude,
                     'verified'=>$at->verified,
                     'location'=>$at->location
               );
             }else{
               $data[] = array(
                'id'=>$at->id,
                 'mode'=>$at->mode,
                 'time'=>date('h:i A', $at->io_time),
                                   'comment'=>$at->comment."\n".$at->emp_comment,
                     'latitude'=>$at->latitude,
                     'longitude'=>$at->longitude,
                     'verified'=>$at->verified,
                     'location'=>$at->location
               );
               $res_day = date('d M', $at->io_time);
               if($date==$match_date){
                 $res_day = "Today";
               }

               $new_array[] =array(
                 'date'=>$match_date,
                 'day'=>$res_day,
                 'weekly_off'=>$weekOff,
                 'holiday'=>$holiday,
                 'data'=> $data
               );
             }
           }
         }
         echo $response= json_encode(array('checkon'=>$new_array));
       }
     }
   }
 }

 function getAbsentAtt(){
   $data=json_decode(file_get_contents("php://input"));
   if(key($data)=="checkon"){
     $check=$this->app->checkMobile($data->checkon->mobile);
     if(!empty($check['id']) && $check['user_group']==2){
      $userCmp = $this->app->getUserCompany($check['id']);

      if($userCmp['left_date']=="" || $userCmp['left_date']>time()){
         $company = $this->app->getbussnames($userCmp['business_id']);
         $user_status = $this->app->userCmpStatus($check['id'],$userCmp['business_id']);
         $startMonth = $this->app->attStartMonth($check['id']);

         $holidays = $this->app->getHoliday($userCmp['business_id']);

         $holiday_array = array();
         if($holidays){
           foreach($holidays as $holiday){
             $holiday_array[] = array(
               'date'=>date('d.m.Y',$holiday->date),
             );
           }
         }

         $groups = $this->app->getUserGroup($check['business_group']);
         $grp = array();

         if($groups){
           $weekly_off = explode(",",$groups->weekly_off);
           $shift_start = $groups->shift_start;
           $shift_end = $groups->shift_end;
           foreach($weekly_off as $key=>$off){
             if($off==1){
               $grp[] = array(
                 'day_off'=>$key+1
               );
             }
           }
         }

         $currentMonth = strtotime(date("d-m-Y",strtotime($data->checkon->month." months")));
         $startTimestamp = strtotime(date("01-m-Y 00:00:00",$currentMonth));
         $endTimestamp = strtotime(date("t-m-Y 12:59:59",$currentMonth));
         $month = date("F Y",$currentMonth);
         $res = $this->app->getUserAttendance($check['id'],$startTimestamp,$endTimestamp);

         $new_array=array();
         if(!empty($res)){
           foreach($res as $at){
             $date = date("d.m.Y");
             $match_date = date('d.m.Y', $at->io_time);

             $id = array_search($match_date,array_column($new_array,'date'));
             $off = array_search(date('N',$at->io_time),array_column($grp,'day_off'));
             $holi = array_search($match_date,array_column($holiday_array,'date'));

             if(!is_bool($off)){
               $weekOff = "1";
             }else{
               $weekOff = "0";
             }

             if(!is_bool($holi)){
               $holiday="1";
             }else{
               $holiday="0";
             }
             $data = array();
             if(!is_bool($id)){
               $new_array[$id]['data'][] = array(
                'id'=>$at->id,
                 'mode'=>$at->mode,
                 'time'=>date('h:i A', $at->io_time),
                                   'comment'=>$at->comment."\n".$at->emp_comment,
                     'latitude'=>$at->latitude,
                     'longitude'=>$at->longitude,
                     'verified'=>$at->verified,
                     'location'=>$at->location
               );
             }else{
               $data[] = array(
                'id'=>$at->id,
                 'mode'=>$at->mode,
                 'time'=>date('h:i A', $at->io_time),
                                   'comment'=>$at->comment."\n".$at->emp_comment,
                     'latitude'=>$at->latitude,
                     'longitude'=>$at->longitude,
                     'verified'=>$at->verified,
                     'location'=>$at->location
               );
               $res_day = date('d M', $at->io_time);
               if($date==$match_date){
                 $res_day = "Today";
               }

               $new_array[] =array(
                 'date'=>$match_date,
                 'day'=>$res_day,
                 'weekly_off'=>$weekOff,
                 'holiday'=>$holiday,
                 'data'=> $data
               );
             }
           }
         }
         $month_array=array();
         $m_length = date("d",$endTimestamp);
         if($endTimestamp>time()){
           $m_length = date("d");
         }
         for($d=0;$d<$m_length;$d++){
           $checkDate =date("d.m.Y",strtotime(date("Y-m-d",$startTimestamp)." +".$d." days"));
           $id = array_search($checkDate,array_column($new_array,'date'));
           $off = array_search(date('N',strtotime($checkDate)),array_column($grp,'day_off'));
           $holi = array_search($checkDate,array_column($holiday_array,'date'));

           if($check['doj']!="" && strtotime($checkDate)>=$check['doj']){
               if(!is_bool($off)){
                 $weekOff = "1";
               }else{
                 $weekOff = "0";
               }

               if(!is_bool($holi)){
                 $holiday="1";
               }else{
                 $holiday="0";
               }

               if(is_bool($id)){
                 $date = date("d.m.Y");
                 $res_day = date('d M', strtotime($checkDate));
                 if($date==$checkDate){
                   $res_day = "Today";
                 }
                 $month_array[] =array(
                   'date'=>$checkDate,
                   'day'=>$res_day,
                   'weekly_off'=>$weekOff,
                   'holiday'=>$holiday,
                   'data'=> []
                 );
               }
           }
         }
         rsort($month_array);
         echo $response= json_encode(array('checkon'=>$month_array));
       }
     }
   }
 }

 function getCompanyPresentUsers(){
   $data=json_decode(file_get_contents("php://input"));
   if(key($data)=="checkon"){
     $check=$this->app->checkMobile($data->checkon->mobile);
     if(!empty($check['id']) && $check['user_group']==1 && $check['prime_att']==1){
       $users_data = $this->app->getCompanyUsers($check['id']);
       $start_time = strtotime(date("d-m-Y 00:00:00",strtotime($data->checkon->date)));
       $end_time = strtotime(date("d-m-Y 23:59:59",strtotime($data->checkon->date)));

       $holidays = $this->app->getHoliday($check['id']);
       $holiday_array = array();
       if($holidays){
         foreach($holidays as $holiday){
           $holiday_array[] = array(
             'date'=>date('d.m.Y',$holiday->date),
           );
         }
       }
       if(!empty($users_data)){
         $new_array = array();
         foreach($users_data as $user){
             if(($user->doj!="" || $start_time>=$user->doj) && ($user->left_date=="" || $start_time<$user->left_date)){
                   $user_at = $this->app->getUserAttendanceByDate($start_time,$end_time,$user->user_id,$check['id'],1);
                   $odAttendance = $this->app->getUserOdAttendanceByDate($start_time,$end_time,$user->user_id,$check['id']);
                 $odData = array();
                   $data = array();

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

                   $off = array_search(date('N',$start_time),array_column($grp,'day_off'));
                   $holi = array_search(date('d.m.Y',$start_time),array_column($holiday_array,'date'));
                if(!empty($day_shift_start)){
                 if($day_shift_start[date('N',$start_time)-1]!=null){
                    $shift_start = $day_shift_start[date('N',$start_time)-1];
                 }

                }
              if(!empty($day_shift_end)){
                 if($day_shift_end[date('N',$start_time)-1]!=null){
                    $shift_end = $day_shift_end[date('N',$start_time)-1];
                 }
              }
                   if(!is_bool($off)){
                     $weekOff = "1";
                   }else{
                     $weekOff = "0";
                   }

                   if(!is_bool($holi)){
                     $holiday="1";
                   }else{
                     $holiday="0";
                   }

                   if(!empty($user_at)){
                     foreach($user_at as $at){
                       $data[] = array(
                        'id'=>$at->id,
                         'mode'=>$at->mode,
                         'time'=>date('h:i A', $at->io_time),
                         'comment'=>$at->comment."\n".$at->emp_comment,
                         'latitude'=>$at->latitude,
                         'longitude'=>$at->longitude,
                         'verified'=>$at->verified,
                         'selfie'=>$at->selfie,
                         'manual'=>$at->manual,
                         'location'=>$at->location
                       );
                     }
                     if(!empty($odAttendance)){
                 foreach($odAttendance as $od){
                   $odselfie = $od->selfie;
                      if(!empty($odselfie)){
                          $url=base_url();
                          $odselfie=$url.'/'.$odselfie;
                          $im = file_get_contents($odselfie);
                          $odselfie = base64_encode($im);
                      }
                   $odData[] = array(
                    'id'=>$od->id,
                     'mode'=>$od->mode,
                     'time'=>$od->io_time,
                     'comment'=>$od->emp_comment,
                     'latitude'=>$od->latitude,
                     'longitude'=>$od->longitude,
                     'verified'=>$od->verified,
                     'location'=>$od->location,
                     'selfie'=>$odselfie
                   );
                 }
               }else{
                 $odData = array();
               }
               $new_array[] =array(
                 'user_id'=>$user->user_id,
                 'name'=>$user->name,
                 'image'=>$user->image,
                 'date'=>date("d M Y",$start_time),
                 'user_status'=>$user->user_status,
                 'weekly_off'=>$weekOff,
                 'holiday'=>$holiday,
                 'shift_start'=>$shift_start,
                 'shift_end'=>$shift_end,
                 'group_name'=>$group_name,
                 'designation'=>$user->designation,
                 'data'=> $data,
                 'odData'=>$odData
               );

                   }
             }
         }
         echo $response= json_encode(array('checkon'=>$new_array));
       }else{
         $res=array('msg'=>'No Data Foud');
         echo $response= json_encode(array('checkon'=>$res));
       }
     }
   }
 }
 function getCompanyAbsentUsers(){
   $data=json_decode(file_get_contents("php://input"));
   if(key($data)=="checkon"){
     $check=$this->app->checkMobile($data->checkon->mobile);
     if(!empty($check['id']) && $check['user_group']==1 && $check['prime_att']==1){
       $users_data = $this->app->getCompanyUsers($check['id']);
       $start_time = strtotime(date("d-m-Y 00:00:00",strtotime($data->checkon->date)));
       $end_time = strtotime(date("d-m-Y 23:59:59",strtotime($data->checkon->date)));

       $holidays = $this->app->getHoliday($check['id']);
       $holiday_array = array();
       if($holidays){
         foreach($holidays as $holiday){
           $holiday_array[] = array(
             'date'=>date('d.m.Y',$holiday->date),
           );
         }
       }
       if(!empty($users_data)){
         $new_array = array();
         foreach($users_data as $user){
             if(($user->doj!="" || $start_time>=$user->doj) && ($user->left_date=="" || $start_time<$user->left_date)){
                   $user_at = $this->app->getUserAttendanceByDate($start_time,$end_time,$user->user_id,$check['id'],1);
                   $odAttendance = $this->app->getUserOdAttendanceByDate($start_time,$end_time,$user->user_id,$check['id']);
                 $odData = array();
                   $data = array();
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
                   $off = array_search(date('N',$start_time),array_column($grp,'day_off'));
                   $holi = array_search(date('d.m.Y',$start_time),array_column($holiday_array,'date'));
                if(!empty($day_shift_start)){
                 if($day_shift_start[date('N',$start_time)-1]!=null){
                    $shift_start = $day_shift_start[date('N',$start_time)-1];
                 }

                }
              if(!empty($day_shift_end)){
                 if($day_shift_end[date('N',$start_time)-1]!=null){
                    $shift_end = $day_shift_end[date('N',$start_time)-1];
                 }
              }
                   if(!is_bool($off)){
                     $weekOff = "1";
                   }else{
                     $weekOff = "0";
                   }

                   if(!is_bool($holi)){
                     $holiday="1";
                   }else{
                     $holiday="0";
                   }

                   if(empty($user_at)){
                     if(!empty($odAttendance)){
                 foreach($odAttendance as $od){
                   $odselfie = $od->selfie;
                      if(!empty($odselfie)){
                          $url=base_url();
                          $odselfie=$url.'/'.$odselfie;
                          $im = file_get_contents($odselfie);
                          $odselfie = base64_encode($im);
                      }
                   $odData[] = array(
                    'id'=>$od->id,
                     'mode'=>$od->mode,
                     'time'=>$od->io_time,
                     'comment'=>$od->emp_comment,
                     'latitude'=>$od->latitude,
                     'longitude'=>$od->longitude,
                     'verified'=>$od->verified,
                     'location'=>$od->location,
                     'selfie'=>$odselfie
                   );
                 }
               }else{
                 $odData = array();
               }
               $new_array[] =array(
                 'user_id'=>$user->user_id,
                 'name'=>$user->name,
                 'image'=>$user->image,
                 'date'=>date("d M Y",$start_time),
                 'user_status'=>$user->user_status,
                 'weekly_off'=>$weekOff,
                 'holiday'=>$holiday,
                 'shift_start'=>$shift_start,
                 'shift_end'=>$shift_end,
                 'group_name'=>$group_name,
                 'designation'=>$user->designation,
                 'data'=> $data,
                 'odData'=>$odData
               );
                   }
             }
         }
         echo $response= json_encode(array('checkon'=>$new_array));
       }else{
         $res=array('msg'=>'No Data Foud');
         echo $response= json_encode(array('checkon'=>$res));
       }
     }
   }
 }


function getCompanyUsersByStatus(){
   $data=json_decode(file_get_contents("php://input"));
   if(key($data)=="checkon"){
     $check=$this->app->checkMobile($data->checkon->mobile);
     if(!empty($check['id']) && $check['user_group']==1 && $check['prime_att']==1){
       $users_data = $this->app->getCompanyUsersByStatus($check['id'],$data->checkon->status);
       $start_time = strtotime(date("d-m-Y 00:00:00",strtotime($data->checkon->date)));
       $end_time = strtotime(date("d-m-Y 23:59:59",strtotime($data->checkon->date)));

       $holidays = $this->app->getHoliday($check['id']);
       $holiday_array = array();
       if($holidays){
         foreach($holidays as $holiday){
           $holiday_array[] = array(
             'date'=>date('d.m.Y',$holiday->date),
           );
         }
       }
       if(!empty($users_data)){
         $new_array = array();
         foreach($users_data as $user){
           if(($user->doj!="" || $start_time>=$user->doj) && ($user->left_date=="" || $start_time<$user->left_date)){
               $user_at = $this->app->getUserAttendanceByDate($start_time,$end_time,$user->user_id,$check['id'],1);
               $odAttendance = $this->app->getUserOdAttendanceByDate($start_time,$end_time,$user->user_id,$check['id']);
                 $odData = array();
               $data = array();

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

               $off = array_search(date('N',$start_time),array_column($grp,'day_off'));
               $holi = array_search(date('d.m.Y',$start_time),array_column($holiday_array,'date'));
            if(!empty($day_shift_start)){
                 if($day_shift_start[date('N',$start_time)-1]!=null){
                    $shift_start = $day_shift_start[date('N',$start_time)-1];
                 }

                }
              if(!empty($day_shift_end)){
                 if($day_shift_end[date('N',$start_time)-1]!=null){
                    $shift_end = $day_shift_end[date('N',$start_time)-1];
                 }
              }
               if(!is_bool($off)){
                 $weekOff = "1";
               }else{
                 $weekOff = "0";
               }

               if(!is_bool($holi)){
                 $holiday="1";
               }else{
                 $holiday="0";
               }
               if(!empty($user_at)){
                 foreach($user_at as $at){
                   $data[] = array(
                    'id'=>$at->id,
                     'mode'=>$at->mode,
                     'time'=>date('h:i A', $at->io_time),
                     'comment'=>$at->comment."\n".$at->emp_comment,
                     'latitude'=>$at->latitude,
                     'longitude'=>$at->longitude,
                     'verified'=>$at->verified,
                     'selfie'=>$at->selfie,
                     'manual'=>$at->manual,
                     'location'=>$at->location
                   );
                 }
               }else{
                 $data = array();
               }
               if(!empty($odAttendance)){
                 foreach($odAttendance as $od){
                   $odselfie = $od->selfie;
                      if(!empty($odselfie)){
                          $url=base_url();
                          $odselfie=$url.'/'.$odselfie;
                          $im = file_get_contents($odselfie);
                          $odselfie = base64_encode($im);
                      }
                   $odData[] = array(
                    'id'=>$od->id,
                     'mode'=>$od->mode,
                     'time'=>$od->io_time,
                     'comment'=>$od->emp_comment,
                     'latitude'=>$od->latitude,
                     'longitude'=>$od->longitude,
                     'verified'=>$od->verified,
                     'location'=>$od->location,
                     'selfie'=>$odselfie
                   );
                 }
               }else{
                 $odData = array();
               }
               $new_array[] =array(
                 'user_id'=>$user->user_id,
                 'name'=>$user->name,
                 'image'=>$user->image,
                 'date'=>date("d M Y",$start_time),
                 'user_status'=>$user->user_status,
                 'weekly_off'=>$weekOff,
                 'holiday'=>$holiday,
                 'shift_start'=>$shift_start,
                 'shift_end'=>$shift_end,
                 'group_name'=>$group_name,
                 'designation'=>$user->designation,
                 'data'=> $data,
                 'odData'=>$odData
               );
           }
         }
         echo $response= json_encode(array('checkon'=>$new_array));
       }else{
         $res=array('msg'=>'No Data Foud');
         echo $response= json_encode(array('checkon'=>$res));
       }
     }
   }
 }

 function getEmpProfile(){
   $data=json_decode(file_get_contents("php://input"));
   if(key($data)=="checkon"){
     $check=$this->app->checkMobile($data->checkon->mobile);
     if(!empty($check['id']) && $check['user_group']==1){
       $emp = $this->app->getEmpProfile($data->checkon->uid,$check['id']);
       $groups = $this->app->getBusinessGroups($check['id']);
       $sections = $this->app->getSections($check['id']);
       $newQr = $this->app->hasNewQR($data->checkon->uid);
       $depart = $this->app->getDepartmentSections($check['id']);
       $rules = $this->app->getAttendanceRules($check['id']);
       $hasNewQr = "0";
       if(!empty($newQr)){
         $hasNewQr = "1";
       }
       if(!empty($emp)){
         $data = array(
           "name"=>$emp->name,
           "mobile"=>$emp->mobile,
           "user_group"=>$emp->user_group,
           "company"=>$check['id'],
           "email"=>$emp->email,
           "address"=>$emp->address,
           "m_id"=>$emp->m_id,
           "business_group"=>$emp->business_group,
           "business_group_name"=>$emp->business_group_name,
           "designation"=>$emp->designation,
           "dob"=>$emp->dob,
           "gender"=>$emp->gender,
           "doj"=>date('d-m-Y',$emp->user_doj),
           "education"=>$emp->education,
           "bluetooth_mac"=>$emp->bluetooth_mac,
           "bluetooth_ssid"=>$emp->bluetooth_ssid,
           "image"=>$emp->image,
           "emp_code"=>$emp->emp_code,
           "manager"=>$emp->manager,
           "groups"=>$groups,
           "section"=>$emp->section,
           "sections"=>$sections,
           "hasNewQr"=>$hasNewQr,
           "department"=>$emp->department,
           "departments"=>$depart,
           "qr"=>$emp->qr,
           "gps"=>$emp->gps,
           "face"=>$emp->face,
           "colleague"=>$emp->colleague,
           "auto_gps"=>$emp->auto_gps,
           "gps_tracking"=>$emp->gps_tracking,
           "field_duty"=>$emp->field_duty,
           "four_layer_security"=>$emp->four_layer_security,
           "selfie_with_gps"=>$emp->selfie_with_gps,
           "selfie_with_field_duty"=>$emp->selfie_with_field_duty,
           "rule_id"=>$emp->rule_id,
           "rules"=>$rules
         );
         echo $response= json_encode(array('checkon'=>$data));
       }else{
         $res=array('msg'=>'No Data Foud');
         echo $response= json_encode(array('checkon'=>$res));
       }
     }
   }
 }

 function addBusinessGroup(){
   $data=json_decode(file_get_contents("php://input"));
   if(key($data)=="checkon"){
     $check=$this->app->checkMobile($data->checkon->mobile);
     if(!empty($check['id']) && $check['user_group']==1){
       $data = array(
         'business_id'=>$check['id'],
         'name'=>$data->checkon->group_name,
         'shift_start'=>$data->checkon->start_time,
         'shift_end'=>$data->checkon->end_time,
         'weekly_off'=>$data->checkon->weekly_off,
         'day_start_time'=>$data->checkon->day_start_time,
         'day_end_time'=>$data->checkon->day_end_time
       );
       $group = $this->app->addBusinessGroup($data);
       if($group){
         echo $response= json_encode(array('checkon'=>array('msg'=>'Group Added','status'=>'1')));
       }else{
         $res=array('msg'=>'Failed to add Group','status'=>'0');
         echo $response= json_encode(array('checkon'=>$res));
       }
     }
   }
 }

 function getBusinessGroups(){
   $data=json_decode(file_get_contents("php://input"));
   if(key($data)=="checkon"){
     $check=$this->app->checkMobile($data->checkon->mobile);
     if(!empty($check['id']) && $check['user_group']==1){
       $groups = $this->app->getBusinessGroups($check['id']);
       if(!empty($groups)){
         echo $response= json_encode(array('checkon'=>$groups,'status'=>'1'));
       }else{
         $res=array('msg'=>'No Data Foud');
         echo $response= json_encode(array('checkon'=>$res,'status'=>'0'));
       }
     }
   }
 }

 function removeBusinessGroup(){
   $data=json_decode(file_get_contents("php://input"));
   if(key($data)=="checkon"){
     $check=$this->app->checkMobile($data->checkon->mobile);
     if(!empty($check['id']) && $check['user_group']==1){
       $group = $this->app->removeBusinessGroup($data->checkon->id);
       if($group){
         echo $response= json_encode(array('checkon'=>array('msg'=>'Group Removed','status'=>'1')));
       }else{
         $res=array('msg'=>'Failed to Remove Group','status'=>'0');
         echo $response= json_encode(array('checkon'=>$res));
       }
     }
   }
 }

 function updateBusinessGroup(){
   $data=json_decode(file_get_contents("php://input"));
   if(key($data)=="checkon"){
     $check=$this->app->checkMobile($data->checkon->mobile);
     if(!empty($check['id']) && $check['user_group']==1){
       $group = $this->app->updateBusinessGroup($data->checkon->id,$data->checkon->group_name,$data->checkon->start_time,$data->checkon->end_time,$data->checkon->weekly_off,$data->checkon->day_start_time,$data->checkon->day_end_time);
       if($group){
         echo $response= json_encode(array('checkon'=>array('msg'=>'Group Updated','status'=>'1')));
       }else{
         $res=array('msg'=>'Failed to Update Group','status'=>'0');
         echo $response= json_encode(array('checkon'=>$res));
       }
     }
   }
 }

 function addHoliday(){
   $data=json_decode(file_get_contents("php://input"));
   if(key($data)=="checkon"){
     $check=$this->app->checkMobile($data->checkon->mobile);
     if(!empty($check['id']) && $check['user_group']==1){
       $this->app->removeHoliday($check['id']);
       $holidays = $data->checkon->holidays;
       $new_array = array();
       foreach($holidays as $holiday){
         $new_array[] = array(
           'business_id'=>$check['id'],
           'date'=>strtotime($holiday->date)
         );
       }
       $res = $this->app->addHoliday($new_array);
       if($res){
         echo $response= json_encode(array('checkon'=>array('msg'=>'Holidays Added','status'=>'1')));
       }else{
         $res=array('msg'=>'Failed to add Holiday','status'=>'0');
         echo $response= json_encode(array('checkon'=>$res));
       }
     }
   }
 }

 function getHoliday(){
   $data=json_decode(file_get_contents("php://input"));
   if(key($data)=="checkon"){
     $check=$this->app->checkMobile($data->checkon->mobile);
     if(!empty($check['id']) && $check['user_group']==1){
       $holidays = $this->app->getHoliday($check['id']);
       $new_array = array();
       foreach($holidays as $holiday){
         $new_array[] = array(
           'year'=>date("Y",$holiday->date),
           'month'=>date("m",$holiday->date),
           'day'=>date("d",$holiday->date)
         );
       }
       if($holidays){
         echo $response= json_encode(array('checkon'=>$new_array));
       }else{
         $res=array('msg'=>'Failed to add Holiday','status'=>'0');
         echo $response= json_encode(array('checkon'=>$res));
       }
     }
   }
 }

 function updateEmpProfile(){
   $data=json_decode(file_get_contents("php://input"));
   if(key($data)=="checkon"){
     $check=$this->app->checkMobile($data->checkon->mobile);
     if(!empty($check['id']) && $check['user_group']==1){
      $doj = $this->app->updateDoj($data->checkon->uid,$check['id'],strtotime($data->checkon->doj),$data->checkon->rule_id,$data->checkon->qr,$data->checkon->gps,$data->checkon->face,$data->checkon->colleague,$data->checkon->autoGps,$data->checkon->gpsTracking,$data->checkon->field_duty,$data->checkon->four_layer_security,$data->checkon->selfie_with_gps,$data->checkon->selfie_with_field_duty);
    $i = $data->checkon->old_image;
      if(!empty($data->checkon->image)){
        $img=base64_decode($data->checkon->image);
          $imgs = imagecreatefromstring($img);
          if($imgs != false){
            $date = strtotime(date('Y-m-d h:i:s'));
            $i="upload/$date.jpg";
              imagejpeg($imgs, "$i");
          }
      }

       $emp = $this->app->updateEmpProfile($data->checkon->uid,$data->checkon->name,$i,$data->checkon->address,$data->checkon->email,$data->checkon->group,$data->checkon->designation,$data->checkon->dob,$data->checkon->gender,strtotime($data->checkon->doj),$data->checkon->education,$data->checkon->bluetooth_ssid,$data->checkon->bluetooth_mac,$data->checkon->emp_code,$data->checkon->manager,$data->checkon->section,$data->checkon->department);
       if($emp){
         echo $response= json_encode(array('checkon'=>array('msg'=>'Profile Updated','status'=>'1')));
       }else{
         $res=array('msg'=>'Failed to Update Profile','status'=>'0');
         echo $response= json_encode(array('checkon'=>$res));
       }
     }
   }
 }
 function addStaff(){
   $data=json_decode(file_get_contents("php://input"));
   if(key($data)=="checkon"){
     $check=$this->app->checkMobile($data->checkon->mobile);
     if(!empty($check['id']) && $check['user_group']==1){
       $mobile = $data->checkon->empMobile;
       $checkMobile=$this->app->checkMobile($mobile);
       if(empty($checkMobile)){
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
         $i='upload/nextpng.png';
         $saveData=array(
           'firebassid'=>$data->checkon->token,
           'otp'=>$otp,
           'm_id'=>$nmid,
           'name'=>$data->checkon->name,
           'mobile'=>$mobile,
           'company'=>$check['id'],
           'user_group'=>"2",
           'email'=>$data->checkon->email,
           'address'=>$data->checkon->address,
           'token'=>$data->checkon->token,
           'app_id'=>$data->checkon->appid,
           'business_group'=>$data->checkon->group,
           'designation'=>$data->checkon->designation,
           'dob'=>$data->checkon->dob,
           'gender'=>$data->checkon->gender,
           'doj'=>strtotime($data->checkon->doj),
           'education'=>$data->checkon->education,
           'image'=>$i,
           'active'=>0,
           'login'=>md5($mobile),
           'date'=>time(),
           'baseurl'=>base_url().'User/profile/'.$nmid,
           'start_date'=>time()
         );
         $this->app->AddUser($saveData);
         $id = $this->db->insert_id();
         if($id){
           $cmpInData = array(
             'business_id'=>$check['id'],
             'user_id'=>$id,
             'doj'=>strtotime($data->checkon->doj),
             'date'=>time()
           );
           $req = $this->app->addUserCmpStatus($cmpInData);
           if(isset($data->checkon->newQr)){
               $string = explode('/', $data->checkon->newQr);
               $assign = $this->app->assignNewQR($id,end($string));
           }
           echo $response= json_encode(array('checkon'=>array('msg'=>'Staff Added','status'=>'1')));
         }else{
           $res=array('msg'=>'Failed to Add Staff','status'=>'0');
           echo $response= json_encode(array('checkon'=>$res));
         }
       }else{
         $res=array('msg'=>'Mobile Number Already Registered','status'=>'0');
         echo $response= json_encode(array('checkon'=>$res));
       }
     }
   }
 }
 function manualAttendance(){
   $data=json_decode(file_get_contents("php://input"));
   if(key($data)=="checkon"){
     $check=$this->app->checkMobile($data->checkon->mobile);
     if(!empty($check['id']) && $check['user_group']==1){
       $user_status = $this->app->userCmpStatus($data->checkon->userId,$check['id']);
       if($user_status['user_status']=="1"){
           $manual = 0;
           if($data->checkon->manual){
               $manual = 1;
           }
           $verified = 1;
           if(isset($data->checkon->verified)){
             $verified = 0;
           }
         $data = array(
           'bussiness_id'=>$check['id'],
           'user_id'=>$data->checkon->userId,
           'mode'=>$data->checkon->mode,
           'io_time'=>strtotime($data->checkon->time),
           'comment'=>$data->checkon->comment,
           'manual'=>$manual,
           'verified'=>$verified,
           'date'=>time()
         );
         $res = $this->app->insertAttendance($data);
         if($res == 1){
           $sendRes = array('msg'=>'Attendance added Successfully','status'=>'1');
         }else{
           $sendRes = array('msg'=>'Failed to Add','status'=>'0');
         }
         echo $response= json_encode(array('checkon'=>$sendRes));
       }else{
         $sendRes = array('msg'=>'Not Active','status'=>'0');
         echo $response= json_encode(array('checkon'=>$sendRes));
       }
     }
   }
 }

 function getAttendanceReport(){
   $mid=json_decode(file_get_contents("php://input"));
   if(key($mid)=="checkon"){
     $check=$this->app->checkMobile($mid->checkon->mobile);
     if(!empty($check['id']) && $check['user_group']==1){
       if($mid->checkon->empid==0){
         $users_data = $this->app->getCompanyUsers($check['id']);
       }else{
         $users_data = $this->app->getCompanyUserById($check['id'],$mid->checkon->empid);
       }
       $start_time = strtotime(date("d-m-Y 00:00:00",strtotime($mid->checkon->datefrom)));
       $end_time = strtotime(date("d-m-Y 23:59:59",strtotime($mid->checkon->dateto)));

       $holidays = $this->app->getHoliday($check['id']);
       $holiday_array = array();
       if($holidays){
         foreach($holidays as $holiday){
           $holiday_array[] = array(
             'date'=>date('d.m.Y',$holiday->date),
           );
         }
       }
       if(!empty($users_data)){
         $new_array = array();
         foreach($users_data as $user){
           $date1=date_create(date("Y-m-d",strtotime($mid->checkon->datefrom)));
           $date2=date_create(date("Y-m-d",strtotime($mid->checkon->dateto)));
           $diff=date_diff($date1,$date2);
           $num_month = $diff->format("%a");

           $num_month++;
          if($num_month>31){
              $num_month=31;
          }

           $groups = $this->app->getUserGroup($user->business_group);
           $grp = array();

           if($groups){
             $weekly_off = explode(",",$groups->weekly_off);
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
           $months_array = array();
           for($d=0; $d<$num_month;$d++){
             $new_start_time = strtotime(date("d-m-Y 00:00:00",strtotime($mid->checkon->datefrom))." +".$d." days");
             $new_end_time = strtotime(date("d-m-Y 23:59:59",strtotime($mid->checkon->datefrom))." +".$d." days");

             if(($user->doj!="" || $start_time>=$user->doj) && ($user->left_date=="" || $start_time<$user->left_date)){
                 $user_at = $this->app->getUserAttendanceReportByDate($new_start_time,$new_end_time,$user->user_id,$check['id'],1);
                 $data = array();
                 $off = array_search(date('N',$new_start_time),array_column($grp,'day_off'));
                 $holi = array_search(date('d.m.Y',$new_start_time),array_column($holiday_array,'date'));
                 if(!is_bool($off)){
                   $weekOff = "1";
                 }else{
                   $weekOff = "0";
                 }

                 if(!is_bool($holi)){
                   $holiday="1";
                 }else{
                   $holiday="0";
                 }
                 if(!empty($user_at)){
                   foreach($user_at as $at){
                     $data[] = array(
                       'mode'=>$at->mode,
                       'time'=>date('h:i A', $at->io_time),
                       'comment'=>$at->comment."\n".$at->emp_comment,
                       'manual'=>$at->manual
                     );
                   }
                 }else{
                   $data = array();
                 }
                 $months_array[] = array(
                   'date'=>date("j",$new_start_time),
                   'day'=>date("l",$new_start_time),
                   'weekly_off'=>$weekOff,
                   'holiday'=>$holiday,
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
                 'shift_start'=>$shift_start,
                 'shift_end'=>$shift_end,
                 'group_name'=>$group_name,
                 'designation'=>$user->designation,
                 'data'=> $months_array
               );
           }
         }
         echo $response= json_encode(array('checkon'=>$new_array,'export'=>$check['export']));
       }else{
         $res=array('msg'=>'No Data Foud');
         echo $response= json_encode(array('checkon'=>$res));
       }
     }
   }
 }

 function getBusinessToken(){
   $mid=json_decode(file_get_contents("php://input"));
   if(key($mid)=="checkon"){
     $check=$this->app->checkMobile($mid->checkon->mobile);
     if(!empty($check['id']) && $check['user_group']==1){
       $res = $this->app->getBusinessToken($check['id'],date("Y-m-d"));
       if($res){
           $count = 0;
       foreach($res as $row){
         $live = $this->app->getlivetoken($row->Dept_id);
         $livetoken = "0";
         if($live['token']){
           $livetoken= $live['token'];
         }
         $res[$count]->livetoken=$livetoken;
         $count++;
       }
         echo $response= json_encode(array('checkon'=>$res,'tokenStatus'=>$check['token_status']));
       }else{
         $sendRes = array('msg'=>'No data','status'=>'0');
         echo $response= json_encode(array('checkon'=>$sendRes,'tokenStatus'=>$check['token_status']));
       }
     }
   }
 }

 function changeTokenStatus(){
   $mid=json_decode(file_get_contents("php://input"));
   if(key($mid)=="checkon"){
     $check=$this->app->checkMobile($mid->checkon->mobile);
     if(!empty($check['id']) && $check['user_group']==1){
         if($mid->checkon->status==1){
             $this->app->closeAllToken($mid->checkon->depid,$mid->checkon->date);
                   $loginid = $this->app->getUserIdByToken($mid->checkon->id);
                   $usertoken=$this->app->userdetails($loginid->userid);
                   $firebasetoken=$usertoken['firebassid'];
                   $this->push_notification_android("You token has been called!",$firebasetoken,"Token Called");
         }
       $res = $this->app->changeTokenStatus($mid->checkon->id,$check['id'],$check['id'],$mid->checkon->status);
       if($res){
         $sendRes = array('msg'=>'Token','status'=>'1');
         echo $response= json_encode(array('checkon'=>$sendRes));
       }else{
         $sendRes = array('msg'=>'Error','status'=>'0');
         echo $response= json_encode(array('checkon'=>$sendRes));
       }
     }
   }
 }

 function businessTokenStatus(){
   $mid=json_decode(file_get_contents("php://input"));
   if(key($mid)=="checkon"){
     $check=$this->app->checkMobile($mid->checkon->mobile);
     if(!empty($check['id']) && $check['user_group']==1){
       $res = $this->app->businessTokenStatus($check['id'],$mid->checkon->status);
       if($res){
         $sendRes = array('msg'=>'Token','status'=>'1');
         echo $response= json_encode(array('checkon'=>$sendRes));
       }else{
         $sendRes = array('msg'=>'Error','status'=>'0');
         echo $response= json_encode(array('checkon'=>$sendRes));
       }
     }
   }
 }

 function closeAccount(){
   $mid=json_decode(file_get_contents("php://input"));
   if(key($mid)=="checkon"){
     $check=$this->app->checkMobile($mid->checkon->mobile);
     if(!empty($check['id'])){
       $res = $this->app->closeAccount($check['id']);
       if($res){
         $sendRes = array('msg'=>'Account Closed Successfully','status'=>'1');
         echo $response= json_encode(array('checkon'=>$sendRes));
       }else{
         $sendRes = array('msg'=>'Error','status'=>'0');
         echo $response= json_encode(array('checkon'=>$sendRes));
       }
     }
   }
 }

 function getChangeMobileOTP(){
   $mid=json_decode(file_get_contents("php://input"));
   if(key($mid)=="checkon"){
     $check=$this->app->checkMobile($mid->checkon->mobile);
     if(!empty($check['id'])){
       $checkMobile = $this->app->checkMobile($mid->checkon->new_mobile);
       if(empty($checkMobile)){
           $otp=rand(1000,9999);
         $data=array(
           'otp'=>$otp
         );
         //$msg="Your Otp verfication no is:$otp";
         //$this->sendsms($mobile,$msg);
         $this->db->where('mobile',$mid->checkon->mobile);
         $this->db->where('deleted',0);
         $update=$this->db->update('login',$data);
         if($update > 0){
           $response=array('msg'=>'OTP Send Successfully!');
           echo $response= json_encode(array('checkon'=>$response));
         }else{
           $sendRes = array('msg'=>'Error','status'=>'0');
           echo $response= json_encode(array('checkon'=>$sendRes));
         }
       }else{
         $sendRes = array('msg'=>'Mobile Already exists','status'=>'0');
         echo $response= json_encode(array('checkon'=>$sendRes));
       }
     }
   }
 }

 function changeMobileNumber(){
   $mid=json_decode(file_get_contents("php://input"));
   if(key($mid)=="checkon"){
     $check=$this->app->checkotp($mid->checkon->mobile,$mid->checkon->otp);
     if(!empty($check['id'])){
       $checkMobile = $this->app->checkMobile($mid->checkon->new_mobile);
       if(empty($checkMobile)){
         $res = $this->app->changeMobileNumber($check['id'],$mid->checkon->new_mobile);
         if($res){
           $sendRes = array('msg'=>'Mobile Number Changed Successfully','status'=>'1');
           echo $response= json_encode(array('checkon'=>$sendRes));
         }else{
           $sendRes = array('msg'=>'Error','status'=>'0');
           echo $response= json_encode(array('checkon'=>$sendRes));
         }
       }else{
         $sendRes = array('msg'=>'Mobile Already exists','status'=>'0');
         echo $response= json_encode(array('checkon'=>$sendRes));
       }
     }
   }
 }

 function activateNewQr(){
   $mid=json_decode(file_get_contents("php://input"));
   if(key($mid)=="checkon"){
     $check=$this->app->checkMobile($mid->checkon->mobile);
     if(!empty($check['id'])){
       $string = explode('/', $mid->checkon->newQr);
       $res = $this->app->checkNewQR(end($string));
       if($res){
         $assign = $this->app->assignNewQR($check['id'],end($string));
         if($assign){
           $sendRes = array('msg'=>'QR Activated Successfully','status'=>'1');
           echo $response= json_encode(array('checkon'=>$sendRes));
         }else{
           $sendRes = array('msg'=>'Failed to activate QR','status'=>'0');
           echo $response= json_encode(array('checkon'=>$sendRes));
         }
       }else{
         $sendRes = array('msg'=>'QR not Found','status'=>'0');
         echo $response= json_encode(array('checkon'=>$sendRes));
       }
     }
   }
 }

 function getEmpByBluetooth(){
   $data=json_decode(file_get_contents("php://input"));
   if(key($data)=="checkon"){
     $check=$this->app->checkMobile($data->checkon->mobile);
     if(!empty($check['id']) && $check['user_group']==1){
       $users_data = $this->app->getCompanyUsersByBluetooth($check['id']);
       $start_time = strtotime(date("d-m-Y 00:00:00",strtotime($data->checkon->date)));
       $end_time = strtotime(date("d-m-Y 23:59:59",strtotime($data->checkon->date)));

       $holidays = $this->app->getHoliday($check['id']);
       $holiday_array = array();
       if($holidays){
         foreach($holidays as $holiday){
           $holiday_array[] = array(
             'date'=>date('d.m.Y',$holiday->date),
           );
         }
       }
       if(!empty($users_data)){
         $new_array = array();
         foreach($users_data as $user){
             if(($user->doj!="" || $start_time>=$user->doj) && ($user->left_date=="" || $start_time<$user->left_date)){
                 $user_at = $this->app->getAttendanceByVerify($start_time,$end_time,$user->user_id,$check['id']);
               $data = array();

               $groups = $this->app->getUserGroup($user->business_group);
               $grp = array();

               if($groups){
                 $weekly_off = explode(",",$groups->weekly_off);
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

               $off = array_search(date('N',$start_time),array_column($grp,'day_off'));
               $holi = array_search(date('d.m.Y',$start_time),array_column($holiday_array,'date'));

               if(!is_bool($off)){
                 $weekOff = "1";
               }else{
                 $weekOff = "0";
               }

               if(!is_bool($holi)){
                 $holiday="1";
               }else{
                 $holiday="0";
               }
               if(!empty($user_at)){
                 foreach($user_at as $at){
                   $data[] = array(
                     'id'=>$at->id,
                     'mode'=>$at->mode,
                     'time'=>date('h:i A', $at->io_time),
                     'comment'=>$at->comment."\n".$at->emp_comment,
                     'emp_comment'=>$at->emp_comment,
                     'verified'=>$at->verified,
                     'latitude'=>$at->latitude,
                     'longitude'=>$at->longitude,
                     'location'=>$at->location
                   );
                 }
               }else{
                 $data = array();
               }
               $new_array[] =array(
                 'user_id'=>$user->user_id,
                 'name'=>$user->name,
                 'image'=>$user->image,
                 'date'=>date("d M Y",$start_time),
                 'user_status'=>$user->user_status,
                 'weekly_off'=>$weekOff,
                 'holiday'=>$holiday,
                 'shift_start'=>$shift_start,
                 'shift_end'=>$shift_end,
                 'group_name'=>$group_name,
                 'designation'=>$user->designation,
                 'bluetooth_ssid'=>$user->bluetooth_ssid,
                 'bluetooth_mac'=>$user->bluetooth_mac,
                 'data'=> $data
               );
             }
         }
         echo $response= json_encode(array('checkon'=>$new_array));
       }else{
         $res=array('msg'=>'No Data Foud');
         echo $response= json_encode(array('checkon'=>$res));
       }
     }
   }
 }

 function verifyAttendance(){
   $data=json_decode(file_get_contents("php://input"));
   if(key($data)=="checkon"){
     $check=$this->app->checkMobile($data->checkon->mobile);
     if(!empty($check['id']) && $check['user_group']==1){
       $user_status = $this->app->userCmpStatus($data->checkon->userId,$check['id']);
       if($user_status['user_status']=="1"){
         $res = $this->app->verifyAttendance($data->checkon->attendanceId);
         if($res == 1){
           $sendRes = array('msg'=>'Attendance Verified','status'=>'1');
         }else{
           $sendRes = array('msg'=>'Failed to Verify','status'=>'0');
         }
         echo $response= json_encode(array('checkon'=>$sendRes));
       }else{
         $sendRes = array('msg'=>'Not Active','status'=>'0');
         echo $response= json_encode(array('checkon'=>$sendRes));
       }
     }
   }
 }

 function cancelAttendance(){
   $data=json_decode(file_get_contents("php://input"));
   if(key($data)=="checkon"){
     $check=$this->app->checkMobile($data->checkon->mobile);
     if(!empty($check['id']) && $check['user_group']==1){
       $user_status = $this->app->userCmpStatus($data->checkon->userId,$check['id']);
       if($user_status['user_status']=="1"){
         $res = $this->app->cancelAttendance($data->checkon->attendanceId);
         if($res == 1){
           $sendRes = array('msg'=>'Attendance Cancelled','status'=>'1');
         }else{
           $sendRes = array('msg'=>'Failed to Cancel','status'=>'0');
         }
         echo $response= json_encode(array('checkon'=>$sendRes));
       }else{
         $sendRes = array('msg'=>'Not Active','status'=>'0');
         echo $response= json_encode(array('checkon'=>$sendRes));
       }
     }
   }
 }
 function onDutyAttendance(){
   $data=json_decode(file_get_contents("php://input"));
   if(key($data)=="checkon"){
     $check=$this->app->checkMobile($data->checkon->mobile);
     if(!empty($check['id']) && $check['user_group']==2){
      $userCmp = $this->app->getUserCompany($check['id']);

      if($userCmp['left_date']=="" || $userCmp['left_date']>time()){
         $user_status = $this->app->userCmpStatus($check['id'],$userCmp['business_id']);
         if($user_status['user_status']=="1"){
           $data = array(
             'bussiness_id'=>$userCmp['business_id'],
             'user_id'=>$check['id'],
             'mode'=>$data->checkon->mode,
             'io_time'=>time(),
             'manual'=>0,
             'verified'=>0,
             'latitude'=>$data->checkon->latitude,
             'longitude'=>$data->checkon->longitude,
             'location'=>$data->checkon->location,
             'emp_comment'=>$data->checkon->emp_comment,
             'date'=>time()
           );
           $res = $this->app->insertAttendance($data);
           if($res == 1){
             $sendRes = array('msg'=>'Attendance added Successfully','status'=>'1');
           }else{
             $sendRes = array('msg'=>'Failed to Add','status'=>'0');
           }
           echo $response= json_encode(array('checkon'=>$sendRes));
         }else{
           $sendRes = array('msg'=>'Not Active','status'=>'0');
           echo $response= json_encode(array('checkon'=>$sendRes));
         }

       }else{
         $sendRes = array('msg'=>'Wrong Company QR','status'=>'0');
         echo $response= json_encode(array('checkon'=>$sendRes));
       }
     }
   }
 }

 function userLeft(){
   $data=json_decode(file_get_contents("php://input"));
   if(key($data)=="checkon"){
     $check=$this->app->checkMobile($data->checkon->mobile);
     if(!empty($check['id']) && $check['user_group']==1){
      $res = $this->app->userLeftRequest($data->checkon->userId,$check['id'],strtotime(date("d-m-Y 00:00:00",strtotime($data->checkon->left_date))));
           $sendRes = array('msg'=>'Left Successfully','status'=>'1');

         echo $response= json_encode(array('checkon'=>$sendRes));
     }
   }
 }

 function getPrimeAtt(){
   $data=json_decode(file_get_contents("php://input"));
   if(key($data)=="checkon"){
     $check=$this->app->checkMobile($data->checkon->mobile);
     if(!empty($check['id'])){
        $sendRes = array('prime_att'=>$check['prime_att'],'status'=>'1');
        echo $response= json_encode(array('checkon'=>$sendRes));
     }
   }
 }

 function getBannerAds(){
   $data=json_decode(file_get_contents("php://input"));
   if(key($data)=="checkon"){
     $check=$this->app->bannerAds();
     echo $response= json_encode(array('checkon'=>$check));
   }
 }

 function features(){
   $data=json_decode(file_get_contents("php://input"));
   if(key($data)=="checkon"){
     $check=$this->app->features();
     echo $response= json_encode(array('checkon'=>$check));
   }
 }
 function requestPremium(){
   $data=json_decode(file_get_contents("php://input"));
   if(key($data)=="checkon"){
     $check=$this->app->checkMobile($data->checkon->mobile);
     if(!empty($check['id']) && $check['user_group']==1){
       $res = $this->app->requestPremium($check['id']);
         if($res == 1){
           $sendRes = array('msg'=>'Requested Successfully','status'=>'1');
         }else{
           $sendRes = array('msg'=>'Failed to Request','status'=>'0');
         }
         echo $response= json_encode(array('checkon'=>$sendRes));
     }
   }
 }

 function getTokenforUser(){
   $data=json_decode(file_get_contents("php://input"));
   if(key($data)=="checkon"){
     $check=$this->app->checkMobile($data->checkon->mobile);
     if(!empty($check['id']) && $check['user_group']==1){
       $res = $this->app->checkMobile($data->checkon->userMobile);
       if(!empty($res['id'])){
         $sendRes = array('name'=>$res['name'],'id'=>$res['id'],'status'=>'1');
       }else{
         $sendRes = array('name'=>'','id'=>'','status'=>'0');
       }
         echo $response= json_encode(array('checkon'=>$sendRes));
     }
   }
 }
 function createTokenUserLogin(){
   $data=json_decode(file_get_contents("php://input"));
   if(key($data)=="checkon"){
     $check=$this->app->checkMobile($data->checkon->mobile);
     if(!empty($check['id']) && $check['user_group']==1){
       $res = $this->app->checkMobile($data->checkon->userMobile);
       if(empty($res['id'])){
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
         $saveData=array(
           'mobile'=>$data->checkon->userMobile,
           'otp'=>"0",
           'm_id'=>$nmid,
           'name'=>$data->checkon->name,
           'start_date'=>time()
         );
         $checkData=$this->app->AddUser($saveData);
         $res = $this->app->checkMobile($data->checkon->userMobile);
         if(!empty($res['id'])){
           $sendRes = array('name'=>$res['name'],'id'=>$res['id'],'status'=>'1');
         }else{
           $sendRes = array('name'=>'','id'=>'','status'=>'0');
         }
           echo $response= json_encode(array('checkon'=>$sendRes));

       }else{
         $upData=array(
           'name'=>$data->checkon->name
         );

         $this->db->where('mobile',$data->checkon->userMobile);
         $this->db->where('deleted',0);
         $update=$this->db->update('login',$upData);
         $res = $this->app->checkMobile($data->checkon->userMobile);
         if(!empty($res['id'])){
           $sendRes = array('name'=>$res['name'],'id'=>$res['id'],'status'=>'1');
         }else{
           $sendRes = array('name'=>'','id'=>'','status'=>'0');
         }
           echo $response= json_encode(array('checkon'=>$sendRes));
       }
     }
   }
 }
 function sendEmail(){
   $data=json_decode(file_get_contents("php://input"));
   if(key($data)=="checkon"){
     $check=$this->app->checkMobile($data->checkon->mobile);
     if(!empty($check['id']) && $check['user_group']==1){
      $date = strtotime(date('Y-m-d h:i:s'));
       $ifp = fopen( 'upload/'.$date.'.xlsx', 'wb' );
       fwrite( $ifp, base64_decode($data->checkon->data));
       fclose( $ifp );
       $config = Array(
         'protocol' => 'smtp',
         'smtp_host' => 'ssl://smtp.hostinger.com',
         'smtp_port' => 465,
         'smtp_user' => 'support@midapp.in',
         'smtp_pass' => 'Scooty@3914',
         'charset' => 'iso-8859-1',
         'wordwrap' => TRUE);

          $this->load->library('email', $config);
          $this->email->set_newline("\r\n");
          $this->email->from('support@midapp.in');
          $this->email->to($data->checkon->email);
          $this->email->subject('Attendance Report');
          $this->email->attach('upload/'.$date.'.xlsx');
          if($this->email->send()){
            $sendRes = array('msg'=>'Email Sent','status'=>'1');
          }else{
            $sendRes = array('msg'=>'Failed to Sent','status'=>'0');
        }
        echo $response= json_encode(array('checkon'=>$sendRes));
     }
   }
 }

 function newQrAttendance(){
   $data=json_decode(file_get_contents("php://input"));
   if(key($data)=="checkon"){
     $check=$this->app->checkMobile($data->checkon->mobile);
     if(!empty($check['id']) && $check['user_group']==1){
       $string = explode('/', $data->checkon->business_id);
       $newQr = $this->app->getLoginIdByQr(end($string));
       if(!empty($newQr)){
         $loginid=$newQr['login_id'];
         $user_status = $this->app->userCmpStatus($loginid,$check['id']);
         $userMobile=$this->app->checkMobile($newQr['mobile']);
         $userCmp = $this->app->getUserCompany($userMobile['id']);
         if($check['id']==$userCmp['business_id']){
           if($user_status['user_status']=="1"){
               $mode = "in";
           $start_time = strtotime(date("d-m-Y 00:00:00"));
           $end_time = strtotime(date("d-m-Y 23:59:59"));
           $res_mode = $this->app->getUserAttendanceByDate($start_time,$end_time,$loginid,$check['id'],1);

           if(!empty($res_mode)){
            if($res_mode[0]->mode=="in"){
                $mode = "out";
            }
           }
           $time = time();
             $data = array(
               'bussiness_id'=>$check['id'],
               'user_id'=>$loginid,
               'mode'=>$mode,
               'io_time'=>$time,
               'date'=>$time
             );
             $res = $this->app->insertAttendance($data);
             if($res == 1){
               $sendRes = array('msg'=>$userMobile['name'],'image'=>$userMobile['image'],'mode'=>$mode,'time'=>date('h:i A',$time),'status'=>'1');
             }else{
               $sendRes = array('msg'=>'Failed to Add','status'=>'0');
             }
             echo $response= json_encode(array('checkon'=>$sendRes));
           }else{
             $sendRes = array('msg'=>'Not Active','status'=>'0');
             echo $response= json_encode(array('checkon'=>$sendRes));
           }

         }else{
           $sendRes = array('msg'=>'Not in company','status'=>'0');
           echo $response= json_encode(array('checkon'=>$sendRes));
         }
       }else{
         $sendRes = array('msg'=>'Invalid QR','status'=>'0');
         echo $response= json_encode(array('checkon'=>$sendRes));
       }
     }
   }
 }

 function getNewQR(){
   $mid=json_decode(file_get_contents("php://input"));
   if(key($mid)=="checkon"){
     $check=$this->app->checkMobile($mid->checkon->mobile);
     if(!empty($check['id'])){
       $string = explode('/', $mid->checkon->newQr);
       $res = $this->app->checkNewQR(end($string));
       if($res){
         $sendRes = array('msg'=>'QR found','status'=>'1');
         echo $response= json_encode(array('checkon'=>$sendRes));
       }else{
         $sendRes = array('msg'=>'QR not Found','status'=>'0');
         echo $response= json_encode(array('checkon'=>$sendRes));
       }
     }
   }
 }

 function managerNewQrAttendance(){
   $data=json_decode(file_get_contents("php://input"));
   if(key($data)=="checkon"){
     $check=$this->app->checkMobile($data->checkon->mobile);
     if(!empty($check['id']) && $check['user_group']==2 && $check['manager']==1){
       $string = explode('/', $data->checkon->business_id);
       $newQr = $this->app->getLoginIdByQr(end($string));
       if(!empty($newQr)){
         $loginid=$newQr['login_id'];
         $userCmp = $this->app->getUserCompany($check['id']);
         $user_status = $this->app->userCmpStatus($loginid,$userCmp['business_id']);
         $userMobile=$this->app->checkMobile($newQr['mobile']);
         if($check['company']==$userCmp['business_id']){
           if($user_status['user_status']=="1"){
               $mode = "in";
           $start_time = strtotime(date("d-m-Y 00:00:00"));
           $end_time = strtotime(date("d-m-Y 23:59:59"));
           $res_mode = $this->app->getUserAttendanceByDate($start_time,$end_time,$loginid,$userCmp['business_id'],1);

           if(!empty($res_mode)){
            if($res_mode[0]->mode=="in"){
                $mode = "out";
            }
           }
           $time = time();
             $data = array(
               'bussiness_id'=>$userCmp['business_id'],
               'user_id'=>$loginid,
               'mode'=>$mode,
               'io_time'=>$time,
               'date'=>$time
             );
             $res = $this->app->insertAttendance($data);
             if($res == 1){
               $sendRes = array('msg'=>$userMobile['name'],'image'=>$userMobile['image'],'mode'=>$mode,'time'=>date('h:i A',$time),'status'=>'1');
             }else{
               $sendRes = array('msg'=>'Failed to Add','status'=>'0');
             }
             echo $response= json_encode(array('checkon'=>$sendRes));
           }else{
             $sendRes = array('msg'=>'Not Active','status'=>'0');
             echo $response= json_encode(array('checkon'=>$sendRes));
           }

         }else{
           $sendRes = array('msg'=>'Not in company','status'=>'0');
           echo $response= json_encode(array('checkon'=>$sendRes));
         }
       }else{
         $sendRes = array('msg'=>'Invalid QR','status'=>'0');
         echo $response= json_encode(array('checkon'=>$sendRes));
       }
     }
   }
 }
 function getEmpLeaves(){
  $data=json_decode(file_get_contents("php://input"));
  if(key($data)=="checkon"){
    $check=$this->app->checkMobile($data->checkon->mobile);
    if(!empty($check['id']) && $check['user_group']==2){
      $res = $this->app->getEmpLeaves($check['id']);
      if(!empty($res)){
        $data = array();
        foreach($res as $leave){
          $leave->from_date = date("d-m-Y",$leave->from_date);
          $leave->to_date = date("d-m-Y",$leave->to_date);
          $data[] = $leave;
        }
        echo $response= json_encode(array('checkon'=>$data));
      }else{
        $sendRes = array('msg'=>'No Leaves Found','status'=>'0');
        echo $response= json_encode(array('checkon'=>$sendRes));
      }
    }
  }
 }
 function addEmpLeave(){
  $data=json_decode(file_get_contents("php://input"));
  if(key($data)=="checkon"){
    $check=$this->app->checkMobile($data->checkon->mobile);
    if(!empty($check['id']) && $check['user_group']==2){
      $userCmp = $this->app->getUserCompany($check['id']);

      if($userCmp['left_date']=="" || $userCmp['left_date']>time()){
        $user_status = $this->app->userCmpStatus($check['id'],$userCmp['business_id']);
        if($user_status['user_status']=="1"){
          $leave = array(
            'bid'=>$userCmp['business_id'],
            'uid'=>$check['id'],
            'from_date'=>strtotime(date("d-m-Y 00:00:00",strtotime($data->checkon->from_date))),
            'to_date'=>strtotime(date("d-m-Y 00:00:00",strtotime($data->checkon->to_date))),
            'reason'=>$data->checkon->reason,
            'type'=>$data->checkon->type,
            'date_time'=>time()
          );
          $res = $this->app->insertLeave($leave);
          if($res == 1){
            $sendRes = array('msg'=>'Leave requested Successfully','status'=>'1');
          }else{
            $sendRes = array('msg'=>'Failed request Leave','status'=>'0');
          }
          echo $response= json_encode(array('checkon'=>$sendRes));
        }else{
          $sendRes = array('msg'=>'Not Active','status'=>'0');
          echo $response= json_encode(array('checkon'=>$sendRes));
        }

      }else{
        $sendRes = array('msg'=>'Wrong Company QR','status'=>'0');
        echo $response= json_encode(array('checkon'=>$sendRes));
      }
    }
  }
 }
  function updateEmpLeave(){
    $data=json_decode(file_get_contents("php://input"));
    if(key($data)=="checkon"){
      $check=$this->app->checkMobile($data->checkon->mobile);
      if(!empty($check['id']) && $check['user_group']==2){
        $userCmp = $this->app->getUserCompany($check['id']);

          if($userCmp['left_date']=="" || $userCmp['left_date']>time()){
          $user_status = $this->app->userCmpStatus($check['id'],$userCmp['business_id']);
          if($user_status['user_status']=="1"){
              $from_date= strtotime(date("d-m-Y 00:00:00",strtotime($data->checkon->from_date)));
              $to_date = strtotime(date("d-m-Y 00:00:00",strtotime($data->checkon->to_date)));

            $res = $this->app->updateEmpLeave($data->checkon->id,$from_date,$to_date,$data->checkon->reason,$data->checkon->type);
            if($res == 1){
              $sendRes = array('msg'=>'Leave requested Successfully','status'=>'1');
            }else{
              $sendRes = array('msg'=>'Failed request Leave','status'=>'0');
            }
            echo $response= json_encode(array('checkon'=>$sendRes));
          }else{
            $sendRes = array('msg'=>'Not Active','status'=>'0');
            echo $response= json_encode(array('checkon'=>$sendRes));
          }

        }else{
          $sendRes = array('msg'=>'Wrong Company QR','status'=>'0');
          echo $response= json_encode(array('checkon'=>$sendRes));
        }
      }
    }
}

  function allRequest(){
    $req = $this->app->allUserRequest();
    foreach($req as $r){
      $res = $this->app->userdetails($r->user_id);
      // print_r($res['doj']);
      $up = $this->app->updateDoj($r->user_id,$r->business_id,$res['doj']);
    }
  }

  function activateLicence(){
    $mid=json_decode(file_get_contents("php://input"));
    if(key($mid)=="checkon"){
      $check=$this->app->checkMobile($mid->checkon->mobile);
      if(!empty($check['id'])){
        $string = explode('/', $mid->checkon->newQr);
        $res = $this->app->checkNewQR(end($string));
        if($res){
          $validity;
          if($check['validity']!="" && $check['validity']>time()){
            $validity = strtotime('+1 year',$check['validity']);
          }else{
            $validity = strtotime('+1 year');
          }
          $assign = $this->app->updateValidity($check['id'],time(),$validity);
          $assign = $this->app->assignNewQR($check['id'],end($string));
          if($assign){
            $sendRes = array('msg'=>'Premium Activated Successfully','status'=>'1');
            echo $response= json_encode(array('checkon'=>$sendRes));
          }else{
            $sendRes = array('msg'=>'Failed to Activate Premium','status'=>'0');
            echo $response= json_encode(array('checkon'=>$sendRes));
          }
        }else{
          $sendRes = array('msg'=>'QR not Found','status'=>'0');
          echo $response= json_encode(array('checkon'=>$sendRes));
        }
      }
    }
  }

  function addOfflineAttendance(){
    $data=json_decode(file_get_contents("php://input"));
    if(key($data)=="checkon"){
      $list = $data->checkon->offline;
      $check=$this->app->checkMobile($data->checkon->mobile);
      $groups = $this->app->getUserGroup($check['business_group']);
      $currentMonth = strtotime(date("d-m-Y",strtotime("0 months")));
      $startTimestamp = strtotime(date("01-m-Y 00:00:00",$currentMonth));
      $endTimestamp = strtotime(date("t-m-Y 12:59:59",$currentMonth));
      $userCmp = $this->app->getUserCompany($check['id']);
      $leaves = $this->app->getEmpLeaves($check['id']);
      if($check['device_id']==$data->checkon->device_id){
          if(isset($userCmp) && ($userCmp['left_date']=="" || $userCmp['left_date']>time())){
        $company = $this->app->getbussnames($userCmp['business_id']);
        $user_status = $this->app->userCmpStatus($check['id'],$userCmp['business_id']);
        $newQrs = $this->app->getCompanyNewQr($userCmp['business_id']);
        $sections = $this->app->getCompanyMacByType($userCmp['business_id'],$check['section']);

        $options = array(
          'qr'=>$userCmp['qr'],
          'gps'=>$userCmp['gps'],
          'face'=>$userCmp['face'],
          'colleague'=>$userCmp['colleague'],
          'auto_gps'=>$userCmp['auto_gps'],
          'gps_tracking'=>$userCmp['gps_tracking'],
          'field_duty'=>$userCmp['field_duty'],
          'four_layer_security'=>$userCmp['four_layer_security'],
          'selfie_with_gps'=>$userCmp['selfie_with_gps'],
          'selfie_with_field_duty'=>$userCmp['selfie_with_field_duty']
        );

        $cmp_array = array(
            'company_id'=>$company['id'],
          'company_name'=>$company['name'],
          'company_mobile'=>$company['mobile'],
          'company_image'=>$company['image'],
          'company_mac'=>$company['mac'],
          'wifi_strength'=>$company['strength'],
          'status'=>$user_status['user_status'],
          'shift_start'=>"",
          'shift_end'=>"",
          'preMonth'=>"",
          'nextMonth'=>"",
          'month'=>"",
          'doj'=>$userCmp['doj'],
          'mid'=>$company['m_id'],
          'newQr'=>$newQrs,
          'manager'=>$check['manager']
        );
        $holidays = $this->app->getHoliday($userCmp['business_id']);
        $groups = $this->app->getUserGroup($check['business_group']);

        $msg = 'Response';
        $status= '0';
      foreach($list as $at){

        if(!empty($check['id']) && $check['user_group']==2){

          $user_status = $this->app->userCmpStatus($check['id'],$at->business_id);

          $userCmp = $this->app->getUserCompany($check['id']);
          $start_time = strtotime(date("d-m-Y 00:00:00",$at->time));
          $end_time = strtotime(date("d-m-Y 23:59:59",$at->time));
          if( !empty($userCmp['business_id']) && $userCmp['business_id']==$at->business_id){
            if($user_status['user_status']=="1"){
              $checkOffline = $this->app->checkOfflineAt($check['id'],$at->business_id,$start_time,$end_time);
              if(empty($checkOffline) || $checkOffline['io_time']!=$at->time || $at->manual==2){
                $at_ver = $at->verified;
                $ver = $this->app->getEmpOptions($check['id'],$at->business_id);
                if($at->location!="" && $at->verified=="0"){
                  if($ver['auto_gps']=="1"){
                    $at_ver = "1";
                  }
                }
                $selfie = $at->selfie;
                if(!empty($selfie)){
                    $img=base64_decode($selfie);
                    $imgs = imagecreatefromstring($img);
                    if($imgs != false){
                       $date = strtotime(date('Y-m-d h:i:s'));
                       $selfie="upload/selfie/$date.jpg";
                         imagejpeg($imgs, "$selfie");
                    }
                }
                $data = array(
                'bussiness_id'=>$at->business_id,
                'user_id'=>$check['id'],
                'mode'=>$at->mode,
                'io_time'=>$at->time,
                'latitude'=>$at->latitude,
                'longitude'=>$at->longitude,
                'location'=>$at->location,
                'comment'=>$at->comment,
                'emp_comment'=>$at->empComment,
                'manual'=>$at->manual,
                'selfie'=>$selfie,
                'verified'=>$at_ver,
                'date'=>time()
                );
                $res = $this->app->insertAttendance($data);
                $msg = 'Added Offline Attendance';
                $status= '1';
              }
            }else{
              $msg = 'Not Active';
              $status= '0';
            }

          }else{
            $msg = 'Wrong Company QR';
            $status= '0';
          }
        }
      }
      $offline = $this->app->getUserAttendanceAll($check['id']);
      $newOffline = array();
      foreach($offline as $at){
          $selfie = $at->selfie;
          if(!empty($selfie)){
              $url=base_url();
              $selfie=$url.'/'.$selfie;
              $im = file_get_contents($selfie);
              $selfie = base64_encode($im);
          }
          $newOffline[]=array(
                "id"=>$at->id,
                "bussiness_id"=>$at->bussiness_id,
                "user_id"=>$at->user_id,
                "mode"=>$at->mode,
                "comment"=>$at->comment,
                "emp_comment"=>$at->emp_comment,
                "manual"=>$at->manual,
                "latitude"=>$at->latitude,
                "longitude"=>$at->longitude,
                "location"=>$at->location,
                "io_time"=>$at->io_time,
                "selfie"=>$selfie,
                "date"=>$at->date,
                "verified"=>$at->verified,
                "status"=>$at->status
              );
      }

    //   if($data->checkon->offlineSize==0){

    //   }else{
    //     $offline = $this->app->getUserAttendance($check['id'],$startTimestamp,$endTimestamp);
    //   }

      $sendRes = array('msg'=>$msg,'status'=>$status,'offline'=>$newOffline,'company_data'=>$cmp_array,'holiday'=>$holidays,'group'=>$groups,'leaves'=>$leaves,'logout'=>'0','sections'=>$sections,'user_section'=>$check['section'],'options'=>$options);
      $response= json_encode(array('checkon'=>$sendRes));
      echo $response;
    }else{
        $cmp_array = array('company_name'=>"",'company_image'=>"",'status'=>'0','preMonth'=>0,'nextMonth'=>0,'month'=>'0','manager'=>'0','logout'=>'0');
        $msg = 'Please Add Company';
        $status= '0';
        $sendRes = array('msg'=>$msg,'status'=>$status,'logout'=>'0');

        echo $response= json_encode(array('checkon'=>$sendRes));
    }
      }else{
          $cmp_array = array('logout'=>'1');
          echo $response= json_encode(array('checkon'=>$cmp_array));
      }

  }
  }

  function updateSection(){
    $data=json_decode(file_get_contents("php://input"));
    if(key($data)=="checkon"){
      $check=$this->app->checkMobile($data->checkon->mobile);
      $cmpMac = $this->app->getCompanyMacByType($check['id'],$data->checkon->type);
      if(!empty($check['id']) && $check['user_group']==1 && !empty($cmpMac)){
        $res = $this->app->updateCompanyMacByType($check['id'],$data->checkon->name,$data->checkon->ssid,$data->checkon->mac,$data->checkon->strength,$data->checkon->type,$data->checkon->location,$data->checkon->latitude,$data->checkon->longitude,$data->checkon->radius);
        if($res){
          $cmpMac = $this->app->getCompanyMacByType($check['id'],$data->checkon->type);
          $sendRes = array('msg'=>'Wifi Added','status'=>'1','data'=>$cmpMac);
          echo $response= json_encode(array('checkon'=>$sendRes));
        }else{
          $sendRes = array('msg'=>'Failed to Add Wifi','status'=>'0','data'=>$cmpMac);
          echo $response= json_encode(array('checkon'=>$sendRes));
        }
      }else{
        $data = array(
        'bid'=>$check['id'],
        'type'=>$data->checkon->type,
        'name'=>$data->checkon->name,
        'ssid'=>$data->checkon->ssid,
        'mac'=>$data->checkon->mac,
        'strength'=>$data->checkon->strength,
        'location'=>$data->checkon->location,
        'latitude'=>$data->checkon->latitude,
        'longitude'=>$data->checkon->longitude,
        'radius'=>$data->checkon->radius,
        'date_time'=>time()
        );
        $res = $this->app->insertCompanyMacByType($data);
        if($res){
          $cmpMac = $this->app->getCompanyMacByType($check['id'],$data->checkon->type);
          $sendRes = array('msg'=>'Wifi Added','status'=>'1','data'=>$cmpMac);
          echo $response= json_encode(array('checkon'=>$sendRes));
        }else{
          $sendRes = array('msg'=>'Failed to Add Wifi','status'=>'0','data'=>$cmpMac);
          echo $response= json_encode(array('checkon'=>$sendRes));
        }
        $sendRes = array('msg'=>'','status'=>'0','data'=>$cmpMac);
        echo $response= json_encode(array('checkon'=>$sendRes));
      }
    }
  }

  function getSections(){
   $data=json_decode(file_get_contents("php://input"));
   if(key($data)=="checkon"){
     $check=$this->app->checkMobile($data->checkon->mobile);
     if(!empty($check['id']) && $check['user_group']==1){
       $res = $this->app->getSections($check['id']);
       if(!empty($res)){
         echo $response= json_encode(array('checkon'=>$res));
       }else{
         $sendRes = array('msg'=>'No Data Found','status'=>'0');
         echo $response= json_encode(array('checkon'=>$sendRes));
       }
     }
   }
  }

  function updateLocation(){
    $data=json_decode(file_get_contents("php://input"));
    if(key($data)=="checkon"){
      $check=$this->app->checkMobile($data->checkon->mobile);
      $cmpMac = $this->app->getCompanyMacByType($check['id'],$data->checkon->type);
      if(!empty($check['id']) && $check['user_group']==1 && !empty($cmpMac)){
        $res = $this->app->updateCompanyLocationByType($check['id'],$data->checkon->location,$data->checkon->latitude,$data->checkon->longitude,$data->checkon->radius,$data->checkon->type);
        if($res){
          $cmpMac = $this->app->getCompanyMacByType($check['id'],$data->checkon->type);
          $sendRes = array('msg'=>'Location Added','status'=>'1','data'=>$cmpMac);
          echo $response= json_encode(array('checkon'=>$sendRes));
        }else{
          $sendRes = array('msg'=>'Failed to Add Location','status'=>'0','data'=>$cmpMac);
          echo $response= json_encode(array('checkon'=>$sendRes));
        }
      }else{
        $data = array(
        'bid'=>$check['id'],
        'type'=>$data->checkon->type,
        'location'=>$data->checkon->location,
        'latitude'=>$data->checkon->latitude,
        'longitude'=>$data->checkon->longitude,
        'radius'=>$data->checkon->radius,
        'date_time'=>time()
        );
        $res = $this->app->insertCompanyMacByType($data);
        if($res){
          $cmpMac = $this->app->getCompanyMacByType($check['id'],$data->checkon->type);
          $sendRes = array('msg'=>'Location Added','status'=>'1','data'=>$cmpMac);
          echo $response= json_encode(array('checkon'=>$sendRes));
        }else{
          $sendRes = array('msg'=>'Failed to Add Location','status'=>'0','data'=>$cmpMac);
          echo $response= json_encode(array('checkon'=>$sendRes));
        }
        $sendRes = array('msg'=>'','status'=>'0','data'=>$cmpMac);
        echo $response= json_encode(array('checkon'=>$sendRes));
      }
    }
  }

  function deactivateNewQr(){
    $mid=json_decode(file_get_contents("php://input"));
    if(key($mid)=="checkon"){
      $check=$this->app->checkMobile($mid->checkon->mobile);
      if(!empty($check['id'])){
        $res = $this->app->deactivateNewQR($check['id']);
        if($res){
          $sendRes = array('msg'=>'QR Deactivated Successfully','status'=>'1');
          echo $response= json_encode(array('checkon'=>$sendRes));
        }else{
          $sendRes = array('msg'=>'Failed to deactivate','status'=>'0');
          echo $response= json_encode(array('checkon'=>$sendRes));
        }
      }
    }
  }

  function hasNewQr(){
    $mid=json_decode(file_get_contents("php://input"));
    if(key($mid)=="checkon"){
      $check=$this->app->checkMobile($mid->checkon->mobile);
      if(!empty($check['id'])){
        $res = $this->app->hasNewQR($check['id']);
        if(!empty($res)){
          $sendRes = array('msg'=>'Found Qr','status'=>'1');
          echo $response= json_encode(array('checkon'=>$sendRes));
        }else{
          $sendRes = array('msg'=>'Not Found Qr','status'=>'0');
          echo $response= json_encode(array('checkon'=>$sendRes));
        }
      }
    }
  }

  function getDepartmentSections(){
   $data=json_decode(file_get_contents("php://input"));
   if(key($data)=="checkon"){
     $check=$this->app->checkMobile($data->checkon->mobile);
     if(!empty($check['id']) && $check['user_group']==1){
       $res = $this->app->getDepartmentSections($check['id']);
       if(!empty($res)){
         echo $response= json_encode(array('checkon'=>$res));
       }else{
         $sendRes = array('msg'=>'No Data Found','status'=>'0');
         echo $response= json_encode(array('checkon'=>$sendRes));
       }
     }
   }
  }

  function addDepartmentSections(){
   $data=json_decode(file_get_contents("php://input"));
   if(key($data)=="checkon"){
     $check=$this->app->checkMobile($data->checkon->mobile);
     if(!empty($check['id']) && $check['user_group']==1){
       $data = array(
       'bid'=>$check['id'],
       'name'=>$data->checkon->name,
       'date_time'=>time()
       );
       $res = $this->app->insertDepartmentSection($data);
       if($res){
         $sendRes = array('msg'=>'Department Added Successfully','status'=>'1');
         echo $response= json_encode(array('checkon'=>$sendRes));
       }else{
         $sendRes = array('msg'=>'Failed to Add Department','status'=>'0');
         echo $response= json_encode(array('checkon'=>$sendRes));
       }
     }
   }
  }

  function editDepartmentSections(){
   $data=json_decode(file_get_contents("php://input"));
   if(key($data)=="checkon"){
     $check=$this->app->checkMobile($data->checkon->mobile);
     if(!empty($check['id']) && $check['user_group']==1){
       $res = $this->app->editDepartmentSections($data->checkon->id,$check['id'],$data->checkon->name);
       if($res){
         $sendRes = array('msg'=>'Department Update Successfully','status'=>'1');
         echo $response= json_encode(array('checkon'=>$sendRes));
       }else{
         $sendRes = array('msg'=>'Failed to Update Department','status'=>'0');
         echo $response= json_encode(array('checkon'=>$sendRes));
       }
     }
   }
  }

  function getCompanySections(){
   $data=json_decode(file_get_contents("php://input"));
   if(key($data)=="checkon"){
     $check=$this->app->checkMobile($data->checkon->mobile);
     if(!empty($check['id']) && $check['user_group']==2){
       $userCmp = $this->app->getUserCompany($check['id']);
       if(isset($userCmp) && ($userCmp['left_date']=="" || $userCmp['left_date']>time())){
         $sections = $this->app->getSections($userCmp['business_id']);
         if(!empty($sections)){
           echo $response= json_encode(array('checkon'=>$sections));
         }else{
           $sendRes = array('msg'=>'No Data Found','status'=>'0');
           echo $response= json_encode(array('checkon'=>$sendRes));
         }
       }else{
           $msg = 'Please Add Company';
           $status= '0';
           $sendRes = array('msg'=>$msg,'status'=>$status);
           echo $response= json_encode(array('checkon'=>$sendRes));
       }
     }
   }
  }

  public function updateUserProfile(){
    $data=json_decode(file_get_contents("php://input"));
    if(key($data)=="checkon"){
      $check=$this->app->checkMobile($data->checkon->mobile);
      if(!empty($check)){
             if($data->checkon->get=="0" && $data->checkon->offline=="1"){
                 $uploadedImg = $check['image'];
                 if($data->checkon->imageChange=="1"){
                    $img=base64_decode($data->checkon->image);
                    $imgs = imagecreatefromstring($img);
                    if($imgs != false){
                    $date = strtotime(date('Y-m-d h:i:s'));
                    $uploadedImg="upload/$date.jpg";
                    imagejpeg($imgs, "$uploadedImg");
                    }
                 }
                 $update=array(
                   'name'=>$data->checkon->name,
                   'website'=>$data->checkon->website,
                   'email'=>$data->checkon->emailid,
                   'address'=>$data->checkon->address,
                   'facebookid'=>$data->checkon->facebookid,
                   'twitterid'=>$data->checkon->twitterid,
                   'instagramid'=>$data->checkon->instagramid,
                   'description'=>$data->checkon->description,
                   'youtube'=>$data->checkon->youtubeid,
                   'BussinessType'=>$data->checkon->businessType,
                   'paymentlink'=>$data->checkon->paymentlink,
                   'about_us'=>$data->checkon->aboutus,
                   'googleprofile'=>$data->checkon->googleprofile,
                   'image'=>$uploadedImg,
                 );
                 $this->db->where('id',$check['id']);
                 $update=$this->db->update('login',$update);
             }

             $check=$this->app->checkMobile($data->checkon->mobile);
             $url=base_url();
             if(!empty($check['image']))
             {
                 $image=$url.'/'.$check['image'];
             }
             else
             {
                  $image=$url.'/upload/nextpng.png';
             }
             $im = file_get_contents($image);
             $image = base64_encode($im);
             $premium = $check['premium'];
             $requested = 0;
             if($premium==3){
               $requested=1;
             }
             if($check['validity']!="" && $check['validity']>time()){
               $premium = "2";
             }else{
               if($check['start_date']!="" && strtotime('+10 day',$check['start_date'])>time()){
                 $premium = "1";
               }else{
                 $premium = "0";
               }
             }
             $ss=array(
              'id'=>$check['id'],
              'mobile'=>$check['mobile'],
              'name'=>$check['name'],
              'address'=>$check['address'],
              'user_group'=>$check['user_group'],
              'email'=>$check['email'],
              'image'=>$image,
              'website'=>$check['website'],
              'facebookid'=>$check['facebookid'],
              'twitterid'=>$check['twitterid'],
              'company'=>$check['company'],
              'instagramid'=>$check['instagramid'],
              'description'=>$check['description'],
              'BussinessType'=>$check['BussinessType'],
              'googleprofile'=>$check['googleprofile'],
              'aboutus'=>$check['about_us'],
              'youtube'=>$check['youtube'],
              'BussinessType'=>$check['BussinessType'],
              'Latitude'=>$check['Latitude'],
              'Longitude'=>$check['Longitude'],
              'premium'=>$premium,
              'requested'=>$requested,
              'validity'=>date("d-M-Y",(Int)$check['validity'])
            );
          $response=array('Data'=>$data);
          echo $response= json_encode(array('checkon'=>$ss));

      }
      else{
        $respons=array('msg'=>'Profile Not Found');
        echo $response= json_encode(array('checkon'=>$respons));
      }
    }
  }

  function getPendingAttendance(){
    $data=json_decode(file_get_contents("php://input"));
    if(key($data)=="checkon"){
      $check=$this->app->checkMobile($data->checkon->mobile);
      if(!empty($check['id']) && $check['user_group']==1){
        $users_data = $this->app->getCompanyUsers($check['id']);
        $start_time = strtotime(date("d-m-Y 00:00:00",strtotime($data->checkon->date)));
        $end_time = strtotime(date("d-m-Y 23:59:59",strtotime($data->checkon->date)));

        $holidays = $this->app->getHoliday($check['id']);
        $holiday_array = array();
        if($holidays){
          foreach($holidays as $holiday){
            $holiday_array[] = array(
              'date'=>date('d.m.Y',$holiday->date),
            );
          }
        }
        if(!empty($users_data)){
          $new_array = array();
          foreach($users_data as $user){
              if(($user->doj!="" || $start_time>=$user->doj) && ($user->left_date=="" || $start_time<$user->left_date)){
                  $user_at = $this->app->getUserPendingAttendance($user->user_id,$check['id'],0);
                $data = array();

                $groups = $this->app->getUserGroup($user->business_group);
                $grp = array();

                if($groups){
                  $weekly_off = explode(",",$groups->weekly_off);
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

                $off = array_search(date('N',$start_time),array_column($grp,'day_off'));
                $holi = array_search(date('d.m.Y',$start_time),array_column($holiday_array,'date'));

                if(!is_bool($off)){
                  $weekOff = "1";
                }else{
                  $weekOff = "0";
                }

                if(!is_bool($holi)){
                  $holiday="1";
                }else{
                  $holiday="0";
                }
                if(!empty($user_at)){
                  foreach($user_at as $at){
                    $data[] = array(
                      'id'=>$at->id,
                      'mode'=>$at->mode,
                      'date'=>date('d M Y', $at->io_time),
                      'time'=>date('h:i A', $at->io_time),
                      'comment'=>$at->comment."\n".$at->emp_comment,
                      'emp_comment'=>$at->emp_comment,
                      'verified'=>$at->verified,
                      'latitude'=>$at->latitude,
                      'longitude'=>$at->longitude,
                      'location'=>$at->location
                    );
                  }
                }else{
                  $data = array();
                }
                $new_array[] =array(
                  'user_id'=>$user->user_id,
                  'name'=>$user->name,
                  'image'=>$user->image,
                  'date'=>date("d M Y",$start_time),
                  'user_status'=>$user->user_status,
                  'weekly_off'=>$weekOff,
                  'holiday'=>$holiday,
                  'shift_start'=>$shift_start,
                  'shift_end'=>$shift_end,
                  'group_name'=>$group_name,
                  'designation'=>$user->designation,
                  'bluetooth_ssid'=>$user->bluetooth_ssid,
                  'bluetooth_mac'=>$user->bluetooth_mac,
                  'data'=> $data
                );
              }
          }
          echo $response= json_encode(array('checkon'=>$new_array));
        }else{
          $res=array('msg'=>'No Data Foud');
          echo $response= json_encode(array('checkon'=>$res));
        }
      }
    }
  }

  function updateEmpOptions(){
   $data=json_decode(file_get_contents("php://input"));
   if(key($data)=="checkon"){
     $check=$this->app->checkMobile($data->checkon->mobile);
     if(!empty($check['id']) && $check['user_group']==1){
       $dt = $this->app->getCmpOptions($check['id']);
       if($dt){
         $res = $this->app->updateCmpOptions($check['id'],$data->checkon->auto_gps,$data->checkon->qr,$data->checkon->gps,$data->checkon->field_duty,$data->checkon->four_layer_security,$data->checkon->face,$data->checkon->selfie_with_gps,$data->checkon->selfie_with_field_duty);
       }else{
         $options=array(
           'bid'=>$check['id'],
           'qr'=>$data->checkon->qr,
           'gps'=>$data->checkon->gps,
           'face'=>$data->checkon->face,
           'auto_gps'=>$data->checkon->auto_gps,
           'field_duty'=>$data->checkon->field_duty,
           'four_layer_security'=>$data->checkon->four_layer_security,
           'selfie_with_gps'=>$data->checkon->selfie_with_gps,
           'selfie_with_field_duty'=>$data->checkon->selfie_with_field_duty,
           'date_time'=>time()
         );
         $res = $this->app->insertCmpOptions($options);
       }
       $res = $this->app->updateEmpOptions($check['id'],$data->checkon->auto_gps,$data->checkon->qr,$data->checkon->gps,$data->checkon->field_duty,$data->checkon->four_layer_security,$data->checkon->face,$data->checkon->selfie_with_gps,$data->checkon->selfie_with_field_duty);
       if($res){
         $sendRes = array('msg'=>'Option Update Successfully','status'=>'1');
         echo $response= json_encode(array('checkon'=>$sendRes));
       }else{
         $sendRes = array('msg'=>'Failed to Update Option','status'=>'0');
         echo $response= json_encode(array('checkon'=>$sendRes));
       }
     }
   }
  }

  function getCmpOption(){
   $data=json_decode(file_get_contents("php://input"));
   if(key($data)=="checkon"){
     $check=$this->app->checkMobile($data->checkon->mobile);
     if(!empty($check['id']) && $check['user_group']==1){
       $res = $this->app->getCmpOptions($check['id']);
       if(!empty($res)){
         echo $response= json_encode(array('checkon'=>$res));
       }else{
         $sendRes = array('msg'=>'No Data Found','status'=>'0');
         echo $response= json_encode(array('checkon'=>$sendRes));
       }
     }
   }
  }

  function getEmpLeavesType(){
   $data=json_decode(file_get_contents("php://input"));
   if(key($data)=="checkon"){
     $check=$this->app->checkMobile($data->checkon->mobile);
     if(!empty($check['id']) && $check['user_group']==2){
       $userCmp = $this->app->getUserCompany($check['id']);
       if(isset($userCmp) && ($userCmp['left_date']=="" || $userCmp['left_date']>time())){
         $res = $this->app->getOpenLeave($userCmp['business_id'],$check['id']);
         if(!empty($res)){
           echo $response= json_encode(array('checkon'=>$res));
         }else{
           $sendRes = array('msg'=>'No Data Found','status'=>'0');
           echo $response= json_encode(array('checkon'=>$sendRes));
         }
       }else{
           $msg = 'Please Add Company';
           $status= '0';
           $sendRes = array('msg'=>$msg,'status'=>$status);
           echo $response= json_encode(array('checkon'=>$sendRes));
       }
     }
   }
  }

  function addUserGPSAttendance(){
    $data=json_decode(file_get_contents("php://input"));
    if(key($data)=="checkon"){
      $check=$this->app->checkMobile($data->checkon->mobile);
      if(!empty($check['id']) && $check['user_group']==2){
        $userCmp = $this->app->getUserCompany($check['id']);


        if( !empty($userCmp['business_id'])){
            $user_status = $this->app->userCmpStatus($check['id'],$userCmp['business_id']);
          if($user_status['user_status']=="1"){
            $data = array(
              'bussiness_id'=>$userCmp['business_id'],
              'user_id'=>$check['id'],
              'mode'=>$data->checkon->mode,
              'comment'=>"",
              'latitude'=>$data->checkon->latitude,
              'longitude'=>$data->checkon->longitude,
              'location'=>$data->checkon->location,
              'emp_comment'=>$data->checkon->empComment,
              'verified'=>$data->checkon->verified,
              'manual'=>$data->checkon->manual,
              'io_time'=>time(),
              'date'=>time()
            );
            $res = $this->app->insertAttendance($data);
            if($res == 1){
              $sendRes = array('msg'=>'Attendance added Successfully','status'=>'1');
            }else{
              $sendRes = array('msg'=>'Failed to Add','status'=>'0');
            }
            echo $response= json_encode(array('checkon'=>$sendRes));
          }else{
            $sendRes = array('msg'=>'Not Active','status'=>'0');
            echo $response= json_encode(array('checkon'=>$sendRes));
          }

        }else{
          $sendRes = array('msg'=>'Wrong Company QR','status'=>'0');
          echo $response= json_encode(array('checkon'=>$sendRes));
        }
      }
    }
  }

  function manageFaceAttendance(){
      $data=json_decode(file_get_contents("php://input"));
      if(key($data)=="checkon"){
        $check=$this->app->checkMobile($data->checkon->mobile);
        if(!empty($check['id'])){
          $loginid=$data->checkon->userId;
          $userCmp = $this->app->getUserCompany($loginid);
          $user_status = $this->app->userCmpStatus($loginid,$userCmp['business_id']);
          $userMobile=$this->app->getbussnames($loginid);
          if($userCmp['business_id']){
            if($user_status['user_status']=="1"){
              $mode = "in";
              $start_time = strtotime(date("d-m-Y 00:00:00"));
              $end_time = strtotime(date("d-m-Y 23:59:59"));
              $res_mode = $this->app->getUserAttendanceByDate($start_time,$end_time,$loginid,$userCmp['business_id'],1);

              if(!empty($res_mode)){
                if($res_mode[0]->mode=="in"){
                  $mode = "out";
                }
              }
              $time = time();
              $data = array(
                'bussiness_id'=>$userCmp['business_id'],
                'user_id'=>$loginid,
                'mode'=>$mode,
                'io_time'=>$time,
                'date'=>$time
              );
              $res = $this->app->insertAttendance($data);
              if($res == 1){
                $sendRes = array('msg'=>$userMobile['name'],'image'=>$userMobile['image'],'mode'=>$mode,'time'=>date('h:i A',$time),'status'=>'1');
              }else{
                $sendRes = array('msg'=>'Failed to Add','status'=>'0');
              }
              echo $response= json_encode(array('checkon'=>$sendRes));
            }else{
              $sendRes = array('msg'=>'Not Active','status'=>'0');
              echo $response= json_encode(array('checkon'=>$sendRes));
            }

          }else{
            $sendRes = array('msg'=>'Not in company','status'=>'0');
            echo $response= json_encode(array('checkon'=>$sendRes));
          }
        }
      }
    }
    function checkStaffMobile(){
     $data=json_decode(file_get_contents("php://input"));
     if(key($data)=="checkon"){
       $check=$this->app->checkMobile($data->checkon->mobile);
       if(!empty($check['id']) && $check['user_group']==1){
         $addStaff=$this->app->checkMobile($data->checkon->staffMobile);
         if(!empty($addStaff)){
           $userCmp = $this->app->getUserCompany($addStaff['id']);
           if(isset($userCmp) && ($userCmp['left_date']=="" || $userCmp['left_date']>time())){
             $sendRes = array('msg'=>'Data Found','status'=>'2');
           }else{
             $sendRes = array('msg'=>'Data Found','status'=>'1');
           }
           echo $response= json_encode(array('checkon'=>$sendRes));
         }else{
           $sendRes = array('msg'=>'No Data Found','status'=>'0');
           echo $response= json_encode(array('checkon'=>$sendRes));
         }
       }
     }
    }
    
    function addStaffToCompany(){
     $data=json_decode(file_get_contents("php://input"));
     if(key($data)=="checkon"){
       $check=$this->app->checkMobile($data->checkon->mobile);
       if(!empty($check['id']) && $check['user_group']==1){
         $addStaff=$this->app->checkMobile($data->checkon->staffMobile);
         if(!empty($addStaff)){
           $userCmp = $this->app->getUserCompany($addStaff['id']);
           if(isset($userCmp) && ($userCmp['left_date']=="" || $userCmp['left_date']>time())){
             $sendRes = array('msg'=>'Already Added in a Company','status'=>'2');
           }else{
             $data1=array(
   						'doj'=>strtotime(date("d-m-Y 00:00:00",time()))
   					);
   					$this->db->where('id',$addStaff['id']);
   					$data= $this->db->update('login',$data1);
   					$cmpInData = array(
   						'business_id'=>$check['id'],
   						'user_id'=>$addStaff['id'],
   						'doj'=>strtotime(date("d-m-Y 00:00:00",time())),
   						'date'=>time(),
   						'user_status'=>"1"
   					);
   					$data=$this->db->insert('user_request',$cmpInData);
             $sendRes = array('msg'=>'New Employee Added','status'=>'1');
           }
           echo $response= json_encode(array('checkon'=>$sendRes));
         }else{
           $sendRes = array('msg'=>'No Data Found','status'=>'0');
           echo $response= json_encode(array('checkon'=>$sendRes));
         }
       }
     }
    }
    
    function getCompanyLicence(){
     $data=json_decode(file_get_contents("php://input"));
     if(key($data)=="checkon"){
       $check=$this->app->checkMobile($data->checkon->mobile);
       if(!empty($check['id'])){

         $newQr=$this->app->getCompanyLicence($check['id']);
         if(!empty($newQr)){
             foreach($newQr as $qr){
              $qr->qr_code = base_url("User/qrProfile/".$qr->qr_code);
            }
           echo $response= json_encode(array('checkon'=>$newQr));
         }else{
           $sendRes = array('msg'=>'No Data Found','status'=>'0');
           echo $response= json_encode(array('checkon'=>$sendRes));
         }
       }
     }
    }
    
    function transferLicence(){
      $data=json_decode(file_get_contents("php://input"));
      if(key($data)=="checkon"){
        $check=$this->app->checkMobile($data->checkon->mobile);
        if(!empty($check['id'])){
          $transferCheck=$this->app->checkMobile($data->checkon->transferMobile);
          if($transferCheck){
              $licence_ids = explode(",",$data->checkon->licence);
              foreach($licence_ids as $licence_id){
                  $transfer=$this->app->transferLicence($check['id'],$transferCheck['id'],$licence_id);
              }
            if(true){
                $licenceData = array(
                "licence_from"=>$check['id'],
                "licence_to"=>$transferCheck['id'],
                "licence_id"=>$data->checkon->licence,
                "quantity"=>count($licence_ids),
                "date_time"=>time()
              );
              $history = $this->app->insertLicenceHistory($licenceData);
              $sendRes = array('msg'=>'Licence Send Successfully','status'=>'1');
              echo $response= json_encode(array('checkon'=>$sendRes));
            }else{
              $sendRes = array('msg'=>'Failed to Send','status'=>'0');
              echo $response= json_encode(array('checkon'=>$sendRes));
            }
          }else{
            $sendRes = array('msg'=>'Mobile Number Not Found','status'=>'0');
              echo $response= json_encode(array('checkon'=>$sendRes));
          }
        }
      }
     }
     
     function licenceHistory(){
      $data=json_decode(file_get_contents("php://input"));
      if(key($data)=="checkon"){
        $check=$this->app->checkMobile($data->checkon->mobile);
        if(!empty($check['id'])){
 
          $licenceHistory=$this->app->licenceHistory($check['id']);
          if(!empty($licenceHistory)){
              foreach($licenceHistory as $qr){
              $qr->date_time = date("d-M-Y",$qr->date_time);
            }
            echo $response= json_encode(array('checkon'=>$licenceHistory));
          }else{
            $sendRes = array('msg'=>'No Data Found','status'=>'0');
            echo $response= json_encode(array('checkon'=>$sendRes));
          }
        }
      }
     }
     
     function getCompanyClients(){
      $data=json_decode(file_get_contents("php://input"));
      if(key($data)=="checkon"){
        $check=$this->app->checkMobile($data->checkon->mobile);
        if(!empty($check['id'])){
 
          $getCompanyClients=$this->app->getCompanyClients($data->checkon->mobile);
          if(!empty($getCompanyClients)){
           $clients = array();
            foreach($getCompanyClients as $client){
              $validFrom="";
              $validTo="";
              if($client->start_date!=""){
                $validFrom = date("d-m-Y",$client->start_date);
              }
              if($client->validity!=""){
                $validTo = date("d-m-Y",$client->validity);
              }
              $clients[]=array(
                'id'=>$client->id,
                'name'=>$client->name,
                'mobile'=>$client->mobile,
                'valid_from'=>$validFrom,
                'valid_to'=>$validTo
              );
            }
            echo $response= json_encode(array('checkon'=>$clients));
          }else{
            $sendRes = array('msg'=>'No Data Found','status'=>'0');
            echo $response= json_encode(array('checkon'=>$sendRes));
          }
        }
      }
     }
}
