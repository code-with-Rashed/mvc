<?php

namespace Management\Database;

class DB
{
    //msdb information
    private string $db_host = MSDB_HOST;
    private int $db_port = MSDB_PORT;
    private string $db_username = MSDB_USERNAME;
    private string $db_password = MSDB_PASSWORD;
    private string $db_name = MSDB_NAME;
    //--------------

    private object $mysqli; //this will be our mysqli object
    private array $result = []; //any results from a query will be stored here

    private bool $db_connection = false; //database connection status

    //open db connection
    public function __construct()
    {
        try {
            if (!$this->db_connection) {
                $this->mysqli = new \mysqli($this->db_host, $this->db_username, $this->db_password, $this->db_name, $this->db_port);
                $this->db_connection = true;
            }
        } catch (\Throwable $error) {
            die($error->getMessage());
        }
    }
    //-----------------

    //select method
    public function select(string $table, string $column = "*", string $join = null, string $where = null, string $order = null, int $start = null, int $limit = null): bool
    {
        $this->table_exist($table);
        $select_sql = "SELECT $column FROM `$table`";
        if ($join) {
            $select_sql .= " JOIN $join";
        }
        if ($where) {
            $select_sql .= " WHERE $where";
        }
        if ($order) {
            $select_sql .= " ORDER BY $order";
        }
        if (!is_null($start) && $limit) {
            $select_sql .= " LIMIT $start , $limit";
        }
        try {
            $select_query_result = $this->mysqli->query($select_sql);
        } catch (\Throwable $error) {
            echo "\n<br>Query : $select_sql<br>\n";
            die($error->getMessage());
        }
        $this->result($select_query_result->fetch_all(MYSQLI_ASSOC));
        return true;
    }
    //-------------

    //insert method
    public function insert(string $table, array $data = []): bool
    {
        $this->table_exist($table);
        $columns = implode(" , ", array_keys($data));
        $values = implode("' , '", $data);
        $insert_sql = "INSERT INTO `$table` ($columns) VALUES ('$values')";
        try {
            $this->mysqli->query($insert_sql);
        } catch (\Throwable $error) {
            echo "\n<br>Query : $insert_sql<br>\n";
            die($error->getMessage());
        }
        $this->result($this->mysqli->insert_id);
        return true;
    }
    //-------------

    //update method
    public function update(string $table, array $updated_data = [], string $where = null): bool
    {
        $this->table_exist($table);
        $arguments = [];
        foreach ($updated_data as $key => $value) {
            $arguments[] = " $key = '$value' ";
        }
        $update_sql = "UPDATE `$table` SET " . implode(" , ", $arguments);
        if ($where) {
            $update_sql .= "WHERE $where";
        }
        try {
            $this->mysqli->query($update_sql);
        } catch (\Throwable $error) {
            echo "\n<br>Query : $update_sql<br>\n";
            die($error->getMessage());
        }
        $this->result($this->mysqli->affected_rows);
        return true;
    }
    //-------------

    //update method
    public function delete(string $table, string $where)
    {
        $this->table_exist($table);
        $delete_sql = "DELETE FROM `$table` WHERE $where";
        try {
            $this->mysqli->query($delete_sql);
        } catch (\Throwable $error) {
            echo "\n<br>Query : $delete_sql<br>\n";
            die($error->getMessage());
        }
        $this->result($this->mysqli->affected_rows);
        return true;
    }
    //-------------

    //count table rows
    public function count_rows(string $table, string $join = null, string $where = null):bool
    {
        $this->table_exist($table);
        $count_rows_sql = "SELECT COUNT(*) FROM `$table`";
        if ($join) {
            $count_rows_sql .= " JOIN $join";
        }
        if ($where) {
            $count_rows_sql .= " WHERE $where";
        }
        try {
            $total = $this->mysqli->query($count_rows_sql);
        } catch (\Throwable $error) {
            echo "\n<br>Query : $count_rows_sql<br>\n";
            die($error->getMessage());
        }
        if ($total->num_rows) {
            $this->result($total->fetch_row());
            return true;
        }
    }
    //----------------

    //direct sql query execution
    public function sql(string $sql): bool
    {
        try {
            $query = $this->mysqli->query($sql);
        } catch (\Throwable $error) {
            die($error->getMessage());
        }
        $sql_array = explode(" ", $sql);
        $sql_type = strtolower($sql_array[0]);
        match ($sql_type) {
            "select" => $this->result($query->fetch_all(MYSQLI_ASSOC)),
            "insert" => $this->result($this->mysqli->insert_id),
            "update" => $this->result($this->mysqli->affected_rows),
            "delete" => $this->result($this->mysqli->affected_rows),
            default => $this->result($query)
        };
        return true;
    }
    //--------------------------

    //check db table exist for use with queries
    private function table_exist(string $table)
    {
        $sql = "SHOW TABLES FROM $this->db_name LIKE '$table'";
        try {
            $result = $this->mysqli->query($sql);
            if (!$result->num_rows) {
                die("This table ($table) does not exist in this database ($this->db_name)");
            }
        } catch (\Throwable $error) {
            echo "\n<br>Query : $sql<br>\n";
            die($error->getMessage());
        }
    }
    //-----------------------------------------
    
    // escape your string
    public function escapestring(string $data): string
    {
      $data = trim($data);
      $data = stripslashes($data);
      $data = strip_tags($data);
      $data = htmlspecialchars($data);
      $data = $this->mysqli->real_escape_string($data);
      return $data;
    }
    //---------------------------------

    //set result method
    private function result($result): void
    {
        array_push($this->result, $result);
    }
    //-----------------

    //get result method
    public function get_result(): array
    {
        $result = $this->result;
        $this->result = [];
        return $result;
    }
    //-----------------

    //close db connection
    public function __destruct()
    {
        if ($this->db_connection) {
            $this->mysqli->close();
            $this->db_connection = false;
        }
    }
    //-------------------
}
