<?php
include_once 'source.php';
include_once 'arrest.php';
include_once 'individual.php';
include_once 'charge.php';
include_once 'detail.php';
include_once 'api.php';

ini_set('memory_limit', '4G'); 

// import sources
$source_repo = new Source();

$sources = fetch_sources();

foreach ($sources['records'] as $source) {
    $source_repo->insert($source);
}