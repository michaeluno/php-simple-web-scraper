<?php
namespace PHPSimpleWebScraper\Scraper;
use PHPSimpleWebScraper\Utility\Utility;
use PHPSimpleWebScraper\Registry;

class ScraperHandler extends Utility {

    protected $_sBinaryPath = '';
    protected $_sDiskCacheDirPath = '';

    /**
     * @var array $_REQUEST
     */
    public $aRequest = array();
    
    public function __construct( array $aRequest ) {

        $this->aRequest = $aRequest;
        
        $this->___checkPhantomJSBinary();
        $this->___setCacheDirectory();

    }
        private function ___checkPhantomJSBinary() {
            $_sFileName = 'WIN' === Utility::getOS() ? 'phantomjs.exe' : 'phantomjs';
            $_sBinPath  = Registry::$sVendorDirPath . '/bin/' . $_sFileName;
            if ( ! file_exists( $_sBinPath ) ) {
                echo( 'PhantomJS bin not found: ' . $_sBinPath );
                Utility::listFolderFiles( dirname( Registry::$sDirPath ) );
                exit;
            }
            $this->_sBinaryPath = $_sBinPath;
        }
        private function ___setCacheDirectory() {
            $_sDiskCacheDirPath = Registry::$sTempDirPath . '/browser';
            if ( ! file_exists( $_sDiskCacheDirPath ) ) {
                mkdir( $_sDiskCacheDirPath, 0777, true );
            }
            $this->_sDiskCacheDirPath = str_replace('\\', '/', $_sDiskCacheDirPath );
        }

    public function do() {

        $_aBaseArguments    = $this->___getBaseArguments( $this->aRequest );
        $_sClassName        = "PHPSimpleWebScraper\Scraper\Scraper_" . $this->___getOutputType( $this->aRequest );
        $_oScraper          = new $_sClassName(
            $_aBaseArguments,
            $this->___getClientArguments( $this->aRequest ),
            $this->___getRequestArguments( $this->aRequest, $_aBaseArguments[ 'method' ] )
        );
        $_oScraper->do();

    }
        private function ___getOutputType( array $aRequest ) {
            if ( ! isset( $aRequest[ 'output' ] ) ) {
                return 'html';
            }
            $_sOutputType = strtolower( $aRequest[ 'output' ] );
            return in_array( $_sOutputType, array( 'screenshot', 'html', 'json' ) )
                ? $_sOutputType
                : 'html';
        }

        /**
         * @param array $aRequest
         * @param $sMethod
         *
         * @return array
         */
        private function ___getRequestArguments( array $aRequest, $sMethod ) {
            $_aRequestArguments = array(
                'method'      => $sMethod,
                'file-type'   => isset( $aRequest[ 'file-type' ] ) ? $aRequest[ 'file-type' ] : 'jpg',
            );
            if ( 'POST' === $sMethod ) {
                $_aRequestArguments[ 'data' ] = isset( $aRequest[ 'data' ] ) ? $aRequest[ 'data' ] : array();
            }
            return $_aRequestArguments + $aRequest;
        }
        /**
         * @param array $aRequest
         * @return array
         */
        private function ___getClientArguments( array $aRequest ) {
            return array(
                // `false` by default. for the `screenshot` output type, `true` should be default and this value will be reassigned.
                'load-images'       => isset( $aRequest[ 'load-images' ] ) && $this->getBoolean( $aRequest[ 'load-images' ] ),
                'output-encoding'   => isset( $aRequest[ 'output-encoding' ] ) ? $aRequest[ 'output-encoding' ] : 'utf8',
                'disk-cache'        => true,
                'disk-cache-path'   => $this->_sDiskCacheDirPath,
            );
        }
        /**
         * @param array $aRequest
         * @return array
         */
        private function ___getBaseArguments( array $aRequest ) {
            return array(
                'url'            => urldecode( trim( $aRequest[ 'url' ] ) ),
                'user_agent'     => $this->___getUserAgent( $aRequest ),
                'headers'        => isset( $aRequest[ 'headers' ] ) && is_array( $aRequest[ 'headers' ] )
                    ? $aRequest[ 'headers' ]
                    : array(),
                'method'         => isset( $aRequest[ 'method' ] )
                    ? strtoupper( $aRequest[ 'method' ] )
                    : 'GET',
                'binary_path'    => $this->_sBinaryPath,
                'cache_lifespan' => isset( $aRequest[ 'cache-lifespan' ] ) ? $aRequest[ 'cache-lifespan' ] : 1200,
            );
        }
            private function ___getUserAgent( array $aRequest ) {
                if ( ! isset( $aRequest[ 'user-agent' ] ) ) {
                    return isset( $_SERVER[ 'HTTP_USER_AGENT' ] )
                        ? $_SERVER[ 'HTTP_USER_AGENT' ]
                        : '';
                }
                return 'random' === $aRequest[ 'user-agent' ]
                    ? $this->getOneFromList( Registry::$sDirPath . '/include/user-agents.txt' )
                    : $aRequest[ 'user-agent' ];
            }


}