<?php 
if ($TEMP['#loggedin'] === false) {
    header("Location: ".Specific::Url());
    exit();
}

if (Specific::Admin() == false) {
    header("Location: " . Specific::Url('404'));
    exit();
}

$authorizations = $dba->query('SELECT * FROM authorization LIMIT ? OFFSET ?', 10, 1)->fetchAll();
$TEMP['#total_pages'] = $dba->totalPages;

if(!empty($authorizations)){
	foreach ($authorizations as $auth) {
		$period = $dba->query('SELECT name FROM periods WHERE id = '.$auth['period_id'])->fetchArray();
		$TEMP['!id'] = $auth['id'];
		$TEMP['!academic'] = $auth['status'] == 'pending' ? $TEMP['#word']['pending'] : $dba->query('SELECT names FROM users WHERE id = '.$auth['user_id'])->fetchArray();
		$TEMP['!teacher'] = $dba->query('SELECT names FROM users u WHERE (SELECT user_id FROM teacher WHERE id = '.$auth['teacher_id'].' AND user_id = u.id) = id')->fetchArray();
		$TEMP['!course'] = $dba->query('SELECT name FROM courses WHERE id = '.$auth['course_id'])->fetchArray();
		$TEMP['!court'] = $TEMP['#word'][$auth['court']];
		$TEMP['!period'] = is_array($period) ? $TEMP['#word']['pending'] : $period;
		$TEMP['!status'] = $TEMP['#word'][$auth['status']];
		$TEMP['!time'] = Specific::DateFormat($auth['time']);
		$TEMP['authorizations'] .= Specific::Maket('more/authorizations/includes/authorizations-list');
	}
	Specific::DestroyMaket();
} else {
	$TEMP['authorizations'] .= Specific::Maket('not-found/authorizations');
}

$TEMP['#page']        = 'authorizations';
$TEMP['#title']       = $TEMP['#word']['authorizations'] . ' - ' . $TEMP['#settings']['title'];
$TEMP['#description'] = $TEMP['#settings']['description'];
$TEMP['#keyword']     = $TEMP['#settings']['keyword'];

$TEMP['#load_url']    = Specific::Url('more?page=authorizations');
$TEMP['second_page']  = Specific::Maket('more/authorizations/content');
$TEMP['#content']     = Specific::Maket("more/content");
?>