<?php
include_once("db.php");

class Entity {
    private $_db;
    private $_table;
    private $_columns;
    private $_id_name;

    public function __construct($columns, $table, $id_name, $debug = false){
        $this->_db = new DB($debug);
        $this->_table = $table;
        $this->_columns = $columns;
        $this->_id_name = $id_name;
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

    public function get_id_name(){
        return $this->_id_name;
    }
}
