<?php

namespace MugshotGame\App\Data;

use SimplePHP\Core\Data\Repository;

class Arrest extends Repository {
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
    private $table = "arrests";
    private $id_name = "id";

    public function __construct(){
        parent::__construct($this->cols, $this->table, $this->id_name);
    }
}