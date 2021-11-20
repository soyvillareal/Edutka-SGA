<?php 
if ($TEMP['#loggedin'] === false || Specific::Teacher() == false) {
	header("Location: " . Specific::Url('404'));
    exit();
}

$TEMP['#courses'] = $dba->query('SELECT * FROM courses c WHERE (SELECT course_id FROM teacher WHERE user_id = '.$TEMP['#user']['id'].' AND course_id = c.id) = id')->fetchAll();
$authorizations = $dba->query('SELECT * FROM authorization a WHERE (SELECT id FROM teacher WHERE user_id = '.$TEMP['#user']['id'].' AND id = a.teacher_id) = teacher_id LIMIT ? OFFSET ?', 10, 1)->fetchAll();
$TEMP['#total_pages'] = $dba->totalPages;

if(!empty($authorizations)){
	foreach ($authorizations as $auth) {
		$TEMP['!id'] = $auth['id'];
		$TEMP['!academic'] = $auth['status'] == 'pending' ? $TEMP['#word']['pending'] : $dba->query('SELECT names FROM users WHERE id = '.$auth['user_id'])->fetchArray();
		$TEMP['!teacher'] = $dba->query('SELECT names FROM users u WHERE (SELECT user_id FROM teacher WHERE id = '.$auth['teacher_id'].' AND user_id = u.id) = id')->fetchArray();
		$TEMP['!course'] = $dba->query('SELECT name FROM courses WHERE id = '.$auth['course_id'])->fetchArray();
		$TEMP['!court'] = $TEMP['#word'][$auth['court']];
		$TEMP['!expires'] = $auth['expires'] == 0 ? $TEMP['#word']['pending'] : Specific::DateFormat($auth['expires']);
		$TEMP['!status'] = $TEMP['#word'][$auth['status']];
		$TEMP['!time'] = Specific::DateFormat($auth['time']);
		$TEMP['authorizations'] .= Specific::Maket('authorizations/includes/authorizations-list');
	}
	Specific::DestroyMaket();
} else {
	$TEMP['authorizations'] .= Specific::Maket('not-found/authorizations');
}

$TEMP['#page']        = 'authorizations';
$TEMP['#title']       = $TEMP['#word']['authorizations'] . ' - ' . $TEMP['#settings']['title'];
$TEMP['#description'] = $TEMP['#settings']['description'];
$TEMP['#keyword']     = $TEMP['#settings']['keyword'];

$TEMP['#load_url']    = Specific::Url('authorizations');
$TEMP['#content']     = Specific::Maket("authorizations/content");
?>