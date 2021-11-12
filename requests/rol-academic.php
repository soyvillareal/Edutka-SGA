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
    $plans = $dba->query('SELECT id FROM plan')->fetchAll(false);
    $emptys = array();
    $errors = array();

    $id = Specific::Filter($_POST['id']);
    $name = Specific::Filter($_POST['name']);
    $faculty_id = Specific::Filter($_POST['faculty_id']);
    $plan_id = Specific::Filter($_POST['plan_id']);
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
    if(empty($plan_id)){
        $emptys[] = 'plan_id';
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
        if(!in_array($plan_id, array_values($faculties))){
            $errors[] = 'plan_id';
        }
        if (empty($errors)) {
            if(!empty($type)){
                if($type == 'add'){
                    if($dba->query('INSERT INTO programs (name, faculty_id, plan_id, title, snies, level, semesters, mode, `time`) VALUES ("'.$name.'", '.$faculty_id.', '.$plan_id.',"'.$title.'",'.$snies.',"'.$level.'",'.$semester.',"'.$mode.'",'.time().')')->returnStatus()){
                        $deliver['status'] = 200;
                    }
                } else if(isset($id) && is_numeric($id)){
                    if($dba->query('UPDATE programs SET name = ?, faculty_id = ?, plan_id = ?, title= ?, snies = ?, level = ?, semesters = ?, mode = ? WHERE id = '.$id, $name, $faculty_id, $plan_id, $title, $snies, $level, $semester, $mode)->returnStatus()){
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
            $items = $dba->query('SELECT name, faculty_id, plan_id, title, snies, level, semesters, mode FROM programs WHERE id = '.$id)->fetchArray();
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
    if(empty($preknowledge)){
        $emptys[] = 'preknowledge';
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
    $users = $dba->query('SELECT * FROM users WHERE role = 1'.$query.' LIMIT 5')->fetchAll();
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
        if($dba->query('DELETE FROM faculty WHERE id = '.$id)->returnStatus()){
            $deliver['status'] = 200;
        };
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
    if($min == date("Y-m-d")){
        if(empty($emptys)){
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
    $statusa  = array('activated', 'deactivated');
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
        $query .= " WHERE (id LIKE '%$keyword%' OR name LIKE '%$keyword%' OR code LIKE '%$keyword%')";
        if($type == 'edit'){
            $query .= " AND id <> $course_id";
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
}
?>
