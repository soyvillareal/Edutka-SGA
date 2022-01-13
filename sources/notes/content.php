<?php
if ($TEMP['#loggedin'] === false) {
	header("Location: ".Specific::Url());
	exit();
}

$TEMP['#user_id'] = $TEMP['#user']['id'];
if(isset($_GET['user']) && (Specific::Admin() == true || Specific::Academic() == true || Specific::Teacher() == true)){
	$TEMP['#user_id'] = Specific::Filter($_GET['user']);
}

$user_data = Specific::Data($TEMP['#user_id']);
$TEMP['#program_id'] = !empty($_GET['program']) ? Specific::Filter($_GET['program']) : $user_data['program'];
$TEMP['#period_id'] = !empty($_GET['period']) ? Specific::Filter($_GET['period']) : $user_data['last_cenrolled'];
$TEMP['#keyword_notes'] = Specific::Filter($_GET['keyword']);
$TEMP['#role'] = Specific::Student() == true ? 'student' : 'teacher';

$params = "";
$sqls = '';
$TEMP['average'] = '- -';


if(Specific::Teacher() == true){
    $my_courses = $dba->query('SELECT course_id FROM teacher WHERE user_id = '.$TEMP['#user']['id'])->fetchAll(false);
}

$TEMP['#programs'] = $dba->query('SELECT * FROM programs p WHERE (SELECT program_id FROM enrolled WHERE user_id = '.$TEMP['#user_id'].' AND program_id = p.id AND type = "program") = id')->fetchAll();

$periods = $dba->query('SELECT period_id FROM enrolled WHERE type = "course" AND user_id = '.$TEMP['#user_id'])->fetchAll(false);

if(!empty($periods)){
	$TEMP['#periods'] = $dba->query('SELECT * FROM periods WHERE id IN ('.implode(',', $periods).')')->fetchAll();
}

if(Specific::Admin() == true || Specific::Academic() == true || Specific::Teacher() == true){
	if(isset($_GET['keyword'])){
		$params .= "?keyword=".$TEMP['#keyword_notes'];
	}
	if(isset($_GET['user'])){
		$params .= "&user={$TEMP['#user_id']}";
	}
}

if(Specific::Teacher() == true){
    $sqls .= ' AND course_id IN ('.implode(',', $my_courses).')';
    if(empty($_GET['period'])){
    	$TEMP['#period_id'] = $dba->query('SELECT max(period_id) FROM enrolled WHERE (SELECT course_id FROM notes WHERE user_id = '.$TEMP['#user_id'].' AND course_id = '.end($my_courses).') = course_id')->fetchArray();
    }
    $TEMP['#periods'] =  $dba->query('SELECT * FROM periods p WHERE (SELECT period_id FROM enrolled WHERE user_id = '.$TEMP['#user_id'].' AND course_id IN ('.implode(',', $my_courses).') AND period_id = p.id) = id')->fetchAll();
}

if(!empty($TEMP['#program_id'])){
	$plan = $dba->query('SELECT * FROM plan WHERE program_id = '.$TEMP['#program_id'])->fetchArray();
	$TEMP['#note_mode'] = $plan['note_mode'];
	$courses = $dba->query('SELECT course_id FROM curriculum WHERE plan_id = '.$plan['id'])->fetchAll(false);
	$sqls .= ' AND course_id IN ('.implode(',', $courses).') AND program_id = '.$TEMP['#program_id'];
	$params .= (!empty($params) ? "&" : "?")."program={$TEMP['#program_id']}";
}
if(!empty($TEMP['#period_id'])){
	$sqls .= ' AND (SELECT course_id FROM enrolled WHERE user_id = '.$TEMP['#user_id'].' AND period_id = '.$TEMP['#period_id'].' AND type = "course" AND course_id = n.course_id) = course_id';
	$params .= "&period={$TEMP['#period_id']}";
}

$TEMP['#url_params'] = str_replace('?', '&', "&one=notes$params");
$TEMP['#load_url'] = Specific::Url("notes$params");

