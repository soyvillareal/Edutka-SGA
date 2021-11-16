<?php
if ($TEMP['#loggedin'] === false) {
    header("Location: " . Specific::Url('login'));
    exit();
}

$TEMP['#load_url'] = Specific::Url('settings?page=change-password');

if (!empty($user_id)) {
    $TEMP['href_setting'] = "?id=$user_id";
    $TEMP['href_settings'] = "&id=$user_id";
    $TEMP['#load_url'] = Specific::Url('settings?page=change-password'.$TEMP['href_settings']);
}

$TEMP['#page']        = 'change-password';
$TEMP['#title']       = $TEMP['#word']['settings'] . ' - ' . $TEMP['#settings']['title'];
$TEMP['#description'] = $TEMP['#settings']['description'];
$TEMP['#keyword']     = $TEMP['#settings']['keyword'];

$TEMP['second_page'] = Specific::Maket('settings/change-password/content');
$TEMP['#content']     = Specific::Maket("settings/content");
?>