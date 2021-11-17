<?php 
if ($TEMP['#loggedin'] === false || Specific::Academic() === false) {
    $deliver = array(
        'status' => 400,
        'error' => $TEMP['#word']['error']
    );
    echo json_encode($deliver);
    exit();
}

if($one == 'this-programs'){
    $deliver['status'] = 400;
    $semesters  = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10);
    $modalities  = array('presential', 'distance', 'virtual');
    $faculties = $dba->query('SELECT id FROM faculty')->fetchAll(false);
    $emptys = array();
    $errors = array();

    $id = Specific::Filter($_POST['id']);
    $name = Specific::Filter($_POST['name']);
    $faculty_id = Specific::Filter($_POST['faculty_id']);
    $title = Specific::Filter($_POST['title']);
    $snies = Specific::Filter($_POST['snies']);
    $level = Specific::Filter($_POST['level']);
    $semester = Specific::Filter($_POST['semesters']);
    $mode = Specific::Filter($_POST['mode']);
    $type = Specific::Filter($_POST['type']);

    if(empty($name)){
        $emptys[] = 'name';
    }
    if(empty($faculty_id)){
        $emptys[] = 'faculty_id';
    }
    if(empty($title)){
        $emptys[] = 'title';
    }
    if(empty($snies)){
        $emptys[] = 'snies';
    }
    if(empty($level)){
        $emptys[] = 'level';
    }
    if(empty($semester)){
        $emptys[] = 'semesters';
    }
    if(empty($mode)){
        $emptys[] = 'mode';
    }

    if(empty($emptys)){
        if(!in_array($semester, $semesters)){
            $errors[] = 'semesters';
        }
        if(!in_array($mode, $modalities)){
            $errors[] = 'mode';
        }
        if(!in_array($faculty_id, array_values($faculties))){
            $errors[] = 'faculty_id';
        }
        if (empty($errors)) {
            if(!empty($type)){
                if($type == 'add'){
                    if($dba->query('INSERT INTO programs (name, faculty_id, title, snies, level, semesters, mode, `time`) VALUES ("'.$name.'", '.$faculty_id.',"'.$title.'",'.$snies.',"'.$level.'",'.$semester.',"'.$mode.'",'.time().')')->returnStatus()){
                        $deliver['status'] = 200;
                    }
                } else if(isset($id) && is_numeric($id)){
                    if($dba->query('UPDATE programs SET name = ?, faculty_id = ?, title= ?, snies = ?, level = ?, semesters = ?, mode = ? WHERE id = '.$id, $name, $faculty_id, $title, $snies, $level, $semester, $mode)->returnStatus()){
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
} else if($one == 'get-pitems'){
    $id = Specific::Filter($_POST['id']);
    if(isset($id) && is_numeric($id)){
        $items = $dba->query('SELECT name, faculty_id, title, snies, level, semesters, mode FROM programs WHERE id = '.$id)->fetchArray();
        if (!empty($items)) {
            $deliver = array(
                'status' => 200,
                'items' => $items
            );
        }
    }
} else if($one == 'delete-program'){
    $deliver['status'] = 400;
    $id = Specific::Filter($_POST['id']);
    if (isset($id) && is_numeric($id)) {
        if($dba->query('DELETE FROM programs WHERE id = '.$id)->returnStatus()){
            $deliver['status'] = 200;
        };
    }
} else if($one == 'search-programs') {
    $keyword = Specific::Filter($_POST['keyword']);
        $html = '';
        $query = '';
        if(!empty($keyword)){
            $query .= " WHERE name LIKE '%$keyword%' OR title LIKE '%$keyword%' OR snies LIKE '%$keyword%'";
        }
        $programs = $dba->query('SELECT * FROM programs'.$query.' LIMIT ? OFFSET ?', 10, 1)->fetchAll();
        $deliver['total_pages'] = $dba->totalPages;
        if (!empty($programs)) {
            foreach ($programs as $program) {
                $TEMP['!id'] = $program['id'];
                $TEMP['!name'] = $program['name'];
                $TEMP['!title'] = $program['title'];
                $TEMP['!snies'] = $program['snies'];
                $TEMP['!level'] = $program['level'] == 'pre' ? $TEMP['#word']['undergraduate'] : ($program['level'] == 'tec' ? $TEMP['#word']['technique'] : $TEMP['#word']['technologist']);
                $TEMP['!semesters'] = $program['semesters'];
                $TEMP['!mode'] = $TEMP['#word'][$program['mode']];
                $TEMP['!time'] = Specific::DateFormat($program['time']);
                $html .= Specific::Maket('programs/includes/programs-list');
            }
            Specific::DestroyMaket();
            $deliver['status'] = 200;
        } else {
            if(!empty($keyword)){
                $TEMP['keyword'] = $keyword;
                $html .= Specific::Maket('not-found/result-for');
            } else {
                $html .= Specific::Maket('not-found/programs');
            }
        }
        $deliver['html'] = $html;
} else if($one == 'table-programs'){
    $page = Specific::Filter($_POST['page_id']);
    if(!empty($page) && is_numeric($page) && isset($page) && $page > 0){
        $html = "";
        $query = '';
        $keyword = Specific::Filter($_POST['keyword']);
        if(!empty($keyword)){
            $query .= " WHERE name LIKE '%$keyword%' OR title LIKE '%$keyword%' OR snies LIKE '%$keyword%'";
        }
        $programs = $dba->query('SELECT * FROM programs'.$query.' LIMIT ? OFFSET ?', 10, $page)->fetchAll();
        if (!empty($programs)) {
            foreach ($programs as $program) {
                $TEMP['!id'] = $program['id'];
                $TEMP['!name'] = $program['name'];
                $TEMP['!title'] = $program['title'];
                $TEMP['!snies'] = $program['snies'];
                $TEMP['!level'] = $program['level'] == 'pre' ? $TEMP['#word']['undergraduate'] : ($program['level'] == 'tec' ? $TEMP['#word']['technique'] : $TEMP['#word']['technologist']);
                $TEMP['!semesters'] = $program['semesters'];
                $TEMP['!mode'] = $TEMP['#word'][$program['mode']];
                $TEMP['!time'] = Specific::DateFormat($program['time']);
                $html .= Specific::Maket('programs/includes/programs-list');
            }
            Specific::DestroyMaket();
            $deliver['status'] = 200;
        }
        $deliver['status'] = 200;
        $deliver['html'] = $html;
    }
} else if($one == 'this-courses'){
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
    $preknowledge = Specific::Filter($_POST['preknowledge']);
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
        if(!empty($preknowledge)){
            $preknowledges = explode(',', $preknowledge);
            $prektrues = array();
            foreach ($preknowledges as $prek) {
                if($dba->query('SELECT COUNT(*) FROM courses WHERE id = '.$prek)->fetchArray() > 0){
                    $prektrues[] = true;
                } else {
                    $prektrues[] = false;
                }
            }
            if(in_array(false, $prektrues)){
                $errors[] = 'preknowledge';
            }
        }
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
            if(!empty($type)){
                if($type == 'add'){
                    $course_id = $dba->query('INSERT INTO courses (code, name, preknowledge, program_id, period_id, semester, credits, quota, type, schedule, `time`) VALUES ("'.$code.'", "'.$name.'", "'.$preknowledge.'", '.$program_id.','.$period_id.', '.$semester.', '.$credit.', '.$quota.', "'.$typec.'", "'.$schedule.'",'.time().')')->insertId();
                    if(isset($course_id)){
                        foreach ($teachers as $teacher_id) {
                            if($teacher_id == end($teachers) && $dba->query('INSERT INTO teacher (user_id, course_id, `time`) VALUES ('.$teacher_id.','.$course_id.','.time().')')->returnStatus()){
                                 $deliver['status'] = 200;
                            }
                        }
                    }
                } else if(isset($id) && is_numeric($id)){
                    if($dba->query('UPDATE courses SET code = ?, name = ?, preknowledge = ?, program_id = ?, period_id = ?, semester = ?, credits = ?, quota = ?, type = ?, schedule = ? WHERE id = '.$id, $code, $name, $preknowledge, $program_id, $period_id, $semester, $credit, $quota, $typec, $schedule)->returnStatus()){
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
} else if($one == 'delete-course'){
    $deliver['status'] = 400;
    $id = Specific::Filter($_POST['id']);
    if (isset($id) && is_numeric($id)) {
        $enrolled_exists = $dba->query('SELECT COUNT(*) FROM enrolled WHERE course_id = '.$id)->fetchArray();
        if($enrolled_exists == 0){
            if($dba->query('DELETE FROM courses WHERE id = '.$id)->returnStatus()){
                $deliver['status'] = 200;
            };
        } else {
            $deliver = array(
                'status' => 400,
                'error' => $TEMP['#word']['you_cannot_delete']
            );
        }
    } else {
        $deliver = array(
            'status' => 400,
            'error' => $TEMP['#word']['error']
        );
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
} else if($one == 'this-faculty'){
    $deliver['status'] = 400;
    $statusa = array('activated', 'deactivated');
    $id = Specific::Filter($_POST['id']);
    $type = Specific::Filter($_POST['type']);
    $name = Specific::Filter($_POST['name']);
    $status = Specific::Filter($_POST['status']);
    $emptys = array();
    $errors = array();

    if(empty($name)){
        $emptys[] = 'name';
    }
    if(empty($status)){
        $emptys[] = 'status';
    }
    if(empty($emptys)){
        if(!in_array($status, array_values($statusa))){
            $errors[] = 'status';
        }
        if (empty($errors)) {
            if(!empty($type)){
                if($type == 'add'){
                    if($dba->query('INSERT INTO faculty (name, status, `time`) VALUES ("'.$name.'","'.$status.'",'.time().')')->returnStatus()){
                        $deliver['status'] = 200;
                    }
                } else if(isset($id) && is_numeric($id)){
                    if($dba->query('UPDATE faculty SET name = "'.$name.'", status = "'.$status.'" WHERE id = '.$id)->returnStatus()){
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
} else if($one == 'get-fitems'){
    $id = Specific::Filter($_POST['id']);
    if(isset($id) && is_numeric($id)){
        $items = $dba->query('SELECT name, status FROM faculty WHERE id = '.$id)->fetchArray();
        if (!empty($items)) {
            $deliver = array(
                'status' => 200,
                'items' => $items
            );
        }
    }
} else if($one == 'search-faculties') {
    $keyword = Specific::Filter($_POST['keyword']);
        $html = '';
        $query = '';
        if(!empty($keyword)){
            $query .= " WHERE name LIKE '%$keyword%'";
        }
        $faculties = $dba->query('SELECT * FROM faculty'.$query.' LIMIT ? OFFSET ?', 10, 1)->fetchAll();
        $deliver['total_pages'] = $dba->totalPages;
        if (!empty($faculties)) {
            foreach ($faculties as $faculty) {
                $TEMP['!id'] = $faculty['id'];
                $TEMP['!name'] = $faculty['name'];
                $TEMP['!status'] = $TEMP['#word'][$faculty['status']];
                $TEMP['!time'] = Specific::DateFormat($faculty['time']);
                $html .= Specific::Maket('faculties/includes/faculties-list');
            }
            Specific::DestroyMaket();
            $deliver['status'] = 200;
        } else {
            if(!empty($keyword)){
                $TEMP['keyword'] = $keyword;
                $html .= Specific::Maket('not-found/result-for');
            } else {
                $html .= Specific::Maket('not-found/faculties');
            }
        }
        $deliver['html'] = $html;
} else if($one == 'delete-faculty'){
    $deliver['status'] = 400;
    $id = Specific::Filter($_POST['id']);
    if (isset($id) && is_numeric($id)) {
        $courses_exists = $dba->query('SELECT COUNT(*) FROM programs WHERE faculty_id = '.$id)->fetchArray();
        if($courses_exists == 0){
            if($dba->query('DELETE FROM faculty WHERE id = '.$id)->returnStatus()){
                $deliver['status'] = 200;
            };
        } else {
            $deliver = array(
                'status' => 400,
                'error' => $TEMP['#word']['you_cannot_delete']
            );
        }
    } else {
        $deliver = array(
            'status' => 400,
            'error' => $TEMP['#word']['error']
        );
    }
} else if($one == 'table-faculties'){
    $page = Specific::Filter($_POST['page_id']);
    if(!empty($page) && is_numeric($page) && isset($page) && $page > 0){
        $html = "";
        $query = '';
        $keyword = Specific::Filter($_POST['keyword']);
        if(!empty($keyword)){
            $query = " WHERE name LIKE '%$keyword%'";
        }
        $faculties = $dba->query('SELECT * FROM faculty'.$query.' LIMIT ? OFFSET ?', 10, $page)->fetchAll();
        if (!empty($faculties)) {
            foreach ($faculties as $faculty) {
                $TEMP['!id'] = $faculty['id'];
                $TEMP['!name'] = $faculty['name'];
                $TEMP['!status'] = $TEMP['#word'][$faculty['status']];
                $TEMP['!time'] = Specific::DateFormat($faculty['time']);
                $html .= Specific::Maket('faculties/includes/faculties-list');
            }
            Specific::DestroyMaket();
            $deliver['status'] = 200;
        }
        $deliver['status'] = 200;
        $deliver['html'] = $html;
    }
} else if($one == 'get-foitems'){
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
} else if($one == 'get-peitems'){
    $id = Specific::Filter($_POST['id']);
    if(isset($id) && is_numeric($id)){
        $items = $dba->query('SELECT name, start, final, status FROM periods WHERE id = '.$id)->fetchArray();
        $name = explode('-', $items['name']);
        unset($items['name']);
        $items['year'] = $name[0];
        $items['period'] = $name[1];
        $items['start'] = date('Y-m-d', $items['start']);
        $items['final'] = date('Y-m-d', $items['final']);
        if (!empty($items)) {
            $deliver = array(
                'status' => 200,
                'items' => $items
            );
        }
    }
} else if($one == 'search-periods') {
    $keyword = Specific::Filter($_POST['keyword']);
        $html = '';
        $query = '';
        if(!empty($keyword)){
            $query .= " WHERE id LIKE '%$keyword%' OR name LIKE '%$keyword%'";
        }
        $periods = $dba->query('SELECT * FROM periods'.$query.' LIMIT ? OFFSET ?', 10, 1)->fetchAll();
        $deliver['total_pages'] = $dba->totalPages;
        if (!empty($periods)) {
            foreach ($periods as $period) {
                $TEMP['!id'] = $period['id'];
                $TEMP['!name'] = $period['name'];
                $TEMP['!start'] = Specific::DateFormat($period['start']);
                $TEMP['!final'] = Specific::DateFormat($period['final']);
                $TEMP['!status'] = $TEMP['#word'][$period['status']];
                $TEMP['!time'] = Specific::DateFormat($period['time']);
                $html .= Specific::Maket('periods/includes/periods-list');
            }
            Specific::DestroyMaket();
            $deliver['status'] = 200;
        } else {
            if(!empty($keyword)){
                $TEMP['keyword'] = $keyword;
                $html .= Specific::Maket('not-found/result-for');
            } else {
                $html .= Specific::Maket('not-found/periods');
            }
        }
        $deliver['html'] = $html;
} else if($one == 'delete-period'){
    $deliver['status'] = 400;
    $id = Specific::Filter($_POST['id']);
    if (isset($id) && is_numeric($id)) {
        $courses_exists = $dba->query('SELECT COUNT(*) FROM courses WHERE period_id = '.$id)->fetchArray();
        if($courses_exists == 0){
            if($dba->query('DELETE FROM periods WHERE id = '.$id)->returnStatus()){
                $deliver['status'] = 200;
            };
        } else {
            $deliver = array(
                'status' => 400,
                'error' => $TEMP['#word']['you_cannot_delete']
            );
        }
    } else {
        $deliver = array(
            'status' => 400,
            'error' => $TEMP['#word']['error']
        );
    }
} else if($one == 'this-periods'){
    $deliver['status'] = 400;
    $statusa  = array('enabled', 'disabled');
    $emptys = array();
    $errors = array();

    $id = Specific::Filter($_POST['id']);
    $period = Specific::Filter($_POST['period']);
    $start = Specific::Filter($_POST['start']);
    $final = Specific::Filter($_POST['final']);
    $status = Specific::Filter($_POST['status']);
    $type = Specific::Filter($_POST['type']);

    if(empty($period)){
        $emptys[] = 'period';
    }
    if(empty($start)){
        $emptys[] = 'start';
    }
    if(empty($final)){
        $emptys[] = 'final';
    }
    if(empty($status)){
        $emptys[] = 'status';
    }

    if(empty($emptys)){
        if(!in_array($status, $statusa)){
            $errors[] = 'status';
        }
        if (empty($errors)) {
            if(!empty($type)){
                $year = explode('-', $start)[0];
                $name = "$year-$period";
                $start = strtotime($start);
                $final = strtotime($final);
                if($type == 'add'){
                    if($dba->query('INSERT INTO periods (name, start, final, status, `time`) VALUES ("'.$name.'", '.$start.', '.$final.',"'.$status.'",'.time().')')->returnStatus()){
                        $deliver['status'] = 200;
                    }
                } else if(isset($id) && is_numeric($id)){
                    if($dba->query('UPDATE periods SET name = ?, start = ?, final = ?, status = ? WHERE id = '.$id, $name, $start, $final, $status)->returnStatus()){
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
} else if($one == 'table-periods'){
    $page = Specific::Filter($_POST['page_id']);
    if(!empty($page) && is_numeric($page) && isset($page) && $page > 0){
        $html = "";
        $query = '';
        $keyword = Specific::Filter($_POST['keyword']);
        if(!empty($keyword)){
            $query .= " WHERE id LIKE '%$keyword%' OR name LIKE '%$keyword%'";
        }
        $periods = $dba->query('SELECT * FROM periods'.$query.' LIMIT ? OFFSET ?', 10, $page)->fetchAll();
        if (!empty($periods)) {
            foreach ($periods as $period) {
                $TEMP['!id'] = $period['id'];
                $TEMP['!name'] = $period['name'];
                $TEMP['!start'] = Specific::DateFormat($period['start']);
                $TEMP['!final'] = Specific::DateFormat($period['final']);
                $TEMP['!status'] = $TEMP['#word'][$period['status']];
                $TEMP['!time'] = Specific::DateFormat($period['time']);
                $html .= Specific::Maket('periods/includes/periods-list');
            }
            Specific::DestroyMaket();
            $deliver['status'] = 200;
        }
        $deliver['status'] = 200;
        $deliver['html'] = $html;
    }
} else if($one == 'search-course') {
    $keyword = Specific::Filter($_POST['keyword']);
    $type = Specific::Filter($_POST['type']);
    $course_id = Specific::Filter($_POST['course_id']);
    $html = '';
    $query = '';
    if(!empty($keyword)){
        $assigned = $dba->query('SELECT course_id FROM assigned')->fetchAll(false);
        $query .= " WHERE (id LIKE '%$keyword%' OR name LIKE '%$keyword%' OR code LIKE '%$keyword%')";
        if($type == 'edit' && !empty($course_id)){
            $query .= " AND id <> $course_id";
        }
        if(!empty($assigned)){
            $query .= " AND id NOT IN (".implode(',', $assigned).")";
        }
    }
    $courses = $dba->query('SELECT * FROM courses'.$query.' LIMIT 5')->fetchAll();
    if (!empty($courses)) {
        foreach ($courses as $course) {
            $html .= "<button class='tipsit-search display-flex btn-noway border-bottom border-grey padding-10 background-hover' data-id='".$course['id']."' data-name='".$course['name']."'>".$course['name']."</button>";
        }
        $deliver['status'] = 200;
    } else {
        $TEMP['keyword'] = $keyword;
        $html .= Specific::Maket('not-found/result-for');
    }
    $deliver['html'] = $html;
} else if($one == 'this-plans'){
    $deliver['status'] = 400;
    $programs = $dba->query('SELECT id FROM programs')->fetchAll(false);
    $modalities  = array('100', '30-30-40');
    $statusa  = array('enabled', 'disabled');
    $emptys = array();
    $errors = array();

    $id = Specific::Filter($_POST['id']);
    $name = Specific::Filter($_POST['name']);
    $resolution = Specific::Filter($_POST['resolution']);
    $date_approved = Specific::Filter($_POST['date_approved']);
    $duration = Specific::Filter($_POST['duration']);
    $credits = Specific::Filter($_POST['credits']);
    $courses = Specific::Filter($_POST['courses']);
    $note_mode = Specific::Filter($_POST['note_mode']);
    $program_id = Specific::Filter($_POST['program_id']);
    $status = Specific::Filter($_POST['status']);
    $type = Specific::Filter($_POST['type']);

    if(empty($name)){
        $emptys[] = 'name';
    }
    if(empty($resolution)){
        $emptys[] = 'resolution';
    }
    if(empty($date_approved)){
        $emptys[] = 'date_approved';
    }
    if(empty($duration)){
        $emptys[] = 'duration';
    }
    if(empty($credits)){
        $emptys[] = 'credits';
    }
    if(empty($courses)){
        $emptys[] = 'courses';
    }
    if(empty($note_mode)){
        $emptys[] = 'note_mode';
    }
    if(empty($program_id)){
        $emptys[] = 'program_id';
    }
    if(empty($status)){
        $emptys[] = 'status';
    }

    if(empty($emptys)){
        $approved = explode('-', $date_approved);
        $date_approved = strtotime($date_approved);
        $couarr = explode(',', $courses);
        $coutrues = array();
        foreach ($couarr as $cou) {
            if($dba->query('SELECT COUNT(*) FROM courses WHERE id = '.$cou)->fetchArray() > 0){
                $coutrues[] = true;
            } else {
                $coutrues[] = false;
            }
        }
        if(in_array(false, $coutrues)){
            $errors[] = 'courses';
        }
        if(!checkdate($approved[1], $approved[2], $approved[0])){
            $errors[] = 'date_approved';
        }
        if(!is_numeric($resolution)){
            $errors[] = 'resolution';
        }
        if(!is_numeric($duration)){
            $errors[] = 'duration';
        }
        if(!is_numeric($credits)){
            $errors[] = 'credits';
        }
        if(!in_array($note_mode, array_values($modalities))){
            $errors[] = 'note_mode';
        }
        if(!in_array($program_id, array_values($programs))){
            $errors[] = 'program_id';
        }
        if(!in_array($status, array_values($statusa))){
            $errors[] = 'status';
        }
        if (empty($errors)) {
            if(!empty($type)){
                $courses = explode(',', $courses);
                if($type == 'add'){
                    $plan_id = $dba->query('INSERT INTO plan (program_id, name, resolution, date_approved, duration, credits, courses, note_mode, status, `time`) VALUES ('.$program_id.', "'.$name.'", '.$resolution.', '.$date_approved.', '.$duration.', '.$credits.', "'.$note_mode.'", "'.$status.'", '.time().')')->insertId();
                    if(isset($plan_id)){
                        foreach ($courses as $course_id) {
                            if($course_id == end($courses) && $dba->query('INSERT INTO assigned (course_id, plan_id, program_id, `time`) VALUES ('.$teacher_id.','.$plan_id.', '.$program_id.','.time().')')->returnStatus()){
                                 $deliver['status'] = 200;
                            }
                        }
                    }
                } else if(isset($id) && is_numeric($id)){
                    if($dba->query('UPDATE plan SET program_id = ?, name = ?, resolution = ?, date_approved = ?, duration= ?, credits = ?, note_mode = ?, status = ? WHERE id = '.$id, $program_id, $name, $resolution, $date_approved, $duration, $credits, $note_mode, $status)->returnStatus()){
                        $courses_all = $dba->query('SELECT course_id FROM assigned WHERE plan_id = '.$id)->fetchAll(false);
                        $deleted = array_diff($courses_all, $courses);
                        $addf = array_diff($courses, $courses_all);
                        $adds = explode(',', implode(',', $addf));
                        if(count($addf) > 0 || count($deleted) > 0){
                            if(count($addf) > 0){
                                for ($i=0; $i < count($adds); $i++) {
                                    if($dba->query('SELECT COUNT(*) FROM assigned WHERE course_id = '.$adds[$i])->fetchArray() == 0){
                                        if($dba->query('INSERT INTO assigned (course_id, plan_id, `time`) VALUES ('.$adds[$i].','.$id.','.time().')')->returnStatus()){
                                            $deliver['status'] = 200;
                                        }
                                    }
                                }
                            }
                            if(count($deleted) > 0){
                                if($dba->query('DELETE FROM assigned WHERE course_id IN ('.implode(',', $deleted).')')->returnStatus()){
                                    $deliver['status'] = 200;
                                }
                            }
                        } else {
                            $deliver['status'] = 200;
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
} else if($one == 'delete-plan'){
    $deliver['status'] = 400;
    $id = Specific::Filter($_POST['id']);
    if (isset($id) && is_numeric($id)) {
        if($dba->query('DELETE FROM plan WHERE id = '.$id)->returnStatus()){
            $deliver['status'] = 200;
        };
    }
} else if($one == 'search-plans') {
    $keyword = Specific::Filter($_POST['keyword']);
        $html = '';
        $query = '';
        if(!empty($keyword)){
            $query .= " WHERE name LIKE '%$keyword%' OR resolution LIKE '%$keyword%'";
        }
        $plans = $dba->query('SELECT * FROM plan'.$query.' LIMIT ? OFFSET ?', 10, 1)->fetchAll();
        $deliver['total_pages'] = $dba->totalPages;
        if (!empty($plans)) {
            foreach ($plans as $plan) {
                $TEMP['!id'] = $plan['id'];
                $TEMP['!name'] = $plan['name'];
                $TEMP['!program'] = $dba->query('SELECT name FROM programs WHERE id = '.$plan['program_id'])->fetchArray();
                $TEMP['!resolution'] = $plan['resolution'];
                $TEMP['!date_approved'] = Specific::DateFormat($plan['date_approved']);
                $TEMP['!duration'] = $plan['duration'];
                $TEMP['!credits'] = $plan['credits'];
                $TEMP['!courses'] = $dba->query('SELECT COUNT(*) FROM assigned WHERE plan_id = '.$plan['id'])->fetchArray();
                $TEMP['!note_mode'] = $plan['note_mode'];
                $TEMP['!status'] = $TEMP['#word'][$plan['status']];
                $TEMP['!time'] = Specific::DateFormat($plan['time']);
                $html .= Specific::Maket('more/plans/includes/plans-list');
            }
            Specific::DestroyMaket();
            $deliver['status'] = 200;
        } else {
            if(!empty($keyword)){
                $TEMP['keyword'] = $keyword;
                $html .= Specific::Maket('not-found/result-for');
            } else {
                $html .= Specific::Maket('not-found/plans');
            }
        }
        $deliver['html'] = $html;
} else if($one == 'get-plitems'){
    $id = Specific::Filter($_POST['id']);
    if(isset($id) && is_numeric($id)){
        $items = $dba->query('SELECT program_id, name, resolution, date_approved, duration, credits, note_mode, status FROM plan WHERE id = '.$id)->fetchArray();
        $items['date_approved'] = date('Y-m-d', $items['date_approved']);
        $assigned = $dba->query('SELECT * FROM assigned WHERE plan_id = '.$id)->fetchAll();
        foreach ($assigned as $assig) {
            $name = $dba->query('SELECT name FROM courses WHERE id = '.$assig['course_id'])->fetchArray();
            $items['courses'][] = array('id' => $assig['course_id'], 'name' => $name);   
        }
        if (!empty($items)) {
            $deliver = array(
                'status' => 200,
                'items' => $items
            );
        }
    }
} else if($one == 'table-plans'){
    $page = Specific::Filter($_POST['page_id']);
    if(!empty($page) && is_numeric($page) && isset($page) && $page > 0){
        $html = "";
        $query = '';
        $keyword = Specific::Filter($_POST['keyword']);
        if(!empty($keyword)){
            $query .= " WHERE name LIKE '%$keyword%' OR resolution LIKE '%$keyword%'";
        }
        $plans = $dba->query('SELECT * FROM plan'.$query.' LIMIT ? OFFSET ?', 10, $page)->fetchAll();
        if (!empty($plans)) {
            foreach ($plans as $plan) {
                $TEMP['!id'] = $plan['id'];
                $TEMP['!name'] = $plan['name'];
                $TEMP['!program'] = $dba->query('SELECT name FROM programs WHERE id = '.$plan['program_id'])->fetchArray();
                $TEMP['!resolution'] = $plan['resolution'];
                $TEMP['!date_approved'] = Specific::DateFormat($plan['date_approved']);
                $TEMP['!duration'] = $plan['duration'];
                $TEMP['!credits'] = $plan['credits'];
                $TEMP['!courses'] = $dba->query('SELECT COUNT(*) FROM assigned WHERE plan_id = '.$plan['id'])->fetchArray();
                $TEMP['!note_mode'] = $plan['note_mode'];
                $TEMP['!status'] = $TEMP['#word'][$plan['status']];
                $TEMP['!time'] = Specific::DateFormat($plan['time']);
                $html .= Specific::Maket('more/plans/includes/plans-list');
            }
            Specific::DestroyMaket();
            $deliver['status'] = 200;
        }
        $deliver['status'] = 200;
        $deliver['html'] = $html;
    }
} else if($one == 'this-users'){
    $deliver['status'] = 400;
    $roles  = array('academic', 'teacher', 'student');
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
    if(empty($role)){
        $emptys[] = 'role';
    }
    if(empty($status)){
        $emptys[] = 'status';
    }
    if(isset($id) && is_numeric($id)){
        if(empty($emptys)){
            if(!is_numeric($dni)){
                $errors[] = 'dni';
            }
            if(!in_array($role, $roles)){
                $errors[] = 'role';
            }
            if(!in_array($status, $statusa)){
                $errors[] = 'status';
            }
            if (empty($errors)) {
                if($dba->query('UPDATE users SET dni = ?, names = ?, surnames = ?, role = ?, status = ? WHERE id = '.$id, $dni, $names, $surnames, $role, $status)->returnStatus()){
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
} else if($one == 'get-uitems'){
    $id = Specific::Filter($_POST['id']);
    if(isset($id) && is_numeric($id)){
        $items = $dba->query('SELECT dni, names, surnames, role, status FROM users WHERE id = '.$id)->fetchArray();
        $deliver['XD'] = 'XD';
        if (!empty($items)) {
            $deliver = array(
                'status' => 200,
                'items' => $items
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
}
?>
