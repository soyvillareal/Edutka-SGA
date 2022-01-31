<?php
if (isset($_GET['one'])) {
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
    if (file_exists("./sources/$page")) {
        require_once("./sources/$page");
    } else {
        require_once("./sources/404/content.php");
    }
    $deliver['title'] = $TEMP['#title'];
    $deliver['description'] = $TEMP['#description'];
    $deliver['keyword'] = $TEMP['#keyword'];
    $deliver['page'] = $TEMP['#page'];
    if(!empty($TEMP['#second_page'])){
        $deliver['second_page'] = $TEMP['#second_page'];
    }
    $deliver['url'] = $TEMP['#load_url'];
    $deliver['html'] = $TEMP['#content'];
    $deliver['status'] = 200;
}
?>