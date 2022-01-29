<?php 
if ($TEMP['#loggedin'] === false) {
	header("Location: ".Specific::ReturnUrl());
	exit();
}

if (Specific::Admin() == false && Specific::Academic() == false) {
	header("Location: " . Specific::Url('404'));
    exit();
}

$forms = $dba->query('SELECT * FROM form LIMIT ? OFFSET ?', 10, 1)->fetchAll();
$TEMP['#total_pages'] = $dba->totalPages;

if(!empty($forms)){
	foreach ($forms as $form) {
		$user = Specific::Data($form['user_id']);
		$access = explode(',', $form['access']);
		$TEMP['!id'] = $form['id'];
		$TEMP['!user'] = $user['full_name'];
		$TEMP['!form_key'] = $form['form_key'];
		$TEMP['!access'] = count($access);
		$TEMP['!status'] = $TEMP['#word'][$form['status']];
		$TEMP['!expire'] = Specific::DateFormat($form['expire']);
		$TEMP['!time'] = Specific::DateFormat($form['time']);
		$TEMP['forms'] .= Specific::Maket('forms/includes/forms-list');
	}
	Specific::DestroyMaket();
} else {
	$TEMP['forms'] .= Specific::Maket('not-found/forms');
}

$TEMP['date_now'] = date("Y-m-d");

$TEMP['#page']        = 'forms';
$TEMP['#title']       = $TEMP['#word']['forms'] . ' - ' . $TEMP['#settings']['title'];
$TEMP['#description'] = $TEMP['#settings']['description'];
$TEMP['#keyword']     = $TEMP['#settings']['keyword'];
$TEMP['#load_url'] 	  = Specific::Url('forms');
$TEMP['#content']     = Specific::Maket("forms/content");
?>