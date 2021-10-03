<?php
$TEMP = array();
$TEMP['#site_url'] = $site_url;
$TEMP['#settings'] = Specific::Settings();
$TEMP['#loggedin'] = Specific::Logged();
if ($TEMP['#loggedin'] === true) {
    $TEMP['#user'] = Specific::Data(null, 3);
}
$language = Specific::Filter($_GET['language']);
if(empty($_GET['language'])){
	$language = $_SESSION['language'];
}
$TEMP['#language'] = $_SESSION['language'] = Specific::Language($language);
$TEMP['#word'] = Specific::Words($TEMP['#language']);
$TEMP['#token_session'] = Specific::TokenSession();
if (isset($_SESSION['session_id'])) {
    if (empty($_COOKIE['session_id'])) {
        setcookie("session_id", $_SESSION['session_id'], time() + 315360000, "/");
    }
}
if (empty($TEMP['#word'])) {
    $TEMP['#word'] = Specific::Words();
}
?>