<?php
/**
 * Displays a response as HTML.
 */
class Scraper_html extends Scraper_Base {

    public function do() {

        $_oBrowser  = new Browser(
            $this->_aBaseArguments[ 'binary_path' ],
            $this->_aBaseArguments[ 'user_agent' ],
            $this->_aBaseArguments[ 'headers' ],
            $this->_aClientArguments
        );
        $_oBrowser->setRequestArguments( $this->_aRequestArguments );

        $_oResponse = $_oBrowser->get( $this->_aBaseArguments[ 'url' ] );
        $_sContent  = $_oResponse->getContent();
        // $_oResponse->getStatus()
        if ( $_sContent ) {
            echo $_oResponse->getContent(); // Dump the requested page content
            return;
        } else {
            echo "<h2>Failed to Get Response</h2>";
            echo "<pre>";
            echo htmlspecialchars( print_r( $_oResponse, true ) );
            echo "</pre>";
        }

    }

}