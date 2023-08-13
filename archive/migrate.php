<?php

include_once("db.php");

class Migration {
    private $db_name = "mugshot_game";
    private $entities = ['Source', 'Arrest', 'Individual', 'Charge', 'Detail'];
    private $db;
    
    public function __construct(){
        $this->db = new DB();
        $this->create_db();
        $this->up();
    }

    private function create_db(){
        $sql = "CREATE DATABASE IF NOT EXISTS $this->db_name";
        $this->db->execute($sql);
    }

    private function up(){
        foreach ($this->entities as $entity) {
            include_once(strtolower($entity) . ".php");
            $entity = new $entity();
            $entity->create_table();
        }
    }

    public function down(){
        foreach ($this->entities as $entity) {
            include_once(strtolower($entity) . ".php");
            $entity = new $entity();
            $entity->drop_table();
        }
    }
}

$migration = new Migration();