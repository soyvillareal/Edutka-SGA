<?php
if ($TEMP['#loggedin'] === false || Specific::Academic() == false) {
    header("Location: " . Specific::Url('404'));
    exit();
}

$TEMP['#programs'] = $dba->query('SELECT * FROM programs')->fetchAll();
$plans = $dba->query('SELECT * FROM plan LIMIT ? OFFSET ?', 10, 1)->fetchAll();
$TEMP['#total_pages'] = $dba->totalPages;

if(!empty($plans)){
    foreach ($plans as $plan) {
        $TEMP['!id'] = $plan['id'];
        $TEMP['!name'] = $plan['name'];
        $TEMP['!program'] = $dba->query('SELECT name FROM programs WHERE id = '.$plan['program_id'])->fetchArray();
        $TEMP['!resolution'] = $plan['resolution'];
        $TEMP['!date_approved'] = Specific::DateFormat($plan['date_approved']);
        $TEMP['!duration'] = $plan['duration'];
        $TEMP['!credits'] = $plan['credits'];
        $TEMP['!credits'] = $plan['credits'];
        $TEMP['!courses'] = $dba->query('SELECT COUNT(*) FROM assigned WHERE plan_id = '.$plan['id'])->fetchArray();
        $TEMP['!note_mode'] = $plan['note_mode'];
        $TEMP['!status'] = $TEMP['#word'][$plan['status']];
        $TEMP['!time'] = Specific::DateFormat($plan['time']);
        $TEMP['plans'] .= Specific::Maket('more/plans/includes/plans-list');
    }
    Specific::DestroyMaket();
} else {
    $TEMP['plans'] .= Specific::Maket('not-found/plans');
}

$TEMP['#page']        = 'plans';
$TEMP['#title']       = $TEMP['#word']['study_plans'] . ' - ' . $TEMP['#settings']['title'];
$TEMP['#description'] = $TEMP['#settings']['description'];
$TEMP['#keyword']     = $TEMP['#settings']['keyword'];
$TEMP['#load_url']    = Specific::Url('more?page=plans');
$TEMP['second_page']  = Specific::Maket('more/plans/content');
$TEMP['#content']     = Specific::Maket("more/content");
?>