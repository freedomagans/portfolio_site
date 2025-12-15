<?php
require_once MODELS_PATH.'ProjectLikeModel.php'; // import ProjectLikeModel
$likeModel = new ProjectLike(); // ProjectLike instance

$projectId = intval($_GET['id']); // retrieve project id
$ipHash = hash('sha256', $_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT']);

header('Content-Type: application/json'); // set header based on ajax

try {
    $hasLiked = $likeModel->hasLiked($projectId, $ipHash); // check if visitor with ip has liked 

    /**
     * if visitor has previously liked remove like instance to unlike
     * else addlike instance to like
     */
    if($hasLiked){
        $likeModel->removeLike($projectId, $ipHash);
        $liked = false;
    } else {
        $likeModel->addLike($projectId, $ipHash);
        $liked = true;
    }

    $likes = $likeModel->countByProject($projectId); // retrieve number of likes for specific project

    echo json_encode([
        'success' => true,
        'liked' => $liked,
        'likes' => $likes
    ]); // return json response for ajax

} catch(Exception $e){
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}