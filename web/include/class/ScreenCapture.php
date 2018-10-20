<?php

class ScreenCapture extends PhantomJSWrapper {

    public function get( $sURL, $sOutputFilePath, $sMethod='GET' ) {

        $sOutputFilePath = str_replace('\\', '/', $sOutputFilePath );

        $width  = 800;
        $height = 600;
//        $top    = 0;
//        $left   = 0;
        
        /** 
         * @see JonnyW\PhantomJs\Http\CaptureRequest
         **/
        $request = $this->oClient->getMessageFactory()->createCaptureRequest($sURL, $sMethod );
        // $request->setBodyStyles( ['backgroundColor' => '#ffffff'] );
        $request->setOutputFile( $sOutputFilePath );
        $request->setViewportSize( $width, $height );
//        $request->setCaptureDimensions( $width, $height, $top, $left );
    
        // @see https://github.com/jonnnnyw/php-phantomjs/issues/208
        if ( $this->_sUserAgent ) {
            $request->addSetting( 'userAgent', $this->_sUserAgent );
        }

        // @see http://jonnnnyw.github.io/php-phantomjs/4.0/3-usage/#custom-headers
        $request->addHeaders( $this->_aHeaders );

        $response = $this->oClient->getMessageFactory()->createResponse();
    
        // Send the request
        $this->oClient->send( $request, $response );
        return $response;

    }

}