<?php
if ($TEMP['#loggedin'] === false) {
	header("Location: ".Specific::Url());
	exit();
}

$TEMP['#user_id'] = $TEMP['#user']['id'];
if(isset($_GET['user']) && Specific::Academic() == true){
	$TEMP['#user_id'] = Specific::Filter($_GET['user']);
	if(Specific::Academic() == true){
		$TEMP['user'] = Specific::Data($TEMP['#user_id'])['full_name'];
	}
}

$type = Specific::Filter($_GET['program']);
$TEMP['#keyword_notes'] = Specific::Filter($_GET['keyword']);

$params = "";
$sqls = '';

if(isset($_GET['keyword'])){
	$params .= "?keyword=".$TEMP['#keyword_notes'];
}
if(isset($_GET['user'])){
	$params .= "&user={$TEMP['#user_id']}";
}

if(isset($_GET['type'])){
	$sqls .= ' AND type = '.$type;
	$params .= (!empty($params) ? "&" : "?")."program=$type";
}


$TEMP['#url_params'] = str_replace('?', '&', "&one=enroll$params");
$TEMP['#load_url'] = Specific::Url("enroll$params");

$TEMP['#enrolled'] = $dba->query('SELECT * FROM enrolled WHERE user_id = '.$TEMP['#user_id'].$sqls)->fetchAll();
$programs = $dba->query('SELECT COUNT(*) FROM enrolled WHERE user_id = '.$TEMP['#user_id'].' AND type = "program"')->fetchArray();
$courses = $dba->query('SELECT COUNT(*) FROM enrolled WHERE user_id = '.$TEMP['#user_id'].' AND type = "course"')->fetchArray();

$TEMP['programs'] = $programs.(count($programs) > 1 ? " {$TEMP['#word']['programs']}" : " {$TEMP['#word']['program']}");
$TEMP['courses'] = $courses.(count($courses) > 1 ? " {$TEMP['#word']['courses']}" : " {$TEMP['#word']['course']}");

if(!empty($TEMP['#enrolled'])){
	foreach ($TEMP['#enrolled'] as $enroll) {
		if($enroll['type'] == 'course'){
			$course = $dba->query('SELECT name FROM courses WHERE id = '.$enroll['course_id'])->fetchArray();
			$periodc = $dba->query('SELECT name FROM periods p WHERE (SELECT period_id FROM courses WHERE id = '.$enroll['course_id'].' AND period_id = p.id)')->fetchArray();
			$TEMP['!name'] = "{$course} ($periodc)";
			$TEMP['!color'] = 'purple';
		} else {
			$program = $dba->query('SELECT * FROM programs WHERE id = '.$enroll['program_id'])->fetchArray();
			$TEMP['!name'] = $program['name'];
			$TEMP['!color'] = 'green';
		}
		if($enroll['status'] == 'registered'){
			$TEMP['!id'] = $enroll['id'];
			$TEMP['!text'] = $TEMP['#word']['cancel'];
		} else {
			if($enroll['type'] == 'course'){
				$TEMP['!id'] = $enroll['course_id'];
			} else {
				$TEMP['!id'] = $enroll['program_id'];
			}
			$TEMP['!text'] = $TEMP['#word']['enroll'];
		}
		$TEMP['!class_event'] = $enroll['type'] == 'course' ? 'show_rcmodal"' : 'show_rpmodal"';
		if($enroll['status'] == 'cancelled'){
			if(Specific::Academic() == false){
				$TEMP['!class_event'] = 'cursor-disabled" disabled';
			}
		} else {
			$TEMP['!class_event'] = 'show_cmodal"';
		}
		$TEMP['!status'] = $enroll['status'];
	    $TEMP['!type'] = "{$TEMP['#word'][$enroll['type']]} ({$TEMP['#word'][$enroll['status']]})";
	    $TEMP['!typet'] = $enroll['type'];
	    $TEMP['!time'] = Specific::DateFormat($enroll['time']);
	    $TEMP['enrolled'] .= Specific::Maket("enroll/includes/enrolled");
	}
	Specific::DestroyMaket();
} else {
	$TEMP['enrolled'] .= Specific::Maket("not-found/enrolled");
}


$TEMP['#page']        = 'enroll';
$TEMP['#title']       = $TEMP['#word']['enroll'] . ' - ' . $TEMP['#settings']['title'];
$TEMP['#description'] = $TEMP['#settings']['description'];
$TEMP['#keyword']     = $TEMP['#settings']['keyword'];

$TEMP['#content']     = Specific::Maket('enroll/content');
?>