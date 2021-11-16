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
				$TEMP['!text'] = "Se matriculo en tu curso: $course";
				$TEMP['!url'] = '#';
				if($value['type'] == 'note'){
					$TEMP['!text'] = "Acaba de subir tu nota en el curso: $course";
					$TEMP['!url'] = Specific::Url('notes');
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