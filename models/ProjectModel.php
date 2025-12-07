<?php

require_once 'BaseModel.php';

class Project extends BaseModel
{
    /**
     * Summary of table
     * defining subclass of BaseModel class to model Projects table
     */
    
    protected $table = 'projects'; // specifying table(projects)


    public function create($title, $slug, $description, $image1, $image2, $image3, $live_url, $github_url, $is_published = 1)
    {
        /**
         * defining method to create a row in projects table
         */

        $query = "INSERT INTO {$this->table} 
        (title, slug, description, image1, image2, image3, live_url, github_url, is_published)
        VALUES 
        (:title, :slug, :description, :image1, :image2, :image3, :live_url, :github_url, :is_published)"; // query

        $stmt = $this->conn->prepare($query); // prepared statement

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

        return $stmt->execute(); // execute statement
    }


    public function update($id, $title, $slug, $description, $image1, $image2, $image3, $live_url, $github_url, $is_published)
    {
        /**
         * defining method to update row of projects table;
         */

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
            WHERE id = :id"; // query

        $stmt = $this->conn->prepare($query); // prepared statement

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

        return $stmt->execute(); // execute statement
    }

    public function getBySlug($slug)
    {
        /**
         * defining method to retrieve row of Projects table with slug;
         */

        $query = "SELECT * FROM {$this->table} WHERE slug = :slug LIMIT 1"; // query
        $stmt = $this->conn->prepare($query); // prepared statement;
        $stmt->bindParam(':slug', $slug); // bind parameter to value
        $stmt->execute(); // execute statement
        return $stmt->fetch(PDO::FETCH_ASSOC); // return result set
    }

    public function Publish($id)
    {
        /**
         * defining method to publish draft project rows in 
         * projects table;
         */

        $query = "UPDATE {$this->table} SET is_published = :status WHERE id = :id"; // query
        $stmt = $this->conn->prepare($query); // prepared statement
        // binding parameters to values
        $stmt->bindParam(':id', $id);
        $stmt->bindValue(':status', 1, PDO::PARAM_INT);
        return $stmt->execute(); // execute statement
    }

    public function getPaginated($start, $perPage)
    {
        /**
         * defining method to get rows of Projects table 
         * based on pagination start and perpage parameters;
         */

        $query = "SELECT * FROM {$this->table} ORDER BY created_at DESC
                LIMIT :start, :perPage"; // query
        $stmt = $this->conn->prepare($query); // prepared statement
        // binding paramets to values;
        $stmt->bindValue(':start', $start, PDO::PARAM_INT);
        $stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);
        $stmt->execute(); // execute statement
        return $stmt->fetchAll(PDO::FETCH_ASSOC); //return result set
    }

    
    public function getPublishedPaginated($start, $perPage)
    {
        /**
         * defining method to get published rows of Projects table
         * based on pagination start and perpage parameters;
         */

        $query = "SELECT * FROM {$this->table} WHERE is_published = 1 ORDER BY created_at DESC
                LIMIT :start, :perPage"; // query
        $stmt = $this->conn->prepare($query); // prepared statement
        // binding paramets to values;
        $stmt->bindValue(':start', $start, PDO::PARAM_INT);
        $stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);
        $stmt->execute(); // execute statement
        return $stmt->fetchAll(PDO::FETCH_ASSOC); //return result set
    }

    public function getPaginatedAll($start, $perPage)
    {
        /**
         * defining method to get all rows of Projects table
         * based on pagination start and perpage parameters;
         */

        $query = "SELECT * FROM {$this->table} ORDER BY created_at DESC
                LIMIT :start, :perPage"; // query
        $stmt = $this->conn->prepare($query); // prepared statement
        // binding paramets to values;
        $stmt->bindValue(':start', $start, PDO::PARAM_INT);
        $stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);
        $stmt->execute(); // execute statement
        return $stmt->fetchAll(PDO::FETCH_ASSOC); //return result set
    }

    public function getPaginatedByStatus($status, $start, $perPage)
    {
        /**
         * defining method to get rows of Projects table by status
         * based on pagination start and perpage parameters;
         */

        $query = "SELECT * FROM {$this->table} WHERE is_published = :status ORDER BY created_at DESC
                LIMIT :start, :perPage"; // query
        $stmt = $this->conn->prepare($query); // prepared statement
        // binding paramets to values;
        $stmt->bindValue(':status', $status, PDO::PARAM_INT);
        $stmt->bindValue(':start', $start, PDO::PARAM_INT);
        $stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);
        $stmt->execute(); // execute statement
        return $stmt->fetchAll(PDO::FETCH_ASSOC); //return result set
    }

    public function countByStatus($status)
    {
        /**
         * defining method to count rows of Projects table by status
         */
        $query = "SELECT COUNT(*) as total FROM {$this->table} WHERE is_published = :status"; // query
        $stmt = $this->conn->prepare($query); // prepared statement
        $stmt->bindValue(':status', $status, PDO::PARAM_INT);
        $stmt->execute(); // execute statement
        $row = $stmt->fetch(PDO::FETCH_ASSOC); // fetch result set
        return $row['total']; // return total number of rows
    }
}
