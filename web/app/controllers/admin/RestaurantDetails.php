<?php
require_once dirname(__DIR__) . '/controllerObject.php';

class RestaurantDetails extends controllerObject
{
    public function __construct($params = array()){
        parent::__construct($params);
        //$this->_view->dump();
    }
    
    public function display() {
    }
    
    protected function _setComponentName() {
        ;
    }
}

