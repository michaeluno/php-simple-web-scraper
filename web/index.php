<?php
// Display Errors
error_reporting(E_ALL);
ini_set('display_errors', 1);   


class Registry {

    const NAME              = 'Simple Web Scraper';
    const SLUG              = 'SimpleWebScraper';
    const DESCRIPTION       = 'PHP & PhantomJS driven web content scraper';
    const VERSION           = '1.0.0';
    const AUTHOR            = 'Michael Uno';
    const AUTHOR_URI        = 'http://en.michaeluno.jp';

    static public $sFilePath = __FILE__;
    static public $sDirPath;

    static public function setUp() {
        self::$sDirPath = dirname( self::$sFilePath );
    }
}
Registry::setUp();

require dirname( dirname( __FILE__ ) ) . '/vendor/autoload.php';    // composer
require dirname( __FILE__ ) . '/include/class/AdminPageFramework_RegisterClasses.php';  // auto loader
new AdminPageFramework_RegisterClasses(
    array(),
    array(),
    include( dirname( __FILE__ ) . '/include/class-list.php' )
);

if ( ! isset( $_GET[ 'url' ] ) ) {
    include( dirname( __FILE__ ) . '/include/template/form.php' );
    exit;
}




$_sFileName = 'WIN' === Utility::getOS() ? 'phantomjs.exe' : 'phantomjs';
$_sBinPath  = dirname( Registry::$sDirPath ) . '/vendor/bin/' . $_sFileName;
if ( ! file_exists( $_sBinPath ) ) {
    echo( 'PhantomJS bin not found: ' . $_sBinPath );
    Utility::listFolderFiles( dirname( Registry::$sDirPath ) );
    exit;
}

$_sURL = urldecode( $_GET[ 'url' ] );

$_sOutputType = isset( $_GET[ 'output' ] )
    ? $_GET[ 'output' ]
    : 'html';
switch( $_sOutputType ) {
    case 'screenshot':
        $_sTempDirPath   = sys_get_temp_dir() . '/' . Registry::SLUG;
        $_sToday         = date("Ymd" );
        $_sTodayDirPath  = $_sTempDirPath . '/capture/' . $_sToday;
        if ( ! file_exists( $_sTodayDirPath ) ) {
            mkdir( $_sTodayDirPath, 0777, true );
        }
        // Delete old cache directories
        $_aSubDirs = Utility::getSubDirPaths( $_sTempDirPath . '/capture/', array( $_sToday ) );
        foreach( $_aSubDirs as $_sDirPath ) {
            Utility::deleteDir( $_sDirPath );
        }

        $_sFileBaseName  = md5( $_sURL ) . '.jpg';
        $_oScreenCapture = new ScreenCapture( $_sBinPath );
        $_sFilePath      = $_sTodayDirPath . '/' . $_sFileBaseName;  // $_sFilePath = Registry::$sDirPath . '/_capture/file.jpg';
        $_oScreenCapture->get( $_sURL, $_sFilePath );
        $_aImageInfo = getimagesize( $_sFilePath );
        header("Content-type: {$_aImageInfo[ 'mime' ]}" );
        readfile( $_sFilePath );
        break;
    case 'json':
        $_oBrowser  = new Browser( $_sBinPath );
        $_oResponse = $_oBrowser->get( $_sURL );
        echo json_encode( $_oResponse );
        break;
    case 'html':
    default:
        $_oBrowser  = new Browser( $_sBinPath );
        $_oResponse = $_oBrowser->get( $_sURL );
        if( 200 === $_oResponse->getStatus() ) {
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
