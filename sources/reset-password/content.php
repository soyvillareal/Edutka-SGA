<?php
if ($TEMP['#loggedin'] === true || empty($_GET['tokenu'])) {
	header("Location: " . Specific::Url());
	exit();
}

$tokenu = Specific::Filter($_GET['tokenu']);
$page = $dba->query('SELECT COUNT(*) FROM user WHERE token = "'.$tokenu.'"')->fetchArray() == 0 ? 'invalid-auth' : 'reset-password';

$TEMP['#bubbles'] = Specific::Bubbles();

$TEMP['tokenu'] = $tokenu;
$TEMP['bubbles'] = implode(',', $TEMP['#bubbles']['rands']);

$TEMP['#page']        = 'reset-password';
$TEMP['#title']       = $TEMP['#word']['change_password'] . ' - ' . $TEMP['#settings']['title'];
$TEMP['#description'] = $TEMP['#settings']['description'];
$TEMP['#keyword']     = $TEMP['#settings']['keyword'];

$TEMP['#content']     = Specific::Maket("$page/content");
?>