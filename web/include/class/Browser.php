<?php

class Browser extends PhantomJSWrapper {

    public function get( $sURL, $sMethod='GET' ) {

        $request  = $this->oClient->getMessageFactory()->createRequest( $sURL, $sMethod );
        $request->setTimeout( 10000 );  // n seconds * 1000 
        $request->setDelay( 5 );       // 5 second delay to wait for complete page load

        // @see https://github.com/jonnnnyw/php-phantomjs/issues/208
        if ( $this->_sUserAgent ) {
            $request->addSetting( 'userAgent', $this->_sUserAgent );
        }

        // @see http://jonnnnyw.github.io/php-phantomjs/4.0/3-usage/#custom-headers
        $request->addHeaders( $this->_aHeaders );

        $response = $this->oClient->getMessageFactory()->createResponse();

        $this->oClient->send( $request, $response );
        return $response;

    }

}