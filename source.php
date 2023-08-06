<?php
include_once("entity.php");

class Source extends Entity {
    private $cols = array("source_id", "name", "state", "state_full", "has_mugshots");
    private $table = "sources";
    private $id_name = "id";

    public function __construct(){
        parent::__construct($this->cols, $this->table, $this->id_name);
    }
}