<?php 
if ($TEMP['#loggedin'] === false) {
	$deliver = array(
		'status' => 400,
		'error' => $TEMP['#word']['error']
	);
    echo json_encode($deliver);
    exit();
} else {
	$html = "";
	$type = Specific::Filter($_POST['type']);
	if ($type == 'new') {
		$live_notify = $dba->query('SELECT COUNT(*) FROM notifications WHERE seen = 0 AND to_id = '.$TEMP['#user']['id'])->fetchArray();
		if(!empty($live_notify)){
			$deliver['status'] = 200;
			$deliver['new'] = $live_notify > 9 ? '9+' : $live_notify;
		}
	} else if ($type == 'click'){
		$deliver['status'] = 304;
		$notifications = $dba->query('SELECT * FROM notifications WHERE to_id = '.$TEMP['#user']['id'].' ORDER BY id DESC LIMIT 10')->fetchAll();
		if(count($notifications) > 0){
			foreach ($notifications as $value) {
				$user_data = Specific::Data($value['from_id']);
				$course = $dba->query('SELECT name FROM courses WHERE id = '.$value['course_id'])->fetchArray();
				$TEMP['!text'] = "{$TEMP['#word']['enrolled_your_course']}: <b>$course</b>";
				$TEMP['!url'] = '#';
				if($value['type'] == 'note'){
					$TEMP['!text'] = "{$TEMP['#word']['just_uploaded_your_grade_course']}: <b>$course</b>";
					$TEMP['!url'] = Specific::Url('notes');
				} else if($value['type'] == 'authorize'){
					$TEMP['!text'] = "{$TEMP['#word']['just_applied_authorization_in_the_course']}: <b>$course</b>";
					$TEMP['!url'] = Specific::Url('more?page=authorizations');
				} else if($value['type'] == 'auth_authorized'){
					$TEMP['!text'] = "{$TEMP['#word']['you_authorized_upload_grades_course_of']}: <b>$course</b>";
					$TEMP['!url'] = Specific::Url('authorizations');
				} else if($value['type'] == 'auth_denied'){
					$TEMP['!text'] = "{$TEMP['#word']['your_authorization_upload_grades_course_denied']} <b>$course</b> {$TEMP['#word']['was_denied']}";
					$TEMP['!url'] = Specific::Url('authorizations');
				} else if($value['type'] == 'qualification'){
					$TEMP['!text'] = "{$TEMP['#word']['you_have_just_applied_course']} <b>$course</b>";
					$TEMP['!url'] = Specific::Url('more?page=qualifications');
				}
				$TEMP['!id'] = $value['id'];
				$TEMP['!data'] = $user_data;
				$TEMP['!point_active'] = $value['seen'] == 0 ? ' background-red' : '';
				$TEMP['!time'] = Specific::DateString($value['time']);
				
				$html .= Specific::Maket('notifications');
			}
			Specific::DestroyMaket();
			$dba->query('UPDATE notifications SET seen = '.time().' WHERE to_id = '.$TEMP['#user']['id']);
			$deliver = array(
				'status' => 200,
				'html' => $html
			);
		}
	}
}
?>