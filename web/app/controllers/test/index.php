<?php

require_once APP_PATH.'controllers/controllerObject.php';
require_once APP_PATH.'library/paypal.php';
require_once APP_PATH.'library/justgiving.php';

class index extends controllerObject{
    protected function _setComponentName() {
        
    }
    function __construct() {
        $jg = new justgiving();
    }

}
