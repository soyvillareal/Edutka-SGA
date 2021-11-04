<?php 
if ($TEMP['#loggedin'] === false || Specific::Academic() == false) {
	header("Location: " . Specific::Url('404'));
    exit();
}

$programs = $dba->query('SELECT * FROM programs LIMIT ? OFFSET ?', 10, 1)->fetchAll();
$TEMP['#total_pages'] = $dba->totalPages;

if(!empty($programs)){
	foreach ($programs as $key => $program) {
		$TEMP['!id'] = $program['id'];
		$TEMP['!name'] = $program['name'];
		$TEMP['!title'] = $program['title'];
		$TEMP['!snies'] = $program['snies'];
		$TEMP['!level'] = $program['level'];
		$TEMP['!semesters'] = $program['semesters'];
		$TEMP['!mode'] = $program['mode'];
		$TEMP['!time'] = $program['time'];
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