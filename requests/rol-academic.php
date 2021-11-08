<?php 
if ($TEMP['#loggedin'] === false || Specific::Academic() === false) {
    $deliver = array(
        'status' => 400,
        'error' => $TEMP['#word']['error']
    );
    echo json_encode($deliver);
    exit();
}

if($one == 'this-program'){
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
            if($type == 'add'){
                if($dba->query('INSERT INTO programs (name, faculty_id, plan_id, title, snies, level, semesters, mode, `time`) VALUES ("'.$name.'", '.$faculty_id.', '.$plan_id.',"'.$title.'",'.$snies.',"'.$level.'",'.$semester.',"'.$mode.'",'.time().')')->returnStatus()){
                    $deliver['status'] = 200;
                }
            } else if(isset($id) && is_numeric($id)){
                if($dba->query('UPDATE programs SET name = ?, faculty_id = ?, plan_id = ?, title= ?, snies = ?, level = ?, semesters = ?, mode = ? WHERE id = '.$id, $name, $faculty_id, $plan_id, $title, $snies, $level, $semester, $mode)->returnStatus()){
                    $deliver['status'] = 200;
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
    if(isset($id) && is_numeric($id)){
        $items = $dba->query('SELECT name, program_id, period_id, code, semester, credits, quota, type, schedule FROM courses WHERE id = '.$id)->fetchArray();
        $teachers = $dba->query('SELECT * FROM teacher WHERE course_id = '.$id)->fetchAll();
        foreach ($teachers as $teacher) {
            $names = $dba->query('SELECT names FROM users WHERE id = '.$teacher['user_id'])->fetchArray();
            $items['teachers'][] = array('id' => $teacher['user_id'], 'name' => $names);   
        }
        if (!empty($items)) {
            $deliver = array(
                'status' => 200,
                'items' => $items
            );
        }
    }
} else if($one == 'delete-course'){
    $deliver['status'] = 400;
    $id = Specific::Filter($_POST['id']);
    if (isset($id) && is_numeric($id)) {
        if($dba->query('DELETE FROM courses WHERE id = '.$id)->returnStatus()){
            $deliver['status'] = 200;
        };
    }
} else if($one == 'search-user') {
    $keyword = Specific::Filter($_POST['keyword']);
    $html = '';
    $query = '';
    if(!empty($keyword)){
        $query .= " WHERE names LIKE '%$keyword%' OR surnames LIKE '%$keyword%' OR dni LIKE '%$keyword%'";
    }
    $users = $dba->query('SELECT * FROM users'.$query.' LIMIT 5')->fetchAll();
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
            if($type == 'add'){
                if($dba->query('INSERT INTO faculty (name, status, `time`) VALUES ("'.$name.'","'.$status.'",'.time().')')->returnStatus()){
                    $deliver['status'] = 200;
                }
            } else if(isset($id) && is_numeric($id)){
                if($dba->query('UPDATE faculty SET name = "'.$name.'", status = "'.$status.'" WHERE id = '.$id)->returnStatus()){
                    $deliver['status'] = 200;
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
}
?>
