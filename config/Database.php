<?php 
class Database //DB class defining base database connection
{

    private $host = 'localhost'; // db host
    private $db_name = 'portfolio_db'; // db name 
    private $username = 'root'; // db user
    private $password = ''; // db password
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
                "mysql:host={$this->host};dbname={$this->db_name};charset=utf8",
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