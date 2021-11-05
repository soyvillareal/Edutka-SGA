<?php 
if ($TEMP['#loggedin'] === false && Specific::Academic() === false) {
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
            } else if(is_numeric($id)){
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
}if($one == 'this-subject'){
    $deliver['status'] = 400;
    $id = Specific::Filter($_POST['id']);
    $type = Specific::Filter($_POST['type']);
    $name = Specific::Filter($_POST['name']);
    if(!empty($name)){
        if($type == 'add'){
            if($dba->query('INSERT INTO subjects (name, `time`) VALUES ("'.$name.'",'.time().')')->returnStatus()){
                $deliver['status'] = 200;
            }
        } else if(is_numeric($id)){
            if($dba->query('UPDATE subjects SET name = "'.$name.'" WHERE id = '.$id)->returnStatus()){
                $deliver['status'] = 200;
            }
        }
    } else {
        $deliver = array(
            'status' => 200,
            'empty' => 'true'
        );
    }
} else if($one == 'get-sitems'){
        $id = Specific::Filter($_POST['id']);
        if(isset($id) && is_numeric($id)){
            $item = $dba->query('SELECT name FROM subjects WHERE id = '.$id)->fetchArray();
            if (!empty($item)) {
                $deliver = array(
                    'status' => 200,
                    'item' => $item
                );
            }
        }
} else if($one == 'search-subjects') {
    $keyword = Specific::Filter($_POST['keyword']);
        $html = '';
        $query = '';
        if(!empty($keyword)){
            $query .= " WHERE name LIKE '%$keyword%'";
        }
        $subjects = $dba->query('SELECT * FROM subjects'.$query.' LIMIT ? OFFSET ?', 10, 1)->fetchAll();
        $deliver['total_pages'] = $dba->totalPages;
        if (!empty($subjects)) {
            foreach ($subjects as $subject) {
                $TEMP['!id'] = $subject['id'];
                $TEMP['!name'] = $subject['name'];
                $TEMP['!time'] = Specific::DateFormat($subject['time']);
                $html .= Specific::Maket('subjects/includes/subjects-list');
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
} else if($one == 'delete-subject'){
    $deliver['status'] = 400;
    $id = Specific::Filter($_POST['id']);
    if (isset($id) && is_numeric($id)) {
        if($dba->query('DELETE FROM subjects WHERE id = '.$id)->returnStatus()){
            $deliver['status'] = 200;
        };
    }
} else if($one == 'table-subjects'){
    $page = Specific::Filter($_POST['page_id']);
    if(!empty($page) && is_numeric($page) && isset($page) && $page > 0){
        $html = "";
        $subjects = $dba->query('SELECT * FROM subjects'.$query.' LIMIT ? OFFSET ?', 10, $page)->fetchAll();
        if (!empty($subjects)) {
            foreach ($subjects as $key => $subject) {
                $TEMP['!id'] = $subject['id'];
                $TEMP['!name'] = $subject['name'];
                $TEMP['!time'] = Specific::DateFormat($subject['time']);
                $html .= Specific::Maket('subjects/includes/subjects-list');
            }
            Specific::DestroyMaket();
            $deliver['status'] = 200;
        }
        $deliver['status'] = 200;
        $deliver['html'] = $html;
    }
}
?>
