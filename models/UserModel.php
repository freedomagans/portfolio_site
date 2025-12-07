<?php

require_once 'BaseModel.php'; // import BaseModel class as parent class

class User extends BaseModel
{
    /**
     * defining subclass of BaseModel class to model User table
     */
    protected $table = 'users'; // specifying table(users);

    public function __construct()
    {
        parent::__construct();
        $this->createLoginAttemptsTable();
    }

    public function getByUsername($username)
    {
        /**
         * defining method to retrieve row of user table by username
         */ 

        $query = "SELECT * FROM {$this->table} WHERE username = :username LIMIT 1"; // query
        $p_stmt = $this->conn->prepare($query); // prepared statement
        $p_stmt->bindParam(':username', $username); // binding parameter to value
        $p_stmt->execute(); // execute statement
        return $p_stmt->fetch(PDO::FETCH_ASSOC); // return resultset
    }

    public function create($username, $password, $email)
    {
        /**
         * defining method to create a row of User table;
         */

        $hashed_pass = password_hash($password, PASSWORD_DEFAULT); // hashing password parameter
        $query = "INSERT INTO {$this->table} (username, password, email) VALUES (:username, :password, :email)"; // query
        $p_stmt = $this->conn->prepare($query); // prepared statement
        // binding parameters to values
        $p_stmt->bindParam(':username', $username);
        $p_stmt->bindParam(':password', $hashed_pass);
        $p_stmt->bindParam(':email', $email);
        return $p_stmt->execute(); // execute statement
    }

    public function update($username, $password, $email)
    {     
        /**
         * defining method to update row of user table;
         */
        $query = "UPDATE {$this->table} SET username = :username, email = :email, password = :password WHERE id = 1"; // query
        $p_stmt = $this->conn->prepare($query); // prepared statement
        // binding parameters to values
        $p_stmt->bindParam(':username', $username);
        $p_stmt->bindParam(':password', $password);
        $p_stmt->bindParam(':email', $email);
        return $p_stmt->execute(); // execute statement
    }

    public function login($username, $password)
    {
        /**
         * defining method to authenticate user by comparing
         * user credetials with that in the database;
         * Includes login attempt limiting based on security settings
         */

        // Get security settings
        require_once __DIR__ . '/../core/Settings.php';
        $settings = AppSettings::getInstance();
        $securitySettings = $settings->getSecuritySettings();

        $maxAttempts = $securitySettings['login_attempts'];
        $ip = $_SERVER['REMOTE_ADDR'];

        // Check if IP is blocked due to too many failed attempts
        if ($this->isBlocked($ip, $maxAttempts)) {
            return false;
        }

        $query = "SELECT * FROM {$this->table} WHERE username = :username LIMIT 1"; // query
        $p_stmt = $this->conn->prepare($query); // prepared statement
        $p_stmt->bindParam(':username', $username); // binding parameters to values
        $p_stmt->execute(); // execute statement

        $user = $p_stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['password'])) // authenticates if user instance exists and password matches
        {
            // Successful login - clear failed attempts
            $this->clearFailedAttempts($ip);

            // sets session values for authenticated user
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            return true;
        }

        // Failed login - record attempt
        $this->recordFailedAttempt($ip);
        return false;
    }

    public function logout(){
        /**
         * defining method to logout user and clear session;
         */
        session_unset();
        session_destroy();
        return true;
    }

    /**
     * Check if IP is blocked due to too many failed login attempts
     */
    public function isBlocked($ip, $maxAttempts)
    {
        $query = "SELECT COUNT(*) as attempts FROM login_attempts
                  WHERE ip_address = :ip
                  AND attempted_at > DATE_SUB(NOW(), INTERVAL 15 MINUTE)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':ip', $ip);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['attempts'] >= $maxAttempts;
    }

    /**
     * Record a failed login attempt
     */
    private function recordFailedAttempt($ip)
    {
        // Create login_attempts table if it doesn't exist
        $this->createLoginAttemptsTable();

        $query = "INSERT INTO login_attempts (ip_address, attempted_at)
                  VALUES (:ip, NOW())";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':ip', $ip);
        $stmt->execute();
    }

    /**
     * Clear failed login attempts for an IP
     */
    private function clearFailedAttempts($ip)
    {
        $query = "DELETE FROM login_attempts WHERE ip_address = :ip";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':ip', $ip);
        $stmt->execute();
    }

    /**
     * Create login_attempts table if it doesn't exist
     */
    private function createLoginAttemptsTable()
    {
        $query = "CREATE TABLE IF NOT EXISTS login_attempts (
            id INT PRIMARY KEY AUTO_INCREMENT,
            ip_address VARCHAR(45) NOT NULL,
            attempted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_ip_attempts (ip_address, attempted_at)
        )";

        $this->conn->exec($query);
    }


}

