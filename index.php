<?php
require_once('./includes/autoload.php');

$page = 'login/content.php';
if($TEMP['#loggedin'] === true){
    $page = 'home/content.php';
}
if (isset($_GET['one'])) {
    if(!empty($_GET['page'])){
        $page = $_GET['one'].'/'.$_GET['page'].'/content.php'; 
    } else {
        $page = $_GET['one'].'/content.php';
    }
}

$TEMP['#return_url'] =  Specific::Url();
if(!empty($_GET['return'])){
    $TEMP['#return_url'] =  urlencode($_GET['return']);
}
if($TEMP['#loggedin'] === true){
    if ($TEMP['#user']['status'] != 'active') {
        if (isset($_COOKIE['_LOGIN_TOKEN'])) {
            setcookie('_LOGIN_TOKEN', null, -1,'/');
        }
        session_destroy();
    }
    if(!empty($_COOKIE['_LOGIN_TOKEN'])){
        if($_COOKIE['_LOGIN_TOKEN'] != $_SESSION['_LOGIN_TOKEN']){
            unset($_COOKIE['_LOGIN_TOKEN']);
            header("Location: ".Specific::ReturnUrl());
            exit();
        }
    }
    $live_notify = $dba->query('SELECT COUNT(*) FROM notification WHERE seen = 0 AND to_id = '.$TEMP['#user']['id'])->fetchArray();
    if(!empty($live_notify)){
        $TEMP['#notifications'] = $live_notify > 9 ? '9+' : $live_notify;
    }
}
if (file_exists("./sources/$page")) {
    require_once("./sources/$page");
} else {
    require_once("./sources/404/content.php");
}
$TEMP['footer_list'] = '';
$pages = $dba->query('SELECT * FROM page')->fetchAll();
foreach ($pages as $key => $value) {
    if($value['active'] == 1){
        $TEMP['footer_list'] .= '<li class="item-footer"><a class="color-tertiary" href="'.Specific::Url("pages/{$value['type']}").'" target="_self">'.$TEMP['#word'][str_replace('-', '_', $value['type'])].'</a></li>';
    }
}
$TEMP['lang_url'] = ((!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != 'on' ? 'http://' : 'https://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) . (strpos($_SERVER['REQUEST_URI'], '?') !== false ? '&' : '?') . 'language';
if(strpos($_SERVER['REQUEST_URI'], 'language') !== false){
    $TEMP['lang_url'] = preg_replace('/language(.+?)$/i', 'language', $_SERVER['REQUEST_URI']);
}
$TEMP['global_title'] = $TEMP['#title'];
$TEMP['global_description'] = $TEMP['#description'];
$TEMP['global_keywords'] = $TEMP['#keyword'];
$TEMP['content'] = $TEMP['#content'];
$TEMP['year_now'] = date('Y');
echo Specific::Maket('wrapper');
$dba->close();
unset($TEMP);
?>