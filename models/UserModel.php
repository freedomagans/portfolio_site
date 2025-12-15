<?php

require_once 'BaseModel.php'; // import BaseModel class as parent class

/**
 * User Model
 * Manages user data and authentication
 */
class User extends BaseModel
{
    
    protected $table = 'users'; // specifying table(users);

    /**
     * defined constructor for class calls the constructor of BaseModel
     * and creates the login_attempts table if it doesn't exist
     */
    public function __construct()
    {
        parent::__construct();
        $this->createLoginAttemptsTable();
    }

    /**
     * method to retrieve row of user table by username
     * @param mixed $username
     * @return array|null
     */
    public function getByUsername($username)
    {
        $query = "SELECT * FROM {$this->table} WHERE username = :username LIMIT 1"; 
        $p_stmt = $this->conn->prepare($query); 
        $p_stmt->bindParam(':username', $username);       $p_stmt->execute(); 
        return $p_stmt->fetch(PDO::FETCH_ASSOC); 
    }

    /**
     * method to create a row of user table
     * @param mixed $username
     * @param mixed $password
     * @param mixed $email
     * @return bool
     */
    public function create($username, $password, $email)
    {
        $hashed_pass = password_hash($password, PASSWORD_DEFAULT); 
        $query = "INSERT INTO {$this->table} (username, password, email) VALUES (:username, :password, :email)"; 
        $p_stmt = $this->conn->prepare($query); 
        $p_stmt->bindParam(':username', $username);
        $p_stmt->bindParam(':password', $hashed_pass);
        $p_stmt->bindParam(':email', $email);
        return $p_stmt->execute(); // return boolean value
    }

    /**
     * method to update row of user table
     * @param mixed $username
     * @param mixed $password
     * @param mixed $email
     * @return bool
     */
    public function update($username, $password, $email)
    {     
        $query = "UPDATE {$this->table} SET username = :username, email = :email, password = :password WHERE id = 1"; 
        $p_stmt = $this->conn->prepare($query); 
        $p_stmt->bindParam(':username', $username);
        $p_stmt->bindParam(':password', $password);
        $p_stmt->bindParam(':email', $email);
        return $p_stmt->execute(); // return boolean value
    }

    /**
     * method to authenticate user login
     * has a login attempt limit based on security settings
     * @param mixed $username
     * @param mixed $password
     * @return bool
     */
    public function login($username, $password)
    {
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

        $query = "SELECT * FROM {$this->table} WHERE username = :username LIMIT 1"; 
        $p_stmt = $this->conn->prepare($query); 
        $p_stmt->bindParam(':username', $username); 
        $p_stmt->execute();

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

    /**
     * method to logout user
     * @return bool
     */
    public function logout(){
        session_unset();
        session_destroy();
        return true;
    }

    /**
     * method to check if an ip is blocked
     * @param mixed $ip
     * @param mixed $maxAttempts
     * @return bool
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
     * method to record Failed login attempt 
     * @param mixed $ip
     * @return void
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
     * method to clear Failed login attempts 
     * @param mixed $ip
     * @return void
     */
    private function clearFailedAttempts($ip)
    {
        $query = "DELETE FROM login_attempts WHERE ip_address = :ip";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':ip', $ip);
        $stmt->execute();
    }
    
    /**
     * method to create login_attempts table if it doesn't exist
     * @return void
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

