<?php
include_once("entity.php");
class Individual extends Entity {
    private $cols = '[
        {
            "name": "id",
            "type": "INT",
            "primary_key": true,
            "auto_increment": true
        },
        {
            "name": "name",
            "type": "VARCHAR(255)"
        },
        {
            "name": "mugshot",
            "type": "VARCHAR(255)"
        },
        {
            "name": "source_id",
            "type": "INT",
            "not_null": true
        },
        {
            "name": "source",
            "type": "VARCHAR(255)",
            "not_null": true
        },
        {
            "name": "county_state",
            "type": "VARCHAR(100)"
        },
        {
            "name": "book_date",
            "type": "VARCHAR(255)"
        },
        {
            "name": "book_date_formatted",
            "type": "VARCHAR(255)"
        },
        {
            "name": "more_info_url",
            "type": "VARCHAR(255)"
        }
    ]';
    private $table = "individuals";
    private $id_name = "id";

    public function __construct(){
        parent::__construct($this->cols, $this->table, $this->id_name);
    }
}