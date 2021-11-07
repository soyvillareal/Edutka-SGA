<?php
if ($TEMP['#loggedin'] === false) {
	header("Location: ".Specific::Url());
	exit();
}

$TEMP['#user_id'] = $TEMP['#user']['id'];
if(isset($_GET['user']) && Specific::Academic() == true){
	$TEMP['#user_id'] = Specific::Filter($_GET['user']);
}

$user_data = Specific::Data($TEMP['#user_id']);

$TEMP['#program_id'] = !empty($_GET['program']) ? Specific::Filter($_GET['program']) : $user_data['program'];
$TEMP['#period_id'] = !empty($_GET['period']) ? Specific::Filter($_GET['period']) : $user_data['period'];
$TEMP['#keyword_notes'] = Specific::Filter($_GET['keyword']);

$params = "";
$sqls = '';
$TEMP['average'] = '- -';

if(Specific::Academic() == false){
	$sqls .= ' AND program_id = '.$TEMP['#program_id'];
	$sqls .= ' AND period_id = '.$TEMP['#period_id'];
} else {
	if(isset($_GET['keyword'])){
		$params .= "?keyword=".$TEMP['#keyword_notes'];
	}
	if(isset($_GET['user'])){
		$params .= "&user={$TEMP['#user_id']}";
	}
}

if(isset($_GET['program'])){
	$sqls .= ' AND program_id = '.$TEMP['#program_id'];
	$params .= (!empty($params) ? "&" : "?")."program={$TEMP['#program_id']}";
}
if(isset($_GET['period'])){
	$sqls .= ' AND period_id = '.$TEMP['#period_id'];
	$params .= "&period={$TEMP['#period_id']}";
}

$TEMP['#load_url'] = Specific::Url("notes$params");

$TEMP['#notes'] = $dba->query('SELECT * FROM notes WHERE user_id = '.$TEMP['#user_id'].$sqls)->fetchAll();

if(!empty($TEMP['#notes'])){
	foreach ($TEMP['#notes'] as $note) {
		$notes = json_decode($note['notes'], true);

		$period = $dba->query('SELECT * FROM periods WHERE id = '.$note['period_id'])->fetchArray();
		$subject = $dba->query('SELECT * FROM subjects WHERE id = '.$note['subject_id'])->fetchArray();

	    $TEMP['!period'] = $period['name'];
	    $TEMP['!subject'] = $subject['name'];

		$TEMP['!first'] = $notes[0];
	    $TEMP['!second'] = $notes[1];
	    $TEMP['!third'] = $notes[2];
	    $TEMP['!average'] = $average[] = round((($notes[0]*0.3)+($notes[1]*0.3)+($notes[2]*0.4)), 2);
	    $TEMP['!teacher'] = Specific::Data($subject['user_id'])['full_name'];

	    $TEMP['!approved'] = ($subject['type'] == 'practice' && $TEMP['!average'] > 3.5) || ($subject['type'] == 'theoretical' && $TEMP['!average'] > 3.0) ? true : false;
	    $TEMP['!status'] = ($period['status'] == 0) ? $TEMP['#word']['finalized'] : $TEMP['#word']['in_progress'];

	    $TEMP['notes'] .= Specific::Maket("notes/includes/notes");
	}
	Specific::DestroyMaket();

	$TEMP['average'] = round(array_sum($average)/count($average), 2);

	$TEMP['#programs'] = $dba->query('SELECT * FROM programs p WHERE (SELECT program_id FROM enrolled WHERE user_id = '.$TEMP['#user_id'].' AND program_id = p.id) = id')->fetchAll();
	$TEMP['#periods'] = $dba->query('SELECT * FROM periods p WHERE (SELECT period_id FROM enrolled WHERE user_id = '.$TEMP['#user_id'].' AND period_id = p.id) = id')->fetchAll();

	} else {
		$TEMP['notes'] .= Specific::Maket("not-found/notes");
}


$TEMP['#page']        = 'notes';
$TEMP['#title']       = $TEMP['#word']['notes'] . ' - ' . $TEMP['#settings']['title'];
$TEMP['#description'] = $TEMP['#settings']['description'];
$TEMP['#keyword']     = $TEMP['#settings']['keyword'];

$TEMP['#content']     = Specific::Maket('notes/content');
?>