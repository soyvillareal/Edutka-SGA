<?php
$ukey = Specific::Filter($_GET['ukey']);
$tokenu = Specific::Filter($_GET['tokenu']);
$TEMP['#descode'] = Specific::Filter($_GET['insert']);
if ($TEMP['#loggedin'] == true || empty($tokenu) || empty($ukey)) {
	header("Location: " . Specific::Url());
	exit();
}

$user = $dba->query('SELECT * FROM user WHERE ukey = "'.$ukey.'" AND token = "'.$tokenu.'"')->fetchArray();
$page = empty($user) || $user['status'] == 'active' || (strlen($TEMP['#descode']) != 6 && !empty($TEMP['#descode'])) ? 'invalid-auth' : 'authentication';

$TEMP['#bubbles'] = Specific::Bubbles();

$TEMP['title'] = $TEMP['#word']['check_your_email'];
$TEMP['type'] = 'email';
$TEMP['ukey'] = $ukey;
$TEMP['token'] = $tokenu;
$TEMP['bubbles'] = implode(',', $TEMP['#bubbles']['rands']);
if(!empty($_GET['insert'])){
	$TEMP['desone'] = substr($TEMP['#descode'], 0, 1);
	$TEMP['destwo'] = substr($TEMP['#descode'], 1, 1);
	$TEMP['desthree'] = substr($TEMP['#descode'], 2, 1);
	$TEMP['desfour'] = substr($TEMP['#descode'], 3, 1);
	$TEMP['desfive'] = substr($TEMP['#descode'], 4, 1);
	$TEMP['dessix'] = substr($TEMP['#descode'], 5, 1);
}

$TEMP['#page']        = 'verify-email';
$TEMP['#title']       = $TEMP['#word']['check_your_email'] . ' - ' . $TEMP['#settings']['title'];
$TEMP['#description'] = $TEMP['#settings']['description'];
$TEMP['#keyword']     = $TEMP['#settings']['keyword'];
$TEMP['#load_url']    = Specific::Url('verify-email/'.$tokenu.'/'.$ukey);

$TEMP['#content'] = Specific::Maket("$page/content");
?>