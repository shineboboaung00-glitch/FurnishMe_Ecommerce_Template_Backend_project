<?php

class database
{
    private $server_name = "localhost";
    private $user_name = "root";
    private $password = ""; 
    private $database = "ecommerce_db";
    private $connection;

    public function getConnection()
    {
        $this->connection = null;

        try {
            $this->connection = new PDO("mysql:host=" . $this->server_name . ";dbname=" . $this->database . ";charset=utf8mb4", $this->user_name, $this->password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            echo "Connection Error: " . $exception->getMessage();
        }
        return $this->connection;
    }
}

?>
