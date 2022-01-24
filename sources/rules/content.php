<?php
if(!empty($_GET['article']) && isset($_GET['article'])){
	$article = Specific::Filter($_GET['article']);
	$rule = $dba->query('SELECT rules, file FROM rule WHERE status = "enabled"')->fetchArray();
	$rules = htmlspecialchars_decode($rule['rules']);
	$articles = array();
	if(preg_match_all('/\=\=\=(.+?)\=\=\=/i', $rules, $art)){
	    if (preg_match_all('/{\>\>(.+?)\<\<}/i', $rules, $num)) {
			for ($i=0; $i < count($art[1]); $i++) {
				$num[1][$i] = preg_replace('/[^0-9]/', '', $num[1][$i]);
			    $articles[$num[1][$i]] = Specific::GetComposeRule($art[1][$i], true);
			}
		}
	}	
	$article = $articles[$article];
	$TEMP['#link']  = Specific::GetFile($rule['file']);
	if(!empty($article)){
		$TEMP['title'] = $TEMP['#word']['cited_article'];
		$TEMP['rules'] = $article;
	} else {
		$TEMP['title'] = $TEMP['#word']['important_articles'];
		$TEMP['rules'] = Specific::GetComposeRule($rule['rules'], true);
	}
} else {
	$rule = $dba->query('SELECT rules, file FROM rule WHERE status = "enabled"')->fetchArray();
	$TEMP['title'] = $TEMP['#word']['important_articles'];
	$TEMP['#link']  = Specific::GetFile($rule['file']);
	$TEMP['rules'] = Specific::GetComposeRule($rule['rules'], true);
}


$TEMP['#page']   	   = 'rules';
$TEMP['#title'] 	   = $TEMP['#settings']['title'] . ' - ' . $TEMP['#word']['regulation'];
$TEMP['#description']  = $TEMP['#settings']['description'];
$TEMP['#keyword']      = $TEMP['#settings']['keyword'];
$TEMP['#load_url'] 	   = Specific::Url('rules');
$TEMP['#content'] = Specific::Maket("rules/content");