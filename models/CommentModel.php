<?php

require_once 'BaseModel.php';

class Comment extends BaseModel
{
    protected $table = "comments";

    /**
     * Create a new comment (anonymous user)
     */
    public function create($project_id, $name, $content, $is_approved = 0)
    {
        $query = "INSERT INTO {$this->table}
            (project_id, name, content, is_approved)
            VALUES
            (:project_id, :name, :content, :is_approved)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':project_id', $project_id, PDO::PARAM_INT);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':is_approved', $is_approved, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Get approved comments for a project (public)
     */
    public function getApprovedByProject($project_id)
    {
        $query = "SELECT * FROM {$this->table}
                  WHERE project_id = :project_id
                  AND is_approved = 1
                  ORDER BY created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':project_id', $project_id, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Count comments by approval status
     *
     * @param int $status (0 = pending, 1 = approved)
     */
    public function countByStatus($status)
    {
        $query = "SELECT COUNT(*) FROM {$this->table}
                  WHERE is_approved = :status";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchColumn();
    }

    /**
     * Count approved comments for a specific project
     *
     * @param int $project_id
     * @return int
     */
    public function countApprovedByProject($project_id)
    {
        $query = "SELECT COUNT(*) FROM {$this->table}
                  WHERE project_id = :project_id
                  AND is_approved = 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':project_id', $project_id, PDO::PARAM_INT);

        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    /**
     * Paginate comments filtered by approval status
     *
     * @param int $status (0 = pending, 1 = approved)
     */
    public function getPaginatedByStatus($status, $start, $perPage)
    {
        $query = "SELECT c.*, p.title AS project_title
                  FROM {$this->table} c
                  LEFT JOIN projects p ON c.project_id = p.id
                  WHERE c.is_approved = :status
                  ORDER BY c.created_at DESC
                  LIMIT :start, :perPage";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':status', $status, PDO::PARAM_INT);
        $stmt->bindValue(':start', $start, PDO::PARAM_INT);
        $stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPaginatedAll($start, $perPage)
    {
        $query = "SELECT c.*, p.title AS project_title
              FROM {$this->table} c
              LEFT JOIN projects p ON c.project_id = p.id
              ORDER BY c.created_at DESC
              LIMIT :start, :perPage";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':start', $start, PDO::PARAM_INT);
        $stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Approve a single comment
     */
    public function approve($id)
    {
        $query = "UPDATE {$this->table}
                  SET is_approved = 1
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Disapprove a single comment
     */
    public function disapprove($id)
    {
        $query = "UPDATE {$this->table}
                  SET is_approved = 0
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Approve all comments
     */
    public function approveAll()
    {
        $query = "UPDATE {$this->table} SET is_approved = 1";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute();
    }

    /**
     * Disapprove all comments
     */
    public function disapproveAll()
    {
        $query = "UPDATE {$this->table} SET is_approved = 0";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute();
    }

    /**
     * Update a comment (admin)
     */
    public function update($id, $name, $content)
    {
        $query = "UPDATE {$this->table} SET
                    name = :name,
                    content = :content
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':content', $content);

        return $stmt->execute();
    }
}
