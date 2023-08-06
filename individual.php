<?php
include_once("entity.php");
/*individuals:
name - The name of the individual.
mugshot - The image url of mugshot.
id - A unique string id for the record.
source_id - The id of the source.
source - The name of the source.
county_state - The county and state of the booking.
book_date - Book Date string in YYYY-MM-DD format.
book_date_formatted - Book Date string in MMM DD, YYYY format.
more_info_url - The url on JailBase.com to get more info. */

class Individual extends Entity {
    private $cols = array("name", "mugshot", "id", "source_id", "source", "county_state", "book_date", "book_date_formatted", "more_info_url");
    private $table = "individuals";
    private $id_name = "id";

    public function __construct(){
        parent::__construct($this->cols, $this->table, $this->id_name);
    }
}