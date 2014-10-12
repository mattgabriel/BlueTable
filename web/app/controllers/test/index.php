<?php

require_once APP_PATH.'controllers/controllerObject.php';
require_once APP_PATH.'library/paypal.php';
require_once APP_PATH.'library/justgiving.php';
require_once APP_PATH.'library/paypal.php';

class index extends controllerObject{
    protected function _setComponentName() {
        
    }
    function __construct() {
        $jg = new paypal();
    }

}
