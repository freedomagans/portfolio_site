<?php
require_once 'BaseModel.php'; // import BaseModel class as parent class


/** 
 * defining Notification class as subclass of 
 * BaseModel database class to model Notifications table
 */
class Notification extends BaseModel
{
    protected $table = 'notifications'; // specifying table(notifications

    /**
     * method to create row in notification table
     * @param mixed $name
     * @param mixed $email
     * @param mixed $subject
     * @param mixed $message
     * @return bool
     */
    public function create($name, $email, $subject, $message)
    {
        $query = "INSERT INTO {$this->table} 
                (name, email, subject, message, created_at) 
                VALUES (:name, :email, :subject, :message, NOW())"; 
        $stmt = $this->conn->prepare($query); 
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':subject', $subject);
        $stmt->bindParam(':message', $message);

        return $stmt->execute(); // returns boolean value
    }

    /**
     * method to mark Notification row as Read
     * @param mixed $id
     * @return bool
     */
    public function markAsRead($id)
    {
        $query = "UPDATE {$this->table} SET is_read = 1 WHERE id = :id"; 
        $stmt = $this->conn->prepare($query); 
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute(); // returns boolean value
    }

    /**
     * method to get rows of Notification table 
     * based on pagination start and perpage parameters;
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
     * method to mark all unread rows 
     * of Notification table as Read
     * @return bool
     */
    public function markAllAsRead()
    {
        $query = "UPDATE {$this->table} SET is_read = 1 WHERE is_read = 0"; 
        $stmt = $this->conn->prepare($query); 
        return $stmt->execute(); // returns boolean value
    }

    /**
     * * defining method to retrieve paginated result 
     * set of rows of Notification table based on status (read or unread);
     * @param mixed $start
     * @param mixed $perPage
     * @param mixed $status
     * @return array
     */
    public function getPaginatedByStatus($status = null, $start, $perPage)
    {
        $query = "SELECT * FROM {$this->table} WHERE is_read = :status ORDER BY created_at DESC LIMIT :start, :perPage";
        $stmt = $this->conn->prepare($query); 
        $stmt->bindValue(':start', $start, PDO::PARAM_INT);
        $stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':status', $status);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * defining method to retrieve count of rows of Notification 
     * table based on status(read or unread);
     * @param mixed $status
     * @return mixed
     */
    public function countByStatus($status = null)
    {
        $query = "SELECT COUNT(*) as total FROM {$this->table} WHERE is_read = :status";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':status', $status);
        $stmt->execute(); 
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total']; 
    }

    /**
     * method to return number of unread notifications
     * @return mixed
     */
    public function unreadCount()
    {
        $query = "SELECT COUNT(*) as total FROM {$this->table} WHERE is_read = 0"; 
        $stmt = $this->conn->query($query); 
        $row = $stmt->fetch(PDO::FETCH_ASSOC); 
        return $row['total']; 
    }
}
