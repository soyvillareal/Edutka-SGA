<?php 
if ($TEMP['#loggedin'] === false) {
	header("Location: ".Specific::Url());
	exit();
}

if (Specific::Admin() == false && Specific::Teacher() == false) {
	header("Location: " . Specific::Url('404'));
    exit();
}

$TEMP['#user_id'] = $TEMP['#user']['id'];
if(isset($_GET['user']) && Specific::Admin() == true){
	$TEMP['#user_id'] = Specific::Filter($_GET['user']);
}

$TEMP['#programs'] = $dba->query('SELECT * FROM programs')->fetchAll();
$query = '';
if(Specific::Teacher() == true){
	$teachers = $dba->query('SELECT course_id FROM teacher WHERE user_id = '.$TEMP['#user_id'])->fetchAll(false);
	$query = ' WHERE id IN ('.implode(',', $teachers).')';
}
$courses = $dba->query('SELECT * FROM courses'.$query.' LIMIT ? OFFSET ?', 10, 1)->fetchAll();
$TEMP['#total_pages'] = $dba->totalPages;

if(!empty($courses)){
	foreach ($courses as $course) {
		$parameters = json_decode($course['parameters']);
		$preknowledge = explode(',', $course['preknowledge']);
		$teachers = $dba->query('SELECT names FROM users u WHERE (SELECT user_id FROM teacher WHERE user_id = u.id AND course_id = '.$course['id'].') = id')->fetchAll(false);
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
		$TEMP['!id'] = $course['id'];
        $TEMP['!code'] = $course['code'];
		$TEMP['!name'] = $course['name'];
		$TEMP['!preknowledge'] = !empty($course['preknowledge']) ? count($preknowledge) : 0;
		$TEMP['!parameters'] = count($parameters);
		$TEMP['!program'] = $course['plan_id'] == 0 ? $TEMP['#word']['pending'] : $dba->query('SELECT name FROM programs p WHERE (SELECT program_id FROM plan WHERE program_id ='.$course['plan_id'].' AND program_id = p.id) = id')->fetchArray();
		$TEMP['!teacher'] = $teachers;
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

$TEMP['#page']        = 'courses';
$TEMP['#title']       = $TEMP['#word']['courses'] . ' - ' . $TEMP['#settings']['title'];
$TEMP['#description'] = $TEMP['#settings']['description'];
$TEMP['#keyword']     = $TEMP['#settings']['keyword'];
$TEMP['#load_url'] 	  = Specific::Url('courses');
$TEMP['#content']     = Specific::Maket("courses/content");
?>