<?php
if ($TEMP['#loggedin'] === false) {
    $deliver = array(
        'status' => 400,
        'error' => $TEMP['#word']['error']
    );
    echo json_encode($deliver);
    exit();
} else if ($one == 'session'){
    $id = Specific::Filter($_POST['id']);
    if (!empty($id)) {
        $sessions = $dba->query('SELECT * FROM sessions WHERE id = '.$id)->fetchArray();
        if (!empty($sessions)) {
            $deliver['reload'] = false;
            if (($sessions['by_id'] == $TEMP['#user']['id']) || Specific::Admin()) {
                if ((!empty($_SESSION['session_id']) && $_SESSION['session_id'] == $sessions['session_id']) || (!empty($_COOKIE['session_id']) && $_COOKIE['session_id'] == $sessions['session_id'])) {
                    setcookie('session_id', null, -1, '/');
                    session_destroy();
                    $deliver['reload'] = true;
                }

                if ($dba->query('DELETE FROM sessions WHERE id = '.$id)->returnStatus()) {
                    $deliver['status'] = 200;
                }
            }
        }
    }
}
?>