<?php  
/**
 * Like Button Component
 * Usage:
 * renderLikeButton($projectId, $likeCount, $hasLiked);
 */

function renderLikeButton($projectId, $likes, $hasLiked = false)
{
    $likedClass = $hasLiked ? 'liked' : '';
?>
<a href="javascript:void(0);"
   class="project-like-btn <?= $likedClass ?>"
   data-project-id="<?= $projectId ?>">
    <i class="fa-solid fa-heart heart-icon"></i>
    <span class="like-count"><?= $likes ?></span>
</a>


<?php 
} 
?>

<?php
/**
 * View Counter Component
 * Usage: renderViewBadge($views);
 */

function renderViewBadge($views)
{
?>
    <div class="project-view-badge">
        <i class="fa-solid fa-eye view-icon"></i>
        <span class="view-count"><?= number_format($views) ?></span>
    </div>
<?php
}
?>

<?php  
/**
 * Comments Button Component
 * Usage:
 * renderCommentsButton($projectId, $commentCount);
 */

function renderCommentsButton($projectId, $commentCount)
{
?>
    <a href="/urls.php?pg=view&id=<?= $projectId ?>#comment-form"
       class="project-comments-btn"
       data-project-id="<?= $projectId ?>">
        <i class="fa-solid fa-comment comment-icon"></i>
        <span class="comment-count"><?= number_format($commentCount) ?></span>
    </a>
<?php
}
?>
