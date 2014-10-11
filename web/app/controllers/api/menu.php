<?php

require_once 'restService.php';
require_once APP_PATH . 'models/UserAtTableModel.php';
require_once APP_PATH . 'models/MenuModel.php';
require_once APP_PATH . 'models/MenuItemModel.php';

class menu extends restService {
    protected function _setServiceName() {
    }
    
    public function getMenu($params){
        $args = $params[ParamTypes::QUERY_STR];
        if(!empty($args))
        {
            $uatm = new UserAtTableModel();
            $menu = $uatm->getRestaurantByTableId($args['tableid']);
            if(!empty($menu))
                echo json_encode ($menu);
        }
        else
            echo 'Bad querystring!';
    }
}
