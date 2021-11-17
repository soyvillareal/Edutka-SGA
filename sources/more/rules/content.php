<?php
if ($TEMP['#loggedin'] === false || Specific::Academic() == false) {
    header("Location: " . Specific::Url('404'));
    exit();
}


$TEMP['#page']        = 'rules';
$TEMP['#title']       = $TEMP['#word']['rules'] . ' - ' . $TEMP['#settings']['title'];
$TEMP['#description'] = $TEMP['#settings']['description'];
$TEMP['#keyword']     = $TEMP['#settings']['keyword'];

$TEMP['second_page'] = Specific::Maket('more/rules/content');
$TEMP['#content']     = Specific::Maket("more/content");
?>