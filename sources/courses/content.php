<?php 
if ($TEMP['#loggedin'] === false || Specific::Academic() == false) {
	header("Location: " . Specific::Url('404'));
    exit();
}

$TEMP['#periods'] = $dba->query('SELECT * FROM periods')->fetchAll();
$TEMP['#programs'] = $dba->query('SELECT * FROM programs')->fetchAll();
$TEMP['#subjects'] = $dba->query('SELECT * FROM subjects')->fetchAll();
$courses = $dba->query('SELECT * FROM courses LIMIT ? OFFSET ?', 10, 1)->fetchAll();
$TEMP['#total_pages'] = $dba->totalPages;

if(!empty($courses)){
	foreach ($courses as $course) {
		$TEMP['!code'] = $course['code'];
		$TEMP['!subject'] = $dba->query('SELECT name FROM subjects WHERE id = '.$course['subject_id'])->fetchArray();
		$TEMP['!program'] = $dba->query('SELECT name FROM programs WHERE id = '.$course['program_id'])->fetchArray();
		$TEMP['!period'] = $dba->query('SELECT name FROM periods WHERE id = '.$course['period_id'])->fetchArray();
		$TEMP['!semester'] = $course['semester'];
		$TEMP['!credits'] = $course['credits'];
		$TEMP['!quota'] = $course['quota'];
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