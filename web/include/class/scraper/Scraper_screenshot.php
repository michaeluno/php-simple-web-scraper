<?php

/**
 * Displays a response as a screenshot.
 */
class Scraper_screenshot extends Scraper_Base {

    private $___sTodayDirPath;

    protected function _construct() {

        $this->___setImageCacheDirectory();

        // Sanitize parameters
        $this->_aClientArguments[ 'load-images' ] = isset( $_REQUEST[ 'load-images' ] ) && ! Utility::getBoolean( $_REQUEST[ 'load-images' ] )
            ? false
            : true;

    }

    public function do() {

        $_oScreenCapture = new ScreenCapture(
            $this->_aBaseArguments[ 'binary_path' ],
            $this->_aBaseArguments[ 'user_agent' ],
            $this->_aBaseArguments[ 'headers' ],
            $this->_aClientArguments
        );

        /// Request Arguments
        $_aRequestArguments = $this->_aRequestArguments;
        $_sFileType      = in_array( $_aRequestArguments[ 'file-type' ], array( 'jpg', 'pdf', 'png', 'jpeg', 'bmp', 'ppm' ) )
            ? $_aRequestArguments[ 'file-type' ]
            : 'jpg';
        $_sFileBaseName  = md5( $this->_aBaseArguments[ 'url' ] ) . ".{$_sFileType}";
        $_sFilePath      = $this->___sTodayDirPath . '/' . $_sFileBaseName;  // $_sFilePath = Registry::$sDirPath . '/_capture/file.jpg';
        $_aRequestArguments[ 'file_path' ] = $_sFilePath;
        $_aRequestArguments[ 'file_type' ] = $_sFileType;
        $_oScreenCapture->setRequestArguments( $_aRequestArguments );

        /// Get a response
        $_oScreenCapture->get( $this->_aBaseArguments[ 'url' ] );

        /// Render the image
        $this->___render( $_sFilePath );

    }
        private function ___setImageCacheDirectory() {

            $_sToday         = date("Ymd" );
            $_sTodayDirPath  = Registry::$sTempDirPath . '/capture/' . $_sToday;
            if ( ! file_exists( $_sTodayDirPath ) ) {
                mkdir( $_sTodayDirPath, 0777, true );
            }
            $this->___sTodayDirPath = $_sTodayDirPath;

            // Delete old cache directories
            $_aSubDirs = $this->getSubDirPaths( Registry::$sTempDirPath . '/capture/', array( $_sToday ) );
            foreach( $_aSubDirs as $_sDirPath ) {
                $this->deleteDir( $_sDirPath );
            }

        }

        private function ___render( $sFilePath ) {
            $_aImageInfo = getimagesize( $sFilePath );
            header("Content-type: {$_aImageInfo[ 'mime' ]}" );
            readfile( $sFilePath );
        }

}