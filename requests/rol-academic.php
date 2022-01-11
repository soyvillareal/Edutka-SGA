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
} else if($one == 'this-assings'){
    $deliver['status'] = 400;
    $teatrues = array(true);
    $types = array('add', 'edit');

    $type = Specific::Filter($_POST['type']);
    $id = Specific::Filter($_POST['id']);
    $teachers = Specific::Filter($_POST['teacher']);
    $period_id = Specific::Filter($_POST['period_id']);

    if(!empty($type) && in_array($type, $types)){
        if(!empty($teachers)){
            $teachers = explode(',', $teachers);
            if(!empty($teachers)){
                foreach ($teachers as $teacher) {
                    if($dba->query('SELECT COUNT(*) FROM users WHERE id = '.$teacher)->fetchArray() > 0){
                        $teatrues[] = true;
                    } else {
                        $teatrues[] = false;
                    }
                }
            }
            if (!in_array(false, $teatrues)) {
                $period_id = !empty($period_id) ? $period_id : $dba->query('SELECT id FROM periods WHERE status = "enabled" AND start < '.time().' AND final > '.time())->fetchArray();
                $instrues = array();
                if($type == 'add'){
                    for ($i=0; $i < count($teachers); $i++) {
                        if($dba->query('SELECT COUNT(*) FROM teacher WHERE user_id = '.$teachers[$i].' AND course_id = '.$id.' AND period_id = '.$period_id)->fetchArray() == 0){
                            if($dba->query('INSERT INTO teacher (user_id, course_id, period_id, `time`) VALUES ('.$teachers[$i].', '.$id.', '.$period_id.', '.time().')')->returnStatus()){
                                $instrues[] = true;
                            } else {
                                $deliver = array(
                                    'status' => 400,
                                    'error' => $TEMP['#word']['error']
                                );
                            }
                        } else {  
                            $instrues[] = false;
                        }
                    }
                    if(!in_array(false, $instrues)){
                        $deliver['status'] = 200;
                    } else {
                        $deliver = array(
                            'status' => 400,
                            'error' => $TEMP['#word']['teacher_already_assigned']
                        );
                    }
                } else {
                    $teachers_all = $dba->query('SELECT user_id FROM teacher WHERE course_id = '.$id.' AND period_id = '.$period_id)->fetchAll(false);
                    $deleted = array_diff($teachers_all, $teachers);
                    $addf = array_diff($teachers, $teachers_all);
                    $adds = explode(',', implode(',', $addf));
                    if(count($addf) > 0 || count($deleted) > 0){
                        if(count($addf) > 0){
                            for ($i=0; $i < count($adds); $i++) {
                                if($dba->query('SELECT COUNT(*) FROM teacher WHERE user_id = '.$adds[$i].' AND course_id = '.$id.' AND period_id = '.$period_id)->fetchArray() == 0){
                                    if($dba->query('INSERT INTO teacher (user_id, course_id, period_id, `time`) VALUES ('.$adds[$i].', '.$id.', '.$period_id.', '.time().')')->returnStatus()){
                                        $instrues[] = true;
                                    } else {
                                        $deliver = array(
                                            'status' => 400,
                                            'error' => $TEMP['#word']['error']
                                        );
                                    }
                                } else {  
                                    $instrues[] = false;
                                }
                            }
                            if(!in_array(false, $instrues)){
                                $deliver['status'] = 200;
                            } else {
                                $deliver['error'] = $TEMP['#word']['teacher_already_assigned'];
                            }
                        }
                        if(count($deleted) > 0){
                            if($dba->query('DELETE FROM teacher WHERE user_id IN ('.implode(',', $deleted).') AND course_id = '.$id.' AND period_id = '.$period_id)->returnStatus()){
                                $deliver['status'] = 200;
                            }
                        }
                    } else {
                        $deliver['status'] = 200;
                    }
                }
            } else {
                $deliver = array(
                    'status' => 400,
                    'errors' => $TEMP['#word']['please_enter_valid_value']
                );
            }
        } else {
            $deliver = array(
                'status' => 400,
                'errors' => $TEMP['#word']['this_field_is_empty']
            );
        }
    } else {
        $deliver = array(
            'status' => 400,
            'error' => $TEMP['#word']['error']
        );
    }
} else if($one == 'get-titems'){
    $id = Specific::Filter($_POST['id']);
    $period_id = Specific::Filter($_POST['period_id']);
    if(isset($id) && is_numeric($id)){
        $items = array();
        $teachers = $dba->query('SELECT * FROM teacher WHERE course_id = '.$id.' AND period_id = '.$period_id)->fetchAll();
        foreach ($teachers as $teacher) {
            $names = $dba->query('SELECT names FROM users WHERE id = '.$teacher['user_id'])->fetchArray();
            $items['teachers'][] = array('id' => $teacher['user_id'], 'name' => $names);   
        }
        $deliver['XD'] = $period_id;
        if (!empty($items)) {
            $deliver['status'] = 200;
            $deliver['items'] = $items;
        }
    }
} else if($one == 'get-periods'){
    $id = Specific::Filter($_POST['id']);
    if(isset($id) && is_numeric($id)){
        $periods = $dba->query('SELECT period_id FROM teacher WHERE course_id = '.$id)->fetchAll(false);
        $periods = array_unique($periods);
        $periods = $dba->query('SELECT * FROM periods WHERE id IN ('.implode(',', $periods).')')->fetchAll();
        if (!empty($periods)) {
            $deliver['status'] = 200;
            $deliver['periods'] = $periods;
        }
    }
} else if($one == 'search-teacher') {
    $keyword = Specific::Filter($_POST['keyword']);
    $html = '';
    $query = '';
    if(!empty($keyword)){
        $query .= " AND (names LIKE '%$keyword%' OR surnames LIKE '%$keyword%' OR dni LIKE '%$keyword%')";
    }
    $users = $dba->query('SELECT * FROM users WHERE role = "teacher"'.$query.' LIMIT 5')->fetchAll();
    if (!empty($users)) {
        foreach ($users as $user) {
            $html .= "<button class='tipsit-search display-flex btn-noway border-bottom border-grey padding-10 background-hover' data-id='".$user['id']."' data-name='".$user['names'].' '.$user['surnames']."'>".$user['names'].' '.$user['surnames']."</button>";
        }
        $deliver['status'] = 200;
    } else {
        $TEMP['keyword'] = $keyword;
        $html .= Specific::Maket('not-found/result-for');
    }
    $deliver['html'] = $html;
}
?>
