<?php

require_once 'restService.php';

class test extends restService {
    protected function _setServiceName() {
        
    }

    function __construct($params){
        parent::__construct($params);
    }
    
    function getTest(){
        echo 'test';
    }
}