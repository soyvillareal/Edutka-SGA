<?php 
if ($TEMP['#loggedin'] === false && Specific::Academic() === false) {
    $deliver = array(
        'status' => 400,
        'error' => $TEMP['#word']['error']
    );
    echo json_encode($deliver);
    exit();
}

if($one == 'add-program'){
    $name = Specific::Filter($_POST['name']);
    $title = Specific::Filter($_POST['title']);
    $snies = Specific::Filter($_POST['snies']);
    $level = Specific::Filter($_POST['level']);
    $semesters = Specific::Filter($_POST['semesters']);
    $mode = Specific::Filter($_POST['mode']);

    if(!empty($name) && !empty($title) && !empty($snies) && !empty($level) && !empty($semesters) && !empty($mode)){
        if($dba->query('INSERT INTO programs (name, title, snies, level, semesters, mode) VALUES ('.$name.','.$title.','.$snies.','.$level.','.$semesters.','.$mode.')')->returnStatus()){
            $deliver['status'] = 200;
        }
    }
}
?>
