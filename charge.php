<?php
include_once("entity.php");
/*individuals:
CREATE TABLE charges (
    individual_id INT NULL, 
    arrest_id INT NULL, 
    charge VARCHAR(255),
    FOREIGN KEY (individual_id) REFERENCES individuals(id),
    FOREIGN KEY (arrest_id) REFERENCES arrests(id)
);
 */

class Charge extends Entity {
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
            "name": "charge",
            "type": "VARCHAR(255)"
        }
    ]';
    private $table = "charges";
    private $id_name = "id";

    public function __construct(){
        parent::__construct($this->cols, $this->table, $this->id_name);
    }
}