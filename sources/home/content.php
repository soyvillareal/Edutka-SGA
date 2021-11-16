<?php
$TEMP['#program'] = $TEMP['#user']['program'];

$TEMP['#page']         = 'home';
$TEMP['#title']        = $TEMP['#settings']['title'].' - '.$TEMP['#word']['home'];
$TEMP['#description']  = $TEMP['#settings']['description'];
$TEMP['#keyword']      = $TEMP['#settings']['keyword'];
$TEMP['#load_url']     = Specific::Url();
$TEMP['#content']      = Specific::Maket('home/content');
?>