<?php 
if ($TEMP['#loggedin'] === false) {
	header("Location: ".Specific::ReturnUrl());
	exit();
}

if (Specific::Admin() == false) {
	header("Location: " . Specific::Url('404'));
    exit();
}

$programs = $dba->query('SELECT * FROM program LIMIT ? OFFSET ?', 10, 1)->fetchAll();
$TEMP['#total_pages'] = $dba->totalPages;

if(!empty($programs)){
	foreach ($programs as $program) {
		$TEMP['!id'] = $program['id'];
		$TEMP['!name'] = $program['name'];
		$TEMP['!title'] = $program['title'];
		$TEMP['!snies'] = $program['snies'];
		$TEMP['!level'] = $program['level'] == 'pre' ? $TEMP['#word']['undergraduate'] : ($program['level'] == 'tec' ? $TEMP['#word']['technique'] : $TEMP['#word']['technologist']);
		$TEMP['!semesters'] = $program['semesters'];
		$TEMP['!mode'] = $TEMP['#word'][$program['mode']];
		$TEMP['!time'] = Specific::DateFormat($program['time']);
		$TEMP['programs'] .= Specific::Maket('programs/includes/programs-list');
	}
	Specific::DestroyMaket();
} else {
	$TEMP['programs'] .= Specific::Maket('not-found/programs');
}

$TEMP['#page']        = 'programs';
$TEMP['#title']       = $TEMP['#word']['programs'] . ' - ' . $TEMP['#settings']['title'];
$TEMP['#description'] = $TEMP['#settings']['description'];
$TEMP['#keyword']     = $TEMP['#settings']['keyword'];
$TEMP['#load_url'] 	  = Specific::Url('programs');
$TEMP['#content']     = Specific::Maket("programs/content");
?>