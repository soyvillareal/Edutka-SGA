<?php 
if ($TEMP['#loggedin'] === false || Specific::Academic() == false) {
	header("Location: " . Specific::Url('404'));
    exit();
}

$TEMP['#periods'] = $dba->query('SELECT * FROM periods')->fetchAll();
$faculties = $dba->query('SELECT * FROM faculty LIMIT ? OFFSET ?', 10, 1)->fetchAll();
$TEMP['#total_pages'] = $dba->totalPages;

if(!empty($faculties)){
	foreach ($faculties as $faculty) {
		$TEMP['!id'] = $faculty['id'];
		$TEMP['!name'] = $faculty['name'];
		$TEMP['!status'] = $TEMP['#word'][$faculty['status']];
		$TEMP['!time'] = Specific::DateFormat($faculty['time']);
		$TEMP['faculties'] .= Specific::Maket('faculties/includes/faculties-list');
	}
	Specific::DestroyMaket();
} else {
	$TEMP['faculties'] .= Specific::Maket('not-found/faculties');
}

$TEMP['#page']        = 'faculties';
$TEMP['#title']       = $TEMP['#word']['faculties'] . ' - ' . $TEMP['#settings']['title'];
$TEMP['#description'] = $TEMP['#settings']['description'];
$TEMP['#keyword']     = $TEMP['#settings']['keyword'];
$TEMP['#load_url'] 	  = Specific::Url('faculties');
$TEMP['#content']     = Specific::Maket("faculties/content");
?>