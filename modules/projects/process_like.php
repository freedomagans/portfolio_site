<?php
require_once MODELS_PATH.'ProjectLikeModel.php';
$likeModel = new ProjectLike();

$projectId = intval($_GET['id']);
$ipHash = hash('sha256', $_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT']);

header('Content-Type: application/json');

try {
    $hasLiked = $likeModel->hasLiked($projectId, $ipHash);

    if($hasLiked){
        $likeModel->removeLike($projectId, $ipHash);
        $liked = false;
    } else {
        $likeModel->addLike($projectId, $ipHash);
        $liked = true;
    }

    $likes = $likeModel->countByProject($projectId);

    echo json_encode([
        'success' => true,
        'liked' => $liked,
        'likes' => $likes
    ]);
} catch(Exception $e){
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}