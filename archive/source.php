<?php
include_once("entity.php");
class Source extends Entity {
    private $cols = '[
        {
            "name": "id",
            "type": "INT",
            "primary_key": true,
            "auto_increment": true
        },
        {
            "name": "source_id",
            "type": "VARCHAR(255)",
            "unique_key": true,
            "not_null": true
        },
        {
            "name": "name",
            "type": "VARCHAR(255)"
        },
        {
            "name": "state",
            "type": "VARCHAR(255)"
        },
        {
            "name": "state_full",
            "type": "VARCHAR(255)"
        },
        {
            "name": "has_mugshots",
            "type": "BOOLEAN"
        }
    ]';
    private $table = "sources";
    private $id_name = "id";

    public function __construct(){
        parent::__construct($this->cols, $this->table, $this->id_name);
    }
}