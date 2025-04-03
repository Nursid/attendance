<?php 
function push_notification_android(){
    //API URL of FCM
    $url = 'https://fcm.googleapis.com/fcm/send';
 
    /*api_key available in:
    Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key*/    
	$api_key = 'AAAAPoWBUlE:APA91bEc5rknh3hGlP1wL2VTz38yYArAlv0wXWoyqmzpfx33OFPI7O4Q6Z0N3bT3ZrddlrGDRmFgmqQBPbKQVmx_cp_xd7_OwnB-ZZpxfVBt-93VOrOtcmsMqGtpqZ3NM-7w22spOhIi'; //Replace with yours
	
	$target = "cfcMQz6JGVo:APA91bFjoKN45oDIEMMH9xz537JQnSuu4CBNjHzYpN5acihRPJkK6hoA9UXlu7rjv72LOeBJGsCukDz5lEA-9gmR-YN_0gTec-51lLrBy4cxeO8CsjQ_o6LxL5xXRFUDwPUW78v4c4Yt";
	
	$fields = array();
	$fields['priority'] = "high";
	$fields['notification'] = [ "title" => "MID", 
				    "body" => "MID", 
				    'data' => ['message' => "MID"],
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
?>