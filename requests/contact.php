<?php
if((filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) || empty($_POST['email'])) && in_array($_POST['subject'], array(0, 1, 2, 3))){
	$emptys = array();
	$name = Specific::Filter($_POST['name']);
	$subject = Specific::Filter($_POST['subject']);
	$email = Specific::Filter($_POST['email']);
	$text = Specific::Filter($_POST['text']);
	if (empty($name)){
        $emptys[] = 'name';
    }
    if(empty($email)){
        $emptys[] = 'email';
    }
    if (empty($text)) {
        $emptys[] = 'text';
    }
    
    $subject = $TEMP['#word']['other'];
    if($subject == 0){
    	$subject = $TEMP['#word']['copyright_'];
    } else if($subject == 1){
    	$subject = $TEMP['#word']['ads'];
    } else if($subject == 2){
    	$subject = $TEMP['#word']['errors'];
    }
    if(empty($emptys)){
    	$send_email_data = array(
		    'from_email' => $TEMP['#settings']['smtp_username'],
		    'from_name' => $name,
        	'reply_to' => $email,
		    'to_email' => $TEMP['#settings']['smtp_username'],
		    'to_name' => $TEMP['#settings']['title'],
		    'subject' => $subject,
		    'charSet' => 'UTF-8',
		    'text_body' => $text
		);
		if(Specific::SendEmail($send_email_data)){
			$deliver['status'] = 200;
		} else {
			$deliver = array(
				'status' => 400,
				'error' => $TEMP['#word']['could_not_send_message_error']
			);
		}
    } else {
    	$deliver = array(
    		'status' => 400,
    		'emptys' => $emptys
    	);
    }
}
?>