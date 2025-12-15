<?php

require_once __DIR__ . "/BaseModel.php";

/**
 * defining ProjectLike class as subclass of 
 * BaseModel database class to model ProjectLikes table
 */
class ProjectLike extends BaseModel
{
    protected $table = "project_likes"; // specifying table(project_likes)

    /**
     * method to retrieve number of likes for project with specified ID
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
     * method to confirm if visitor with ip already liked 
     * project with specified ID
     * @param mixed $projectId
     * @param mixed $ipHash
     * @return bool
     */
    public function hasLiked($projectId, $ipHash)
    {
        $stmt = $this->conn->prepare("SELECT id FROM {$this->table} WHERE project_id=? AND ip_hash=? LIMIT 1");
        $stmt->execute([$projectId, $ipHash]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ? true : false;
    }

    /**
     * method to save like row for visitor 
     * with specified ip
     * @param mixed $projectId
     * @param mixed $ipHash
     * @return bool
     */
    public function addLike($projectId, $ipHash)
    {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} (project_id, ip_hash) VALUES (?, ?)");
        return $stmt->execute([$projectId, $ipHash]);
    }

    /**
     * method to remove like row for visitor 
     * with specified ip
     * @param mixed $projectId
     * @param mixed $ipHash
     * @return bool
     */
    public function removeLike($projectId, $ipHash)
    {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE project_id=? AND ip_hash=?");
        return $stmt->execute([$projectId, $ipHash]);
    }
}