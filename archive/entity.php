<?php
include_once("db.php");

class Entity {
    private $_db;
    private $_table;
    private $_columns;
    private $_id_name;
    private $_auto_increment;
    private $_other_constraints;

    public function __construct($columns, $table, $id_name, 
    $auto_increment = true, $other_constraints = [], $debug = false){
        $this->_db = new DB($debug);
        $this->_table = $table;
        $this->_columns = $columns;
        $this->_id_name = $id_name;
        $this->_auto_increment = $auto_increment;
        $this->_other_constraints = $other_constraints;
    }

    public function get_by_id($id){
        $where = $this->_id_name . " = " . $id;
        $result = $this->_db->select($this->_table, $this->_columns, $where);
        return $result[0];
    }

    private function bind_unique_key_getters(){
        $getters = [];
        foreach($columns as $column){
            if($column->unique_key){
                $getter = "get_by_" . $column->name;
                Closure::bind(function($val) use ($column){
                    $where = $column->name . " = " . $val;
                    $result = $this->_db->select($this->_table, $this->_columns, $where);
                    return $result[0];
                }, $this, $getter);
            }
        } 
    }

    private function bind_foreign_key_getters($foreign_keys){
        $getters = [];
        foreach($columns as $column){
            if($column->foreign_key){
                $getter = "get_" . $this->get_foreign_key_table_name($column->foreign_key);
                Closure::bind(function() use ($foreign_key){
                    $where = $foreign_key . " = " . $this->$foreign_key;
                    $result = $this->_db->select($this->_table, $this->_columns, $where);
                    return $result[0];
                }, $this, $getter);
            }
        } 
    }

    private function get_foreign_key_table_name($foreign_key){
        $foreign_key = explode("(", $foreign_key)[0];
        $foreign_key = explode(".", $foreign_key)[0];
        return $foreign_key;
    }

    public function insert($records){
       return $this->_db->insert($this->_table, $records, $this->_columns);
    }

    public function select($where){
        return $this->_db->select($this->_table, $this->_columns, $where);
    }

    public function update($columns, $where){
        $this->_db->update($this->_table, $columns, $where);
    }

    public function delete($where){
        $this->_db->delete($this->_table, $where);
    }

    public function create_table(){
        $this->_db->create_table($this->_table, $this->_columns, $this->_auto_increment, $this->_other_constraints);
    }

    public function get_id_name(){
        return $this->_id_name;
    }
}
