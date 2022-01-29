<?php
if ($TEMP['#loggedin'] === true && (Specific::Admin() === true || Specific::Academic() === true || Specific::Teacher() === true)) {
    if($one == 'search-courses') {
        $keyword = Specific::Filter($_POST['keyword']);
        $user_id = Specific::Filter($_POST['user_id']);
        $period_id = Specific::Filter($_POST['period_id']);
        $html = '';
        $query = '';
        if(!empty($keyword)){
            $query .= " WHERE name LIKE '%$keyword%'";
        }
        if(Specific::Teacher() == true || Specific::Admin() == true){
            $sql = '';
            if(Specific::Teacher() == true){
                $sql .= ' WHERE user_id = '.$TEMP['#user']['id'];
                if(is_numeric($period_id)){
                    $sql .= (!empty($sql) ? ' AND' : ' WHERE').' period_id = '.$period_id;
                }
                $teachers = $dba->query('SELECT course_id FROM teacher'.$sql)->fetchAll(false);
            }
            $sql = (!empty($keyword) ? ' AND' : ' WHERE').' id IN ('.implode(',', $teachers);
            if(!empty($teachers)){
                $courses = $dba->query('SELECT * FROM course'.$query.$sql.') LIMIT ? OFFSET ?', 10, 1)->fetchAll();
            } else {
                $courses = $dba->query('SELECT * FROM course'.$query.' LIMIT ? OFFSET ?', 10, 1)->fetchAll();
            }
        } else {
            $courses = $dba->query('SELECT * FROM course'.$query.' LIMIT ? OFFSET ?', 10, 1)->fetchAll();
        }
        
        $deliver['total_pages'] = $dba->totalPages;
        if (!empty($courses)) {
            foreach ($courses as $course) {
                $preknowledge = explode(',', $course['preknowledge']);
                $assignments = $dba->query('SELECT period_id FROM teacher WHERE course_id = '.$course['id'])->fetchAll(false);
                $assignments = array_unique($assignments);

                if(Specific::Teacher() == true){
                    if(is_numeric($period_id)){
                        $note_mode = $dba->query('SELECT note_mode FROM plan WHERE (SELECT plan_id FROM curriculum WHERE course_id = '.$course['id'].') = id')->fetchArray();
                        $parameters = $dba->query('SELECT parameters FROM parameter p WHERE (SELECT id FROM teacher WHERE course_id = '.$course['id'].' AND period_id = '.$period_id.' AND id = p.teacher_id) = teacher_id')->fetchArray();
                        $parameters = json_decode($parameters);
                        $teachers = $dba->query('SELECT names FROM user u WHERE (SELECT user_id FROM teacher WHERE user_id = u.id AND course_id = '.$course['id'].' AND period_id = '.$period_id.') = id')->fetchAll(false);
                        $enrolled = $dba->query('SELECT COUNT(*) FROM enrolled WHERE type = "course" AND status = "registered" AND course_id = '.$course['id'].' AND period_id = '.$period_id)->fetchArray();

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
                        $TEMP['!quota'] = ($course['quota']-$enrolled). "/{$course['quota']}";

                        $paramarr = 0;
                        if($note_mode == '30-30-40'){
                            for ($i=0; $i < count($parameters); $i++) {
                                for ($j=0; $j < count($parameters[$i]); $j++) { 
                                    $paramarr++;
                                }
                            }
                        } else {
                            for ($i=0; $i < count($parameters); $i++) {
                                $paramarr++;
                            }
                        }
                        $TEMP['!parameters'] = $paramarr;
                    }
                }


                $TEMP['!program'] = $TEMP['#word']['pending'];

                $plans = $dba->query('SELECT plan_id FROM curriculum WHERE course_id = '.$course['id'])->fetchAll(false);
                if(!empty($plans)){
                    $programs = $dba->query('SELECT name FROM program p WHERE (SELECT program_id FROM plan WHERE id IN ('.implode(',', $plans).') AND program_id = p.id) = id')->fetchAll(false);

                    if(count($programs) == 2){
                        $TEMP['!program'] = "{$programs[0]} {$TEMP['#word']['and']} {$programs[1]}";
                    } else if(count($programs) > 2){
                        $end = end($programs);
                        array_pop($programs);
                        $TEMP['!program'] = implode(', ', $programs)." {$TEMP['#word']['and']} $end";
                    } else {
                        $TEMP['!program'] = $programs[0];
                    }
                }

                $TEMP['!id'] = $course['id'];
                $TEMP['!code'] = $course['code'];
                $TEMP['!name'] = $course['name'];
                $TEMP['!assignments'] = count($assignments);
                $TEMP['!preknowledge'] = !empty($course['preknowledge']) ? count($preknowledge) : 0;
                $TEMP['!qualification'] = $TEMP['#word'][$course['qualification']];
                $TEMP['!credits'] = $course['credits'];
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
            if(Specific::Teacher() == true || Specific::Admin() == true){
                $sql = '';
                if(Specific::Teacher() == true){
                    $sql .= ' WHERE user_id = '.$TEMP['#user']['id'];
                    if(is_numeric($period_id)){
                        $sql .= (!empty($keyword) ? ' AND' : ' WHERE').' period_id = '.$period_id;
                    }
                    $teachers = $dba->query('SELECT course_id FROM teacher'.$sql)->fetchAll(false);
                }
                $sql = (!empty($keyword) ? ' AND' : ' WHERE').' id IN ('.implode(',', $teachers);
                if(!empty($teachers)){
                    $courses = $dba->query('SELECT * FROM course'.$query.$sql.') LIMIT ? OFFSET ?', 10, $page)->fetchAll();
                } else {
                    $courses = $dba->query('SELECT * FROM course'.$query.' LIMIT ? OFFSET ?', 10, $page)->fetchAll();
                }
            } else {
                $courses = $dba->query('SELECT * FROM course'.$query.' LIMIT ? OFFSET ?', 10, $page)->fetchAll();
            }

            if (!empty($courses)) {
                foreach ($courses as $course) {
                    $preknowledge = explode(',', $course['preknowledge']);
                    $assignments = $dba->query('SELECT period_id FROM teacher WHERE course_id = '.$course['id'])->fetchAll(false);
                    $assignments = array_unique($assignments);
                    if(Specific::Teacher() == true){
                        if(is_numeric($period_id)){
                            $note_mode = $dba->query('SELECT note_mode FROM plan WHERE (SELECT plan_id FROM curriculum WHERE course_id = '.$course['id'].') = id')->fetchArray();
                            $parameters = $dba->query('SELECT parameters FROM parameter p WHERE (SELECT id FROM teacher WHERE course_id = '.$course['id'].' AND period_id = '.$period_id.' AND id = p.teacher_id) = teacher_id')->fetchArray();
                            $parameters = json_decode($parameters);
                            $teachers = $dba->query('SELECT names FROM user u WHERE (SELECT user_id FROM teacher WHERE user_id = u.id AND course_id = '.$course['id'].' AND period_id = '.$period_id.') = id')->fetchAll(false);
                            $enrolled = $dba->query('SELECT COUNT(*) FROM enrolled WHERE type = "course" AND status = "registered" AND course_id = '.$course['id'].' AND period_id = '.$period_id)->fetchArray();
                            
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
                            $TEMP['!quota'] = ($course['quota']-$enrolled). "/{$course['quota']}";

                            $paramarr = 0;
                            if($note_mode == '30-30-40'){
                                for ($i=0; $i < count($parameters); $i++) {
                                    for ($j=0; $j < count($parameters[$i]); $j++) { 
                                        $paramarr++;
                                    }
                                }
                            } else {
                                for ($i=0; $i < count($parameters); $i++) {
                                    $paramarr++;
                                }
                            }
                            $TEMP['!parameters'] = $paramarr;
                        }
                    }

                    $TEMP['!program'] = $TEMP['#word']['pending'];

                    $plans = $dba->query('SELECT plan_id FROM curriculum WHERE course_id = '.$course['id'])->fetchAll(false);
                    if(!empty($plans)){
                        $programs = $dba->query('SELECT name FROM program p WHERE (SELECT program_id FROM plan WHERE id IN ('.implode(',', $plans).') AND program_id = p.id) = id')->fetchAll(false);

                        if(count($programs) == 2){
                            $TEMP['!program'] = "{$programs[0]} {$TEMP['#word']['and']} {$programs[1]}";
                        } else if(count($programs) > 2){
                            $end = end($programs);
                            array_pop($programs);
                            $TEMP['!program'] = implode(', ', $programs)." {$TEMP['#word']['and']} $end";
                        } else {
                            $TEMP['!program'] = $programs[0];
                        }
                    }

                    $TEMP['!id'] = $course['id'];
                    $TEMP['!code'] = $course['code'];
                    $TEMP['!name'] = $course['name'];
                    $TEMP['!assignments'] = count($assignments);
                    $TEMP['!preknowledge'] = !empty($course['preknowledge']) ? count($preknowledge) : 0;
                    $TEMP['!qualification'] = $TEMP['#word'][$course['qualification']];
                    $TEMP['!credits'] = $course['credits'];
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
        if (!empty($_POST['keyword'])) {
            $html = '';
            $keyword = Specific::Filter($_POST['keyword']);
            $type = Specific::Filter($_POST['typet']);
            $screen = Specific::Filter($_POST['screen']);
            if($type == 'notes' && Specific::Teacher() == true){
                $my_courses = $dba->query('SELECT course_id FROM teacher WHERE user_id = '.$TEMP['#user']['id'])->fetchAll(false);
                $users = $dba->query('SELECT user_id FROM enrolled WHERE type = "course" AND course_id IN ('.implode(',', $my_courses).')')->fetchAll(false);
                $users = $dba->query('SELECT * FROM user u WHERE id != '.$TEMP['#user']['id'].' AND role = "student" AND (names LIKE "%'.$keyword.'%" OR surnames LIKE "%'.$keyword.'%") AND id IN ('.implode(',', $users).') LIMIT 10')->fetchAll();
            } else if($type == 'teachers' && (Specific::Academic() == true || Specific::Admin() == true)){
                $users = $dba->query('SELECT * FROM user WHERE id IN (SELECT user_id FROM teacher WHERE course_id IN ((SELECT course_id FROM enrolled))) AND role = "teacher" AND (names LIKE "%'.$keyword.'%" OR surnames LIKE "%'.$keyword.'%") LIMIT 10')->fetchAll();
            } else {
                $users = $dba->query('SELECT * FROM user WHERE id != '.$TEMP['#user']['id'].' AND role = "student" AND (names LIKE "%'.$keyword.'%" OR surnames LIKE "%'.$keyword.'%") LIMIT 10')->fetchAll();
            }
        }
            
        if (!empty($users)) {
            foreach ($users as $user) {
                $full_name = "{$user['names']} {$user['surnames']}";
                $url = "$type?keyword={$full_name}&user={$user['id']}&screen={$screen}";
                if($type == 'teachers'){
                    $url = "notes?type=course&keyword={$full_name}&user={$user['id']}&screen={$screen}";
                }
                $html .= "<a class='display-flex border-left border-right border-bottom border-grey padding-10 background-hover' href='".Specific::Url("$url")."' target='_self'><div class='margin-right-auto color-black'>".$full_name.'</div></a>';
            }
            $deliver['status'] = 200;
        } else {
            $TEMP['keyword'] = $keyword;
            $html .= Specific::Maket('not-found/aj-result-for');
        }
        $deliver['html'] = $html;
    } else if($one == 'get-citems'){
        $id = Specific::Filter($_POST['id']);
        $period_id = Specific::Filter($_POST['period_id']);
        $type = Specific::Filter($_POST['type']);
        $pos = Specific::Filter($_POST['pos']);
        if(isset($id) && is_numeric($id)){
            if($type == 'notes'){
                $note = $dba->query('SELECT * FROM note WHERE id = '.$id)->fetchArray();
                $id = $note['course_id'];
            }
            if(Specific::Admin() == true || Specific::Academic() == true || Specific::Teacher() == true){
                if(Specific::Admin() == true || Specific::Academic() == true){
                    $items = $dba->query('SELECT name, preknowledge, qualification, credits, quota, type, schedule FROM course WHERE id = '.$id)->fetchArray();
                    $teachers = $dba->query('SELECT * FROM teacher WHERE course_id = '.$id)->fetchAll();
                    foreach ($teachers as $teacher) {
                        $names = $dba->query('SELECT names FROM user WHERE id = '.$teacher['user_id'])->fetchArray();
                        $items['teachers'][] = array('id' => $teacher['user_id'], 'name' => $names);   
                    }
                    if(!empty($items['preknowledge'])){
                        $preknowledge = array();
                        $preknowledges = explode(',', $items['preknowledge']);
                        foreach ($preknowledges as $prek) {
                            $name = $dba->query('SELECT name FROM course WHERE id = '.$prek)->fetchArray();
                            $preknowledge[] = array('id' => $prek, 'name' => $name);   
                        }
                        $items['preknowledge'] = $preknowledge;
                    } 
                } else {
                    $items = array();
                }

                if($type == 'notes'){
                    $parameter = $dba->query('SELECT * FROM parameter p WHERE (SELECT id FROM teacher WHERE course_id = '.$id.' AND period_id = '.$period_id.' AND id = p.teacher_id) = teacher_id')->fetchArray();
                    $items['parameters'] = json_decode($parameter['parameters'], true);
                    $items['notes'] = json_decode($note['notes'], true);
                    if(isset($pos) && is_numeric($pos)){
                        $items['parameters'] = json_decode($parameter['parameters'], true)[$pos];
                        $notes = json_decode($note['notes'], true)[$pos];
                        $items['notes'] = $notes;
                    }
                } else if(Specific::Teacher() == true){
                    $parameter = $dba->query('SELECT * FROM parameter p WHERE (SELECT id FROM teacher WHERE course_id = '.$id.' AND period_id = '.$period_id.' AND id = p.teacher_id) = teacher_id')->fetchArray();
                    $note_mode = $dba->query('SELECT note_mode FROM plan p WHERE (SELECT plan_id FROM curriculum WHERE course_id = '.$id.' AND plan_id = p.id) = id')->fetchArray();

                    if(isset($note_mode)){
                        $deliver['mode'] = $note_mode;
                        if(!empty($parameter['parameters'])){
                           $items = json_decode($parameter['parameters'], true); 
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
        $emptys = array();
        $error = array();

        $id = Specific::Filter($_POST['id']);
        $period_id = Specific::Filter($_POST['period_id']);
        $params = Specific::Filter($_POST['params']);
        $params = html_entity_decode($params);
        $params = json_decode($params);
        $arrayj = array();

        if(!empty($id) && is_numeric($id)){
            $note_mode = $dba->query('SELECT note_mode FROM plan p WHERE (SELECT plan_id FROM curriculum WHERE course_id = '.$id.' AND plan_id = p.id) = id')->fetchArray();

            if($note_mode == '100'){
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
                if (empty($error)) {
                    $teachers = $dba->query('SELECT id FROM teacher WHERE course_id = '.$id.' AND period_id = '.$period_id)->fetchAll(false);
                    $teacher_id = $dba->query('SELECT id FROM teacher WHERE user_id = '.$TEMP['#user']['id'].' AND course_id = '.$id.' AND period_id = '.$period_id)->fetchArray();
                    $parameter_id = $dba->query('SELECT id FROM parameter WHERE teacher_id IN ('.implode(',', $teachers).')')->fetchArray();
                    if(!empty($parameter_id)){
                        if($dba->query('UPDATE parameter SET parameters = ?, teacher_id = ?, `time` = ? WHERE id = '.$parameter_id, json_encode($arrayj), $teacher_id, time())->returnStatus()){
                            $deliver['status'] = 200;
                        }
                    } else {
                        if($dba->query('INSERT INTO parameter (user_id, teacher_id, parameters, `time`) VALUES (?, ?, ?, ?)', $TEMP['#user']['id'], $teacher_id, json_encode($arrayj), time())->returnStatus()){
                            $deliver['status'] = 200;
                        }
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
        }
    } else if($one == 'this-notes'){
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
                $note = $dba->query('SELECT * FROM note WHERE id = '.$id)->fetchArray();
                $period = $dba->query('SELECT * FROM period p WHERE (SELECT period_id FROM enrolled WHERE user_id = '.$note['user_id'].' AND course_id = '.$note['course_id'].' AND type = "course" AND period_id = p.id) = id')->fetchArray();
                $status = $dba->query('SELECT status FROM enrolled WHERE type = "course" AND user_id = '.$note['user_id'].' AND course_id = '.$note['course_id'].' AND period_id = '.$note['period_id'].' AND program_id = '.$note['program_id'])->fetchArray();
                if($status != 'cancelled'){
                    if(time() < $period['final'] || Specific::Admin() == true || Specific::Academic() == true){
                        $court = array('first', 'second', 'third')[$pos];
                        $TEMP['!authorization'] = $dba->query('SELECT court FROM authorization WHERE status = "authorized" AND period_id = '.$period['id'].' AND course_id = '.$note['course_id'])->fetchAll(false);
                        if(in_array($court, $TEMP['!authorization']) || Specific::Admin() == true || Specific::Academic() == true){
                            if (empty($errors)) {
                                $note_mode = $dba->query('SELECT note_mode FROM plan p WHERE (SELECT plan_id FROM curriculum WHERE course_id = '.$note['course_id'].' AND plan_id = p.id) = id')->fetchArray();
                                $notes = $noq = $params;
                                if(isset($pos)){
                                    $notes = $note['notes'];
                                    $notes = json_decode($notes, true);
                                    $notes[$pos] = $params;
                                    $noq = $notes[$pos];
                                }
                                $noqtrues = array();
                                for ($i=0; $i < count($noq); $i++) {
                                    $noqtrues[] = true;
                                    if((substr_count($noq[$i], '.') == 0 && !is_numeric($noq[$i])) || (substr_count($noq[$i], '.') == 1 && strlen($noq[$i]) == 2) || substr_count($noq[$i], '.') > 1 || ctype_alpha($noq[$i]) == true || ctype_space($noq[$id]) == true){
                                        $noqtrues[] = false;
                                    }
                                }
                                if(!in_array(false, $noqtrues)){
                                    if(((($notes[0][0]*0.3)+($notes[1][0]*0.3)) >= $TEMP['#nnevf']) || $note_mode == '100' || in_array($pos, array(0, 1))){
                                        if($dba->query('UPDATE note SET notes = ? WHERE id = '.$id, json_encode($notes))->returnStatus()){
                                            $background = 'false';
                                            $approved = 'false';
                                            $notesa = $dba->query('SELECT * FROM note WHERE user_id = '.$note['user_id'].' AND period_id = '.$note['period_id'].' AND program_id = '.$note['program_id'])->fetchAll();

                                            foreach ($notesa as $notea) {
                                                $params = $dba->query('SELECT parameters FROM parameter p WHERE (SELECT id FROM teacher WHERE course_id = '.$notea['course_id'].' AND period_id = '.$notea['period_id'].' AND id = p.teacher_id) = teacher_id')->fetchArray();
                                                $notes = json_decode($notea['notes'], true);
                                                
                                                $type = $dba->query('SELECT type FROM course WHERE id = '.$notea['course_id'])->fetchArray();

                                                if($notea['id'] == $id){
                                                    $final = $dba->query('SELECT final FROM period WHERE id = '.$notea['period_id'])->fetchArray();
                                                }
                                                

                                                $qualification = $dba->query('SELECT note FROM qualification WHERE note_id = '.$notea['id'])->fetchArray();

                                                if($note_mode == '30-30-40'){
                                                    for ($i=0; $i < 3; $i++) { 
                                                        $anotes = array();
                                                        $parameters = json_decode($params, true)[$i];
                                                        foreach ($parameters as $key => $param) {
                                                            $anotes[] = (($notes[$i][$key]/100)*$param['percent']);
                                                        }
                                                        $notes[$i] = array_sum($anotes);
                                                    }

                                                    if($notea['id'] == $id){
                                                        $last_eval = 'false';
                                                        if((($notes[0]*0.3)+($notes[1]*0.3)) >= $TEMP['#nnevf']){
                                                            $last_eval = 'true';
                                                        } else {
                                                            if($notes[2] > 0){
                                                                $third = 0;
                                                            }
                                                        }
                                                        $third = $notes[2];
                                                    }

                                                    $evalfinal = 'false';
                                                    if($qualification == NULL){
                                                        $abe = round((($notes[0]*0.3)+($notes[1]*0.3)+($notes[2]*0.4)), 2);
                                                        if($abe >= 0 && $notes[2] < $TEMP['#nmtc']){
                                                            $ab = $average[] = round($notes[2], 2);
                                                            if($notea['id'] == $id){
                                                                $evalfinal = 'true';
                                                                $ave = round($notes[2], 2);
                                                            }
                                                        } else {
                                                            $ab = $average[] = $abe;
                                                            if($notea['id'] == $id){
                                                                $ave = $abe;
                                                            }
                                                        }
                                                    } else {
                                                        $ab = $average[] = $qualification;
                                                        if($notea['id'] == $id){
                                                            $ave = $qualification;
                                                        }
                                                    }
                                                    if($notea['id'] == $id){
                                                        $not = round($notes[$pos], 2);
                                                    }
                                                } else {
                                                    $anotes = array();
                                                    $parameters = json_decode($params, true);
                                                    foreach ($parameters as $key => $param) {
                                                        $anotes[] = (($notes[$key]/100)*$param['percent']);
                                                    }
                                                    $notes = array_sum($anotes);

                                                    if($qualification == NULL){
                                                        $ab = $average[] = round($notes, 2);
                                                        if($notea['id'] == $id){
                                                            $ave = round($notes, 2);
                                                        }
                                                    } else {
                                                        $ab = $average[] = $qualification;
                                                        if($notea['id'] == $id){
                                                            $ave = $qualification;
                                                        }
                                                    }
                                                    if($notea['id'] == $id){
                                                        $not = round($notes, 2);
                                                    }
                                                }

                                                if(!($type == 'practice' && $ab >= $TEMP['#nmcnt']) || ($type == 'theoretical' && $ab >= $TEMP['#nmct'])){
                                                    $avekey[] = $ab;
                                                }

                                                if($notea['id'] == $id){
                                                    if(($type == 'practice' && $ave >= $TEMP['#nmcnt']) || ($type == 'theoretical' && $ave >= $TEMP['#nmct'])){
                                                        $approved = 'true';
                                                    }
                                                    if($approved == true || time() < $final){
                                                        $background = 'true';
                                                    }
                                                }
                                            }

                                            $average = round(array_sum($average)/count($average), 2);

                                            $semesbad = 'false';
                                            if($average < $TEMP['#nmcs']){
                                                $semesbad = 'true';
                                            }

                                            if(Specific::ValidateDates($note['period_id'], 17, 2) == true && count($avekey) >= $TEMP['#cers']){
                                                $semesbad = 'true';
                                            }

                                            $deliver = array(
                                                'status' => 200,
                                                'note' => $not,
                                                'average' => $average,
                                                'semesbad' => $semesbad,
                                                'ave' => $ave,
                                                'approved' => $approved,
                                                'background' => $background,
                                                'last_eval' => $last_eval,
                                                'third' => $third,
                                                'evalfinal' => $evalfinal
                                            );
                                            if(Specific::Teacher() == true){
                                                Specific::SendNotification(array(
                                                    'from_id' => $TEMP['#user']['id'],
                                                    'to_id' => $note['user_id'],
                                                    'course_id' => $note['user_id'],
                                                    'type' => "'note'",
                                                    'time' => time()
                                                ));
                                            }
                                        }
                                    }
                                } else {
                                    $deliver = array(
                                        'status' => 400,
                                        'error' => $TEMP['#word']['please_enter_valid_value']
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
                                'error' => $TEMP['#word']['you_authorized_upload_grades_course']
                            );
                        }
                    } else {
                        $deliver = array(
                            'status' => 400,
                            'error' => $TEMP['#word']['deadline_uploading_notes_already_passed']
                        );
                    }
                }
            } else {
                $deliver = array(
                    'status' => 400,
                    'emptys' => $emptys
                );
            } 
        }
    } else if($one == 'this-qualification') {
        $note = Specific::Filter($_POST['note']);
        $note_id = Specific::Filter($_POST['note_id']);
        $period_id = Specific::Filter($_POST['period_id']);

        if(!empty($note_id)){
            $qualification = $dba->query('SELECT note_id, status, COUNT(*) as count FROM qualification WHERE note_id = '.$note_id)->fetchArray();
            $final = $dba->query('SELECT final FROM period WHERE id = '.$period_id)->fetchArray();

            if(((Specific::Teacher() == true && Specific::ValidateDates($period_id, 17, 2) == true && Specific::ValidateDates($period_id, 13) == false && $qualification['status'] == 'accepted') && time() < $final || Specific::Admin() == true || Specific::Academic() == true) && $qualification['count'] > 0){
                if(isset($note)){
                    $course = $dba->query('SELECT id, qualification, type FROM course c WHERE (SELECT course_id FROM note WHERE id = '.$note_id.' AND course_id = c.id) = id')->fetchArray();
                    if(($note >= 3.5 && $course['type'] == 'practice') || $course['type'] == 'theoretical' || $note == 0){
                        if(($note >= 3.2 && $course['type'] == 'theoretical') || $course['type'] == 'practice' || $note == 0){
                            if($note >= 0){
                                if($note <= $TEMP['#nm']){
                                    if($dba->query('UPDATE qualification SET note = "'.$note.'", status = "accepted" WHERE note_id = '.$note_id)->returnStatus()){
                                        $deliver = array(
                                            'status' => 200,
                                            'note' => $note
                                        );
                                        if(Specific::Teacher() == true){
                                            Specific::SendNotification(array(
                                                'from_id' => $TEMP['#user']['id'],
                                                'to_id' => $dba->query('SELECT user_id FROM note WHERE id = '.$qualification['note_id'])->fetchArray(),
                                                'course_id' => $course['id'],
                                                'type' => "'qualification_note'",
                                                'time' => time()
                                            ));
                                        }
                                    }
                                } else {
                                    $deliver = array(
                                        'status' => 400,
                                        'error' => $TEMP['#word']['the_maximum_grade_is']
                                    );
                                }
                            } else {
                                $deliver = array(
                                    'status' => 400,
                                    'error' => $TEMP['#word']['please_enter_valid_value']
                                );
                            }
                        } else {
                            $deliver = array(
                                'status' => 400,
                                'error' => "{$TEMP['#word']['minimum_mark_must_be_equal_greater_than']} {$TEMP['#nmat']}"
                            );
                        }
                    } else {
                        $deliver = array(
                            'status' => 400,
                            'error' => "{$TEMP['#word']['minimum_mark_must_be_equal_greater_than']} {$TEMP['#nmant']}"
                        );
                    }
                } else {
                    $deliver = array(
                        'status' => 400,
                        'empty' => $TEMP['#word']['please_complete_the_information']
                    );
                }
            }
        }
    } else if($one == 'search-authorizations') {
        $keyword = Specific::Filter($_POST['keyword']);
            $html = '';
            $query = '';
            if(!empty($keyword)){
                $query .= " a WHERE (SELECT id FROM teacher WHERE user_id = ".$TEMP['#user']['id']." AND id = a.teacher_id) = teacher_id AND (id LIKE '%$keyword%' OR (SELECT id FROM user WHERE (dni LIKE '%$keyword%' OR names LIKE '%$keyword%' OR surnames LIKE '%$keyword%') AND id = a.user_id) = user_id OR (SELECT id FROM course WHERE (code LIKE '%$keyword%' OR name LIKE '%$keyword%') AND id = a.course_id) = course_id)";
            }
            $authorizations = $dba->query('SELECT * FROM authorization'.$query.' LIMIT ? OFFSET ?', 10, 1)->fetchAll();
            $deliver['total_pages'] = $dba->totalPages;
            if(!empty($authorizations)){
                foreach ($authorizations as $auth) {
                    $TEMP['!id'] = $auth['id'];
                    $TEMP['!academic'] = $auth['status'] == 'pending' ? $TEMP['#word']['pending'] : $dba->query('SELECT names FROM user WHERE id = '.$auth['user_id'])->fetchArray();
                    $TEMP['!teacher'] = $dba->query('SELECT names FROM user u WHERE (SELECT user_id FROM teacher WHERE id = '.$auth['teacher_id'].' AND user_id = u.id) = id')->fetchArray();
                    $TEMP['!course'] = $dba->query('SELECT name FROM course WHERE id = '.$auth['course_id'])->fetchArray();
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
                $query .= " a WHERE (SELECT id FROM teacher WHERE user_id = ".$TEMP['#user']['id']." AND id = a.teacher_id) = teacher_id AND (id LIKE '%$keyword%' OR (SELECT id FROM user WHERE (dni LIKE '%$keyword%' OR names LIKE '%$keyword%' OR surnames LIKE '%$keyword%') AND id = a.user_id) = user_id OR (SELECT id FROM course WHERE (code LIKE '%$keyword%' OR name LIKE '%$keyword%') AND id = a.course_id) = course_id)";
            }
            $authorizations = $dba->query('SELECT * FROM authorization'.$query.' LIMIT ? OFFSET ?', 10, $page)->fetchAll();
            if (!empty($authorizations)) {
                foreach ($authorizations as $auth) {
                    $TEMP['!id'] = $auth['id'];
                    $TEMP['!academic'] = $auth['status'] == 'pending' ? $TEMP['#word']['pending'] : $dba->query('SELECT names FROM user WHERE id = '.$auth['user_id'])->fetchArray();
                    $TEMP['!teacher'] = $dba->query('SELECT names FROM user u WHERE (SELECT user_id FROM teacher WHERE id = '.$auth['teacher_id'].' AND user_id = u.id) = id')->fetchArray();
                    $TEMP['!course'] = $dba->query('SELECT name FROM course WHERE id = '.$auth['course_id'])->fetchArray();
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
            $note_mode = $dba->query('SELECT note_mode FROM plan p WHERE (SELECT plan_id FROM curriculum WHERE course_id = '.$course_id.' AND plan_id = p.id) = id')->fetchArray();
            $courses = $dba->query('SELECT course_id FROM teacher t WHERE user_id = '.$TEMP['#user']['id'].' AND (SELECT id FROM period WHERE final > '.time().' AND id = t.period_id) = period_id')->fetchAll(false);
            $teacher_id = $dba->query('SELECT id FROM teacher WHERE user_id = '.$TEMP['#user']['id'].' AND course_id = '.$course_id)->fetchArray();
            $authorization = $dba->query('SELECT status, COUNT(*) AS count FROM authorization WHERE teacher_id = '.$teacher_id.' AND course_id = '.$course_id.' AND court = "'.$court.'" AND status = "pending"')->fetchArray();

            if(($type == 'add' && $authorization['count'] == 0) || ($type == 'edit' && $authorization['status'] == 'pending')){
                if(!in_array($course_id, $dba->query('SELECT id FROM course WHERE id IN ((SELECT course_id FROM teacher WHERE user_id = '.$TEMP['#user']['id'].'))')->fetchAll(false))){
                    $errors[] = 'course_id';
                }
                if($note_mode == '30-30-40' && !in_array($court, $courts)){
                    $errors[] = 'court';
                }
                if((($note_mode == '100' && $court == 'unique') || $note_mode == '30-30-40') && in_array($course_id, $courses)){
                    if (empty($errors)) {
                        $period = $dba->query('SELECT *, COUNT(*) AS count FROM period WHERE status = "enabled" AND start < '.time().' AND final > '.time())->fetchArray();
                        if($period['count'] > 0){
                            if($dba->query('SELECT COUNT(*) FROM authorization WHERE status <> "denied" AND course_id = "'.$course_id.'" AND court = "'.$court.'" AND period_id = '.$period['id'])->fetchArray() == 0){
                                if($type == 'add'){
                                    if($dba->query('INSERT INTO authorization (user_id, teacher_id, course_id, description, court, expires, status, `time`) VALUES (0, '.$teacher_id.', '.$course_id.', "'.$description.'", "'.$court.'", 0, "pending", '.time().')')->returnStatus()){
                                        $deliver['status'] = 200;
                                        $users = $dba->query('SELECT id FROM user WHERE role = "admin" AND role = "academic"')->fetchAll();
                                        foreach ($users as $user) {
                                            Specific::SendNotification(array(
                                                'from_id' => $TEMP['#user']['id'],
                                                'to_id' => $user['id'],
                                                'course_id' => $course_id,
                                                'type' => "'authorize'",
                                                'time' => time()
                                            ));
                                        }
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
                    }
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
        $id = Specific::Filter($_POST['id']);
        if(isset($id) && is_numeric($id)){
            $note_mode = $dba->query('SELECT note_mode FROM plan p WHERE (SELECT plan_id FROM curriculum WHERE course_id = '.$id.' AND plan_id = p.id) = id')->fetchArray();
            if(!empty($note_mode)){
                $deliver = array(
                    'status' => 200,
                    'note_mode' => $note_mode
                );
            }
        }
    } else if($one == 'get-aitems'){
        $id = Specific::Filter($_POST['id']);
        if(!empty($id) && is_numeric($id)){
            $items = $dba->query('SELECT course_id, description, court, status FROM authorization WHERE id = '.$id)->fetchArray();
            if (!empty($items)) {
                $deliver = array(
                    'status' => 200,
                    'items' => $items
                );
            }
        }
    } else if($one == 'upload-observation'){
        $note_id = Specific::Filter($_POST['id']);
        $observation = Specific::Filter($_POST['observation']);

        if(!empty($note_id) && is_numeric($note_id) && strlen($observation) <= 500){
            if(empty($observation)){
                $observation = NULL;
            }
            if($dba->query('UPDATE note SET observation = ? WHERE id = '.$note_id, $observation)->returnStatus()){
                $deliver['status'] = 200;
            }
        }
    }
}
?>