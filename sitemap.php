<?php
require_once('./includes/autoload.php');

if(time() > (intval($TEMP['#settings']['last_sitemap']) + 39600)){
	Specific::Sitemap();
}
?>