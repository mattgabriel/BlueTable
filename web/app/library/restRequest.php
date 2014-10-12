<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of restRequest
 *
 * @author fdevbt
 */
class restRequest {
    public function __construct() {
    }
    
    public function generateRequest($url, $httpmethod, $headers, $data='')
    {
        // Set the HTTP request authentication headers
        $context_options = array(
            'http' => array(
                'method' => $httpmethod,
                'header' => $headers,
                'content' => $data
          )
        );

        // Creates a stream context
        $context = stream_context_create($context_options);

        // Open the URL with the HTTP headers (fopen wrappers must be enabled)
        //use https url
        $response = file_get_contents($url, false, $context);
        /*ssl? <?php
$url = 'https://secure.example.com/test/1';
$contextOptions = array(
    'ssl' => array(
        'verify_peer'   => true,
        'cafile'        => __DIR__ . '/cacert.pem',
        'verify_depth'  => 5,
        'CN_match'      => 'secure.example.com'
    )
);
$sslContext = stream_context_create($contextOptions);
$result = file_get_contents($url, NULL, $sslContext);
?>

More information about those context options can be found at http://php.net/manual/en/context.ssl.php*/
        return json_decode($response);
    }
}