$TEMP['#notes'] = $dba->query('SELECT * FROM notes'.(!empty($TEMP['#period_id']) ? ' n ' : ' ').'WHERE user_id = '.$TEMP['#user_id'].$sqls)->fetchAll();
if(!empty($TEMP['#notes'])){
	foreach ($TEMP['#notes'] as $note) {
		$notes = json_decode($note['notes'], true);
		$course = $dba->query('SELECT * FROM courses WHERE id = '.$note['course_id'])->fetchArray();
		$period = $dba->query('SELECT * FROM periods p WHERE (SELECT period_id FROM enrolled WHERE user_id = '.$TEMP['#user_id'].' AND course_id = '.$note['course_id'].' AND type = "course" AND period_id = p.id) = id')->fetchArray();
		$TEMP['!period_final'] = time() > $period['final'];
		$authorization = $dba->query('SELECT court FROM authorization WHERE status = "authorized" AND period_id = '.$period['id'].' AND course_id = '.$note['course_id'])->fetchAll(false);
		$TEMP['!authorization'] = Specific::Admin() == true || Specific::Academic() == true ? array('first', 'second', 'third') : $authorization;

		$teachers = $dba->query('SELECT names FROM users u WHERE (SELECT user_id FROM teacher WHERE user_id = u.id AND course_id = '.$note['course_id'].') = id')->fetchAll(false);
		if(count($teachers) == 2){
			$teachers = "{$teachers[0]} {$TEMP['#word']['and']} {$teachers[1]}";
		} else if(count($teachers) > 2){
			$end = end($teachers);
			array_pop($teachers);
			$teachers = implode(', ', $teachers)." {$TEMP['#word']['and']} $end";
		} else {
			$teachers = $teachers[0];
		}

		$TEMP['!id'] = $note['id'];
	    $TEMP['!period'] = $period['name'];
	    $TEMP['!course'] = $course['name'];
	    $TEMP['!parameters'] = $course['parameters'];
	    if($TEMP['#note_mode'] == '30-30-40'){
	    	for ($i=0; $i < 3; $i++) { 
		    	$anotes = array();
		        $parameters = json_decode($course['parameters'], true)[$i];
		        foreach ($parameters as $key => $param) {
		        	$anotes[] = (($notes[$i][$key]/100)*$param['percent']);
		        }
		        $notes[$i] = array_sum($anotes);
		    }
			$TEMP['!first'] = $notes[0];
		    $TEMP['!second'] = $notes[1];
		    $TEMP['!third'] = $notes[2];
	    	$TEMP['!average'] = $average[] = round((($notes[0]*0.3)+($notes[1]*0.3)+($notes[2]*0.4)), 2);
		    if($TEMP['!average'] > 0 && $notes[2] < $TEMP['#nmtc']){
		    	$TEMP['!evalfinal'] = true;
		    	$TEMP['!article'] = 43;
		    	$TEMP['!average'] = $average[] = round($notes[2], 2);
		    }
	    } else {
	    	$anotes = array();
		    $parameters = json_decode($course['parameters'], true);
		    foreach ($parameters as $key => $param) {
		      	$anotes[] = (($notes[$key]/100)*$param['percent']);
		    }
		    $notes = array_sum($anotes);
			$TEMP['!first'] = $notes;
	    	$TEMP['!average'] = $average[] = round($notes, 2);
	    }

	    $TEMP['!teacher'] = $teachers;
	    $TEMP['!approved'] = ($course['type'] == 'practice' && $TEMP['!average'] >= $TEMP['#nmcnt']) || ($course['type'] == 'theoretical' && $TEMP['!average'] >= $TEMP['#nmct']) ? true : false;
	    if($TEMP['!approved'] == false){
	    	$avekey[] = $TEMP['!average'];
	    } else if($notes[2] > $TEMP['#nmtc']){
	    	$TEMP['!article'] = 60;
	    }
	    $TEMP['!status'] = $TEMP['!period_final'] == true ? $TEMP['#word']['finalized'] : $TEMP['#word']['in_progress'];
	    $TEMP['notes'] .= Specific::Maket("notes/includes/notes");
	}
	Specific::DestroyMaket();
	$TEMP['#semesbad'] = count($avekey) >= $TEMP['#cers'];
	$TEMP['average'] = round(array_sum($average)/count($average), 2);
} else {
	$TEMP['notes'] .= Specific::Maket("not-found/notes");
}


$TEMP['#page']        = 'notes';
$TEMP['#title']       = $TEMP['#word']['notes'] . ' - ' . $TEMP['#settings']['title'];
$TEMP['#description'] = $TEMP['#settings']['description'];
$TEMP['#keyword']     = $TEMP['#settings']['keyword'];

$TEMP['#content']     = Specific::Maket('notes/content');
?>