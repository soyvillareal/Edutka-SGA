<?php
if ($TEMP['#loggedin'] === false) {
    header("Location: ".Specific::ReturnUrl());
    exit();
}

$TEMP['#load_url'] = Specific::Url('settings');

if (!empty($user_id)) {
    $TEMP['href_setting'] = "?id=$user_id";
    $TEMP['href_settings'] = "&id=$user_id";
    $TEMP['#load_url'] = Specific::Url('settings'.$TEMP['href_setting']);
}

$TEMP['#birthday_enable'] = $TEMP['#user']['age_changed'] >= 1;

$TEMP['#page']        = 'general';
$TEMP['#title']       = $TEMP['#word']['settings'] . ' - ' . $TEMP['#settings']['title'];
$TEMP['#description'] = $TEMP['#settings']['description'];
$TEMP['#keyword']     = $TEMP['#settings']['keyword'];

$TEMP['second_page']  = Specific::Maket('settings/general/content');
$TEMP['#content']     = Specific::Maket("settings/content");
?>