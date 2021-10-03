<?php
if ($TEMP['#loggedin'] === false || $TEMP['#settings']['upload_videos'] == 'off') {
    $deliver = array(
        'status' => 400,
        'error' => $TEMP['#word']['error']
    );
    echo json_encode($deliver);
    exit();
} else if($one == 'upload-thumbnail') {
    if (!empty($_FILES['thumbnail']['tmp_name'])) {
        $file_info   = array(
            'file' => $_FILES['thumbnail']['tmp_name'],
            'size' => $_FILES['thumbnail']['size'],
            'name' => $_FILES['thumbnail']['name'],
            'type' => $_FILES['thumbnail']['type'],
            'from' => 'thumbnail',
            'crop' => array(
                'width' => 1076,
                'height' => 604
            )
        );
        $thumb_draft = Specific::Filter($_GET['draft_thumb']);
        if (!empty($thumb_draft) && strpos($thumb_draft, '_thumbnail')) {
            unlink($thumb_draft);
        }
        $file_data = Specific::UploadImage($file_info);
        if (!empty($file_data)) {
            $thumbnail = $file_data;
            $_SESSION['uploads']['images'][] = $thumbnail;
            $deliver = array(
                'status' => 200,
                'thumbnail' => $thumbnail,
                'media_thumbnail' => Specific::GetFile($thumbnail)
            );
        }
    }
}
?>