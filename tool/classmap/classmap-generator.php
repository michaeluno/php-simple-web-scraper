<?php

$_sProjectRootDirPath = dirname( dirname( __DIR__ ) );
include( $_sProjectRootDirPath . '/vendor/autoload.php' );

new \PHPClassMapGenerator\PHPClassMapGenerator(
    $_sProjectRootDirPath . '/web',          // base dir path
    [ $_sProjectRootDirPath . '/web/include', ],        // scan dir paths
    $_sProjectRootDirPath . '/web/include/class-map.php',        // output file path
    array(
        'output_buffer'      => true,
        'exclude_classes'    => [ 'TestClass' ],
        'output_var_name'   => 'return',
        'base_dir_var'      => '\PHPSimpleWebScraper\Registry::$sDirPath',
        'search'            => array(
            'allowed_extensions'    => array( 'php' ),
            'exclude_dir_paths'     => array( __DIR__ . '/includes/class/admin' ),
            'exclude_dir_names'     => array( '_del', '_bak' ),
            'exclude_file_names'    => [ 'test.php', 'uninsall.php' ],
            'is_recursive'          => true,
        ),
    )
);