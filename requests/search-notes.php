<?php 
$deliver['status'] = 400;
if (!empty($_POST['keyword'])) {
    $html = '';
    $keyword = Specific::Filter($_POST['keyword']);
    $program_id = !empty($_GET['program_id']) ? '&program='.Specific::Filter($_GET['program_id']) : '';
    $period_id = !empty($_GET['period_id']) ? '&period='.Specific::Filter($_GET['period_id']) : '';

    $users = $dba->query('SELECT * FROM users WHERE names LIKE "%'.$keyword.'%" OR surnames LIKE "%'.$keyword.'%" LIMIT 10')->fetchAll();

    if (!empty($users)) {
        foreach ($users as $user) {
            $html .= "<a class='display-flex padding-10 background-hover' href='".Specific::Url("notes?keyword=$keyword&user={$user['id']}")."' target='_self'><div class='margin-right-auto color-black'>".$user['names'].' '.$user['surnames'].'</div></a>';
        }
        $deliver = array(
            'status' => 200,
            'html' => $html
        );
    }
}
?>