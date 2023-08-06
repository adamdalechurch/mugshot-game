
<?php

/* exapmle methods */

// http://www.JailBase.com/api/1/search/?source_id=az-mcso&last_name=smith
// http://www.JailBase.com/api/1/recent/?source_id=az-mcso
// http://www.JailBase.com/api/1/sources/
// Method: sources - all the organizations we collect information for
// Function to fetch data from Jailbase API


/* function: fetch_sources
   What it does: fetches all the sources from the Jailbase API
   Parameters: none
*/
function fetch_sources() {
    $url = "http://www.JailBase.com/api/1/sources/";
    return fetch_api_data($url);
}

/* function: fetch_recent_by_source_id
   What it does: fetches recent arrests from the Jailbase API by source ID 
   Parameters:
    source_id:
        The id of a specific organization to search. See list.
        Type: String (Required )
    page:
        The page number to return. Only 10 records are returned per page. See total_records, current_page and next_page values in the results.
        Type: Integer (Default: 1)
    json_callback:
        If using JSONP, specify the function name here.
        Type: String

*/
function fetch_recent_by_source_id($source_id, $page, $json_callback = null) {
    $url = "http://www.JailBase.com/api/1/recent/?source_id=" . $source_id . "&page=" . $page; 
    echo 'das';
    echo $url; exit;
    //($json_callback ? "&json_callback=" . $json_callback : '');
    return fetch_api_data($url);
}

function fetch_api_data($url)
{
    $response = file_get_contents($url);
    $data = json_decode($response, true);
    return $data;
}

function mysqli_connect()
{
    $mysqli = new mysqli("localhost", "root", "", "mugshot_game");
    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    }
    return $mysqli;
}

function import_sources($sources)
{
    $mysqli = mysqli_connect();
    foreach ($sources as $source) {
        $source_id = $source['source_id'];
        $name = $source['name'];
        $state = $source['state'];
        $state_full = $source['state_full'];
        $has_mugshots = $source['has_mugshots'];
        $sql = "INSERT INTO sources (source_id, name, state, state_full, has_mugshots) VALUES ('$source_id', '$name', '$state', '$state_full', '$has_mugshots')";
        $mysqli->query($sql);
    }
    $mysqli->close();
}

/*
the schema:
CREATE DATABASE mugshot_game;

'''
sources:
source_id - A unique string id for the source.
name - The name of the source.
state - The state (abbreviated) the source is located in.
state_full - The state (full name) the source is located in.
has_mugshots - A boolean value stating if mugshots are available for this source.

individuals:
name - The name of the individual.
mugshot - The image url of mugshot.
id - A unique string id for the record.
source_id - The id of the source.
source - The name of the source.
county_state - The county and state of the booking.
book_date - Book Date string in YYYY-MM-DD format.
book_date_formatted - Book Date string in MMM DD, YYYY format.
more_info_url - The url on JailBase.com to get more info.

details:
individual_id - The id of the individual. (nullable fk)
arrest_id - The id of the arrest. (nullable fk)
description - The description of the detail.

charges:
individual_id - The id of the individual. (nullable fk)
arrest_id - The id of the arrest. (nullable fk)
charge - The charge.

arrests:
name - The name of the individual.
mugshot - The image url of mugshot.
id - A unique string id for the record.
book_date - Book Date string in YYYY-MM-DD format.
book_date_formatted - Book Date string in MMM DD, YYYY format.
more_info_url - The url on JailBase.com to get more info.
'''

CREATE TABLE sources (
    source_id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    name VARCHAR(255),
    state VARCHAR(255),
    state_full VARCHAR(255),
    has_mugshots BOOLEAN
);

CREATE TABLE individuals (
    name VARCHAR(255),
    mugshot VARCHAR(255),
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    source_id INT NOT NULL,
    county_state VARCHAR(100),
    book_date VARCHAR(255),
    book_date_formatted VARCHAR(255),
    more_info_url VARCHAR(255),
    FOREIGN KEY (source_id) REFERENCES sources(source_id)
);

CREATE TABLE arrests (
    name VARCHAR(255),
    mugshot VARCHAR(255),
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT, 
    book_date VARCHAR(255),
    book_date_formatted VARCHAR(255),
    more_info_url VARCHAR(255)
);

CREATE TABLE charges (
    individual_id INT, 
    arrest_id INT, 
    charge VARCHAR(255),
    FOREIGN KEY (individual_id) REFERENCES individuals(id),
    FOREIGN KEY (arrest_id) REFERENCES arrests(id)
);

CREATE TABLE details (
    individual_id INT, 
    arrest_id INT,
    description VARCHAR(255),
    FOREIGN KEY (individual_id) REFERENCES individuals(id),
    FOREIGN KEY (arrest_id) REFERENCES arrests(id)
);


*/