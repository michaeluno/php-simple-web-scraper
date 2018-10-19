<?php

class Browser extends PhantomJSWrapper {

    public function get( $sURL ) {

        $request  = $this->oClient->getMessageFactory()->createRequest( $sURL, 'GET');    
        $request->setTimeout( 10000 );  // n seconds * 1000 
        $request->setDelay( 5 );       // 5 second delay to wait for complete page load
        
        // @see https://github.com/jonnnnyw/php-phantomjs/issues/208
        $_sUserAgent = "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/28.0.1500.71 Safari/537.36";
        $request->addSetting( 'userAgent', $_sUserAgent );
        
        $response = $this->oClient->getMessageFactory()->createResponse();

        $this->oClient->send( $request, $response );
        return $response;

    }

}