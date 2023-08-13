<?php

namespace MugshotGame\App\Data;

use SimplePHP\Core\Data\Repository;

class Charge extends Repository {
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