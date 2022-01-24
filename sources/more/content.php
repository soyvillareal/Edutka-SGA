<?php
if ($TEMP['#loggedin'] === false) {
	header("Location: ".Specific::ReturnUrl());
	exit();
}

if (Specific::Admin() == false && Specific::Academic() == false) {
	header("Location: " . Specific::Url('404'));
    exit();
}

$users = $dba->query('SELECT * FROM users LIMIT ? OFFSET ?', 10, 1)->fetchAll();
$TEMP['#total_pages'] = $dba->totalPages;

if(!empty($users)){
	foreach ($users as $user) {
		$TEMP['!data'] = Specific::Data($user['id']);
	    $TEMP['!status'] = $TEMP['#word'][$user['status']];
		$TEMP['users'] .= Specific::Maket('more/users/includes/users-list');
	}
	Specific::DestroyMaket();
} else {
	$TEMP['users'] .= Specific::Maket('not-found/users');
}


$TEMP['#page']        = 'users';
$TEMP['#title']       = $TEMP['#word']['more'] . ' - ' . $TEMP['#settings']['title'];
$TEMP['#description'] = $TEMP['#settings']['description'];
$TEMP['#keyword']     = $TEMP['#settings']['keyword'];
$TEMP['#load_url']    = Specific::Url('more');
$TEMP['second_page']  = Specific::Maket('more/users/content');
$TEMP['#content']     = Specific::Maket("more/content");
?>