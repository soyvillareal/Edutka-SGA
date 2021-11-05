<?php 
if ($TEMP['#loggedin'] === false || Specific::Academic() == false) {
	header("Location: " . Specific::Url('404'));
    exit();
}

$TEMP['#periods'] = $dba->query('SELECT * FROM periods')->fetchAll();
$subjects = $dba->query('SELECT * FROM subjects LIMIT ? OFFSET ?', 10, 1)->fetchAll();
$TEMP['#total_pages'] = $dba->totalPages;

if(!empty($subjects)){
	foreach ($subjects as $subject) {
		$TEMP['!id'] = $subject['id'];
		$TEMP['!name'] = $subject['name'];
		$TEMP['!time'] = Specific::DateFormat($subject['time']);
		$TEMP['subjects'] .= Specific::Maket('subjects/includes/subjects-list');
	}
	Specific::DestroyMaket();
} else {
	$TEMP['subjects'] .= Specific::Maket('not-found/subjects');
}

$TEMP['#page']        = 'subjects';
$TEMP['#title']       = $TEMP['#word']['subjects'] . ' - ' . $TEMP['#settings']['title'];
$TEMP['#description'] = $TEMP['#settings']['description'];
$TEMP['#keyword']     = $TEMP['#settings']['keyword'];
$TEMP['#load_url'] 	  = Specific::Url('subjects');
$TEMP['#content']     = Specific::Maket("subjects/content");
?>