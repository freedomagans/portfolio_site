<?php

require_once __DIR__ . "/BaseModel.php";

class ProjectView extends BaseModel
{
    protected $table = "project_views";

    // Count views for a project
    public function countByProject($projectId)
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM {$this->table} WHERE project_id = ?");
        $stmt->execute([$projectId]);
        return (int)$stmt->fetchColumn();
    }

    // Check if this visitor has already viewed
    public function hasViewed($projectId, $ipHash)
    {
        $stmt = $this->conn->prepare("SELECT id FROM {$this->table} WHERE project_id=? AND ip_hash=? LIMIT 1");
        $stmt->execute([$projectId, $ipHash]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ? true : false;
    }

    // Add new view
    public function addView($projectId, $ipHash)
    {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} (project_id, ip_hash) VALUES (?, ?)");
        return $stmt->execute([$projectId, $ipHash]);
    }
}