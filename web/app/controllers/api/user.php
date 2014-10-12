<?php

require_once 'restService.php';
require_once APP_PATH . 'models/UserModel.php';
require_once APP_PATH . 'library/justgiving.php';

class user extends restService {
    protected function _setServiceName() {
        
    }
    
    public function getCharity()
    {
        $jg = new justgiving();
        $charity = $jg->listCharities();
        echo json_encode($charity);
    }
    
    public function postRemoveusersfromtable($params) {
        $args = (array)$params[ParamTypes::PAYLOAD];
        $userId = $args['UserId'];
        if ($userId) {
            $userModel = new UserModel();
            $userModel->deleteUsersAtTable($userId);
        } else {
            echo 'Bad payload';
        }
    }
}
