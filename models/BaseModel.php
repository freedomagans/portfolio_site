<?php

require_once __DIR__ . '/../config/Database.php'; // import the Database definition class;

class BaseModel // defining the base class for all db models;
{

    public $conn; // declaring connection variable
    protected $table; // declaring table variable

    public function __construct()
    {
        /**
         *  defining the constructor of the class
         */

        $database = new Database(); // db instance
        $this->conn = $database->connect(); // instantiating db connection instance
    }


    public function getRows()
    {
        /**
         * defining method to retrieve all rows of table
         */

        $query = "SELECT * FROM {$this->table}"; // query
        $p_stmt = $this->conn->prepare($query); // prepared statement
        $p_stmt->execute(); // execute statement
        return $p_stmt->fetchAll(PDO::FETCH_ASSOC); // fetch result set

    }

    public function getById($id)
    {
        /**
         * defining method to retrieve a row of a table with specified ID;
         */

        $query = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1"; // query
        $p_stmt = $this->conn->prepare($query); // prepared statement 
        $p_stmt->bindParam(":id", $id);
        $p_stmt->execute(); // execute statment 
        return $p_stmt->fetch(PDO::FETCH_ASSOC); // fetch row
    }

    public function delete($id)
    {
        /**
         * defining method to delete row of table for specified ID;
         */

        $query = "DELETE FROM {$this->table} WHERE id = :id"; // query
        $p_stmt = $this->conn->prepare($query); // prepared statement 
        $p_stmt->bindParam(':id', $id);
        return $p_stmt->execute(); // execute statement 
    }

    public function delete_all()
    {
        /**
         * defining function to delete all rows of table;
         */

        $query = "DELETE FROM {$this->table}"; // query
        $stmt = $this->conn->prepare($query); // prepared statement 
        return $stmt->execute(); // execute statement
    }

    public function count()
    {
        /**
         * defining method to retrieve total number of rows of table 
         */
        $query = "SELECT COUNT(*) as total FROM {$this->table}"; // query
        $stmt = $this->conn->query($query); // prepared statement
        $row = $stmt->fetch(PDO::FETCH_ASSOC); // fetch result set
        return $row['total']; // return total number of rows
    }

    public function getLatest($numofRows)
    {
        /**
         * defining method to retrieve latest rows of table
         */

        $query = "SELECT * FROM {$this->table} ORDER BY created_at DESC LIMIT :numOfRows"; // query
        $p_stmt = $this->conn->prepare($query); // prepared statement
        $p_stmt->bindParam(':numOfRows', $numofRows, PDO::PARAM_INT);
        $p_stmt->execute(); // execute statement
        return $p_stmt->fetchAll(PDO::FETCH_ASSOC); // fetch result set
        
    }
}
