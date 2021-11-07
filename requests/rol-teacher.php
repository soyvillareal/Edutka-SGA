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
        $courses = $dba->query('SELECT * FROM courses'.$query.' LIMIT ? OFFSET ?', 10, 1)->fetchAll();
        $deliver['total_pages'] = $dba->totalPages;
        if (!empty($courses)) {
            foreach ($courses as $course) {
                $teachers = $dba->query('SELECT names FROM users WHERE id IN ('.$course['teacher'].')')->fetchAll(false);
				$enrolled = $dba->query('SELECT COUNT(*) FROM enrolled WHERE course_id = '.$course['id'])->fetchArray();
                if(count($teachers) == 2){
                    $teachers = $teachers[0]. ' y ' .$teachers[1];
                } else if(count($teachers) > 2){
                    $and = end($teachers);
                    array_pop($teachers);
                    $teachers = implode(', ', $teachers). ' y '. $and;
                } else {
                    $teachers = $teachers[0];
                }
                $TEMP['!id'] = $course['id'];
                $TEMP['!code'] = $course['code'];
                $TEMP['!name'] = $course['name'];
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
        $TEMP['#periods'] = $dba->query('SELECT * FROM periods')->fetchAll();
        $TEMP['#programs'] = $dba->query('SELECT * FROM programs')->fetchAll();
        $courses = $dba->query('SELECT * FROM courses'.$query.' LIMIT ? OFFSET ?', 10, $page)->fetchAll();
        if (!empty($courses)) {
            foreach ($courses as $course) {
                $teachers = $dba->query('SELECT names FROM users WHERE id IN ('.$course['teacher'].')')->fetchAll(false);
				$enrolled = $dba->query('SELECT COUNT(*) FROM enrolled WHERE course_id = '.$course['id'])->fetchArray();
                if(count($teachers) == 2){
                    $teachers = $teachers[0]. ' y ' .$teachers[1];
                } else if(count($teachers) > 2){
                    $and = end($teachers);
                    array_pop($teachers);
                    $teachers = implode(', ', $teachers). ' y '. $and;
                } else {
                    $teachers = $teachers[0];
                }
                $TEMP['!id'] = $course['id'];
                $TEMP['!code'] = $course['code'];
                $TEMP['!name'] = $course['name'];
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
}
?>