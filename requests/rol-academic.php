<?php 
if ($TEMP['#loggedin'] === false && (Specific::Admin() === false || Specific::Academic() === false)) {
    $deliver = array(
        'status' => 400,
        'error' => $TEMP['#word']['error']
    );
    echo json_encode($deliver);
    exit();
}

if($one == 'get-foitems'){
    $id = Specific::Filter($_POST['id']);
    if(isset($id) && is_numeric($id)){
        $items = $dba->query('SELECT form_key, access, expire, status FROM forms WHERE id = '.$id)->fetchArray();
        $items['access'] = explode(',', $items['access']);
        $items['expire'] = date('Y-m-d', $items['expire']);
        if (!empty($items)) {
            $deliver = array(
                'status' => 200,
                'items' => $items
            );
        }
    }
} else if($one == 'this-forms'){
    $deliver['status'] = 400;
    $statusa  = array('activated', 'deactivated');
    $min = Specific::Filter($_POST['min']);
    $emptys = array();
    $errors = array();


    $type = Specific::Filter($_POST['type']);
    $id = Specific::Filter($_POST['id']);
    $access = Specific::Filter($_POST['access']);
    $expire = Specific::Filter($_POST['expire']);
    $status = Specific::Filter($_POST['status']);

    if(empty($access)){
        $emptys[] = 'access';
    }
    if(empty($status)){
        $emptys[] = 'status';
    }
    if(empty($expire)){
        $emptys[] = 'expire';
    }
    if($min == date("Y-m-d") && is_numeric($id)){
        if(empty($emptys)){
            $expires = explode('-', $expire);
            if(!checkdate($expires[1], $expires[2], $expires[0])){
                $errors[] = 'expire';
            }
            if(!in_array($status, array_values($statusa))){
                $errors[] = 'status';
            }
            if (empty($errors)) {
                if(!empty($type)){
                    $expire = strtotime($expire);
                    if($type == 'add'){
                        $form_key = Specific::RandomKey(8, 16);
                        $form_exists = $dba->query('SELECT COUNT(*) FROM forms WHERE form_key = "'.$form_key.'"')->fetchArray();
                        if($form_exists > 0){
                            $form_key = Specific::RandomKey(8, 16);
                        }
                        if($dba->query('INSERT INTO forms (user_id, form_key, access, expire, status, `time`) VALUES ('.$TEMP['#user']['id'].', "'.$form_key.'", "'.$access.'", '.$expire.', "'.$status.'", '.time().')')->returnStatus()){
                            $deliver['status'] = 200;
                        };
                    } else if(isset($id) && is_numeric($id)){
                        if($dba->query('UPDATE forms SET access = ?, expire = ?, status = ? WHERE id = '.$id, $access, $expire, $status)->returnStatus()){
                            $deliver['status'] = 200;
                        }
                    }
                }
            } else {
                $deliver = array(
                    'status' => 400,
                    'errors' => $errors
                );
            }
        } else {
            $deliver = array(
                'status' => 400,
                'emptys' => $emptys
            );
        }
    }
} else if($one == 'delete-form'){
    $deliver['status'] = 400;
    $id = Specific::Filter($_POST['id']);
    if (isset($id) && is_numeric($id)) {
        if($dba->query('DELETE FROM forms WHERE id = '.$id)->returnStatus()){
            $deliver['status'] = 200;
        };
    }
} else if($one == 'search-forms') {
    $keyword = Specific::Filter($_POST['keyword']);
    $html = '';
    $query = '';
    if(!empty($keyword)){
        $query .= " f WHERE id LIKE '%$keyword%' OR (SELECT id FROM users WHERE (names LIKE '%$keyword%' OR surnames LIKE '%$keyword%') AND id = f.user_id) = user_id";
    }
    $forms = $dba->query('SELECT * FROM forms'.$query.' LIMIT ? OFFSET ?', 10, 1)->fetchAll();
    $deliver['total_pages'] = $dba->totalPages;
    if (!empty($forms)) {
        foreach ($forms as $form) {
            $user = Specific::Data($form['user_id']);
            $access = explode(',', $form['access']);
            $TEMP['!id'] = $form['id'];
            $TEMP['!user'] = $user['full_name'];
            $TEMP['!form_key'] = $form['form_key'];
            $TEMP['!access'] = count($access);
            $TEMP['!status'] = $TEMP['#word'][$form['status']];
            $TEMP['!time'] = Specific::DateFormat($form['time']);
            $html .= Specific::Maket('forms/includes/forms-list');
        }
        Specific::DestroyMaket();
        $deliver['status'] = 200;
    } else {
        if(!empty($keyword)){
            $TEMP['keyword'] = $keyword;
            $html .= Specific::Maket('not-found/result-for');
        } else {
            $html .= Specific::Maket('not-found/forms');
        }
    }
    $deliver['html'] = $html;
} else if($one == 'table-forms'){
    $page = Specific::Filter($_POST['page_id']);
    if(!empty($page) && is_numeric($page) && isset($page) && $page > 0){
        $html = "";
        $query = '';
        $keyword = Specific::Filter($_POST['keyword']);
        if(!empty($keyword)){
            $query = " f WHERE id LIKE '%$keyword%' OR (SELECT id FROM users WHERE (names LIKE '%$keyword%' OR surnames LIKE '%$keyword%') AND id = f.user_id) = user_id";
        }
        $forms = $dba->query('SELECT * FROM forms'.$query.' LIMIT ? OFFSET ?', 10, $page)->fetchAll();
        if (!empty($forms)) {
            foreach ($forms as $form) {
                $user = Specific::Data($form['user_id']);
                $access = explode(',', $form['access']);
                $TEMP['!id'] = $form['id'];
                $TEMP['!user'] = $user['full_name'];
                $TEMP['!form_key'] = $form['form_key'];
                $TEMP['!access'] = count($access);
                $TEMP['!status'] = $TEMP['#word'][$form['status']];
                $TEMP['!time'] = Specific::DateFormat($form['time']);
                $html .= Specific::Maket('forms/includes/forms-list');
            }
            Specific::DestroyMaket();
            $deliver['status'] = 200;
        }
        $deliver['status'] = 200;
        $deliver['html'] = $html;
    }
}
?>
