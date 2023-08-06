<?php
include_once 'source.php';
include_once 'arrest.php';
include_once 'individual.php';
include_once 'charge.php';
include_once 'detail.php';
include_once 'api.php';


function import_sources(){
    $source_repo = new Source;
    $num_imported = 0;

    $sources = fetch_sources();
    foreach ($sources['records'] as $source) {
        if ($source_repo->insert($source) && $source['has_mugshots']){
            $num_imported++;
            import_source_recent_arrests($source);
        }
    }

    if($num_imported > 0){
        echo "<script>location.reload()</script>";
    } else {
        echo "No sources imported.";
    }
}
 
// // // import source arrests:
function import_source_recent_arrests($source){
    $arrest_repo = new Arrest();
    $page = 1;
    $arrests = true;
    while($arrests){
        try{
            $arrests = fetch_recent_by_source_id($source['source_id'], $page);
            foreach ($arrests['records'] as $arrest) {
                $arrest_id = $arrest_repo->insert($arrest);
                print_r($arrest['charges'] );
                foreach ($arrest['charges'] as $charge) {
                    $charge_repo = new Charge();
                    $charge_repo->insert([
                        'arrest_id' => $arrest_id,
                        'individual_id' => null,
                        'charge' => $charge
                    ]);
                }
            }
        } catch (Exception $e){
            $arrests = false;
        } finally {
            $page++;
        }
    }
}

import_sources();

?>