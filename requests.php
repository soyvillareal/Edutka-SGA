<?php
require_once('./includes/autoload.php');

$deliver = array(
	'status' => 400,
	'error' => $TEMP['#word']['error']
);
$one = Specific::Filter($_GET['one']);
$token = Specific::Filter($_POST['token']);
if (!empty($_GET['token'])) {
	$token = Specific::Filter($_GET['token']);
}

if (empty($token) || $token != $_SESSION['_LOGIN_TOKEN']) {
	$deliver['error'] = $TEMP['#word']['invalid_request'];
}

if (!empty($_GET['request-name']) && !empty($token) && $token == $_SESSION['_LOGIN_TOKEN']) {
	$req = Specific::Filter($_GET['request-name']);
	if (file_exists('./requests/'.$req.'.php')) {
		require_once('./requests/'.$req.'.php');
	} else {
		$deliver = array(
			'status' => 404,
			'error' => $TEMP['#word']['request_not_found']
		);
	}
}

header('Content-Type: application/json');
echo json_encode($deliver);
exit();
?>