<?php

namespace PHPSimpleWebScraper;

// Display Errors
error_reporting(E_ALL);
ini_set('display_errors', 1);   

// Basic Information
class Registry {

    const NAME              = 'Simple Web Scraper';
    const SLUG              = 'SimpleWebScraper';
    const SUBTITLE          = 'Fetches even JavaScript generated contents.';
    const DESCRIPTION       = 'A web page content proxy scraper, supporting JavaScript generated contents, a cross domain solution.';
    const PROGRAM_URI       = 'https://github.com/michaeluno/php-simple-web-scraper';
    const VERSION           = '1.4.0b01';
    const AUTHOR            = 'Michael Uno';
    const AUTHOR_URI        = 'http://en.michaeluno.jp';

    static public $sFilePath = __FILE__;
    static public $sDirPath;
    static public $sTempDirPath;

    static public function setUp() {
        self::$sDirPath     = dirname( self::$sFilePath );
        self::$sTempDirPath = sys_get_temp_dir() . '/' . self::SLUG;
        // Includes
        require dirname( dirname( __FILE__ ) ) . '/vendor/autoload.php';    // composer
        self::registerClasses( include( dirname( __FILE__ ) . '/include/class-map.php' ) );
    }

   static public function registerClasses( array $aClasses ) {
        self::$___aAutoLoadClasses = $aClasses + self::$___aAutoLoadClasses;
        spl_autoload_register( array( __CLASS__, 'replyToLoadClass' ) );
    }
        static private $___aAutoLoadClasses = array();
        static public function replyToLoadClass( $sCalledUnknownClassName ) {
            if ( ! isset( self::$___aAutoLoadClasses[ $sCalledUnknownClassName ] ) ) {
                return;
            }
            include( self::$___aAutoLoadClasses[ $sCalledUnknownClassName ] );
        }

}
Registry::setUp();

// HTTP Headers
header( 'Access-Control-Allow-Origin: *' );

// Front-end Form
if ( ! isset( $_GET[ 'url' ] ) ) {
    include( dirname( __FILE__ ) . '/include/template/form.php' );
    exit;
}
// Fetched Results
$_oScraper = new \PHPSimpleWebScraper\Scraper\ScraperHandler;
$_oScraper->do();
exit;