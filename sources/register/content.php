<?php
$TEMP['#form_key'] = Specific::Filter($_GET['form-key']);
if (empty($TEMP['#form_key']) || ($TEMP['#loggedin'] === true && $TEMP['#academic'] == false)) {
	header("Location: " . Specific::Url());
	exit();
}
if($TEMP['#loggedin'] == true && $TEMP['#academic'] == false){
	header("Location: " . Specific::Url());
	exit();
}

$form = $dba->query('SELECT * FROM forms WHERE form_key = "'.$TEMP['#form_key'].'"')->fetchArray();

$page = empty($form) || time() >= $form['expire'] ? 'invalid-auth' : 'register';

$TEMP['#bubbles'] = Specific::Bubbles();

$TEMP['bubbles']      = implode(',', $TEMP['#bubbles']['rands']);

$TEMP['#page']        = 'register';
$TEMP['#title']       = $TEMP['#word']['create_account'] . ' - ' . $TEMP['#settings']['title'];
$TEMP['#description'] = $TEMP['#settings']['description'];
$TEMP['#keyword']     = $TEMP['#settings']['keyword'];
$TEMP['#load_url']    = Specific::Url('register/'.$TEMP['#form_key']);

$TEMP['#content']     = Specific::Maket("$page/content");
?>