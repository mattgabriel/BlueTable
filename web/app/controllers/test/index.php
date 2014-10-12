<?php

require_once APP_PATH.'controllers/controllerObject.php';
require_once APP_PATH.'library/paypal.php';
require_once APP_PATH.'library/justgiving.php';
require_once APP_PATH.'library/paypal.php';
require_once APP_PATH.'library/sendgrid.php';

class index extends controllerObject{
    protected function _setComponentName() {
        
    }
    function __construct() {
        $sg = new sendgrid();
        $sg->send('test');
    }

}
