<?php
if (!empty($_SESSION['_LOGIN_TOKEN'])) {
    $dba->query('DELETE FROM session WHERE session_id = "'.Specific::Filter($_SESSION['_LOGIN_TOKEN']).'"');
}
if (isset($_COOKIE['_LOGIN_TOKEN'])) {
    $dba->query('DELETE FROM session WHERE session_id = "'.Specific::Filter($_COOKIE['_LOGIN_TOKEN']).'"');
    setcookie('_LOGIN_TOKEN', null, -1,'/');
} 
session_destroy();
header("Location: ".Specific::Url());
exit();
?>