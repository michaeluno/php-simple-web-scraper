<?php
use JonnyW\PhantomJs\Client;

abstract class PhantomJSWrapper {

    /**
     * @var Client
     */
    public $oClient;

    public function __construct( $sPhantomJSBinaryPath='', $sPhantomJSConfigPath='' ) {

        // @see http://jonnnnyw.github.io/php-phantomjs/4.0/3-usage/#custom-headers
        $this->oClient = Client::getInstance();
        $this->oClient->isLazy(); // Tells the client to wait for all resources before rendering
        // $this->oClient->getEngine()->addOption('--load-images=true');
        // $this->oClient->getEngine()->addOption('--ignore-ssl-errors=true');

        if ( file_exists( $sPhantomJSBinaryPath ) ) {
            $this->oClient->getEngine()->setPath( str_replace('\\', '/', $sPhantomJSBinaryPath ) );
        }

        // Set config
        // @see http://phantomjs.org/api/command-line.html
        $_sConfigPath          = str_replace('\\', '/', $sPhantomJSConfigPath );
        if ( file_exists( $_sConfigPath ) ) {
            $this->oClient->getEngine()->addOption('--config="' . $_sConfigPath . '"' );
        }

    }

}
