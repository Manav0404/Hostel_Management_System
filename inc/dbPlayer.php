<?php

namespace dbPlayer;

class dbPlayer {

    private $db_host = "localhost";
    private $db_name = "hms";
    private $db_user = "root";
    private $db_pass = "";
    protected $con;

    public function open() {
        $this->con = mysqli_connect($this->db_host, $this->db_user, $this->db_pass, $this->db_name);
        if (!$this->con) {
            return mysqli_connect_error();
        }
        return true;
    }

    public function close() {
        $res = mysqli_close($this->con);
        if (!$res) {
            return "Failed to connect to MySQL: " . mysqli_connect_error();
        }
        return true;
    }

    public function insertData($table, $data) {
        $keys = "`" . implode("`, `", array_keys($data)) . "`";
        $values = "'" . implode("', '", $data) . "'";
        $query = "INSERT INTO `$table` ($keys) VALUES ($values)";
        $result = mysqli_query($this->con, $query);
        if ($result) {
            return mysqli_insert_id($this->con);
        } else {
            return mysqli_error($this->con);
        }
    }

    public function registration($query, $query2) {
        $res = mysqli_query($this->con, $query);
        if ($res) {
            $res = mysqli_query($this->con, $query2);
            if ($res) {
                return true;
            } else {
                return mysqli_error($this->con);
            }
        } else {
            return mysqli_error($this->con);
        }
    }

    public function getData($query) {
        $res = mysqli_query($this->con, $query);
        if (!$res) {
            return "Can't get data " . mysqli_error($this->con);
        } else {
            return $res;
        }
    }

    public function update($query) {
        $res = mysqli_query($this->con, $query);
        if (!$res) {
            return "Can't update data " . mysqli_error($this->con);
        } else {
            return true;
        }
    }

    public function updateData($table, $conColumn, $conValue, $data) {
        $updates = array();
        foreach ($data as $key => $value) {
            $value = mysqli_real_escape_string($this->con, $value);
            $updates[] = "$key = '$value'";
        }
        $updateString = implode(', ', $updates);
        $query = "UPDATE $table SET $updateString WHERE $conColumn = '$conValue'";
        $res = mysqli_query($this->con, $query);
        if (!$res) {
            return "Can't Update data " . mysqli_error($this->con);
        } else {
            return true;
        }
    }

    public function delete($query) {
        $res = mysqli_query($this->con, $query);
        if (!$res) {
            return "Can't delete data " . mysqli_error($this->con);
        } else {
            return true;
        }
    }

    public function getAutoId($prefix) {
        $uId = "";
        $q = "SELECT number FROM auto_id WHERE prefix = '$prefix'";
        $result = $this->getData($q);
        $userId = array();
        while ($row = mysqli_fetch_assoc($result)) {
            array_push($userId, $row['number']);
        }
        if (strlen($userId[0]) >= 1) {
            $uId = $prefix . "00" . $userId[0];
        } elseif (strlen($userId[0]) == 2) {
            $uId = $prefix . "0" . $userId[0];
        } else {
            $uId = $prefix . $userId[0];
        }
        array_push($userId, $uId);
        return $userId;
    }

    public function updateAutoId($value, $prefix) {
        $id = intval($value) + 1;
        $query = "UPDATE auto_id SET number = $id WHERE prefix = '$prefix'";
        return $this->update($query);
    }

    public function execNonQuery($query) {
        $res = mysqli_query($this->con, $query);
        if (!$res) {
            return "Can't Execute Query" . mysqli_error($this->con);
        } else {
            return true;
        }
    }
    public function execDataTable($query) {
        // Check if the database connection is established
        if (!$this->con) {
            // If the connection is not established, try opening it
            $openResult = $this->open();
            // If opening the connection fails, return an error message
            if ($openResult !== true) {
                return "Can't execute query: " . $openResult;
            }
        }
    
        // Now that the connection is established, execute the query
        $res = mysqli_query($this->con, $query);
        if (!$res) {
            return "Can't execute query: " . mysqli_error($this->con);
        } else {
            return $res;
        }
    }
    
    // Add more methods as needed...
}

?>
