<?php 
class Database //DB class defining base database connection
{

    private $host = DB_HOST; // db host
    private $db_name = DB_NAME; // db name 
    private $username = DB_USER; // db user
    private $password = DB_PASS; // db password
    private $db_charset = DB_CHARSET;
    private $conn; // db connection instance 


    public function connect() {
        /**
         * establishes database connection with database details;
         */

        $this->conn = null;  // initialises connection instance to null

        try{
            /**
             * instantiates the PDO db connection;
             */
            $this->conn = new PDO (
                "mysql:host={$this->host};dbname={$this->db_name};charset={$this->db_charset}",
                $this->username,
                $this->password
            );

            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
        catch(PDOException $e) 
            {
                //echo error message if connection fails
                echo "Connection failed: " . $e->getMessage();
            }
        return $this->conn; // return connection instance
    }

}