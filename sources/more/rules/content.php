<?php
if ($TEMP['#loggedin'] === false) {
    header("Location: " . Specific::Url('login'));
    exit();
}
$by_id = $TEMP['#user']['id'];
$user_id = Specific::Filter($_GET['id']);
if (isset($_GET['id']) && !empty($user_id) && Specific::Admin() === true) {
    if ($dba->query('SELECT COUNT(*) FROM users WHERE user_id = "'.$user_id.'"')->fetchArray() == 0) {
        header("Location: " . Specific::Url('404'));
        exit();
    }
    $by_id = $dba->query('SELECT id FROM users WHERE user_id = "'.$user_id.'"')->fetchArray();
}

$TEMP['#data']     = Specific::Data($by_id);
$TEMP['#load_url'] = Specific::Url('more?page=rules');

if (!empty($user_id)) {
    $TEMP['href_more'] = "?id=$user_id";
    $TEMP['href_more'] = "&id=$user_id";
    $TEMP['#load_url'] = Specific::Url('more?page=rules'.$TEMP['href_more']);
}

$TEMP['data'] = $TEMP['#data'];

$TEMP['#page']        = 'rules';
$TEMP['#title']       = $TEMP['#word']['rules'] . ' - ' . $TEMP['#settings']['title'];
$TEMP['#description'] = $TEMP['#settings']['description'];
$TEMP['#keyword']     = $TEMP['#settings']['keyword'];

$TEMP['second_page'] = Specific::Maket('more/rules/content');
$TEMP['#content']     = Specific::Maket("more/content");
?>