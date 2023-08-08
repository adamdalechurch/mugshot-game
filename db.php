<?php
class DB {
    private $_db;
    private $debug;

    public function __construct($debug = false){
        $this->connect();
        $this->debug = $debug;
    }

    public function __destruct(){
        $this->_db->close();
    }

    public function insert($table, $records, $columns){
       
        $mysqli = $this->_db;

        $columns = pluck_column_names($columns);

        $sql = "INSERT INTO $table (";
        foreach ($columns as $column) {
            $sql .= "$column, ";
        }
        $sql = rtrim($sql, ", ");
        $sql .= ") VALUES (";
        foreach ($columns as $column) {
            $record = $records[$column];
            $sql .= "'".$mysqli->real_escape_string($record)."', ";
        }
        $sql = rtrim($sql, ", ");
        $sql .= ")";

        $this->debug($sql);

        $mysqli->query($sql);
        return $mysqli->insert_id;       
    }

    public function select($table, $columns, $where){
        $mysqli = $this->_db;

        $columns = pluck_column_names($columns);

        $sql = "SELECT ";
        foreach ($columns as $column) {
            $sql .= "$column, ";
        }
        $sql = rtrim($sql, ", ");
        $sql .= " FROM $table WHERE ";
        foreach ($where as $key => $value) {
            $sql .= "$key = '".$mysqli->real_escape_string($value)."' AND ";
        }
        $sql = rtrim($sql, " AND ");
        $result = $mysqli->query($sql);
        $rows = array();
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }

        $this->debug($sql);

        return $rows;
    }

    public function update($table, $columns, $where){
        $mysqli = $this->_db;

        $columns = pluck_column_names($columns);

        $sql = "UPDATE $table SET ";
        foreach ($columns as $key => $value) {
            $sql .= "$key = '$value', ";
        }
        $sql = rtrim($sql, ", ");
        $sql .= " WHERE ";
        foreach ($where as $key => $value) {
            $sql .= "$key = '".$mysqli->real_escape_string($value)." AND ";
        }
        $sql = rtrim($sql, " AND ");
        $mysqli->query($sql);
    }

    public function delete($table, $where){
        $mysqli = $this->_db;
        $sql = "DELETE FROM $table WHERE ";
        foreach ($where as $key => $value) {
            $sql .= "$key = '$value' AND ";
        }
        $sql = rtrim($sql, " AND ");
        $mysqli->query($sql);
    }

    public function query($sql){
        $mysqli = $this->_db;
        $result = $mysqli->query($sql);
        $rows = array();
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function execute($sql){
        $mysqli = $this->_db;
        return $mysqli->query($sql);
    }

    // foreign keys, unique keys, and primary keys will be properties on columns array now
    public function create_table($table, $columns, $auto_increment = true, $other_constraints = []){
        $mysqli = $this->_db;

        // convert $columns to an array of Column objects:
        $columns = $this->decode_columns($columns);

        $sql = "CREATE TABLE $table (";
        foreach ($columns as $key => $column) {
            $sql .= "$column->name $column->type";
            if($column->primary_key){
                $sql .= " PRIMARY KEY NOT NULL".($column->auto_increment ?  " AUTO_INCREMENT" : "" );
            }

            $sql .= ", ";
        }

        // foreign keys, unique keys, and primary keys will be properties on columns array now
        foreach ($columns as $key => $column) {
            if($column->foreign_key){
                $sql .= "FOREIGN KEY ($column->name) REFERENCES $column->foreign_key, ";
            }
            if($column->unique_key){
                $sql .= "UNIQUE ($column->name), ";
            }
        }

        $sql = rtrim($sql, ", ");
        $sql .= ")";

        if(count($other_constraints) > 0)
            $sql .= implode(";\n ", $other_constraints);

        $this->debug($sql);

        $mysqli->query($sql);
        
    }

    public function drop_table($table){
        $mysqli = $this->_db;
        $sql = "DROP TABLE $table";
        $mysqli->query($sql);
    }

    public function truncate_table($table){
        $mysqli = $this->_db;
        $sql = "TRUNCATE TABLE $table";
        $mysqli->query($sql);
    }

    private function decode_columns($columns){
        $columns = json_decode($columns);
        $column_objects = [];
        foreach ($columns as $column) {
            $column_objects[] = new Column($column);
        }
        return $column_objects;
    }

    private function pluck_column_names($columns){
        $columns = $this->decode_columns($columns);
        $column_names = [];
        foreach ($columns as $column) {
            $column_names[] = $column->name;
        }
        return $column_names;
    }

    public function connect(){
        $this->_db = new mysqli("localhost", "root", "", "mugshot_game");
        
        if ($this->_db->connect_errno) {
            echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
        }
    }

    private function debug($sql){
        if($this->debug){
            echo $sql;
            exit();
        }
    }
}

class Column{
    public $name;
    public $type;
    public $primary_key;
    public $auto_increment;
    public $foreign_key;
    public $unique_key;
    public $not_null;

    public function __construct($column){
        $this->name = $column->name;
        $this->type = $column->type;
        $this->primary_key = isset($column->primary_key) ? $column->primary_key : false;
        $this->auto_increment = isset($column->auto_increment) ? $column->auto_increment : false;
        $this->foreign_key = isset($column->foreign_key) ? $column->foreign_key : false;
        $this->unique_key = isset($column->unique_key) ? $column->unique_key : false;
        $this->not_null = isset($column->not_null) ? $column->not_null : false;
    }
}
