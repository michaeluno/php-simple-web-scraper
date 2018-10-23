<?php
/**
 * Created by PhpStorm.
 * User: Internet
 * Date: 10/23/2018
 * Time: 11:55 PM
 */

class Scraper_Base extends Utility {

    protected $_aBaseArguments = array(
        'url'               => '',
        'user_agent'        => '',
        'method'            => '',
        'headers'           => array(),
        'binary_path'       => '',  // PhantomJS binary path
    );

    protected $_aClientArguments = array(
        'load-images'       => false,
        'output-encoding'   => 'utf8',
        'disk-cache'        => true,
        'disk-cache-path'   => '',
    );

    protected $_aRequestArguments = array(
        'method'     => '',
        'file-type'  => 'jpg',
        'data'       => array(),
    );

    public function __construct( array $aBaseArguments, array $aClientArguments, array $aRequestArguments ) {
        $this->_aBaseArguments    = $aBaseArguments + $this->_aBaseArguments;
        $this->_aClientArguments  = $aClientArguments + $this->_aClientArguments;
        $this->_aRequestArguments = $aRequestArguments + $this->_aRequestArguments;
        $this->_construct();
    }

    protected function _construct() {}

    public function do() {}

}