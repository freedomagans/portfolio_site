<?php

require_once __DIR__ . '/../config/Database.php'; // import the Database definition class;

/**
 * defining the base database Model class for all DB Models;
 */

class BaseModel
{

    public $conn; // declaring connection variable
    protected $table; // declaring table variable

    /**
     *  defining the constructor of the class
     *  the constructor instantiates the database 
     *  and retrieves the connection(conn) instance
     */
    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect();
    }


    /**
     * defining method to retrieve all rows of table
     * @return array
     */
    public function getRows(): array
    {
        $query = "SELECT * FROM {$this->table}";
        $p_stmt = $this->conn->prepare($query);
        $p_stmt->execute();
        return $p_stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * defining method to retrieve a row 
     * of a table with specified ID; 
     * @param int $id
     * @return array
     */
    public function getById($id)
    {
        $query = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1";
        $p_stmt = $this->conn->prepare($query);
        $p_stmt->bindParam(":id", $id);
        $p_stmt->execute();
        return $p_stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * defining method to delete row 
     * of table for specified ID;
     * @param int $id
     * @return bool
     */

    public function delete($id)
    {
        $query = "DELETE FROM {$this->table} WHERE id = :id";
        $p_stmt = $this->conn->prepare($query);
        $p_stmt->bindParam(':id', $id);
        return $p_stmt->execute(); // returns boolean value 
    }

    /**
     * defining function to delete
     *  all rows of table;
     * @return bool
     */
    public function delete_all()
    {
        $query = "DELETE FROM {$this->table}";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute(); // returns boolean value
    }

    /**
     * defining method to retrieve total
     *  number of rows of table 
     * @return int
     */
    public function count()
    {
        $query = "SELECT COUNT(*) as total FROM {$this->table}";
        $stmt = $this->conn->query($query);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    /**
     * defining method to retrieve
     * latest specified nuber of rows of table
     * @param $numOfRows
     * @return array
     */
    public function getLatest($numOfRows)
    {
        $query = "SELECT * FROM {$this->table} ORDER BY created_at DESC LIMIT :numOfRows
        "; 
        $p_stmt = $this->conn->prepare($query); 
        $p_stmt->bindParam(':numOfRows', $numOfRows,PDO::PARAM_INT);
        $p_stmt->execute(); 
        return $p_stmt->fetchAll(PDO::FETCH_ASSOC); 

    }
}
