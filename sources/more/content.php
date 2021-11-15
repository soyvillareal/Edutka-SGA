<?php
if ($TEMP['#loggedin'] === false || Specific::Academic() == false) {
    header("Location: " . Specific::Url('404'));
    exit();
}




$TEMP['#page']        = 'users';
$TEMP['#title']       = $TEMP['#word']['more'] . ' - ' . $TEMP['#settings']['title'];
$TEMP['#description'] = $TEMP['#settings']['description'];
$TEMP['#keyword']     = $TEMP['#settings']['keyword'];
$TEMP['#load_url']    = Specific::Url('more');
$TEMP['second_page']  = Specific::Maket('more/users/content');
$TEMP['#content']     = Specific::Maket("more/content");
?>