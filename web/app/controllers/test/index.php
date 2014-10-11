<?php

require_once APP_PATH.'controllers/controllerObject.php';

class index extends controllerObject{
    protected function _setComponentName() {
        
    }
    function __construct() {
        echo 'test';
        var_dump($_POST);
    }

}
