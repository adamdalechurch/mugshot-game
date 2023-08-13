
<?php
ini_set('memory_limit', '9999M'); 

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
    //($json_callback ? "&json_callback=" . $json_callback : '');
    return fetch_api_data($url);
}

function fetch_api_data($url)
{
    $response = file_get_contents($url);
    $data = json_decode($response, true);
    return $data;
}