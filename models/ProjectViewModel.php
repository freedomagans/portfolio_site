<?php

require_once __DIR__ . "/BaseModel.php";

/**
 * defining ProjectView class as subclass of 
 * BaseModel database class to model ProjectViews table
 */
class ProjectView extends BaseModel
{
    protected $table = "project_views"; // specifying table(project_views)

    /**
     * method to retrieve number of views for project with specified ID
     * @param mixed $projectId
     * @return int
     */
    public function countByProject($projectId)
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM {$this->table} WHERE project_id = ?");
        $stmt->execute([$projectId]);
        return (int)$stmt->fetchColumn();
    }

    /**
     * method to confirm if visitor with ip already viewed 
     * project with specified ID
     * @param mixed $projectId
     * @param mixed $ipHash
     * @return bool
     */
    public function hasViewed($projectId, $ipHash)
    {
        $stmt = $this->conn->prepare("SELECT id FROM {$this->table} WHERE project_id=? AND ip_hash=? LIMIT 1");
        $stmt->execute([$projectId, $ipHash]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ? true : false;
    }

    /**
     * method to save view row for visitor 
     * with specified ip
     * @param mixed $projectId
     * @param mixed $ipHash
     * @return bool
     */
    public function addView($projectId, $ipHash)
    {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} (project_id, ip_hash) VALUES (?, ?)");
        return $stmt->execute([$projectId, $ipHash]);
    }
}