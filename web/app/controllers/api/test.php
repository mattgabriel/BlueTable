<?php

require_once 'restService.php';

class test extends restService {
    protected function _setServiceName() {
        
    }

    function __construct($params){
        parent::__construct($params);
    }
    
    function postTest($params){
        echo 'QueryString: ' . json_encode($params[ParamTypes::QUERY_STR]) . '</br>'; 
        echo 'URI Params: ' . json_encode($params[ParamTypes::URI_PARAMS]) . '</br>';
        echo 'Payload: ' . json_encode($params[ParamTypes::PAYLOAD]) . '</br>';
    }
    
}
