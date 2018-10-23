<?php
// Display Errors
error_reporting(E_ALL);
ini_set('display_errors', 1);   

// Basic Information
class Registry {

    const NAME              = 'Simple Web Scraper';
    const SLUG              = 'SimpleWebScraper';
    const DESCRIPTION       = 'PHP & PhantomJS driven web content scraper';
    const PROGRAM_URI       = 'https://github.com/michaeluno/php-simple-web-scraper';
    const VERSION           = '1.2.1';
    const AUTHOR            = 'Michael Uno';
    const AUTHOR_URI        = 'http://en.michaeluno.jp';

    static public $sFilePath = __FILE__;
    static public $sDirPath;
    static public $sTempDirPath;

    static public function setUp() {
        self::$sDirPath     = dirname( self::$sFilePath );
        self::$sTempDirPath = sys_get_temp_dir() . '/' . self::SLUG;
    }
}
Registry::setUp();

// Includes
require dirname( dirname( __FILE__ ) ) . '/vendor/autoload.php';    // composer
require dirname( __FILE__ ) . '/include/class/utility/AdminPageFramework_RegisterClasses.php';  // auto loader
new AdminPageFramework_RegisterClasses(
    array(),
    array(),
    include( dirname( __FILE__ ) . '/include/class-list.php' )
);

// HTTP Headers
header( 'Access-Control-Allow-Origin: *' );

// Front-end Form
if ( ! isset( $_GET[ 'url' ] ) ) {
    include( dirname( __FILE__ ) . '/include/template/form.php' );
    exit;
}

// Scraping
$_sFileName = 'WIN' === Utility::getOS() ? 'phantomjs.exe' : 'phantomjs';
$_sBinPath  = dirname( Registry::$sDirPath ) . '/vendor/bin/' . $_sFileName;
if ( ! file_exists( $_sBinPath ) ) {
    echo( 'PhantomJS bin not found: ' . $_sBinPath );
    Utility::listFolderFiles( dirname( Registry::$sDirPath ) );
    exit;
}

// Caches
$_sDiskCacheDirPath = Registry::$sTempDirPath . '/browser';
if ( ! file_exists( $_sDiskCacheDirPath ) ) {
    mkdir( $_sDiskCacheDirPath, 0777, true );
}

/// Parameters
$_sURL        = urldecode( $_REQUEST[ 'url' ] );
$_sOutputType = isset( $_REQUEST[ 'output' ] )
    ? $_REQUEST[ 'output' ]
    : 'html';
$_sUserAgent  = isset( $_REQUEST[ 'user-agent' ] )
    ? (
        'random' === $_REQUEST[ 'user-agent' ]
            ? Utility::getOneFromList( Registry::$sDirPath . '/include/user-agents.txt' )
            : $_REQUEST[ 'user-agent' ]
    )
    : $_SERVER[ 'HTTP_USER_AGENT' ];
$_aHeaders    = isset( $_REQUEST[ 'headers' ] ) && is_array( $_REQUEST[ 'headers' ] )
    ? $_REQUEST[ 'headers' ]
    : array();
$_sMethod     = isset( $_REQUEST[ 'method' ] )
    ? strtoupper( $_REQUEST[ 'method' ] )
    : 'GET';
$_aClientConfigurations = array(
    // `false` by default. for the `screenshot` output type, `true` should be default and this value will be reassigned.
    'load-images'       => isset( $_REQUEST[ 'load-images' ] ) && Utility::getBoolean( $_REQUEST[ 'load-images' ] ),
    'output-encoding'   => isset( $_REQUEST[ 'output-encoding' ] ) ? $_REQUEST[ 'output-encoding' ] : 'utf8',
    'disk-cache'        => true,
    'disk-cache-path'   => str_replace('\\', '/', $_sDiskCacheDirPath ),
);
$_aRequestArguments = array(
    'method'      => $_sMethod,
    'file-type'   => isset( $_REQUEST[ 'file-type' ] ) ? $_REQUEST[ 'file-type' ] : 'jpg',

);
if ( 'POST' === $_sMethod ) {
    $_aRequestArguments[ 'data' ] = isset( $_REQUEST[ 'data' ] ) ? $_REQUEST[ 'data' ] : array();
}


/// Requests by type
switch( $_sOutputType ) {
    case 'screenshot':
        // Caches
        $_sToday         = date("Ymd" );
        $_sTodayDirPath  = Registry::$sTempDirPath . '/capture/' . $_sToday;
        if ( ! file_exists( $_sTodayDirPath ) ) {
            mkdir( $_sTodayDirPath, 0777, true );
        }
        // Delete old cache directories
        $_aSubDirs = Utility::getSubDirPaths( Registry::$sTempDirPath . '/capture/', array( $_sToday ) );
        foreach( $_aSubDirs as $_sDirPath ) {
            Utility::deleteDir( $_sDirPath );
        }

        // Parameters
        $_aClientConfigurations[ 'load-images' ] = isset( $_REQUEST[ 'load-images' ] ) && ! Utility::getBoolean( $_REQUEST[ 'load-images' ] )
            ? false
            : true;

        // Request
        $_sFileType      = in_array( $_aRequestArguments[ 'file-type' ], array( 'jpg', 'pdf', 'png', 'jpeg', 'bmp', 'ppm' ) )
            ? $_aRequestArguments[ 'file-type' ]
            : 'jpg';
        $_sFileBaseName  = md5( $_sURL ) . ".{$_sFileType}";
        $_oScreenCapture = new ScreenCapture( $_sBinPath, $_sUserAgent, $_aHeaders, $_aClientConfigurations );
        $_sFilePath      = $_sTodayDirPath . '/' . $_sFileBaseName;  // $_sFilePath = Registry::$sDirPath . '/_capture/file.jpg';
        $_aRequestArguments[ 'file_path' ] = $_sFilePath;
        $_aRequestArguments[ 'file_type' ] = $_sFileType;
        $_oScreenCapture->setRequestArguments( $_aRequestArguments );
        $_oScreenCapture->get( $_sURL );
        $_aImageInfo = getimagesize( $_sFilePath );
        header("Content-type: {$_aImageInfo[ 'mime' ]}" );
        readfile( $_sFilePath );
        break;
    case 'json':
        // Requests
        $_oBrowser  = new Browser( $_sBinPath, $_sUserAgent, $_aHeaders, $_aClientConfigurations );
        $_oBrowser->setRequestArguments( $_aRequestArguments );
        $_oResponse = $_oBrowser->get( $_sURL );
        echo json_encode( $_oResponse );
        break;
    case 'html':
    default:
        $_oBrowser  = new Browser( $_sBinPath, $_sUserAgent, $_aHeaders, $_aClientConfigurations );
        $_oBrowser->setRequestArguments( $_aRequestArguments );
        $_oResponse = $_oBrowser->get( $_sURL );
        $_sContent  = $_oResponse->getContent();
        // $_oResponse->getStatus()
        if ( $_sContent ) {
            echo $_oResponse->getContent(); // Dump the requested page content
            break;
        } else {
            echo "<h2>Failed to Get Response</h2>";
            echo "<pre>";
            echo htmlspecialchars( print_r( $_oResponse, true ) );
            echo "</pre>";
        }
        break;
}
exit;
