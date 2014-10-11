<?php

require_once 'restService.php';

class menu extends restService {
    protected function _setServiceName() {
    }
    
    public function getMenu($params){
        $args = $params[ParamTypes::QUERY_STR];
        if(!empty($args))
        {
            
        }
    }
}
