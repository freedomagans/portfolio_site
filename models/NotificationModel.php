<?php
require_once 'BaseModel.php'; // import BaseModel class as parent class

class Notification extends BaseModel
{
    /** 
     * defining Notification class as subclass of 
     * BaseModel database class to model Notifications table
     */

    protected $table = 'notifications'; // specifying table(notifications)


    public function create($name, $email, $subject, $message)
    {
        /**
         * defining function to create Notification database row;
         */

        $query = "INSERT INTO {$this->table} (name, email, subject, message, created_at) 
                VALUES (:name, :email, :subject, :message, NOW())"; // query
        $stmt = $this->conn->prepare($query); // prepared statement

        // binding parameters for statement
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':subject', $subject);
        $stmt->bindParam(':message', $message);

        return $stmt->execute(); // execute statement
    }

    public function markAsRead($id)
    {
        /**
         * defining method to mark notification instance as READ;
         */

        $query = "UPDATE {$this->table} SET is_read = 1 WHERE id = :id"; // query
        $stmt = $this->conn->prepare($query); // prepared statement
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute(); // execute statement
    }

    public function getPaginated($start, $perPage)
    {
        /**
         * defining method to get rows of Notification table 
         * based on pagination start and perpage parameters;
         */

        $query = "SELECT * FROM {$this->table} ORDER BY created_at DESC LIMIT :start, :perPage"; // query
        $stmt = $this->conn->prepare($query); // prepared statement
        // binding statement parameters;
        $stmt->bindValue(':start', $start, PDO::PARAM_INT);
        $stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);

        $stmt->execute(); // execute statement;
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // retrieving result set
    }

    public function markAllAsRead()
    {
        /**
         * defining method to mark all unread rows of Notification table as Read
         */
        $query = "UPDATE {$this->table} SET is_read = 1 WHERE is_read = 0"; // query
        $stmt = $this->conn->prepare($query); // prepared statement
        return $stmt->execute(); // execute statement
    }

    public function getPaginatedByStatus($start, $perPage, $status = null)
    {
        /**
         * defining method to retrieve paginated result set of rows of Notification table based on 
         * status (read or unread);
         */

        // query(based on status(read or unread))
        $query = "SELECT * FROM {$this->table}"; 
        if ($status === 'read') {
            $query .= " WHERE is_read = 1";
        } elseif ($status === 'unread') {
            $query .= " WHERE is_read = 0";
        }
        $query .= " ORDER BY created_at DESC LIMIT :start, :perPage";

        $stmt = $this->conn->prepare($query); // prepared statement
        //binding parameter values
        $stmt->bindValue(':start', $start, PDO::PARAM_INT);
        $stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);
        $stmt->execute(); // execute statement
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // return result set
    }

    public function countByStatus($status = null)
    {
        /**
         * defining method to retrieve count of rows of Notification 
         * table based on status(read or unread);
         */

        //query based on status(read or unread)
        $query = "SELECT COUNT(*) as total FROM {$this->table}";
        if ($status === 'read') {
            $query .= " WHERE is_read = 1";
        } elseif ($status === 'unread') {
            $query .= " WHERE is_read = 0";
        }

        $stmt = $this->conn->query($query); // statement
        $row = $stmt->fetch(PDO::FETCH_ASSOC); // fetch result set
        return $row['total']; // return number of returned rows
    }

    public function unreadCount()
    {
        /**
         * defining method to return number of rows of Notification 
         * table that are unread;
         */

        $query = "SELECT COUNT(*) as total FROM {$this->table} WHERE is_read = 0"; // query
        $stmt = $this->conn->query($query); // statement
        $row = $stmt->fetch(PDO::FETCH_ASSOC); // fetch result set
        return $row['total']; // returning unread count value
    }
}
