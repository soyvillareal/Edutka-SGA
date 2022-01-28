<?php
require_once('./includes/autoload.php');

$page = 'login/content.php';
if($TEMP['#loggedin'] === true){
    $page = 'home/content.php';
}
if (isset($_GET['one'])) {
    if($_GET['one'] != 'admin' || ($_GET['one'] == 'admin' && !isset($_GET['two']))){
        $page = $_GET['one'].'/'.$_GET['page'].'/content.php'; 
    } else {
        if(!empty($_GET['three'])){
            $page = $_GET['one'].'/'.$_GET['two'].'/'.$_GET['three'].'.php';
        } else {
            $page = $_GET['one'].'/'.$_GET['two'].'.php';
        }
    }
}
$now_url = (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != 'on' ? 'http://' : 'https://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$TEMP['#now_url']  = urlencode($now_url);
$TEMP['#return_url'] =  Specific::Url();
if(!empty($_GET['return'])){
    $TEMP['#return_url'] =  urlencode($_GET['return']);
}
if($TEMP['#loggedin'] === true){
    if ($TEMP['#user']['status'] != 'active') {
        if (isset($_COOKIE['session_id'])) {
            setcookie('session_id', null, -1,'/');
        }
        session_destroy();
    }
    $live_notify = $dba->query('SELECT COUNT(*) FROM notifications WHERE seen = 0 AND to_id = '.$TEMP['#user']['id'])->fetchArray();
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
$pages = $dba->query('SELECT * FROM pages')->fetchAll();
foreach ($pages as $key => $value) {
    if($value['active'] == 1){
        $TEMP['footer_list'] .= '<li class="item-footer"><a class="color-tertiary" href="'.Specific::Url("pages/{$value['type']}").'" target="_self">'.$TEMP['#word'][str_replace('-', '_', $value['type'])].'</a></li>';
    }
}
$TEMP['lang_url'] = $now_url . (strpos($_SERVER['REQUEST_URI'], '?') !== false ? '&' : '?') . 'language';
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