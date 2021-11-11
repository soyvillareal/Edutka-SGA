<?php 
if ($TEMP['#loggedin'] === false || Specific::Academic() == false) {
	header("Location: " . Specific::Url('404'));
    exit();
}

$TEMP['#periods'] = $dba->query('SELECT * FROM periods')->fetchAll();
$forms = $dba->query('SELECT * FROM forms LIMIT ? OFFSET ?', 10, 1)->fetchAll();
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
		$TEMP['!time'] = Specific::DateFormat($form['time']);
		$TEMP['forms'] .= Specific::Maket('forms/includes/forms-list');
	}
	Specific::DestroyMaket();
} else {
	$TEMP['forms'] .= Specific::Maket('not-found/forms');
}

$TEMP['#page']        = 'forms';
$TEMP['#title']       = $TEMP['#word']['forms'] . ' - ' . $TEMP['#settings']['title'];
$TEMP['#description'] = $TEMP['#settings']['description'];
$TEMP['#keyword']     = $TEMP['#settings']['keyword'];
$TEMP['#load_url'] 	  = Specific::Url('forms');
$TEMP['#content']     = Specific::Maket("forms/content");
?>