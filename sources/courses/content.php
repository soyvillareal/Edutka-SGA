<?php 
if ($TEMP['#loggedin'] === false) {
	header("Location: ".Specific::Url());
	exit();
}

if (Specific::Admin() == false && Specific::Academic() == false && Specific::Teacher() == false) {
	header("Location: " . Specific::Url('404'));
    exit();
}

$TEMP['#user_id'] = $TEMP['#user']['id'];
if(isset($_GET['user']) && Specific::Admin() == true && Specific::Academic() == true){
	$TEMP['#user_id'] = Specific::Filter($_GET['user']);
}

$params = "";
$query = '';
$TEMP['#programs'] = $dba->query('SELECT * FROM programs')->fetchAll();
$courses = array();

if(Specific::Teacher() == true || Specific::Admin() == true){
	$period_id = Specific::Filter($_GET['period_id']);
	$TEMP['#period_id'] = !empty($period_id) ? $period_id : $dba->query('SELECT period_id FROM teacher WHERE user_id = '.$TEMP['#user_id'].' LIMIT 1')->fetchArray();

	if(!empty($TEMP['#period_id'])){
		$params = "?period_id={$TEMP['#period_id']}";
	}

	if(Specific::Teacher() == true){
		$periods = $dba->query('SELECT period_id FROM teacher WHERE user_id = '.$TEMP['#user_id'])->fetchAll(false);
		$periods = array_unique($periods);
		if(!empty($periods)){
			$TEMP['#periods'] = $dba->query('SELECT * FROM periods WHERE id IN ('.implode(',', $periods).')')->fetchAll();
		}
	}

	if(Specific::Teacher() == true){
		$query = ' WHERE user_id = '.$TEMP['#user_id'];
		if(is_numeric($TEMP['#period_id'])){
			$query .= ' AND period_id = '.$TEMP['#period_id'];
		}
		$teachers = $dba->query('SELECT course_id FROM teacher'.$query)->fetchAll(false);
		if(!empty($teachers)){
			$courses = $dba->query('SELECT * FROM courses WHERE id IN ('.implode(',', $teachers).') LIMIT ? OFFSET ?', 10, 1)->fetchAll();
		}
	} else {
		$courses = $dba->query('SELECT * FROM courses LIMIT ? OFFSET ?', 10, 1)->fetchAll();
	}
} else {
	$courses = $dba->query('SELECT * FROM courses LIMIT ? OFFSET ?', 10, 1)->fetchAll();
}
$TEMP['#total_pages'] = $dba->totalPages;

if(!empty($courses)){
	foreach ($courses as $course) {
		if(Specific::Teacher() == true || Specific::Admin() == true){
			if(is_numeric($TEMP['#period_id'])){
				$parameters = $dba->query('SELECT parameters FROM parameter WHERE course_id = '.$course['id'].' AND period_id = '.$TEMP['#period_id'])->fetchArray();
			}
			$parameters = json_decode($parameters);
		}
		$preknowledge = explode(',', $course['preknowledge']);
		$assignments = $dba->query('SELECT period_id FROM teacher WHERE course_id = '.$course['id'])->fetchAll(false);
		$assignments = array_unique($assignments);

		if(Specific::Teacher() == true){
			if(is_numeric($TEMP['#period_id'])){
				$teachers = $dba->query('SELECT names FROM users u WHERE (SELECT user_id FROM teacher WHERE user_id = u.id AND course_id = '.$course['id'].' AND period_id = '.$TEMP['#period_id'].') = id')->fetchAll(false);
				$enrolled = $dba->query('SELECT COUNT(*) FROM enrolled WHERE course_id = '.$course['id'])->fetchArray();
				if(count($teachers) == 2){
					$teachers = "{$teachers[0]} {$TEMP['#word']['and']} {$teachers[1]}";
				} else if(count($teachers) > 2){
					$end = end($teachers);
					array_pop($teachers);
					$teachers = implode(', ', $teachers)." {$TEMP['#word']['and']} $end";
				} else {
					$teachers = $teachers[0];
				}
				$TEMP['!teacher'] = $teachers;
			}
		}
		

		$TEMP['!id'] = $course['id'];
        $TEMP['!code'] = $course['code'];
		$TEMP['!name'] = $course['name'];
		$TEMP['!assignments'] = count($assignments);
		$TEMP['!preknowledge'] = !empty($course['preknowledge']) ? count($preknowledge) : 0;
		$TEMP['!parameters'] = count($parameters);
		$TEMP['!program'] = $course['plan_id'] == 0 ? $TEMP['#word']['pending'] : $dba->query('SELECT name FROM programs p WHERE (SELECT program_id FROM plan WHERE program_id ='.$course['plan_id'].' AND program_id = p.id) = id')->fetchArray();
		$TEMP['!semester'] = $course['semester'];
		$TEMP['!credits'] = $course['credits'];
		$TEMP['!quota'] = ($course['quota']-$enrolled). "/{$course['quota']}";
		$TEMP['!type'] = $TEMP['#word'][$course['type']];
		$TEMP['!schedule'] = $TEMP['#word'][$course['schedule']];
		$TEMP['!time'] = Specific::DateFormat($course['time']);
		$TEMP['courses'] .= Specific::Maket('courses/includes/courses-list');
	}
	Specific::DestroyMaket();
} else {
	$TEMP['courses'] .= Specific::Maket('not-found/courses');
}

$TEMP['#period_now'] = $dba->query('SELECT * FROM periods WHERE status = "enabled" AND start < '.time().' AND final > '.time())->fetchArray();
$TEMP['#period_all'] = $dba->query('SELECT * FROM periods')->fetchAll();

$TEMP['#page']        = 'courses';
$TEMP['#title']       = $TEMP['#word']['courses'] . ' - ' . $TEMP['#settings']['title'];
$TEMP['#description'] = $TEMP['#settings']['description'];
$TEMP['#keyword']     = $TEMP['#settings']['keyword'];
$TEMP['#load_url'] 	  = Specific::Url("courses$params");
$TEMP['#content']     = Specific::Maket("courses/content");
?>