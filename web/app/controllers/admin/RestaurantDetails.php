<?php
require_once dirname(__DIR__) . '/controllerObject.php';

class RestaurantDetails extends controllerObject
{
    public function __construct($params){
        parent::__construct($params);
        
        $this->_view->dump();
    }
    
    public function display() {
        echo '1';
    }
    
    protected function _setComponentName() {
        ;
    }
}

