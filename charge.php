<?php
include_once("entity.php");
/*individuals:
charges:
individual_id - The id of the individual. (nullable fk)
arrest_id - The id of the arrest. (nullable fk)
charge - The charge.
 */

class Charge extends Entity {
    private $cols = array("individual_id", "arrest_id", "charge");
    private $table = "charges";
    private $id_name = "id";

    public function __construct(){
        parent::__construct($this->cols, $this->table, $this->id_name);
    }
}