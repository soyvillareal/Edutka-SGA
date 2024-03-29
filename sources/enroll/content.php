<?php
if ($TEMP['#loggedin'] === false) {
	header("Location: ".Specific::ReturnUrl());
	exit();
}

if (Specific::Teacher() == true) {
	header("Location: ".Specific::Url('404'));
	exit();
}

$TEMP['#user_id'] = $TEMP['#user']['id'];
if(isset($_GET['user']) && (Specific::Admin() == true || Specific::Academic() == true)){
	$TEMP['#user_id'] = Specific::Filter($_GET['user']);
	if(Specific::Admin() == true || Specific::Academic() == true){
		$user = Specific::Data($TEMP['#user_id']);
		$TEMP['full_name'] = $user['full_name'];
	}
}

$TEMP['#type'] = !empty($_GET['type']) ? Specific::Filter($_GET['type']) : 'program';
$TEMP['#keyword_enroll'] = Specific::Filter($_GET['keyword']);
$TEMP['#enrolled'] = false;
$params = "";
$sqls = '';

if(isset($_GET['keyword'])){
	$params .= "?keyword=".$TEMP['#keyword_enroll'];
}
if(isset($_GET['user'])){
	$params .= "&user={$TEMP['#user_id']}";
}

$TEMP['#program_id'] = Specific::Filter($_GET['program_id']);
if(empty($TEMP['#program_id'])){
	$TEMP['#program_id'] = $user['program'];
	if(empty($TEMP['#program_id'])){
		$TEMP['#program_id'] = $TEMP['#user']['program'];
	}
}

if($TEMP['#type'] == 'course'){
	if(!empty($TEMP['#program_id'])){
		$params .= (!empty($params) ? "&" : "?")."program_id={$TEMP['#program_id']}";
		$sqls .= " AND program_id = '{$TEMP['#program_id']}'";
	}
}
$sqls .= " AND type = '{$TEMP['#type']}'";
$params .= (!empty($params) ? "&" : "?")."type={$TEMP['#type']}";

$programs = $dba->query('SELECT program_id FROM enrolled WHERE user_id = '.$TEMP['#user_id'].' AND type = "program"')->fetchAll(false);

$TEMP['#programs'] = 0;
if(!empty($programs)){
	$TEMP['#programs'] = $dba->query('SELECT * FROM program WHERE id IN ('.implode(',', $programs).')')->fetchAll();
}

$courses = $dba->query('SELECT COUNT(*) FROM enrolled WHERE user_id = '.$TEMP['#user_id'].' AND type = "course"')->fetchArray();

$programs = count($programs);

$TEMP['programs'] = $programs.($programs > 1 || $programs == 0 ? " {$TEMP['#word']['programs']}" : " {$TEMP['#word']['program']}");
$TEMP['courses'] = $courses.($courses > 1 || $courses == 0 ? " {$TEMP['#word']['courses']}" : " {$TEMP['#word']['course']}");


$TEMP['#url_params'] = str_replace('?', '&', "&one=enroll$params");
$TEMP['#load_url'] = Specific::Url("enroll$params");

$enrolled = $dba->query('SELECT * FROM enrolled WHERE user_id = '.$TEMP['#user_id'].$sqls)->fetchAll();

if(!empty($enrolled)){
	$TEMP['#enrolled'] = true;
	foreach ($enrolled as $enroll) {
		if($enroll['type'] == 'course'){
			$estatus = $dba->query('SELECT status FROM enrolled e WHERE user_id = '.$TEMP['#user_id'].' AND type = "program" AND program_id = '.$enroll['program_id'])->fetchArray();
			$course = $dba->query('SELECT name FROM course WHERE id = '.$enroll['course_id'])->fetchArray();
			$periodc = $dba->query('SELECT name, final FROM period WHERE id = '.$enroll['period_id'])->fetchArray();
			$plan_id = $dba->query('SELECT id FROM plan WHERE program_id = '.$enroll['program_id'])->fetchArray();

			$teachers = $dba->query('SELECT names FROM user u WHERE (SELECT user_id FROM teacher WHERE user_id = u.id AND course_id = '.$enroll['course_id'].' AND period_id = '.$enroll['period_id'].') = id')->fetchAll(false);
			if(!empty($teachers)){
				if(count($teachers) == 2){
					$TEMP['!teacher'] = "{$teachers[0]} {$TEMP['#word']['and']} {$teachers[1]}";
				} else if(count($teachers) > 2){
					$end = end($teachers);
					array_pop($teachers);
					$TEMP['!teacher'] = implode(', ', $teachers)." {$TEMP['#word']['and']} $end";
				} else {
					$TEMP['!teacher'] = $teachers[0];
				}
			} else {
				$TEMP['!teacher'] = $TEMP['#word']['pending'];
			}
		
			$TEMP['!name'] = "$course ({$periodc['name']})";
			$TEMP['!color'] = 'purple';
			$TEMP['!plan_id'] = $plan_id;
		} else {
			$program = $dba->query('SELECT * FROM program WHERE id = '.$enroll['program_id'])->fetchArray();
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
			if(Specific::Student() == true){
				$TEMP['!class_event'] = 'cursor-disabled" disabled';
			}
		} else {
			$TEMP['!class_event'] = 'cursor-disabled" disabled';
			if(time() < $periodc['final'] || Specific::Academic() == true || Specific::Admin() == true || $TEMP['#type'] == 'program'){
				$TEMP['!class_event'] = 'show_cmodal"';
			}
		}
		if($TEMP['#type'] == 'course'){	
			if($estatus == 'cancelled'){
				$TEMP['!class_event'] = 'cursor-disabled" disabled';
			}
		}
		$TEMP['!status'] = $enroll['status'];
	    $TEMP['!type'] = "{$TEMP['#word'][$enroll['type']]} ({$TEMP['#word'][$enroll['status']]})";
	    $TEMP['!typet'] = $enroll['type'];
	    $TEMP['!time'] = Specific::DateFormat($enroll['time']);
	    $TEMP['enrolled'] .= Specific::Maket("enroll/includes/enroll-list");
	}
	Specific::DestroyMaket();
} else {
	$TEMP['enrolled'] .= Specific::Maket("not-found/enroll");
}

$TEMP['#period_all'] = $dba->query('SELECT * FROM period')->fetchAll();

$TEMP['#page']        = 'enroll';
$TEMP['#title']       = $TEMP['#word']['enroll'] . ' - ' . $TEMP['#settings']['title'];
$TEMP['#description'] = $TEMP['#settings']['description'];
$TEMP['#keyword']     = $TEMP['#settings']['keyword'];

$TEMP['#content']     = Specific::Maket('enroll/content');
?>