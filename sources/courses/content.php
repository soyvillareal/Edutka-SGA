<?php 
if ($TEMP['#loggedin'] === false && Specific::Student() == false) {
	header("Location: " . Specific::Url('404'));
    exit();
}

$TEMP['#periods'] = $dba->query('SELECT * FROM periods')->fetchAll();
$TEMP['#programs'] = $dba->query('SELECT * FROM programs')->fetchAll();
$courses = $dba->query('SELECT * FROM courses LIMIT ? OFFSET ?', 10, 1)->fetchAll();
$TEMP['#total_pages'] = $dba->totalPages;

if(!empty($courses)){
	foreach ($courses as $course) {
		$teachers = $dba->query('SELECT names FROM users WHERE id IN ('.$course['teacher'].')')->fetchAll(false);
		$enrolled = $dba->query('SELECT COUNT(*) FROM enrolled WHERE course_id = '.$course['id'])->fetchArray();
		if(count($teachers) == 2){
			$teachers = $teachers[0]. ' y ' .$teachers[1];
		} else if(count($teachers) > 2){
			$and = end($teachers);
			array_pop($teachers);
			$teachers = implode(', ', $teachers). ' y '. $and;
		} else {
			$teachers = $teachers[0];
		}
		$TEMP['!id'] = $course['id'];
        $TEMP['!code'] = $course['code'];
		$TEMP['!name'] = $course['name'];
		$TEMP['!program'] = $dba->query('SELECT name FROM programs WHERE id = '.$course['program_id'])->fetchArray();
		$TEMP['!period'] = $dba->query('SELECT name FROM periods WHERE id = '.$course['period_id'])->fetchArray();
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