
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

/* function: search_source
   What it does: fetches recent arrests from the Jailbase API by last name
   Parameters:
    source_id:
        The id of a specific organization to search. See list.
        Type: String (Required )
    last_name:
        The last name to search for. Partial names accepted.
        Type: String (Required) 
    first_name:
        The first name to search for. Partial names accepted.
        Type: String (NOT Required)
    source_id:
        The id of a specific organization to search. See list.
        Type: String (NOT Required)
    page:
        The page number to return. Only 10 records are returned per page. See total_records, current_page and next_page values in the results.
        Type: Integer (Default: 1)
    json_callback:
        If using JSONP, specify the function name here.
        Type: String
*/
function search_source($source_id, $last_name, $extra = []) {
    $url = "http://www.JailBase.com/api/1/search/?source_id=" . $source_id . "&last_name=" . $last_name;
    if (count($extra) > 0) {
        $url .= "&" . http_build_query($extra);
    }
    
    return fetch_api_data($url);
}

function fetch_api_data($url)
{
    $response = file_get_contents($url);
    $data = json_decode($response, true);
    return $data;
}

function try_get_random_object($object_type, $list_function, $meh)
{
    $object = null;
    $objects = null;

    while( $object == null || $objects == null) 
    {
        try{
            $objects = $list_function( ...$args );
            if ($list_function != 'fetch_sources'){
                echo var_dump([$object_type, $list_function, $meh]);
            }
            exit();
            $object = $objects['records'][rand(0, count($objects['records']) - 1)];
        } catch( \Exception $e){
            // whoops something happened, but who cares??
        } finally {
            sleep(1);
        }
    }

    return $object;
}

function get_api_random_mugshot()
{
    $source = null;
    $arrests = null;
    $arrest = null;
    $used_sources = [];

    while( $source == null || $arrest == null) 
    {
        $source = null;
        $arrest = null;
        
        while( $source == null )
        {
            $source = try_get_random_object( 'source', 'fetch_sources');
            // if(in_array($source['source_id'], $used_sources)) continue;
        }
        
       
        $arrest = try_get_random_object( 'arrest', 'fetch_recent_by_source_id', $source['source_id']);
        $used_sources[] = $source['source_id'];
    }

    return [ 'source' => $source, 'arrest' => $arrest ];
}

// Function to insert data into MySQL database
function insert_data_to_mysql($data) {
    // Replace these values with your actual database credentials
    $host = "localhost";
    $username = "root";
    $password = "";
    $dbname = "mugshot_game";

    // Connect to the MySQL database
    $conn = new mysqli($host, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Iterate through the records and insert them into the arrests and charges tables
    foreach ($data['records'] as $record) {
        $arrest = $record;
        $charges = $arrest['charges'];

        // Prepare the arrest data for insertion into the arrests table
        $arrestValues = array_values($arrest);
        $arrestColumns = implode(", ", array_keys($arrest));
        $arrestPlaceholders = rtrim(str_repeat("?, ", count($arrestValues)), ", ");
        $insertArrestQuery = "INSERT INTO arrests ($arrestColumns) VALUES ($arrestPlaceholders)";
        $stmt = $conn->prepare($insertArrestQuery);
        $stmt->bind_param(str_repeat("s", count($arrestValues)), ...$arrestValues);
        $stmt->execute();

        // Get the auto-generated arrest ID from the last insert
        $arrestId = $conn->insert_id;

        // Insert charges data into the charges table for the current arrest
        foreach ($charges as $charge) {
            $chargeUrl = $charge['url'];
            $insertChargeQuery = "INSERT INTO charges (arrest_id, url) VALUES (?, ?)";
            $stmt = $conn->prepare($insertChargeQuery);
            $stmt->bind_param("is", $arrestId, $chargeUrl);
            $stmt->execute();
        }
    }

    // Close the connection
    $conn->close();
}

// Function to get a random mugshot and associated crimes from the database
function get_random_mugshot() {
    // Replace these values with your actual database credentials
    $host = "localhost";
    $username = "root";
    $password = "";
    $dbname = "mugshot_game";

    // Connect to the MySQL database
    $conn = new mysqli($host, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch a random arrest record and its associated charges
    $getRandomArrestQuery = "SELECT * FROM arrests ORDER BY RAND() LIMIT 1";
    $result = $conn->query($getRandomArrestQuery);
    $arrest = $result->fetch_assoc();

    // Fetch charges for the selected arrest
    $getChargesQuery = "SELECT * FROM charges WHERE arrest_id = " . $arrest['id'];
    $result = $conn->query($getChargesQuery);
    $charges = $result->fetch_all(MYSQLI_ASSOC);

    // Close the connection
    $conn->close();

    return ['arrest' => $arrest, 'charges' => $charges];
}

// Example usage
// Step 3: Fetch Mugshot and Crime Data from Jailbase API and Insert into MySQL database
// $jailbaseData = fetch_jailbase_data();
// insert_data_to_mysql($jailbaseData);

// Step 4: Display a Random Mugshot and Ask the User to Guess the Crime
// $randomData = get_random_mugshot();
// $arrest = $randomData['arrest'];
// $charges = $randomData['charges'];
$data = get_api_random_mugshot();

print_r($data);
exit();

$arrest = $data['arrest'];
$charges = $data['charges'];


// Display the mugshot
echo '<img src="' . $arrest['mugshot'] . '" alt="Mugshot">';

// Display crime options for the user to guess
echo '<h2>Guess the Crime:</h2>';
echo '<ul>';
foreach ($charges as $charge) {
    echo '<li>' . $charge['charge'] . '</li>';
}
echo '</ul>';

// Game logic - check user's input against the correct crime
$userGuess = $_POST['user_guess']; // Assuming the user's input is submitted via a form
$correctCrime = $charges[0]['charge']; // Assuming the first charge in the array is the correct crime

if ($userGuess === $correctCrime) {
    echo '<p>Congratulations! Your guess is correct!</p>';
} else {
    echo '<p>Sorry, your guess is incorrect. The correct crime is: ' . $correctCrime . '</p>';
}
?>

