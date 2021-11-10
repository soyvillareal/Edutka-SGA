<?php
if ($TEMP['#loggedin'] === false) {
	header("Location: ".Specific::Url());
	exit();
}

$TEMP['#user_id'] = $TEMP['#user']['id'];
if(isset($_GET['user']) && (Specific::Academic() == true || Specific::Teacher() == true)){
	$TEMP['#user_id'] = Specific::Filter($_GET['user']);
}

$user_data = Specific::Data($TEMP['#user_id']);

$TEMP['#program_id'] = !empty($_GET['program']) ? Specific::Filter($_GET['program']) : $user_data['program'];
$TEMP['#period_id'] = !empty($_GET['period']) ? Specific::Filter($_GET['period']) : $user_data['last_cenrolled'];
$TEMP['#keyword_notes'] = Specific::Filter($_GET['keyword']);

$params = "";
$sqls = '';
$TEMP['average'] = '- -';


if(Specific::Teacher() == true){
    $my_courses = $dba->query('SELECT course_id FROM teacher WHERE user_id = '.$TEMP['#user']['id'])->fetchAll(false);
}

$TEMP['#programs'] = $dba->query('SELECT * FROM programs p WHERE (SELECT program_id FROM enrolled WHERE user_id = '.$TEMP['#user_id'].' AND program_id = p.id) = id')->fetchAll();
$periods = $dba->query('SELECT period_id FROM courses c WHERE (SELECT course_id FROM enrolled WHERE user_id = '.$TEMP['#user_id'].' AND course_id = c.id) = id')->fetchAll(false);
if(!empty($periods)){
	$TEMP['#periods'] = $dba->query('SELECT * FROM periods WHERE id IN ('.implode(',', $periods).')')->fetchAll();
}

if(Specific::Academic() == true || Specific::Teacher() == true){
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
    	$TEMP['#period_id'] = $dba->query('SELECT max(period_id) FROM courses WHERE (SELECT course_id FROM notes WHERE user_id = '.$TEMP['#user_id'].' AND course_id = '.end($my_courses).') = id')->fetchArray();
    }
}

if(!empty($TEMP['#program_id'])){
	$sqls .= ' AND (SELECT id FROM courses WHERE program_id = '.$TEMP['#program_id'].' AND id = n.course_id) = course_id';
	$params .= (!empty($params) ? "&" : "?")."program={$TEMP['#program_id']}";
}
if(!empty($TEMP['#period_id'])){
	$sqls .= ' AND (SELECT id FROM courses WHERE period_id = '.$TEMP['#period_id'].' AND id = n.course_id) = course_id';

	$params .= "&period={$TEMP['#period_id']}";
}

$TEMP['#url_params'] = str_replace('?', '&', "&one=notes$params");
$TEMP['#load_url'] = Specific::Url("notes$params");

$TEMP['#notes'] = $dba->query('SELECT * FROM notes'.(!empty($TEMP['#period_id']) ? ' n ' : ' ').'WHERE user_id = '.$TEMP['#user_id'].$sqls)->fetchAll();

if(!empty($TEMP['#notes'])){
	foreach ($TEMP['#notes'] as $note) {
		$notes = json_decode($note['notes'], true);


		
		$course = $dba->query('SELECT * FROM courses WHERE id = '.$note['course_id'])->fetchArray();
		$period = $dba->query('SELECT * FROM periods WHERE id = '.$course['period_id'])->fetchArray();


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


	    for ($i=0; $i < 3; $i++) { 
	    	$anotes = array();
		    $rnotes = json_decode($notes[$i], true);
	        $parameters = json_decode($course['parameters'], true)[$i];
	        foreach ($parameters as $key => $param) {
	        	$anotes[] = (($rnotes[$key]/100)*$param['percent']);
	        }
	        $notes[$i] = array_sum($anotes);
	    }

		$TEMP['!first'] = $notes[0];
	    $TEMP['!second'] = $notes[1];
	    $TEMP['!third'] = $notes[2];
	    $TEMP['!average'] = $average[] = round((($notes[0]*0.3)+($notes[1]*0.3)+($notes[2]*0.4)), 2);
	    $TEMP['!teacher'] = $teachers;

	    $TEMP['!approved'] = ($course['type'] == 'practice' && $TEMP['!average'] >= 3.5) || ($course['type'] == 'theoretical' && $TEMP['!average'] >= 3.0) ? true : false;
	    $TEMP['!status'] = ($period['status'] == 0) ? $TEMP['#word']['finalized'] : $TEMP['#word']['in_progress'];

	    $TEMP['notes'] .= Specific::Maket("notes/includes/notes");
	}
	Specific::DestroyMaket();

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