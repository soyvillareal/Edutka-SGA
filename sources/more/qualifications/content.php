<?php 
if ($TEMP['#loggedin'] === false) {
    header("Location: ".Specific::Url());
    exit();
}

if (Specific::Admin() == false) {
    header("Location: " . Specific::Url('404'));
    exit();
}

$qualifications = $dba->query('SELECT * FROM qualification LIMIT ? OFFSET ?', 10, 1)->fetchAll();
$TEMP['#total_pages'] = $dba->totalPages;

if(!empty($qualifications)){
	foreach ($qualifications as $qua) {
		$course_id = $dba->query('SELECT course_id FROM notes n WHERE id = '.$qua['note_id'])->fetchArray();
		$TEMP['!id'] = $qua['id'];
		$TEMP['!applicant'] = $dba->query('SELECT names FROM users WHERE id = '.$qua['user_id'])->fetchArray();
		$TEMP['!course'] = $dba->query('SELECT name FROM courses c WHERE (SELECT course_id FROM notes n WHERE id = '.$qua['note_id'].' AND course_id = c.id) = id')->fetchArray();
		$TEMP['!note'] = empty($qua['note']) ? $TEMP['#word']['undefined'] : $qua['note'];
		$TEMP['!status'] = $TEMP['#word'][$qua['status']];
		$TEMP['!time'] = Specific::DateFormat($qua['time']);
		$TEMP['qualifications'] .= Specific::Maket('more/qualifications/includes/qualifications-list');
	}
	Specific::DestroyMaket();
} else {
	$TEMP['qualifications'] .= Specific::Maket('not-found/qualifications');
}

$TEMP['#page']        = 'qualifications';
$TEMP['#title']       = $TEMP['#word']['qualifications'] . ' - ' . $TEMP['#settings']['title'];
$TEMP['#description'] = $TEMP['#settings']['description'];
$TEMP['#keyword']     = $TEMP['#settings']['keyword'];

$TEMP['#load_url']    = Specific::Url('more?page=qualifications');
$TEMP['second_page']  = Specific::Maket('more/qualifications/content');
$TEMP['#content']     = Specific::Maket("more/content");
?>