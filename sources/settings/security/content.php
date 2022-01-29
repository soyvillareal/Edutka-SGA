<?php
if ($TEMP['#loggedin'] === false) {
    header("Location: ".Specific::ReturnUrl());
    exit();
}

$TEMP['#load_url'] = Specific::Url('settings?page=security');

if (!empty($user_id)) {
    $TEMP['href_setting'] = "?id=$user_id";
    $TEMP['href_settings'] = "&id=$user_id";
    $TEMP['#load_url'] = Specific::Url('settings?page=security'.$TEMP['href_settings']);
}


$user_sessions = $dba->query('SELECT * FROM session WHERE user_id = '.$TEMP['#user']['id'].' ORDER BY id DESC LIMIT ? OFFSET ?', 10, 1)->fetchAll();
$TEMP['#total_pages'] = $dba->totalPages;

if (!empty($user_sessions)) {
    foreach ($user_sessions as $value) {
        $session = Specific::GetSessions($value);

        $TEMP['!id'] = $value['id'];
        $TEMP['!ip'] = $session['ip'];
        $TEMP['!browser'] = $session['browser'];
        $TEMP['!platform'] = $session['platform'];
        $TEMP['!time'] = Specific::DateFormat($value['time']);

        $TEMP['sessions'] .= Specific::Maket("settings/security/includes/sessions");
    }
    Specific::DestroyMaket();
} else {
    $TEMP['sessions'] = Specific::Maket("not-found/sessions");
}

$TEMP['#page']        = 'security';
$TEMP['#title']       = $TEMP['#word']['settings'] . ' - ' . $TEMP['#settings']['title'];
$TEMP['#description'] = $TEMP['#settings']['description'];
$TEMP['#keyword']     = $TEMP['#settings']['keyword'];

$TEMP['second_page'] = Specific::Maket('settings/security/content');
$TEMP['#content']     = Specific::Maket("settings/content");
?>