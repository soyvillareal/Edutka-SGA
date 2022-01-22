<?php 
if ($TEMP['#loggedin'] === true && (Specific::Admin() === true || Specific::Academic() === true)) {
    if($one == 'get-foitems'){
        $deliver['status'] = 400;
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
                                        $teacher_id = $dba->query('INSERT INTO teacher (user_id, course_id, period_id, `time`) VALUES ('.$adds[$i].', '.$id.', '.$period_id.', '.time().')')->insertId();
                                        if($teacher_id){
                                            if(count($deleted) > 0){
                                                if($dba->query('SELECT COUNT(*) FROM parameter p WHERE (SELECT id FROM teacher WHERE user_id IN ('.implode(',', $deleted).') AND course_id = '.$id.' AND period_id = '.$period_id.' AND id = p.teacher_id) = teacher_id')->fetchArray() > 0){
                                                    
                                                    if($dba->query('UPDATE parameter SET teacher_id = '.$teacher_id.' WHERE (SELECT id FROM teacher WHERE user_id IN ('.implode(',', $deleted).') AND course_id = '.$id.' AND period_id = '.$period_id.') = teacher_id')->returnStatus()){
                                                        $instrues[] = true;
                                                    }
                                                } else {
                                                    $instrues[] = true;
                                                }
                                            } else {
                                                $instrues[] = true;
                                            }
                                        }
                                    } else {  
                                        $instrues[] = false;
                                    }
                                }
                                if(!in_array(false, $instrues)){
                                    $deliver['status'] = 200;
                                } else {
                                    $deliver = array(
                                        'status' => 302,
                                        'error' => $TEMP['#word']['teacher_already_assigned']
                                    );
                                }
                            }
                            if(count($deleted) > 0){
                                if($dba->query('SELECT COUNT(*) FROM parameter p WHERE (SELECT id FROM teacher WHERE user_id IN ('.implode(',', $deleted).') AND course_id = '.$id.' AND period_id = '.$period_id.' AND id = p.teacher_id) = teacher_id')->fetchArray() > 0){
                                    $parameter_id = $dba->query('SELECT id FROM parameter p WHERE (SELECT id FROM teacher WHERE user_id IN ('.implode(',', $deleted).') AND course_id = '.$id.' AND period_id = '.$period_id.' AND id = p.teacher_id) = teacher_id')->fetchArray();
                                    $add_id = $dba->query('SELECT id FROM teacher WHERE user_id = '.$teachers[0].' AND course_id = '.$id.' AND period_id = '.$period_id)->fetchArray();

                                    if($dba->query('UPDATE parameter SET teacher_id = '.$add_id.' WHERE id = '.$parameter_id)->returnStatus()){
                                        if($dba->query('DELETE FROM teacher WHERE user_id IN ('.implode(',', $deleted).') AND course_id = '.$id.' AND period_id = '.$period_id)->returnStatus()){
                                            $deliver['status'] = 200;
                                        }
                                    }
                                } else if($dba->query('DELETE FROM teacher WHERE user_id IN ('.implode(',', $deleted).') AND course_id = '.$id.' AND period_id = '.$period_id)->returnStatus()){
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
        }
    } else if($one == 'delete-assing'){
        $deliver['status'] = 400;
        $id = Specific::Filter($_POST['id']);
        $period_id = Specific::Filter($_POST['period_id']);
        if (isset($id) && is_numeric($id) && isset($period_id) && is_numeric($period_id)) {
            $teacher_id = $dba->query('SELECT id FROM teacher WHERE course_id = '.$id.' AND period_id = '.$period_id)->fetchArray();
            if($dba->query('DELETE FROM teacher WHERE id = '.$teacher_id)->returnStatus()){
                if($dba->query('DELETE FROM parameter WHERE teacher_id = '.$teacher_id)->returnStatus()){
                    $deliver['status'] = 200;
                }
            };
        }
    } else if($one == 'get-titems'){
        $deliver['status'] = 400;
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
        $deliver['status'] = 400;
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
    } else if($one == 'check-enroll'){
        $deliver['status'] = 400;
        $id = Specific::Filter($_POST['course_id']);
        $user_id = Specific::Filter($_POST['user_id']);
        $program_id = Specific::Filter($_POST['program_id']);
        if(!empty($id) && is_numeric($id) && !empty($user_id) && is_numeric($user_id)){
            $period_id = $dba->query('SELECT id FROM periods WHERE status = "enabled" AND start < '.time().' AND final > '.time())->fetchArray();
            $periods = $dba->query('SELECT period_id FROM enrolled WHERE user_id = '.$user_id.' AND course_id = '.$id.' AND program_id = '.$program_id)->fetchAll(false);
            $deliver['status'] = 200;
            $deliver['cstatus'] = 'false';
            if(in_array($period_id, $periods)){
                $deliver['cstatus'] = 'true';
                $deliver['period'] = $period_id;
            }
        }
    } else if($one == 'get-uitems'){
        $deliver['status'] = 400;
        $id = Specific::Filter($_POST['id']);
        if(isset($id) && is_numeric($id)){
            $sql = 'SELECT dni, names, surnames FROM users WHERE id = '.$id;
            if(Specific::Admin() == true){
                $sql = 'SELECT dni, names, surnames, role, status FROM users WHERE id = '.$id;
            }
            $items = $dba->query($sql)->fetchArray();
            if (!empty($items)) {
                $deliver = array(
                    'status' => 200,
                    'items' => $items
                );
            }
        }
    } else if($one == 'this-users'){
        $deliver['status'] = 400;
        $roles  = array('admin', 'academic', 'teacher', 'student');
        $statusa  = array(0, 1);
        $emptys = array();
        $errors = array();

        $id = Specific::Filter($_POST['id']);
        $dni = Specific::Filter($_POST['dni']);
        $names = Specific::Filter($_POST['names']);
        $surnames = Specific::Filter($_POST['surnames']);
        $role = Specific::Filter($_POST['role']);
        $status = Specific::Filter($_POST['status']);

        if(empty($dni)){
            $emptys[] = 'dni';
        }
        if(empty($names)){
            $emptys[] = 'names';
        }
        if(empty($surnames)){
            $emptys[] = 'surnames';
        }
        if(Specific::Admin() == true){
           if(empty($role)){
                $emptys[] = 'role';
            }
            if(empty($status)){
                $emptys[] = 'status';
            } 
        }

        if(isset($id) && is_numeric($id)){
            if(empty($emptys)){
                if(!is_numeric($dni)){
                    $errors[] = 'dni';
                }
                if(strlen($dni) >= 11){
                    $errors[] = 'dni';
                }
                if(Specific::Admin() == true){
                    if(!in_array($role, $roles)){
                        $errors[] = 'role';
                    }
                    if(!in_array($status, $statusa)){
                        $errors[] = 'status';
                    }
                }
                if (empty($errors)) {
                    $sql = 'UPDATE users SET dni = '.$dni.', names = "'.$names.'", surnames = "'.$surnames.'" WHERE id = '.$id;
                    if(Specific::Admin() == true){
                        $sql = 'UPDATE users SET dni = '.$dni.', names = "'.$names.'", surnames = "'.$surnames.'", role = "'.$role.'", status = "'.$status.'" WHERE id = '.$id;
                    }

                    if($dba->query($sql)->returnStatus()){
                            $deliver['status'] = 200;
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
    } else if($one == 'search-users') {
        $keyword = Specific::Filter($_POST['keyword']);
        $html = '';
        $query = '';
        if(!empty($keyword)){
            $query .= " WHERE dni LIKE '%$keyword%' OR names LIKE '%$keyword%' OR surnames LIKE '%$keyword%'";
        }
        $users = $dba->query('SELECT * FROM users'.$query.' LIMIT ? OFFSET ?', 10, 1)->fetchAll();
        $deliver['total_pages'] = $dba->totalPages;
        if (!empty($users)) {
            foreach ($users as $user) {
                $TEMP['!data'] = Specific::Data($user['id']);
                $TEMP['!status'] = $TEMP['#word'][$user['status']];
                $html .= Specific::Maket('more/users/includes/users-list');
            }
            Specific::DestroyMaket();
            $deliver['status'] = 200;
        } else {
            if(!empty($keyword)){
                $TEMP['keyword'] = $keyword;
                $html .= Specific::Maket('not-found/result-for');
            } else {
                $html .= Specific::Maket('not-found/users');
            }
        }
        $deliver['html'] = $html;
    } else if($one == 'table-users'){
        $page = Specific::Filter($_POST['page_id']);
        if(!empty($page) && is_numeric($page) && isset($page) && $page > 0){
            $html = "";
            $query = '';
            $keyword = Specific::Filter($_POST['keyword']);
            if(!empty($keyword)){
                $query .= " WHERE dni LIKE '%$keyword%' OR names LIKE '%$keyword%' OR surnames LIKE '%$keyword%'";
            }
            $users = $dba->query('SELECT * FROM users'.$query.' LIMIT ? OFFSET ?', 10, $page)->fetchAll();
            if (!empty($users)) {
                foreach ($users as $user) {
                    $TEMP['!data'] = Specific::Data($user['id']);
                    $TEMP['!status'] = $TEMP['#word'][$user['status']];
                    $html .= Specific::Maket('more/users/includes/users-list');
                }
                Specific::DestroyMaket();
                $deliver['status'] = 200;
            }
            $deliver['status'] = 200;
            $deliver['html'] = $html;
        }
    } else if($one == 'get-aitems'){
        $deliver['status'] = 400;
        $id = Specific::Filter($_POST['id']);
        if(isset($id) && is_numeric($id)){
            $items = $dba->query('SELECT description, court, expires, status FROM authorization WHERE id = '.$id)->fetchArray();
            $items['coutxt'] = $TEMP['#word'][$items['court']];
            if($items['expires'] != 0){
               $items['expires'] = date('Y-m-d', $items['expires']); 
            }
            if($items['status'] == 'pending'){
                unset($items['status']);
            }
            if (!empty($items)) {
                $deliver = array(
                    'status' => 200,
                    'items' => $items
                );
            }
        }
    } else if($one == 'this-authorizations'){
        $deliver['status'] = 400;
        $emptys = array();
        $errors = array();
        $statusa = array('authorized', 'pending', 'denied');
        $id = Specific::Filter($_POST['id']);
        $expires = Specific::Filter($_POST['expires']);
        $status = Specific::Filter($_POST['status']);
        if(isset($id) && is_numeric($id)){
            if(empty($expires)){
                $emptys[] = 'expires';
            }
            if(empty($status)){
                $emptys[] = 'status';
            }
            if(empty($emptys)){
                $expire = explode('-', $expires);
                if(!checkdate($expire[1], $expire[2], $expire[0])){
                    $errors[] = 'expires';
                }
                if(!in_array($status, $statusa)){
                    $errors[] = 'status';
                }
                if(empty($errors)){
                    $expires = strtotime($expires);
                    $period = $dba->query('SELECT *, COUNT(*) AS count FROM periods WHERE status = "enabled" AND start < '.time().' AND final > '.time())->fetchArray();
                    if($period['count'] > 0){
                        if($expires > $period['start'] && $expires < $period['final']){
                            if($dba->query('UPDATE authorization SET user_id = ?, period_id = ?, expires = ?, status = ? WHERE id = '.$id, $TEMP['#user']['id'], $period['id'], $expires, $status)->returnStatus()){
                                $deliver['status'] = 200;
                                $authorization = $dba->query('SELECT * FROM authorization WHERE id = '.$id)->fetchArray();
                                Specific::SendNotification(array(
                                    'from_id' => $TEMP['#user']['id'],
                                    'to_id' => $dba->query('SELECT id FROM users u WHERE (SELECT user_id FROM teacher WHERE id = '.$authorization['teacher_id'].' AND user_id = u.id) = id')->fetchArray(),
                                    'course_id' => $authorization['course_id'],
                                    'type' => "'auth_".$status."'",
                                    'time' => time()
                                ));
                            }
                        } else {
                            $deliver = array(
                                'status' => 400,
                                'error' => "{$TEMP['#word']['authorization_must_expire_within_period']} {$period['name']}"
                            );
                        }
                    } else {
                        $deliver = array(
                            'status' => 400,
                            'error' => $TEMP['#word']['there_no_active_period_moment']
                        );
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
    } else if($one == 'search-authorizations') {
        $keyword = Specific::Filter($_POST['keyword']);
            $html = '';
            $query = '';
            if(!empty($keyword)){
                $query .= " a WHERE id LIKE '%$keyword%' OR (SELECT id FROM users WHERE (dni LIKE '%$keyword%' OR names LIKE '%$keyword%' OR surnames LIKE '%$keyword%') AND id = a.user_id) = user_id OR (SELECT id FROM courses WHERE (code LIKE '%$keyword%' OR name LIKE '%$keyword%') AND id = a.course_id) = course_id";
            }
            $authorizations = $dba->query('SELECT * FROM authorization'.$query.' LIMIT ? OFFSET ?', 10, 1)->fetchAll();
            $deliver['total_pages'] = $dba->totalPages;
            if(!empty($authorizations)){
                foreach ($authorizations as $auth) {
                    $period = $dba->query('SELECT name FROM periods WHERE id = '.$auth['period_id'])->fetchArray();
                    $TEMP['!id'] = $auth['id'];
                    $TEMP['!academic'] = $auth['status'] == 'pending' ? $TEMP['#word']['pending'] : $dba->query('SELECT names FROM users WHERE id = '.$auth['user_id'])->fetchArray();
                    $TEMP['!teacher'] = $dba->query('SELECT names FROM users u WHERE (SELECT user_id FROM teacher WHERE id = '.$auth['teacher_id'].' AND user_id = u.id) = id')->fetchArray();
                    $TEMP['!course'] = $dba->query('SELECT name FROM courses WHERE id = '.$auth['course_id'])->fetchArray();
                    $TEMP['!court'] = $TEMP['#word'][$auth['court']];
                    $TEMP['!period'] = is_array($period) ? $TEMP['#word']['pending'] : $period;
                    $TEMP['!status'] = $TEMP['#word'][$auth['status']];
                    $TEMP['!time'] = Specific::DateFormat($auth['time']);
                    $html .= Specific::Maket('more/authorizations/includes/authorizations-list');
                }
                Specific::DestroyMaket();
                $deliver['status'] = 200;
            } else {
                if(!empty($keyword)){
                    $TEMP['keyword'] = $keyword;
                    $html .= Specific::Maket('not-found/result-for');
                } else {
                    $html .= Specific::Maket('not-found/authorizations');
                }
            }
            $deliver['html'] = $html;
    } else if($one == 'table-authorizations'){
        $page = Specific::Filter($_POST['page_id']);
        if(!empty($page) && is_numeric($page) && isset($page) && $page > 0){
            $html = "";
            $query = '';
            $keyword = Specific::Filter($_POST['keyword']);
            if(!empty($keyword)){
                $query .= " a WHERE id LIKE '%$keyword%' OR (SELECT id FROM users WHERE (dni LIKE '%$keyword%' OR names LIKE '%$keyword%' OR surnames LIKE '%$keyword%') AND id = a.user_id) = user_id OR (SELECT id FROM courses WHERE (code LIKE '%$keyword%' OR name LIKE '%$keyword%') AND id = a.course_id) = course_id";
            }
            $authorizations = $dba->query('SELECT * FROM authorization'.$query.' LIMIT ? OFFSET ?', 10, $page)->fetchAll();
            if (!empty($authorizations)) {
                foreach ($authorizations as $auth) {
                    $period = $dba->query('SELECT name FROM periods WHERE id = '.$auth['period_id'])->fetchArray();
                    $TEMP['!id'] = $auth['id'];
                    $TEMP['!academic'] = $auth['status'] == 'pending' ? $TEMP['#word']['pending'] : $dba->query('SELECT names FROM users WHERE id = '.$auth['user_id'])->fetchArray();
                    $TEMP['!teacher'] = $dba->query('SELECT names FROM users u WHERE (SELECT user_id FROM teacher WHERE id = '.$auth['teacher_id'].' AND user_id = u.id) = id')->fetchArray();
                    $TEMP['!course'] = $dba->query('SELECT name FROM courses WHERE id = '.$auth['course_id'])->fetchArray();
                    $TEMP['!court'] = $TEMP['#word'][$auth['court']];
                    $TEMP['!period'] = is_array($period) ? $TEMP['#word']['pending'] : $period;
                    $TEMP['!status'] = $TEMP['#word'][$auth['status']];
                    $TEMP['!time'] = Specific::DateFormat($auth['time']);
                    $html .= Specific::Maket('more/authorizations/includes/authorizations-list');
                }
                Specific::DestroyMaket();
                $deliver['status'] = 200;
            }
            $deliver['status'] = 200;
            $deliver['html'] = $html;
        }
    } else if($one == 'get-qitems'){
        $deliver['status'] = 400;
        $id = Specific::Filter($_POST['id']);
        if(isset($id) && is_numeric($id)){
            $items = $dba->query('SELECT note_id, note, status FROM qualification WHERE id = '.$id)->fetchArray();
            $items['course'] = $dba->query('SELECT name FROM courses c WHERE (SELECT course_id FROM notes n WHERE id = '.$items['note_id'].' AND course_id = c.id) = id')->fetchArray();
            $user_id = $dba->query('SELECT user_id FROM notes WHERE id = '.$items['note_id'])->fetchArray();
            $data = Specific::Data($user_id);
            $items['applicant'] = $data['full_name'];
            $items['email'] = $data['email'];
            $items['cellphone'] = $data['cellphone'];
            $items['phone'] = 'true';
            if(!empty($data['phone'])){
                $items['phone'] = $data['phone'];
            }
            unset($items['note_id']);

            if (!empty($items)) {
                $deliver = array(
                    'status' => 200,
                    'items' => $items
                );
            }
        }
    } else if($one == 'this-qualifications'){
        $deliver['status'] = 400;
        $emptys = array();
        $errors = array();
        $statusa = array('accepted', 'pending', 'rejected');
        $id = Specific::Filter($_POST['id']);
        $note = Specific::Filter($_POST['note']);
        $status = Specific::Filter($_POST['status']);
        if(isset($id) && is_numeric($id)){
            if(empty($status)){
                $emptys[] = 'status';
            }
            if(empty($emptys)){
                $qualification = $dba->query('SELECT * FROM qualification WHERE id = '.$id)->fetchArray();
                $noteq = $dba->query('SELECT course_id, period_id FROM notes WHERE id = '.$qualification['note_id'])->fetchArray();
                $users = $dba->query('SELECT user_id FROM teacher WHERE course_id = '.$noteq['course_id'].' AND period_id = '.$noteq['period_id'])->fetchAll(false);
                $user_id = $dba->query('SELECT user_id FROM notes WHERE id = '.$qualification['note_id'])->fetchArray();
                $users[] = $user_id;

                if(!in_array($status, $statusa)){
                    $errors[] = array('el' => 'status', 'text' => $TEMP['#word']['please_enter_valid_value']);
                }
                if(!empty($note)){
                    if($note > $TEMP['#nm'] || $note < 0){
                        $errors[] = array('el' => 'note', 'text' => $TEMP['#word']['please_enter_valid_value']);
                    }
                }
                if(empty($note)){
                    $note = NULL;
                    if($qualification['note'] != null){
                        $errors[] = array('el' => 'note', 'text' => $TEMP['#word']['this_field_is_empty']);
                    }
                }
                if(empty($errors)){
                    if($dba->query('UPDATE qualification SET note = ?, status = ? WHERE id = '.$id, $note, $status)->returnStatus()){
                        $deliver['status'] = 200;
                        if($status != 'pending' && $status != $qualification['status']){
                            foreach ($users as $user) {
                                $notify_exists = $dba->query('SELECT COUNT(*) FROM notifications WHERE from_id = '.$TEMP['#user']['id'].' AND to_id = '.$user.' AND course_id = '.$noteq['course_id'].' AND type = "quate_accepted"')->fetchArray();
                                $type = $user == $user_id ? "st_$status" : ($notify_exists > 0 ? "te_rejected" : "te_accepted");
                                if($status != 'rejected' || $user == $user_id || $notify_exists > 0){
                                    Specific::SendNotification(array(
                                        'from_id' => $TEMP['#user']['id'],
                                        'to_id' => $user,
                                        'course_id' => $noteq['course_id'],
                                        'type' => "'qua$type'",
                                        'time' => time()
                                    ));
                                }
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
    } else if($one == 'search-qualifications') {
        $keyword = Specific::Filter($_POST['keyword']);
            $html = '';
            $query = '';
            if(!empty($keyword)){
                $query .= " a WHERE id LIKE '%$keyword%' OR (SELECT id FROM users WHERE (dni LIKE '%$keyword%' OR names LIKE '%$keyword%' OR surnames LIKE '%$keyword%') AND id = a.user_id) = user_id";
            }
            $qualifications = $dba->query('SELECT * FROM qualification'.$query.' LIMIT ? OFFSET ?', 10, 1)->fetchAll();
            $deliver['total_pages'] = $dba->totalPages;
            if(!empty($qualifications)){
                foreach ($qualifications as $qua) {
                    $TEMP['!id'] = $qua['id'];
                    $TEMP['!applicant'] = $dba->query('SELECT names FROM users WHERE (SELECT user_id FROM notes WHERE id = '.$qua['note_id'].') = id')->fetchArray();
                    $TEMP['!course'] = $dba->query('SELECT name FROM courses c WHERE (SELECT course_id FROM notes n WHERE id = '.$qua['note_id'].' AND course_id = c.id) = id')->fetchArray();
                    $TEMP['!note'] = empty($qua['note']) ? $TEMP['#word']['undefined'] : $qua['note'];
                    $TEMP['!status'] = $TEMP['#word'][$qua['status']];
                    $TEMP['!time'] = Specific::DateFormat($qua['time']);
                    $html .= Specific::Maket('more/qualifications/includes/qualifications-list');
                }
                Specific::DestroyMaket();
                $deliver['status'] = 200;
            } else {
                if(!empty($keyword)){
                    $TEMP['keyword'] = $keyword;
                    $html .= Specific::Maket('not-found/result-for');
                } else {
                    $html .= Specific::Maket('not-found/qualifications');
                }
            }
            $deliver['html'] = $html;
    } else if($one == 'table-qualifications'){
        $page = Specific::Filter($_POST['page_id']);
        if(!empty($page) && is_numeric($page) && isset($page) && $page > 0){
            $html = "";
            $query = '';
            $keyword = Specific::Filter($_POST['keyword']);
            if(!empty($keyword)){
                $query .= " a WHERE id LIKE '%$keyword%' OR (SELECT id FROM users WHERE (dni LIKE '%$keyword%' OR names LIKE '%$keyword%' OR surnames LIKE '%$keyword%') AND id = a.user_id) = user_id";
            }
            $qualifications = $dba->query('SELECT * FROM qualification'.$query.' LIMIT ? OFFSET ?', 10, $page)->fetchAll();
            if (!empty($qualifications)) {
                foreach ($qualifications as $qua) {
                    $TEMP['!id'] = $qua['id'];
                    $TEMP['!applicant'] = $dba->query('SELECT names FROM users WHERE (SELECT user_id FROM notes WHERE id = '.$qua['note_id'].') = id')->fetchArray();
                    $TEMP['!course'] = $dba->query('SELECT name FROM courses c WHERE (SELECT course_id FROM notes n WHERE id = '.$qua['note_id'].' AND course_id = c.id) = id')->fetchArray();
                    $TEMP['!note'] = empty($qua['note']) ? $TEMP['#word']['undefined'] : $qua['note'];
                    $TEMP['!status'] = $TEMP['#word'][$qua['status']];
                    $TEMP['!time'] = Specific::DateFormat($qua['time']);
                    $html .= Specific::Maket('more/qualifications/includes/qualifications-list');
                }
                Specific::DestroyMaket();
                $deliver['status'] = 200;
            }
            $deliver['status'] = 200;
            $deliver['html'] = $html;
        }
    }
}
?>
