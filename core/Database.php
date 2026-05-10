<?php
class Database
{
    private static $instance = null;
    private  $connection;
    private function __construct()
    {
        $this->connection = mysqli_connect("localhost", "root", "", "tripverse");
        if (!$this->connection) {
            die("Database Connection  fail");
        }
    }
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }
 public function getConnection()
     {
        return $this->connection;
     }
    public function insert($query)
    {
        $insertResult = $this->connection->query($query);
        if (!$insertResult) {
            echo "Error:" . mysqli_error($this->connection);
            return false;
        } else {
            return $this->connection->insert_id;
        }
    }
    public function select($query)
    {
        $selectResult = $this->connection->query($query);
        if (!$selectResult) {
            echo "Error:" . mysqli_error($this->connection);
            return false;
        }
        return $selectResult->fetch_all(MYSQLI_ASSOC);
    }
    public function update($query)
    {
        $result = $this->connection->query($query);
        if (!$result) {
            echo "Error:" . mysqli_error($this->connection);
            return false;
        }
        return true;
    }
    public function delete($query)
{
    $result = $this->connection->query($query);

    if (!$result) {

        echo "Error:" . mysqli_error($this->connection);

        return false;
    }

    return true;
}

    
}
