<?php

    use Fracture\Transcription\JsonReader;
    use Fracture\Autoload\NodeMap;
    use Fracture\Autoload\ClassLoader;


    define('SOURCE_PATH', dirname( __DIR__ ) . '/src' );
    define('TEST_PATH', dirname( __DIR__ ) . '/tests' );
    define('FIXTURE_PATH', TEST_PATH . '/fixtures' );



    require '../vendor/autoload.php';
