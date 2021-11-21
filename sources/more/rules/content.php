<?php
if ($TEMP['#loggedin'] === false) {
	header("Location: ".Specific::Url());
	exit();
}

if (Specific::Admin() == false) {
	header("Location: " . Specific::Url('404'));
    exit();
}

$rules = $dba->query('SELECT * FROM rule')->fetchAll();
$TEMP['#total_pages'] = $dba->totalPages;
$number = 1;
if(!empty($rules)){
	foreach ($rules as $rule) {
		$TEMP['!modified'] = $rule['modified'];
		$TEMP['!number'] = $number;

		$TEMP['!id'] = $rule['id'];
		$TEMP['!user'] = $dba->query('SELECT names FROM users WHERE id = '.$rule['user_id'])->fetchArray();
		$TEMP['!rulest'] = Specific::GetComposeRule($rule['rules']);
		$TEMP['!rulesh'] = Specific::GetComposeRule($rule['rules'], true);
		$TEMP['!link'] = $rule['link'];
		$TEMP['!status'] = $rule['status'];
		$TEMP['!modify'] = Specific::DateFormat($rule['modified']);
		$TEMP['!time'] = Specific::DateFormat($rule['time']);
		$TEMP['rules'] .= Specific::Maket('more/rules/includes/rules-list');
		$number++;
	}
	Specific::DestroyMaket();
} else {
	$TEMP['rules'] .= Specific::Maket('not-found/rules');
}


$TEMP['#page']        = 'rules';
$TEMP['#title']       = $TEMP['#word']['rules'] . ' - ' . $TEMP['#settings']['title'];
$TEMP['#description'] = $TEMP['#settings']['description'];
$TEMP['#keyword']     = $TEMP['#settings']['keyword'];
$TEMP['#load_url']    = Specific::Url('more?page=rules');
$TEMP['second_page'] = Specific::Maket('more/rules/content');
$TEMP['#content']     = Specific::Maket("more/content");
?>