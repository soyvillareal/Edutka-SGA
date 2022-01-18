<?php 
if ($TEMP['#loggedin'] === false || Specific::Admin() === false) {
    $deliver = array(
        'status' => 400,
        'error' => $TEMP['#word']['error']
    );
    echo json_encode($deliver);
    exit();
}

if($one == 'search-course') {
    $nextrues = array(true);
    $keyword = Specific::Filter($_POST['keyword']);
    $type = Specific::Filter($_POST['typet']);
    $course_id = Specific::Filter($_POST['course_id']);
    $plan_id = Specific::Filter($_POST['plan_id']);
    $courses = Specific::Filter($_POST['courses']);
    $html = '';
    $query = '';
    if(!empty($keyword)){
        $query .= " WHERE (id LIKE '%$keyword%' OR name LIKE '%$keyword%' OR code LIKE '%$keyword%')";
        if($type == 'edit'){
            if(!empty($course_id)){
                $query .= " AND id <> $course_id";
            }
        }
        if(!empty($courses)){
            $query .= " AND id NOT IN (".$courses.")";
        }
        if(!empty($plan_id)){
            $query .= ' AND id NOT IN ((SELECT course_id FROM curriculum))';
        } else {
            $pcourses = $dba->query('SELECT courses FROM plan WHERE courses = '.$course_id)->fetchAll(false);
            if(!empty($pcourses)){
                $query .= " AND id IN (".implode(',', $pcourses).")";
            }
        }
        $courses = $dba->query('SELECT * FROM courses'.$query.' LIMIT 5')->fetchAll();
    }

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
} else if($one == 'this-courses'){
    $deliver['status'] = 400;
    $qualifications  = array('activated', 'deactivated');
    $credits  = array(1, 2, 3, 4);
    $types  = array('practice', 'theoretical');
    $schedules  = array('daytime', 'nightly');
    $emptys = array();
    $errors = array();


    $type = Specific::Filter($_POST['type']);
    $id = Specific::Filter($_POST['id']);
    $code = Specific::Filter($_POST['code']);
    $name = Specific::Filter($_POST['name']);
    $preknowledge = Specific::Filter($_POST['preknowledge']);
    $qualification = Specific::Filter($_POST['qualification']);
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
    if(empty($qualification)){
        $emptys[] = 'qualification';
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
        if(!in_array($qualification, $qualifications)){
            $errors[] = 'qualification';
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
            if(!empty($type)){
                if($type == 'add'){
                    if($dba->query('INSERT INTO courses (code, name, preknowledge, qualification, credits, quota, type, schedule, `time`) VALUES ("'.$code.'", "'.$name.'", "'.$preknowledge.'", '.$qualification.', '.$credit.', '.$quota.', "'.$typec.'", "'.$schedule.'",'.time().')')->returnStatus()){
                        $deliver['status'] = 200;
                    }
                } else if(isset($id) && is_numeric($id)){
                    if($dba->query('UPDATE courses SET code = ?, name = ?, preknowledge = ?, qualification = ?, credits = ?, quota = ?, type = ?, schedule = ? WHERE id = '.$id, $code, $name, $preknowledge, $qualification, $credit, $quota, $typec, $schedule)->returnStatus()){
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
} else if($one == 'get-pitems'){
    $deliver['status'] = 400;
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
} else if($one == 'this-programs'){
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
    $deliver['status'] = 400;
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
} else if($one == 'get-peitems'){
    $deliver['status'] = 400;
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
        $courses_exists = $dba->query('SELECT COUNT(*) FROM enrolled WHERE period_id = '.$id)->fetchArray();
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
            if(($dba->query('SELECT COUNT(*) FROM enrolled WHERE period_id = '.$id)->fetchArray() > 0 || $dba->query('SELECT status FROM periods WHERE id = '.$id)->fetchArray() == $status) || $type == 'add'){
                $year = explode('-', $start)[0];
                $name = "$year-$period";
                $start = strtotime($start);
                $final = strtotime($final);
                if(!in_array($status, $statusa)){
                    $errors[] = 'status';
                }
                if($start > $final){
                    $errors[] = 'start';
                }
                $query = $type == 'edit' ? ' AND id <> '.$id : '';
                if($dba->query('SELECT COUNT(*) FROM periods WHERE name = "'.$name.'"'.$query)->fetchArray() == 0){
                    if($dba->query('SELECT COUNT(*) FROM periods WHERE start < '.$start.' AND final > '.$final)->fetchArray() == 0){
                        if (empty($errors)) {
                            if(!empty($type)){
                                if($type == 'add'){
                                    if($dba->query('INSERT INTO periods (name, start, final, status, `time`) VALUES ("'.$name.'", '.$start.', '.$final.', "'.$status.'",'.time().')')->returnStatus()){
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
                            'error' => $TEMP['#word']['there_already_period_these_dates']
                        );
                    }
                } else {
                    $deliver = array(
                        'status' => 400,
                        'error' => $TEMP['#word']['this_period_already_exists']
                    );
                }  
            } else {
                $deliver = array(
                    'status' => 400,
                    'error' => $TEMP['#word']['period_has_already_been_assigned']
                );
            }
        } else {
            $deliver = array(
                'status' => 400,
                'emptys' => $emptys
            );
        }
} else if($one == 'this-dates'){
    $deliver['status'] = 400;
    $emptys = array();
    $errors = array();

    $id = Specific::Filter($_POST['id']);
    $dates = Specific::Filter($_POST['dates']);

    if(!empty($id) && is_numeric($id) && !empty($dates)){
        $dates = html_entity_decode($dates);
        $dates = json_decode($dates);
        
        if($dba->query('SELECT COUNT(*) FROM periods WHERE id = '.$id)->fetchArray() > 0){
            if(!empty($dates[1]) && empty($dates[2]) || empty($dates[1]) && !empty($dates[2])){
                $emptys[] = 0;
            }
            if(!empty($dates[3]) && empty($dates[4]) || empty($dates[3]) && !empty($dates[4])){
                $emptys[] = 1;
            }
            if(!empty($dates[8]) && empty($dates[9]) || empty($dates[8]) && !empty($dates[9])){
                $emptys[] = 2;
            }
            if(!empty($dates[11]) && empty($dates[12]) || empty($dates[11]) && !empty($dates[12])){
                $emptys[] = 3;
            }
            $aerror = array();
            foreach ($dates as $key => $value) {
                $aerror[$key] = true;
                if(!empty($value)){
                    $value = strtotime($value);
                    if($value > $dba->query('SELECT final FROM periods WHERE id = '.$id)->fetchArray()){
                        $aerror[$key] = false;
                    }
                }
            }

            if(!in_array(false, $aerror)){
                if(empty($emptys)){
                    if(strtotime($dates[1]) > strtotime($dates[2])){
                        $errors[] = 0;
                    }
                    if(strtotime($dates[3]) > strtotime($dates[4])){
                        $errors[] = 1;
                    }
                    if(strtotime($dates[8]) > strtotime($dates[9])){
                        $errors[] = 2;
                    }
                    if(strtotime($dates[11]) > strtotime($dates[12])){
                        $errors[] = 3;
                    }
                    if (empty($errors)) {
                        if($dba->query('UPDATE periods SET dates = ? WHERE id = '.$id, json_encode($dates))->returnStatus()){
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
            } else {
                $deliver = array(
                    'status' => 400,
                    'error' => $TEMP['#word']['error']
                );
            }
        } else {
            $deliver = array(
                'status' => 400,
                'error' => $TEMP['#word']['error']
            );
        }
    } else {
        $deliver = array(
            'status' => 400,
            'error' => $TEMP['#word']['error']
        );
    }
} else if($one == 'get-ditems'){
    $deliver['status'] = 400;
    $id = Specific::Filter($_POST['id']);
    if(!empty($id) && is_numeric($id)){
        $items = $dba->query('SELECT dates FROM periods WHERE id = '.$id)->fetchArray();
        $items = json_decode($items, true);
        if(empty($items)){
            $items = ["", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", ""];
        }

        $dates = array();
        $dates[0] = $items[0];
        $dates[1] = array($items[1], $items[2]);
        $dates[2] = array($items[3], $items[4]);
        $dates[3] = $items[5];
        $dates[4] = $items[6];
        $dates[5] = $items[7];
        $dates[6] = array($items[8], $items[9]);
        $dates[7] = $items[10];
        $dates[8] = array($items[11], $items[12]);
        $dates[9] = $items[13];
        $dates[10] = $items[14];
        $dates[11] = $items[15];
        $dates[12] = $items[16];
        $dates[13] = $items[17];

        foreach ($dates as $key => $value) {
            if (is_array($value)){
                foreach ($value as $k => $val) {
                    if(empty($val)) {
                        $dates[$key][$k] = $TEMP['#word']['pending'];
                    } else {
                        $val = strtotime($val);
                        $dates[$key][$k] = Specific::DateFormat($val, true);
                    }
                }
            } else {
                if(empty($value)) {
                    $dates[$key] = $TEMP['#word']['pending'];
                } else {
                    $value = strtotime($value);
                    $dates[$key] = Specific::DateFormat($value, true);
                }
            }
        }

        $period = $dba->query('SELECT name, final FROM periods WHERE id = '.$id)->fetchArray();

        if (!empty($items)) {
            $deliver = array(
                'status' => 200,
                'items' => $items,
                'dates' => $dates,
                'name' => $period['name'],
                'final' => date('Y-m-d', $period['final'])
            );
        }
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
} else if($one == 'get-uitems'){
    $deliver['status'] = 400;
    $id = Specific::Filter($_POST['id']);
    if(isset($id) && is_numeric($id)){
        $items = $dba->query('SELECT dni, names, surnames, role, status FROM users WHERE id = '.$id)->fetchArray();
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
} else if($one == 'get-plitems'){
    $deliver['status'] = 400;
    $id = Specific::Filter($_POST['id']);
    if(isset($id) && is_numeric($id)){
        $items = $dba->query('SELECT program_id, name, resolution, date_approved, duration, credits, note_mode, status FROM plan WHERE id = '.$id)->fetchArray();
        $items['date_approved'] = date('Y-m-d', $items['date_approved']);
        if (!empty($items)) {
            $deliver = array(
                'status' => 200,
                'items' => $items
            );
        }
    }
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
        $coutrues = array();
        $couarr = explode(',', $courses);
        foreach ($couarr as $course_id) {
            if($dba->query('SELECT COUNT(*) FROM courses WHERE id = '.$course_id)->fetchArray() > 0){
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
                if($type == 'add'){
                    $plan_id = $dba->query('INSERT INTO plan (program_id, name, resolution, date_approved, duration, credits, note_mode, status, `time`) VALUES ('.$program_id.', "'.$name.'", '.$resolution.', '.$date_approved.', '.$duration.', '.$credits.', "'.$note_mode.'", "'.$status.'", '.time().')')->insertId();
                    if($dba->query('UPDATE courses SET plan_id = ? WHERE id IN ('.$courses.')', $plan_id)->returnStatus()){
                        $deliver['status'] = 200;
                    }
                } else if(isset($id) && is_numeric($id)){
                    $nextrues = array();
                    $nowcou = $dba->query('SELECT id FROM courses WHERE plan_id = '.$id)->fetchAll(false);
                    foreach ($nowcou as $course_id) {
                        if(in_array($course_id, array_diff($nowcou, $couarr)) && $dba->query('SELECT COUNT(*) FROM teacher WHERE course_id = '.$course_id)->fetchArray() > 0){
                            $nextrues[] = false;
                        }
                    }
                    if(!in_array(false, $nextrues)){
                        if($dba->query('UPDATE plan SET program_id = ?, name = ?, resolution = ?, date_approved = ?, duration = ?, credits = ?, note_mode = ?, status = ? WHERE id = '.$id, $program_id, $name, $resolution, $date_approved, $duration, $credits, $note_mode, $status)->returnStatus()){
                            $deleted = array_diff($nowcou, $couarr);
                            $addf = array_diff($couarr, $nowcou);
                            $adds = explode(',', implode(',', $addf));
                            if(count($addf) > 0 || count($deleted) > 0){
                                if(count($addf) > 0){
                                    if($dba->query('UPDATE courses SET plan_id = ? WHERE id IN ('.implode(',', $addf).')', $id)->returnStatus()){
                                        $deliver['status'] = 200;
                                    }
                                }
                                if(count($deleted) > 0){
                                    if($dba->query('UPDATE courses SET plan_id = 0 WHERE id IN ('.implode(',', $deleted).')')->returnStatus()){
                                        $deliver['status'] = 200;
                                    }
                                }
                            } else {
                                $deliver['status'] = 200;
                            }


                        } else {
                            $deliver = array(
                                'status' => 400,
                                'error' => $TEMP['#word']['error']
                            );
                        }
                    } else {
                        $deliver = array(
                            'status' => 400,
                            'error' => $TEMP['#word']['one_deleted_courses_already_assignments']
                        );
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
        if($dba->query('SELECT COUNT(*) FROM curriculum WHERE plan_id = '.$id)->fetchArray() == 0){
            if($dba->query('DELETE FROM plan WHERE id = '.$id)->returnStatus()){
                $deliver['status'] = 200;
            } else {
                $deliver = array(
                    'status' => 400,
                    'error' => $TEMP['#word']['error']
                );
            }
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
                $TEMP['!courses'] = $dba->query('SELECT COUNT(*) FROM curriculum WHERE plan_id = '.$plan['id'])->fetchArray();
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
                $TEMP['!courses'] = $dba->query('SELECT COUNT(*) FROM curriculum WHERE plan_id = '.$plan['id'])->fetchArray();
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
                        } else {
                            $deliver = array(
                                'status' => 400,
                                'error' => $TEMP['#word']['error']
                            );
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
        $items = $dba->query('SELECT user_id, note_id, note, status FROM qualification WHERE id = '.$id)->fetchArray();
        $items['course'] = $dba->query('SELECT name FROM courses c WHERE (SELECT course_id FROM notes n WHERE id = '.$items['note_id'].' AND course_id = c.id) = id')->fetchArray();
        $data = Specific::Data($items['user_id']);
        $items['applicant'] = $data['full_name'];
        $items['email'] = $data['email'];
        $items['cellphone'] = $data['cellphone'];
        $items['phone'] = 'true';
        if(!empty($data['phone'])){
            $items['phone'] = $data['phone'];
        }
        unset($items['user_id']);
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
            $qualification = $dba->query('SELECT note FROM qualification WHERE id = '.$id)->fetchArray();
            if(!in_array($status, $statusa)){
                $errors[] = array('el' => 'status', 'text' => 'please_enter_valid_value');
            }
            if(!empty($note) && ($note > $TEMP['#nm'] || $note < 0)){
                $errors[] = array('el' => 'note', 'text' => 'please_enter_valid_value');
            }
            if(empty($note)){
                $note = NULL;
                if($qualification != NULL){
                    $errors[] = array('el' => 'note', 'text' => 'this_field_is_empty');
                }
            }
            if(empty($errors)){
                if($dba->query('UPDATE qualification SET note = ?, status = ? WHERE id = '.$id, $note, $status)->returnStatus()){
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
    } else {
        $deliver = array(
            'status' => 400,
            'error' => $TEMP['#word']['error']
        );
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
                $TEMP['!applicant'] = $dba->query('SELECT names FROM users WHERE id = '.$qua['user_id'])->fetchArray();
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
                $TEMP['!applicant'] = $dba->query('SELECT names FROM users WHERE id = '.$qua['user_id'])->fetchArray();
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
} else if($one == 'this-rules'){
	$deliver['status'] = 400;
	$id = Specific::Filter($_POST['id']);
	$rules = Specific::Filter($_POST['rules']);
	$link = Specific::Filter($_POST['link']);
	$status = Specific::Filter($_POST['status']);
	$type = Specific::Filter($_POST['type']);
	if(!empty($rules) && !empty($status)){
        if (preg_match_all('/{\#(.+?)->(.+?)}/i', htmlspecialchars_decode($rules), $rls)) {
            $count = 0;
            $params_uniq = array_count_values($rls[1]);
            foreach ($params_uniq as $key => $uniq) {
                if(in_array($rls[1][$count], $TEMP['#rules'])){
                    if($uniq > 1){
                        $uniq_error = $TEMP['#word']['the_parameter'].' "'.$key.'" '.$TEMP['#word']['repeated_parameters_unique'];
                        break;
                    }
                }
                $count++;
            }
            for ($i=0; $i < count($TEMP['#rulen']); $i++) { 
                $pnot = array_search($TEMP['#rulen'][$i], $rls[1]);
                $pmax = array_search('NM', $rls[1]);
                if($rls[2][$pnot] > $rls[2][$pmax]){
                    $max_error = $TEMP['#word']['the_maximum_grade_is'].' '.$TEMP['#word']['and_was_set_to'].' "'.$rls[1][$pmax].'", '.$TEMP['#word']['change_the_parameter'].': '.$rls[1][$pnot];
                }
            }    
        }
        if(!isset($max_error)){
            if(!isset($uniq_error)){
                if (empty($link) || !filter_var($link, FILTER_VALIDATE_URL) === false) {
                    if($type == 'add'){
                        $rule_id = $dba->query('INSERT INTO rule (user_id, rules, link, status, modified, `time`) VALUES ('.$TEMP['#user']['id'].', "'.$rules.'", "'.$link.'", "'.$status.'" , 0, '.time().')')->insertId();
                        if($rule_id){
                            $rule = $dba->query('SELECT id, COUNT(*) AS count FROM rule WHERE status = "enabled" AND id <> '.$rule_id)->fetchArray();
                            if($rule['count'] > 0 && $status == 'enabled'){
                                $dba->query('UPDATE rule SET status = "disabled" WHERE id = '.$rule['id']);
                            }
                            $deliver['status'] = 200;
                        } else {
                            $deliver = array(
                                'status' => 400,
                                'error' => $TEMP['#word']['error']
                            );
                        }
                    } else {
                        if(isset($id) && is_numeric($id)){
                            if($dba->query('UPDATE rule SET rules = ?, link = ?, status = ? WHERE id = '.$id, $rules, $link, $status)->returnStatus()){
                                $rule = $dba->query('SELECT id, COUNT(*) AS count FROM rule WHERE status = "enabled" AND id <> '.$id)->fetchArray();
                                if($rule['count'] > 0 && $status == 'enabled'){
                                    $dba->query('UPDATE rule SET status = "disabled" WHERE id = '.$rule['id']);
                                }
                                $deliver['status'] = 200;
                            } else {
                                $deliver = array(
                                    'status' => 400,
                                    'error' => $TEMP['#word']['error']
                                );
                            }
                        } else {
                            $deliver = array(
                                'status' => 400,
                                'error' => $TEMP['#word']['error']
                            );
                        }
                    }
                } else {
                    $deliver = array(
                        'status' => 400,
                        'error' => $TEMP['#word']['please_enter_valid_link']
                    );
                }
            } else {
                $deliver = array(
                    'status' => 400,
                    'error' => $uniq_error
                );
            }
        } else {
            $deliver = array(
                'status' => 400,
                'error' => $max_error
            );
        }
	} else {
		$deliver = array(
			'status' => 400,
			'error' => $TEMP['#word']['please_complete_information_before_sending']
		);
	}
} else if($one == 'delete-rule'){
    $deliver['status'] = 400;
    $id = Specific::Filter($_POST['id']);
    if (isset($id) && is_numeric($id)) {
        if($dba->query('DELETE FROM rule WHERE id = '.$id)->returnStatus()){
        	$rule = $dba->query('SELECT id, COUNT(*) AS count FROM rule ORDER BY id DESC LIMIT 1')->fetchArray();
			if($rule['count'] > 0){
				$dba->query('UPDATE rule SET status = "enabled" WHERE id = '.$rule['id']);
			}
            $deliver['status'] = 200;
        };
    }
} else if($one == 'this-curriculum'){
    $deliver['status'] = 400;
    $teatrues = array(true);
    $types = array('add', 'edit');

    $type = Specific::Filter($_POST['type']);
    $id = Specific::Filter($_POST['id']);
    $courses = Specific::Filter($_POST['courses']);

    if(!empty($type) && in_array($type, $types)){
        if(!empty($courses)){
            $courses = explode(',', $courses);
            if(!empty($courses)){
                foreach ($courses as $course) {
                    if($dba->query('SELECT COUNT(*) FROM courses WHERE id = '.$course)->fetchArray() > 0){
                        $teatrues[] = true;
                    } else {
                        $teatrues[] = false;
                    }
                }
            }
            if (!in_array(false, $teatrues)) {
                $instrues = array();
                if($type == 'add'){
                    for ($i=0; $i < count($courses); $i++) {
                        if($dba->query('SELECT COUNT(*) FROM curriculum WHERE course_id = '.$courses[$i])->fetchArray() == 0){
                            if($dba->query('INSERT INTO curriculum (course_id, plan_id, `time`) VALUES ('.$courses[$i].', '.$id.', '.time().')')->returnStatus()){
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
                            'error' => $TEMP['#word']['one_courses_already_assigned_curriculum']
                        );
                    }
                } else {
                    $courses_all = $dba->query('SELECT course_id FROM curriculum WHERE plan_id = '.$id)->fetchAll(false);
                    $deleted = array_diff($courses_all, $courses);
                    $addf = array_diff($courses, $courses_all);
                    $adds = explode(',', implode(',', $addf));
                    if(count($addf) > 0 || count($deleted) > 0){
                        if(count($addf) > 0){
                            for ($i=0; $i < count($adds); $i++) {
                                if($dba->query('SELECT COUNT(*) FROM curriculum WHERE course_id = '.$adds[$i])->fetchArray() == 0){
                                    if($dba->query('INSERT INTO curriculum (course_id, plan_id, `time`) VALUES ('.$adds[$i].', '.$id.', '.time().')')->returnStatus()){
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
                            if($dba->query('DELETE FROM curriculum WHERE course_id IN ('.implode(',', $deleted).')')->returnStatus()){
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
} else if($one == 'get-citems'){
    $deliver['status'] = 400;
    $id = Specific::Filter($_POST['id']);
    if(isset($id) && is_numeric($id)){
        $items = array();
        $courses = $dba->query('SELECT * FROM curriculum WHERE plan_id = '.$id)->fetchAll();
        foreach ($courses as $course) {
            $name = $dba->query('SELECT name FROM courses WHERE id = '.$course['course_id'])->fetchArray();
            $items['courses'][] = array('id' => $course['course_id'], 'name' => $name);   
        }
        if (!empty($items)) {
            $deliver['status'] = 200;
            $deliver['items'] = $items;
        }
    }
} else if($one == 'delete-curriculum'){
    $deliver['status'] = 400;
    $id = Specific::Filter($_POST['id']);
    if (isset($id) && is_numeric($id)) {
        if($dba->query('DELETE FROM curriculum WHERE plan_id = '.$id)->returnStatus()){
            $deliver['status'] = 200;
        };
    }
}
?>