<?php

class ScreenCapture extends PhantomJSWrapper {

    public function get( $sURL, $sOutputFilePath ) {

        $sOutputFilePath = str_replace('\\', '/', $sOutputFilePath );

        $width  = 800;
        $height = 600;
//        $top    = 0;
//        $left   = 0;
        
        /** 
         * @see JonnyW\PhantomJs\Http\CaptureRequest
         **/
        $request = $this->oClient->getMessageFactory()->createCaptureRequest($sURL, 'GET');
        // $request->setBodyStyles( ['backgroundColor' => '#ffffff'] );
        $request->setOutputFile( $sOutputFilePath );
        $request->setViewportSize( $width, $height );
//        $request->setCaptureDimensions( $width, $height, $top, $left );
    
        /** 
         * @see JonnyW\PhantomJs\Http\Response 
         **/
        $response = $this->oClient->getMessageFactory()->createResponse();
    
        // Send the request
        $this->oClient->send( $request, $response );
        return $response;

    }

}