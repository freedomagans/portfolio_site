<?php

require_once 'BaseModel.php';

/**
 * defining subclass of BaseModel class to model Projects table
 */
class Project extends BaseModel
{
    protected $table = 'projects'; // specifying table(projects)

    /**
     * method to create new project row in Projects table
     * @param mixed $title
     * @param mixed $slug
     * @param mixed $description
     * @param mixed $image1
     * @param mixed $image2
     * @param mixed $image3
     * @param mixed $live_url
     * @param mixed $github_url
     * @param mixed $is_published
     * @return bool
     */
    public function create($title, $slug, $description, $image1, $image2, $image3, $live_url, $github_url, $is_published = 1)
    {
        $query = "INSERT INTO {$this->table} 
        (title, slug, description, image1, image2, image3, live_url, github_url, is_published)
        VALUES 
        (:title, :slug, :description, :image1, :image2, :image3, :live_url, :github_url, :is_published)";

        $stmt = $this->conn->prepare($query);

        // binding parameters to values;
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':slug',  $slug);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':image1', $image1);
        $stmt->bindParam(':image2', $image2);
        $stmt->bindParam(':image3', $image3);
        $stmt->bindParam(':live_url', $live_url);
        $stmt->bindParam(':github_url', $github_url);
        $stmt->bindParam(':is_published', $is_published, PDO::PARAM_INT);

        return $stmt->execute(); // returns boolean value
    }

    /**
     * method to update project row in projects table
     * @param int $id
     * @param mixed $title
     * @param mixed $slug
     * @param mixed $description
     * @param mixed $image1
     * @param mixed $image2
     * @param mixed $image3
     * @param mixed $live_url
     * @param mixed $github_url
     * @param mixed $is_published
     * @return bool
     */
    public function update($id, $title, $slug, $description, $image1, $image2, $image3, $live_url, $github_url, $is_published)
    {
        $query = "UPDATE {$this->table} SET 
            title = :title,
            slug = :slug,
            description = :description,
            image1 = :image1,
            image2 = :image2,
            image3 = :image3,
            live_url = :live_url,
            github_url = :github_url,
            is_published = :is_published
            WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // binding parameters to values 
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':slug', $slug);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':image1', $image1);
        $stmt->bindParam(':image2', $image2);
        $stmt->bindParam(':image3', $image3);
        $stmt->bindParam(':live_url', $live_url);
        $stmt->bindParam(':github_url', $github_url);
        $stmt->bindParam(':is_published', $is_published, PDO::PARAM_INT);

        return $stmt->execute(); // returns boolean value
    }

    /**
     * method to get project row by slug from Projects table
     * @param mixed $slug
     * @return array|false
     */
    public function getBySlug($slug)
    {

        $query = "SELECT * FROM {$this->table} WHERE slug = :slug LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':slug', $slug);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * method to publish draft project row in Projects table
     * @param int $id
     * @return bool
     */
    public function Publish($id)
    {
        $query = "UPDATE {$this->table} SET is_published = :status WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindValue(':status', 1, PDO::PARAM_INT);
        return $stmt->execute(); // returns boolean value
    }


    /**
     * method to get published project rows based on pagination
     * @param int $start
     * @param int $perPage
     * @return array
     */
    public function getPublishedPaginated($start, $perPage)
    {
        $query = "SELECT * FROM {$this->table} 
                 WHERE is_published = 1 ORDER BY 
                 created_at DESC LIMIT :start, :perPage";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':start', $start, PDO::PARAM_INT);
        $stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * method to get rows of projects table 
     * based on pagination start and perpage parameters
     * @param mixed $start
     * @param mixed $perPage
     * @return array
     */
    public function getPaginatedAll($start, $perPage)
    {
        $query = "SELECT * FROM {$this->table} ORDER BY created_at DESC LIMIT :start, :perPage";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':start', $start, PDO::PARAM_INT);
        $stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * method to retrieve paginated result 
     * set of rows of Projects table based on status (published or draft);
     * @param mixed $start
     * @param mixed $perPage
     * @param mixed $status
     * @return array
     */
    public function getPaginatedByStatus($status, $start, $perPage)
    {
        $query = "SELECT * FROM {$this->table} WHERE is_published = :status ORDER BY created_at DESC LIMIT :start, :perPage"; 
        $stmt = $this->conn->prepare($query); 
        $stmt->bindValue(':status', $status, PDO::PARAM_INT);
        $stmt->bindValue(':start', $start, PDO::PARAM_INT);
        $stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);
        $stmt->execute(); 
        return $stmt->fetchAll(PDO::FETCH_ASSOC); //return result set
    }

    /**
     * method to count number of rows in Projects table based on status (published or draft);
     * @param mixed $status
     * @return int
     */
    public function countByStatus($status)
    {
        $query = "SELECT COUNT(*) as total FROM {$this->table} WHERE is_published = :status"; 
        $stmt = $this->conn->prepare($query); 
        $stmt->bindValue(':status', $status, PDO::PARAM_INT);
        $stmt->execute(); 
        $row = $stmt->fetch(PDO::FETCH_ASSOC); 
        return $row['total']; 
    }
}
