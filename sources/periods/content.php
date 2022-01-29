<?php 
if ($TEMP['#loggedin'] === false) {
	header("Location: ".Specific::ReturnUrl());
	exit();
}

if (Specific::Admin() == false) {
	header("Location: " . Specific::Url('404'));
    exit();
}

$periods = $dba->query('SELECT * FROM period ORDER BY start ASC LIMIT ? OFFSET ?', 10, 1)->fetchAll();
$TEMP['#total_pages'] = $dba->totalPages;

if(!empty($periods)){
	foreach ($periods as $key => $period) {
		$TEMP['!id'] = $period['id'];
		$TEMP['!name'] = $period['name'];
		$TEMP['!start'] = Specific::DateFormat($period['start']);
		$TEMP['!final'] = Specific::DateFormat($period['final']);
		$TEMP['!status'] = $TEMP['#word'][$period['status']];
		$TEMP['!time'] = Specific::DateFormat($period['time']);
		$TEMP['periods'] .= Specific::Maket('periods/includes/periods-list');
	}
	Specific::DestroyMaket();
} else {
	$TEMP['periods'] .= Specific::Maket('not-found/periods');
}

$TEMP['#page']        = 'periods';
$TEMP['#title']       = $TEMP['#word']['periods'] . ' - ' . $TEMP['#settings']['title'];
$TEMP['#description'] = $TEMP['#settings']['description'];
$TEMP['#keyword']     = $TEMP['#settings']['keyword'];
$TEMP['#load_url'] 	  = Specific::Url('periods');
$TEMP['#content']     = Specific::Maket("periods/content");
?>