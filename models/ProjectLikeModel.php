<?php

require_once __DIR__ . "/BaseModel.php";

class ProjectLike extends BaseModel
{
    protected $table = "project_likes";

    // Count likes
    public function countByProject($projectId)
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM {$this->table} WHERE project_id = ?");
        $stmt->execute([$projectId]);
        return (int)$stmt->fetchColumn();
    }

    // Check if visitor already liked
    public function hasLiked($projectId, $ipHash)
    {
        $stmt = $this->conn->prepare("SELECT id FROM {$this->table} WHERE project_id=? AND ip_hash=? LIMIT 1");
        $stmt->execute([$projectId, $ipHash]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ? true : false;
    }

    // Add like
    public function addLike($projectId, $ipHash)
    {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} (project_id, ip_hash) VALUES (?, ?)");
        return $stmt->execute([$projectId, $ipHash]);
    }

    // Remove like
    public function removeLike($projectId, $ipHash)
    {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE project_id=? AND ip_hash=?");
        return $stmt->execute([$projectId, $ipHash]);
    }
}