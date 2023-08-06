<?php
include_once("entity.php");

class Arrest extends Entity {
    private $cols = array("name", "mugshot", "id", "book_date", "book_date_formatted", "more_info_url");
    private $table = "arrests";
    private $id_name = "id";

    public function __construct(){
        parent::__construct($this->cols, $this->table, $this->id_name);
    }
}