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
}
