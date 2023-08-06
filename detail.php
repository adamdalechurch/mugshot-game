<?php
include_once("entity.php");
/*
details:
individual_id - The id of the individual. (nullable fk)
arrest_id - The id of the arrest. (nullable fk)
description - The description.
*/

class Detail extends Entity {
    private $cols = array("individual_id", "arrest_id", "description");
    private $table = "charges";
    private $id_name = "id";

    public function __construct(){
        parent::__construct($this->cols, $this->table, $this->id_name);
    }
}