<?php
if ($TEMP['#loggedin'] === false && (Specific::Admin() === false || Specific::Academic() === false || Specific::Teacher() === false)) {
    $deliver = array(
        'status' => 400,
        'error' => $TEMP['#word']['error']
    );
    echo json_encode($deliver);
    exit();
}

if($one == 'search-courses') {
    $keyword = Specific::Filter($_POST['keyword']);
    $user_id = Specific::Filter($_POST['user_id']);
    $period_id = Specific::Filter($_POST['period_id']);
    $html = '';
    $query = '';
    if(!empty($keyword)){
        $query .= " WHERE name LIKE '%$keyword%'";
    }
    $TEMP['#programs'] = $dba->query('SELECT * FROM programs')->fetchAll();
    if(Specific::Teacher() == true){
        $teachers = $dba->query('SELECT course_id FROM teacher WHERE user_id = '.$TEMP['#user']['id'].' AND period_id = '.$period_id)->fetchAll(false);
        $query .= (!empty($keyword) ? ' AND' : ' WHERE').' id IN ('.implode(',', $teachers).')';
    }
    $courses = $dba->query('SELECT * FROM courses'.$query.' LIMIT ? OFFSET ?', 10, 1)->fetchAll();
    $deliver['total_pages'] = $dba->totalPages;
    if (!empty($courses)) {
        foreach ($courses as $course) {
            $parameters = json_decode($course['parameters']);
            $preknowledge = explode(',', $course['preknowledge']);
            $assignments = $dba->query('SELECT period_id FROM teacher WHERE course_id = '.$course['id'])->fetchAll(false);
            $assignments = array_unique($assignments);

            if(Specific::Teacher() == true){
                $teachers = $dba->query('SELECT names FROM users u WHERE (SELECT user_id FROM teacher WHERE user_id = u.id AND course_id = '.$course['id'].' AND period_id = '.$period_id.') = id')->fetchAll(false);
                $enrolled = $dba->query('SELECT COUNT(*) FROM enrolled WHERE course_id = '.$course['id'])->fetchArray();
                if(count($teachers) == 2){
                    $teachers = "{$teachers[0]} {$TEMP['#word']['and']} {$teachers[1]}";
                } else if(count($teachers) > 2){
                    $end = end($teachers);
                    array_pop($teachers);
                    $teachers = implode(', ', $teachers)." {$TEMP['#word']['and']} $end";
                } else {
                    $teachers = $teachers[0];
                }
                $TEMP['!teacher'] = $teachers;
            }

            $TEMP['!id'] = $course['id'];
            $TEMP['!code'] = $course['code'];
            $TEMP['!name'] = $course['name'];
            $TEMP['!assignments'] = count($assignments);
            $TEMP['!preknowledge'] = !empty($course['preknowledge']) ? count($preknowledge) : 0;
            $TEMP['!parameters'] = count($parameters);
            $TEMP['!program'] = $course['plan_id'] == 0 ? $TEMP['#word']['pending'] : $dba->query('SELECT name FROM programs p WHERE (SELECT program_id FROM plan WHERE program_id ='.$course['plan_id'].' AND program_id = p.id) = id')->fetchArray();
            $TEMP['!semester'] = $course['semester'];
            $TEMP['!credits'] = $course['credits'];
            $TEMP['!quota'] = ($course['quota']-$enrolled). "/{$course['quota']}";
            $TEMP['!type'] = $TEMP['#word'][$course['type']];
            $TEMP['!schedule'] = $TEMP['#word'][$course['schedule']];
            $TEMP['!time'] = Specific::DateFormat($course['time']);
            $html .= Specific::Maket('courses/includes/courses-list');
        }
        Specific::DestroyMaket();
        $deliver['status'] = 200;
    } else {
        if(!empty($keyword)){
            $TEMP['keyword'] = $keyword;
            $html .= Specific::Maket('not-found/result-for');
        } else {
            $html .= Specific::Maket('not-found/courses');
        }
    }
    $deliver['html'] = $html;
} else if($one == 'table-courses'){
    $page = Specific::Filter($_POST['page_id']);
    if(!empty($page) && is_numeric($page) && isset($page) && $page > 0){
        $html = "";
        $query = "";
        $keyword = Specific::Filter($_POST['keyword']);
        $user_id = Specific::Filter($_POST['user_id']);
        $period_id = Specific::Filter($_POST['period_id']);
        if(!empty($keyword)){
            $query .= " WHERE name LIKE '%$keyword%'";
        }
        $TEMP['#programs'] = $dba->query('SELECT * FROM programs')->fetchAll();
        if(Specific::Teacher() == true){
            $teachers = $dba->query('SELECT course_id FROM teacher WHERE user_id = '.$TEMP['#user']['id'].' AND period_id = '.$period_id)->fetchAll(false);
            $query .= (!empty($keyword) ? ' AND' : ' WHERE').' id IN ('.implode(',', $teachers).')';
        }
        $courses = $dba->query('SELECT * FROM courses'.$query.' LIMIT ? OFFSET ?', 10, $page)->fetchAll();
        if (!empty($courses)) {
            foreach ($courses as $course) {
                $parameters = json_decode($course['parameters']);
                $preknowledge = explode(',', $course['preknowledge']);
                $assignments = $dba->query('SELECT period_id FROM teacher WHERE course_id = '.$course['id'])->fetchAll(false);
                $assignments = array_unique($assignments);
                if(Specific::Teacher() == true){
                    $teachers = $dba->query('SELECT names FROM users u WHERE (SELECT user_id FROM teacher WHERE user_id = u.id AND course_id = '.$course['id'].' AND period_id = '.$period_id.') = id')->fetchAll(false);
                    $enrolled = $dba->query('SELECT COUNT(*) FROM enrolled WHERE course_id = '.$course['id'])->fetchArray();
                    if(count($teachers) == 2){
                        $teachers = "{$teachers[0]} {$TEMP['#word']['and']} {$teachers[1]}";
                    } else if(count($teachers) > 2){
                        $end = end($teachers);
                        array_pop($teachers);
                        $teachers = implode(', ', $teachers)." {$TEMP['#word']['and']} $end";
                    } else {
                        $teachers = $teachers[0];
                    }
                    $TEMP['!teacher'] = $teachers;
                }
                $TEMP['!id'] = $course['id'];
                $TEMP['!code'] = $course['code'];
                $TEMP['!name'] = $course['name'];
                $TEMP['!assignments'] = count($assignments);
                $TEMP['!preknowledge'] = !empty($course['preknowledge']) ? count($preknowledge) : 0;
                $TEMP['!parameters'] = count($parameters);
                $TEMP['!program'] = $course['plan_id'] == 0 ? $TEMP['#word']['pending'] : $dba->query('SELECT name FROM programs p WHERE (SELECT program_id FROM plan WHERE program_id ='.$course['plan_id'].' AND program_id = p.id) = id')->fetchArray();
                $TEMP['!semester'] = $course['semester'];
                $TEMP['!credits'] = $course['credits'];
                $TEMP['!quota'] = ($course['quota']-$enrolled). "/{$course['quota']}";
                $TEMP['!type'] = $TEMP['#word'][$course['type']];
                $TEMP['!schedule'] = $TEMP['#word'][$course['schedule']];
                $TEMP['!time'] = Specific::DateFormat($course['time']);
                $html .= Specific::Maket('courses/includes/courses-list');
            }
            Specific::DestroyMaket();
            $deliver['status'] = 200;
        }
        $deliver['status'] = 200;
        $deliver['html'] = $html;
    }
} else if($one == 'search-users'){
    $deliver['status'] = 400;
    if (!empty($_POST['keyword'])) {
        $html = '';
        $keyword = Specific::Filter($_POST['keyword']);
        $type = Specific::Filter($_POST['type']);
        if($type == 'notes' && Specific::Teacher() == true){
            $my_courses = $dba->query('SELECT course_id FROM teacher WHERE user_id = '.$TEMP['#user']['id'])->fetchAll(false);
            $users = $dba->query('SELECT * FROM users u WHERE id != '.$TEMP['#user']['id'].' AND role = "student" AND (names LIKE "%'.$keyword.'%" OR surnames LIKE "%'.$keyword.'%") AND (SELECT user_id FROM enrolled WHERE user_id = u.id AND type = "course" AND course_id IN ('.implode(',', $my_courses).')) = id LIMIT 10')->fetchAll();
        } else {
            $users = $dba->query('SELECT * FROM users WHERE id != '.$TEMP['#user']['id'].' AND role = "student" AND (names LIKE "%'.$keyword.'%" OR surnames LIKE "%'.$keyword.'%") LIMIT 10')->fetchAll();
        }
        
        if (!empty($users)) {
            foreach ($users as $user) {
                $full_name = "{$user['names']} {$user['surnames']}";
                $html .= "<a class='display-flex border-left border-right border-bottom border-grey padding-10 background-hover' href='".Specific::Url("$type?keyword={$full_name}&user={$user['id']}")."' target='_self'><div class='margin-right-auto color-black'>".$full_name.'</div></a>';
            }
            $deliver = array(
                'status' => 200,
                'html' => $html
            );
        }
    }
} else if($one == 'get-citems'){
    $id = Specific::Filter($_POST['id']);
    $type = Specific::Filter($_POST['type']);
    $pos = Specific::Filter($_POST['pos']);
    if(isset($id) && is_numeric($id)){
        if($type == 'notes'){
            $note = $dba->query('SELECT * FROM notes WHERE id = '.$id)->fetchArray();
            $id = $note['course_id'];
        }
        if(Specific::Admin() == true || Specific::Academic() == true || Specific::Teacher() == true){
            if(Specific::Admin() == true || Specific::Academic() == true){
                $items = $dba->query('SELECT name, preknowledge, code, semester, credits, quota, type, schedule FROM courses WHERE id = '.$id)->fetchArray();
                $teachers = $dba->query('SELECT * FROM teacher WHERE course_id = '.$id)->fetchAll();
                foreach ($teachers as $teacher) {
                    $names = $dba->query('SELECT names FROM users WHERE id = '.$teacher['user_id'])->fetchArray();
                    $items['teachers'][] = array('id' => $teacher['user_id'], 'name' => $names);   
                }
                if(!empty($items['preknowledge'])){
                    $preknowledge = array();
                    $preknowledges = explode(',', $items['preknowledge']);
                    foreach ($preknowledges as $prek) {
                        $name = $dba->query('SELECT name FROM courses WHERE id = '.$prek)->fetchArray();
                        $preknowledge[] = array('id' => $prek, 'name' => $name);   
                    }
                    $items['preknowledge'] = $preknowledge;
                } 
            } else {
                $items = array();
            }
            $course = $dba->query('SELECT * FROM courses WHERE id = '.$id)->fetchArray();
            if($type == 'notes'){
                $items['parameters'] = json_decode($course['parameters'], true);
                $items['notes'] = json_decode($note['notes'], true);
                if(isset($pos) && is_numeric($pos)){
                    $items['parameters'] = json_decode($course['parameters'], true)[$pos];
                    $notes = json_decode($note['notes'], true)[$pos];
                    $items['notes'] = $notes;
                }
            } else if(Specific::Teacher() == true){
                $note_mode = $dba->query('SELECT note_mode FROM plan WHERE id = '.$course['plan_id'])->fetchArray();
                if(isset($note_mode)){
                    $deliver['mode'] = $note_mode;
                    if(!empty($course['parameters'])){
                       $items = json_decode($course['parameters'], true); 
                    } else {
                        $example = array(
                            array(
                                array('name' => "{$TEMP['#word']['example']} 1", 'percent' => 100)
                            ),
                            array(
                                array('name' => "{$TEMP['#word']['example']} 2", 'percent' => 100)
                            ),
                            array(
                                array('name' => "{$TEMP['#word']['example']} 3", 'percent' => 100)
                            )
                        );
                        if($note_mode == '100'){
                            $example = array(
                                    array('name' => "{$TEMP['#word']['example']} 1", 'percent' => 100)
                                );
                        }
                        $example = json_encode($example);
                        $items = json_decode($example, true);
                    }
                }
            }
        }
        if (!empty($items)) {
            $deliver['status'] = 200;
            $deliver['items'] = $items;
        }
    }
} else if($one == 'this-courses'){
    $deliver['status'] = 400;
    $modes = array('100', '30-30-40');
    $emptys = array();
    $error = array();

    $id = Specific::Filter($_POST['id']);
    $params = Specific::Filter($_POST['params']);
    $mode = Specific::Filter($_POST['mode']);
    $params = html_entity_decode($params);
    $params = json_decode($params);
    $arrayj = array();
    if($mode == '100'){
        $sum = 0;
        foreach ($params as $key => $param) {
            $sum += $param[1];
            if(empty($param[0])){
                $emptys[] = array('id' => $key, 'class' => 'param_name_');
            }
            if(empty($param[1])){
                $emptys[] = array('id' => $key, 'class' => 'param_percent_');
            }
            if(($sum < 100 || $sum > 100) && end(array_keys($params)) == $key){
                $error = 'params';
            }
            $arrayj[$key] = array('name' => $param[0], 'percent' => $param[1]);
        }
    } else {
        $sum = array(0, 0, 0);
        foreach ($params as $keyp => $courts) {
            foreach ($courts as $keyc => $court) {
                $sum[$keyp] += $court[1];
                if(empty($court[0])){
                    $emptys[] = array('id' => $keyc, 'class' => 'param_name_');
                }
                if(empty($court[1])){
                    $emptys[] = array('id' => $keyc, 'class' => 'param_percent_');
                }
                if(($sum[$keyp] < 100 || $sum[$keyp] > 100) && end(array_keys($courts)) == $keyc){
                    $error[] = 'params';
                }
                $arrayj[$keyp][$keyc] = array('name' => $court[0], 'percent' => $court[1]);
            }
        }
    }
    if(empty($emptys)){
        if(!in_array($mode, $modes)){
            $error = 'other';
        }
        if (empty($error)) {
            if($dba->query('UPDATE courses SET parameters = ? WHERE id = '.$id, json_encode($arrayj))->returnStatus()){
                $deliver['status'] = 200;
            }
        } else {
            $deliver = array(
                'status' => 400,
                'error' => $error
            );
        }
    } else {
        $deliver = array(
            'status' => 400,
            'emptys' => $emptys
        );
    } 
} else if($one == 'this-notes'){
    $deliver['status'] = 400;
    $emptys = array();
    $errors = array();

    $id = Specific::Filter($_POST['id']);
    $params = Specific::Filter($_POST['params']);
    $pos = Specific::Filter($_POST['pos']);
    $params = html_entity_decode($params);
    $params = json_decode($params);
    if(!empty($id) && is_numeric($id)){
        if(!isset($pos)){
            $params = json_encode($params);
            $params = json_decode($params);
        }
        foreach ($params as $key => $note) {
            if(!isset($note)){
                $emptys[] = Array('id' => $key, 'class' => 'param_note_');
            }
            if($note > $TEMP['#nm'] || $note < 0){
                $errors[] = Array('id' => $key, 'class' => 'param_note_');
            }
        }
        if(empty($emptys)){
            $note = $dba->query('SELECT * FROM notes WHERE id = '.$id)->fetchArray();
            $period = $dba->query('SELECT * FROM periods p WHERE (SELECT period_id FROM enrolled WHERE user_id = '.$note['user_id'].' AND course_id = '.$note['course_id'].' AND type = "course" AND period_id = p.id) = id')->fetchArray();
            if(time() < $period['final'] || Specific::Admin() == true || Specific::Academic() == true){
                $court = array('first', 'second', 'third')[$pos];
                $TEMP['!authorization'] = $dba->query('SELECT court FROM authorization WHERE status = "authorized" AND period_id = '.$period['id'].' AND course_id = '.$note['course_id'])->fetchAll(false);
                if(in_array($court, $TEMP['!authorization']) || Specific::Admin() == true || Specific::Academic() == true){
                    if (empty($errors)) {
                        $notes = $params;
                        if(isset($pos)){
                            $notes = $note['notes'];
                            $notes = json_decode($notes, true);
                            $notes[$pos] = $params;
                        }
                        if($dba->query('UPDATE notes SET notes = ? WHERE id = '.$id, json_encode($notes))->returnStatus()){
                            $deliver['status'] = 200;
                            Specific::SendNotification(array(
                                'from_id' => $TEMP['#user']['id'],
                                'to_id' => $note['user_id'],
                                'course_id' => $note['user_id'],
                                'type' => "'note'",
                                'time' => time()
                            ));
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
                        'error' => $TEMP['#word']['you_authorized_upload_grades_course']
                    );
                }
            } else {
                $deliver = array(
                    'status' => 400,
                    'error' => $TEMP['#word']['deadline_uploading_notes_already_passed']
                );
            }
        } else {
            $deliver = array(
                'status' => 400,
                'emptys' => $emptys
            );
        } 
    } else {
        $deliver = array(
            'status' => 400,
            'error' => $TEMP['#word']['error']
        );
    }
} else if($one == 'search-authorizations') {
    $keyword = Specific::Filter($_POST['keyword']);
        $html = '';
        $query = '';
        if(!empty($keyword)){
            $query .= " a WHERE (SELECT id FROM teacher WHERE user_id = ".$TEMP['#user']['id']." AND id = a.teacher_id) = teacher_id AND (id LIKE '%$keyword%' OR (SELECT id FROM users WHERE (dni LIKE '%$keyword%' OR names LIKE '%$keyword%' OR surnames LIKE '%$keyword%') AND id = a.user_id) = user_id OR (SELECT id FROM courses WHERE (code LIKE '%$keyword%' OR name LIKE '%$keyword%') AND id = a.course_id) = course_id)";
        }
        $authorizations = $dba->query('SELECT * FROM authorization'.$query.' LIMIT ? OFFSET ?', 10, 1)->fetchAll();
        $deliver['total_pages'] = $dba->totalPages;
        if(!empty($authorizations)){
            foreach ($authorizations as $auth) {
                $TEMP['!id'] = $auth['id'];
                $TEMP['!academic'] = $auth['status'] == 'pending' ? $TEMP['#word']['pending'] : $dba->query('SELECT names FROM users WHERE id = '.$auth['user_id'])->fetchArray();
                $TEMP['!teacher'] = $dba->query('SELECT names FROM users u WHERE (SELECT user_id FROM teacher WHERE id = '.$auth['teacher_id'].' AND user_id = u.id) = id')->fetchArray();
                $TEMP['!course'] = $dba->query('SELECT name FROM courses WHERE id = '.$auth['course_id'])->fetchArray();
                $TEMP['!court'] = $TEMP['#word'][$auth['court']];
                $TEMP['!expires'] = $auth['expires'] == 0 ? $TEMP['#word']['pending'] : Specific::DateFormat($auth['expires']);
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
            $query .= " a WHERE (SELECT id FROM teacher WHERE user_id = ".$TEMP['#user']['id']." AND id = a.teacher_id) = teacher_id AND (id LIKE '%$keyword%' OR (SELECT id FROM users WHERE (dni LIKE '%$keyword%' OR names LIKE '%$keyword%' OR surnames LIKE '%$keyword%') AND id = a.user_id) = user_id OR (SELECT id FROM courses WHERE (code LIKE '%$keyword%' OR name LIKE '%$keyword%') AND id = a.course_id) = course_id)";
        }
        $authorizations = $dba->query('SELECT * FROM authorization'.$query.' LIMIT ? OFFSET ?', 10, $page)->fetchAll();
        if (!empty($authorizations)) {
            foreach ($authorizations as $auth) {
                $TEMP['!id'] = $auth['id'];
                $TEMP['!academic'] = $auth['status'] == 'pending' ? $TEMP['#word']['pending'] : $dba->query('SELECT names FROM users WHERE id = '.$auth['user_id'])->fetchArray();
                $TEMP['!teacher'] = $dba->query('SELECT names FROM users u WHERE (SELECT user_id FROM teacher WHERE id = '.$auth['teacher_id'].' AND user_id = u.id) = id')->fetchArray();
                $TEMP['!course'] = $dba->query('SELECT name FROM courses WHERE id = '.$auth['course_id'])->fetchArray();
                $TEMP['!court'] = $TEMP['#word'][$auth['court']];
                $TEMP['!expires'] = $auth['expires'] == 0 ? $TEMP['#word']['pending'] : Specific::DateFormat($auth['expires']);
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
} else if($one == 'this-authorizations'){
    $deliver['status'] = 400;
    $courts = array('first', 'second', 'third');
    $emptys = array();
    $errors = array();

    $id = Specific::Filter($_POST['id']);
    $description = Specific::Filter($_POST['description']);
    $course_id = Specific::Filter($_POST['course_id']);
    $court = Specific::Filter($_POST['court']);
    $type = Specific::Filter($_POST['type']);

    if(empty($course_id)){
        $emptys[] = 'course_id';
    }
    if(empty($description)){
        $emptys[] = 'description';
    }
    if(empty($court)){
        $emptys[] = 'court';
    }

    if(empty($emptys)){
        $note_mode = $dba->query('SELECT note_mode FROM plan WHERE (SELECT plan_id FROM courses WHERE id = '.$course_id.') = id')->fetchArray();
        $teacher_id = $dba->query('SELECT id FROM teacher WHERE user_id = '.$TEMP['#user']['id'].' AND course_id = '.$course_id)->fetchArray();
        $authorization = $dba->query('SELECT status, COUNT(*) AS count FROM authorization WHERE teacher_id = '.$teacher_id.' AND course_id = '.$course_id.' AND court = "'.$court.'" AND status = "pending"')->fetchArray();
        
        if(($type == 'add' && $authorization['count'] == 0) || ($type == 'edit' && $authorization['status'] == 'pending')){
            if(!in_array($course_id, $dba->query('SELECT id FROM courses c WHERE (SELECT course_id FROM teacher WHERE user_id = '.$TEMP['#user']['id'].' AND course_id = c.id) = id')->fetchAll(false))){
                $errors[] = 'course_id';
            }
            if($note_mode == '100'){
                $court = 'only';
            } else if(!in_array($court, $courts)){
                $errors[] = 'court';
            }
            if (empty($errors)) {
                $period = $dba->query('SELECT *, COUNT(*) AS count FROM periods WHERE status = "enabled" AND start < '.time().' AND final > '.time())->fetchArray();
                if($period['count'] > 0){
                    if($dba->query('SELECT COUNT(*) FROM authorization WHERE status <> "denied" AND course_id = "'.$course_id.'" AND court = "'.$court.'" AND period_id = '.$period['id'])->fetchArray() == 0){
                        if($type == 'add'){
                            if($dba->query('INSERT INTO authorization (user_id, teacher_id, course_id, description, court, expires, status, `time`) VALUES (0, '.$teacher_id.', '.$course_id.', "'.$description.'", "'.$court.'", 0, "pending", '.time().')')->returnStatus()){
                                $deliver['status'] = 200;
                                $users = $dba->query('SELECT id FROM users WHERE role = "admin" AND role = "academic"')->fetchAll();
                                foreach ($users as $user) {
                                    Specific::SendNotification(array(
                                        'from_id' => $TEMP['#user']['id'],
                                        'to_id' => $user['id'],
                                        'course_id' => $course_id,
                                        'type' => "'authorize'",
                                        'time' => time()
                                    ));
                                }
                            } else {
                                $deliver = array(
                                    'status' => 400,
                                    'error' => $TEMP['#word']['error']
                                );
                            }
                        } else if(isset($id) && is_numeric($id)){
                            if($dba->query('UPDATE authorization SET description = ?, court = ? WHERE id = '.$id, $description, $court)->returnStatus()){
                                $deliver['status'] = 200;
                            }
                        }
                    } else {
                        $deliver = array(
                            'status' => 400,
                            'error' => "{$TEMP['#word']['there_already_authorization_court_period']}: {$period['name']}"
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
                'error' => $TEMP['#word']['must_wait_previous_authorization_respond']
            );
        }
    } else {
        $deliver = array(
            'status' => 400,
            'emptys' => $emptys
        );
    }
} else if($one == 'cut-select') {
    $deliver['status'] = 400;
    $id = Specific::Filter($_POST['id']);
    if(isset($id) && is_numeric($id)){
        $note_mode = $dba->query('SELECT note_mode FROM plan WHERE (SELECT plan_id FROM courses WHERE id = '.$id.') = id')->fetchArray();
        if(!empty($note_mode)){
            $deliver = array(
                'status' => 200,
                'note_mode' => $note_mode
            );
        }
    }
} else if($one == 'get-aitems'){
    $id = Specific::Filter($_POST['id']);
    if(isset($id) && is_numeric($id)){
        $items = $dba->query('SELECT course_id, description, court, status FROM authorization WHERE id = '.$id)->fetchArray();
        if (!empty($items)) {
            $deliver = array(
                'status' => 200,
                'items' => $items
            );
        }
    }
}
?>