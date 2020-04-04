<?php

// Display Errors
error_reporting(E_ALL);
ini_set('display_errors', 1);   

require dirname( __FILE__ ) . '/include/class/Registry.php';
\PHPSimpleWebScraper\Registry::setUp(
    __FILE__,
    dirname( __DIR__ ) . '/vendor'
);

// HTTP Headers
header( 'Access-Control-Allow-Origin: *' );

// Front-end Form
if ( ! isset( $_REQUEST[ 'url' ] ) ) {
    include( dirname( __FILE__ ) . '/include/template/form.php' );
    exit;
}
// Fetched Results
$_oScraper = new \PHPSimpleWebScraper\Scraper\ScraperHandler( $_REQUEST );
$_oScraper->do();
exit;