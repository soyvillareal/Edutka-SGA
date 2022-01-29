<?php 
$ukey = Specific::Filter($_GET['ukey']);
$tokenu = Specific::Filter($_GET['tokenu']);
if ($TEMP['#loggedin'] === true || empty($ukey) || empty($tokenu)) {
	header("Location: " . Specific::Url());
	exit();
}

$TEMP['#user'] = $dba->query('SELECT * FROM user WHERE ukey = "'.$ukey.'" AND token = "'.$tokenu.'"')->fetchArray();

$page = empty($TEMP['#user']) ? 'invalid-auth' : 'not-me';

$TEMP['#bubbles'] = Specific::Bubbles();

$TEMP['ukey']         = $ukey;
$TEMP['token']        = $tokenu;
$TEMP['email']        = $TEMP['#user']['email'];
$TEMP['id']           = $TEMP['#user']['id'];
$TEMP['bubbles']      = implode(',', $TEMP['#bubbles']['rands']);

$TEMP['#page']        = 'not-me';
$TEMP['#title']       = $TEMP['#word']['didnt_create_this_account'] . ' - ' . $TEMP['#settings']['title'];
$TEMP['#description'] = $TEMP['#settings']['description'];
$TEMP['#keyword']     = $TEMP['#settings']['keyword'];
$TEMP['#load_url']    = Specific::Url('not-me/'.$tokenu.'/'.$ukey);

$TEMP['#content']     = Specific::Maket("$page/content");
?>