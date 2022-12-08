<?php
class database
{
    private $host     = HOST;
    private $dbname   = DBNAME;
    private $username = USERNAME;
    private $password = PASSWORD;
    private  object $con;
    private  object $result;

    //DATABASE CONNECTION STUBLISH
    public function __construct()
    {
        try {
            $this->con = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->username, $this->password);
        } catch (PDOException $e) {
            echo "<div style='background-color:silver;padding:10px;margin:0;text-align:center;font-size:1.5rem;'>Database connection errors <strong style='color:red;'>$e.getMessage()</strong></div>";
            exit;
        }
    }
    //---------------------------

    //QUERY EXECUTION
    public function Query(string $query, array $params = [])
    {
        if (empty($params)) {
            $this->result = $this->con->prepare($query);
            return $this->result->execute();
        } else {
            $this->result = $this->con->prepare($query);
            return $this->result->execute($params);
        }
    }
    //----------------

    //DATA ROWS COUNTING
    public function row_count()
    {
        return $this->result->rowCount();
    }
    //----------------

    //FETCH ALL DATA
    public function fetch_all()
    {
        return $this->result->fetchAll(PDO::FETCH_OBJ);
    }
    //--------------

    //FETCH SINGLE DATA
    public function fetch()
    {
        return $this->result->fetch(PDO::FETCH_OBJ);
    }
    //----------------
}
