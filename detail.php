<?php
include_once("entity.php");
/*
CREATE TABLE details (
    individual_id INT, 
    arrest_id INT,
    description VARCHAR(255),
    FOREIGN KEY (individual_id) REFERENCES individuals(id),
    FOREIGN KEY (arrest_id) REFERENCES arrests(id)
);
*/
class Detail extends Entity {
    private $cols = '[
        {
            "name": "id",
            "type": "INT",
            "primary_key": true,
            "auto_increment": true
        },
        {
            "name": "individual_id",
            "type": "INT",
            "foreign_key": "individuals(id)"
        },
        {
            "name": "arrest_id",
            "type": "INT",
            "foreign_key": "arrests(id)"
        },
        {
            "name": "description",
            "type": "VARCHAR(255)"
        }
    ]';
    private $table = "details";
    private $id_name = "id";

    public function __construct(){
        parent::__construct($this->cols, $this->table, $this->id_name);
    }
}