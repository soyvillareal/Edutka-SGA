<?php
if ($TEMP['#loggedin'] === false && (Specific::Academic() === false || Specific::Teacher() === false)) {
    $deliver = array(
        'status' => 400,
        'error' => $TEMP['#word']['error']
    );
    echo json_encode($deliver);
    exit();
}

if($one == 'search-courses') {
    $keyword = Specific::Filter($_POST['keyword']);
        $html = '';
        $query = '';
        if(!empty($keyword)){
            $query .= " WHERE name LIKE '%$keyword%'";
        }
        $TEMP['#periods'] = $dba->query('SELECT * FROM periods')->fetchAll();
        $TEMP['#programs'] = $dba->query('SELECT * FROM programs')->fetchAll();
        if(Specific::Teacher() == true){
            $teachers = $dba->query('SELECT course_id FROM teacher WHERE user_id = '.$TEMP['#user']['id'])->fetchAll(false);
            $query .= (!empty($keyword) ? ' AND' : ' WHERE').' id IN ('.implode(',', $teachers).')';
        }
        $courses = $dba->query('SELECT * FROM courses'.$query.' LIMIT ? OFFSET ?', 10, 1)->fetchAll();
        $deliver['total_pages'] = $dba->totalPages;
        if (!empty($courses)) {
            foreach ($courses as $course) {
                $parameters = json_decode($course['parameters']);
                $teachers = $dba->query('SELECT names FROM users u WHERE (SELECT user_id FROM teacher WHERE user_id = u.id AND course_id = '.$course['id'].') = id')->fetchAll(false);
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
                $TEMP['!id'] = $course['id'];
                $TEMP['!code'] = $course['code'];
                $TEMP['!name'] = $course['name'];
                $TEMP['!parameters'] = count($parameters);
                $TEMP['!program'] = $dba->query('SELECT name FROM programs WHERE id = '.$course['program_id'])->fetchArray();
                $TEMP['!period'] = $dba->query('SELECT name FROM periods WHERE id = '.$course['period_id'])->fetchArray();
                $TEMP['!teacher'] = $teachers;
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
        $TEMP['#periods'] = $dba->query('SELECT * FROM periods')->fetchAll();
        $TEMP['#programs'] = $dba->query('SELECT * FROM programs')->fetchAll();
        if(Specific::Teacher() == true){
            $teachers = $dba->query('SELECT course_id FROM teacher WHERE user_id = '.$TEMP['#user']['id'])->fetchAll(false);
            $query .= (!empty($keyword) ? ' AND' : ' WHERE').' id IN ('.implode(',', $teachers).')';
        }
        $courses = $dba->query('SELECT * FROM courses'.$query.' LIMIT ? OFFSET ?', 10, $page)->fetchAll();
        if (!empty($courses)) {
            foreach ($courses as $course) {
                $parameters = json_decode($course['parameters']);
                $teachers = $dba->query('SELECT names FROM users u WHERE (SELECT user_id FROM teacher WHERE user_id = u.id AND course_id = '.$course['id'].') = id')->fetchAll(false);
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
                $TEMP['!id'] = $course['id'];
                $TEMP['!code'] = $course['code'];
                $TEMP['!name'] = $course['name'];
                $TEMP['!parameters'] = count($parameters);
                $TEMP['!program'] = $dba->query('SELECT name FROM programs WHERE id = '.$course['program_id'])->fetchArray();
                $TEMP['!period'] = $dba->query('SELECT name FROM periods WHERE id = '.$course['period_id'])->fetchArray();
                $TEMP['!teacher'] = $teachers;
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
            foreach ($my_courses as $course) {
                $users = $dba->query('SELECT * FROM users u WHERE id != '.$TEMP['#user']['id'].' AND (names LIKE "%'.$keyword.'%" OR surnames LIKE "%'.$keyword.'%") AND (SELECT user_id FROM enrolled WHERE user_id = u.id AND type = "course" AND course_id = '.$course.') = id LIMIT 10')->fetchAll();
            }
        } else {
            $users = $dba->query('SELECT * FROM users WHERE id != '.$TEMP['#user']['id'].' AND (names LIKE "%'.$keyword.'%" OR surnames LIKE "%'.$keyword.'%") LIMIT 10')->fetchAll();
        }
        
        if (!empty($users)) {
            foreach ($users as $user) {
                $html .= "<a class='display-flex border-left border-right border-bottom border-grey padding-10 background-hover' href='".Specific::Url("$type?keyword=$keyword&user={$user['id']}")."' target='_self'><div class='margin-right-auto color-black'>".$user['names'].' '.$user['surnames'].'</div></a>';
            }
            $deliver = array(
                'status' => 200,
                'html' => $html
            );
        }
    }
} else if($one == 'upload-notes'){
    $deliver['status'] = 400;
    $programs = $dba->query('SELECT id FROM programs')->fetchAll(false);
    $periods = $dba->query('SELECT id FROM periods')->fetchAll(false);
    $semesters  = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10);
    $credits  = array(1, 2, 3, 4);
    $types  = array('practice', 'theoretical');
    $schedules  = array('daytime', 'nightly');
    $emptys = array();
    $errors = array();


    $type = Specific::Filter($_POST['type']);
    $id = Specific::Filter($_POST['id']);
    $code = Specific::Filter($_POST['code']);
    $name = Specific::Filter($_POST['name']);
    $program_id = Specific::Filter($_POST['program_id']);
    $period_id = Specific::Filter($_POST['period_id']);
    $teachers = Specific::Filter($_POST['teacher']);
    $semester = Specific::Filter($_POST['semester']);
    $credit = Specific::Filter($_POST['credits']);
    $quota = Specific::Filter($_POST['quota']);
    $typec = Specific::Filter($_POST['typec']);
    $schedule = Specific::Filter($_POST['schedule']);

    if(empty($code)){
        $emptys[] = 'code';
    }
    if(empty($name)){
        $emptys[] = 'name';
    }
    if(empty($program_id)){
        $emptys[] = 'program_id';
    }
    if(empty($period_id)){
        $emptys[] = 'period_id';
    }
    if(empty($teachers)){
        $emptys[] = 'teachers';
    }
    if(empty($semester)){
        $emptys[] = 'semester';
    }
    if(empty($credit)){
        $emptys[] = 'credits';
    }
    if(empty($quota)){
        $emptys[] = 'quota';
    }
    if(empty($typec)){
        $emptys[] = 'type';
    }
    if(empty($schedule)){
        $emptys[] = 'schedule';
    }

    if(empty($emptys)){
        if(!in_array($program_id, array_values($programs))){
            $errors[] = 'program_id';
        }
        if(!in_array($period_id, array_values($periods))){
            $errors[] = 'period_id';
        }
        if(!in_array($semester, $semesters)){
            $errors[] = 'semester';
        }
        if(!in_array($credit, $credits)){
            $errors[] = 'credits';
        }
        if(!in_array($typec, $types)){
            $errors[] = 'type';
        }
        if(!in_array($schedule, $schedules)){
            $errors[] = 'schedule';
        }
        if (empty($errors)) {
            $teachers = explode(',', $teachers);
            if($type == 'add'){
                $course_id = $dba->query('INSERT INTO courses (code, name, program_id, period_id, semester, credits, quota, type, schedule, `time`) VALUES ("'.$code.'", '.$name.', '.$program_id.','.$period_id.','.$semester.','.$credit.','.$quota.',"'.$typec.'","'.$schedule.'",'.time().')')->insertId();
                if(isset($course_id)){
                    foreach ($teachers as $teacher_id) {
                        if($teacher_id == end($teachers) && $dba->query('INSERT INTO teacher (user_id, course_id, `time`) VALUES ('.$teacher_id.','.$course_id.','.time().')')->returnStatus()){
                             $deliver['status'] = 200;
                        }
                    }
                }
            } else if(isset($id) && is_numeric($id)){
                if($dba->query('UPDATE courses SET code = ?, name = ?, program_id = ?, period_id = ?, semester = ?, credits = ?, quota = ?, type = ?, schedule = ? WHERE id = '.$id, $code, $name, $program_id, $period_id, $semester, $credit, $quota, $typec, $schedule)->returnStatus()){
                    $teachers_all = $dba->query('SELECT user_id FROM teacher WHERE course_id = '.$id)->fetchAll(false);
                    $deleted = array_diff($teachers_all, $teachers);
                    $addf = array_diff($teachers, $teachers_all);
                    $adds = explode(',', implode(',', $addf));
                    if(count($addf) > 0 || count($deleted) > 0){
                        if(count($addf) > 0){
                            for ($i=0; $i < count($adds); $i++) {
                                if($dba->query('INSERT INTO teacher (user_id, course_id, `time`) VALUES ('.$adds[$i].','.$id.','.time().')')->returnStatus()){
                                    $deliver['status'] = 200;
                                }
                            }
                        }
                        if(count($deleted) > 0){
                            if($dba->query('DELETE FROM teacher WHERE user_id IN ('.implode(',', $deleted).')')->returnStatus()){    
                                $deliver['status'] = 200;
                            }
                        }
                    } else {
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
} else if($one == 'get-citems'){
    $id = Specific::Filter($_POST['id']);
    $type = Specific::Filter($_POST['type']);
    $pos = Specific::Filter($_POST['pos']);
    if(isset($id) && is_numeric($id)){
        if($type == 'notes'){
            $note = $dba->query('SELECT * FROM notes WHERE id = '.$id)->fetchArray();
            $id = $note['course_id'];
        }
        if(Specific::Academic() == true || Specific::Teacher() == true){
            if(Specific::Academic() == true){
                $items = $dba->query('SELECT name, program_id, period_id, code, semester, credits, quota, type, schedule FROM courses WHERE id = '.$id)->fetchArray();
                $teachers = $dba->query('SELECT * FROM teacher WHERE course_id = '.$id)->fetchAll();
                foreach ($teachers as $teacher) {
                    $names = $dba->query('SELECT names FROM users WHERE id = '.$teacher['user_id'])->fetchArray();
                    $items['teachers'][] = array('id' => $teacher['user_id'], 'name' => $names);   
                }

            } else {
                $items = array();
            } 
            $parameters = $dba->query('SELECT parameters FROM courses WHERE id = '.$id)->fetchArray();
            if($type == 'notes'){
                $items['parameters'] = json_decode($parameters, true)[$pos];
                $notes = json_decode($note['notes'], true)[$pos];
                $items['notes'] = json_decode($notes, true);
            } else {
                if(!empty($parameters)){
                   $items = json_decode($parameters, true); 
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
                    $example = json_encode($example);
                    $items = json_decode($example, true);
                }   
            }
        }
        if (!empty($items)) {
            $deliver = array(
                'status' => 200,
                'items' => $items
            );
        }
    }
} else if($one == 'this-courses'){
    $deliver['status'] = 400;
    $emptys = array();
    $errors = array();

    $id = Specific::Filter($_POST['id']);
    $params = Specific::Filter($_POST['params']);
    $params = html_entity_decode($params);
    $params = json_decode($params);
    $sum = 0;
    $arrayj = array();
    foreach ($params as $keyp => $courts) {
        foreach ($courts as $keyc => $court) {
            $sum += $court[1];
            if(empty($court[0])){
                $emptys[] = Array('id' => $keyc, 'class' => 'param_name_');
            }
            if(empty($court[1])){
                $emptys[] = Array('id' => $keyc, 'class' => 'param_percent_');
            }
            if($sum < 100 && end(array_keys($courts)) == $keyc){
                $errors[] = 'params';
            }
            $arrayj[$keyp][$keyc] = array('name' => $court[0], 'percent' => $court[1]);
        }
    }
    $deliver['XD'] = json_encode($arrayj, true);
    if(empty($emptys)){
        if (empty($errors)) {
            if($dba->query('UPDATE courses SET parameters = ? WHERE id = '.$id, json_encode($arrayj))->returnStatus()){
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
} else if($one == 'this-notes'){
    $deliver['status'] = 400;
    $emptys = array();
    $errors = array();

    $id = Specific::Filter($_POST['id']);
    $params = Specific::Filter($_POST['params']);
    $pos = Specific::Filter($_POST['pos']);
    $params = html_entity_decode($params);
    $params = json_decode($params);
    if(!empty($id) && is_numeric($id) && isset($pos) && is_numeric($pos)){
        $params = json_encode($params, true);
        foreach (json_decode($params) as $key => $note) {
            if(empty($note)){
                $emptys[] = Array('id' => $key, 'class' => 'param_note_');
            }
            if($note > 5 || $note < 0){
                $errors[] = Array('id' => $key, 'class' => 'param_note_');
            }
        }
        if(empty($emptys)){
            if (empty($errors)) {
                $notes = $dba->query('SELECT notes FROM notes WHERE id = '.$id)->fetchArray();
                $notes = json_decode($notes, true);
                $notes[$pos] = $params;
                if($dba->query('UPDATE notes SET notes = ? WHERE id = '.$id, json_encode($notes))->returnStatus()){
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
}
?>